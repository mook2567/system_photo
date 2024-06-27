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
                        <a href="about.php" class="nav-item nav-link">เกี่ยวกับ</a>
                        <a href="contact.php" class="nav-item nav-link active">ติดต่อ</a>
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
                    <h1 class="display-5 animated fadeIn mb-4 f">Contact Us</h1>
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

    <!-- Contact Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h1 class="mb-3 f">ติดต่อเรา</h1>
                <p>หากคุณพบเจอปัญหาหรืออยากคุยกับเราคุณสามารถติต่อเราได้ตามด้านล่าง</p>
            </div>
            <div class="row g-4">
                <div class="col-12">
                    <div class="row gy-4">
                        <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.1s">
                            <div class="bg-light rounded p-3">
                                <div class="d-flex align-items-center bg-white rounded p-3" style="border: 1px dashed rgba(0, 185, 142, .3)">
                                    <div class="icon me-3" style="width: 45px; height: 45px;">
                                        <i class="fa fa-map-marker-alt text-primary"></i>
                                    </div>
                                    <span>172/20 ม.6 ถ.ศรีจันทร์ ต.ในเมือน อ.เมือง จ.ขอนแก่น 40000</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.3s">
                            <div class="bg-light rounded p-3">
                                <div class="d-flex align-items-center bg-white rounded p-3" style="border: 1px dashed rgba(0, 185, 142, .3)">
                                    <div class="icon me-3" style="width: 45px; height: 45px;">
                                        <i class="fa fa-envelope-open text-primary"></i>
                                    </div>
                                    <span>photomatch@gmai.com</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.5s">
                            <div class="bg-light rounded p-3">
                                <div class="d-flex align-items-center bg-white rounded p-3" style="border: 1px dashed rgba(0, 185, 142, .3)">
                                    <div class="icon me-3" style="width: 45px; height: 45px;">
                                        <i class="fa fa-phone-alt text-primary"></i>
                                    </div>
                                    <span>096-825-4382</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->


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