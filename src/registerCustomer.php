<?php
session_start();
require_once 'config_db.php';
require_once 'popup.php';

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

    // ตรวจสอบไฟล์รูปภาพ
    if (isset($_FILES["profileImage"])) {
        $image_file = $_FILES['profileImage']['name'];
        $type = $_FILES['profileImage']['type'];
        $size = $_FILES['profileImage']['size'];
        $temp = $_FILES['profileImage']['tmp_name'];

        $path = "img/profile/" . date("d_m_Y_H_i_s") . '-' . $image_file;
        $directory = "img/profile/";

        // ตรวจสอบชนิดของไฟล์รูปภาพ
        if ($type == "image/jpg" || $type == 'image/jpeg' || $type == "image/png" || $type == "image/jpeg") {
            // ตรวจสอบขนาดของไฟล์
            if ($size < 5000000) { // 5MB
                // ย้ายไฟล์รูปภาพไปยังโฟลเดอร์ที่กำหนด
                if (move_uploaded_file($temp, $path)) {

                    // ตรวจสอบว่า Email มีใช้งานอยู่แล้วหรือไม่
                    $check_email_query = "SELECT admin_email AS email FROM admin WHERE admin_email = ? 
                                        UNION 
                                        SELECT cus_email AS email FROM customer WHERE cus_email = ?
                                        UNION
                                        SELECT photographer_email AS email FROM photographer WHERE photographer_email = ?";
                    $stmt_check_email = $conn->prepare($check_email_query);
                    $stmt_check_email->bind_param("sss", $email, $email, $email);
                    $stmt_check_email->execute();
                    $stmt_check_email->store_result();
                    $count = $stmt_check_email->num_rows;
                    $stmt_check_email->close();

                    if ($count > 0) {
?>
                        <script>
                            setTimeout(function() {
                                Swal.fire({
                                    title: '<div class="t1">Email นี้มีผู้ใช้งานแล้ว</div>',
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
                        </script>
                        <?php
                    } else {
                        // เตรียมคำสั่ง SQL สำหรับการเพิ่มข้อมูลลูกค้า
                        $stmt = $conn->prepare("INSERT INTO customer (cus_prefix, cus_name, cus_surname, cus_tell, cus_address, cus_district, cus_province, cus_zip_code, cus_email, cus_password, cus_Photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("sssssssssss", $prefix, $firstName, $lastName, $tell, $address, $district, $province, $zipCode, $email, $password, $path);

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
                                            window.location.href = "login.php";
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
                    }
                } else {
                    ?>
                    <script>
                        setTimeout(function() {
                            Swal.fire({
                                title: '<div class="t1">มีปัญหาในการย้ายไฟล์รูปภาพ</div>',
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
            } else {
                ?>
                <script>
                    setTimeout(function() {
                        Swal.fire({
                            title: '<div class="t1">ไฟล์ของคุณใหญ่เกินไป โปรดอัปโหลดไฟล์ที่มีขนาดน้อยกว่า 5MB</div>',
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
        } else {
            ?>
            <script>
                setTimeout(function() {
                    Swal.fire({
                        title: '<div class="t1">อัปโหลดไฟล์รูปภาพเฉพาะรูปแบบ JPG, JPEG, PNG และ GIF เท่านั้น</div>',
                        icon: 'error',
                        confirmButtonText: 'ออก',
                        allowOutsideClick: true,
                        allowEscapeKey: true,
                        allowEnterKey: false
                    });
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "";
                    }
                });
            </script>
        <?php
        }
    } else {
        ?>
        <script>setTimeout(function() {
            Swal.fire({
                title: '<div class="t1">ยังไม่มีไฟล์ที่อัปโหลด</div>',
                icon: 'error',
                confirmButtonText: 'ออก',
                allowOutsideClick: true,
                allowEscapeKey: true,
                allowEnterKey: false
            });}).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "";
                    }
                });
        </script>
<?php
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
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Athiti&family=Merriweather:wght@700&display=swap" rel="stylesheet">
    <style>
        .company__info {
            background-image: url('bg.png');
            background-size: cover;
            /* ปรับขนาดรูปภาพให้เต็มพื้นที่ */
            background-position: center;
            /* จัดตำแหน่งรูปภาพให้อยู่ตรงกลาง */
        }

        .main-content {
            width: 80%;
            display: flex;
            border-radius: 100px;
            box-shadow: 0 5px 5px rgba(0, 0, 0, .4);
            margin: 5em auto;
            display: flex;
        }

        .company__info {
            background-color: #1E2045;
            border-top-left-radius: 20px;
            border-bottom-left-radius: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: #fff;
        }

        .fa-android {
            font-size: 3em;
        }

        @media screen and (max-width: 640px) {
            .main-content {
                width: 90%;
            }

            .company__info {
                display: none;
            }

            .login_form {
                border-top-left-radius: 20px;
                border-bottom-left-radius: 20px;
            }
        }

        @media screen and (min-width: 642px) and (max-width: 800px) {
            .main-content {
                width: 70%;
            }
        }

        .row h2 {
            color: #1E2045;
        }

        .login_form {
            background-color: #fff;
            border-top-right-radius: 20px;
            border-bottom-right-radius: 20px;
            border-top: 1px solid #ccc;
            border-right: 1px solid #ccc;
        }

        form {
            padding: 0 2em;
        }

        .form__input {
            width: 100%;
            border: 0px solid transparent;
            border-radius: 0;
            border-bottom: 1px solid #aaa;
            padding: 1em .5em .5em;
            padding-left: 2em;
            outline: none;
            margin: 1.5em auto;
            transition: all .5s ease;
        }

        .form__input:focus {
            border-bottom-color: #1E2045;
            box-shadow: 0 0 5px rgba(0, 80, 80, .4);
            border-radius: 4px;
        }

        .btn {
            transition: all .5s ease;
            width: 70%;
            border-radius: 30px;
            color: #1E2045;
            font-weight: 600;
            background-color: #fff;
            border: 1px solid #1E2045;
            margin-top: 1.5em;
            margin-bottom: 1em;
        }

        .btn:hover,
        .btn:focus {
            background-color: #1E2045;
            color: #fff;
        }

        body {
            font-family: 'Athiti', sans-serif;
            background-image: url('img/background.png');
            /* Replace 'new_bg.png' with the path to your new background image */
            background-size: cover;
            /* Optional: Adjusts the background image size to cover the entire body */

        }

        a {
            text-decoration: none;
        }

        table {
            width: 100%;
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
    <form action="" method="post" enctype="multipart/form-data" onsubmit="return validatePassword()">
        <div class="container-fluid">
            <div class="row main-content text-center">
                <div class="col-md-4 text-center company__info" style="background-color:#1E2045">
                    <span>
                        <h2><span class="fa fa-android"></span></h2>
                    </span>
                    <img class="imag-fluid" src="img/photomatchLogo.png" alt="">
                    <h4 class="company_title" style="font-size: 15px;">ยินดีต้อนรับสู่หน้าสมัครสมาชิก</h4>
                    <h3><b>Photo Match</b></h3>
                </div>
                <div class="col-md-8 col-xs-12 col-sm-12 login_form "><br>
                    <div class="container-fluid">
                        <div class="row" style="color:#FF5733">
                            <h2><b>สมัครสมาชิก</b></h2>
                        </div>
                        <div class="col-5">
                            <h6><b>คุณต้องการสมัครสมาชิกในสถานะไหน?</b></h6>
                        </div>
                        <div class="form-group col-4 ms-0">
                            <input class="form-check-input me-1" type="radio" id="customerIcon" name="userIcon" value="customer" checked>ลูกค้า
                            <input class="form-check-input me-1 ms-5" type="radio" id="photographerIcon" name="userIcon" value="photographer">ช่างภาพ
                        </div>
                    </div>
                    <div class="container-fluid">
                        <div class="row mt-2">
                            <div class="col-2">
                                <label for="prefix" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px;font-size: 13px; "> คำนำหน้า</span>
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
                                <label for="name" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black;margin-right: 5px; font-size: 13px;">นามสกุล</span>
                                    <span style="color: red;">*</span>
                                </label>
                                <input type="text" name="lastname" class="form-control" placeholder="กรุณากรอกนามสกุล" required>
                            </div>
                        </div>
                        <div class="mt-2">
                            <label for="address" style="font-weight: bold; display: flex; align-items: center; margin-right: 5px;">
                                <span style="color: black; margin-right: 5px;font-size: 13px;">ที่อยู่</span>
                                <span style="color: red;">*</span>
                                </lab>
                            </label>
                            <input type="text" name="address" class="form-control" placeholder="กรุณากรอกที่อยู่" required>
                        </div>
                        <div class="row mt-2">
                            <div class="col-4">
                                <label for="district" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px;font-size: 13px;">อำเภอ</span>
                                    <span style="color: red;">*</span>
                                    </lab>
                                </label>
                                <input type="text" id="district" name="district" class="form-control" placeholder="กรุณากรอกอำเภอ" required>
                            </div>
                            <div class="col-4">
                                <label for="province" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px;font-size: 13px;">จังหวัด</span>
                                    <span style="color: red;">*</span>
                                </label>
                                <input type="text" name="province" class="form-control" placeholder="กรุณากรอกจังหวัด" required>
                            </div>
                            <div class="col-4">
                                <label for="zipCode" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px;font-size: 13px;">รหัสไปรษณีย์</span>
                                    <span style="color: red;">*</span>
                                </label>
                                <input type="text" name="zipCode" class="form-control" placeholder="กรุณากรอกรหัสไปรษณีย์" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="phone" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px; font-size: 13px;">เบอร์โทรศัพท์</span>
                                    <span style="color: red;">*</span>
                                </label>
                                <input type="tel" id="phone" name="phone" class="form-control" placeholder="กรุณากรอกเบอร์โทรศัพท์" required>
                            </div>
                            <div class="col-6">
                                <label for="email" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px;font-size: 13px;">อีเมล</span>
                                    <span style="color: red;">*</span>
                                    </lab>
                                </label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="กรุณากรอกอีเมล" required>
                            </div>
                        </div>
                        <div class="row mt-2">
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
                        <div class="row mt-3">
                            <div class="col-7 align-items-center ">
                                <div class="">
                                    <label for="profileImage" style="font-weight: bold; display: flex; align-items: center;">
                                        <span style="color: black; margin-right: 5px; font-size: 13px;">รูปภาพโปรไฟล์</span>
                                        <span style="color: red;">*</span>
                                        <span style="color: red;font-size: 13px;">(อัปโหลดไฟล์รูปภาพเฉพาะรูปแบบ JPG, JPEG และ PNG เท่านั้น)</span>
                                    </label>
                                    <input type="file" id="profileImage" name="profileImage" class="form-control" accept=".jpg, .jpeg, .png" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center mt-4">
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary fw-bold m-1">สมัครสมาชิก</button>
                        </div><br>
                    </div>
    </form>
    <div class="Login-register mt-3">
        <div>
            <p>คุณมีบัญชีผู้ใช้แล้ว? <a href="login.php" class="login-link">เข้าสู่ระบบ</a></p>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // เลือก Radio Buttons
            var regisRadios = document.querySelectorAll('input[name="userIcon"]');
            // เลือกเนื้อหาของโพสต์
            var registerDiv = document.getElementById('register');

            // เพิ่ม Event Listener สำหรับ Radio Buttons
            regisRadios.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    // ตรวจสอบสถานะของ Radio Buttons เมื่อมีการเปลี่ยนแปลง
                    if (this.id === 'customerIcon' && this.checked) {
                        window.location.href = "registerCustomer.php";
                    }
                    if (this.id === 'photographerIcon' && this.checked) {
                        window.location.href = "registerPhotographer.php";
                    }
                });
            });
        });
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>

</html>
