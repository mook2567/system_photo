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
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-blue-grey.css">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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

    <!-- Lightbox2 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">

    <!-- Lightbox2 JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

    <!-- Fancybox CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />

    <link href="https://fonts.googleapis.com/css2?family=Athiti&family=Merriweather:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Athiti', sans-serif;
            overflow-x: hidden;
            background: #E5E4E2;
        }

        #calendar {
            width: 800px;
            margin: auto;
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

        /* Responsive styles */
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

        .bottom-bar {
            position: sticky;
            bottom: 0;
            width: 100%;
            margin-top: 20px;
            /* เพิ่มช่องว่างด้านบน */
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
    <div class="bg-dark">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a href="index.html" class="navbar-brand d-flex align-items-center text-center">
                <img class="img-fluid ms-3" src="../img/photoLogo.png" style="height: 60px;">
            </a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon text-primary"></span>
            </button>
            <div class="collapse navbar-collapse m-4" id="navbarCollapse">
                <div class="navbar-nav ms-auto">
                    <a href="index.php" class="nav-item nav-link">หน้าหลัก</a>
                    <a href="table.php" class="nav-item nav-link">ตารางงาน</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle bg-dark" data-bs-toggle="dropdown">รายการจอง</a>
                        <div class="dropdown-menu rounded-0 m-0">
                            <a href="bookingListAll.php" class="dropdown-item">รายการจองทั้งหมด</a>
                            <a href="bookingListWaittingForApproval.php" class="dropdown-item">รายการจองที่รออนุมัติ</a>
                            <a href="bookingListApproved.php" class="dropdown-item">รายการจองที่อนุมัติแล้ว</a>
                            <a href="bookingListNotApproved.php" class="dropdown-item">รายการจองที่ไม่อนุมัติ</a>
                        </div>
                    </div>
                    <a href="report.php" class="nav-item nav-link">รายงาน</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle bg-dark active" data-bs-toggle="dropdown">โปรไฟล์</a>
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

    <!-- Profile Start -->
    <div class="container-sm mt-2" style="height: auto;">
        <div class="row justify-content-between">
            <!-- Photographer Information -->
            <div class="col-3 bg-white" style="border-radius: 10px; height: 800px;">
                <div class="col-12 mt-4">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="circle">
                            <img src="../img/dev3.jpg" alt="Your Image">
                        </div>
                    </div>
                    <div class="col-12 text-center md-3 py-3 px-4 mt-3">
                        <h3>ชื่อช่างภาพ</h3>
                    </div>
                    <div class="col-12 text-start">
                        <h5>ติดต่อ</h5>
                        <div class="col-12 text-start px-3">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-phone me-2"></i>
                                <p class="mb-0">เบอร์โทรศัพท์</p>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-envelope me-2"></i>
                                <p class="mb-0">อีเมล</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-start mt-2">
                        <h5>ประเภทงานที่รับ</h5>
                        <div class="col-12 text-start px-3">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-circle me-2" style="font-size: 5px;"></i>
                                <p class="mb-0">ประเภทงาน1</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-start mt-2">
                        <h5>ขอบเขตพื้นที่รับงาน</h5>
                        <div class="col-12 text-start px-3">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-location-dot me-2"></i>
                                <p class="mb-0">พื้นที่</p>
                            </div>
                        </div>
                    </div>
                    <div class="justify-content-center py-4 text-center">
                        <button type="button" class="btn btn-dark btn-sm" onclick="window.location.href='editProfile.php'">
                            <i class="fa-solid fa-pencil"></i> แก้ไขข้อมูลส่วนตัว
                        </button>
                    </div>
                    <!-- <div class="justify-content-center py-4 text-center">
                        <button type="button" class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#editModal">
                            <i class="fa-solid fa-pencil"></i> แก้ไข
                        </button>
                    </div> -->
                </div>
            </div>
            <!-- Modal -->
            <div class="col-6 ">
                <div class="col-12 bg-white container" style="height: 160px; border-radius: 10px;">
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
                <!-- post type -->
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
                                            <input type="text" id="reat" placeholder="เรทราคาที่รับ" style="border: none; outline: none;">
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

                <!-- Other Posts -->
                <div class="mt-3">
                    <p>โพสต์อื่น ๆ</p>
                </div>
                <div class="col-12 bg-white container mt-2 mb-5" style="height: auto; border-radius: 10px;">
                    <div class="py-1 px-5 mt-1 ms-2 mb-1 justify-content-center">
                        <div class="d-flex align-items-center justify-content-start mt-3">
                            <div style="display: flex; align-items: center;">
                                <div class="circle me-3" style="width: 50px; height: 50px;">
                                    <img src="../img/dev3.jpg" alt="Your Image">
                                </div>
                                <div style="flex-grow: 1;">
                                    <p style="margin-bottom: 0;">ชื่อช่างภาพ</p>
                                    <div>
                                        <p style="margin-bottom: 0;">ประเภทงาน</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 post-text center">รายละเอียดผลงาน</p>
                        <div class="row">
                            <div class="col-md-6">
                                <a href="../img/dev3.jpg" class="mb-2 col-1 col-sm-1 img-fluid" data-fancybox="image-group">
                                    <img class="post-img mb-2" src="../img/dev3.jpg" width="160" alt="img-post" />
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="../img/dev2.jpg" class="mb-2 col-1 col-sm-1 img-fluid" data-fancybox="image-group">
                                    <img class="post-img mb-2" src="../img/dev2.jpg" width="160" alt="img-post" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Weekly Schedule -->
            <div class="col-3 bg-white" style="border-radius: 10px; height: 450px;">
                <div class="col-12">
                    <div class="d-flex justify-content-center align-items-center mt-3">
                        <h4>ตารางงาน</h4>
                    </div>
                    <div class="ms-2">
                        ตารางงานสัปดาห์นี้
                    </div>
                    <div id="bookingStatus" class="col-12 text-center" style="border-radius: 10px; padding-top: 10px; padding-bottom: 10px;">
                        <p class="mb-0 text-white">วันนที่จอง</p>
                    </div>
                    <div class="justify-content-center py-4 text-center">
                        <button type="button" class="btn btn-dark btn-sm" onclick="window.location.href='table.php'">
                            <i class="fa-solid fa-magnifying-glass"></i> ดูเพิ่มเติม
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Profile End -->

    <!-- Footer Start -->
    <footer class="footer">
        &copy; <a class="border-bottom text-dark" href="#">2024 Photo Match</a>, All Right Reserved.
    </footer>
    <!-- Footer End -->

    <script>
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true
        })
    </script>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/wow/wow.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="../js/main.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $(function() {
            // กำหนด element ที่จะแสดงปฏิทิน
            var calendarEl = $("#calendar")[0];

            // กำหนดการตั้งค่า
            var calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: ['dayGrid']
            });

            // แสดงปฏิทิน 
            calendar.render();

        });
    </script>

    <script>
        document.getElementById('uploadImageButton').addEventListener('click', function() {
            document.getElementById('postImg').click();
        });
    </script>

    <!-- Fancybox JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>

    <script>
        // ตัวแปรที่ต้องการตรวจสอบ
        var bookingAvailable = true; // แทนค่าที่ต้องการตรวจสอบว่าว่างหรือไม่

        // ตรวจสอบเงื่อนไขและกำหนดสีพื้นหลัง
        if (bookingAvailable) {
            document.getElementById("bookingStatus").style.backgroundColor = "lightgreen"; // ถ้าว่างให้เป็นสีเขียว
        } else {
            document.getElementById("bookingStatus").style.backgroundColor = "lightcoral"; // ถ้าไม่ว่างให้เป็นสีแดง
        }
    </script>

    <!-- post -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // เลือก Radio Buttons
            var postOptionRadios = document.querySelectorAll('input[name="postOption"]');
            // เลือกเนื้อหาของโพสต์
            var postContentDiv = document.getElementById('postContent');

            // แสดงเนื้อหาสำหรับโพสต์รูปเป็นค่าเริ่มต้น
            postContentDiv.innerHTML = `
            <div class="col-5 mt-4">
                <select class="form-select border-1 py-2">
                    <option selected>ประเภทงาน</option>
                    <option value="1">งานแต่งงาน</option>
                    <option value="2">งานพรีเวดดิ้ง</option>
                    <option value="3">งานอีเว้นท์</option>
                </select>
            </div>
            <div class="post-input-container">
                <textarea rows="8" placeholder="วันนี้คุณถ่ายอะไร"></textarea>
            </div>
            <div class="post-image-preview">
                <img src="../img/dev3-1.jpg" alt="Uploaded Image" style="width: 150px; height: 150px; object-fit: cover; border-radius: 10px;">
            </div>
            <div class="bottom-bar">
                <div class="bg-white post-input-container content-end" style="border-radius: 10px; border: 1px solid #C0C0C0; padding: 10px;">
                    <button type="button" id="uploadImageButton" style="background: none; border: none; display: flex; align-items: center;">
                        <i class="fa-solid fa-images me-2" style="font-size: 30px; color: #69D40F; cursor: pointer;"></i>
                        <p class="mb-0">เพิ่มรูปภาพ</p>
                        <input type="file" class="form-control" id="postImg" style="display: none;">
                    </button>
                </div>
            </div>
            `;

            // เพิ่ม Event Listener สำหรับ Radio Buttons
            postOptionRadios.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    // ตรวจสอบสถานะของ Radio Buttons เมื่อมีการเปลี่ยนแปลง
                    if (this.id === 'postPhotoRadio' && this.checked) {
                        // ในกรณีที่โพสต์รูปถูกเลือก
                        // แสดงเนื้อหาสำหรับโพสต์รูป
                        postContentDiv.innerHTML = `
                        <div class="col-5 mt-4">
                            <select class="form-select border-1 py-2">
                                <option selected>ประเภทงาน</option>
                                <option value="1">งานแต่งงาน</option>
                                <option value="2">งานพรีเวดดิ้ง</option>
                                <option value="3">งานอีเว้นท์</option>
                            </select>
                        </div>
                        <div class="post-input-container">
                            <textarea rows="8" placeholder="วันนี้คุณถ่ายอะไร"></textarea>
                        </div>
                        <div class="post-image-preview">
                            <img src="../img/dev3-1.jpg" alt="Uploaded Image" style="width: 150px; height: 150px; object-fit: cover; border-radius: 10px;">
                        </div>
                        <div class="bottom-bar">
                            <div class="bg-white post-input-container content-end" style="border-radius: 10px; border: 1px solid #C0C0C0; padding: 10px;">
                                <button type="button" id="uploadImageButton" style="background: none; border: none; display: flex; align-items: center;">
                                    <i class="fa-solid fa-images me-2" style="font-size: 30px; color: #69D40F; cursor: pointer;"></i>
                                    <p class="mb-0">เพิ่มรูปภาพ</p>
                                    <input type="file" class="form-control" id="postImg" style="display: none;">
                                </button>
                            </div>
                        </div>`;
                    } else if (this.id === 'postTypeRadio' && this.checked) {
                        // ในกรณีที่โพสต์ประเภทงานถูกเลือก
                        // แสดงเนื้อหาสำหรับโพสต์ประเภทงาน
                        postContentDiv.innerHTML = `
                        <div class="col-5 mt-4">
                            <select class="form-select border-1 py-2">
                                <option selected>ประเภทงานที่รับ</option>
                                <option value="1">งานแต่งงาน</option>
                                <option value="2">งานพรีเวดดิ้ง</option>
                                <option value="3">งานอีเว้นท์</option>
                            </select>
                        </div>
                        <div class="post-input-container">
                            <textarea rows="3" placeholder="รายละเอียดการรับงาน"></textarea>
                        </div>`;
                    }
                });
            });

            // เพิ่ม Event Listener สำหรับปุ่ม "เพิ่มรูปภาพ"
            document.getElementById('uploadImageButton').addEventListener('click', function() {
                document.getElementById('postImg').click(); // คลิกที่ input element ประเภท file
            });
        });
    </script>


</body>

</html>