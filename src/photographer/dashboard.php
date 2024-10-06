<?php
session_start();
include '../config_db.php';
require_once '../popup.php';

// Fetch information from `information` table
$sql = "SELECT * FROM `information`";
$resultInfo = $conn->query($sql);

if ($resultInfo && $resultInfo->num_rows > 0) {
    $rowInfo = $resultInfo->fetch_assoc();
    $information_name = $rowInfo['information_name'];
    $information_caption = $rowInfo['information_caption'];
    // สร้างพาธของไฟล์ภาพ
    $image_path = '../img/logo/' . $rowInfo['information_icon'];

    if (file_exists($image_path)) {
        $image_data = base64_encode(file_get_contents($image_path));
        $image_type = pathinfo($image_path, PATHINFO_EXTENSION);
        $image_base64 = 'data:image/' . $image_type . ';base64,' . $image_data;
    } else {
        $image_base64 = ''; // Handle case if the image doesn't exist
    }
} else {
    // Handle case where no information is found
    $information_name = '';
    $information_caption = '';
    $image_base64 = '';
}

// Check if photographer is logged in
if (isset($_SESSION['photographer_login'])) {
    $email = $_SESSION['photographer_login'];
    // Use prepared statements to prevent SQL injection
    $sql = "SELECT * FROM photographer WHERE photographer_email = ?";
    $stmtPhoto = $conn->prepare($sql);
    if ($stmtPhoto) {
        $stmtPhoto->bind_param('s', $email);
        $stmtPhoto->execute();
        $resultPhoto = $stmtPhoto->get_result();
        if ($resultPhoto && $resultPhoto->num_rows > 0) {
            $rowPhoto = $resultPhoto->fetch_assoc();
            $id_photographer = $rowPhoto['photographer_id'];
        } else {
            // Handle case where photographer is not found
            die("Photographer not found.");
        }
        $stmtPhoto->close();
    } else {
        die("Prepare failed for photographer query: " . $conn->error);
    }
} else {
    // Handle case where photographer is not logged in
    die("Photographer not logged in.");
}

// === Query 1: Fetch Deposits, Remainings, and Total Income ===
$query1 = "
    SELECT 
        DATE_FORMAT(p.pay_date, '%Y-%m') AS month, 
        SUM(CASE WHEN p.pay_status = 0 THEN b.booking_price * 0.3 ELSE 0 END) AS deposit,
        SUM(CASE WHEN p.pay_status = 1 THEN b.booking_price * 0.7 ELSE 0 END) AS remaining,
        SUM(CASE 
                WHEN p.pay_status = 0 THEN b.booking_price * 0.3 
                WHEN p.pay_status = 1 THEN b.booking_price * 0.7
                ELSE 0 
            END) AS total_income
    FROM 
        pay p
    JOIN 
        booking b ON p.booking_id = b.booking_id
    JOIN 
        photographer ph ON b.photographer_id = ph.photographer_id
    WHERE 
        YEAR(p.pay_date) = YEAR(CURDATE())  -- Restrict to current year
        AND ph.photographer_id = ?
    GROUP BY 
        DATE_FORMAT(p.pay_date, '%Y-%m')
    ORDER BY 
        month ASC
";

// Prepare and execute Query 1
$stmt1 = $conn->prepare($query1);
if (!$stmt1) {
    die("Prepare failed for Query 1: " . $conn->error);
}
$stmt1->bind_param('i', $id_photographer);
if (!$stmt1->execute()) {
    die("Execute failed for Query 1: " . $stmt1->error);
}
$result1 = $stmt1->get_result();

// Initialize arrays for months, deposits, remainings, and incomes
$months = [];
$deposits = [];
$remainings = [];
$incomes = [];

// Get the current year
$currentYear = date('Y');

// Initialize all months of the current year with default values
for ($m = 1; $m <= 12; $m++) {
    $monthKey = sprintf("%04d-%02d", $currentYear, $m);
    $months[] = $monthKey;
    $deposits[] = 0;
    $remainings[] = 0;
    $incomes[] = 0;
}

// Populate arrays based on Query 1 results
while ($row = $result1->fetch_assoc()) {
    $monthIndex = array_search($row['month'], $months);
    if ($monthIndex !== false) {
        $deposits[$monthIndex] = (float)$row['deposit'];
        $remainings[$monthIndex] = (float)$row['remaining'];
        $incomes[$monthIndex] = (float)$row['total_income'];
    }
}

// Free result and close statement for Query 1
$result1->free();
$stmt1->close();

// === Query 2: Fetch Review Data ===
$query2 = "
    SELECT 
        r.review_level, 
        COUNT(*) AS count
    FROM 
        review r
    JOIN 
        booking b ON b.booking_id = r.booking_id
    JOIN
        photographer ph ON ph.photographer_id = b.photographer_id
    WHERE 
        r.review_level BETWEEN 1 AND 5
        AND ph.photographer_id = ?
    GROUP BY 
        r.review_level
    ORDER BY 
        r.review_level ASC
";

// Prepare and execute Query 2
$stmt2 = $conn->prepare($query2);
if (!$stmt2) {
    die("Prepare failed for Query 2: " . $conn->error);
}
$stmt2->bind_param("i", $id_photographer);
if (!$stmt2->execute()) {
    die("Execute failed for Query 2: " . $stmt2->error);
}
$result2 = $stmt2->get_result();

// Initialize review data for levels 1 through 5
$reviewData = array_fill(1, 5, 0);

// Populate review data based on Query 2 results
while ($row2 = $result2->fetch_assoc()) {
    $level = (int)$row2['review_level'];
    $count = (int)$row2['count'];
    $reviewData[$level] = $count;
}

// Free result and close statement for Query 2
$result2->free();
$stmt2->close();

// === Query 3: Fetch Booking Status Data ===
$query3 = "
    SELECT 
        MONTH(booking_date) AS booking_month,
        SUM(booking_confirm_status = 0) AS total_reserved,
        SUM(booking_confirm_status = 1) AS total_pending,
        SUM(booking_confirm_status = 2) AS total_canceled,
        SUM(booking_confirm_status = 3) AS total_completed
    FROM 
        booking b
    JOIN
        photographer p ON b.photographer_id = p.photographer_id
    WHERE 
        YEAR(booking_date) = YEAR(CURDATE())
        AND p.photographer_id = ?
    GROUP BY 
        booking_month
    ORDER BY 
        booking_month ASC
";

// Prepare and execute Query 3
$stmt3 = $conn->prepare($query3);
if (!$stmt3) {
    die("Prepare failed for Query 3: " . $conn->error);
}
$stmt3->bind_param("i", $id_photographer);
if (!$stmt3->execute()) {
    die("Execute failed for Query 3: " . $stmt3->error);
}
$result3 = $stmt3->get_result();

// Initialize arrays for each booking status with 12 months set to 0
$totalReserved = array_fill(1, 12, 0);
$totalPending = array_fill(1, 12, 0);
$totalCanceled = array_fill(1, 12, 0);
$totalCompleted = array_fill(1, 12, 0);

// Populate arrays based on Query 3 results
while ($row3 = $result3->fetch_assoc()) {
    $month = (int)$row3['booking_month']; // 1-12
    $totalReserved[$month] = (int)$row3['total_reserved'];
    $totalPending[$month] = (int)$row3['total_pending'];
    $totalCanceled[$month] = (int)$row3['total_canceled'];
    $totalCompleted[$month] = (int)$row3['total_completed'];
}

// Reindex arrays to have 0-based indices for JavaScript
$totalReserved = array_values($totalReserved);
$totalPending = array_values($totalPending);
$totalCanceled = array_values($totalCanceled);
$totalCompleted = array_values($totalCompleted);

// Close statement and free result for Query 3
$result3->free();
$stmt3->close();

// Prepare the data for JSON output for bookings (if needed elsewhere)
$type_data = [];
for ($m = 1; $m <= 12; $m++) {
    $type_data[] = [
        'booking_month' => $m,
        'total_reserved' => $totalReserved[$m - 1],
        'total_pending' => $totalPending[$m - 1],
        'total_canceled' => $totalCanceled[$m - 1],
        'total_completed' => $totalCompleted[$m - 1]
    ];
}

// Optionally, encode the review data for JavaScript usage
$jsonReviewData = json_encode($reviewData, JSON_NUMERIC_CHECK);

// Encode other data for JavaScript
$jsonMonths = json_encode($months);
$jsonDeposits = json_encode($deposits, JSON_NUMERIC_CHECK);
$jsonRemainings = json_encode($remainings, JSON_NUMERIC_CHECK);
$jsonIncomes = json_encode($incomes, JSON_NUMERIC_CHECK);
$jsonTypeData = json_encode($type_data, JSON_NUMERIC_CHECK);
?>


<!DOCTYPE html>
<html lang="en">

<head>
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

    <!-- Fancybox CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />

    <!-- font awaysome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.6/flatpickr.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Athiti&family=Merriweather:wght@700&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Load external libraries first -->
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.6.0/dist/jspdf.plugin.autotable.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>



    <style>
        body {
            font-family: 'Athiti', sans-serif;
            overflow-x: hidden;
            background: #E5E4E2;
        }


        html,
        body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .content {
            flex: 1;
        }

        #calendar {
            width: 800px;
            margin: auto;
            font-family: 'Athiti', sans-serif;
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

        .write-post-container {
            width: 100%;
            background: #fff;
            border-radius: 4px;
            padding: 20px;
            color: #626262;
        }

        .post-img {
            width: 100%;
            border-radius: 4px;
            margin-bottom: 5px;
        }

        .post-text {
            color: #626262;
            margin: 15px;
            font-size: 15px;
        }

        /* Responsive styles */
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

        .modal-dialog {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-dialog {
            max-width: 90%;
            /* กำหนดความกว้างสูงสุดเป็น 80% */
            width: 60%;
            /* กำหนดความกว้างเป็น 80% */
        }

        .post-input-container {
            /* padding-left: 55px; */
            padding-top: 20px;
        }

        .post-input-container textarea {
            width: 100%;
            border: 0;
            outline: 0;
            /* border-bottom: 1px solid #ccc; */
            resize: none;
        }

        .form-container {
            flex: 1;
            overflow-y: auto;
        }

        .bottom-bar {
            position: sticky;
            bottom: 0;
            width: 100%;
            margin-top: 20px;
            /* เพิ่มช่องว่างด้านบน */
        }

        .row-scroll {
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
            /* เพื่อการเลื่อนสามารถทำงานได้ดีบนอุปกรณ์มือถือ iOS */
        }

        .col-md-4 {
            flex: 0 0 calc(33.33% - 10px);
            max-width: calc(33.33% - 10px);
        }

        .bgIndex {
            background-image: url('../img/bgIndex1.png');
            background-attachment: fixed;
            background-size: cover;
            /* เพิ่มการปรับแต่งในการขยับภาพตามต้องการ */
        }
    </style>
</head>

<body>
    <div class="content">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-dark" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Navbar Start -->
        <div class="bgIndex" style="height: 250px;">
            <!-- <div style="background-color: rgba(	0, 41, 87, 0.6);"> -->
            <div class="d-flex justify-content-center">
                <nav class="navbar navbar-expand-lg navbar-dark col-10">
                    <a href="index.php" class="navbar-brand d-flex align-items-center text-center">
                        <img class="img-fluid" src="../img/logo/<?php echo isset($rowInfo['information_icon']) ? $rowInfo['information_icon'] : ''; ?>" style="height: 30px;">
                    </a>
                    <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <span class="navbar-toggler-icon text-primary"></span>
                    </button>
                    <div class="collapse navbar-collapse m-4" id="navbarCollapse">
                        <div class="navbar-nav ms-auto">
                            <a href="index.php" class="nav-item nav-link">หน้าหลัก</a>
                            <a href="table.php" class="nav-item nav-link">ตารางงาน</a>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">รายการจอง</a>
                                <div class="dropdown-menu rounded-0 m-0">
                                    <!-- <a href="bookingListAll.php" class="dropdown-item">รายการจองทั้งหมด</a> -->
                                    <a href="bookingListWaittingForApproval.php" class="dropdown-item">รายการจองที่รออนุมัติ</a>
                                    <a href="bookingListApproved.php" class="dropdown-item">รายการจองที่อนุมัติแล้ว</a>
                                    <a href="bookingListConfirmPayment.php" class="dropdown-item">รายการจองที่รอตรวจสอบการชำระ</a>
                                    <a href="bookingListSend.php" class="dropdown-item">รายการจองที่ต้องส่งงาน</a>
                                    <a href="bookingListApproved.php" class="dropdown-item">รายการจองที่เสร็จสิ้นแล้ว</a>
                                    <a href="bookingListNotApproved.php" class="dropdown-item">รายการจองที่ไม่อนุมัติ</a>
                                </div>
                            </div>
                            <a href="report.php" class="nav-item nav-link">รายงาน</a>
                            <a href="dashboard.php" class="nav-item nav-link active">สถิติ</a>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">โปรไฟล์</a>
                                <div class="dropdown-menu rounded-0 m-0">
                                    <a href="profile.php" class="dropdown-item">โปรไฟล์</a>
                                    <a href="editProfile.php" class="dropdown-item">แก้ไขข้อมูลส่วนตัว</a>
                                    <a href="about.php" class="dropdown-item">เกี่ยวกับ</a>
                                    <a href="contact.php" class="dropdown-item">ติดต่อ</a>
                                    <a href="../logout.php" class="dropdown-item">ออกจากระบบ</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
            <!-- Navbar End -->


            <!-- Category Start -->
            <div class="mb-5 mt-4 text-center mx-auto  wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h1 class="f" style="color:aliceblue;"><?php echo $rowInfo['information_name']; ?></h1>
                <p style="color:aliceblue;"><?php echo $rowInfo['information_caption']; ?></p>
            </div><br>
            <center>
                <div id="contentToConvert">
                    <div class="col-11 mt-3">
                        <div class="row">
                            <!-- Customer Popular Work Types -->
                            <div class="col-4">
                                <div class="card text-dark shadow" style="height: 210px;">
                                    <div class="card-header">
                                        <h4 class="mt-2">ประเภทงานที่ลูกค้านิยมจ้าง</h4>
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
                            <div class="col-4">
                                <div class="card text-dark shadow" style="height: 210px;">
                                    <div class="card-header">
                                        <h4 class="mt-2">รายได้ในปี <?php echo date('Y'); ?></h4>
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
                            <div class="col-4">
                                <div class="card text-dark shadow" style="height: 210px;">
                                    <div class="card-header">
                                        <h4 class="mt-2">คะแนน</h4>
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
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card border">
                                            <div class="card-body shadow">
                                                <h3 class="card-title">แผนภูมิแท่งแสดงรายได้ในแต่ละเดือน (ในปี <?php echo date('Y'); ?>)</h3>
                                                <div class="d-flex justify-content-center mt-3">
                                                    <div class="col-10">
                                                        <canvas id="overviewChart1" width="400" height="400" style="max-height: 400px; height: auto;"></canvas>
                                                    </div>
                                                    <script>
                                                        document.addEventListener("DOMContentLoaded", function() {
                                                            var ctx = document.getElementById('overviewChart1').getContext('2d');

                                                            const monthNames = [
                                                                'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม',
                                                                'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม',
                                                                'พฤศจิกายน', 'ธันวาคม'
                                                            ];

                                                            // Initialize an array for all 12 months with 0 values
                                                            const monthlyData = new Array(12).fill(0);

                                                            // Assuming $months contains month data in "YYYY-MM" format
                                                            const existingMonths = <?= json_encode($months) ?>;

                                                            // Populate the monthlyData based on existing months and income, deposits, and remaining data
                                                            existingMonths.forEach((month, index) => {
                                                                const monthNumber = parseInt(month.split('-')[1]) - 1; // Extract month number (0-11)
                                                                monthlyData[monthNumber] = <?= json_encode($incomes) ?>[index]; // Set income data for the corresponding month
                                                            });

                                                            var chartData = {
                                                                labels: monthNames, // Use all month names
                                                                datasets: [{
                                                                        label: 'รายได้รวมต่อเดือน',
                                                                        data: monthlyData, // Use the monthlyData array
                                                                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                                                        borderColor: 'rgba(54, 162, 235, 1)',
                                                                        borderWidth: 1
                                                                    },
                                                                    {
                                                                        label: 'ค่ามัดจำ',
                                                                        data: <?= json_encode($deposits) ?>, // Ensure this has data for all 12 months
                                                                        backgroundColor: 'rgba(255, 206, 86, 0.6)',
                                                                        borderColor: 'rgba(255, 206, 86, 1)',
                                                                        borderWidth: 1
                                                                    },
                                                                    {
                                                                        label: 'ยอดคงเหลือ',
                                                                        data: <?= json_encode($remainings) ?>, // Ensure this has data for all 12 months
                                                                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                                                                        borderColor: 'rgba(75, 192, 192, 1)',
                                                                        borderWidth: 1
                                                                    }
                                                                ]
                                                            };

                                                            var myChart = new Chart(ctx, {
                                                                type: 'bar',
                                                                data: chartData,
                                                                options: {
                                                                    scales: {
                                                                        y: {
                                                                            beginAtZero: true,
                                                                            ticks: {
                                                                                font: {
                                                                                    size: 20
                                                                                }
                                                                            }
                                                                        },
                                                                        x: {
                                                                            ticks: {
                                                                                font: {
                                                                                    size: 20
                                                                                }
                                                                            }
                                                                        }
                                                                    },
                                                                    plugins: {
                                                                        legend: {
                                                                            labels: {
                                                                                font: {
                                                                                    size: 20
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            });
                                                        });
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mt-4">
                                    <div class="row">
                                        <div class="col-lg-7">
                                            <div class="card text-dark shadow" height=650px; style=" max-height: 650px; height: 450;">
                                                <div class="card-body">
                                                    <h3 class="card-title mt-2">กราฟแสดงข้อมูลสถานะการจองต่อเดือน (ในปี <?php echo date('Y'); ?>)</h3>
                                                    <div class="d-flex justify-content-center">
                                                        <div class="col-10 mt-4">
                                                            <canvas id="overviewChart3" width="400" height="300" style="max-height: 300px; height: auto;"></canvas>
                                                        </div>
                                                        <script>
                                                            document.addEventListener("DOMContentLoaded", function() {
                                                                var ctx = document.getElementById('overviewChart3').getContext('2d');

                                                                const monthNames = [
                                                                    'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม',
                                                                    'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม',
                                                                    'พฤศจิกายน', 'ธันวาคม'
                                                                ];

                                                                // Convert PHP arrays to JavaScript
                                                                const totalReserved = <?= json_encode($totalReserved) ?>;
                                                                const totalPending = <?= json_encode($totalPending) ?>;
                                                                const totalCanceled = <?= json_encode($totalCanceled) ?>;
                                                                const totalCompleted = <?= json_encode($totalCompleted) ?>;

                                                                var myChart = new Chart(ctx, {
                                                                    type: 'line',
                                                                    data: {
                                                                        labels: monthNames, // Month names for the X axis
                                                                        datasets: [{
                                                                                label: 'การจองที่รอดำเนินการ', // Reserved bookings
                                                                                data: totalReserved, // Use the reserved data
                                                                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                                                                borderColor: 'rgba(54, 162, 235, 1)',
                                                                                borderWidth: 2,
                                                                                fill: false,
                                                                                tension: 0.4
                                                                            },
                                                                            {
                                                                                label: 'การจองที่ยังไม่สำเร็จ', // Pending bookings
                                                                                data: totalPending, // Use the pending data
                                                                                backgroundColor: 'rgba(255, 206, 86, 0.2)',
                                                                                borderColor: 'rgba(255, 206, 86, 1)',
                                                                                borderWidth: 2,
                                                                                fill: false,
                                                                                tension: 0.4
                                                                            },
                                                                            {
                                                                                label: 'การจองที่ถูกยกเลิก', // Canceled bookings
                                                                                data: totalCanceled, // Use the canceled data
                                                                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                                                                borderColor: 'rgba(255, 99, 132, 1)',
                                                                                borderWidth: 2,
                                                                                fill: false,
                                                                                tension: 0.4
                                                                            },
                                                                            {
                                                                                label: 'การจองที่เสร็จสิ้น', // Completed bookings
                                                                                data: totalCompleted, // Use the completed data
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
                                                            });
                                                        </script>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <div class="card" style="height: 450px;">
                                                <div class="card-body shadow">
                                                    <h3 class="card-title">แผนภูมิแท่งแสดงคะแนนรีวิว</h3>
                                                    <div class="d-flex justify-content-center">
                                                        <div class="col-10 mt-2">
                                                            <canvas id="overviewChart2" width="400" height="400" style="max-height: 340px; height: auto;"></canvas>
                                                        </div>
                                                        <script>
                                                            document.addEventListener("DOMContentLoaded", function() {
                                                                // ข้อมูลรีวิวที่ดึงมาจาก PHP และส่งไปยัง JavaScript
                                                                const reviewData = <?php echo $jsonReviewData; ?>;

                                                                // Prepare labels and counts
                                                                const labels = Object.keys(reviewData).map(item => `${item} คะแนน`);
                                                                const counts = Object.values(reviewData);

                                                                const ctx = document.getElementById('overviewChart2').getContext('2d');
                                                                const myChart = new Chart(ctx, {
                                                                    type: 'bar',
                                                                    data: {
                                                                        labels: labels,
                                                                        datasets: [{
                                                                            label: 'จำนวนครั้ง',
                                                                            data: counts,
                                                                            backgroundColor: 'rgba(75, 192, 192, 0.6)',
                                                                            borderColor: 'rgba(75, 192, 192, 1)',
                                                                            borderWidth: 1
                                                                        }]
                                                                    },
                                                                    options: {
                                                                        scales: {
                                                                            y: {
                                                                                beginAtZero: true,
                                                                                title: {
                                                                                    display: true,
                                                                                    text: 'จำนวน (ครั้ง)',
                                                                                    font: {
                                                                                        size: 20
                                                                                    }
                                                                                },
                                                                                ticks: {
                                                                                    font: {
                                                                                        size: 20
                                                                                    }
                                                                                }
                                                                            },
                                                                            x: {
                                                                                title: {
                                                                                    display: true,
                                                                                    text: 'คะแนน',
                                                                                    font: {
                                                                                        size: 20
                                                                                    }
                                                                                },
                                                                                ticks: {
                                                                                    font: {
                                                                                        size: 20
                                                                                    }
                                                                                }
                                                                            }
                                                                        },
                                                                        plugins: {
                                                                            legend: {
                                                                                labels: {
                                                                                    font: {
                                                                                        size: 20
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                });
                                                            });
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
                    </div>
                </div>
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




            <!-- Back to Top -->
            <a href="#" class="btn btn-lg btn-dark btn-lg-square back-to-top" style="background-color:#1E2045"><i class="bi bi-arrow-up"></i></a>

            <!-- Footer Start -->
            <!-- <div class="container-fluid bg-dark text-white-50 footer wow fadeIn">
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            &copy; <a class="border-bottom" href="#">2024 Photo Match</a>, All Right Reserved.
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- Footer End -->


        </div>
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