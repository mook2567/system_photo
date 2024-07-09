<?php
session_start();
include '../config_db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $information_name = $_POST['information_name'];
    $information_caption = $_POST['information_caption'];

    if (isset($_FILES["logoImage"])) {
        $image_file = $_FILES['logoImage']['name'];
        $new_name = date("d_m_Y_H_i_s") . '-' . $image_file;
        $type = $_FILES['logoImage']['type'];
        $size = $_FILES['logoImage']['size'];
        $temp = $_FILES['logoImage']['tmp_name'];

        $path = "../img/logo/" . $new_name;
        $directory = "../img/logo/";

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
                        $stmt = $conn->prepare("UPDATE `information` SET `information_name` = '$information_name', `information_caption` = '$information_caption', `information_icon` = '$new_name' WHERE `information`.`information_id` = 1;");

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
                                        title: '<div class="t1">เกิดข้อผิดพลาดในการบันทึก</div>',
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






$sql = "SELECT * FROM `information`";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

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

        /* เพิ่ม CSS ของพื้นหลัง */
        body {
            background-size: cover;
            /* ปรับขนาดรูปภาพให้เต็มพื้นที่ */
            background-repeat: no-repeat;
            /* ไม่ให้รูปภาพซ้ำซ้อน */
            background-position: center;
            /* ตำแหน่งภาพในหน้าจอ */
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
            <img class="img-fluid" src="../img/logo/<?php echo $row['information_icon']; ?>" style="height: 30px;">
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
                        <a href="manageWeb.php" class="dropdown-item active">ข้อมูลระบบ</a>
                        <a href="manageAdmin.php" class="dropdown-item">ข้อมูลผู้ดูแลระบบ</a>
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
        <div class="text-center" style="font-size: 18px;"><b><i class="fa fa-cogs"></i>&nbsp;&nbsp;จัดการข้อมูลระบบ</b></div>
        <form method="post" action="manageWeb.php?id=<?php echo $row['information_id']; ?>" enctype="multipart/form-data">
            <div class="mt-3 col-md-8 container-fluid">
                <div class="col-12">
                    <div class="row mt-2">
                        <div class="col-6">
                            <label for="information_name" style="font-weight: bold; display: flex; align-items: center;">
                                <span style="color: black; margin-right: 5px;font-size: 13px;">ชื่อระบบ</span>
                                <span style="color: red;">*</span>
                            </label>
                            <input type="text" required name="information_name" class="form-control mt-1" placeholder="กรุณากรอกชื่อ" value="<?php echo $row['information_name']; ?>">
                        </div>
                        <div class="col-6">
                            <label for="information_caption" style="font-weight: bold; display: flex; align-items: center; margin-right: 5px;">
                                <span style="color: black; margin-right: 5px;font-size: 13px;">คำอธิบายของระบบ</span>
                                <span style="color: red;">*</span>
                            </label>
                            <textarea name="information_caption" required class="form-control mt-1" rows="1" style="resize: none;"><?php echo $row['information_caption']; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row mt-2">
                        <div class="col-6" >
                            <label for="information_icon" style="font-weight: bold; display: flex; align-items: center;">
                                <span style="color: black; margin-right: 5px;font-size: 13px;">โลโก้</span>
                                <span style="color: red;">*</span>
                            </label>
                            <div class="d-flex justify-content-center align-items-center md"style="background-color: #cfd8dc;">
                                <div class="mt-1 mb-1">
                                    <img src="../img/logo/<?php echo $row['information_icon']; ?>" style="height: 50px; width: auto;" alt="Your Image">
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                                <label for="photo" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px; font-size: 13px;">แก้ไข logo</span>
                                    <span style="color: red;">*</span>
                                    <span style="color: red;font-size: 13px;">(อัปโหลดไฟล์รูปภาพเฉพาะรูปแบบ JPG, JPEG, PNG และ GIF เท่านั้น)</span>
                                </label>
                            <input type="file" required name="logoImage" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center container-center text-center">
                <div class="col-md-12 mt-5">
                    <!-- ตำแหน่งสำหรับปุ่ม "ย้อนกลับ" -->
                    <button type="button" onclick="window.location.href='manage.php'" class="btn btn-danger me-4" style="width: 150px; height:45px;">ย้อนกลับ</button>
                    <!-- ตำแหน่งสำหรับปุ่ม "บันทึกการแก้ไข" -->
                    <button id="saveButton" class="btn btn-primary" style="width: 150px; height:45px;">บันทึกการแก้ไข</button>
                </div>
            </div>
        </form>
        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-white-50 footer wow fadeIn fixed-bottom" data-wow-delay="0.1s">
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