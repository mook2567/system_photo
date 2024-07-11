<?php
session_start();
include "../config_db.php";
require_once '../popup.php';
$id = $_GET['id'];

$sql = "SELECT * FROM `admin` WHERE admin_id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
// ตรวจสอบว่ามีการส่งข้อมูลมาหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่า license ที่ส่งมาจากฟอร์ม
    $license = $_POST['license'];
    $cus_id = $id; // สมมติว่าคุณส่งค่า cus_id มาจากฟอร์มด้วย

    // ปรับปรุงข้อมูลในฐานข้อมูล
    $sql = "UPDATE `admin` SET admin_license = ? WHERE admin_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $license, $cus_id);

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
                        window.location.href = "manageAdminDetails.php?id=<?php echo $id; ?>";
                    }
                });
            });
        </script><?php
                } else { ?>
        <script>
            setTimeout(function() {
                Swal.fire({
                    title: '<div class="t1">เกิดข้อผิดพลาดในการสมัครใช้งาน</div>',
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
        </script><?php
                }
                $stmt->close();
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
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container-md {
            flex: 1;
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

        .footer {
            background-color: #343a40;
            color: rgba(255, 255, 255, 0.5);
        }

        .footer a {
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
        }

        .footer a:hover {
            color: rgba(255, 255, 255, 0.75);
        }

        .footer .footer-menu a {
            margin-right: 15px;
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
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-0 px-4">
        <a href="index.html" class="navbar-brand d-flex align-items-center text-center">
            <img class="img-fluid" src="../img/photoLogo.png" style="height: 60px;">
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
                        <a href="manageWeb.php" class="dropdown-item ">ข้อมูลระบบ</a>
                        <a href="manageAdmin.php" class="dropdown-item active">ข้อมูลผู้ดูแลระบบ</a>
                        <a href="manageCustomer.php" class="dropdown-item">ข้อมูลลูกค้า</a>
                        <a href="managePhotographer.php" class="dropdown-item">ข้อมูลช่างภาพ</a>
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
    <div class="mt-5 container-md">
        <div class="text-center" style="font-size: 18px;"><b><i class="fa fa-user-cog"></i>&nbsp;&nbsp;ข้อมูลผู้ดูแลระบบ คุณ <?php echo $row['admin_name']; ?></b></div>
        <div class="mt-3 col-md-10 container-fluid">
            <div class="row">
                <div class="col-8">
                    <div class="col-12">
                        <div class="row mt-2">
                            <div class="col-2">
                                <label for="prefix" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px;font-size: 13px;"> คำนำหน้า</span>
                                </label>
                                <input type="text" name="prefix" class="form-control mt-1" value="<?php echo $row['admin_prefix']; ?>" readonly>
                            </div>
                            <div class="col-5">
                                <label for="name" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px;font-size: 13px;">ชื่อ</span>
                                </label>
                                <input type="text" name="name" class="form-control mt-1" value="<?php echo $row['admin_name']; ?>" readonly>
                            </div>
                            <div class="col-5">
                                <label for="surname" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; font-size: 13px;">นามสกุล</span>
                                </label>
                                <input type="text" name="surname" class="form-control mt-1" value="<?php echo $row['admin_surname']; ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <label for="address" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px;font-size: 13px;">ที่อยู่</span>
                                </label>
                                <input type="text" name="address" class="form-control mt-1" value="<?php echo $row['admin_address']; ?>" readonly style="resize: none;">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="district" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px; font-size: 13px;">อำเภอ</span>
                                </label>
                                <input type="text" name="district" class="form-control mt-1" value="<?php echo $row['admin_district']; ?>" readonly style="resize: none;">
                            </div>
                            <div class="col-md-4">
                                <label for="province" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px; font-size: 13px;">จังหวัด</span>
                                </label>
                                <input type="text" name="province" class="form-control mt-1" value="<?php echo $row['admin_province']; ?>" readonly style="resize: none;">
                            </div>
                            <div class="col-md-4">
                                <label for="zipcode" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px; font-size: 13px;">ไปรษณีย์</span>
                                </label>
                                <input type="text" name="zipcode" class="form-control mt-1" value="<?php echo $row['admin_zip_code']; ?>" readonly style="resize: none;">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="tell" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px; font-size: 13px;">เบอร์โทรศัพท์</span>
                                </label>
                                <input type="text" name="tell" class="form-control mt-1" value="<?php echo $row['admin_tell']; ?>" readonly style="resize: none;">
                            </div>
                            <div class="col-md-4">
                                <label for="email" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px; font-size: 13px;">อีเมล</span>
                                </label>
                                <input type="text" name="email" class="form-control mt-1" value="<?php echo $row['admin_email']; ?>" readonly style="resize: none;">
                            </div>
                            <div class="col-md-4">
                                <label for="password" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px; font-size: 13px;">รหัสผ่าน</span>
                                </label>
                                <input type="password" name="password" class="form-control mt-1" value="<?php echo $row['admin_password']; ?>" readonly style="resize: none;">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-4 mt-5">
                    <div class="d-flex justify-content-center align-items-center md mt-2">
                        <div class="circle">
                            <img src="../img/profile/<?php echo $row['admin_photo']; ?>" alt="Your Image">
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
                    <form method="post" action="manageAdminDetails.php?id=<?php echo $id; ?>">
                        <div class="mt-2">
                            <label for="license" style="font-weight: bold; display: flex; align-items: center;">
                                <span style="color: black; margin-right: 5px;font-size: 13px;">สิทธิ์การใช้งาน</span>
                            </label>
                            <select class="form-select border-1 mt-1" name="license">
                                <?php
                                if ($row['admin_license'] == '0') {
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
            </div>
            <div class="row justify-content-center mt-5">
                <div class="col-md-12 text-center">
                    <!-- ตำแหน่งสำหรับปุ่ม "ย้อนกลับ" -->
                    <button type="button" onclick="window.location.href='manageAdmin.php'" class="btn btn-danger me-4" style="width: 150px; height:45px;">ย้อนกลับ</button>
                    <!-- ตำแหน่งสำหรับปุ่ม "บันทึกการแก้ไข" -->
                    <button type="submit" class="btn btn-primary" style="width: 150px; height:45px;">บันทึกการแก้ไข</button>
                </div>
            </div>
            </form>
        </div>
    </div>
    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white-50 footer wow fadeIn">
        <div class="copyright">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; <a class="border-bottom" href="#">2024 Photo Match</a>, All Right Reserved.
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
</body>

</html>