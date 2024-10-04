<?php
include '../config_db.php'; // Include your database configuration

if (isset($_SESSION['photographer_login'])) {
    $email = $_SESSION['photographer_login'];
    $sql = "SELECT * FROM photographer WHERE photographer_email LIKE '$email'";
    $resultPhoto = $conn->query($sql);
    $rowPhoto = $resultPhoto->fetch_assoc();
    $id_photographer = $rowPhoto['photographer_id'];
}

// Initialize an array to store total bookings for each month
$monthlyBookings = array_fill(1, 12, ['pending' => 0, 'completed' => 0, 'canceled' => 0, 'reserved' => 0]); // Set all months to 0 initially

// Query to get booking counts by month for the current year
$type_query = $conn->prepare("SELECT 
    MONTH(b.booking_date) AS booking_month,
    SUM(b.booking_confirm_status = 0) AS total_reserved,
    SUM(b.booking_confirm_status = 1) AS total_pending,
    SUM(b.booking_confirm_status = 2) AS total_canceled,
    SUM(b.booking_confirm_status = 3) AS total_completed
FROM 
    booking b
WHERE 
    YEAR(b.booking_date) = YEAR(CURDATE())
AND 
    b.photographer_id = ?
GROUP BY 
    booking_month
ORDER BY 
    booking_month");

$type_query->bind_param("i", $id_photographer); // Bind photographer ID
$type_query->execute();
$type_result = $type_query->get_result();


// Check for errors in query execution
if (!$type_result) {
    $error = [
        'error' => 'Query failed: ' . $conn->error
    ];
    echo json_encode($error);
    $conn->close();
    exit;
}

// Loop through the result set and populate the monthly bookings array
while ($row = $type_result->fetch_assoc()) {
    $month = (int)$row['booking_month'];
    $type_data[] = [
        'booking_month' => $month,
        'total_reserved' => (int)$row['total_reserved'],
        'total_pending' => (int)$row['total_pending'],
        'total_canceled' => (int)$row['total_canceled'],
        'total_completed' => (int)$row['total_completed']
    ];
}
echo json_encode($type_data);


// Prepare the data for JSON output
$type_data = [];
foreach ($monthlyBookings as $month => $totals) {
    $type_data[] = [
        'booking_month' => $month,
        'total_reserved' => $totals['reserved'],
        'total_pending' => $totals['pending'],
        'total_canceled' => $totals['canceled'],
        'total_completed' => $totals['completed']
    ];
}

// Output data as JSON
echo json_encode($type_data);

// Close the database connection
$conn->close();
