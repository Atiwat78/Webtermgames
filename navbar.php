<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php'; // เรียก DB ตรงนี้เพื่อให้ทุกหน้าที่ใช้ Navbar มีการเชื่อมต่อ

// --- Logic ดึงยอดเงิน (ใส่ตรงนี้ เพื่อให้โชว์ทุกหน้า) ---
$navbar_user_points = 0;
if (isset($_SESSION['user_id'])) {
    $sql_nav_balance = "SELECT coins FROM users WHERE id = ?";
    if ($stmt_nav = $conn->prepare($sql_nav_balance)) {
        $stmt_nav->bind_param("i", $_SESSION['user_id']);
        $stmt_nav->execute();
        $res_nav = $stmt_nav->get_result();
        if ($row_nav = $res_nav->fetch_assoc()) {
            $navbar_user_points = $row_nav['coins'];
        }
        $stmt_nav->close();
    }
}
?>

<style>
    /* --- CSS Navbar (ก๊อปมาจาก topup.php เป๊ะๆ) --- */
    
    /* Google Fonts */
    @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;600;700&display=swap');

    .navbar {
        position: fixed; 
        top: 0; 
        left: 0; 
        width: 100%; 
        height: 70px;
        
        /* พื้นหลัง Light Mode */
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(12px); 
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        
        display: flex; 
        justify-content: space-between; 
        align-items: center;
        
        /* ✅ ระยะห่างขอบซ้าย-ขวา (ตามหน้า topup.php) */
        padding: 0 40px; 
        box-sizing: border-box;
        
        z-index: 1000;
        transition: background-color 0.3s ease, border-color 0.3s ease;
        font-family: 'Sarabun', sans-serif;
    }

    /* การจัดการโลโก้ */
    .navbar .logo {
        font-size: 22px; font-weight: 700; 
        text-decoration: none;
        display: flex; align-items: center; gap: 12px;
        margin-left: -30px;
    }
    .navbar .logo img { height: 250px; width: auto; padding: 0; }

    /* Logic สลับ Logo */
    .logo-black { display: block; } .logo-white { display: none; }
    body.dark-mode .logo-black { display: none; }
    body.dark-mode .logo-white { display: block; }

    /* เมนู */
    .navbar-links { display: flex; align-items: center; }
    .navbar ul { list-style: none; display: flex; align-items: center; margin: 0; padding: 0; }
    .navbar ul li { margin-left: 25px; display: flex; align-items: center; }
    .navbar ul li a {
        color: #333; text-decoration: none; font-weight: 500; font-size: 16px;
        transition: color 0.3s ease;
    }
    .navbar ul li a:hover { color: #ffc107; }

    /* ปุ่ม Login/Logout */
    .login-btn, .logout-btn {
        background-color: #27c791ff;
        color: #333 !important;
        border: none; padding: 8px 18px; border-radius: 20px;
        transition: transform 0.2s ease; font-weight: 600; text-decoration: none;
    }
    .login-btn:hover, .logout-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(255, 193, 7, 0.4);
    }

    /* Switch Theme */
    .theme-switch {
        position: relative; display: inline-block;
        width: 50px; height: 26px;
        margin-left: 0px; 
        vertical-align: middle;
    }
    .theme-switch input { display: none; }
    .slider {
        position: absolute; cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #ccc; transition: .4s; border-radius: 34px;
    }
    .slider:before {
        position: absolute; content: "";
        height: 18px; width: 18px; left: 4px; bottom: 4px;
        background-color: white; transition: .4s; border-radius: 50%;
    }
    input:checked + .slider { background-color: #ffc107; }
    input:checked + .slider:before { transform: translateX(24px); }

    /* --- Dark Mode Styles สำหรับ Navbar --- */
    body.dark-mode .navbar {
        background: rgba(26, 26, 46, 0.95);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    body.dark-mode .navbar ul li a { color: #e0e0e0; }
    body.dark-mode .navbar ul li a:hover { color: #ffc107; }

    /* Responsive มือถือ */
    @media (max-width: 768px) {
        .navbar { padding: 0 15px; }
        .navbar ul li { margin-left: 15px; }
        .navbar ul li a { font-size: 14px; }
    }
</style>

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
            <li>
                <a href="profile.php" style="color: #ffc107; font-weight: 600; display: flex; align-items: center; gap: 8px;"> 
                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                    <span style="font-weight: 400; color: #28a745; background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 12px; font-size: 0.9em;">
                        🪙 <?php echo number_format($navbar_user_points); ?> เหรียญ
                    </span>
                </a>
            </li>

            <li>
                <label class="theme-switch" for="theme-toggle">
                    <input type="checkbox" id="theme-toggle">
                    <span class="slider"></span>
                </label>
            </li>

            <li><a href="logout.php" class="logout-btn">ออกจากระบบ</a></li>

        <?php else : ?>
            <li>
                <label class="theme-switch" for="theme-toggle">
                    <input type="checkbox" id="theme-toggle">
                    <span class="slider"></span>
                </label>
            </li>
            <li><a href="login.php" class="login-btn">เข้าสู่ระบบ</a></li>
        <?php endif; ?>
        </ul>
    </div>
</nav>