<?php
$servername = "45.136.254.198:3309";
$username = "rmuti";
$password = "rmuti";
$dbname = "photo_match";

$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>