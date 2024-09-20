<?php
session_start();
include '../config_db.php'; // Include your database configuration

if (isset($_SESSION['photographer_login'])) {
    $email = $_SESSION['photographer_login'];
    $timeFrame = isset($_GET['timeFrame']) ? intval($_GET['timeFrame']) : 3;

    // Use a prepared statement for security
    $stmt = $conn->prepare("SELECT * FROM photographer WHERE photographer_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultPhoto = $stmt->get_result();

    if ($resultPhoto->num_rows > 0) {
        $rowPhoto = $resultPhoto->fetch_assoc();
        $id_photographer = $rowPhoto['photographer_id'];

        // Calculate the date range based on the time frame
        $dateEnd = date('Y-m-d');
        $dateStart = date('Y-m-d', strtotime("-$timeFrame months"));

        // Create an array for storing all months
        $months = [];
        $currentDate = new DateTime($dateStart);
        $endDate = new DateTime($dateEnd);

        while ($currentDate <= $endDate) {
            $monthKey = $currentDate->format('Y-m');
            $months[$monthKey] = ['deposit_price' => 0, 'payment_price' => 0];
            $currentDate->modify('+1 month');
        }

        // Query to get booking details for the photographer
        $money_query = "
            SELECT 
    b.photographer_id, 
    t.type_work, 
    pay.pay_date, 
    (b.booking_price * 0.30) AS deposit_price, 
    0 AS payment_price
FROM 
    booking b 
JOIN 
    type_of_work tow ON b.type_of_work_id = tow.type_of_work_id 
JOIN 
    type t ON t.type_id = tow.type_id 
JOIN 
    pay ON pay.booking_id = b.booking_id 
WHERE 
    b.photographer_id = ?
    AND b.booking_pay_status = 5 
    AND pay.pay_status = 0
    AND pay.pay_date BETWEEN ? AND ?
    
UNION ALL

SELECT 
    b.photographer_id, 
    t.type_work, 
    pay.pay_date, 
    0 AS deposit_price, 
    (b.booking_price - (b.booking_price * 0.30)) AS payment_price
FROM 
    booking b 
JOIN 
    type_of_work tow ON b.type_of_work_id = tow.type_of_work_id 
JOIN 
    type t ON t.type_id = tow.type_id 
JOIN 
    pay ON pay.booking_id = b.booking_id 
WHERE 
    b.photographer_id = ? 
    AND b.booking_pay_status = 5 
    AND pay.pay_status = 1
    AND pay.pay_date BETWEEN ? AND ?;

        ";

        // Prepare and bind the statement
        $money_stmt = $conn->prepare($money_query);
        $money_stmt->bind_param("ississ", $id_photographer, $dateStart, $dateEnd, $id_photographer, $dateStart, $dateEnd);
        $money_stmt->execute();
        $money_result = $money_stmt->get_result();

        // Check for errors in query execution
        if (!$money_result) {
            $error = ['error' => 'Query failed: ' . $conn->error];
            echo json_encode($error);
            $conn->close();
            exit;
        }

        // Process query results and add to months array
        while ($money_data = $money_result->fetch_assoc()) {
            $payMonth = date('Y-m', strtotime($money_data['pay_date']));
            if (isset($months[$payMonth])) {
                $months[$payMonth]['deposit_price'] += (float)$money_data['deposit_price'];
                $months[$payMonth]['payment_price'] += (float)$money_data['payment_price'];
            }
        }

        // Prepare data for JSON output
        $data = [];
        foreach ($months as $month => $values) {
            $data[] = [
                'pay_date' => $month . '-01', // Just to show the month
                'deposit_price' => $values['deposit_price'],
                'payment_price' => $values['payment_price']
            ];
        }

        // Output data as JSON
        echo json_encode($data);
    } else {
        echo json_encode(['error' => 'Photographer not found.']);
    }
} else {
    echo json_encode(['error' => 'User not logged in.']);
}

// Close connection
$conn->close();
?>
