<?php
session_start();
require_once 'config_db.php';
require_once 'popup.php';

$_SESSION['otp'];
$email = $_SESSION['email_forgot'];


if (isset($_GET['new_password']) && isset($_GET['email'])) {
    $newPassword = $_GET['new_password'];
    $email = $_GET['email'];

    // บันทึกรหัสผ่านใหม่ลงฐานข้อมูล
    // ตรวจสอบตาราง admin
    $sqlAdmin = "SELECT * FROM admin WHERE admin_email = ?";
    $stmtAdmin = $conn->prepare($sqlAdmin);
    $stmtAdmin->bind_param("s", $email);
    $stmtAdmin->execute();
    $resultAdmin = $stmtAdmin->get_result();

    if ($resultAdmin->num_rows > 0) {
        // อีเมลอยู่ในตาราง admin, อัพเดทรหัสผ่านในตาราง admin
        $sqlUpdateAdmin = "UPDATE admin SET admin_password = ? WHERE admin_email = ?";
        $stmtUpdateAdmin = $conn->prepare($sqlUpdateAdmin);
        $stmtUpdateAdmin->bind_param("ss", $newPassword, $email);
        if ($stmtUpdateAdmin->execute()) {
            unset($_SESSION['otp']);
            unset($_SESSION['email_forgot']);
?>
            <div>
                <script>
                    setTimeout(function() {
                        Swal.fire({
                            title: 'ตั้งรหัสผ่านสำเร็จ',
                            text: "กลับสู่หน้า Login",
                            icon: 'success',
                            confirmButtonText: 'ออก',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = `login.php`;
                            }
                        });
                    });
                </script>
            </div>
        <?php
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'ไม่สามารถอัพเดทรหัสผ่านใน admin ได้']);
            exit;
        }
    }

    // ตรวจสอบตาราง photographer
    $sqlPhotographer = "SELECT * FROM photographer WHERE photographer_email = ?";
    $stmtPhotographer = $conn->prepare($sqlPhotographer);
    $stmtPhotographer->bind_param("s", $email);
    $stmtPhotographer->execute();
    $resultPhotographer = $stmtPhotographer->get_result();

    if ($resultPhotographer->num_rows > 0) {
        // อีเมลอยู่ในตาราง photographer, อัพเดทรหัสผ่านในตาราง photographer
        $sqlUpdatePhotographer = "UPDATE photographer SET photographer_password = ? WHERE photographer_email = ?";
        $stmtUpdatePhotographer = $conn->prepare($sqlUpdatePhotographer);
        $stmtUpdatePhotographer->bind_param("ss", $newPassword, $email);
        if ($stmtUpdatePhotographer->execute()) {
            unset($_SESSION['otp']);
            unset($_SESSION['email_forgot']);
        ?>
            <div>
                <script>
                    setTimeout(function() {
                        Swal.fire({
                            title: 'ตั้งรหัสผ่านสำเร็จ',
                            text: "กลับสู่หน้า Login",
                            icon: 'success',
                            confirmButtonText: 'ออก',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = `login.php`;
                            }
                        });
                    });
                </script>
            </div>
        <?php
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'ไม่สามารถอัพเดทรหัสผ่านใน photographer ได้']);
            exit;
        }
    }

    // ตรวจสอบตาราง customer
    $sqlCustomer = "SELECT * FROM customer WHERE cus_email = ?";
    $stmtCustomer = $conn->prepare($sqlCustomer);
    $stmtCustomer->bind_param("s", $email);
    $stmtCustomer->execute();
    $resultCustomer = $stmtCustomer->get_result();

    if ($resultCustomer->num_rows > 0) {
        // อีเมลอยู่ในตาราง customer, อัพเดทรหัสผ่านในตาราง customer
        $sqlUpdateCustomer = "UPDATE customer SET cus_password = ? WHERE cus_email = ?";
        $stmtUpdateCustomer = $conn->prepare($sqlUpdateCustomer);
        $stmtUpdateCustomer->bind_param("ss", $newPassword, $email);
        if ($stmtUpdateCustomer->execute()) {
            unset($_SESSION['otp']);
            unset($_SESSION['email_forgot']);
        ?>
            <div>
                <script>
                    setTimeout(function() {
                        Swal.fire({
                            title: 'ตั้งรหัสผ่านสำเร็จ',
                            text: "กลับสู่หน้า Login",
                            icon: 'success',
                            confirmButtonText: 'ออก',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = `login.php`;
                            }
                        });
                    });
                </script>
            </div>
        <?php
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'ไม่สามารถอัพเดทรหัสผ่านใน customer ได้']);
            exit;
        }
    }

    // ถ้าอีเมลไม่พบในทุกตาราง
    echo json_encode(['success' => false, 'message' => 'ไม่พบอีเมลนี้ในระบบ']);
}
if (isset($_GET['status']) == "true") {
    if (isset($_POST['otp_input']) && $_POST['otp_input'] === $_SESSION['otp']) {
        // หาก OTP ถูกต้อง แสดงฟอร์มเพื่อให้ผู้ใช้กรอกรหัสผ่านใหม่
        ?>
        <div>
            <script>
                Swal.fire({
                    title: 'ตั้งรหัสผ่านใหม่',
                    html: '<input id="new_password" type="password" class="swal2-input" placeholder="รหัสผ่านใหม่">' +
                        '<input id="confirm_password" type="password" class="swal2-input" placeholder="ยืนยันรหัสผ่านใหม่">',
                    icon: 'info',
                    confirmButtonText: 'บันทึก',
                    focusConfirm: false,
                    preConfirm: () => {
                        const newPassword = document.getElementById('new_password').value;
                        const confirmPassword = document.getElementById('confirm_password').value;

                        if (!newPassword || !confirmPassword) {
                            Swal.showValidationMessage('กรุณากรอกรหัสผ่านให้ครบถ้วน');
                        } else if (newPassword !== confirmPassword) {
                            Swal.showValidationMessage('รหัสผ่านไม่ตรงกัน');
                        } else {
                            return {
                                newPassword: newPassword
                            };
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const newPassword = encodeURIComponent(result.value.newPassword);
                        const email = encodeURIComponent('<?= $email; ?>');

                        // ส่งข้อมูลรหัสผ่านใหม่ผ่าน URL ไปยัง OTP.php เพื่อบันทึกลงฐานข้อมูล
                        window.location.href = `OTP.php?new_password=${newPassword}&email=${email}`;
                    }
                });
            </script>
        </div>
    <?php
    } else {
        // หาก OTP ไม่ถูกต้อง แสดง popup แจ้งเตือน
    ?>
        <div>
            <script>
                setTimeout(function() {
                    Swal.fire({
                        title: 'รหัส OTP ไม่ถูกต้อง',
                        text: "กรุณาตรวจสอบอีเมลของท่านอีกครั้ง",
                        icon: 'error',
                        confirmButtonText: 'ออก',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            exit;
                        }
                    });
                });
            </script>
        </div>
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
    <link rel="icon" type="image/png" href="img/icon-logo.png">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Athiti&family=Merriweather:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        .main-content {
            width: 50%;
            border-radius: 20px;
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
    </style>
</head>

<body>
    <form action="?status=true" method="post">
        <div class="container-fluid">
            <div class="row main-content text-center">
                <div class="col-md-4 text-center company__info" style="background-color:#1E2045">
                    <span>
                        <h2><span class="fa fa-android"></span></h2>
                    </span>
                    <img class="imag-fluid" src="img/photomatchLogo.png" alt="">
                    <h4 class="company_title">ยินดีต้อนรับสู่หน้าลืมรหัสผ่าน</h4>
                    <h3><b>Photo Match</b></h3>
                    <br><br><br><br><br>
                </div>
                <div class="col-md-8 col-xs-12 col-sm-12 login_form "><br>
                    <div class="container-fluid">
                        <br><br><br>
                        <div class="row" style="color:#FF5733">
                            <h2><b>OTP</b></h2>
                        </div>
                        <div class="row">
                            <div class="form-group row" style="position: relative;">
                                <div class="col-12"><input type="text" name="otp_input" class="form__input" placeholder="กรุณากรอก OTP ที่ส่งไปยังอีเมลของคุณ" maxlength="6"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div> <!-- removed redundant form tag -->
                                <button type="submit" class="btn">ตรวจสอบ</button>
                            </div>
                            <br>
                        </div>
                        <div>
                            <a href="login.php">กลับไปยังหน้าเข้าสู่ระบบ</a>
                        </div>
                        <div class="Login-register">
                            <div>
                                <p>ยังไม่มีบัญชีผู้ใช้? <a href="RegisterCustomer.php" class="register-link">สมัครสมาชิก</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>