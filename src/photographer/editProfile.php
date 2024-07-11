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
            padding-left: 55px;
            padding-top: 20px;
        }

        .post-input-container textarea {
            width: 100%;
            border: 0;
            outline: 0;
            border-bottom: 1px solid #ccc;
            resize: none;
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
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a href="index.php">
                <img class="img-fluid" src="../img/logo/<?php echo isset($rowInfo['information_icon']) ? $rowInfo['information_icon'] : ''; ?>" style="height: 30px;">
            </a>
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
                            <a href="profile.php" class="dropdown-item">โปรไฟล์</a>
                            <a href="editProfile.php" class="dropdown-item active">แก้ไขข้อมูลส่วนตัว</a>
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

    <!-- Profile Edit Start -->
    <!-- <div class="container-sm mt-3" style="height: 100%;">
        <div class="col-12 d-flex justify-content-between">
            <button onclick="window.history.back();" class="btn btn-danger">ย้อนกลับ</button>
            <button type="button" onclick="window.location.href='bookingListAll.php'" class="btn btn-primary">บันทึก</button>
        </div>
    </div> -->

    <div>
        <div class="container-xxl card-body bg-white mt-4" style="min-height: 800px;border-radius: 10px; box-shadow: 0 2px 2px rgba(0, 0, 0, 0.2);">

        </div>
    </div>

    <!-- Profile Edit End -->


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

</body>

</html>