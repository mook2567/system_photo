<?php
session_start();
include '../config_db.php';

// SQL query to count rows from multiple tables
$sql = "SELECT 'information' AS table_name, COUNT(*) AS row_count FROM information
        UNION ALL
        SELECT 'admin' AS table_name, COUNT(*) AS row_count FROM admin
        UNION ALL
        SELECT 'customer' AS table_name, COUNT(*) AS row_count FROM customer
        UNION ALL
        SELECT 'photographer' AS table_name, COUNT(*) AS row_count FROM photographer
        UNION ALL
        SELECT 'type' AS table_name, COUNT(*) AS row_count FROM type";

// Execute SQL query
$result = $conn->query($sql);

$sqlInformation = "SELECT * FROM `information`";
$resultInformation = $conn->query($sqlInformation);

if ($resultInformation->num_rows > 0) {
    $rowInformation = $resultInformation->fetch_assoc();
}

// Check if query executed successfully
if ($result) {
    // Fetch associative array
    $row_count_data = $result->fetch_all(MYSQLI_ASSOC);
} else {
    echo "Error executing query: " . $conn->error;
}

// Close database connection
$conn->close();
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
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-0 px-4" style="height: 70px;">
        <a href="index.html" class="navbar-brand d-flex align-items-center text-center">
            <img class="img-fluid" src="../img/logo/<?php echo isset($rowInformation['information_icon']) ? $rowInformation['information_icon'] : ''; ?>" style="height: 30px;">
        </a>
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon text-primary"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto f">
                <a href="index.php" class="nav-item nav-link ">หน้าหลัก</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle bg-dark active active" data-bs-toggle="dropdown">ข้อมูลพื้นฐาน</a>
                    <div class="dropdown-menu rounded-0 m-0">
                        <a href="manage.php" class="dropdown-item active">ข้อมูลพื้นฐาน</a>
                        <a href="manageWeb.php" class="dropdown-item">ข้อมูลระบบ</a>
                        <a href="manageAdmin.php" class="dropdown-item">ข้อมูลผู้ดูแลระบบ</a>
                        <a href="manageCustomer.php" class="dropdown-item">ข้อมูลลูกค้า</a>
                        <a href="managePhotographer.php" class="dropdown-item">ข้อมูลช่างภาพ</a>
                        <a href="manageType.php" class="dropdown-item">ข้อมูลประเภทงาน</a>
                    </div>
                </div>
                <a href="approvMember.php" class="nav-item nav-link ">อนุมัติสมาชิก</a>
                <!-- <a href="report.php" class="nav-item nav-link ">รายงาน</a> -->
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle bg-dark" data-bs-toggle="dropdown">โปรไฟล์</a>
                    <div class="dropdown-menu rounded-0 m-0">
                        <!-- <a href="profile.php" class="dropdown-item">โปรไฟล์</a> -->

                        <a href="../index.php" class="dropdown-item">ออกจากระบบ</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->
    <div class="container-fluid text-center">
        <div class="d-sm-flex align-items-center justify-content-between mt-5 mb-5"></div>
        <!-- Row for the first set of cards -->
        <div class="row align-items-center justify-content-center mt-5">
            <!-- Card for "ข้อมูลระบบ" -->
            <div class="col-xl-2 col-6 mt-2 mb-2 ms-2">
                <a href="manageWeb.php" class="text-decoration-none">
                    <div class="card text-white mb-3 shadow h-100 py-2 bg-primary" style="max-width: 18rem;">
                        <div class="card-header"><b><i class="fa fa-cogs"></i>&nbsp;&nbsp;ข้อมูลระบบ</b></div>
                        <div class="card-body">
                            <h3 class="text-white">
                                <?php echo $row_count_data[0]['row_count']; ?> <!-- แสดงจำนวนแถวของตาราง information -->
                            </h3>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card for "ข้อมูลผู้ดูแลระบบ" -->
            <div class="col-xl-2 col-6 mt-2 mb-2 ms-2">
                <a href="manageAdmin.php" class="text-decoration-none">
                    <div class="card text-white mb-3 shadow h-100 py-2 bg-primary" style="max-width: 18rem;">
                        <div class="card-header"><b><i class="fa fa-user-cog"></i>&nbsp;&nbsp;ข้อมูลผู้ดูแลระบบ</b></div>
                        <div class="card-body">
                            <h3 class="text-white">
                                <?php echo $row_count_data[1]['row_count']; ?> <!-- แสดงจำนวนแถวของตาราง admin -->
                            </h3>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card for "ข้อมูลลูกค้า" -->
            <div class="col-xl-2 col-6 mt-2 mb-2 ms-2">
                <a href="manageCustomer.php" class="text-decoration-none">
                    <div class="card text-white mb-3 shadow h-100 py-2 bg-primary" style="max-width: 18rem;">
                        <div class="card-header"><b><i class="fa fa-user"></i>&nbsp;&nbsp;ข้อมูลลูกค้า</b></div>
                        <div class="card-body">
                            <h3 class="text-white">
                                <?php echo $row_count_data[2]['row_count']; ?> <!-- แสดงจำนวนแถวของตาราง customer -->
                            </h3>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card for "ข้อมูลช่างภาพ" -->
            <div class="col-xl-2 col-6 mt-2 mb-2 ms-2">
                <a href="managePhotographer.php" class="text-decoration-none">
                    <div class="card text-white mb-3 shadow h-100 py-2 bg-primary" style="max-width: 18rem;">
                        <div class="card-header"><b><i class="fa fa-camera"></i>&nbsp;&nbsp;ข้อมูลช่างภาพ</b></div>
                        <div class="card-body">
                            <h3 class="text-white">
                                <?php echo $row_count_data[3]['row_count']; ?> <!-- แสดงจำนวนแถวของตาราง photographer -->
                            </h3>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card for "ข้อมูลประเภทงาน" -->
            <div class="col-xl-2 col-6 mt-2 mb-2 ms-2">
                <a href="manageType.php" class="text-decoration-none">
                    <div class="card text-white mb-3 shadow h-100 py-2 bg-primary" style="max-width: 18rem;">
                        <div class="card-header"><b><i class="fa fa-briefcase"></i>&nbsp;&nbsp;ข้อมูลประเภทงาน</b></div>
                        <div class="card-body">
                            <h3 class="text-white">
                                <?php echo $row_count_data[4]['row_count']; ?> 
                            </h3>
                        </div>
                    </div>
                </a>
            </div>


            <div class="container-fluid bg-dark text-white-50 footer wow fadeIn fixed-bottom" data-wow-delay="0.1s">
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