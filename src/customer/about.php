<?php
session_start();
include '../config_db.php';
require_once '../popup.php';

$sql = "SELECT * FROM `information`";
$resultInfo = $conn->query($sql);
$rowInfo = $resultInfo->fetch_assoc();

if (isset($_SESSION['cus_login'])) {
    $email = $_SESSION['cus_login'];
    $sql = "SELECT * FROM customer WHERE cus_email LIKE '$email'";
    $resultCus = $conn->query($sql);
    $rowCus = $resultCus->fetch_assoc();
    $id_cus = $rowCus['cus_id'];
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
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon text-primary"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto f">
                        <a href="index.php" class="nav-item nav-link">หน้าหลัก</a>
                        <a href="search.php" class="nav-item nav-link">ค้นหาช่างภาพ</a>
                        <a href="workings.php" class="nav-item nav-link">ผลงานช่างภาพ</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">รายการจองคิวช่างภาพ</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="bookingLists.php" class="dropdown-item">รายการจองคิวทั้งหมด</a>
                                <a href="payLists.php" class="dropdown-item ">รายการจองคิวที่ต้องชำระเงิน/ค่ามัดจำ</a>
                                <!-- <a href="reviewLists.php" class="dropdown-item">รายการจองคิวที่ต้องรีวิว</a> -->
                                <!-- <a href="bookingFinishedLists.php" class="dropdown-item">รายการจองคิวที่เสร็จสิ้นแล้ว</a> -->
                                <a href="bookingRejectedLists.php" class="dropdown-item">รายการจองคิวที่ถูกปฏิเสธ</a>
                            </div>
                        </div>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown">โปรไฟล์</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="profile.php" class="dropdown-item">โปรไฟล์</a>
                                <a href="about.php" class="dropdown-item active">เกี่ยวกับ</a>
                                <a href="contact.php" class="dropdown-item">ติดต่อ</a>
                                <a href="../index.php" class="dropdown-item">ออกจากระบบ</a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <!-- Navbar End -->

        <!-- Header Start -->
        <div class="container-fluid header bg-white p-0">
            <div class="row g-0 align-items-center flex-column-reverse flex-md-row">
                <div class="col-md-6 p-5 mt-lg-5">
                    <h1 class="display-5 animated fadeIn mb-2 f">About Us</h1>
                </div>
                <div class="col-md-6 animated fadeIn">
                    <img class="img-fluid" src="../img/graduation.jpg" alt="">
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- Header End -->

    <!-- About Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                    <div class="about-img position-relative overflow-hidden p-5 pe-0">
                        <img class="img-fluid w-100" src="../img/aboutImg.jpg">
                    </div>
                </div>
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                    <h1 class="mb-4 f">#1 แหล่งรวมช่างภาพที่มากประสบการณ์</h1>
                    <p class="mb-4">และท่านสามารถเลือกใช้บริการช่างภาพได้ตามความต้องการตามปัจจัยต่าง ๆ ได้แก่ ประเภทงาน ช่วงราคา ช่วงเวลา และสถานที่</p>
                    <p class="mb-4">แน่นอนว่าช่างภาพของเรานั้นมีคุณสมบัติที่ดี</p>
                    <p><i class="fa fa-check text-dark me-3"></i>มากประสบการณ์</p>
                    <p><i class="fa fa-check text-dark me-3"></i>มีเทคนิคการถ่าย</p>
                    <p><i class="fa fa-check text-dark me-3"></i>ผลงานดีมีคุณภาพ</p>
                    <a class="btn btn-dark py-3 px-5 mt-3" href="">เรียนรู้เพิ่มเติม</a>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->


     <!-- Dev Start -->
     <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h1 class="mb-3 f">คณะผู้พัฒนาเว็บไซต์</h1>
                <p>คณะผู้พัฒนาเว็บไซต์ Photo Match ที่ช่วยให้ลูกค้าที่ต้องการภาพถ่ายสามารถเข้ามาค้นหาช่างภาพที่มีความสามารถและทำการจองคิวได้ภายในเว็บไซต์เดียว</p>
            </div>
            <div class="row g-4 text-center mx-auto justify-content-center" style="max-width: 900px;">
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="team-item rounded overflow-hidden bg-white">
                        <div class="position-relative">
                            <img class="img-fluid" src="../img/dev2.jpg" alt="">
                            <!-- <div class="position-absolute start-50 top-100 translate-middle d-flex align-items-center">
                                <a class="btn btn-square mx-1" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-square mx-1" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-square mx-1" href=""><i class="fab fa-instagram"></i></a>
                            </div> -->
                        </div>
                        <div class="text-center p-4 mt-3">
                            <h5 class="fw-bold mb-0 f">นางสาวนันทิยา นารินรักษ์</h5>
                            <small>Front End Developer</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="team-item rounded overflow-hidden bg-white">
                        <div class="position-relative">
                            <img class="img-fluid" src="../img/dev3.jpg" alt="">
                            <!-- <div class="position-absolute start-50 top-100 translate-middle d-flex align-items-center">
                                <a class="btn btn-square mx-1" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-square mx-1" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-square mx-1" href=""><i class="fab fa-instagram"></i></a>
                            </div> -->
                        </div>
                        <div class="text-center p-4 mt-3">
                            <h5 class="fw-bold mb-0 f">นางสาวพีรดา แสนเสร็จ</h5>
                            <small>Full Stack Developer</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Dev End -->

    <!-- Footer Start -->
    <!--<div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-white mb-4 f">ติดต่อ</h5>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>172/20 ม.6 ถ.ศรีจันทร์ ต.ในเมือน อ.เมือง จ.ขอนแก่น 40000</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>096-825-4382</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>photomatch@gmail.com</p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-white mb-4 f">ลิงก์ด่วน</h5>
                    <a class="btn btn-link text-white-50" href="about.php">เกี่ยวกับพวกเรา</a>
                    <a class="btn btn-link text-white-50" href="contact.php">ติดต่อเรา</a>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="#">2024 Photo Match</a>, All Right Reserved.
                    </div>
                </div>
            </div>
        </div>
    </div>-->
    <!-- Footer End -->
    <div class="container-fluid bg-dark text-white-50 footer wow fadeIn">
        <div class="copyright">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; <a class="border-bottom" href="#">2024 Photo Match</a>, All Right Reserved.
                </div>
            </div>
        </div>
    </div>

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