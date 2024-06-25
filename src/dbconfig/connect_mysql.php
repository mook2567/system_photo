<?php
$servername = "db_photomatch.pcnone.com";
$username = "rmuti";
$password = "rmuti";
$dbname = "photo_system";

// ลองเชื่อมต่อกับ MySQL server
$conn = new mysqli($servername, $username, $password, $dbname);

// เช็คการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

//echo "การเชื่อมต่อสำเร็จ";

// หลังจากนี้คุณสามารถทำงานกับฐานข้อมูลต่อได้ เช่น ดึงข้อมูล แก้ไขข้อมูล หรือ ลบข้อมูล
?>
