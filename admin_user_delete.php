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

// ตรวจสอบว่ามี user_id ที่ส่งมาหรือไม่
if (!isset($_GET['id'])) {
    header("Location: admin_users.php");
    exit();
}

$delete_user_id = $_GET['id'];

// เชื่อมต่อฐานข้อมูล
require_once 'connect_db.php';

// ตรวจสอบว่าผู้ใช้ที่จะลบไม่ใช่ admin
$sql_check = "SELECT Comments FROM members WHERE user_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $delete_user_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    $user_check = $result_check->fetch_assoc();

    // ห้ามลบ admin
    if ($user_check['Comments'] === 'admin') {
        $stmt_check->close();
        $conn->close();

        echo '<script language="javascript">';
        echo 'alert("ไม่สามารถลบผู้ใช้ Admin ได้"); location.href="admin_users.php"';
        echo '</script>';
        exit();
    }
}

$stmt_check->close();

// ลบผู้ใช้
$sql = "DELETE FROM members WHERE user_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    $conn->close();
    die("ข้อผิดพลาดในการเตรียม statement: " . $conn->error);
}

$stmt->bind_param("i", $delete_user_id);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();

    echo '<script language="javascript">';
    echo 'alert("ลบผู้ใช้สำเร็จ"); location.href="admin_users.php"';
    echo '</script>';
} else {
    $stmt->close();
    $conn->close();

    echo '<script language="javascript">';
    echo 'alert("เกิดข้อผิดพลาด: ' . $stmt->error . '"); location.href="admin_users.php"';
    echo '</script>';
}
?>
