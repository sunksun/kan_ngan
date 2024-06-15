<?php
// เริ่มต้นเซสชัน
session_start();
$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];
$field = $_SESSION['field'];

// ตรวจสอบการร้องขอแบบ POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ข้อมูลการเชื่อมต่อฐานข้อมูล
    $servername = "localhost";
    $username = "root";
    $password = ""; // ใส่รหัสผ่านของฐานข้อมูล MySQL
    $dbname = "kan_ngan"; // ใส่ชื่อฐานข้อมูลที่ต้องการใช้

    $conn = new mysqli($servername, $username, $password, $dbname);

    // ตรวจสอบการเชื่อมต่อ
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // รับค่าจากฟอร์ม
    $kan_nos = $_POST['kan_no'];
    $item_names = $_POST['item_name'];
    $quantities = $_POST['quantity'];
    $units = $_POST['unit'];
    $price_per_units = $_POST['price_per_unit'];
    $totals = $_POST['total'];
    $reason = $_POST['reason'];
    $budget_used = $_POST['budget_used'];

    $sqlUpdate = "UPDATE `report_request` SET 
    `reason` = '$reason',
    `budget_used` = '$budget_used' 
    WHERE `report_request`.`kan_no` = '$kan_nos';";

    if (mysqli_query($conn, $sqlUpdate)) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }

    // เตรียมคำสั่ง SQL
    $sql = "INSERT INTO items (kan_no, item_name, quantity, unit, price_per_unit, total) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // วนลูปเพิ่มข้อมูลแต่ละรายการ
    for ($i = 0; $i < count($item_names); $i++) {
        $stmt->bind_param("ssisdd", $kan_nos, $item_names[$i], $quantities[$i], $units[$i], $price_per_units[$i], $totals[$i]);
        if ($stmt->execute() === FALSE) {
            echo "Error: " . $stmt->error . "<br>";
        }
    }

    echo '<script language="javascript">';
    echo 'alert("บันทึกข้อมูลเรียบร้อยแล้ว กลับไปหน้าหลัก"); location.href="dashboard.php"';
    echo '</script>';

    // ปิดการเชื่อมต่อ
    $stmt->close();
    $conn->close();
}
