<?php
include '../config_db.php'; // Include your database configuration

// คำสั่ง SQL เพื่อดึงข้อมูล photographer_scope
$query = "SELECT photographer_scope FROM photographer WHERE photographer_scope IS NOT NULL";
$result = $conn->query($query);

if ($result) {
    // ตรวจสอบรูปแบบของแต่ละค่าใน photographer_scope
    while ($row = $result->fetch_assoc()) {
        // แยกข้อมูล photographer_scope เป็นอาเรย์
        $scopes = explode(',', $row['photographer_scope']);

        // ตรวจสอบรูปแบบของแต่ละค่าในอาเรย์
        foreach ($scopes as $scope) {
            $scope = trim($scope); // ลบช่องว่างด้านหน้าและด้านหลัง
            if (!in_array($scope, ['กรุงเทพฯ', 'ภาคกลาง', 'ภาคใต้', 'ภาคเหนือ', 'ภาคตะวันออกเฉียงเหนือ', 'ภาคตะวันตก'])) {
                echo "ค่าที่ไม่ถูกต้อง: '$scope'\n"; // พิมพ์ค่าที่ไม่ถูกต้องรวมถึงเครื่องหมายอัญประกาศ
            }
        }
    }
} else {
    echo "Error: " . $conn->error;
}

// Query to get photographer work counts
$photographer_query = "
    SELECT 
        SUM(CASE WHEN photographer_scope LIKE '%กรุงเทพฯ%' THEN 1 ELSE 0 END) AS bangkok,
        SUM(CASE WHEN photographer_scope LIKE '%ภาคกลาง%' THEN 1 ELSE 0 END) AS central,
        SUM(CASE WHEN photographer_scope LIKE '%ภาคใต้%' THEN 1 ELSE 0 END) AS south,
        SUM(CASE WHEN photographer_scope LIKE '%ภาคเหนือ%' THEN 1 ELSE 0 END) AS north,
        SUM(CASE WHEN photographer_scope LIKE '%ภาคตะวันออกเฉียงเหนือ%' THEN 1 ELSE 0 END) AS northeast,
        SUM(CASE WHEN photographer_scope LIKE '%ภาคตะวันตก%' THEN 1 ELSE 0 END) AS west
    FROM 
        photographer
    WHERE 
        photographer_scope REGEXP 'กรุงเทพฯ|ภาคกลาง|ภาคใต้|ภาคเหนือ|ภาคตะวันออกเฉียงเหนือ|ภาคตะวันตก'
";

// Execute the photographer query
$photographer_result = $conn->query($photographer_query);

// Check for errors in query execution
if (!$photographer_result) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Query failed: ' . $conn->error]);
    $conn->close(); // Close connection only once
    exit;
}

// Fetch photographer data
$photographer_data = $photographer_result->fetch_assoc() ?: []; // Ensure it's an array

// Prepare combined data for JSON output
$data = [
    'bangkok' => (int)($photographer_data['bangkok'] ?? 0),
    'central' => (int)($photographer_data['central'] ?? 0),
    'south' => (int)($photographer_data['south'] ?? 0),
    'north' => (int)($photographer_data['north'] ?? 0),
    'northeast' => (int)($photographer_data['northeast'] ?? 0),
    'west' => (int)($photographer_data['west'] ?? 0)
];

// Output data as JSON
header('Content-Type: application/json'); // Set the correct content type
echo json_encode($data, JSON_NUMERIC_CHECK); // Ensure numeric values are returned as numbers

// Close connection
$conn->close();
