<?php
// เริ่มต้นเซสชัน
session_start();

// ตรวจสอบว่ามีการตั้งค่าเซสชันไว้หรือไม่
if (!isset($_SESSION['username'])) {
    // หากไม่มีเซสชัน ให้เปลี่ยนเส้นทางกลับไปยังหน้าเข้าสู่ระบบ
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];
$field = $_SESSION['field'];

// เชื่อมต่อฐานข้อมูล
require_once 'connect_db.php';

// 1. นับจำนวนรายการที่กันไปแล้ว (จำนวน report_request ของ user)
$sql_count = "SELECT COUNT(*) as total_requests FROM report_request WHERE user_id = ?";
$stmt_count = $conn->prepare($sql_count);
$stmt_count->bind_param("i", $user_id);
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$row_count = $result_count->fetch_assoc();
$total_requests = $row_count['total_requests'];
$stmt_count->close();

// 2. รวมยอดเงินทั้งหมด (total จากตาราง items ที่เชื่อมกับ report_request)
$sql_sum = "SELECT SUM(i.total) as total_amount
            FROM items i
            INNER JOIN report_request r ON i.kan_no = r.kan_no
            WHERE r.user_id = ?";
$stmt_sum = $conn->prepare($sql_sum);
$stmt_sum->bind_param("i", $user_id);
$stmt_sum->execute();
$result_sum = $stmt_sum->get_result();
$row_sum = $result_sum->fetch_assoc();
$total_amount = $row_sum['total_amount'] ? $row_sum['total_amount'] : 0;
$stmt_sum->close();

// 3. นับจำนวนโครงการทั้งหมด (นับโครงการที่ไม่ซ้ำกัน)
$sql_project = "SELECT COUNT(DISTINCT project_name) as total_projects FROM report_request WHERE user_id = ? AND project_name IS NOT NULL AND project_name != ''";
$stmt_project = $conn->prepare($sql_project);
$stmt_project->bind_param("i", $user_id);
$stmt_project->execute();
$result_project = $stmt_project->get_result();
$row_project = $result_project->fetch_assoc();
$total_projects = $row_project['total_projects'];
$stmt_project->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Dashboard - กันเงิน กัน</title>
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

    <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 20 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="index.html" class="logo d-flex align-items-center">
                <i class="bi bi-cash-coin" style="font-size: 2rem; color: #012970; margin-right: 0.5rem;"></i>
                <span class="d-none d-lg-block">กันเงิน กัน</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->


        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

                <li class="nav-item d-block d-lg-none">
                    <a class="nav-link nav-icon search-bar-toggle " href="#">
                        <i class="bi bi-search"></i>
                    </a>
                </li><!-- End Search Icon-->
                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
                        <span class="d-none d-md-block dropdown-toggle ps-2">[<?php echo $user_id; ?>]<?php echo $_SESSION['username']; ?></span>
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6><?php echo $name; ?></h6>
                            <span><?php echo $field; ?></span>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->

            </ul>
        </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->

    <?php include 'includes/sidebar.php'; ?>

    <main id="main" class="main">


        <section class="section dashboard">
            <div class="row">

                <!-- Left side columns -->
                <div class="col-lg-12">
                    <div class="row">

                        <!-- Sales Card -->
                        <div class="col-xxl-4 col-md-6">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">กันไปแล้ว</h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-cart"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6><?php echo number_format($total_requests); ?></h6>
                                            <span class="text-success small pt-1 fw-bold">ชุด</span>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div><!-- End Sales Card -->

                        <!-- Revenue Card -->
                        <div class="col-xxl-4 col-md-6">
                            <div class="card info-card revenue-card">

                                <div class="card-body">
                                    <h5 class="card-title">ทั้งหมด (บาท) </h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-currency-dollar"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6><?php echo number_format($total_amount, 2); ?></h6>
                                            <span class="text-success small pt-1 fw-bold">บาท</span>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div><!-- End Revenue Card -->

                        <!-- Customers Card -->
                        <div class="col-xxl-4 col-xl-12">

                            <div class="card info-card customers-card">

                                <div class="card-body">
                                    <h5 class="card-title">โครงการทั้งหมด</h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6><?php echo number_format($total_projects); ?></h6>
                                            <span class="text-danger small pt-1 fw-bold">โครงการ</span>

                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div><!-- End Customers Card -->

                        <!-- Reports -->


                        <!-- Recent Sales -->


                        <!-- Top Selling -->


                    </div>
                </div><!-- End Left side columns -->

                <!-- Right side columns -->


            </div>
        </section>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
            <!-- All the links in the footer should remain intact. -->
            <!-- You can delete the links only if you purchased the pro version. -->
            <!-- Licensing information: https://bootstrapmade.com/license/ -->
            <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
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