<?php
include '../config_db.php'; // Include your database configuration

// Check if photographer is logged in and retrieve their ID
if (isset($_SESSION['photographer_login'])) {
    $email = $_SESSION['photographer_login'];
    $stmt = $conn->prepare("SELECT photographer_id FROM photographer WHERE photographer_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultPhoto = $stmt->get_result();
    $rowPhoto = $resultPhoto->fetch_assoc();
    $id_photographer = $rowPhoto['photographer_id'];
    $stmt->close();
} else {
    echo json_encode(['error' => 'Photographer not logged in']);
    exit;
}

// Query to get income data for the last 3 months
$income_query = "
    SELECT 
        DATE_FORMAT(p.pay_date, '%Y-%m') AS month, 
        SUM(CASE 
                WHEN p.pay_status = 0 THEN b.booking_price * 0.3 
                ELSE 0 
            END) AS total_deposit,
        SUM(CASE 
                WHEN p.pay_status = 1 THEN b.booking_price * 0.7 
                ELSE 0 
            END) AS total_payment
    FROM 
        pay p
    JOIN 
        booking b ON p.booking_id = b.booking_id
    WHERE 
        p.pay_date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
        AND b.photographer_id =  $id_photographer
    GROUP BY 
        DATE_FORMAT(p.pay_date, '%Y-%m')
    ORDER BY 
        month DESC
";

// Prepare the statement
$stmt = $conn->prepare($income_query);
$stmt->bind_param('i', $id_photographer);

// Execute the query
$stmt->execute();
$income_result = $stmt->get_result();

// Check if the query was successful
if (!$income_result) {
    echo json_encode(['error' => 'Income query failed: ' . $conn->error]);
    $stmt->close();
    $conn->close();
    exit;
}

// Fetch income data
$income_data = [];
while ($row = $income_result->fetch_assoc()) {
    $income_data[] = $row;
}

// Output income data as JSON
echo json_encode($income_data);

// Close connection
$stmt->close();
$conn->close();
?>
