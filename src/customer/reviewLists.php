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
$sql1 = "SELECT b.*, c.cus_prefix, c.cus_name, c.cus_surname, c.cus_tell, c.cus_email, t.type_work, p.*, sub.*,
        (b.booking_price - (b.booking_price * 0.30)) AS payment_price, 
        (b.booking_price * 0.30) AS deposit_price
    FROM booking b
    JOIN customer c ON b.cus_id = c.cus_id
    JOIN `type` t ON b.type_of_work_id = t.type_id
    JOIN type_of_work tow ON tow.type_of_work_id = b.type_of_work_id
    JOIN photographer p ON p.photographer_id = b.photographer_id
    JOIN submit sub ON sub.booking_id = b.booking_id
    WHERE c.cus_id = $id_cus
    AND b.booking_confirm_status = '1'
    AND b.booking_pay_status = '5'
    AND sub.submit_details IS NOT NULL
    ";

$resultBooking = $conn->query($sql1);

$sql2 = "SELECT pay.*, (b.booking_price - (b.booking_price * 0.30)) AS payment_price, 
        (b.booking_price * 0.30) AS deposit_price FROM pay JOIN booking b JOIN customer c ON b.cus_id = c.cus_id WHERE b.booking_id = pay.booking_id AND c.cus_id = $id_cus AND pay.pay_status = '0'";
$resultPay0 = $conn->query($sql2);

$sql3 = "SELECT pay.*, (b.booking_price - (b.booking_price * 0.30)) AS payment_price, 
(b.booking_price * 0.30) AS deposit_price FROM pay JOIN booking b JOIN customer c ON b.cus_id = c.cus_id WHERE b.booking_id = pay.booking_id AND c.cus_id = $id_cus AND pay.pay_status = '1'";
$resultPay1 = $conn->query($sql3);

$sql4 = "SELECT * FROM review";
$resultReview = $conn->query($sql4);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["submit_review"])) {
        $details = $_POST['details'];
        $rating = $_POST['rating'];
        $review_date = $_POST['review_date'];
        $review_time = $_POST['review_time'];
        $booking_id = $_POST['booking_id'];

        // Insert data into the database
        $sql = "INSERT INTO `review` (`booking_id`, `review_date`, `review_time`, `review_caption`, `review_level`) VALUES ( ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $booking_id, $review_date, $review_time, $details, $rating);

        if ($stmt->execute()) {
            // Update booking status
            $updateSql = "UPDATE booking SET booking_confirm_status = '3' WHERE booking_id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("i", $booking_id);

            if ($updateStmt->execute()) {
?>
                <script>
                    setTimeout(function() {
                        Swal.fire({
                            title: '<div class="t1">บันทึกข้อมูลการรีวิวสำเร็จ</div>',
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
                            title: '<div class="t1">เกิดข้อผิดพลาดในการอัพเดตสถานะการจอง</div>',
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
            $updateStmt->close();
        } else {
            ?>
            <script>
                setTimeout(function() {
                    Swal.fire({
                        title: '<div class="t1">เกิดข้อผิดพลาดในการบันทึกข้อมูลการรีวิว</div>',
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
                width: 500px;
                height: 50px;
                text-align: center;
                /* กำหนดความกว้างของคอลัมน์การจัดการให้เหมาะสม */
            }

            .table th:nth-child(1),
            .table td:nth-child(1) {
                width: 200px;
                height: 50px;
                text-align: center;
                /* กำหนดความกว้างของคอลัมน์การจัดการให้เหมาะสม */
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
            }
        </style>
    </head>
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
                                    <a href="bookingLists.php" class="dropdown-item">รายการจองคิวที่รออนุมัต</a>
                                    <a href="payLists.php" class="dropdown-item">รายการจองคิวที่ต้องชำระเงิน/ค่ามัดจำ</a>
                                    <a href="reviewLists.php" class="dropdown-item active">รายการจองคิวที่ต้องรีวิว</a>
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
                <div class="col-md-6 ms-5 p-5">
                    <h1 class="display-5 animated fadeIn text-white f">รายการจองคิวที่ต้องรีวิว</h1>
                    <p class="text-white">คุณสามารถเลือกดูรายการที่คุณต้องรีวิวหรือทำการรีวิวได้ที่หน้าต่างนี้</p>
                </div>
            </div>
            <!-- Header End -->
        </div>
    </div>
    <div class="bg-white">
        <div class="container bg-white" style="min-height: 662px"><br>
            <h1 class="text-center f">รายการจองคิวที่ต้องรีวิว</h1>
            <div class="row justify-content-end">
            </div>
            <div class="table-responsive mt-3">
                <table class="table bg-white table-hover table-bordered-3">
                    <thead>
                        <!-- <tr>
                            <th colspan="10" class="table-heading text-center bg-white">รายการจองคิวช่างภาพ</th>
                        </tr> -->
                        <tr>
                            <th scope="col">ประเภทงาน</th>
                            <!-- <th scope="col">สถานที่</th> -->
                            <th scope="col">วันที่เริ่มงาน</th>
                            <th scope="col">เวลาเริ่มงาน</th>
                            <th scope="col">ราคาจ่าย</th>
                            <th scope="col">ราคามัดจำ</th>
                            <th scope="col">ลิงก์ส่งงาน</th>
                            <th scope="col">ดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($resultBooking->num_rows > 0) {
                            while ($rowBooking = $resultBooking->fetch_assoc()) {
                                if (isset($rowBooking['booking_id'])) {
                                    // echo $rowBooking['pay_status'];
                        ?>
                                    <tr>
                                        <td><?php echo $rowBooking['type_work']; ?></td>
                                        <td><?php echo $rowBooking['booking_start_date']; ?></td>
                                        <td><?php echo $rowBooking['booking_start_time']; ?></td>
                                        <td><?php echo $rowBooking['booking_price']; ?></td>
                                        <td><?php echo $rowBooking['deposit_price']; ?></td>
                                        <td><?php echo '<a href="' . $rowBooking['submit_details'] . '" target="_blank">ดูไดร์ฟส่งงาน</a>'; ?></td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#details<?php echo $rowBooking['booking_id']; ?>">ดูเพิ่มเติม</button>
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#review<?php echo $rowBooking['booking_id']; ?>">รีวิว</button>
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
                                                <div class="modal-body" style="height: auto;">
                                                    <div class="container-md">
                                                        <div class="row">
                                                            <div class="col-md-6 container-fluid">
                                                                <div class="card">
                                                                    <div class="mt-3 mb-3 ms-3 me-3">
                                                                        <div class="col-md-12 ms-3">
                                                                            <h6 class="f mb-3">ข้อมูลช่างภาพ</h6>
                                                                            <div class="col-12">
                                                                                <div class="">
                                                                                    <span style="color: black; margin-right: 5px;font-size: 18px;">ชื่อ-นามสกุล : <?php echo  $rowBooking['photographer_prefix'] . '' . $rowBooking['photographer_name'] . ' ' . $rowBooking['photographer_surname']; ?></span>
                                                                                </div>
                                                                                <div class="mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">เบอร์โทรศัพท์มือถือ : <?php echo  $rowBooking['photographer_tell']; ?></span>
                                                                                </div>
                                                                                <div class=" mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">อีเมล : <?php echo  $rowBooking['photographer_email']; ?></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="card mt-3">
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
                                                                                    <div class="col-12 mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">ราคาจ่าย : <?php echo  $rowBooking['booking_price'] . ' บาท'; ?></span></div>
                                                                                    <div class="col-12 mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">เบอร์โทรศัพท์มือถือ : <?php echo  $rowBooking['cus_tell']; ?></span></div>
                                                                                    <div class="col-12 mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">อีเมล : <?php echo  $rowBooking['cus_email']; ?></span></div>
                                                                                    <div class="col-12 mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">วันที่บันทึก : <?php echo  $rowBooking['booking_date']; ?></span> </div>
                                                                                    <div class="col-12 mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">สถานะการชำระ : <?php echo ($rowBooking['booking_pay_status'] == '2') ? 'รอชำระเงิน' : (($rowBooking['booking_pay_status'] == '4') ? 'ชำระเงินแล้วรอตรวจสอบ' : 'ตรวจสอบแล้ว'); ?></span></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 container-fluid mb-3">
                                                                <div class="card" style="height: auto;">
                                                                    <div class="mt-3 mb-3 ms-3 me-3">
                                                                        <div class="col-md-12 ms-3">
                                                                            <h6 class="f mb-3">ข้อมูลการชำระค่ามัดจำ</h6>
                                                                            <?php
                                                                            if ($rowPay0 = $resultPay0->fetch_assoc()) :
                                                                            ?>
                                                                                <div class="col-12 mt-2">
                                                                                    <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                                                        ค่ามัดจำที่จ่าย : <?php echo $rowPay0['deposit_price'] . ' บาท'; ?>
                                                                                    </span>
                                                                                </div>
                                                                                <div class="col-12 mt-2">
                                                                                    <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                                                        หลักฐานการชำระเงิน :
                                                                                        <?php
                                                                                        if (empty($rowPay0['pay_slip'])) {
                                                                                            echo 'คุณชำระเป็นเงินสด';
                                                                                        } else {
                                                                                            echo '<a href="../img/slip/' . $rowPay0['pay_slip'] . '" target="_blank">ดูหลักฐานการชำระเงิน</a>';
                                                                                        }
                                                                                        ?>
                                                                                    </span>
                                                                                </div>
                                                                                <div class="col-12 mt-2">
                                                                                    <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                                                        วันที่ชำระ : <?php echo $rowPay0['pay_date']; ?>
                                                                                    </span>
                                                                                </div>
                                                                                <div class="col-12 mt-2">
                                                                                    <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                                                        เวลาที่ชำระ : <?php echo $rowPay0['pay_time'] . ' น.'; ?>
                                                                                    </span>
                                                                                </div>
                                                                            <?php endif; ?>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="card mt-3" style="height: auto;">
                                                                    <div class="mt-3 mb-3 ms-3 me-3">
                                                                        <div class="col-md-12 ms-3">
                                                                            <h6 class="f mb-3">ข้อมูลการชำระเงิน</h6>
                                                                            <?php
                                                                            if ($rowPay1 = $resultPay1->fetch_assoc()) :
                                                                            ?>
                                                                                <div class="col-12 mt-2">
                                                                                    <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                                                        จำนวนเงินที่ชำระ : <?php echo $rowPay1['payment_price'] . ' บาท'; ?>
                                                                                    </span>
                                                                                </div>
                                                                                <div class="col-12 mt-2">
                                                                                    <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                                                        หลักฐานการชำระเงิน :
                                                                                        <?php
                                                                                        if (empty($rowPay1['pay_slip'])) {
                                                                                            echo 'คุณชำระเป็นเงินสด';
                                                                                        } else {
                                                                                            echo '<a href="../img/slip/' . $rowPay1['pay_slip'] . '" target="_blank">ดูหลักฐานการชำระเงิน</a>';
                                                                                        }
                                                                                        ?>
                                                                                    </span>
                                                                                </div>
                                                                                <div class="col-12 mt-2">
                                                                                    <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                                                        วันที่ชำระ : <?php echo $rowPay1['pay_date']; ?>
                                                                                    </span>
                                                                                </div>
                                                                                <div class="col-12 mt-2">
                                                                                    <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                                                        เวลาที่ชำระ : <?php echo $rowPay1['pay_time'] . ' น.'; ?>
                                                                                    </span>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="card mt-3" style="height: auto;">
                                                                    <div class="mt-3 mb-3 ms-3 me-3">
                                                                        <div class="col-md-12 ms-3">
                                                                            <h6 class="f mb-3">ข้อมูลการส่งมอบงาน</h6>
                                                                            <div class="col-12 mt-2">
                                                                                <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                                                    ไดฟ์ส่งงาน : <?php echo '<a href="' . $rowBooking['submit_details'] . '" target="_blank">ดูไดร์ฟส่งงาน</a>'; ?>
                                                                                </span>
                                                                            </div>
                                                                            <div class="col-12 mt-2">
                                                                                <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                                                    วันที่ส่งงาน : <?php echo $rowBooking['submit_date']; ?>
                                                                                </span>
                                                                            </div>
                                                                            <div class="col-12 mt-2">
                                                                                <span style="color: black; margin-right: 5px; font-size: 18px;">
                                                                                    เวลาที่ส่งงาน : <?php echo $rowBooking['submit_time'] . ' น.'; ?>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-center mt-3">
                                                            <button type="button" class="btn btn-danger" style="width: 150px; height:45px;" data-bs-dismiss="modal">ปิด</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="review<?php echo $rowBooking['booking_id']; ?>" tabindex="-1" aria-labelledby="reviewLabel<?php echo $rowBooking['booking_id']; ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="reviewLabel<?php echo $rowBooking['booking_id']; ?>"><b><i class="fa-solid fa-pen"></i>&nbsp;&nbsp;เขียนรีวิวให้ช่างภาพ คุณ<?php echo $rowBooking['photographer_name'] . ' ' . $rowBooking['photographer_surname']; ?></b></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body" style="height: auto;">
                                                    <div class="mt-3 container-md">
                                                        <div class="mt-3 col-md-8 container-fluid">
                                                            <div class="col-12">
                                                                <div class="row">
                                                                    <div class="col-4">
                                                                        <h6 class="f mt-2">ข้อมูลการจอง</h6>
                                                                    </div>
                                                                    <div class="col-8">
                                                                        <div class="col-12 mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">ชื่อ-นามสกุล ช่างภาพ : <?php echo  $rowBooking['photographer_prefix'] . '' . $rowBooking['photographer_name'] . ' ' . $rowBooking['photographer_surname']; ?></span></div>
                                                                        <div class="col-12 mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">ประเภทงาน : <?php echo  $rowBooking['type_work']; ?></span> </div>
                                                                        <div class="col-12 mt-2"><span style="color: black; margin-right: 5px;font-size: 18px;">ราคาจ่าย : <?php echo  $rowBooking['booking_price'] . ' บาท'; ?></span></div>
                                                                        <div class="col-12 mt-2"><span style="color: black; margin-right: 5px; font-size: 18px;">ไดฟ์ส่งงาน : <?php echo '<a href="' . $rowBooking['submit_details'] . '" target="_blank">ดูไดร์ฟส่งงาน</a>'; ?></span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <form action=" " enctype="multipart/form-data" method="POST">
                                                                <!-- รีวิวเริ่มต้น -->
                                                                <div class="col-12 mt-5">
                                                                    <div class="row">
                                                                        <div class="col-4">
                                                                            <h6 class="f mt-2">ข้อมูลคะแนนความพึงพอใจ</h6>
                                                                        </div>
                                                                        <div class="col-8">
                                                                            <div class="row">
                                                                                <div class="col-6">
                                                                                    <i class="far fa-star star-icon" style="font-size: 30px;color:goldenrod;" data-rating="1"></i>
                                                                                    <i class="far fa-star star-icon" style="font-size: 30px;color:goldenrod;" data-rating="2"></i>
                                                                                    <i class="far fa-star star-icon" style="font-size: 30px;color:goldenrod;" data-rating="3"></i>
                                                                                    <i class="far fa-star star-icon" style="font-size: 30px;color:goldenrod;" data-rating="4"></i>
                                                                                    <i class="far fa-star star-icon" style="font-size: 30px;color:goldenrod;" data-rating="5"></i>
                                                                                    <input type="hidden" name="rating" id="rating" value="0">
                                                                                </div>
                                                                                <div class="col-6">
                                                                                    <h6 class="f mt-2" style="color:goldenrod; margin-right: 5px;font-size: 18px;" id="rating-text"></h6>
                                                                                    <small id="rating-error" class="text-danger" style="display:none;">กรุณาเลือกคะแนนความพึงพอใจ</small>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <script>
                                                                            document.querySelectorAll('.star-icon').forEach(function(star) {
                                                                                star.addEventListener('click', function() {
                                                                                    let rating = this.getAttribute('data-rating');
                                                                                    document.getElementById('rating').value = rating;

                                                                                    // อัปเดตข้อความระดับความพึงพอใจ
                                                                                    let ratingText = '';
                                                                                    switch (rating) {
                                                                                        case '5':
                                                                                            ratingText = 'พึงพอใจมากที่สุด';
                                                                                            break;
                                                                                        case '4':
                                                                                            ratingText = 'พึงพอใจมาก';
                                                                                            break;
                                                                                        case '3':
                                                                                            ratingText = 'พึงพอใจปานกลาง';
                                                                                            break;
                                                                                        case '2':
                                                                                            ratingText = 'พึงพอใจน้อย';
                                                                                            break;
                                                                                        case '1':
                                                                                            ratingText = 'พึงพอใจน้อยที่สุด';
                                                                                            break;
                                                                                        default:
                                                                                            ratingText = '';
                                                                                    }
                                                                                    document.getElementById('rating-text').innerText = ratingText;

                                                                                    // รีเซ็ตดาว
                                                                                    document.querySelectorAll('.star-icon').forEach(function(star) {
                                                                                        star.classList.remove('fas');
                                                                                        star.classList.add('far');
                                                                                    });

                                                                                    // ไฮไลต์ดาวที่เลือก
                                                                                    for (let i = 1; i <= rating; i++) {
                                                                                        document.querySelector('.star-icon[data-rating="' + i + '"]').classList.remove('far');
                                                                                        document.querySelector('.star-icon[data-rating="' + i + '"]').classList.add('fas');
                                                                                    }

                                                                                    // ซ่อนข้อความเตือนเมื่อเลือกคะแนนแล้ว
                                                                                    document.getElementById('rating-error').style.display = 'none';
                                                                                });
                                                                            });

                                                                            document.querySelector('form').addEventListener('submit', function(event) {
                                                                                var rating = document.getElementById('rating').value;

                                                                                // ตรวจสอบว่ามีการเลือกคะแนนหรือไม่
                                                                                if (rating == 0) {
                                                                                    event.preventDefault(); // ยกเลิกการส่งแบบฟอร์ม
                                                                                    document.getElementById('rating-error').style.display = 'block'; // แสดงข้อความเตือน
                                                                                } else {
                                                                                    document.getElementById('rating-error').style.display = 'none'; // ซ่อนข้อความเตือนถ้าถูกต้อง
                                                                                }
                                                                            });
                                                                        </script>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 mt-5">
                                                                    <div class="row">
                                                                        <div class="col-4">
                                                                            <h6 class="f mt-2">ข้อมูลคำอธิบาย</h6>
                                                                        </div>
                                                                        <div class="col-8 mt-2">
                                                                            <small id="details-counter" class="text-muted">0/20 ตัวอักษร</small> <!-- แสดงจำนวนตัวอักษร -->
                                                                            <textarea id="details" name="details" class="form-control mt-1" placeholder="กรุณากรอกคำอธิบาย (อย่างน้อย 20 ตัวอักษร)" style="resize: none; height: 100px;" required></textarea>
                                                                            <small id="details-error" class="text-danger" style="display:none;">กรุณากรอกคำอธิบายไม่ต่ำกว่า 20 ตัวอักษร</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- รีวิวสิ้นสุด -->
                                                                <script>
                                                                    var detailsInput = document.getElementById('details');
                                                                    var detailsError = document.getElementById('details-error');
                                                                    var detailsCounter = document.getElementById('details-counter');

                                                                    // ฟังก์ชันสำหรับอัปเดตจำนวนตัวอักษรที่กรอก
                                                                    detailsInput.addEventListener('input', function() {
                                                                        var detailsLength = detailsInput.value.length;
                                                                        detailsCounter.textContent = detailsLength + "/20 ตัวอักษร";

                                                                        // ตรวจสอบความยาวของคำอธิบาย
                                                                        if (detailsLength < 20) {
                                                                            detailsError.style.display = 'block'; // แสดงข้อความเตือน
                                                                        } else {
                                                                            detailsError.style.display = 'none'; // ซ่อนข้อความเตือนเมื่อกรอกครบ
                                                                        }
                                                                    });

                                                                    // ตรวจสอบคำอธิบายเมื่อส่งฟอร์ม
                                                                    document.querySelector('form').addEventListener('submit', function(event) {
                                                                        if (detailsInput.value.length < 20) {
                                                                            event.preventDefault(); // ยกเลิกการส่งแบบฟอร์ม
                                                                            detailsError.style.display = 'block'; // แสดงข้อความเตือน
                                                                        }
                                                                    });
                                                                </script>

                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="review_date" name="review_date" class="form-control mt-1" style="resize: none;" required>
                                                    <input type="hidden" id="review_time" name="review_time" class="form-control mt-1" style="resize: none;" required>
                                                    <script>
                                                        window.onload = function() {
                                                            // ตั้งค่าวันที่ปัจจุบัน
                                                            let today = new Date();
                                                            let year = today.getFullYear();
                                                            let month = ('0' + (today.getMonth() + 1)).slice(-2); // เพิ่มเลข 0 ถ้าเดือนเป็นตัวเลขหลักเดียว
                                                            let day = ('0' + today.getDate()).slice(-2); // เพิ่มเลข 0 ถ้าวันเป็นตัวเลขหลักเดียว
                                                            document.getElementById('review_date').value = year + '-' + month + '-' + day;

                                                            // ตั้งค่าเวลาปัจจุบัน
                                                            let hours = ('0' + today.getHours()).slice(-2); // เพิ่มเลข 0 ถ้าเป็นเลขหลักเดียว
                                                            let minutes = ('0' + today.getMinutes()).slice(-2);
                                                            document.getElementById('review_time').value = hours + ':' + minutes;
                                                        };
                                                    </script>
                                                    <input type="hidden" name="booking_id" value="<?php echo $rowBooking['booking_id']; ?>">
                                                    <div class="modal-footer mt-5 justify-content-center">
                                                        <div class="col-md-12 text-center">
                                                            <button id="saveButton" name="submit_review" class="btn btn-primary" style="width: 150px; height:45px;">ยืนยันการรีวิว</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                        <?php
                                }
                            }
                        } else {
                            echo "<tr><td  colspan='7'>ไม่พบข้อมูลรายการที่ต้องรีวิว</td></tr>";
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
</body>

</html>