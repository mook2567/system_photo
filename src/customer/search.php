<?php
session_start();
include '../config_db.php';
require_once '../popup.php';

$sql = "SELECT * FROM `information`";
$resultInfo = $conn->query($sql);
$rowInfo = $resultInfo->fetch_assoc();

if (isset($_SESSION['customer_login'])) {
    $email = $_SESSION['customer_login'];
    $sql = "SELECT * FROM customer WHERE cus_email LIKE '$email'";
    $resultCus = $conn->query($sql);
    $rowCus = $resultCus->fetch_assoc();
    $id_cus = $rowCus['cus_id'];
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

    <!-- Fancybox CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />

    <!-- font awaysome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.6/flatpickr.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Athiti&family=Merriweather:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Athiti', sans-serif;
            background: #F0F2F5;
        }

        .f {
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

        .bgIndex {
            background-image: url('../img/bgIndex3.png');
            background-attachment: fixed;
            background-size: cover;
            /* เพิ่มการปรับแต่งในการขยับภาพตามต้องการ */
        }

        .property-item img {
            width: 100%;
            height: 250px;
            /* กำหนดความสูงตามที่คุณต้องการ */
            object-fit: cover;
            /* ทำให้รูปภาพครอบคลุมพื้นที่ */
        }
    </style>
</head>

<body>

    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar Start -->
    <div class="bgIndex mb-3" style="height: auto;">
        <!-- <div style="background-color: rgba(0, 41, 87, 0.6);"> -->
        <div class="d-flex justify-content-center">
            <nav class="mt-3 navbar navbar-expand-lg navbar-dark col-10">
                <a href="index.php" class="navbar-brand d-flex align-items-center text-center" style="height: 70px;">
                    <img class="img-fluid" src="../img/logo/<?php echo isset($rowInfo['information_icon']) ? $rowInfo['information_icon'] : ''; ?>" style="height: 30px;">
                </a>
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon text-primary"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto f">
                        <a href="index.php" class="nav-item nav-link">หน้าหลัก</a>
                        <a href="search.php" class="nav-item nav-link active">ค้นหาช่างภาพ</a>
                        <a href="workings.php" class="nav-item nav-link">ผลงานช่างภาพ</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">รายการจองคิวช่างภาพ</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="bookingLists.php" class="dropdown-item">รายการจองคิวที่รออนุมัต</a>
                                <a href="payLists.php" class="dropdown-item ">รายการจองคิวที่ต้องชำระเงิน/ค่ามัดจำ</a>
                                <!-- <a href="reviewLists.php" class="dropdown-item">รายการจองคิวที่ต้องรีวิว</a> -->
                                <!-- <a href="bookingFinishedLists.php" class="dropdown-item">รายการจองคิวที่เสร็จสิ้นแล้ว</a> -->
                                <a href="bookingRejectedLists.php" class="dropdown-item">รายการจองคิวที่ถูกปฏิเสธ</a>
                            </div>
                        </div>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">โปรไฟล์</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="profile.php" class="dropdown-item">โปรไฟล์</a>
                                <!-- <a href="about.php" class="dropdown-item">เกี่ยวกับ</a> -->
                                <!-- <a href="contact.php" class="dropdown-item">ติดต่อ</a> -->
                                <a href="../index.php" class="dropdown-item">ออกจากระบบ</a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <!-- Navbar End -->


        <!-- Header Start -->
        <div class="container-fluid row g-0 align-items-center flex-column-reverse flex-md-row">
            <div class="col-md-6 p-5 mt-lg-5">
                <h1 class="display-5 animated fadeIn text-white mb-4 f">ค้นหาช่างภาพ</h1>
                <p class="text-white">คุณสามารถค้นหาช่างภาพตามความสนใจของคุณได้</p>
            </div>
        </div>
        <!-- Header End -->

        <!-- Search Start -->
        <div class="mt-5 wow fadeIn" style="background-color: rgba(250, 250, 250, 0.4); padding: 35px;" data-wow-delay="0.1s">
            <div class="container">
                <div class="row flex-row g-2 align-items-center">
                    <h2 class="text-white">ค้นหาช่างภาพ</h2>
                    <div class="col-md-3">
                        <form action="" method="POST">
                            <select class="form-select border-0 py-3 mt-3" name="type" required>
                                <option selected>ประเภทงาน</option>
                                <?php
                                $sql = "SELECT t.type_id, t.type_work
                                FROM type t
                                INNER JOIN (
                                    SELECT type_id, MAX(photographer_id) AS photographer_id
                                    FROM type_of_work
                                    GROUP BY type_id
                                ) AS tow_latest ON t.type_id = tow_latest.type_id";
                                $resultTypeWorkDetail = $conn->query($sql);

                                if ($resultTypeWorkDetail->num_rows > 0) {
                                    while ($rowTypeWorkDetail = $resultTypeWorkDetail->fetch_assoc()) {
                                ?>
                                        <option value="<?php echo $rowTypeWorkDetail['type_id']; ?>"><?php echo $rowTypeWorkDetail['type_work']; ?></option>
                                <?php
                                    }
                                } ?>
                            </select>
                    </div>
                    <div class="col-md-2">
                        <input class="border-0 py-3" type="number" name="budget" placeholder="งบประมาณ (บาท)" style="border: none; outline: none; width: 100%; border-radius: 5px;" required>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select border-0 py-3" name="time" required>
                            <option selected>ช่วงเวลา</option>
                            <option value="1">เต็มวัน</option>
                            <option value="2">ครึ่งวัน</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="scope" class="form-select border-0 py-3" required>
                            <option selected>สถานที่</option>
                            <option value="กรุงเทพฯ">กรุงเทพฯ</option>
                            <option value="ภาคกลาง">ภาคกลาง</option>
                            <option value="ภาคใต้">ภาคใต้</option>
                            <option value="ภาคเหนือ">ภาคเหนือ</option>
                            <option value="ภาคตะวันออกเฉียงเหนือ">ภาคตะวันออกเฉียงเหนือ</option>
                            <option value="ภาคตะวันตก">ภาคตะวันตก</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary border-0 w-100 py-3" name="search">ค้นหา</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Search End -->

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['search'])) {
            // Sanitize and assign POST data
            $type_id = intval($conn->real_escape_string($_POST['type']));
            $budget = intval($conn->real_escape_string($_POST['budget']));
            $time = intval($conn->real_escape_string($_POST['time']));
            $scope = $conn->real_escape_string($_POST['scope']);

            // Build the SQL query
            $sql = "SELECT 
                p.photographer_prefix,
                p.photographer_name,
                p.photographer_surname,
                p.photographer_tell,
                p.photographer_email,
                p.photographer_scope,
                p.photographer_photo,
                p.photographer_address,
                tow.type_of_work_rate_half,
                tow.type_of_work_rate_full,
                t.type_work,
                p.photographer_id
            FROM 
                photographer p
            INNER JOIN 
                type_of_work tow ON p.photographer_id = tow.photographer_id
            INNER JOIN 
                type t ON t.type_id = tow.type_id
            WHERE 
                tow.type_id = $type_id
                AND (
                    ($time = 1 AND CAST(tow.type_of_work_rate_full AS UNSIGNED) <= $budget AND CAST(tow.type_of_work_rate_full AS UNSIGNED) != 0)
                    OR
                    ($time = 2 AND CAST(tow.type_of_work_rate_half AS UNSIGNED) <= $budget AND CAST(tow.type_of_work_rate_half AS UNSIGNED) != 0)
                )
                AND p.photographer_scope = '$scope'";

            // Execute the query
            $result = $conn->query($sql);
    ?>
            <!-- Examples of work Start -->
            <div class="container-xxl py-5">
                <div class="container">
                    <div class="tab-content">
                        <div id="tab-1" class="tab-pane fade show p-0 active">
                            <div class="row g-4">
                                <?php
                                if ($result->num_rows > 0) {
                                    while ($row_photographer = $result->fetch_assoc()) {
                                ?>
                                        <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                                            <div class="property-item rounded overflow-hidden bg-white" style="height: auto; width: 600px;">
                                                <div class="row">
                                                    <div class="col-6 position-relative overflow-hidden">
                                                        <a href="profile_photographer.php?photographer_id=<?php echo $row_photographer['photographer_id']; ?>"><img class="img-fluid" src="../img/profile/<?php echo isset($row_photographer['photographer_photo']) ? $row_photographer['photographer_photo'] : 'default.jpg'; ?>" alt=""></a>
                                                        <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">
                                                            <?php echo $row_photographer['photographer_prefix'] . ' ' . $row_photographer['photographer_name'] . ' ' . $row_photographer['photographer_surname']; ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 p-2 pb-0">
                                                        <?php if (isset($row_photographer['type_work'])) { ?>
                                                            <p class="text-dark mb-3">
                                                            <div class="d-flex align-items-center">
                                                                <b><?php echo $row_photographer['type_work']; ?> </b>
                                                            </div>
                                                            <div class="ms-3 mt-1">
                                                                <?php if ($row_photographer['type_of_work_rate_half'] > 0) { ?>
                                                                    <b>ราคาครึ่งวัน : </b><?php echo number_format($row_photographer['type_of_work_rate_half'], 0); ?><b> บาท</b>
                                                                <?php } ?>
                                                            </div>
                                                            <div class="ms-3 mt-1">
                                                                <?php if ($row_photographer['type_of_work_rate_full'] > 0) { ?>
                                                                    <b>ราคาเต็มวัน : </b><?php echo number_format($row_photographer['type_of_work_rate_full'], 0); ?><b> บาท</b>
                                                                <?php } ?>
                                                            </div>
                                                            </p>
                                                        <?php } else { ?>
                                                            <p class="text-dark mb-3">ยังไม่ได้ลงประเภทงาน</p>
                                                        <?php } ?>
                                                        <a class="d-block mb-3" href="mailto:<?php echo $row_photographer['photographer_email']; ?>"><?php echo $row_photographer['photographer_email']; ?></a>
                                                        <p class="text-dark mb-3">โทร <?php echo $row_photographer['photographer_tell']; ?></p>
                                                        <p><i class="fa fa-map-marker-alt text-dark me-2"></i><?php echo $row_photographer['photographer_scope']; ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <div class="card-body bg-white" style="height: 132px; width:auto;">
                                        <center>
                                            <i class="fas fa-exclamation-triangle fa-3x text-danger"></i>
                                            <h1 class="">ไม่พบข้อมูลที่คุณต้องการ</h1>
                                        </center>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Examples of work End -->
        <?php
        }
    } else {
        ?>
        <div class="container-xxl py-5">
            <div class="container">
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane fade show p-0 active">
                        <div class="row g-4">
                            <?php
                            $sql = "SELECT 
                            p.photographer_prefix,
                            p.photographer_name,
                            p.photographer_surname,
                            p.photographer_tell,
                            p.photographer_email,
                            p.photographer_scope,
                            p.photographer_photo,
                            p.photographer_address,
                            tow.type_of_work_rate_half,
                            tow.type_of_work_rate_full,
                            t.type_work,
                            p.photographer_id
                        FROM 
                            photographer p
                        LEFT JOIN 
                            type_of_work tow ON p.photographer_id = tow.photographer_id
                        LEFT JOIN 
                            type t ON t.type_id = tow.type_id";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                $photographers = [];

                                // Store data in an array for processing
                                while ($row = $result->fetch_assoc()) {
                                    if (!isset($photographers[$row['photographer_id']])) {
                                        $photographers[$row['photographer_id']] = [
                                            'photographer_prefix' => $row['photographer_prefix'],
                                            'photographer_name' => $row['photographer_name'],
                                            'photographer_surname' => $row['photographer_surname'],
                                            'photographer_tell' => $row['photographer_tell'],
                                            'photographer_email' => $row['photographer_email'],
                                            'photographer_scope' => $row['photographer_scope'],
                                            'photographer_photo' => $row['photographer_photo'],
                                            'photographer_address' => $row['photographer_address'],
                                            'type_of_work' => []
                                        ];
                                    }

                                    if ($row['type_work'] !== null) {
                                        $photographers[$row['photographer_id']]['type_of_work'][] = [
                                            'type_work' => $row['type_work'],
                                            'rate_half' => (int)$row['type_of_work_rate_half'],
                                            'rate_full' => (int)$row['type_of_work_rate_full']
                                        ];
                                    }
                                }

                                foreach ($photographers as $photographer_id => $photographer) {
                            ?>
                                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                                        <div class="property-item rounded overflow-hidden bg-white" style="height: auto; width: 600px;">
                                            <div class="row">
                                                <div class="col-6 position-relative overflow-hidden">
                                                    <a href="profile_photographer.php?photographer_id=<?php echo $photographer_id; ?>">
                                                        <img class="img-fluid" src="../img/profile/<?php echo isset($photographer['photographer_photo']) ? $photographer['photographer_photo'] : 'default.jpg'; ?>" alt="">
                                                    </a>
                                                    <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">
                                                        <?php echo $photographer['photographer_prefix'] . ' ' . $photographer['photographer_name'] . ' ' . $photographer['photographer_surname']; ?>
                                                    </div>
                                                </div>
                                                <div class="col-6 p-4 pb-0">
                                                    <!-- <?php if (!empty($photographer['type_of_work'])) { ?>
                                                        <?php foreach ($photographer['type_of_work'] as $work) { ?>
                                                            <p class="text-dark mb-3">
                                                                <?php echo $work['type_work']; ?>
                                                                <?php if ($work['rate_half'] > 0) { ?>
                                                                    <?php echo 'ราคาครึ่งวัน ', number_format($work['rate_half']); ?>
                                                                <?php } ?>
                                                                <?php if ($work['rate_full'] > 0) { ?>
                                                                    <?php echo 'ราคาเต็มวัน ', number_format($work['rate_full']); ?>
                                                                <?php } ?>
                                                            </p>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <p class="text-dark mb-3">ยังไม่ได้ลงประเภทงาน</p>
                                                    <?php } ?> -->
                                                    <a class="d-block mb-3" href="mailto:<?php echo isset($photographer['photographer_email']) ? $photographer['photographer_email'] : '#'; ?>">
                                                        <?php echo isset($photographer['photographer_email']) ? $photographer['photographer_email'] : 'Email not available'; ?>
                                                    </a>
                                                    <p class="text-dark mb-3">โทร <?php echo isset($photographer['photographer_tell']) ? $photographer['photographer_tell'] : 'Phone number not available'; ?></p>
                                                    <p><i class="fa fa-map-marker-alt text-dark me-2"></i><?php echo isset($photographer['photographer_scope']) ? $photographer['photographer_scope'] : 'Scope not available'; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                            } else {
                                ?>
                                <div class="card-body bg-white" style="height: 132px; width:auto;">
                                    <center>
                                        <i class="fas fa-exclamation-triangle fa-3x text-danger"></i>
                                        <h1 class="">ไม่พบข้อมูลที่คุณต้องการ</h1>
                                    </center>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    ?>

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white-50 footer wow fadeIn">
        <div class="copyright">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; <a class="border-bottom" href="#">2024 Photo Match</a>, All Right Reserved.
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-dark btn-lg-square back-to-top" style="background-color:#1E2045"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/wow/wow.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- select date -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.6/flatpickr.min.js"></script>
    <script src="../js/main.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous"></script>
    <!-- Fancybox JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
    <script>
        flatpickr("#dateRangePicker", {
            mode: "range",
            dateFormat: "Y-m-d"
        });
    </script>

    <!-- Template Javascript -->
    <script src="../js/main.js"></script>
</body>

</html>