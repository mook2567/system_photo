<?php
include '../config_db.php'; // Include your database configuration

header('Content-Type: application/json'); // Set content type to JSON

// Check if photographer ID is set
if (!isset($id_photographer)) {
    echo json_encode(['error' => 'Photographer ID is required']);
    exit;
}

// Initialize an array to store total bookings for each month
$monthlyBookings = array_fill(1, 12, ['pending' => 0, 'completed' => 0, 'canceled' => 0, 'reserved' => 0]); // Set all months to 0 initially

// Prepare the SQL query with parameter binding
$type_query = "SELECT 
    MONTH(booking_date) AS booking_month,
    SUM(booking_confirm_status = 0) AS total_reserved,
    SUM(booking_confirm_status = 1) AS total_pending,
    SUM(booking_confirm_status = 2) AS total_canceled,
    SUM(booking_confirm_status = 3) AS total_completed
FROM 
    booking b
JOIN
    photographer p ON b.photographer_id = p.photographer_id
WHERE 
    YEAR(booking_date) = YEAR(CURDATE())
    AND p.photographer_id = ?  -- Use a placeholder for prepared statement
GROUP BY 
    booking_month
ORDER BY 
    booking_month;";

// Prepare the statement
$stmt = $conn->prepare($type_query);

// Bind the parameter (assuming $id_photographer is an integer)
$stmt->bind_param('i', $id_photographer);

// Execute the query
$stmt->execute();

// Get the result
$type_result = $stmt->get_result();

// Check for errors in query execution
if (!$type_result) {
    $error = [
        'error' => 'Query failed: ' . $stmt->error
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

// Close the statement and database connection
$stmt->close();
$conn->close();
