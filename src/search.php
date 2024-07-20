<?php
session_start();
include 'config_db.php';
require_once 'popup.php';

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

    <!-- Fancybox CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />

    <!-- font awaysome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.6/flatpickr.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Athiti&family=Merriweather:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Athiti', sans-serif;
            background: #F0F2F5;
        }

        .f {
            font-family: 'Athiti', sans-serif;
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

        .circle {
            width: 200px;
            height: 200px;
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

        .write-post-container {
            width: 100%;
            background: #fff;
            border-radius: 4px;
            padding: 20px;
            color: #626262;
        }

        .post-img {
            width: 100%;
            border-radius: 4px;
            margin-bottom: 5px;
        }

        .post-text {
            color: #626262;
            margin: 15px;
            font-size: 15px;
        }

        .bgIndex {
            background-image: url('../img/bgIndex3.png');
            background-attachment: fixed;
            background-size: cover;
            /* เพิ่มการปรับแต่งในการขยับภาพตามต้องการ */
        }
    </style>
</head>

<body>

    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar Start -->
    <div class="bgIndex mb-3" style="height: auto;">
        <!-- <div style="background-color: rgba(0, 41, 87, 0.6);"> -->
        <div class="d-flex justify-content-center">
            <nav class="mt-3 navbar navbar-expand-lg navbar-dark col-10">
                <a href="index.php" class="navbar-brand d-flex align-items-center text-center" style="height: 70px;">
                    <img class="img-fluid" src="../img/logo/<?php echo isset($rowInfo['information_icon']) ? $rowInfo['information_icon'] : ''; ?>" style="height: 30px;">
                </a>
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon text-primary"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto">
                        <a href="index.php" class="nav-item nav-link ">หน้าหลัก</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle  active" data-bs-toggle="dropdown">รายการ</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="search.php" class="dropdown-item active">ค้นหาช่างภาพ</a>
                                <a href="type.php" class="dropdown-item">ประเภทงาน</a>
                                <a href="workings.php" class="dropdown-item">ผลงงานช่างภาพ</a>
                            </div>
                        </div>
                        <a href="about.php" class="nav-item nav-link">เกี่ยวกับ</a>
                        <a href="contact.php" class="nav-item nav-link">ติดต่อ</a>
                        <a onclick="window.location.href='login.php'" class="nav-item nav-link">เข้าสู่ระบบ<i class="ms-1 fa-solid fa-right-to-bracket"></i></a>
                    </div>
                </div>
            </nav>
        </div>
        <!-- Navbar End -->




        <!-- Header Start -->
        <div class="container-fluid row g-0 align-items-center flex-column-reverse flex-md-row">
            <div class="col-md-6 p-5 mt-lg-5">
                <h1 class="display-5 animated fadeIn text-white mb-4 f">ค้นหาช่างภาพ</h1>
                <p class="text-white">คุณสามารถค้นหาช่างภาพตามความสนใจของคุณได้</p>
            </div>
        </div>
        <!-- Header End -->

        <!-- Search Start -->
        <form action="search.php" method="POST">
            <div class="mt-5 mb-5 wow fadeIn" style="background-color: rgba(250,250,250, 0.4);padding: 35px;" data-wow-delay="0.1s">
                <div class="container">
                    <div class="row flex-row g-2 align-items-center">
                        <h2 class="text-white f">ค้นหาช่างภาพ</h2>
                        <?php
                        $sql = "SELECT * FROM `type`";
                        $resultType = $conn->query($sql);
                        if ($resultType->num_rows > 0) {
                        ?>
                            <div class="col-md-3">
                                <select class="form-select border-0 py-3 ">
                                    <option selected>ประเภทงาน</option>
                                    <?php
                                    while ($rowType = $resultType->fetch_assoc()) {
                                    ?>
                                        <option value="<?php echo $rowType['type_work']; ?>"><?php echo $rowType['type_work']; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        <?php
                        }
                        ?>
                        <div class="col-md-2">
                            <input class="border-0 py-3" type="text" id="reat" placeholder="งบประมาณ (บาท)" style="border: none; outline: none; width:100%; border-radius: 5px;">
                        </div>
                        <div class="col-md-2">
                            <select class="form-select border-0 py-3">
                                <option selected>ช่วงเวลา</option>
                                <option value="1">เต็มวัน</option>
                                <option value="2">ครึ่งวัน</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select border-0 py-3">
                                <option selected>สถานที่</option>
                                <option value="กรุงเทพฯ">กรุงเทพฯ</option>
                                <option value="ภาคกลาง">ภาคกลาง</option>
                                <option value="ภาคใต้">ภาคใต้</option>
                                <option value="ภาคเหนือ">ภาคเหนือ</option>
                                <option value="ภาคตะวันออกเฉียงเหนือ">ภาคตะวันออกเฉียงเหนือ</option>
                                <option value="ภาคตะวันตก">ภาคตะวันตก</option>
                            </select>
                        </div>
                        <div class="col-2 mt-2">
                            <button type="submit" class="btn btn-primary border-0 w-100 py-3" name="search">ค้นหา</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- Search End -->


    <!-- Examples of work Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-0 gx-5 align-items-end">
                <div class="col-lg-6">
                    <div class="text-start mx-auto mb-5 wow slideInLeft" data-wow-delay="0.1s">
                        <h1 class="mb-3 f">ผลงานช่างภาพ</h1>
                        <p>คุณลองดูผลงานช่างภาพของเราสิ!!!</p>
                    </div>
                </div>
                <!-- <div class="col-lg-6 text-start text-lg-end wow slideInRight" data-wow-delay="0.1s">
                <ul class="nav nav-pills d-inline-flex justify-content-end mb-5">
                    <li class="nav-item me-2">
                        <a class="btn btn-outline-primary active" data-bs-toggle="pill" href="#tab-1">Featured</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="btn btn-outline-primary" data-bs-toggle="pill" href="#tab-2">For Sell</a>
                    </li>
                    <li class="nav-item me-0">
                        <a class="btn btn-outline-primary" data-bs-toggle="pill" href="#tab-3">For Rent</a>
                    </li>
                </ul>
            </div> -->
            </div>
            <div class="row g-4">
                <?php
                $sql = "SELECT 
                        po.portfolio_id, 
                        po.portfolio_photo, 
                        t.type_work,
                        t.type_icon,
                        p.photographer_id,
                        CAST( tow.type_of_work_rate_half  AS UNSIGNED) AS rate_half,
                        CAST( tow.type_of_work_rate_full AS UNSIGNED) AS rate_full,
                        p.photographer_name,
                        p.photographer_surname,
                        p.photographer_scope
                        FROM 
                        portfolio po
                        JOIN 
                        type_of_work tow ON po.type_of_work_id = tow.type_of_work_id 
                        JOIN 
                        photographer p ON p.photographer_id = tow.photographer_id
                        JOIN 
                        `type` t ON t.type_id = tow.type_id
                        ORDER BY po.portfolio_id DESC
                        LIMIT 6;
                        ";
                $resultPost = $conn->query($sql);
                if ($resultPost->num_rows > 0) {
                    while ($rowPost = $resultPost->fetch_assoc()) {
                ?>
                        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                            <div class="property-item rounded overflow-hidden">
                                <div class="position-relative overflow-hidden">
                                    <a><img class="img-fluid property-img" src="img/post/<?php echo explode(',', $rowPost['portfolio_photo'])[0]; ?>" alt=""></a>
                                    <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3"><?php echo $rowPost['type_work']; ?></div>
                                </div>
                                <div class="p-4 pb-0">
                                    <h5 class="text-dark mb-1"><i class="fa-solid fa-money-bill me-2"></i><?php echo $rate = $rowPost['rate_half'] == 0 ? $rowPost['rate_full'] : $rowPost['rate_half']; ?> บาท</h5>
                                    <a class="d-block h5 mb-2" href=""><?php echo $rowPost['photographer_name'] . ' ' . $rowPost['photographer_surname']; ?></a>
                                    <p><i class="fa fa-map-marker-alt text-dark me-2"></i><?php echo $rowPost['photographer_scope']; ?></p>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
            <div class="col-12 text-center wow fadeInUp mt-5" data-wow-delay="0.1s">
                <a class="btn btn-dark py-3 px-5" href="workings.php">ดูเพิ่มเติม</a>
            </div>
        </div>
    </div>
    <!-- Examples of work End -->

    
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
                    <a class="btn btn-link text-white-50" href="">เกี่ยวกับพวกเรา</a>
                    <a class="btn btn-link text-white-50" href="">ติดต่อเรา</a>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="#">2024 Photo Match</a>, All Right Reserved.

                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <div class="footer-menu">
                            <a href="">หน้าหลัก</a>
                            <a href="">คุกกี้</a>
                            <a href="">ช่วยเหลือ</a>
                            <a href="">ถามตอบ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>-->
    <!-- Footer End -->
       <!-- Footer Start -->
       <div class="container-fluid bg-dark text-white-50 footer wow fadeIn mt-5">
        <div class="copyright">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; <a class="border-bottom" href="#">2024 Photo Match</a>, All Right Reserved.
                </div>
                </div>
            </div>
        </div>

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