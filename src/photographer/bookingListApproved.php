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
            width: 100px;
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
            width: 200px;
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
        <div class="container-fluid nav-bar bg-transparent">
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
                                <a href="bookingListAll.php" class="dropdown-item">รายการจองทั้งหมด</a>
                                <a href="bookingListWaittingForApproval.php" class="dropdown-item">รายการจองที่รออนุมัติ</a>
                                <a href="bookingListApproved.php" class="dropdown-item active">รายการจองที่อนุมัติแล้ว</a>
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
                <h1 class="display-7 animated fadeIn mb-1 text-white f text-md-end">รายการจองที่อนุมัติแล้ว</h1>
                <h1 class="display-9 animated fadeIn mb-1 text-white f text-md-end">ของคุณ</h1>
            </div>
        </div>
    </div>
    <!-- Header End -->
    <div class="center container mt-5" style="height: 520px;">
        <h1 class="footer-title text-center f mt-3">ตารางรายการจองที่อนุมัติแล้ว</h1>
        <div class="row justify-content-end">
            <div class="col-md-4">
                <button type="button" onclick="window.location.href='bookingListAll.php'" class="btn btn-outline-dark">ทั้งหมด</button>
                <button type="button" onclick="window.location.href='bookingListWaittingForApproval.php'" class="btn btn-outline-dark">รออนุมัติ</button>
                <button type="button" onclick="window.location.href='bookingListApproved.php'" class="btn btn-outline-dark active">อนุมัติแล้ว</button>
                <button type="button" onclick="window.location.href='bookingListNotApproved.php'" class="btn btn-outline-dark">ไม่อนุมัติ</button>
            </div>
        </div>
        <div class="table-responsive mt-1">
            <table class="table bg-white table-hover table-bordered-3">
                <thead>
                    <tr>
                        <th scope="col">รหัส</th>
                        <th scope="col">ชื่อ</th>
                        <th scope="col">นามสกุล</th>
                        <th scope="col">เบอร์โทรศัพท์</th>
                        <th scope="col">อีเมล</th>
                        <th scope="col">ดำเนินการ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>John</td>
                        <td>Doe</td>
                        <td>0812345678</td>
                        <td>john@example.com</td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#details">ดูเพิ่มเติม</button>
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edite">แก้ไข</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="row justify-content-center mt-3 container-center text-center">
            <div class="col-md-12"><br><br>
                <button onclick="window.history.back();" class="btn btn-danger" style="width: 150px; height: 45px;">ย้อนกลับ</button>
            </div>
        </div>
    </div>

    <div class="modal fade" id="details" tabindex="-1" aria-labelledby="detailsLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsLabel"><b><i class="fas fa-clipboard-list"></i>&nbsp;&nbsp;รายละเอียดการจองคิว</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="height: 560px;">
                    <div class="mt-2 container-md">
                        <div class="mt-3 col-md-12 container-fluid">
                            <div class="col-12">
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <label for="prefix " style="font-weight: bold; display: flex; align-items: center;">
                                            <span style="color: black; margin-right: 5px;font-size: 13px; "> คำนำหน้า</span>
                                        </label>
                                        <select class="form-select border-1 mt-1">
                                            <option value="1">นาย</option>
                                            <option value="2">นางสาว</option>
                                            <option value="3">นาง</option>
                                        </select>
                                    </div>
                                    <div class="col-5">
                                        <label for="name" style="font-weight: bold; display: flex; align-items: center;">
                                            <span style="color: black; margin-right: 5px;font-size: 13px;">ชื่อ</span>
                                        </label>
                                        <input type="text" name="name" class="form-control mt-1" placeholder="กรุณากรอกชื่อ">
                                    </div>
                                    <div class="col-5">
                                        <label for="surname" style="font-weight: bold; display: flex; align-items: center;">
                                            <span style="color: black; font-size: 13px;">นามสกุล</span>
                                        </label>
                                        <input type="text" name="surname" class="form-control mt-1" placeholder="กรุณากรอกนามสกุล">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-3">
                                <div class="row">
                                    <div class="col-md-4 text-center">
                                        <label for="booking-start-date" style="font-weight: bold; display: flex; align-items: center;">
                                            <span style="color: black; margin-right: 5px;font-size: 13px;">วันที่เริ่มจอง</span>
                                        </label>
                                        <input type="date" name="booking-start-date" class="form-control mt-1" style="resize: none;">
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <label for="booking-start-time" style="font-weight: bold; display: flex; align-items: center;">
                                            <span style="color: black; margin-right: 5px;font-size: 13px;">เวลาเริ่มงาน</span>
                                        </label>
                                        <input type="time" name="booking-start-time" class="form-control mt-1" style="resize: none;">
                                    </div>

                                    <div class="col-md-4 text-center">
                                        <label for="booking-end-date" style="font-weight: bold; display: flex; align-items: center;">
                                            <span style="color: black; margin-right: 5px;font-size: 13px;">วันที่สิ้นสุดการจอง</span>
                                        </label>
                                        <input type="date" name="booking-end-date" class="form-control mt-1" style="resize: none;">
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <label for="booking-end-time" style="font-weight: bold; display: flex; align-items: center;">
                                            <span style="color: black; margin-right: 5px;font-size: 13px;">เวลาสิ้นสุด</span>
                                        </label>
                                        <input type="time" name="booking-end-time" class="form-control mt-1" style="resize: none;">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-3">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <label for="location" style="font-weight: bold; display: flex; align-items: center;">
                                            <span style="color: black; margin-right: 5px;font-size: 13px;">สถานที่</span>

                                        </label>
                                        <input type="text" name="location" class="form-control mt-1" placeholder="กรุณากรอกสถานที่" style="resize: none;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3 text-center">
                                <label for="Information-caption" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px;font-size: 13px;">คำอธิบาย</span>
                                </label>
                                <textarea name="Information-caption" class="form-control mt-1" placeholder="กรุณากรอกคำอธิบาย" style="resize: none; height: 100px;"></textarea>
                            </div>
                            <div class="col-12">
                                <div class="row mt-3">
                                    <div class="col-5">
                                        <label for="mobile" style="font-weight: bold; display: flex; align-items: center;">
                                            <span style="color: black; margin-right: 5px;font-size: 13px;">เบอร์โทรศัพท์มือถือ</span>
                                        </label>
                                        <input type="text" name="mobile" class="form-control mt-1" placeholder="กรุณากรอกเบอร์โทร" style="resize: none;">
                                    </div>
                                    <div class="col-5 text-center">
                                        <label for="email" style="font-weight: bold; display: flex; align-items: center;">
                                            <span style="color: black; margin-right: 5px;font-size: 13px;">อีเมล</span>
                                        </label>
                                        <input type="email" name="email" class="form-control mt-1" placeholder="กรุณากรอกอีเมล" style="resize: none;">
                                    </div>
                                    <div class="col-2">
                                        <label for="date-saved" style="font-weight: bold; display: flex; align-items: center;">
                                            <span style="color: black; margin-right: 5px;font-size: 13px;">วันที่บันทึก</span>
                                        </label>
                                        <input type="date" name="date-saved" class="form-control mt-1" style="resize: none;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edite" tabindex="-1" aria-labelledby="editeLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editeLabel"><b><i class="fas fa-clipboard-list"></i>&nbsp;&nbsp;รายละเอียดการจองคิว</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="height: 560px;">
                    <div class="mt-2 container-md">
                        <div class="mt-3 col-md-12 container-fluid">
                            <div class="col-12">
                                <div class="row mt-2">
                                    <div class="col-2">
                                        <label for="prefix" style="font-weight: bold; display: flex; align-items: center;">
                                            <span style="color: black; margin-right: 5px;font-size: 13px; "> คำนำหน้า</span>
                                        </label>
                                        <select class="form-select border-1 mt-1">
                                            <option value="1">นาย</option>
                                            <option value="2">นางสาว</option>
                                            <option value="3">นาง</option>
                                        </select>
                                    </div>
                                    <div class="col-5">
                                        <label for="name" style="font-weight: bold; display: flex; align-items: center;">
                                            <span style="color: black; margin-right: 5px;font-size: 13px;">ชื่อ</span>
                                        </label>
                                        <input type="text" name="name" class="form-control mt-1" placeholder="กรุณากรอกชื่อ">
                                    </div>
                                    <div class="col-5">
                                        <label for="surname" style="font-weight: bold; display: flex; align-items: center;">
                                            <span style="color: black; font-size: 13px;">นามสกุล</span>
                                        </label>
                                        <input type="text" name="surname" class="form-control mt-1" placeholder="กรุณากรอกนามสกุล">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-3">
                                <div class="row">
                                    <div class="col-md-4 text-center">
                                        <label for="booking-start-date" style="font-weight: bold; display: flex; align-items: center;">
                                            <span style="color: black; margin-right: 5px;font-size: 13px;">วันที่เริ่มจอง<span style="color: red;">*</span></span>
                                        </label>
                                        <input type="date" name="booking-start-date" class="form-control mt-1" style="resize: none;">
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <label for="booking-start-time" style="font-weight: bold; display: flex; align-items: center;">
                                            <span style="color: black; margin-right: 5px;font-size: 13px;">เวลาเริ่มงาน<span style="color: red;">*</span></span>
                                        </label>
                                        <input type="time" name="booking-start-time" class="form-control mt-1" style="resize: none;">
                                    </div>

                                    <div class="col-md-4 text-center">
                                        <label for="booking-end-date" style="font-weight: bold; display: flex; align-items: center;">
                                            <span style="color: black; margin-right: 5px;font-size: 13px;">วันที่สิ้นสุดการจอง<span style="color: red;">*</span></span>
                                        </label>
                                        <input type="date" name="booking-end-date" class="form-control mt-1" style="resize: none;">
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <label for="booking-end-time" style="font-weight: bold; display: flex; align-items: center;">
                                            <span style="color: black; margin-right: 5px;font-size: 13px;">เวลาสิ้นสุด<span style="color: red;">*</span></span>
                                        </label>
                                        <input type="time" name="booking-end-time" class="form-control mt-1" style="resize: none;">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-3">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <label for="location" style="font-weight: bold; display: flex; align-items: center;">
                                            <span style="color: black; margin-right: 5px;font-size: 13px;">สถานที่<span style="color: red;">*</span></span>

                                        </label>
                                        <input type="text" name="location" class="form-control mt-1" placeholder="กรุณากรอกสถานที่" style="resize: none;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3 text-center">
                                <label for="Information-caption" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px;font-size: 13px;">คำอธิบาย<span style="color: red;">*</span></span>
                                </label>
                                <textarea name="Information-caption" class="form-control mt-1" placeholder="กรุณากรอกคำอธิบาย" style="resize: none; height: 100px;"></textarea>
                            </div>
                            <div class="col-12">
                                <div class="row mt-3">
                                    <div class="col-5">
                                        <label for="mobile" style="font-weight: bold; display: flex; align-items: center;">
                                            <span style="color: black; margin-right: 5px;font-size: 13px;">เบอร์โทรศัพท์มือถือ</span>
                                        </label>
                                        <input type="text" name="mobile" class="form-control mt-1" placeholder="กรุณากรอกเบอร์โทร" style="resize: none;">
                                    </div>
                                    <div class="col-5 text-center">
                                        <label for="email" style="font-weight: bold; display: flex; align-items: center;">
                                            <span style="color: black; margin-right: 5px;font-size: 13px;">อีเมล</span>
                                        </label>
                                        <input type="email" name="email" class="form-control mt-1" placeholder="กรุณากรอกอีเมล" style="resize: none;">
                                    </div>
                                    <div class="col-2">
                                        <label for="date-saved" style="font-weight: bold; display: flex; align-items: center;">
                                            <span style="color: black; margin-right: 5px;font-size: 13px;">วันที่บันทึก</span>
                                        </label>
                                        <input type="date" name="date-saved" class="form-control mt-1" style="resize: none;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-3">
                            <div class="col-md-12 text-center">
                                <!-- ตำแหน่งสำหรับปุ่ม "บันทึกการแก้ไข" -->
                                <button id="saveButton" class="btn btn-danger me-3" style="width: 150px; height:45px;">ลบข้อมูลการจอง</button>
                                <button id="saveButton" class="btn btn-primary" style="width: 150px; height:45px;">บันทึกการแก้ไข</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white-50 footer wow fadeIn">
        <div class="copyright">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; <a class="border-bottom" href="#">2024 Photo Match</a>, All Right Reserved.
                </div>
            </div>
        </div>
    </div>
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