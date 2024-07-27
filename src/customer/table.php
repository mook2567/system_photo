<?php
session_start();
include '../config_db.php';
require_once '../popup.php';

$sql = "SELECT * FROM `information`";
$resultInfo = $conn->query($sql);
$rowInfo = $resultInfo->fetch_assoc();

$sql = "SELECT * FROM `photographer`";
$resultPhoto = $conn->query($sql);
$rowPhoto = $resultPhoto->fetch_assoc();

if (isset($_SESSION['customer_login'])) {
    $email = $_SESSION['customer_login'];
    $sql = "SELECT * FROM customer WHERE cus_email LIKE '$email'";
    $resultCus = $conn->query($sql);
    $rowCus = $resultCus->fetch_assoc();
    $id_cus = $rowCus['cus_id'];
}

// รับค่าจาก URL query parameters
$id_photographer = isset($_GET['id_photographer']) ? intval($_GET['id_photographer']) : null;

$booking = array();
if ($id_photographer !== null) {
    $stmt = $conn->prepare("SELECT * FROM booking WHERE photographer_id = ?");
    $stmt->bind_param("i", $id_photographer);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $booking[] = $row;
    }
}

// ตรวจสอบข้อมูล
// echo '<pre>';
// print_r($booking);
// echo '</pre>';


// Return bookings data as JSON
// echo json_encode($booking);

$fullcalendar_path = "fullcalendar-4.4.2/packages/";
?>
<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset="utf-8">
    <title>Photo Match</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="icon" type="image/png" href="../img/icon-logo.png">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

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


    <link href='<?= $fullcalendar_path ?>/core/main.css' rel='stylesheet' />
    <link href='<?= $fullcalendar_path ?>/daygrid/main.css' rel='stylesheet' />

    <script src='<?= $fullcalendar_path ?>/core/main.js'></script>
    <script src='<?= $fullcalendar_path ?>/daygrid/main.js'></script>

    <style type="text/css">
        body {
            font-family: 'Athiti', sans-serif;
            overflow-x: hidden;
        }

        .f {
            font-family: 'Athiti', sans-serif;
        }

        #calendar {
            width: 800px;
            margin: auto;
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
    </style>

</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-dark" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Navbar Start -->
        <div class="container-fluid nav-bar bg-transparent">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-0 px-4">
                <a href="index.php" class="navbar-brand d-flex align-items-center text-center" style="height: 70px;">
                    <img class="img-fluid" src="../img/logo/<?php echo isset($rowInfo['information_icon']) ? $rowInfo['information_icon'] : ''; ?>" style="height: 30px;">
                </a>
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon text-primary"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto f">
                        <a href="index.php" class="nav-item nav-link">หน้าหลัก</a>
                        <a href="table.php" class="nav-item nav-link active">ตารางงาน</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle bg-dark" data-bs-toggle="dropdown">รายการจอง</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="bookingListAll.php" class="dropdown-item">รายการจองทั้งหมด</a>
                                <a href="bookingListWaittingForApproval.php" class="dropdown-item">รายการจองที่รออนุมัติ</a>
                                <a href="bookingListApproved.php" class="dropdown-item">รายการจองที่อนุมัติแล้ว</a>
                                <a href="bookingListNotApproved.php" class="dropdown-item">รายการจองที่ไม่อนุมัติ</a>
                            </div>
                        </div>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle bg-dark" data-bs-toggle="dropdown">โปรไฟล์</a>
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
    </div>
    <!-- Navbar End -->

    <!-- Header Start -->
    <div class="container-fluid header bg-primary p-1" style="height: 300px;">
        <div class="row g-1 align-items-center flex-column-reverse flex-md-row">
            <div class="col-md-5 p-5 mt-lg-5">
                <br><br>
                <h1 class="display-7 animated fadeIn mb-1 text-white f text-md-end">ตารางงานของช่างภาพ</h1>
                <h1 class="display-7 animated fadeIn mb-1 text-white f text-md-end">คุณ<?php echo $rowPhoto['photographer_name'] . ' ' . $rowPhoto['photographer_surname']; ?></h1>
            </div>
            <!-- <div class="col-5 p-5 mt-5 text-end mt-lg-5"><br><br>
                <h4 class="ms-5">หมายเหตุ</h4>
                <b>
                    <p style="color: red; font-size: 19px;">สีแดง คือ รายการจองเต็มวัน</p><p style="color: orange; font-size: 19px;">สีส้ม คือ รายการจองครึ่งวัน</p>
                </b>
            </div> -->
        </div>
    </div>
    <!-- Header End -->
    <div class="bg-white" style="height: 980px;"><br>
        <div class=" mt-2">
            <div class="col-12 mt-3">
                <div class="bg-white">
                    <div class="d-flex justify-content-end">
                        <div id='calendar' style="width: 55%;" class="bg-white ms-auto">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mb-5 mt-3 container-center text-center">
            <div class="col-md-12">
                <button onclick="window.history.back();" class="btn btn-danger me-4" style="width: 150px; height:45px;">ย้อนกลับ</button>
                <button type="button" data-bs-toggle="modal" data-bs-target="#details" class="btn btn-primary" style="width: 150px; height: 45px;">จองคิว</button>
            </div>
        </div>
    </div>
    <div class="modal fade" id="details" tabindex="-1" aria-labelledby="detailsLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsLabel"><b><i class="fas fa-clipboard-list"></i>&nbsp;&nbsp;จองคิวช่างภาพ</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body" style="height: auto;">
                        <div class="mt-2 container-md">
                            <div class="mt-3 col-md-12 container-fluid">
                                <div class="col-12">
                                    <div class="row mt-2">
                                        <div class="col-2">
                                            <label for="prefix" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px;font-size: 13px;"> คำนำหน้า</span>
                                            </label>
                                            <input type="text" name="prefix" class="form-control mt-1" value="<?php echo $rowCus['cus_prefix']; ?>" readonly>
                                        </div>
                                        <div class="col-5">
                                            <label for="name" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px;font-size: 13px;">ชื่อ</span>
                                            </label>
                                            <input type="text" name="name" class="form-control mt-1" value="<?php echo $rowCus['cus_name']; ?>" readonly>
                                        </div>
                                        <div class="col-5">
                                            <label for="surname" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; font-size: 13px;">นามสกุล</span>
                                            </label>
                                            <input type="text" name="surname" class="form-control mt-1" value="<?php echo $rowCus['cus_surname']; ?>" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <div class="row">
                                        <div class="col-md-4 text-center">
                                            <label for="booking-start-date" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px;font-size: 13px;">วันที่เริ่มจอง</span>
                                            </label>
                                            <input type="date" name="start_date" class="form-control mt-1" style="resize: none;">
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <label for="booking-start-time" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px;font-size: 13px;">เวลาเริ่มงาน</span>
                                            </label>
                                            <input type="time" name="start_time" class="form-control mt-1" style="resize: none;">
                                        </div>

                                        <div class="col-md-4 text-center">
                                            <label for="booking-end-date" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px;font-size: 13px;">วันที่สิ้นสุดการจอง</span>
                                            </label>
                                            <input type="date" name="end_date" class="form-control mt-1" style="resize: none;">
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <label for="booking-end-time" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px;font-size: 13px;">เวลาสิ้นสุด</span>
                                            </label>
                                            <input type="time" name="end_time" class="form-control mt-1" style="resize: none;">
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
                                    <textarea name="details" class="form-control mt-1" placeholder="กรุณากรอกคำอธิบาย" style="resize: none; height: 100px;"></textarea>
                                </div>
                                <div class="col-12">
                                    <div class="row mt-3">
                                        <div class="col-5">
                                            <label for="tell" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px;font-size: 13px;">เบอร์โทรศัพท์มือถือ</span>
                                            </label>
                                            <input type="text" name="tell" class="form-control mt-1" value="<?php echo $rowCus['cus_tell']; ?>" style="resize: none;">
                                        </div>
                                        <div class="col-5 text-center">
                                            <label for="email" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px;font-size: 13px;">อีเมล</span>
                                            </label>
                                            <input type="email" name="email" class="form-control mt-1" value="<?php echo $rowCus['cus_email']; ?>" style="resize: none;">
                                        </div>
                                        <div class="col-2">
                                            <label for="date-saved" style="font-weight: bold; display: flex; align-items: center;">
                                                <span style="color: black; margin-right: 5px;font-size: 13px;">วันที่บันทึก</span>
                                            </label>
                                            <input type="date" id="date-saved" name="date" class="form-control mt-1" style="resize: none;" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="cus_id" value="<?php echo $rowCus['cus_id']; ?>">
                            <div class="modal-footer mt-5 justify-content-center">
                                <button type="button" class="btn btn-danger" style="width: 150px; height:45px;" data-bs-dismiss="modal">ยกเลิก</button>
                                <button type="submit" name="submit_book" class="btn btn-primary" style="width: 150px; height:45px;">จองคิว</button>
                            </div>
                        </div>
                    </div>
                </form>
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

    <!-- FullCalendar JavaScript -->
    <script src='<?= $fullcalendar_path ?>/core/main.js'></script>
    <script src='<?= $fullcalendar_path ?>/daygrid/main.js'></script>

    <!-- Template Javascript -->
    <script src="../js/main.js"></script>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: ['dayGrid'],
                events: function(fetchInfo, successCallback, failureCallback) {
                    $.ajax({
                        url: 'fetchBookings.php',
                        method: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            console.log("Bookings data:", data); // ตรวจสอบข้อมูลที่ได้รับ

                            var events = data.map(function(booking) {
                                var startDate = new Date(booking.booking_start_date + 'T' + booking.booking_start_time);
                                var endDate = new Date(booking.booking_end_date + 'T' + booking.booking_end_time);
                                var isHalfDay = (startDate.getHours() < 12 && endDate.getHours() <= 12) ||
                                    (startDate.getHours() >= 12 && endDate.getHours() > 12);

                                var eventColor = isHalfDay ? 'orange' : 'red';
                                var eventTextColor = isHalfDay ? 'black' : 'white';
                                var eventTitle = isHalfDay ? 'ครึ่งวัน' : 'เต็มวัน';

                                const startTime = startDate.toLocaleTimeString([], {
                                    hour: '2-digit',
                                    minute: '2-digit'
                                });
                                const endTime = endDate.toLocaleTimeString([], {
                                    hour: '2-digit',
                                    minute: '2-digit'
                                });

                                return {
                                    title: ' (' + startTime + ' - ' + endTime + ')',
                                    start: startDate.toISOString(),
                                    end: endDate.toISOString(),
                                    color: eventColor,
                                    textColor: eventTextColor,
                                    description: booking.booking_details,
                                    extendedProps: {
                                        startTime: startTime,
                                        endTime: endTime
                                    }
                                };
                            });
                            successCallback(events);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error("Error fetching bookings:", textStatus, errorThrown);
                            console.error("Response text:", jqXHR.responseText); // ตรวจสอบเนื้อหาของข้อผิดพลาด
                            failureCallback('Failed to fetch bookings');
                        }
                    });
                },
                eventContent: function(arg) {
                    // Create div elements to display event title and times
                    var titleEl = document.createElement('div');
                    var startTimeEl = document.createElement('div');
                    var endTimeEl = document.createElement('div');

                    titleEl.innerHTML = arg.event.title;
                    startTimeEl.innerHTML = 'Start: ' + arg.event.extendedProps.startTime;
                    endTimeEl.innerHTML = 'End: ' + arg.event.extendedProps.endTime;

                    // Apply inline styles for event container
                    var eventContainer = document.createElement('div');
                    eventContainer.style.height = 'auto'; // ปรับความสูงให้เป็นอัตโนมัติ
                    eventContainer.style.overflow = 'visible'; // ทำให้เนื้อหาภายในไม่ถูกตัด
                    eventContainer.style.whiteSpace = 'normal'; // ให้ข้อความไหลลงหลายบรรทัด
                    eventContainer.style.wordWrap = 'break-word'; // การตัดคำเพื่อไม่ให้ข้อความยาวเกินขอบเขต
                    eventContainer.appendChild(titleEl);
                    eventContainer.appendChild(startTimeEl);
                    eventContainer.appendChild(endTimeEl);

                    // Append the elements
                    return {
                        domNodes: [eventContainer]
                    };
                }
            });

            calendar.render();
        });
    </script>
<script>
        // ฟังก์ชันเพื่อกำหนดวันที่ปัจจุบันให้กับฟิลด์ input
        function setDefaultDate() {
            const dateInput = document.getElementById('date-saved');
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0'); // เดือนเริ่มต้นที่ 0
            const day = String(today.getDate()).padStart(2, '0');

            const formattedDate = `${year}-${month}-${day}`;
            dateInput.value = formattedDate;
        }

        // เรียกใช้ฟังก์ชันเมื่อโหลดหน้าเว็บ
        window.onload = setDefaultDate;
    </script>

</body>

</html>