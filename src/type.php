<?php
session_start();
include 'config_db.php';
require_once 'popup.php';

$sql = "SELECT * FROM `information`";
$resultInfo = $conn->query($sql);
$rowInfo = $resultInfo->fetch_assoc();

$sql = "SELECT * FROM `type`";
$resultType = $conn->query($sql);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Photo Match</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="icon" type="image/png" href="img/icon-logo.png">
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
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <!-- font awaysome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Athiti&family=Merriweather:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Athiti', sans-serif;
            overflow-x: hidden;
        }

        .f {
            font-family: 'Athiti', sans-serif;
        }

        .nav-bar {
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .navbar {
            padding: 0;

        }

        p {
            font-family: 'Athiti', sans-serif;
        }

        h1 {
            font-family: 'Athiti', sans-serif;
        }

        h2 {
            font-family: 'Athiti', sans-serif;
        }

        h3 {
            font-family: 'Athiti', sans-serif;
        }

        h4 {
            font-family: 'Athiti', sans-serif;
        }

        h5 {
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

        .nav-bar {
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .navbar {
            padding: 0;

        }



        .container {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .bgIndex {
            background-image: url('../img/bgIndex1.png');
            background-attachment: fixed;
            background-size: cover;
            /* เพิ่มการปรับแต่งในการขยับภาพตามต้องการ */
        }

        body {
            font-family: 'Athiti', sans-serif;
            background: #fff;
            overflow-x: hidden;
        }

        .f {
            font-family: 'Athiti', sans-serif;
        }

        .circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            position: relative;
        }

        .circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
        }

        body {
            font-family: 'Athiti', sans-serif;
            background: #f0f0f0;
            /* Change background color to gray */
            overflow-x: hidden;
        }
    </style>
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-dark" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->


    <!-- Navbar Start -->
    <div class="d-flex justify-content-center bg-primary" style="height: 90px;">
        <nav class="mt-3 navbar navbar-expand-lg navbar-dark bg-dark col-10">
            <a href="index.php" class="navbar-brand d-flex align-items-center text-center" style="height: 70px;">
                <img class="img-fluid" src="img/logo/<?php echo isset($rowInfo['information_icon']) ? $rowInfo['information_icon'] : ''; ?>" style="height: 30px;">
            </a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon text-primary"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto">
                    <a href="index.php" class="nav-item nav-link">หน้าหลัก</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown">รายการ</a>
                        <div class="dropdown-menu rounded-0 m-0">
                            <a href="search.php" class="dropdown-item">ค้นหาช่างภาพ</a>
                            <a href="type.php" class="dropdown-item active">ประเภทงาน</a>
                            <a href="workings.php" class="dropdown-item">ผลงานช่างภาพ</a>
                        </div>
                    </div>
                    <a href="about.php" class="nav-item nav-link">เกี่ยวกับ</a>
                    <a href="contact.php" class="nav-item nav-link">ติดต่อ</a>
                    <a href="login.php" class="nav-item nav-link">เข้าสู่ระบบ<i class="ms-1 fa-solid fa-right-to-bracket"></i></a>
                </div>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->
    <!-- Header Start -->
    <div class="container-fluid header bg-primary p-5" style="height: 346px;">
        <div class="row mt-5  align-items-center flex-column-reverse flex-md-row">
            <div class="mt-5 text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 450px;">
                <h1 class="mt-5 f " style="color: #fff;">ประเภทงาน</h1>
                <p style="font-size: 20px;color: #fff;">คุณสามารถเลือกดูผลงานช่างภาพตามประเภทงานนี้ได้เลย</p>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Category Start -->
    <div class="bg-white" style="min-height: 601px;">
        <div class="d-flex justify-content-center align-items-center" style="border-radius: 10px;">
            <div class="container-xxl">
                <div class="container">
                    <div class="row mt-5 justify-content-start">
                        <?php
                        if ($resultType->num_rows > 0) {
                            while ($rowType = $resultType->fetch_assoc()) {
                        ?>
                                <div class="col-lg-3 mt-4 mb-4 col-sm-6 wow pulse" data-wow-delay="0.1s">
                                    <a class="cat-item bg-light text-center" href="workings.php?type_id=<?php echo $rowType['type_id']; ?>">
                                        <div class="rounded p-4" style="font-size: 60.9px;">
                                            <img src="img/icon/<?php echo $rowType['type_icon']; ?>" style="height: 75px; width: 75px;">
                                            <h6 class="f mt-3"><?php echo $rowType['type_work']; ?></h6>
                                        </div>
                                    </a>
                                </div>
                        <?php
                            }
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category End -->

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


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-dark btn-lg-square back-to-top" style="background-color:#1E2045"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>