<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/vendor/autoload.php';

// ข้อมูลการเชื่อมต่อฐานข้อมูล
require_once 'connect_db.php';

// ดึงข้อมูลจากตาราง report_request
$request_query = "SELECT * FROM report_request";
$request_result = $conn->query($request_query);

$requests = [];
if ($request_result->num_rows > 0) {
    while ($row = $request_result->fetch_assoc()) {
        $requests[] = $row;
    }
}

// ดึงข้อมูลจากตาราง items
$items_query = "SELECT * FROM items";
$items_result = $conn->query($items_query);

$items = [];
if ($items_result && $items_result->num_rows > 0) {
    while ($row = $items_result->fetch_assoc()) {
        $items[] = $row;
    }
}

// สร้าง HTML สำหรับสร้าง PDF
$html = '<h1 style="text-align: center;">รายงานขอซื้อ/ขอจ้าง</h1>';

// ตาราง report_request
$html .= '<h2>ตาราง report_request</h2>';
$html .= '<table border="1" cellspacing="0" cellpadding="8">';
$html .= '<tr>';
$html .= '<th>Report ID</th><th>Create At</th><th>Name</th><th>Field</th>';
$html .= '</tr>';

foreach ($requests as $row) {
    $html .= '<tr>';
    $html .= '<td>' . $row['report_id'] . '</td>';
    $html .= '<td>' . $row['create_at'] . '</td>';
    $html .= '<td>' . $row['name'] . '</td>';
    $html .= '<td>' . $row['field'] . '</td>';
    $html .= '</tr>';
}
$html .= '</table>';

// ตาราง items
$html .= '<h2>ตาราง items</h2>';
$html .= '<table border="1" cellspacing="0" cellpadding="8">';
$html .= '<tr>';
$html .= '<th>KAN NO</th><th>Item Name</th><th>Quantity</th><th>Price per Unit</th>';
$html .= '</tr>';

foreach ($items as $row) {
    $html .= '<tr>';
    $html .= '<td>' . $row['kan_no'] . '</td>';
    $html .= '<td>' . $row['item_name'] . '</td>';
    $html .= '<td>' . $row['quantity'] . '</td>';
    $html .= '<td>' . $row['price_per_unit'] . '</td>';
    $html .= '</tr>';
}
$html .= '</table>';

// สร้าง PDF ด้วย mPDF
use Mpdf\Mpdf;

$mpdfConfig = [
    'tempDir' => __DIR__ . '/tmp', // โฟลเดอร์ชั่วคราวสำหรับ mPDF
    'fontDir' => realpath(__DIR__ . '/fonts'), // โฟลเดอร์ที่เก็บฟอนต์
    'default_font' => 'THSarabunNew', // ฟอนต์ที่ใช้เป็นค่าเริ่มต้น
    'fontdata' => [
        "dejavusanscondensed" => [
            'R' => "DejaVuSansCondensed.ttf",
        ]
    ]
];


$mpdf = new Mpdf($mpdfConfig);
$mpdf->WriteHTML($html);
$mpdf->Output('report.pdf', 'I');

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
