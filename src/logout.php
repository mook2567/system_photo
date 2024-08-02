<?php
// เริ่มต้นเซสชัน
session_start();

// ทำลายเซสชันทั้งหมด
session_unset();
session_destroy();

// ส่งผู้ใช้กลับไปที่หน้า index.php
header("Location: index.php");

// หยุดการทำงานของสคริปต์เพื่อให้แน่ใจว่าไม่มีการทำงานต่อหลังจากการเปลี่ยนเส้นทาง
exit();
?>
