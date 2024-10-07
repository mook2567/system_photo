<?php
session_start();
include '../config_db.php';
require_once '../popup.php';

// $sql = "SELECT * FROM `customer` WHERE cus_license = '1'";
// $result = $conn->query($sql);

// Fetching data from 'information' table
$sqlInformation = "SELECT * FROM `information`";
$resultInformation = $conn->query($sqlInformation);
$rowInformation = $resultInformation->fetch_assoc();

$information_name = $rowInformation['information_name'];
$information_caption = $rowInformation['information_caption'];
$rowInformation['information_icon'];
// สร้างพาธของไฟล์ภาพ
$image_path = '../img/logo/' . $rowInformation['information_icon'];

if (file_exists($image_path)) {
    $image_data = base64_encode(file_get_contents($image_path));
    $image_type = pathinfo($image_path, PATHINFO_EXTENSION);
    $image_base64 = 'data:image/' . $image_type . ';base64,' . $image_data;
} else {
    $image_base64 = ''; // Handle case if the image doesn't exist
}

$sqlUser = "SELECT 
                t.type_work, 
                COUNT(tow.type_id) AS total_count, 
                COUNT(b.booking_id) AS total_count_b,
                SUM(CASE WHEN b.booking_confirm_status = 1 THEN 1 ELSE 0 END) AS total_count_a,
                SUM(CASE WHEN b.booking_confirm_status = 3 THEN 1 ELSE 0 END) AS total_count_s,
                SUM(CASE WHEN b.booking_confirm_status = 2 THEN 1 ELSE 0 END) AS total_count_d,
                MIN(CASE WHEN tow.type_of_work_rate_half_start != 0 THEN tow.type_of_work_rate_half_start END) AS min_half_rate,
                MIN(CASE WHEN tow.type_of_work_rate_full_start != 0 THEN tow.type_of_work_rate_full_start END) AS min_full_rate,
                SUM(b.booking_price) AS income
            FROM 
                type_of_work tow
            JOIN 
                type t ON t.type_id = tow.type_id
            LEFT JOIN 
                booking b ON b.type_of_work_id = tow.type_of_work_id
            GROUP BY 
                t.type_work
";
$resultUser = $conn->query($sqlUser);


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

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.6.0/dist/jspdf.plugin.autotable.min.js"></script>

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

        .table th,
        .table td {
            vertical-align: middle;
            /* จัดการให้เนื้อหาตรงกลางของเซลล์ */
        }

        .table th.text-center,
        .table td.text-center {
            text-align: center;
            /* จัดการให้เนื้อหาอยู่ตรงกลางของเซลล์ */
        }

        .table .btn {
            width: 150px;
        }

        /* 3. เพิ่มการเรียงลำดับให้เป็นแถวคู่-คี่ */
        .table tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
            /* สลับสีพื้นหลังแถว */
        }


        .table th:nth-child(1),
        .table td:nth-child(1) {
            width: 100px;
            height: 50px;
            overflow: hidden;
            text-align: center;
            white-space: nowrap;
        }

        .table th:nth-child(2),
        .table th:nth-child(3),
        .table th:nth-child(4),
        .table td:nth-child(2),
        .table td:nth-child(3),
        .table td:nth-child(4) {
            width: 200px;
            height: 50px;
        }


        .table th:nth-child(5),
        .table th:nth-child(6),
        .table th:nth-child(7),
        .table th:nth-child(8),
        .table th:nth-child(9),
        .table td:nth-child(5),
        .table td:nth-child(6),
        .table td:nth-child(7),
        .table td:nth-child(8),
        .table td:nth-child(8) {
            width: 100px;
            text-align: center;
            height: 50px;
        }

        .table .btn {
            width: 100px;
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
        <a href="index.html" class="navbar-brand ms-5 d-flex align-items-center text-center">
            <img class="img-fluid" src="../img/logo/<?php echo isset($rowInformation['information_icon']) ? $rowInformation['information_icon'] : ''; ?>" style="height: 30px;">
        </a>
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon text-primary"></span>
        </button>
        <div class="collapse me-5 navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto f">
                <a href="index.php" class="nav-item nav-link ">หน้าหลัก</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle bg-dark" data-bs-toggle="dropdown">ข้อมูลพื้นฐาน</a>
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
                    <a href="#" class="nav-link dropdown-toggle bg-dark active" data-bs-toggle="dropdown">รายงาน</a>
                    <div class="dropdown-menu rounded-0 m-0">
                        <a href="reportUser.php" class="dropdown-item">รายงานข้อมูลผู้ใช้งานระบบ</a>
                        <a href="reportCustomer.php" class="dropdown-item">รายงานข้อมูลลูกค้า</a>
                        <a href="reportPhotographer.php" class="dropdown-item">รายงานข้อมูลช่างภาพ</a>
                        <a href="reportType.php" class="dropdown-item active">รายงานข้อมูลประเภทงาน</a>
                    </div>
                </div>
                <a href="../logout.php" class="nav-item nav-link">ออกจากระบบ</a>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->
    <div style="height: 100%;">
        <div class="footer-box text-center mt-5" style="font-size: 18px;"><b>รายการข้อมูลประเภทงาน</b></div>
        <div class="container-sm mt-2 table-responsive col-10">
            <table id="example" class="table bg-white table-hover table-bordered-3">
                <thead>
                    <tr>
                        <th scope="col">ลำดับที่</th>
                        <th scope="col">ประเภทงาน</th>
                        <th scope="col">ราคาครึ่งวันเริ่มต้น (บาท)</th>
                        <th scope="col">ราคาเต็มวันเริ่มต้น (บาท)</th>
                        <th scope="col">จำนวนการจอง</th>
                        <th scope="col">จำนวนการจองที่ยังไม่สำเร็จ</th>
                        <th scope="col">จำนวนการจองสำเร็จ</th>
                        <th scope="col">จำนวนการจองที่ไม่สำเร็จ</th>
                        <th scope="col">รายได้</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($resultUser->num_rows > 0) {
                        $counter = 1; // เริ่มตัวนับที่ 1
                        while ($rowUser = $resultUser->fetch_assoc()) {
                    ?>
                            <tr>
                                <td><?php echo $counter++; ?></td> <!-- ใช้ตัวนับแทน id -->
                                <td><?php echo $rowUser['type_work']; ?></td>
                                <td><?php echo $rowUser['min_half_rate']; ?></td>
                                <td><?php echo $rowUser['min_full_rate']; ?></td>
                                <td><?php echo $rowUser['total_count_b']; ?></td>
                                <td><?php echo $rowUser['total_count_a']; ?></td>
                                <td><?php echo $rowUser['total_count_s']; ?></td>
                                <td><?php echo $rowUser['total_count_d']; ?></td>
                                <td><?php echo $rowUser['income']; ?></td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='8'>ไม่พบข้อมูล</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="row justify-content-center mt-3 container-center text-center">
            <div class="col-md-12">
                <!-- ตำแหน่งสำหรับปุ่ม "ย้อนกลับ" -->
                <button onclick="window.history.back();" class="btn me-4" style="background-color:gray; color:#fff; width: 150px; height:45px;">ย้อนกลับ</button>
                <button id="generatePDF" class="btn btn-primary" style="width: 150px; height:45px;">ออก PDF</button>
            </div>
        </div>
    </div>
    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white-50 footer" data-wow-delay="0.1s">
        <div class="copyright">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; <a class="border-bottom" href="#">2024 Photo Match</a>, All Right Reserved.
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->
    <!-- Template Javascript -->
    <script src="../js/main.js"></script>
    <script>
        document.getElementById("generatePDF").addEventListener("click", function() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();

            // Add custom font (THSarabunNew)
            var fontBase64 = "<?php echo $fontBase64; ?>";
            if (fontBase64) {
                doc.addFileToVFS('THSarabunNew.ttf', fontBase64);
                doc.addFont('THSarabunNew.ttf', 'customFont', 'normal');
                doc.setFont('customFont');
            }

            // Add image
            var imgBase64 = "<?php echo $image_base64; ?>";
            if (imgBase64) {
                const imageType = imgBase64.includes("jpeg") || imgBase64.includes("jpg") ? 'JPEG' : 'PNG';
                doc.addImage(imgBase64, imageType, 10, 10, 45, 12); // ปรับขนาดของภาพ

                // Add system name under the image
                var informationName = "<?php echo $information_name; ?>";
                doc.setFontSize(20); // ขนาดตัวอักษร
                doc.text(informationName, 15, 30); // ปรับตำแหน่งตัวอักษรใต้ภาพ

                // Add detail text on a new line
                var informationCaption = "<?php echo $information_caption; ?>";
                doc.setFontSize(16); // ขนาดตัวอักษร
                doc.text(informationCaption, 15, 37); // ปรับตำแหน่งข้อความเพิ่มเติม
            }

            // Define table content
            const table = document.getElementById("example");
            const rows = [...table.querySelectorAll('tbody tr')].map(tr => {
                const cells = tr.querySelectorAll('td');
                return [...cells].map(td => td.innerText);
            });

            // Add table with adjusted position
            doc.autoTable({
                startY: 40, // เริ่มแสดงตารางที่ตำแหน่ง Y หลังจากภาพ
                head: [
                    ['ลำดับที่', 'ประเภทงาน', 'ราคาครึ่งวันเริ่มต้น (บาท)', 'ราคาเต็มวันเริ่มต้น (บาท)', 'จำนวนการลงประเภทงาน', 'จำนวนการจอง']
                ],
                body: rows,
                styles: {
                    fontSize: 16, // ขนาดตัวอักษร
                    font: 'customFont',
                }
            });

            // Save the PDF
            doc.save("user_list.pdf");


        });
    </script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "language": {
                    "sProcessing": "กำลังดำเนินการ...",
                    "sLengthMenu": "แสดง _MENU_ แถว",
                    "sZeroRecords": "ไม่พบข้อมูล",
                    "sInfo": "แสดง _START_ ถึง _END_ จาก _TOTAL_ แถว",
                    "sInfoEmpty": "แสดง 0 ถึง 0 จาก 0 แถว",
                    "sInfoFiltered": "(กรองข้อมูล _MAX_ ทุกแถว)",
                    "sSearch": "ค้นหา:",
                    "oPaginate": {
                        "sFirst": "แรก",
                        "sPrevious": "ก่อนหน้า",
                        "sNext": "ถัดไป",
                        "sLast": "สุดท้าย"
                    }
                }
            });
        });
    </script>

</body>

</html>