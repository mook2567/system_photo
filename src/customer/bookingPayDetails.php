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
    <div class="bg-dark">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark">
            <a href="index.php" class="navbar-brand d-flex align-items-center text-center">
                <img class="img-fluid ms-5" src="../img/photoLogo.png" style="height: 60px;">
            </a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon text-primary"></span>
            </button>
            <div class="collapse navbar-collapse me-5" id="navbarCollapse">
                <div class="navbar-nav ms-auto f">
                    <a href="search.php" class="nav-item nav-link">ค้นหาช่างภาพ</a>
                    <a href="index.php" class="nav-item nav-link">หน้าหลัก</a>
                    <a href="workings.php" class="nav-item nav-link">ผลงานช่างภาพ</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown">รายการจองคิวช่างภาพ</a>
                        <div class="dropdown-menu rounded-0 m-0">
                            <a href="bookingLists.php" class="dropdown-item active">รายการจองคิวทั้งหมด</a>
                            <a href="payLists.php" class="dropdown-item ">รายการจองคิวที่ต้องชำระเงิน/ค่ามัดจำ</a>
                            <a href="reviewLists.php" class="dropdown-item">รายการจองคิวที่ต้องรีวิว</a>
                            <a href="bookingFinishedLists.php" class="dropdown-item">รายการจองคิวที่เสร็จสิ้นแล้ว</a>
                            <a href="bookingRejectedLists.php" class="dropdown-item">รายการจองคิวที่ถูกปฏิเสธ</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">โปรไฟล์</a>
                        <div class="dropdown-menu rounded-0 m-0">
                            <a href="profile.php" class="dropdown-item">โปรไฟล์</a>
                            <a href="about.php" class="dropdown-item">เกี่ยวกับ</a>
                            <a href="contact.php" class="dropdown-item">ติดต่อ</a>
                            <a href="../index.php" class="dropdown-item">ออกจากระบบ</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->
    <div class="mt-5 container-md">
        <div class="text-center" style="font-size: 18px;"><b><i class="fas fa-clipboard-list"></i>&nbsp;&nbsp;รายละเอียดการจองคิว</b></div>
        <div class="mt-3 col-md-8 container-fluid">
            <div class="col-12">
                <div class="row mt-2">
                    <div class="col-2">
                        <label for="prefix" style="font-weight: bold; display: flex; align-items: center;">
                            <span style="color: black; margin-right: 5px;font-size: 13px; "> คำนำหน้า</span>
                        </label>
                        <select class="form-select border-1 mt-1">
                            <option value="1">นาย</option>
                            <option value="2">นางสาว</option>
                            <option value="3">นาง</option>
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
                        <input type="text" name="surname" class="form-control mt-1" placeholder="กรุณากรอกนามสกุล">
                    </div>
                </div>
            </div>

            <div class="col-12 mt-3">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <label for="booking-start-date" style="font-weight: bold; display: flex; align-items: center;">
                            <span style="color: black; margin-right: 5px;font-size: 13px;">วันที่เริ่มจอง</span>
                        </label>
                        <input type="date" name="booking-start-date" class="form-control mt-1" style="resize: none;">
                    </div>
                    <div class="col-md-2 text-center">
                        <label for="booking-start-time" style="font-weight: bold; display: flex; align-items: center;">
                            <span style="color: black; margin-right: 5px;font-size: 13px;">เวลาเริ่มงาน</span>
                        </label>
                        <input type="time" name="booking-start-time" class="form-control mt-1" style="resize: none;">
                    </div>

                    <div class="col-md-4 text-center">
                        <label for="booking-end-date" style="font-weight: bold; display: flex; align-items: center;">
                            <span style="color: black; margin-right: 5px;font-size: 13px;">วันที่สิ้นสุดการจอง</span>
                        </label>
                        <input type="date" name="booking-end-date" class="form-control mt-1" style="resize: none;">
                    </div>
                    <div class="col-md-2 text-center">
                        <label for="booking-end-time" style="font-weight: bold; display: flex; align-items: center;">
                            <span style="color: black; margin-right: 5px;font-size: 13px;">เวลาสิ้นสุด</span>
                        </label>
                        <input type="time" name="booking-end-time" class="form-control mt-1" style="resize: none;">
                    </div>
                </div>
            </div>

            <div class="col-12 mt-3">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <label for="location" style="font-weight: bold; display: flex; align-items: center;">
                            <span style="color: black; margin-right: 5px;font-size: 13px;">สถานที่</span>

                        </label>
                        <input type="text" name="location" class="form-control mt-1" placeholder="กรุณากรอกสถานที่" style="resize: none;">
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3 text-center">
                <label for="Information_caption" style="font-weight: bold; display: flex; align-items: center;">
                    <span style="color: black; margin-right: 5px;font-size: 13px;">คำอธิบาย</span>
                </label>
                <textarea name="Information_caption" class="form-control mt-1" placeholder="กรุณากรอกคำอธิบาย" style="resize: none; height: 100px;"></textarea>
            </div>
            <div class="col-12">
                <div class="row mt-3">
                    <div class="col-5">
                        <label for="mobile" style="font-weight: bold; display: flex; align-items: center;">
                            <span style="color: black; margin-right: 5px;font-size: 13px;">เบอร์โทรศัพท์มือถือ</span>
                        </label>
                        <input type="text" name="mobile" class="form-control mt-1" placeholder="กรุณากรอกเบอร์โทร" style="resize: none;">
                    </div>
                    <div class="col-5 text-center">
                        <label for="email" style="font-weight: bold; display: flex; align-items: center;">
                            <span style="color: black; margin-right: 5px;font-size: 13px;">อีเมล</span>
                        </label>
                        <input type="email" name="email" class="form-control mt-1" placeholder="กรุณากรอกอีเมล" style="resize: none;">
                    </div>
                    <div class="col-2">
                        <label for="date-saved" style="font-weight: bold; display: flex; align-items: center;">
                            <span style="color: black; margin-right: 5px;font-size: 13px;">วันที่บันทึก</span>
                        </label>
                        <input type="date" name="date-saved" class="form-control mt-1" style="resize: none;">
                    </div>
                </div>
            </div>

            <div class="col-12 mt-3 row">
                <div class="col-md-6 text-center">
                    <label for="rate" style="font-weight: bold; display: flex; align-items: center;">
                        <span style="color: black; margin-right: 5px;font-size: 13px;">ราคา</span>
                    </label>
                    <input type="text" name="rate" class="form-control mt-1" placeholder="กรุณากรอกราคา" style="resize: none;">
                </div>
                <div class="col-2 mt-3 ">
                    <button type="submit" class="btn btn-warning btn-block mt-2" style="width: 150px;" onclick="window.location.href='pay.php'">ชำระ</button>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-5">
            <div class="col-md-12 text-center">
                <!-- ตำแหน่งสำหรับปุ่ม "ย้อนกลับ" -->
                <button type="button" class="btn btn-danger" style="width: 150px; height:45px;" onclick="window.history.back()">ย้อนกลับ</button>
                <!-- ตำแหน่งสำหรับปุ่ม "บันทึกการแก้ไข" -->
                <!-- <button id="saveButton" class="btn btn-primary">ลบข้อมูลการจอง</button> -->
            </div>
        </div>
    </div>
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