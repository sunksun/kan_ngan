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

$edit_user_id = $_GET['id'];
$admin_name = $_SESSION['name'];
$admin_field = $_SESSION['field'];

// เชื่อมต่อฐานข้อมูล
require_once 'connect_db.php';

// ดึงข้อมูลผู้ใช้ที่ต้องการแก้ไข
$sql = "SELECT user_id, name, field, username, Comments FROM members WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $edit_user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $stmt->close();
    $conn->close();
    header("Location: admin_users.php");
    exit();
}

$user = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>แก้ไขข้อมูลผู้ใช้ - Admin</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.svg" rel="icon" type="image/svg+xml">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">

</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="admin_dash.php" class="logo d-flex align-items-center">
                <i class="bi bi-cash-coin" style="font-size: 2rem; color: #012970; margin-right: 0.5rem;"></i>
                <span class="d-none d-lg-block">กันเงิน กัน</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo htmlspecialchars($admin_name); ?></span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6><?php echo htmlspecialchars($admin_name); ?></h6>
                            <span><?php echo htmlspecialchars($admin_field); ?></span>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="sign_out.php">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>ออกจากระบบ</span>
                            </a>
                        </li>

                    </ul>
                </li>

            </ul>
        </nav>

    </header><!-- End Header -->

    <?php include 'includes/sidebar_admin.php'; ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>แก้ไขข้อมูลผู้ใช้</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin_dash.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="admin_users.php">จัดการผู้ใช้งาน</a></li>
                    <li class="breadcrumb-item active">แก้ไขข้อมูล</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-8">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">แก้ไขข้อมูล: <?php echo htmlspecialchars($user['name']); ?></h5>

                            <!-- แบบฟอร์มแก้ไขข้อมูล -->
                            <form action="admin_user_update.php" method="POST" class="row g-3">
                                <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">

                                <div class="col-md-12">
                                    <label for="name" class="form-label">ชื่อ-นามสกุล</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                </div>

                                <div class="col-md-12">
                                    <label for="field" class="form-label">สาขาวิชา / หน่วยงาน</label>
                                    <input type="text" class="form-control" id="field" name="field" value="<?php echo htmlspecialchars($user['field']); ?>" required>
                                </div>

                                <div class="col-md-12">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                                    <div class="form-text">Username ไม่สามารถแก้ไขได้</div>
                                </div>

                                <div class="col-md-12">
                                    <label for="comments" class="form-label">สิทธิ์การใช้งาน</label>
                                    <select class="form-select" id="comments" name="comments" required>
                                        <option value="">เลือกสิทธิ์...</option>
                                        <option value="" <?php echo (empty($user['Comments']) || $user['Comments'] === NULL) ? 'selected' : ''; ?>>User ทั่วไป</option>
                                        <option value="admin" <?php echo ($user['Comments'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <div class="alert alert-warning" role="alert">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        <strong>หมายเหตุ:</strong> การแก้ไขสิทธิ์เป็น Admin จะทำให้ผู้ใช้สามารถเข้าถึงหน้า Admin Dashboard และจัดการระบบได้ทั้งหมด
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
                                    <a href="admin_users.php" class="btn btn-secondary">ยกเลิก</a>
                                </div>
                            </form><!-- End แบบฟอร์มแก้ไขข้อมูล -->

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
            Designed by ลูกปลาน้อย
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chart.js/chart.umd.js"></script>
    <script src="assets/vendor/echarts/echarts.min.js"></script>
    <script src="assets/vendor/quill/quill.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>

</body>

</html>
