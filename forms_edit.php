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
    <link href="assets/img/favicon.png" rel="icon">
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
                <img src="assets/img/logo.png" alt="">
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

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
                <a class="nav-link " href="dashboard.php">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li><!-- End Dashboard Nav -->
            <!-- 
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-menu-button-wide"></i><span>Components</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="components-alerts.html">
                            <i class="bi bi-circle"></i><span>Alerts</span>
                        </a>
                    </li>
                    <li>
                        <a href="components-accordion.html">
                            <i class="bi bi-circle"></i><span>Accordion</span>
                        </a>
                    </li>
                    <li>
                        <a href="components-badges.html">
                            <i class="bi bi-circle"></i><span>Badges</span>
                        </a>
                    </li>
                    <li>
                        <a href="components-breadcrumbs.html">
                            <i class="bi bi-circle"></i><span>Breadcrumbs</span>
                        </a>
                    </li>
                    <li>
                        <a href="components-buttons.html">
                            <i class="bi bi-circle"></i><span>Buttons</span>
                        </a>
                    </li>
                    <li>
                        <a href="components-cards.html">
                            <i class="bi bi-circle"></i><span>Cards</span>
                        </a>
                    </li>
                    <li>
                        <a href="components-carousel.html">
                            <i class="bi bi-circle"></i><span>Carousel</span>
                        </a>
                    </li>
                    <li>
                        <a href="components-list-group.html">
                            <i class="bi bi-circle"></i><span>List group</span>
                        </a>
                    </li>
                    <li>
                        <a href="components-modal.html">
                            <i class="bi bi-circle"></i><span>Modal</span>
                        </a>
                    </li>
                    <li>
                        <a href="components-tabs.html">
                            <i class="bi bi-circle"></i><span>Tabs</span>
                        </a>
                    </li>
                    <li>
                        <a href="components-pagination.html">
                            <i class="bi bi-circle"></i><span>Pagination</span>
                        </a>
                    </li>
                    <li>
                        <a href="components-progress.html">
                            <i class="bi bi-circle"></i><span>Progress</span>
                        </a>
                    </li>
                    <li>
                        <a href="components-spinners.html">
                            <i class="bi bi-circle"></i><span>Spinners</span>
                        </a>
                    </li>
                    <li>
                        <a href="components-tooltips.html">
                            <i class="bi bi-circle"></i><span>Tooltips</span>
                        </a>
                    </li>
                </ul>
            </li> -->
            <!-- End Components Nav -->
            <!-- 
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-journal-text"></i><span>Forms</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="forms-elements.html">
                            <i class="bi bi-circle"></i><span>Form Elements</span>
                        </a>
                    </li>
                    <li>
                        <a href="forms-layouts.html">
                            <i class="bi bi-circle"></i><span>Form Layouts</span>
                        </a>
                    </li>
                    <li>
                        <a href="forms-editors.html">
                            <i class="bi bi-circle"></i><span>Form Editors</span>
                        </a>
                    </li>
                    <li>
                        <a href="forms-validation.html">
                            <i class="bi bi-circle"></i><span>Form Validation</span>
                        </a>
                    </li>
                </ul>
            </li> -->
            <!-- End Forms Nav -->
            <!-- 
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-layout-text-window-reverse"></i><span>Tables</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="tables-general.html">
                            <i class="bi bi-circle"></i><span>General Tables</span>
                        </a>
                    </li>
                    <li>
                        <a href="tables-data.html">
                            <i class="bi bi-circle"></i><span>Data Tables</span>
                        </a>
                    </li>
                </ul>
            </li> -->
            <!-- End Tables Nav -->
            <!-- 
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-bar-chart"></i><span>Charts</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="charts-chartjs.html">
                            <i class="bi bi-circle"></i><span>Chart.js</span>
                        </a>
                    </li>
                    <li>
                        <a href="charts-apexcharts.html">
                            <i class="bi bi-circle"></i><span>ApexCharts</span>
                        </a>
                    </li>
                    <li>
                        <a href="charts-echarts.html">
                            <i class="bi bi-circle"></i><span>ECharts</span>
                        </a>
                    </li>
                </ul>
            </li> -->
            <!-- End Charts Nav -->
            <!-- 
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#icons-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-gem"></i><span>Icons</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="icons-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="icons-bootstrap.html">
                            <i class="bi bi-circle"></i><span>Bootstrap Icons</span>
                        </a>
                    </li>
                    <li>
                        <a href="icons-remix.html">
                            <i class="bi bi-circle"></i><span>Remix Icons</span>
                        </a>
                    </li>
                    <li>
                        <a href="icons-boxicons.html">
                            <i class="bi bi-circle"></i><span>Boxicons</span>
                        </a>
                    </li>
                </ul>
            </li> -->
            <!-- End Icons Nav -->
            <!-- 
            <li class="nav-heading">Pages</li>
            -->
            <!-- 
            <li class="nav-item">
                <a class="nav-link collapsed" href="pages-faq.html">
                    <i class="bi bi-question-circle"></i>
                    <span>F.A.Q</span>
                </a>
            </li> -->
            <!-- End F.A.Q Page Nav -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="kan_lists.php">
                    <i class="bi bi-card-list"></i>
                    <span>รายการกันเงิน กัน</span>
                </a>
            </li><!-- End Register Page Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" href="forms.php">
                    <i class="bi bi-journal-text"></i>
                    <span>ฟอร์มกันเงิน กัน</span>
                </a>
            </li>
            <!-- End Contact Page Nav -->


            <li class="nav-item">
                <a class="nav-link collapsed" href="sign_out.php">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Sign Out</span>
                </a>
            </li><!-- End Login Page Nav -->
            <!-- 
            <li class="nav-item">
                <a class="nav-link collapsed" href="pages-error-404.html">
                    <i class="bi bi-dash-circle"></i>
                    <span>Error 404</span>
                </a>
            </li> -->
            <!-- End Error 404 Page Nav -->
            <!-- 
            <li class="nav-item">
                <a class="nav-link collapsed" href="pages-blank.html">
                    <i class="bi bi-file-earmark"></i>
                    <span>Blank</span>
                </a>
            </li> -->
            <!-- End Blank Page Nav -->

        </ul>

    </aside><!-- End Sidebar-->

    <main id="main" class="main">
        <?php
        // ข้อมูลการเชื่อมต่อฐานข้อมูล
        $servername = "localhost";
        $username = "root";
        $password = ""; // ใส่รหัสผ่านของฐานข้อมูล MySQL
        $dbname = "kan_ngan"; // ใส่ชื่อฐานข้อมูลที่ต้องการใช้

        // สร้างการเชื่อมต่อ
        $conn = new mysqli($servername, $username, $password, $dbname);

        // ตรวจสอบการเชื่อมต่อ
        if ($conn->connect_error) {
            die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
        }
        ?>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <?php

                    $report_id = $_GET['id'];
                    // คำสั่ง SQL เพื่อดึงข้อมูลจากตาราง
                    $sql = "SELECT * FROM `report_request` WHERE report_id = '$report_id';";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        // output data of each row
                        $row = $result->fetch_assoc();
                        $report_id = $row["report_id"];
                        $kan_no = $row["kan_no"];
                    }
                    ?>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">แก้ไข รายงานขอซื้อหรือขอจ้าง [<?php echo $row["kan_no"]; ?>]</h5>

                            <!-- Multi Columns Form -->
                            <form name="report_request" action="forms_edit_save.php?report_id=<?php echo $report_id; ?>&kan_no=<?php echo $row["kan_no"]; ?>" method="POST" class="row g-3">
                                <div class="col-md-12">
                                    <label for="inputName5" class="form-label">สาขาวิชา</label>
                                    <input type="text" name="field" value="<?php echo $row['field']; ?>" class="form-control" id="inputName5" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="inputEmail5" class="form-label">ชื่อ-นามสกุล</label>
                                    <input type="text" name="name" value="<?php echo $row['name']; ?>" class="form-control" id="inputEmail5" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label for="inputPassword5" class="form-label">มีความประสงค์</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="purpose" id="gridRadios1" value="ขอซื้อ" <?php echo ($row['purpose'] == 'ขอซื้อ') ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="gridRadios1">
                                            ขอซื้อ
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="purpose" id="gridRadios2" value="ขอจ้าง" <?php echo ($row['purpose'] == 'ขอจ้าง') ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="gridRadios2">
                                            ขอจ้าง
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="inputEmail5" class="form-label">&nbsp;</label>
                                    <input type="text" name="purpose_" value="<?php echo $row["purpose_"]; ?>" class="form-control" id="inputEmail5">
                                </div>
                                <div class="col-12">
                                    <label for="inputAddress5" class="form-label">เพื่อใช้ในโครงการ</label>
                                    <input type="text" name="project_name" value="<?php echo $row["project_name"]; ?>" class="form-control" id="inputAddres5s" placeholder="">
                                </div>
                                <div class="col-12">
                                    <label for="inputAddress2" class="form-label">โดยพัสดุ/งานจ้างครั้งนี้ใช้ในงาน/กิจกรรม</label>
                                    <input type="text" name="activity" value="<?php echo $row["activity"]; ?>" class="form-control" id="inputAddress2" placeholder="">
                                </div>
                                <div class="col-md-4">
                                    <label for="inputState" class="form-label">โดยใช้งบประมาณ</label>
                                    <select id="inputState" class="form-select" name="budget">
                                        <option value="">กรุณาเลือก...</option>
                                        <option value="แผ่นดิน" <?php echo ($row["budget"] == "แผ่นดิน") ? "selected" : ""; ?>>แผ่นดิน</option>
                                        <option value="เงินรายได้" <?php echo ($row["budget"] == "เงินรายได้") ? "selected" : ""; ?>>เงินรายได้</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="inputCity" class="form-label">หมายเลขโครงการ</label>
                                    <input type="text" name="project_number" value="<?php echo $row["project_number"]; ?>" class="form-control" id="inputCity">
                                </div>
                                <div class="col-md-2">
                                    <label for="inputCity" class="form-label">วงเงินที่จะจัดซื้อ/จ้าง</label>
                                    <input type="text" name="budget_used" value="<?php echo $row["budget_used"]; ?>" class="form-control" id="inputCity" readonly>
                                </div>
                                <div class="text-center">
                                    <input type="hidden" name="row_count" id="row_count" value="<?php echo $row['order_number']; ?>">
                                </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">แก้ไข รายละเอียดของพัสดุที่จะขอ [<?php echo $row["kan_no"]; ?>]</h5>
                            <div id="item-container">
                                <div class="item-row">
                                    <?php
                                    // คำสั่ง SQL
                                    $sqlItem = "SELECT * FROM items WHERE kan_no = '$kan_no'";
                                    $resultItem = $conn->query($sqlItem);
                                    ?>
                                    <!-- Default Table -->
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">รายการ</th>
                                                <th scope="col">จำนวน</th>
                                                <th scope="col">หน่วย</th>
                                                <th scope="col">ราคาต่อหน่วย</th>
                                                <th scope="col">รวมเงิน</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($resultItem->num_rows > 0) {
                                                // แสดงผลข้อมูลของแต่ละแถว
                                                while ($rowItem = $resultItem->fetch_assoc()) {
                                                    echo '<tr>';
                                                    echo '<td><input type="text" class="form-control" name="item_name[]" value="' . $rowItem["item_name"] . '" placeholder="รายการ" required></td>';
                                                    echo '<td><input type="number" class="form-control quantity" name="quantity[]" value="' . $rowItem["quantity"] . '" placeholder="จำนวน" required oninput="calculateTotal(this)"></td>';
                                                    echo '<td><input type="text" class="form-control" name="unit[]" value="' . $rowItem["unit"] . '" placeholder="หน่วย" required></td>';
                                                    echo '<td><input type="number" class="form-control price_per_unit" name="price_per_unit[]" value="' . $rowItem["price_per_unit"] . '" placeholder="ราคาต่อหน่วย" required oninput="calculateTotal(this)"></td>';
                                                    echo '<td><input type="number" class="form-control total" name="total[]" value="' . ($rowItem["quantity"] * $rowItem["price_per_unit"]) . '" placeholder="รวมเงิน" readonly></td>';
                                                    echo '</tr>';
                                                }
                                            } else {
                                                echo '<tr>';
                                                echo '<td colspan="5">ไม่พบข้อมูล</td>';
                                                echo '</tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <!-- End Default Table Example -->
                                </div>
                            </div>
                            <div class="d-flex flex-row-reverse bd-highlight">
                                <div class="p-2 bd-highlight">
                                    <input type="text" name="total_budget_used" class="form-control" id="total_budget_used" placeholder="ราคารวม" readonly>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="inputAddress5" class="form-label">เหตุผลและความจำเป็นที่จะต้องซื้อหรือจ้าง</label>
                                <input type="text" name="reason" value="<?php echo $row["reason"]; ?>" class="form-control" id="inputAddres5s" placeholder="">
                            </div>
                        </div>
                        <div class="text-center">
                            <input type="hidden" name="kan_no" id="kan_no" value="<?php echo $kan_no; ?>">
                            <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                            <button type="reset" class="btn btn-secondary">ล้างค่า</button>
                        </div>
                        </form>
                    </div>

                    <script>
                        function calculateTotal(element) {
                            let row = element.closest('tr');
                            let quantity = row.querySelector('input[name="quantity[]"]').value;
                            let pricePerUnit = row.querySelector('input[name="price_per_unit[]"]').value;
                            let totalField = row.querySelector('input[name="total[]"]');

                            let total = quantity * pricePerUnit;
                            totalField.value = total;

                            calculateGrandTotal();
                        }

                        function calculateGrandTotal() {
                            let totalFields = document.querySelectorAll('input[name="total[]"]');
                            let grandTotal = 0;

                            totalFields.forEach(function(field) {
                                grandTotal += parseFloat(field.value) || 0;
                            });

                            document.getElementById('total_budget_used').value = grandTotal;
                        }

                        document.addEventListener('DOMContentLoaded', (event) => {
                            calculateGrandTotal();
                        });
                    </script>
                    <?php $conn->close(); ?>



                </div>


            </div>

        </section>
        <script>
            // ฟังก์ชั่นเพื่อเลือก radio button ตามข้อมูลที่ได้จากตาราง
            function selectRadioButton(purpose) {
                if (purpose === "ขอซื้อ") {
                    document.getElementById('gridRadios1').checked = true;
                } else if (purpose === "ขอจ้าง") {
                    document.getElementById('gridRadios2').checked = true;
                }
            }

            // เรียกฟังก์ชั่น selectRadioButton โดยใส่ค่าที่ได้จากตาราง เช่น
            // สมมติว่าได้ข้อมูล "ขอจ้าง" จากตาราง
            var purposeFromTable = "<?php echo $row["purpose"]; ?>";
            selectRadioButton(purposeFromTable);

            // รับค่าจาก PHP และส่งเข้ามาใน JavaScript
            var budgetFromTable = "<?php echo $row["budget"]; ?>";

            // ฟังก์ชั่นเพื่อเลือก option ของ select ตามข้อมูลที่ได้จากตาราง
            function selectBudget(budget) {
                var selectElement = document.getElementById('inputState');
                for (var i = 0; i < selectElement.options.length; i++) {
                    if (selectElement.options[i].value === budget) {
                        selectElement.selectedIndex = i;
                        break;
                    }
                }
            }

            // เรียกฟังก์ชั่น selectBudget โดยใส่ค่าที่ได้จากตาราง
            selectBudget(budgetFromTable);
        </script>
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