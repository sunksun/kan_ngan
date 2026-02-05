<?php
// เริ่มต้นเซสชัน
session_start();
$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];
$field = $_SESSION['field'];

// ตรวจสอบว่ามีการตั้งค่าเซสชันไว้หรือไม่
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// ตรวจสอบว่ามี ID ที่ส่งมาหรือไม่
if (!isset($_GET['id'])) {
    header("Location: kan_lists.php");
    exit();
}

$report_id = $_GET['id'];

// เชื่อมต่อฐานข้อมูล
require_once 'connect_db.php';

// ดึงข้อมูลรายงาน
$sql = "SELECT * FROM report_request WHERE report_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $report_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "ไม่พบข้อมูล";
    exit();
}

$report = $result->fetch_assoc();
$stmt->close();

// ดึงข้อมูลรายการพัสดุจากตาราง items โดยใช้ kan_no เป็น key
$items_sql = "SELECT * FROM items WHERE kan_no = ?";
$items_stmt = $conn->prepare($items_sql);
$items_stmt->bind_param("s", $report['kan_no']);
$items_stmt->execute();
$items_result = $items_stmt->get_result();

$items = [];
$total = 0;
while ($item = $items_result->fetch_assoc()) {
    $items[] = $item;
    $total += $item['total'];
}
$items_stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>รายละเอียดกันเงิน - กันเงิน กัน</title>
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
            <a href="dashboard.php" class="logo d-flex align-items-center">
                <i class="bi bi-cash-coin" style="font-size: 2rem; color: #012970; margin-right: 0.5rem;"></i>
                <span class="d-none d-lg-block">กันเงิน กัน</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

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

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="users-profile.php">
                                <i class="bi bi-person"></i>
                                <span>โปรไฟล์</span>
                            </a>
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

    <?php include 'includes/sidebar.php'; ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>รายละเอียดกันเงิน</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">หน้าหลัก</a></li>
                    <li class="breadcrumb-item"><a href="kan_lists.php">รายการกันเงิน</a></li>
                    <li class="breadcrumb-item active">รายละเอียด</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">ข้อมูลรายการกันเงิน #<?php echo $report['kan_no']; ?></h5>
                                <div>
                                    <a href="generate_pdf_new.php?id=<?php echo $report_id; ?>" class="btn btn-success" target="_blank">
                                        <i class="bi bi-file-pdf"></i> ดาวน์โหลด PDF
                                    </a>
                                    <a href="forms_edit.php?id=<?php echo $report_id; ?>" class="btn btn-primary">
                                        <i class="bi bi-pencil"></i> แก้ไข
                                    </a>
                                    <a href="kan_lists.php" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left"></i> กลับ
                                    </a>
                                </div>
                            </div>

                            <!-- ข้อมูลทั่วไป -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-primary">ข้อมูลผู้ขอ</h6>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="40%"><strong>ชื่อผู้ขอ:</strong></td>
                                            <td><?php echo htmlspecialchars($report['name']); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>สาขาวิชา/หน่วยงาน:</strong></td>
                                            <td><?php echo htmlspecialchars($report['field']); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>เลขที่กันเงิน:</strong></td>
                                            <td><?php echo htmlspecialchars($report['kan_no']); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>วันที่สร้าง:</strong></td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($report['create_at'])); ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-primary">ข้อมูลโครงการ</h6>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="40%"><strong>ประเภท:</strong></td>
                                            <td>
                                                <span class="badge <?php echo ($report['purpose'] == 'ขอซื้อ') ? 'bg-info' : 'bg-warning'; ?>">
                                                    <?php echo htmlspecialchars($report['purpose']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>ชื่อโครงการ:</strong></td>
                                            <td><?php echo htmlspecialchars($report['project_name']); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>หมายเลขโครงการ:</strong></td>
                                            <td><?php echo htmlspecialchars($report['project_number']); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>งาน/กิจกรรม:</strong></td>
                                            <td><?php echo htmlspecialchars($report['activity']); ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h6 class="fw-bold text-primary">รายละเอียดงบประมาณ</h6>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="20%"><strong>ประเภทงบประมาณ:</strong></td>
                                            <td><?php echo htmlspecialchars($report['budget']); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>วงเงินที่จะจัดซื้อ/จ้าง:</strong></td>
                                            <td><span class="text-success fw-bold"><?php echo number_format((float)$report['budget_used'], 2); ?> บาท</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>เหตุผลความจำเป็น:</strong></td>
                                            <td><?php echo htmlspecialchars($report['reason']); ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- รายการพัสดุ -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h6 class="fw-bold text-primary">รายการพัสดุ/งานจ้าง</h6>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th width="5%" class="text-center">ลำดับ</th>
                                                    <th width="40%">รายการ</th>
                                                    <th width="15%" class="text-center">จำนวน</th>
                                                    <th width="20%" class="text-end">ราคาต่อหน่วย (บาท)</th>
                                                    <th width="20%" class="text-end">รวม (บาท)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (count($items) > 0) {
                                                    $counter = 1;
                                                    foreach ($items as $item) {
                                                        echo '<tr>';
                                                        echo '<td class="text-center">' . $counter . '</td>';
                                                        echo '<td>' . htmlspecialchars($item['item_name']) . '</td>';
                                                        echo '<td class="text-center">' . number_format((float)$item['quantity']) . ' ' . htmlspecialchars($item['unit']) . '</td>';
                                                        echo '<td class="text-end">' . number_format((float)$item['price_per_unit'], 2) . '</td>';
                                                        echo '<td class="text-end fw-bold">' . number_format((float)$item['total'], 2) . '</td>';
                                                        echo '</tr>';
                                                        $counter++;
                                                    }
                                                } else {
                                                    echo '<tr><td colspan="5" class="text-center">ไม่มีรายการพัสดุ</td></tr>';
                                                }
                                                ?>
                                            </tbody>
                                            <tfoot>
                                                <tr class="table-success">
                                                    <td colspan="4" class="text-end fw-bold">รวมทั้งสิ้น:</td>
                                                    <td class="text-end fw-bold fs-5"><?php echo number_format((float)$total, 2); ?> บาท</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>กันเงิน กัน</span></strong>. All Rights Reserved
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

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>

</body>

</html>
