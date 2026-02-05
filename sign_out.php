<?php
// เริ่มต้นเซสชัน
session_start();

// ลบข้อมูลเซสชันทั้งหมด
session_unset();

// ทำลายเซสชัน
session_destroy();

// เปลี่ยนเส้นทางไปยังหน้าเข้าสู่ระบบหรือหน้าแรก
header("Location: index.php");
exit();
