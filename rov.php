<?php
// --- ✨ ต้องมีบรรทัดนี้เสมอเพื่อให้ระบบ Session ทำงาน ---
session_start();

// --- (ข้อมูล ROV เหมือนเดิม) ---
$current_game_data = [
    'id' => 'rov',
    'name' => 'ROV',
    'image' => 'image/idSb4U3QE9_1759416194311.png', 
    'description' => 'บริการเติมคูปอง ROV นี้สำหรับผู้เล่น Garena RoV: Mobile MOBA ในประเทศไทยเท่านั้น'
];
$current_price_list = [
    ['amount' => '12 คูปอง', 'price' => 9, 'tag' => null],
    ['amount' => '24 คูปอง', 'price' => 20, 'tag' => null],
    ['amount' => '60 คูปอง', 'price' => 49, 'tag' => null],
    ['amount' => '110 คูปอง', 'price' => 88, 'tag' => null],
    ['amount' => '185 คูปอง', 'price' => 145, 'tag' => null],
    ['amount' => '370 คูปอง', 'price' => 285, 'tag' => null],
    ['amount' => '620 คูปอง', 'price' => 475, 'tag' => null],
    ['amount' => '1,240 คูปอง', 'price' => 950, 'tag' => null],
    ['amount' => '3,720 คูปอง', 'price' => 2850, 'tag' => 'ราคาสุดคุ้ม'],
    ['amount' => '6,200 คูปอง', 'price' => 4750, 'tag' => 'ราคาสุดคุ้ม'],
    ['amount' => '12,400 คูปอง', 'price' => 9500, 'tag' => 'ราคาสุดคุ้ม'],
];
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>💎 เติมเงิน ROV - ntyztermgame</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet" />
    
<style>
    /* --- 🎨 [ปรับปรุง] ธีมหลัก (Light Mode: ขาว-ทอง) --- */
    body {
        font-family: 'Sarabun', sans-serif;
        background-color: #f4f7f6; /* (สีพื้นหลังขาวนวล) */
        color: #333; /* (สีตัวอักษรดำ) */
        margin: 0;
        padding: 0;
        padding-top: 70px;
        min-height: 100vh;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    /* --- Navbar (ใช้ดีไซน์เดิม เพราะสวยทั้ง 2 ธีม) --- */
    .navbar {
        position: fixed; top: 0; left: 0; width: 100%; height: 70px;
        background: rgba(17, 24, 39, 0.8); 
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px); 
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        display: flex; justify-content: space-between; align-items: center;
        padding: 0 10px; 
        color: white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2); z-index: 1000; box-sizing: border-box;
    }
    
    .navbar .logo {
        font-size: 22px; 
        font-weight: 700; 
        color: #fff;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 12px; 
    }
    .navbar .logo img {
        height: 66px; 
        width: auto;
        padding: -4px; 
    }

    .navbar-links { display: flex; align-items: center; }
    .navbar ul { list-style: none; display: flex; align-items: center; margin: 0; padding: 0; }
    .navbar ul li { margin-left: 25px; }
    .navbar ul li a { color: white; text-decoration: none; font-weight: 500; transition: color 0.3s ease; font-size: 16px; }
    .navbar ul li a:hover { color: #DAA520; } /* (สีทอง) */
    
    .login-btn, .logout-btn { background-color: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); padding: 8px 18px; border-radius: 20px; transition: background-color 0.3s ease, border-color 0.3s ease; font-weight: 500; }
    .login-btn:hover, .logout-btn:hover { background-color: rgba(255, 255, 255, 0.2); border-color: rgba(255, 255, 255, 0.4); color: #fff !important; }

    /* --- 🎨 [ปรับปรุง] CSS สำหรับปุ่มสลับธีม --- */
    .theme-switch {
        position: relative;
        display: inline-block;
        width: 50px; 
        height: 26px;
        margin-left: 20px; 
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
        background-color: #a970ff; /* (สีม่วงตอนเปิด) */
    }
    input:checked + .slider:before {
        transform: translateX(24px); 
    }


    /* --- Layout หลัก 2 Columns --- */
    .topup-wrapper {
        max-width: 1300px;
        margin: 30px auto;
        padding: 0 20px;
    }

    /* --- 🎨 [ปรับปรุง] ธีม ขาว-ทอง (Default) --- */

    .game-header-banner {
        background-color: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 30px;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }
    .game-icon img {
        width: 90px;
        height: 90px;
        border-radius: 12px;
    }
    .game-title h1 {
        color: #111827;
        font-size: 24px;
        margin: 0 0 5px 0;
        font-weight: 700;
        transition: color 0.3s ease;
    }
    .game-title p { margin: 0; font-size: 16px; color: #6b7280; transition: color 0.3s ease; }
    .game-title p.note { font-size: 14px; color: #6b7280; margin-top: 10px; }

    .main-content-grid { display: flex; gap: 30px; }
    .package-grid-container { flex: 2.5; }
    
    .package-grid-container h2,
    .sidebar-box h2 {
        font-size: 20px;
        font-weight: 600;
        color: #111827;
        margin: 0 0 20px 0;
        display: flex;
        align-items: center;
        transition: color 0.3s ease;
    }
    
    .price-grid-items {
        display: grid;
        grid-template-columns: repeat(2, 1fr); 
        gap: 15px;
    }
    
    .price-card {
        background: #ffffff;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 18px 22px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }
    .price-card:hover {
        border-color: #DAA520; /* (สีทอง Goldenrod) */
        transform: translateY(-3px);
    }
    .price-card.selected {
        border-color: #B8860B; /* (สีทองเข้ม DarkGoldenrod) */
        background-color: #fffaf0; /* (สีครีม FloralWhite) */
        box-shadow: 0 0 15px rgba(218, 165, 32, 0.5);
        transform: translateY(-3px);
    }
    
    .price-card .package-info { display: flex; align-items: center; gap: 12px; }
    .price-card .coupon-icon { width: 36px; height: 36px; object-fit: contain; }
    
    .price-card .amount {
        font-weight: 600;
        color: #333;
        font-size: 17px;
        transition: color 0.3s ease;
    }
    .price-card .price {
        font-weight: 700;
        color: #DAA520; /* (สีทอง) */
        font-size: 17px;
    }
    .price-card .tag {
        position: absolute; top: 0; left: 0;
        background: #e11d48; color: white;
        padding: 2px 10px; font-size: 10px; font-weight: 600;
        border-bottom-right-radius: 8px;
    }

    .order-sidebar { flex: 1.5; }
    
    .sidebar-box {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .input-group { display: flex; flex-direction: column; }
    .input-group label {
        font-size: 15px; color: #374151;
        margin-bottom: 8px; font-weight: 500;
        transition: color 0.3s ease;
    }
    .input-group input[type="text"] {
        background: #f9fafb;
        border: 1px solid #d1d5db;
        color: #111827;
        border-radius: 8px;
        padding: 12px 15px;
        font-size: 16px;
        font-family: 'Sarabun', sans-serif;
        transition: all 0.3s ease;
    }
    .input-group input[type="text"]:focus {
        border-color: #DAA520;
        box-shadow: 0 0 8px rgba(218, 165, 32, 0.3);
        outline: none;
    }
    .input-group small {
        font-size: 13px; color: #6b7280;
        margin-top: 8px;
    }

    .total-summary {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 20px; padding-top: 10px;
    }
    .total-summary span {
        font-size: 16px; color: #374151;
        transition: color 0.3s ease;
    }
    .total-summary #total-price-display {
        font-size: 28px;
        font-weight: 700;
        color: #DAA520; /* (สีทอง) */
        transition: color 0.3s ease;
    }

    .payment-buttons { display: flex; flex-direction: column; gap: 12px; }
    .btn {
        padding: 14px; border: none; border-radius: 8px;
        cursor: pointer; font-weight: 700; font-size: 16px;
        font-family: 'Sarabun', sans-serif;
        transition: all 0.3s ease;
    }
    .btn:hover { opacity: 0.9; transform: translateY(-2px); }
    
    .btn.btn-primary {
        background-color: #DAA520; /* (สีทอง) */
        color: #fff; /* (ตัวอักษรขาว) */
        box-shadow: 0 5px 15px rgba(218, 165, 32, 0.3);
    }
    .btn.btn-primary:hover {
        box-shadow: 0 8px 20px rgba(218, 165, 32, 0.4);
    }
    .btn.btn-secondary {
        background-color: #e5e7eb;
        color: #374151;
    }
    .btn.btn-secondary:hover {
        background-color: #d1d5db;
    }

    .save-for-future {
        display: flex; align-items: center; gap: 10px;
        margin-top: 20px; font-size: 14px;
        color: #6b7280;
    }
    .save-for-future input {
        accent-color: #DAA520; /* (สีทอง) */
    }


    /* === 💡 2. CSS ที่ [แก้ไข] สำหรับโลโก้และ QR Code === */
    .payment-methods {
        margin-top: 25px; /* เว้นระยะห่างจาก checkbox */
        padding-top: 20px; /* เว้นระยะห่างด้านบน */
        border-top: 1px solid #e5e7eb; /* เส้นคั่นบางๆ */
        display: flex; 
        flex-direction: column; /* เปลี่ยนเป็นเรียงแนวตั้ง */
        align-items: center; /* จัดทุกอย่างให้อยู่กลาง */
        gap: 20px; /* ระยะห่างระหว่าง แถวโลโก้ กับ QR Code */
    }
    
    /* 2.1) แถวโลโก้ด้านบน */
    .payment-logos-grid {
        display: flex;
        flex-wrap: wrap; 
        justify-content: center; 
        gap: 12px; 
    }

    .payment-logos-grid img {
        height: 32px; /* ขนาดโลโก้ (เท่าเดิม) */
        width: auto; 
        border-radius: 4px; 
        object-fit: contain;
    }

    /* 2.2) ส่วน QR Code ด้านล่าง */
    .payment-qr-code {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    .payment-qr-code img {
        width: 150px; /* 👈 กำหนดขนาด QR Code ให้ใหญ่ขึ้น */
        height: 150px;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 5px; /* เพิ่มกรอบสีขาวเล็กน้อย */
        background: #fff; /* พื้นหลัง QR Code เป็นสีขาว */
        object-fit: contain; /* ปรับเป็น contain หาก QR ไม่ใช่สี่เหลี่ยมจัตุรัส */
    }

    .payment-qr-code span {
        font-size: 14px;
        font-weight: 500;
        color: #555; /* สีตัวอักษร "สแกนเพื่อชำระเงิน" */
    }
    /* === สิ้นสุด CSS ที่แก้ไข === */


    /* --- Footer (ใช้ดีไซน์เดิม) --- */
    .footer {
        background-color: #111827;
        color: #9ca3af;
        text-align: center;
        padding: 25px 20px;
        margin-top: 100px;
        border-top: 1px solid #374151;
    }
    
    /* ======================================================= */
    /* --- 🎨 [ปรับปรุง] ธีม ม่วง-ชมพู (Dark Mode) --- */
    /* ======================================================= */

    body.dark-mode {
        background: radial-gradient(ellipse at top, #3a3a50, #1a1a2e);
        color: #d1d5db;
    }

    body.dark-mode .navbar ul li a:hover { color: #a970ff; }
    
    body.dark-mode .game-header-banner {
        background: rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    body.dark-mode .game-title h1 { color: #fff; }
    body.dark-mode .game-title p { color: #9ca3af; }

    body.dark-mode .package-grid-container h2,
    body.dark-mode .sidebar-box h2 {
        color: #fff;
    }
    
    body.dark-mode .price-card {
        background: rgba(0, 0, 0, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.1);
    }
    body.dark-mode .price-card:hover {
        border-color: #a970ff; 
    }
    body.dark-mode .price-card.selected {
        border-color: #ff4aa1;
        background-color: rgba(255, 74, 161, 0.1);
        box-shadow: 0 0 20px rgba(255, 74, 161, 0.6);
    }
    
    body.dark-mode .price-card .amount {
        color: #fff;
    }
    body.dark-mode .price-card .price {
        color: #a970ff; 
    }

    body.dark-mode .sidebar-box {
        background: rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    body.dark-mode .input-group label { color: #d1d5db; }
    body.dark-mode .input-group input[type="text"] {
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #fff;
    }
    body.dark-mode .input-group input[type="text"]:focus {
        border-color: #a970ff;
        box-shadow: 0 0 10px rgba(169, 112, 255, 0.5);
    }
    body.dark-mode .input-group small { color: #9ca3af; }

    body.dark-mode .total-summary span { color: #d1d5db; }
    body.dark-mode .total-summary #total-price-display {
        color: #a970ff;
    }
    
    body.dark-mode .btn.btn-primary {
        background: linear-gradient(90deg, #a970ff, #ff4aa1);
        color: #fff;
        box-shadow: 0 5px 15px rgba(169, 112, 255, 0.3);
    }
    body.dark-mode .btn.btn-primary:hover {
        box-shadow: 0 8px 20px rgba(169, 112, 255, 0.5);
    }
    body.dark-mode .btn.btn-secondary {
        background-color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #fff;
    }
    body.dark-mode .btn.btn-secondary:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }
    body.dark-mode .save-for-future input {
        accent-color: #a970ff; 
    }

    /* === 💡 2. CSS (Dark Mode) ที่ [แก้ไข] === */
    body.dark-mode .payment-methods {
        border-top: 1px solid rgba(255, 255, 255, 0.1); 
    }

    body.dark-mode .payment-qr-code img {
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    body.dark-mode .payment-qr-code span {
        color: #d1d5db;
    }
    /* === สิ้นสุด CSS (Dark Mode) ที่แก้ไข === */


    /* --- Responsive สำหรับมือถือ --- */
    @media (max-width: 992px) {
        .price-grid-items {
             grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 768px) {
        .navbar { padding: 0 10px; }

        .game-header-banner {
            flex-direction: column;
            align-items: flex-start;
        }

        .main-content-grid {
            flex-direction: column; 
        }
        
        .price-grid-items {
            grid-template-columns: repeat(1, 1fr);
        }

        .order-sidebar {
            order: -1; 
        }
    }

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
        <img src="image/logomee-Photoroom.png" alt="Logo Icon"> 
    
    </a> 
    <div class="navbar-links">
        <ul class="main-menu">
            <li><a href="topup.php">หน้าแรก</a></li>
            <li><a href="topup.php">เติมเกม</a></li>
        </ul>
        
        <ul class="user-menu">
        <?php if (isset($_SESSION['user_id'])) : ?>
            <li>
                <a href="profile.php" title="ไปที่หน้าโปรไฟล์ของคุณ"
                   style="color: #ffc107; font-weight: 600;"> 
                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                </a>
            </li>
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


<div class="topup-wrapper">

    <div class="game-header-banner">
        <div class="game-icon">
            <img src="<?php echo htmlspecialchars($current_game_data['image']); ?>" alt="<?php echo htmlspecialchars($current_game_data['name']); ?>" />
        </div>
        <div class="game-title">
            <h1>เติมเงิน <?php echo htmlspecialchars($current_game_data['name']); ?></h1>
            <p>🇹🇭 Thailand</p>
            <p class="note"><?php echo htmlspecialchars($current_game_data['description']); ?></p>
        </div>
    </div>

    <div class="main-content-grid">
        
        <div class="package-grid-container">
            <h2>เลือกจำนวนที่ต้องการเติม</h2>
            <div class="price-grid-items">
                
                <?php foreach ($current_price_list as $package) : ?>
                    <div class="price-card" 
                         onclick="selectPackage(this, <?php echo $package['price']; ?>)"
                         data-amount="<?php echo htmlspecialchars($package['amount']); ?>">
                        
                        <?php if ($package['tag']) : ?>
                            <div class="tag"><?php echo htmlspecialchars($package['tag']); ?></div>
                        <?php endif; ?>
                        
                        <div class="package-info">
                            <img src="image/copong.png" alt="คูปอง" class="coupon-icon">
                            <span class="amount"><?php echo htmlspecialchars($package['amount']); ?></span>
                        </div>
                        
                        <span class="price">฿<?php echo htmlspecialchars($package['price']); ?></span>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
        
        <div class="order-sidebar">
            
            <div class="sidebar-box">
                <h2>กรอกข้อมูลผู้ใช้</h2>
                <div class="input-group">
                    <label for="player-id">OpenID</label>
                    <input type="text" id="player-id" placeholder="กรุณากรอก Player ID (OpenID)">
                    <small>E.g. 27863125986705458</small>
                </div>
            </div>
            
            <div class="sidebar-box">
                <h2>สรุปทั้งหมด</h2>
                <div class="total-summary">
                    <span>ราคารวม:</span>
                    <span id="total-price-display">฿0.00</span>
                </div>
                <div class="payment-buttons">
                    <button class="btn btn-primary" id="buy-btn">ชำระเงิน</button>
                    <button class="btn btn-secondary">ชำระด้วย PayPal</button>
                </div>
                <div class="save-for-future">
                    <input type="checkbox" id="save-future">
                    <label for="save-future">บันทึกข้อมูลไว้ใช้ครั้งถัดไป</label>
                </div>
                
                <div class="payment-methods">

                    <div class="payment-logos-grid">
                        <img src="image/PromptPay-logo-Photoroom.png" alt="PromptPay">
                        <img src="image/truremoudfv.png" alt="TrueMoney Wallet">
                        <img src="image/กสิกร.png" alt="Kasikorn Bank">
                        <img src="image/ไทยพา.jpg" alt="SCB Bank"> 
                    </div>
                    
                    <div class="payment-qr-code">
                        <img src="image/Qr me.jpg" alt="Scan QR Code for Payment">
                        <span>สแกนเพื่อชำระเงิน</span>
                    </div>

                </div>
                </div>

        </div>
    </div>
</div>


<div class="footer">
    <p>&copy; <?php echo date('Y'); ?> ntyztermgame. สงวนลิขสิทธิ์.</p>
</div>


<script>
    // --- Logic การเลือก Package (เหมือนเดิม) ---
    const totalPriceDisplay = document.getElementById('total-price-display');

    function selectPackage(cardElement, price) {
        const currentSelected = document.querySelector('.price-card.selected');
        if (currentSelected) {
            currentSelected.classList.remove('selected');
        }
        cardElement.classList.add('selected');
        
        totalPriceDisplay.textContent = '฿' + price.toFixed(2);
    }

    // --- Logic ปุ่มยืนยันการสั่งซื้อ (เหมือนเดิม) ---
    const buyButton = document.getElementById('buy-btn');
    
    buyButton.addEventListener('click', function() {
        const playerIdInput = document.getElementById('player-id');
        const playerId = playerIdInput.value.trim();
        
        const selectedPackage = document.querySelector('.price-card.selected');
        
        if (playerId === "") {
            alert('กรุณากรอก Player ID (OpenID) ของคุณ');
            playerIdInput.focus();
            return;
        }
        
        if (!selectedPackage) {
            alert('กรุณาเลือกแพ็กเกจที่ต้องการเติม');
            return;
        }
        
        const amount = selectedPackage.getAttribute('data-amount');
        const price = totalPriceDisplay.textContent; 
        
        alert(
            `--- สรุปการสั่งซื้อ (ตัวอย่าง) ---\n\n` +
            `Player ID: ${playerId}\n` +
            `แพ็กเกจ: ${amount}\n` +
            `ราคารวม: ${price}\n\n` +
            `(ในระบบจริง ส่วนนี้จะพาคุณไปหน้าชำระเงิน)`
        );
    });

    // --- 🎨 [ปรับปรุง] เพิ่ม Logic สำหรับ Theme Toggle ---
    const themeToggle = document.getElementById('theme-toggle');
    
    // 1. ตั้งค่าปุ่มสวิตช์ให้ตรงกับธีมตอนโหลดหน้า
    if (document.body.classList.contains('dark-mode')) {
        themeToggle.checked = true;
    }

    // 2. เพิ่ม Listener ให้ปุ่ม
    themeToggle.addEventListener('change', function() {
        if (this.checked) {
            // ถ้าติ๊กถูก (เปิด) -> Dark Mode (ม่วง)
            document.body.classList.add('dark-mode');
            localStorage.setItem('theme', 'dark'); // บันทึกไว้ ตามที่วงไว้
        } else {
            // ถ้าติ๊กออก (ปิด) -> Light Mode (ทอง)
            document.body.classList.remove('dark-mode');
            localStorage.setItem('theme', 'light'); // บันทึกไว้
        }
    });

</script>

</body>
</html>