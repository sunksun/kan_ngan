<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานขอซื้อหรือขอจ้าง</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin: 20mm;
            /* ขอบเท่ากันทุกด้าน 20mm */
        }

        body {
            font-family: 'Sarabun', sans-serif;
            font-size: 14px;
            background: white;
            margin: 0;
            padding: 0;
            line-height: 1.4;
        }

        .container {
            width: 100%;
            max-width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 0;
            box-sizing: border-box;
        }

        .header {
            text-align: right;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .text-right {
            text-align: right;
        }

        .section-title-center-bold {
            text-align: center;
            font-weight: bold;
            margin-top: 20px;
        }

        .section-title-center {
            text-align: center;
            margin-top: 20px;
        }

        .section-title-right {
            text-align: right;
            margin-top: 5px;
            /* ระยะห่างด้านบนของ <p> */
            margin-bottom: 5px;
            /* ระยะห่างด้านล่างของ <p> */
        }

        .signatures {
            margin-top: 40px;
        }

        .signatures div {
            margin-bottom: 20px;
        }

        .dotted-underline {
            border-bottom: 1px dotted #000;
            flex-grow: 1;
            display: inline-block;
            position: relative;
        }

        p {
            margin-top: 5px;
            /* ระยะห่างด้านบนของ <p> */
            margin-bottom: 5px;
            /* ระยะห่างด้านล่างของ <p> */
            display: flex;
            align-items: center;
        }

        .p-10 {
            margin-top: 5px;
            margin-bottom: 5px;
            padding-left: 20px;
            /* ระยะห่างจากขอบซ้ายของพื้นที่ที่ครอบ <p> */
            display: flex;
            align-items: center;
        }

        .h6-center-bold {
            text-align: center;
            font-weight: bold;
            margin-top: 20px;
        }

        .span-right {
            text-align: right;
        }

        .single-line {
            white-space: nowrap;
            /* ไม่ให้ข้อความตัดบรรทัด */
        }

        .tab-space {
            margin-left: 60px;
            /* ระยะเว้นเท่ากับการกดแท็บ */
        }

        .text-end {
            text-align: right;
        }

        .page-break {
            page-break-before: always;
        }

        @media print {
            @page {
                margin: 20mm;
            }
            
            /* ลบ header และ footer ของเบราว์เซอร์เมื่อพิมพ์ */
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
        </div>
        <div class="content">
            <h6 class="h6-center-bold">รายงานขอซื้อหรือขอจ้าง</h6>
            <p>เรียน ...........................</p>
            <p class="p-10">ด้วยข้าพเจ้า นาย,นาง,นางสาว &nbsp;<span class="dotted-underline">นายสังสรรค์ หล้าพันธ์</span></p>
            <p>สาขาวิชา/หน่วยงาน &nbsp;<span class="dotted-underline">สาขาวิชาคอมพิวเตอร์และเทคโนโลยีสารสนเทศ</span></p>
            <p>มีความประสงค์ ( / ) ขอซื้อ ( ) ขอจ้าง &nbsp;<span class="dotted-underline">วัสดุ ปากกา และอื่น ๆ อีก 3 รายการ</span></p>
            <p>เพื่อใช้ในโครงการ &nbsp;<span class="dotted-underline">พัฒนาศูนย์การเรียนรู้และฝึกอบรมสำหรับผู้สูงวัยแบบบูรณาการ บ้านนาค้อ ต.ปากชม</span></p>
            <p>โดยพัสดุ/งานจ้างครั้งนี้ใช้ในงาน/กิจกรรม &nbsp;<span class="dotted-underline">สำรวจพฤติกรรมสุขภาพที่พึงประสงค์</span></p>
            <p>โดยใช้งบประมาณ <span class="dotted-underline">แผ่นดิน</span> หมายเลขโครงการ <span class="dotted-underline">660205161</span></p>
            <p>วงเงินที่จะจัดซื้อ/จ้าง <span class="dotted-underline">6470</span> กำหนดเวลาที่ต้องการใช้พัสดุ (วัน) <span class="dotted-underline">1</span></p>
            <p>ในกรณีที่เป็นครุภัณฑ์การซื้อครั้งนี้มีราคา ( ) ต่ำกว่า/เท่ากับราคามาตรฐาน ( ) สูงกว่าเพราะ</p>
            <p>ตามพันธกิจ ( ) การจัดการศึกษา ( ) การวิจัย ( ) การบริการวิชาการ ( ) การทำนุบำรุงศิลปวัฒนธรรม</p>
            <p>ยุทธศาสตร์<span class="dotted-underline">&nbsp;</span></p>
            <div class="text-end">......................................................................................... ผู้ขออนุญาต........./............/.............</div>
            <div class="text-end">..............................................หัวหน้าภาควิชา/ประธานสาขาวิชา/หัวหน้างาน........./......./..........</div>
            <hr>
            <p class="section-title">การตรวจสอบความสอดคล้องกับวัตถุประสงค์ของโครงการ ( ) สอดคล้องกับวัตถุประสงค์ของโครงการ ( .....1........)</p>
            <p>( ) ไม่สอดคล้องไม่ตรงกับวัตถุประสงค์ของโครงการ</p>
            <p>เห็นควร ( ) อนุมัติ </p>
            <p>( ) ไม่อนุมัติ <span class="dotted-underline">&nbsp;</span></p>
            <p class="section-title-right">.......................................หัวหน้าสำนักงานคณบดี/หัวหน้าสำนักงานสถาบัน/สำนัก........./........../.........</p>
            <hr>
            <p>เห็นควรอนุมัติให้ดำเนินการตามเสนอ หมวดเงิน ( ) ใช้สอย ( ) วัสดุ ( ) ค่าครุภัณฑ์ ( ) อื่น ๆ ที่ดินและสิ่งก่อสร้าง</p>
            <p>โดยมีเงินคงเหลือ ....................... บาท จ่ายครั้งนี้ ....................... บาท ทำให้มีเงินคงเหลือ ....................... บาท</p>
            <p class="section-title-right">............................................. เจ้าหน้าที่การเงินหน่วยงาน.........../............/............</p>
            <p class="section-title-right">............................................ หัวหน้าการเงินของหน่วยงาน........./............./.......….</p>
            <p class="section-title-right">.............................คณบดี/ผู้อำนวยการสถาบัน/สำนัก/ศูนย์........./............./.......….</p>
            <hr>
            <p>เห็นควรอนุมัติให้ดำเนินการตามเสนอ</p>
            <p>โดยมีเงินคงเหลือ <span class="dotted-underline">45524175.- </span>บาท จ่ายครั้งนี้ <span class="dotted-underline">6826700.- </span>บาท ทำให้มีเงินคงเหลือ <span class="dotted-underline">38697475.- </span>บาท</p>
            <p class="section-title-right">..................................................... เจ้าหน้าที่การเงิน.........../............/............</p>
            <p class="section-title-right">......................................................หัวหน้างานการเงิน........./............./.......….</p>
            <hr>
            <p>ได้พิจารณาแล้วสมควรจัดหา ( ) ใช้สอย ( ) วัสดุ ( ) ค่าครุภัณฑ์ ( ) อื่น ๆ <span class="dotted-underline">&nbsp;</span>
            <p>โดยให้ดำเนินการตามพระราชบัญญัติการจัดซื้อจัดจ้างและการบริหารพัสดุภาครัฐ พ.ศ. 2560 มาตรา 56 (2)(ข) และระเบียบกระทรวงการคลังว่าด้วย</p>
            <p>การจัดซื้อจัดจ้างและการบริหารพัสดุภาครัฐ พ.ศ. 2560 ซึ่งจะจัดหาโดยวิธี เฉพาะเจาะจง</p>
            <p>และหลักเกณฑ์ในการพิจารณาคือ เกณฑ์ราคา</p>
            <p>และควรเสนอให้</p>
            <p class="single-line">
                <span class="tab-space">บุคคลต่อไปนี้เป็นผู้กำหนดคุณลักษณะของพัสดุ /</span>
                <span class="tab-space">บุคคลต่อไปนี้เป็นกรรมการตรวจรับพัสดุ</span>
            </p>
            <p class="single-line">
                <span class="tab-space">คณะกรรมการกำหนดคุณลักษณะของพัสดุ </span>
                <span class="tab-space">1. <span class="dotted-underline">&nbsp;</span>ประธานกรรมการ</span>
            </p>
            <p class="single-line">
                <span class="tab-space">1. <span class="dotted-underline">&nbsp;</span> </span>
                <span class="tab-space">2. <span class="dotted-underline">&nbsp;</span>กรรมการ</span>
            </p>
            <p class="single-line">
                <span class="tab-space">2. <span class="dotted-underline">&nbsp;</span> </span>
                <span class="tab-space">3. <span class="dotted-underline">&nbsp;</span>กรรมการ</span>
            </p>
            <p>พร้อมนี้ได้แนบหลักฐาน คือ</p>
            <p>1. บันทึกรายงานของเจ้าหน้าที่พัสดุ............ชุด</p>
            <p>2. ...............................................................</p>
            <p>จึงเรียนมาเพื่อโปรดพิจารณา</p>
            <p class="section-title-right">..................................................................... เจ้าหน้าที่ ................./.............../............</p>
            <p class="section-title-right">................................................................... หัวหน้าเจ้าหน้าที่ ................/.............../.............</p>
            <p class="single-line">
                เห็นชอบและอนุมัติวงเงินตามเสนอ
                <span class="tab-space">......................................................อธิการบดีมหาวิทยาลัยราชภัฏเลย............./.............../..............</span>
            </p>

            <p class="section-title-center-bold page-break">รายละเอียดของพัสดุที่จะขอ ( / ) ซื้อ ( ) จ้าง</p>
            <p class="section-title-center">ตามพระราชบัญญัติการจัดซื้อจัดจ้างและการบริหารพัสดุภาครัฐ พ.ศ. 2560 </p>
            <p class="section-title-center">และระเบียบกระทรวงการคลังว่าด้วยการจัดซื้อจัดจ้างและการบริหารพัสดุภาครัฐ พ.ศ. 2560</p>

            ลำดับที่ รายการ ชื่อ ลักษณะ ขนาด ยี่ห้อ (ขอบเขตของงานหรือรายละเอียดคุณลักษณะเฉพาะของพัสดุ ราคากลาง ของพัสดุ ขอดำเนินการครั้งนี้ เหตุผลและความจำเป็นที่จะต้องซื้อหรือจ้าง
            </p>
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>ลำดับที่</th>
                        <th>รายการ</th>
                        <th>ชื่อ ลักษณะ ขนาด ยี่ห้อ</th>
                        <th>ราคากลางของพัสดุ</th>
                        <th>ขอดำเนินการครั้งนี้</th>
                        <th>เหตุผลและความจำเป็นที่จะต้องซื้อหรือจ้าง</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- รายการพัสดุ -->
                    <tr>
                        <td>1</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <p>รวมเป็นเงินทั้งสิ้น 1800</p>
            <p>รวมเงิน ( หนึ่งพันแปดร้อยบาทถ้วน )</p>
        </div>
        <div class="signatures">
            <div>ผู้สำรวจ ................................... ........................................ .......................................... ..................................</div>
            <div>เจ้าหน้าที่ วัน เดือน ปี ผู้ขออนุญาต วัน เดือน ปี</div>
            <div>บันทึกอธิการบดี ( ) ไม่เห็นชอบ ( ) เห็นชอบและอนุมัติให้ดำเนินการได้ และทำให้ถูกต้องตามระเบียบ</div>
            <div>ผู้อนุมัติ ................................................... .............../................../..............</div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>