<?php
session_start();
include '../config_db.php';
require_once '../popup.php';

$sql = "SELECT * FROM `customer` WHERE cus_license = '1' OR cus_license = '2'";
$result = $conn->query($sql);

// Fetching data from 'information' table
$sqlInformation = "SELECT * FROM `information`";
$resultInformation = $conn->query($sqlInformation);
$rowInformation = $resultInformation->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Handle cus license update if submitted
    if (isset($_POST['license']) && isset($_POST['cus_id'])) {
        $license = $_POST['license'];
        $cus_id = $_POST['cus_id'];

        $sql = "UPDATE `customer` SET cus_license = ? WHERE cus_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $license, $cus_id);

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


        .table th:nth-child(6),
        .table td:nth-child(6) {
            width: 600px;
            height: 50px;
            text-align: center;
            /* กำหนดความกว้างของคอลัมน์การจัดการให้เหมาะสม */
        }

        .table td:nth-child(1),
        .table td:nth-child(2),
        .table td:nth-child(3),
        .table td:nth-child(4),
        .table td:nth-child(5),
        .table tr:nth-child(1),
        .table tr:nth-child(2),
        .table tr:nth-child(3),
        .table tr:nth-child(4),
        .table tr:nth-child(5) {
            width: 300px;
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
        <a href="index.php" class="navbar-brand ms-5 d-flex align-items-center text-center">
            <img class="img-fluid" src="../img/logo/<?php echo isset($rowInformation['information_icon']) ? $rowInformation['information_icon'] : ''; ?>" style="height: 30px;">
        </a>
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon text-primary"></span>
        </button>
        <div class="collapse navbar-collapse me-5" id="navbarCollapse">
            <div class="navbar-nav ms-auto f">
                <a href="index.php" class="nav-item nav-link">หน้าหลัก</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle bg-dark active" data-bs-toggle="dropdown">ข้อมูลพื้นฐาน</a>
                    <div class="dropdown-menu rounded-0 m-0">
                        <!-- <a href="manage.php" class="dropdown-item ">ข้อมูลพื้นฐาน</a> -->
                        <a href="manageWeb.php" class="dropdown-item">ข้อมูลระบบ</a>
                        <a href="manageAdmin.php" class="dropdown-item">ข้อมูลผู้ดูแลระบบ</a>
                        <a href="manageCustomer.php" class="dropdown-item active">ข้อมูลลูกค้า</a>
                        <a href="managePhotographer.php" class="dropdown-item">ข้อมูลช่างภาพ</a>
                        <a href="manageType.php" class="dropdown-item">ข้อมูลประเภทงาน</a>
                    </div>
                </div>
                <a href="approvMember.php" class="nav-item nav-link ">อนุมัติสมาชิก</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle bg-dark" data-bs-toggle="dropdown">รายงาน</a>
                    <div class="dropdown-menu rounded-0 m-0">
                        <a href="reportUser.php" class="dropdown-item">รายงานข้อมูลผู้ใช้งานระบบ</a>
                        <a href="reportCustomer.php" class="dropdown-item ">รายงานข้อมูลลูกค้า</a>
                        <a href="reportPhotographer.php" class="dropdown-item">รายงานข้อมูลช่างภาพ</a>
                        <a href="reportType.php" class="dropdown-item">รายงานข้อมูลประเภทงาน</a>
                    </div>
                </div>
                <a href="../logout.php" class="nav-item nav-link">ออกจากระบบ</a>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->
    <div style="height: 100%;">
        <div class="footer-box text-center mt-5" style="font-size: 18px;"><b><i class="fa fa-user"></i>&nbsp;&nbsp;รายการข้อมูลลูกค้า</b></div>
        <!-- ช่องค้นหา -->
        <div class="container-sm d-flex justify-content-end col-7 mt-4">
            <div>
                <input type="text" id="searchInput" class="form-control col-3" placeholder="ค้นหาข้อมูลลูกค้า" onkeyup="searchTable()" style="margin-bottom: 15px;">
            </div>
        </div>
        <div class="container-sm mt-2 table-responsive col-7">
            <table class="table bg-white table-hover table-bordered-3" id="customerTable">
                <thead>
                    <tr>
                        <!-- <th scope="col" class="text-center">รหัส</th> -->
                        <th scope="col">ชื่อ</th>
                        <th scope="col">นามสกุล</th>
                        <th scope="col">เบอร์โทรศัพท์</th>
                        <th scope="col">อีเมล</th>
                        <th scope="col">สถานะ</th>
                        <th scope="col" class="text-center">ดำเนินการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                    ?>
                            <tr>
                                <!-- <th class="text-center" scope="row"><?php echo $row['cus_id']; ?></th> -->
                                <td><?php echo $row['cus_name']; ?></td>
                                <td><?php echo $row['cus_surname']; ?></td>
                                <td><?php echo $row['cus_tell']; ?></td>
                                <td><?php echo $row['cus_email']; ?></td>
                                <td><?php if ($row['cus_license'] == '1') {
                                        echo 'มีสิทธิ์การใช้งาน';
                                    } elseif ($row['cus_license'] == '2') {
                                        echo 'ไม่มีสิทธิ์การใช้งาน';
                                    } else {
                                        echo 'ไม่ทราบสิทธิ์การใช้งาน';
                                    } ?></td>
                                <td>
                                    <!-- <button type="button" class="btn btn-primary btn-sm" onclick="window.location.href='manageCustomerDetails.php? id=<?php echo $row['cus_id']; ?>'">ดูเพิ่มเติม</button> -->
                                    <!-- <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detaileModal<?php echo $row['cus_id']; ?>">ดูเพิ่มเติม</button> -->
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['cus_id']; ?>">กำหนดสิทธิ์</button>
                                </td>
                            </tr>
                            <!-- JavaScript สำหรับการค้นหาในตาราง -->
                            <script>
                                function searchTable() {
                                    // ประกาศตัวแปร
                                    var input, filter, table, tr, td, i, txtValue;
                                    input = document.getElementById("searchInput");
                                    filter = input.value.toLowerCase(); // เปลี่ยนค่าเป็นพิมพ์เล็ก
                                    table = document.getElementById("customerTable");
                                    tr = table.getElementsByTagName("tr");

                                    // วนลูปผ่านแถวทั้งหมดของตาราง
                                    for (i = 1; i < tr.length; i++) { // เริ่มจาก 1 เพราะแถวแรกเป็นหัวตาราง
                                        tr[i].style.display = "none"; // ซ่อนแถวทั้งหมดก่อน

                                        // วนลูปผ่านแต่ละคอลัมน์ของแถว
                                        td = tr[i].getElementsByTagName("td");
                                        for (var j = 0; j < td.length; j++) {
                                            if (td[j]) {
                                                txtValue = td[j].textContent || td[j].innerText;
                                                if (txtValue.toLowerCase().indexOf(filter) > -1) { // เปลี่ยนค่าของ txtValue เป็นพิมพ์เล็ก
                                                    tr[i].style.display = ""; // แสดงแถวที่ตรงกับคำค้นหา
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                            </script>
                            <!-- Detail Modal -->
                            <div class="modal fade" id="detaileModal<?php echo $row['cus_id']; ?>" tabindex="-1" aria-labelledby="detaileModalLabel<?php echo $row['cus_id']; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="detaileModalLabel<?php echo $row['cus_id']; ?>"><b><i class="fas fa-file-alt"></i>&nbsp;รายละเอียดข้อมูลลูกค้า คุณ <?php echo $row['cus_name']; ?></b></h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mt-1 container-md ">
                                                <div class="mt-1 col-md-12 container-fluid ">
                                                    <div class="mt-1 container-md">
                                                        <div class="mt-3 col-md-10 container-fluid">
                                                            <div class="row">
                                                                <div class="col-8">
                                                                    <div class="col-12">
                                                                        <div class="row mt-2">
                                                                            <div class="col-2">
                                                                                <label for="prefix" style="font-weight: bold; display: flex; align-items: center;">
                                                                                    <span style="color: black; margin-right: 5px;font-size: 13px;"> คำนำหน้า</span>
                                                                                </label>
                                                                                <input type="text" name="prefix" class="form-control mt-1" value="<?php echo $row['cus_prefix']; ?>" readonly>
                                                                            </div>
                                                                            <div class="col-5">
                                                                                <label for="name" style="font-weight: bold; display: flex; align-items: center;">
                                                                                    <span style="color: black; margin-right: 5px;font-size: 13px;">ชื่อ</span>
                                                                                </label>
                                                                                <input type="text" name="name" class="form-control mt-1" value="<?php echo $row['cus_name']; ?>" readonly>
                                                                            </div>
                                                                            <div class="col-5">
                                                                                <label for="surname" style="font-weight: bold; display: flex; align-items: center;">
                                                                                    <span style="color: black; font-size: 13px;">นามสกุล</span>
                                                                                </label>
                                                                                <input type="text" name="surname" class="form-control mt-1" value="<?php echo $row['cus_surname']; ?>" readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 mt-2">
                                                                        <div class="row">
                                                                            <div class="col-md-12 text-center">
                                                                                <label for="address" style="font-weight: bold; display: flex; align-items: center;">
                                                                                    <span style="color: black; margin-right: 5px;font-size: 13px;">ที่อยู่</span>
                                                                                </label>
                                                                                <input type="text" name="address" class="form-control mt-1" value="<?php echo $row['cus_address']; ?>" readonly style="resize: none;">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 mt-2">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                <label for="district" style="font-weight: bold; display: flex; align-items: center;">
                                                                                    <span style="color: black; margin-right: 5px; font-size: 13px;">อำเภอ</span>
                                                                                </label>
                                                                                <input type="text" name="district" class="form-control mt-1" value="<?php echo $row['cus_district']; ?>" readonly style="resize: none;">
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <label for="province" style="font-weight: bold; display: flex; align-items: center;">
                                                                                    <span style="color: black; margin-right: 5px; font-size: 13px;">จังหวัด</span>
                                                                                </label>
                                                                                <input type="text" name="province" class="form-control mt-1" value="<?php echo $row['cus_province']; ?>" readonly style="resize: none;">
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <label for="zipcode" style="font-weight: bold; display: flex; align-items: center;">
                                                                                    <span style="color: black; margin-right: 5px; font-size: 13px;">ไปรษณีย์</span>
                                                                                </label>
                                                                                <input type="text" name="zipcode" class="form-control mt-1" value="<?php echo $row['cus_zip_code']; ?>" readonly style="resize: none;">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 mt-2">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                <label for="tell" style="font-weight: bold; display: flex; align-items: center;">
                                                                                    <span style="color: black; margin-right: 5px; font-size: 13px;">เบอร์โทรศัพท์</span>
                                                                                </label>
                                                                                <input type="text" name="tell" class="form-control mt-1" value="<?php echo $row['cus_tell']; ?>" readonly style="resize: none;">
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <label for="email" style="font-weight: bold; display: flex; align-items: center;">
                                                                                    <span style="color: black; margin-right: 5px; font-size: 13px;">อีเมล</span>
                                                                                </label>
                                                                                <input type="text" name="email" class="form-control mt-1" value="<?php echo $row['cus_email']; ?>" readonly style="resize: none;">
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <label for="password" style="font-weight: bold; display: flex; align-items: center;">
                                                                                    <span style="color: black; margin-right: 5px; font-size: 13px;">รหัสผ่าน</span>
                                                                                </label>
                                                                                <input type="password" name="password" class="form-control mt-1" value="<?php echo $row['cus_password']; ?>" readonly style="resize: none;">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-4 mt-5">
                                                                    <div class="d-flex justify-content-center align-items-center md mt-2">
                                                                        <div class="circle">
                                                                            <img id="userImage" src="../img/profile/<?php echo $row['cus_photo'] ? $row['cus_photo'] : 'null.png'; ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="mt-2">
                                                                        <label for="license" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">สิทธิ์การใช้งาน</span>
                                                                        </label>
                                                                        <input type="text" name="license" class="form-control mt-1" value="<?php echo ($row['cus_license'] == '1') ? 'มีสิทธิ์การเข้าใช้งาน' : 'รออนุมัติสิทธิ์การใช้งาน'; ?>" readonly style="resize: none;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="button" class="btn" style="background-color:gray; color:#fff; width: 150px; height:45px;" data-bs-dismiss="modal">ปิด</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="editModal<?php echo $row['cus_id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $row['cus_id']; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel<?php echo $row['cus_id']; ?>"><b><i class="fas fa-file-alt"></i>&nbsp; กำหนดสิทธิ์การใช้งานลูกค้า คุณ <?php echo $row['cus_name']; ?></b></h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="container-md">
                                                <div class="mt-3 col-md-10 container-fluid">
                                                    <div class="row">
                                                        <div class="col-8">
                                                            <div class="col-12">
                                                                <div class="row mt-2">
                                                                    <div class="col-2">
                                                                        <label for="prefix" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;"> คำนำหน้า</span>
                                                                        </label>
                                                                        <input type="text" name="prefix" class="form-control mt-1" value="<?php echo $row['cus_prefix']; ?>" readonly>
                                                                    </div>
                                                                    <div class="col-5">
                                                                        <label for="name" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">ชื่อ</span>
                                                                        </label>
                                                                        <input type="text" name="name" class="form-control mt-1" value="<?php echo $row['cus_name']; ?>" readonly>
                                                                    </div>
                                                                    <div class="col-5">
                                                                        <label for="surname" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; font-size: 13px;">นามสกุล</span>
                                                                        </label>
                                                                        <input type="text" name="surname" class="form-control mt-1" value="<?php echo $row['cus_surname']; ?>" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 mt-2">
                                                                <div class="row">
                                                                    <div class="col-md-12 text-center">
                                                                        <label for="address" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">ที่อยู่</span>
                                                                        </label>
                                                                        <input type="text" name="address" class="form-control mt-1" value="<?php echo $row['cus_address']; ?>" style="resize: none;" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mt-2">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <label for="district" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px; font-size: 13px;">อำเภอ</span>
                                                                        </label>
                                                                        <input type="text" name="district" class="form-control mt-1" value="<?php echo $row['cus_district']; ?>" style="resize: none;" readonly>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label for="province" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px; font-size: 13px;">จังหวัด</span>
                                                                        </label>
                                                                        <input type="text" name="province" class="form-control mt-1" value="<?php echo $row['cus_province']; ?>" style="resize: none;" readonly>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label for="zipcode" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px; font-size: 13px;">ไปรษณีย์</span>
                                                                        </label>
                                                                        <input type="text" name="zipcode" class="form-control mt-1" value="<?php echo $row['cus_zip_code']; ?>" style="resize: none;" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mt-2">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <label for="phone" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px; font-size: 13px;">เบอร์โทรศัพท์</span>
                                                                        </label>
                                                                        <input type="text" name="phone" class="form-control mt-1" value="<?php echo $row['cus_tell']; ?>" style="resize: none;" readonly>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label for="email" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px; font-size: 13px;">อีเมล</span>
                                                                        </label>
                                                                        <input type="text" name="email" class="form-control mt-1" value="<?php echo $row['cus_email']; ?>" style="resize: none;" readonly>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label for="password" style="font-weight: bold; display: flex; align-items: center;">
                                                                            <span style="color: black; margin-right: 5px; font-size: 13px;">รหัสผ่าน</span>
                                                                        </label>
                                                                        <input type="password" name="password" class="form-control mt-1" value="<?php echo $row['cus_password']; ?>" style="resize: none;" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="col-4 mt-5">
                                                            <div class="d-flex justify-content-center align-items-center md mt-2">
                                                                <div class="circle">
                                                                    <img id="userImage" src="../img/profile/<?php echo $row['cus_photo'] ? $row['cus_photo'] : 'null.png'; ?>">
                                                                </div>
                                                            </div>
                                                            <form method="post" action="">
                                                                <div class="mt-1">
                                                                    <label for="license" style="font-weight: bold; display: flex; align-items: center;">
                                                                        <span style="color: black; margin-right: 5px;font-size: 13px;"> สิทธิ์การใช้งาน</span>
                                                                    </label>
                                                                    <select class="form-select border-1 mt-1" name="license">
                                                                        <?php
                                                                        if ($row['cus_license'] == '1') {
                                                                        ?>
                                                                            <!-- <option value="0">รออนุมัติสิทธิ์การใช้งาน</option> -->
                                                                            <option selected value="1">มีสิทธิ์การเข้าใช้งาน</option>
                                                                            <option value="2">ไม่มีสิทธิ์การเข้าใช้งาน</option>
                                                                        <?php
                                                                        } else {
                                                                        ?>
                                                                            <option value="1">มีสิทธิ์การเข้าใช้งาน</option>
                                                                            <!-- <option value="0">รออนุมัติสิทธิ์การใช้งาน</option> -->
                                                                            <option selected value="2">ไม่มีสิทธิ์การเข้าใช้งาน</option>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                <input type="hidden" name="cus_id" value="<?php echo $row['cus_id']; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="button" class="btn" data-bs-dismiss="modal" style="background-color:gray; color:white; width: 150px; height:45px;">ปิด</button>
                                            <!-- ตำแหน่งสำหรับปุ่ม "บันทึกการแก้ไข" -->
                                            <button type="submit" name="submit" class="btn btn-primary" style="width: 150px; height:45px;">บันทึกการแก้ไข</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='6'>ไม่พบข้อมูลผู้ดูแลระบบ</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="row justify-content-center mt-4 text-center">
            <div class="col-md-12 mt-4 ">
                <!-- ตำแหน่งสำหรับปุ่ม "ย้อนกลับ" -->
                <button onclick="window.history.back();" class="btn me-4" style="color:#fff; background-color: gray; width: 150px; height:45px;">ย้อนกลับ</button>
            </div>
        </div>
    </div>
    <!-- Footer Start -->
    <div class="container-fluid text-white-50 footer" data-wow-delay="0.1s">
        <div class="copyright">
            <div class="row">
                <div class="col-md-6 text-dark text-center text-md-start mb-3 mb-md-0">
                    &copy; <a class="border-bottom text-dark" href="#">2024 Photo Match</a>, All Right Reserved.
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