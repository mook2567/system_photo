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


    <div class=" mb-5 mt-2" style="height: 100%">

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

    </div><!-- Footer Start -->
    <div class="container-fluid bg-dark text-white-50 footer mt-5 wow fadeIn">
        <div class="copyright">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; <a class="border-bottom" href="#">2024 Photo Match</a>, All Right Reserved.
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->
    </div>
    <!-- Examples of work End -->



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