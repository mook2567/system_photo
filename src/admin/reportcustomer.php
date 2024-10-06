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

$sqlUser = "
SELECT 
    cus.cus_id AS id, 
    cus.cus_prefix AS prefix, 
    cus.cus_name AS firstname, 
    cus.cus_surname AS surname, 
    cus.cus_tell AS phone, 
    cus.cus_district AS district, 
    cus.cus_province AS province, 
    cus.cus_email AS email, 
    cus.cus_license AS license, 
    'ลูกค้า' AS types, 
    COUNT(b.cus_id) AS num,
    SUM(CASE WHEN b.booking_pay_status = 5 THEN 1 ELSE 0 END) AS completed_bookings,
    SUM(CASE WHEN b.booking_pay_status = 0 AND b.booking_confirm_status = 1 THEN 1 ELSE 0 END) AS unpaid_bookings,
    SUM(CASE WHEN b.booking_pay_status = 0 AND b.booking_confirm_status = 2 THEN 1 ELSE 0 END) AS not_bookings,
    COUNT(r.booking_id) AS review_count
FROM 
    customer cus 
LEFT JOIN 
    booking b ON b.cus_id = cus.cus_id 
LEFT JOIN 
    review r ON r.booking_id = b.booking_id 
WHERE 
    cus.cus_license = '1' 
GROUP BY 
    cus.cus_id, 
    cus.cus_prefix, 
    cus.cus_name, 
    cus.cus_surname, 
    cus.cus_tell, 
    cus.cus_district, 
    cus.cus_province, 
    cus.cus_email, 
    cus.cus_license;
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

    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.6.0/dist/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.0.0-rc.5/html2canvas.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

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
            width: 50px;
            /* ลำดับที่ */
            height: 50px;
            overflow: hidden;
            text-align: center;
            white-space: nowrap;
        }

        .table th:nth-child(2),
        .table td:nth-child(2) {
            width: 200px;
            /* ชื่อ-นามสกุล */
            height: 50px;
        }

        .table th:nth-child(3),
        .table td:nth-child(3) {
            width: 80px;
            /* เบอร์โทรศัพท์ */
            height: 50px;
        }

        .table th:nth-child(4),
        .table td:nth-child(4) {
            width: 100px;
            /* อีเมล */
            height: 50px;
        }

        .table th:nth-child(5),
        .table th:nth-child(6),
        .table th:nth-child(7),
        .table th:nth-child(8),
        .table th:nth-child(9) {
            width: 40px;
            /* จำนวนการจอง, จำนวนการจองที่ถูกปฏิเสธ, จำนวนการจองที่ยังไม่ชำระ, จำนวนการจองที่ชำระเสร็จสิ้น, จำนวนการจองที่รีวิว */
            height: 50px;
            text-align: center;
        }

        .table td:nth-child(5),
        .table td:nth-child(6),
        .table td:nth-child(7),
        .table td:nth-child(8),
        .table td:nth-child(9) {
            width: 40px;
            /* จำนวนการจอง, จำนวนการจองที่ถูกปฏิเสธ, จำนวนการจองที่ยังไม่ชำระ, จำนวนการจองที่ชำระเสร็จสิ้น, จำนวนการจองที่รีวิว */
            height: 50px;
            text-align: center;
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
                        <a href="reportCustomer.php" class="dropdown-item active">รายงานข้อมูลลูกค้า</a>
                        <a href="reportPhotographer.php" class="dropdown-item">รายงานข้อมูลช่างภาพ</a>
                        <a href="reportType.php" class="dropdown-item">รายงานข้อมูลประเภทงาน</a>
                    </div>
                </div>
                <a href="../logout.php" class="nav-item nav-link">ออกจากระบบ</a>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->
    <div id="contentToConvert">
        <div style="height: 100%; ">
            <div class="footer-box text-center mt-5" style="font-size: 18px;"><b>รายการข้อมูลลูกค้า</b></div>
            <div class="container-lg mt-2 table-responsive" style="max-width: 90%;">
                <table id="example" class="mt-5 table bg-white table-hover table-bordered-3">
                    <thead>
                        <tr>
                            <th scope="col">ลำดับที่</th>
                            <th scope="col">ชื่อ-นามสกุล</th>
                            <th scope="col">เบอร์โทรศัพท์</th>
                            <th scope="col">อีเมล</th>
                            <th scope="col">จำนวนการจอง</th>
                            <th scope="col">จำนวนการจองที่ถูกปฏิเสธ</th>
                            <th scope="col">จำนวนการจองที่ยังไม่ชำระ</th>
                            <th scope="col">จำนวนการจองที่ชำระเสร็จสิ้น</th>
                            <th scope="col">จำนวนการจองที่รีวิว</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($resultUser->num_rows > 0) {
                            $counter = 1;
                            while ($rowUser = $resultUser->fetch_assoc()) {
                        ?>
                                <tr>
                                    <td><?php echo $counter++; ?></td>
                                    <td><?php echo $rowUser['prefix'] . $rowUser['firstname'] . ' ' . $rowUser['surname']; ?></td>
                                    <td><?php echo $rowUser['phone']; ?></td>
                                    <td><?php echo $rowUser['email']; ?></td>
                                    <td><?php echo $rowUser['num']; ?></td>
                                    <td><?php echo $rowUser['not_bookings']; ?></td>
                                    <td><?php echo $rowUser['unpaid_bookings']; ?></td>
                                    <td><?php echo $rowUser['completed_bookings']; ?></td>
                                    <td><?php echo $rowUser['review_count']; ?></td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='9'>ไม่พบข้อมูล</td></tr>"; // แก้ไขให้ colspan = 9
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mt-3 container-center text-center">
        <div class="col-md-12">
            <button onclick="window.history.back();" class="btn me-4" style="background-color:gray; color:#fff; width: 150px; height:45px;">ย้อนกลับ</button>
            <button id="generatePDF" class="btn btn-primary" style="width: 150px; height:45px;">ออก PDF</button>
        </div><br><br><br>
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
        const content = document.getElementById("contentToConvert");

        // Increase the scale of the canvas to improve resolution
        html2canvas(content, {
            scale: 5, // Increase scale for higher quality
            useCORS: true, // Allows cross-origin resources
        }).then(function(canvas) {
            const imgData = canvas.toDataURL('image/png');
            const { jsPDF } = window.jspdf;

            // Change orientation to landscape ('l')
            const doc = new jsPDF('l', 'mm', 'a4');

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
                const imgWidth = 297; // A4 width in landscape mode (297mm)
                const pageHeight = 210; // A4 height in landscape mode (210mm)
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
                doc.save('report-cus.pdf');
            }
        });
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