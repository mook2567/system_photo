<?php
session_start();
include '../config_db.php';
require_once '../popup.php';

$sql = "SELECT * FROM `information`";
$resultInfo = $conn->query($sql);
$rowInfo = $resultInfo->fetch_assoc();


$information_name = $rowInfo['information_name'];
$information_caption = $rowInfo['information_caption'];
$rowInfo['information_icon'];
// สร้างพาธของไฟล์ภาพ
$image_path = '../img/logo/' . $rowInfo['information_icon'];

if (file_exists($image_path)) {
    $image_data = base64_encode(file_get_contents($image_path));
    $image_type = pathinfo($image_path, PATHINFO_EXTENSION);
    $image_base64 = 'data:image/' . $image_type . ';base64,' . $image_data;
} else {
    $image_base64 = ''; // Handle case if the image doesn't exist
}


if (isset($_SESSION['photographer_login'])) {
    $email = $_SESSION['photographer_login'];
    $sql = "SELECT * FROM photographer WHERE photographer_email LIKE '$email'";
    $resultPhoto = $conn->query($sql);
    $rowPhoto = $resultPhoto->fetch_assoc();
    $id_photographer = $rowPhoto['photographer_id'];
}

$sql = "SELECT * FROM `portfolio`";
$resultPort = $conn->query($sql);
$rowPort = $resultPort->fetch_assoc();

$query1 = "
    SELECT 
        DATE_FORMAT(p.pay_date, '%Y-%m') AS month, 
        SUM(CASE 
                WHEN p.pay_status = 0 THEN b.booking_price * 0.3 
                ELSE 0 
            END) AS total_deposit,
        SUM(CASE 
                WHEN p.pay_status = 1 THEN b.booking_price * 0.7 
                ELSE 0 
            END) AS total_payment
    FROM 
        pay p
    JOIN 
        booking b ON p.booking_id = b.booking_id
    JOIN 
        photographer ph ON b.photographer_id = ph.photographer_id
    WHERE 
        p.pay_date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
        AND ph.photographer_id = ?
    GROUP BY 
        DATE_FORMAT(p.pay_date, '%Y-%m')
    ORDER BY 
        month DESC
";

$stmt = $conn->prepare($query1);
$stmt->bind_param('i', $id_photographer);
$stmt->execute();
$result1 = $stmt->get_result();

// Prepare data for Chart.js
$months = [];
$deposits = [];
$payments = [];

while ($row2 = $result1->fetch_assoc()) {
    $months[] = $row2['month'];
    $deposits[] = $row2['total_deposit'];
    $payments[] = $row2['total_payment'];
}


$query2 = "SELECT 
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
          AND
            ph.photographer_id = ?
          GROUP BY 
            r.review_level
          ORDER BY 
            r.review_level";

$stmt = $conn->prepare($query2);
$stmt->bind_param("i", $id_photographer);
$stmt->execute();
$result2 = $stmt->get_result();

$reviewData = [];
while ($row2 = $result2->fetch_assoc()) {
    $reviewData[] = $row2;
}


$query3 = "
    SELECT 
        t.type_work, 
        COUNT(b.booking_id) AS num 
    FROM 
        booking b 
    JOIN 
        type_of_work tow ON tow.type_of_work_id = b.type_of_work_id
    JOIN 
        type t ON t.type_id = tow.type_id
    JOIN 
        photographer ph ON ph.photographer_id = tow.photographer_id
    WHERE
        ph.photographer_id = $id_photographer
    GROUP BY 
        t.type_work 
    ORDER BY 
        num DESC
";

$result3 = mysqli_query($conn, $query3);
$data = [];
$total_count = 0;

while ($row3 = mysqli_fetch_assoc($result3)) {
    $data[] = $row3;
    $total_count += $row3['num']; // Accumulate total counts for display
}

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
                            <a href="index.php" class="nav-item nav-link active">หน้าหลัก</a>
                            <a href="table.php" class="nav-item nav-link">ตารางงาน</a>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">รายการจอง</a>
                                <div class="dropdown-menu rounded-0 m-0">
                                    <a href="bookingListAll.php" class="dropdown-item">รายการจองทั้งหมด</a>
                                    <a href="bookingListWaittingForApproval.php" class="dropdown-item">รายการจองที่รออนุมัติ</a>
                                    <a href="bookingListApproved.php" class="dropdown-item">รายการจองที่อนุมัติแล้ว</a>
                                    <a href="bookingListNotApproved.php" class="dropdown-item">รายการจองที่ไม่อนุมัติ</a>
                                </div>
                            </div>
                            <!-- <a href="report.php" class="nav-item nav-link">รายงาน</a> -->
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
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card border">
                                            <div class="card-body">
                                                <h3 class="card-title">แผนภูมิแท่งแสดงรายในแต่ละเดือนของช่างภาพ</h3>
                                                <div class="d-flex justify-content-center mt-3">
                                                    <div class="col-10">
                                                        <canvas id="overviewChart1"></canvas>
                                                    </div>
                                                    <script>
                                                        document.addEventListener("DOMContentLoaded", function() {
                                                            var ctx = document.getElementById('overviewChart1').getContext('2d');

                                                            var chartData = {
                                                                labels: <?= json_encode($months) ?>, // x-axis (Months)
                                                                datasets: [{
                                                                        label: 'Total Deposit',
                                                                        data: <?= json_encode($deposits) ?>,
                                                                        backgroundColor: 'rgba(54, 162, 235, 0.6)', // Blue for Deposit
                                                                        borderColor: 'rgba(54, 162, 235, 1)',
                                                                        borderWidth: 1
                                                                    },
                                                                    {
                                                                        label: 'Total Payment',
                                                                        data: <?= json_encode($payments) ?>,
                                                                        backgroundColor: 'rgba(75, 192, 192, 0.6)', // Green for Payment
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
                                                                        },
                                                                        title: {
                                                                            display: true,
                                                                            text: 'ข้อมูลการฝากและจ่ายรายเดือน',
                                                                            font: {
                                                                                size: 20
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

                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-lg-6 mt-2">
                                            <div class="card" style="height: 600px;">
                                                <div class="card-body">
                                                    <h3 class="card-title">แผนภูมิแท่งแสดงคะแนนรีวิว</h3>
                                                    <div class="d-flex justify-content-center">
                                                        <div class="col-10">
                                                            <canvas id="overviewChart2"></canvas>
                                                        </div>
                                                        <script>
                                                            document.addEventListener("DOMContentLoaded", function() {
                                                                // ข้อมูลรีวิวที่ดึงมาจาก PHP และส่งไปยัง JavaScript
                                                                const reviewData = <?php echo json_encode($reviewData); ?>;

                                                                const labels = reviewData.map(item => `ระดับ ${item.review_level}`);
                                                                const counts = reviewData.map(item => item.count);

                                                                const ctx = document.getElementById('overviewChart2').getContext('2d');
                                                                const myChart = new Chart(ctx, {
                                                                    type: 'bar',
                                                                    data: {
                                                                        labels: labels,
                                                                        datasets: [{
                                                                            label: 'จำนวนรีวิว',
                                                                            data: counts,
                                                                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                                                            borderColor: 'rgba(75, 192, 192, 1)',
                                                                            borderWidth: 1
                                                                        }]
                                                                    },
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
                                                                            },
                                                                            title: {
                                                                                display: true,
                                                                                text: 'คะแนนรีวิว',
                                                                                font: {
                                                                                    size: 20
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

                                        <div class="col-lg-6 mt-2">
                                            <div class="card" style="height: 800px;">
                                                <div class="card-body">
                                                    <h3 class="card-title">แผนภูมิวงกลมแสดงข้อมูลจำนวนการจองตามประเภทของงานถ่ายภาพ</h3>
                                                    <p class="card-title">มีการจอง <?php echo $total_count; ?> ครั้ง</p>
                                                    <div class="d-flex justify-content-center">
                                                        <div class="col-9">
                                                            <canvas id="overviewChart3"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            const ctx = document.getElementById('overviewChart3').getContext('2d');
                                            const data = {
                                                labels: [
                                                    <?php foreach ($data as $row) {
                                                        echo "'" . $row['type_work'] . "',";
                                                    } ?>
                                                ],
                                                datasets: [{
                                                    label: 'จำนวนการจอง',
                                                    data: [
                                                        <?php foreach ($data as $row) {
                                                            echo $row['num'] . ",";
                                                        } ?>
                                                    ],
                                                    backgroundColor: [
                                                        'rgba(255, 99, 132, 0.2)',
                                                        'rgba(54, 162, 235, 0.2)',
                                                        'rgba(255, 206, 86, 0.2)',
                                                        'rgba(75, 192, 192, 0.2)',
                                                        'rgba(153, 102, 255, 0.2)',
                                                        'rgba(255, 159, 64, 0.2)',
                                                    ],
                                                    borderColor: [
                                                        'rgba(255, 99, 132, 1)',
                                                        'rgba(54, 162, 235, 1)',
                                                        'rgba(255, 206, 86, 1)',
                                                        'rgba(75, 192, 192, 1)',
                                                        'rgba(153, 102, 255, 1)',
                                                        'rgba(255, 159, 64, 1)',
                                                    ],
                                                    borderWidth: 1
                                                }]
                                            };

                                            const overviewChart3 = new Chart(ctx, {
                                                type: 'pie', // or 'doughnut' for a doughnut chart
                                                data: data,
                                                options: {
                                                    responsive: true,
                                                    plugins: {
                                                        legend: {
                                                            position: 'top',
                                                            labels: {
                                                                font: {
                                                                    size: 20
                                                                }
                                                            }
                                                        },
                                                        title: {
                                                            display: true,
                                                            text: 'ข้อมูลประเภทของงานถ่ายภาพ',
                                                            font: {
                                                                size: 20
                                                            }
                                                        }
                                                    }
                                                }
                                            });
                                        </script>

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