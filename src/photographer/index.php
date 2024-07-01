<?php
$images = glob("images/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
if ($images === false) {
    $images = [];
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

        .modal-dialog {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-dialog {
            max-width: 90%;
            /* กำหนดความกว้างสูงสุดเป็น 80% */
            width: 60%;
            /* กำหนดความกว้างเป็น 80% */
        }

        .post-input-container {
            /* padding-left: 55px; */
            padding-top: 20px;
        }

        .post-input-container textarea {
            width: 100%;
            border: 0;
            outline: 0;
            /* border-bottom: 1px solid #ccc; */
            resize: none;
        }

        .container {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .form-container {
            flex: 1;
            overflow-y: auto;
        }

        .slider {
            position: relative;
            width: 100%;
            max-width: 600px;
            margin: auto;
            overflow: hidden;
            border: 2px solid #ddd;
        }

        .slides {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .slides img {
            width: 100%;
            display: block;
        }

        .navigation {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
        }

        .prev,
        .next {
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 10px;
            cursor: pointer;
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
    <div class="bgIndex" style="height: 560px;">
        <!-- <div style="background-color: rgba(	0, 41, 87, 0.6);"> -->
        <div class="d-flex justify-content-center">
            <nav class="navbar navbar-expand-lg navbar-dark col-10">
                <a href="index.html" class="navbar-brand d-flex align-items-center text-center">
                    <img class="img-fluid ms-3" src="../img/photoLogo.png" style="height: 60px;">
                </a>
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon text-primary"></span>
                </button>
                <div class="collapse navbar-collapse m-4" id="navbarCollapse">
                    <div class="navbar-nav ms-auto">
                        <a href="index.php" class="nav-item nav-link active">หน้าหลัก</a>
                        <a href="table.php" class="nav-item nav-link">ตารางงาน</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">รายการจอง</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="bookingListAll.php" class="dropdown-item">รายการจองทั้งหมด</a>
                                <a href="bookingListWaittingForApproval.php" class="dropdown-item">รายการจองที่รออนุมัติ</a>
                                <a href="bookingListApproved.php" class="dropdown-item">รายการจองที่อนุมัติแล้ว</a>
                                <a href="bookingListNotApproved.php" class="dropdown-item">รายการจองที่ไม่อนุมัติ</a>
                            </div>
                        </div>
                        <a href="report.php" class="nav-item nav-link">รายงาน</a>
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

        <!-- Category Start -->
        <div class="mb-3 mt-4 text-center mx-auto  wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                        <h1 class="f" style="color:aliceblue;">Photo Match</h1>
                        <p style="color:aliceblue;">เว็บไซต์ที่จะช่วยคุณหาช่างภาพที่คุณต้องการ</p>
                    </div>
        <div class="col-12 container" style="height: 160px; border-radius: 10px; background-color: rgb(255,255,255, 0.7);">
            <div class="py-1 px-5 mt-1 ms-2 mb-1 justify-content-center">
                <div class="d-flex align-items-center justify-content-center mt-3">
                    <div class="circle" style="width: 50px; height: 50px;">
                        <img src="../img/dev3.jpg" alt="Your Image">
                    </div>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#post" class="btn text-start text-black ms-4" style="width: 80%; height: 45px; background-color: #F0F2F5; border-radius: 50px; font-size: 18px;">
                        วันนี้คุณถ่ายอะไร
                    </button>
                </div>
            </div>
            <hr>
            <!-- Buttons for image and work type -->
            <div class="d-flex align-items-center justify-content-center me-5 ms-5 mb-1">
                <button type="button" style="width: 45%; background: none; border: none; display: flex; align-items: center;" data-bs-toggle="modal" data-bs-target="#postPhoto">
                    <i class="fa-solid fa-images me-2" style="font-size: 30px; color: #69D40F; cursor: pointer;"></i>
                    <p class="mb-0" style="margin-right: 5px;">ลงผลงาน</p>
                </button>
                <button type="button" style="width: 40%; background: none; border: none; display: flex; align-items: center;" data-bs-toggle="modal" data-bs-target="#postType">
                    <i class="fa-solid fa-briefcase me-2" style="font-size: 30px; color: #E53935; cursor: pointer;"></i>
                    <p class="mb-0" style="margin-right: 5px;">ลงประเภทงานที่รับ</p>
                </button>
            </div>
        </div>
        <!-- post -->
        <div class="modal fade" id="post" tabindex="-1" aria-labelledby="postLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="width: 30%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title me-2" id="postLabel">โพสต์</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="height: 645px;">
                        <!-- Form for editing photographer's information -->
                        <div class="container">
                            <div class="form-container">
                                <form>
                                    <div class="d-flex align-items-center mb-3 justify-content-start mt-3">
                                        <div class="circle me-3" style="width: 60px; height: 60px;">
                                            <img src="../img/dev3.jpg" alt="Your Image">
                                        </div>
                                        <div class="col-7">
                                            <p class="mb-0" style="margin-right: 5px;">ชื่อช่างภาพ</p>
                                        </div>
                                    </div>
                                    <div class="row col-12 ">
                                        <div class="col-3 ms-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="postOption" id="postPhotoRadio" checked>
                                                <label class="form-check-label" for="postPhotoRadio">ลงผลงาน</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="postOption" id="postTypeRadio">
                                                <label class="form-check-label" for="postTypeRadio">ประเภทงาน</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="postContent">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn text-white" style="background:#0F52BA; width: 100%;">โพสต์</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- post photo -->
        <div class="modal fade" id="postPhoto" tabindex="-1" aria-labelledby="postPhotoLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="width: 30%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="postPhotolLabel">ลงผลงาน</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="height: 560px;">
                        <!-- Form for editing photographer's information -->
                        <div class="container">
                            <div class="form-container">
                                <form>
                                    <div class="d-flex align-items-center mb-3 justify-content-start mt-3">
                                        <div class="circle me-3" style="width: 60px; height: 60px;">
                                            <img src="../img/dev3.jpg" alt="Your Image">
                                        </div>
                                        <div>
                                            <p class="mb-0" style="margin-right: 5px;">ชื่อช่างภาพ</p>
                                            <div>
                                                <select class="form-select border-1 py-1">
                                                    <option selected>ประเภทงาน</option>
                                                    <option value="1">งานแต่งงาน</option>
                                                    <option value="2">งานพรีเวดดิ้ง</option>
                                                    <option value="3">งานอีเว้นท์</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="post-input-container">
                                        <textarea rows="8" placeholder="วันนี้คุณถ่ายอะไร"></textarea>
                                    </div>
                                </form>
                                <div class="post-image-preview">
                                    <img src="../img/dev3-1.jpg" alt="Uploaded Image" style="width: 150px; height: 150px; object-fit: cover; border-radius: 10px;">
                                </div>
                            </div>
                            <div class="bottom-bar">
                                <div class="bg-white post-input-container content-end" style="border-radius: 10px; border: 1px solid #C0C0C0; padding: 10px;">
                                    <button type="button" id="uploadImageButton" style="background: none; border: none; display: flex; align-items: center;">
                                        <i class="fa-solid fa-images me-2" style="font-size: 30px; color: #69D40F; cursor: pointer;"></i>
                                        <input type="file" class="form-control" id="postImg" style="display: none;">
                                        <p class="mb-0">เพิ่มรูปภาพ</p>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn text-white" style="background:#0F52BA; width: 100%;">โพสต์</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="postType" tabindex="-1" aria-labelledby="postTypeLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="width: 30%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="postTypeLabel">ลงประเภทงานที่รับ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="height: 390px;">
                        <!-- Form for editing photographer's information -->
                        <div class="container">
                            <form>
                                <div class="d-flex align-items-center mb-3 justify-content-start mt-3">
                                    <div class="circle me-3" style="width: 60px; height: 60px;">
                                        <img src="../img/dev3.jpg" alt="Your Image">
                                    </div>
                                    <div>
                                        <p class="mb-0" style="margin-right: 5px;">ชื่อช่างภาพ</p>
                                    </div>
                                </div>
                                <div class="col-5 mt-4">
                                    <select class="form-select border-1 py-2">
                                        <option selected>ประเภทงานที่รับ</option>
                                        <option value="1">งานแต่งงาน</option>
                                        <option value="2">งานพรีเวดดิ้ง</option>
                                        <option value="3">งานอีเว้นท์</option>
                                    </select>
                                </div>
                                <div class="col-5 mt-4">
                                    <input type="text" name="reat" placeholder="เรทราคาที่รับ" style="border: none; outline: none;">
                                </div>
                                <div class="post-input-container">
                                    <textarea rows="3" placeholder="รายละเอียดการรับงาน"></textarea>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn text-white" style="background:#0F52BA; width: 100%;">โพสต์</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- post type -->
    </div>
    <!-- Category End -->


    <div class="container mb-5 mt-2" style="height: 100%">
        <div class="container-lg mt-3">

            <!-- Examples of work Start -->
            <div class="container-xxl mt-4">
                <div class="container">
                    <div class="row g-0 gx-5 align-items-end">
                        <div class="col-lg-6">
                            <div class="text-start mx-auto mb-5 wow slideInLeft" data-wow-delay="0.1s">
                                <h1 class="mb-3 f">ผลงานช่างภาพ</h1>
                                <!-- <p>คุณลองดูผลงานช่างภาพของเราสิ!!!</p> -->
                            </div>
                        </div>
                        <div class="col-lg-6 text-start text-lg-end wow slideInRight" data-wow-delay="0.1s">
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
                        </div>
                    </div>
                    <div class="tab-content">
                        <div id="tab-1" class="tab-pane fade show p-0 active">
                            <div class="row g-4">
                                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
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
                                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
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
                                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
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
                                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
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
                                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
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
                                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
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

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/wow/wow.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>
    <script>
        // JavaScript สำหรับควบคุมสไลด์โชว์
        let slideIndex = 0;

        show
        showSlides();

        function showSlides() {


            let i;
            let slides = $(".slide");
            slides.hide();
            slideIndex++;

            slideIndex++;


            if (slideIndex > slides.length) {
                slideIndex = 1
            }
            slides.
            slides
            eq(slideIndex - 1).show();


            setTimeout(showSlides, 2000); // เปลี่ยนภาพทุกๆ 2 วินาที
        }

        function plusSlides(n) {

            showSlides
            showSlides(slideIndex += n);
        }
    </script>

    <!-- Template Javascript -->
    <script src="../js/main.js"></script>
</body>

</html>