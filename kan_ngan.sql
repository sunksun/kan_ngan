-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 15, 2024 at 02:29 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.29

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `kan_no`, `item_name`, `quantity`, `unit`, `price_per_unit`, `total`) VALUES
(40, 'KAN_NGAN0001', 'กระดาษ A4 80g', 1, 'รีม', '125.00', '125.00'),
(41, 'KAN_NGAN0001', 'หมึก hp150', 1, 'ตลับ', '1500.00', '1500.00'),
(42, 'KAN_NGAN0001', 'ปากกาลูกลื่น 0.5', 1, 'โหล', '250.00', '250.00');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`user_id`, `name`, `field`, `username`, `password`, `created_at`) VALUES
(1, 'สังสรรค์ หล้าพันธ์', 'เทคโนโลยีสารสนเทศ', 'sunksun.lap', '$2y$10$yqah7uPNQVxyKFsbXxIPhea7vV6P.x5PD730hzvJjQVPqEVimr4F2', '2024-06-13 03:08:27'),
(4, 'สังสรรค์ หล้าพันธ์', 'เทคโนโลยีสารสนเทศ', 'admin_sci', '$2y$10$1Y1wNeaAjuZwBAKUc0e2f.g/xURV2iHnkvEPTm3bkixxtUhOLcsx2', '2024-06-13 03:33:47'),
(5, 'sunksun', 'เทคโนโลยีสารสนเทศ', '6240267101', '$2y$10$msS2qwBIcgA9YCMaEoWbE.A2bFkji2UJkLz8pSYv2mJkkLpiiCOw2', '2024-06-13 08:10:27'),
(6, 'ผ.ศ.มัลลิกา หล้าพันธ์', 'ฟิสิกส์', 'mallika.lap', '$2y$10$6d4syQ9CyFzhCGeR6wkyu.pv6A10RgSq5bCbKPHJDfeJOfK4QTobW', '2024-06-13 11:18:56');

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
  `budget` varchar(100) NOT NULL,
  `project_number` varchar(50) NOT NULL,
  `budget_used` varchar(50) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `report_request`
--

INSERT INTO `report_request` (`report_id`, `user_id`, `kan_no`, `field`, `name`, `purpose`, `purpose_`, `project_name`, `activity`, `budget`, `project_number`, `budget_used`, `reason`, `create_at`) VALUES
(1, 1, 'KAN_NGAN0001', 'เทคโนโลยีสารสนเทศ', 'สังสรรค์ หล้าพันธ์', 'ขอซื้อ', 'วัสดุ', 'อบรมปรับพื้นฐานนักศึกษาชั้นปีที่ 1', 'อบรม full stack developer', 'แผ่นดิน', '5555555555', '1875', 'ประกอบการอบรม', '2024-06-15 03:37:41');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `report_request`
--
ALTER TABLE `report_request`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
