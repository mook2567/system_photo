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

    if (isset($_FILES["profileImage"])) {
        $image_file = $_FILES['profileImage']['name'];
        $new_name = date("d_m_Y_H_i_s") . '-' . $image_file;
        $type = $_FILES['profileImage']['type'];
        $size = $_FILES['profileImage']['size'];
        $temp = $_FILES['profileImage']['tmp_name'];

        $path = "img/profile/" . $new_name;
        $directory = "img/profile/";

        if ($image_file) {
            if ($type == "image/jpg" || $type == 'image/jpeg' || $type == "image/png" || $type == "image/gif") {
                if (!file_exists($path)) {
                    if ($size < 5000000) {
                        move_uploaded_file($temp, $path);

                        // Prepare SQL statement with parameterized query
                        $stmt = $conn->prepare("INSERT INTO customer (cus_prefix, cus_name, cus_surname, cus_tell, cus_address, cus_district, cus_province, cus_zip_code, cus_email, cus_password, cus_Photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
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
</head>

<body>
    <form action="" method="post" enctype="multipart/form-data">
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
                                <select class="form-select border-1" id="prefix" name="prefix">
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
                                <input type="text" name="firstname" class="form-control" placeholder="กรุณากรอกชื่อ">
                            </div>
                            <div class="col-5">
                                <label for="name" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; font-size: 13px;">นามสกุล</span>
                                    <span style="color: red;">*</span>
                                </label>
                                <input type="text" name="lastname" class="form-control" placeholder="กรุณากรอกนามสกุล">
                            </div>
                        </div>
                        <div class="mt-2">
                            <label for="address" style="font-weight: bold; display: flex; align-items: center; margin-right: 5px;">
                                <span style="color: black; margin-right: 5px;font-size: 13px;">ที่อยู่</span>
                                <span style="color: red;">*</span>
                                </lab>
                            </label>
                            <input type="text" name="address" class="form-control" placeholder="กรุณากรอกที่อยู่">
                        </div>
                        <div class="row mt-2">
                            <div class="col-4">
                                <label for="district" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px;font-size: 13px;">อำเภอ</span>
                                    <span style="color: red;">*</span>
                                    </lab>
                                </label>
                                <input type="text" id="district" name="district" class="form-control" placeholder="กรุณากรอกอำเภอ">
                            </div>
                            <div class="col-4">
                                <label for="province" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px;font-size: 13px;">จังหวัด</span>
                                    <span style="color: red;">*</span>
                                </label>
                                <input type="text" name="province" class="form-control" placeholder="กรุณากรอกจังหวัด">
                            </div>
                            <div class="col-4">
                                <label for="zipCode" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px;font-size: 13px;">รหัสไปรษณีย์</span>
                                    <span style="color: red;">*</span>
                                </label>
                                <input type="text" name="zipCode" class="form-control" placeholder="กรุณากรอกรหัสไปรษณีย์">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="phone" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px;font-size: 13px;">เบอร์โทรศัพท์</span>
                                    <span style="color: red;">*</span>
                                    </lab>
                                </label>
                                <input type="tel" id="phone" name="phone" class="form-control" placeholder="กรุณากรอกเบอร์โทรศัพท์">
                            </div>

                            <div class="col-6">
                                <label for="email" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px;font-size: 13px;">อีเมล</span>
                                    <span style="color: red;">*</span>
                                    </lab>
                                </label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="กรุณากรอกอีเมล">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="province" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px;font-size: 13px;">รหัสผ่าน</span>
                                    <span style="color: red;">*</span>
                                </label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="กรุณากรอกรหัสผ่าน">
                            </div>
                            <div class="col-md-6">
                                <label for="phone" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px;font-size: 13px;">ยืนยันรหัสผ่าน</span>
                                    <span style="color: red;">*</span>
                                    </lab>
                                </label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="กรุณากรอกรหัสผ่าน">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-6 align-items-center justify-content-center d-flex">
                                <div class="">
                                    <div>
                                        <label for="profileImage" style="font-weight: bold; display: flex; align-items: center;">
                                            <span style="color: black; margin-right: 5px;">รูปภาพโปรไฟล์</span>
                                            <span style="color: red;">*</span>
                                        </label>
                                    </div>
                                    <input type="file" id="profileImage" name="profileImage" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center mt-4">
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary fw-bold m-1">สมัครสมาชิก
                            </button>
                        </div><br>
                    </div>
    </form>
    <div class="Login-register mt-3">
        <div>
            <p>คุณมีบัญชีผู้ใช้แล้ว? <a href="login.php" class="login-link">เช้าสู่ระบบ</a></p>
        </div>
    </div>
    </div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>

</html>