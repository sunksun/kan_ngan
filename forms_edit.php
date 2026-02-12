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

    <!-- jQuery UI for Autocomplete -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">

    <style>
        .ui-autocomplete {
            max-height: 300px;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 9999 !important;
        }

        .ui-menu-item {
            font-size: 14px;
            padding: 5px;
        }
    </style>

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
                                    <label for="projectType" class="form-label">ประเภทโครงการ</label>
                                    <select id="projectType" class="form-select" name="project_type">
                                        <option value="">กรุณาเลือก...</option>
                                        <option value="โครงการยุทธศาสตร์ฯ" <?php echo ($row["project_type"] == "โครงการยุทธศาสตร์ฯ") ? "selected" : ""; ?>>โครงการยุทธศาสตร์ฯ</option>
                                        <option value="โครงการสาขาวิชา/คณะ" <?php echo ($row["project_type"] == "โครงการสาขาวิชา/คณะ") ? "selected" : ""; ?>>โครงการสาขาวิชา/คณะ</option>
                                        <option value="โครงการวิจัย" <?php echo ($row["project_type"] == "โครงการวิจัย") ? "selected" : ""; ?>>โครงการวิจัย</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="inputState" class="form-label">โดยใช้งบประมาณ</label>
                                    <select id="inputState" class="form-select" name="budget">
                                        <option value="">กรุณาเลือก...</option>
                                        <option value="แผ่นดิน" <?php echo ($row["budget"] == "แผ่นดิน") ? "selected" : ""; ?>>แผ่นดิน</option>
                                        <option value="เงินรายได้" <?php echo ($row["budget"] == "เงินรายได้ บ.กศ.") ? "selected" : ""; ?>>เงินรายได้</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="inputCity" class="form-label">หมายเลขโครงการ</label>
                                    <input type="text" name="project_number" value="<?php echo $row["project_number"]; ?>" class="form-control" id="inputCity">
                                </div>
                                <div class="col-md-2">
                                    <label for="inputCity" class="form-label">วงเงินที่จะจัดซื้อ/จ้าง</label>
                                    <input type="text" name="budget_used" value="<?php echo $row["budget_used"]; ?>" class="form-control" id="inputCity" readonly>
                                </div>
                                <div class="text-center">
                                    <input type="hidden" name="row_count" id="row_count" value="<?php echo isset($row['order_number']) ? $row['order_number'] : '1'; ?>">
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
                                                <th scope="col">ราคา (กลาง)</th>
                                                <th scope="col">หน่วย</th>
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
                                                    $middle_price = isset($rowItem["middle_price"]) ? $rowItem["middle_price"] : '';
                                                    $middle_unit = isset($rowItem["middle_unit"]) ? $rowItem["middle_unit"] : '';
                                                    echo '<tr>';
                                                    echo '<td><input type="text" class="form-control item_name_input" name="item_name[]" value="' . htmlspecialchars($rowItem["item_name"]) . '" placeholder="รายการ" required></td>';
                                                    echo '<td><input type="number" class="form-control" name="middle_price[]" value="' . $middle_price . '" placeholder="ราคา (กลาง)" readonly></td>';
                                                    echo '<td><input type="text" class="form-control" name="middle_unit[]" value="' . htmlspecialchars($middle_unit) . '" placeholder="หน่วย" readonly></td>';
                                                    echo '<td><input type="number" class="form-control quantity" name="quantity[]" value="' . $rowItem["quantity"] . '" placeholder="จำนวน" required oninput="calculateTotal(this)"></td>';
                                                    echo '<td><input type="text" class="form-control" name="unit[]" value="' . htmlspecialchars($rowItem["unit"]) . '" placeholder="หน่วย" required></td>';
                                                    echo '<td><input type="number" class="form-control price_per_unit" name="price_per_unit[]" value="' . $rowItem["price_per_unit"] . '" placeholder="ราคาต่อหน่วย" required oninput="calculateTotal(this)"></td>';
                                                    echo '<td><input type="number" class="form-control total" name="total[]" value="' . ($rowItem["quantity"] * $rowItem["price_per_unit"]) . '" placeholder="รวมเงิน" readonly></td>';
                                                    echo '</tr>';
                                                }
                                            } else {
                                                echo '<tr>';
                                                echo '<td colspan="7">ไม่พบข้อมูล</td>';
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
        // ตรวจสอบและแสดง input ที่เหมาะสมเมื่อโหลดหน้า
        function updateBudgetInput(projectType, currentBudget) {
            const budgetContainer = document.querySelector('#inputState').parentElement;

            if (projectType === 'โครงการวิจัย') {
                // เปลี่ยนเป็น input text
                budgetContainer.innerHTML = `
                <label for="inputState" class="form-label">โดยใช้งบประมาณ</label>
                <input type="text" class="form-control" id="inputState" name="budget" placeholder="เช่น กองทุน ววน.69" value="${currentBudget}">
            `;
            } else {
                // เปลี่ยนเป็น select
                const isAplandin = currentBudget === 'แผ่นดิน' ? 'selected' : '';
                const isIncome = currentBudget === 'เงินรายได้' ? 'selected' : '';
                budgetContainer.innerHTML = `
                <label for="inputState" class="form-label">โดยใช้งบประมาณ</label>
                <select id="inputState" class="form-select" name="budget">
                    <option value="">กรุณาเลือก...</option>
                    <option value="แผ่นดิน" ${isAplandin}>แผ่นดิน</option>
                    <option value="เงินรายได้" ${isIncome}>เงินรายได้ บ.กศ.</option>
                </select>
            `;
            }
        }

        // เรียกใช้เมื่อโหลดหน้า
        const currentProjectType = '<?php echo $row["project_type"]; ?>';
        const currentBudget = '<?php echo $row["budget"]; ?>';
        updateBudgetInput(currentProjectType, currentBudget);

        // เปลี่ยน input งบประมาณเมื่อเปลี่ยนประเภทโครงการ
        document.getElementById('projectType').addEventListener('change', function() {
            updateBudgetInput(this.value, '');
        });
    </script>

    <!-- jQuery and jQuery UI for Autocomplete -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <script>
        $(function() {
            // Autocomplete สำหรับรายการสินค้า
            $(document).on('focus', '.item_name_input', function() {
                if (!$(this).data('autocomplete-initialized')) {
                    $(this).autocomplete({
                        source: function(request, response) {
                            $.ajax({
                                url: "search_middle_price.php",
                                dataType: "json",
                                data: {
                                    term: request.term
                                },
                                success: function(data) {
                                    response(data);
                                }
                            });
                        },
                        minLength: 2,
                        select: function(event, ui) {
                            // หา row ที่เกี่ยวข้อง
                            var row = $(this).closest('tr');

                            // ใส่ค่าในฟิลด์ต่างๆ
                            $(this).val(ui.item.value);
                            row.find('input[name="middle_price[]"]').val(ui.item.price);
                            row.find('input[name="middle_unit[]"]').val(ui.item.unit);

                            return false;
                        }
                    }).data('autocomplete-initialized', true);
                }
            });
        });
    </script>

</body>

</html>