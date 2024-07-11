<?php
session_start();
include '../config_db.php';
require_once '../popup.php';

$sql = "SELECT * FROM `information`";
$resultInfo = $conn->query($sql);
$rowInfo = $resultInfo->fetch_assoc();

if (isset($_SESSION['photographer_login'])) {
    $email = $_SESSION['photographer_login'];
    $sql = "SELECT * FROM photographer WHERE photographer_email LIKE '$email'";
    $resultPhoto = $conn->query($sql);
    $rowPhoto = $resultPhoto->fetch_assoc();
    $id_photographer = $rowPhoto['photographer_id'];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['submit_photographer'])) {
        $photographer_id = $_POST["photographer_id"];
        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $address = $_POST["address"];
        $district = $_POST["district"];
        $province = $_POST["province"];
        $zipcode = $_POST["zipcode"];
        $tell = $_POST["tell"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $work_area = $_POST["work_area"];
        $bank = isset($_POST["bank"]) ? $_POST["bank"] : "";
        $accountNumber = $_POST["accountNumber"];
        $accountName = $_POST["accountName"];
        $profileImage = ""; // Initialize the profileImage variable

        // Check if the profile image is uploaded
        if (isset($_FILES["profileImage"]) && $_FILES['profileImage']['error'] == 0) {
            $image_file = $_FILES['profileImage']['name'];
            $new_name = date("d_m_Y_H_i_s") . '-' . $image_file;
            $type = $_FILES['profileImage']['type'];
            $size = $_FILES['profileImage']['size'];
            $temp = $_FILES['profileImage']['tmp_name'];

            $path = "../img/profile/" . $new_name;
            $allowed_types = array('image/jpg', 'image/jpeg', 'image/png', 'image/gif');

            // Check if the directory exists, if not, create it
            if (!is_dir("img/profile")) {
                mkdir("img/profile", 0777, true);
            }

            // Validate image type and size
            if (in_array($type, $allowed_types) && $size < 5000000) { // 5MB limit
                if (!file_exists($path)) {
                    if (move_uploaded_file($temp, $path)) {
                        $profileImage = $new_name;
                    } else {
                        echo '
                        <div>
                            <script>
                                Swal.fire({
                                    title: "<div class=\"t1\">มีปัญหาในการย้ายไฟล์รูปภาพ</div>",
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
                        exit();
                    }
                } else {
                    echo "File already exists... Check upload folder<br>";
                    exit();
                }
            } else {
                echo '
                <div>
                    <script>
                        Swal.fire({
                            title: "<div class=\"t1\">อัปโหลดไฟล์รูปภาพเฉพาะรูปแบบ JPG, JPEG, PNG และ GIF เท่านั้น หรือขนาดไฟล์เกิน 5MB</div>",
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
                exit();
            }
        } else {
            echo '
            <div>
                <script>
                    Swal.fire({
                        title: "<div class=\"t1\">กรุณาอัพโหลดรูปโปรไฟล์</div>",
                        icon: "error",
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
            exit();
        }

        $sql = "UPDATE photographer SET 
            photographer_name = ?, 
            photographer_surname = ?, 
            photographer_tell = ?, 
            photographer_address = ?, 
            photographer_district = ?, 
            photographer_province = ?, 
            photographer_scope = ?, 
            photographer_zip_code = ?, 
            photographer_email = ?, 
            photographer_password = ?, 
            photographer_photo = ?, 
            photographer_bank = ?, 
            photographer_account_name = ?, 
            photographer_account_number = ? 
            WHERE photographer_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssssssi", $name, $surname, $tell, $address, $district, $province, $work_area, $zipcode, $email, $password, $profileImage, $bank, $accountName, $accountNumber, $photographer_id);

        if ($stmt->execute()) {
?>
            <script>
                setTimeout(function() {
                    Swal.fire({
                        title: '<div class="t1">บันทึกการแก้ไขสำเร็จ</div>',
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
                });
            </script>
        <?php
        } else {
        ?>
            <script>
                setTimeout(function() {
                    Swal.fire({
                        title: '<div class="t1">เกิดข้อผิดพลาดในการบันทึกการแก้ไข</div>',
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
        // Close the statement after usage
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
        $photographer_id = $_POST['photographer_id'];
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

        }

        .f {
            font-family: 'Athiti', sans-serif;
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

        .container {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .bgIndex {
            background-image: url('../img/bgIndex1.png');
            background-attachment: fixed;
            background-size: cover;
            /* เพิ่มการปรับแต่งในการขยับภาพตามต้องการ */
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

        .slider {
            position: relative;
            width: 100%;
            max-width: 600px;
            margin: auto;
            overflow: hidden;
            border: 2px solid #ddd;
        }

        .slides {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .slides img {
            width: 100%;
            display: block;
        }

        .navigation {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
        }

        .prev,
        .next {
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 10px;
            cursor: pointer;
        }

        .property-item img {
            width: 100%;
            height: 250px;
            /* กำหนดความสูงตามที่คุณต้องการ */
            object-fit: cover;
            /* ทำให้รูปภาพครอบคลุมพื้นที่ */
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
    <div class="bgIndex" style="height: 560px;">
        <!-- <div style="background-color: rgba(	0, 41, 87, 0.6);"> -->
        <div class="d-flex justify-content-center">
            <nav class="navbar navbar-expand-lg navbar-dark col-10">
            <a href="index.php" class="navbar-brand d-flex align-items-center text-center">
                <img class="img-fluid" src="../img/logo/<?php echo isset($rowInfo['information_icon']) ? $rowInfo['information_icon'] : ''; ?>" style="height: 30px;">
            </a>
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon text-primary"></span>
                </button>
                <div class="collapse navbar-collapse m-4" id="navbarCollapse">
                    <div class="navbar-nav ms-auto">
                        <a href="index.php" class="nav-item nav-link active">หน้าหลัก</a>
                        <a href="table.php" class="nav-item nav-link">ตารางงาน</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">รายการจอง</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="bookingListAll.php" class="dropdown-item">รายการจองทั้งหมด</a>
                                <a href="bookingListWaittingForApproval.php" class="dropdown-item">รายการจองที่รออนุมัติ</a>
                                <a href="bookingListApproved.php" class="dropdown-item">รายการจองที่อนุมัติแล้ว</a>
                                <a href="bookingListNotApproved.php" class="dropdown-item">รายการจองที่ไม่อนุมัติ</a>
                            </div>
                        </div>
                        <!-- <a href="report.php" class="nav-item nav-link">รายงาน</a> -->
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">โปรไฟล์</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="profile.php" class="dropdown-item">โปรไฟล์</a>
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

        <!-- Category Start -->
        <div class="mb-5 mt-4 text-center mx-auto  wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <h1 class="f" style="color:aliceblue;"><?php echo $rowInfo['information_name']; ?></h1>
            <p style="color:aliceblue;"><?php echo $rowInfo['information_caption']; ?></p>
        </div>
        <div class="col-12 container" style="height: 160px; border-radius: 10px; background-color: rgb(255,255,255, 0.7);">
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
                <button type="button" class=" justify-content-center" style="width: 45%; background: none; border: none; display: flex; align-items: center;" data-bs-toggle="modal" data-bs-target="#postPhoto">
                    <i class="fa-solid fa-images me-2" style="font-size: 30px; color: #69D40F; cursor: pointer;"></i>
                    <p class="mb-0" style="margin-right: 5px;">ลงผลงาน</p>
                </button>
                <button type="button" class=" justify-content-center" style="width: 40%; background: none; border: none; display: flex; align-items: center;" data-bs-toggle="modal" data-bs-target="#postType">
                    <i class="fa-solid fa-briefcase me-2" style="font-size: 30px; color: #E53935; cursor: pointer;"></i>
                    <p class="mb-0" style="margin-right: 5px;">ลงประเภทงานที่รับ</p>
                </button>
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
                                            <option required>เลือกประเภทงาน</option>
                                            <?php
                                            // ทำการเชื่อมต่อฐานข้อมูล ($conn) ก่อน query
                                            $sql = "SELECT t.type_id, t.type_work, MAX(tow.photographer_id) AS photographer_id
                                                            FROM type t
                                                            INNER JOIN type_of_work tow ON t.type_id = tow.type_id
                                                            GROUP BY t.type_id, t.type_work;
                                                            ";
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
                                        <input class="form-control" required type="file" name="upload[]" multiple="multiple" id="fileUpload" accept="image/*">
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
                                                    FROM type t
                                                    LEFT JOIN type_of_work tow ON t.type_id = tow.type_id
                                                    WHERE tow.photographer_id IS NULL;
                                                    ";
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
    </div>
    <!-- Category End -->


    <div class="container mb-5 mt-2" style="height: 100%">
        <div class="container-lg mt-3">

            <!-- Examples of work Start -->
            <div class="container-xxl mt-4">
                <div class="container">
                    <div class="row g-0 gx-5 align-items-end">
                        <div class="col-lg-6">
                            <div class="text-start mx-auto mb-5 wow slideInLeft" data-wow-delay="0.1s">
                                <h1 class="mb-3 f">ผลงานช่างภาพ</h1>
                                <!-- <p>คุณลองดูผลงานช่างภาพของเราสิ!!!</p> -->
                            </div>
                        </div>
                        <div class="col-lg-6 text-start text-lg-end wow slideInRight" data-wow-delay="0.1s">
                            <ul class="nav nav-pills d-inline-flex justify-content-end mb-5">
                                <li class="nav-item me-2">
                                    <a class="btn btn-outline-primary active" data-bs-toggle="pill" href="#tab-1">Featured</a>
                                </li>
                                <li class="nav-item me-2">
                                    <a class="btn btn-outline-primary" data-bs-toggle="pill" href="#tab-2">For Sell</a>
                                </li>
                                <li class="nav-item me-0">
                                    <a class="btn btn-outline-primary" data-bs-toggle="pill" href="#tab-3">For Rent</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div id="tab-1" class="tab-pane fade show p-0 active">
                            <div class="row g-4">
                                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                                    <div class="property-item rounded overflow-hidden">
                                        <div class="position-relative overflow-hidden">
                                            <a href=""><img class="img-fluid" src="../img/dev15.jpg" alt=""></a>
                                            <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">งานวันรับปริญญา</div>
                                        </div>
                                        <div class="p-4 pb-0">
                                            <h5 class="text-dark mb-3">$5000</h5>
                                            <a class="d-block h5 mb-2" href="">ช่างภาพ คุณ...</a>
                                            <p><i class="fa fa-map-marker-alt text-dark me-2"></i>จังหวัดขอนแก่น</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                                    <div class="property-item rounded overflow-hidden">
                                        <div class="position-relative overflow-hidden">
                                            <a href=""><img class="img-fluid" src="../img/dev19.jpg" alt=""></a>
                                            <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">งานบวช</div>
                                        </div>
                                        <div class="p-4 pb-0">
                                            <h5 class="text-dark mb-3">$4000</h5>
                                            <a class="d-block h5 mb-2" href="">ช่างภาพ คุณ.....</a>
                                            <p><i class="fa fa-map-marker-alt text-dark me-2"></i>ภาคกลาง</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                                    <div class="property-item rounded overflow-hidden">
                                        <div class="position-relative overflow-hidden">
                                            <a href=""><img class="img-fluid" src="../img/d1.jpg" alt=""></a>
                                            <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">งานแต่งงาน</div>
                                        </div>
                                        <div class="p-4 pb-0">
                                            <h5 class="text-dark mb-3">$6000</h5>
                                            <a class="d-block h5 mb-2" href="">ช่างภาพ คุณ ....</a>
                                            <p><i class="fa fa-map-marker-alt text-dark me-2"></i>กลางภาค</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                                    <div class="property-item rounded overflow-hidden">
                                        <div class="position-relative overflow-hidden">
                                            <a href=""><img class="img-fluid" src="../img/d2.jpg" alt=""></a>
                                            <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">งานพรีเวดดิ้ง</div>
                                        </div>
                                        <div class="p-4 pb-0">
                                            <h5 class="text-dark mb-3">$12,345</h5>
                                            <a class="d-block h5 mb-2" href="">ช่างภาพ คุณ...</a>
                                            <p><i class="fa fa-map-marker-alt text-dark me-2"></i>ภาคกลาง</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                                    <div class="property-item rounded overflow-hidden">
                                        <div class="position-relative overflow-hidden">
                                            <a href=""><img class="img-fluid" src="../img/dev18.jpg" alt=""></a>
                                            <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">งานบวช</div>
                                        </div>
                                        <div class="p-4 pb-0">
                                            <h5 class="text-dark mb-3">$5000</h5>
                                            <a class="d-block h5 mb-2" href="">ช่างภาพ คุณ...</a>
                                            <p><i class="fa fa-map-marker-alt text-dark me-2"></i>กลางภาค</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                                    <div class="property-item rounded overflow-hidden">
                                        <div class="position-relative overflow-hidden">
                                            <a href=""><img class="img-fluid" src="../img/dev17.jpg" alt=""></a>
                                            <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">งานวันรับปริญญา</div>
                                        </div>
                                        <div class="p-4 pb-0">
                                            <h5 class="text-dark mb-3">$5000</h5>
                                            <a class="d-block h5 mb-2" href="">ช่างภาพ คุณ...</a>
                                            <p><i class="fa fa-map-marker-alt text-dark me-2"></i>กลางภาค</p>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-12 text-center wow fadeInUp" data-wow-delay="0.1s">
                                    <a class="btn btn-dark py-3 px-5" href="">ดูเพิ่มเติม</a>
                                </div>
                            </div>
                        </div>
                        <div id="tab-2" class="tab-pane fade show p-0">
                            <div class="row g-4">
                                <div class="col-lg-4 col-md-6">
                                    <div class="property-item rounded overflow-hidden">
                                        <div class="position-relative overflow-hidden">
                                            <a href=""><img class="img-fluid" src="../img/property-1.jpg" alt=""></a>
                                            <div class="bg-dark rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">For Sell</div>
                                            <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Appartment</div>
                                        </div>
                                        <div class="p-4 pb-0">
                                            <h5 class="text-dark mb-3">$12,345</h5>
                                            <a class="d-block h5 mb-2" href="">Golden Urban House For Sell</a>
                                            <p><i class="fa fa-map-marker-alt text-dark me-2"></i>123 Street, New York, USA</p>
                                        </div>
                                        <div class="d-flex border-top">
                                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-dark me-2"></i>1000 Sqft</small>
                                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-dark me-2"></i>3 Bed</small>
                                            <small class="flex-fill text-center py-2"><i class="fa fa-bath text-dark me-2"></i>2 Bath</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="property-item rounded overflow-hidden">
                                        <div class="position-relative overflow-hidden">
                                            <a href=""><img class="img-fluid" src="../img/property-2.jpg" alt=""></a>
                                            <div class="bg-dark rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">For Rent</div>
                                            <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Villa</div>
                                        </div>
                                        <div class="p-4 pb-0">
                                            <h5 class="text-dark mb-3">$12,345</h5>
                                            <a class="d-block h5 mb-2" href="">Golden Urban House For Sell</a>
                                            <p><i class="fa fa-map-marker-alt text-dark me-2"></i>123 Street, New York, USA</p>
                                        </div>
                                        <div class="d-flex border-top">
                                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-dark me-2"></i>1000 Sqft</small>
                                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-dark me-2"></i>3 Bed</small>
                                            <small class="flex-fill text-center py-2"><i class="fa fa-bath text-dark me-2"></i>2 Bath</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="property-item rounded overflow-hidden">
                                        <div class="position-relative overflow-hidden">
                                            <a href=""><img class="img-fluid" src="../img/property-3.jpg" alt=""></a>
                                            <div class="bg-dark rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">For Sell</div>
                                            <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Office</div>
                                        </div>
                                        <div class="p-4 pb-0">
                                            <h5 class="text-dark mb-3">$12,345</h5>
                                            <a class="d-block h5 mb-2" href="">Golden Urban House For Sell</a>
                                            <p><i class="fa fa-map-marker-alt text-dark me-2"></i>123 Street, New York, USA</p>
                                        </div>
                                        <div class="d-flex border-top">
                                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-dark me-2"></i>1000 Sqft</small>
                                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-dark me-2"></i>3 Bed</small>
                                            <small class="flex-fill text-center py-2"><i class="fa fa-bath text-dark me-2"></i>2 Bath</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="property-item rounded overflow-hidden">
                                        <div class="position-relative overflow-hidden">
                                            <a href=""><img class="img-fluid" src="../img/property-4.jpg" alt=""></a>
                                            <div class="bg-dark rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">For Rent</div>
                                            <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Building</div>
                                        </div>
                                        <div class="p-4 pb-0">
                                            <h5 class="text-dark mb-3">$12,345</h5>
                                            <a class="d-block h5 mb-2" href="">Golden Urban House For Sell</a>
                                            <p><i class="fa fa-map-marker-alt text-dark me-2"></i>123 Street, New York, USA</p>
                                        </div>
                                        <div class="d-flex border-top">
                                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-dark me-2"></i>1000 Sqft</small>
                                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-dark me-2"></i>3 Bed</small>
                                            <small class="flex-fill text-center py-2"><i class="fa fa-bath text-dark me-2"></i>2 Bath</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="property-item rounded overflow-hidden">
                                        <div class="position-relative overflow-hidden">
                                            <a href=""><img class="img-fluid" src="../img/property-5.jpg" alt=""></a>
                                            <div class="bg-dark rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">For Sell</div>
                                            <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Home</div>
                                        </div>
                                        <div class="p-4 pb-0">
                                            <h5 class="text-dark mb-3">$12,345</h5>
                                            <a class="d-block h5 mb-2" href="">Golden Urban House For Sell</a>
                                            <p><i class="fa fa-map-marker-alt text-dark me-2"></i>123 Street, New York, USA</p>
                                        </div>
                                        <div class="d-flex border-top">
                                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-dark me-2"></i>1000 Sqft</small>
                                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-dark me-2"></i>3 Bed</small>
                                            <small class="flex-fill text-center py-2"><i class="fa fa-bath text-dark me-2"></i>2 Bath</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="property-item rounded overflow-hidden">
                                        <div class="position-relative overflow-hidden">
                                            <a href=""><img class="img-fluid" src="../img/property-6.jpg" alt=""></a>
                                            <div class="bg-dark rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">For Rent</div>
                                            <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Shop</div>
                                        </div>
                                        <div class="p-4 pb-0">
                                            <h5 class="text-dark mb-3">$12,345</h5>
                                            <a class="d-block h5 mb-2" href="">Golden Urban House For Sell</a>
                                            <p><i class="fa fa-map-marker-alt text-dark me-2"></i>123 Street, New York, USA</p>
                                        </div>
                                        <div class="d-flex border-top">
                                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-dark me-2"></i>1000 Sqft</small>
                                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-dark me-2"></i>3 Bed</small>
                                            <small class="flex-fill text-center py-2"><i class="fa fa-bath text-dark me-2"></i>2 Bath</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 text-center">
                                    <a class="btn btn-dark py-3 px-5" href="">ดูเพิ่มเติม</a>
                                </div>
                            </div>
                        </div>
                        <div id="tab-3" class="tab-pane fade show p-0">
                            <div class="row g-4">
                                <div class="col-lg-4 col-md-6">
                                    <div class="property-item rounded overflow-hidden">
                                        <div class="position-relative overflow-hidden">
                                            <a href=""><img class="img-fluid" src="../img/property-1.jpg" alt=""></a>
                                            <div class="bg-dark rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">For Sell</div>
                                            <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Appartment</div>
                                        </div>
                                        <div class="p-4 pb-0">
                                            <h5 class="text-dark mb-3">$12,345</h5>
                                            <a class="d-block h5 mb-2" href="">Golden Urban House For Sell</a>
                                            <p><i class="fa fa-map-marker-alt text-dark me-2"></i>123 Street, New York, USA</p>
                                        </div>
                                        <div class="d-flex border-top">
                                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-dark me-2"></i>1000 Sqft</small>
                                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-dark me-2"></i>3 Bed</small>
                                            <small class="flex-fill text-center py-2"><i class="fa fa-bath text-dark me-2"></i>2 Bath</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="property-item rounded overflow-hidden">
                                        <div class="position-relative overflow-hidden">
                                            <a href=""><img class="img-fluid" src="../img/property-2.jpg" alt=""></a>
                                            <div class="bg-dark rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">For Rent</div>
                                            <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Villa</div>
                                        </div>
                                        <div class="p-4 pb-0">
                                            <h5 class="text-dark mb-3">$12,345</h5>
                                            <a class="d-block h5 mb-2" href="">Golden Urban House For Sell</a>
                                            <p><i class="fa fa-map-marker-alt text-dark me-2"></i>123 Street, New York, USA</p>
                                        </div>
                                        <div class="d-flex border-top">
                                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-dark me-2"></i>1000 Sqft</small>
                                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-dark me-2"></i>3 Bed</small>
                                            <small class="flex-fill text-center py-2"><i class="fa fa-bath text-dark me-2"></i>2 Bath</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="property-item rounded overflow-hidden">
                                        <div class="position-relative overflow-hidden">
                                            <a href=""><img class="img-fluid" src="../img/property-3.jpg" alt=""></a>
                                            <div class="bg-dark rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">For Sell</div>
                                            <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Office</div>
                                        </div>
                                        <div class="p-4 pb-0">
                                            <h5 class="text-dark mb-3">$12,345</h5>
                                            <a class="d-block h5 mb-2" href="">Golden Urban House For Sell</a>
                                            <p><i class="fa fa-map-marker-alt text-dark me-2"></i>123 Street, New York, USA</p>
                                        </div>
                                        <div class="d-flex border-top">
                                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-dark me-2"></i>1000 Sqft</small>
                                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-dark me-2"></i>3 Bed</small>
                                            <small class="flex-fill text-center py-2"><i class="fa fa-bath text-dark me-2"></i>2 Bath</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="property-item rounded overflow-hidden">
                                        <div class="position-relative overflow-hidden">
                                            <a href=""><img class="img-fluid" src="../img/property-4.jpg" alt=""></a>
                                            <div class="bg-dark rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">For Rent</div>
                                            <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Building</div>
                                        </div>
                                        <div class="p-4 pb-0">
                                            <h5 class="text-dark mb-3">$12,345</h5>
                                            <a class="d-block h5 mb-2" href="">Golden Urban House For Sell</a>
                                            <p><i class="fa fa-map-marker-alt text-dark me-2"></i>123 Street, New York, USA</p>
                                        </div>
                                        <div class="d-flex border-top">
                                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-dark me-2"></i>1000 Sqft</small>
                                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-dark me-2"></i>3 Bed</small>
                                            <small class="flex-fill text-center py-2"><i class="fa fa-bath text-dark me-2"></i>2 Bath</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="property-item rounded overflow-hidden">
                                        <div class="position-relative overflow-hidden">
                                            <a href=""><img class="img-fluid" src="../img/property-5.jpg" alt=""></a>
                                            <div class="bg-dark rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">For Sell</div>
                                            <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Home</div>
                                        </div>
                                        <div class="p-4 pb-0">
                                            <h5 class="text-dark mb-3">$12,345</h5>
                                            <a class="d-block h5 mb-2" href="">Golden Urban House For Sell</a>
                                            <p><i class="fa fa-map-marker-alt text-dark me-2"></i>123 Street, New York, USA</p>
                                        </div>
                                        <div class="d-flex border-top">
                                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-dark me-2"></i>1000 Sqft</small>
                                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-dark me-2"></i>3 Bed</small>
                                            <small class="flex-fill text-center py-2"><i class="fa fa-bath text-dark me-2"></i>2 Bath</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <div class="property-item rounded overflow-hidden">
                                        <div class="position-relative overflow-hidden">
                                            <a href=""><img class="img-fluid" src="../img/property-6.jpg" alt=""></a>
                                            <div class="bg-dark rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">For Rent</div>
                                            <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Shop</div>
                                        </div>
                                        <div class="p-4 pb-0">
                                            <h5 class="text-dark mb-3">$12,345</h5>
                                            <a class="d-block h5 mb-2" href="">Golden Urban House For Sell</a>
                                            <p><i class="fa fa-map-marker-alt text-dark me-2"></i>123 Street, New York, USA</p>
                                        </div>
                                        <div class="d-flex border-top">
                                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-dark me-2"></i>1000 Sqft</small>
                                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-dark me-2"></i>3 Bed</small>
                                            <small class="flex-fill text-center py-2"><i class="fa fa-bath text-dark me-2"></i>2 Bath</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 text-center">
                                    <a class="btn btn-dark py-3 px-5" href="">ดูเพิ่มเติม</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Examples of work End -->

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white-50 footer wow fadeIn">
        <div class="copyright">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; <a class="border-bottom" href="#">2024 Photo Match</a>, All Right Reserved.
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="footer-menu">
                        <a href="index.php">หน้าหลัก</a>
                        <a href="">คุกกี้</a>
                        <a href="contact.php">ช่วยเหลือ</a>
                        <a href="">ถามตอบ</a>
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
    <script>
        // JavaScript สำหรับควบคุมสไลด์โชว์
        let slideIndex = 0;

        show
        showSlides();

        function showSlides() {


            let i;
            let slides = $(".slide");
            slides.hide();
            slideIndex++;

            slideIndex++;


            if (slideIndex > slides.length) {
                slideIndex = 1
            }
            slides.
            slides
            eq(slideIndex - 1).show();


            setTimeout(showSlides, 2000); // เปลี่ยนภาพทุกๆ 2 วินาที
        }

        function plusSlides(n) {

            showSlides
            showSlides(slideIndex += n);
        }
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
    <!-- post -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // เลือก Radio Buttons
            var postOptionRadios = document.querySelectorAll('input[name="postOption"]');
            // เลือกเนื้อหาของโพสต์
            var postContentDiv = document.getElementById('postContent');

            // แสดงเนื้อหาสำหรับโพสต์รูปเป็นค่าเริ่มต้น
            postContentDiv.innerHTML = `
            <div class="col-12  mt-3">
            <form class="upe-mutistep-form" method="post" id="Upemultistepsform" action="" enctype="multipart/form-data">
                                
                                   
                                        <div class="form-container">
                                            
                                                    <div>
                                                        <select class="form-select border-1 py-2" name="workPost" id="workPost">
                                                            <option required>เลือกประเภทงาน</option>
                                                            <?php
                                                            // ทำการเชื่อมต่อฐานข้อมูล ($conn) ก่อน query
                                                            $sql = "SELECT t.type_id, t.type_work, MAX(tow.photographer_id) AS photographer_id
                                                            FROM type t
                                                            INNER JOIN type_of_work tow ON t.type_id = tow.type_id
                                                            GROUP BY t.type_id, t.type_work;
                                                            ";
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
                                                </div>
                                            </div>
                                            <div class="post-input-container">
                                                <textarea name="caption" rows="8" required placeholder="วันนี้คุณถ่ายอะไร"></textarea>
                                            </div>
                                            <div class="post-image-preview" id="preview-containerT" style="display: flex; flex-wrap: wrap; gap: 10px;">
                                            </div>
                                            <div class="mt-2">
                                                <label class="form-label" for="imp_event"><strong>อัพโหลดภาพ (ไม่เกิน 10 ภาพ)</strong><br></label>
                                                <input class="form-control" required type="file" name="upload[]" multiple="multiple" id="fileUploadT"  accept="image/*">
                                                <progress id="progressBar" value="0" max="100" style="width:300px;display:none"></progress>
                                                <p id="loaded_n_total"></p>
                                            </div>
                                        </div>
                                    
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="submit_post_portfolio" class="btn text-white" style="background:#0F52BA; width: 100%;">โพสต์</button>
                                </div>
                            </form>
            </div>
            `;
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
            // เพิ่ม Event Listener สำหรับ Radio Buttons
            postOptionRadios.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    // ตรวจสอบสถานะของ Radio Buttons เมื่อมีการเปลี่ยนแปลง
                    if (this.id === 'postPhotoRadio' && this.checked) {
                        // ในกรณีที่โพสต์รูปถูกเลือก
                        // แสดงเนื้อหาสำหรับโพสต์รูป
                        postContentDiv.innerHTML = `
                        <div class="col-12  mt-3">
            <form class="upe-mutistep-form" method="post" id="Upemultistepsform" action="" enctype="multipart/form-data">
                                
                                   
                                        <div class="form-container">
                                            
                                                    <div>
                                                        <select class="form-select border-1 py-2" name="workPost" id="workPost">
                                                            <option required>เลือกประเภทงาน</option>
                                                            <?php
                                                            // ทำการเชื่อมต่อฐานข้อมูล ($conn) ก่อน query
                                                            $sql = "SELECT t.type_id, t.type_work, MAX(tow.photographer_id) AS photographer_id
                                                            FROM type t
                                                            INNER JOIN type_of_work tow ON t.type_id = tow.type_id
                                                            GROUP BY t.type_id, t.type_work;
                                                            ";
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
                                                </div>
                                            </div>
                                            <div class="post-input-container">
                                                <textarea name="caption" rows="8" required placeholder="วันนี้คุณถ่ายอะไร"></textarea>
                                            </div>
                                            <div class="post-image-preview" id="preview-containerT" style="display: flex; flex-wrap: wrap; gap: 10px;">
                                            </div>
                                            <div class="mt-2">
                                                <label class="form-label" for="imp_event"><strong>อัพโหลดภาพ (ไม่เกิน 10 ภาพ)</strong><br></label>
                                                <input class="form-control" required type="file" name="upload[]" multiple="multiple" id="fileUploadT" accept="image/*">
                                                <progress id="progressBar" value="0" max="100" style="width:300px;display:none"></progress>
                                                <p id="loaded_n_total"></p>
                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="submit_post_portfolio" class="btn text-white" style="background:#0F52BA; width: 100%;">โพสต์</button>
                                </div>
                            </form>
                            
            </div>
            `;
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
                    } else if (this.id === 'postTypeRadio' && this.checked) {
                        // ในกรณีที่โพสต์ประเภทงานถูกเลือก
                        // แสดงเนื้อหาสำหรับโพสต์ประเภทงาน
                        postContentDiv.innerHTML = `
                        <div class="col-12 mt-4">
                        <form class="upe-mutistep-form" method="post" id="Upemultistepsform" action="">
                                        <div class="row">
                                            <div class="col-4 mt-4">
                                                <span style="color: black;">ประเภทงานที่รับ</span>
                                            </div>
                                            <div class="col-8 mt-2">
                                                <select class="form-select border-1 py-2" name="type">
                                                    <option selected>เลือกประเภทงานที่รับ</option>
                                                    <?php
                                                    $sqlType = "SELECT t.*
                                                    FROM type t
                                                    LEFT JOIN type_of_work tow ON t.type_id = tow.type_id
                                                    WHERE tow.photographer_id IS NULL;
                                                    ";
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
                                <div class="mt-5">
                                    <button type="submit" name="submit_type_of_work" class="btn text-white" style="background:#0F52BA; width: 100%;">โพสต์</button>
                                </div>
                            </form>
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
    <!-- Template Javascript -->
    <script src="../js/main.js"></script>
</body>

</html>