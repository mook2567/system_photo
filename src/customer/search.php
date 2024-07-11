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
        .property-item img {
            width: 100%;
            height: 250px; /* กำหนดความสูงตามที่คุณต้องการ */
            object-fit: cover; /* ทำให้รูปภาพครอบคลุมพื้นที่ */
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
                <a href="index.php" class="navbar-brand d-flex align-items-center text-center">
                    <img class="img-fluid" src="../img/photoLogo.png" style="height: 60px;">
                </a>
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon text-primary"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto f">
                        <a href="index.php" class="nav-item nav-link">หน้าหลัก</a>
                        <a href="search.php" class="nav-item nav-link active">ค้นหาช่างภาพ</a>
                        <a href="workings.php" class="nav-item nav-link">ผลงานช่างภาพ</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">รายการจองคิวช่างภาพ</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="bookingLists.php" class="dropdown-item">รายการจองคิวทั้งหมด</a>
                                <a href="payLists.php" class="dropdown-item ">รายการจองคิวที่ต้องชำระเงิน/ค่ามัดจำ</a>
                                <a href="reviewLists.php" class="dropdown-item">รายการจองคิวที่ต้องรีวิว</a>
                                <a href="bookingFinishedLists.php" class="dropdown-item">รายการจองคิวที่เสร็จสิ้นแล้ว</a>
                                <a href="bookingRejectedLists.php" class="dropdown-item">รายการจองคิวที่ถูกปฏิเสธ</a>
                            </div>
                        </div>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">โปรไฟล์</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="profile.php" class="dropdown-item">โปรไฟล์</a>
                                <a href="about.php" class="dropdown-item">เกี่ยวกับ</a>
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
        <div class="container-fluid row g-0 align-items-center flex-column-reverse flex-md-row">
            <div class="col-md-6 p-5 mt-lg-5">
                <h1 class="display-5 animated fadeIn text-white mb-4 f">ค้นหาช่างภาพ</h1>
                <p class="text-white">คุณสามารถค้นหาช่างภาพตามความสนใจของคุณได้</p>
            </div>
        </div>
        <!-- Header End -->

        <!-- Search Start -->
        <div class="mt-5 wow fadeIn" style="background-color: rgba(	250,250,250, 0.4);padding: 35px;" data-wow-delay="0.1s">
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
                        <input class="border-0 py-3" type="text" name="reat" placeholder="  งบประมาณ (บาท)" style="border: none; outline: none; width:100%; border-radius: 5px;">
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
                            <button type="submit" class="btn btn-primary border-0 w-100 py-3"  name="search">ค้นหา</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Search End -->

   <!-- Examples of work Start -->
   <div class="container-xxl py-5">
        <div class="container">
        <div class="tab-content">
                        <div id="tab-1" class="tab-pane fade show p-0 active">
                            <div class="row g-4">
                                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                                    <div class="property-item rounded overflow-hidden">
                                        <div class="position-relative overflow-hidden">
                                            <a href=""><img class="img-fluid" src="../img/m01.jpg" alt=""></a>
                                            <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">ภาพบุคคล</div>
                                        </div>
                                        <div class="p-4 pb-0">
                                            <h5 class="text-dark mb-3">$5000</h5>
                                            <a class="d-block h5 mb-2" href="">ช่างภาพ คุณ...</a>
                                            <p><i class="fa fa-map-marker-alt text-dark me-2"></i>จังหวัดขอนแก่น</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                                    <div class="property-item rounded overflow-hidden">
                                        <div class="position-relative overflow-hidden">
                                            <a href=""><img class="img-fluid" src="../img/m02.jpg" alt=""></a>
                                            <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">ภาพบุคคล</div>
                                        </div>
                                        <div class="p-4 pb-0">
                                            <h5 class="text-dark mb-3">$4000</h5>
                                            <a class="d-block h5 mb-2" href="">ช่างภาพ คุณ.....</a>
                                            <p><i class="fa fa-map-marker-alt text-dark me-2"></i>ภาคกลาง</p>
                                    </div>
                                </div>
                                </div>
                                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                                    <div class="property-item rounded overflow-hidden">
                                        <div class="position-relative overflow-hidden">
                                            <a href=""><img class="img-fluid" src="../img/m03.jpg" alt=""></a>
                                            <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">ภาพบุคคล</div>
                                        </div>
                                        <div class="p-4 pb-0">
                                            <h5 class="text-dark mb-3">$6000</h5>
                                            <a class="d-block h5 mb-2" href="">ช่างภาพ คุณ ....</a>
                                            <p><i class="fa fa-map-marker-alt text-dark me-2"></i>กลางภาค</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                                    <div class="property-item rounded overflow-hidden">
                                        <div class="position-relative overflow-hidden">
                                            <a href=""><img class="img-fluid" src="../img/d2.jpg" alt=""></a>
                                            <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">งานพรีเวดดิ้ง</div>
                                        </div>
                                        <div class="p-4 pb-0">
                                            <h5 class="text-dark mb-3">$12,345</h5>
                                            <a class="d-block h5 mb-2" href="">ช่างภาพ คุณ...</a>
                                            <p><i class="fa fa-map-marker-alt text-dark me-2"></i>ภาคกลาง</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                                    <div class="property-item rounded overflow-hidden">
                                        <div class="position-relative overflow-hidden">
                                            <a href=""><img class="img-fluid" src="../img/dev18.jpg" alt=""></a>
                                            <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">งานบวช</div>
                                        </div>
                                        <div class="p-4 pb-0">
                                            <h5 class="text-dark mb-3">$5000</h5>
                                            <a class="d-block h5 mb-2" href="">ช่างภาพ คุณ...</a>
                                            <p><i class="fa fa-map-marker-alt text-dark me-2"></i>กลางภาค</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                                    <div class="property-item rounded overflow-hidden">
                                        <div class="position-relative overflow-hidden">
                                            <a href=""><img class="img-fluid" src="../img/dev17.jpg" alt=""></a>
                                            <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">งานวันรับปริญญา</div>
                                        </div>
                                        <div class="p-4 pb-0">
                                            <h5 class="text-dark mb-3">$5000</h5>
                                            <a class="d-block h5 mb-2" href="">ช่างภาพ คุณ...</a>
                                            <p><i class="fa fa-map-marker-alt text-dark me-2"></i>กลางภาค</p>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-12 text-center wow fadeInUp" data-wow-delay="0.1s">
                                    <a class="btn btn-dark py-3 px-5" href="">ดูเพิ่มเติม</a>
                                </div>
                            </div>
                        </div>
                <div id="tab-2" class="tab-pane fade show p-0">
                    <div class="row g-4">
                        <div class="col-lg-4 col-md-6">
                            <div class="property-item rounded overflow-hidden">
                                <div class="position-relative overflow-hidden">
                                    <a href=""><img class="img-fluid" src="../img/property-1.jpg" alt=""></a>
                                    <div class="bg-dark rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">For Sell</div>
                                    <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Appartment</div>
                                </div>
                                <div class="p-4 pb-0">
                                    <h5 class="text-dark mb-3">$12,345</h5>
                                    <a class="d-block h5 mb-2" href="">Golden Urban House For Sell</a>
                                    <p><i class="fa fa-map-marker-alt text-dark me-2"></i>123 Street, New York, USA</p>
                                </div>
                                <div class="d-flex border-top">
                                    <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-dark me-2"></i>1000 Sqft</small>
                                    <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-dark me-2"></i>3 Bed</small>
                                    <small class="flex-fill text-center py-2"><i class="fa fa-bath text-dark me-2"></i>2 Bath</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="property-item rounded overflow-hidden">
                                <div class="position-relative overflow-hidden">
                                    <a href=""><img class="img-fluid" src="../img/property-2.jpg" alt=""></a>
                                    <div class="bg-dark rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">For Rent</div>
                                    <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Villa</div>
                                </div>
                                <div class="p-4 pb-0">
                                    <h5 class="text-dark mb-3">$12,345</h5>
                                    <a class="d-block h5 mb-2" href="">Golden Urban House For Sell</a>
                                    <p><i class="fa fa-map-marker-alt text-dark me-2"></i>123 Street, New York, USA</p>
                                </div>
                                <div class="d-flex border-top">
                                    <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-dark me-2"></i>1000 Sqft</small>
                                    <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-dark me-2"></i>3 Bed</small>
                                    <small class="flex-fill text-center py-2"><i class="fa fa-bath text-dark me-2"></i>2 Bath</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="property-item rounded overflow-hidden">
                                <div class="position-relative overflow-hidden">
                                    <a href=""><img class="img-fluid" src="../img/property-3.jpg" alt=""></a>
                                    <div class="bg-dark rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">For Sell</div>
                                    <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Office</div>
                                </div>
                                <div class="p-4 pb-0">
                                    <h5 class="text-dark mb-3">$12,345</h5>
                                    <a class="d-block h5 mb-2" href="">Golden Urban House For Sell</a>
                                    <p><i class="fa fa-map-marker-alt text-dark me-2"></i>123 Street, New York, USA</p>
                                </div>
                                <div class="d-flex border-top">
                                    <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-dark me-2"></i>1000 Sqft</small>
                                    <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-dark me-2"></i>3 Bed</small>
                                    <small class="flex-fill text-center py-2"><i class="fa fa-bath text-dark me-2"></i>2 Bath</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="property-item rounded overflow-hidden">
                                <div class="position-relative overflow-hidden">
                                    <a href=""><img class="img-fluid" src="../img/property-4.jpg" alt=""></a>
                                    <div class="bg-dark rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">For Rent</div>
                                    <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Building</div>
                                </div>
                                <div class="p-4 pb-0">
                                    <h5 class="text-dark mb-3">$12,345</h5>
                                    <a class="d-block h5 mb-2" href="">Golden Urban House For Sell</a>
                                    <p><i class="fa fa-map-marker-alt text-dark me-2"></i>123 Street, New York, USA</p>
                                </div>
                                <div class="d-flex border-top">
                                    <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-dark me-2"></i>1000 Sqft</small>
                                    <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-dark me-2"></i>3 Bed</small>
                                    <small class="flex-fill text-center py-2"><i class="fa fa-bath text-dark me-2"></i>2 Bath</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="property-item rounded overflow-hidden">
                                <div class="position-relative overflow-hidden">
                                    <a href=""><img class="img-fluid" src="../img/property-5.jpg" alt=""></a>
                                    <div class="bg-dark rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">For Sell</div>
                                    <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Home</div>
                                </div>
                                <div class="p-4 pb-0">
                                    <h5 class="text-dark mb-3">$12,345</h5>
                                    <a class="d-block h5 mb-2" href="">Golden Urban House For Sell</a>
                                    <p><i class="fa fa-map-marker-alt text-dark me-2"></i>123 Street, New York, USA</p>
                                </div>
                                <div class="d-flex border-top">
                                    <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-dark me-2"></i>1000 Sqft</small>
                                    <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-dark me-2"></i>3 Bed</small>
                                    <small class="flex-fill text-center py-2"><i class="fa fa-bath text-dark me-2"></i>2 Bath</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="property-item rounded overflow-hidden">
                                <div class="position-relative overflow-hidden">
                                    <a href=""><img class="img-fluid" src="../img/property-6.jpg" alt=""></a>
                                    <div class="bg-dark rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">For Rent</div>
                                    <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Shop</div>
                                </div>
                                <div class="p-4 pb-0">
                                    <h5 class="text-dark mb-3">$12,345</h5>
                                    <a class="d-block h5 mb-2" href="">Golden Urban House For Sell</a>
                                    <p><i class="fa fa-map-marker-alt text-dark me-2"></i>123 Street, New York, USA</p>
                                </div>
                                <div class="d-flex border-top">
                                    <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-dark me-2"></i>1000 Sqft</small>
                                    <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-dark me-2"></i>3 Bed</small>
                                    <small class="flex-fill text-center py-2"><i class="fa fa-bath text-dark me-2"></i>2 Bath</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <a class="btn btn-dark py-3 px-5" href="">ดูเพิ่มเติม</a>
                        </div>
                    </div>
                </div>
                <div id="tab-3" class="tab-pane fade show p-0">
                    <div class="row g-4">
                        <div class="col-lg-4 col-md-6">
                            <div class="property-item rounded overflow-hidden">
                                <div class="position-relative overflow-hidden">
                                    <a href=""><img class="img-fluid" src="../img/property-1.jpg" alt=""></a>
                                    <div class="bg-dark rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">For Sell</div>
                                    <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Appartment</div>
                                </div>
                                <div class="p-4 pb-0">
                                    <h5 class="text-dark mb-3">$12,345</h5>
                                    <a class="d-block h5 mb-2" href="">Golden Urban House For Sell</a>
                                    <p><i class="fa fa-map-marker-alt text-dark me-2"></i>123 Street, New York, USA</p>
                                </div>
                                <div class="d-flex border-top">
                                    <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-dark me-2"></i>1000 Sqft</small>
                                    <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-dark me-2"></i>3 Bed</small>
                                    <small class="flex-fill text-center py-2"><i class="fa fa-bath text-dark me-2"></i>2 Bath</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="property-item rounded overflow-hidden">
                                <div class="position-relative overflow-hidden">
                                    <a href=""><img class="img-fluid" src="../img/property-2.jpg" alt=""></a>
                                    <div class="bg-dark rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">For Rent</div>
                                    <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Villa</div>
                                </div>
                                <div class="p-4 pb-0">
                                    <h5 class="text-dark mb-3">$12,345</h5>
                                    <a class="d-block h5 mb-2" href="">Golden Urban House For Sell</a>
                                    <p><i class="fa fa-map-marker-alt text-dark me-2"></i>123 Street, New York, USA</p>
                                </div>
                                <div class="d-flex border-top">
                                    <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-dark me-2"></i>1000 Sqft</small>
                                    <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-dark me-2"></i>3 Bed</small>
                                    <small class="flex-fill text-center py-2"><i class="fa fa-bath text-dark me-2"></i>2 Bath</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="property-item rounded overflow-hidden">
                                <div class="position-relative overflow-hidden">
                                    <a href=""><img class="img-fluid" src="../img/property-3.jpg" alt=""></a>
                                    <div class="bg-dark rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">For Sell</div>
                                    <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Office</div>
                                </div>
                                <div class="p-4 pb-0">
                                    <h5 class="text-dark mb-3">$12,345</h5>
                                    <a class="d-block h5 mb-2" href="">Golden Urban House For Sell</a>
                                    <p><i class="fa fa-map-marker-alt text-dark me-2"></i>123 Street, New York, USA</p>
                                </div>
                                <div class="d-flex border-top">
                                    <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-dark me-2"></i>1000 Sqft</small>
                                    <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-dark me-2"></i>3 Bed</small>
                                    <small class="flex-fill text-center py-2"><i class="fa fa-bath text-dark me-2"></i>2 Bath</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="property-item rounded overflow-hidden">
                                <div class="position-relative overflow-hidden">
                                    <a href=""><img class="img-fluid" src="../img/property-4.jpg" alt=""></a>
                                    <div class="bg-dark rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">For Rent</div>
                                    <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Building</div>
                                </div>
                                <div class="p-4 pb-0">
                                    <h5 class="text-dark mb-3">$12,345</h5>
                                    <a class="d-block h5 mb-2" href="">Golden Urban House For Sell</a>
                                    <p><i class="fa fa-map-marker-alt text-dark me-2"></i>123 Street, New York, USA</p>
                                </div>
                                <div class="d-flex border-top">
                                    <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-dark me-2"></i>1000 Sqft</small>
                                    <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-dark me-2"></i>3 Bed</small>
                                    <small class="flex-fill text-center py-2"><i class="fa fa-bath text-dark me-2"></i>2 Bath</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="property-item rounded overflow-hidden">
                                <div class="position-relative overflow-hidden">
                                    <a href=""><img class="img-fluid" src="../img/property-5.jpg" alt=""></a>
                                    <div class="bg-dark rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">For Sell</div>
                                    <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Home</div>
                                </div>
                                <div class="p-4 pb-0">
                                    <h5 class="text-dark mb-3">$12,345</h5>
                                    <a class="d-block h5 mb-2" href="">Golden Urban House For Sell</a>
                                    <p><i class="fa fa-map-marker-alt text-dark me-2"></i>123 Street, New York, USA</p>
                                </div>
                                <div class="d-flex border-top">
                                    <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-dark me-2"></i>1000 Sqft</small>
                                    <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-dark me-2"></i>3 Bed</small>
                                    <small class="flex-fill text-center py-2"><i class="fa fa-bath text-dark me-2"></i>2 Bath</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="property-item rounded overflow-hidden">
                                <div class="position-relative overflow-hidden">
                                    <a href=""><img class="img-fluid" src="../img/property-6.jpg" alt=""></a>
                                    <div class="bg-dark rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">For Rent</div>
                                    <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Shop</div>
                                </div>
                                <div class="p-4 pb-0">
                                    <h5 class="text-dark mb-3">$12,345</h5>
                                    <a class="d-block h5 mb-2" href="">Golden Urban House For Sell</a>
                                    <p><i class="fa fa-map-marker-alt text-dark me-2"></i>123 Street, New York, USA</p>
                                </div>
                                <div class="d-flex border-top">
                                    <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-dark me-2"></i>1000 Sqft</small>
                                    <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-dark me-2"></i>3 Bed</small>
                                    <small class="flex-fill text-center py-2"><i class="fa fa-bath text-dark me-2"></i>2 Bath</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <a class="btn btn-dark py-3 px-5" href="">ดูเพิ่มเติม</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Examples of work End -->

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white-50 footer wow fadeIn">
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
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-dark btn-lg-square back-to-top" style="background-color:#1E2045"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/wow/wow.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- select date -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.6/flatpickr.min.js"></script>
    <script src="../js/main.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous"></script>
    <!-- Fancybox JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
    <script>
        flatpickr("#dateRangePicker", {
            mode: "range",
            dateFormat: "Y-m-d"
        });
    </script>

    <!-- Template Javascript -->
    <script src="../js/main.js"></script>
</body>

</html>