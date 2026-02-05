<?php
// ข้อมูลการเชื่อมต่อฐานข้อมูล
require_once 'connect_db.php';

// รับข้อมูลจากฟอร์ม
$name = $_POST['name'];
$field = $_POST['field'];
$username = trim($_POST['username']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // เข้ารหัสรหัสผ่าน

// ตรวจสอบว่า username ซ้ำหรือไม่
$check_sql = "SELECT username FROM members WHERE username = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("s", $username);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    // username ซ้ำ
    echo '<!DOCTYPE html>
    <html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
    <script>
        Swal.fire({
            icon: "error",
            title: "รหัสผู้ใช้งานซ้ำ",
            text: "รหัสผู้ใช้งานนี้มีผู้ใช้แล้ว กรุณาเลือกรหัสผู้ใช้งานอื่น",
            confirmButtonText: "ลองใหม่",
            confirmButtonColor: "#d33"
        }).then((result) => {
            window.location.href = "register.php";
        });
    </script>
    </body>
    </html>';
    $check_stmt->close();
    $conn->close();
    exit;
}
$check_stmt->close();

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
    echo '<!DOCTYPE html>
    <html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
    <script>
        Swal.fire({
            icon: "success",
            title: "สำเร็จ!",
            text: "สมัครสมาชิกเรียบร้อยแล้ว",
            confirmButtonText: "เข้าสู่ระบบ",
            confirmButtonColor: "#28a745"
        }).then((result) => {
            window.location.href = "index.php";
        });
    </script>
    </body>
    </html>';
} else {
    echo '<!DOCTYPE html>
    <html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
    <script>
        Swal.fire({
            icon: "error",
            title: "เกิดข้อผิดพลาด",
            text: "เกิดข้อผิดพลาดในการสมัครสมาชิก กรุณาลองใหม่อีกครั้ง",
            confirmButtonText: "ลองใหม่",
            confirmButtonColor: "#d33"
        }).then((result) => {
            window.location.href = "register.php";
        });
    </script>
    </body>
    </html>';
}

// ปิด statement และการเชื่อมต่อ
$stmt->close();
$conn->close();
