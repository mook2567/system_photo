<?php
session_start();
include "../config_db.php";
require_once '../popup.php';

$email = $_SESSION['admin_login'];
// $sql = "UPDATE * FROM `customer` WHERE cus_id = $id";
$sql = "SELECT * FROM `admin` WHERE admin_email = '$email'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

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
            background: #F0F2F5;
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

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
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
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-0 px-4">
        <a href="index.html" class="navbar-brand d-flex align-items-center text-center">
            <img class="img-fluid" src="../img/photoLogo.png" style="height: 60px;">
        </a>
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon text-primary"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto f">
                <a href="index.php" class="nav-item nav-link ">หน้าหลัก</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle bg-dark " data-bs-toggle="dropdown">ข้อมูลพื้นฐาน</a>
                    <div class="dropdown-menu rounded-0 m-0">
                        <a href="manage.php" class="dropdown-item">ข้อมูลพื้นฐาน</a>
                        <a href="manageWeb.php" class="dropdown-item">ข้อมูลระบบ</a>
                        <a href="manageAdmin.php" class="dropdown-item">ข้อมูลผู้ดูแลระบบ</a>
                        <a href="manageCustomer.php" class="dropdown-item">ข้อมูลลูกค้า</a>
                        <a href="managePhotographer.php" class="dropdown-item">ข้อมูลช่างภาพ</a>
                        <!-- <a href="manageType.php" class="dropdown-item">ข้อมูลประเภทงาน</a> -->
                    </div>
                </div>
                <a href="approvMember.php" class="nav-item nav-link ">อนุมัติสมาชิก</a>
                <!-- <a href="report.php" class="nav-item nav-link ">รายงาน</a> -->
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle bg-dark active" data-bs-toggle="dropdown">โปรไฟล์</a>
                    <div class="dropdown-menu rounded-0 m-0">
                        <!-- <a href="profile.php" class="dropdown-item active">โปรไฟล์</a> -->

                        <a href="../index.php" class="dropdown-item">ออกจากระบบ</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->
    <div class="card mt-5 col-10 container-fluid">
        <div class="card-body col-md-12 ">
            <form method="post" action="profile.php?id=<?php echo $row['admin_email']; ?>" enctype="multipart/form-data">
                <div class="container-md">
                    <div class="text-center" style="font-size: 18px;">
                        <b>สวัสดี! คุณ <?php echo $row['admin_name']; ?></b>
                    </div>
                    <div class="mt-3 col-md-10 container-fluid">
                        <div class="row">
                            <div class="col-8">
                                <!-- Personal information fields -->
                                <div class="col-12">
                                    <div class="row mt-2">
                                        <div class="col-2">
                                            <label for="prefix" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px;font-size: 13px;"> คำนำหน้า</span>
                                            </label>
                                            <input type="text" name="prefix" class="form-control mt-1" value="<?php echo $row['admin_prefix']; ?>" readonly>
                                        </div>
                                        <div class="col-5">
                                            <label for="name" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px;font-size: 13px;">ชื่อ</span>
                                            </label>
                                            <input type="text" name="name" class="form-control mt-1" value="<?php echo $row['admin_name']; ?>" readonly>
                                        </div>
                                        <div class="col-5">
                                            <label for="surname" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; font-size: 13px;">นามสกุล</span>
                                            </label>
                                            <input type="text" name="surname" class="form-control mt-1" value="<?php echo $row['admin_surname']; ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <!-- Address fields -->
                                <div class="col-12 mt-2">
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <label for="address" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px;font-size: 13px;">ที่อยู่</span>
                                            </label>
                                            <input type="text" name="address" class="form-control mt-1" value="<?php echo $row['admin_address']; ?>" style="resize: none;">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="district" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px; font-size: 13px;">อำเภอ</span>
                                            </label>
                                            <input type="text" name="district" class="form-control mt-1" value="<?php echo $row['admin_district']; ?>" style="resize: none;">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="province" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px; font-size: 13px;">จังหวัด</span>
                                            </label>
                                            <input type="text" name="province" class="form-control mt-1" value="<?php echo $row['admin_province']; ?>" style="resize: none;">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="zipcode" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px; font-size: 13px;">ไปรษณีย์</span>
                                            </label>
                                            <input type="text" name="zipcode" class="form-control mt-1" value="<?php echo $row['admin_zip_code']; ?>" style="resize: none;">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="tell" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px; font-size: 13px;">เบอร์โทรศัพท์</span>
                                            </label>
                                            <input type="text" name="tell" class="form-control mt-1" value="<?php echo $row['admin_tell']; ?>" style="resize: none;">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="email" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px; font-size: 13px;">อีเมล</span>
                                            </label>
                                            <input type="text" name="email" class="form-control mt-1" value="<?php echo $row['admin_email']; ?>" style="resize: none;">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="password" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px; font-size: 13px;">รหัสผ่าน</span>
                                            </label>
                                            <input type="password" name="password" class="form-control mt-1" value="<?php echo $row['admin_password']; ?>" style="resize: none;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-flex justify-content-center align-items-center md">
                                    <div class="circle">
                                        <img src="../img/profile/<?php echo $row['admin_photo']; ?>" alt="Your Image">
                                    </div>
                                </div>
                                <div class="align-items-center justify-content-center d-flex">
                                    <div>
                                        <div>
                                            <label for="photo" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px; font-size: 13px;">รูปภาพโปรไฟล์</span>
                                                <span style="color: red;">*</span>
                                            </label>
                                        </div>
                                        <input type="file" name="photo" class="form-control">
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <label for="license" style="font-weight: bold; display: flex; align-items: center;">
                                        <span style="color: black; margin-right: 5px;font-size: 13px;">สิทธิ์การใช้งาน</span>
                                    </label>
                                    <select class="form-select border-1 mt-1" name="license">
                                        <?php if ($row['admin_license'] == '0') { ?>
                                            <option value="0">ไม่มีสิทธิ์การเข้าใช้งาน</option>
                                            <option value="1">มีสิทธิ์การเข้าใช้งาน</option>
                                        <?php } else { ?>
                                            <option value="1">มีสิทธิ์การเข้าใช้งาน</option>
                                            <option value="0">ไม่มีสิทธิ์การเข้าใช้งาน</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center mt-5">
                            <div class="col-md-12 text-center">
                                <!-- Navigation buttons -->
                                <button onclick="window.history.back();" class="btn btn-danger me-4" style="width: 150px; height:45px;">ย้อนกลับ</button>
                                <button type="submit" class="btn btn-primary" style="width: 150px; height:45px;">บันทึกการแก้ไข</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

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