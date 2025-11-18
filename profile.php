<?php
session_start();

// --- 1. ตรวจสอบว่าล็อกอินหรือยัง ---
// ถ้ายังไม่ได้ล็อกอิน (ไม่มี session) ให้เด้งกลับไปหน้า login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// --- 2. ดึงชื่อผู้ใช้จาก Session มาเก็บไว้ ---
// (เราเก็บชื่อนี้ไว้ตอนที่เขา Cิn ล็อกอินในไฟล์ login_process.php)
$username = $_SESSION['username'];

?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>โปรไฟล์ของฉัน | เติมเกม</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    
    <style>
        /* Reset เบื้องต้น */
        * {
            box-sizing: border-box;
        }

        /* ▼▼▼ (ใช้พื้นหลังแบบเดียวกับ login.php) ▼▼▼ */
        body {
            font-family: 'Sarabun', sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url('image/Deerii.jpg'); /* <-- ใช้ภาพพื้นหลังเดียวกับ login */
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            padding-top: 70px;
        }
        /* ▲▲▲ -------------------------------------- ▲▲▲ */

        /* ▼▼▼ (คัดลอก CSS Navbar ทั้งหมดจาก login.php) ▼▼▼ */
        .navbar {
            position: fixed; 
            top: 0;
            left: 0;
            width: 100%;
            height: 70px; 
            background: rgba(25, 25, 30, 0.7); 
            backdrop-filter: blur(12px); 
            -webkit-backdrop-filter: blur(12px); 
            border-bottom: 1px solid rgba(255, 255, 255, 0.1); 
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 50px; 
            color: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2); 
            z-index: 1000; 
            box-sizing: border-box; 
        }
        .navbar .logo { 
            font-size: 22px; 
            font-weight: bold; 
            color: #fff; 
            text-decoration: none; 
        }
        .navbar-links { 
            display: flex; 
            align-items: center; 
        }
        .navbar ul { 
            list-style: none; 
            display: flex; 
            align-items: center; 
            margin: 0; 
            padding: 0; 
        }
        .navbar ul li { 
            margin-left: 25px; 
        }
        .navbar ul li a { 
            color: white; 
            text-decoration: none; 
            font-weight: 500; 
            transition: color 0.3s ease; 
            font-size: 16px;
        }
        .navbar ul li a:hover { 
            color: #ffc107; /* สีทอง */
        }
        .login-btn, .logout-btn { 
            background-color: rgba(255, 255, 255, 0.1); 
            border: 1px solid rgba(255, 255, 255, 0.2); 
            padding: 8px 18px; 
            border-radius: 20px; 
            transition: background-color 0.3s ease, border-color 0.3s ease;
            font-weight: 500;
        }
        .login-btn:hover, .logout-btn:hover { 
            background-color: rgba(255, 255, 255, 0.2); 
            border-color: rgba(255, 255, 255, 0.4);
            color: #fff !important; 
        }
        /* CSS สำหรับต้อนรับ (จาก topup.php) */
        .navbar .user-menu .welcome-user a {
            color: #ffc107;
            font-weight: 600;
            cursor: default; 
        }
        .navbar .user-menu .welcome-user a:hover {
            color: #ffc107; 
        }
        /* ▲▲▲ จบส่วน Navbar ▲▲▲ */

        /* ▼▼▼ --- (เพิ่ม) CSS สำหรับกล่องโปรไฟล์ --- ▼▼▼ */
        .profile-container {
            background: rgba(25, 25, 30, 0.7); 
            backdrop-filter: blur(3px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1); 
            padding: 40px 50px 50px;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3); 
            width: 480px;
            text-align: center;
            position: relative;
            overflow: hidden;
            color: #fff; /* สีตัวอักษรหลัก */
        }
        .profile-container h2 {
            font-size: 28px;
            margin-bottom: 20px;
        }
        .profile-container p {
            font-size: 18px;
            margin: 10px 0;
        }
        .profile-container .username {
            color: #ffc107; /* สีทอง */
            font-weight: bold;
            font-size: 22px;
        }
        /* ▲▲▲ ------------------------------------ ▲▲▲ */
    </style>
</head>
<body>

<nav class="navbar">
    <a href="topup.php" class="logo">ntyztermgame</a> 
    <div class="navbar-links">
        <ul class="main-menu">
            <li><a href="topup.php">หน้าแรก</a></li> 
            <li><a href="topup.php">เติมเกม</a></li>
        </ul>
        <ul class="user-menu">
            <?php if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) : ?>
                <li class="welcome-user">
                    <a>สวัสดี, <?php echo htmlspecialchars($_SESSION['username']); ?></a>
                </li>
                <li><a href="profile.php">โปรไฟล์</a></li>
                <li><a href="logout.php" class="logout-btn">ออกจากระบบ</a></li>
            <?php else : ?>
                <li><a href="login.php" class="login-btn">เข้าสู่ระบบ</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<div class="profile-container">
    <h2>โปรไฟล์ของฉัน</h2>
    <p>ยินดีต้อนรับ</p>
    <p class="username"><?php echo htmlspecialchars($username); ?></p>
    
    <p style="margin-top: 30px;">
        (คุณสามารถเพิ่มข้อมูลอื่นๆ ของผู้ใช้ได้ที่นี่)
    </p>
    
    </div>
</body>
</html>