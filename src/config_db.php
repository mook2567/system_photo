<?php
// -----------------------  VPN  -----------------------------
$servername = "100.99.99.99:3309";
$username = "rmuti";
$password = "rmuti";
$dbname = "photo_match";

$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// ----------- localhost --------------------------
// $servername = "localhost:3306";
// $username = "root";
// $password = "";
// $dbname = "photo_match";

// $conn = new mysqli($servername, $username, $password, $dbname);

// // ตรวจสอบการเชื่อมต่อ
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }
?>