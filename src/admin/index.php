<?php
session_start();
include '../config_db.php';

$sql = "SELECT * FROM `information`";
$result = $conn->query($sql);
$row = $result->fetch_assoc();


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

    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.6.0/dist/jspdf.plugin.autotable.min.js"></script>

    <style>
        body {
            font-family: 'Athiti', sans-serif;
            overflow-x: hidden;
        }

        .f {
            font-family: 'Athiti', sans-serif;
        }

        .nav-bar {
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .navbar {
            padding: 0;

        }

        p {
            font-family: 'Athiti', sans-serif;
        }

        h1 {
            font-family: 'Athiti', sans-serif;
        }

        h2 {
            font-family: 'Athiti', sans-serif;
        }

        h3 {
            font-family: 'Athiti', sans-serif;
        }

        h4 {
            font-family: 'Athiti', sans-serif;
        }

        h5 {
            font-family: 'Athiti', sans-serif;
        }

        @media (max-width: 768px) {

            .col-md-3,
            .col-md-6,
            .col-md-2 {
                width: 100% !important;
            }

            .mb-3-md {
                margin-bottom: 1rem !important;
            }

            .me-2-md {
                margin-right: 0.5rem !important;
            }

            .ms-2-md {
                margin-left: 0.5rem !important;
            }

            .py-3-md {
                padding-top: 1rem !important;
                padding-bottom: 1rem !important;
            }

            .px-4-md {
                padding-left: 1.5rem !important;
                padding-right: 1.5rem !important;
            }

            .text-center-md {
                text-align: center !important;
            }

            .text-start-md {
                text-align: start !important;
            }

            .text-end-md {
                text-align: end !important;
            }

            .justify-content-center-md {
                justify-content: center !important;
            }

            .align-items-center-md {
                align-items: center !important;
            }
        }

        .nav-bar {
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .navbar {
            padding: 0;

        }



        .container {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .bgIndex {
            background-image: url('../img/bgIndex1.png');
            background-attachment: fixed;
            background-size: cover;
            /* เพิ่มการปรับแต่งในการขยับภาพตามต้องการ */
        }

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

        body {
            font-family: 'Athiti', sans-serif;
            background: #f0f0f0;
            /* Change background color to gray */
            overflow-x: hidden;
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
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-0 px-4" style="height: 70px;">
        <a href="index.php" class="navbar-brand ms-5 d-flex align-items-center text-center">
            <img class="img-fluid" src="../img/logo/<?php echo $row['information_icon']; ?>" style="height: 30px;">
        </a>
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon text-primary"></span>
        </button>
        <div class="collapse navbar-collapse me-5" id="navbarCollapse">
            <div class="navbar-nav ms-auto f">
                <a href="index.php" class="nav-item nav-link active">หน้าหลัก</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle bg-dark " data-bs-toggle="dropdown">ข้อมูลพื้นฐาน</a>
                    <div class="dropdown-menu rounded-0 m-0">
                        <a href="manage.php" class="dropdown-item ">ข้อมูลพื้นฐาน</a>
                        <a href="manageWeb.php" class="dropdown-item">ข้อมูลระบบ</a>
                        <a href="manageAdmin.php" class="dropdown-item">ข้อมูลผู้ดูแลระบบ</a>
                        <a href="manageCustomer.php" class="dropdown-item">ข้อมูลลูกค้า</a>
                        <a href="managePhotographer.php" class="dropdown-item">ข้อมูลช่างภาพ</a>
                        <a href="manageType.php" class="dropdown-item">ข้อมูลประเภทงาน</a>
                    </div>
                </div>
                <a href="approvMember.php" class="nav-item nav-link ">อนุมัติสมาชิก</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle bg-dark" data-bs-toggle="dropdown">รายงาน</a>
                    <div class="dropdown-menu rounded-0 m-0">
                        <a href="reportUser.php" class="dropdown-item">รายงานข้อมูลผู้ใช้งานระบบ</a>
                        <a href="reportCustomer.php" class="dropdown-item ">รายงานข้อมูลลูกค้า</a>
                        <a href="reportPhotographer.php" class="dropdown-item">รายงานข้อมูลช่างภาพ</a>
                        <a href="reportType.php" class="dropdown-item">รายงานข้อมูลประเภทงาน</a>
                    </div>
                </div>
                <a href="../logout.php" class="nav-item nav-link">ออกจากระบบ</a>
                <!-- <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle bg-dark" data-bs-toggle="dropdown">โปรไฟล์</a>
                    <div class="dropdown-menu rounded-0 m-0">
                        <a href="profile.php" class="dropdown-item">โปรไฟล์</a>
                        <a href="../logout.php" class="dropdown-item">ออกจากระบบ</a>
                    </div>
                </div> -->
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Sidebar Card Start -->
    <aside class="sidebar">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Dashboard</h5>
                <p class="text-dark">ยินดีต้อนรับสู่แดชบอร์ด <?php echo $row['information_name']; ?> คุณสามารถดูภาพรวมประสิทธิภาพของระบบและสถิติผู้ใช้ได้ที่นี่</p>
            </div>
        </div>
    </aside>
    <!-- Sidebar Card End gg-->

    <div class="container-fluid text-center">
        <div class="d-sm-flex align-items-center justify-content-between mt-2 mb-2"></div>
        <h5 class="card-title">เลือกเมนูเพื่อจัดทำรายงาน</h5>
        <!-- Row for the first set of cards -->
        <div class="row align-items-center justify-content-center mt-3">

            <div class="col-xl-2 col-6 mt-2 mb-2 ms-2">
                <a href="reportUser.php" class="text-decoration-none">
                    <div class="card text-white mb-3 shadow h-100 py-2 bg-primary" style="max-width: 18rem;">
                        <div class="card-body"><b>รายงานข้อมูลผู้ใช้งานระบบ</b>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-2 col-6 mt-2 mb-2 ms-2">
                <a href="reportCustomer.php" class="text-decoration-none">
                    <div class="card text-white mb-3 shadow h-100 py-2 bg-primary" style="max-width: 18rem;">
                        <div class="card-body"><b>รายงานข้อมูลลูกค้า</b>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-2 col-6 mt-2 mb-2 ms-2">
                <a href="reportPhotographer.php" class="text-decoration-none">
                    <div class="card text-white mb-3 shadow h-100 py-2 bg-primary" style="max-width: 18rem;">
                        <div class="card-body"><b>รายงานข้อมูลช่างภาพ</b>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-2 col-6 mt-2 mb-2 ms-2">
                <a href="reportType.php" class="text-decoration-none">
                    <div class="card text-white mb-3 shadow h-100 py-2 bg-primary" style="max-width: 18rem;">
                        <div class="card-body"><b>รายงานข้อมูลประเภทงาน</b>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!-- Chart Section Start -->
    <section class="container mt-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="card border">
                    <div class="card-body">
                        <h5 class="card-title">แผนภูมิแท่งแสดงจำนวนสมาชิกผู้ใช้งาน</h5>
                        <div class="d-flex justify-content-center">
                            <div class="col-8">
                                <canvas id="overviewChart" width="800" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Fetch data from the PHP script
        fetch('grap1.php')
            .then(response => response.json())
            .then(data => {
                // Make sure data is in the expected format
                const malePhotographersCount = data.malePhotographersCount || 0;
                const femalePhotographersCount = data.femalePhotographersCount || 0;
                const maleCustomersCount = data.maleCustomersCount || 0;
                const femaleCustomersCount = data.femaleCustomersCount || 0;

                // Create the bar chart
                const ctx = document.getElementById('overviewChart').getContext('2d');
                const overviewChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [''],
                        datasets: [{
                                label: 'ช่างภาพ (ชาย)',
                                data: [malePhotographersCount],
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'ช่างภาพ (หญิง)',
                                data: [femalePhotographersCount],
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'ลูกค้า (ชาย)',
                                data: [maleCustomersCount],
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'ลูกค้า (หญิง)',
                                data: [femaleCustomersCount],
                                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                borderColor: 'rgba(153, 102, 255, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching data:', error));
    </script>

    <div class="container mt-5">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">กราฟจำนวนผู้ใช้งานระบบ</h5>
                    <div class="d-flex justify-content-center">
                        <div class="col-8">
                            <canvas id="overviewChart1" width="800" height="400"></canvas>
                        </div>
                        <script>
        // Fetch data from the PHP script
        fetch('grap2.php')
            .then(response => response.json())
            .then(data => {
                // Make sure data is in the expected format
                const malePhotographersCount = data.malePhotographersCount || 0;
                const femalePhotographersCount = data.femalePhotographersCount || 0;
                const maleCustomersCount = data.maleCustomersCount || 0;
                const femaleCustomersCount = data.femaleCustomersCount || 0;

                // Create the bar chart
                const ctx = document.getElementById('overviewChart1').getContext('2d');
                const overviewChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [''],
                        datasets: [{
                                label: 'ช่างภาพ (ชาย)',
                                data: [malePhotographersCount],
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'ช่างภาพ (หญิง)',
                                data: [femalePhotographersCount],
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'ลูกค้า (ชาย)',
                                data: [maleCustomersCount],
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'ลูกค้า (หญิง)',
                                data: [femaleCustomersCount],
                                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                borderColor: 'rgba(153, 102, 255, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching data:', error));
    </script>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
    <!-- Chart Section End -->
    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white-50 footer wow fadeIn mt-5">
        <div class="copyright">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; <a class="border-bottom" href="#">2024 Photo Match</a>, All Right Reserved.
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

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Template Javascript -->
    <script src="../js/main.js"></script>

</body>

</html>