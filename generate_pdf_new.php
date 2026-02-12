<?php
// เริ่มต้นเซสชัน
session_start();

// ตรวจสอบว่ามีการตั้งค่าเซสชันไว้หรือไม่
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// ตรวจสอบว่ามี ID ที่ส่งมาหรือไม่
if (!isset($_GET['id'])) {
    die("ไม่พบรหัสรายการ");
}

$report_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// เชื่อมต่อฐานข้อมูล
require_once 'connect_db.php';

// ดึงข้อมูลจากตาราง report_request
$sql = "SELECT * FROM report_request WHERE report_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $report_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("ไม่พบข้อมูลรายการ");
}

$report = $result->fetch_assoc();
$stmt->close();

// ดึงข้อมูลผู้ใช้จากตาราง members
$sql_member = "SELECT * FROM members WHERE user_id = ?";
$stmt_member = $conn->prepare($sql_member);
$stmt_member->bind_param("i", $user_id);
$stmt_member->execute();
$result_member = $stmt_member->get_result();
$member = $result_member->fetch_assoc();
$stmt_member->close();

// ดึงข้อมูลรายการพัสดุจากตาราง items
$sql_items = "SELECT * FROM items WHERE kan_no = ?";
$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param("s", $report['kan_no']);
$stmt_items->execute();
$result_items = $stmt_items->get_result();

$items = [];
$total_amount = 0;
while ($item = $result_items->fetch_assoc()) {
    $items[] = $item;
    $total_amount += $item['total'];
}
$stmt_items->close();
$conn->close();

// โหลด mPDF
require_once __DIR__ . '/vendor/autoload.php';

// กำหนดค่าไดเรกทอรีฟอนต์
$defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

// สร้าง mPDF instance พร้อมตั้งค่าหน้ากระดาษและขอบตามที่กำหนด
// A4, ขอบบน 0.7 ซม., ล่าง 0.7 ซม., ซ้าย 1.5 ซม., ขวา 1 ซม.
// ใช้ฟอนต์ THSarabunNew ที่รองรับภาษาไทยพร้อมวรรณยุกต์ได้ดี
$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4',
    'margin_top' => 7,      // 0.7 ซม. = 7 มม.
    'margin_bottom' => 7,   // 0.7 ซม. = 7 มม.
    'margin_left' => 15,      // 1.5 ซม. = 15 มม.
    'margin_right' => 10,     // 1 ซม. = 10 มม.
    'tempDir' => __DIR__ . '/tmp',  // ใช้ temp directory ในโปรเจค
    'fontDir' => array_merge($fontDirs, [
        __DIR__ . '/fonts/THSarabunNew',
    ]),
    'fontdata' => $fontData + [
        'thsarabunnew' => [
            'R' => 'THSarabunNew.ttf',
            'B' => 'THSarabunNew Bold.ttf',
            'I' => 'THSarabunNew Italic.ttf',
            'BI' => 'THSarabunNew BoldItalic.ttf',
        ]
    ],
    'default_font' => 'thsarabunnew',
    'default_font_size' => 16, // THSarabunNew ขนาด 16pt เทียบเท่า 14pt ของฟอนต์ปกติ
    'autoScriptToLang' => true,
    'autoLangToFont' => true,
]);

// กำหนด CSS สำหรับ PDF พร้อมระยะห่างระหว่างบรรทัด = 1
$stylesheet = '
<style>
    body {
        font-family: "thsarabunnew", sans-serif;
        font-size: 16pt;
        line-height: 1;  /* ระยะห่างระหว่างบรรทัด = 1 */
    }

    h1 {
        font-size: 18pt;
        font-weight: bold;
        text-align: center;
        margin: 10px 0;
        line-height: 1;
    }

    h2 {
        font-size: 16pt;
        font-weight: bold;
        text-align: center;
        margin: 8px 0;
        line-height: 1;
    }

    h3 {
        font-size: 15pt;
        font-weight: bold;
        margin: 8px 0;
        line-height: 1;
    }

    h4 {
        font-size: 14pt;
        font-weight: bold;
        margin: 5px 0;
        line-height: 1;
    }

    p {
        margin: 5px 0;
        line-height: 1;
        font-size: 16pt;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .indent {
        padding-left: 30px;
    }

    .indent-20 {
        padding-left: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 15px 0;
        line-height: 2;
        font-size: 16pt;
    }

    table, th, td {
        border: 1px solid #000;
    }

    th {
        background-color: #f0f0f0;
        font-weight: bold;
        padding: 5px;
        text-align: center;
        line-height: 1;
        font-size: 16pt;
    }

    td {
        padding: 5px;
        line-height: 2;
        font-size: 16pt;
    }

    .no-border {
        border: none;
    }

    .signature-section {
        margin-top: 20px;
    }

    .dotted-line {
        border-bottom: 1px dotted #000;
        display: inline-block;
        min-width: 150px;
    }

    hr {
        border: none;
        border-top: 1px solid #000;
        margin: 15px 0;
    }
</style>
';

// ฟังก์ชันแปลงตัวเลขเป็นข้อความไทย
function convertNumberToThaiText($number)
{
    $txtnum1 = array('', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า');
    $txtnum2 = array('', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน');

    $number = number_format($number, 2, '.', '');
    $bahtSplit = explode('.', $number);
    $baht = $bahtSplit[0];
    $satang = isset($bahtSplit[1]) ? $bahtSplit[1] : '00';

    $bahtText = '';
    $bahtLength = strlen($baht);

    for ($i = 0; $i < $bahtLength; $i++) {
        $tmp = substr($baht, $i, 1);
        if ($tmp != 0) {
            if (($i == ($bahtLength - 1)) && ($tmp == 1)) {
                $bahtText .= 'เอ็ด';
            } elseif (($i == ($bahtLength - 2)) && ($tmp == 2)) {
                $bahtText .= 'ยี่';
            } elseif (($i == ($bahtLength - 2)) && ($tmp == 1)) {
                $bahtText .= '';
            } else {
                $bahtText .= $txtnum1[$tmp];
            }
            $bahtText .= $txtnum2[$bahtLength - $i - 1];
        }
    }

    $bahtText = $bahtText ? $bahtText . 'บาท' : 'ศูนย์บาท';

    if ($satang != '00') {
        $satangText = '';
        $satangLength = strlen($satang);
        for ($i = 0; $i < $satangLength; $i++) {
            $tmp = substr($satang, $i, 1);
            if ($tmp != 0) {
                if (($i == ($satangLength - 1)) && ($tmp == 1) && ($satangLength > 1)) {
                    $satangText .= 'เอ็ด';
                } elseif (($i == ($satangLength - 2)) && ($tmp == 2)) {
                    $satangText .= 'ยี่';
                } elseif (($i == ($satangLength - 2)) && ($tmp == 1)) {
                    $satangText .= '';
                } else {
                    $satangText .= $txtnum1[$tmp];
                }
                $satangText .= $txtnum2[$satangLength - $i - 1];
            }
        }
        $bahtText .= $satangText . 'สตางค์';
    } else {
        $bahtText .= 'ถ้วน';
    }

    return $bahtText;
}

// ฟังก์ชันตัดข้อความให้เป็นคำสมบูรณ์
function truncateText($text, $maxLength = 50)
{
    if (mb_strlen($text) <= $maxLength) {
        return $text;
    }

    // ตัดที่ความยาวที่กำหนด
    $truncated = mb_substr($text, 0, $maxLength);

    // หาตำแหน่งช่องว่างสุดท้าย
    $lastSpace = mb_strrpos($truncated, ' ');

    if ($lastSpace !== false && $lastSpace > $maxLength * 0.7) {
        // ถ้าเจอช่องว่างและอยู่ในช่วงที่ยอมรับได้ (มากกว่า 70% ของความยาว)
        $truncated = mb_substr($truncated, 0, $lastSpace);
    }

    return $truncated . 'ฯ';
}

// แปลงตัวเลข budget_used เป็นข้อความไทย
$budgetText = convertNumberToThaiText($report['budget_used']);

// สร้างเนื้อหา HTML สำหรับ PDF
// ตรวจสอบว่าควรแสดงข้อความโครงการยุทธศาสตร์หรือไม่
$show_strategic_project = ($report['project_type'] == 'โครงการยุทธศาสตร์ฯ');

$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    ' . $stylesheet . '
</head>
<body>';

if ($show_strategic_project) {
    $html .= '
    <p style="font-size: 8pt; font-weight: normal; margin-bottom: 0; line-height: 1.2; text-align: right;">โครงการยุทธศาสตร์มหาวิทยาลัยราชภัฏเพื่อการพัฒนาท้องถิ่น พ.ศ.2569</p>
    ';
}

// กำหนดข้อความ "เรียน" ตามประเภทโครงการ
$recipient = ($report['project_type'] == 'โครงการยุทธศาสตร์ฯ')
    ? 'เรียน อธิการบดีมหาวิทยาลัยราชภัฏเลย'
    : 'เรียน คณบดี (ผู้รับมอบอำนาจปฏิบัติราชการแทนอธิการบดี)';

// กำหนดข้อความหัวหน้าสำนักงานตามประเภทโครงการ
$office_head_signature = ($report['project_type'] == 'โครงการยุทธศาสตร์ฯ')
    ? '....................................................หัวหน้าสำนักงานคณบดี/หัวหน้าสำนักงานสถาบัน/สำนัก......../......../........'
    : '....................................................หัวหน้าสำนักงานคณบดี/ภาควิชา......../......../........';

$html .= '
    <p class="text-center" style="font-size: 10pt; font-weight: bold; margin-bottom: 0; line-height: 1.2;">รายงานขอซื้อหรือขอจ้าง</p>
    <table style="width: 100%; border: none; margin: 6px 0 0 0; border-collapse: collapse;">
        <tr>
            <td colspan="2" style="border: none; padding: 2px; font-size: 10pt; font-weight: normal; text-align: right; line-height: 1.2;">
                วันที่...........เดือน...........................พ.ศ................
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border: none; padding: 2px; font-size: 10pt; font-weight: normal; text-align: left; line-height: 1.2;">
                ' . $recipient . '
            </td>
        </tr>
    </table>

    <table style="width: 100%; border: none; margin: 4px 0; border-collapse: collapse;">
        <tr>
            <td style="border: none; padding: 2px; width: 20%; font-size: 10pt; font-weight: normal; text-align: right; vertical-align: middle; line-height: 1.2;">
                ด้วยข้าพเจ้า
            </td>
            <td style="border: none; padding: 2px; width: 80%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2; border-bottom: 1px dotted #000;">
                ' . htmlspecialchars($report['name']) . '
            </td>
        </tr>
    </table>

    <table style="width: 100%; border: none; margin: 4px 0; border-collapse: collapse;">
        <tr>
            <td style="border: none; padding: 2px; width: 18%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                สาขาวิชา/หน่วยงาน
            </td>
            <td style="border: none; padding: 2px; width: 82%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2; border-bottom: 1px dotted #000;">
                ' . htmlspecialchars($report['field']) . '
            </td>
        </tr>
    </table>

    <table style="width: 100%; border: none; margin: 4px 0; border-collapse: collapse;">
        <tr>
            <td style="border: none; padding: 2px; width: 32%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                มีความประสงค์ ( ' . ($report['purpose'] == 'ขอซื้อ' ? '/' : ' ') . ' ) ขอซื้อ ( ' . ($report['purpose'] == 'ขอจ้าง' ? '/' : ' ') . ' ) ขอจ้าง
            </td>
            <td style="border: none; padding: 2px; width: 68%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2; border-bottom: 1px dotted #000;">
                ' . htmlspecialchars($report['purpose_']) . '
            </td>
        </tr>
    </table>

    <table style="width: 100%; border: none; margin: 4px 0; border-collapse: collapse;">
        <tr>
            <td style="border: none; padding: 2px; width: 15%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                เพื่อใช้ในโครงการ
            </td>
            <td style="border: none; padding: 2px; width: 85%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2; border-bottom: 1px dotted #000;">
                ' . htmlspecialchars(truncateText($report['project_name'], 100)) . '
            </td>
        </tr>
    </table>

    <table style="width: 100%; border: none; margin: 4px 0; border-collapse: collapse;">
        <tr>
            <td style="border: none; padding: 2px; width: 35%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                โดยพัสดุ/งานจ้างครั้งนี้ใช้ในงาน/กิจกรรม
            </td>
            <td style="border: none; padding: 2px; width: 65%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2; border-bottom: 1px dotted #000;">
                ' . htmlspecialchars($report['activity']) . '
            </td>
        </tr>
    </table>

    <table style="width: 100%; border: none; margin: 4px 0; border-collapse: collapse;">
        <tr>
            <td style="border: none; padding: 2px; width: 15%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                โดยใช้งบประมาณ
            </td>
            <td style="border: none; padding: 2px; width: 35%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2; border-bottom: 1px dotted #000;">
                ' . htmlspecialchars($report['budget']) . '
            </td>
            <td style="border: none; padding: 2px; width: 16%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                หมายเลขโครงการ
            </td>
            <td style="border: none; padding: 2px; width: 34%; font-size: 12pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2; border-bottom: 1px dotted #000;">
                ' . htmlspecialchars($report['project_number']) . '
            </td>
        </tr>
    </table>

    <table style="width: 100%; border: none; margin: 4px 0; border-collapse: collapse;">
        <tr>
            <td style="border: none; padding: 2px; width: 18%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                วงเงินที่จะจัดซื้อ/จ้าง
            </td>
            <td style="border: none; padding: 2px; width: 22%; font-size: 12pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2; border-bottom: 1px dotted #000;">
                ' . number_format((float)$report['budget_used']) . '
            </td>
            <td style="border: none; padding: 2px; width: 30%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                กำหนดเวลาที่ต้องการใช้พัสดุ(วัน)
            </td>
            <td style="border: none; padding: 2px; width: 30%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2; border-bottom: 1px dotted #000;">

            </td>
        </tr>
    </table>

    <table style="width: 100%; border: none; margin: 4px 0; border-collapse: collapse;">
        <tr>
            <td style="border: none; padding: 2px; width: 72%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                ในกรณีที่เป็นครุภัณฑ์การซื้อครั้งนี้มีราคา  (&nbsp;&nbsp;&nbsp;) ต่ำกว่า/เท่ากับราคามาตรฐาน  (&nbsp;&nbsp;&nbsp;) สูงกว่าเพราะ
            </td>
            <td style="border: none; padding: 2px; width: 28%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2; border-bottom: 1px dotted #000;">
            </td>
        </tr>
    </table>

    <table style="width: 100%; border: none; margin: 4px 0; border-collapse: collapse;">
        <tr>
            <td style="border: none; padding: 2px; width: 100%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                ตามพันธกิจ  (&nbsp;&nbsp;&nbsp;)  การจัดการศึกษา   (&nbsp;&nbsp;&nbsp;)  การวิจัย     (&nbsp;&nbsp;&nbsp;)  การบริการวิชาการ    (&nbsp;&nbsp;&nbsp;)  การทำนุบำรุงศิลปวัฒนธรรม
        </tr>
    </table>

    <table style="width: 100%; border: none; margin: 4px 0; border-collapse: collapse;">
        <tr>
            <td style="border: none; padding: 2px; width: 12%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                ยุทธศาสตร์
            </td>
            <td style="border: none; padding: 2px; width: 88%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2; border-bottom: 1px dotted #000;">
            </td>
        </tr>
    </table>

    <table style="width: 100%; border: none; margin: 5px 0; border-collapse: collapse;">
        <tr>
            <td style="border: none; padding: 2px; width: 100%; font-size: 10pt; font-weight: normal; text-align: right; vertical-align: middle; line-height: 1.2;">
                ....................................................ผู้ขออนุญาต......../......../........
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 100%; font-size: 10pt; font-weight: normal; text-align: right; vertical-align: middle; line-height: 1.2;">
                ....................................................หัวหน้าภาควิชา/ประธานสาขาวิชา/หัวหน้างาน......../......../........
            </td>
        </tr>
    </table>

    <hr style="border: none; border-top: 1px solid #000; margin: 5px 0;">

    <table style="width: 100%; border: none; margin: 4px 0; border-collapse: collapse;">
        <tr>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                การตรวจสอบความสอดคล้องกับวัตถุประสงค์ของโครงการ
            </td>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
            (&nbsp;&nbsp;&nbsp;)  สอดคล้องกับวัตถุประสงค์ของโครงการ   (..........................)
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
            </td>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
            (&nbsp;&nbsp;&nbsp;)  ไม่สอดคล้องไม่ตรงกับวัตถุประสงค์ของโครงการ
            </td>
        </tr>
    </table>

    <table style="width: 100%; border: none; margin: 4px; border-collapse: collapse;">
        <tr>
            <td style="border: none; padding: 2px; width: 10%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                เห็นควร
            </td>
            <td style="border: none; padding: 2px; width: 90%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                (&nbsp;&nbsp;&nbsp;)  อนุมัติ
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 10%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
            </td>
            <td style="border: none; padding: 2px; width: 90%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                (&nbsp;&nbsp;&nbsp;)  ไม่อนุมัติ .............................................................................................
            </td>
        </tr>
    </table>

    <table style="width: 100%; border: none; margin: 4px 0; border-collapse: collapse;">
        <tr>
            <td style="border: none; padding: 2px; width: 100%; font-size: 10pt; font-weight: normal; text-align: right; vertical-align: middle; line-height: 1.2;">
                ' . $office_head_signature . '
            </td>
        </tr>
    </table>

    <hr style="border: none; border-top: 1px solid #000; margin: 5px 0;">

    <table style="width: 100%; border: none; margin: 4px 0; border-collapse: collapse;">
        <tr>
            <td style="border: none; padding: 2px; width: 100%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                เห็นควรอนุมัติให้ดำเนินการตามเสนอ  หมวดเงิน (&nbsp;&nbsp;&nbsp;)  ใช้สอย   (&nbsp;&nbsp;&nbsp;) วัสดุ    (&nbsp;&nbsp;&nbsp;) ครุภัณฑ์    (&nbsp;&nbsp;&nbsp;) อื่นๆ ที่ดินและสิ่งก่อสร้าง
            </td>
        <tr>
    </table>

    <table style="width: 100%; border: none; margin: 4px 0; border-collapse: collapse;">
        <tr>
            <td style="border: none; padding: 2px; width: 15%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                โดยมีเงินคงเหลือ
            </td>
            <td style="border: none; padding: 2px; width: 18%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2; border-bottom: 1px dotted #000;">

            </td>
            <td style="border: none; padding: 2px; width: 15%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                บาท  &nbsp;&nbsp;&nbsp;   จ่ายครั้งนี้
            </td>
            <td style="border: none; padding: 2px; width: 12%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2; border-bottom: 1px dotted #000;">

            </td>
            <td style="border: none; padding: 2px; width: 22%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                บาท  &nbsp;&nbsp;&nbsp;    ทำให้มีเงินคงเหลือ
            </td>
            <td style="border: none; padding: 2px; width: 12%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2; border-bottom: 1px dotted #000;">

            </td>
            <td style="border: none; padding: 2px; width: 6%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                บาท
            </td>
        </tr>
    </table>
';

// แสดงตารางที่แตกต่างกันตามประเภทโครงการ
if ($report['project_type'] == 'โครงการยุทธศาสตร์ฯ') {
    // ตารางสำหรับโครงการยุทธศาสตร์ฯ
    $html .= '
    <table style="width: 100%; border: none; margin: 4px 0; border-collapse: collapse;">
        <tr>
            <td style="border: none; padding: 2px; width: 100%; font-size: 10pt; font-weight: normal; text-align: right; vertical-align: middle; line-height: 1.2;">
                ....................................................เจ้าหน้าที่การเงินหน่วยงาน......../......../........
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 100%; font-size: 10pt; font-weight: normal; text-align: right; vertical-align: middle; line-height: 1.2;">
                ....................................................หัวหน้าการเงินหน่วยงาน......../......../........
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 100%; font-size: 10pt; font-weight: normal; text-align: right; vertical-align: middle; line-height: 1.2;">
                ....................................................คณบดี/ผู้อำนวยการสถาบัน/สำนัก/ศูนย์......../......../........
            </td>
        </tr>
    </table>';
} else {
    // ตารางสำหรับโครงการสาขาวิชา/คณะ
    $html .= '
    <table style="width: 100%; border: none; margin: 4px 0; border-collapse: collapse;">
        <tr>
            <td style="border: none; padding: 2px; width: 100%; font-size: 10pt; font-weight: normal; text-align: right; vertical-align: middle; line-height: 1.2;">
                ....................................................เจ้าหน้าที่การเงินคณะ......../......../........
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 100%; font-size: 10pt; font-weight: normal; text-align: right; vertical-align: middle; line-height: 1.2;">
                ....................................................หัวหน้าการเงินคณะ......../......../........
            </td>
        </tr>
    </table>';
}

// ตรวจสอบประเภทโครงการเพื่อแสดงส่วนที่แตกต่างกัน
if ($report['project_type'] == 'โครงการยุทธศาสตร์ฯ') {
    // แสดงส่วนของโครงการยุทธศาสตร์ฯ
    $html .= '
    <hr style="border: none; border-top: 1px solid #000; margin: 5px 0;">

    <!-- ส่วนของโครงการยุทธศาสตร์มหาวิทยาลัยราชภัฏเพื่อการพัฒนาท้องถิ่น พ.ศ.2568 -->
    <table style="width: 100%; border: none; margin: 4px 0; border-collapse: collapse;">
        <tr>
            <td style="border: none; padding: 2px; width: 100%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                เห็นควรอนุมัติให้ดำเนินการตามเสนอ
            </td>
        <tr>
    </table>

    <table style="width: 100%; border: none; margin: 4px 0; border-collapse: collapse;">
        <tr>
            <td style="border: none; padding: 2px; width: 15%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                โดยมีเงินคงเหลือ
            </td>
            <td style="border: none; padding: 2px; width: 18%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2; border-bottom: 1px dotted #000;">

            </td>
            <td style="border: none; padding: 2px; width: 15%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                บาท  &nbsp;&nbsp;&nbsp;   จ่ายครั้งนี้
            </td>
            <td style="border: none; padding: 2px; width: 12%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2; border-bottom: 1px dotted #000;">

            </td>
            <td style="border: none; padding: 2px; width: 22%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                บาท  &nbsp;&nbsp;&nbsp;    ทำให้มีเงินคงเหลือ
            </td>
            <td style="border: none; padding: 2px; width: 12%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2; border-bottom: 1px dotted #000;">

            </td>
            <td style="border: none; padding: 2px; width: 6%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                บาท
            </td>
        </tr>
    </table>

    <table style="width: 100%; border: none; margin: 4px 0; border-collapse: collapse;">
        <tr>
            <td style="border: none; padding: 2px; width: 100%; font-size: 10pt; font-weight: normal; text-align: right; vertical-align: middle; line-height: 1.2;">
                ....................................................เจ้าหน้าที่การเงิน......../......../........
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 100%; font-size: 10pt; font-weight: normal; text-align: right; vertical-align: middle; line-height: 1.2;">
                ....................................................หัวหน้าการเงิน......../......../........
            </td>
        </tr>
    </table>

    <hr style="border: none; border-top: 1px solid #000; margin: 5px 0;">
    ';
} else {
    // แสดงส่วนของโครงการสาขาวิชา/คณะ (ไม่มีส่วนเพิ่มเติม)
    $html .= '
    <hr style="border: none; border-top: 1px solid #000; margin: 5px 0;">
    ';
}

$html .= '
    <table style="width: 100%; border: none; margin: 4px 0; border-collapse: collapse;">
        <tr>
            <td style="border: none; padding: 2px; width: 100%; font-size: 10pt; font-weight: normal; text-align: justify; vertical-align: middle; line-height: 1.2;">
                ได้พิจารณาแล้วสมควรจัดหา  (&nbsp;&nbsp;&nbsp;)  ใช้สอย  (&nbsp;&nbsp;&nbsp;) วัสดุ   (&nbsp;&nbsp;&nbsp;)  ค่าครุภัณฑ์   (&nbsp;&nbsp;&nbsp;)  อื่นๆ (......................................................................)
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 100%; font-size: 10pt; font-weight: normal; text-align: justify; vertical-align: middle; line-height: 1.2;">
                โดยให้ดำเนินการตามพระราชบัญญัติการจัดซื้อจัดจ้างและการบริหารพัสดุภาครัฐ พ.ศ. 2560 มาตรา 56 (2) (ข) และ
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 100%; font-size: 10pt; font-weight: normal; text-align: justify; vertical-align: middle; line-height: 1.2;">
                ระเบียบกระทรวงการคลังว่าด้วยการจัดซื้อจัดจ้างและการบริหารพัสดุภาครัฐ พ.ศ.2560 ซึ่งจะจัดหาโดยวิธี เฉพาะเจาะจง
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 100%; font-size: 10pt; font-weight: normal; text-align: justify; vertical-align: middle; line-height: 1.2;">
                และหลักเกณฑ์ในการพิจารณาคือ เกณฑ์ราคา และควรเสนอให้
            </td>
        </tr>
    </table>
    ';

// ตรวจสอบประเภทโครงการเพื่อแสดงตารางกรรมการโครงการคณะ
if ($report['project_type'] != 'โครงการยุทธศาสตร์ฯ') {
    $html .= '
    <!-- ตารางกรรมการกำหนดคุณลักษณะของพัสดุ และ กรรมการตรวจรับพัสดุ โครงการคณะ -->
    <table style="width: 100%; border: none; margin: 5px 0; border-collapse: collapse;">
        <tr>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;บุคคลต่อไปนี้เป็นผู้กำหนดคุณลักษณะของพัสดุ/
            </td>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;บุคคลต่อไปนี้เป็นกรรมการตรวจรับพัสดุ
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                คณะกรรมการกำหนดคุณลักษณะของพัสดุ
            </td>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">

            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1. ................................................
            </td>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                1. ................................................ ประธานกรรมการ
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2. ................................................
            </td>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                2. ................................................ กรรมการ
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 3. ................................................
            </td>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                3. ................................................ กรรมการ
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                พร้อมนี้ได้แนบหลักฐาน คือ
            </td>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1. บันทึกรายงานของเจ้าหน้าที่ ............... ชุด
            </td>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2. ........................................
            </td>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: center; vertical-align: middle; line-height: 1.2;">
                จึงเรียนมาเพื่อโปรดพิจารณา
            </td>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
            </td>
        </tr>
    </table>
    ';
}

// ตรวจสอบประเภทโครงการเพื่อแสดงตารางกรรมการโครงการยุทธศาสตร์
if ($report['project_type'] == 'โครงการยุทธศาสตร์ฯ') {
    $html .= '
    <!-- ตารางกรรมการกำหนดคุณลักษณะของพัสดุ และ กรรมการตรวจรับพัสดุ โครงการยุทธศาสตร์ -->
    <table style="width: 100%; border: none; margin: 5px 0; border-collapse: collapse;">
        <tr>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;บุคคลต่อไปนี้เป็นผู้กำหนดคุณลักษณะของพัสดุ/
            </td>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;บุคคลต่อไปนี้เป็นกรรมการตรวจรับพัสดุ
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                คณะกรรมการกำหนดคุณลักษณะของพัสดุ
            </td>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                1. ................................................ ประธานกรรมการ
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1. ................................................
            </td>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                2. ................................................ กรรมการ
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2. ................................................
            </td>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                3. ................................................ กรรมการ
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                พร้อมนี้ได้แนบหลักฐาน คือ
            </td>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1. บันทึกรายงานของเจ้าหน้าที่ ............... ชุด
            </td>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2. ........................................
            </td>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: center; vertical-align: middle; line-height: 1.2;">
                จึงเรียนมาเพื่อโปรดพิจารณา
            </td>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
            </td>
        </tr>
    </table>
    ';
}

// ตรวจสอบประเภทโครงการเพื่อแสดงตารางลายเซ็นที่ต่างกัน
if ($report['project_type'] == 'โครงการยุทธศาสตร์ฯ') {
    // ตารางสำหรับโครงการยุทธศาสตร์ฯ
    $html .= '
    <table style="width: 100%; border: none; margin: 2px 0; border-collapse: collapse;">
        <tr>
            <td style="border: none; padding: 2px; width: 100%; font-size: 10pt; font-weight: normal; text-align: right; vertical-align: middle; line-height: 1.2;">
                ....................................................เจ้าหน้าที่.........../................../............
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 100%; font-size: 10pt; font-weight: normal; text-align: right; vertical-align: middle; line-height: 1.2;">
                ....................................................หัวหน้าเจ้าหน้าที่.........../................../............
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 100%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                เห็นชอบและอนุมัติวงเงินตามเสนอ .........................................อธิการบดีมหาวิทยาลัยราชภัฏเลย.........../................../............
            </td>
        </tr>
    </table>
    ';
} else {
    // ตารางสำหรับโครงการสาขาวิชา/คณะ
    $html .= '
    <table style="width: 100%; border: none; margin: 4px 0; border-collapse: collapse;">
        <tr>
            <td style="border: none; padding: 2px; width: 100%; font-size: 10pt; font-weight: normal; text-align: right; vertical-align: middle; line-height: 1.2;">
                ....................................................เจ้าหน้าที่คณะ.........../................../............
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 100%; font-size: 10pt; font-weight: normal; text-align: right; vertical-align: middle; line-height: 1.2;">
                ....................................................หัวหน้าเจ้าหน้าที่คณะ.........../................../............
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 100%; font-size: 10pt; font-weight: normal; text-align: right; vertical-align: middle; line-height: 1.2;">
                ....................................................รองคณบดี.........../................../............
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 100%; font-size: 10pt; font-weight: normal; text-align: left; vertical-align: middle; line-height: 1.2;">
                เห็นชอบและอนุมัติวงเงินตามเสนอ ....................................................คณบดี (ผู้รับมอบอำนาจปฏิบัติราชการแทนอธิการบดี)
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 100%; font-size: 10pt; font-weight: normal; text-align: center; vertical-align: middle; line-height: 1.2;">
                ....................../............................./.......................
            </td>
        </tr>
    </table>
    ';
}

// ตรวจสอบ purpose เพื่อแสดงเครื่องหมาย /
$buy_mark = ($report['purpose'] == 'ขอซื้อ') ? '/' : '';
$hire_mark = ($report['purpose'] == 'ขอจ้าง') ? '/' : '';

$html .= '
    <div style="page-break-after: always;"></div>

    <!-- หน้าที่ 2 -->
    <p style="font-size: 10pt; font-weight: normal; margin: 10px 0 0 0; line-height: 1.5; text-align: center;">รายละเอียดของวัสดุ/ครุภัณฑ์ ที่จะขอ  ( ' . $buy_mark . ' ) ซื้อ  ( ' . $hire_mark . ' ) จ้าง</p>
    <p style="font-size: 10pt; font-weight: normal; margin: 0; line-height: 1.5; text-align: center;">ตามพระราชบัญญัติการจัดซื้อจัดจ้างและการบริหารพัสดุภาครัฐ พ.ศ. 2560</p>
    <p style="font-size: 10pt; font-weight: normal; margin: 0 0 10px 0; line-height: 1.5; text-align: center;">และระเบียนกระทรวงการคลังว่าด้วยการจัดซื้อจัดจ้างและการบริหารพัสดุภาครัฐ พ.ศ. 2560</p>

    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
        <tr>
            <td rowspan="2" style="border: 1px solid black; padding: 2px; width: 5%; font-size: 8.5pt; font-weight: normal; text-align: center; vertical-align: middle; line-height: 1.2;">ลำดับที่</td>
            <td rowspan="2" style="border: 1px solid black; padding: 2px; width: 30%; font-size: 10pt; font-weight: normal; text-align: center; vertical-align: middle; line-height: 1.2;">รายการ ชื่อ ลักษณะ ขนาด ยี่ห้อ (ขอบเขตของงานหรือรายละเอียดคุณลักษณะเฉพาะของพัสดุ)</td>
            <td colspan="2" style="border: 1px solid black; padding: 2px; width: 15%; font-size: 10pt; font-weight: normal; text-align: center; vertical-align: middle; line-height: 1.2;">ราคากลางของพัสดุ</td>
            <td colspan="4" style="border: 1px solid black; padding: 2px; width: 30%; font-size: 10pt; font-weight: normal; text-align: center; vertical-align: middle; line-height: 1.2;">ขอดำเนินการครั้งนี้</td>
            <td rowspan="2" style="border: 1px solid black; padding: 2px; width: 20%; font-size: 8.5pt; font-weight: normal; text-align: center; vertical-align: middle; line-height: 1.2;">เหตุผลและความจำเป็นที่จะต้องซื้อหรือจ้าง</td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 2px; font-size: 10pt; font-weight: normal; text-align: center; vertical-align: middle; line-height: 1.2;">ราคา</td>
            <td style="border: 1px solid black; padding: 2px; font-size: 10pt; font-weight: normal; text-align: center; vertical-align: middle; line-height: 1.2;">หน่วย</td>
            <td style="border: 1px solid black; padding: 2px; font-size: 10pt; font-weight: normal; text-align: center; vertical-align: middle; line-height: 1.2;">จำนวน</td>
            <td style="border: 1px solid black; padding: 2px; font-size: 10pt; font-weight: normal; text-align: center; vertical-align: middle; line-height: 1.2;">หน่วย</td>
            <td style="border: 1px solid black; padding: 2px; font-size: 10pt; font-weight: normal; text-align: center; vertical-align: middle; line-height: 1.2;">ราคาต่อหน่วย</td>
            <td style="border: 1px solid black; padding: 2px; font-size: 10pt; font-weight: normal; text-align: center; vertical-align: middle; line-height: 1.2;">รวมเงิน</td>
        </tr>
        ';

// นับจำนวนรายการจริง
$item_count = count($items);

// กำหนดจำนวนแถวคงที่ทั้งหมด (รวมแถวข้อมูลและแถวว่าง)
$total_rows = 20;

// Loop แสดงข้อมูล items ทั้งหมด
$item_number = 1;
foreach ($items as $item) {
    // แสดงเหตุผลเฉพาะแถวแรกเท่านั้น
    $reason_text = ($item_number == 1) ? htmlspecialchars($report['reason']) : '';

    // แสดงราคากลางและหน่วยจากฐานข้อมูล (ถ้ามี) ไม่มีให้แสดง -
    $middle_price_display = (!empty($item['middle_price']) && $item['middle_price'] > 0) ? number_format($item['middle_price'], 2) : '-';
    $middle_unit_display = !empty($item['middle_unit']) ? htmlspecialchars($item['middle_unit']) : '-';

    $html .= '
        <tr>
            <td style="border: 1px solid black; padding: 5px 2px; font-size: 10pt; font-weight: normal; text-align: center; line-height: 1.2;">' . $item_number . '</td>
            <td style="border: 1px solid black; padding: 5px 2px; font-size: 8.5pt; font-weight: normal; line-height: 1.2;">' . htmlspecialchars($item['item_name']) . '</td>
            <td style="border: 1px solid black; padding: 5px 2px; font-size: 10pt; font-weight: normal; text-align: center; line-height: 1.2;">' . $middle_price_display . '</td>
            <td style="border: 1px solid black; padding: 5px 2px; font-size: 10pt; font-weight: normal; text-align: center; line-height: 1.2;">' . $middle_unit_display . '</td>
            <td style="border: 1px solid black; padding: 5px 2px; font-size: 10pt; font-weight: normal; text-align: center; line-height: 1.2;">' . htmlspecialchars($item['quantity']) . '</td>
            <td style="border: 1px solid black; padding: 5px 2px; font-size: 8.5pt; font-weight: normal; text-align: center; line-height: 1.2;">' . htmlspecialchars($item['unit']) . '</td>
            <td style="border: 1px solid black; padding: 5px 2px; font-size: 10pt; font-weight: normal; text-align: right; line-height: 1.2;">' . number_format($item['price_per_unit'], 2) . '</td>
            <td style="border: 1px solid black; padding: 5px 2px; font-size: 10pt; font-weight: normal; text-align: right; line-height: 1.2;">' . number_format($item['total'], 2) . '</td>
            <td style="border: 1px solid black; padding: 5px 2px; font-size: 8.5pt; font-weight: normal; line-height: 1.2;">' . $reason_text . '</td>
        </tr>';
    $item_number++;
}

// เติมแถวว่างให้ครบจำนวนที่กำหนด
$empty_rows = $total_rows - $item_count;
for ($i = 0; $i < $empty_rows; $i++) {
    $html .= '
        <tr>
            <td style="border: 1px solid black; padding: 5px 2px; font-size: 10pt; font-weight: normal; text-align: center; line-height: 1.2;">&nbsp;</td>
            <td style="border: 1px solid black; padding: 5px 2px; font-size: 10pt; font-weight: normal; line-height: 1.2;"></td>
            <td style="border: 1px solid black; padding: 5px 2px; font-size: 10pt; font-weight: normal; line-height: 1.2;"></td>
            <td style="border: 1px solid black; padding: 5px 2px; font-size: 10pt; font-weight: normal; line-height: 1.2;"></td>
            <td style="border: 1px solid black; padding: 5px 2px; font-size: 10pt; font-weight: normal; line-height: 1.2;"></td>
            <td style="border: 1px solid black; padding: 5px 2px; font-size: 10pt; font-weight: normal; line-height: 1.2;"></td>
            <td style="border: 1px solid black; padding: 5px 2px; font-size: 10pt; font-weight: normal; line-height: 1.2;"></td>
            <td style="border: 1px solid black; padding: 5px 2px; font-size: 10pt; font-weight: normal; line-height: 1.2;"></td>
            <td style="border: 1px solid black; padding: 5px 2px; font-size: 10pt; font-weight: normal; line-height: 1.2;"></td>
        </tr>';
}

$html .= '
        <tr>
            <td style="border: 1px solid black; padding: 5px 2px; font-size: 10pt; font-weight: normal; text-align: center; line-height: 1.2;">&nbsp;</td>
            <td style="border: 1px solid black; padding: 5px 2px; font-size: 10pt; font-weight: normal; text-align: center; font-weight: bold; line-height: 1.2;">รวมเป็นเงินทั้งสิ้น</td>
            <td style="border: 1px solid black; padding: 5px 2px; font-size: 10pt; font-weight: normal; line-height: 1.2;"></td>
            <td style="border: 1px solid black; padding: 5px 2px; font-size: 10pt; font-weight: normal; line-height: 1.2;"></td>
            <td style="border: 1px solid black; padding: 5px 2px; font-size: 10pt; font-weight: normal; line-height: 1.2;"></td>
            <td style="border: 1px solid black; padding: 5px 2px; font-size: 10pt; font-weight: normal; line-height: 1.2;"></td>
            <td style="border: 1px solid black; padding: 5px 2px; font-size: 10pt; font-weight: normal; line-height: 1.2;"></td>
            <td style="border: 1px solid black; padding: 5px 2px; font-size: 10pt; font-weight: bold; text-align: right; line-height: 1.2;">' . number_format((float)$report['budget_used'], 2) . '</td>
            <td style="border: 1px solid black; padding: 5px 2px; font-size: 10pt; font-weight: normal; line-height: 1.2;"></td>
        </tr>
    </table>

    <table style="width: 100%; border: none; border-collapse: collapse;">
        <tr>
            <td style="border: none; padding: 2px; font-size: 10pt; font-weight: bold; text-align: center; line-height: 1.2;">รวมเงิน ( <span style="border-bottom: 1px dotted #000;">' . $budgetText . '</span> )</td>
        </tr>
    </table>

    <table style="width: 100%; border: none; border-collapse: collapse; margin-top: -5px;">
        <tr>
            <td style="border: none; padding: 2px; font-size: 10pt; font-weight: normal; text-align: center; vertical-align: middle; line-height: 1.2;">ผู้สำรวจ</td>
            <td style="border: none; padding: 2px; font-size: 10pt; font-weight: normal; text-align: center; vertical-align: middle; line-height: 1.2;">&nbsp;</td>
            <td style="border: none; padding: 2px; font-size: 10pt; font-weight: normal; text-align: center; vertical-align: middle; line-height: 1.2;">&nbsp;</td>
            <td style="border: none; padding: 2px; font-size: 10pt; font-weight: normal; text-align: center; vertical-align: middle; line-height: 1.2;">&nbsp;</td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; font-size: 10pt; font-weight: normal; text-align: center; vertical-align: middle; line-height: 1.2;">......................................................</td>
            <td style="border: none; padding: 2px; font-size: 10pt; font-weight: normal; text-align: center; vertical-align: middle; line-height: 1.2;">......................................................</td>
            <td style="border: none; padding: 2px; font-size: 10pt; font-weight: normal; text-align: center; vertical-align: middle; line-height: 1.2;">......................................................</td>
            <td style="border: none; padding: 2px; font-size: 10pt; font-weight: normal; text-align: center; vertical-align: middle; line-height: 1.2;">......................................................</td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; font-size: 10pt; font-weight: normal; text-align: center; vertical-align: middle; line-height: 1.2;">เจ้าหน้าที่</td>
            <td style="border: none; padding: 2px; font-size: 10pt; font-weight: normal; text-align: center; vertical-align: middle; line-height: 1.2;">วัน เดือน ปี</td>
            <td style="border: none; padding: 2px; font-size: 10pt; font-weight: normal; text-align: center; vertical-align: middle; line-height: 1.2;">ผู้ขออนุญาต</td>
            <td style="border: none; padding: 2px; font-size: 10pt; font-weight: normal; text-align: center; vertical-align: middle; line-height: 1.2;">วัน เดือน ปี</td>
        </tr>
    </table>

    <table style="width: 100%; border: none; border-collapse: collapse;">
        <tr>
            <td style="border: none; padding: 2px; font-size: 10pt; font-weight: normal; line-height: 1.2;">' .
    ($report['project_type'] == 'โครงการยุทธศาสตร์ฯ' ? 'บันทึกอธิการบดี' : 'บันทึกคณบดี') .
    '</td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; font-size: 10pt; font-weight: normal; line-height: 1.2;">( ) ' .
    ($report['project_type'] == 'โครงการยุทธศาสตร์ฯ' ? 'ไม่เห็นชอบ' : 'ไม่อนุมัติ') .
    '</td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; font-size: 10pt; font-weight: normal; line-height: 1.2;">( ) เห็นชอบและอนุมัติให้ดำเนินการได้ และทำให้ถูกต้องตามระเบียบ</td>
        </tr>
        <tr>
            <td style="border: none; padding: 15px 2px; font-size: 10pt; font-weight: normal; line-height: 1.2;">ผู้อนุมัติ.....................................................</td>
        </tr>
    </table>

    <table style="width: 100%; border: none; border-collapse: collapse;">
';

// แสดงข้อมูลผู้อนุมัติเฉพาะโครงการสาขาวิชา/คณะ
if ($report['project_type'] != 'โครงการยุทธศาสตร์ฯ') {
    $html .= '
        <tr>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: center; line-height: 1.2;">(นายวิสุทธิ์ กิจชัยนุกูล)</td>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; line-height: 1.2;">&nbsp;</td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: center; line-height: 1.2;">คณบดีคณะวิทยาศาสตร์และเทคโนโลยี ปฏิบัติราชการแทน</td>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; line-height: 1.2;">&nbsp;</td>
        </tr>
        <tr>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: center; line-height: 1.2;">อธิการบดีมหาวิทยาลัยราชภัฏเลย</td>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; line-height: 1.2;">&nbsp;</td>
        </tr>
    ';
}

$html .= '
        <tr>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; text-align: center; line-height: 1.2;">..................../.................../...................</td>
            <td style="border: none; padding: 2px; width: 50%; font-size: 10pt; font-weight: normal; line-height: 1.2;">&nbsp;</td>
        </tr>
    </table>

    </body>
</html>
';

// เขียน HTML ลงใน PDF
$mpdf->WriteHTML($html);

// ออก PDF
$filename = 'รายงานกันเงิน_' . $report['kan_no'] . '_' . date('Ymd') . '.pdf';
$mpdf->Output($filename, 'I'); // 'I' = แสดงใน browser, 'D' = ดาวน์โหลด
