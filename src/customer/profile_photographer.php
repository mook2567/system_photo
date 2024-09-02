<?php
session_start();
include '../config_db.php';
require_once '../popup.php';

$sql = "SELECT * FROM `information`";
$resultInfo = $conn->query($sql);
$rowInfo = $resultInfo->fetch_assoc();

$id_photographer = $_GET['photographer_id'];
$sql = "SELECT * FROM photographer WHERE photographer_id = '$id_photographer'";
$resultPhoto = $conn->query($sql);
$rowPhoto = $resultPhoto->fetch_assoc();

if (isset($_SESSION['customer_login'])) {
    $email = $_SESSION['customer_login'];
    $sql = "SELECT * FROM customer WHERE cus_email LIKE '$email'";
    $resultCus = $conn->query($sql);
    $rowCus = $resultCus->fetch_assoc();
    $id_cus = $rowCus['cus_id'];
}

$sql = "SELECT *
        FROM `booking` 
        WHERE photographer_id = $id_photographer
        -- AND booking_confirm_status = all
        -- AND (
        --     (booking_start_date <= CURDATE() + INTERVAL (6 - WEEKDAY(CURDATE())) DAY 
        --     AND booking_end_date >= CURDATE() - INTERVAL WEEKDAY(CURDATE()) DAY)
        --     OR
        --     (booking_start_date <= CURDATE() - INTERVAL WEEKDAY(CURDATE()) DAY 
        --     AND booking_end_date >= CURDATE() - INTERVAL WEEKDAY(CURDATE()) DAY - INTERVAL 1 WEEK)
        -- )
        ";
$resultBooking = $conn->query($sql);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['submit_book'])) {
        $location = $_POST["location"];
        $details = $_POST['details'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $start_time = $_POST["start_time"];
        $end_time = $_POST["end_time"];
        $cus_id = $_POST['cus_id'];
        $date = $_POST["date"];
        $type = $_POST["type"];
        $id_photographer = $_POST["photographer_id"]; // Assumes photographer_id is passed in the form

        // ตรวจสอบการจองที่ซ้ำกันสำหรับ photographer_id เดียวกัน
        $check_stmt = $conn->prepare("SELECT COUNT(*) FROM `booking` WHERE (`booking_start_date` BETWEEN ? AND ? OR `booking_end_date` BETWEEN ? AND ?) AND `photographer_id` = ? AND `booking_confirm_status` = 0");
        if ($check_stmt) {
            $check_stmt->bind_param("ssssi", $start_date, $end_date, $start_date, $end_date, $id_photographer);
            $check_stmt->execute();
            $check_stmt->bind_result($count);
            $check_stmt->fetch();
            $check_stmt->close();

            if ($count > 0) {
                // มีการจองซ้ำอยู่แล้ว
?>
                <script>
                    setTimeout(function() {
                        Swal.fire({
                            title: '<div class="t1">ทำการจองไม่สำเร็จ</div>',
                            text: 'เนื่องจากมีรายการจองของลูกค้าท่านอื่นที่รออนุมัติอยู่สำหรับช่างภาพท่านนี้',
                            icon: 'error',
                            confirmButtonText: 'ตกลง',
                            allowOutsideClick: true,
                            allowEscapeKey: true,
                            allowEnterKey: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "";
                            }
                        });
                    });
                </script>
                <?php
            } else {
                // ไม่มีการจองซ้ำ ทำการบันทึกการจอง
                $stmt = $conn->prepare("INSERT INTO `booking` (`booking_location`, `booking_details`, `booking_start_date`, `booking_end_date`, `booking_start_time`, `booking_end_time`, `booking_date`, `photographer_id`, `cus_id`, `type_of_work_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param("sssssssiii", $location, $details, $start_date, $end_date, $start_time, $end_time, $date, $id_photographer, $cus_id, $type);
                    if ($stmt->execute()) {
                ?>
                        <script>
                            console.log('Swal script should be executed');
                            setTimeout(function() {
                                Swal.fire({
                                    title: '<div class="t1">บันทึกการจองสำเร็จ</div>',
                                    icon: 'success',
                                    confirmButtonText: 'ไปยังหน้ารายการจอง',
                                    allowOutsideClick: true,
                                    allowEscapeKey: true,
                                    allowEnterKey: false
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "bookingLists.php";
                                    }
                                });
                            }, 0);
                        </script>
                    <?php
                    } else {
                    ?>
                        <script>
                            setTimeout(function() {
                                Swal.fire({
                                    title: '<div class="t1">เกิดข้อผิดพลาดในการบันทึกการจอง</div>',
                                    icon: 'error',
                                    confirmButtonText: 'ออก',
                                    allowOutsideClick: true,
                                    allowEscapeKey: true,
                                    allowEnterKey: false
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "";
                                    }
                                });
                            });
                        </script>
<?php
                    }
                    $stmt->close();
                } else {
                    echo "Error preparing statement: " . $conn->error;
                }
            }
        } else {
            echo "Error preparing check statement: " . $conn->error;
        }
    }
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        function validateForm() {
            var location = document.getElementById("location").value;
            var details = document.getElementById("details").value;
            var startDate = document.getElementById("start_date").value;
            var endDate = document.getElementById("end_date").value;
            var startTime = document.getElementById("start_time").value;
            var endTime = document.getElementById("end_time").value;
            var cusId = document.getElementById("cus_id").value;
            var date = document.getElementById("date").value;
            var type = document.getElementById("type").value;

            if (!location || !details || !startDate || !endDate || !startTime || !endTime || !cusId || !date || !type) {
                var missingFields = [];
                if (!location) missingFields.push("Location");
                if (!details) missingFields.push("Details");
                if (!startDate) missingFields.push("Start Date");
                if (!endDate) missingFields.push("End Date");
                if (!startTime) missingFields.push("Start Time");
                if (!endTime) missingFields.push("End Time");
                if (!cusId) missingFields.push("Customer ID");
                if (!date) missingFields.push("Date");
                if (!type) missingFields.push("Type");

                Swal.fire({
                    title: 'Missing Fields',
                    text: 'Please fill in the following fields: ' + missingFields.join(", "),
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return false;
            }
            return true;
        }
    </script>

    <style>
        body {
            font-family: 'Athiti', sans-serif;
            overflow-x: hidden;
            background: #E5E4E2;
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
            <a href="index.html" class="navbar-brand d-flex align-items-center text-center">
                <img class="img-fluid" src="../img/logo/<?php echo isset($rowInfo['information_icon']) ? $rowInfo['information_icon'] : ''; ?>" style="height: 30px;">
            </a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon text-primary"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse" style="height: 70px;">
                <div class="navbar-nav ms-auto f">
                    <a href="index.php" class="nav-item nav-link ">หน้าหลัก</a>
                    <a href="search.php" class="nav-item nav-link">ค้นหาช่างภาพ</a>
                    <a href="workings.php" class="nav-item nav-link">ผลงานช่างภาพ</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">รายการจองคิวช่างภาพ</a>
                        <div class="dropdown-menu rounded-0 m-0">
                            <!-- <a href="bookingLists.php" class="dropdown-item">รายการจองคิวทั้งหมด</a> -->
                            <a href="payLists.php" class="dropdown-item ">รายการจองคิวที่ต้องชำระเงิน/ค่ามัดจำ</a>
                            <!-- <a href="reviewLists.php" class="dropdown-item">รายการจองคิวที่ต้องรีวิว</a> -->
                            <!-- <a href="bookingFinishedLists.php" class="dropdown-item">รายการจองคิวที่เสร็จสิ้นแล้ว</a> -->
                            <a href="bookingRejectedLists.php" class="dropdown-item">รายการจองคิวที่ถูกปฏิเสธ</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">โปรไฟล์</a>
                        <div class="dropdown-menu rounded-0 m-0">
                            <a href="profile.php" class="dropdown-item">โปรไฟล์</a>
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
                        <div class="col-12 text-center md-3 py-3 px-4 mt-3">
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
                    <!-- POST -->
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
                                                <img src="../img/profile/<?php echo $rowPhoto['photographer_photo'] ? $rowPhoto['photographer_photo'] : 'null.png'; ?>" alt="Profile Photo">
                                            </div>
                                            <div class="mt-2" style="flex-grow: 1;">
                                                <b><?php echo $rowPhoto['photographer_name'] . ' ' . $rowPhoto['photographer_surname']; ?></b>
                                                <p style="margin-bottom: 0;">
                                                    <?php
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
                                                    ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="mt-4 post-text center" style="font-size: 18px;"><?php echo $rowPost['portfolio_caption']; ?></p>
                                    </div>
                                    <div class="row row-scroll" style="display: flex; flex-wrap: nowrap;">
                                        <?php
                                        $photos = explode(',', $rowPost['portfolio_photo']);
                                        $max_photos = min(10, count($photos)); // จำกัดจำนวนภาพไม่เกิน 10
                                        for ($i = 0; $i < $max_photos; $i++) : ?>
                                            <div class="col-md-4 mb-2" style="flex: 0 0 calc(33.33% - 10px); max-width: calc(33.33% - 10px);">
                                                <a data-fancybox="gallery" href="../img/post/<?php echo trim($photos[$i]); ?>">
                                                    <img class="post-img" style="max-width: 100%; height: 100%;" src="../img/post/<?php echo trim($photos[$i]); ?>" alt="img-post" />
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

            if ($resultBooking->num_rows > 0) {
                while ($rowBooking = $resultBooking->fetch_assoc()) {
                    $startDate = $rowBooking['booking_start_date'];
                    $endDate = $rowBooking['booking_end_date'];
                    $confirmStatus = $rowBooking['booking_confirm_status'];

                    // เพิ่มช่วงเวลาการจองลงในอาร์เรย์
                    for ($date = $startDate; $date <= $endDate; $date = date('Y-m-d', strtotime($date . ' +1 day'))) {
                        if ($confirmStatus == 1) {
                            $bookedDates[] = $date;
                        } else {
                            $unconfirmedDates[] = $date;
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

            ?>

            <div class="col-3 flex-fill" style="margin-left: auto;">
                <div class="col-8 start-0 card-header bg-white" style="border-radius: 10px; height: 700px; margin-left: auto;">
                    <div class="d-flex justify-content-center align-items-center mt-3">
                        <h4>ตารางงาน</h4>
                    </div>
                    <div class="ms-2 mb-2">
                        ตารางงานสัปดาห์นี้
                    </div>
                    <?php
                    // ลูปผ่านแต่ละวันในสัปดาห์ปัจจุบัน
                    $currentDate = $startOfWeek;
                    for ($i = 0; $i < 7; $i++) {
                        $backgroundColor = 'lightgreen'; // สีพื้นหลังเริ่มต้น

                        if (in_array($currentDate, $bookedDates)) {
                            $backgroundColor = 'lightcoral'; // มีการจองแล้ว
                        } elseif (in_array($currentDate, $unconfirmedDates)) {
                            $backgroundColor = 'lightsalmon'; // มีการจองแต่ยังไม่อนุมัติ
                        }

                        echo "<div id='bookingStatus_$i' class='col-12 text-center mb-3' style='border-radius: 10px; padding-top: 10px; padding-bottom: 10px; background-color: {$backgroundColor};'>";
                        echo "<p class='mb-0'>";
                        echo "วันที่: " . htmlspecialchars($currentDate);

                        if (in_array($currentDate, $bookedDates)) {
                            echo " - จองแล้ว";
                        } elseif (in_array($currentDate, $unconfirmedDates)) {
                            echo " - จองแต่ยังไม่อนุมัติ";
                        } else {
                            echo " - ว่าง";
                        }

                        echo "</p>";
                        echo "</div>";
                        $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
                    }
                    ?>
                    <div class="justify-content-center py-4 text-center">
                        <div class="row justify-content-center">
                            <div class="col-5">
                                <button type="button" class="btn btn-dark btn-sm" style="width: 100px; height:30px;" onclick="window.location.href='table.php?id_photographer=<?php echo $rowPhoto['photographer_id']; ?>'">
                                    <i class="fa-solid fa-magnifying-glass"></i> ดูเพิ่มเติม
                                </button>
                            </div>
                            <div class="col-5">
                                <button type="button" class="btn btn-dark btn-sm" data-bs-toggle="modal" style="width: 100px; height:30px;" data-bs-target="#details">
                                    <i class="fa-solid fa-bookmark"></i> จองคิว
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="details" tabindex="-1" aria-labelledby="detailsLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsLabel"><b><i class="fas fa-clipboard-list"></i>&nbsp;&nbsp;จองคิวช่างภาพ</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="bookingForm" action="" method="POST" onsubmit="return validateForm()">
                    <div class="modal-body" style="height: auto;">
                        <div class="mt-2 container-md">
                            <div class="mt-3 col-md-12 container-fluid">
                                <div class="col-12">
                                    <div class="row mt-2">
                                        <div class="col-2">
                                            <label for="prefix" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px;font-size: 13px;"> คำนำหน้า</span>
                                            </label>
                                            <input type="text" name="prefix" class="form-control mt-1" value="<?php echo $rowCus['cus_prefix']; ?>" readonly>
                                        </div>
                                        <div class="col-5">
                                            <label for="name" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px;font-size: 13px;">ชื่อ</span>
                                            </label>
                                            <input type="text" name="name" class="form-control mt-1" value="<?php echo $rowCus['cus_name']; ?>" readonly>
                                        </div>
                                        <div class="col-5">
                                            <label for="surname" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; font-size: 13px;">นามสกุล</span>
                                            </label>
                                            <input type="text" name="surname" class="form-control mt-1" value="<?php echo $rowCus['cus_surname']; ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mt-3">
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label for="booking-start-date" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px;font-size: 13px;">เวลาที่ใช้บริการ</span>
                                            </label>
                                            <div class="form-group col-12">
                                                <input class="form-check-input me-1" type="radio" id="half" name="userIcon" value="half" checked>ครึ่งวัน (4 ชั่วโมงต่อวัน)
                                                <input class="form-check-input me-1 ms-5" type="radio" id="full" name="userIcon" value="full">เต็มวัน (8 ชั่วโมงต่อวัน)
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-4 text-center">
                                            <label for="booking-start-date" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px;font-size: 13px;">วันที่เริ่มจอง</span>
                                                <span style="color: red;">*</span>
                                            </label>
                                            <input type="date" id="start_date" name="start_date" class="form-control mt-1" style="resize: none;" required>
                                        </div>
                                        <div class="col-4 text-center">
                                            <label for="booking-end-date" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px;font-size: 13px;">วันที่สิ้นสุดการจอง</span>
                                                <span style="color: red;">*</span>
                                            </label>
                                            <input type="date" id="end_date" name="end_date" class="form-control mt-1" style="resize: none;" required>
                                        </div>
                                        <div class="col-2 text-center">
                                            <label for="start_time" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px; font-size: 13px;">เวลาเริ่มต้นงาน</span>
                                                <span style="color: red;">*</span>
                                            </label>
                                            <input type="time" id="start_time" name="start_time" class="form-control mt-1" style="resize: none;" required oninput="calculateEndTime()">
                                        </div>
                                        <div class="col-2 text-center">
                                            <label for="end_time" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px; font-size: 13px;">เวลาสิ้นสุดงาน</span>
                                                <!-- <span style="color: red;">*</span> -->
                                            </label>
                                            <input type="time" id="end_time" name="end_time" class="form-control mt-1" style="resize: none;" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mt-3">
                                    <div class="row">
                                        <div class="col-md-10 text-center">
                                            <label for="location" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px;font-size: 13px;">สถานที่</span>
                                                <span style="color: red;">*</span>
                                            </label>
                                            <input type="text" id="location" name="location" class="form-control mt-1" placeholder="กรุณากรอกสถานที่" style="resize: none;" required>
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <label for="type" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px;font-size: 13px;">ประเภทงาน</span>
                                                <span style="color: red;">*</span>
                                            </label>
                                            <select class="form-select border-1 py-2" id="type" name="type" required>
                                                <option value="">เลือกประเภทงาน</option>
                                                <?php
                                                // ทำการเชื่อมต่อฐานข้อมูล ($conn) ก่อน query
                                                $sql = "SELECT t.type_id, t.type_work, MAX(tow.photographer_id) AS photographer_id
                                                        FROM `type` t
                                                        INNER JOIN type_of_work tow ON t.type_id = tow.type_id
                                                        WHERE tow.photographer_id = $id_photographer
                                                        GROUP BY t.type_id, t.type_work;";
                                                $resultTypeWork = $conn->query($sql);

                                                // ตรวจสอบว่ามีข้อมูลที่ได้จาก query หรือไม่
                                                if ($resultTypeWork->num_rows > 0) {
                                                    while ($rowTypeWork = $resultTypeWork->fetch_assoc()) {
                                                        echo '<option value="' . htmlspecialchars($rowTypeWork['type_id']) . '">' . htmlspecialchars($rowTypeWork['type_work']) . '</option>';
                                                    }
                                                } else {
                                                    echo '<option value="">ไม่มีประเภทงาน ช่างภาพไม่มีประเภทงานที่รับ</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3 text-center">
                                    <label for="Information_caption" style="font-weight: bold; display: flex; align-items: center;">
                                        <span style="color: black; margin-right: 5px;font-size: 13px;">คำอธิบาย</span>
                                        <span style="color: red;">*</span>
                                    </label>
                                    <textarea id="details" name="details" class="form-control mt-1" placeholder="กรุณากรอกคำอธิบาย" style="resize: none; height: 100px;" required></textarea>
                                </div>
                                <div class="col-12">
                                    <div class="row mt-3">
                                        <div class="col-5">
                                            <label for="tell" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px;font-size: 13px;">เบอร์โทรศัพท์มือถือ</span>
                                            </label>
                                            <input type="text" id="tell" name="tell" class="form-control mt-1" value="<?php echo $rowCus['cus_tell']; ?>" style="resize: none;" readonly>
                                        </div>
                                        <div class="col-5 text-center">
                                            <label for="email" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px;font-size: 13px;">อีเมล</span>
                                            </label>
                                            <input type="email" id="email" name="email" class="form-control mt-1" value="<?php echo $rowCus['cus_email']; ?>" style="resize: none;" readonly>
                                        </div>
                                        <div class="col-2">
                                            <label for="date-saved" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px;font-size: 13px;">วันที่บันทึก</span>
                                            </label>
                                            <input type="date" id="date-saved" name="date" class="form-control mt-1" style="resize: none;" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="photographer_id" value="<?php echo $rowPhoto['photographer_id']; ?>">
                            <input type="hidden" name="cus_id" value="<?php echo $rowCus['cus_id']; ?>">
                            <div class="modal-footer mt-5 justify-content-center">
                                <button type="button" class="btn btn-danger" style="width: 150px; height:45px;" data-bs-dismiss="modal">ยกเลิก</button>
                                <button type="submit" name="submit_book" class="btn btn-primary" style="width: 150px; height:45px;">จองคิว</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Profile End -->

    <!-- Footer Start -->
    <!-- <footer class="footer">
        &copy; <a class="border-bottom text-dark" href="#">2024 Photo Match</a>, All Right Reserved.
    </footer> -->
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

    <script>
        // ฟังก์ชันเพื่อกำหนดวันที่ปัจจุบันให้กับฟิลด์ input
        function setDefaultDate() {
            const dateInput = document.getElementById('date-saved');
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0'); // เดือนเริ่มต้นที่ 0
            const day = String(today.getDate()).padStart(2, '0');

            const formattedDate = `${year}-${month}-${day}`;
            dateInput.value = formattedDate;
        }

        // เรียกใช้ฟังก์ชันเมื่อโหลดหน้าเว็บ
        window.onload = setDefaultDate;
    </script>
    
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        // Disable past dates
        const today = new Date().toISOString().split('T')[0];
        startDateInput.setAttribute('min', today);
        endDateInput.setAttribute('min', today);

        // Fetch unavailable dates from the server
        function fetchUnavailableDates() {
            return fetch('path_to_your_php_file.php')
                .then(response => response.json())
                .then(data => data)
                .catch(error => {
                    console.error('Error fetching unavailable dates:', error);
                    return [];
                });
        }

        // Update date pickers with unavailable dates
        function updateDatePickers(unavailableDates) {
            const minDate = new Date(startDateInput.getAttribute('min'));
            const maxDate = new Date();

            // Update end date minimum based on start date
            function updateEndDateMin() {
                const startDate = new Date(startDateInput.value);
                if (startDateInput.value) {
                    endDateInput.setAttribute('min', startDate.toISOString().split('T')[0]);
                } else {
                    endDateInput.setAttribute('min', today);
                }
            }

            // Disable unavailable dates in start date input
            startDateInput.addEventListener('input', function() {
                const selectedDate = new Date(startDateInput.value);
                if (unavailableDates.includes(selectedDate.toISOString().split('T')[0])) {
                    alert('Selected start date is unavailable.');
                    startDateInput.value = '';
                }
                updateEndDateMin(); // Update min date for end date
            });

            // Disable unavailable dates in end date input
            endDateInput.addEventListener('input', function() {
                const selectedDate = new Date(endDateInput.value);
                if (unavailableDates.includes(selectedDate.toISOString().split('T')[0])) {
                    alert('Selected end date is unavailable.');
                    endDateInput.value = '';
                }
            });
        }

        // Initialize
        fetchUnavailableDates().then(unavailableDates => {
            updateDatePickers(unavailableDates);
        });
    });
</script>
    <script>
        function calculateEndTime() {
            // Get the start time input value
            const startTimeInput = document.getElementById('start_time');
            const endTimeInput = document.getElementById('end_time');
            const durationRadios = document.querySelectorAll('input[name="userIcon"]');

            if (startTimeInput.value) {
                // Get selected duration
                let hoursToAdd = 4; // Default to half-day

                durationRadios.forEach(radio => {
                    if (radio.checked) {
                        hoursToAdd = radio.value === 'full' ? 8 : 4;
                    }
                });

                // Create Date objects for the start time and the end time
                const startTime = new Date(`1970-01-01T${startTimeInput.value}:00`);
                const endTime = new Date(startTime.getTime() + hoursToAdd * 60 * 60 * 1000); // Add hours

                // Format the end time as HH:MM
                const hours = String(endTime.getHours()).padStart(2, '0');
                const minutes = String(endTime.getMinutes()).padStart(2, '0');
                const formattedEndTime = `${hours}:${minutes}`;

                // Set the value of the end time input
                endTimeInput.value = formattedEndTime;
            }
        }

        // Add event listener for duration radio buttons
        document.addEventListener('DOMContentLoaded', function() {
            const durationRadios = document.querySelectorAll('input[name="userIcon"]');
            durationRadios.forEach(radio => {
                radio.addEventListener('change', calculateEndTime);
            });
        });
    </script>

</html>