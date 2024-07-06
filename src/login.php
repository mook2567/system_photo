<?php
session_start();
require_once 'config_db.php';
$error = ""; // Initialize error variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email and password are set
    if (isset($_POST["acID"]) && isset($_POST["password"])) {
        // Get email and password from the form
        $email = $_POST["acID"];
        $password = $_POST["password"];

        // Prepare statement to prevent SQL injection
        $stmt = $conn->prepare("
    SELECT email, password, license, type FROM (
        SELECT admin_email AS email, admin_password AS password, admin_license AS license, 'admin' AS type FROM admin
        UNION ALL
        SELECT cus_email AS email, cus_password AS password, cus_license AS license, 'customer' AS type FROM customer
        UNION ALL
        SELECT photographer_email AS email, photographer_password AS password, photographer_license AS license, 'photographer' AS type FROM photographer
    ) AS users
    WHERE email = ?
");


        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if there is a matching user
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verify password
            if ($password == $row['password']) {
                if ($row['license'] == '1') {
                    // Set session based on user type
                    if ($row['type'] == 'admin') {
                        $_SESSION['admin_login'] = $row['email'];
                        header("Location: admin/index.php");
                        exit();
                    } else if ($row['type'] == 'customer') {
                        $_SESSION['customer_login'] = $row['email'];
                        header("Location: customer/index.php");
                        exit();
                    } else if ($row['type'] == 'photographer') {
                        $_SESSION['photographer_login'] = $row['email'];
                        header("Location: photographer/index.php");
                        exit();
                    }
                } else {
                    $error = "รออนุมัติสิทธิ์การใช้งาน";
                }
            } else {
                $error = "รหัสผ่านไม่ถูกต้อง โปรดลองอีกครั้ง";
            }
        } else {
            $error = "ไม่มีอีเมลนี้ในระบบ";
        }

        // Close the statement and the connection
        $stmt->close();
        $conn->close();
    } else {
        $error = "กรุณากรอกอีเมลและรหัสผ่าน";
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
    <script type="text/javascript">
            function noBack(){
                window.history.forward()
            }
             
            noBack();
            window.onload = noBack;
            window.onpageshow = function(evt) { if (evt.persisted) noBack() }
            window.onunload = function() { void (0) }
        </script>
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
            height: 600px;
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
                <div class="col-md-4  text-center company__info" style="background-color:#1E2045">

                    <img class="imag-fluid" src="img/photomatchLogo.png" alt="">
                    <h4 class="company_title">ยินดีต้อนรับสู่หน้าเข้าระบบ</h4>
                    <h3><b>Photo Match</b></h3>
                </div>
                <div class="col-md-8 col-xs-12  col-sm-12 login_form"><br>
                    <div class="container-fluid">
                        <div class="row mt-5 " style="color:#FF5733">
                            <h2><b>เข้าสู่ระบบ</b></h2>
                        </div>
                        <?php if (!empty($error)) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        <div class="row">
                            <div class="form-group row" style="position: relative;">
                                <div class="col-1"><i class="fa-solid fa-envelope" style="position: absolute; top: 50%; transform: translateY(-50%);"></i></div>
                                <div class="col-11"><input type="email" name="acID" class="form__input" placeholder="อีเมล" required></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group row" style="position: relative;">
                                <div class="col-1"><i class="fa-solid fa-lock" style="position: absolute; top: 50%; transform: translateY(-50%);"></i></div>
                                <div class="col-11"><input type="password" name="password" class="form__input" placeholder="รหัสผ่าน" required></div>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-6"><label><input type="checkbox"> จดจำฉันไว้</label></div>
                            <div class="col-6"><a href="forgotPassword.php">ลืมรหัสผ่าน</a></div>
                        </div> -->
                        <div class="row">
                            <div>
                                <button type="submit" name="submit" value="เข้าสู่ระบบ" class="btn" >เข้าสู่ระบบ</button>
                            </div>
                            <br>
                        </div>
                        <div class="Login-register mb-5">
                            <div>
                                <p>ยังไม่มีบัญชีผู้ใช้? <a href="registerCustomer.php" class="register-link" >สมัครสมาชิก</a></p>
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