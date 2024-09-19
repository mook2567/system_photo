<?php
include '../config_db.php'; // Include your database configuration

// Query to get photographer counts
$type_query = "
    SELECT t.type_work, COUNT(tow.type_id) AS total_count
    FROM type_of_work tow
    JOIN type t ON t.type_id = tow.type_id
    GROUP BY t.type_work;
";

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

// Fetch all rows of data
$type_data = $type_result->fetch_all(MYSQLI_ASSOC);

// Prepare data for JSON output
$data = [];
foreach ($type_data as $row) {
    $data[] = [
        'type_work' => $row['type_work'],
        'total_count' => (int)$row['total_count']
    ];
}

// Output data as JSON
echo json_encode($data);

// Close connection
$conn->close();
