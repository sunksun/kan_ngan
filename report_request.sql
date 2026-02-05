-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: 05 ก.พ. 2026 เมื่อ 07:22 AM
-- เวอร์ชันของเซิร์ฟเวอร์: 10.6.17-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sunksund_kan_ngan`
--

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `report_request`
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
-- dump ตาราง `report_request`
--

INSERT INTO `report_request` (`report_id`, `user_id`, `kan_no`, `field`, `name`, `purpose`, `purpose_`, `project_name`, `activity`, `project_type`, `budget`, `project_number`, `budget_used`, `reason`, `create_at`) VALUES
(1, 2, 'KAN_NGAN0001', 'สาขาวิชาเทคโนโลยีสารสนเทศ', 'นายสังสรรค์ หล้าพันธ์', 'ขอซื้อ', 'ตะกั่ว และวัสดุอีก 10 รายการ', 'จัดซื้อวัสดุฝึก บ.กศ. ปีงบประมาณ 2569 ภาคเรียนที่ 2/2568', 'จัดการเรียนการสอนรายวิชาการเขียนโปรแกรมระบบฐานข้อมูล', 'โครงการสาขาวิชา/คณะ', 'เงินรายได้', '690015068', '1187', 'จัดการเรียนการสอน', '2026-02-04 12:02:03'),
(2, 10, 'KAN_NGAN0002', 'สาขาวิชาฟิสิกส์', 'ผศ.วีรชน มีฐาน', 'ขอซื้อ', 'วัสดุ', 'ววน 69', 'สร้างชิ้นงาน', 'โครงการวิจัย', '5000', 'ววน 69', '5000.00', 'สร้างชิ้นงานชุดทดลอง', '2026-02-04 09:32:43'),
(4, 2, 'KAN_NGAN0003', 'สาขาวิชาเทคโนโลยีสารสนเทศ', 'นายสังสรรค์ หล้าพันธ์', 'ขอซื้อ', 'วัสดุ กระดาษ A4 ปากกา และอีก 2 รายการ', 'การพัฒนานวัตกรการศึกษาด้วย Mobile Application', 'อบรมการสร้างแอปพลิเคชันการเรียนรู้ด้วย React Native ', 'โครงการสาขาวิชา/คณะ', 'เงินรายได้ บ.กศ.', '6800222023', '735.00', 'เก็บข้อมูลพันธุ์ปลาในงานวิจัย', '2026-02-05 00:02:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `report_request`
--
ALTER TABLE `report_request`
  ADD PRIMARY KEY (`report_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `report_request`
--
ALTER TABLE `report_request`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
