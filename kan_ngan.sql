-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 09, 2025 at 03:17 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kan_ngan`
--

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `kan_no` varchar(20) NOT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `price_per_unit` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `kan_no`, `item_name`, `quantity`, `unit`, `price_per_unit`, `total`) VALUES
(1, 'KAN_NGAN0001', 'กระดาษ A4 80g', 5, 'รีม', 135.00, 675.00),
(2, 'KAN_NGAN0001', 'ปากกา 0.5 น้ำเงิน', 12, 'ด้าม', 8.00, 96.00),
(3, 'KAN_NGAN0001', 'สมุดปกอ่อน', 1, 'แพ็ค', 120.00, 120.00),
(4, 'KAN_NGAN0001', 'ESP8266', 1, 'ตัว', 125.00, 125.00),
(5, 'KAN_NGAN0002', 'สมุด', 1, 'แพ็ค', 110.00, 110.00),
(6, 'KAN_NGAN0002', 'กระดาษ A4 120g', 5, 'รีม', 135.00, 675.00),
(7, 'KAN_NGAN0002', 'หมึก HP 85A', 1, 'ตลับ', 2500.00, 2500.00),
(11, 'KAN_NGAN0003', 'สมุด', 2, 'แพ็ค', 250.00, 500.00),
(12, 'KAN_NGAN0003', 'กระดาษ A4 80g', 5, 'รีม', 125.00, 625.00),
(13, 'KAN_NGAN0003', 'ดินสอ 2B', 1, 'กล่อง', 189.00, 189.00);

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `field` varchar(250) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`user_id`, `name`, `field`, `username`, `password`, `created_at`) VALUES
(1, 'นายสังสรรค์ หล้าพันธ์', 'สาขาวิชาเทคโนโลยีสารสนเทศ', 'sunksun', '$2y$10$z18OmLrLB2/HF.gb/ySVxOox6YWzKT.m6dJoq00uvQlOu.vs1x5rG', '2025-12-08 16:31:11');

-- --------------------------------------------------------

--
-- Table structure for table `report_request`
--

CREATE TABLE `report_request` (
  `report_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `kan_no` varchar(20) NOT NULL,
  `field` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `purpose` varchar(50) NOT NULL,
  `purpose_` varchar(50) NOT NULL,
  `project_name` varchar(250) NOT NULL,
  `activity` varchar(100) NOT NULL,
  `project_type` varchar(100) DEFAULT NULL,
  `budget` varchar(100) NOT NULL,
  `project_number` varchar(50) NOT NULL,
  `budget_used` varchar(50) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report_request`
--

INSERT INTO `report_request` (`report_id`, `user_id`, `kan_no`, `field`, `name`, `purpose`, `purpose_`, `project_name`, `activity`, `project_type`, `budget`, `project_number`, `budget_used`, `reason`, `create_at`) VALUES
(1, 1, 'KAN_NGAN0001', 'สาขาวิชาเทคโนโลยีสารสนเทศ', 'นายสังสรรค์ หล้าพันธ์', 'ขอซื้อ', 'วัสดุ กระดาษ A4 ปากกา และอีก 2 รายการ', 'การพัฒนานวัตกรการศึกษาด้วย Mobile Application', 'อบรมการสร้างแอปพลิเคชันการเรียนรู้ด้วย React Native ', 'โครงการสาขาวิชา/คณะ', 'แผ่นดิน', '6800022019', '1016.00', 'ใช้ในการอบรม', '2025-12-08 16:53:35'),
(2, 1, 'KAN_NGAN0002', 'สาขาวิชาเทคโนโลยีสารสนเทศ', 'นายสังสรรค์ หล้าพันธ์', 'ขอซื้อ', 'วัสดุ กระดาษ A4 ปากกา และอีก 1 รายการ', 'โครงการพัฒนาศูนย์การเรียนรู้ด้านพลังงานเพื่อการพัฒนาการบริหารจัดการทรัพยากรชุมชนอย่าง', 'อบรมการใช้ระบบโซล่าเซลล์', 'โครงการยุทธศาสตร์ฯ', 'แผ่นดิน', '6800222020', '3285.00', 'ใช้ในการอบรม', '2025-12-09 02:37:14'),
(3, 1, 'KAN_NGAN0003', 'สาขาวิชาเทคโนโลยีสารสนเทศ', 'นายสังสรรค์ หล้าพันธ์', 'ขอซื้อ', 'วัสดุ กระดาษ A4 ปากกา และอีก 2 รายการ', 'ส่งเสริมการปลูกผักปลอดสารพิษด้วยระบบน้ำพลังงานแสงอาทิตย์และเกษตรอัจฉริยะ', 'อบรมการใช้ระบบ IoT', 'โครงการยุทธศาสตร์ฯ', 'แผ่นดิน', '6800222021', '1314', 'ใช้ในการอบรม', '2025-12-09 14:12:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `report_request`
--
ALTER TABLE `report_request`
  ADD PRIMARY KEY (`report_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `report_request`
--
ALTER TABLE `report_request`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
