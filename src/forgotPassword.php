<?php
session_start();
require_once 'config_db.php';
require_once 'popup.php';
// Including PHPMailer files and declaring the namespaces
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    // SQL query to search for user across 3 tables
    $query = "
        SELECT email, password, license, type FROM (
            SELECT admin_email AS email, admin_password AS password, admin_license AS license, 'admin' AS type FROM admin
            UNION ALL
            SELECT cus_email AS email, cus_password AS password, cus_license AS license, 'customer' AS type FROM customer
            UNION ALL
            SELECT photographer_email AS email, photographer_password AS password, photographer_license AS license, 'photographer' AS type FROM photographer
        ) AS users
        WHERE email = ?
    ";

    // Prepare and execute the query
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate random OTP
        function generateRandomPassword($length = 6)
        {
            $characters = '0123456789';
            $password = '';
            for ($i = 0; $i < $length; $i++) {
                $index = rand(0, strlen($characters) - 1);
                $password .= $characters[$index];
            }
            return $password;
        }

        $newPassword = generateRandomPassword();
        $_SESSION['otp'] = $newPassword;
        $_SESSION['email_forgot'] = $email;


        // Initialize PHPMailer and configure settings
        $mail = new PHPMailer(true);

        try {
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'botpcnone@gmail.com';
            $mail->Password   = 'rvda fhah qwxq smab'; // Please replace with the correct SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('bot@pcnone.com', 'ระบบ PhotoMatch');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'รีเซ็ตรหัสผ่าน';
            $mail->Body    = "
                <div style='font-family: Arial, sans-serif; color: #333;'>
                    <h2 style='color: #4CAF50;'>รหัสOTPคือ: $newPassword</h2><br>
                    <p>โปรดนำ OTP นี้ไปเติมในหน้าถัดไป:</p><br>
                    <a href='https://photomatch.pcnone.com/otp.php' style='display: inline-block; padding: 12px 24px; background-color: #4CAF50; color: #ffffff; text-decoration: none; border-radius: 5px; font-size: 16px;'>ไปที่ระบบ OTP</a><br><br>
                </div>
            ";

            $mail->send();
?>
            <div>
                <script>
                    setTimeout(function() {
                        Swal.fire({
                            title: 'ระบบได้ทำการส่งรหัสOTPไปยัง Gmail ของคุณแล้ว',
                            text: "นำรหัส OTP มากรอกในฟอร์มหน้าถัดไป",
                            icon: 'success',
                            confirmButtonText: 'ถัดไป',
                            allowOutsideClick: false, // ไม่อนุญาตให้คลิกนอก popup ปิด
                            allowEscapeKey: false, // ไม่อนุญาตให้กดปุ่ม ESC เพื่อปิด
                            allowEnterKey: false // ไม่อนุญาตให้กดปุ่ม Enter เพื่อปิด
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "OTP.php";
                            }
                        });
                    });
                </script>
            </div><?php
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            } else {

                    ?>
        <div>
            <script>
                setTimeout(function() {
                    Swal.fire({
                        title: 'ไม่พบบัญชีผู้ใช้',
                        text: " กรุณาตรวจสอบอีเมลของท่านอีกครั้ง",
                        icon: 'error',
                        confirmButtonText: 'ย้อนกลับ',
                        allowOutsideClick: false, // ไม่อนุญาตให้คลิกนอก popup ปิด
                        allowEscapeKey: false, // ไม่อนุญาตให้กดปุ่ม ESC เพื่อปิด
                        allowEnterKey: false // ไม่อนุญาตให้กดปุ่ม Enter เพื่อปิด
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "forgotPassword.php";
                        }
                    });
                });
            </script>
        </div>
<?php

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
    <form action="" method="post">
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
                            <h2><b>ลืมรหัสผ่าน</b></h2>
                        </div>
                        <div class="row">
                            <div class="form-group row" style="position: relative;">
                                <div class="col-1"><i class="fa-solid fa-envelope" style="font-size : 20px; position: absolute; top: 50%; transform: translateY(-50%);"></i></div>
                                <div class="col-11"><input type="email" name="email" class="form__input" placeholder="อีเมล"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div> <!-- removed redundant form tag -->
                                <button type="submit" value="ลืมรหัสผ่าน" class="btn">ลืมรหัสผ่าน</button>
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