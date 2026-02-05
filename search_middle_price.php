<?php
header('Content-Type: application/json; charset=utf-8');

// เชื่อมต่อฐานข้อมูล
require_once 'connect_db.php';

$searchTerm = isset($_GET['term']) ? $_GET['term'] : '';

if (empty($searchTerm)) {
    echo json_encode([]);
    exit;
}

// ค้นหาสินค้าที่ตรงกับคำค้นหา
$sql = "SELECT item_name, unit, price FROM middle_price WHERE item_name LIKE ? LIMIT 10";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['error' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$searchPattern = "%{$searchTerm}%";
$stmt->bind_param("s", $searchPattern);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = [
        'label' => $row['item_name'],
        'value' => $row['item_name'],
        'unit' => $row['unit'],
        'price' => $row['price']
    ];
}

// ส่งออกเป็น JSON
echo json_encode($items, JSON_UNESCAPED_UNICODE);

$stmt->close();
$conn->close();
