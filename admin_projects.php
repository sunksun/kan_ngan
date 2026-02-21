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

// ดึงข้อมูลสรุปโครงการ จัดกลุ่มตามชื่อโครงการ
$sql = "SELECT
    r.project_name,
    r.budget,
    r.project_type,
    r.activity,
    COUNT(r.report_id) AS total_requests,
    GROUP_CONCAT(DISTINCT r.name ORDER BY r.name SEPARATOR ', ') AS project_owners,
    COALESCE(SUM(i.total), 0) AS total_amount
FROM report_request r
LEFT JOIN items i ON r.kan_no = i.kan_no
WHERE r.project_name IS NOT NULL AND r.project_name != ''
GROUP BY r.project_name, r.budget, r.project_type, r.activity
ORDER BY r.project_name ASC";

$result = $conn->query($sql);

// สรุปตามประเภทงบประมาณ
$sql_budget_summary = "SELECT
    r.budget,
    COUNT(DISTINCT r.project_name) AS project_count,
    COUNT(r.report_id) AS request_count,
    COALESCE(SUM(i.total), 0) AS budget_total
FROM report_request r
LEFT JOIN items i ON r.kan_no = i.kan_no
WHERE r.project_name IS NOT NULL AND r.project_name != ''
GROUP BY r.budget
ORDER BY budget_total DESC";

$result_budget = $conn->query($sql_budget_summary);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>สรุปโครงการ - Admin</title>
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

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">
        <?php include 'includes/sidebar_admin.php'; ?>
    </aside><!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>สรุปโครงการ</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="admin_dash.php">Admin Dashboard</a></li>
                    <li class="breadcrumb-item">รายงานและสรุป</li>
                    <li class="breadcrumb-item active">สรุปโครงการ</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">

                <!-- สรุปตามประเภทงบประมาณ -->
                <div class="col-lg-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">สรุปตามประเภทงบประมาณ</h5>

                            <div class="row">
                                <?php
                                if ($result_budget && $result_budget->num_rows > 0) {
                                    $colors = ['primary', 'success', 'warning', 'info', 'danger'];
                                    $icons = ['bi-cash-stack', 'bi-wallet2', 'bi-piggy-bank', 'bi-currency-dollar', 'bi-bank'];
                                    $index = 0;

                                    while ($row = $result_budget->fetch_assoc()) {
                                        $color = $colors[$index % count($colors)];
                                        $icon = $icons[$index % count($icons)];
                                        ?>
                                        <div class="col-xxl-4 col-md-6">
                                            <div class="card info-card">
                                                <div class="card-body">
                                                    <h5 class="card-title"><?php echo htmlspecialchars($row['budget']); ?></h5>
                                                    <div class="d-flex align-items-center">
                                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="color: var(--bs-<?php echo $color; ?>); background: var(--bs-<?php echo $color; ?>-bg-subtle); width: 64px; height: 64px; font-size: 32px;">
                                                            <i class="bi <?php echo $icon; ?>"></i>
                                                        </div>
                                                        <div class="ps-3">
                                                            <h6><?php echo number_format($row['budget_total'], 2); ?> บาท</h6>
                                                            <span class="text-muted small pt-2">
                                                                <?php echo $row['project_count']; ?> โครงการ |
                                                                <?php echo $row['request_count']; ?> ใบกัน
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        $index++;
                                    }
                                }
                                ?>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- ตารางรายละเอียดโครงการ -->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">รายละเอียดโครงการทั้งหมด</h5>

                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">ชื่อโครงการ</th>
                                        <th scope="col">งบประมาณ</th>
                                        <th scope="col">ประเภทโครงการ</th>
                                        <th scope="col">กิจกรรม</th>
                                        <th scope="col">เจ้าของโครงการ</th>
                                        <th scope="col">จำนวนใบกัน</th>
                                        <th scope="col">ยอดเงินรวม (บาท)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result->num_rows > 0) {
                                        $no = 1;
                                        mysqli_data_seek($result, 0);
                                        while ($row = $result->fetch_assoc()) {
                                            // กำหนดสี badge ตามประเภทงบประมาณ
                                            $badge_class = 'bg-primary';
                                            if (strpos($row['budget'], 'แผ่นดิน') !== false) {
                                                $badge_class = 'bg-success';
                                            } elseif (strpos($row['budget'], 'รายได้') !== false) {
                                                $badge_class = 'bg-info';
                                            } elseif (strpos($row['budget'], 'วิจัย') !== false) {
                                                $badge_class = 'bg-warning';
                                            }

                                            echo '<tr>';
                                            echo '<th scope="row">' . $no . '</th>';
                                            echo '<td>' . htmlspecialchars($row['project_name']) . '</td>';
                                            echo '<td><span class="badge ' . $badge_class . '">' . htmlspecialchars($row['budget']) . '</span></td>';
                                            echo '<td>' . htmlspecialchars($row['project_type'] ?? '-') . '</td>';
                                            echo '<td>' . htmlspecialchars($row['activity'] ?? '-') . '</td>';
                                            echo '<td>' . htmlspecialchars($row['project_owners']) . '</td>';
                                            echo '<td class="text-center">' . $row['total_requests'] . '</td>';
                                            echo '<td class="text-end">' . number_format($row['total_amount'], 2) . '</td>';
                                            echo '</tr>';
                                            $no++;
                                        }
                                    } else {
                                        echo '<tr><td colspan="8" class="text-center">ไม่พบข้อมูลโครงการ</td></tr>';
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
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
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
