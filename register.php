<?php

session_start();

if (isset($_SESSION['user_id'])) { // แก้ check session ให้เหมือน login
    header("Location: topup.php");
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
/* Reset เบื้องต้น */
* {
    box-sizing: border-box;
}

/* ▼▼▼ --- CSS หลัก (Light Mode - ธีมขาว/ทอง) --- ▼▼▼ */
body {
    font-family: 'Sarabun', sans-serif;
    min-height: 100vh; /* ใช้ min-height เพื่อรองรับเนื้อหาที่ยาวกว่าหน้าจอ */
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    padding-top: 90px; /* เพิ่มพื้นที่ด้านบนเล็กน้อยเพราะกล่องสมัครสมาชิกยาว */
    padding-bottom: 50px;
    
    /* พื้นหลัง Light Mode */
    background-color: #f4f7f6;
    color: #333;
    transition: background-color 0.3s ease, color 0.3s ease;
}

/* ▼▼▼ --- Navbar --- ▼▼▼ */
.navbar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 70px;
    background: rgba(26, 26, 46, 0.95); 
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 50px;
    color: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
    z-index: 1000;
}

.navbar .logo {
    font-size: 24px; 
    font-weight: 700;
    color: #FFC107; 
    text-decoration: none;
    letter-spacing: 1px;
    display: flex;
    align-items: center;
    gap: 8px;
}
/* ปรับขนาดรูปโลโก้ใน Navbar */
.navbar .logo img { height: 250px; width: auto; padding: 0; }

/* ▼▼▼ --- Logic สลับโลโก้ --- ▼▼▼ */
.logo-black { display: none; } 
.logo-white { display: block; }

/* Logic แสดงผลตามโหมด */
body:not(.dark-mode) .logo-black { display: block !important; }
body:not(.dark-mode) .logo-white { display: none !important; }

body.dark-mode .logo-black { display: none !important; }
body.dark-mode .logo-white { display: block !important; }

body:not(.dark-mode) .navbar {
    background: rgba(255, 255, 255, 0.95);
    color: #333;
    border-bottom: 1px solid rgba(0,0,0,0.1);
}
body:not(.dark-mode) .navbar ul li a { color: #333; }
body:not(.dark-mode) .navbar ul li a:hover { color: #FFC107; }

/* ▼▼▼ --- Components --- ▼▼▼ */
.navbar-links { display: flex; align-items: center; }
.navbar ul { list-style: none; display: flex; align-items: center; margin: 0; padding: 0; }
.navbar ul li { margin-left: 30px; }
.navbar ul li a {
    text-decoration: none;
    font-weight: 400;
    transition: color 0.3s ease;
    font-size: 16px;
    padding: 5px 0;
}

.login-btn, .logout-btn {
    background-color: #FFC107;
    color: #1A1A2E !important; 
    border: none;
    padding: 8px 18px;
    border-radius: 20px;
    transition: background-color 0.3s ease, transform 0.2s ease;
    font-weight: 600;
}
.login-btn:hover, .logout-btn:hover {
    background-color: #FFD740;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(255, 193, 7, 0.4);
}

/* Theme Switch */
.theme-switch { position: relative; display: inline-block; width: 50px; height: 26px; margin-left: 25px; }
.theme-switch input { display: none; }
.slider {
    position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
    background-color: #ccc; transition: .4s; border-radius: 34px;
}
.slider:before {
    position: absolute; content: ""; height: 18px; width: 18px; left: 4px; bottom: 4px;
    background-color: white; transition: .4s; border-radius: 50%;
}
input:checked + .slider { background-color: #FFC107; }
input:checked + .slider:before { transform: translateX(24px); }


/* ▼▼▼ --- กล่อง Register (ใช้สไตล์เดียวกับ Login) --- ▼▼▼ */
.register-container {
    background: #ffffff; /* พื้นหลังขาว */
    border: 1px solid #e5e7eb;
    /* จัด Padding ตามสูตร Logo สวย */
    padding: 10px 50px 50px;
    
    border-radius: 18px; 
    box-shadow: 0 10px 40px rgba(0,0,0,0.1); 
    width: 450px; /* กว้างกว่า Login นิดหน่อยเพราะข้อมูลเยอะ */
    max-width: 90%;
    text-align: center;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

/* เส้นตกแต่งด้านบน */
.register-container::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 5px;
    background: linear-gradient(90deg, transparent, #FFC107, transparent);
    animation: border-animate 2s linear infinite;
}
@keyframes border-animate {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

h2 {
    margin-bottom: 30px;
    color: #1A1A2E; 
    font-weight: 700;
    font-size: 32px;
    letter-spacing: 1px;
    border-bottom: 2px solid rgba(0, 0, 0, 0.1);
    padding-bottom: 10px;
    transition: color 0.3s ease, border-color 0.3s ease;
}

/* --- Input --- */
input[type="text"], input[type="password"], input[type="email"], input[type="tel"] {
    width: 100%;
    padding: 15px 20px 15px 50px; 
    margin: 10px 0 20px;
    background: #f9fafb; 
    border: 1px solid #d1d5db;
    color: #333;
    border-radius: 8px;
    font-size: 16px;
    transition: all 0.3s ease;
    font-weight: 400;
}
input::placeholder { color: #aaa; opacity: 0.8; font-weight: 300; }
input:focus {
    border-color: #FFC107; outline: none;
    box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.2); background: #fff;
}

button {
    background: #FFC107; color: #1A1A2E; padding: 15px 0; width: 100%;
    border: none; border-radius: 8px; font-size: 18px; font-weight: 700; cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
    letter-spacing: 0.5px; margin-top: 10px;
}
button:hover {
    background: #FFD740; transform: translateY(-2px);
    box-shadow: 0 8px 15px rgba(255, 193, 7, 0.3);
}

.input-icon { position: relative; }
.input-icon svg {
    position: absolute; top: 50%; left: 18px; transform: translateY(-50%);
    fill: #FFC107; width: 20px; height: 20px; z-index: 2;
}

.login-link { margin-top: 25px; font-size: 15px; color: #666; transition: color 0.3s ease; }
.login-link a { color: #FFC107; font-weight: 600; text-decoration: none; transition: color 0.3s ease; }
.login-link a:hover { color: #DAA520; text-decoration: underline; }

.error-message {
    color: #ff3366; background: rgba(255, 51, 102, 0.1); border: 1px solid #ff3366;
    padding: 10px; border-radius: 5px; margin-top: -10px; margin-bottom: 20px; font-size: 0.95em; font-weight: 500;
}
.success-message {
    color: #27ae60; background: rgba(39, 174, 96, 0.1); border: 1px solid #27ae60;
    padding: 10px; border-radius: 5px; margin-top: -10px; margin-bottom: 20px; font-size: 0.95em; font-weight: 500;
}

/* ▼▼▼ --- Dark Mode Styles --- ▼▼▼ */
body.dark-mode {
    background: linear-gradient(-45deg, #1A1A2E, #283049, #1a1a2e, #0f0f1a);
    background-size: 400% 400%; animation: gradientShift 15s ease infinite; color: #fff;
}
@keyframes gradientShift { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }

body.dark-mode .navbar { background: rgba(26, 26, 46, 0.95); border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
body.dark-mode .navbar ul li a { color: #e0e0e0; }
body.dark-mode .navbar ul li a:hover { color: #ffc107; }

body.dark-mode .register-container {
    background: rgba(30, 30, 45, 0.8); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1); box-shadow: 0 25px 50px rgba(0,0,0,0.5);
}
body.dark-mode h2 { color: #fff; border-bottom-color: rgba(255, 255, 255, 0.1); }
body.dark-mode input[type="text"], body.dark-mode input[type="password"], body.dark-mode input[type="email"], body.dark-mode input[type="tel"] {
    background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.15); color: #fff;
}
body.dark-mode input:focus {
    border-color: #FFC107; background: rgba(255, 193, 7, 0.05); box-shadow: 0 0 12px rgba(255, 193, 7, 0.3);
}
body.dark-mode .login-link { color: #B0B0B0; }
body.dark-mode .login-link a:hover { color: #FFD740; }

/* Responsive */
@media (max-width: 768px) {
    .navbar { padding: 0 15px; }
    .navbar ul li { margin-left: 15px; }
    .theme-switch { margin-left: 15px; }
    .register-container { width: 90%; padding: 30px 25px 40px; }
}

/* ▼▼▼ --- CSS สำหรับโลโก้ในกล่อง Register (สูตรล่าสุด) --- ▼▼▼ */
.form-logo {
    display: flex;
    justify-content: center;
    margin-bottom: 15px;
}

.register-container img {
    width: 220px;          /* ความกว้างตามสูตร */
    height: auto;
    
    margin-top: -20px;     /* ดึงขึ้นตามสูตร */
    margin-bottom: 0px;    /* ลดช่องว่างล่าง */
    
    display: block;
    margin-left: auto;
    margin-right: auto;
    object-fit: contain;
}
</style>
</head>
<body>

<script>
    // Theme logic
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-mode');
    }
</script>

<nav class="navbar">
    <a href="topup.php" class="logo">
        <img src="image/Elite Logo black.png" class="logo-black" alt="Elite Logo Black">
        <img src="image/Elite Logo white.png" class="logo-white" alt="Elite Logo White">
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

        <label class="theme-switch" for="theme-toggle">
            <input type="checkbox" id="theme-toggle">
            <span class="slider"></span>
        </label>
    </div>
</nav>

<div class="register-container"> 
    
    <div class="form-logo">
        <img src="image/Elite Logo black.png" class="logo-black" alt="Elite Logo">
        <img src="image/Elite Logo white.png" class="logo-white" alt="Elite Logo">
    </div>
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

<script>
    const themeToggle = document.getElementById('theme-toggle');
    if (document.body.classList.contains('dark-mode')) {
        themeToggle.checked = true;
    }
    themeToggle.addEventListener('change', function() {
        if (this.checked) {
            document.body.classList.add('dark-mode');
            localStorage.setItem('theme', 'dark');
        } else {
            document.body.classList.remove('dark-mode');
            localStorage.setItem('theme', 'light');
        }
    });
</script>

</body>
</html>