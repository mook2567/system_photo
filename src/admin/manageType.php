<?php
session_start();
include '../config_db.php';
require_once '../popup.php';
// Fetching data from 'information' table
$sqlInformation = "SELECT * FROM `information`";
$resultInformation = $conn->query($sqlInformation);

if ($resultInformation->num_rows > 0) {
    $rowInformation = $resultInformation->fetch_assoc();
} else {
    $rowInformation = ['information_icon' => ''];
}
// Handle form submission to insert new type_work
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type_work = $_POST['type_work'];

    if (isset($_POST['submit_add'])) {
        if (isset($_FILES["iconImage"])) {
            $image_file = $_FILES['iconImage']['name'];
            $new_name = date("d_m_Y_H_i_s") . '-' . $image_file;
            $type = $_FILES['iconImage']['type'];
            $size = $_FILES['iconImage']['size'];
            $temp = $_FILES['iconImage']['tmp_name'];

            $path = "../img/icon/" . $new_name;
            $directory = "../img/icon/";

            // Allowed file types
            $allowed_types = array('image/jpg', 'image/jpeg', 'image/png', 'image/gif');

            // Validate file type
            if (in_array($type, $allowed_types)) {
                // Validate file size (less than 5MB)
                if ($size < 5000000) {
                    // Check if file already exists
                    if (!file_exists($path)) {
                        // Upload file
                        if (move_uploaded_file($temp, $path)) {
                            // Insert into database
                            // Assuming $conn is your database connection

                            // Prepare SQL statement with parameterized query
                            $stmt = $conn->prepare("INSERT INTO `type` (`type_id`, `type_work`, `type_icon`) VALUES (NULL, ?, ?)");
                            $stmt->bind_param("ss", $type_work, $new_name); // Assuming type_icon is the column name in your database for storing the file name
                            if ($stmt->execute()) {
?>
                                <script>
                                    setTimeout(function() {
                                        Swal.fire({
                                            title: '<div class="t1">บันทึกสำเร็จ</div>',
                                            icon: 'success',
                                            confirmButtonText: 'ตกลง',
                                            allowOutsideClick: true,
                                            allowEscapeKey: true,
                                            allowEnterKey: false
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location.href = "manageType.php";
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
                                            title: '<div class="t1">เกิดข้อผิดพลาดในการบันทึก</div>',
                                            icon: 'error',
                                            confirmButtonText: 'ออก',
                                            allowOutsideClick: true,
                                            allowEscapeKey: true,
                                            allowEnterKey: false
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location.href = "manageType.php";
                                            }
                                        });
                                    });
                                </script>
                            <?php
                            }
                            $stmt->close();
                        } else {
                            echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์";
                        }
                    } else {
                        echo "ไฟล์นี้มีอยู่แล้ว... กรุณาตรวจสอบโฟลเดอร์การอัปโหลด";
                    }
                } else {
                    echo "ไฟล์ของคุณมีขนาดใหญ่เกินไป โปรดอัปโหลดไฟล์ขนาดไม่เกิน 5MB";
                }
            } else {
                echo "กรุณาอัปโหลดไฟล์ในรูปแบบ JPG, JPEG, PNG หรือ GIF เท่านั้น";
            }
        } else {
            echo "ไม่มีไฟล์ที่อัปโหลด";
        }
    }
}
if (isset($_POST['submit_edit'])) {
    $type_id = $_POST['type_id'];

    if (isset($_FILES["iconImage"])) {
        $image_file = $_FILES['iconImage']['name'];
        $new_name = date("d_m_Y_H_i_s") . '-' . $image_file;
        $type = $_FILES['iconImage']['type'];
        $size = $_FILES['iconImage']['size'];
        $temp = $_FILES['iconImage']['tmp_name'];

        $path = "../img/icon/" . $new_name;
        $directory = "../img/icon/";

        // Allowed file types
        $allowed_types = array('image/jpg', 'image/jpeg', 'image/png', 'image/gif');

        // Validate file type
        if (in_array($type, $allowed_types)) {
            // Validate file size (less than 5MB)
            if ($size < 5000000) {
                // Check if file already exists
                if (!file_exists($path)) {
                    // Upload file
                    if (move_uploaded_file($temp, $path)) {
                        // Insert into database
                        // Assuming $conn is your database connection

                        // Prepare SQL statement with parameterized query
                        $stmt = $conn->prepare("UPDATE `type` SET `type_work` = '$type_work', `type_icon` = '$new_name' WHERE `type`.`type_id` = $type_id");

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
                        $stmt->close();
                    } else {
                        echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์";
                    }
                } else {
                    echo "ไฟล์นี้มีอยู่แล้ว... กรุณาตรวจสอบโฟลเดอร์การอัปโหลด";
                }
            } else {
                echo "ไฟล์ของคุณมีขนาดใหญ่เกินไป โปรดอัปโหลดไฟล์ขนาดไม่เกิน 5MB";
            }
        } else {
            echo "กรุณาอัปโหลดไฟล์ในรูปแบบ JPG, JPEG, PNG หรือ GIF เท่านั้น";
        }
    } else {
        echo "ไม่มีไฟล์ที่อัปโหลด";
    }
}

if (isset($_POST['submit_delete'])) {
    $type_id = $_POST['type_id'];

    // Assuming $conn is your database connection
    $stmt = $conn->prepare("SELECT `type_icon` FROM `type` WHERE `type_id` = ?");
    $stmt->bind_param("i", $type_id);
    $stmt->execute();
    $stmt->bind_result($type_icon);
    $stmt->fetch();
    $stmt->close();

    if ($type_icon) {
        $path = "../img/icon/" . $type_icon;

        // Delete the file if it exists
        if (file_exists($path)) {
            unlink($path);
        }

        // Prepare SQL statement to delete the record
        $stmt = $conn->prepare("DELETE FROM `type` WHERE `type_id` = ?");
        $stmt->bind_param("i", $type_id);

        if ($stmt->execute()) {
            ?>
            <script>
                setTimeout(function() {
                    Swal.fire({
                        title: '<div class="t1">ลบข้อมูลสำเร็จ</div>',
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
                        title: '<div class="t1">เกิดข้อผิดพลาดในการลบข้อมูล</div>',
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
        $stmt->close();
    } else {
        echo "ไม่พบข้อมูลที่ต้องการลบ";
    }
}


// Query to fetch all types after insertion
$sql = "SELECT * FROM `type`";
$result = $conn->query($sql);
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

    <!-- Favicon -->
    <!-- <link href="img/favicon.ico" rel="icon"> -->

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

    <!-- font awesome -->
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

        .container-box {
            border: 1px solid #ccc;
            padding: 35px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
            max-width: 1000px;
        }

        /* เพิ่มสไตล์ CSS เพื่อปรับสีกรอบให้เข้มขึ้น */
        .table-bordered {
            border-color: #666 !important;
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
            width: 100px;
        }

        /* 3. เพิ่มการเรียงลำดับให้เป็นแถวคู่-คี่ */
        .table tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
            /* สลับสีพื้นหลังแถว */
        }

        .table th:nth-child(1),
        .table td:nth-child(1) {
            width: 200px;
            height: 50px;
            /* กำหนดความกว้างของคอลัมน์การจัดการให้เหมาะสม */
        }

        .table th:nth-child(2),
        .table td:nth-child(2) {
            width: 400px;
            height: 50px;
            /* กำหนดความกว้างของคอลัมน์การจัดการให้เหมาะสม */
        }
        
        .table th:nth-child(3),
        .table td:nth-child(3) {
            width: 500px;
            height: 50px;
            /* กำหนดความกว้างของคอลัมน์การจัดการให้เหมาะสม */
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
                        <a href="managePhotographer.php" class="dropdown-item">ข้อมูลช่างภาพ</a>
                        <a href="manageType.php" class="dropdown-item active">ข้อมูลประเภทงาน</a>
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
    <div class="mt-5 " style="height: 100%;">
        <div class="text-center" style="font-size: 18px;"><b><i class="fas fa-file-alt"></i>&nbsp;&nbsp;ข้อมูลประเภทงาน</b></div>
        <div class="mt-3  col-7 container-fluid ">
            <div class="row ">
                <div class="container-sm mt-2 table-responsive">
                    <table class="table bg-white table-hover table-bordered-3">
                        <thead>
                            <tr>
                                <!-- <th scope="col" class="text-center">รหัส</th> -->
                                <th scope="col">ประเภทงาน</th>
                                <th scope="col">สัญลักษณ์ประเภทงาน</th>
                                <th scope="col" class="text-center">ดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                            ?>
                                    <tr>
                                        <td><?php echo $row['type_work']; ?></td>
                                        <td><?php echo $row['type_icon']; ?></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detaileModal<?php echo $row['type_id']; ?>">ดูเพิ่มเติม</button>
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['type_id']; ?>">แก้ไข</button>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $row['type_id']; ?>">ลบ</button>
                                        </td>
                                    </tr>
                                    <!-- Detail Modal -->
                                    <div class="modal fade" id="detaileModal<?php echo $row['type_id']; ?>" tabindex="-1" aria-labelledby="detaileModalLabel<?php echo $row['type_id']; ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered ">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="detaileModalLabel<?php echo $row['type_id']; ?>"><b><i class="fas fa-file-alt"></i>&nbsp;รายละเอียดประเภท<?php echo $row['type_work']; ?></b></h5>
                                                </div>
                                                <div class="modal-body" style="height: 460px;">
                                                    <div class="mt-1 container-md ">
                                                        <!-- <div class="text-center" style="font-size: 18px;"><b><i class="fas fa-file-alt"></i>&nbsp;&nbsp;รายละเอียดข้อมูลประเภท<?php echo $row['type_work']; ?></b></div> -->
                                                        <div class="mt-1 col-md-12 container-fluid ">
                                                            <div class="row ">
                                                                <div class="col-md-12 container-fluid">
                                                                    <div class="card-body">
                                                                        <form method="post" action="" enctype="multipart/form-data">
                                                                            <div class="row mt-1 align-items-center">
                                                                                <div class="col-12 mt-1">
                                                                                    <label for="photo" style="font-weight: bold; display: flex; align-items: center;">
                                                                                        <span style="color: black; margin-right: 5px; font-size: 13px;">สัญลักษณ์ประเภทงาน</span>
                                                                                        <span style="color: red;">*</span>
                                                                                    </label>
                                                                                    <center><img src="../img/icon/<?php echo $row['type_icon']; ?>" style="height: 90px; width: 90px;" alt="Your Image"></center>
                                                                                </div>
                                                                                <div class="col-12 mt-3">
                                                                                    <label for="photo" style="font-weight: bold; display: flex; align-items: center;">
                                                                                        <span style="color: black; margin-right: 5px; font-size: 13px;">ชื่อประเถทงาน</span>
                                                                                        <span style="color: red;">*</span>
                                                                                    </label>
                                                                                    <input type="text" required name="type_work" class="form-control" value="<?php echo $row['type_work']; ?>" readonly>
                                                                                </div>
                                                                                <div class="modal-footer mt-5 justify-content-center">
                                                                                    <button type="button" class="btn btn-danger" style="width: 150px; height:45px;" data-bs-dismiss="modal">ปิด</button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Edite Modal -->
                                    <div class="modal fade" id="editModal<?php echo $row['type_id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $row['type_id']; ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered ">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel<?php echo $row['type_id']; ?>"><b><i class="fas fa-file-alt"></i>&nbsp;แก้ไขประเภท<?php echo $row['type_work']; ?></b></h5>
                                                </div>
                                                <div class="modal-body" style="height: 460px;">
                                                    <div class="mt-1 container-md ">
                                                        <div class="text-center" style="font-size: 18px;"><b><i class="fas fa-file-alt"></i>&nbsp;&nbsp;แก้ไขข้อมูลประเภท<?php echo $row['type_work']; ?></b></div>
                                                        <div class="mt-1 col-md-12 container-fluid ">
                                                            <div class="row ">
                                                                <div class="col-md-12 container-fluid">
                                                                    <div class="card-body">
                                                                        <form method="post" action="" enctype="multipart/form-data">
                                                                            <div class="row mt-1 align-items-center">
                                                                                <div class="col-12 mt-2">
                                                                                    <center><img src="../img/icon/<?php echo $row['type_icon']; ?>" style="height: 90px; width: 90px;" alt="Your Image"></center>
                                                                                    <div>
                                                                                        <label for="photo" style="font-weight: bold; display: flex; align-items: center;">
                                                                                            <span style="color: black; margin-right: 5px; font-size: 13px;">สัญลักษณ์ประเภทงาน</span>
                                                                                            <span style="color: red;">*</span>
                                                                                        </label>
                                                                                    </div>
                                                                                    <input type="file" required name="iconImage" class="form-control">
                                                                                </div>
                                                                                <div class="col-12 mt-3">
                                                                                    <input type="text" required name="type_work" class="form-control" value="<?php echo $row['type_work']; ?>">
                                                                                </div>
                                                                                <div class="modal-footer mt-5 justify-content-center">
                                                                                    <button type="button" class="btn btn-danger" style="width: 150px; height:45px;" data-bs-dismiss="modal">ปิด</button>
                                                                                    <button type="submit" name="submit_edit" class="btn btn-primary" style="width: 150px; height:45px;">บันทึกการแก้ไข</button>
                                                                                </div>
                                                                            </div>
                                                                            <input type="hidden" name="type_id" value="<?php echo $row['type_id']; ?>">
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal<?php echo $row['type_id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $row['type_id']; ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered ">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel<?php echo $row['type_id']; ?>"><b><i class="fas fa-file-alt"></i>&nbsp;ลบประเภท <?php echo $row['type_work']; ?></b></h5>
                                                </div>
                                                <div class="modal-body" style="height: 460px;">
                                                    <div class="mt-1 container-md ">
                                                        <div class="mt-1 col-md-12 container-fluid ">
                                                            <div class="row ">
                                                                <div class="col-md-12 container-fluid">
                                                                    <div class="card-body">
                                                                        <form method="post" action="" enctype="multipart/form-data">
                                                                            <div class="row mt-1 align-items-center">
                                                                                <div class="col-12 mt-1">
                                                                                    <label for="photo" style="font-weight: bold; display: flex; align-items: center;">
                                                                                        <span style="color: black; margin-right: 5px; font-size: 13px;">สัญลักษณ์ประเภทงาน</span>
                                                                                        <span style="color: red;">*</span>
                                                                                    </label>
                                                                                    <center><img src="../img/icon/<?php echo $row['type_icon']; ?>" style="height: 90px; width: 90px;" alt="Your Image"></center>
                                                                                </div>
                                                                                <div class="col-12 mt-3">
                                                                                    <label for="type_work" style="font-weight: bold; display: flex; align-items: center;">
                                                                                        <span style="color: black; margin-right: 5px; font-size: 13px;">ชื่อประเภทงาน</span>
                                                                                        <span style="color: red;">*</span>
                                                                                    </label>
                                                                                    <input type="text" required name="type_work" class="form-control" value="<?php echo $row['type_work']; ?>" readonly>
                                                                                </div>
                                                                                <div class="modal-footer mt-5 justify-content-center">
                                                                                    <button type="button" class="btn btn-danger" style="width: 150px; height:45px;" data-bs-dismiss="modal">ยกเลิก</button>
                                                                                    <button type="submit" name="submit_delete" class="btn btn-warning" style="width: 150px; height:45px;">ลบ</button>
                                                                                </div>
                                                                            </div>
                                                                            <input type="hidden" name="type_id" value="<?php echo $row['type_id']; ?>">
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='6'>ไม่พบข้อมูลประเภทงาน</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-5 mb-1">
            <div class="col-md-12 text-center">
                <button type="button" onclick="window.location.href='manage.php'" class="btn btn-danger me-4" style="width: 150px; height:45px;">ย้อนกลับ</button>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal" style="width: 150px; height:45px;">เพิ่มประเภทงาน</button>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-l">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel"><b><i class="fas fa-file-alt"></i>&nbsp;เพิ่มประเภทงาน</b></h5>
                </div>
                <div class="modal-body" style="height: 460px;">
                    <div class="mt-1 container-md">
                        <div class="card-body">
                            <form method="post" action="" enctype="multipart/form-data">
                                <div class="row mt-1 align-items-center">
                                    <div class="d-flex justify-content-center align-items-center mt-2">
                                        <div class="circle">
                                            <img id="previewImage" src="../img/icon/nullIcon.png" alt="Default Icon">
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div>
                                            <label for="iconImage" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px; font-size: 13px;">สัญลักษณ์ประเภทงาน</span>
                                                <span style="color: red;">*</span>
                                            </label>
                                        </div>
                                        <input id="iconImage" type="file" required name="iconImage" class="form-control" onchange="updateImage()">
                                    </div>
                                    <div class="col-12 mt-3">
                                        <input type="text" required name="type_work" class="form-control" placeholder="ป้อนชื่อประเภทงาน">
                                    </div>
                                    <div class="modal-footer mt-5 justify-content-center">
                                        <button type="button" class="btn btn-danger" style="width: 150px; height:45px;" data-bs-dismiss="modal">ปิด</button>
                                        <button type="submit" name="submit_add" class="btn btn-primary" style="width: 150px; height:45px;">เพิ่ม</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer Start -->
    <div class="container-fluid bg-dark footer wow fadeIn ">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const previewImage = document.getElementById('previewImage');

            // Set a default image if the current src is null, empty, or ends with '/'
            if (!previewImage.src || previewImage.src.endsWith('/') || previewImage.src.includes('null')) {
                previewImage.src = '../img/icon/nullIcon.png'; // Path to the default image
            }
        });

        function updateImage() {
            const input = document.getElementById('iconImage');
            const previewImage = document.getElementById('previewImage');
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
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

</body>

</html>