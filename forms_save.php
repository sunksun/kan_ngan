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

// เริ่มต้น transaction เพื่อป้องกัน race condition
$conn->begin_transaction();

try {
    // ล็อกตารางและหาเลขที่ใบกันใหม่ภายใน transaction
    $sql_lock = "SELECT IFNULL(MAX(CAST(SUBSTRING(kan_no, 9) AS UNSIGNED)), 0) + 1 AS next_number FROM report_request FOR UPDATE";
    $result = $conn->query($sql_lock);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $next_number = $row['next_number'];
        $kan_no = "KAN_NGAN" . str_pad($next_number, 4, '0', STR_PAD_LEFT);
    } else {
        throw new Exception("ไม่สามารถสร้างเลขที่ใบกันได้");
    }

    // เตรียมคำสั่ง SQL สำหรับการเพิ่มข้อมูล
    $sql = "INSERT INTO report_request (user_id, kan_no, field, name, purpose, purpose_, project_name, activity, project_type, budget, project_number, budget_used, reason)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // สร้างคำสั่ง prepared statement
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        throw new Exception("ข้อผิดพลาดในการเตรียม statement: " . $conn->error);
    }

    // ผูกค่ากับ parameter (ใช้ $kan_no ที่สร้างใหม่แทน $row_count)
    $stmt->bind_param("issssssssssss", $user_id, $kan_no, $field, $name, $purpose, $purpose_, $project_name, $activity, $project_type, $budget, $project_number, $budget_used, $reason);

    // รันคำสั่ง
    if (!$stmt->execute()) {
        throw new Exception("ข้อผิดพลาดในการบันทึก: " . $stmt->error);
    }

    // commit transaction
    $conn->commit();

    // แสดง alert และเปลี่ยนหน้า
    echo '<script>';
    echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว ไปหน้าถัดไป");';
    echo 'location.href="forms-items.php?kan_no=' . $kan_no . '";';
    echo '</script>';

    $stmt->close();

} catch (Exception $e) {
    // rollback ถ้าเกิดข้อผิดพลาด
    $conn->rollback();
    echo '<script>';
    echo 'alert("เกิดข้อผิดพลาด: ' . addslashes($e->getMessage()) . '");';
    echo 'history.back();';
    echo '</script>';
}

// ปิดการเชื่อมต่อ
$conn->close();
