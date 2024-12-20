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

$sql2 = "SELECT b.*, 
                c.cus_prefix, 
                c.cus_name, 
                c.cus_surname, 
                c.cus_tell, 
                c.cus_email, 
                t.type_work, 
                (b.booking_price * 0.30) AS deposit_price,
                b.booking_pay_status
         FROM booking b
         JOIN customer c ON b.cus_id = c.cus_id
         JOIN `type` t ON b.type_of_work_id = t.type_id
         JOIN type_of_work tow ON tow.type_of_work_id = b.type_of_work_id
         WHERE b.photographer_id = $id_photographer
         AND b.booking_confirm_status = '1'
         AND b.booking_pay_status = '0'
";
$resultBooking = $conn->query($sql2);


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

        .table th:nth-child(8),
        .table td:nth-child(8) {
            width: 200px;
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
        .table th:nth-child(7),
        .table td:nth-child(2),
        .table td:nth-child(3),
        .table td:nth-child(4),
        .table td:nth-child(5),
        .table td:nth-child(6),
        .table td:nth-child(7) {
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
                                <a href="bookingListApproved.php" class="dropdown-item active">รายการจองที่อนุมัติแล้ว</a>
                                <a href="bookingListConfirmDeposit.php" class="dropdown-item">รายการจองที่รอตรวจสอบการชำระ</a>
                                <a href="bookingListSend.php" class="dropdown-item">รายการจองที่ต้องส่งงาน</a>
                                <a href="bookingListFinish.php" class="dropdown-item">รายการจองที่เสร็จสิ้นแล้ว</a>
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
                <h1 class="display-7 animated fadeIn mb-1 text-white f text-md-end">รายการจองที่อนุมัติแล้ว</h1>
                <h1 class="display-9 animated fadeIn mb-1 text-white f text-md-end">ของคุณ</h1>
            </div>
        </div>
    </div>
    <!-- Header End -->
    <div class="center container mt-5" style="height: 520px;">
        <h1 class="footer-title text-center f mt-3">ตารางรายการจองที่อนุมัติแล้ว</h1>
        <div class="row">
            <div class="col-md-4">
                <!-- <button type="button" onclick="window.location.href='bookingListAll.php'" class="btn btn-outline-dark">ทั้งหมด</button> -->
                <button type="button" onclick="window.location.href='bookingListWaittingForApproval.php'" class="btn btn-outline-dark">รออนุมัติ</button>
                <button type="button" onclick="window.location.href='bookingListApproved.php'" class="btn btn-outline-dark active">อนุมัติแล้ว</button>
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
                        <th scope="col">ราคา (บาท)</th>
                        <th scope="col">สถานะการชำระ</th>
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
                                    <td><?php echo $rowBooking['booking_price']; ?></td>
                                    <td>
                                        <?php
                                        if ($rowBooking['booking_pay_status'] == '0') {
                                            echo '<p class="mt-3">ยังไม่ชำระ</p>';
                                        } else if ($rowBooking['booking_pay_status'] == '1') {
                                            echo '<a href="bookingListConfirmDeposit.php">ชำระค่ามัดจำแล้ว</a>';
                                        } else if ($rowBooking['booking_pay_status'] == '3') {
                                            echo '<p>ชำระเงินแล้ว</p>';
                                        } else {
                                            echo '<p>สถานะไม่ถูกต้อง</p>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#details<?php echo $rowBooking['booking_id']; ?>">ดูเพิ่มเติม</button>
                                    </td>
                                </tr>
                                <!-- details -->
                                <div class="modal fade" id="details<?php echo $rowBooking['booking_id']; ?>" tabindex="-1" aria-labelledby="detailsLabel<?php echo $rowBooking['booking_id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="detailsLabel<?php echo $rowBooking['booking_id']; ?>"><b><i class="fas fa-clipboard-list"></i>&nbsp;&nbsp;รายละเอียดการจองคิวที่อนุมัติแล้ว</b></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                                <div class="modal-body" style="height: auto;">
                                                    <div class="container-md mb-5">
                                                        <div class="row">
                                                            <div class="col-10 container-fluid">
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
                                                                        <div class="col-12 mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">เบอร์โทรศัพท์มือถือ : <?php echo  $rowBooking['cus_tell']; ?></span></div>
                                                                        <div class="col-12 mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">อีเมล : <?php echo  $rowBooking['cus_email']; ?></span></div>
                                                                        <div class="col-12 mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">วันที่บันทึก : <?php echo  $rowBooking['booking_date']; ?></span> </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer justify-content-center">
                                                    <button type="button" class="btn" style="background-color:gray; color:#ffff; width: 150px; height:45px;" data-bs-dismiss="modal">ปิด</button>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                    <?php
                            }
                        }
                    } else {
                        echo "<tr><td colspan='8'>ไม่พบข้อมูลรายการที่อนุมัติ</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="row justify-content-center mt-2 container-center text-center">
            <div class="col-md-12">
                <button onclick="window.history.back();" class="btn mb-5 " style="background-color:gray; color:#ffff; width: 150px; height: 45px;">ย้อนกลับ</button>
            </div>
        </div>
    </div>

    <!-- Footer Start -->
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


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/wow/wow.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="../js/main.js"></script>
</body>

</html>