<?php
session_start();
include '../config_db.php';
require_once '../popup.php';

$sql = "SELECT * FROM `information`";
$resultInfo = $conn->query($sql);
$rowInfo = $resultInfo->fetch_assoc();

if (isset($_SESSION['photographer_login'])) {
    $email = $_SESSION['photographer_login'];

    // Prepared statement for photographer query
    $stmt = $conn->prepare("SELECT * FROM photographer WHERE photographer_email LIKE ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultPhoto = $stmt->get_result();
    $rowPhoto = $resultPhoto->fetch_assoc();
    $id_photographer = $rowPhoto['photographer_id'];
}

// Query to retrieve bookings
$sql1 = "
    SELECT b.*, 
           c.cus_prefix, 
           c.cus_name, 
           c.cus_surname, 
           c.cus_tell, 
           c.cus_email, 
           t.type_work, 
           p.*, 
           sub.*, 
           r.*, 
           (b.booking_price - (b.booking_price * 0.30)) AS payment_price, 
           (b.booking_price * 0.30) AS deposit_price
    FROM booking b
    JOIN customer c ON b.cus_id = c.cus_id
    JOIN `type` t ON b.type_of_work_id = t.type_id
    JOIN type_of_work tow ON tow.type_of_work_id = b.type_of_work_id
    JOIN photographer p ON p.photographer_id = b.photographer_id
    JOIN submit sub ON sub.booking_id = b.booking_id
    JOIN review r ON r.booking_id = b.booking_id
    WHERE b.photographer_id = ?
      AND b.booking_confirm_status = '3'
      AND b.booking_pay_status = '5'
      AND sub.submit_details IS NOT NULL
      AND r.review_level IS NOT NULL 
    ORDER BY b.booking_id ASC";

$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("i", $id_photographer);
$stmt1->execute();
$resultBooking = $stmt1->get_result();

// Query to retrieve deposit payments
$sql2 = "
    SELECT pay.*, 
           (b.booking_price - (b.booking_price * 0.30)) AS payment_price, 
           (b.booking_price * 0.30) AS deposit_price 
    FROM pay 
    JOIN booking b ON pay.booking_id = b.booking_id 
    JOIN customer c ON b.cus_id = c.cus_id 
    WHERE b.photographer_id = ? 
      AND b.booking_confirm_status = '3'
      AND b.booking_pay_status = '5'
      AND pay.pay_status = '0' 
    ORDER BY b.booking_id ASC";

$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $id_photographer);
$stmt2->execute();
$resultPay0 = $stmt2->get_result();

// Query to retrieve full payments
$sql3 = "
    SELECT pay.*, 
           (b.booking_price - (b.booking_price * 0.30)) AS payment_price, 
           (b.booking_price * 0.30) AS deposit_price 
    FROM pay 
    JOIN booking b ON pay.booking_id = b.booking_id 
    JOIN customer c ON b.cus_id = c.cus_id 
    WHERE b.photographer_id = ? 
      AND b.booking_confirm_status = '3'
      AND b.booking_pay_status = '5'
      AND pay.pay_status = '1' 
    ORDER BY b.booking_id ASC";

$stmt3 = $conn->prepare($sql3);
$stmt3->bind_param("i", $id_photographer);
$stmt3->execute();
$resultPay1 = $stmt3->get_result();

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

        .table th:nth-child(7),
        .table td:nth-child(7) {
            width: 400px;
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
        .table th:nth-child(6),
        .table td:nth-child(2),
        .table td:nth-child(3),
        .table td:nth-child(4),
        .table td:nth-child(5),
        .table td:nth-child(6) {
            width: 180px;
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
                                <a href="bookingListWaittingForApproval.php" class="dropdown-item">รายการจองที่รออนุมัติ</a>
                                <a href="bookingListApproved.php" class="dropdown-item">รายการจองที่อนุมัติแล้ว</a>
                                <a href="bookingListConfirmDeposit.php" class="dropdown-item">รายการจองที่รอตรวจสอบการชำระ</a>
                                <a href="bookingListSend.php" class="dropdown-item">รายการจองที่ต้องส่งงาน</a>
                                <a href="bookingListFinish.php" class="dropdown-item active">รายการจองที่เสร็จสิ้นแล้ว</a>
                                <a href="bookingListNotApproved.php" class="dropdown-item">รายการจองที่ไม่อนุมัติ</a>
                            </div>
                        </div>
                        <a href="report.php" class="nav-item nav-link">รายงาน</a>
                        <a href="dashboard.php" class="nav-item nav-link">สถิติ</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle bg-dark" data-bs-toggle="dropdown">โปรไฟล์</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="profile.php" class="dropdown-item">โปรไฟล์</a>
                                <a href="editProfile.php" class="dropdown-item">แก้ไขข้อมูลส่วนตัว</a>
                                <!-- <a href="about.php" class="dropdown-item">เกี่ยวกับ</a>
                                <a href="contact.php" class="dropdown-item">ติดต่อ</a> -->
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
            <div class="col-md-5 p-5 mt-lg-5">
                <br><br>
                <h1 class="display-7 animated fadeIn mb-1 text-white f text-md-end">รายการจองที่ต้องส่งงาน</h1>
                <h1 class="display-9 animated fadeIn mb-1 text-white f text-md-end">ของคุณ</h1>
            </div>
        </div>
    </div>
    <!-- content -->
    <div class="bg-white">
        <div class="container bg-white" style="min-height: 662px"><br>
            <h1 class="text-center f">รายการจองคิวที่เสร็จสิ้นแล้ว</h1>
            <div class="table-responsive mt-3">
                <table class="table bg-white table-hover table-bordered-3">
                    <thead>
                        <!-- <tr>
                            <th colspan="10" class="table-heading text-center bg-white">รายการจองคิวช่างภาพ</th>
                        </tr> -->
                        <tr>
                            <th scope="col">ประเภทงาน</th>
                            <th scope="col">วันที่เริ่มงาน</th>
                            <th scope="col">เวลาเริ่มงาน</th>
                            <th scope="col">ราคาจ่าย</th>
                            <th scope="col">ราคามัดจำ</th>
                            <th scope="col">ลิงก์ส่งงาน</th>
                            <th scope="col">ดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($resultBooking->num_rows > 0) {
                            while ($rowBooking = $resultBooking->fetch_assoc()) {
                                if (isset($rowBooking['booking_id'])) {
                                    // echo $rowBooking['pay_status'];
                        ?>
                                    <tr>
                                        <td><?php echo $rowBooking['type_work']; ?></td>
                                        <td><?php echo $rowBooking['booking_start_date']; ?></td>
                                        <td><?php echo $rowBooking['booking_start_time']; ?></td>
                                        <td><?php echo $rowBooking['booking_price']; ?></td>
                                        <td><?php echo $rowBooking['deposit_price']; ?></td>
                                        <td><?php echo '<a href="' . $rowBooking['submit_details'] . '" target="_blank">ดูไดร์ฟส่งงาน</a>'; ?></td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#details<?php echo $rowBooking['booking_id']; ?>">ดูเพิ่มเติม</button>
                                            <button type="button" class="btn btn-warning btn-sm me-3" data-bs-toggle="modal" data-bs-target="#review<?php echo $rowBooking['booking_id']; ?>">ดูการรีวิว</button>
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
                                                <div class="modal-body" style="height: auto;">
                                                    <div class="container-md">
                                                        <div class="row">
                                                            <div class="col-md-6 container-fluid">
                                                                <div class="card">
                                                                    <div class="mt-3 mb-3 ms-3 me-3">
                                                                        <div class="col-md-12 ms-3">
                                                                            <h6 class="f mb-3">ข้อมูลช่างภาพ</h6>
                                                                            <div class="col-12">
                                                                                <div class="">
                                                                                    <span style="color: black; margin-right: 5px;font-size: 18px;">ชื่อ-นามสกุล : <?php echo  $rowBooking['photographer_prefix'] . '' . $rowBooking['photographer_name'] . ' ' . $rowBooking['photographer_surname']; ?></span>
                                                                                </div>
                                                                                <div class="mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">เบอร์โทรศัพท์มือถือ : <?php echo  $rowBooking['photographer_tell']; ?></span>
                                                                                </div>
                                                                                <div class=" mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">อีเมล : <?php echo  $rowBooking['photographer_email']; ?></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="card mt-3">
                                                                    <div class="mt-3 mb-3 ms-3 me-3">
                                                                        <div class="col-md-12 ms-3">
                                                                            <h6 class="f mb-3">ข้อมูลการจองของคุณ</h6>
                                                                            <div class="col-12">
                                                                                <div class="row">
                                                                                    <div class="col-12 mt-2">
                                                                                        <span style="color: black; margin-right: 5px;font-size: 18px;">ชื่อ-นามสกุล : <?php echo  $rowBooking['cus_prefix'] . '' . $rowBooking['cus_name'] . ' ' . $rowBooking['cus_surname']; ?></span>
                                                                                    </div>
                                                                                    <div class="col-12 mt-2">
                                                                                        <?php if ($rowBooking['booking_start_date'] == $rowBooking['booking_end_date']): ?>
                                                                                            <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                                                                วันที่จอง : <?php echo $rowBooking['booking_start_date']; ?>
                                                                                            </span>
                                                                                        <?php else: ?>
                                                                                            <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                                                                วันที่จอง : <?php echo $rowBooking['booking_start_date'] . '  ถึง  ' . $rowBooking['booking_end_date']; ?>
                                                                                            </span>
                                                                                        <?php endif; ?>
                                                                                    </div>
                                                                                    <div class="col-12 mt-2">
                                                                                        <?php
                                                                                        $startTime = new DateTime($rowBooking['booking_start_time']);
                                                                                        $endTime = new DateTime($rowBooking['booking_end_time']);

                                                                                        $formattedStartTime = $startTime->format('H:i');
                                                                                        $formattedEndTime = $endTime->format('H:i');
                                                                                        ?>

                                                                                        <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                                                            เวลา : <?php echo $formattedStartTime . ' น.' . '  -  ' . $formattedEndTime . ' น.'; ?>
                                                                                        </span>
                                                                                    </div>
                                                                                    <div class="col-12 mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">สถานที่ : <?php echo  $rowBooking['booking_location']; ?></span></div>
                                                                                    <div class="col-12 mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">ประเภทงาน : <?php echo  $rowBooking['type_work']; ?></span> </div>
                                                                                    <div class="col-12 mt-2"><span style="color: black; margin-right: 5px; font-size: 18px; overflow-wrap: break-word;">คำอธิบาย : <?php echo $rowBooking['booking_details']; ?></span></div>
                                                                                    <div class="col-12 mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">ราคาจ่าย : <?php echo  $rowBooking['booking_price'] . ' บาท'; ?></span></div>
                                                                                    <div class="col-12 mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">เบอร์โทรศัพท์มือถือ : <?php echo  $rowBooking['cus_tell']; ?></span></div>
                                                                                    <div class="col-12 mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">อีเมล : <?php echo  $rowBooking['cus_email']; ?></span></div>
                                                                                    <div class="col-12 mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">วันที่บันทึก : <?php echo  $rowBooking['booking_date']; ?></span> </div>
                                                                                    <div class="col-12 mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">สถานะการชำระ : <?php echo ($rowBooking['booking_pay_status'] == '2') ? 'รอชำระเงิน' : (($rowBooking['booking_pay_status'] == '4') ? 'ชำระเงินแล้วรอตรวจสอบ' : 'ตรวจสอบแล้ว'); ?></span></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 container-fluid mb-3">
                                                                <div class="card" style="height: auto;">
                                                                    <div class="mt-3 mb-3 ms-3 me-3">
                                                                        <div class="col-md-12 ms-3">
                                                                            <h6 class="f mb-3">ข้อมูลการชำระค่ามัดจำ</h6>
                                                                            <?php
                                                                            if ($rowPay0 = $resultPay0->fetch_assoc()) :
                                                                            ?>
                                                                                <div class="col-12 mt-2">
                                                                                    <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                                                        ค่ามัดจำที่จ่าย : <?php echo $rowPay0['deposit_price'] . ' บาท'; ?>
                                                                                    </span>
                                                                                </div>
                                                                                <div class="col-12 mt-2">
                                                                                    <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                                                        หลักฐานการชำระเงิน :
                                                                                        <?php
                                                                                        if (empty($rowPay0['pay_slip'])) {
                                                                                            echo 'คุณชำระเป็นเงินสด';
                                                                                        } else {
                                                                                            echo '<a href="../img/slip/' . $rowPay0['pay_slip'] . '" target="_blank">ดูหลักฐานการชำระเงิน</a>';
                                                                                        }
                                                                                        ?>
                                                                                    </span>
                                                                                </div>
                                                                                <div class="col-12 mt-2">
                                                                                    <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                                                        วันที่ชำระ : <?php echo $rowPay0['pay_date']; ?>
                                                                                    </span>
                                                                                </div>
                                                                                <div class="col-12 mt-2">
                                                                                    <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                                                        เวลาที่ชำระ : <?php echo $rowPay0['pay_time'] . ' น.'; ?>
                                                                                    </span>
                                                                                </div>
                                                                            <?php endif; ?>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="card mt-3" style="height: auto;">
                                                                    <div class="mt-3 mb-3 ms-3 me-3">
                                                                        <div class="col-md-12 ms-3">
                                                                            <h6 class="f mb-3">ข้อมูลการชำระเงิน</h6>
                                                                            <?php
                                                                            if ($rowPay1 = $resultPay1->fetch_assoc()) :
                                                                            ?>
                                                                                <div class="col-12 mt-2">
                                                                                    <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                                                        จำนวนเงินที่ชำระ : <?php echo $rowPay1['payment_price'] . ' บาท'; ?>
                                                                                    </span>
                                                                                </div>
                                                                                <div class="col-12 mt-2">
                                                                                    <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                                                        หลักฐานการชำระเงิน :
                                                                                        <?php
                                                                                        if (empty($rowPay1['pay_slip'])) {
                                                                                            echo 'คุณชำระเป็นเงินสด';
                                                                                        } else {
                                                                                            echo '<a href="../img/slip/' . $rowPay1['pay_slip'] . '" target="_blank">ดูหลักฐานการชำระเงิน</a>';
                                                                                        }
                                                                                        ?>
                                                                                    </span>
                                                                                </div>
                                                                                <div class="col-12 mt-2">
                                                                                    <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                                                        วันที่ชำระ : <?php echo $rowPay1['pay_date']; ?>
                                                                                    </span>
                                                                                </div>
                                                                                <div class="col-12 mt-2">
                                                                                    <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                                                        เวลาที่ชำระ : <?php echo $rowPay1['pay_time'] . ' น.'; ?>
                                                                                    </span>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="card mt-3" style="height: auto;">
                                                                    <div class="mt-3 mb-3 ms-3 me-3">
                                                                        <div class="col-md-12 ms-3">
                                                                            <h6 class="f mb-3">ข้อมูลการส่งมอบงาน</h6>
                                                                            <div class="col-12 mt-2">
                                                                                <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                                                    ไดฟ์ส่งงาน : <?php echo '<a href="' . $rowBooking['submit_details'] . '" target="_blank">ดูไดร์ฟส่งงาน</a>'; ?>
                                                                                </span>
                                                                            </div>
                                                                            <div class="col-12 mt-2">
                                                                                <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                                                    วันที่ส่งงาน : <?php echo $rowBooking['submit_date']; ?>
                                                                                </span>
                                                                            </div>
                                                                            <div class="col-12 mt-2">
                                                                                <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                                                    เวลาที่ส่งงาน : <?php echo $rowBooking['submit_time'] . ' น.'; ?>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-center mt-3">
                                                            <button type="button" class="btn" style="background-color:gray; color:#fff; width: 150px; height:45px;" data-bs-dismiss="modal">ปิด</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="review<?php echo $rowBooking['booking_id']; ?>" tabindex="-1" aria-labelledby="reviewLabel<?php echo $rowBooking['booking_id']; ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="reviewLabel<?php echo $rowBooking['booking_id']; ?>"><b><i class="fa-solid fa-star"></i>&nbsp;&nbsp;รีวิวโดย คุณ<?php echo $rowBooking['cus_name'] . ' ' . $rowBooking['cus_surname']; ?></b></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body" style="height: auto;">
                                                    <div class="mt-3 container-md">
                                                        <div class="mt-3 col-md-10 container-fluid">
                                                            <div class="col-12 mt-3">
                                                                <div class="row">
                                                                    <div class="col-4">
                                                                        <h6 class="f mt-2">ข้อมูลคะแนนความพึงพอใจ</h6>
                                                                    </div>
                                                                    <div class="col-8 mt-1">
                                                                        <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                                            <?php
                                                                            $reviewLevel = $rowBooking['review_level'];
                                                                            for ($i = 1; $i <= 5; $i++) {
                                                                                if ($i <= $reviewLevel) {
                                                                                    echo '<i class="fas fa-star" style="color: gold; margin-right: 2px;"></i>'; // ดาวเต็มสีทอง
                                                                                } else {
                                                                                    echo '<i class="far fa-star" style="color: gold; margin-right: 2px;"></i>'; // ดาวว่างเปล่าสีทอง
                                                                                }
                                                                            }
                                                                            $satisfactionText = '';
                                                                            switch ($reviewLevel) {
                                                                                case 1:
                                                                                    $satisfactionText = 'ไม่พอใจอย่างยิ่ง';
                                                                                    break;
                                                                                case 2:
                                                                                    $satisfactionText = 'ไม่พอใจ';
                                                                                    break;
                                                                                case 3:
                                                                                    $satisfactionText = 'ปานกลาง';
                                                                                    break;
                                                                                case 4:
                                                                                    $satisfactionText = 'พอใจ';
                                                                                    break;
                                                                                case 5:
                                                                                    $satisfactionText = 'พอใจอย่างยิ่ง';
                                                                                    break;
                                                                                default:
                                                                                    $satisfactionText = 'ไม่มีข้อมูลการรีวิว';
                                                                            }
                                                                            echo ' - ' . $satisfactionText;
                                                                            ?>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 mt-3">
                                                                <div class="row">
                                                                    <div class="col-4">
                                                                        <h6 class="f mt-2">ข้อมูลคำอธิบาย</h6>
                                                                    </div>
                                                                    <div class="col-8">
                                                                        <div class="col-12 mt-1"><span style="color: black; margin-right: 5px;font-size: 18px;"><?php echo $rowBooking['review_caption']; ?></span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 mt-3">
                                                                <div class="row">
                                                                    <div class="col-4">
                                                                        <h6 class="f mt-2">วันที่และเวลาที่บันทึก</h6>
                                                                    </div>
                                                                    <div class="col-8">
                                                                        <div class="col-12 mt-1"><span style="color: black; margin-right: 5px;font-size: 18px;"><?php echo $rowBooking['review_date'] . ' เวลา ' . $rowBooking['review_time'] . ' น.'; ?></span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="booking_id" value="<?php echo $rowBooking['booking_id']; ?>">
                                                    <div class="modal-footer mt-5 justify-content-center">
                                                        <div class="col-md-12 text-center">
                                                            <button type="button" class="btn" style="background-color:gray; color:#fff; width: 150px; height:45px;" data-bs-dismiss="modal">ปิด</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                        <?php
                                }
                            }
                        } else {
                            echo "<tr><td  colspan='7' >ไม่พบข้อมูลรายการจองที่เสร็จสิ้นแล้ว</td></tr>";
                        }

                        ?>
                    </tbody>
                </table>
            </div><!-- Footer Start --><br><br>
            <div class="container-fluid text-gry wow fadeIn">
                <div class="copyright">
                    &copy; <a class="border-bottom text-dark">2024 Photo Match</a>, All Right Reserved.
                </div>
            </div>
            <!-- Footer End -->
        </div>
    </div>

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

    <!-- Template Javascript -->
    <script src="../js/main.js"></script>
    <script>
        // Mock data for charts
        const overviewData = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [{
                label: 'Total Visits',
                backgroundColor: 'rgb(54, 162, 235)',
                borderColor: 'rgb(54, 162, 235)',
                data: [1000, 1500, 2000, 1800, 2500, 2200, 3000],
            }]
        };

        const userData = {
            labels: ['Admin', 'Photographer', 'Customer'],
            datasets: [{
                label: 'User Type',
                backgroundColor: ['#FF5733', '#FFC300', '#36A2EB'],
                borderColor: ['#FF5733', '#FFC300', '#36A2EB'],
                data: [500, 800, 1200],
            }]
        };

        // Render charts
        const overviewChartCtx = document.getElementById('overviewChart').getContext('2d');
        const overviewChart = new Chart(overviewChartCtx, {
            type: 'line',
            data: overviewData,
        });

        const userChartCtx = document.getElementById('userChart').getContext('2d');
        const userChart = new Chart(userChartCtx, {
            type: 'bar',
            data: userData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var startDateInput = document.getElementById('start_date');
            var endDateInput = document.getElementById('end_date');

            // Set the min attribute for the start date to today
            startDateInput.min = new Date().toISOString().split('T')[0];

            // Adjust end date based on start date selection
            startDateInput.addEventListener('change', function() {
                // Set min value for end date as the greater of today or start date
                endDateInput.min = startDateInput.value;
                if (new Date(endDateInput.value) < new Date(startDateInput.value)) {
                    endDateInput.value = startDateInput.value;
                }
            });

            // Set initial min value for end date
            var today = new Date().toISOString().split('T')[0];
            endDateInput.min = startDateInput.value || today;

            // Prevent selecting a past date for the end date if it's set initially
            if (new Date(endDateInput.value) < new Date(today)) {
                endDateInput.value = today;
            }
        });
    </script>
    <script>
        function calculateEndTime() {
            // Get the start time input value
            const startTimeInput = document.getElementById('start_time');
            const endTimeInput = document.getElementById('end_time');
            const durationRadios = document.querySelectorAll('input[name="userIcon"]');

            if (startTimeInput.value) {
                // Get selected duration
                let hoursToAdd = 4; // Default to half-day

                durationRadios.forEach(radio => {
                    if (radio.checked) {
                        hoursToAdd = radio.value === 'full' ? 8 : 4;
                    }
                });

                // Create Date objects for the start time and the end time
                const startTime = new Date(`1970-01-01T${startTimeInput.value}:00`);
                const endTime = new Date(startTime.getTime() + hoursToAdd * 60 * 60 * 1000); // Add hours

                // Format the end time as HH:MM
                const hours = String(endTime.getHours()).padStart(2, '0');
                const minutes = String(endTime.getMinutes()).padStart(2, '0');
                const formattedEndTime = `${hours}:${minutes}`;

                // Set the value of the end time input
                endTimeInput.value = formattedEndTime;
            }
        }

        // Add event listener for duration radio buttons
        document.addEventListener('DOMContentLoaded', function() {
            const durationRadios = document.querySelectorAll('input[name="userIcon"]');
            durationRadios.forEach(radio => {
                radio.addEventListener('change', calculateEndTime);
            });
        });
    </script>
</body>

</html>