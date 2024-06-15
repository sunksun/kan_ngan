<?php
// การเชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = ""; // รหัสผ่านฐานข้อมูล MySQL
$dbname = "kan_ngan"; // ชื่อฐานข้อมูลที่ต้องการใช้

$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// รับค่าที่ส่งมาจากฟอร์ม
$report_id = $_GET['report_id'];
$kan_no = $_GET['kan_no'];
$field = $_POST['field'];
$name = $_POST['name'];
$purpose = $_POST['purpose'];
$purpose_ = $_POST['purpose_'];
$project_name = $_POST['project_name'];
$activity = $_POST['activity'];
$budget = $_POST['budget'];
$project_number = $_POST['project_number'];
$budget_used = $_POST['total_budget_used'];
$reason = $_POST['reason'];

// เตรียมคำสั่ง SQL สำหรับอัปเดตข้อมูลในตาราง report_request
$sqlUpdateReport = "UPDATE report_request SET field='$field', name='$name', purpose='$purpose', purpose_='$purpose_', project_name='$project_name', activity='$activity', budget='$budget', project_number='$project_number', budget_used='$budget_used', reason='$reason' WHERE report_id='$report_id'";
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
$quantities = $_POST['quantity'];
$units = $_POST['unit'];
$price_per_units = $_POST['price_per_unit'];
$totals = $_POST['total'];

for ($i = 0; $i < count($item_names); $i++) {
    $item_name = $item_names[$i];
    $quantity = $quantities[$i];
    $unit = $units[$i];
    $price_per_unit = $price_per_units[$i];
    $total = $totals[$i];

    // เตรียมคำสั่ง SQL สำหรับเพิ่มข้อมูลใหม่ลงในตาราง items
    $sqlInsertItem = "INSERT INTO items (kan_no, item_name, quantity, unit, price_per_unit, total) VALUES ('$kan_no', '$item_name', '$quantity', '$unit', '$price_per_unit', '$total')";
    if ($conn->query($sqlInsertItem) !== TRUE) {
        echo '<script>';
        echo 'alert("เกิดข้อผิดพลาดในการเพิ่มข้อมูลใหม่ในตาราง items: ' . $conn->error . '");';
        echo '</script>';
    }
}

$conn->close();
