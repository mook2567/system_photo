<?php
session_start();
include '../config_db.php';
require_once '../popup.php';

$sql = "SELECT * FROM `information`";
$resultInfo = $conn->query($sql);
$rowInfo = $resultInfo->fetch_assoc();

if (isset($_SESSION['cus_login'])) {
    $email = $_SESSION['cus_login'];
    $sql = "SELECT * FROM customer WHERE cus_email LIKE '$email'";
    $resultCus = $conn->query($sql);
    $rowCus = $resultCus->fetch_assoc();
    $id_cus = $rowCus['cus_id'];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['submit_photographer'])) {
        $photographer_id = $_POST["photographer_id"];
        $address = $_POST["address"];
        $district = $_POST["district"];
        $province = $_POST["province"];
        $zipcode = $_POST["zipcode"];
        $password = $_POST["password"];
        $bank = isset($_POST["bank"]) ? $_POST["bank"] : "";
        $accountNumber = $_POST["accountNumber"];
        $accountName = $_POST["accountName"];

        $sql = "UPDATE photographer SET 
            photographer_address = ?, 
            photographer_district = ?, 
            photographer_province = ?, 
            photographer_zip_code = ?, 
            photographer_password = ?, 
            photographer_bank = ?, 
            photographer_account_name = ?, 
            photographer_account_number = ? 
            WHERE photographer_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssi", $address, $district, $province, $zipcode, $password, $bank, $accountName, $accountNumber, $photographer_id);

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
            <a href="index.php" class="navbar-brand d-flex align-items-center text-center" style="height: 70px;">
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
                    <a href="report.php" class="nav-item nav-link">รายงาน</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle bg-dark active" data-bs-toggle="dropdown">โปรไฟล์</a>
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

    <!-- Profile Edit Start -->
    <div>
        <div class="container-xxl card-body bg-white mt-4" style="min-height: 800px;border-radius: 10px; box-shadow: 0 2px 2px rgba(0, 0, 0, 0.2);">
            <center>
                <h2 class="mt-2"><i class="fa-solid fa-pencil"></i> แก้ไขข้อมูลเกี่ยวกับคุณ</h2>
            </center>
            <form class="upe-mutistep-form" method="post" id="Upemultistepsform" action="">
                <div class="modal-body">
                    <div class="container-xxl">
                        <div class="mt-3 col-md-12 container-fluid">
                            <div class="row ">
                                <div class="col-12">
                                    <div class="text-start mt-1" style="font-size: 18px;"><b>ข้อมูลส่วนตัว</b></div>
                                    <div class="col-12">
                                        <div class="row mt-3">
                                            <div class="col-2">
                                                <label for="prefix" style="font-weight: bold; display: flex; align-items: center;">
                                                    <span style="color: black; margin-right: 5px;font-size: 13px;"> คำนำหน้า</span>
                                                </label>
                                                <input type="text" name="prefix" class="form-control mt-2" value="<?php echo $rowCus['cus_prefix']; ?>" readonly>
                                            </div>
                                            <div class="col-5">
                                                <label for="name" style="font-weight: bold; display: flex; align-items: center;">
                                                    <span style="color: black; margin-right: 5px;font-size: 13px;">ชื่อ</span>
                                                    <span style="color: red;">*</span>
                                                </label>
                                                <input type="text" name="name" class="form-control mt-1" value="<?php echo $rowCus['cus_name']; ?>" required>
                                            </div>
                                            <div class="col-5">
                                                <label for="surname" style="font-weight: bold; display: flex; align-items: center;">
                                                    <span style="color: black; margin-right: 5px; font-size: 13px;">นามสกุล</span>
                                                    <span style="color: red;">*</span>
                                                </label>
                                                <input type="text" name="surname" class="form-control" value="<?php echo $rowCus['cus_surname']; ?>" required>
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
                                                <input type="text" name="address" class="form-control mt-1" value="<?php echo $rowCus['cus_address']; ?>" required style="resize: none;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="district" style="font-weight: bold; display: flex; align-items: center;">
                                                    <span style="color: black; margin-right: 5px; font-size: 13px;">อำเภอ</span>
                                                    <span style="color: red;">*</span>
                                                </label>
                                                <input type="text" name="district" class="form-control mt-1" value="<?php echo $rowCus['cus_district']; ?>" required style="resize: none;">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="province" style="font-weight: bold; display: flex; align-items: center;">
                                                    <span style="color: black; margin-right: 5px; font-size: 13px;">จังหวัด</span>
                                                    <span style="color: red;">*</span>
                                                </label>
                                                <input type="text" name="province" class="form-control mt-1" value="<?php echo $rowCus['cus_province']; ?>" required style="resize: none;">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="zipcode" style="font-weight: bold; display: flex; align-items: center;">
                                                    <span style="color: black; margin-right: 5px; font-size: 13px;">ไปรษณีย์</span>
                                                    <span style="color: red;">*</span>
                                                </label>
                                                <input type="text" name="zipcode" class="form-control mt-1" value="<?php echo $rowCus['cus_zip_code']; ?>" required style="resize: none;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="password" style="font-weight: bold; display: flex; align-items: center;">
                                                    <span style="color: black; margin-right: 5px; font-size: 13px;">รหัสผ่าน</span>
                                                    <span style="color: red;">*</span>
                                                    <span style="color: red;font-size: 13px;">(ต้องกรอกไม่น้อยกว่า 5 ตัว)</span>
                                                </label>
                                                <div class="input-group">
                                                    <input type="password" name="password" minlength="5" id="password" class="form-control mt-1" value="<?php echo $rowcCs['cus_password']; ?>" required style="resize: none;">
                                                    <button type="button" style="color: #fff; width: 60px; background-color: #555555; border: none;" id="togglePassword">
                                                        <svg xmlns="http://www.w3.org/2000/svg" id="eye-icon" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="confirm_password" style="font-weight: bold; display: flex; align-items: center;">
                                                    <span style="color: black; margin-right: 5px; font-size: 13px;">ยืนยันรหัสผ่าน</span>
                                                    <span style="color: red;">*</span>
                                                </label>
                                                <input type="password" minlength="5" name="confirm_password" id="confirm_password" class="form-control mt-1" onchange="validatePassword()" placeholder="กรุณายืนยันรหัสผ่าน" value="<?php echo $rowCus['cus_password']; ?>" required style="resize: none;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="cuc_id" value="<?php echo $rowCus['cuc_id']; ?>">
                <div class="modal-footer mt-2 justify-content-center">
                    <button type="button" onclick="window.location.href='profile.php'" class="btn btn-danger" style="width: 150px; height:45px;" data-bs-dismiss="modal">ย้อนกลับ</button>
                    <button type="submit" name="submit_cus" class="btn btn-primary" style="width: 150px; height:45px;">บันทึกการแก้ไข</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Profile Edit End -->

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