<?php
// Script สำหรับ import ข้อมูลราคากลางจาก CSV เข้าฐานข้อมูล

// เชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kan_ngan";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// ลบข้อมูลเก่าออกก่อน (ถ้ามี)
$conn->query("TRUNCATE TABLE middle_price");

// อ่านข้อมูลจากไฟล์ CSV
$csvFile = 'file/ราคากลางวัสดุ.csv';

if (!file_exists($csvFile)) {
    die("ไม่พบไฟล์ CSV: $csvFile");
}

$file = fopen($csvFile, 'r');

// ข้าม 3 บรรทัดแรก (header)
for ($i = 0; $i < 3; $i++) {
    fgets($file);
}

// อ่าน header row
fgetcsv($file);

// เตรียม prepared statement
$stmt = $conn->prepare("INSERT INTO middle_price (item_no, category, item_name, unit, price, fiscal_year) VALUES (?, ?, ?, ?, ?, 2567)");

$currentCategory = '';
$importCount = 0;
$errorCount = 0;

// อ่านข้อมูลแต่ละแถว
while (($row = fgetcsv($file)) !== false) {
    // ข้ามแถวว่าง
    if (empty(array_filter($row))) continue;

    // ตรวจสอบว่าเป็นแถวหมวดหมู่หรือไม่
    // (มีข้อมูลในคอลัมน์แรก แต่คอลัมน์อื่นว่าง และไม่ใช่ตัวเลข)
    if (!empty(trim($row[0])) && empty(trim($row[2])) && !is_numeric(trim($row[0]))) {
        $currentCategory = trim($row[0]);
        echo "พบหมวดหมู่: $currentCategory<br>\n";
        continue;
    }

    // แถวข้อมูลปกติ (เริ่มด้วยตัวเลข)
    if (!empty(trim($row[0])) && is_numeric(trim($row[0]))) {
        $itemNo = trim($row[0]);
        $itemName = trim($row[1]);
        $unit = trim($row[2]);
        $priceStr = trim($row[3]);

        // แปลงราคาเป็นตัวเลข (ลบ comma ออก)
        $price = (float)str_replace(',', '', $priceStr);

        // บันทึกลงฐานข้อมูล
        $stmt->bind_param("ssssd", $itemNo, $currentCategory, $itemName, $unit, $price);

        if ($stmt->execute()) {
            $importCount++;
            echo "✓ นำเข้า: [$itemNo] $itemName - $price บาท (หมวด: $currentCategory)<br>\n";
        } else {
            $errorCount++;
            echo "✗ Error: " . $stmt->error . "<br>\n";
        }
    }
}

fclose($file);
$stmt->close();
$conn->close();

echo "<hr>";
echo "<h3>สรุปผลการนำเข้าข้อมูล</h3>";
echo "<p><strong>นำเข้าสำเร็จ:</strong> $importCount รายการ</p>";
echo "<p><strong>ข้อผิดพลาด:</strong> $errorCount รายการ</p>";

if ($importCount > 0) {
    echo "<p><a href='middle_price.php' class='btn btn-success'>ไปดูข้อมูลราคากลาง</a></p>";
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import ข้อมูลราคากลาง</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
        .btn {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Content will be inserted by PHP above -->
    </div>
</body>
</html>
