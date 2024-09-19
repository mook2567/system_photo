<?php
include '../config_db.php'; // Include your database configuration

// Query to get photographer counts
$photographer_query = "
    SELECT 
        SUM(CASE WHEN photographer_prefix = 'นาย' THEN 1 ELSE 0 END) AS male_photographers,
        SUM(CASE WHEN photographer_prefix IN ('นาง', 'นางสาว') THEN 1 ELSE 0 END) AS female_photographers
    FROM photographer
";


// Execute queries
$photographer_result = $conn->query($photographer_query);
// Check for errors in query execution
if (!$photographer_result) {
    $error = [
        'error' => 'Query failed: ' . $conn->error
    ];
    echo json_encode($error);
    $conn->close();
    exit;
}

// Fetch data
$photographer_data = $photographer_result->fetch_assoc();

// Prepare data for JSON output
$data = [
    'malePhotographersCount' => (int)$photographer_data['male_photographers'],
    'femalePhotographersCount' => (int)$photographer_data['female_photographers']
];

// Output data as JSON
echo json_encode($data);

// Close connection
$conn->close();
?>
