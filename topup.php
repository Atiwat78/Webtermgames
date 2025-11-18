<?php
// --- ✨ ต้องมีบรรทัดนี้เสมอเพื่อให้ระบบ Session ทำงาน ---
session_start();

// --- ข้อมูลเกม (เหมือนเดิม) ---
$games = [
    [
        'id' => 'freefire',
        'name' => 'Free Fire',
        'image' => 'image/free-fire-seeklogo.png', 
        'background_image' => 'image/Black and Teal Modern Gym & Fitness YouTube Banner.jpg',
    ],
    [
        'id' => 'rov',
        'name' => 'ROV',
        'image' => 'image/idSb4U3QE9_1759416194311.png',
        'background_image' => 'image/เเวน03.jpg',
    ],
    [
        'id' => 'genshin',
        'name' => 'Genshin Impact',
        'image' => 'image/gent.png',
        'background_image' => 'image/เกนชิน05.jpg',
    ],
    [
        'id' => 'valorant',
        'name' => 'Valorant',
        'image' => 'https://img.icons8.com/color/96/000000/valorant.png',
        'background_image' => 'image/สสหสหสดกสห.jpg',
    ],
    [
        'id' => 'roblox',
        'name' => 'Roblox',
        'image' => 'image/roblox.png',
        'background_image' => 'image/robloxxjpg-1.jpg',
    ],
];
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8" />
    <title>💎 เติมเกม - คุ้มค่า ปลอดภัย</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet" />
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<style>
    /* --- General Body --- */
    body {
        font-family: 'Sarabun', sans-serif;
        background-color: #f4f7f6;
        margin: 0; padding: 0;
        color: #333;
        padding-top: 70px;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    /* ▼▼▼ --- Navbar (แก้ไขแล้ว) --- ▼▼▼ */
    .navbar {
        position: fixed; 
        top: 0; 
        left: 0; 
        width: 100%; 
        height: 70px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(12px); 
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        display: flex; 
        justify-content: space-between; 
        align-items: center;
        
        /* 🎨 [จุดที่ 1] เพิ่มระยะห่างขอบซ้าย-ขวา เป็น 40px (หรือ 50px ถ้าชอบห่างๆ) */
        padding: 0 40px; 
        
        /* 🎨 [จุดที่ 2] สำคัญมาก! ต้องใส่ตัวนี้เพื่อไม่ให้จอกว้างเกิน 100% */
        box-sizing: border-box;
        
        z-index: 1000;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    /* การจัดการโลโก้ */
    .navbar .logo {
        font-size: 22px; font-weight: 700; 
        text-decoration: none;
        display: flex; align-items: center; gap: 12px;
        margin-left: -30px;
    }
    .navbar .logo img { height: 250px; width: auto; padding: 0; }

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
        background-color: #ffc107;
        color: #333 !important;
        border: none; padding: 8px 18px; border-radius: 20px;
        transition: transform 0.2s ease; font-weight: 600;
    }
    .login-btn:hover, .logout-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(255, 193, 7, 0.4);
    }

    /* ▼▼▼ --- Toggle Switch (แก้ไข CSS) --- ▼▼▼ */
    .theme-switch {
        position: relative; display: inline-block;
        width: 50px; height: 26px;
        
        /* 🎨 เอา margin-left ออก เพราะ Li จัดการให้แล้ว */
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


    /* ▼▼▼ --- Logic สลับโลโก้ --- ▼▼▼ */
    .logo-black { display: block; }
    .logo-white { display: none; }
    body.dark-mode .logo-black { display: none; }
    body.dark-mode .logo-white { display: block; }


    /* ▼▼▼ --- Dark Mode Styles --- ▼▼▼ */
    body.dark-mode { background-color: #1a1a2e; color: #e0e0e0; }
    body.dark-mode .navbar {
        background: rgba(26, 26, 46, 0.95);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    body.dark-mode .navbar ul li a { color: #e0e0e0; }
    body.dark-mode .navbar ul li a:hover { color: #ffc107; }
    body.dark-mode .game-card {
        background: #283049; color: #fff;
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }
    body.dark-mode .game-card .game-name { color: #ffc107; }
    body.dark-mode .section-title { color: #e0e0e0; }


    /* --- Hero & Game Slider CSS --- */
    .hero-slider { width: 100%; height: 300px; position: relative; }
    .hero-slider .swiper-slide { background-size: cover; background-position: center; }
    .hero-slider .slide-content { display: flex; flex-direction: column; justify-content: center; align-items: center; width: 100%; height: 100%; text-decoration: none; color: white; background: rgba(0, 0, 0, 0.4); }
    .hero-slider h1 { font-size: 48px; font-weight: 700; margin: 0; text-shadow: 2px 2px 8px rgba(0,0,0,0.7); }
    .hero-slider p { font-size: 22px; margin: 10px 0 25px; text-shadow: 1px 1px 4px rgba(0,0,0,0.7); }
    .hero-slider .cta-button { background-color: #ffc107; color: #333; padding: 12px 30px; border-radius: 50px; font-size: 18px; font-weight: 600; transition: transform 0.2s ease; }
    .hero-slider .cta-button:hover { transform: scale(1.05); }
    .hero-slider .swiper-button-next, .hero-slider .swiper-button-prev { color: #fff; }
    
    .container { max-width: 1100px; margin: 50px auto; padding: 0 20px; }
    .section-title { text-align: center; font-size: 28px; margin-bottom: 30px; color: #5a3577; font-weight: 600; transition: color 0.3s; }
    .game-slider { width: 100%; height: auto; padding: 10px 0 40px; }
    .game-slider .swiper-slide { display: flex; justify-content: center; align-items: center; }
    
    .game-card { 
        width: 95%; height: auto; margin: 0 auto; 
        background: #fff; border-radius: 12px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08); 
        padding: 20px; text-align: center; cursor: pointer; 
        transition: all 0.3s ease; text-decoration: none; color: #333; 
    }
    .game-card:hover { box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12); transform: translateY(-5px); }
    .game-card img { width: 80px; height: 80px; object-fit: contain; margin-bottom: 12px; }
    .game-card .game-name { font-size: 18px; font-weight: 600; margin-bottom: 8px; color: #5a3577; transition: color 0.3s; }

    .footer { background-color: #343a40; color: #ccc; text-align: center; padding: 15px 20px; margin-top: 350px; }
    .footer p { margin: 0; font-size: 14px; }
</style>
</head>
<body>

<script>
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
            <li><a href="">หน้าแรก</a></li>
            <li><a href="termcoin.php">เติมเหรียญ</a></li>
        </ul>
        
        <ul class="user-menu">
        <?php if (isset($_SESSION['user_id'])) : ?>
            <li>
                <a href="profile.php" style="color: #ffc107; font-weight: 600;"> 
                    <?php echo htmlspecialchars($_SESSION['username']); ?>
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


<div class="swiper hero-slider">
    <div class="swiper-wrapper">
        <?php foreach ($games as $game) : ?>
            <?php $position = ($game['id'] === 'freefire') ? 'left center' : 'center'; ?>
            <div class="swiper-slide" style="background-image: url('<?php echo htmlspecialchars($game['background_image']); ?>'); background-position: <?php echo $position; ?>;">
                <a href="<?php echo htmlspecialchars($game['id']); ?>.php" class="slide-content">
                    <h1><?php echo htmlspecialchars($game['name']); ?></h1>
                    <p>บริการเติมเกมชั้นนำ รวดเร็วทันใจ</p>
                    <span class="cta-button">👉 เติมเลย!</span>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
    <div class="swiper-pagination"></div>
</div>

<div class="container">
    <h2 class="section-title">เกมยอดนิยม</h2>
    <div class="swiper game-slider">
        <div class="swiper-wrapper">
            <?php foreach ($games as $game) : ?>
                <div class="swiper-slide">
                    <a href="<?php echo htmlspecialchars($game['id']); ?>.php" class="game-card">
                        <img src="<?php echo htmlspecialchars($game['image']); ?>" alt="<?php echo htmlspecialchars($game['name']); ?>" />
                        <div class="game-name"><?php echo htmlspecialchars($game['name']); ?></div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>
</div>

<div class="footer">
    <p>&copy; <?php echo date('Y'); ?> ntyztermgame. สงวนลิขสิทธิ์.</p>
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

    const heroSwiper = new Swiper('.hero-slider', {
        loop: true,
        autoplay: { delay: 4000, disableOnInteraction: false },
        navigation: { nextEl: '.hero-slider .swiper-button-next', prevEl: '.hero-slider .swiper-button-prev' },
        pagination: { el: '.hero-slider .swiper-pagination', clickable: true },
    });
    const gameSwiper = new Swiper('.game-slider', {
        loop: true,
        spaceBetween: 20,
        slidesPerView: 2,
        breakpoints: {
            768: { slidesPerView: 4 },
            1024: { slidesPerView: 5 }
        },
        navigation: { nextEl: '.game-slider .swiper-button-next', prevEl: '.game-slider .swiper-button-prev' },
    });
</script>

</body>
</html>