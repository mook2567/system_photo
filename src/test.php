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
        .main-content {
            width: 80%;
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

        a {
            text-decoration: none;
        }

        table {
            width: 100%;
        }

        body {
            font-family: 'Athiti', sans-serif;
            background-image: url('img/background.png');
            /* Replace 'new_bg.png' with the path to your new background image */
            background-size: cover;
            /* Optional: Adjusts the background image size to cover the entire body */
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

        .upe-mutistep-form .step {
            display: none;
        }

        .upe-mutistep-form .step-header .steplevel {
            position: relative;
            flex: 1;
            padding-bottom: 30px;
        }

        .upe-mutistep-form .step-header .steplevel.active {
            font-weight: 600;
        }

        .upe-mutistep-form .step-header .steplevel.finish {
            font-weight: 600;
            color: #009688;
        }

        .upe-mutistep-form .step-header .steplevel::before {
            content: "";
            position: absolute;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            z-index: 9;
            width: 20px;
            height: 20px;
            background-color: #d5efed;
            border-radius: 50%;
            border: 3px solid #ecf5f4;
        }

        .upe-mutistep-form .step-header .steplevel.active::before {
            background-color: #3fbdb4;
            border: 3px solid #d5f9f6;
        }

        .upe-mutistep-form .step-header .steplevel.finish::before {
            background-color: #3fbdb4;
            border: 3px solid #3fbdb4;
        }

        .upe-mutistep-form .step-header .steplevel::after {
            content: "";
            position: absolute;
            left: 50%;
            bottom: 8px;
            width: 100%;
            height: 3px;
            background-color: #f3f3f3;
        }

        .upe-mutistep-form .step-header .steplevel.active::after {
            background-color: #a7ede8;
        }

        .upe-mutistep-form .step-header .steplevel.finish::after {
            background-color: #009688;
        }

        .upe-mutistep-form .step-header .steplevel:last-child:after {
            display: none;
        }
    </style>
</head>

<body>
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
                        <h2 style="font-size: 15px;"><b>คุณต้องการสมัครสมาชิกในสถานะไหน ?</b></h2>
                    </div>
                    <div class="row">
                        <div class="form-group" style="display: flex; align-items: center;">
                            <input class="me-1" type="radio" id="customerIcon" name="userIcon" value="customer">ลูกค้า
                            <input class="me-1 ms-2" type="radio" id="photographerIcon" name="userIcon" value="photographer">ช่างภาพ
                        </div>
                    </div>

                    <div class="container">
                        <div class="row justify-content-md-center">
                            <div class="col-md-12">
                                <form class="upe-mutistep-form" id="Upemultistepsform" action="">
                                    <div class="step-header d-flex mb-2">
                                        <span class="steplevel">ข้อมูลส่วนตัว</span>
                                        <span class="steplevel">ข้อมูลเกี่ยวกับผลงาน</span>
                                        <span class="steplevel">ข้อมูลการชำระเงิน</span>
                                    </div>
                                    <div class="step">
                                        <div class="mb-3">
                                            <div class="row mt-2">
                                                <div class="col-2">
                                                    <label for="name" style="font-weight: bold; display: flex; align-items: center;">
                                                        <span style="color: black; margin-right: 5px;font-size: 13px; "> คำนำหน้า</span>
                                                        <span style="color: red;">*</span>
                                                    </label>
                                                    <select class="form-select border-1">
                                                        <option value="1">นาย</option>
                                                        <option value="2">นางสาว</option>
                                                        <option value="3">นาง</option>
                                                    </select>
                                                </div>
                                                <div class="col-5">
                                                    <label for="name" style="font-weight: bold; display: flex; align-items: center;">
                                                        <span style="color: black; margin-right: 5px;font-size: 13px;">ชื่อ</span>
                                                        <span style="color: red;">*</span>
                                                    </label>
                                                    <input type="text" id="name" class="form-control" placeholder="กรุณากรอกชื่อ">
                                                </div>
                                                <div class="col-5">
                                                    <label for="name" style="font-weight: bold; display: flex; align-items: center;">
                                                        <span style="color: black; font-size: 13px;">นามสกุล</span>
                                                        <span style="color: red;">*</span>
                                                    </label>
                                                    <input type="text" id="name" class="form-control" placeholder="กรุณากรอกนามสกุล">
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <label for="address" style="font-weight: bold; display: flex; align-items: center; margin-right: 5px;">
                                                    <span style="color: black; margin-right: 5px;font-size: 13px;">ที่อยู่</span>
                                                    <span style="color: red;">*</span>
                                                    </lab>
                                                </label>
                                                <textarea id="address" class="form-control" placeholder="กรุณากรอกที่อยู่" rows="1" style="resize: none; width: 100%;"></textarea>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-4">
                                                    <label for="district" style="font-weight: bold; display: flex; align-items: center;">
                                                        <span style="color: black; margin-right: 5px;font-size: 13px;">อำเภอ</span>
                                                        <span style="color: red;">*</span>
                                                        </lab>
                                                    </label>
                                                    <input type="text" id="district" class="form-control" placeholder="กรุณากรอกอำเภอ">
                                                </div>
                                                <div class="col-4">
                                                    <label for="province" style="font-weight: bold; display: flex; align-items: center;">
                                                        <span style="color: black; margin-right: 5px;font-size: 13px;">จังหวัด</span>
                                                        <span style="color: red;">*</span>
                                                    </label>
                                                    <input type="text" id="name" class="form-control" placeholder="กรุณากรอกจังหวัด">
                                                </div>
                                                <div class="col-4">
                                                    <label for="province" style="font-weight: bold; display: flex; align-items: center;">
                                                        <span style="color: black; margin-right: 5px;font-size: 13px;">รหัสไปรษณีย์</span>
                                                        <span style="color: red;">*</span>
                                                    </label>
                                                    <input type="text" id="province" class="form-control" placeholder="กรุณากรอกรหัสไปรษณีย์">
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <label for="phone" style="font-weight: bold; display: flex; align-items: center;">
                                                        <span style="color: black; margin-right: 5px;font-size: 13px;">เบอร์โทรศัพท์</span>
                                                        <span style="color: red;">*</span>
                                                        </lab>
                                                    </label>
                                                    <input type="tel" id="phone" class="form-control" placeholder="กรุณากรอกเบอร์โทรศัพท์">
                                                </div>

                                                <div class="col-6">
                                                    <label for="email" style="font-weight: bold; display: flex; align-items: center;">
                                                        <span style="color: black; margin-right: 5px;font-size: 13px;">อีเมล</span>
                                                        <span style="color: red;">*</span>
                                                        </lab>
                                                    </label>
                                                    <input type="email" id="email" class="form-control" placeholder="กรุณากรอกอีเมล">
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <label for="province" style="font-weight: bold; display: flex; align-items: center;">
                                                        <span style="color: black; margin-right: 5px;font-size: 13px;">รหัสผ่าน</span>
                                                        <span style="color: red;">*</span>
                                                    </label>
                                                    <input type="text" id="province" class="form-control" placeholder="กรุณากรอกรหัสผ่าน">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="phone" style="font-weight: bold; display: flex; align-items: center;">
                                                        <span style="color: black; margin-right: 5px;font-size: 13px;">ยืนยันรหัสผ่าน</span>
                                                        <span style="color: red;">*</span>
                                                        </lab>
                                                    </label>
                                                    <input type="tel" id="phone" class="form-control" placeholder="กรุณากรอกยืนยันรหัสผ่าน">
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
                                                <div class="d-flex justify-content-center align-items-center md col-6">
                                                    <div class="circle">
                                                        <img src="../img/dev3.jpg" alt="Your Image">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="step">
                                        <div class="mb-3">
                                            <div class="row g-4">
                                                <div class="col-md-12">
                                                    <label for="address" style="font-weight: bold; display: flex; align-items: center; margin-right: 5px;">
                                                        <span style="color: black; margin-right: 5px;font-size: 13px;">ไฟล์แฟ้มสะสมผลงาน</span>
                                                        <span style="color: red;">*</span>
                                                        </lab>
                                                    </label>
                                                    <input type="file" class="form-control" id="portfolio_files" name="portfolio_files" accept="image/*" multiple>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade show p-0 active">
                                                <div class="row g-2">
                                                    <div class="col-md-13">
                                                        <label for="address" style="font-weight: bold; display: flex; align-items: center; margin-right: 10px;">
                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">ขอบเขตพื้นที่ที่รับงาน</span>
                                                            <span style="color: red;">*</span>
                                                        </label>
                                                        <div style="border: 1px solid black; padding: 10px; border-radius: 5px;">
                                                            <div class="container">
                                                                <div class="row">
                                                                    <div class="col-6 justify-content-sm-start">
                                                                        <div class="form-check d-flex align-items-center">
                                                                            <input type="checkbox" id="bangkok" name="work_area" value="bangkok" class="form-check-input">
                                                                            <label class="form-check-label ms-2 mb-0" for="bangkok">กรุงเทพฯ</label>
                                                                        </div>
                                                                        <div class="form-check d-flex align-items-center">
                                                                            <input type="checkbox" id="central" name="work_area" value="central" class="form-check-input">
                                                                            <label class="form-check-label ms-2 mb-0" for="central">ภาคกลาง</label>
                                                                        </div>
                                                                        <div class="form-check d-flex align-items-center">
                                                                            <input type="checkbox" id="southern" name="work_area" value="southern" class="form-check-input">
                                                                            <label class="form-check-label ms-2 mb-0" for="southern">ภาคใต้</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6 justify-content-sm-start">
                                                                        <div class="form-check d-flex align-items-center">
                                                                            <input type="checkbox" id="northern" name="work_area" value="northern" class="form-check-input">
                                                                            <label class="form-check-label ms-2 mb-0" for="northern">ภาคเหนือ</label>
                                                                        </div>
                                                                        <div class="form-check d-flex align-items-center">
                                                                            <input type="checkbox" id="northeastern" name="work_area" value="northeastern" class="form-check-input">
                                                                            <label class="form-check-label ms-2 mb-0" for="northeastern">ภาคตะวันออกเฉียงเหนือ</label>
                                                                        </div>
                                                                        <div class="form-check d-flex align-items-center">
                                                                            <input type="checkbox" id="other" name="work_area" value="other" class="form-check-input">
                                                                            <label class="form-check-label ms-2 mb-0" for="other">อื่นๆ</label>
                                                                            <!-- <input type="text" id="other_text" name="other_text" placeholder="กรุณาระบุ" class="form-control"> -->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="step">
                                        <div class="mb-3">
                                            <div id="tab-3" class="tab-pane fade show p-0">
                                                <div class="row g-4">
                                                    <div class="col-md-12">
                                                        <label for="bank" style="font-weight: bold; display: flex; align-items: center; margin-right: 5px;">
                                                            <span style="color: black; margin-right: 5px; font-size: 13px;">ชื่อธนาคาร</span>
                                                            <span style="color: red;">*</span>
                                                        </label>
                                                        <div style="border: 1px solid black; padding: 10px; border-radius: 5px;">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="kbank" name="bank" value="kbank">
                                                                <label class="form-check-label" for="kbank">ธนาคารกสิกรไทย</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="scb" name="bank" value="scb">
                                                                <label class="form-check-label" for="scb">ธนาคารไทยพาณิชย์</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="bbl" name="bank" value="bbl">
                                                                <label class="form-check-label" for="bbl">ธนาคารกรุงเทพ</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="bbl" name="bank" value="bbl">
                                                                <label class="form-check-label" for="bbl">ธนาคารธนาคารทหารไทย</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="bbl" name="bank" value="bbl">
                                                                <label class="form-check-label" for="bbl">ธนาคารกรุงศรีอยุธยา</label>
                                                            </div>
                                                            <input type="checkbox" id="other" name="work_area" value="other">
                                                            <label for="other">อื่นๆ</label>
                                                            <input type="text" id="other_text" name="other_text" placeholder="กรุณาระบุ">
                                                        </div>
                                                        <!-- เพิ่มเติมตามต้องการ -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="name" style="font-weight: bold; display: flex; align-items: center;">
                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">เลขที่บัญชี</span>
                                                            <span style="color: red;">*</span>
                                                        </label>
                                                        <input type="text" id="name" class="form-control" placeholder="กรุณากรอกเลขที่บัญชี">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="name" style="font-weight: bold; display: flex; align-items: center;">
                                                            <span style="color: black; margin-right: 5px;font-size: 13px;">ชื่อบัญชี</span>
                                                            <span style="color: red;">*</span>
                                                        </label>
                                                        <input type="text" id="name" class="form-control" placeholder="กรุณากรอกบัญชี">
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="border: 1px solid black; padding: 5px; border-radius: 3px; margin-top: 20px;">
                                                <p style="font-size: 13px; color: red;">โปรดทราบนี่เป็นส่วนหนึ่งของการเก็บข้อมูลเพื่อนำไปใช้ในส่วนของการรับชำระเงินค่ามัดจำและค่าบริการจากลูกค้าหรือผู้ใช้บริการช่างภาพ คำแนะนำ* ชื่อบัญชี ควรตรงกับ ชื่อ-นาสกุล ที่ท่านได้กรอกสมัคร</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="step">
                                        <h3>สิ้นสุดการกรอกข้อมูล</h3>
                                    </div>
                                    <div class="d-flex btn-row justify-content-center">
                                        <button class="btn btn-primary fw-bold m-1" id="prevBtn" onclick="nextPrev(-1)" type="button">ย้อนกลับ</button>
                                        <button class="btn btn-primary fw-bold m-1" id="nextBtn" onclick="nextPrev(1)" type="button">ถัดไป</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
    <script>
        var currentTab = 0;
        tabShow(currentTab);

        function tabShow(n) {
            var x = document.getElementsByClassName("step");
            x[n].style.display = "block";
            if (n == 0) {
                document.getElementById("prevBtn").style.display = "none";
            } else {
                document.getElementById("prevBtn").style.display = "inline";
            }
            if (n == (x.length - 1)) {
                document.getElementById("nextBtn").innerHTML = "สมัครสมาชิก"
            } else {
                document.getElementById("nextBtn").innerHTML = "ถัดไป"
            }
            activelevel(n)
        }

        function nextPrev(n) {
            var x = document.getElementsByClassName("step");
            x[currentTab].style.display = "none";
            currentTab = currentTab + n;
            if (currentTab >= x.length) {
                document.getElementById("Upemultistepsform").submit();
                return false;
            }
            tabShow(currentTab);
        }

        function backPrev(n) {
            var x = document.getElementsByClassName("step");
            x[n].style.display = "block";
        }

        function activelevel(n) {
            var i, x = document.getElementsByClassName("steplevel");
            for (i = 0; i < x.length; i++) {
                x[i].className = x[i].className.replace(" active", "");
            }
            x[n].className += " active";
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>