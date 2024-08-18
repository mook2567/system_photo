<?php
session_start();
include '../config_db.php';
require_once '../popup.php';

// Ensure database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch information
$sql = "SELECT * FROM `information`";
$resultInfo = $conn->query($sql);
$rowInfo = $resultInfo->fetch_assoc();

if (isset($_SESSION['photographer_login'])) {
    $email = $conn->real_escape_string($_SESSION['photographer_login']);
    $sql = "SELECT * FROM photographer WHERE photographer_email = '$email'";
    $resultPhoto = $conn->query($sql);

    if ($resultPhoto->num_rows > 0) {
        $rowPhoto = $resultPhoto->fetch_assoc();
        $id_photographer = $rowPhoto['photographer_id'];
    } else {
        die("Photographer not found.");
    }
} else {
    die("Session not started.");
}

// Fetch bookings
$sql = "SELECT *
        FROM `booking` 
        WHERE photographer_id = $id_photographer
        AND booking_confirm_status = '1'
        AND (
            (booking_start_date <= CURDATE() + INTERVAL (6 - WEEKDAY(CURDATE())) DAY
            AND booking_end_date >= CURDATE() - INTERVAL WEEKDAY(CURDATE()) DAY)
            OR
            (booking_start_date <= CURDATE() - INTERVAL WEEKDAY(CURDATE()) DAY
            AND booking_end_date >= CURDATE() - INTERVAL WEEKDAY(CURDATE()) DAY - INTERVAL 1 WEEK)
        )";
$resultBooking = $conn->query($sql);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['submit_photographer'])) {
        // Process profile update
        $photographer_id = $id_photographer;
        $name = $conn->real_escape_string($_POST["name"]);
        $surname = $conn->real_escape_string($_POST["surname"]);
        $tell = $conn->real_escape_string($_POST["tell"]);
        $email = $conn->real_escape_string($_POST["email"]);

        $selectedWorkAreas = isset($_POST['work_area']) ? $_POST['work_area'] : array();

        // Convert the array into a comma-separated string
        $selectedWorkAreasString = implode(', ', $selectedWorkAreas);
        // Retrieve the rates and type_ids from the form
        $profileImage = ""; // Initialize the profileImage variable

        $rate_half = $_POST['rate_half'];
        $rate_full = $_POST['rate_full'];
        $type_id = $_POST['type_id'];

        foreach ($type_id as $index => $id) {
            $half_rate = $rate_half[$index];
            $full_rate = $rate_full[$index];

            $sql = "UPDATE type_of_work 
            SET type_of_work_rate_half = ?, type_of_work_rate_full = ?
            WHERE type_id = ? AND photographer_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ddii', $half_rate, $full_rate, $id, $photographer_id);
            $stmt->execute();
            $stmt->close();
        }
        // Updated SQL statement
        $sql = "UPDATE photographer SET 
            photographer_name = ?, 
            photographer_surname = ?, 
            photographer_tell = ?, 
            photographer_scope = ?, 
            photographer_email = ?
            WHERE photographer_id = ?";
        $stmt = $conn->prepare($sql);

        // Bind parameters with the additional profileImage if required
        $stmt->bind_param("sssssi", $name, $surname, $tell, $selectedWorkAreasString, $email, $photographer_id);

        if ($stmt->execute()) {
            echo '<script>
                setTimeout(function() {
                    Swal.fire({
                        title: "<div class=\"t1\">บันทึกการแก้ไขสำเร็จ</div>",
                        icon: "success",
                        confirmButtonText: "ตกลง",
                        allowOutsideClick: true,
                        allowEscapeKey: true,
                        allowEnterKey: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "";
                        }
                    });
                });
            </script>';
        } else {
            echo '<script>
                setTimeout(function() {
                    Swal.fire({
                        title: "<div class=\"t1\">เกิดข้อผิดพลาดในการบันทึกการแก้ไข</div>",
                        icon: "error",
                        confirmButtonText: "ออก",
                        allowOutsideClick: true,
                        allowEscapeKey: true,
                        allowEnterKey: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "";
                        }
                    });
                });
            </script>';
        }

        $stmt->close();
    }

    $sql = "SELECT * FROM `portfolio`";
    $resultPort = $conn->query($sql);
    $rowPort = $resultPort->fetch_assoc();

    if (isset($_POST['submit_post_portfolio'])) {
        

        // ตรวจสอบว่ามีไฟล์ที่ถูกอัปโหลดหรือไม่
        if (!empty($_FILES['upload']['tmp_name'][0])) {
            $supported = array('jpg', 'jpeg', 'png', 'gif');
            $uploadedImages = [];

            foreach ($_FILES['upload']['tmp_name'] as $key => $tmp_name) {
                $file_name = $_FILES['upload']['name'][$key];
                $file_tmp = $_FILES['upload']['tmp_name'][$key];
                $file_size = $_FILES['upload']['size'][$key];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                // ตรวจสอบว่าไฟล์อัปโหลดเป็นไฟล์รูปที่รองรับหรือไม่
                if (in_array($file_ext, $supported)) {
                    // สร้างชื่อไฟล์ใหม่
                    $fileNewName = uniqid('img_') . '_' . time();
                    $file_dest = '../img/post/' . $fileNewName . '.' . $file_ext;

                    // อัปโหลดไฟล์
                    if (move_uploaded_file($file_tmp, $file_dest)) {
                        // บันทึกชื่อไฟล์ในอาร์เรย์ $uploadedImages
                        $uploadedImages[] = $fileNewName . '.' . $file_ext;
                    } else {
                        echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์";
                    }
                } else {
                    echo "ประเภทไฟล์ไม่รองรับ";
                }
            }

            // Serialize อาร์เรย์ของชื่อไฟล์
            $photo = serialize($uploadedImages);
            // แปลงข้อมูลจาก serialize เป็น array
            $files = unserialize($photo);

            // สร้างรายการชื่อไฟล์ที่คั่นด้วยเครื่องหมาย ','
            $fileNames = implode(', ', $files);


            $caption = $_POST['caption'];
            $workPost = $_POST["workPost"];
            $date = date('Y-m-d H:i:s');

            // เตรียมคำสั่ง SQL สำหรับ INSERT
            $sql = "INSERT INTO `portfolio` (`portfolio_photo`, `portfolio_caption`, `type_of_work_id`, `portfolio_date`) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $fileNames, $caption, $workPost, $date);

            // ทำการ execute คำสั่ง SQL
            if ($stmt->execute()) {
                echo '
                <div>
                    <script>
                        Swal.fire({
                            title: "<div class=\"t1\">ลงผลงานสำเร็จ</div>",
                            icon: "success",
                            confirmButtonText: "ตกลง",
                            allowOutsideClick: true,
                            allowEscapeKey: true,
                            allowEnterKey: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "";
                            }
                        });
                    </script>
                </div>';
            } else {
                echo '
                <div>
                    <script>
                        Swal.fire({
                            title: "<div class=\"t1\">เกิดข้อผิดพลาดในลงผลงาน</div>",
                            icon: "error",
                            confirmButtonText: "ออก",
                            allowOutsideClick: true,
                            allowEscapeKey: true,
                            allowEnterKey: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "";
                            }
                        });
                    </script>
                </div>';
            }
        } else {
            echo "กรุณาเลือกไฟล์ที่ต้องการอัปโหลด";
        }
    }


    $sql = "SELECT t.type_id, t.type_work, tow_latest.photographer_id
    FROM type t
    INNER JOIN (
        SELECT type_id, MAX(photographer_id) AS photographer_id
        FROM type_of_work
        GROUP BY type_id
    ) AS tow_latest ON t.type_id = tow_latest.type_id;
    ";
    $resultTypeWork = $conn->query($sql);
    $rowTypeWork = $resultTypeWork->fetch_assoc();

    if (isset($_POST['submit_type_of_work'])) {
        $details = $_POST['details'];
        $rate_half = isset($_POST['rate_half']) && $_POST['rate_half'] !== '' ? $_POST['rate_half'] : 0.0;
        $rate_full = isset($_POST['rate_full']) && $_POST['rate_full'] !== '' ? $_POST['rate_full'] : 0.0;
        $photographer_id = $id_photographer;
        $type = $_POST['type'];

        $sql = "INSERT INTO `type_of_work` (`type_of_work_details`, `type_of_work_rate_half`, `type_of_work_rate_full`, `photographer_id`, `type_id`) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $details, $rate_half, $rate_full, $photographer_id, $type);

        if ($stmt->execute()) {
            echo '
            <div>
                <script>
                    Swal.fire({
                        title: "<div class=\"t1\">ลงประเภทงานที่รับสำเร็จ</div>",
                        icon: "success",
                        confirmButtonText: "ตกลง",
                        allowOutsideClick: true,
                        allowEscapeKey: true,
                        allowEnterKey: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "";
                        }
                    });
                </script>
            </div>';
        } else {
            echo '
            <div>
                <script>
                    Swal.fire({
                        title: "<div class=\"t1\">เกิดข้อผิดพลาดในลงประเภทงานที่รับ</div>",
                        icon: "error",
                        confirmButtonText: "ออก",
                        allowOutsideClick: true,
                        allowEscapeKey: true,
                        allowEnterKey: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "";
                        }
                    });
                </script>
            </div>';
        }
    }



    if (isset($_POST['submit_profile_img'])) {
        $targetDir = "../img/profile/";
        $targetFile = $targetDir . basename($_FILES["profileImage"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $photo_name = basename($_FILES["profileImage"]["name"]);

        // Check if the file is an actual image
        $check = getimagesize($_FILES["profileImage"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["profileImage"]["tmp_name"], $targetFile)) {
                echo "The file " . htmlspecialchars(basename($_FILES["profileImage"]["name"])) . " has been uploaded.";

                // Update the database with the new file name
                $sql = "UPDATE photographer SET photographer_photo = ? WHERE photographer_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $photo_name, $id_photographer);

                if ($stmt->execute()) {
                    echo '<script>
                Swal.fire({
                    title: "<div class=\"t1\">เปลี่ยนภาพโปรไฟล์สำเร็จ</div>",
                    icon: "success",
                    confirmButtonText: "ตกลง",
                    allowOutsideClick: true,
                    allowEscapeKey: true,
                    allowEnterKey: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "";
                    }
                });
            </script>';
                } else {
                    echo '<script>
                Swal.fire({
                    title: "<div class=\"t1\">เกิดข้อผิดพลาดในการเปลี่ยนภาพโปรไฟล์</div>",
                    icon: "error",
                    confirmButtonText: "ออก",
                    allowOutsideClick: true,
                    allowEscapeKey: true,
                    allowEnterKey: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "";
                    }
                });
            </script>';
                }

                $stmt->close();
            } else {
                // echo "Sorry, there was an error uploading your file.";
            }
        } else {
            // echo "File is not an image.";
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


    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.0.0/css/bootstrap.min.css" rel="stylesheet">




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

        .profile-container {
            position: relative;
            display: inline-block;
        }

        .circle {
            position: relative;
        }

        .camera-icon {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border-radius: 50%;
            padding: 10px;
        }

        .circle:hover .camera-icon {
            display: block;
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

        /* Darken the backdrop */
        .modal-backdrop.show {
            background-color: rgba(0, 0, 0, 0.7);
        }

        /* Center the modal vertically */
        .modal-dialog {
            display: flex;
            align-items: center;
            min-height: calc(100% - 1rem);
        }

        /* Style for the modal content */
        .modal-content {
            border-radius: 0.5rem;
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
        <nav class="navbar me-5 ms-5 navbar-expand-lg navbar-dark bg-dark">
            <a href="index.html" class="navbar-brand d-flex align-items-center text-center">
                <img class="img-fluid" src="../img/logo/<?php echo isset($rowInfo['information_icon']) ? $rowInfo['information_icon'] : ''; ?>" style="height: 30px;">
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
                    <!-- <a href="report.php" class="nav-item nav-link">รายงาน</a> -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle bg-dark active" data-bs-toggle="dropdown">โปรไฟล์</a>
                        <div class="dropdown-menu rounded-0 m-0">
                            <a href="profile.php" class="dropdown-item active">โปรไฟล์</a>
                            <a href="editProfile.php" class="dropdown-item">แก้ไขข้อมูลส่วนตัว</a>
                            <a href="about.php" class="dropdown-item">เกี่ยวกับ</a>
                            <a href="contact.php" class="dropdown-item">ติดต่อ</a>
                            <a href="../logout.php" class="dropdown-item">ออกจากระบบ</a>
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
                            <div class="profile-container">
                                <div class="circle">
                                    <img src="../img/profile/<?php echo $rowPhoto['photographer_photo'] ? $rowPhoto['photographer_photo'] : 'null.png'; ?>" id="profileImage" data-bs-toggle="modal" data-bs-target="#uploadProfilephotoModal">
                                    <div class="camera-icon" id="cameraIcon" data-bs-toggle="modal" data-bs-target="#uploadProfilephotoModal">
                                        <i class="fa fa-camera"></i>
                                    </div>
                                    <!-- Upload Profilephoto Modal -->
                                    <div class="modal fade" id="uploadProfilephotoModal" tabindex="-1" aria-labelledby="uploadProfilephotoModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title" id="uploadProfilephotoModalLabel">เปลี่ยนรูปโปรไฟล์</h3>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div style="height: 460px;">
                                                        <div class="container-md mt-1">
                                                            <div class="card-body">
                                                                <form method="post" action="" enctype="multipart/form-data">
                                                                    <div class="row mt-1 align-items-center">
                                                                        <div class="d-flex justify-content-center align-items-center mt-2">
                                                                            <div class="circle">
                                                                                <img id="previewImage" src="../img/profile/<?php echo htmlspecialchars($rowPhoto['photographer_photo']) ? htmlspecialchars($rowPhoto['photographer_photo']) : 'null.png'; ?>" alt="Profile Photo">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12 mt-2">
                                                                            <div>
                                                                                <label for="photoUpload" style="font-weight: bold; display: flex; align-items: center;">
                                                                                    <span style="color: black; margin-right: 5px; font-size: 13px;">เลือกไฟล์รูปภาพ</span>
                                                                                    <span style="color: red;">*</span>
                                                                                </label>
                                                                            </div>
                                                                            <input type="file" id="photoUpload" name="profileImage" class="form-control" accept="image/png, image/jpeg" onchange="updateImage()">
                                                                        </div>
                                                                        <div class="modal-footer mt-5 justify-content-center">
                                                                            <button type="button" class="btn btn-danger" style="width: 150px; height: 45px;" data-bs-dismiss="modal">ปิด</button>
                                                                            <button type="submit" name="submit_profile_img" class="btn btn-primary" style="width: 150px; height: 45px;">อัปโหลด</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                                <script>
                                                                    function updateImage() {
                                                                        const file = document.getElementById('photoUpload').files[0];
                                                                        const reader = new FileReader();
                                                                        reader.onload = function(e) {
                                                                            document.getElementById('previewImage').src = e.target.result;
                                                                        };
                                                                        if (file) {
                                                                            reader.readAsDataURL(file);
                                                                        } else {
                                                                            document.getElementById('previewImage').src = '../img/profile/null.png'; // Default image if no file selected
                                                                        }
                                                                    }
                                                                </script>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-center md-3 py-3 px-4 mt-3">
                            <h3><?php echo $rowPhoto['photographer_name'] . ' ' . $rowPhoto['photographer_surname']; ?></h3>
                            <button type="button" class="btn btn-sm" style="color: #424242; background-color: #f5f5f5; width: 150px; height:45px;" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $rowPhoto['photographer_id']; ?>"><i class="fa-solid fa-pencil"></i> แก้ไขข้อมูลโปรไฟล์</button>
                        </div>
                        <div class="col-12 text-start mt-2">
                            <h5>ติดต่อ</h5>
                            <div class=" ms-4">
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
                        <div class="col-12 text-start mt-2">
                            <div class="col-12 text-start mt-2">
                                <h5>ประเภทงานที่รับ</h5>
                                <!-- <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#editType<?php echo $rowPhoto['photographer_id']; ?>">
                                    </button></a> -->
                                <div class="ms-4">
                                    <?php
                                    $sql = "SELECT 
                                    t.type_id, 
                                    t.type_work, 
                                    tow_latest.photographer_id, 
                                    tow_latest.type_of_work_details, 
                                    tow_latest.type_of_work_rate_half, 
                                    tow_latest.type_of_work_rate_full
                                FROM 
                                    type t
                                INNER JOIN (
                                    SELECT 
                                        type_id, 
                                        photographer_id, 
                                        type_of_work_details, 
                                        type_of_work_rate_half, 
                                        type_of_work_rate_full
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
                                    t.type_id = tow_latest.type_id;
                                ";
                                    $resultTypeWorkDetail = $conn->query($sql);

                                    if ($resultTypeWorkDetail->num_rows > 0) {
                                        while ($rowTypeWorkDetail = $resultTypeWorkDetail->fetch_assoc()) {
                                    ?>
                                            <div class="d-flex align-items-center">
                                                <i class="fa-solid fa-circle me-2" style="font-size: 5px;"></i>
                                                <b><?php echo htmlspecialchars($rowTypeWorkDetail['type_work']); ?></b>
                                            </div>
                                            <div class="ms-3">
                                                <?php if ($rowTypeWorkDetail['type_of_work_rate_half'] > 0) { ?>
                                                    <?php echo 'ราคาครึ่งวัน: ' . number_format($rowTypeWorkDetail['type_of_work_rate_half'], 0) . ' บาท'; ?>
                                                <?php } ?>
                                            </div>
                                            <div class="ms-3">
                                                <?php if ($rowTypeWorkDetail['type_of_work_rate_full'] > 0) { ?>
                                                    <?php echo ' ราคาเต็มวัน: ' . number_format($rowTypeWorkDetail['type_of_work_rate_full'], 0) . ' บาท'; ?>
                                                <?php } ?>
                                            </div>
                                    <?php
                                        }
                                    } ?>
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
                            <h5>ขอบเขตพื้นที่รับงาน
                                <!-- <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#editType<?php echo $rowPhoto['photographer_id']; ?>">
                                    <i class="fa-solid fa-pencil"></i>
                                </button> -->
                            </h5>
                            <div class="ms-4">
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
                    <div class="mt-5 footer">
                        &copy; <a class="border-bottom text-dark" href="#">2024 Photo Match</a>, All Right Reserved.
                    </div>
                </div>
            </div>
            <!-- Edit Modal -->
            <div class="modal fade" id="editModal<?php echo $rowPhoto['photographer_id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $row['photographer_id']; ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content">
                        <div class="modal-header justify-content-center">
                            <h3 class="modal-title" id="editModalLabel<?php echo $rowPhoto['photographer_id']; ?>"><b>แก้ไขข้อมูลโปรไฟล์</b></h3>
                            <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                        </div>
                        <form class="upe-mutistep-form" method="post" id="Upemultistepsform" action="">
                            <div class="modal-body">
                                <div class="container-xxl">
                                    <div class="mt-3 col-md-12 container-fluid">
                                        <div class="row ">
                                            <div class="col-12">
                                                <div class="text-start mt-1" style="font-size: 18px;"><b>ข้อมูลส่วนตัว</b></div>
                                                <div class="col-12">
                                                    <div class="row mt-2">
                                                        <div class="col-2">
                                                            <label for="prefix" style="font-weight: bold; display: flex; align-items: center;">
                                                                <span style="color: black; margin-right: 5px;font-size: 13px;"> คำนำหน้า</span>
                                                            </label>
                                                            <input type="text" name="prefix" class="form-control mt-2" value="<?php echo $rowPhoto['photographer_prefix']; ?>" readonly>
                                                        </div>
                                                        <div class="col-5">
                                                            <label for="name" style="font-weight: bold; display: flex; align-items: center;">
                                                                <span style="color: black; margin-right: 5px;font-size: 13px;">ชื่อ</span>
                                                                <span style="color: red;">*</span>
                                                            </label>
                                                            <input type="text" name="name" class="form-control mt-1" value="<?php echo $rowPhoto['photographer_name']; ?>" required>
                                                        </div>
                                                        <div class="col-5">
                                                            <label for="surname" style="font-weight: bold; display: flex; align-items: center;">
                                                                <span style="color: black; margin-right: 5px; font-size: 13px;">นามสกุล</span>
                                                                <span style="color: red;">*</span>
                                                            </label>
                                                            <input type="text" name="surname" class="form-control mt-1" value="<?php echo $rowPhoto['photographer_surname']; ?>" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="mt-4">
                                                <div class="mt-2">
                                                    <div class="row">
                                                        <div class="text-start mt-1 mb-2" style="font-size: 18px;"><b>ข้อมูลการติดต่อ</b></div>
                                                        <div class="col-md-6">
                                                            <label for="tell" style="font-weight: bold; display: flex; align-items: center;">
                                                                <span style="color: black; margin-right: 5px; font-size: 13px;">เบอร์โทรศัพท์</span>
                                                                <span style="color: red;">*</span>
                                                            </label>
                                                            <input type="text" name="tell" class="form-control mt-1" value="<?php echo $rowPhoto['photographer_tell']; ?>" required style="resize: none;">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="email" style="font-weight: bold; display: flex; align-items: center;">
                                                                <span style="color: black; margin-right: 5px; font-size: 13px;">อีเมล</span>
                                                                <span style="color: red;">*</span>
                                                            </label>
                                                            <input type="text" name="email" class="form-control mt-1" value="<?php echo $rowPhoto['photographer_email']; ?>" required style="resize: none;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <div class="col-4 mt-2">
                                                <div class="d-flex justify-content-center align-items-center md">
                                                    <div class="circle">
                                                        <div style="width: 60px; height: 60px;">
                                                            <img id="userImage" src="../img/profile/<?php echo $rowPhoto['photographer_photo'] ? $rowPhoto['photographer_photo'] : 'null.png'; ?>">
                                                        </div>
                                                    </div>
                                                </div> -->
                                            <!-- <div class="align-items-center justify-content-center d-flex">
                                                    <div class="mt-4">
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                                            แก้ไขรูปโปรไฟล์
                                                        </button> -->
                                            <!-- modal -->
                                            <!-- <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="uploadModalLabel">เปลี่ยนรูปโปรไฟล์</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form>
                                                                            <div class="mb-4">
                                                                                <label for="photoUpload" class="form-label">เลือกไฟล์รูปภาพ</label>
                                                                                <input type="file" id="photoUpload" name="profileImage" class="form-control" onchange="updateImage()">
                                                                            </div>
                                                                            <button type="submit" class="btn btn-primary">อัปโหลด</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> -->
                                            <!-- <input type="file" id="photo" name="profileImage" class="form-control" onchange="updateImage()"> -->
                                            <!-- </div>
                                                </div> -->
                                            <!-- </div> -->
                                            <hr class="mt-4">
                                            <div class="row justify-content-center">
                                                <div class="col-md-12 ">
                                                    <div class="text-start mt-1" style="font-size: 18px;"><b>ข้อมูลเกี่ยวกับงาน</b>
                                                    </div>
                                                    <div class="mt-3">
                                                        <div class="row">
                                                            <div class="col-2 mt-1">
                                                                <label for="portfolio" style="font-weight: bold; display: flex; align-items: center;">
                                                                    <span style="color: black; margin-right: 5px; font-size: 13px;">ไฟล์แฟ้มสะสมผลงาน</span>
                                                                </label>
                                                            </div>
                                                            <div class="col-8 ms-3">
                                                                <div class="input-group">
                                                                    <input type="text" name="portfolio" class="form-control" value="<?php echo $rowPhoto['photographer_portfolio']; ?>" readonly>
                                                                    <a href="../portfolio/<?php echo $rowPhoto['photographer_portfolio']; ?>" target="_blank" class="btn btn-primary">ดูไฟล์ PDF</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <label class="mb-2" for="workid" style="font-weight: bold; display: flex; align-items: center;">
                                                            <span style="color: black; margin-right: 5px; font-size: 13px;">ประเภทงานที่รับ</span>
                                                            <span style="color: red;">*</span>
                                                        </label>
                                                        <div class="ms-4">
                                                            <?php
                                                            $sql = "SELECT 
                                                                            t.type_id, 
                                                                            t.type_work, 
                                                                            tow_latest.photographer_id, 
                                                                            tow_latest.type_of_work_details, 
                                                                            tow_latest.type_of_work_rate_half, 
                                                                            tow_latest.type_of_work_rate_full
                                                                        FROM 
                                                                            type t
                                                                        INNER JOIN (
                                                                            SELECT 
                                                                                type_id, 
                                                                                photographer_id, 
                                                                                type_of_work_details, 
                                                                                type_of_work_rate_half, 
                                                                                type_of_work_rate_full
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
                                                                            t.type_id = tow_latest.type_id;
                                                                        ";
                                                            $resultTypeWorkDetail = $conn->query($sql);

                                                            if ($resultTypeWorkDetail->num_rows > 0) {
                                                                $index = 0; // Initialize an index for naming inputs
                                                                while ($rowTypeWorkDetail = $resultTypeWorkDetail->fetch_assoc()) {
                                                            ?>
                                                                    <div class="row">
                                                                        <div class="col-2 mt-1">
                                                                            <div class="d-flex align-items-center">
                                                                                <i class="fa-solid fa-circle me-2" style="font-size: 5px;"></i>
                                                                                <b><?php echo htmlspecialchars($rowTypeWorkDetail['type_work']); ?></b>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-9">
                                                                            <div class="mb-3">
                                                                                <div class="row">
                                                                                    <div class="col-2 mt-2">
                                                                                        <label for="rate_half_<?php echo $index; ?>" class="form-label">ราคาครึ่งวัน:</label>
                                                                                    </div>
                                                                                    <div class="col-3">
                                                                                        <input type="number" id="rate_half_<?php echo $index; ?>" name="rate_half[<?php echo $index; ?>]" class="form-control" value="<?php echo htmlspecialchars($rowTypeWorkDetail['type_of_work_rate_half'], ENT_QUOTES, 'UTF-8'); ?>" min="0" step="0.01" placeholder="Enter half-day rate in บาท">
                                                                                    </div>
                                                                                    <div class="col-2 mt-2">
                                                                                        <label for="rate_half_<?php echo $index; ?>" class="form-label"> บาท</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <div class="row">
                                                                                    <div class="col-2 mt-2">
                                                                                        <label for="rate_full_<?php echo $index; ?>" class="form-label">ราคาเต็มวัน:</label>
                                                                                    </div>
                                                                                    <div class="col-3">
                                                                                        <input type="number" id="rate_full_<?php echo $index; ?>" name="rate_full[<?php echo $index; ?>]" class="form-control" value="<?php echo htmlspecialchars($rowTypeWorkDetail['type_of_work_rate_full'], ENT_QUOTES, 'UTF-8'); ?>" min="0" step="0.01" placeholder="Enter full-day rate in บาท">
                                                                                    </div>
                                                                                    <div class="col-2 mt-2">
                                                                                        <label for="rate_full_<?php echo $index; ?>" class="form-label"> บาท</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <!-- Hidden fields to store type ID and photographer ID -->
                                                                            <input type="hidden" name="type_id[<?php echo $index; ?>]" value="<?php echo htmlspecialchars($rowTypeWorkDetail['type_id'], ENT_QUOTES, 'UTF-8'); ?>">
                                                                            <input type="hidden" name="photographer_id[<?php echo $index; ?>]" value="<?php echo htmlspecialchars($rowTypeWorkDetail['photographer_id'], ENT_QUOTES, 'UTF-8'); ?>">
                                                                        </div>
                                                                    </div>
                                                            <?php
                                                                    $index++;
                                                                }
                                                            } ?>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    // Existing database query to get selected work areas
                                                    $sql = "SELECT photographer_scope
                                                            FROM `photographer`
                                                            WHERE photographer.photographer_id = $id_photographer";
                                                    $resultScopSelect = $conn->query($sql);

                                                    // Store selected work areas in an array
                                                    $selectedScopPhoto = [];
                                                    if ($resultScopSelect->num_rows > 0) {
                                                        while ($row = $resultScopSelect->fetch_assoc()) {
                                                            $scop = $row['photographer_scope'];
                                                            $selectedScopPhoto = array_merge($selectedScopPhoto, explode(',', $scop));
                                                        }
                                                    }
                                                    $selectedScopPhoto = array_map('trim', $selectedScopPhoto); // Trim whitespace for accurate checking
                                                    ?>
                                                    <div class="mt-2">
                                                        <div class="row">
                                                            <div class="col-2 mt-3">
                                                                <label for="work_area" style="font-weight: bold; display: flex; align-items: center;">
                                                                    <span style="color: black; margin-right: 5px; font-size: 13px;">ขอบเขตพื้นที่ที่รับงาน</span>
                                                                    <span style="color: red;">*</span>
                                                                </label>
                                                            </div>
                                                            <div class="col-9 mt-3">
                                                                <div class="row ms-2">
                                                                    <div class="col-3 justify-content-sm-start">
                                                                        <div class="form-check d-flex align-items-center">
                                                                            <input type="checkbox" id="bangkok" name="work_area[]" value="กรุงเทพ" class="form-check-input" <?php echo in_array('กรุงเทพฯ', $selectedScopPhoto) ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label ms-2 mb-0" for="bangkok">กรุงเทพฯ</label>
                                                                        </div>
                                                                        <div class="form-check d-flex align-items-center">
                                                                            <input type="checkbox" id="central" name="work_area[]" value="ภาคกลาง" class="form-check-input" <?php echo in_array('ภาคกลาง', $selectedScopPhoto) ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label ms-2 mb-0" for="central">ภาคกลาง</label>
                                                                        </div>
                                                                        <div class="form-check d-flex align-items-center">
                                                                            <input type="checkbox" id="southern" name="work_area[]" value="ภาคใต้" class="form-check-input" <?php echo in_array('ภาคใต้', $selectedScopPhoto) ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label ms-2 mb-0" for="southern">ภาคใต้</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-3 justify-content-sm-start">
                                                                        <div class="form-check d-flex align-items-center">
                                                                            <input type="checkbox" id="northern" name="work_area[]" value="ภาคเหนือ" class="form-check-input" <?php echo in_array('ภาคเหนือ', $selectedScopPhoto) ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label ms-2 mb-0" for="northern">ภาคเหนือ</label>
                                                                        </div>
                                                                        <div class="form-check d-flex align-items-center">
                                                                            <input type="checkbox" id="northeastern" name="work_area[]" value="ภาคตะวันออกเฉียงเหนือ" class="form-check-input" <?php echo in_array('ภาคตะวันออกเฉียงเหนือ', $selectedScopPhoto) ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label ms-2 mb-0" for="northeastern">ภาคตะวันออกเฉียงเหนือ</label>
                                                                        </div>
                                                                        <div class="form-check d-flex align-items-center">
                                                                            <input type="checkbox" id="western" name="work_area[]" value="ภาคตะวันตก" class="form-check-input" <?php echo in_array('ภาคตะวันตก', $selectedScopPhoto) ? 'checked' : ''; ?>>
                                                                            <label class="form-check-label ms-2 mb-0" for="western">ภาคตะวันตก</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="photographer_id" value="<?php echo $rowPhoto['photographer_id']; ?>">
                            <div class="modal-footer mt-2 justify-content-center">
                                <button type="button" onclick="window.location.href='profile.php'" class="btn btn-danger" style="width: 150px; height:45px;" data-bs-dismiss="modal">ปิด</button>
                                <button type="submit" name="submit_photographer" class="btn btn-primary" style="width: 150px; height:45px;">บันทึกการแก้ไข</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- post -->
            <div class="col-6" style="overflow-y: scroll; height: 89vh; scrollbar-width: none; -ms-overflow-style: none;">

                <div class="row">

                    <div class="col-12 card-header bg-white" style="border-radius: 10px; height: auto; box-shadow: 0 2px 2px rgba(0, 0, 0, 0.2);">
                        <div class="col-12 bg-white container" style="height: 160px; border-radius: 10px;">
                            <div class="py-1 px-5 mt-1 ms-2 mb-1 justify-content-center">
                                <div class="d-flex align-items-center justify-content-center mt-3">
                                    <div class="circle" style="width: 50px; height: 50px;">
                                        <img id="userImage" src="../img/profile/<?php echo $rowPhoto['photographer_photo'] ? $rowPhoto['photographer_photo'] : 'null.png'; ?>">
                                    </div>
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#post" class="btn text-start text-black ms-4" style="width: 80%; height: 45px; background-color: #F0F2F5; border-radius: 50px; font-size: 18px;">
                                        วันนี้คุณถ่ายอะไร
                                    </button>
                                </div>
                            </div>
                            <hr>
                            <!-- Buttons for image and work type -->
                            <div class="d-flex align-items-center justify-content-center me-5 ms-5 mb-1">
                                <button class=" justify-content-center " type="button" style="width: 45%; background: none; border: none; display: flex; align-items: center;" data-bs-toggle="modal" data-bs-target="#postPhoto">
                                    <i class="fa-solid fa-images me-2" style="font-size: 30px; color: #69D40F; cursor: pointer;"></i>
                                    <p class="mb-0" style="margin-right: 5px;">ลงผลงาน</p>
                                </button>
                                <button class=" justify-content-center " type="button" style="width: 40%; background: none; border: none; display: flex; align-items: center;" data-bs-toggle="modal" data-bs-target="#postType">
                                    <i class="fa-solid fa-briefcase me-2" style="font-size: 30px; color: #E53935; cursor: pointer;"></i>
                                    <p class="mb-0" style="margin-right: 5px;">ลงประเภทงานที่รับ</p>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- post -->
                    <div class="modal fade" id="post" tabindex="-1" aria-labelledby="postLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" style="width: 35%;">
                            <div class="modal-content justify-content-center">
                                <div class="modal-header justify-content-center">
                                    <h5 class="modal-title me-2 justify-content-center text-center" id="postLabel">โพสต์</h5>
                                    <button type="button" onclick="window.location.href='profile.php'" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Form for editing photographer's information -->
                                    <div class="container">
                                        <div class="">
                                            <div class="d-flex align-items-center mb-3 justify-content-start mt-3">
                                                <div class="circle me-3" style="width: 60px; height: 60px;">
                                                    <img src="../img/profile/<?php echo $rowPhoto['photographer_photo'] ? $rowPhoto['photographer_photo'] : 'null.png'; ?>">
                                                </div>
                                                <div class="col-7">
                                                    <p><?php echo $rowPhoto['photographer_name'] . ' ' . $rowPhoto['photographer_surname']; ?></p>
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- post portfolio -->
                    <div class="modal fade" id="postPhoto" tabindex="-1" aria-labelledby="postPhotoLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" style="width: 35%;">
                            <div class="modal-content">
                                <div class="modal-header justify-content-center">
                                    <h5 class="modal-title" id="postPhotolLabel">ลงผลงาน</h5>
                                    <button type="button" onclick="window.location.href='profile.php'" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form class="upe-mutistep-form" method="post" id="Upemultistepsform" action="" enctype="multipart/form-data">
                                    <div class="modal-body" style="height:auto;">
                                        <!-- Form for editing photographer's information -->
                                        <div class="container">
                                            <div class="form-container">
                                                <div class="d-flex align-items-center justify-content-start mt-3">
                                                    <div class="circle me-3" style="width: 60px; height: 60px;">
                                                        <img id="userImage" src="../img/profile/<?php echo $rowPhoto['photographer_photo'] ? $rowPhoto['photographer_photo'] : 'null.png'; ?>">
                                                    </div>
                                                    <div class="mt-2">
                                                        <p><?php echo $rowPhoto['photographer_name'] . ' ' . $rowPhoto['photographer_surname']; ?></p>

                                                    </div>
                                                </div>
                                                <div class="mt-4">
                                                    <select class="form-select border-1 py-2" name="workPost" id="workPost">
                                                        <option required value="">เลือกประเภทงาน</option>
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
                                                            echo '<option value="">ไม่มีประเภทงาน ต้องลงประเภทงานที่รับก่อน</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="post-input-container">
                                                    <textarea name="caption" rows="8" required placeholder="วันนี้คุณถ่ายอะไร"></textarea>
                                                </div>
                                                <div class="post-image-preview" id="preview-container" style="display: flex; flex-wrap: wrap; gap: 10px;">
                                                </div>
                                                <div class="mt-2">
                                                    <label class="form-label" for="imp_event"><strong>อัพโหลดภาพ (ไม่เกิน 10 ภาพ)</strong><br></label>
                                                    <input class="form-control" required type="file" name="upload[]" multiple="multiple" id="fileUpload" accept="image/jpeg, image/jpg, image/png">
                                                    <progress id="progressBar" value="0" max="100" style="width:300px;display:none"></progress>
                                                    <p id="loaded_n_total"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="submit_post_portfolio" class="btn text-white" style="background:#0F52BA; width: 100%;">โพสต์</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- post type -->
                    <div class="modal fade" id="postType" tabindex="-1" aria-labelledby="postTypeLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" style="width: 30%;">
                            <div class="modal-content">
                                <div class="modal-header justify-content-center">
                                    <h5 class="modal-title justify-content-center" id="postTypeLabel">ลงประเภทงานที่รับ</h5>
                                    <button type="button" onclick="window.location.href='profile.php'" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form class="upe-mutistep-form" method="post" id="Upemultistepsform" action="">
                                    <div class="modal-body" style="height: auto;">
                                        <!-- Form for editing photographer's information -->
                                        <div class="container">
                                            <div class="d-flex align-items-center mb-3 justify-content-start mt-3">
                                                <div class="circle me-3" style="width: 60px; height: 60px;">
                                                    <img id="userImage" src="../img/profile/<?php echo $rowPhoto['photographer_photo'] ? $rowPhoto['photographer_photo'] : 'null.png'; ?>">
                                                </div>
                                                <div>
                                                    <p><?php echo $rowPhoto['photographer_name'] . ' ' . $rowPhoto['photographer_surname']; ?></p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4 mt-4">
                                                    <span style="color: black;">ประเภทงานที่รับ</span>
                                                </div>
                                                <div class="col-8 mt-2">
                                                    <select class="form-select border-1 py-2" name="type">
                                                        <option selected required>เลือกประเภทงานที่รับ</option>
                                                        <?php
                                                        $sqlType = "SELECT t.*
                                                        FROM `type` t
                                                        LEFT JOIN type_of_work tow ON t.type_id = tow.type_id AND tow.photographer_id = $id_photographer
                                                        WHERE tow.type_id IS NULL;";
                                                        $resultType = $conn->query($sqlType);
                                                        $rowType = $resultInfo->fetch_assoc();
                                                        if ($resultType->num_rows > 0) {
                                                            while ($rowType = $resultType->fetch_assoc()) {
                                                                echo '<option value="' . htmlspecialchars($rowType['type_id']) . '">' . htmlspecialchars($rowType['type_work']) . '</option>';
                                                            }
                                                        } else {
                                                            echo '<option value="">ไม่มีประเภทงาน คุณได้ลงประเภทงานครบแล้ว</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4 mt-4">
                                                    <label for="rate_half">
                                                        <span style="color: black;">เรทราคาครึ่งวัน</span>
                                                        <div class="row">
                                                            <span style="color: red;font-size: 13px;">หากไม่รับครึ่งวันไม่ต้องกรอก</span>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="col-8 mt-4">
                                                    <input type="text" name="rate_half" placeholder="กรอกเรทราคาครึ่งวัน" style="outline: none; width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4 mt-4">
                                                    <label for="rate_full">
                                                        <span style="color: black;">เรทราคาเต็มวัน</span>
                                                        <div class="row">
                                                            <span style="color: red;font-size: 13px;">หากไม่รับเต็มวันไม่ต้องกรอก</span>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="col-8 mt-4">
                                                    <input type="text" name="rate_full" placeholder="กรอกเรทราคาเต็มวัน" style="outline: none; width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
                                                </div>
                                            </div>
                                            <div class="post-input-container">
                                                <span style="color: black;">รายละเอียดการรับงาน</span>
                                                <span style="color: red;">*</span>
                                                <textarea name="details" placeholder="รายละเอียดการรับงาน" style="outline: none; width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="photographer_id" value="<?php echo $rowPhoto['photographer_id']; ?>">
                                    <div class="modal-footer">
                                        <button type="submit" name="submit_type_of_work" class="btn text-white" style="background:#0F52BA; width: 100%;">โพสต์</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

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
                    ?>
                    <?php while ($rowPost = $resultPost->fetch_assoc()) : ?>

                        <div class="col-12 card-body bg-white mt-2 mb-5" style="border-radius: 10px; box-shadow: 0 2px 2px rgba(0, 0, 0, 0.2); height: auto; max-height: 650; ">
                            <div class="py-1 px-5 mt-1 ms-2 mb-1 justify-content-center">
                                <div class="d-flex align-items-center justify-content-start mt-3">
                                    <div style="display: flex; align-items: center;">
                                        <div class="circle me-3" style="width: 60px; height: 60px;">
                                            <img src="../img/profile/<?php echo $rowPhoto['photographer_photo'] ? $rowPhoto['photographer_photo'] : 'null.png'; ?>">
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
                                            <a data-fancybox="gallery" href="../img/post/<?php echo trim($photos[$i]) ?>">
                                                <img class="post-img" style="max-width: 100%; height: 100%;" src="../img/post/<?php echo trim($photos[$i]) ?>" alt="img-post" />
                                            </a>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
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
            $bookedPeriods = []; // เก็บช่วงเวลาการจอง

            if ($resultBooking->num_rows > 0) {
                while ($rowBooking = $resultBooking->fetch_assoc()) {
                    $startDate = $rowBooking['booking_start_date'];
                    $endDate = $rowBooking['booking_end_date'];

                    // เพิ่มช่วงเวลาการจองลงในอาร์เรย์
                    $bookedPeriods[] = [$startDate, $endDate];

                    // เพิ่มวันเริ่มต้นการจองลงในอาร์เรย์
                    $bookedDates[] = $startDate;
                }
            }

            // สร้างอาร์เรย์วันทั้งหมดในสัปดาห์ปัจจุบัน
            $allDates = [];
            $currentDate = $startOfWeek;
            for ($i = 0; $i < 7; $i++) {
                $allDates[] = $currentDate;
                $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
            }

            // ตรวจสอบช่วงเวลาการจองและอัพเดตวันในช่วงเวลาที่จอง
            foreach ($bookedPeriods as $period) {
                list($periodStart, $periodEnd) = $period;

                foreach ($allDates as $date) {
                    if ($date >= $periodStart && $date <= $periodEnd) {
                        $bookedDates[] = $date;
                    }
                }
            }

            // ลบวันจองที่ซ้ำออก
            $bookedDates = array_unique($bookedDates);

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
                        $backgroundColor = in_array($currentDate, $bookedDates) ? 'lightcoral' : 'lightgreen';
                        echo "<div id='bookingStatus' class='col-12 text-center mb-3' style='border-radius: 10px; padding-top: 10px; padding-bottom: 10px; background-color: {$backgroundColor};'>";
                        echo "<p class='mb-0'>";
                        echo "วันที่: " . htmlspecialchars($currentDate);

                        if (in_array($currentDate, $bookedDates)) {
                            echo " - จองแล้ว";
                        } else {
                            echo " - ว่าง";
                        }

                        echo "</p>";
                        echo "</div>";
                        $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
                    }
                    ?>

                    <div class="justify-content-center py-4 text-center">
                        <button type="button" class="btn btn-dark btn-sm" onclick="window.location.href='table.php'">
                            <i class="fa-solid fa-magnifying-glass"></i> ดูเพิ่มเติม
                        </button>
                    </div>
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

        <script>
            document.getElementById('uploadImageButton').addEventListener('click', function() {
                document.getElementById('postImg').click();
            });
        </script>

        <!-- Fancybox JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
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
                var postOptionRadios = document.querySelectorAll('input[name="postOption"]');
                var postContentDiv = document.getElementById('postContent');

                // Display initial content for posting photos
                displayPhotoPostContent();

                // Function to display photo post content
                function displayPhotoPostContent() {
                    postContentDiv.innerHTML = `
                    <div class="col-12 mt-3">
                        <form class="upe-mutistep-form" method="post" id="Upemultistepsform" action="" enctype="multipart/form-data">
                            <div class="form-container">
                                <div>
                                    <select class="form-select border-1 py-2" name="workPost" id="workPost" required>
                                        <option value="">เลือกประเภทงาน</option>
                                        <?php
                                        $sql = "SELECT t.type_id, t.type_work, MAX(tow.photographer_id) AS photographer_id
                                                FROM type t
                                                INNER JOIN type_of_work tow ON t.type_id = tow.type_id
                                                WHERE tow.photographer_id = $id_photographer
                                                GROUP BY t.type_id, t.type_work;";
                                        $resultTypeWork = $conn->query($sql);

                                        if ($resultTypeWork->num_rows > 0) {
                                            while ($rowTypeWork = $resultTypeWork->fetch_assoc()) {
                                                echo '<option value="' . htmlspecialchars($rowTypeWork['type_id']) . '">' . htmlspecialchars($rowTypeWork['type_work']) . '</option>';
                                            }
                                        } else {
                                            echo '<option value="">ไม่มีประเภทงาน ต้องลงประเภทงานที่รับก่อน</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="post-input-container">
                                <textarea name="caption" rows="8" required placeholder="วันนี้คุณถ่ายอะไร"></textarea>
                            </div>
                            <div class="post-image-preview" id="preview-containerT" style="display: flex; flex-wrap: wrap; gap: 10px;"></div>
                            <div class="mt-2">
                                <label class="form-label" for="imp_event"><strong>อัพโหลดภาพ (ไม่เกิน 10 ภาพ)</strong><br></label>
                                <input class="form-control" type="file" name="upload[]" multiple="multiple" id="fileUploadT" accept="image/*" required>
                                <progress id="progressBar" value="0" max="100" style="width:300px;display:none"></progress>
                                <p id="loaded_n_total"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="submit_post_portfolio" class="btn text-white" style="background:#0F52BA; width: 100%;">โพสต์</button>
                            </div>
                        </form>
                    </div>
                    `;
                    addFileUploadListener();
                }

                // Function to display type post content
                function displayTypePostContent() {
                    postContentDiv.innerHTML = `
                    <div class="col-12 mt-4">
                        <form class="upe-mutistep-form" method="post" id="Upemultistepsform" action="">
                            <div class="row">
                                <div class="col-4 mt-4">
                                    <span style="color: black;">ประเภทงานที่รับ</span>
                                </div>
                                <div class="col-8 mt-2">
                                    <select class="form-select border-1 py-2" name="type" required>
                                        <option value="">เลือกประเภทงานที่รับ</option>
                                        <?php
                                        $sqlType = "SELECT t.* FROM type t
                                                    LEFT JOIN type_of_work tow ON t.type_id = tow.type_id AND tow.photographer_id = $id_photographer
                                                    WHERE tow.type_id IS NULL;";
                                        $resultType = $conn->query($sqlType);

                                        if ($resultType->num_rows > 0) {
                                            while ($rowType = $resultType->fetch_assoc()) {
                                                echo '<option value="' . htmlspecialchars($rowType['type_id']) . '">' . htmlspecialchars($rowType['type_work']) . '</option>';
                                            }
                                        } else {
                                            echo '<option value="">ไม่มีประเภทงาน คุณได้ลงประเภทงานครบแล้ว</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4 mt-4">
                                    <label for="rate_half">
                                        <span style="color: black;">เรทราคาครึ่งวัน</span>
                                        <div class="row">
                                            <span style="color: red;font-size: 13px;">หากไม่รับครึ่งวันไม่ต้องกรอก</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-8 mt-4">
                                    <input type="text" name="rate_half" placeholder="กรอกเรทราคาครึ่งวัน" style="outline: none; width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4 mt-4">
                                    <label for="rate_full">
                                        <span style="color: black;">เรทราคาเต็มวัน</span>
                                        <div class="row">
                                            <span style="color: red;font-size: 13px;">หากไม่รับเต็มวันไม่ต้องกรอก</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-8 mt-4">
                                    <input type="text" name="rate_full" placeholder="กรอกเรทราคาเต็มวัน" style="outline: none; width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
                                </div>
                            </div>
                            <div class="post-input-container">
                                <span style="color: black;">รายละเอียดการรับงาน</span>
                                <span style="color: red;">*</span>
                                <textarea name="details" placeholder="รายละเอียดการรับงาน" style="outline: none; width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;" required></textarea>
                            </div>
                            <input type="hidden" name="photographer_id" value="<?php echo $rowPhoto['photographer_id']; ?>">
                            <div class="mt-5">
                                <button type="submit" name="submit_type_of_work" class="btn text-white" style="background:#0F52BA; width: 100%;">โพสต์</button>
                            </div>
                        </form>
                    </div>`;
                }

                // Function to add file upload listener
                function addFileUploadListener() {
                    document.getElementById('fileUploadT').addEventListener('change', function() {
                        const previewContainer = document.getElementById('preview-containerT');
                        previewContainer.innerHTML = ''; // Clear previous images

                        const files = this.files; // Selected files

                        if (files.length > 10) {
                            alert("คุณสามารถอัพโหลดได้ไม่เกิน 10 ภาพเท่านั้น");
                            this.value = ''; // Clear selected files
                            return;
                        }

                        for (let i = 0; i < files.length; i++) {
                            const file = files[i];
                            const reader = new FileReader();

                            reader.onload = function(e) {
                                const img = document.createElement('img');
                                img.src = e.target.result;
                                img.style.width = '150px';
                                img.style.height = '150px';
                                img.style.objectFit = 'cover';
                                img.style.borderRadius = '10px';
                                previewContainer.appendChild(img); // Add image to preview container
                            }

                            reader.readAsDataURL(file); // Read file as Data URL
                        }
                    });
                }

                // Add event listener for radio buttons
                postOptionRadios.forEach(function(radio) {
                    radio.addEventListener('change', function() {
                        if (this.id === 'postPhotoRadio' && this.checked) {
                            displayPhotoPostContent();
                        } else if (this.id === 'postTypeRadio' && this.checked) {
                            displayTypePostContent();
                        }
                    });
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const userImage = document.getElementById('userImage');

                // Set a default image if the current src is null, empty, or ends with '/'
                if (!userImage.src || userImage.src.endsWith('/') || userImage.src.includes('null')) {
                    userImage.src = '../img/profile/null.png'; // Path to the default image
                }
            });

            function updateImage() {
                const input = document.getElementById('photo');
                const userImage = document.getElementById('userImage');
                const file = input.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        userImage.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            }
        </script>

        <script>
            document.getElementById('fileUpload').addEventListener('change', function() {
                const previewContainer = document.getElementById('preview-container');
                previewContainer.innerHTML = '';
                const files = this.files;
                if (files.length > 10) {
                    alert("คุณสามารถอัพโหลดได้ไม่เกิน 10 ภาพเท่านั้น");
                    this.value = '';
                    return;
                }
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.width = '150px';
                        img.style.height = '150px';
                        img.style.objectFit = 'cover';
                        img.style.borderRadius = '10px';
                        previewContainer.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                }
            });
        </script>
        <script>
            document.getElementById('fileUploadT').addEventListener('change', function() {
                const previewContainer = document.getElementById('preview-containerT');
                previewContainer.innerHTML = ''; // เคลียร์คอนเทนเนอร์ภาพเก่าทั้งหมด

                const files = this.files; // ไฟล์ที่ถูกเลือก

                if (files.length > 10) {
                    alert("คุณสามารถอัพโหลดได้ไม่เกิน 10 ภาพเท่านั้น");
                    this.value = ''; // เคลียร์ไฟล์ที่เลือก
                    return;
                }

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.width = '150px';
                        img.style.height = '150px';
                        img.style.objectFit = 'cover';
                        img.style.borderRadius = '10px';
                        previewContainer.appendChild(img); // เพิ่มภาพที่ตัวอย่างในคอนเทนเนอร์
                    }

                    reader.readAsDataURL(file); // อ่านไฟล์ในรูปแบบ Data URL
                }
            });
        </script>

</body>

</html>