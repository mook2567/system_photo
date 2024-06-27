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
        }

        .f {
            font-family: 'Athiti', sans-serif;
        }
    </style>
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Navbar Start -->
        <div class="container-fluid nav-bar bg-transparent">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-0 px-4">
                <a href="index.html" class="navbar-brand d-flex align-items-center text-center">
                    <img class="img-fluid" src="img/photoLogo.png" style="height: 60px;">
                </a>
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon text-primary"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto">
                        <a href="index.php" class="nav-item nav-link ">หน้าหลัก</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle bg-dark" data-bs-toggle="dropdown">รายการ</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="search.php" class="dropdown-item">ค้นหาช่างภาพ</a>
                                <a href="type.php" class="dropdown-item">ประเภทงาน</a>
                                <a href="workings.php" class="dropdown-item">ผลงานช่างภาพ</a>
                            </div>
                        </div>
                        <a href="about.php" class="nav-item nav-link active">เกี่ยวกับ</a>
                        <a href="contact.php" class="nav-item nav-link">ติดต่อ</a>
                        <a onclick="window.location.href='login.php'" class="nav-item nav-link">เข้าสู่ระบบ<i class="ms-1 fa-solid fa-right-to-bracket"></i></a>
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
                    <img class="img-fluid" src="img/header.jpg" alt="">
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- การค้นหาช่างภาพ -->
    <div class=" bg-dark mb-5 wow fadeIn " data-wow-delay="0.1s" style="padding: 35px;">
        <div class="container">
            <div class="row flex-row g-2 align-items-center">
                <h2 class="text-white f">ค้นหาช่างภาพ</h2>
                <div class="col-md-3">
                    <select class="form-select border-0 py-3">
                        <option selected>ประเภทงาน</option>
                        <option value="1">งานแต่งงาน</option>
                        <option value="2">งานพรีเวดดิ้ง</option>
                        <option value="3">งานอีเว้นท์</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input class="border-0 py-3" type="text" id="reat" placeholder="  งบประมาณ (บาท)" style="border: none; outline: none; width:100%; border-radius: 5px;">
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
                        <option value="1">ภาคกลาง</option>
                        <option value="2">ภาคตะวันออกเฉียงเหนือ</option>
                        <option value="3">ภาคใต้</option>
                        <option value="4">ภาคตะวันออก</option>
                        <option value="6">ภาคตะวันตก</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <form action="search.php" method="GET">
                        <button type="submit" class="btn btn-primary border-0 w-100 py-3" name="search">ค้นหา</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Search End -->

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
    <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
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
                    <div class="col-md-6 text-center text-md-end">
                        <div class="footer-menu">
                            <a href="index.php">หน้าหลัก</a>
                            <a href="">คุกกี้</a>
                            <a href="contact.php">ช่วยเหลือ</a>
                            <a href="">ถามตอบ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

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