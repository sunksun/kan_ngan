<?php
// การเชื่อมต่อฐานข้อมูล
require_once 'connect_db.php';

// รับค่าที่ส่งมาจากฟอร์ม
$report_id = $_GET['report_id'];
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

// เตรียมคำสั่ง SQL สำหรับอัปเดตข้อมูลในตาราง report_request
$sqlUpdateReport = "UPDATE report_request SET field='$field', name='$name', purpose='$purpose', purpose_='$purpose_', project_name='$project_name', activity='$activity', project_type='$project_type', budget='$budget', project_number='$project_number', budget_used='$budget_used', reason='$reason' WHERE report_id='$report_id'";
if ($conn->query($sqlUpdateReport) === TRUE) {
    echo '<script>';
    echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว");';
    echo 'window.location.href="dashboard.php";'; // เปลี่ยนเส้นทางไปที่ dashboard.php
    echo '</script>';
} else {
    echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $conn->error . "<br>";
}

// ลบข้อมูลเก่าในตาราง items ที่เกี่ยวข้องกับ report_id นี้
$sqlDeleteItems = "DELETE FROM items WHERE kan_no='$kan_no'";
if ($conn->query($sqlDeleteItems) === TRUE) {
    echo '<script>';
    echo 'alert("ลบข้อมูลเก่าในตาราง items เรียบร้อยแล้ว");';
    echo '</script>';
} else {
    echo "เกิดข้อผิดพลาดในการลบข้อมูลเก่าในตาราง items: " . $conn->error . "<br>";
}

// เพิ่มข้อมูลใหม่เข้าสู่ตาราง items
$item_names = $_POST['item_name'];
$middle_prices = $_POST['middle_price'];
$middle_units = $_POST['middle_unit'];
$quantities = $_POST['quantity'];
$units = $_POST['unit'];
$price_per_units = $_POST['price_per_unit'];
$totals = $_POST['total'];

// เตรียมคำสั่ง SQL แบบ prepared statement
$sqlInsertItem = "INSERT INTO items (kan_no, item_name, middle_price, middle_unit, quantity, unit, price_per_unit, total) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sqlInsertItem);

for ($i = 0; $i < count($item_names); $i++) {
    $item_name = $item_names[$i];
    $middle_price = $middle_prices[$i];
    $middle_unit = $middle_units[$i];
    $quantity = $quantities[$i];
    $unit = $units[$i];
    $price_per_unit = $price_per_units[$i];
    $total = $totals[$i];

    // ผูกค่าและเพิ่มข้อมูล
    $stmt->bind_param("ssdsisdd", $kan_no, $item_name, $middle_price, $middle_unit, $quantity, $unit, $price_per_unit, $total);
    if ($stmt->execute() === FALSE) {
        echo '<script>';
        echo 'alert("เกิดข้อผิดพลาดในการเพิ่มข้อมูลใหม่ในตาราง items: ' . $stmt->error . '");';
        echo '</script>';
    }
}

$stmt->close();

$conn->close();
