<?php
session_start();

// --- 1. ข้อมูลเชื่อมต่อฐานข้อมูล (DB) ---
$host = 'localhost';
$db_name = 'games'; // ❗ เปลี่ยนชื่อ DB ของคุณ
$db_user = 'root';
$db_pass = ''; // ❗ รหัสผ่าน DB (ถ้ามี)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ไม่สามารถเชื่อมต่อฐานข้อมูลได้: " . $e->getMessage());
}

// --- 2. รับค่าจากฟอร์ม ---
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    header("Location: login.php?error=กรุณากรอกข้อมูลให้ครบ");
    exit();
}

// --- 3. ตรวจสอบผู้ใช้ใน DB ---
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // --- (อัปเดต!) ใช้ password_verify เพื่อเช็ครหัสที่เข้ารหัสไว้ ---
    if ($user && password_verify($password, $user['password'])) {
        
        // ล็อกอินสำเร็จ: บันทึก ID และ "ชื่อผู้ใช้" ลงใน Session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username']; // <-- บันทึกชื่อตรงนี้

        // ไปยังหน้าหลัก (topup.php)
        header("Location: topup.php");
        exit();

    } else {
        // ล็อกอินไม่สำเร็จ
        header("Location: login.php?error=ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง");
        exit();
    }

} catch (PDOException $e) {
    header("Location: login.php?error=เกิดข้อผิดพลาด: " . $e->getMessage());
    exit();
}
?>