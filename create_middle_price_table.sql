-- ตารางสำหรับเก็บข้อมูลราคากลางวัสดุสิ้นเปลือง
CREATE TABLE IF NOT EXISTS `middle_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_no` varchar(10) NOT NULL COMMENT 'ลำดับที่',
  `category` varchar(100) DEFAULT NULL COMMENT 'หมวดหมู่',
  `item_name` varchar(255) NOT NULL COMMENT 'รายการวัสดุ',
  `unit` varchar(50) NOT NULL COMMENT 'หน่วยนับ',
  `price` decimal(10,2) NOT NULL COMMENT 'ราคากลาง',
  `fiscal_year` int(4) DEFAULT 2567 COMMENT 'ปีงบประมาณ',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_category` (`category`),
  KEY `idx_fiscal_year` (`fiscal_year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางราคากลางวัสดุสิ้นเปลือง';
