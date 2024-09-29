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
$sql1 = "SELECT b.*, c.cus_prefix, c.cus_name, c.cus_surname, c.cus_tell, c.cus_email, t.type_work, p.*, t.type_id
        FROM booking b
        JOIN customer c ON b.cus_id = c.cus_id
        JOIN `type` t ON b.type_of_work_id = t.type_id
        JOIN type_of_work tow ON tow.type_of_work_id = b.type_of_work_id
        JOIN photographer p ON p.photographer_id = b.photographer_id
        WHERE c.cus_id = $id_cus
        AND b.booking_confirm_status = '0'
";
$resultBooking = $conn->query($sql1);
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['submit_booking_edit'])) {
        $start_date = $_POST['start_date'];
        $start_time = $_POST['start_time'];
        $end_date = $_POST['end_date'];
        $end_time = $_POST['end_time'];
        $location = $_POST['location'];
        $type = $_POST['type'];
        $details = $_POST['details'];
        $booking_id = $_POST['booking_id'];
        $photographer_id = $_POST['photographer_id']; // Assuming this is passed from the form

        // Check if the start and end dates have been changed
        $check_date_change_sql = "SELECT `booking_start_date`, `booking_end_date` FROM `booking` WHERE `booking_id` = ?";
        $check_date_change_stmt = $conn->prepare($check_date_change_sql);
        $check_date_change_stmt->bind_param("i", $booking_id);
        $check_date_change_stmt->execute();
        $check_date_change_stmt->bind_result($existing_start_date, $existing_end_date);
        $check_date_change_stmt->fetch();
        $check_date_change_stmt->close();

        if ($start_date !== $existing_start_date || $end_date !== $existing_end_date) {
            // Check for overlapping bookings if the dates have changed
            $check_sql = "SELECT COUNT(*) FROM `booking` 
                          WHERE `photographer_id` = ? 
                          AND `booking_id` != ? 
                          AND `booking_confirm_status` = 0
                          AND (
                              (? BETWEEN `booking_start_date` AND `booking_end_date`)
                              OR
                              (? BETWEEN `booking_start_date` AND `booking_end_date`)
                              OR
                              (`booking_start_date` BETWEEN ? AND ?)
                          )";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("iissss", $photographer_id, $booking_id, $start_date, $end_date, $start_date, $end_date);
            $check_stmt->execute();
            $check_stmt->bind_result($count);
            $check_stmt->fetch();
            $check_stmt->close();

            if ($count > 0) {
                // There is an overlapping booking
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
                    }, 500);
                </script>
            <?php
                exit; // Stop execution if there is an overlap
            }
        }

        // Proceed with the update (dates and/or other details)
        $sql = "UPDATE `booking` SET `booking_start_date` = ?, `booking_start_time` = ?, `booking_end_date` = ?, `booking_end_time` = ?, `booking_location` = ?, `booking_details` = ? WHERE `booking_id` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $start_date, $start_time, $end_date, $end_time, $location, $details, $booking_id);

        if ($stmt->execute()) {
            ?>
            <script>
                setTimeout(function() {
                    Swal.fire({
                        title: '<div class="t1">แก้ไขข้อมูลการจองสำเร็จ</div>',
                        icon: 'success',
                        confirmButtonText: 'ตกลง',
                        allowOutsideClick: true,
                        allowEscapeKey: true,
                        allowEnterKey: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "";
                        }
                    });
                }, 500);
            </script>
        <?php
        } else {
        ?>
            <script>
                setTimeout(function() {
                    Swal.fire({
                        title: '<div class="t1">เกิดข้อผิดพลาดในการแก้ไขข้อมูลการจอง</div>',
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
                }, 500);
            </script>
<?php
        }
        $stmt->close();
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
            background-image: url('../img/bgIndex2.jpg');
            background-attachment: fixed;
            background-size: cover;
            /* เพิ่มการปรับแต่งในการขยับภาพตามต้องการ */
        }

        .table th,
        .table td {
            vertical-align: middle;
            /* จัดการให้เนื้อหาตรงกลางของเซลล์ */
        }

        .table th.text-center,
        .table td.text-center {
            text-align: center;
            /* จัดการให้เนื้อหาอยู่ตรงกลางของเซลล์ */
        }

        .table .btn {
            width: 150px;
        }

        /* 3. เพิ่มการเรียงลำดับให้เป็นแถวคู่-คี่ */
        .table tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
            /* สลับสีพื้นหลังแถว */
        }

        .table th:nth-child(7),
        .table td:nth-child(7) {
            width: 200px;
            height: 50px;
            text-align: center;
            /* กำหนดความกว้างของคอลัมน์การจัดการให้เหมาะสม */
        }

        .table th:nth-child(1),
        .table td:nth-child(1) {
            width: 150px;
            height: 50px;
            text-align: center;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
        }

        .modal-dialog {
            width: 70%;
            /* เปลี่ยนเป็นค่าที่คุณต้องการ เช่น 50% หรือ 70% */
        }

        .table th:nth-child(2),
        .table th:nth-child(3),
        .table th:nth-child(4),
        .table th:nth-child(5),
        .table th:nth-child(6),
        .table td:nth-child(2),
        .table td:nth-child(3),
        .table td:nth-child(4),
        .table td:nth-child(5),
        .table td:nth-child(6) {
            width: 180px;
            height: 50px;
            /* กำหนดความกว้างของคอลัมน์การจัดการให้เหมาะสม */
            text-overflow: ellipsis;
        }
    </style>
</head>

<body>
    <div class="bg-white" style="height: auto;">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-dark" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Navbar Start -->
        <div class="bgIndex" style="height: auto;">
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
                        <div class="navbar-nav ms-auto f">
                            <a href="index.php" class="nav-item nav-link">หน้าหลัก</a>
                            <a href="search.php" class="nav-item nav-link">ค้นหาช่างภาพ</a>
                            <a href="workings.php" class="nav-item nav-link">ผลงานช่างภาพ</a>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown">รายการจองคิวช่างภาพ</a>
                                <div class="dropdown-menu rounded-0 m-0">
                                    <a href="bookingLists.php" class="dropdown-item active">รายการจองคิวที่รออนุมัติ</a>
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

            <!-- Header Start -->
            <div class="container-fluid row g-0 align-items-center flex-column-reverse flex-md-row">
                <div class="col-md-6 ms-5 p-5">
                    <h1 class="display-5 animated fadeIn text-white f">รายการจองคิวที่รออนุมัติ</h1>
                    <p class="text-white">คุณสามารถดูรายการจองที่ต้องรอการอนุมัติจากช่างภาพได้ในหน้าต่างนี้</p>
                </div>
            </div>
            <!-- Header End -->
        </div>
    </div>
    <div class="bg-white">
        <div class="container bg-white" style="min-height: 662px"><br>
            <h1 class="text-center f">รายการจองคิวที่รออนุมัติ</h1>
            <div class="table-responsive mt-3">
                <table class="table bg-white table-hover table-bordered-3">
                    <thead>
                        <!-- <tr>
                            <th colspan="10" class="table-heading text-center bg-white">รายการจองคิวช่างภาพ</th>
                        </tr> -->
                        <tr>
                            <th scope="col">ประเภทงาน</th>
                            <th scope="col">สถานที่</th>
                            <th scope="col">วันที่เริ่มงาน</th>
                            <th scope="col">เวลาเริ่มงาน</th>
                            <th scope="col">วันที่สิ้นสุดงาน</th>
                            <th scope="col">เวลาสิ้นสุดงาน</th>
                            <th scope="col">ดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($resultBooking->num_rows > 0) {
                            while ($rowBooking = $resultBooking->fetch_assoc()) {
                                if (isset($rowBooking['booking_id'])) {
                        ?>
                                    <tr>
                                        <td><?php echo $rowBooking['type_work']; ?></td>
                                        <td><?php echo $rowBooking['booking_location']; ?></td>
                                        <td><?php echo $rowBooking['booking_start_date']; ?></td>
                                        <td><?php echo $rowBooking['booking_start_time']; ?></td>
                                        <td><?php echo $rowBooking['booking_end_date']; ?></td>
                                        <td><?php echo $rowBooking['booking_end_time']; ?></td>
                                        <td>
                                            <!-- <button type="button" class="btn btn-primary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#details<?php echo $rowBooking['booking_id']; ?>">ดูเพิ่มเติม</button> -->
                                            <button type="button" class="btn btn-warning btn-sm me-3" data-bs-toggle="modal" data-bs-target="#edite<?php echo $rowBooking['booking_id']; ?>">แก้ไข</button>
                                        </td>
                                    </tr>
                                    <!-- details -->
                                    <div class="modal fade" id="details<?php echo $rowBooking['booking_id']; ?>" tabindex="-1" aria-labelledby="detailsLabel<?php echo $rowBooking['booking_id']; ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="detailsLabel<?php echo $rowBooking['booking_id']; ?>"><b><i class="fas fa-clipboard-list"></i>&nbsp;&nbsp;รายละเอียดการจองคิว</b></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action=" " method="POST">
                                                    <div class="modal-body" style="height: auto;">
                                                        <div class="container-md">
                                                            <div class="col-md-12 container-fluid">
                                                                <div class="mt-3 mb-3 ms-3 me-3">
                                                                    <div class="col-md-12 ms-3">
                                                                        <h6 class="f mb-3">ข้อมูลช่างภาพ</h6>
                                                                        <div class="col-12">
                                                                            <div class="col-4">
                                                                                <span style="color: black; margin-right: 5px;font-size: 18px;">ชื่อ-นามสกุล : <?php echo  $rowBooking['photographer_prefix'] . '' . $rowBooking['photographer_name'] . ' ' . $rowBooking['photographer_surname']; ?></span>
                                                                            </div>
                                                                            <div class="col-4 mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">เบอร์โทรศัพท์มือถือ : <?php echo  $rowBooking['photographer_tell']; ?></span>
                                                                            </div>
                                                                            <div class="col-4 mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">อีเมล : <?php echo  $rowBooking['photographer_email']; ?></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                                <div class="mt-3 mb-3 ms-3 me-3">
                                                                    <div class="col-md-12 ms-3">
                                                                        <h6 class="f mb-3">ข้อมูลการจองของคุณ</h6>
                                                                        <div class="col-12">
                                                                            <div class="row">
                                                                                <div class="col-12 mt-2">
                                                                                    <span style="color: black; margin-right: 5px;font-size: 18px;">ชื่อ-นามสกุล : <?php echo  $rowBooking['cus_prefix'] . '' . $rowBooking['cus_name'] . ' ' . $rowBooking['cus_surname']; ?></span>
                                                                                </div>
                                                                                <div class="col-12 mt-2">
                                                                                    <?php if ($rowBooking['booking_start_date'] == $rowBooking['booking_end_date']): ?>
                                                                                        <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                                                            วันที่จอง : <?php echo $rowBooking['booking_start_date']; ?>
                                                                                        </span>
                                                                                    <?php else: ?>
                                                                                        <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                                                            วันที่จอง : <?php echo $rowBooking['booking_start_date'] . '  ถึง  ' . $rowBooking['booking_end_date']; ?>
                                                                                        </span>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <div class="col-12 mt-2">
                                                                                    <?php
                                                                                    $startTime = new DateTime($rowBooking['booking_start_time']);
                                                                                    $endTime = new DateTime($rowBooking['booking_end_time']);

                                                                                    $formattedStartTime = $startTime->format('H:i');
                                                                                    $formattedEndTime = $endTime->format('H:i');
                                                                                    ?>

                                                                                    <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                                                        เวลา : <?php echo $formattedStartTime . ' น.' . '  -  ' . $formattedEndTime . ' น.'; ?>
                                                                                    </span>
                                                                                </div>
                                                                                <div class="col-12 mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">สถานที่ : <?php echo  $rowBooking['booking_location']; ?></span></div>
                                                                                <div class="col-12 mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">ประเภทงาน : <?php echo  $rowBooking['type_work']; ?></span> </div>
                                                                                <div class="col-12 mt-2"><span style="color: black; margin-right: 5px; font-size: 18px; overflow-wrap: break-word;">คำอธิบาย : <?php echo $rowBooking['booking_details']; ?></span></div>
                                                                                <div class="col-12 mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">เบอร์โทรศัพท์มือถือ : <?php echo  $rowBooking['cus_tell']; ?></span></div>
                                                                                <div class="col-12 mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">อีเมล : <?php echo  $rowBooking['cus_email']; ?></span></div>
                                                                                <div class="col-12 mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">วันที่บันทึก : <?php echo  $rowBooking['booking_date']; ?></span> </div>
                                                                                <div class="col-12 mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">สถานะการชำระ : <?php echo ($rowBooking['booking_confirm_status'] == '0') ? 'รออนุมัติการจอง' : (($rowBooking['bookinh_confim_status'] == '2') ? 'ถูกปฏิเสธการจอง' : 'เสร็จสิ้น'); ?></span></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer justify-content-center">
                                                        <button type="button" class="btn btn-danger" style="width: 150px; height:45px;" data-bs-dismiss="modal">ปิด</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Edit -->
                                    <div class="modal fade" id="edite<?php echo $rowBooking['booking_id']; ?>" tabindex="-1" aria-labelledby="editeLabel<?php echo $rowBooking['booking_id']; ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-xl">
                                            <div class="modal-content">
                                            <div class="modal-body" style="height: auto;">
                                                        <div class="container-md">
                                                            <div class="col-md-12 container-fluid">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editeLabel<?php echo $rowBooking['booking_id']; ?>"><b><i class="fas fa-clipboard-list"></i>&nbsp;&nbsp;รายละเอียดการจองคิวที่ต้องการแก้ไข</b></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="mt-3 mb-3 ms-3 me-3">
                                                    <div class="col-md-12 ms-3">
                                                        <h6 class="f mb-3">ข้อมูลช่างภาพ</h6>
                                                        <div class="col-12">
                                                            <div class="col-4">
                                                                <span style="color: black; margin-right: 5px;font-size: 18px;">ชื่อ-นามสกุล : <?php echo  $rowBooking['photographer_prefix'] . '' . $rowBooking['photographer_name'] . ' ' . $rowBooking['photographer_surname']; ?></span>
                                                            </div>
                                                            <div class="col-4 mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">เบอร์โทรศัพท์มือถือ : <?php echo  $rowBooking['photographer_tell']; ?></span>
                                                            </div>
                                                            <div class="col-4 mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">อีเมล : <?php echo  $rowBooking['photographer_email']; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <form action="" method="POST" onsubmit="return validateForm()">
                                                    <div class="modal-body" style="height: auto;">
                                                        <div class="container-md">
                                                            <div class="col-md-12 container-fluid">
                                                                <!-- Customer Information -->
                                                                <div class="col-12">
                                                                    <div class="row">
                                                                        <div class="col-2">
                                                                            <label for="prefix" style="font-weight: bold; display: flex; align-items: center;">
                                                                                <span style="color: black; margin-right: 5px;font-size: 13px;">คำนำหน้า</span>
                                                                            </label>
                                                                            <input type="text" name="prefix" class="form-control mt-1" value="<?php echo $rowBooking['cus_prefix']; ?>" readonly>
                                                                        </div>
                                                                        <div class="col-5">
                                                                            <label for="name" style="font-weight: bold; display: flex; align-items: center;">
                                                                                <span style="color: black; margin-right: 5px;font-size: 13px;">ชื่อ</span>
                                                                            </label>
                                                                            <input type="text" name="name" class="form-control mt-1" value="<?php echo $rowBooking['cus_name']; ?>" readonly>
                                                                        </div>
                                                                        <div class="col-5">
                                                                            <label for="surname" style="font-weight: bold; display: flex; align-items: center;">
                                                                                <span style="color: black; font-size: 13px;">นามสกุล</span>
                                                                            </label>
                                                                            <input type="text" name="surname" class="form-control mt-1" value="<?php echo $rowBooking['cus_surname']; ?>" readonly>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Booking Information -->
                                                                <div class="col-12 mt-3">
                                                                    <div class="row">
                                                                        <div class="col-md-4 text-center">
                                                                            <label for="booking-start-date" style="font-weight: bold; display: flex; align-items: center;">
                                                                                <span style="color: black; margin-right: 5px;font-size: 13px;">วันที่เริ่มจอง</span>
                                                                                <!-- <span style="color: red;">*</span> -->
                                                                            </label>
                                                                            <input type="date" id="start_date" name="start_date" class="form-control mt-1"
                                                                                value="<?php echo $rowBooking['booking_start_date']; ?>"
                                                                                min="<?php echo $rowBooking['booking_start_date']; ?>" readonly>
                                                                        </div>
                                                                        <div class="col-md-4 text-center">
                                                                            <label for="end_date" style="font-weight: bold; display: flex; align-items: center;">
                                                                                <span style="color: black; margin-right: 5px;font-size: 13px;">วันที่สิ้นสุดการจอง</span>
                                                                                <!-- <span style="color: red;">*</span> -->
                                                                            </label>
                                                                            <input type="date" id="end_date" name="end_date" class="form-control mt-1"
                                                                                value="<?php echo $rowBooking['booking_end_date']; ?>" min="<?php echo $rowBooking['booking_start_date']; ?>"
                                                                                readonly>
                                                                        </div>
                                                                        <div class="col-md-2 text-center">
                                                                            <label for="start_time" style="font-weight: bold; display: flex; align-items: center;">
                                                                                <span style="color: black; margin-right: 5px;font-size: 13px;">เวลาเริ่มงาน</span>
                                                                                <!-- <span style="color: red;">*</span> -->
                                                                            </label>
                                                                            <input type="time" id="start_time" name="start_time" class="form-control mt-1" value="<?php echo $rowBooking['booking_start_time']; ?>" readonly oninput="calculateEndTime()">
                                                                        </div>
                                                                        <div class="col-md-2 text-center">
                                                                            <label for="end_time" style="font-weight: bold; display: flex; align-items: center;">
                                                                                <span style="color: black; margin-right: 5px;font-size: 13px;">เวลาสิ้นสุด</span>
                                                                                <!-- <span style="color: red;">*</span> -->
                                                                            </label>
                                                                            <input type="time" id="end_time" name="end_time" class="form-control mt-1" value="<?php echo $rowBooking['booking_end_time']; ?>" readonly>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Additional Booking Information -->
                                                                <div class="col-12 mt-3">
                                                                    <div class="row">
                                                                        <div class="col-md-10 text-center">
                                                                            <label for="location" style="font-weight: bold; display: flex; align-items: center;">
                                                                                <span style="color: black; margin-right: 5px;font-size: 13px;">สถานที่</span>
                                                                                <span style="color: red;">*</span>
                                                                            </label>
                                                                            <input type="text" name="location" class="form-control mt-1" value="<?php echo $rowBooking['booking_location']; ?>" required>
                                                                        </div>
                                                                        <div class="col-md-2 text-center">
                                                                            <label for="type" style="font-weight: bold; display: flex; align-items: center;">
                                                                                <span style="color: black; margin-right: 5px;font-size: 13px;">ประเภทงาน</span>
                                                                                <!-- <span style="color: red;">*</span> -->
                                                                            </label>
                                                                            <input type="text" name="type" class="form-control mt-1" value="<?php echo $rowBooking['type_work']; ?>" readonly style="resize: none;">
                                                                            <!-- <select class="form-select border-1 py-2" id="type" name="type" required>
                                                                                <option value="">เลือกประเภทงาน</option>
                                                                                <?php
                                                                                // ทำการเชื่อมต่อฐานข้อมูล ($conn) ก่อน query
                                                                                $sql = "SELECT t.type_id, t.type_work, MAX(tow.photographer_id) AS photographer_id
                                                                                FROM `type` t
                                                                                INNER JOIN type_of_work tow ON t.type_id = tow.type_id
                                                                                INNER JOIN booking b ON b.photographer_id = tow.photographer_id
                                                                                INNER JOIN photographer p ON p.photographer_id = tow.photographer_id
                                                                                WHERE p.photographer_id = b.photographer_id
                                                                                GROUP BY t.type_id, t.type_work;
                                                                                ;";
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
                                                                            </select> -->
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Additional Booking Details -->
                                                                <div class="col-md-12 mt-3 text-center">
                                                                    <label for="details" style="font-weight: bold; display: flex; align-items: center;">
                                                                        <span style="color: black; margin-right: 5px;font-size: 13px;">คำอธิบาย</span>
                                                                        <span style="color: red;">*</span>
                                                                    </label>
                                                                    <input name="details" class="form-control mt-1" value="<?php echo $rowBooking['booking_details']; ?>" required style="resize: none; height: 100px;"></input>
                                                                </div>

                                                                <!-- Customer Contact Information -->
                                                                <div class="col-12">
                                                                    <div class="row mt-3">
                                                                        <div class="col-5">
                                                                            <label for="mobile" style="font-weight: bold; display: flex; align-items: center;">
                                                                                <span style="color: black; margin-right: 5px;font-size: 13px;">เบอร์โทรศัพท์มือถือ</span>
                                                                            </label>
                                                                            <input type="text" name="mobile" class="form-control mt-1" value="<?php echo $rowBooking['cus_tell']; ?>" readonly>
                                                                        </div>
                                                                        <div class="col-5 text-center">
                                                                            <label for="email" style="font-weight: bold; display: flex; align-items: center;">
                                                                                <span style="color: black; margin-right: 5px;font-size: 13px;">อีเมล</span>
                                                                            </label>
                                                                            <input type="email" name="email" class="form-control mt-1" value="<?php echo $rowBooking['cus_email']; ?>" readonly>
                                                                        </div>
                                                                        <div class="col-2 mt-1">
                                                                            <label for="date-saved" style="font-weight: bold; display: flex; align-items: center;">
                                                                                <span style="color: black; margin-right: 5px;font-size: 13px;">วันที่บันทึก</span>
                                                                            </label>
                                                                            <input type="date" name="date-saved" class="form-control mt-1" value="<?php echo $rowBooking['booking_date']; ?>" readonly style="resize: none;">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="booking_id" value="<?php echo $rowBooking['booking_id']; ?>">
                                                    <input type="hidden" name="photographer_id" value="<?php echo $rowBooking['photographer_id']; ?>">
                                                    <div class="modal-footer justify-content-center">
                                                        <button type="button" class="btn" style="background-color:gray; color:#ffff; width: 150px; height:45px;" data-bs-dismiss="modal">ปิด</button>
                                                        <button id="saveButton" name="submit_booking_edit" class="btn btn-primary" style="width: 150px; height:45px;">บันทึกการแก้ไข</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                        <?php
                                }
                            }
                        } else {
                            echo "<tr><td  colspan='7' >ไม่พบข้อมูลรายการจองที่รออนุมัติ</td></tr>";
                        }

                        ?>
                    </tbody>
                </table>
            </div><!-- Footer Start --><br><br>
            <div class="container-fluid text-gry wow fadeIn">
                <div class="copyright">
                    &copy; <a class="border-bottom text-dark">2024 Photo Match</a>, All Right Reserved.
                </div>
            </div>
            <!-- Footer End -->
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/wow/wow.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/wow/wow.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="../js/main.js"></script>
    <script>
        // Mock data for charts
        const overviewData = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [{
                label: 'Total Visits',
                backgroundColor: 'rgb(54, 162, 235)',
                borderColor: 'rgb(54, 162, 235)',
                data: [1000, 1500, 2000, 1800, 2500, 2200, 3000],
            }]
        };

        const userData = {
            labels: ['Admin', 'Photographer', 'Customer'],
            datasets: [{
                label: 'User Type',
                backgroundColor: ['#FF5733', '#FFC300', '#36A2EB'],
                borderColor: ['#FF5733', '#FFC300', '#36A2EB'],
                data: [500, 800, 1200],
            }]
        };

        // Render charts
        const overviewChartCtx = document.getElementById('overviewChart').getContext('2d');
        const overviewChart = new Chart(overviewChartCtx, {
            type: 'line',
            data: overviewData,
        });

        const userChartCtx = document.getElementById('userChart').getContext('2d');
        const userChart = new Chart(userChartCtx, {
            type: 'bar',
            data: userData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
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