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
$username = $_POST['username'];
$password = $_POST['password'];

// เตรียมคำสั่ง SQL
$sql = "SELECT * FROM members WHERE username = ?";

// สร้างคำสั่ง prepared statement
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("ข้อผิดพลาดในการเตรียม statement: " . $conn->error);
}

// ผูกค่ากับ parameter
$stmt->bind_param("s", $username);

// รันคำสั่ง
$stmt->execute();

// เก็บผลลัพธ์
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // ดึงข้อมูลผู้ใช้
    $user = $result->fetch_assoc();

    // ตรวจสอบรหัสผ่าน
    if (password_verify($password, $user['password'])) {
        // ตั้ง session หรือการดำเนินการหลังจากเข้าสู่ระบบสำเร็จ
        session_start();
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['field'] = $user['field'];
        echo "เข้าสู่ระบบสำเร็จ";
        // เปลี่ยนเส้นทางไปยังหน้าหลักหรือหน้าที่ต้องการ
        header("Location: dashboard.php");
        exit();
    } else {
        echo '<script language="javascript">';
        echo 'alert("รหัสผ่านไม่ถูกต้อง"); location.href="index.php"';
        echo '</script>';
    }
} else {
    echo '<script language="javascript">';
    echo 'alert("ไม่พบผู้ใช้งาน"); location.href="index.php"';
    echo '</script>';
}

// ปิด statement และการเชื่อมต่อ
$stmt->close();
$conn->close();
