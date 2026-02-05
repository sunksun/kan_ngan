<?php
// เริ่มต้นเซสชัน
session_start();

// ตรวจสอบว่ามีการตั้งค่าเซสชันไว้หรือไม่
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];
$field = $_SESSION['field'];

// เชื่อมต่อฐานข้อมูล
require_once 'connect_db.php';

// อ่านข้อมูลจากฐานข้อมูล
$csvData = [];
$sql = "SELECT item_no, category, item_name, unit, price FROM middle_price ORDER BY id ASC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $csvData[] = [
            'category' => $row['category'],
            'no' => $row['item_no'],
            'item' => $row['item_name'],
            'unit' => $row['unit'],
            'price' => $row['price']
        ];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>ราคากลาง - กันเงิน กัน</title>
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
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">

    <style>
        .category-header {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #012970;
        }
        .price-column {
            text-align: right;
            font-weight: 500;
        }
        #priceTable th {
            cursor: pointer;
            user-select: none;
        }
        #priceTable th:hover {
            background-color: #e9ecef;
        }
        .sort-icon {
            font-size: 0.8em;
            margin-left: 5px;
        }
    </style>

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
        </div>

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">
                <li class="nav-item dropdown pe-3">
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
                        <span class="d-none d-md-block dropdown-toggle ps-2">[<?php echo $user_id; ?>]<?php echo $_SESSION['username']; ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6><?php echo $name; ?></h6>
                            <span><?php echo $field; ?></span>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>

    </header>

    <?php include 'includes/sidebar.php'; ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>ราคากลางวัสดุสิ้นเปลือง</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">หน้าหลัก</a></li>
                    <li class="breadcrumb-item active">ราคากลาง</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">บัญชีรายการวัสดุสิ้นเปลืองประจำปีงบประมาณ พ.ศ. 2567
                                <small class="text-muted">(<?php echo count($csvData); ?> รายการ)</small>
                            </h5>

                            <!-- Action Buttons -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <input type="text" id="searchInput" class="form-control" placeholder="ค้นหารายการ...">
                                </div>
                                <div class="col-md-4">
                                    <select id="categoryFilter" class="form-select">
                                        <option value="">ทุกหมวดหมู่</option>
                                        <option value="กระดาษ">1. กระดาษ</option>
                                        <option value="กระดานไวท์บอร์ด">2. กระดานไวท์บอร์ด</option>
                                        <option value="กล่องล้อพลาสติก">3. กล่องล้อพลาสติก</option>
                                        <option value="ตู้เอกสาร">4. ตู้เอกสาร</option>
                                        <option value="กาว">5. กาว</option>
                                        <option value="กรรไกร">6. กรรไกร</option>
                                        <option value="คลิปบอร์ด">7. คลิปบอร์ด</option>
                                        <option value="คลิปดำหนีกระดาษ">8. คลิปดำหนีกระดาษ</option>
                                        <option value="เครื่องเย็บกระดาษ">9. เครื่องเย็บกระดาษ</option>
                                        <option value="เครื่องคิดเลข">10. เครื่องคิดเลข</option>
                                        <option value="เครื่องเจาะกระดาษ / เครื่องเหลาดินสอ">11. เครื่องเจาะกระดาษ / เครื่องเหลาดินสอ</option>
                                        <option value="คัดเตอร์">12. คัดเตอร์</option>
                                        <option value="ซอง">13. ซอง</option>
                                        <option value="ดินสอ">14. ดินสอ</option>
                                        <option value="ตรายาง">15. ตรายาง</option>
                                        <option value="ถ่าน">16. ถ่าน</option>
                                        <option value="ถ้วยรางวัล">17. ถ้วยรางวัล</option>
                                        <option value="ถุงมือ">18. ถุงมือ</option>
                                        <option value="เข็มหมุด">19. เข็มหมุด</option>
                                        <option value="แท่นตัดกระดาษ">20. แท่นตัดกระดาษ</option>
                                        <option value="เทปใส / แล็คซีน">21. เทปใส / แล็คซีน</option>
                                        <option value="แท่นประทับตรา">22. แท่นประทับตรา</option>
                                        <option value="ธง / เสาธง /ขาตั้งเสา">23. ธง / เสาธง /ขาตั้งเสา</option>
                                        <option value="ปากกา">24. ปากกา</option>
                                        <option value="ป้าย">25. ป้าย</option>
                                        <option value="แผ่นรอง">26. แผ่นรอง</option>
                                        <option value="แฟ้ม">27. แฟ้ม</option>
                                        <option value="ไม้บรรทัด">28. ไม้บรรทัด</option>
                                        <option value="ยางลบ">29. ยางลบ</option>
                                        <option value="ลวดเย็บกระดาษ">30. ลวดเย็บกระดาษ</option>
                                        <option value="ฉากกั้นหนังสือ">31. ฉากกั้นหนังสือ</option>
                                        <option value="สมุด">32. สมุด</option>
                                        <option value="หมึก">33. หมึก</option>
                                    </select>
                                </div>
                                <div class="col-md-4 text-end">
                                    <a href="file/ราคากลางวัสดุ.csv"
                                       class="btn btn-primary me-2"
                                       download="ราคากลางวัสดุ.csv">
                                        <i class="bi bi-download"></i> ดาวน์โหลด CSV
                                    </a>
                                    <button class="btn btn-success" id="exportExcel">
                                        <i class="bi bi-printer"></i> พิมพ์
                                    </button>
                                </div>
                            </div>

                            <!-- Data Table -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="priceTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="8%" onclick="sortTable(0)">ลำดับ <i class="bi bi-arrow-down-up sort-icon"></i></th>
                                            <th width="50%" onclick="sortTable(1)">รายการวัสดุ <i class="bi bi-arrow-down-up sort-icon"></i></th>
                                            <th width="15%" onclick="sortTable(2)">หน่วยนับ <i class="bi bi-arrow-down-up sort-icon"></i></th>
                                            <th width="15%" onclick="sortTable(3)">ราคากลาง (บาท) <i class="bi bi-arrow-down-up sort-icon"></i></th>
                                            <th width="12%" class="text-center">หมวดหมู่</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                        <?php
                                        $currentCategory = '';
                                        foreach ($csvData as $row) {
                                            // Show category header if changed
                                            if ($row['category'] !== $currentCategory && !empty($row['category'])) {
                                                $currentCategory = $row['category'];
                                                echo "<tr class='category-header'>";
                                                echo "<td colspan='5'><i class='bi bi-tag-fill me-2'></i>{$currentCategory}</td>";
                                                echo "</tr>";
                                            }

                                            echo "<tr data-category='{$row['category']}'>";
                                            echo "<td class='text-center'>{$row['no']}</td>";
                                            echo "<td>{$row['item']}</td>";
                                            echo "<td class='text-center'>{$row['unit']}</td>";
                                            echo "<td class='price-column'>" . number_format((float)str_replace(',', '', $row['price']), 2) . "</td>";
                                            echo "<td class='text-center'><span class='badge bg-secondary'>{$row['category']}</span></td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main>

    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>
    </footer>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            filterTable();
        });

        // Category filter
        document.getElementById('categoryFilter').addEventListener('change', function(e) {
            filterTable();
        });

        function filterTable() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const categoryFilter = document.getElementById('categoryFilter').value;
            const rows = document.querySelectorAll('#tableBody tr:not(.category-header)');
            let visibleCount = 0;

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const category = row.getAttribute('data-category');

                const matchesSearch = text.includes(searchTerm);
                const matchesCategory = !categoryFilter || category === categoryFilter;

                if (matchesSearch && matchesCategory) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Update title with count
            const totalCount = <?php echo count($csvData); ?>;
            const title = document.querySelector('.card-title');
            if (searchTerm || categoryFilter) {
                title.innerHTML = `บัญชีรายการวัสดุสิ้นเปลืองประจำปีงบประมาณ พ.ศ. 2567 <small class="text-muted">(แสดง ${visibleCount} จาก ${totalCount} รายการ)</small>`;
            } else {
                title.innerHTML = `บัญชีรายการวัสดุสิ้นเปลืองประจำปีงบประมาณ พ.ศ. 2567 <small class="text-muted">(${totalCount} รายการ)</small>`;
            }

            // Handle category headers visibility
            updateCategoryHeaders();
        }

        function updateCategoryHeaders() {
            const categoryHeaders = document.querySelectorAll('#tableBody tr.category-header');
            categoryHeaders.forEach(header => {
                let nextRow = header.nextElementSibling;
                let hasVisibleRows = false;

                while (nextRow && !nextRow.classList.contains('category-header')) {
                    if (nextRow.style.display !== 'none') {
                        hasVisibleRows = true;
                        break;
                    }
                    nextRow = nextRow.nextElementSibling;
                }

                header.style.display = hasVisibleRows ? '' : 'none';
            });
        }

        // Sort table
        let sortDirection = {};
        function sortTable(columnIndex) {
            const direction = sortDirection[columnIndex] === 'asc' ? 'desc' : 'asc';
            sortDirection[columnIndex] = direction;

            const tbody = document.getElementById('tableBody');
            const rows = Array.from(tbody.querySelectorAll('tr:not(.category-header)'));

            rows.sort((a, b) => {
                let aVal = a.cells[columnIndex].textContent.trim();
                let bVal = b.cells[columnIndex].textContent.trim();

                // Try to parse as number
                const aNum = parseFloat(aVal.replace(/,/g, ''));
                const bNum = parseFloat(bVal.replace(/,/g, ''));

                if (!isNaN(aNum) && !isNaN(bNum)) {
                    return direction === 'asc' ? aNum - bNum : bNum - aNum;
                }

                // String comparison
                return direction === 'asc'
                    ? aVal.localeCompare(bVal, 'th')
                    : bVal.localeCompare(aVal, 'th');
            });

            // Clear tbody and re-insert sorted rows
            tbody.innerHTML = '';
            let currentCategory = '';

            rows.forEach(row => {
                const category = row.getAttribute('data-category');
                if (category !== currentCategory && category) {
                    currentCategory = category;
                    const categoryRow = document.createElement('tr');
                    categoryRow.className = 'category-header';
                    categoryRow.innerHTML = `<td colspan='5'><i class='bi bi-tag-fill me-2'></i>${category}</td>`;
                    tbody.appendChild(categoryRow);
                }
                tbody.appendChild(row);
            });
        }

        // Print function
        document.getElementById('exportExcel').addEventListener('click', function() {
            window.print();
        });
    </script>

    <style media="print">
        .header, .sidebar, .footer, .back-to-top, .card-title small,
        #searchInput, #categoryFilter, .btn {
            display: none !important;
        }
        .main {
            margin: 0 !important;
            padding: 0 !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    </style>

</body>

</html>
