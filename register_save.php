<?php
// ข้อมูลการเชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = ""; // ใส่รหัสผ่านของฐานข้อมูล MySQL
$dbname = "kan_ngan";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// รับข้อมูลจากฟอร์ม
$name = $_POST['name'];
$field = $_POST['field'];
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // เข้ารหัสรหัสผ่าน

// เตรียมคำสั่ง SQL
$sql = "INSERT INTO members (name, field, username, password) VALUES (?, ?, ?, ?)";

// สร้างคำสั่ง prepared statement
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("ข้อผิดพลาดในการเตรียม statement: " . $conn->error);
}

// ผูกค่ากับ parameter
$stmt->bind_param("ssss", $name, $field, $username, $password);

// รันคำสั่ง
if ($stmt->execute()) {
    echo '<script language="javascript">';
    echo 'alert("สมัครสมาชิกเรียบร้อยแล้ว"); location.href="index.php"';
    echo '</script>';
} else {
    echo '<script language="javascript">';
    echo 'alert("เกิดข้อผิดพลาด รหัสผู้ใช้งานซ้ำ"); location.href="register.php"';
    echo '</script>';
}

// ปิด statement และการเชื่อมต่อ
$stmt->close();
$conn->close();
