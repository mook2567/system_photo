<?php
include '../config_db.php'; // Include your database configuration

// Query to get photographer counts
$type_query = "
    SELECT 
        SUM(CASE WHEN photographer_prefix = 'นาย' THEN 1 ELSE 0 END) AS male_photographers,
        SUM(CASE WHEN photographer_prefix IN ('นาง', 'นางสาว') THEN 1 ELSE 0 END) AS female_photographers
    FROM photographer
";

// Execute queries
$type_result = $conn->query($type_query);
$customer_result = $conn->query($customer_query);

// Check for errors in query execution
if (!$type_result) {
    $error = [
        'error' => 'Query failed: ' . $conn->error
    ];
    echo json_encode($error);
    $conn->close();
    exit;
}

// Fetch data
$type_data = $type_result->fetch_assoc();
$customer_data = $customer_result->fetch_assoc();

// Prepare data for JSON output
$data = [
    'typeData' => (int)$type_data['typeData'],
];

// Output data as JSON
echo json_encode($data);

// Close connection
$conn->close();
?>
