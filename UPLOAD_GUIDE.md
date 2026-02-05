# คู่มือการอัปโหลดไฟล์ขึ้น Server ด้วย FileZilla

## การตั้งค่า FileZilla เพื่อใช้ไฟล์ Ignore

### วิธีที่ 1: ใช้ Filename Filters (แนะนำ)

1. เปิด FileZilla
2. ไปที่ **Edit** > **Settings** (Windows) หรือ **FileZilla** > **Preferences** (Mac)
3. เลือก **Transfers** > **Filename Filters**
4. คลิก **Edit filter rules...**
5. เพิ่ม Filter rules ตามรายการด้านล่าง:

#### Filter Rules ที่ควรเพิ่ม:

```
# ไฟล์ระบบ
*.DS_Store
.git
.gitignore
.ftpignore
.vscode
.idea

# ไฟล์ temporary
tmp
temp
*.tmp
*.log
*.cache
*.backup
*.backup2
*.bak

# ไฟล์ทดสอบ
test*.php
test*.html
*_test.php
check_*.php

# ไฟล์เอกสาร
CLAUDE.md
SCOPE.md
UPLOAD_GUIDE.md

# Vendor (อัปโหลดแยกต่างหาก)
vendor/tecnickcom/tcpdf/examples
vendor/mpdf/mpdf/.github
```

### วิธีที่ 2: เลือกไฟล์ด้วยตนเอง

เมื่ออัปโหลด ให้ **ไม่เลือก** โฟลเดอร์/ไฟล์เหล่านี้:

#### ไม่ต้องอัปโหลด:
- ❌ `.git/`, `.vscode/`, `.idea/`
- ❌ `tmp/`, `temp/`, `fonts/`
- ❌ ไฟล์ `.DS_Store`, `*.log`, `*.tmp`
- ❌ ไฟล์ทดสอบทั้งหมด (`test*.php`, `check_*.php`)
- ❌ ไฟล์ backup (`*.backup`, `*.backup2`)
- ❌ `CLAUDE.md`, `SCOPE.md`, `UPLOAD_GUIDE.md`
- ❌ ไฟล์ PDF ที่ generate (`*.pdf`)

#### ต้องอัปโหลด:
- ✅ ไฟล์ PHP หลักทั้งหมด (`.php`)
- ✅ โฟลเดอร์ `assets/` (แต่ไม่รวม `.DS_Store`)
- ✅ ไฟล์ `kan_ngan.sql` (สำหรับติดตั้งฐานข้อมูล)
- ✅ ไฟล์ `.htaccess` (ถ้ามี)

#### Vendor (แนะนำวิธีการติดตั้ง):

**ตัวเลือก A: อัปโหลด vendor ทั้งหมด**
- อัปโหลดโฟลเดอร์ `vendor/` ทั้งหมด (จะใช้เวลานาน)
- ข้ามโฟลเดอร์ที่ไม่จำเป็น เช่น `/examples/`, `/.github/`

**ตัวเลือก B: ติดตั้ง Composer บน Server (แนะนำ)**
1. อัปโหลดเฉพาะ `composer.json`
2. เชื่อมต่อ Server ผ่าน SSH
3. รันคำสั่ง: `composer install`
4. Composer จะดาวน์โหลด dependencies ให้อัตโนมัติ

## ขั้นตอนการอัปโหลดที่แนะนำ

### 1. เตรียมไฟล์ก่อนอัปโหลด

```bash
# ตรวจสอบ syntax ก่อน
php -l *.php

# ลบไฟล์ temporary
rm -rf tmp/*
rm -f *.tmp *.log *.pdf
```

### 2. การอัปโหลดครั้งแรก

1. อัปโหลดไฟล์ PHP หลักทั้งหมด
2. อัปโหลดโฟลเดอร์ `assets/`
3. อัปโหลด `vendor/` (ถ้าไม่มี Composer บน server)
4. อัปโหลด `kan_ngan.sql`

### 3. การอัปเดตไฟล์

- อัปโหลดเฉพาะไฟล์ที่แก้ไข
- ใช้ฟีเจอร์ **Synchronized Browsing** ใน FileZilla

### 4. หลังอัปโหลด

- ตรวจสอบ file permissions (PHP files: 644, folders: 755)
- Import ฐานข้อมูล: `mysql -u user -p database < kan_ngan.sql`
- แก้ไขไฟล์ `connect_db.php` ให้ตรงกับ server (ต้องสร้างใหม่บน server)

## ไฟล์ที่ต้องสร้างใหม่บน Server

สร้างไฟล์ `connect_db.php` บน server (อย่าอัปโหลดจาก local):

```php
<?php
// ไฟล์นี้อย่า commit หรืออัปโหลดจาก local
$servername = "localhost"; // หรือ IP ของ database server
$username = "your_db_username";
$password = "your_db_password";
$dbname = "kan_ngan";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>
```

## Tips และคำแนะนำ

1. **สำรองข้อมูลก่อนอัปโหลด**: สำรอง server เสมอก่อนอัปเดตไฟล์
2. **ทดสอบบน Local ก่อน**: ตรวจสอบว่าระบบทำงานถูกต้องบน local
3. **ใช้ Queue**: FileZilla มี queue feature สำหรับจัดการการอัปโหลดหลายไฟล์
4. **ตั้งค่า Transfer Type**: ตั้งเป็น "Auto" เพื่อให้ FileZilla เลือก ASCII/Binary เอง
5. **เก็บ Log**: บันทึกการเปลี่ยนแปลงทุกครั้งที่อัปโหลด

## การแก้ปัญหา

### ปัญหา: อัปโหลดช้า
- **แก้ไข**: ลด concurrent transfers หรือใช้ compression

### ปัญหา: Permission denied
- **แก้ไข**: ตั้ง permissions ที่ถูกต้อง (files: 644, folders: 755)

### ปัญหา: Thai characters แสดงผิด
- **แก้ไข**:
  - ตรวจสอบ encoding เป็น UTF-8
  - ตั้งค่า `$conn->set_charset("utf8");` ในไฟล์เชื่อมต่อฐานข้อมูล

## คำสั่งที่มีประโยชน์บน Server

```bash
# ดู permissions
ls -la

# แก้ไข permissions
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;

# Import database
mysql -u username -p database_name < kan_ngan.sql

# ตรวจสอบ PHP version
php -v

# ทดสอบ PHP syntax
php -l filename.php
```
