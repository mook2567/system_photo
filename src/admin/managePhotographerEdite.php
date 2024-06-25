<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Photo Match</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="icon" type="image/png" href="../img/icon-logo.png">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Athiti&family=Merriweather:wght@700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Template Stylesheet -->
    <link href="../css/style.css" rel="stylesheet">

    <!-- Custom Styles -->
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
            width: 190px;
            height: 190px;
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

        .row {
            display: flex;
        }

        .col-divider {
            display: flex;
            flex-direction: column;
        }

        .col-divider::after {
            content: "";
            display: block;
            margin-top: auto;
            /* จัดการให้เส้นขั้นอยู่ด้านล่าง */
            margin-left: 5rem;
            /* จัดการระยะห่างด้านซ้าย */
            border-left: 1px solid #ddd;
            /* สีและขนาดของเส้นขั้น */
            height: 100%;
            /* ความสูงของเส้นขั้น */
        }
    </style>
</head>

<body>

    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-0 px-4">
        <a href="index.html" class="navbar-brand d-flex align-items-center text-center">
            <img class="img-fluid" src="../img/photoLogo.png" style="height: 60px;">
        </a>
        <!-- Toggler button for small screens -->
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon text-primary"></span>
        </button>
        <!-- Navbar links -->
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto f">
                <a href="index.php" class="nav-item nav-link ">หน้าหลัก</a>
                <!-- Dropdown menu -->
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
                <a href="report.php" class="nav-item nav-link ">รายงาน</a>
                <!-- Dropdown menu -->
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle bg-dark" data-bs-toggle="dropdown">โปรไฟล์</a>
                    <div class="dropdown-menu rounded-0 m-0">
                        <a href="profile.php" class="dropdown-item">โปรไฟล์</a>
                        <a href="../index.php" class="dropdown-item">ออกจากระบบ</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Page Content -->
    <div class="mt-5 container-md ">
        <div class="text-center" style="font-size: 18px;"><b><i class="fas fa-file-alt"></i>&nbsp;&nbsp;รายละเอียดข้อมูลช่างภาพ</b></div>
        <div class="mt-3 col-md-10 container-fluid ">
            <div class="row ">
                <div class="col-8">
                    <div class="text-start mt-1" style="font-size: 18px;"><b>ข้อมูลส่วนตัว</b></div>
                    <div class="col-12">
                        <div class="row mt-3">
                            <div class="col-2">
                                <label for="prefi" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px;font-size: 13px;"> คำนำหน้า</span>
                                </label>
                                <select class="form-select border-1 mt-1">
                                <option value="นาย">นาย</option>
                                    <option value="นางสาว">นางสาว</option>
                                    <option value="นาง">นาง</option>
                                </select>
                            </div>
                            <div class="col-5">
                                <label for="name" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px;font-size: 13px;">ชื่อ</span>
                                </label>
                                <input type="text" name="name" class="form-control mt-1" placeholder="กรุณากรอกชื่อ">
                            </div>
                            <div class="col-5">
                                <label for="surname" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; font-size: 13px;">นามสกุล</span>
                                </label>
                                <input type="text" name="surname" class="form-control" placeholder="กรุณากรอกนามสกุล">
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
                                <input type="text" name="address" class="form-control mt-1" placeholder="กรุณากรอกที่อยู่" style="resize: none;">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="district" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px; font-size: 13px;">อำเภอ</span>
                                </label>
                                <input type="text" name="district" class="form-control mt-1" placeholder="xxxx" style="resize: none;">
                            </div>
                            <div class="col-md-4">
                                <label for="province" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px; font-size: 13px;">จังหวัด</span>
                                </label>
                                <input type="text" name="province" class="form-control mt-1" placeholder="xxxx" style="resize: none;">
                            </div>
                            <div class="col-md-4">
                                <label for="zipcode" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px; font-size: 13px;">ไปรษณีย์</span>
                                </label>
                                <input type="text" name="zipcode" class="form-control mt-1" placeholder="xxxx" style="resize: none;">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="phone" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px; font-size: 13px;">เบอร์โทรศัพท์</span>
                                </label>
                                <input type="text" name="phone" class="form-control mt-1" placeholder="กรุณากรอกเบอร์โทรศัพท์" style="resize: none;">
                            </div>
                            <div class="col-md-4">
                                <label for="email" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px; font-size: 13px;">อีเมล</span>
                                </label>
                                <input type="text" name="email" class="form-control mt-1" placeholder="กรุณากรอกอีเมล" style="resize: none;">
                            </div>
                            <div class="col-md-4">
                                <label for="password" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px; font-size: 13px;">รหัสผ่าน</span>
                                </label>
                                <input type="password" name="password" class="form-control mt-1" placeholder="กรุณากรอกรหัสผ่าน" style="resize: none;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="d-flex justify-content-center align-items-center md">
                        <div class="circle">
                            <img src="../img/dev3.jpg" alt="Your Image">
                        </div>
                    </div>
                    <div class="align-items-center justify-content-center d-flex">
                        <div class="">
                            <div>
                                <label for="photo" style="font-weight: bold; display: flex; align-items: center;">
                                    <span style="color: black; margin-right: 5px; font-size: 13px;">รูปภาพโปรไฟล์</span>
                                    <span style="color: red;">*</span>
                                </label>
                            </div>
                            <input type="file" name="photo" name="profileImage" class="form-control">
                        </div>
                    </div>
                    <div class="mt-2">
                        <label for="prefix" style="font-weight: bold; display: flex; align-items: center;">
                            <span style="color: black; margin-right: 5px;font-size: 13px;"> คำนำหน้า</span>
                        </label>
                        <select class="form-select border-1 mt-1">
                            <option value="1">มีสิทธิ์การเข้าใช้งาน</option>
                            <option value="2">ไม่มีสิทธิ์การเข้าใช้งาน</option>
                        </select>
                    </div>
                </div>
                <hr class="mt-4">
                <div class="row justify-content-center">
                    <div class="col-md-5 mt-0">
                        <div class="text-start mt-1" style="font-size: 18px;"><b>ข้อมูลเกี่ยวกับผลงาน</b></div>
                        <div class="mt-3">
                            <label for="portfolio" style="font-weight: bold; display: flex; align-items: center;">
                                <span style="color: black; margin-right: 5px; font-size: 13px;">ไฟล์แฟ้มสะสมผลงาน</span>
                            </label>
                            <input type="text" name="portfolio" class="form-control mt-1" placeholder="กรุณากรอกไฟล์แฟ้มสะสมผลงาน" style="resize: none;">
                        </div>
                        <div class="mt-2">
                            <label for="worked" style="font-weight: bold; display: flex; align-items: center;">
                                <span style="color: black; margin-right: 5px; font-size: 13px;">ประเภทงานที่รับ</span>
                            </label>
                            <input type="text" name="worked" class="form-control mt-1" placeholder="กรุณากรอกประเภทงานที่รับ" style="resize: none;">
                        </div>
                        <div class="mt-2">
                            <label for="Price" style="font-weight: bold; display: flex; align-items: center;">
                                <span style="color: black; margin-right: 5px; font-size: 13px;">ช่วงราคาที่รับงาน</span>
                            </label>
                            <input type="password" name="Price" class="form-control mt-1" placeholder="กรุณากรอกช่วงราคาที่รับงาน" style="resize: none;">
                        </div>
                        <div class="mt-2">
                            <label for="scope" style="font-weight: bold; display: flex; align-items: center;">
                                <span style="color: black; margin-right: 5px; font-size: 13px;">ขอบเขตพื้นที่ที่รับงาน</span>
                            </label>
                            <input type="password" name="scope" class="form-control mt-1" placeholder="กรุณากรอกขอบเขตพื้นที่ที่รับงาน" style="resize: none;">
                        </div>
                    </div>
                    <div class="col-md-2 mt-0 col-divider justify-content-center">
                    </div>
                    <div class="col-md-5 mt-0">
                        <div class="text-start mt-1" style="font-size: 18px;"><b>ข้อมูลรับชำระเงิน</b></div>
                        <div class="mt-3">
                            <label for="bank" style="font-weight: bold; display: flex; align-items: center;">
                                <span style="color: black; margin-right: 5px; font-size: 13px;">ชื่อธนาคาร</span>
                            </label>
                            <input type="text" name="bank" class="form-control mt-1" placeholder="กรุณากรอกชื่อธนาคาร" style="resize: none;">
                        </div>
                        <div class="mt-2">
                            <label for="accountname" style="font-weight: bold; display: flex; align-items: center;">
                                <span style="color: black; margin-right: 5px; font-size: 13px;">ชื่อบัญชี</span>
                            </label>
                            <input type="text" name="accountname" class="form-control mt-1" placeholder="กรุณากรอกชื่อบัญชี" style="resize: none;">
                        </div>
                        <div class="mt-2">
                            <label for="accountnumber" style="font-weight: bold; display: flex; align-items: center;">
                                <span style="color: black; margin-right: 5px; font-size: 13px;">เลขที่บัญชี</span>
                            </label>
                            <input type="password" name="accountnumber" class="form-control mt-1" placeholder="กรุณากรอกเลขที่บัญชี" style="resize: none;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center mt-5 mb-5">
                <div class="col-md-12 text-center">
                    <!-- ตำแหน่งสำหรับปุ่ม "ย้อนกลับ" -->
                    <button onclick="window.history.back();" class="btn btn-danger me-4" style="width: 150px; height:45px;">ย้อนกลับ</button>
                    <!-- ตำแหน่งสำหรับปุ่ม "บันทึกการแก้ไข" -->
                    <button id="saveButton" class="btn btn-primary" style="width: 150px; height:45px;">บันทึกการแก้ไข</button>
                </div>
            </div>
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

    <!-- Template Javascript -->
    <script src="../js/main.js"></script>
</body>

</html>
