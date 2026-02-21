<?php
// เริ่มต้นเซสชัน
session_start();

// ตรวจสอบว่ามีการตั้งค่าเซสชันไว้หรือไม่
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// ตรวจสอบว่าเป็น admin หรือไม่
if ($_SESSION['username'] !== 'admin' || $_SESSION['Comments'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

// ตรวจสอบการส่งข้อมูลแบบ POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // เชื่อมต่อฐานข้อมูล
    require_once 'connect_db.php';

    // รับค่าจากฟอร์ม
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $field = $_POST['field'];
    $comments = isset($_POST['comments']) && $_POST['comments'] !== '' ? $_POST['comments'] : NULL;

    // เตรียมคำสั่ง SQL สำหรับอัปเดต
    $sql = "UPDATE members SET name = ?, field = ?, Comments = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("ข้อผิดพลาดในการเตรียม statement: " . $conn->error);
    }

    // ผูกค่ากับ parameter
    $stmt->bind_param("sssi", $name, $field, $comments, $user_id);

    // รันคำสั่ง
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();

        echo '<script language="javascript">';
        echo 'alert("อัปเดตข้อมูลผู้ใช้สำเร็จ"); location.href="admin_users.php"';
        echo '</script>';
    } else {
        $stmt->close();
        $conn->close();

        echo '<script language="javascript">';
        echo 'alert("เกิดข้อผิดพลาด: ' . $stmt->error . '"); location.href="admin_users.php"';
        echo '</script>';
    }
} else {
    header("Location: admin_users.php");
    exit();
}
?>
