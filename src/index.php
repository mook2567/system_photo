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

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Athiti&family=Merriweather:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Athiti', sans-serif;
        }

        .f {
            font-family: 'Athiti', sans-serif;
        }

        .img-fluid {
            max-width: 100%;
            height: auto;
        }

        .container-xl {
            max-width: 1140px;
            margin: 0 auto;
        }

        .header-carousel {
            width: 100%;
        }

        .header-carousel .owl-carousel-item {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .bgIndex {
            background-image: url('img/bgIndex1.jpg');
            background-attachment: fixed;
            background-size: cover;
        }

        .property-img {
            width: 100%;
            height: 250px;
            /* or any desired height */
            object-fit: cover;
        }
        .caption {
  white-space: nowrap; /* ทำให้ข้อความไม่ขึ้นบรรทัดใหม่ */
  overflow: hidden; /* ซ่อนข้อความที่ล้น */
  text-overflow: ellipsis; /* แสดง ... เมื่อข้อความล้น */
  width: 100%; /* ตั้งค่าความกว้างตามที่ต้องการ */
  display: block; /* ทำให้พารากราฟเป็นบล็อค */
}

    </style>
</head>

<body>
    <div class="bgIndex" style="height: auto;">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-dark" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Navbar Start -->
        <div class="d-flex justify-content-center">
            <nav class="mt-3 navbar navbar-expand-lg navbar-dark col-10">
                <a href="index.php" class="navbar-brand d-flex align-items-center text-center" style="height: 70px;">
                    <img class="img-fluid" src="/img/logo/<?php echo isset($rowInfo['information_icon']) ? $rowInfo['information_icon'] : ''; ?>" style="height: 30px;">
                </a>
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon text-primary"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto">
                        <a href="index.php" class="nav-item nav-link active">หน้าหลัก</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">รายการ</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="search.php" class="dropdown-item">ค้นหาช่างภาพ</a>
                                <a href="type.php" class="dropdown-item">ประเภทงาน</a>
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

        <!-- Category Start -->
        <div class="mt-4 d-flex justify-content-center align-items-center" style="border-radius: 10px;">
            <div class="container-xxl py-5">
                <div class="container">
                    <div class="mb-3 mt-4 text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                        <h1 class="f" style="color:aliceblue;">Photo Match</h1>
                        <p style="color:aliceblue;">เว็บไซต์ที่จะช่วยคุณหาช่างภาพที่คุณต้องการ</p>
                    </div>
                    <div class="row g-5 mt-2 justify-content-center">
                        <?php
                        if ($resultType->num_rows > 0) {
                            while ($rowType = $resultType->fetch_assoc()) {
                        ?>
                                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                                    <a class="cat-item bg-light text-center" href="">
                                        <div class="rounded p-4" style="font-size: 60.9px;">
                                            <img src="img/icon/<?php echo $rowType['type_icon']; ?>" style="height: 75px; width: 75px;"></img>
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
        <!-- Category End -->

        <!-- Search Start -->
        <div class="mt-5 wow fadeIn" style="background-color: rgba(250, 250, 250, 0.4); padding: 35px;" data-wow-delay="0.1s">
            <div class="container">
                <div class="row flex-row g-2 align-items-center">
                    <h2 class="text-white">ค้นหาช่างภาพ</h2>
                    <div class="col-md-3">
                        <form action="search.php" method="POST">
                            <select class="form-select border-0 py-3  mt-3" name="type" required>
                                <option selected>ประเภทงาน</option>
                                <?php
                                $sql = "SELECT t.type_id, t.type_work
                                FROM type t
                                INNER JOIN (
                                    SELECT type_id, MAX(photographer_id) AS photographer_id
                                    FROM type_of_work
                                    GROUP BY type_id
                                ) AS tow_latest ON t.type_id = tow_latest.type_id";
                                $resultTypeWorkDetail = $conn->query($sql);

                                if ($resultTypeWorkDetail->num_rows > 0) {
                                    while ($rowTypeWorkDetail = $resultTypeWorkDetail->fetch_assoc()) {
                                ?>
                                        <option value="<?php echo $rowTypeWorkDetail['type_id']; ?>"><?php echo $rowTypeWorkDetail['type_work']; ?></option>
                                <?php
                                    }
                                } ?>
                            </select>
                    </div>
                    <div class="col-md-2">
                        <input class="border-0 py-3" type="number" name="budget" placeholder=" งบประมาณ (บาท)" style="border: none; outline: none; width: 100%; border-radius: 5px;" required>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select border-0 py-3" required>
                            <option selected>ช่วงเวลา</option>
                            <option value="1">เต็มวัน</option>
                            <option value="2">ครึ่งวัน</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select border-0 py-3" required>
                            <option selected>สถานที่</option>
                            <option value="กรุงเทพฯ">กรุงเทพฯ</option>
                            <option value="ภาคกลาง">ภาคกลาง</option>
                            <option value="ภาคใต้">ภาคใต้</option>
                            <option value="ภาคเหนือ">ภาคเหนือ</option>
                            <option value="ภาคตะวันออกเฉียงเหนือ">ภาคตะวันออกเฉียงเหนือ</option>
                            <option value="ภาคตะวันตก">ภาคตะวันตก</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary border-0 w-100 py-3" name="search">ค้นหา</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Search End -->

    <!-- Header Start -->
    <!-- <div class="container-fluid mt-5 header bg-white p-0">
        <div class="col-md-12 owl-carousel header-carousel">
            <div class="owl-carousel-item d-flex justify-content-center align-items-center">
                <img class="img-fluid" src="img/dev5.jpg" alt="">
            </div>
            <div class="owl-carousel-item d-flex justify-content-center align-items-center">
                <img class="img-fluid" src="img/dev4.jpg" alt="">
            </div>
        </div>
    </div> -->
    <!-- Header End -->

    <!-- About Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                    <div class="about-img position-relative overflow-hidden p-5 pe-0">
                        <img class="img-fluid w-100" src="img/Photomatch.gif">
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
                        po.portfolio_caption, 
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
                    LEFT JOIN 
                        type_of_work tow ON po.type_of_work_id = tow.type_of_work_id 
                    LEFT JOIN 
                        photographer p ON p.photographer_id = tow.photographer_id
                    LEFT JOIN 
                        type t ON t.type_id = tow.type_id
                    ORDER BY 
                        po.portfolio_id DESC;";
                $resultPost = $conn->query($sql);
                if ($resultPost->num_rows > 0) {
                    while ($rowPost = $resultPost->fetch_assoc()) {
                ?>
                        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                            <div class="property-item rounded overflow-hidden bg-white" >
                                <div class="position-relative overflow-hidden">
                                    <a><img class="img-fluid property-img" src="img/post/<?php echo explode(',', $rowPost['portfolio_photo'])[0]; ?>" alt=""></a>
                                    <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3"><?php echo $rowPost['type_work']; ?></div>
                                </div>
                                <div class="p-4 pb-0">
                                    <p class="caption"><?php echo $rowPost['portfolio_caption']; ?></p>
                                    <a class="d-block h5 mb-2" href="profile_photographer.php?photographer_id=<?php echo $rowPost['photographer_id']; ?>"><?php echo $rowPost['photographer_name'] . ' ' . $rowPost['photographer_surname']; ?></a>
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

    <!-- Call to Action Start -->
    <!-- <div class="container-xxl py-5">
                    <div class="container">
                        <div class="bg-light rounded p-3">
                            <div class="bg-white rounded p-4" style="border: 1px dashed rgba(0, 185, 142, .3)">
                                <div class="row g-5 align-items-center">
                                    <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                                        <img class="img-fluid rounded w-100" src="img/call-to-action.jpg" alt="">
                                    </div>
                                    <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                                        <div class="mb-4">
                                            <h1 class="mb-3">Contact With Our Certified Agent</h1>
                                            <p>Eirmod sed ipsum dolor sit rebum magna erat. Tempor lorem kasd vero ipsum sit sit diam justo sed vero dolor duo.</p>
                                        </div>
                                        <a href="" class="btn btn-dark py-3 px-4 me-2"><i class="fa fa-phone-alt me-2"></i>Make A Call</a>
                                        <a href="" class="btn btn-dark py-3 px-4"><i class="fa fa-calendar-alt me-2"></i>Get Appoinment</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
    <!-- Call to Action End -->


    <!-- Dev Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h1 class="mb-3 f">คณะผู้พัฒนาเว็บไซต์</h1>
                <p>คณะผู้พัฒนาเว็บไซต์ Photo Match ที่ช่วยให้ลูกค้าที่ต้องการภาพถ่ายสามารถเข้ามาค้นหาช่างภาพที่มีความสามารถและทำการจองคิวได้ภายในเว็บไซต์เดียว</p>
            </div>
            <div class="row g-4 text-center mx-auto " style="max-width: 900px;">
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="team-item rounded overflow-hidden">
                        <div class="position-relative">
                            <img class="img-fluid" src="img/dev1.jpg" alt="">
                            <!-- <div class="position-absolute start-50 top-100 translate-middle d-flex align-items-center">
                                <a class="btn btn-square mx-1" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-square mx-1" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-square mx-1" href=""><i class="fab fa-instagram"></i></a>
                            </div> -->
                        </div>
                        <div class="text-center p-4 mt-3">
                            <h5 class="fw-bold mb-0 f">นางสาวกชกร วงพิรงค์</h5>
                            <small>Back End Developer </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="team-item rounded overflow-hidden">
                        <div class="position-relative">
                            <img class="img-fluid" src="img/dev2.jpg" alt="">
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
                    <div class="team-item rounded overflow-hidden">
                        <div class="position-relative">
                            <img class="img-fluid" src="img/dev3.jpg" alt="">
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


    <!-- Review Start -->
    <!-- <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h1 class="mb-3">รีวิวจากผู้ใช้งาน</h1>
                <p>Eirmod sed ipsum dolor sit rebum labore magna erat. Tempor ut dolore lorem kasd vero ipsum sit eirmod sit. Ipsum diam justo sed rebum vero dolor duo.</p>
            </div>
            <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.1s">
                <div class="testimonial-item bg-light rounded p-3">
                    <div class="bg-white border rounded p-4">
                        <p>Tempor stet labore dolor clita stet diam amet ipsum dolor duo ipsum rebum stet dolor amet diam stet. Est stet ea lorem amet est kasd kasd erat eos</p>
                        <div class="d-flex align-items-center">
                            <img class="img-fluid flex-shrink-0 rounded" src="img/testimonial-1.jpg" style="width: 45px; height: 45px;">
                            <div class="ps-3">
                                <h6 class="fw-bold mb-1">Client Name</h6>
                                <small>Profession</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-item bg-light rounded p-3">
                    <div class="bg-white border rounded p-4">
                        <p>Tempor stet labore dolor clita stet diam amet ipsum dolor duo ipsum rebum stet dolor amet diam stet. Est stet ea lorem amet est kasd kasd erat eos</p>
                        <div class="d-flex align-items-center">
                            <img class="img-fluid flex-shrink-0 rounded" src="img/testimonial-2.jpg" style="width: 45px; height: 45px;">
                            <div class="ps-3">
                                <h6 class="fw-bold mb-1">Client Name</h6>
                                <small>Profession</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-item bg-light rounded p-3">
                    <div class="bg-white border rounded p-4">
                        <p>Tempor stet labore dolor clita stet diam amet ipsum dolor duo ipsum rebum stet dolor amet diam stet. Est stet ea lorem amet est kasd kasd erat eos</p>
                        <div class="d-flex align-items-center">
                            <img class="img-fluid flex-shrink-0 rounded" src="img/testimonial-3.jpg" style="width: 45px; height: 45px;">
                            <div class="ps-3">
                                <h6 class="fw-bold mb-1">Client Name</h6>
                                <small>Profession</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!-- Review End -->


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
                </div>-->
    <!-- <div class="col-lg-3 col-md-6">
                    <h5 class="text-white mb-4">Photo Gallery</h5>
                    <div class="row g-2 pt-2">
                        <div class="col-4">
                            <img class="img-fluid rounded bg-light p-1" src="img/property-1.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid rounded bg-light p-1" src="img/property-2.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid rounded bg-light p-1" src="img/property-3.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid rounded bg-light p-1" src="img/property-4.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid rounded bg-light p-1" src="img/property-5.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid rounded bg-light p-1" src="img/property-6.jpg" alt="">
                        </div>
                    </div>
                </div> -->
    <!-- <div class="col-lg-3 col-md-6">
                    <h5 class="text-white mb-4">Newsletter</h5>
                    <p>Dolor amet sit justo amet elitr clita ipsum elitr est.</p>
                    <div class="position-relative mx-auto" style="max-width: 400px;">
                        <input class="form-control bg-transparent w-100 py-3 ps-4 pe-5" type="text" placeholder="Your email">
                        <button type="button" class="btn btn-dark py-2 position-absolute top-0 end-0 mt-2 me-2">SignUp</button>
                    </div>
                </div> -->
    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white-50 footer wow fadeIn">
        <div class="copyright">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; <a class="border-bottom" href="#">2024 Photo Match</a>, All Right Reserved.
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