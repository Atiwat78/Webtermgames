<?php
// --- ✨ ต้องมีบรรทัดนี้เสมอเพื่อให้ระบบ Session ทำงาน ---
session_start();

// --- 💡 1. [แก้ไข] ข้อมูลสำหรับหน้า "เติมเหรียญ" ---
$current_game_data = [
    'id' => 'coin',
    'name' => 'เหรียญ (Coins)',
    'image' => 'image/coin.png', // 👈 (ต้องสร้างรูปนี้)
    'description' => 'เติมเหรียญ/เครดิต เพื่อใช้สำหรับบริการต่างๆ ภายในเว็บไซต์'
];

// --- 💡 2. [แก้ไข] รายการราคาสำหรับ "เหรียญ" ---
$current_price_list = [
    ['amount' => '50 เหรียญ', 'price' => 50, 'tag' => null],
    ['amount' => '100 เหรียญ', 'price' => 100, 'tag' => null],
    ['amount' => '300 เหรียญ', 'price' => 300, 'tag' => null],
    ['amount' => '500 เหรียญ', 'price' => 500, 'tag' => 'โบนัส 5%'],
    ['amount' => '1,000 เหรียญ', 'price' => 1000, 'tag' => 'โบนัส 10%'],
    
];
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🪙 เติมเหรียญ/เครดิต - ntyztermgame</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet" />
    
<style>
    /* --- (CSS ทั้งหมดเหมือนเดิม) --- */
    body {
        font-family: 'Sarabun', sans-serif;
        background-color: #f4f7f6;
        color: #333;
        margin: 0;
        padding: 0;
        padding-top: 70px;
        min-height: 100vh;
        transition: background-color 0.3s ease, color 0.3s ease;
    }
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
    .navbar ul li a:hover { color: #DAA520; }
    .login-btn, .logout-btn { background-color: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); padding: 8px 18px; border-radius: 20px; transition: background-color 0.3s ease, border-color 0.3s ease; font-weight: 500; }
    .login-btn:hover, .logout-btn:hover { background-color: rgba(255, 255, 255, 0.2); border-color: rgba(255, 255, 255, 0.4); color: #fff !important; }
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
        background-color: #a970ff;
    }
    input:checked + .slider:before {
        transform: translateX(24px); 
    }
    .topup-wrapper {
        max-width: 1300px;
        margin: 30px auto;
        padding: 0 20px;
    }
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
        border-color: #DAA520;
        transform: translateY(-3px);
    }
    .price-card.selected {
        border-color: #B8860B;
        background-color: #fffaf0;
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
        color: #DAA520;
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
    .user-info-box {
        background: #f9fafb;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 12px 15px;
        font-size: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #374151;
    }
    .user-info-box strong {
        color: #DAA520;
        font-weight: 700;
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
        color: #DAA520;
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
        background-color: #DAA520;
        color: #fff;
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
    .btn.btn-disabled {
        background-color: #e5e7eb;
        color: #9ca3af;
        cursor: not-allowed;
        opacity: 0.7;
    }
    .save-for-future {
        display: flex; align-items: center; gap: 10px;
        margin-top: 20px; font-size: 14px;
        color: #6b7280;
    }
    .save-for-future input {
        accent-color: #DAA520;
    }
    
    /* --- 🎨 [ปรับปรุง] CSS สำหรับระบบเลือกช่องทางชำระเงิน --- */
    .payment-methods {
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
        display: flex; 
        flex-direction: column;
        gap: 20px;
    }
    /* (ตัวเลือกโลโก้) */
    .payment-selector {
        display: flex;
        flex-wrap: wrap; 
        justify-content: center; 
        gap: 12px; 
    }
    .payment-selector input[type="radio"] {
        display: none; /* ซ่อนปุ่มติ๊กวงกลม */
    }
    .payment-selector label {
        display: block;
        padding: 8px;
        border: 2px solid #e5e7eb; /* ขอบปกติ */
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .payment-selector label img {
        height: 32px;
        width: auto; 
        display: block;
    }
    .payment-selector input[type="radio"]:checked + label {
        border-color: #DAA520; /* ขอบสีทองเมื่อถูกเลือก */
        box-shadow: 0 0 10px rgba(218, 165, 32, 0.5);
    }
    
    /* (ส่วนแสดงรายละเอียด) */
    .payment-detail-pane {
        display: none; /* ซ่อนทั้งหมดไว้ก่อน */
        flex-direction: column;
        align-items: center;
        gap: 8px;
        padding: 15px;
        background: #f9fafb;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }
    .payment-detail-pane.active {
        display: flex; /* แสดงเฉพาะอันที่ Active */
    }
    .payment-detail-pane img { /* (QR Code) */
        width: 150px;
        height: 150px;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 5px;
        background: #fff;
    }
    .payment-detail-pane span {
        font-size: 14px;
        font-weight: 500;
        color: #555;
    }
    .payment-detail-pane strong {
        font-size: 16px;
        color: #DAA520;
    }
    /* --- จบส่วน CSS [ปรับปรุง] --- */

    .footer {
        background-color: #111827;
        color: #9ca3af;
        text-align: center;
        padding: 25px 20px;
        margin-top: 100px;
        border-top: 1px solid #374151;
    }
    
    /* --- (CSS Dark Mode ทั้งหมดเหมือนเดิม) --- */
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
    body.dark-mode .user-info-box {
         background: rgba(0, 0, 0, 0.3);
         border: 1px solid rgba(255, 255, 255, 0.2);
         color: #d1d5db;
    }
    body.dark-mode .user-info-box strong {
        color: #a970ff;
    }
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
    body.dark-mode .btn.btn-disabled {
        background-color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #6b7280;
    }
    body.dark-mode .save-for-future input {
        accent-color: #a970ff; 
    }
    
    /* --- 🎨 [ปรับปรุง] CSS Dark Mode สำหรับระบบเลือกช่องทางชำระเงิน --- */
    body.dark-mode .payment-methods {
        border-top: 1px solid rgba(255, 255, 255, 0.1); 
    }
    body.dark-mode .payment-selector label {
        border: 2px solid rgba(255, 255, 255, 0.2);
    }
    body.dark-mode .payment-selector input[type="radio"]:checked + label {
        border-color: #a970ff; /* ขอบสีม่วงเมื่อถูกเลือก (Dark Mode) */
        box-shadow: 0 0 10px rgba(169, 112, 255, 0.5);
    }
    body.dark-mode .payment-detail-pane {
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    body.dark-mode .payment-detail-pane img { /* (QR Code) */
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    body.dark-mode .payment-detail-pane span {
        color: #d1d5db;
    }
    body.dark-mode .payment-detail-pane strong {
        color: #a970ff;
    }
    /* --- จบส่วน CSS Dark Mode [ปรับปรุง] --- */


    /* --- (Responsive CSS เหมือนเดิม) --- */
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
            <li><a href="termcoin.php" style="color: #DAA520;">เติมเหรียญ</a></li>
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
            <h1>เติม<?php echo htmlspecialchars($current_game_data['name']); ?></h1>
            <p>🇹🇭 <?php echo htmlspecialchars($_SESSION['username'] ?? 'N/A'); ?></p>
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
                            <img src="image/coin.png" alt="เหรียญ" class="coupon-icon">
                            <span class="amount"><?php echo htmlspecialchars($package['amount']); ?></span>
                        </div>
                        
                        <span class="price">฿<?php echo htmlspecialchars($package['price']); ?></span>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
        
        <div class="order-sidebar">
            
            <div class="sidebar-box">
                <h2>ข้อมูลผู้ใช้</h2>
                <div class="input-group">

                <?php if (isset($_SESSION['user_id'])) : ?>
                    <label for="player-id">เติมเหรียญสำหรับ:</label>
                    <div class="user-info-box">
                        <span>Username:</span>
                        <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                    </div>
                    <input type="hidden" id="user-id-hidden" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">

                <?php else : ?>
                    <label for="player-id">กรุณาเข้าสู่ระบบ</label>
                    <div class="user-info-box">
                        <span>คุณยังไม่ได้เข้าสู่ระบบ</span>
                    </div>
                    <small style="margin-top: 10px; color: #e11d48;">คุณต้องเข้าสู่ระบบก่อน จึงจะสามารถเติมเหรียญได้</small>
                    
                <?php endif; ?>

                </div>
            </div>
            
            <div class="sidebar-box">
                <h2>สรุปทั้งหมด</h2>
                <div class="total-summary">
                    <span>ราคารวม:</span>
                    <span id="total-price-display">฿0.00</span>
                </div>

                <div class="payment-buttons">

                <?php if (isset($_SESSION['user_id'])) : ?>
                    <button class="btn btn-primary" id="buy-btn">ชำระเงิน</button>
                    <button class="btn btn-secondary">ชำระด้วย PayPal</button>
                <?php else : ?>
                    <button class="btn btn-primary btn-disabled" disabled>ชำระเงิน (ต้อง Login ก่อน)</button>
                    <button class="btn btn-secondary" onclick="window.location.href='login.php'">ไปหน้าเข้าสู่ระบบ</button>
                <?php endif; ?>

                </div>

                <div class="payment-methods">

                    <div class="payment-selector">
                        
                        <input type="radio" name="payment_method" id="pay-promptpay" data-target="promptpay-details" checked>
                        <label for="pay-promptpay" title="PromptPay">
                            <img src="image/PromptPay-logo-Photoroom.png" alt="PromptPay">
                        </label>

                        <input type="radio" name="payment_method" id="pay-truemoney" data-target="truemoney-details">
                        <label for="pay-truemoney" title="TrueMoney Wallet">
                            <img src="image/truremoudfv.png" alt="TrueMoney Wallet">
                        </label>

                        <input type="radio" name="payment_method" id="pay-kbank" data-target="kbank-details">
                        <label for="pay-kbank" title="Kasikorn Bank">
                            <img src="image/กสิกร.png" alt="Kasikorn Bank">
                        </label>
                        
                        <input type="radio" name="payment_method" id="pay-scb" data-target="scb-details">
                        <label for="pay-scb" title="SCB Bank">
                            <img src="image/ไทยพา.jpg" alt="SCB Bank"> 
                        </label>
                    </div>

                    <div class="payment-details-container">

                        <div class="payment-detail-pane active" id="promptpay-details">
                            <span>PromptPay</span>
                            <strong>เบอร์: 065-345-5229</strong>
                            <img src="image/Qr me.jpg" alt="Scan QR Code for Payment">
                            <span>สแกน QR Code เพื่อชำระเงิน</span>
                            <span>ชื่อบัญชี: อธิวัฒน์ เปรียบจัตุรัส</span>
                        </div>

                        <div class="payment-detail-pane" id="truemoney-details">
                            <span>TrueMoney Wallet</span>
                            <strong>เบอร์: 065-345-5229</strong>
                            <img src="image/Qr me true.jpg" alt="Scan QR Code for Payment">
                            <span>สแกน QR Code เพื่อชำระเงิน</span>
                            <span>ชื่อบัญชี: อธิวัฒน์ เปรียบจัตุรัส</span>
                        </div>

                        <div class="payment-detail-pane" id="kbank-details">
                            <span>ธนาคารกสิกรไทย</span>
                            <strong>เลขบัญชี: 123-4-56789-0</strong>
                            <span>ชื่อบัญชี: อธิวัฒน์ เปรียบจัตุรัส</span>
                        </div>

                        <div class="payment-detail-pane" id="scb-details">
                            <span>ธนาคารไทยพาณิชย์</span>
                            <strong>เลขบัญชี: 800-261379-2</strong>
                            <span>ชื่อบัญชี: อธิวัฒน์ เปรียบจัตุรัส</span>
                        </div>

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
    if (buyButton) { 
        buyButton.addEventListener('click', function() {
            const userIdInput = document.getElementById('user-id-hidden');
            if (!userIdInput || userIdInput.value === "") {
                alert('เกิดข้อผิดพลาด: ไม่พบข้อมูลผู้ใช้ กรุณาลอง Login ใหม่');
                return;
            }
            const userId = userIdInput.value;
            const username = "<?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>";
            const selectedPackage = document.querySelector('.price-card.selected');
            if (!selectedPackage) {
                alert('กรุณาเลือกแพ็กเกจเหรียญที่ต้องการเติม');
                return;
            }
            const amount = selectedPackage.getAttribute('data-amount');
            const price = totalPriceDisplay.textContent; 
            
            alert(
                `--- สรุปการสั่งซื้อ (ตัวอย่าง) ---\n\n` +
                `เติมเหรียญสำหรับ: ${username} (ID: ${userId})\n` +
                `แพ็กเกจ: ${amount}\n` +
                `ราคารวม: ${price}\n\n` +
                `(ในระบบจริง ส่วนนี้จะพาคุณไปหน้าชำระเงิน)`
            );
        });
    }

    // --- Logic สำหรับ Theme Toggle (เหมือนเดิม) ---
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

    // --- 🎨 [ปรับปรุง] JavaScript สำหรับสลับช่องทางชำระเงิน ---
    const paymentRadios = document.querySelectorAll('.payment-selector input[type="radio"]');
    const paymentPanes = document.querySelectorAll('.payment-detail-pane');

    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            // 1. เอา ID เป้าหมายจาก data-target (เช่น "promptpay-details")
            const targetId = this.dataset.target;
            
            // 2. ซ่อนทุก Pane โดยการลบคลาส .active
            paymentPanes.forEach(pane => {
                pane.classList.remove('active');
            });
            
            // 3. แสดง Pane ที่ตรงกับเป้าหมาย โดยการเพิ่มคลาส .active
            const targetPane = document.getElementById(targetId);
            if (targetPane) {
                targetPane.classList.add('active');
            }
        });
    });

    // (สั่งให้ปุ่มที่ติ๊กไว้ (PromptPay) ทำงานครั้งแรกตอนโหลดหน้า)
    document.querySelector('.payment-selector input[type="radio"]:checked').dispatchEvent(new Event('change'));
    // --- จบส่วน JavaScript [ปรับปรุง] ---

</script>

</body>
</html>