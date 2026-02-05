<?php
// เริ่มต้นเซสชัน
session_start();

// ตรวจสอบว่ามีการเข้าสู่ระบบหรือไม่
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// รับ ID ที่ส่งมา
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: kan_lists.php");
    exit();
}

$report_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// ข้อมูลการเชื่อมต่อฐานข้อมูล
require_once 'connect_db.php';

// ตรวจสอบว่ารายการนี้เป็นของผู้ใช้คนนี้จริงหรือไม่ และดึง kan_no มาใช้
$check_sql = "SELECT kan_no FROM report_request WHERE report_id = ? AND user_id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ii", $report_id, $user_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows === 0) {
    // ไม่พบข้อมูลหรือไม่ใช่ของผู้ใช้คนนี้
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
            title: "ไม่พบข้อมูล",
            text: "ไม่พบรายการที่ต้องการลบหรือคุณไม่มีสิทธิ์ลบรายการนี้",
            confirmButtonText: "ตกลง",
            confirmButtonColor: "#d33"
        }).then((result) => {
            window.location.href = "kan_lists.php";
        });
    </script>
    </body>
    </html>';
    $check_stmt->close();
    $conn->close();
    exit();
}

// ดึง kan_no มาใช้
$report_data = $check_result->fetch_assoc();
$kan_no = $report_data['kan_no'];
$check_stmt->close();

// ลบรายการย่อยในตาราง items ก่อน (ใช้ kan_no)
$delete_items_sql = "DELETE FROM items WHERE kan_no = ?";
$delete_items_stmt = $conn->prepare($delete_items_sql);
$delete_items_stmt->bind_param("s", $kan_no);
$delete_items_stmt->execute();
$delete_items_stmt->close();

// ลบรายการหลักในตาราง report_request
$delete_sql = "DELETE FROM report_request WHERE report_id = ? AND user_id = ?";
$delete_stmt = $conn->prepare($delete_sql);
$delete_stmt->bind_param("ii", $report_id, $user_id);

if ($delete_stmt->execute()) {
    // ลบสำเร็จ
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
            title: "ลบสำเร็จ!",
            text: "ลบรายการเรียบร้อยแล้ว",
            confirmButtonText: "ตกลง",
            confirmButtonColor: "#28a745"
        }).then((result) => {
            window.location.href = "kan_lists.php";
        });
    </script>
    </body>
    </html>';
} else {
    // ลบไม่สำเร็จ
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
            text: "ไม่สามารถลบรายการได้ กรุณาลองใหม่อีกครั้ง",
            confirmButtonText: "ตกลง",
            confirmButtonColor: "#d33"
        }).then((result) => {
            window.location.href = "kan_lists.php";
        });
    </script>
    </body>
    </html>';
}

$delete_stmt->close();
$conn->close();
?>
