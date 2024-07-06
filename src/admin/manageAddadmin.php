<?php
session_start();
require_once '../config_db.php';
require_once '../popup.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับข้อมูลจากฟอร์ม
    $prefix = $_POST["prefix"];
    $firstName = $_POST["firstname"];
    $lastName = $_POST["lastname"];
    $tell = $_POST["phone"];
    $address = $_POST["address"];
    $district = $_POST["district"];
    $province = $_POST["province"];
    $zipCode = $_POST["zipCode"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $check_email_query = "SELECT admin_email FROM admin WHERE admin_email = ?";
    $stmt_check_email = $conn->prepare($check_email_query);
    $stmt_check_email->bind_param("s", $email);
    $stmt_check_email->execute();
    $stmt_check_email->store_result();
    $count = $stmt_check_email->num_rows;
    $stmt_check_email->close();
?>
    <?php
    if ($count > 0) { ?>
        <script>
            setTimeout(function() {
                Swal.fire({
                    title: '<div class=\"t1\">Email นี้มีผู้ใช้งานแล้ว</div>',
                    icon: 'error',
                    confirmButtonText: 'ออก',
                    allowOutsideClick: true,
                    allowEscapeKey: true,
                    allowEnterKey: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '';
                    }
                });
            });
        </script> <?php
                } else {
                    ?>
        <?php
                    if (isset($_FILES["profileImage"])) {
                        $image_file = $_FILES['profileImage']['name'];
                        $new_name = date("d_m_Y_H_i_s") . '-' . $image_file;
                        $type = $_FILES['profileImage']['type'];
                        $size = $_FILES['profileImage']['size'];
                        $temp = $_FILES['profileImage']['tmp_name'];

                        $path = "../img/profile/" . $new_name;
                        $directory = "../img/profile/";

                        if ($image_file) {
                            if ($type == "image/jpg" || $type == 'image/jpeg' || $type == "image/png" || $type == "image/gif") {
                                if (!file_exists($path)) {
                                    if ($size < 5000000) {
                                        move_uploaded_file($temp, $path);

                                        // Prepare SQL statement with parameterized query
                                        $stmt = $conn->prepare("INSERT INTO `admin` (admin_prefix, admin_name, admin_surname, admin_tell, admin_address, admin_district, admin_province, admin_zip_code, admin_email, admin_password, admin_Photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                                        $stmt->bind_param("sssssssssss", $prefix, $firstName, $lastName, $tell, $address, $district, $province, $zipCode, $email, $password, $new_name);
                                        if ($stmt->execute()) {
        ?>
                                <script>
                                    setTimeout(function() {
                                        Swal.fire({
                                            title: '<div class="t1">สมัครใช้งานสำเร็จ</div>',
                                            icon: 'success',
                                            confirmButtonText: 'ตกลง',
                                            allowOutsideClick: true,
                                            allowEscapeKey: true,
                                            allowEnterKey: false
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location.href = "manageAdmin.php";
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
                                </script>
<?php
                                        }
                                        $stmt->close();
                                    } else {
                                        echo "Your file is too large, please upload a file less than 5MB";
                                    }
                                } else {
                                    echo "File already exists... Check upload folder";
                                }
                            } else {
                                echo "Upload JPG, JPEG, PNG & GIF formats...";
                            }
                        } else {
                            echo "No file uploaded";
                        }
                    }
                    // ปิดการเชื่อมต่อ
                    $conn->close();
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
    <script>
        function validatePassword() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirm_password").value;
            if (password != confirmPassword) {
                Swal.fire({
                    title: 'รหัสผ่านไม่ตรงกัน',
                    icon: 'error',
                    confirmButtonText: 'ตกลง'
                });
                return false;
            }
            return true;
        }
    </script>
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
        <div class="text-center" style="font-size: 18px;"><b><i class="fa fa-user-plus"></i>&nbsp;&nbsp;ข้อมูลเพิ่มผู้ดูแลระบบ</b></div>
        <div class="mt-3 col-md-10 container-fluid">
        <form action="" method="post" enctype="multipart/form-data" onsubmit="return validatePassword()">

            <div class="row">
                <div class="col-8">
                    <div class="col-12">
                        <div class="row mt-2">
                            <div class="col-2">
                                <label for="prefix" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px;font-size: 13px;"> คำนำหน้า</span>
                                    <span style="color: red;">*</span>

                                </label>
                                <select class="form-select border-1" required id="prefix" name="prefix">
                                    <option value="">คำนำหน้า</option>
                                    <option value="นาย">นาย</option>
                                    <option value="นางสาว">นางสาว</option>
                                    <option value="นาง">นาง</option>
                                </select>
                            </div>
                            <div class="col-5">
                                <label for="name" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px;font-size: 13px;">ชื่อ</span>
                                    <span style="color: red;">*</span>
                                </label>
                                <input type="text" name="firstname" class="form-control" placeholder="กรุณากรอกชื่อ" required>
                            </div>
                            <div class="col-5">
                                <label for="surname" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black;margin-right: 5px; font-size: 13px;">นามสกุล</span>
                                    <span style="color: red;">*</span>
                                </label>
                                <input type="text" name="lastname" class="form-control" placeholder="กรุณากรอกนามสกุล" required>
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
                                <input type="text" name="address" class="form-control mt-1" placeholder="กรุณากรอกที่อยู่" style="resize: none;" required>
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
                                <input type="text" id="district" name="district" class="form-control" placeholder="กรุณากรอกอำเภอ" required>
                            </div>
                            <div class="col-md-4">
                                <label for="province" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px; font-size: 13px;">จังหวัด</span>
                                    <span style="color: red;">*</span>

                                </label>
                                <input type="text" name="province" class="form-control" placeholder="กรุณากรอกจังหวัด" required>
                            </div>
                            <div class="col-md-4">
                                <label for="zipcode" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px; font-size: 13px;">ไปรษณีย์</span>
                                    <span style="color: red;">*</span>

                                </label>
                                <input type="text" name="zipCode" class="form-control" placeholder="กรุณากรอกรหัสไปรษณีย์" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="phone" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px; font-size: 13px;">เบอร์โทรศัพท์</span>
                                    <span style="color: red;">*</span>
                                </label>
                                <input type="text" name="phone" class="form-control mt-1" placeholder="กรุณากรอกเบอร์โทรศัพท์" style="resize: none;" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px; font-size: 13px;">อีเมล</span>
                                    <span style="color: red;">*</span>
                                </label>
                                <input type="text" name="email" class="form-control mt-1" placeholder="กรุณากรอกอีเมล" style="resize: none;" required>
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
                                    <input type="password" id="password" minlength="5" name="password" class="form-control" placeholder="กรุณากรอกรหัสผ่าน" required>
                                    <button type="button" style="color: #fff; width: 60px; background-color: #555555; border: none;" id="togglePassword">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
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
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="กรุณายืนยันรหัสผ่าน" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4 mt-5">
                    <div class="d-flex justify-content-center align-items-center md">
                        <div class="circle">
                            <img id="userImage" src="../img/profile/<?php echo $row['admin_photo'] ? $row['admin_photo'] : 'null.png'; ?>">
                        </div>
                    </div>
                    <div class="align-items-center justify-content-center d-flex">
                        <div>
                            <div>
                                <label for="photo" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px; font-size: 13px;">รูปภาพโปรไฟล์</span>
                                    <span style="color: red;">*</span>
                                </label>
                            </div>
                            <input type="file" id="photo" name="profileImage" class="form-control" onchange="updateImage()">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center mt-5">
                <div class="col-md-12 text-center">
                    <!-- ตำแหน่งสำหรับปุ่ม "ย้อนกลับ" -->
                    <button onclick="window.location.href='manageAdmin.php'" class="btn btn-danger me-4" style="width: 150px; height:45px;">ย้อนกลับ</button>
                    <!-- ตำแหน่งสำหรับปุ่ม "บันทึกการแก้ไข" -->
                    <button type="submit" class="btn btn-primary m-1" style="width: 150px; height:45px;">เพิ่มผู้ดูแลระบบ</button>
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
                <div class="col-md-6 text-center text-md-end">
                    <div class="footer-menu">
                        <a href="index.php">หน้าหลัก</a>
                        <a href="#">คุกกี้</a>
                        <a href="contact.php">ช่วยเหลือ</a>
                        <a href="#">ถามตอบ</a>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                eyeIcon.classList.toggle('fa-eye-slash');
            });
        });
    </script>

</body>

</html>