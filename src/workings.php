<?php
session_start();
include 'config_db.php';
require_once 'popup.php';

$sql = "SELECT * FROM `information`";
$resultInfo = $conn->query($sql);
$rowInfo = $resultInfo->fetch_assoc();

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
            background-image: url('../img/bgIndex6.jpg');
            background-attachment: fixed;
            background-size: cover;
            /* เพิ่มการปรับแต่งในการขยับภาพตามต้องการ */
        }

        .row-scroll {
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
            /* เพื่อการเลื่อนสามารถทำงานได้ดีบนอุปกรณ์มือถือ iOS */
        }

        .col-md-4 {
            flex: 0 0 calc(33.33% - 10px);
            max-width: calc(33.33% - 10px);
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
                        <a href="index.php" class="nav-item nav-link">หน้าหลัก</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown">รายการ</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="search.php" class="dropdown-item">ค้นหาช่างภาพ</a>
                                <a href="type.php" class="dropdown-item">ประเภทงาน</a>
                                <a href="workings.php" class="dropdown-item active">ผลงานช่างภาพ</a>
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
                <h1 class="display-5 animated fadeIn text-white mb-4 f">ผลงานช่างภาพ</h1>
                <p class="text-white">คุณสามารถเลือกดูผลงานช่างภาพตามความสนใจของคุณได้</p>
            </div>
        </div>
        <!-- Header End -->

        <!-- Search Start -->
        <!-- <div class="mt-5 wow fadeIn" style="background-color: rgba(	250,250,250, 0.4);padding: 35px;" data-wow-delay="0.1s">
            <div class="container">
                <div class="row flex-row g-2 align-items-center">
                    <h2 class="text-white f">ค้นหาผลงานช่างภาพ</h2>
                    <div class="col-md-3">
                        <select class="form-select border-0 py-3">
                            <option selected>ประเภทงาน</option>
                            <option value="1">งานแต่งงาน</option>
                            <option value="2">งานพรีเวดดิ้ง</option>
                            <option value="3">งานอีเว้นท์</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" id="dateRangePicker" class="form-control border-0 py-3 f bg-white" placeholder="ช่วงวันที่โพสต์">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select border-0 py-3">
                            <option selected>คะแนนช่างภาพ</option>
                            <option value="1">คะแนนจากมากไปน้อย</option>
                            <option value="1">คะแนนจากน้อยไปมาก</option>
                            <option value="2">5</option>
                            <option value="3">4</option>
                            <option value="4">3</option>
                            <option value="6">2</option>
                            <option value="6">1</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <form action="search.php" method="GET">
                            <button type="submit" class="btn btn-primary border-0 w-100 py-3" name="search">ค้นหา</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>-->
    </div>
    <!-- Search End -->

    <div class="row mt-3">
        <div class="col-3">
        </div>

        <!-- post -->
        <div class="col-6" style="overflow-y: scroll; height: 89vh; scrollbar-width: none; -ms-overflow-style: none;">

            <div class="row">




                <div class="col-12 mt-3">
                    <p>โพสต์อื่น ๆ</p>
                </div>
                <!-- POST -->

                <?php




                $sql = "SELECT 
                    po.portfolio_id, 
                    po.portfolio_photo, 
                    po.portfolio_caption, 
                    po.portfolio_date,
                    t.type_work,
                    p.photographer_id
                FROM 
                    portfolio po
                JOIN 
                    type_of_work tow ON po.type_of_work_id = tow.type_of_work_id 
                JOIN 
                    photographer p ON p.photographer_id = tow.photographer_id
                JOIN 
                    `type` t ON t.type_id = tow.type_id

                ORDER BY 
                    po.portfolio_id DESC";

                $resultPost = $conn->query($sql);
                ?>
                <?php while ($rowPost = $resultPost->fetch_assoc()) :

                    $account_id = $rowPost['photographer_id'];

                    $sql1 = "SELECT * FROM photographer WHERE photographer_id = $account_id";
                    $resultPhoto1 = $conn->query($sql1);
                    $rowPhoto1 = $resultPhoto1->fetch_assoc();
                ?>

                    <div class="col-12 card-body bg-white mt-2 mb-5" style="border-radius: 10px; box-shadow: 0 2px 2px rgba(0, 0, 0, 0.2); height: auto; max-height: 650; ">
                        <div class="py-1 px-5 mt-1 ms-2 mb-1 justify-content-center">
                            <div class="d-flex align-items-center justify-content-start mt-3">
                                <div style="display: flex; align-items: center;">
                                    <div class="circle me-3" style="width: 60px; height: 60px;">
                                        <img src="../img/profile/<?php echo $rowPhoto1['photographer_photo'] ? $rowPhoto1['photographer_photo'] : 'null.png'; ?>">
                                    </div>
                                    <div class="mt-2" style="flex-grow: 1;">
                                        <b><?php echo $rowPhoto1['photographer_name'] . ' ' . $rowPhoto1['photographer_surname']; ?></b>
                                        <p style="margin-bottom: 0;"><?php
                                                                        // แปลงวันที่ในรูปแบบของ portfolio_date ให้เป็นภาษาไทย
                                                                        $months_th = array(
                                                                            '01' => 'มกราคม',
                                                                            '02' => 'กุมภาพันธ์',
                                                                            '03' => 'มีนาคม',
                                                                            '04' => 'เมษายน',
                                                                            '05' => 'พฤษภาคม',
                                                                            '06' => 'มิถุนายน',
                                                                            '07' => 'กรกฎาคม',
                                                                            '08' => 'สิงหาคม',
                                                                            '09' => 'กันยายน',
                                                                            '10' => 'ตุลาคม',
                                                                            '11' => 'พฤศจิกายน',
                                                                            '12' => 'ธันวาคม'
                                                                        );

                                                                        $date_thai = date('d', strtotime($rowPost['portfolio_date'])) . ' ' .
                                                                            $months_th[date('m', strtotime($rowPost['portfolio_date']))] . ' ' .
                                                                            (date('Y', strtotime($rowPost['portfolio_date'])) + 543); // ปี พ.ศ.

                                                                        echo $rowPost['type_work'] . ' (Post เมื่อ ' . $date_thai . ')';
                                                                        ?></p>

                                    </div>
                                </div>
                            </div>
                            <div>
                                <p class="mt-4 post-text center" style="font-size: 18px;"><?php echo $rowPost['portfolio_caption'] ?></p>
                            </div>
                            <div class="row row-scroll" style="display: flex; flex-wrap: nowrap;">
                                <?php
                                $photos = explode(',', $rowPost['portfolio_photo']);
                                $max_photos = min(10, count($photos)); // จำกัดจำนวนภาพไม่เกิน 10
                                for ($i = 0; $i < $max_photos; $i++) : ?>
                                    <div class="col-md-4 mb-2" style="flex: 0 0 calc(33.33% - 10px); max-width: calc(33.33% - 10px);">
                                        <a data-fancybox="gallery" href="../img/post/<?php echo trim($photos[$i]) ?>">
                                            <img class="post-img" style="max-width: 100%; height: 100%;" src="../img/post/<?php echo trim($photos[$i]) ?>" alt="img-post" />
                                        </a>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile;
                ?>
            </div>
        </div>
    </div>



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

    <!-- select date -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.6/flatpickr.min.js"></script>
    <script>
        flatpickr("#dateRangePicker", {
            mode: "range",
            dateFormat: "Y-m-d"
        });
    </script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>