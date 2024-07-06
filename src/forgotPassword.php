<?php
session_start();
require_once 'config_db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // เช็คว่ามีค่าข้อมูลถูกส่งมาจากฟอร์มหรือไม่
    if (isset($_POST["acID"]) && isset($_POST["password"])) {
        // รับค่าอีเมลและรหัสผ่านจากฟอร์ม
        $email = $_POST["acID"];
        $password = $_POST["password"];

        // ทำสิ่งที่ต้องการกับค่าที่ได้รับ เช่น ตรวจสอบข้อมูลในฐานข้อมูลหรือประมวลผลต่อไป
        // ตัวอย่างเช่น
        echo "อีเมล: " . $email . "<br>";
        echo "รหัสผ่าน: " . $password . "<br>";

        // คำสั่ง SQL เพื่อค้นหาข้อมูล
        $sql = "SELECT * FROM admin WHERE admin_email = '$email'";
        $result = mysqli_query($conn, $sql);

        // ตรวจสอบว่าพบข้อมูลหรือไม่
        if (mysqli_num_rows($result) > 0) {


            if ($email == $row['email']) {
                $_SESSION["firstname"] = $row["firstname"];
                $_SESSION["lastname"] = $row["lastname"];
                if (password_verify($password, $row['password'])) {
                    if ($row['urole'] == 'admin') {
                        $_SESSION['admin_login'] = $row['id'];
                        header("location: admin/admin.php");
                    } else if ($row['urole'] == 'teacher') {
                        $_SESSION['user_login'] = $row['id'];
                        header("location: user/user.php");
                    } else if ($row['urole'] == 'janitor') {
                        $_SESSION['janitor_login'] = $row['id'];
                        header("location: user1/user.php");
                    } else if ($row['urole'] == 'manager') {
                        $_SESSION['manager_login'] = $row['id'];
                        header("location: manager/manager.php");
                    } else if ($row['urole'] == 'superadmin') {
                        $_SESSION['superadmin_login'] = $row['id'];
                        header("location: superadmin/admin.php");
                    } else if ($row['urole'] == 'disable') {
                        $_SESSION['disable_login'] = $row['id'];
                        header("location: disable.php");
                    } else if ($row['urole'] == 'unconfirmemail') {
                        $_SESSION['unconfirmemail_login'] = $row['id'];
                        header("location: unconfirm_email.php");
                    }
                } else {
                    $_SESSION['error'] = 'รหัสผ่านไม่ถูกต้อง โปรดลองอีกครั้ง';
                    header("location: index.php");
                }
            } else {
                $_SESSION['error'] = 'อีเมลไม่ถูกต้อง';
                header("location: index.php");
            }
        } else {
            $_SESSION['error'] = "ไม่มีข้อมูลในระบบ";
            header("location: index.php");
        }
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
    <form action="sendOTP.php" method="post" method="post">
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
                        <div class="row"  style="color:#FF5733">
                            <h2><b>ลืมรหัสผ่าน</b></h2>
                        </div>
                        <div class="row">
                            <div class="form-group row" style="position: relative;">
                                <div class="col-1"><i class="fa-solid fa-envelope" style="font-size : 20px; position: absolute; top: 50%; transform: translateY(-50%);"></i></div>
                                <div class="col-11"><input type="email" name="acID" class="form__input" placeholder="อีเมล"></div>
                            </div>
                        </div>
                        <div>
                            <a href="login.php">กลับไปยังหน้าเข้าสู่ระบบ</a>
                        </div>
                        <div class="row">
                            <div> <!-- removed redundant form tag -->
                                <button type="submit" value="ลืมรหัสผ่าน" class="btn">ลืมรหัสผ่าน</button>
                            </div>
                            <br>
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