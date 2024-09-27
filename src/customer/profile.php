<?php
session_start();
include '../config_db.php';
require_once '../popup.php';

$sql = "SELECT * FROM `information`";
$resultInfo = $conn->query($sql);
$rowInfo = $resultInfo->fetch_assoc();

if (isset($_SESSION['customer_login'])) {
    $email = $_SESSION['customer_login'];
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
    <div class="bg-dark">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark">
            <a href="index.php" class="navbar-brand d-flex align-items-center text-center" style="height: 70px;">
                <img class="img-fluid" src="../img/logo/<?php echo isset($rowInfo['information_icon']) ? $rowInfo['information_icon'] : ''; ?>" style="height: 30px;">
            </a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon text-primary"></span>
            </button>
            <div class="collapse navbar-collapse me-5" id="navbarCollapse">
                <div class="navbar-nav ms-auto f">
                <a href="index.php" class="nav-item nav-link">หน้าหลัก</a>
                        <a href="search.php" class="nav-item nav-link">ค้นหาช่างภาพ</a>
                        <a href="workings.php" class="nav-item nav-link">ผลงานช่างภาพ</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">รายการจองคิวช่างภาพ</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="bookingLists.php" class="dropdown-item">รายการจองคิวที่รออนุมัต</a>
                                <a href="payLists.php" class="dropdown-item ">รายการจองคิวที่ต้องชำระเงิน/ค่ามัดจำ</a>
                                <a href="reviewLists.php" class="dropdown-item">รายการจองคิวที่ต้องรีวิว</a>
                                <a href="bookingFinishedLists.php" class="dropdown-item">รายการจองคิวที่เสร็จสิ้นแล้ว</a>
                                <a href="bookingRejectedLists.php" class="dropdown-item">รายการจองคิวที่ถูกปฏิเสธ</a>
                            </div>
                        </div>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">โปรไฟล์</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="profile.php" class="dropdown-item active">โปรไฟล์</a>
                                <!-- <a href="about.php" class="dropdown-item">เกี่ยวกับ</a> -->
                                <!-- <a href="contact.php" class="dropdown-item">ติดต่อ</a> -->
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
                            <img src="../img/profile/<?php echo $rowCus['cus_photo']; ?>" alt="Your Image">
                        </div>
                    </div>
                    <div class="col-12 text-center md-3 py-3 px-4 mt-3">
                        <h3><?php echo $rowCus['cus_name'] . ' ' . $rowCus['cus_surname']; ?></h3>
                    </div>
                    <div class="col-12 text-start">
                        <h5>ติดต่อ</h5>
                        <div class="col-12 text-start px-3">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-phone me-2"></i>
                                <p class="mb-0"><?php echo $rowCus['cus_tell'];?></p>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-envelope me-2"></i>
                                <p class="mb-0"><?php echo $rowCus['cus_email'];?></p>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="justify-content-center py-4 text-center">
                        <button type="button" class="btn btn-dark btn-sm" style="width: 150px; height:45px;" onclick="window.location.href='editProfile.php?'">
                            <i class="fa-solid fa-pencil"></i> แก้ไขข้อมูลส่วนตัว
                        </button>
                    </div> -->
                </div>
            </div>

            <!-- Review Section (Right) -->
            <div class="col-9">
                            <?php
                            // SQL Query for Reviews
                            $sql3 = "SELECT 
                                            SUM(r.review_level) AS scor, 
                                            r.review_caption,
                                            c.cus_name,
                                            c.cus_surname,
                                            cus_photo,
                                            p.photographer_id,
                                            p.photographer_name,
                                            p.photographer_surname
                                        FROM 
                                            review r
                                        JOIN 
                                            booking b ON r.booking_id = b.booking_id 
                                        JOIN 
                                            photographer p ON b.photographer_id = p.photographer_id
                                        JOIN
                                            customer c ON b.cus_id = c.cus_id
                                        WHERE
                                            c.cus_id = $id_cus
                                        GROUP BY
                                            r.review_caption, c.cus_name, c.cus_surname
                                        ORDER BY
                                            r.review_date DESC -- เรียงลำดับตามวันที่รีวิวล่าสุด
                                        ";
                            $resultReview = $conn->query($sql3);
                            if ($resultReview->num_rows > 0) {

                                echo '<div class="row"><div class="text-start mx-auto wow slideInLeft">
                                        <div class="col-11 justify-content-center mt-4"><h4 class="f" data-wow-delay="0.1s">คำรีวิว</h4>
                                    </div></div>';  // เริ่ม row สำหรับการ์ดทั้งหมด
                                while ($rowReview = $resultReview->fetch_assoc()) {
                            ?>
                                    <div class="col-12 mt-1"> <!-- เปลี่ยนเป็น col-12 เพื่อให้การ์ดแต่ละใบเต็มแถว -->

                                        <div class="card bg-white mb-5" style="border-radius: 10px; box-shadow: 0 2px 2px rgba(0, 0, 0, 0.2); height: auto; max-height: 650px;">
                                            <div class="card-body">
                                                <!-- Card Title -->
                                                <div class="d-flex align-items-center mb-3 justify-content-start mt-3">
                                                    <div class="circle me-3" style="width: 40px; height: 40px;">
                                                        <img id="userImage" src="../img/profile/<?php echo $rowReview['cus_photo'] ? $rowReview['cus_photo'] : 'null.png'; ?>">
                                                    </div>
                                                    <div>
                                                        <p><?php echo $rowReview['cus_name'] . ' ' . $rowReview['cus_surname'];?></p>
                                                        <a href="profile_photographer.php?photographer_id=<?php echo $rowReview['photographer_id']; ?>"><p><?php echo ' รีวิวช่างภาพ '. $rowReview['photographer_name'] . ' ' . $rowReview['photographer_surname']; ?></p></a>
                                                    </div>
                                                </div>
                                                <!-- Card Text -->
                                                <p class="card-text">
                                                    <?php echo $rowReview['review_caption']; ?>
                                                </p>
                                                <p>การรีวิว <?php echo $rowReview['scor']; ?></p>
                                                <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                    <?php
                                                    $reviewLevel = $rowReview['scor'];
                                                    for ($i = 1; $i <= 5; $i++) {
                                                        if ($i <= $reviewLevel) {
                                                            echo '<i class="fas fa-star" style="color: gold; margin-right: 2px;"></i>'; // ดาวเต็มสีทอง
                                                        } else {
                                                            echo '<i class="far fa-star" style="color: gold; margin-right: 2px;"></i>'; // ดาวว่างเปล่าสีทอง
                                                        }
                                                    }
                                                    $satisfactionText = '';
                                                    switch ($reviewLevel) {
                                                        case 1:
                                                            $satisfactionText = 'ไม่พอใจอย่างยิ่ง';
                                                            break;
                                                        case 2:
                                                            $satisfactionText = 'ไม่พอใจ';
                                                            break;
                                                        case 3:
                                                            $satisfactionText = 'ปานกลาง';
                                                            break;
                                                        case 4:
                                                            $satisfactionText = 'พอใจ';
                                                            break;
                                                        case 5:
                                                            $satisfactionText = 'พอใจอย่างยิ่ง';
                                                            break;
                                                        default:
                                                            $satisfactionText = 'ไม่มีข้อมูลการรีวิว';
                                                    }
                                                    echo ' - ' . $satisfactionText;
                                                    ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                                echo '</div>';  // ปิด row
                            } else {
                                echo "ไม่มีข้อมูลรีวิว";
                            }

                            ?>
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