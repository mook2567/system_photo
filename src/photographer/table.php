<?php
session_start();
include '../config_db.php';
require_once '../popup.php';

$sql = "SELECT * FROM `information`";
$resultInfo = $conn->query($sql);
$rowInfo = $resultInfo->fetch_assoc();

if (isset($_SESSION['photographer_login'])) {
    $email = $_SESSION['photographer_login'];
    $sql = "SELECT * FROM photographer WHERE photographer_email LIKE '$email'";
    $resultPhoto = $conn->query($sql);
    $rowPhoto = $resultPhoto->fetch_assoc();
    $id_photographer = $rowPhoto['photographer_id'];
}

// $booking = array();
$booking = array(); // Initialize $booking to avoid undefined variable issues
if ($id_photographer !== null) {
    $stmt = $conn->prepare("SELECT * FROM booking WHERE photographer_id = ? AND booking_confirm_status IN (0, 1, 3)");
    $stmt->bind_param("i", $id_photographer);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $booking[] = $row;
    }
}

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
                                <!-- <a href="bookingListAll.php" class="dropdown-item">รายการจองทั้งหมด</a> -->
                                <a href="bookingListWaittingForApproval.php" class="dropdown-item active">รายการจองที่รออนุมัติ</a>
                                <a href="bookingListApproved.php" class="dropdown-item">รายการจองที่อนุมัติแล้ว</a>
                                <a href="bookingListConfirmPayment.php" class="dropdown-item">รายการจองที่รอตรวจสอบการชำระ</a>
                                <a href="bookingListSend.php" class="dropdown-item">รายการจองที่ต้องส่งงาน</a>
                                <a href="bookingListApproved.php" class="dropdown-item">รายการจองที่เสร็จสิ้นแล้ว</a>
                                <a href="bookingListNotApproved.php" class="dropdown-item">รายการจองที่ไม่อนุมัติ</a>
                            </div>
                        </div>
                        <a href="report.php" class="nav-item nav-link">รายงาน</a>
                        <a href="dashboard.php" class="nav-item nav-link">สถิติ</a>
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
            <div class="col-md-5 p-5 mt-lg-5 text-md-end">
                <br><br>
                <h1 class="display-7 animated fadeIn mb-1 text-white f text-md-end">ตารางงานของคุณ</h1>
            </div>
            <div class="col-5 p-5 mt-5 text-end text-white mt-lg-5">
                <h5 class="mt-5 text-white">หมายเหตุ <i class="fa-solid fa-circle-exclamation"></i></h5>
                <h5 class="text-white">สีฟ้า คือ รายการจองที่รออนุมัติ</h5>
                <h5 class="text-white">สีเหลือง คือ รายการจองครึ่งวัน</h5>
                <h5 class="text-white">สีส้ม คือ รายการจองเต็มวัน</h5>
                <h5 class="text-white">สีม่วง คือ รายการจองที่เสร็จสิ้น</h5>
            </div>
        </div>
    </div>
    <!-- Header End -->
    <div class="center bg-white" style="height: auto;"><br>
        <div class="bg-white">
            <div id='calendar' style="width: 45%;" class="bg-white"></div>
        </div>
        <div class="row justify-content-center mt-3 container-center text-center">
            <div class="col-md-12 mb-3">
                <button onclick="window.history.back();" class="btn btn-danger me-4" style="width: 150px; height:45px;">ย้อนกลับ</button>
                <button id="saveButton" onclick="window.location.href='bookingListWaittingForApproval.php'" class="btn btn-primary" style="width: 150px; height: 45px;">ดูเพิ่มเติม</button>
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
        $(function() {
            // Get the booking data from PHP
            var bookings = <?php echo json_encode($booking); ?>;

            // Format the booking data for FullCalendar
            var events = bookings.map(function(booking) {
                var startDate = new Date(booking.booking_start_date + 'T' + booking.booking_start_time);
                var endDate = new Date(booking.booking_end_date + 'T' + booking.booking_end_time);
                var isHalfDay = (startDate.getHours() < 12 && endDate.getHours() <= 12) ||
                    (startDate.getHours() >= 12 && endDate.getHours() > 12);

                var eventColor;
                var eventTextColor;
                var eventTitle;

                // Determine color and title based on booking confirmation status
                if (booking.booking_confirm_status == 0) { // Unapproved bookings
                    eventColor = '#edf6fa'; // Light gray
                    eventTextColor = 'black';
                    eventTitle = 'รออนุมัติ';
                } else if (booking.booking_confirm_status == 1) { // Approved bookings
                    eventColor = isHalfDay ? '#e9ec86' : '#ecab86'; // Light yellow for half-day/full-day
                    eventTextColor = 'black';
                    eventTitle = isHalfDay ? 'ครึ่งวัน' : 'เต็มวัน';
                } else if (booking.booking_confirm_status == 3) { // New completed bookings
                    eventColor = '#f8d7da'; // Light red for finished bookings
                    eventTextColor = 'black';
                    eventTitle = 'เสร็จสิ้นแล้ว';
                }

                const startTime = startDate.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });
                const endTime = endDate.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                return {
                    title: eventTitle + ' (' + startTime + ' - ' + endTime + ')',
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

            // Initialize the FullCalendar
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: ['dayGrid', 'interaction'],
                editable: true,
                events: events
            });

            // Render the calendar
            calendar.render();
        });
    </script>

</body>

</html>