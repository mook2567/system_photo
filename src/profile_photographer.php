<?php
session_start();
include 'config_db.php';
require_once 'popup.php';

$sql = "SELECT * FROM `information`";
$resultInfo = $conn->query($sql);
$rowInfo = $resultInfo->fetch_assoc();

$id_photographer = $_GET['photographer_id'];
$sql = "SELECT * FROM photographer WHERE photographer_id = '$id_photographer'";
$resultPhoto = $conn->query($sql);
$rowPhoto = $resultPhoto->fetch_assoc();

$sql = "SELECT *
        FROM `booking` 
        WHERE photographer_id = $id_photographer
        ";
$resultBooking = $conn->query($sql);
$sql = "SELECT 
            (SUM(r.review_level)/COUNT(r.review_level)) AS scor, 
            p.photographer_id, 
            p.photographer_prefix, 
            p.photographer_name, 
            p.photographer_surname
        FROM 
            review r
        JOIN 
            booking b ON r.booking_id = b.booking_id 
        JOIN 
            photographer p ON b.photographer_id = p.photographer_id
        WHERE 
            p.photographer_id = $id_photographer";

$resultScor = $conn->query($sql);
$rowScor = $resultScor->fetch_assoc();
$reviewLevel = isset($rowScor['scor']) ? $rowScor['scor'] : 0;  // ค่าเฉลี่ยจริง ไม่ต้องปัดเศษ
$reviewPercentage = ($reviewLevel / 5) * 100;  // คิดเป็นเปอร์เซ็นต์

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
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

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
    <script>
        function validatePassword() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirm_password").value;
            if (password != confirmPassword) {
                Swal.fire({
                    title: 'รหัสผ่านไม่ตรงกัน',
                    icon: 'error',
                    confirmButtonText: 'ตกลง'
                });
                return false;
            }
            return true;
        }
    </script>
</head>

<body>
    <!-- Spinner Start -->
    <!-- <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-dark" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div> -->
    <!-- Spinner End -->

    <!-- Navbar Start -->
    <div class="bg-dark">
        <nav class="navbar me-5 ms-5 navbar-expand-lg navbar-dark bg-dark">
            <a href="index.php" class="navbar-brand d-flex align-items-center text-center">
                <img class="img-fluid" src="img/logo/<?php echo isset($rowInfo['information_icon']) ? $rowInfo['information_icon'] : ''; ?>" style="height: 30px;">
            </a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon text-primary"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse" style="height: 70px;">
                <div class="navbar-nav ms-auto f">
                    <div class="navbar-nav ms-auto">
                        <a href="index.php" class="nav-item nav-link">หน้าหลัก</a>
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
                        <a onclick="window.location.href='login.php'" class="nav-item nav-link">เข้าสู่ระบบ<i class="ms-1 fa-solid fa-right-to-bracket"></i></a>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->



    <div>
        <div class="row mt-3">
            <!-- Profile Start -->
            <div class="col-3">
                <div class="col-8 card-body bg-white" style="border-radius: 10px; height: auto; min-height: 700px;">
                    <div class="row mt-2 mb-2">
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="circle">
                                <img src="../img/profile/<?php echo $rowPhoto['photographer_photo'] ? $rowPhoto['photographer_photo'] : 'null.png'; ?>">
                            </div>
                        </div>
                        <div class="col-12 d-flex flex-column align-items-center justify-content-center md-3 py-3 px-4 mt-3">
                            <div class="col-8 mt-1 text-center mb-2">
                                <span style="color: black; margin-right: 5px; font-size: 18px;">
                                    <?php
                                    // แสดงดาวตามคะแนนที่ได้ (เต็ม 5 ดาว)
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= floor($reviewLevel)) {
                                            // ดาวเต็มสีทอง
                                            echo '<i class="fas fa-star" style="color: gold; margin-right: 2px;"></i>';
                                        } elseif ($i - $reviewLevel < 1) {
                                            // ดาวครึ่งดวงถ้าค่ามีทศนิยม
                                            echo '<i class="fas fa-star-half-alt" style="color: gold; margin-right: 2px;"></i>';
                                        } else {
                                            // ดาวว่างเปล่าสีทอง
                                            echo '<i class="far fa-star" style="color: gold; margin-right: 2px;"></i>';
                                        }
                                    }
                                    ?>
                                </span>
                            </div>
                            <h3><?php echo $rowPhoto['photographer_name'] . ' ' . $rowPhoto['photographer_surname']; ?></h3>
                        </div>
                        <div class="col-12 text-start mt-1">
                            <h5>ติดต่อ</h5>
                            <div class=" ms-2">
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-phone me-2"></i>
                                    <p class="mb-0"><?php echo $rowPhoto['photographer_tell']; ?></p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-envelope me-2"></i>
                                    <p class="mb-0"><?php echo $rowPhoto['photographer_email']; ?></p>
                                </div>
                            </div>
                        </div>
                        <?php
                        // Fetch all work types
                        $sql = "SELECT 
                                t.type_id, 
                                t.type_icon, 
                                t.type_work, 
                                tow_latest.photographer_id, 
                                tow_latest.type_of_work_details, 
                                tow_latest.type_of_work_rate_half_start, 
                                tow_latest.type_of_work_rate_half_end, 
                                tow_latest.type_of_work_rate_full_start, 
                                tow_latest.type_of_work_rate_full_end
                            FROM 
                                type t
                            INNER JOIN (
                                SELECT 
                                    type_id, 
                                    photographer_id, 
                                    type_of_work_details, 
                                    type_of_work_rate_half_start, 
                                    type_of_work_rate_half_end, 
                                    type_of_work_rate_full_start, 
                                    type_of_work_rate_full_end
                                FROM 
                                    type_of_work
                                WHERE 
                                    photographer_id = $id_photographer
                                    AND (type_id, photographer_id) IN (
                                        SELECT 
                                            type_id, 
                                            MAX(photographer_id) AS photographer_id
                                        FROM 
                                            type_of_work
                                        WHERE 
                                            photographer_id = $id_photographer
                                        GROUP BY 
                                            type_id
                                    )
                            ) AS tow_latest 
                            ON 
                                t.type_id = tow_latest.type_id;";

                        $resultTypeWorkDetail = $conn->query($sql);
                        $workTypes = [];
                        if ($resultTypeWorkDetail->num_rows > 0) {
                            while ($rowTypeWorkDetail = $resultTypeWorkDetail->fetch_assoc()) {
                                $workTypes[] = $rowTypeWorkDetail;
                            }
                        }

                        // Determine if more than 3 types and set flag
                        $moreThanThree = count($workTypes) > 3;
                        $displayedTypes = array_slice($workTypes, 0, 3);
                        ?>
                        <div class="col-12 text-start mt-2">
                            <h5>ประเภทงานที่รับ</h5>
                            <div class="ms-2">
                                <?php
                                foreach ($displayedTypes as $rowTypeWorkDetail) {
                                ?>
                                    <div class="mb-1">
                                        <div class="d-flex align-items-center">
                                            <i class="fa-solid fa-circle me-2" style="font-size: 5px;"></i>
                                            <b><?php echo htmlspecialchars($rowTypeWorkDetail['type_work']); ?></b>
                                        </div>
                                        <div class="ms-3">
                                            <?php if ($rowTypeWorkDetail['type_of_work_rate_half_start'] > 0) { ?>
                                                <div><?php echo 'ราคาครึ่งวัน : ' . number_format($rowTypeWorkDetail['type_of_work_rate_half_start'], 0) . ' - ' . number_format($rowTypeWorkDetail['type_of_work_rate_half_end'], 0) . ' บาท'; ?></div>
                                            <?php } ?>
                                        </div>
                                        <div class="ms-3">
                                            <?php if ($rowTypeWorkDetail['type_of_work_rate_full_start'] > 0) { ?>
                                                <div><?php echo 'ราคาเต็มวัน : ' . number_format($rowTypeWorkDetail['type_of_work_rate_full_start'], 0) . ' - ' . number_format($rowTypeWorkDetail['type_of_work_rate_full_end'], 0) . ' บาท'; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                                <div class="ms-5">
                                    <button type="button" class="btn btn-sm ms-4" data-bs-toggle="modal" data-bs-target="#moreTypesModal">
                                        <i class="fa-solid fa-magnifying-glass"></i> ดูเพิ่มเติม
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Modal for additional work types -->
                        <div class="modal fade" id="moreTypesModal" tabindex="-1" aria-labelledby="moreTypesModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="moreTypesModalLabel">ประเภทงานที่รับทั้งหมด</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <?php foreach ($workTypes as $rowTypeWorkDetail): ?>
                                                <div class="col-12 col-md-4 mb-4">
                                                    <div class="card" style="min-height: 250px;">
                                                        <div class="card-body">
                                                            <h5 class="card-title">
                                                                <i class="fa-solid fa-circle me-2" style="font-size: 8px;"></i>
                                                                <?php echo htmlspecialchars($rowTypeWorkDetail['type_work']); ?>
                                                            </h5>
                                                            <div class="mb-1">
                                                                <?php if ($rowTypeWorkDetail['type_of_work_rate_half_start'] > 0) { ?>
                                                                    <div><?php echo 'ราคาครึ่งวัน : ' . number_format($rowTypeWorkDetail['type_of_work_rate_half_start'], 0) . ' - ' . number_format($rowTypeWorkDetail['type_of_work_rate_half_end'], 0) . ' บาท'; ?></div>
                                                                <?php } ?>
                                                            </div>
                                                            <div class="mb-1">
                                                                <?php if ($rowTypeWorkDetail['type_of_work_rate_full_start'] > 0) { ?>
                                                                    <div><?php echo 'ราคาเต็มวัน : ' . number_format($rowTypeWorkDetail['type_of_work_rate_full_start'], 0) . ' - ' . number_format($rowTypeWorkDetail['type_of_work_rate_full_end'], 0) . ' บาท'; ?></div>
                                                                <?php } ?>
                                                            </div>
                                                            <?php if (!empty($rowTypeWorkDetail['type_of_work_details'])) { ?>
                                                                <p class="card-text"><?php echo 'รายละเอียด : ' . htmlspecialchars($rowTypeWorkDetail['type_of_work_details']); ?></p>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        $sql = "SELECT photographer_scope FROM `photographer` WHERE photographer.photographer_id = $id_photographer";
                        $resultScopSelect = $conn->query($sql);

                        $photographerScopes = [];
                        if ($resultScopSelect->num_rows > 0) {
                            $row = $resultScopSelect->fetch_assoc();
                            $photographerScopes = array_map('trim', explode(',', $row['photographer_scope']));
                        }
                        ?>
                        <div class="col-12 text-start mt-2">
                            <h5>ขอบเขตพื้นที่รับงาน</h5>
                            <div class="ms-2">
                                <?php if (!empty($photographerScopes)) : ?>
                                    <?php foreach ($photographerScopes as $scope) : ?>
                                        <div class="d-flex align-items-center">
                                            <i class="fa-solid fa-location-dot me-2"></i>
                                            <p class="mb-0"><?php echo htmlspecialchars($scope); ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- post -->
            <div class="col-6" style="overflow-y: scroll; height: 89vh; scrollbar-width: none; -ms-overflow-style: none;">
                <div class="row">
                    <?php
                    $sql = "SELECT 
                    po.portfolio_id, 
                    po.portfolio_photo, 
                    po.portfolio_caption, 
                    po.portfolio_date,
                    t.type_work
                FROM 
                    portfolio po
                JOIN 
                    type_of_work tow ON po.type_of_work_id = tow.type_of_work_id 
                JOIN 
                    photographer p ON p.photographer_id = tow.photographer_id
                JOIN 
                    `type` t ON t.type_id = tow.type_id
                WHERE 
                    tow.photographer_id = $id_photographer
                ORDER BY 
                    po.portfolio_id DESC";

                    $resultPost = $conn->query($sql);

                    if ($resultPost->num_rows > 0) {
                        while ($rowPost = $resultPost->fetch_assoc()) :
                    ?>
                            <div class="col-12 card-body bg-white mb-5" style="border-radius: 10px; box-shadow: 0 2px 2px rgba(0, 0, 0, 0.2); height: auto; max-height: 650px;">
                                <div class="py-1 px-5 mt-1 ms-2 mb-1 justify-content-center">
                                    <div class="d-flex align-items-center justify-content-start mt-3">
                                        <div style="display: flex; align-items: center;">
                                            <div class="circle me-3" style="width: 60px; height: 60px;">
                                                <img src="img/profile/<?php echo $rowPhoto['photographer_photo'] ? $rowPhoto['photographer_photo'] : 'null.png'; ?>" alt="Profile Photo">
                                            </div>
                                            <div class="mt-2" style="flex-grow: 1;">
                                                <b><?php echo $rowPhoto['photographer_name'] . ' ' . $rowPhoto['photographer_surname']; ?></b>
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
                                                <a data-fancybox="gallery" href="img/post/<?php echo trim($photos[$i]) ?>">
                                                    <img class="post-img" style="max-width: 100%; height: 100%;" src="img/post/<?php echo trim($photos[$i]) ?>" alt="img-post" />
                                                </a>
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                    <?php
                        endwhile;
                    } else {
                        echo '<hr><div class="col-12"><p class="text-center">ไม่มีโพสต์ให้แสดง</p></div>';
                    }
                    ?>
                </div>
            </div>


            <!-- ตารางงาน -->
            <?php
            $bookingAvailable = false; // ตั้งค่าเริ่มต้นเป็น false

            if ($resultBooking->num_rows > 0) {
                $bookingAvailable = true; // ตั้งค่าเป็น true หากมีการจอง
            }

            // คำนวณวันเริ่มต้นและวันสิ้นสุดของสัปดาห์ปัจจุบัน (อาทิตย์ถึงเสาร์)
            $today = date('Y-m-d');
            $dayOfWeek = date('w', strtotime($today));
            $startOfWeek = date('Y-m-d', strtotime($today . ' -' . $dayOfWeek . ' days'));
            $endOfWeek = date('Y-m-d', strtotime($startOfWeek . ' +6 days'));

            // ดึงข้อมูลวันที่จองทั้งหมดมาเก็บในอาร์เรย์สำหรับการตรวจสอบ
            $bookedDates = [];
            $unconfirmedDates = []; // เก็บวันที่ที่ยังไม่อนุมัติ
            $completedDates = []; // เก็บวันที่ที่จองเสร็จสิ้น

            if ($resultBooking->num_rows > 0) {
                while ($rowBooking = $resultBooking->fetch_assoc()) {
                    $startDate = $rowBooking['booking_start_date'];
                    $endDate = $rowBooking['booking_end_date'];
                    $confirmStatus = $rowBooking['booking_confirm_status'];

                    // เพิ่มช่วงเวลาการจองลงในอาร์เรย์
                    for ($date = $startDate; $date <= $endDate; $date = date('Y-m-d', strtotime($date . ' +1 day'))) {
                        if ($confirmStatus == 1) {
                            $bookedDates[] = $date;
                        } elseif ($confirmStatus == 0) {
                            $unconfirmedDates[] = $date;
                        } elseif ($confirmStatus == 3) {
                            $completedDates[] = $date;
                        }
                    }
                }
            }

            // สร้างอาร์เรย์วันทั้งหมดในสัปดาห์ปัจจุบัน
            $allDates = [];
            $currentDate = $startOfWeek;
            for ($i = 0; $i < 7; $i++) {
                $allDates[] = $currentDate;
                $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
            }

            // ลบวันจองที่ซ้ำออก
            $bookedDates = array_unique($bookedDates);
            $unconfirmedDates = array_unique($unconfirmedDates);
            $completedDates = array_unique($completedDates);
            ?>

            <div class="col-3 flex-fill" style="margin-left: auto;">
                <div class="col-8 start-0 card-header bg-white" style="border-radius: 10px; height: 700px; margin-left: auto;">
                    <div class="d-flex justify-content-center align-items-center mt-3">
                        <h4>ตารางงาน</h4>
                    </div>
                    <div class="ms-2 mt-3 mb-2">
                        <h5>ตารางงานสัปดาห์นี้</h5>
                    </div>
                    <?php
                    // ลูปผ่านแต่ละวันในสัปดาห์ปัจจุบัน
                    $currentDate = $startOfWeek;
                    for ($i = 0; $i < 7; $i++) {
                        $backgroundColor = 'rgba(144, 238, 144, 0.5)'; // สีพื้นหลังจาง (lightgreen)

                        if (in_array($currentDate, $bookedDates)) {
                            $backgroundColor = 'rgba(255, 99, 71, 0.5)'; // จางลงจาก lightcoral
                        } elseif (in_array($currentDate, $unconfirmedDates)) {
                            $backgroundColor = 'rgba(255, 160, 122, 0.5)'; // จางลงจาก lightsalmon
                        } elseif (in_array($currentDate, $completedDates)) {
                            $backgroundColor = 'rgba(173, 216, 230, 0.5)'; // จางลงจาก lightblue
                        }

                        echo "<div id='bookingStatus_$i' class='col-12 text-center mb-3' style='border-radius: 10px; padding-top: 10px; padding-bottom: 10px; background-color: {$backgroundColor};'>";
                        echo "<p class='mb-0' style='color: #000000; font-weight: bold; font-size: 16px;'>"; // ปรับสีฟอนต์และความหนา
                        echo "วันที่: " . htmlspecialchars($currentDate);

                        if (in_array($currentDate, $bookedDates)) {
                            echo " - จองแล้ว";
                        } elseif (in_array($currentDate, $unconfirmedDates)) {
                            echo " - จองแต่ยังไม่อนุมัติ";
                        } elseif (in_array($currentDate, $completedDates)) {
                            echo " - จองเสร็จสิ้นแล้ว";
                        } else {
                            echo " - ว่าง";
                        }

                        echo "</p>";
                        echo "</div>";
                        $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
                    }
                    ?>


                    <div class="justify-content-center py-4 text-center">
                        <button type="button" class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#details">
                            <i class="fa-solid fa-magnifying-glass"></i> ดูเพิ่มเติม
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="details" tabindex="-1" aria-labelledby="detailsLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="mt-3">
                        <i class="fas fa-exclamation-triangle fa-3x text-danger"></i> <!-- Error icon -->
                        <h2 class="mt-3">คุณไม่สามารถทำรายการนี้ได้</h2>
                        <h5>หากต้องการจองคิวโปรดเข้าสู่ระบบก่อน</h5>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-danger" style="width: 150px; height:45px;" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="button" class="btn btn-primary" style="width: 150px; height:45px;" onclick="redirectToLogin()">ไปยังหน้าเข้าสู่ระบบ</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
    <script>
        function redirectToLogin() {
            window.location.href = 'login.php';
        }
    </script>


</body>

</html>