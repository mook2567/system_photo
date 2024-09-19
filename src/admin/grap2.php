<?php
include '../config_db.php'; // Include your database configuration

// Query to get photographer counts
$photographer_query = "
    SELECT 
        SUM(CASE WHEN photographer_prefix = 'นาย' THEN 1 ELSE 0 END) AS male_photographers,
        SUM(CASE WHEN photographer_prefix IN ('นาง', 'นางสาว') THEN 1 ELSE 0 END) AS female_photographers
    FROM photographer
";

// Query to get customer counts
$customer_query = "
    SELECT 
        SUM(CASE WHEN cus_prefix = 'นาย' THEN 1 ELSE 0 END) AS male_customers,
        SUM(CASE WHEN cus_prefix IN ('นาง', 'นางสาว') THEN 1 ELSE 0 END) AS female_customers
    FROM customer
";

// Execute queries
$photographer_result = $conn->query($photographer_query);
$customer_result = $conn->query($customer_query);

// Check for errors in query execution
if (!$photographer_result || !$customer_result) {
    $error = [
        'error' => 'Query failed: ' . $conn->error
    ];
    echo json_encode($error);
    $conn->close();
    exit;
}

// Fetch data
$photographer_data = $photographer_result->fetch_assoc();
$customer_data = $customer_result->fetch_assoc();

// Prepare data for JSON output
$data = [
    'malePhotographersCount' => (int)$photographer_data['male_photographers'],
    'femalePhotographersCount' => (int)$photographer_data['female_photographers'],
    'maleCustomersCount' => (int)$customer_data['male_customers'],
    'femaleCustomersCount' => (int)$customer_data['female_customers']
];

// Output data as JSON
echo json_encode($data);

// Close connection
$conn->close();
?>
