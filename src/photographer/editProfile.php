<?php
session_start();
include '../config_db.php';

$sql = "SELECT * FROM `information`";
$resultInfo = $conn->query($sql);
$rowInfo = $resultInfo->fetch_assoc();

if (isset($_SESSION['photographer_login'])) {
    $email = $_SESSION['photographer_login'];
    $sql = "SELECT * FROM photographer WHERE photographer_email LIKE '$email'";
    $resultPhoto = $conn->query($sql);
    $rowPhoto = $resultPhoto->fetch_assoc();

    // echo $rowPhoto['photographer_name'];
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
                            <a href="profile.php" class="dropdown-item">โปรไฟล์</a>
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

    <div class="container-sm mt-3 bg-white" style="height: 100%; border-radius: 10px; border: 10px solid white;">
    <div class="mt-5 container-md ">
        <div class="text-center" style="font-size: 18px;"><b><i class="fas fa-file-alt"></i>&nbsp;&nbsp;รายละเอียดข้อมูลช่างภาพ คุณ <?php echo $row['photographer_name']; ?></b></div>
        <div class="mt-3 col-md-10 container-fluid ">
            <div class="row ">
                <div class="col-8">
                    <div class="text-start mt-1" style="font-size: 18px;"><b>ข้อมูลส่วนตัว</b></div>
                    <div class="col-12">
                        <div class="row mt-3">
                            <div class="col-2">
                                <label for="prefix" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px;font-size: 13px;"> คำนำหน้า</span>
                                </label>
                                <input type="text" name="prefix" class="form-control mt-1" value="<?php echo $row['photographer_prefix']; ?>" readonly>
                            </div>
                            <div class="col-5">
                                <label for="name" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px;font-size: 13px;">ชื่อ</span>
                                </label>
                                <input type="text" name="name" class="form-control mt-1" value="<?php echo $row['photographer_name']; ?>" readonly>
                            </div>
                            <div class="col-5">
                                <label for="surname" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; font-size: 13px;">นามสกุล</span>
                                </label>
                                <input type="text" name="surname" class="form-control" value="<?php echo $row['photographer_surname']; ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <label for="address" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px;font-size: 13px;">ที่อยู่</span>
                                    <span style="color: red;">*</span>
                                </label>
                                <input type="text" name="address" class="form-control mt-1" value="<?php echo $row['photographer_address']; ?>" readonly style="resize: none;">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="district" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px; font-size: 13px;">อำเภอ</span>
                                </label>
                                <input type="text" name="district" class="form-control mt-1" value="<?php echo $row['photographer_district']; ?>" readonly style="resize: none;">
                            </div>
                            <div class="col-md-4">
                                <label for="province" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px; font-size: 13px;">จังหวัด</span>
                                </label>
                                <input type="text" name="province" class="form-control mt-1" value="<?php echo $row['photographer_province']; ?>" readonly style="resize: none;">
                            </div>
                            <div class="col-md-4">
                                <label for="zipcode" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px; font-size: 13px;">ไปรษณีย์</span>
                                </label>
                                <input type="text" name="zipcode" class="form-control mt-1" value="<?php echo $row['photographer_zip_code']; ?>" readonly style="resize: none;">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="tell" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px; font-size: 13px;">เบอร์โทรศัพท์</span>
                                </label>
                                <input type="text" name="tell" class="form-control mt-1" value="<?php echo $row['photographer_tell']; ?>" readonly style="resize: none;">
                            </div>
                            <div class="col-md-4">
                                <label for="email" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px; font-size: 13px;">อีเมล</span>
                                </label>
                                <input type="text" name="email" class="form-control mt-1" value="<?php echo $row['photographer_email']; ?>" readonly style="resize: none;">
                            </div>
                            <div class="col-md-4">
                                <label for="password" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px; font-size: 13px;">รหัสผ่าน</span>
                                </label>
                                <input type="password" name="password" class="form-control mt-1" value="<?php echo $row['photographer_password']; ?>" readonly style="resize: none;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4 mt-5">
                    <div class="d-flex justify-content-center align-items-center md">
                        <div class="circle">
                            <img src="../img/profile/<?php echo $row['photographer_photo']; ?>" alt="Your Image">
                        </div>
                    </div>
                    <!-- <div class="align-items-center justify-content-center d-flex">
                        <div class="">
                            <div>
                                <label for="photo" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px; font-size: 13px;">รูปภาพโปรไฟล์</span>
                                    <span style="color: red;">*</span>
                                </label>
                            </div>
                            <input type="file" name="photo" name="profileImage" class="form-control">
                        </div>
                    </div> -->
                    <form method="post" action="managePhotographerDetails.php?id=<?php echo $id; ?>">
                        <div class="mt-1">
                            <label for="license" style="font-weight: bold; display: flex; align-items: center;">
                                <span style="color: black; margin-right: 5px;font-size: 13px;"> สิทธิ์การใช้งาน</span>
                            </label>
                            <select class="form-select border-1 mt-1" name="license">
                                <?php
                                if ($row['photographer_license'] == '0') {
                                ?>
                                    <option value="0">รออนุมัติสิทธิ์การใช้งาน</option>
                                    <option value="1">มีสิทธิ์การเข้าใช้งาน</option>
                                <?php
                                } else {
                                ?>
                                    <option value="1">มีสิทธิ์การเข้าใช้งาน</option>
                                    <option value="0">รออนุมัติสิทธิ์การใช้งาน</option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                </div>
                <hr class="mt-4">
                <div class="row justify-content-center">
                    <div class="col-md-5 mt-0">
                        <div class="text-start mt-1" style="font-size: 18px;"><b>ข้อมูลเกี่ยวกับงาน</b></div>
                        <div class="mt-3">
                            <label for="portfolio" style="font-weight: bold; display: flex; align-items: center;">
                                <span style="color: black; margin-right: 5px; font-size: 13px;">ไฟล์แฟ้มสะสมผลงาน</span>
                            </label>
                            <div class="input-group">
                                <input type="text" name="portfolio" class="form-control" value="<?php echo $row['photographer_portfolio']; ?>" readonly>
                                <a href="../portfolio/<?php echo $row['photographer_portfolio']; ?>" target="_blank" class="btn btn-primary">ดูไฟล์ PDF</a>
                            </div>
                        </div>
                        <!-- <div class="mt-2">
                            <label for="workid" style="font-weight: bold; display: flex; align-items: center;">
                                <span style="color: black; margin-right: 5px; font-size: 13px;">ประเภทงานที่รับ</span>
                            </label>
                            <input type="text" name="working" class="form-control mt-1" value="<?php echo $row['photographer_']; ?>" readonly style="resize: none;">
                        </div> -->
                        <!-- <div class="mt-2">
                            <label for="Price " style="font-weight: bold; display: flex; align-items: center;">
                                <span style="color: black; margin-right: 5px; font-size: 13px;">ช่วงราคาที่รับงาน</span>
                            </label>
                            <input type="password" name="Price " class="form-control mt-1" placeholder="กรุณากรอกช่วงราคาที่รับงาน" style="resize: none;">
                        </div> -->
                        <div class="mt-2">
                            <label for="scope" style="font-weight: bold; display: flex; align-items: center;">
                                <span style="color: black; margin-right: 5px; font-size: 13px;">ขอบเขตพื้นที่ที่รับงาน</span>
                            </label>
                            <input type="text" name="scope" class="form-control mt-1" value="<?php echo $row['photographer_scope']; ?>" readonly style="resize: none;">
                        </div>
                    </div>
                    <div class="col-md-2 mt-0 col-divider justify-content-center">
                    </div>
                    <div class="col-md-5 mt-0">
                        <div class="text-start mt-1" style="font-size: 18px;"><b>ข้อมูลรับชำระเงิน</b></div>
                        <div class="mt-3">
                            <label for="workid" style="font-weight: bold; display: flex; align-items: center;">
                                <span style="color: black; margin-right: 5px; font-size: 13px;">ชื่อธนาคาร</span>
                            </label>
                            <input type="text" name="bank" class="form-control mt-1" value="<?php echo $row['photographer_bank']; ?>" readonly style="resize: none;">
                        </div>
                        <div class="mt-2">
                            <label for="accountname" style="font-weight: bold; display: flex; align-items: center;">
                                <span style="color: black; margin-right: 5px; font-size: 13px;">ชื่อบัญชี</span>
                            </label>
                            <input type="text" name="accountname" class="form-control mt-1" value="<?php echo $row['photographer_account_name']; ?>" readonly style="resize: none;">
                        </div>
                        <div class="mt-2">
                            <label for="accountnumber" style="font-weight: bold; display: flex; align-items: center;">
                                <span style="color: black; margin-right: 5px; font-size: 13px;">เลขที่บัญชี</span>
                            </label>
                            <input type="text" name="accountnumber" class="form-control mt-1" value="<?php echo $row['photographer_account_number']; ?>" readonly style="resize: none;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center mt-5 mb-5">
                <div class="col-md-12 text-center">
                    <!-- ตำแหน่งสำหรับปุ่ม "ย้อนกลับ" -->
                    <button type="button" onclick="window.location.href='Profile.php'" class="btn btn-danger me-4" style="width: 150px; height:45px;">ย้อนกลับ</button>
                    <!-- <button onclick="window.location.href='manageCustomerEdite.php? id=<?php echo $row['photographer_id']; ?>'" class="btn btn-primary" style="width: 150px; height:45px;">แก้ไขสิทธิ์</button> -->
                    <!-- ตำแหน่งสำหรับปุ่ม "บันทึกการแก้ไข" -->
                    <button id="saveButton" class="btn btn-primary" style="width: 150px; height:45px;">บันทึกการแก้ไข</button>
                </div>
            </div>
            </form>
        </div>
    </div>

    </div>
    <!-- Profile Edit End -->

    <!-- Footer Start -->
    <footer class="footer">
        &copy; <a class="border-bottom text-dark" href="#">2024 Photo Match</a>, All Right Reserved.
    </footer>
    <!-- Footer End -->

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