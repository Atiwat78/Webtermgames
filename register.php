<?php

session_start();

if (isset($_SESSION['user'])) {
    header("Location: topup.php?game=freefire");
    exit();
}

$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? ''; 
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>สมัครสมาชิก | เติมเกม</title>
<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
/* --- THEME COLORS ---
 * Primary Accent: #FFC107 (Bright Gold/Amber)
 * Dark Accent: #FFA500 (Orange Gold)
 * Success: #27ae60
*/

/* Reset เบื้องต้น */
* {
    box-sizing: border-box;
}

/* ▼▼▼ --- พื้นหลัง Gradient Animation (เหมือน Login) --- ▼▼▼ */
body {
    font-family: 'Sarabun', sans-serif;
    height: 100vh;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    padding-top: 70px; 
    
    /* --- พื้นหลัง Gradient Animation --- */
    background: linear-gradient(-45deg, #1A1A2E, #283049, #1a1a2e, #1b263b); 
    background-size: 400% 400%; 
    animation: gradientShift 15s ease infinite; 
}

/* Keyframes สำหรับการเคลื่อนไหวของพื้นหลัง */
@keyframes gradientShift {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}
/* ▲▲▲ ----------------------------------------------------- ▲▲▲ */


/* ▼▼▼ CSS Navbar (ปรับให้รองรับ Logo รูปภาพและใช้สีทอง) ▼▼▼ */
.navbar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 70px;
    background: rgba(25, 25, 30, 0.9); 
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 50px;
    color: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.5); 
    z-index: 1000;
    box-sizing: border-box;
}
.navbar .logo {
    font-size: 24px; 
    font-weight: 700;
    color: #FFC107; /* <-- สีทอง */
    text-decoration: none;
    letter-spacing: 1px;
    display: flex; 
    align-items: center;
    gap: 8px; 
}
.navbar .logo img {
    height: 60px; /* ขนาด Logo */
    width: auto;
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
    margin-left: 30px;
}
.navbar ul li a {
    color: #E0E0E0;
    text-decoration: none;
    font-weight: 400;
    transition: color 0.3s ease;
    font-size: 16px;
    padding: 5px 0;
}
.navbar ul li a:hover {
    color: #FFC107; /* <-- สีทอง */
}
.login-btn, .logout-btn {
    background-color: #FFC107; /* <-- สีทอง */
    color: #1A1A2E !important; 
    border: none;
    padding: 8px 18px;
    border-radius: 20px;
    transition: background-color 0.3s ease, transform 0.2s ease;
    font-weight: 600;
}
.login-btn:hover, .logout-btn:hover {
    background-color: #FFD740; /* <-- สีทองอ่อนลง */
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(255, 193, 7, 0.4);
}
/* ▲▲▲ จบส่วน Navbar ▲▲▲ */


/* --- กล่อง Register (Glassmorphism + สีทอง) --- */
.register-container { 
    background: rgba(30, 30, 45, 0.8);
    backdrop-filter: blur(20px); 
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 40px 50px 50px;
    border-radius: 18px; 
    box-shadow: 0 25px 50px rgba(0,0,0,0.5); 
    width: 420px; /* กะทัดรัดขึ้น */
    max-width: 90%;
    text-align: center;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

/* เพิ่มเส้นตกแต่ง (สีทอง) */
.register-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(90deg, transparent, #FFC107, transparent); /* <-- สีทอง */
    animation: border-animate 2s linear infinite;
}

@keyframes border-animate {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

h2 {
    margin-bottom: 30px;
    color: #fff;
    font-weight: 700;
    font-size: 32px;
    letter-spacing: 1px;
    border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    padding-bottom: 10px;
}
form {
    padding-top: 10px;
}

/* --- Input (Sleek Glass - ใช้สีทองสำหรับ focus) --- */
input[type="text"], input[type="password"], input[type="email"], input[type="tel"] {
    width: 100%;
    padding: 15px 20px; /* ปรับขนาดให้เท่ากับ Login */
    margin: 10px 0 15px; 
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #fff;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 300;
    transition: border-color 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
}
input::placeholder {
    color: #aaa;
    opacity: 0.8;
    font-weight: 300;
}

input[type="text"]:focus, input[type="password"]:focus, input[type="email"]:focus, input[type="tel"]:focus {
    border-color: #FFC107; /* <-- สีทอง */
    outline: none;
    box-shadow: 0 0 12px rgba(255, 193, 7, 0.5); /* <-- เงาจากสีทอง */
    background: rgba(255, 193, 7, 0.05); /* <-- พื้นหลังโฟกัสสีทองจางๆ */
}

/* --- ปุ่ม (สีทอง) --- */
button {
    background: #FFC107; /* <-- สีทอง */
    color: #1A1A2E; /* ตัวอักษรสีเข้ม */
    padding: 15px 0;
    width: 100%;
    border: none;
    border-radius: 8px;
    font-size: 18px;
    font-weight: 700;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
    letter-spacing: 0.5px;
    margin-top: 15px; 
}
button:hover {
    background: #FFD740;
    transform: translateY(-2px);
    box-shadow: 0 8px 15px rgba(255, 193, 7, 0.3);
}

/* --- สี Error และ Success --- */
.error-message {
    color: #ff3366; /* แดง */
    background: rgba(255, 51, 102, 0.1);
    border: 1px solid #ff3366;
    padding: 10px;
    border-radius: 5px;
    margin-top: -10px;
    margin-bottom: 20px;
    font-size: 0.95em;
    font-weight: 500;
}
.success-message {
    color: #27ae60; /* <-- เขียว */
    background: rgba(39, 174, 96, 0.1);
    border: 1px solid #27ae60;
    padding: 10px;
    border-radius: 5px;
    margin-top: -10px;
    margin-bottom: 20px;
    font-size: 0.95em;
    font-weight: 500;
}


/* --- ไอคอน (สีทอง) --- */
.input-icon {
    position: relative;
}
.input-icon input {
    padding-left: 50px; /* ปรับให้เท่ากับ Login */
}
.input-icon svg {
    position: absolute;
    top: 50%;
    left: 18px; /* ปรับให้เท่ากับ Login */
    transform: translateY(-50%); /* แก้ไขให้ตรงกลาง 100% */
    fill: #FFC107; /* <-- สีไอคอนเป็นสีทอง */
    width: 20px;
    height: 20px;
    opacity: 0.8;
}

/* --- ลิงก์เข้าสู่ระบบ (สีทอง) --- */
.login-link { 
    margin-top: 25px; /* ปรับให้เท่ากับ Login */
    font-size: 15px;
    color: #B0B0B0;
}
.login-link a {
    color: #FFC107; /* <-- สีทอง */
    font-weight: 600;
    text-decoration: none;
    transition: color 0.3s ease;
}
.login-link a:hover {
    color: #FFD740; /* <-- สีทองอ่อนลงเมื่อโฮเวอร์ */
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 768px) {
    .navbar {
        padding: 0 15px;
        backdrop-filter: blur(8px); 
        -webkit-backdrop-filter: blur(8px);
    }
    .navbar ul li {
        margin-left: 10px;
    }
    .navbar .logo {
        font-size: 20px;
    }
    .navbar .logo img {
        height: 50px; 
    }
    .register-container {
        width: 90%;
        padding: 30px 25px 40px;
    }
    h2 {
        font-size: 28px;
    }
}
</style>
</head>
<body>

<nav class="navbar">
    <a href="topup.php" class="logo">
        <img src="image/logomee-Photoroom.png" alt="Logo Icon"> 
    </a>
    <div class="navbar-links">
        <ul class="main-menu">
            <li><a href="topup.php">หน้าแรก</a></li>
            <li><a href="topup.php">เติมเกม</a></li>
        </ul>
        <ul class="user-menu">
            <?php if (isset($_SESSION['user_id'])) : ?>
                <li><a href="profile.php">โปรไฟล์</a></li>
                <li><a href="logout.php" class="logout-btn">ออกจากระบบ</a></li>
            <?php else : ?>
                <li><a href="login.php" class="login-btn">เข้าสู่ระบบ</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<div class="register-container"> 
    <h2>สมัครสมาชิก</h2>
    
    <?php if ($error): ?>
        <div class="error-message">❌ <?php echo htmlspecialchars($error); ?></div>
    <?php elseif ($success): ?>
        <div class="success-message">✅ <?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <form method="post" action="register_process.php" autocomplete="off">
        
        <div class="input-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
            <input type="text" name="username" placeholder="ชื่อผู้ใช้" required />
        </div>
        
        <div class="input-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
            </svg>
            <input type="password" name="password" placeholder="รหัสผ่าน" required />
        </div>

        <div class="input-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
            </svg>
            <input type="password" name="password_confirm" placeholder="ยืนยันรหัสผ่าน" required />
        </div>

        <div class="input-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
            </svg>
            <input type="email" name="email" placeholder="อีเมล" required />
        </div>

        <div class="input-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M17 1.01L7 1c-1.1 0-2 .9-2 2v18c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V3c0-1.1-.9-1.99-2-1.99zM17 19H7V5h10v14z"/>
            </svg>
            <input type="tel" name="phone" placeholder="เบอร์โทรศัพท์" required />
        </div>
        
        <button type="submit">สมัครสมาชิก</button>
        
        <p class="login-link">
            มีบัญชีแล้ว? 
            <a href="login.php">เข้าสู่ระบบที่นี่</a>
        </p>
    </form>
</div>
</body>
</html>