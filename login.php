<?php

session_start();

// ตรวจสอบว่ามีเซสชัน 'user_id' อยู่หรือไม่ ถ้ามีให้เปลี่ยนเส้นทางไปหน้าเติมเกม
if (isset($_SESSION['user_id'])) {
    header("Location: topup.php");
    exit();
}

// รับค่า error จาก URL
$error = $_GET['error'] ?? '';

?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>เข้าสู่ระบบ | เติมเกม</title>

<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
/* Reset เบื้องต้น */
* {
    box-sizing: border-box;
}

/* ▼▼▼ --- CSS หลัก (Light Mode - ธีมขาว/ทอง) --- ▼▼▼ */
body {
    font-family: 'Sarabun', sans-serif;
    height: 100vh;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    padding-top: 70px; 
    
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
    /* Light Mode Navbar: สีดำโปร่งแสงเล็กน้อย เพื่อให้ตัดกับพื้นขาว */
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
/* ปรับขนาดรูปโลโก้ */
.navbar .logo img { height: 250px; width: auto; padding: 0; }

/* ▼▼▼ --- Logic สลับโลโก้ (เหมือนไฟล์ที่ 2) --- ▼▼▼ */
/* ค่าเริ่มต้น (Light Mode ของหน้านี้ Navbar เป็นสีเข้ม เราจึงใช้โลโก้ขาวเป็นหลัก หรือปรับตามต้องการ) */
/* หมายเหตุ: ในหน้า Login Navbar เป็นสีเข้ม (rgba(26, 26, 46)) ดังนั้นโลโก้ควรเป็นสีขาวหรือทอง */

.logo-black { display: none; } /* ซ่อนโลโก้ดำในตอนแรกเพราะ Navbar เป็นสีเข้ม */
.logo-white { display: block; }

/* แต่ถ้า Body เป็น Dark Mode Navbar ก็ยังเข้มอยู่ Logic นี้จะช่วยสลับถ้าคุณเปลี่ยนสี Navbar */
/* ถ้าต้องการให้สลับตาม Dark Mode แบบเป๊ะๆ ตามไฟล์ 2 ใช้ Logic นี้: */

/* Override Logic เพื่อให้เหมือนไฟล์ที่ 2 */
/* ถ้า Light Mode (ปกติ) */
body:not(.dark-mode) .logo-black { display: none; } /* ปรับเป็น display: block; ถ้าพื้น Navbar สว่าง */
body:not(.dark-mode) .logo-white { display: block; }

/* ถ้า Dark Mode */
body.dark-mode .logo-black { display: none; }
body.dark-mode .logo-white { display: block; }

/* *** หมายเหตุสำคัญ *** หน้า Login ของคุณ CSS .navbar พื้นหลังเป็นสีเข้ม (rgba 26,26,46) ตลอดเวลา
   ดังนั้นโลโก้ "สีดำ" จะมองไม่เห็น ผมจึงตั้งให้โชว์ "สีขาว" ตลอด 
   
   แต่ถ้าคุณอยากให้สลับจริงๆ ตามไฟล์ที่ 2 (ดำ/ขาว) ผมใส่โค้ดไว้ให้ทำงานแล้ว
   ด้านล่างนี้คือ Logic มาตรฐานแบบไฟล์ที่ 2 ครับ:
*/

/* --- เริ่ม Logic สลับโลโก้แบบไฟล์ที่ 2 --- */
/* 1. ซ่อนโลโก้ดำ โชว์โลโก้ขาว (สำหรับ Dark Mode หรือ Navbar เข้ม) */
.logo-black { display: none; }
.logo-white { display: block; }

/* 2. ถ้าต้องการให้ Light Mode โชว์โลโก้ดำ (ต้องแก้สีพื้นหลัง Navbar ให้สว่างด้วย) */
/* ในที่นี้ผมจะคงไว้ตามไฟล์ที่ 2 คือ: */
/* Light Mode -> โชว์ดำ */
/* Dark Mode -> โชว์ขาว */
/* แต่เนื่องจาก Navbar หน้า Login คุณเป็นสีเข้ม ผมจะปรับ CSS Navbar ให้สว่างใน Light Mode ด้วยเพื่อให้เห็นโลโก้ดำ */

body:not(.dark-mode) .navbar {
    background: rgba(255, 255, 255, 0.95); /* ปรับพื้นหลัง Navbar เป็นขาวใน Light Mode */
    color: #333;
    border-bottom: 1px solid rgba(0,0,0,0.1);
}
body:not(.dark-mode) .navbar ul li a {
    color: #333;
}
body:not(.dark-mode) .navbar ul li a:hover {
    color: #FFC107;
}

/* บังคับการแสดงผลโลโก้ */
body:not(.dark-mode) .logo-black { display: block !important; }
body:not(.dark-mode) .logo-white { display: none !important; }

body.dark-mode .logo-black { display: none !important; }
body.dark-mode .logo-white { display: block !important; }
/* --- จบ Logic สลับโลโก้ --- */


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

/* --- ปุ่มสลับธีม (Theme Switch) --- */
.theme-switch {
    position: relative;
    display: inline-block;
    width: 50px; 
    height: 26px;
    margin-left: 25px; 
}
.theme-switch input { display: none; }
.slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: #ccc; 
    transition: .4s;
    border-radius: 34px;
}
.slider:before {
    position: absolute;
    content: "";
    height: 18px; 
    width: 18px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}
input:checked + .slider {
    background-color: #FFC107; /* สีทองเมื่อเปิด */
}
input:checked + .slider:before {
    transform: translateX(24px); 
}

/* --- กล่อง Login (Light Mode) --- */
.login-container {
    background: #ffffff; /* พื้นหลังขาว */
    border: 1px solid #e5e7eb;
    padding: 10px 50px 50px;
    border-radius: 18px; 
    box-shadow: 0 10px 40px rgba(0,0,0,0.1); /* เงานุ่มๆ */
    width: 420px; 
    max-width: 90%;
    text-align: center;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

/* เส้นตกแต่งด้านบน */
.login-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(90deg, transparent, #FFC107, transparent);
    animation: border-animate 2s linear infinite;
}
@keyframes border-animate {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

h2 {
    margin-bottom: 30px;
    color: #1A1A2E; /* หัวข้อสีเข้ม */
    font-weight: 700;
    font-size: 32px;
    letter-spacing: 1px;
    border-bottom: 2px solid rgba(0, 0, 0, 0.1);
    padding-bottom: 10px;
    transition: color 0.3s ease, border-color 0.3s ease;
}

/* --- Input (Light Mode) --- */
input[type="text"], input[type="password"] {
    width: 100%;
    padding: 15px 20px 15px 50px; /* เว้นที่ให้ไอคอน */
    margin: 10px 0 20px;
    background: #f9fafb; 
    border: 1px solid #d1d5db;
    color: #333;
    border-radius: 8px;
    font-size: 16px;
    transition: all 0.3s ease;
    font-weight: 400;
}
input::placeholder {
    color: #aaa;
    opacity: 0.8;
    font-weight: 300;
}
input:focus {
    border-color: #FFC107;
    outline: none;
    box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.2);
    background: #fff;
}

/* --- ปุ่ม Submit --- */
button {
    background: #FFC107;
    color: #1A1A2E;
    padding: 15px 0;
    width: 100%;
    border: none;
    border-radius: 8px;
    font-size: 18px;
    font-weight: 700;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
    letter-spacing: 0.5px;
    margin-top: 10px;
}
button:hover {
    background: #FFD740;
    transform: translateY(-2px);
    box-shadow: 0 8px 15px rgba(255, 193, 7, 0.3);
}

/* --- ไอคอน --- */
.input-icon {
    position: relative;
}
.input-icon svg {
    position: absolute;
    top: 50%;
    left: 18px;
    transform: translateY(-50%);
    fill: #FFC107;
    width: 20px;
    height: 20px;
    z-index: 2;
}

.register-link {
    margin-top: 25px;
    font-size: 15px;
    color: #666;
    transition: color 0.3s ease;
}
.register-link a {
    color: #FFC107;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.3s ease;
}
.register-link a:hover {
    color: #DAA520;
    text-decoration: underline;
}
.error-message {
    color: #ff3366; 
    background: rgba(255, 51, 102, 0.1);
    border: 1px solid #ff3366;
    padding: 10px;
    border-radius: 5px;
    margin-top: -10px;
    margin-bottom: 20px;
    font-size: 0.95em;
    font-weight: 500;
}


/* ▼▼▼ --- Dark Mode Styles (ธีมดำ-ทอง) --- ▼▼▼ */
body.dark-mode {
    /* พื้นหลังไล่เฉดดำ-น้ำเงินเข้ม */
    background: linear-gradient(-45deg, #1A1A2E, #283049, #1a1a2e, #0f0f1a);
    background-size: 400% 400%;
    animation: gradientShift 15s ease infinite;
    color: #fff;
}

/* Keyframes พื้นหลังเฉพาะ Dark Mode */
@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* เมื่อ Dark Mode Navbar กลับมาเข้ม */
body.dark-mode .navbar {
    background: rgba(26, 26, 46, 0.95);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}
body.dark-mode .navbar ul li a {
    color: #e0e0e0;
}
body.dark-mode .navbar ul li a:hover {
    color: #ffc107;
}

body.dark-mode .login-container {
    background: rgba(30, 30, 45, 0.8); /* Glassmorphism สีดำ */
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 25px 50px rgba(0,0,0,0.5);
}

body.dark-mode h2 {
    color: #fff;
    border-bottom-color: rgba(255, 255, 255, 0.1);
}

body.dark-mode input[type="text"], 
body.dark-mode input[type="password"] {
    background: rgba(255, 255, 255, 0.05); 
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #fff;
}

body.dark-mode input:focus {
    border-color: #FFC107;
    background: rgba(255, 193, 7, 0.05);
    box-shadow: 0 0 12px rgba(255, 193, 7, 0.3);
}

body.dark-mode .register-link {
    color: #B0B0B0;
}
body.dark-mode .register-link a:hover {
    color: #FFD740;
}

/* Responsive */
@media (max-width: 768px) {
    .navbar { padding: 0 15px; }
    .navbar ul li { margin-left: 15px; }
    .theme-switch { margin-left: 15px; }
    .login-container { width: 90%; padding: 30px 25px 40px; }
}
/* --- CSS สำหรับโลโก้ในกล่อง Login --- */
.form-logo {
    display: flex;
    justify-content: center; /* จัดกึ่งกลาง */
    margin-bottom: 15px;     /* เว้นระยะห่างจากหัวข้อ "เข้าสู่ระบบ" */
}

.login-container img {
    width: 250px; /* ✅ กำหนดความกว้างแทน (แนะนำ 180px - 250px ลองปรับดูครับ) */
    height: auto;        /* ✅ สำคัญ! ปล่อยความสูงให้ออโต้ ภาพจะไม่บี้แบน */
    
    margin-bottom: 0px;
    display: block;
    margin-left: auto;
    margin-right: auto;
    object-fit: contain;
}
</style>
</head>
<body>

<script>
    // ตรวจสอบ Theme ตั้งแต่โหลดหน้า เพื่อกันหน้าจอกระพริบ
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
            <?php if (!isset($_SESSION['user_id'])) : ?>
                <li><a href="register.php" class="login-btn">สมัครสมาชิก</a></li>
            <?php endif; ?>
        </ul>

        <label class="theme-switch" for="theme-toggle">
            <input type="checkbox" id="theme-toggle">
            <span class="slider"></span>
        </label>

    </div>
</nav>

<div class="login-container">
    <div class="form-logo">
        <img src="image/Elite Logo black.png" class="logo-black" alt="Elite Logo">
        <img src="image/Elite Logo white.png" class="logo-white" alt="Elite Logo">
    </div>
    <h2>เข้าสู่ระบบ</h2>
    
    <?php if ($error): ?>
        <div class="error-message">⚠️ <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post" action="login_process.php" autocomplete="off">
        <div class="input-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
            <input type="text" name="username" placeholder="ชื่อผู้ใช้" required autofocus />
        </div>
        <div class="input-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
            </svg>
            <input type="password" name="password" placeholder="รหัสผ่าน" required />
        </div>
        <button type="submit">เข้าสู่ระบบ</button>
        
        <p class="register-link">
            ยังไม่มีบัญชี?
            <a href="register.php">สมัครสมาชิกที่นี่</a>
        </p>
    </form>
</div>

<script>
    // --- Logic สำหรับ Theme Toggle ---
    const themeToggle = document.getElementById('theme-toggle');
    
    // เช็คสถานะเริ่มต้น
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