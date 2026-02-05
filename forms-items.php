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
        $kan_no = $_GET['kan_no'];

        // เชื่อมต่อฐานข้อมูลเพื่อดึงข้อมูลราคากลาง
        require_once 'connect_db.php';
        $sql = "SELECT id, item_name, unit, price FROM middle_price ORDER BY category, item_name";
        $result = $conn->query($sql);
        $middle_price_items = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $middle_price_items[] = $row;
            }
        }
        $conn->close();
        ?>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">รายละเอียดของพัสดุที่จะขอ [<?php echo $kan_no; ?>]</h5>
                            <form name="report_request" action="forms-items_save.php" method="POST" class="row g-3">
                                <div id="item-container">
                                    <div class="item-row">
                                        <!-- Default Table -->
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col" style="width: 25%;">รายการ</th>
                                                    <th scope="col" style="width: 12%;">ราคา (กลาง)</th>
                                                    <th scope="col" style="width: 8%;">หน่วย</th>
                                                    <th scope="col" style="width: 12%;">จำนวน</th>
                                                    <th scope="col" style="width: 12%;">หน่วย</th>
                                                    <th scope="col" style="width: 15%;">ราคาต่อหน่วย</th>
                                                    <th scope="col" style="width: 16%;">รวมเงิน</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <input type="text" class="form-control" name="item_name[]" placeholder="รายการ" required>
                                                    </td>
                                                    <td><input type="number" class="form-control middle-price" name="middle_price[]" placeholder="" readonly></td>
                                                    <td><input type="text" class="form-control middle-unit" name="middle_unit[]" placeholder="" readonly></td>
                                                    <td><input type="number" class="form-control quantity" name="quantity[]" placeholder="จำนวน" required></td>
                                                    <td><input type="text" class="form-control unit" name="unit[]" placeholder="หน่วย" required></td>
                                                    <td><input type="number" class="form-control price_per_unit" name="price_per_unit[]" placeholder="ราคาต่อหน่วย" required></td>
                                                    <td><input type="number" class="form-control total" name="total[]" placeholder="รวมเงิน" readonly></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <!-- End Default Table Example -->
                                    </div>
                                </div>
                                <div class="d-flex flex-row-reverse bd-highlight">
                                    <div class="p-2 bd-highlight">
                                        <input type="text" name="budget_used" class="form-control" id="inputAddres5s" placeholder="ราคารวม" readonly>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-success" onclick="addItemRow()">เพิ่มรายการ</button>
                                <div class="col-12">
                                    <label for="inputAddress5" class="form-label">เหตุผลและความจำเป็นที่จะต้องซื้อหรือจ้าง</label>
                                    <input type="text" name="reason" class="form-control" id="inputAddres5s" placeholder="เขียนเหตุผลและความจำเป็นที่จะต้องซื้อหรือจ้างสั้นๆ ได้ใจความ" required>
                                </div>
                        </div>
                        <div class="text-center">
                            <input type="hidden" name="kan_no" id="kan_no" value="<?php echo $kan_no; ?>">
                            <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                            <button type="reset" class="btn btn-secondary">ล้างค่า</button>
                        </div>
                        </form>
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

    <!-- jQuery and jQuery UI -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

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

    <!-- Custom Scripts -->
    <script>
        // ฟังก์ชันสำหรับเปิดใช้งาน autocomplete สำหรับ input รายการ
        function initAutocomplete(inputElement) {
            $(inputElement).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        // ใช้ Path แบบสัมพันธ์เพื่อให้รองรับทั้ง http และ https
                        url: 'search_middle_price.php',
                        dataType: 'json',
                        data: {
                            term: request.term
                        },
                        success: function(data) {
                            response(data);
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', status, error);
                            response([]);
                        }
                    });
                },
                minLength: 2,
                select: function(event, ui) {
                    const row = $(this).closest('tr');
                    row.find('.middle-price').val(ui.item.price);
                    row.find('.middle-unit').val(ui.item.unit);
                    row.find('.unit').val(ui.item.unit);
                    row.find('.price_per_unit').val(ui.item.price);

                    const quantity = row.find('.quantity').val();
                    if (quantity) {
                        const total = parseFloat(quantity) * parseFloat(ui.item.price);
                        row.find('.total').val(total.toFixed(2));
                        calculateGrandTotal();
                    }
                    return true;
                }
            });
        }

        // เปิดใช้งาน autocomplete สำหรับ input รายการทุกตัวที่มีอยู่
        $(document).ready(function() {
            $('input[name="item_name[]"]').each(function() {
                initAutocomplete(this);
            });
        });

        function addItemRow() {
            var container = document.getElementById('item-container');
            var row = document.createElement('div');
            row.className = 'item-row';
            row.innerHTML = `<table class="table">
                    <tr>
                        <td style="width: 25%;">
                            <input type="text" class="form-control" name="item_name[]" placeholder="รายการ" required>
                        </td>
                        <td style="width: 12%;"><input type="number" class="form-control middle-price" name="middle_price[]" placeholder="" readonly></td>
                        <td style="width: 8%;"><input type="text" class="form-control middle-unit" name="middle_unit[]" placeholder="" readonly></td>
                        <td style="width: 12%;"><input type="number" class="form-control quantity" name="quantity[]" placeholder="จำนวน" required></td>
                        <td style="width: 12%;"><input type="text" class="form-control unit" name="unit[]" placeholder="หน่วย" required></td>
                        <td style="width: 15%;"><input type="number" class="form-control price_per_unit" name="price_per_unit[]" placeholder="ราคาต่อหน่วย" required></td>
                        <td style="width: 16%;"><input type="number" class="form-control total" name="total[]" placeholder="รวมเงิน" readonly></td>
                    </tr>
                </table>`;
            container.appendChild(row);

            // Add event listeners to new row
            const quantities = row.querySelectorAll('.quantity');
            const prices = row.querySelectorAll('.price_per_unit');

            quantities.forEach(input => input.addEventListener('input', handleInput));
            prices.forEach(input => input.addEventListener('input', handleInput));

            // เปิดใช้งาน autocomplete สำหรับ input รายการใหม่
            const itemInput = row.querySelector('input[name="item_name[]"]');
            initAutocomplete(itemInput);
        }

        function handleInput(event) {
            calculateTotal(event);
            calculateGrandTotal();
        }

        function calculateTotal(event) {
            const row = event.target.closest('tr');
            const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
            const pricePerUnit = parseFloat(row.querySelector('.price_per_unit').value) || 0;
            const totalField = row.querySelector('.total');

            const total = quantity * pricePerUnit;
            totalField.value = total.toFixed(2); // แสดงผลลัพธ์เป็นทศนิยม 2 ตำแหน่ง
        }

        function calculateGrandTotal() {
            const totals = document.querySelectorAll('.total');
            let grandTotal = 0;

            totals.forEach(total => {
                grandTotal += parseFloat(total.value) || 0;
            });

            document.querySelector('input[name="budget_used"]').value = grandTotal.toFixed(2); // แสดงผลลัพธ์เป็นทศนิยม 2 ตำแหน่ง
        }

        document.addEventListener('input', function(event) {
            if (event.target.classList.contains('quantity') || event.target.classList.contains('price_per_unit')) {
                handleInput(event);
            }
        });
    </script>

</body>

</html>