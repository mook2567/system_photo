-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql-photo
-- Generation Time: May 24, 2024 at 06:30 AM
-- Server version: 11.3.2-MariaDB-1:11.3.2+maria~ubu2204
-- PHP Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `photo_match`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL COMMENT 'รหัสผู้ดูแลระบบ',
  `admin_prefix` varchar(10) NOT NULL COMMENT 'คำนำหน้า',
  `admin_name` text NOT NULL COMMENT 'ชื่อผู้ดูแลระบบ',
  `admin_surname` varchar(50) NOT NULL COMMENT 'นามสกุล',
  `admin_tell` varchar(10) NOT NULL COMMENT 'เบอร์โทรศัพท์',
  `admin_address` text NOT NULL COMMENT 'ที่อยู่',
  `admin_district` varchar(50) NOT NULL COMMENT 'อำเภอ',
  `admin_province` varchar(50) NOT NULL COMMENT 'จังหวัด',
  `admin_zip_code` varchar(5) NOT NULL COMMENT 'รหัสไปรษณีย์',
  `admin_email` varchar(100) NOT NULL COMMENT 'อีเมล',
  `admin_password` varchar(50) NOT NULL COMMENT 'รหัสผ่าน',
  `admin_photo` text NOT NULL COMMENT 'รูปภาพโปรไฟล์',
  `admin_license` tinyint(1) NOT NULL COMMENT 'สิทธิ์การใช้งาน 0 : ไม่มีสิทธิ์ 1 : มีสิทธิ์'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(11) NOT NULL COMMENT 'รหัสจองคิว',
  `booking_location` text NOT NULL COMMENT 'สถานที่',
  `booking_price` float(8,2) NOT NULL COMMENT 'ราคา',
  `booking_details` text NOT NULL COMMENT 'รายละเอียดการจอง',
  `booking_start_date` date NOT NULL COMMENT 'วันที่เริ่มจอง',
  `booking_end_date` date NOT NULL COMMENT 'วันที่สิ้นสุดการจอง',
  `booking_pay_status` tinyint(1) NOT NULL COMMENT 'สถานะการชำระเงิน 0 : ยังไม่ชำระ 1 : ชำระค่ามัดจำ 2 : ชำระเงิน',
  `booking_start_time` time NOT NULL COMMENT 'เวลาเริ่มงาน',
  `booking_end_time` time NOT NULL COMMENT 'เวลาสิ้นสุด',
  `booking_date` date NOT NULL COMMENT 'วันที่บันทึก',
  `booking_confirm_status` tinyint(1) NOT NULL COMMENT 'สถานะยืนยันการจอง 0 : รออนุมัติ 1 : อนุมัติแล้ว\r\n2 : ไม่อนุมัติ',
  `Booking_note` text NOT NULL COMMENT 'หมายเหตุการไม่รับงาน',
  `photographer_id` int(11) NOT NULL COMMENT 'รหัสช่างภาพ',
  `cus_id` int(11) NOT NULL COMMENT 'รหัสลูกค้า',
  `type_of_work_id` int(11) NOT NULL COMMENT 'รหัสประเภทการรับงาน'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `cus_id` int(11) NOT NULL COMMENT 'รหัสลูกค้า',
  `cus_prefix` varchar(10) NOT NULL COMMENT 'คำนำหน้า',
  `cus_name` varchar(50) NOT NULL COMMENT 'ชื่อลูกค้า',
  `cus_surname` varchar(50) NOT NULL COMMENT 'นามสกุล',
  `cus_tell` varchar(10) NOT NULL COMMENT 'เบอร์โทรศัพท์',
  `cus_address` text NOT NULL COMMENT 'ที่อยู่',
  `cus_district` varchar(50) NOT NULL COMMENT 'อำเภอ',
  `cus_province` varchar(50) NOT NULL COMMENT 'จังหวัด',
  `cus_zip_code` varchar(5) NOT NULL COMMENT 'รหัสไปรษณีย์',
  `cus_email` varchar(100) NOT NULL COMMENT 'อีเมล',
  `cus_password` varchar(50) NOT NULL COMMENT 'รหัสผ่าน',
  `cus_Photo` text NOT NULL COMMENT 'รูปภาพโปรไฟล์',
  `cus_license` tinyint(1) NOT NULL COMMENT 'สิทธิ์การใช้งาน 0 : ไม่มีสิทธิ์ 1 : มีสิทธิ์\r\n'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `information`
--

CREATE TABLE `information` (
  `Information_id` int(11) NOT NULL COMMENT 'รหัสระบบ',
  `Information_Name` varchar(50) NOT NULL COMMENT 'ชื่อระบบ',
  `Information_Icon` text NOT NULL COMMENT 'โลโก้ระบบ',
  `Information_caption` text NOT NULL COMMENT 'คำอธิบาย'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pay`
--

CREATE TABLE `pay` (
  `pay_id` int(11) NOT NULL COMMENT 'รหัสชำระเงิน',
  `pay_date` date NOT NULL COMMENT 'วันที่',
  `pay_time` time NOT NULL COMMENT 'เวลา',
  `pay_type` varchar(50) NOT NULL COMMENT 'ประเภทการชำระเงิน',
  `pay_bank` varchar(50) NOT NULL COMMENT 'ธนาคาร',
  `pay_slip` text NOT NULL COMMENT 'หลักฐานการโอน',
  `pay_status` tinyint(1) NOT NULL COMMENT 'สถานะการชำระเงิน\r\n0 : ชำระค่ามัดจำ 1 : ชำระเงิน',
  `booking_id` int(11) NOT NULL COMMENT 'รหัสจองคิว'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `photographer`
--

CREATE TABLE `photographer` (
  `photographer_id` int(11) NOT NULL COMMENT 'รหัสช่างภาพ',
  `photographer_prefix` varchar(10) NOT NULL COMMENT 'คำนำหน้า',
  `photographer_name` varchar(50) NOT NULL COMMENT 'ชื่อช่างภาพ',
  `photographer_surname` varchar(50) NOT NULL COMMENT 'นามสกุล',
  `photographer_tell` varchar(10) NOT NULL COMMENT 'เบอร์โทรศัพท์',
  `photographer_address` text NOT NULL COMMENT 'ที่อยู่',
  `photographer_district` varchar(50) NOT NULL COMMENT 'อำเภอ',
  `photographer_province` varchar(50) NOT NULL COMMENT 'จังหวัด',
  `photographer_scope` text NOT NULL COMMENT 'ขอบเขตรับงาน',
  `photographer_zip_code` varchar(5) NOT NULL COMMENT 'รหัสไปรษณีย์',
  `photographer_email` varchar(100) NOT NULL COMMENT 'อีเมล',
  `photographer_password` varchar(50) NOT NULL COMMENT 'รหัสผ่าน',
  `photographer_photo` text NOT NULL COMMENT 'รูปภาพโปรไฟล์',
  `photographer_portfolio` text NOT NULL COMMENT 'ไฟล์แฟ้มผลงานช่างภาพ',
  `photographer_bank` varchar(50) NOT NULL COMMENT 'ชื่อธนาคาร',
  `photographer_account_name` varchar(100) NOT NULL COMMENT 'ชื่อบัญชีธนาคาร',
  `photographer_account_number` varchar(50) NOT NULL COMMENT 'เลขที่บัญชี',
  `photographer_license` tinyint(1) NOT NULL COMMENT 'สิทธิ์การใช้งาน 0 : ไม่มีสิทธิ์ 1 : มีสิทธิ์'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `portfolio`
--

CREATE TABLE `portfolio` (
  `portfolio_id` int(11) NOT NULL COMMENT 'รหัสผลงาน',
  `portfolio_photo` text NOT NULL COMMENT 'ภาพผลงาน',
  `portfolio_caption` text NOT NULL COMMENT 'คำอธิบายภาพ',
  `portfolio_date` date NOT NULL COMMENT 'วันที่ลงผลงาน',
  `type_of_work_id` int(11) NOT NULL COMMENT 'รหัสประเภทการรับงาน'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `booking_id` int(11) NOT NULL COMMENT 'รหัสจองคิว',
  `review_date` date NOT NULL COMMENT 'วันที่',
  `review_time` time NOT NULL COMMENT 'เวลา',
  `review_caption` text NOT NULL COMMENT 'คำอธิบาย',
  `review_level` text NOT NULL COMMENT 'ระดับความพึ่งพอใจ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `submit`
--

CREATE TABLE `submit` (
  `submit_id` int(11) NOT NULL COMMENT 'รหัสส่งงาน',
  `submit_date` date NOT NULL COMMENT 'วันที่ส่งงาน',
  `submit_time` time NOT NULL COMMENT 'เวลาส่งงาน',
  `submit_details` text NOT NULL COMMENT 'รายละเอียดการส่งงาน',
  `booking_id` int(11) NOT NULL COMMENT 'รหัสจองคิว'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `type`
--

CREATE TABLE `type` (
  `type_id` int(11) NOT NULL COMMENT 'รหัสประเภทงาน',
  `type_work` varchar(50) NOT NULL COMMENT 'ประเภทงาน'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `type_of_work`
--

CREATE TABLE `type_of_work` (
  `type_of_work_id` int(11) NOT NULL COMMENT 'รหัสประเภทการรับงาน',
  `type_of_work_ details` text NOT NULL COMMENT 'รายละเอียดการรับงาน',
  `type_of_work_rate_half` float(8,2) NOT NULL COMMENT 'ราคาครึ่งวัน',
  `type_of_work_rate_full` float(8,2) NOT NULL COMMENT 'ราคาเต็มวัน',
  `photographer_id` int(11) NOT NULL COMMENT 'รหัสช่างภาพ',
  `type_id` int(11) NOT NULL COMMENT 'รหัสประเภทงาน'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`cus_id`);

--
-- Indexes for table `information`
--
ALTER TABLE `information`
  ADD PRIMARY KEY (`Information_id`);

--
-- Indexes for table `pay`
--
ALTER TABLE `pay`
  ADD PRIMARY KEY (`pay_id`);

--
-- Indexes for table `photographer`
--
ALTER TABLE `photographer`
  ADD PRIMARY KEY (`photographer_id`);

--
-- Indexes for table `portfolio`
--
ALTER TABLE `portfolio`
  ADD PRIMARY KEY (`portfolio_id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `submit`
--
ALTER TABLE `submit`
  ADD PRIMARY KEY (`submit_id`);

--
-- Indexes for table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `type_of_work`
--
ALTER TABLE `type_of_work`
  ADD PRIMARY KEY (`type_of_work_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสผู้ดูแลระบบ';

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสจองคิว';

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `cus_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสลูกค้า';

--
-- AUTO_INCREMENT for table `information`
--
ALTER TABLE `information`
  MODIFY `Information_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสระบบ';

--
-- AUTO_INCREMENT for table `pay`
--
ALTER TABLE `pay`
  MODIFY `pay_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสชำระเงิน';

--
-- AUTO_INCREMENT for table `photographer`
--
ALTER TABLE `photographer`
  MODIFY `photographer_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสช่างภาพ';

--
-- AUTO_INCREMENT for table `portfolio`
--
ALTER TABLE `portfolio`
  MODIFY `portfolio_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสผลงาน';

--
-- AUTO_INCREMENT for table `submit`
--
ALTER TABLE `submit`
  MODIFY `submit_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสส่งงาน';

--
-- AUTO_INCREMENT for table `type`
--
ALTER TABLE `type`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสประเภทงาน';

--
-- AUTO_INCREMENT for table `type_of_work`
--
ALTER TABLE `type_of_work`
  MODIFY `type_of_work_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสประเภทการรับงาน';

--
-- Constraints for dumped tables
--

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
