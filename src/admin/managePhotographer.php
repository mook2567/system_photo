<?php
session_start();
include '../config_db.php';
require_once '../popup.php';

$sql = "SELECT * FROM `photographer` WHERE photographer_license = '1'";
$result = $conn->query($sql);

// Fetching data from 'information' table
$sqlInformation = "SELECT * FROM `information`";
$resultInformation = $conn->query($sqlInformation);
$rowInformation = $resultInformation->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Handle photographer license update if submitted
    if (isset($_POST['license']) && isset($_POST['photographer_id'])) {
        $license = $_POST['license'];
        $photographer_id = $_POST['photographer_id'];

        $sql = "UPDATE `photographer` SET photographer_license = ? WHERE photographer_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $license, $photographer_id);

        if ($stmt->execute()) {
?>
            <script>
                setTimeout(function() {
                    Swal.fire({
                        title: '<div class="t1">บันทึกสิทธิ์การใช้งานสำเร็จ</div>',
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
                        title: '<div class="t1">เกิดข้อผิดพลาดในการบันทึกสิทธิ์การใช้งาน</div>',
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
            background: #fff;
            overflow-x: hidden;
        }

        .f {
            font-family: 'Athiti', sans-serif;
        }

        .circle {
            width: 150px;
            height: 150px;
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


        .table th:nth-child(5),
        .table td:nth-child(5) {
            width: 500px;
            height: 50px;
            text-align: center;
            /* กำหนดความกว้างของคอลัมน์การจัดการให้เหมาะสม */
        }

        .table th:nth-child(1),
        .table th:nth-child(2),
        .table th:nth-child(3),
        .table th:nth-child(4),
        .table td:nth-child(1),
        .table td:nth-child(2),
        .table td:nth-child(3),
        .table td:nth-child(4) {
            width: 200px;
            height: 50px;
            /* กำหนดความกว้างของคอลัมน์การจัดการให้เหมาะสม */
        }

        .table .btn {
            width: 100px;
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
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-0 px-4" style="height: 70px;">
        <a href="index.html" class="navbar-brand d-flex align-items-center text-center">
            <img class="img-fluid" src="../img/logo/<?php echo isset($rowInformation['information_icon']) ? $rowInformation['information_icon'] : ''; ?>" style="height: 30px;">
        </a>
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon text-primary"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto f">
                <a href="index.php" class="nav-item nav-link ">หน้าหลัก</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle bg-dark active" data-bs-toggle="dropdown">ข้อมูลพื้นฐาน</a>
                    <div class="dropdown-menu rounded-0 m-0">
                        <a href="manage.php" class="dropdown-item ">ข้อมูลพื้นฐาน</a>
                        <a href="manageWeb.php" class="dropdown-item">ข้อมูลระบบ</a>
                        <a href="manageAdmin.php" class="dropdown-item">ข้อมูลผู้ดูแลระบบ</a>
                        <a href="manageCustomer.php" class="dropdown-item">ข้อมูลลูกค้า</a>
                        <a href="managePhotographer.php" class="dropdown-item active">ข้อมูลช่างภาพ</a>
                        <a href="manageType.php" class="dropdown-item">ข้อมูลประเภทงาน</a>
                    </div>
                </div>
                <a href="approvMember.php" class="nav-item nav-link ">อนุมัติสมาชิก</a>
                <!-- <a href="report.php" class="nav-item nav-link ">รายงาน</a> -->
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle bg-dark" data-bs-toggle="dropdown">โปรไฟล์</a>
                    <div class="dropdown-menu rounded-0 m-0">
                        <!-- <a href="profile.php" class="dropdown-item">โปรไฟล์</a> -->

                        <a href="../index.php" class="dropdown-item">ออกจากระบบ</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->
    <div style="height: 100%;">
        <div class="footer-box text-center mt-5" style="font-size: 18px;"><b><i class="fa fa-camera"></i>&nbsp;&nbsp;รายการข้อมูลช่างภาพ</b></div>
        <div class="container-sm mt-2 table-responsive col-7">
            <table class="table bg-white table-hover table-bordered-3">
                <thead>
                    <tr>
                        <!-- <th scope="col">รหัส</th> -->
                        <th scope="col">ชื่อ</th>
                        <th scope="col">นามสกุล</th>
                        <th scope="col">เบอร์โทรศัพท์</th>
                        <th scope="col">อีเมล</th>
                        <th scope="col">ดำเนินการ</th>
                    </tr>
                </thead>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                ?>
                        <tr>
                            <!-- <th scope="row"><?php echo $row['photographer_id']; ?></th> -->
                            <td><?php echo $row['photographer_name']; ?></td>
                            <td><?php echo $row['photographer_surname']; ?></td>
                            <td><?php echo $row['photographer_tell']; ?></td>
                            <td><?php echo $row['photographer_email']; ?></td>
                            <td>
                                <!-- <button type="button" class="btn btn-primary btn-sm" onclick="window.location.href='managePhotographerDetails.php? id=<?php echo $row['photographer_id']; ?>'">ดูเพิ่มเติม</button> -->
                                <!-- <button type="button" class="btn btn-warning btn-sm" onclick="window.location.href='managePhotographerEdite.php? id=<?php echo $row['photographer_id']; ?>'">แก้ไข</button> -->
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detaileModal<?php echo $row['photographer_id']; ?>">ดูเพิ่มเติม</button>
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['photographer_id']; ?>">แก้ไข</button>
                            </td>
                        </tr>
                        <!-- Detaile Modal -->
                        <div class="modal fade" id="detaileModal<?php echo $row['photographer_id']; ?>" tabindex="-1" aria-labelledby="detaileModalLabel<?php echo $row['photographer_id']; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="detailModalLabel<?php echo $row['photographer_id']; ?>"><b><i class="fas fa-clipboard-list"></i>&nbsp;ข้อมูลผู้ดูแลระบบ คุณ <?php echo $row['photographer_name']; ?></b></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container-md">
                                            <div class="text-center" style="font-size: 18px;"><b><i class="fas fa-file-alt"></i>&nbsp;&nbsp;รายละเอียดข้อมูลช่างภาพ คุณ <?php echo $row['photographer_name']; ?></b></div>
                                            <div class="mt-3 col-md-12 container-fluid">
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
                                                        <div class="d-flex justify-content-center align-items-center md mt-2">
                                                            <div class="circle">
                                                            <img id="userImage" src="../img/profile/<?php echo $rowCustomer['cus_photo'] ? $rowCustomer['cus_photo'] : 'null.png'; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="mt-2">
                                                            <label for="license" style="font-weight: bold; display: flex; align-items: center;">
                                                                <span style="color: black; margin-right: 5px;font-size: 13px;">สิทธิ์การใช้งาน</span>
                                                            </label>
                                                            <input type="text" name="license" class="form-control mt-1" value="<?php echo ($row['photographer_license'] == '1') ? 'มีสิทธิ์การเข้าใช้งาน' : 'รออนุมัติสิทธิ์การใช้งาน'; ?>" readonly style="resize: none;">
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
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer mt-2 justify-content-center">
                                        <button type="button" class="btn btn-danger" style="width: 150px; height:45px;" data-bs-dismiss="modal">ปิด</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal<?php echo $row['photographer_id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $row['photographer_id']; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel<?php echo $row['photographer_id']; ?>"><b><i class="fas fa-clipboard-list"></i>&nbsp;ข้อมูลผู้ดูแลระบบ คุณ <?php echo $row['photographer_name']; ?></b></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container-md">
                                            <div class="text-center" style="font-size: 18px;"><b><i class="fas fa-file-alt"></i>&nbsp;&nbsp;รายละเอียดข้อมูลช่างภาพ คุณ <?php echo $row['photographer_name']; ?></b></div>
                                            <div class="mt-3 col-md-12 container-fluid">
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
                                                            <img id="userImage" src="../img/profile/<?php echo !empty($rowPhotographer['photographer_photo']) ? $rowPhotographer['photographer_photo'] : 'null.png'; ?>">
                                                            </div>
                                                        </div>
                                                        <form method="post" action="">
                                                            <div class="mt-1">
                                                                <label for="license" style="font-weight: bold; display: flex; align-items: center;">
                                                                    <span style="color: black; margin-right: 5px;font-size: 13px;"> สิทธิ์การใช้งาน</span>
                                                                </label>
                                                                <select class="form-select border-1 mt-1" name="license">
                                                                    <?php
                                                                    if ($rowAdmin['admin_license'] == '0') {
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
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="photographer_id" value="<?php echo $row['photographer_id']; ?>">
                                    <div class="modal-footer mt-2 justify-content-center">
                                        <button type="button" class="btn btn-danger" style="width: 150px; height:45px;" data-bs-dismiss="modal">ปิด</button>
                                        <button type="submit" name="submit" class="btn btn-primary" style="width: 150px; height:45px;">บันทึกการแก้ไข</button>
                                    </div>
                                    </form>
                                </div>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='6'>ไม่พบข้อมูลช่างภาพ</td></tr>";
                }
                        ?>
                        </tbody>
            </table>
        </div>
        <div class="row justify-content-center mt-3 container-center text-center">
            <div class="col-md-12">
                <!-- ตำแหน่งสำหรับปุ่ม "ย้อนกลับ" -->
                <button onclick="window.history.back();" class="btn btn-danger me-4" style="width: 150px; height:45px;">ย้อนกลับ</button>
            </div>
        </div>
    </div>
    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white-50 footer" data-wow-delay="0.1s">
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
</body>

</html>