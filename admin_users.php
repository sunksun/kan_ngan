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

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];
$field = $_SESSION['field'];

// เชื่อมต่อฐานข้อมูล
require_once 'connect_db.php';

// ดึงข้อมูลผู้ใช้ทั้งหมด
$sql = "SELECT user_id, name, field, username, Comments FROM members ORDER BY user_id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>จัดการผู้ใช้งาน - Admin</title>
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
                        <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo htmlspecialchars($name); ?></span>
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6><?php echo htmlspecialchars($name); ?></h6>
                            <span><?php echo htmlspecialchars($field); ?></span>
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

                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->

            </ul>
        </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->

    <?php include 'includes/sidebar_admin.php'; ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>จัดการผู้ใช้งาน</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin_dash.php">Home</a></li>
                    <li class="breadcrumb-item">การจัดการผู้ใช้งาน</li>
                    <li class="breadcrumb-item active">จัดการผู้ใช้งาน</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">รายชื่อผู้ใช้งานทั้งหมด</h5>

                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">ชื่อ-นามสกุล</th>
                                        <th scope="col">สาขาวิชา</th>
                                        <th scope="col">Username</th>
                                        <th scope="col">สิทธิ์</th>
                                        <th scope="col">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result->num_rows > 0) {
                                        $no = 1;
                                        // แสดงข้อมูลแต่ละแถว
                                        mysqli_data_seek($result, 0); // Reset pointer
                                        while ($row = $result->fetch_assoc()) {
                                            $badge_class = ($row['Comments'] === 'admin') ? 'bg-danger' : 'bg-primary';
                                            $role_text = ($row['Comments'] === 'admin') ? 'Admin' : 'User';

                                            echo '<tr>';
                                            echo '<th scope="row">' . $no . '</th>';
                                            echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                                            echo '<td>' . htmlspecialchars($row['field']) . '</td>';
                                            echo '<td>' . htmlspecialchars($row['username']) . '</td>';
                                            echo '<td><span class="badge ' . $badge_class . '">' . $role_text . '</span></td>';
                                            echo '<td>';
                                            echo '<a href="admin_user_edit.php?id=' . $row['user_id'] . '" class="btn btn-sm btn-primary" title="แก้ไข"><i class="bi bi-pencil-square"></i></a> ';

                                            // ไม่แสดงปุ่มลบสำหรับ admin
                                            if ($row['Comments'] !== 'admin') {
                                                echo '<a href="admin_user_delete.php?id=' . $row['user_id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'คุณต้องการลบผู้ใช้นี้หรือไม่?\')" title="ลบ"><i class="bi bi-trash"></i></a>';
                                            }

                                            echo '</td>';
                                            echo '</tr>';
                                            $no++;
                                        }
                                    } else {
                                        echo '<tr><td colspan="6" class="text-center">ไม่พบข้อมูลผู้ใช้งาน</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->

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
<?php
// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
