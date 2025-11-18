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
$password_confirm = $_POST['password_confirm'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';

// --- 3. ตรวจสอบข้อมูลเบื้องต้น ---
if (empty($username) || empty($password) || empty($password_confirm) || empty($email) || empty($phone)) {
    header("Location: register.php?error=กรุณากรอกข้อมูลให้ครบทุกช่อง");
    exit();
}

if ($password !== $password_confirm) {
    header("Location: register.php?error=รหัสผ่านไม่ตรงกัน");
    exit();
}

// --- 4. (สำคัญ!) เข้ารหัสรหัสผ่าน ---
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// --- 5. ตรวจสอบว่ามีชื่อผู้ใช้หรืออีเมลนี้ในระบบหรือยัง ---
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        header("Location: register.php?error=ชื่อผู้ใช้ หรือ อีเมลนี้ ถูกใช้งานแล้ว");
        exit();
    }

    // --- 6. ถ้าไม่มี... เพิ่มผู้ใช้ใหม่ลงใน DB ---
    $stmt = $pdo->prepare("INSERT INTO users (username, password, email, phone) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $hashed_password, $email, $phone]);

    // สมัครสำเร็จ! ส่งกลับไปหน้า Login พร้อมข้อความ
    header("Location: login.php?success=สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ");
    exit();

} catch (PDOException $e) {
    header("Location: register.php?error=เกิดข้อผิดพลาด: " . $e->getMessage());
    exit();
}
?>