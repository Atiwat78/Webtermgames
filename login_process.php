<?php
session_start();
require_once 'db.php'; // เรียกใช้ไฟล์เชื่อมต่อฐานข้อมูลตัวกลาง

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าจากฟอร์ม
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        header("Location: login.php?error=กรุณากรอกข้อมูลให้ครบ");
        exit();
    }

    // 1. ดึงข้อมูล id, username, password และ role จากฐานข้อมูล
    $sql = "SELECT id, username, password, role FROM users WHERE username = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            
            // 2. ตรวจสอบรหัสผ่าน (ต้องใช้ password_verify เพราะรหัสถูกเข้ารหัสไว้)
            if (password_verify($password, $row['password'])) {
                
                // 3. สร้าง Session เก็บค่าต่างๆ
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role']; // ✅ เก็บสิทธิ์ผู้ใช้งาน (admin/member)

                // 4. ✅ แยกทาง: แอดมินไปหลังบ้าน / ลูกค้าไปหน้าเติมเกม
                if ($row['role'] === 'admin') {
                    header("Location: admin/index.php");
                } else {
                    header("Location: topup.php");
                }
                exit();

            } else {
                header("Location: login.php?error=รหัสผ่านไม่ถูกต้อง");
                exit();
            }
        } else {
            header("Location: login.php?error=ไม่พบชื่อผู้ใช้นี้ในระบบ");
            exit();
        }
        $stmt->close();
    } else {
        header("Location: login.php?error=ระบบผิดพลาด");
        exit();
    }
} else {
    // ถ้าไม่ได้กด Submit มา ให้เด้งกลับ
    header("Location: login.php");
    exit();
}
?>