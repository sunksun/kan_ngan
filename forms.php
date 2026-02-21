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

    <title>ฟอร์มรายงานขอซื้อหรือขอจ้าง - กันเงิน กัน</title>
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
        <?php
        // ข้อมูลการเชื่อมต่อฐานข้อมูล
        require_once 'connect_db.php';

        // ใช้ MAX เพื่อหาเลขที่ใบกันล่าสุด และบวก 1 เพื่อป้องกัน race condition
        $sql = "SELECT IFNULL(MAX(CAST(SUBSTRING(kan_no, 9) AS UNSIGNED)), 0) + 1 AS next_number FROM report_request";
        $result = $conn->query($sql);

        $next_number = 1;
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $next_number = $row['next_number'];
        }

        $order_number = "KAN_NGAN" . str_pad($next_number, 4, '0', STR_PAD_LEFT);

        $conn->close();
        //echo $order_number;
        ?>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">รายงานขอซื้อหรือขอจ้าง [<?php echo $order_number; ?>]</h5>

                            <!-- Multi Columns Form -->
                            <form name="report_request" action="forms_save.php" method="POST" class="row g-3">
                                <div class="col-md-12">
                                    <label for="inputName5" class="form-label">สาขาวิชา</label>
                                    <input type="text" name="field" value="<?php echo $field; ?>" class="form-control" id="inputName5" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="inputEmail5" class="form-label">ชื่อ-นามสกุล</label>
                                    <input type="text" name="name" value="<?php echo $name; ?>" class="form-control" id="inputEmail5" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label for="inputPassword5" class="form-label">มีความประสงค์</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="purpose" id="gridRadios1" value="ขอซื้อ" checked>
                                        <label class="form-check-label" for="gridRadios1">
                                            ขอซื้อ
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="purpose" id="gridRadios2" value="ขอจ้าง">
                                        <label class="form-check-label" for="gridRadios2">
                                            ขอจ้าง
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="inputEmail5" class="form-label">&nbsp;</label>
                                    <input type="text" name="purpose_" class="form-control" id="inputEmail5" placeholder="เช่น ระบุรายการและ จำนวนรายการวัสดุ/งานจ้าง">
                                </div>
                                <div class="col-12">
                                    <label for="inputAddress5" class="form-label">เพื่อใช้ในโครงการ</label>
                                    <input type="text" name="project_name" class="form-control" id="inputAddres5s" placeholder="">
                                </div>
                                <div class="col-12">
                                    <label for="inputAddress2" class="form-label">โดยพัสดุ/งานจ้างครั้งนี้ใช้ในงาน/กิจกรรม</label>
                                    <input type="text" name="activity" class="form-control" id="inputAddress2" placeholder="">
                                </div>
                                <div class="col-md-4">
                                    <label for="projectType" class="form-label">ประเภทโครงการ</label>
                                    <select id="projectType" class="form-select" name="project_type">
                                        <option selected>กรุณาเลือก...</option>
                                        <option value="โครงการยุทธศาสตร์ฯ">โครงการยุทธศาสตร์ฯ</option>
                                        <option value="โครงการสาขาวิชา/คณะ">โครงการสาขาวิชา/คณะ</option>
                                        <option value="โครงการวิจัย">โครงการวิจัย</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="inputState" class="form-label">โดยใช้งบประมาณ</label>
                                    <select id="inputState" class="form-select" name="budget">
                                        <option selected>กรุณาเลือก...</option>
                                        <option value="แผ่นดิน">แผ่นดิน</option>
                                        <option value="เงินรายได้ บ.กศ.">เงินรายได้ บ.กศ.</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="inputCity" class="form-label">หมายเลขโครงการ</label>
                                    <input type="text" name="project_number" class="form-control" id="inputCity" placeholder="เช่น 680205216 หรือ กองทุน ววน.69">
                                </div>
                                <div class="text-center">
                                    <input type="hidden" name="row_count" id="row_count" value="<?php echo $order_number; ?>">
                                    <button type="submit" class="btn btn-primary">ถัดไป</button>
                                    <button type="reset" class="btn btn-secondary">ล้างค่า</button>
                                </div>
                            </form><!-- End Multi Columns Form -->

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

    <script>
        // เปลี่ยน input งบประมาณเมื่อเลือกโครงการวิจัย
        document.getElementById('projectType').addEventListener('change', function() {
            const budgetContainer = document.querySelector('#inputState').parentElement;
            const projectType = this.value;

            if (projectType === 'โครงการวิจัย') {
                // เปลี่ยนเป็น input text
                budgetContainer.innerHTML = `
                <label for="inputState" class="form-label">โดยใช้งบประมาณ</label>
                <input type="text" class="form-control" id="inputState" name="budget" placeholder="เช่น กองทุน ววน.69">
            `;
            } else {
                // เปลี่ยนกลับเป็น select
                budgetContainer.innerHTML = `
                <label for="inputState" class="form-label">โดยใช้งบประมาณ</label>
                <select id="inputState" class="form-select" name="budget">
                    <option selected>กรุณาเลือก...</option>
                    <option value="แผ่นดิน">แผ่นดิน</option>
                    <option value="เงินรายได้ บ.กศ.">เงินรายได้ บ.กศ.</option>
                </select>
            `;
            }
        });
    </script>

</body>

</html>