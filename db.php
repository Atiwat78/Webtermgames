<?php
// ------------------------------------------------------------------
// ⚙️ ตั้งค่าการเชื่อมต่อฐานข้อมูล (Database Configuration)
// ------------------------------------------------------------------

$servername = "localhost";  // ชื่อ Host (ปกติใช้ localhost)
$username   = "root";       // ชื่อผู้ใช้ (XAMPP ปกติคือ root)
$password   = "";           // รหัสผ่าน (XAMPP ปกติปล่อยว่าง)
$dbname     = "games";     // ⚠️ ชื่อฐานข้อมูลของคุณ (แก้ให้ตรงกับใน phpMyAdmin)

// สร้างการเชื่อมต่อ (Create connection)
$conn = new mysqli($servername, $username, $password, $dbname);

// ตั้งค่าภาษาไทยให้แสดงผลถูกต้อง (ป้องกันภาษาต่างดาว)
$conn->set_charset("utf8");

// ตรวจสอบการเชื่อมต่อ (Check connection)
if ($conn->connect_error) {
    // ถ้าเชื่อมต่อไม่ได้ ให้หยุดทำงานและแจ้ง Error
    die("Connection failed: " . $conn->connect_error);
}

// ถ้าเชื่อมต่อสำเร็จ โค้ดจะทำงานต่อเงียบๆ (ไม่ต้อง echo อะไรออกมา)
?>