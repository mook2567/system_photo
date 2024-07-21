<?php
session_start();
include '../config_db.php';
require_once '../popup.php';

$sql = "SELECT * FROM `information`";
$resultInfo = $conn->query($sql);
$rowInfo = $resultInfo->fetch_assoc();

$sql = "SELECT * FROM `type`";
$resultType = $conn->query($sql);

if (isset($_SESSION['cus_login'])) {
    $email = $_SESSION['cus_login'];
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

    <!-- font awaysome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Athiti&family=Merriweather:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Athiti', sans-serif;
            background-color: #F0F2F5;
        }

        .f {
            font-family: 'Athiti', sans-serif;
        }

        .bgIndex {
            background-image: url('../img/bgIndex1.jpg');
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
        .caption {
  white-space: nowrap; /* ทำให้ข้อความไม่ขึ้นบรรทัดใหม่ */
  overflow: hidden; /* ซ่อนข้อความที่ล้น */
  text-overflow: ellipsis; /* แสดง ... เมื่อข้อความล้น */
  width: 100%; /* ตั้งค่าความกว้างตามที่ต้องการ */
  display: block; /* ทำให้พารากราฟเป็นบล็อค */
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
    <div class="bgIndex" style="height: auto;">
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
                        <a href="index.php" class="nav-item nav-link active">หน้าหลัก</a>
                        <a href="search.php" class="nav-item nav-link">ค้นหาช่างภาพ</a>
                        <a href="workings.php" class="nav-item nav-link">ผลงานช่างภาพ</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">รายการจองคิวช่างภาพ</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="bookingLists.php" class="dropdown-item">รายการจองคิวทั้งหมด</a>
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

        <!-- Category Start -->
        <div class="mt-4 d-flex justify-content-center align-items-center" style="border-radius: 10px;">
            <div class="container-xxl py-5">
                <div class="container">
                    <div class="mb-3 mt-4 text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                        <h1 class="f" style="color:aliceblue;">Photo Match</h1>
                        <p style="color:aliceblue;">เว็บไซต์ที่จะช่วยคุณหาช่างภาพที่คุณต้องการ</p>
                    </div>
                    <div class="row g-5 mt-2 justify-content-start">
                        <?php
                        if ($resultType->num_rows > 0) {
                            while ($rowType = $resultType->fetch_assoc()) {
                        ?>
                                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                                    <a class="cat-item bg-light text-center" href="">
                                        <div class="rounded p-4" style="font-size: 60.9px;">
                                            <img src="../img/icon/<?php echo $rowType['type_icon']; ?>" style="height: 75px; width: 75px;"></img>
                                            <h6 class="f mt-3"><?php echo $rowType['type_work']; ?></h6>
                                        </div>
                                    </a>
                                </div>
                        <?php
                            }
                        } ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Category End -->

        <!-- Search Start -->
        <div class="mt-5 wow fadeIn" style="background-color: rgba(250, 250, 250, 0.4); padding: 35px;" data-wow-delay="0.1s">
            <div class="container">
                <div class="row flex-row g-2 align-items-center">
                    <h2 class="text-white">ค้นหาช่างภาพ</h2>
                    <div class="col-md-3">
                        <form action="search.php" method="POST">
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

    <!-- Examples of work Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-0 gx-5 align-items-end">
                <div class="col-lg-6">
                    <div class="text-start mx-auto mb-5 wow slideInLeft" data-wow-delay="0.1s">
                        <h1 class="mb-3 f">ช่างภาพแนะนำ</h1>
                    </div>
                </div>

            </div>
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
                    INNER JOIN 
                        type_of_work tow ON p.photographer_id = tow.photographer_id
                    INNER JOIN 
                        type t ON t.type_id = tow.type_id LIMIT 6";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            $photographers = [];

                            // Store data in an array for processing
                            while ($row = $result->fetch_assoc()) {
                                $photographers[$row['photographer_id']]['photographer_prefix'] = $row['photographer_prefix'];
                                $photographers[$row['photographer_id']]['photographer_name'] = $row['photographer_name'];
                                $photographers[$row['photographer_id']]['photographer_surname'] = $row['photographer_surname'];
                                $photographers[$row['photographer_id']]['photographer_tell'] = $row['photographer_tell'];
                                $photographers[$row['photographer_id']]['photographer_email'] = $row['photographer_email'];
                                $photographers[$row['photographer_id']]['photographer_scope'] = $row['photographer_scope'];
                                $photographers[$row['photographer_id']]['photographer_photo'] = $row['photographer_photo'];
                                $photographers[$row['photographer_id']]['photographer_address'] = $row['photographer_address'];
                                $photographers[$row['photographer_id']]['type_of_work'][] = [
                                    'type_work' => $row['type_work'],
                                    'rate_half' => (int)$row['type_of_work_rate_half'],
                                    'rate_full' => (int)$row['type_of_work_rate_full']
                                ];
                            }

                            foreach ($photographers as $photographer_id => $photographer) {
                        ?>
                                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                                    <div class="property-item rounded overflow-hidden bg-white" style="height: auto; width: 600px;">
                                        <div class="row">
                                            <div class="col-5 position-relative overflow-hidden">
                                                <a href="profile_photographer.php?photographer_id=<?php echo $photographer_id; ?>">
                                                    <img class="img-fluid" src="../img/profile/<?php echo isset($photographer['photographer_photo']) ? $photographer['photographer_photo'] : 'default.jpg'; ?>" alt="">
                                                </a>
                                                <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3">
                                                    <?php echo $photographer['photographer_prefix'] . ' ' . $photographer['photographer_name'] . ' ' . $photographer['photographer_surname']; ?>
                                                </div>
                                            </div>
                                            <div class="col-7 p-4 pb-0">
                                                <!-- <p class="text-dark mb-3"><?php echo isset($photographer['photographer_address']) ? $photographer['photographer_address'] : 'Address not available'; ?></p> -->
                                                <?php foreach ($photographer['type_of_work'] as $work) { ?>
                                                    <p class="text-dark mb-3">
                                                        <?php echo $work['type_work']; ?>
                                                        <?php echo isset($work['rate_half']) ? $work['rate_half'] : 'Rate half not available'; ?>
                                                        <?php echo isset($work['rate_full']) ? $work['rate_full'] : 'Rate full not available'; ?>
                                                    </p>
                                                <?php } ?>
                                                <a class="d-block mb-2" href="mailto:<?php echo isset($photographer['photographer_email']) ? $photographer['photographer_email'] : '#'; ?>">
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
                            echo "0 results";
                        }
                        ?>
                    </div>
                </div>
                <div class="col-12 text-center wow fadeInUp mt-5" data-wow-delay="0.1s">
                    <a class="btn btn-dark py-3 px-5" href="search.php">ดูเพิ่มเติม</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Examples of work End -->
    <!-- Examples of work Start -->
    <div class="container-xxl py-2">
        <div class="container">
            <div class="row g-0 gx-5 align-items-end">
                <div class="col-lg-6">
                    <div class="text-start mx-auto mb-5 wow slideInLeft" data-wow-delay="0.1s">
                        <h1 class="mb-3 f">ผลงานช่างภาพ</h1>
                        <p>คุณลองดูผลงานช่างภาพของเราสิ!!!</p>
                    </div>
                </div>
                <div class="row g-4">
                    <?php
                    $sql = "SELECT 
                        po.portfolio_id, 
                        po.portfolio_photo, 
                        po.portfolio_caption, 
                        t.type_work,
                        t.type_icon,
                        p.photographer_id,
                        CAST( tow.type_of_work_rate_half  AS UNSIGNED) AS rate_half,
                        CAST( tow.type_of_work_rate_full AS UNSIGNED) AS rate_full,
                        p.photographer_name,
                        p.photographer_surname,
                        p.photographer_scope
                    FROM 
                        portfolio po 
                    LEFT JOIN 
                        type_of_work tow ON po.type_of_work_id = tow.type_of_work_id 
                    LEFT JOIN 
                        photographer p ON p.photographer_id = tow.photographer_id
                    LEFT JOIN 
                        type t ON t.type_id = tow.type_id
                    ORDER BY 
                        po.portfolio_id DESC;";
                    $resultPost = $conn->query($sql);
                    if ($resultPost->num_rows > 0) {
                        while ($rowPost = $resultPost->fetch_assoc()) {
                    ?>
                            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                                <div class="property-item rounded overflow-hidden bg-white">
                                    <div class="position-relative overflow-hidden">
                                        <a><img class="img-fluid property-img" src="../img/post/<?php echo explode(',', $rowPost['portfolio_photo'])[0]; ?>" alt=""></a>
                                        <div class="bg-white rounded-top text-dark position-absolute start-0 bottom-0 mx-4 pt-1 px-3"><?php echo $rowPost['type_work']; ?></div>
                                    </div>
                                    <div class="p-4 pb-0">
                                        <p class="caption"><?php echo $rowPost['portfolio_caption']; ?></p>
                                        <a class="d-block h5 mb-2" href="profile_photographer.php?photographer_id=<?php echo $rowPost['photographer_id']; ?>"><?php echo $rowPost['photographer_name'] . ' ' . $rowPost['photographer_surname']; ?></a>
                                        <p><i class="fa fa-map-marker-alt text-dark me-2"></i><?php echo $rowPost['photographer_scope']; ?></p>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
                <div class="col-12 text-center wow fadeInUp mt-5" data-wow-delay="0.1s">
                    <a class="btn btn-dark py-3 px-5" href="workings.php">ดูเพิ่มเติม</a>
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