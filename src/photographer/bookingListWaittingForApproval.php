<?php
session_start();
include '../config_db.php';
require_once '../popup.php';

$sql = "SELECT * FROM `information`";
$resultInfo = $conn->query($sql);
$rowInfo = $resultInfo->fetch_assoc();

if (isset($_SESSION['photographer_login'])) {
    $email = $_SESSION['photographer_login'];
    $sql = "SELECT * FROM photographer WHERE photographer_email LIKE '$email'";
    $resultPhoto = $conn->query($sql);
    $rowPhoto = $resultPhoto->fetch_assoc();
    $id_photographer = $rowPhoto['photographer_id'];
}
$sql1 = "SELECT 
            b.*, 
            c.cus_prefix, 
            c.cus_name, 
            c.cus_surname, 
            c.cus_tell, 
            c.cus_email, 
            t.type_work, 
            tow.type_of_work_rate_half_end, 
            tow.type_of_work_rate_full_end,
            tow.type_of_work_rate_half_start,
            tow.type_of_work_rate_full_start,
            CASE 
                WHEN (b.booking_start_time < '12:00:00' AND b.booking_end_time <= '12:00:00')
                    OR (b.booking_start_time >= '12:00:00' AND b.booking_end_time > '12:00:00') 
                THEN tow.type_of_work_rate_half_start
                ELSE tow.type_of_work_rate_full_start
            END AS booking_price
            FROM 
            booking b
            JOIN 
            customer c ON b.cus_id = c.cus_id
            JOIN 
            `type` t ON b.type_of_work_id = t.type_id
            JOIN 
            type_of_work tow ON tow.type_of_work_id = b.type_of_work_id
            WHERE 
            b.photographer_id = $id_photographer
            AND b.booking_confirm_status = '0';

";
$resultBooking = $conn->query($sql1);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['submit_booking_confirm_status'])) {
        echo $booking_price = $_POST['booking_price'];
        // echo $confirm_status = $_POST['confirm_status'];
        echo $booking_id = $_POST['booking_id'];

        // Assuming $conn is your MySQLi connection
        $sql = "UPDATE `booking` SET booking_price = ?, booking_confirm_status = '1' WHERE booking_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("di", $booking_price, $booking_id);

        if ($stmt->execute()) {
?>
            <script>
                setTimeout(function() {
                    Swal.fire({
                        title: '<div class="t1">อนุมัติการจองสำเร็จ</div>',
                        icon: 'success',
                        confirmButtonText: 'ตกลง',
                        allowOutsideClick: true,
                        allowEscapeKey: true,
                        allowEnterKey: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "";
                        }
                    });
                });
            </script>
        <?php
        } else {
        ?>
            <script>
                setTimeout(function() {
                    Swal.fire({
                        title: '<div class="t1">เกิดข้อผิดพลาดในการอนุมัติการจอง</div>',
                        icon: 'error',
                        confirmButtonText: 'ออก',
                        allowOutsideClick: true,
                        allowEscapeKey: true,
                        allowEnterKey: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "";
                        }
                    });
                });
            </script>
        <?php
        }
        $stmt->close();
    }
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['submit_booking_cancel'])) {
        echo $booking_note = $_POST['booking_note'];
        // echo $confirm_status = $_POST['confirm_status'];
        echo $booking_id = $_POST['booking_id'];

        // Assuming $conn is your MySQLi connection
        $sql = "UPDATE `booking` SET booking_note = ?, booking_confirm_status = '2' WHERE booking_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $booking_note, $booking_id);

        if ($stmt->execute()) {
        ?>
            <script>
                setTimeout(function() {
                    Swal.fire({
                        title: '<div class="t1">ปฎิเสธการจองสำเร็จ</div>',
                        icon: 'success',
                        confirmButtonText: 'ตกลง',
                        allowOutsideClick: true,
                        allowEscapeKey: true,
                        allowEnterKey: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "";
                        }
                    });
                });
            </script>
        <?php
        } else {
        ?>
            <script>
                setTimeout(function() {
                    Swal.fire({
                        title: '<div class="t1">เกิดข้อผิดพลาดในการปฎิเสธการจอง</div>',
                        icon: 'error',
                        confirmButtonText: 'ออก',
                        allowOutsideClick: true,
                        allowEscapeKey: true,
                        allowEnterKey: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "";
                        }
                    });
                });
            </script>
<?php
        }
        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Photo Match</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="icon" type="image/png" href="../img/icon-logo.png">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon
    <link href="img/favicon.ico" rel="icon"> -->

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="../lib/animate/animate.min.css" rel="stylesheet">
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../css/style.css" rel="stylesheet">

    <!-- font awaysome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Athiti&family=Merriweather:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Athiti', sans-serif;
            overflow-x: hidden;
            background-color: #ffff;
        }

        .f {
            font-family: 'Athiti', sans-serif;
        }

        @media (max-width: 768px) {

            .col-md-3,
            .col-md-6,
            .col-md-2 {
                width: 100% !important;
            }

            .mb-3-md {
                margin-bottom: 1rem !important;
            }

            .me-2-md {
                margin-right: 0.5rem !important;
            }

            .ms-2-md {
                margin-left: 0.5rem !important;
            }

            .py-3-md {
                padding-top: 1rem !important;
                padding-bottom: 1rem !important;
            }

            .px-4-md {
                padding-left: 1.5rem !important;
                padding-right: 1.5rem !important;
            }

            .text-center-md {
                text-align: center !important;
            }

            .text-start-md {
                text-align: start !important;
            }

            .text-end-md {
                text-align: end !important;
            }

            .justify-content-center-md {
                justify-content: center !important;
            }

            .align-items-center-md {
                align-items: center !important;
            }
        }

        .table th,
        .table td {
            vertical-align: middle;
            /* จัดการให้เนื้อหาตรงกลางของเซลล์ */
        }

        .table th.text-center,
        .table td.text-center {
            text-align: center;
            /* จัดการให้เนื้อหาอยู่ตรงกลางของเซลล์ */
        }

        .table .btn {
            width: 150px;
        }

        /* 3. เพิ่มการเรียงลำดับให้เป็นแถวคู่-คี่ */
        .table tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
            /* สลับสีพื้นหลังแถว */
        }

        .table th:nth-child(6),
        .table td:nth-child(6) {
            width: 500px;
            height: 50px;
            text-align: center;
            /* กำหนดความกว้างของคอลัมน์การจัดการให้เหมาะสม */
        }

        .table th:nth-child(1),
        .table td:nth-child(1) {
            width: 200px;
            height: 50px;
            text-align: center;
            /* กำหนดความกว้างของคอลัมน์การจัดการให้เหมาะสม */
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
        }

        .modal-dialog {
            width: 70%;
            /* เปลี่ยนเป็นค่าที่คุณต้องการ เช่น 50% หรือ 70% */
        }

        .table th:nth-child(2),
        .table th:nth-child(3),
        .table th:nth-child(4),
        .table th:nth-child(5),
        .table td:nth-child(2),
        .table td:nth-child(3),
        .table td:nth-child(4),
        .table td:nth-child(5) {
            width: 140px;
            height: 50px;
            /* กำหนดความกว้างของคอลัมน์การจัดการให้เหมาะสม */
        }
    </style>
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-dark" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Navbar Start -->
        <div class="mt-5 container-fluid  bg-transparent">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-0 px-4">
                <a href="index.php" class="navbar-brand d-flex align-items-center text-center" style="height: 70px;">
                    <img class="img-fluid" src="../img/logo/<?php echo isset($rowInfo['information_icon']) ? $rowInfo['information_icon'] : ''; ?>" style="height: 30px;">
                </a>
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon text-primary"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto f">
                        <a href="index.php" class="nav-item nav-link">หน้าหลัก</a>
                        <a href="table.php" class="nav-item nav-link">ตารางงาน</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle bg-dark active" data-bs-toggle="dropdown">รายการจอง</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <!-- <a href="bookingListAll.php" class="dropdown-item">รายการจองทั้งหมด</a> -->
                                <a href="bookingListWaittingForApproval.php" class="dropdown-item active">รายการจองที่รออนุมัติ</a>
                                <a href="bookingListApproved.php" class="dropdown-item">รายการจองที่อนุมัติแล้ว</a>
                                <a href="bookingListConfirmPayment.php" class="dropdown-item">รายการจองที่รอตรวจสอบการชำระ</a>
                                <a href="bookingListSend.php" class="dropdown-item">รายการจองที่ต้องส่งงาน</a>
                                <a href="bookingListApproved.php" class="dropdown-item">รายการจองที่เสร็จสิ้นแล้ว</a>
                                <a href="bookingListNotApproved.php" class="dropdown-item">รายการจองที่ไม่อนุมัติ</a>
                            </div>
                        </div>
                        <!-- <a href="report.php" class="nav-item nav-link">รายงาน</a> -->
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle bg-dark" data-bs-toggle="dropdown">โปรไฟล์</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="profile.php" class="dropdown-item">โปรไฟล์</a>
                                <a href="editProfile.php" class="dropdown-item">แก้ไขข้อมูลส่วนตัว</a>
                                <a href="about.php" class="dropdown-item">เกี่ยวกับ</a>
                                <a href="contact.php" class="dropdown-item">ติดต่อ</a>
                                <a href="../logout.php" class="dropdown-item">ออกจากระบบ</a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->

    <!-- Header Start -->
    <div class="container-fluid header bg-primary p-1" style="height: 300px;">
        <div class="row g-1 align-items-center flex-column-reverse flex-md-row">
            <div class="col-md-4 p-5 mt-lg-5">
                <br><br>
                <h1 class="display-7 animated fadeIn mb-1 text-white f text-md-end">รายการจองที่รออนุมัติ</h1>
                <h1 class="display-9 animated fadeIn mb-1 text-white f text-md-end">ของคุณ</h1>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <div class="center container mt-5" style="height: 520px;">
        <h1 class="footer-title text-center f mt-3">ตารางรายการจองที่รออนุมัติ</h1>
        <div class="row justify-content-end">
            <div class="col-md-4">
                <!-- <button type="button" onclick="window.location.href='bookingListAll.php'" class="btn btn-outline-dark">ทั้งหมด</button> -->
                <button type="button" onclick="window.location.href='bookingListWaittingForApproval.php'" class="btn btn-outline-dark active">รออนุมัติ</button>
                <button type="button" onclick="window.location.href='bookingListApproved.php'" class="btn btn-outline-dark">อนุมัติแล้ว</button>
                <button type="button" onclick="window.location.href='bookingListNotApproved.php'" class="btn btn-outline-dark">ไม่อนุมัติ</button>
            </div>
        </div>
        <div class="table-responsive mt-1">
            <table class="table table-hover table-bordered-3">
                <thead>
                    <tr>
                        <th scope="col">ชื่อ</th>
                        <th scope="col">นามสกุล</th>
                        <th scope="col">วันที่เริ่มงาน</th>
                        <th scope="col">เวลาเริ่มงาน</th>
                        <th scope="col">ประเภทงาน</th>
                        <th scope="col">ดำเนินการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($resultBooking->num_rows > 0) {
                        while ($rowBooking = $resultBooking->fetch_assoc()) {
                            if (isset($rowBooking['booking_id'])) {
                    ?>
                                <tr>
                                    <td><?php echo $rowBooking['cus_name']; ?></td>
                                    <td><?php echo $rowBooking['cus_surname']; ?></td>
                                    <td><?php echo $rowBooking['booking_start_date']; ?></td>
                                    <td><?php echo $rowBooking['booking_start_time']; ?></td>
                                    <td><?php echo $rowBooking['type_work']; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#details<?php echo $rowBooking['booking_id']; ?>">ดูเพิ่มเติม</button>
                                        <button type="button" class="btn btn-warning btn-sm me-3" data-bs-toggle="modal" data-bs-target="#edite<?php echo $rowBooking['booking_id']; ?>">อนุมัติการจอง</button>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#cancel<?php echo $rowBooking['booking_id']; ?>">ปฎิเสธการจอง</button>
                                    </td>
                                </tr>
                                <!-- details -->
                                <div class="modal fade" id="details<?php echo $rowBooking['booking_id']; ?>" tabindex="-1" aria-labelledby="detailsLabel<?php echo $rowBooking['booking_id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="detailsLabel<?php echo $rowBooking['booking_id']; ?>"><b><i class="fas fa-clipboard-list"></i>&nbsp;&nbsp;รายละเอียดการจองคิว</b></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action=" " method="POST">
                                                <div class="modal-body" style="height: auto;">
                                                    <div class="container-md">
                                                        <div class="col-md-12 container-fluid">
                                                            <div class="col-12">
                                                                <div class="row">
                                                                    <div class="col-2">
                                                                        <label for="prefix" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px; "> คำนำหน้า</span>
                                                                        </label>
                                                                        <input type="text" name="prefix" class="form-control mt-1" value="<?php echo $rowBooking['cus_prefix']; ?>" readonly>
                                                                    </div>
                                                                    <div class="col-5">
                                                                        <label for="name" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">ชื่อ</span>
                                                                        </label>
                                                                        <input type="text" name="name" class="form-control mt-1" value="<?php echo $rowBooking['cus_name']; ?>" readonly>
                                                                    </div>
                                                                    <div class="col-5">
                                                                        <label for="surname" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; font-size: 13px;">นามสกุล</span>
                                                                        </label>
                                                                        <input type="text" name="surname" class="form-control mt-1" value="<?php echo $rowBooking['cus_surname']; ?>" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 mt-3">
                                                                <div class="row">
                                                                    <div class="col-md-4 text-center">
                                                                        <label for="booking-start-date" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">วันที่เริ่มจอง</span>
                                                                        </label>
                                                                        <input type="date" name="booking-start-date" class="form-control mt-1" value="<?php echo $rowBooking['booking_start_date']; ?>" readonly style="resize: none;">
                                                                    </div>
                                                                    <div class="col-md-4 text-center">
                                                                        <label for="booking-end-date" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">วันที่สิ้นสุดการจอง</span>
                                                                        </label>
                                                                        <input type="date" name="booking-end-date" class="form-control mt-1" value="<?php echo $rowBooking['booking_end_date']; ?>" readonly style="resize: none;">
                                                                    </div>
                                                                    <div class="col-md-2 text-center">
                                                                        <label for="booking-start-time" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">เวลาเริ่มงาน</span>
                                                                        </label>
                                                                        <input type="time" name="booking-start-time" class="form-control mt-1" value="<?php echo $rowBooking['booking_start_time']; ?>" readonly style="resize: none;">
                                                                    </div>
                                                                    <div class="col-md-2 text-center">
                                                                        <label for="booking-end-time" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">เวลาสิ้นสุด</span>
                                                                        </label>
                                                                        <input type="time" name="booking-end-time" class="form-control mt-1" value="<?php echo $rowBooking['booking_end_time']; ?>" readonly style="resize: none;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 mt-3">
                                                                <div class="row">
                                                                    <div class="col-md-8 text-center">
                                                                        <label for="location" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">สถานที่</span>
                                                                        </label>
                                                                        <input type="text" name="location" class="form-control mt-1" value="<?php echo $rowBooking['booking_location']; ?>" readonly style="resize: none;">
                                                                    </div>
                                                                    <div class="col-md-2 text-center">
                                                                        <label for="type" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">ประเภทงาน</span>
                                                                        </label>
                                                                        <input type="text" name="type" class="form-control mt-1" value="<?php echo $rowBooking['type_work']; ?>" readonly style="resize: none;">
                                                                    </div>
                                                                    <div class="col-md-2 text-center">
                                                                        <label for="type" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">จำนวนชั่วโมงทำงาน</span>
                                                                        </label>
                                                                        <?php
                                                                        // Convert time to DateTime objects
                                                                        $startTime = new DateTime($rowBooking['booking_start_time']);
                                                                        $endTime = new DateTime($rowBooking['booking_end_time']);

                                                                        // Calculate the interval between the start and end times
                                                                        $interval = $startTime->diff($endTime);
                                                                        $hours = $interval->h; // Hours part
                                                                        $minutes = $interval->i; // Minutes part

                                                                        // Format the total hours and minutes
                                                                        if ($minutes == 0) {
                                                                            $workingHours = $hours . " ชั่วโมง";
                                                                        } else {
                                                                            $workingHours = $hours . " ชั่วโมง " . $minutes . " นาที";
                                                                        }
                                                                        ?>
                                                                        <input type="text" name="type" class="form-control mt-1" value="<?php echo $workingHours; ?>" readonly style="resize: none;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mt-3 text-center">
                                                                <label for="details" style="font-weight: bold; display: flex; align-items: center;">
                                                                    <span style="color: black; margin-right: 5px;font-size: 13px;">คำอธิบาย</span>
                                                                </label>
                                                                <input name="details" class="form-control mt-1" value="<?php echo $rowBooking['booking_details']; ?>" readonly style="resize: none; height: 100px;"></input>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="row mt-3">
                                                                    <div class="col-5">
                                                                        <label for="mobile" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">เบอร์โทรศัพท์มือถือ</span>
                                                                        </label>
                                                                        <input type="text" name="mobile" class="form-control mt-1" value="<?php echo $rowBooking['cus_tell']; ?>" readonly style="resize: none;">
                                                                    </div>
                                                                    <div class="col-5 text-center">
                                                                        <label for="email" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">อีเมล</span>
                                                                        </label>
                                                                        <input type="email" name="email" class="form-control mt-1" value="<?php echo $rowBooking['cus_email']; ?>" readonly style="resize: none;">
                                                                    </div>
                                                                    <div class="col-2">
                                                                        <label for="date-saved" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">วันที่บันทึก</span>
                                                                        </label>
                                                                        <input type="date" name="date-saved" class="form-control mt-1" value="<?php echo $rowBooking['booking_date']; ?>" readonly style="resize: none;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer justify-content-center">
                                                    <button type="button" class="btn btn-danger" style="width: 150px; height:45px;" data-bs-dismiss="modal">ปิด</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- Edit -->
                                <div class="modal fade" id="edite<?php echo $rowBooking['booking_id']; ?>" tabindex="-1" aria-labelledby="editeLabel<?php echo $rowBooking['booking_id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editeLabel<?php echo $rowBooking['booking_id']; ?>"><b><i class="fas fa-clipboard-list"></i>&nbsp;&nbsp;รายละเอียดการจองคิวที่ต้องการอนุมัติ</b></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="" method="POST">
                                                <div class="modal-body" style="height: auto;">
                                                    <div class="container-md">
                                                        <div class="col-md-12 container-fluid">
                                                            <!-- Customer Information -->
                                                            <div class="col-12">
                                                                <div class="row">
                                                                    <div class="col-2">
                                                                        <label for="prefix" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">คำนำหน้า</span>
                                                                        </label>
                                                                        <input type="text" name="prefix" class="form-control mt-1" value="<?php echo $rowBooking['cus_prefix']; ?>" readonly>
                                                                    </div>
                                                                    <div class="col-5">
                                                                        <label for="name" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">ชื่อ</span>
                                                                        </label>
                                                                        <input type="text" name="name" class="form-control mt-1" value="<?php echo $rowBooking['cus_name']; ?>" readonly>
                                                                    </div>
                                                                    <div class="col-5">
                                                                        <label for="surname" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; font-size: 13px;">นามสกุล</span>
                                                                        </label>
                                                                        <input type="text" name="surname" class="form-control mt-1" value="<?php echo $rowBooking['cus_surname']; ?>" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Booking Information -->
                                                            <div class="col-12 mt-3">
                                                                <div class="row">
                                                                    <div class="col-md-4 text-center">
                                                                        <label for="booking-start-date" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">วันที่เริ่มจอง</span>
                                                                        </label>
                                                                        <input type="date" name="booking-start-date" class="form-control mt-1" value="<?php echo $rowBooking['booking_start_date']; ?>" readonly style="resize: none;">
                                                                    </div>
                                                                    <div class="col-md-4 text-center">
                                                                        <label for="booking-end-date" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">วันที่สิ้นสุดการจอง</span>
                                                                        </label>
                                                                        <input type="date" name="booking-end-date" class="form-control mt-1" value="<?php echo $rowBooking['booking_end_date']; ?>" readonly style="resize: none;">
                                                                    </div>
                                                                    <div class="col-md-2 text-center">
                                                                        <label for="booking-start-time" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">เวลาเริ่มงาน</span>
                                                                        </label>
                                                                        <input type="time" name="booking-start-time" class="form-control mt-1" value="<?php echo $rowBooking['booking_start_time']; ?>" readonly style="resize: none;">
                                                                    </div>
                                                                    <div class="col-md-2 text-center">
                                                                        <label for="booking-end-time" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">เวลาสิ้นสุด</span>
                                                                        </label>
                                                                        <input type="time" name="booking-end-time" class="form-control mt-1" value="<?php echo $rowBooking['booking_end_time']; ?>" readonly style="resize: none;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 mt-3">
                                                                <div class="row">
                                                                    <div class="col-md-8 text-center">
                                                                        <label for="location" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">สถานที่</span>
                                                                        </label>
                                                                        <input type="text" name="location" class="form-control mt-1" value="<?php echo $rowBooking['booking_location']; ?>" readonly style="resize: none;">
                                                                    </div>
                                                                    <div class="col-md-2 text-center">
                                                                        <label for="type" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">ประเภทงาน</span>
                                                                        </label>
                                                                        <input type="text" name="type" class="form-control mt-1" value="<?php echo $rowBooking['type_work']; ?>" readonly style="resize: none;">
                                                                    </div>
                                                                    <div class="col-md-2 text-center">
                                                                        <label for="type" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">จำนวนชั่วโมงทำงาน</span>
                                                                        </label>
                                                                        <?php
                                                                        // Convert time to DateTime objects
                                                                        $startTime = new DateTime($rowBooking['booking_start_time']);
                                                                        $endTime = new DateTime($rowBooking['booking_end_time']);

                                                                        // Calculate the interval between the start and end times
                                                                        $interval = $startTime->diff($endTime);
                                                                        $hours = $interval->h; // Hours part
                                                                        $minutes = $interval->i; // Minutes part

                                                                        // Format the total hours and minutes
                                                                        if ($minutes == 0) {
                                                                            $workingHours = $hours . " ชั่วโมง";
                                                                        } else {
                                                                            $workingHours = $hours . " ชั่วโมง " . $minutes . " นาที";
                                                                        }
                                                                        ?>
                                                                        <input type="text" name="type" class="form-control mt-1" value="<?php echo $workingHours; ?>" readonly style="resize: none;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Additional Booking Details -->
                                                            <div class="col-md-12 mt-3 text-center">
                                                                <label for="details" style="font-weight: bold; display: flex; align-items: center;">
                                                                    <span style="color: black; margin-right: 5px;font-size: 13px;">คำอธิบาย</span>
                                                                </label>
                                                                <input name="details" class="form-control mt-1" value="<?php echo $rowBooking['booking_details']; ?>" readonly style="resize: none; height: 100px;"></input>
                                                            </div>

                                                            <!-- Customer Contact Information -->
                                                            <div class="col-12">
                                                                <div class="row mt-3">
                                                                    <div class="col-3">
                                                                        <label for="mobile" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">เบอร์โทรศัพท์มือถือ</span>
                                                                        </label>
                                                                        <input type="text" name="mobile" class="form-control mt-1" value="<?php echo $rowBooking['cus_tell']; ?>" readonly>
                                                                    </div>
                                                                    <div class="col-3 text-center">
                                                                        <label for="email" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">อีเมล</span>
                                                                        </label>
                                                                        <input type="email" name="email" class="form-control mt-1" value="<?php echo $rowBooking['cus_email']; ?>" readonly>
                                                                    </div>
                                                                    <?php
                                                                    // การคำนวณระยะเวลา
                                                                    $startTime = strtotime($rowBooking['booking_start_time']);
                                                                    $endTime = strtotime($rowBooking['booking_end_time']);
                                                                    $hoursDifference = ($endTime - $startTime) / 3600; // แปลงวินาทีเป็นชั่วโมง

                                                                    // กำหนดเรทราคาสูงสุดที่สามารถกรอกได้
                                                                    $maxPrice = 0;
                                                                    if ($hoursDifference <= 4) {
                                                                        $maxPrice = $rowBooking['type_of_work_rate_half_end'];
                                                                        $messageQ = "กรอกราคาได้ไม่เกิน " . number_format($maxPrice, 2) . " บาท (ครึ่งวัน)";
                                                                    } elseif ($hoursDifference > 4 && $hoursDifference <= 8) {
                                                                        $maxPrice = $rowBooking['type_of_work_rate_full_end'];
                                                                        $messageQ = "กรอกราคาได้ไม่เกิน " . number_format($maxPrice, 2) . " บาท (เต็มวัน)";
                                                                    } else {
                                                                        $messageQ = "ระยะเวลาเกินขอบเขตที่กำหนด";
                                                                    }
                                                                    ?>
                                                                    <!-- รับจองคิวช่างภาพเริ่มต้น -->
                                                                    <div class="col-4">
                                                                        <label for="confirm_status" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px; font-size: 13px;">ราคา (บาท)</span>
                                                                            <span id="priceMessage" style="color: red; font-size: 13px;"><?php echo $messageQ; ?></span>

                                                                        </label>
                                                                        <input type="number" name="booking_price" id="booking_price" class="form-control mt-1"
                                                                            value="<?php echo htmlspecialchars($rowBooking['booking_price'], ENT_QUOTES, 'UTF-8'); ?>"
                                                                            step="5"
                                                                            max="<?php echo $maxPrice; ?>">
                                                                    </div>
                                                                    <!-- รับจองคิวช่างภาพสิ้นสุด -->
                                                                    <?php
                                                                    // การคำนวณระยะเวลา
                                                                    $startTime = strtotime($rowBooking['booking_start_time']);
                                                                    $endTime = strtotime($rowBooking['booking_end_time']);
                                                                    $hoursDifference = ($endTime - $startTime) / 3600; // แปลงวินาทีเป็นชั่วโมง

                                                                    // กำหนดข้อความและเรทราคา
                                                                    if ($hoursDifference <= 4) {
                                                                        $rateStart = $rowBooking['type_of_work_rate_half_start'];
                                                                        $rateEnd = $rowBooking['type_of_work_rate_half_end'];
                                                                        $message = "เรทราคาเริ่มต้นคือ " . number_format($rateStart, 0) . " บาท เรทราคาสิ้นสุดคือ " . number_format($rateEnd, 0) . " บาท";
                                                                    } elseif ($hoursDifference > 4 && $hoursDifference <= 8) {
                                                                        $rateStart = $rowBooking['type_of_work_rate_full_start'];
                                                                        $rateEnd = $rowBooking['type_of_work_rate_full_end'];
                                                                        $message = "เรทราคาเริ่มต้นคือ " . number_format($rateStart, 0) . " บาท เรทราคาสิ้นสุดคือ " . number_format($rateEnd, 0) . " บาท";
                                                                    } else {
                                                                        $message = "ระยะเวลาเกินขอบเขตที่กำหนด";
                                                                    }
                                                                    ?>

                                                                    <div class="col-2">
                                                                        <label class="" for="confirm_status" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: red; margin-right: 5px; font-size: 13px;">หมายเหตุ</span>
                                                                            <span style="color: red;">*</span>
                                                                        </label>
                                                                        <span id="rateMessage" style="color: red; font-size: 13px; margin-top: 10px;"><?php echo $message; ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="booking_id" value="<?php echo $rowBooking['booking_id']; ?>">
                                                <div class="modal-footer justify-content-center">
                                                    <button type="button" class="btn btn-danger" style="width: 150px; height:45px;" data-bs-dismiss="modal">ปิด</button>
                                                    <button id="saveButton" name="submit_booking_confirm_status" class="btn btn-primary" style="width: 150px; height:45px;">อนุมัติการจอง</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- cancel -->
                                <div class="modal fade" id="cancel<?php echo $rowBooking['booking_id']; ?>" tabindex="-1" aria-labelledby="cancelLabel<?php echo $rowBooking['booking_id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="cancelLabel<?php echo $rowBooking['booking_id']; ?>"><b><i class="fas fa-clipboard-list"></i>&nbsp;&nbsp;รายละเอียดการจองคิวที่ต้องการปฎิเสธ</b></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="" method="POST">
                                                <div class="modal-body" style="height: auto;">
                                                    <div class="container-md">
                                                        <div class="col-md-12 container-fluid">
                                                            <!-- Customer Information -->
                                                            <div class="col-12">
                                                                <div class="row">
                                                                    <div class="col-2">
                                                                        <label for="prefix" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">คำนำหน้า</span>
                                                                        </label>
                                                                        <input type="text" name="prefix" class="form-control mt-1" value="<?php echo $rowBooking['cus_prefix']; ?>" readonly>
                                                                    </div>
                                                                    <div class="col-5">
                                                                        <label for="name" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">ชื่อ</span>
                                                                        </label>
                                                                        <input type="text" name="name" class="form-control mt-1" value="<?php echo $rowBooking['cus_name']; ?>" readonly>
                                                                    </div>
                                                                    <div class="col-5">
                                                                        <label for="surname" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; font-size: 13px;">นามสกุล</span>
                                                                        </label>
                                                                        <input type="text" name="surname" class="form-control mt-1" value="<?php echo $rowBooking['cus_surname']; ?>" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Booking Information -->
                                                            <div class="col-12 mt-3">
                                                                <div class="row">
                                                                    <div class="col-md-4 text-center">
                                                                        <label for="booking-start-date" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">วันที่เริ่มจอง</span>
                                                                        </label>
                                                                        <input type="date" name="booking-start-date" class="form-control mt-1" value="<?php echo $rowBooking['booking_start_date']; ?>" readonly style="resize: none;">
                                                                    </div>
                                                                    <div class="col-md-4 text-center">
                                                                        <label for="booking-end-date" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">วันที่สิ้นสุดการจอง</span>
                                                                        </label>
                                                                        <input type="date" name="booking-end-date" class="form-control mt-1" value="<?php echo $rowBooking['booking_end_date']; ?>" readonly style="resize: none;">
                                                                    </div>
                                                                    <div class="col-md-2 text-center">
                                                                        <label for="booking-start-time" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">เวลาเริ่มงาน</span>
                                                                        </label>
                                                                        <input type="time" name="booking-start-time" class="form-control mt-1" value="<?php echo $rowBooking['booking_start_time']; ?>" readonly style="resize: none;">
                                                                    </div>
                                                                    <div class="col-md-2 text-center">
                                                                        <label for="booking-end-time" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">เวลาสิ้นสุด</span>
                                                                        </label>
                                                                        <input type="time" name="booking-end-time" class="form-control mt-1" value="<?php echo $rowBooking['booking_end_time']; ?>" readonly style="resize: none;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 mt-3">
                                                                <div class="row">
                                                                    <div class="col-md-8 text-center">
                                                                        <label for="location" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">สถานที่</span>
                                                                        </label>
                                                                        <input type="text" name="location" class="form-control mt-1" value="<?php echo $rowBooking['booking_location']; ?>" readonly style="resize: none;">
                                                                    </div>
                                                                    <div class="col-md-2 text-center">
                                                                        <label for="type" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">ประเภทงาน</span>
                                                                        </label>
                                                                        <input type="text" name="type" class="form-control mt-1" value="<?php echo $rowBooking['type_work']; ?>" readonly style="resize: none;">
                                                                    </div>
                                                                    <div class="col-md-2 text-center">
                                                                        <label for="type" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">จำนวนชั่วโมงทำงาน</span>
                                                                        </label>
                                                                        <?php
                                                                        // Convert time to DateTime objects
                                                                        $startTime = new DateTime($rowBooking['booking_start_time']);
                                                                        $endTime = new DateTime($rowBooking['booking_end_time']);

                                                                        // Calculate the interval between the start and end times
                                                                        $interval = $startTime->diff($endTime);
                                                                        $hours = $interval->h; // Hours part
                                                                        $minutes = $interval->i; // Minutes part

                                                                        // Format the total hours and minutes
                                                                        if ($minutes == 0) {
                                                                            $workingHours = $hours . " ชั่วโมง";
                                                                        } else {
                                                                            $workingHours = $hours . " ชั่วโมง " . $minutes . " นาที";
                                                                        }
                                                                        ?>
                                                                        <input type="text" name="type" class="form-control mt-1" value="<?php echo $workingHours; ?>" readonly style="resize: none;">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Additional Booking Details -->
                                                            <div class="col-md-12 mt-3 text-center">
                                                                <label for="details" style="font-weight: bold; display: flex; align-items: center;">
                                                                    <span style="color: black; margin-right: 5px;font-size: 13px;">คำอธิบาย</span>
                                                                </label>
                                                                <input name="details" class="form-control mt-1" value="<?php echo $rowBooking['booking_details']; ?>" readonly style="resize: none; height: 100px;"></input>
                                                            </div>

                                                            <!-- Customer Contact Information -->
                                                            <div class="col-12">
                                                                <div class="row mt-3">
                                                                    <div class="col-4">
                                                                        <label for="mobile" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">เบอร์โทรศัพท์มือถือ</span>
                                                                        </label>
                                                                        <input type="text" name="mobile" class="form-control mt-1" value="<?php echo $rowBooking['cus_tell']; ?>" readonly>
                                                                    </div>
                                                                    <div class="col-4 text-center">
                                                                        <label for="email" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">อีเมล</span>
                                                                        </label>
                                                                        <input type="email" name="email" class="form-control mt-1" value="<?php echo $rowBooking['cus_email']; ?>" readonly>
                                                                    </div>
                                                                    <div class="col-4">
                                                                        <label for="booking_note" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">หมายเหตุการปฎิเสธ</span>
                                                                            <span style="color: red;">*</span>
                                                                        </label>
                                                                        <input type="booking_note" name="booking_note" class="form-control mt-1" placeholder="กรุณากรอกหมายเหตุการไม่รับงาน">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="booking_id" value="<?php echo $rowBooking['booking_id']; ?>">
                                                <div class="modal-footer justify-content-center">
                                                    <button type="button" class="btn btn-danger" style="width: 150px; height:45px;" data-bs-dismiss="modal">ปิด</button>
                                                    <button id="saveButton" name="submit_booking_cancel" class="btn btn-primary" style="width: 150px; height:45px;">ปฎิเสธการจอง</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                    <?php
                            }
                        }
                    } else {
                        echo "<tr><td colspan='6'>ไม่พบข้อมูลรายการรออนุมัติ</td></tr>";
                    }

                    ?>
                </tbody>
            </table>
        </div>
        <div class="row justify-content-center mt-2 container-center text-center">
            <div class="col-md-12">
                <button onclick="window.history.back();" class="btn btn-danger mb-5" style="width: 150px; height: 45px;">ย้อนกลับ</button>
            </div>
        </div>
    </div>

    <!-- <div class="container-fluid bg-dark text-white-50 footer wow fadeIn">
        <div class="copyright">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; <a class="border-bottom" href="#">2024 Photo Match</a>, All Right Reserved.
                </div>
            </div>
        </div>
    </div> -->
    <!-- Footer End -->
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/wow/wow.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/wow/wow.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>

    <script>
        document.getElementById('booking_price').addEventListener('input', function() {
            const maxPrice = parseFloat(<?php echo $maxPrice; ?>);
            const inputPrice = parseFloat(this.value);
            const priceMessage = document.getElementById('priceMessage');
            const saveButton = document.getElementById('saveButton');

            if (inputPrice > maxPrice) {
                priceMessage.textContent = 'ราคาที่กรอกเกินจากที่กำหนดไว้ กรุณากรอกไม่เกิน ' + maxPrice.toFixed(2) + ' บาท';
                saveButton.disabled = true; // ปิดการใช้งานปุ่มอนุมัติการจอง
            } else {
                priceMessage.textContent = '';
                saveButton.disabled = false; // เปิดการใช้งานปุ่มอนุมัติการจอง
            }
        });
    </script>

    <!-- Template Javascript -->
    <script src="../js/main.js"></script>
</body>

</html>