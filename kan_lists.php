<?php
// เริ่มต้นเซสชัน
session_start();
$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];
$field = $_SESSION['field'];

// ตรวจสอบว่ามีการตั้งค่าเซสชันไว้หรือไม่
if (isset($_SESSION['username'])) {
    // แสดงชื่อผู้ใช้ที่เข้าสู่ระบบ
    echo "ยินดีต้อนรับ, " . $_SESSION['username'];
} else {
    // หากไม่มีเซสชัน ให้เปลี่ยนเส้นทางกลับไปยังหน้าเข้าสู่ระบบ
    header("Location: index.php");
    exit();
}
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

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

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
                        <!-- Top Selling -->
                        <div class="col-12">
                            <div class="card top-selling overflow-auto">

                                <div class="card-body pb-0">
                                    <h5 class="card-title">รายการกันเงิน กัน</span></h5>

                                    <table class="table table-borderless">
                                        <thead>
                                            <tr>
                                                <th scope="col">ลำดับ</th>
                                                <th scope="col">หมายเลขโครงการ</th>
                                                <th scope="col">ชื่อโครงการ</th>
                                                <th scope="col">วงเงินที่จะจัดซื้อ/จ้าง</th>
                                                <th scope="col">ส่งออก PDF/แก้ไข/ลบ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // ข้อมูลการเชื่อมต่อฐานข้อมูล
                                            require_once 'connect_db.php';

                                            $sql = "SELECT * FROM report_request WHERE user_id = ? ORDER BY `report_request`.`kan_no` DESC";
                                            $stmt = $conn->prepare($sql);
                                            $stmt->bind_param("i", $user_id);
                                            $stmt->execute();
                                            $result = $stmt->get_result();

                                            if ($result->num_rows > 0) {
                                                $counter = 1; // ตัวแปรนับเพื่อเก็บลำดับการ
                                                while ($row = $result->fetch_assoc()) {
                                                    echo '<tr>';
                                                    echo '<th scope="row">' . $counter . '</th>'; // ใช้ตัวแปรนับแทน report_id
                                                    echo '<td>' . $row['project_number'] . '</td>';
                                                    $project_name = $row['project_name'];
                                                    $project_name = $row['project_name'];

                                                    if (mb_strlen($project_name) > 20) {
                                                        $project_name = mb_substr($project_name, 0, 40) . '...';
                                                    }
                                                    echo '<td><a href="kan_details.php?id=' . $row['report_id'] . '">' . $project_name . '</a></td>'; // ลิงก์ไปที่ไฟล์ kan_details.php

                                                    echo '<td>' . number_format((float)$row['budget_used'], 2) . '</td>';
                                                    echo '<td>
                                                    <a class="btn btn-success btn-sm" href="generate_pdf_new.php?id=' . $row['report_id'] . '" target="_blank"><i class="bi bi-file-pdf"></i> PDF</a>
                                                    <a class="btn btn-primary btn-sm" href="forms_edit.php?id=' . $row['report_id'] . '"><i class="bi bi-pencil"></i> แก้ไข</a>
                                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete(' . $row['report_id'] . ')"><i class="bi bi-trash"></i> ลบ</button>
                                                    </td>';
                                                    echo '</tr>';
                                                    $counter++; // เพิ่มตัวแปรนับทุกครั้งที่ผ่านลูป
                                                }
                                            } else {
                                                echo '<tr>';
                                                echo '<td colspan="5" class="text-center">ไม่มีข้อมูล</td>';
                                                echo '</tr>';
                                            }

                                            $stmt->close();
                                            $conn->close();
                                            ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div><!-- End Top Selling -->

                    </div>
                </div><!-- End Left side columns -->

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

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Delete Confirmation Script -->
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'ยืนยันการลบ?',
                text: "คุณต้องการลบรายการนี้ใช่หรือไม่? การลบจะไม่สามารถกู้คืนได้",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    // ส่งไปยังหน้าลบข้อมูล
                    window.location.href = 'delete_request.php?id=' + id;
                }
            });
        }
    </script>

</body>

</html>