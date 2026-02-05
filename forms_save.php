<?php
// เริ่มต้นเซสชัน
session_start();
$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];
$field = $_SESSION['field'];

// ข้อมูลการเชื่อมต่อฐานข้อมูล
require_once 'connect_db.php';

// รับข้อมูลจากฟอร์ม
$row_count = $_POST['row_count'];
$field = $_POST['field'];
$name = $_POST['name'];
$purpose = $_POST['purpose'];
$purpose_ = $_POST['purpose_'];
$project_name = $_POST['project_name'];
$activity = $_POST['activity'];
$project_type = $_POST['project_type'];
$budget = $_POST['budget'];
$project_number = $_POST['project_number'];
$budget_used = '';
$reason = '';

// เตรียมคำสั่ง SQL สำหรับการเพิ่มข้อมูล
$sql = "INSERT INTO report_request (user_id, kan_no, field, name, purpose, purpose_, project_name, activity, project_type, budget, project_number, budget_used, reason)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// สร้างคำสั่ง prepared statement
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("ข้อผิดพลาดในการเตรียม statement: " . $conn->error);
}

// ผูกค่ากับ parameter
$stmt->bind_param("issssssssssss", $user_id, $row_count, $field, $name, $purpose, $purpose_, $project_name, $activity, $project_type, $budget, $project_number, $budget_used, $reason);

// รันคำสั่ง
if ($stmt->execute()) {
    // แสดง alert และเปลี่ยนหน้า
    echo '<script>';
    echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว ไปหน้าถัดไป");';
    echo 'location.href="forms-items.php?kan_no=' . $row_count . '";';
    echo '</script>';
} else {
    echo "ข้อผิดพลาด: " . $stmt->error;
}

// ปิด statement และการเชื่อมต่อ
$stmt->close();
$conn->close();
