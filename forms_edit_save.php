<?php
// การเชื่อมต่อฐานข้อมูล
require_once 'connect_db.php';

// รับค่าที่ส่งมาจากฟอร์ม
$report_id = intval($_GET['report_id']);
$kan_no = $_GET['kan_no'];
$field = $_POST['field'];
$name = $_POST['name'];
$purpose = $_POST['purpose'];
$purpose_ = $_POST['purpose_'];
$project_name = $_POST['project_name'];
$activity = $_POST['activity'];
$project_type = $_POST['project_type'];
$budget = $_POST['budget'];
$project_number = $_POST['project_number'];
$budget_used = $_POST['total_budget_used'];
$reason = $_POST['reason'];

// เพิ่มข้อมูลใหม่เข้าสู่ตาราง items
$item_names = $_POST['item_name'];
$middle_prices = $_POST['middle_price'];
$middle_units = $_POST['middle_unit'];
$quantities = $_POST['quantity'];
$units = $_POST['unit'];
$price_per_units = $_POST['price_per_unit'];
$totals = $_POST['total'];

// เริ่มต้น transaction
$conn->begin_transaction();

try {
    // 1. อัปเดตข้อมูลในตาราง report_request
    $sqlUpdateReport = "UPDATE report_request SET field=?, name=?, purpose=?, purpose_=?, project_name=?, activity=?, project_type=?, budget=?, project_number=?, budget_used=?, reason=? WHERE report_id=?";
    $stmt_update = $conn->prepare($sqlUpdateReport);
    $stmt_update->bind_param("sssssssssssi", $field, $name, $purpose, $purpose_, $project_name, $activity, $project_type, $budget, $project_number, $budget_used, $reason, $report_id);

    if (!$stmt_update->execute()) {
        throw new Exception("ไม่สามารถอัปเดตข้อมูลหลักได้");
    }
    $stmt_update->close();

    // 2. ลบข้อมูลเก่าในตาราง items ที่เกี่ยวข้องกับ kan_no นี้
    $sqlDeleteItems = "DELETE FROM items WHERE kan_no=?";
    $stmt_delete = $conn->prepare($sqlDeleteItems);
    $stmt_delete->bind_param("s", $kan_no);

    if (!$stmt_delete->execute()) {
        throw new Exception("ไม่สามารถลบข้อมูลเก่าในตาราง items ได้");
    }
    $stmt_delete->close();

    // 3. เพิ่มข้อมูลใหม่เข้าสู่ตาราง items
    $sqlInsertItem = "INSERT INTO items (kan_no, item_name, middle_price, middle_unit, quantity, unit, price_per_unit, total) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sqlInsertItem);

    for ($i = 0; $i < count($item_names); $i++) {
        $item_name = $item_names[$i];
        $middle_price = $middle_prices[$i];
        $middle_unit = $middle_units[$i];
        $quantity = $quantities[$i];
        $unit = $units[$i];
        $price_per_unit = $price_per_units[$i];
        $total = $totals[$i];

        $stmt_insert->bind_param("ssdsisdd", $kan_no, $item_name, $middle_price, $middle_unit, $quantity, $unit, $price_per_unit, $total);

        if (!$stmt_insert->execute()) {
            throw new Exception("ไม่สามารถเพิ่มข้อมูลใหม่ในตาราง items ได้");
        }
    }
    $stmt_insert->close();

    // commit transaction
    $conn->commit();

    echo '<script>';
    echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว");';
    echo 'window.location.href="dashboard.php";';
    echo '</script>';

} catch (Exception $e) {
    // rollback ถ้าเกิดข้อผิดพลาด
    $conn->rollback();

    echo '<script>';
    echo 'alert("เกิดข้อผิดพลาด: ' . addslashes($e->getMessage()) . '");';
    echo 'history.back();';
    echo '</script>';
}

$conn->close();
