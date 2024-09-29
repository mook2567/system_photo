<?php
session_start();
include '../config_db.php';

$sql = "SELECT * FROM `information`";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$information_name = $row['information_name'];
$information_caption = $row['information_caption'];
$row['information_icon'];
// สร้างพาธของไฟล์ภาพ
$image_path = '../img/logo/' . $row['information_icon'];

if (file_exists($image_path)) {
    $image_data = base64_encode(file_get_contents($image_path));
    $image_type = pathinfo($image_path, PATHINFO_EXTENSION);
    $image_base64 = 'data:image/' . $image_type . ';base64,' . $image_data;
} else {
    $image_base64 = ''; // Handle case if the image doesn't exist
}




$sql1 = "SELECT SUM(row_count) AS total_count
        FROM (
            SELECT COUNT(*) AS row_count FROM admin WHERE admin_license = 1
            UNION ALL
            SELECT COUNT(*) AS row_count FROM customer WHERE cus_license = 1
            UNION ALL
            SELECT COUNT(*) AS row_count FROM photographer WHERE photographer_license = 1
        ) AS all_counts";
$result1 = $conn->query($sql1);

if ($result1) {
    $row_count_data1 = $result1->fetch_assoc();
    htmlspecialchars($row_count_data1['total_count']);
} else {
    "Error: " . $conn->error;
}

$sql2 = "SELECT 'admin' AS table_name, COUNT(*) AS row_count FROM admin WHERE admin_license = 1
        UNION ALL
        SELECT 'customer' AS table_name, COUNT(*) AS row_count FROM customer WHERE cus_license = 1
        UNION ALL
        SELECT 'photographer' AS table_name, COUNT(*) AS row_count FROM photographer WHERE photographer_license = 1";
$result2 = $conn->query($sql2);
if ($result2) {
    $row_count_data2 = $result2->fetch_all(MYSQLI_ASSOC);
} else {
    "Error executing query: " . $conn->error;
}

$sql3 = "SELECT COUNT(type_id) AS total_count FROM type";
$result3 = $conn->query($sql3);
if ($result3) {
    $row_count_data3 = $result3->fetch_all(MYSQLI_ASSOC);
} else {
    "Error executing query: " . $conn->error;
}


$currentYear = date("Y"); // Get the current year

$sql4 = "SELECT MONTH(booking_date) AS booking_month, COUNT(*) AS total_count
         FROM booking
         WHERE YEAR(booking_date) = $currentYear
         GROUP BY MONTH(booking_date)
         ORDER BY booking_month";

$result4 = $conn->query($sql4);

if ($result4) {
    $row_count_data4 = $result4->fetch_all(MYSQLI_ASSOC);

    // To calculate the total count of bookings
    $totalBookings = array_sum(array_column($row_count_data4, 'total_count'));

    // Display total bookings
    "Total bookings this year: " . $totalBookings . " times";
} else {
    "Error executing query: " . $conn->error;
}


$sql5 = "SELECT t.type_work, COUNT(tow.type_id) AS total_count
         FROM type_of_work tow
         JOIN type t ON t.type_id = tow.type_id
         GROUP BY t.type_work
         ORDER BY total_count DESC
         LIMIT 3
         ";
$result5 = $conn->query($sql5);
if ($result5) {
    $row_count_data5 = $result5->fetch_all(MYSQLI_ASSOC);
} else {
    "Error executing query: " . $conn->error;
}
$sql6 = "SELECT t.type_work, COUNT(tow.type_of_work_id) AS total_count
        FROM type_of_work tow
        JOIN type t ON t.type_id = tow.type_id
        JOIN booking b ON b.type_of_work_id = tow.type_of_work_id
        GROUP BY t.type_work
        ORDER BY total_count DESC
        LIMIT 3
        ";
$result6 = $conn->query($sql6);
if ($result6) {
    $row_count_data6 = $result6->fetch_all(MYSQLI_ASSOC);
} else {
    "Error executing query: " . $conn->error;
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

    <!-- Load external libraries first -->
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.6.0/dist/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.0.0-rc.5/html2canvas.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

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
                        <!-- <a href="manage.php" class="dropdown-item ">ข้อมูลพื้นฐาน</a> -->
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


    <center>
        <div id="contentToConvert">
            <div class="col-11 mt-3">
                <div class="col-12">
                    <div class="row">
                        <div class="col-10">
                            <div class="card shadow">
                                <div class="card-body">
                                    <h3 class="card-title text-center mt-4 mb-4">แผนภูมิแท่งแสดงจำนวนสมาชิกผู้ใช้งาน</h3>
                                    <div class="d-flex justify-content-center">
                                        <div class="col-10">
                                            <canvas id="overviewChart1" width="400" height="400" style="max-height: 455px; height: auto;"></canvas>
                                            <script>
                                                fetch('grap1.php')
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        const malePhotographersCount = data.malePhotographersCount || 0;
                                                        const femalePhotographersCount = data.femalePhotographersCount || 0;
                                                        const maleCustomersCount = data.maleCustomersCount || 0;
                                                        const femaleCustomersCount = data.femaleCustomersCount || 0;
                                                        const maleAdminCount = data.maleAdminCount || 0;
                                                        const femaleAdminCount = data.femaleAdminCount || 0;

                                                        const ctx = document.getElementById('overviewChart1').getContext('2d');
                                                        const overviewChart = new Chart(ctx, {
                                                            type: 'bar',
                                                            data: {
                                                                labels: ['ช่างภาพ', 'ลูกค้า', 'ผู้ดูแลระบบ'], // Label for user types
                                                                datasets: [{
                                                                        label: 'ชาย',
                                                                        data: [malePhotographersCount, maleCustomersCount, maleAdminCount],
                                                                        backgroundColor: 'rgba(255, 159, 64, 0.2)',
                                                                        borderColor: 'rgba(255, 159, 64, 1)',
                                                                        borderWidth: 1
                                                                    },
                                                                    {
                                                                        label: 'หญิง',
                                                                        data: [femalePhotographersCount, femaleCustomersCount, femaleAdminCount],
                                                                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                                                        borderColor: 'rgba(255, 99, 132, 1)',
                                                                        borderWidth: 1
                                                                    }
                                                                ]
                                                            },
                                                            options: {
                                                                plugins: {
                                                                    legend: {
                                                                        position: 'right',
                                                                        labels: {
                                                                            font: {
                                                                                size: 20 // Set font size for legend
                                                                            }
                                                                        }
                                                                    },
                                                                    tooltip: {
                                                                        callbacks: {
                                                                            label: function(tooltipItem) {
                                                                                const value = tooltipItem.raw;
                                                                                return tooltipItem.label + ': ' + value;
                                                                            }
                                                                        }
                                                                    }
                                                                },
                                                                scales: {
                                                                    y: {
                                                                        beginAtZero: true,
                                                                        title: { // Add title for the Y-axis
                                                                            display: true,
                                                                            text: 'จำนวนสมาชิค (คน)', // Y-axis label
                                                                            font: {
                                                                                size: 20 // Font size for the Y-axis label
                                                                            }
                                                                        },
                                                                        ticks: {
                                                                            stepSize: 1,
                                                                            font: {
                                                                                size: 20 // Set font size for Y axis ticks
                                                                            }
                                                                        }
                                                                    },
                                                                    x: {
                                                                        ticks: {
                                                                            font: {
                                                                                size: 20 // Set font size for X axis ticks
                                                                            }
                                                                        }
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

                        <div class="col-2">
                            <div class="card text-dark mb-4 shadow">
                                <div class="card-header text-center"><b>จำนวนผู้ใช้งานทั้งหมด</b></div>
                                <div class="card-body">
                                    <h3 class="mt-2 text-dark text-center">
                                        <?php echo $row_count_data1['total_count'] . ' คน'; ?>
                                    </h3>
                                </div>
                            </div>
                            <div class="card text-dark mb-4 shadow">
                                <div class="card-header text-center"><b>จำนวนช่างภาพ</b></div>
                                <div class="card-body">
                                    <h3 class="mt-2 text-dark text-center">
                                        <?php echo $row_count_data2[2]['row_count'] . ' คน'; ?>
                                    </h3>
                                </div>
                            </div>
                            <div class="card text-dark mb-4 shadow">
                                <div class="card-header text-center"><b>จำนวนลูกค้า</b></div>
                                <div class="card-body">
                                    <h3 class="mt-2 text-dark text-center">
                                        <?php echo $row_count_data2[1]['row_count'] . ' คน'; ?>
                                    </h3>
                                </div>
                            </div>
                            <div class="card text-dark shadow">
                                <div class="card-header text-center"><b>จำนวนผู้ดูแลระบบ</b></div>
                                <div class="card-body">
                                    <h3 class="mt-2 text-dark text-center">
                                        <?php echo $row_count_data2[0]['row_count'] . ' คน'; ?>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-4">
                    <div class="row">
                        <!-- Left Column with Chart -->
                        <div class="col-9">
                            <div class="card shadow" style="height: 580px;"> <!-- Set fixed height for the card -->
                                <div class="card-body">
                                    <h3 class="card-title mt-4 mb-4">กราฟแสดงข้อมูลสถานะการจองต่อเดือน (ใปี <?php echo date('Y'); ?>)</h3>
                                    <canvas id="overviewChart2" width="400" height="400" style="max-height: 400px; height: auto;"></canvas>
                                    <script>
                                        fetch('grap2.php')
                                            .then(response => response.json())
                                            .then(data => {
                                                // Create month labels
                                                const labels = data.map(item => {
                                                    const monthNames = [
                                                        'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน',
                                                        'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม',
                                                        'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
                                                    ];
                                                    return monthNames[item.booking_month - 1]; // Convert month number to name
                                                });

                                                // Extract totals for each status
                                                const totalCompleted = data.map(item => item.total_completed);
                                                const totalPending = data.map(item => item.total_pending);
                                                const totalCanceled = data.map(item => item.total_canceled);
                                                const totalReserved = data.map(item => item.total_reserved);

                                                const ctx = document.getElementById('overviewChart2').getContext('2d');
                                                const overviewChart = new Chart(ctx, {
                                                    type: 'line', // Set chart type to 'line'
                                                    data: {
                                                        labels: labels, // Use month names as labels
                                                        datasets: [{
                                                                label: 'การจองที่รอดำเนินการ', // Reserved bookings
                                                                data: totalReserved,
                                                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                                                borderColor: 'rgba(54, 162, 235, 1)',
                                                                borderWidth: 2,
                                                                fill: false,
                                                                tension: 0.4
                                                            },
                                                            {
                                                                label: 'การจองที่ยังไม่สำเร็จ', // Pending bookings
                                                                data: totalPending,
                                                                backgroundColor: 'rgba(255, 206, 86, 0.2)',
                                                                borderColor: 'rgba(255, 206, 86, 1)',
                                                                borderWidth: 2,
                                                                fill: false,
                                                                tension: 0.4
                                                            },
                                                            {
                                                                label: 'การจองที่ถูกยกเลิก', // Canceled bookings
                                                                data: totalCanceled,
                                                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                                                borderColor: 'rgba(255, 99, 132, 1)',
                                                                borderWidth: 2,
                                                                fill: false,
                                                                tension: 0.4
                                                            },
                                                            {
                                                                label: 'การจองที่เสร็จสิ้น', // Completed bookings
                                                                data: totalCompleted,
                                                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                                                borderColor: 'rgba(75, 192, 192, 1)',
                                                                borderWidth: 2,
                                                                fill: false,
                                                                tension: 0.4
                                                            }
                                                        ]
                                                    },
                                                    options: {
                                                        plugins: {
                                                            legend: {
                                                                labels: {
                                                                    font: {
                                                                        size: 20 // Set font size for legend
                                                                    }
                                                                }
                                                            }
                                                        },
                                                        scales: {
                                                            y: {
                                                                beginAtZero: true,
                                                                title: {
                                                                    display: true,
                                                                    text: 'จำนวนการจอง (ครั้ง)', // Y-axis label
                                                                    font: {
                                                                        size: 20 // Font size for the Y-axis label
                                                                    }
                                                                },
                                                                ticks: {
                                                                    font: {
                                                                        size: 20 // Set font size for Y axis ticks
                                                                    }
                                                                }
                                                            },
                                                            x: {
                                                                ticks: {
                                                                    font: {
                                                                        size: 20 // Set font size for X axis ticks
                                                                    }
                                                                }
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
                        <!-- Right Column with Cards -->
                        <div class="col-3">
                            <div class="card text-dark shadow" height=650px; style=" max-height: 650px; height: auto;">
                                <div class="card-header">
                                    <h4 class="mt-2">รวมจำนวนการจองต่อเดือน (ปี <?php echo date('Y'); ?>)</h4>
                                </div>
                                <div class="card-body" style="text-align:left;">
                                    <?php
                                    // Initialize an array with month names and total counts set to 0
                                    $months = [
                                        1 => 'เดือนมกราคม',
                                        2 => 'เดือนกุมภาพันธ์',
                                        3 => 'เดือนมีนาคม',
                                        4 => 'เดือนเมษายน',
                                        5 => 'เดือนพฤษภาคม',
                                        6 => 'เดือนมิถุนายน',
                                        7 => 'เดือนกรกฎาคม',
                                        8 => 'เดือนสิงหาคม',
                                        9 => 'เดือนกันยายน',
                                        10 => 'เดือนตุลาคม',
                                        11 => 'เดือนพฤศจิกายน',
                                        12 => 'เดือนธันวาคม'
                                    ];

                                    // Create an array to hold the total counts for each month
                                    $monthlyCounts = array_fill(1, 12, 0);

                                    // Populate the monthly counts from the query result
                                    if (!empty($row_count_data4)) {
                                        foreach ($row_count_data4 as $row) {
                                            $monthlyCounts[$row['booking_month']] = $row['total_count'];
                                        }
                                    }

                                    // Display the results
                                    ?>
                                    <ol>
                                        <?php foreach ($months as $monthNum => $monthName): ?>
                                            <li style="font-size: 20px; margin-bottom: 10px;"> <!-- Set the desired font size -->
                                                <b><?php echo htmlspecialchars($monthName); ?></b> - จำนวน: <?php echo htmlspecialchars($monthlyCounts[$monthNum]) . ' ครั้ง'; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-4">
                    <div class="row">
                        <div class="col-6">
                            <div class="row">
                                <!-- Customer Popular Work Types -->
                                <div class="col-6">
                                    <div class="card text-dark shadow" style="height: 210px;">
                                        <div class="card-header">
                                            <h4 class="mt-2">อันดับประเภทงานที่ลูกค้านิยมจ้าง</h4>
                                        </div>
                                        <div class="card-body" style="text-align:left; padding: 15px;">
                                            <?php if (!empty($row_count_data6)): ?>
                                                <ol>
                                                    <?php foreach ($row_count_data6 as $index => $row): ?>
                                                        <li style="font-size: 20px; margin-bottom: 10px;">
                                                            <b><?php echo htmlspecialchars($row['type_work']); ?></b> - จำนวน: <?php echo htmlspecialchars($row['total_count']) . ' ครั้ง'; ?>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ol>
                                            <?php else: ?>
                                                <p style="font-size: 16px; color: #888;">ไม่มีข้อมูลการจ้างงาน</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Photographer Popular Work Types -->
                                <div class="col-6">
                                    <div class="card text-dark shadow" style="height: 210px;">
                                        <div class="card-header">
                                            <h4 class="mt-2">อันดับประเภทงานที่ช่างภาพนิยมรับ</h4>
                                        </div>
                                        <div class="card-body" style="text-align:left;">
                                            <?php if (!empty($row_count_data5)): ?>
                                                <ol>
                                                    <?php foreach ($row_count_data5 as $index => $row): ?>
                                                        <li style="font-size: 20px; margin-bottom: 10px;">
                                                            <b><?php echo htmlspecialchars($row['type_work']); ?></b> - จำนวน: <?php echo htmlspecialchars($row['total_count']) . ' คน'; ?>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ol>
                                            <?php else: ?>
                                                <p>ไม่มีข้อมูลประเภทงาน</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card shadow mt-4" style="height: 560px;">
                                    <div>
                                        <h3 class="card-title mt-4 mb-4">แผนภูมิวงกลมแสดงข้อมูลประเภทงาน</h3>
                                        <!-- <h4 class="card-title mb-4">มีประเภทงาน <?php echo htmlspecialchars($row_count_data3[0]['total_count']); ?> ประเภท</h4> -->
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-center">
                                            <div class="col-9">
                                                <canvas id="overviewChart4" width="400" height="300" style="max-height: 400px; height: auto;"></canvas>
                                                <script>
                                                    fetch('grap4.php') // เรียกไฟล์ PHP ที่สร้าง JSON
                                                        .then(response => response.json())
                                                        .then(data => {
                                                            const labels = [];
                                                            const photographerWorkCounts = [];
                                                            const customerHiredCounts = [];

                                                            // เตรียมข้อมูลจาก JSON ที่ได้
                                                            data.forEach(item => {
                                                                labels.push(item.type_work);
                                                                photographerWorkCounts.push(item.photographer_count); // จำนวนงานที่ช่างภาพรับ
                                                                customerHiredCounts.push(item.customer_count); // จำนวนงานที่ลูกค้าจ้าง
                                                            });

                                                            const ctx = document.getElementById('overviewChart4').getContext('2d');
                                                            const overviewChart = new Chart(ctx, {
                                                                type: 'doughnut',
                                                                data: {
                                                                    labels: labels,
                                                                    datasets: [{
                                                                            label: 'ประเภทงานที่ช่างภาพรับ',
                                                                            data: photographerWorkCounts,
                                                                            backgroundColor: [
                                                                                'rgba(255, 99, 132, 0.2)', // สีสำหรับประเภทงานที่ช่างภาพรับ
                                                                                'rgba(54, 162, 235, 0.2)',
                                                                                'rgba(255, 206, 86, 0.2)',
                                                                                'rgba(75, 192, 192, 0.2)',
                                                                                'rgba(153, 102, 255, 0.2)',
                                                                                'rgba(255, 159, 64, 0.2)',
                                                                                'rgba(201, 203, 207, 0.2)'
                                                                            ],
                                                                            borderColor: [
                                                                                'rgba(255, 99, 132, 1)',
                                                                                'rgba(54, 162, 235, 1)',
                                                                                'rgba(255, 206, 86, 1)',
                                                                                'rgba(75, 192, 192, 1)',
                                                                                'rgba(153, 102, 255, 1)',
                                                                                'rgba(255, 159, 64, 1)',
                                                                                'rgba(201, 203, 207, 1)'
                                                                            ],
                                                                            borderWidth: 1
                                                                        },
                                                                        {
                                                                            label: 'ประเภทงานที่ลูกค้าจ้าง',
                                                                            data: customerHiredCounts,
                                                                            backgroundColor: [
                                                                                'rgba(255, 99, 132, 0.2)', // สีสำหรับประเภทงานที่ช่างภาพรับ
                                                                                'rgba(54, 162, 235, 0.2)',
                                                                                'rgba(255, 206, 86, 0.2)',
                                                                                'rgba(75, 192, 192, 0.2)',
                                                                                'rgba(153, 102, 255, 0.2)',
                                                                                'rgba(255, 159, 64, 0.2)',
                                                                                'rgba(201, 203, 207, 0.2)'
                                                                            ],
                                                                            borderColor: [
                                                                                'rgba(255, 99, 132, 1)',
                                                                                'rgba(54, 162, 235, 1)',
                                                                                'rgba(255, 206, 86, 1)',
                                                                                'rgba(75, 192, 192, 1)',
                                                                                'rgba(153, 102, 255, 1)',
                                                                                'rgba(255, 159, 64, 1)',
                                                                                'rgba(201, 203, 207, 1)'
                                                                            ],
                                                                            borderWidth: 1
                                                                        }
                                                                    ]
                                                                },
                                                                options: {
                                                                    plugins: {
                                                                        legend: {
                                                                            position: 'right',
                                                                            labels: {
                                                                                font: {
                                                                                    size: 20 // ขนาดตัวอักษรใน legend
                                                                                }
                                                                            }
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
                        </div>
                        <!-- Chart Section -->
                        <div class="col-6">
                            <div class="card shadow" style="height: auto;">
                                <div class="card-body">
                                    <h3 class="card-title mt-4 mb-4">แผนภูมิแท่งแสดงข้อมูลช่างภาพ</h3>
                                    <div class="d-flex justify-content-center">
                                        <div class="col-10">
                                            <canvas class="mb-4" id="workCountsChart" width="400" height="400" style="max-height: 650px; height: 100%;"></canvas>
                                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                                            <script>
                                                // Fetch the data from your PHP script
                                                fetch('grap3.php')
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        const ctx = document.getElementById('workCountsChart').getContext('2d');
                                                        const chart = new Chart(ctx, {
                                                            type: 'bar',
                                                            data: {
                                                                labels: ['กรุงเทพฯ', 'ภาคกลาง', 'ภาคใต้', 'ภาคเหนือ', 'ภาคตะวันออกเฉียงเหนือ', 'ภาคตะวันตก'],
                                                                datasets: [{
                                                                    label: 'จำนวนช่างภาพ',
                                                                    data: [
                                                                        data.bangkok,
                                                                        data.central,
                                                                        data.south,
                                                                        data.north,
                                                                        data.northeast,
                                                                        data.west
                                                                    ],
                                                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                                                    borderColor: 'rgba(75, 192, 192, 1)',
                                                                    borderWidth: 1
                                                                }]
                                                            },
                                                            options: {
                                                                responsive: true,
                                                                scales: {
                                                                    y: {
                                                                        beginAtZero: true,
                                                                        title: {
                                                                            display: true,
                                                                            text: 'จำนวนช่างภาพ', // Y-axis label
                                                                            font: {
                                                                                size: 20 // Font size for the Y-axis label
                                                                            }
                                                                        },
                                                                        ticks: {
                                                                            stepSize: 1, // Set tick step size to 1
                                                                            callback: function(value) {
                                                                                return Number.isInteger(value) ? value : ''; // Show only integer values
                                                                            },
                                                                            font: {
                                                                                size: 20 // Font size for Y axis ticks
                                                                            }
                                                                        }
                                                                    },
                                                                    x: {
                                                                        title: {
                                                                            display: true,
                                                                            text: 'ภูมิภาค', // X-axis label
                                                                            font: {
                                                                                size: 20 // Font size for the X-axis label
                                                                            }
                                                                        },
                                                                        ticks: {
                                                                            font: {
                                                                                size: 20 // Font size for X axis ticks
                                                                            }
                                                                        }
                                                                    }
                                                                },
                                                                plugins: {
                                                                    legend: {
                                                                        display: true,
                                                                        position: 'top',
                                                                        labels: {
                                                                            font: {
                                                                                size: 20 // Font size for legend labels
                                                                            }
                                                                        }
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
                    </div>
                </div>
            </div>
        </div>
    </center>

    <div class="card mt-4" style="height: 60px;">
        <div class="d-flex justify-content-center">
            <div class="row mt-2 ms-3">
                <div class="col-6 mt-2">
                    <h5 class="card-title">พิมพ์รายงานสรุปผล</h5>
                </div>
                <div class="col-6">
                    <button id="generatePDF" class="btn btn-primary mb-2" style="width: 150px; height:40px;">ออก PDF</button>
                </div>
                <script>
                    document.getElementById("generatePDF").addEventListener("click", function() {
                        const content = document.getElementById("contentToConvert");

                        // Increase the scale of the canvas to improve resolution
                        html2canvas(content, {
                            scale: 5, // Increase scale for higher quality
                            useCORS: true, // Allows cross-origin resources
                        }).then(function(canvas) {
                            const imgData = canvas.toDataURL('image/png');
                            const {
                                jsPDF
                            } = window.jspdf;
                            const doc = new jsPDF('p', 'mm', 'a4');

                            // Add custom font (THSarabunNew)
                            var fontBase64 = "<?php echo $fontBase64; ?>";
                            if (fontBase64) {
                                doc.addFileToVFS('THSarabunNew.ttf', fontBase64);
                                doc.addFont('THSarabunNew.ttf', 'customFont', 'normal');
                                doc.setFont('customFont');
                            }

                            // Process and invert the image (if provided)
                            var imgBase64 = "<?php echo $image_base64; ?>";
                            if (imgBase64) {
                                const imageType = imgBase64.includes("jpeg") || imgBase64.includes("jpg") ? 'JPEG' : 'PNG';

                                // Create a new image element to load the base64 data
                                var image = new Image();
                                image.src = imgBase64;

                                image.onload = function() {
                                    // Create a canvas to manipulate the image
                                    var tempCanvas = document.createElement('canvas');
                                    var ctx = tempCanvas.getContext('2d');
                                    tempCanvas.width = image.width;
                                    tempCanvas.height = image.height;

                                    // Draw the image onto the canvas
                                    ctx.drawImage(image, 0, 0);

                                    // Get image data (pixels)
                                    var imageData = ctx.getImageData(0, 0, tempCanvas.width, tempCanvas.height);
                                    var data = imageData.data;

                                    // Loop through the pixels and invert colors
                                    for (var i = 0; i < data.length; i += 4) {
                                        data[i] = 255 - data[i]; // Invert red
                                        data[i + 1] = 255 - data[i + 1]; // Invert green
                                        data[i + 2] = 255 - data[i + 2]; // Invert blue
                                        // Alpha (data[i + 3]) remains unchanged
                                    }

                                    // Update the canvas with the inverted data
                                    ctx.putImageData(imageData, 0, 0);

                                    // Convert the updated canvas back to base64
                                    var invertedImgBase64 = tempCanvas.toDataURL('image/png');

                                    // Add the inverted image to the PDF
                                    doc.addImage(invertedImgBase64, imageType, 10, 10, 45, 12); // Resize and position the image

                                    // Add system name below the image
                                    var informationName = "<?php echo $information_name; ?>";
                                    doc.setFontSize(20);
                                    doc.text(informationName, 15, 30);

                                    // Add additional detail below the system name
                                    var informationCaption = "<?php echo $information_caption; ?>";
                                    doc.setFontSize(16);
                                    doc.text(informationCaption, 15, 37);

                                    // Now proceed with the rest of the content (canvas, etc.)
                                    generatePDFContent();
                                };
                            } else {
                                // If no image, just proceed with the rest of the content
                                generatePDFContent();
                            }

                            function generatePDFContent() {
                                const imgWidth = 210; // A4 width in mm
                                const pageHeight = 297; // A4 height in mm
                                const imgHeight = (canvas.height * imgWidth) / canvas.width;
                                let heightLeft = imgHeight;
                                let position = 50; // Start at 50mm from the top

                                // Add canvas image to the PDF
                                doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight, null, 'FAST');

                                heightLeft -= (pageHeight - 10); // Adjust height left after the first page

                                // Continue adding pages if content exceeds one page
                                while (heightLeft > 0) {
                                    doc.addPage();
                                    position = heightLeft - imgHeight;
                                    doc.addImage(imgData, 'PNG', 0, 10, imgWidth, imgHeight, null, 'FAST'); // New page image
                                    heightLeft -= pageHeight;
                                }

                                // Save the generated PDF
                                doc.save('photo_match_report.pdf');
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>
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