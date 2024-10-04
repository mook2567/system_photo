<?php
include '../config_db.php'; // Include your database configuration

// Query to get photographer work counts
$photographer_query = "
    SELECT t.type_work, COUNT(tow.type_id) AS photographer_count
    FROM type_of_work tow
    JOIN type t ON t.type_id = tow.type_id
    GROUP BY t.type_work
    ORDER BY photographer_count DESC;
";

// Query to get customer popular work counts
$customer_query = "
    SELECT t.type_work, COUNT(b.type_of_work_id) AS customer_count 
    FROM booking b
    JOIN type_of_work tow ON b.type_of_work_id = tow.type_of_work_id
    JOIN type t ON t.type_id = tow.type_id
    GROUP BY t.type_work
    ORDER BY customer_count DESC;
";

// Execute the queries
$photographer_result = $conn->query($photographer_query);
$customer_result = $conn->query($customer_query);

// Fetch photographer data
$photographer_data = $photographer_result->fetch_all(MYSQLI_ASSOC);

// Fetch customer data
$customer_data = $customer_result->fetch_all(MYSQLI_ASSOC);

// Combine data
$data = [];
foreach ($photographer_data as $index => $row) {
    // Get customer count safely with fallback to 0
    $customer_count = isset($customer_data[$index]) ? (int)$customer_data[$index]['customer_count'] : 0;
    $data[] = [
        'type_work' => $row['type_work'],
        'photographer_count' => (int)$row['photographer_count'], // เปลี่ยนชื่อให้ตรงกับ JavaScript
        'customer_count' => $customer_count // เปลี่ยนชื่อให้ตรงกับ JavaScript
    ];
}

// Output data as JSON
header('Content-Type: application/json'); // กำหนด Content-Type ให้เป็น JSON
echo json_encode($data);

// Close connection
$conn->close();
