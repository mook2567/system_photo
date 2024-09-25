<?php
session_start();
include '../config_db.php';
require_once '../popup.php';

$sql = "SELECT * FROM `information`";
$resultInfo = $conn->query($sql);
$rowInfo = $resultInfo ? $resultInfo->fetch_assoc() : [];

if (isset($_GET['id_photographer'])) {
    $id_photographer = intval($_GET['id_photographer']);
} else {
    die("Photographer ID is not specified.");
}

$sql = "SELECT * FROM `photographer` WHERE photographer_id =  $id_photographer";
$resultPhoto = $conn->query($sql);

if ($resultPhoto && $resultPhoto->num_rows > 0) {
    $rowPhoto = $resultPhoto->fetch_assoc();
} else {
    $rowPhoto = [];
}

if (isset($_SESSION['customer_login'])) {
    $email = $_SESSION['customer_login'];
    $stmtCus = $conn->prepare("SELECT * FROM customer WHERE cus_email = ?");
    $stmtCus->bind_param("s", $email);
    $stmtCus->execute();
    $resultCus = $stmtCus->get_result();
    $rowCus = $resultCus ? $resultCus->fetch_assoc() : [];
    $id_cus = $rowCus['cus_id'] ?? null;
}

// Initialize the $booking array to avoid the undefined variable error
$booking = array();

$id_photographer = isset($_GET['id_photographer']) ? intval($_GET['id_photographer']) : null;

if ($id_photographer !== null) {
    $stmt = $conn->prepare("SELECT * FROM booking WHERE photographer_id = ?");
    $stmt->bind_param("i", $id_photographer);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $booking[] = $row;
    }
}

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
$bookings_json = json_encode($booking);

$fullcalendar_path = "fullcalendar-4.4.2/packages/";
?>
<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset="utf-8">
    <title>Photo Match</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="icon" type="image/png" href="../img/icon-logo.png">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

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


    <link href='<?= $fullcalendar_path ?>/core/main.css' rel='stylesheet' />
    <link href='<?= $fullcalendar_path ?>/daygrid/main.css' rel='stylesheet' />

    <script src='<?= $fullcalendar_path ?>/core/main.js'></script>
    <script src='<?= $fullcalendar_path ?>/daygrid/main.js'></script>

    <style type="text/css">
        body {
            font-family: 'Athiti', sans-serif;
            overflow-x: hidden;
        }

        .f {
            font-family: 'Athiti', sans-serif;
        }

        #calendar {
            width: 800px;
            margin: auto;
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
    </style>

</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-dark" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Navbar Start -->
        <div class="container-fluid nav-bar bg-transparent">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-0 px-4">
                <a href="index.php" class="navbar-brand d-flex align-items-center text-center" style="height: 70px;">
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
    </div>
    <!-- Navbar End -->

    <!-- Header Start -->
    <div class="container-fluid header bg-primary p-1" style="height: 300px;">
        <div class="row g-1 align-items-center flex-column-reverse flex-md-row">
            <div class="col-md-5 p-5 mt-lg-5 text-md-end">
                <br><br>
                <h1 class="display-7 animated fadeIn mb-1 text-white f">ตารางงานของช่างภาพ</h1>
                <a href="profile_photographer.php?photographer_id=<?php echo $rowPhoto['photographer_id']; ?>" style="text-decoration: none;">
                    <h1 class="display-7 animated fadeIn mb-1 text-white f m-0">
                        คุณ<?php echo $rowPhoto['photographer_name'] . ' ' . $rowPhoto['photographer_surname']; ?>
                    </h1>
                </a>
            </div>
            <div class="col-5 p-5 mt-5 text-end text-white mt-lg-5">
                <h5 class="mt-5 text-white">หมายเหตุ <i class="fa-solid fa-circle-exclamation"></i></h5>
                <h5 class="text-white">สีฟ้า คือ รายการจองที่รออนุมัติ</h5>
                <h5 class="text-white">สีเหลือง คือ รายการจองครึ่งวัน</h5>
                <h5 class="text-white">สีส้ม คือ รายการจองเต็มวัน</h5>
            </div>
        </div>
    </div>

    <!-- Header End -->
    <div class="bg-white" style="height: auto;"><br>
        <div class=" mt-2">
            <div class="col-12 mt-3">
                <div class="bg-white">
                    <div class="d-flex justify-content-end">
                        <div id='calendar' style="width: 45%;" class="bg-white ms-auto">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mb-5 mt-3 container-center text-center">
            <div class="col-md-12">
                <button onclick="window.history.back();" class="btn btn-danger me-4" style="width: 150px; height:45px;">ย้อนกลับ</button>
                <button type="button" data-bs-toggle="modal" data-bs-target="#details" class="btn btn-primary" style="width: 150px; height: 45px;">จองคิว</button>
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
        </div><!-- Footer Start -->
        <div class="container-fluid bg-dark text-white-50 footer wow fadeIn">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="#">2024 Photo Match</a>, All Right Reserved.
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

    <!-- FullCalendar JavaScript -->
    <script src='<?= $fullcalendar_path ?>/core/main.js'></script>
    <script src='<?= $fullcalendar_path ?>/daygrid/main.js'></script>

    <!-- Template Javascript -->
    <script src="../js/main.js"></script>

    <script type="text/javascript">
        $(function() {
            // Get the booking data from PHP
            var bookings = <?php echo json_encode($booking); ?>;

            // Format the booking data for FullCalendar
            var events = bookings.map(function(booking) {
                var startDate = new Date(booking.booking_start_date + 'T' + booking.booking_start_time);
                var endDate = new Date(booking.booking_end_date + 'T' + booking.booking_end_time);
                var isHalfDay = (startDate.getHours() < 12 && endDate.getHours() <= 12) ||
                    (startDate.getHours() >= 12 && endDate.getHours() > 12);

                var eventColor;
                var eventTextColor;
                var eventTitle;

                // Determine color and title based on booking confirmation status
                if (booking.booking_confirm_status == 0) { // Unapproved bookings
                    eventColor = '#edf6fa'; // Light gray
                    eventTextColor = 'black';
                    eventTitle = 'รออนุมัติ';
                } else { // Approved bookings
                    eventColor = isHalfDay ? '#e9ec86' : '#ecab86';
                    eventTextColor = isHalfDay ? 'black' : 'black';
                    eventTitle = isHalfDay ? 'ครึ่งวัน' : 'เต็มวัน';
                }

                const startTime = startDate.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });
                const endTime = endDate.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                return {
                    title: eventTitle + ' (' + startTime + ' - ' + endTime + ')',
                    start: startDate.toISOString(),
                    end: endDate.toISOString(),
                    color: eventColor,
                    textColor: eventTextColor,
                    description: booking.booking_details,
                    extendedProps: {
                        startTime: startTime,
                        endTime: endTime
                    }
                };
            });

            // Initialize the FullCalendar
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: ['dayGrid', 'interaction'],
                editable: true,
                events: events
            });

            // Render the calendar
            calendar.render();
        });
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

</body>

</html>