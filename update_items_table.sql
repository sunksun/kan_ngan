-- ====================================================================
-- SQL Script: เพิ่มคอลัมน์ middle_price และ middle_unit ในตาราง items
-- วันที่: 12 กุมภาพันธ์ 2569
-- สำหรับ: Database sunksund_kan_ngan
-- ====================================================================
--
-- วิธีการรัน:
-- 1. ผ่าน phpMyAdmin: Import ไฟล์นี้ หรือ Copy-Paste ทั้งหมดแล้ว Execute
-- 2. ผ่าน MySQL CLI: mysql -u sunksund_kan_ngan -p sunksund_kan_ngan < update_items_table.sql
--
-- หมายเหตุ:
-- - Script นี้ป้องกันการรันซ้ำ (ถ้าคอลัมน์มีอยู่แล้วจะไม่ทำอะไร)
-- - ใช้เวลาประมาณ 1-2 วินาที
-- - ไม่กระทบข้อมูลเดิมในตาราง items
--
-- ====================================================================

USE sunksund_kan_ngan;

-- ตรวจสอบโครงสร้างตารางปัจจุบัน
SELECT 'ตรวจสอบโครงสร้างตารางก่อนแก้ไข:' as '';
DESCRIBE items;

-- เพิ่มคอลัมน์ middle_price และ middle_unit (ตรวจสอบว่ายังไม่มีอยู่)
SELECT 'กำลังเพิ่มคอลัมน์ middle_price และ middle_unit...' as '';

-- ตรวจสอบและเพิ่มคอลัมน์ middle_price ถ้ายังไม่มี
SET @dbname = DATABASE();
SET @tablename = 'items';
SET @columnname = 'middle_price';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  "SELECT 'คอลัมน์ middle_price มีอยู่แล้ว' as ''",
  "ALTER TABLE items ADD COLUMN middle_price DECIMAL(10,2) AFTER item_name"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- ตรวจสอบและเพิ่มคอลัมน์ middle_unit ถ้ายังไม่มี
SET @columnname = 'middle_unit';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  "SELECT 'คอลัมน์ middle_unit มีอยู่แล้ว' as ''",
  "ALTER TABLE items ADD COLUMN middle_unit VARCHAR(50) AFTER middle_price"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- ตรวจสอบโครงสร้างตารางหลังแก้ไข
SELECT 'ตรวจสอบโครงสร้างตารางหลังแก้ไข:' as '';
DESCRIBE items;

-- ตรวจสอบข้อมูลในตาราง middle_price
SELECT 'ตรวจสอบจำนวนข้อมูลในตาราง middle_price:' as '';
SELECT COUNT(*) as total_items FROM middle_price;

-- แสดงตัวอย่างข้อมูล middle_price
SELECT 'ตัวอย่างข้อมูลราคากลาง 5 รายการแรก:' as '';
SELECT item_name, unit, price FROM middle_price LIMIT 5;

SELECT 'สำเร็จ! คอลัมน์ middle_price และ middle_unit ถูกเพิ่มเข้าในตาราง items แล้ว' as '';

-- ====================================================================
-- ROLLBACK SCRIPT (กรณีต้องการลบคอลัมน์ที่เพิ่มไป)
-- ====================================================================
-- ⚠️ คำเตือน: อย่ารัน Script ด้านล่างนี้ เว้นแต่ต้องการย้อนกลับการเปลี่ยนแปลง
-- ⚠️ การรัน Rollback จะทำให้ข้อมูล middle_price และ middle_unit ที่บันทึกไว้หายไป!
--
-- หากต้องการย้อนกลับ ให้ Copy คำสั่งด้านล่างนี้แล้วรันแยก:
--
-- USE sunksund_kan_ngan;
-- ALTER TABLE items DROP COLUMN middle_price;
-- ALTER TABLE items DROP COLUMN middle_unit;
-- SELECT 'Rollback สำเร็จ: ลบคอลัมน์ middle_price และ middle_unit แล้ว' as '';
--
-- ====================================================================
