<?php
include '../config_db.php'; // Include your database configuration

// Initialize an array to store total bookings for each month
$monthlyBookings = array_fill(1, 12, ['pending' => 0, 'completed' => 0, 'canceled' => 0, 'reserved' => 0]); // Set all months to 0 initially

// Query to get booking counts by month for the current year
$type_query = "SELECT 
    MONTH(booking_date) AS booking_month,
    SUM(booking_confirm_status = 0) AS total_reserved,
    SUM(booking_confirm_status = 1) AS total_pending,
    SUM(booking_confirm_status = 2) AS total_canceled,
    SUM(booking_confirm_status = 3) AS total_completed
FROM 
    booking
WHERE 
    YEAR(booking_date) = YEAR(CURDATE())
GROUP BY 
    booking_month
ORDER BY 
    booking_month";

// Execute the query
$type_result = $conn->query($type_query);

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
    $monthlyBookings[$month]['pending'] = (int)$row['total_pending'];
    $monthlyBookings[$month]['completed'] = (int)$row['total_completed'];
    $monthlyBookings[$month]['canceled'] = (int)$row['total_canceled'];
    $monthlyBookings[$month]['reserved'] = (int)$row['total_reserved'];
}

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
