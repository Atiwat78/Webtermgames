<?php
session_start();
require_once 'db.php'; 

// --- ข้อมูลสำหรับหน้า "เติมเหรียญ" ---
$current_game_data = [
    'id' => 'coin',
    'name' => 'เหรียญ (Elite coins)',
    'image' => 'image/coingold.png', 
    'description' => 'เติมเหรียญ/เครดิต เพื่อใช้สำหรับบริการต่างๆ ภายในเว็บไซต์'
];

// --- รายการราคา ---
$current_price_list = [
    ['amount' => '20 เหรียญ', 'price' => 20, 'tag' => 'HOT🔥'],
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
        <title>Elite</title> 
    <link rel="icon" type="image/png" sizes="32x32" href="image/Elite Logo black favni.png">
    <link rel="icon" type="image/png" sizes="16x16" href="image/Elite Logo black favni.png">
    <link rel="apple-touch-icon" sizes="180x180" href="image/Elite Logo black favni.pngg">
    <link rel="icon" type="image/png" sizes="192x192" href="image/Elite Logo black favni.png">

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🪙 เติมเหรียญ/เครดิต - Elite Topup</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet" />
    
<style>
    /* --- CSS หลัก (Navbar CSS ถูกลบออกแล้วเพราะใช้จาก navbar.php) --- */
    body {
        font-family: 'Sarabun', sans-serif;
        background-color: #f4f7f6;
        color: #333;
        margin: 0; padding: 0; padding-top: 70px;
        min-height: 100vh;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    /* Wrapper */
    .topup-wrapper { max-width: 1300px; margin: 30px auto; padding: 0 20px; }
    
    /* Banner */
    .game-header-banner {
        background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px;
        padding: 24px; display: flex; align-items: center; gap: 20px; margin-bottom: 30px;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }
    .game-icon img { width: 90px; height: 90px; border-radius: 12px; }
    .game-title h1 { color: #111827; font-size: 24px; margin: 0 0 5px 0; font-weight: 700; }
    .game-title p { margin: 0; font-size: 16px; color: #6b7280; }
    .game-title p.note { font-size: 14px; color: #6b7280; margin-top: 10px; }
    
    /* Grid Layout */
    .main-content-grid { display: flex; gap: 30px; }
    .package-grid-container { flex: 2.5; }
    .package-grid-container h2, .sidebar-box h2 {
        font-size: 20px; font-weight: 600; color: #111827; margin: 0 0 20px 0;
    }
    .price-grid-items { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; }
    
    /* Card Style */
    .price-card {
        background: #ffffff; border: 2px solid #e5e7eb; border-radius: 8px;
        padding: 18px 22px; cursor: pointer; display: flex; justify-content: space-between; align-items: center;
        transition: all 0.2s ease; position: relative; overflow: hidden;
    }
    .price-card:hover { border-color: #DAA520; transform: translateY(-3px); }
    .price-card.selected {
        border-color: #B8860B; background-color: #fffaf0;
        box-shadow: 0 0 15px rgba(218, 165, 32, 0.5); transform: translateY(-3px);
    }
    .price-card .package-info { display: flex; align-items: center; gap: 12px; }
    .price-card .coupon-icon { width: 36px; height: 36px; object-fit: contain; }
    .price-card .amount { font-weight: 600; color: #333; font-size: 17px; }
    .price-card .price { font-weight: 700; color: #DAA520; font-size: 17px; }
    .price-card .tag {
        position: absolute; top: 0; left: 0; background: #e11d48; color: white;
        padding: 2px 10px; font-size: 10px; font-weight: 600; border-bottom-right-radius: 8px;
    }

    /* Sidebar */
    .order-sidebar { flex: 1.5; }
    .sidebar-box {
        background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px;
        padding: 24px; margin-bottom: 20px;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }
    
    /* สไตล์กล่องราคา */
    .amount-box-preview {
        font-size: 2rem; color: #059669; font-weight: 800;
        border: 2px dashed #059669; padding: 15px; border-radius: 10px;
        background-color: #ecfdf5; text-align: center; margin-bottom: 10px;
        transition: all 0.3s ease;
    }
    .amount-box-preview.empty {
        color: #9ca3af; border-color: #d1d5db; background-color: #f3f4f6; font-size: 1.5rem;
    }
    .discount-hint { font-size: 13px; color: #059669; text-align: center; display: block; margin-bottom: 15px; }

    /* Inputs & User Info */
    .input-group { display: flex; flex-direction: column; }
    .input-group label { font-size: 15px; color: #374151; margin-bottom: 8px; font-weight: 500; }
    .user-info-box {
        background: #f9fafb; border: 1px solid #d1d5db; border-radius: 8px;
        padding: 12px 15px; font-size: 16px; display: flex; justify-content: space-between;
        align-items: center; color: #374151;
    }
    .user-info-box strong { color: #DAA520; font-weight: 700; }

    /* Payment Buttons */
    .payment-buttons { display: flex; flex-direction: column; gap: 12px; }
    .btn {
        padding: 14px; border: none; border-radius: 8px; cursor: pointer;
        font-weight: 700; font-size: 16px; font-family: 'Sarabun', sans-serif; transition: all 0.3s ease; width: 100%;
    }
    .btn:hover { opacity: 0.9; transform: translateY(-2px); }
    .btn-primary { background-color: #DAA520; color: #fff; box-shadow: 0 5px 15px rgba(218, 165, 32, 0.3); }
    .btn-primary:hover { box-shadow: 0 8px 20px rgba(218, 165, 32, 0.4); }
    .btn-secondary { background-color: #e5e7eb; color: #374151; }
    .btn-disabled { background-color: #e5e7eb; color: #9ca3af; cursor: not-allowed; opacity: 0.7; }

    /* Payment Selector */
    .payment-methods { margin-top: 25px; padding-top: 20px; border-top: 1px solid #e5e7eb; display: flex; flex-direction: column; gap: 20px; }
    .payment-selector { display: flex; flex-wrap: wrap; justify-content: center; gap: 12px; }
    .payment-selector input { display: none; }
    .payment-selector label {
        display: block; padding: 8px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer; transition: all 0.2s ease;
    }
    .payment-selector label img { height: 32px; width: auto; display: block; }
    .payment-selector input:checked + label { border-color: #DAA520; box-shadow: 0 0 10px rgba(218, 165, 32, 0.5); }
    
    .payment-detail-pane {
        display: none; flex-direction: column; align-items: center; gap: 8px; padding: 15px;
        background: #f9fafb; border-radius: 8px; border: 1px solid #e5e7eb;
    }
    .payment-detail-pane.active { display: flex; }
    .payment-detail-pane img { width: 150px; height: 150px; border: 1px solid #ddd; border-radius: 8px; padding: 5px; background: #fff; }
    .payment-detail-pane span { font-size: 14px; font-weight: 500; color: #555; }

    /* Footer */
    .footer { background-color: #111827; color: #9ca3af; text-align: center; padding: 25px 20px; margin-top: 100px; border-top: 1px solid #374151; }

    /* --- Dark Mode Overrides --- */
    body.dark-mode { background: radial-gradient(ellipse at top, #3a3a50, #1a1a2e); color: #d1d5db; }
    body.dark-mode .sidebar-box, body.dark-mode .game-header-banner, body.dark-mode .price-card {
        background: rgba(0, 0, 0, 0.2); border-color: rgba(255, 255, 255, 0.1);
    }
    body.dark-mode h2, body.dark-mode h1, body.dark-mode .package-grid-container h2 { color: #fff; }
    body.dark-mode .price-card:hover { border-color: #a970ff; }
    body.dark-mode .price-card.selected { border-color: #ff4aa1; background-color: rgba(255, 74, 161, 0.1); }
    body.dark-mode .price-card .amount { color: #fff; }
    body.dark-mode .price-card .price { color: #a970ff; }
    body.dark-mode .user-info-box { background: rgba(0, 0, 0, 0.3); border-color: rgba(255, 255, 255, 0.2); color: #d1d5db; }
    body.dark-mode .user-info-box strong { color: #a970ff; }
    body.dark-mode .btn-primary { background: linear-gradient(90deg, #a970ff, #ff4aa1); box-shadow: 0 5px 15px rgba(169, 112, 255, 0.3); }
    body.dark-mode .amount-box-preview {
        background-color: rgba(5, 150, 105, 0.1); border-color: #34d399; color: #34d399;
    }
    body.dark-mode .amount-box-preview.empty {
        background-color: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: #6b7280;
    }
    body.dark-mode .discount-hint { color: #34d399; }

    @media (max-width: 768px) {
        .main-content-grid { flex-direction: column; }
        .price-grid-items { grid-template-columns: 1fr; }
        .order-sidebar { order: -1; }
    }
</style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="topup-wrapper">
    <div class="game-header-banner">
        <div class="game-icon"><img src="<?php echo htmlspecialchars($current_game_data['image']); ?>" alt="Coin" /></div>
        <div class="game-title">
            <h1>เติม<?php echo htmlspecialchars($current_game_data['name']); ?></h1>
            <p>🇹🇭 <?php echo htmlspecialchars($_SESSION['username'] ?? 'Guest'); ?></p>
            <p class="note"><?php echo htmlspecialchars($current_game_data['description']); ?></p>
        </div>
    </div>

    <div class="main-content-grid">
        <div class="package-grid-container">
            <h2>เลือกจำนวนที่ต้องการเติม</h2>
            <div class="price-grid-items">
                <?php foreach ($current_price_list as $package) : ?>
                    <div class="price-card" onclick="selectPackage(this, <?php echo $package['price']; ?>)">
                        <?php if ($package['tag']) : ?><div class="tag"><?php echo htmlspecialchars($package['tag']); ?></div><?php endif; ?>
                        <div class="package-info">
                            <img src="image/coingold.png" class="coupon-icon">
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
                    <label>เติมเหรียญสำหรับ:</label>
                    <div class="user-info-box">
                        <span>Username:</span><strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                    </div>
                    <input type="hidden" id="user-id-hidden" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">
                <?php else : ?>
                    <label>สถานะ:</label>
                    <div class="user-info-box"><span>คุณยังไม่ได้เข้าสู่ระบบ</span></div>
                    <small style="color: #e11d48;">ต้องเข้าสู่ระบบก่อนจึงจะเติมเหรียญได้</small>
                <?php endif; ?>
                </div>
            </div>
            
            <div class="sidebar-box">
                <h2>สรุปยอดชำระ</h2>
                
                <div id="amount-box-display" class="amount-box-preview empty">
                    ฿0.00
                </div>
                <span id="discount-hint-text" class="discount-hint" style="display: none;">
                    (เตรียมรับส่วนลดพิเศษในหน้าถัดไป...)
                </span>

                <div class="payment-buttons">
                <?php if (isset($_SESSION['user_id'])) : ?>
                    <button class="btn btn-primary" id="buy-btn">ชำระเงิน</button>
                    
                <?php else : ?>
                    <button class="btn btn-primary btn-disabled" disabled>ชำระเงิน (ต้อง Login ก่อน)</button>
                    <button class="btn btn-secondary" onclick="window.location.href='login.php'">ไปหน้าเข้าสู่ระบบ</button>
                <?php endif; ?>
                </div>

                <div class="payment-methods">
                    <div class="payment-selector">
                        <input type="radio" name="payment_method" id="pay-promptpay" value="promptpay" data-target="promptpay-details" checked>
                        <label for="pay-promptpay"><img src="image/PromptPay-logo-Photoroom.png"></label>
                        
                        <input type="radio" name="payment_method" id="pay-truemoney" value="truemoney" data-target="truemoney-details">
                        <label for="pay-truemoney"><img src="image/truremoudfv.png"></label>
                        
                        <input type="radio" name="payment_method" id="pay-kbank" value="kbank" data-target="kbank-details">
                        <label for="pay-kbank"><img src="image/กสิกร.png"></label>
                        
                        <input type="radio" name="payment_method" id="pay-scb" value="scb" data-target="scb-details">
                        <label for="pay-scb"><img src="image/ไทยพา.jpg"></label>
                    </div>
                    
                    <div class="payment-details-container">
                        <div class="payment-detail-pane active" id="promptpay-details">
                            </div>
                        <div class="payment-detail-pane" id="truemoney-details"></div>
                        <div class="payment-detail-pane" id="kbank-details"></div>
                        <div class="payment-detail-pane" id="scb-details"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="footer"><p>&copy; <?php echo date('Y'); ?> ntyztermgame. สงวนลิขสิทธิ์.</p></div>

<script>
    let currentSelectedPrice = 0;
    const amountBoxDisplay = document.getElementById('amount-box-display');
    const discountHint = document.getElementById('discount-hint-text');

    function selectPackage(cardElement, price) {
        const currentSelected = document.querySelector('.price-card.selected');
        if (currentSelected) currentSelected.classList.remove('selected');
        cardElement.classList.add('selected');
        
        currentSelectedPrice = price;
        
        amountBoxDisplay.textContent = '฿' + price.toFixed(2);
        amountBoxDisplay.classList.remove('empty');
        discountHint.style.display = 'block';
    }

    // Submit Logic (POST)
    const buyButton = document.getElementById('buy-btn');
    if (buyButton) { 
        buyButton.addEventListener('click', function() {
            const userIdInput = document.getElementById('user-id-hidden');
            if (!userIdInput || userIdInput.value === "") {
                alert('กรุณาเข้าสู่ระบบก่อนทำรายการ');
                window.location.href = 'login.php';
                return;
            }
            if (currentSelectedPrice === 0) {
                alert('กรุณาเลือกแพ็กเกจเหรียญที่ต้องการเติม');
                return;
            }
            
            // 1. หาว่าเลือกธนาคารอะไรอยู่
            const selectedBankInput = document.querySelector('input[name="payment_method"]:checked');
            const selectedBankValue = selectedBankInput ? selectedBankInput.value : 'promptpay';

            // 2. สร้าง Form ส่งค่าไป confirm_topup.php
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'confirm_topup.php';
            
            // ส่งราคา
            const amountField = document.createElement('input');
            amountField.type = 'hidden';
            amountField.name = 'amount';
            amountField.value = currentSelectedPrice;
            form.appendChild(amountField);

            // ส่งธนาคารที่เลือก
            const bankField = document.createElement('input');
            bankField.type = 'hidden';
            bankField.name = 'bank'; 
            bankField.value = selectedBankValue;
            form.appendChild(bankField);

            document.body.appendChild(form);
            form.submit();
        });
    }

    // Theme Toggle Logic
    const themeToggle = document.getElementById('theme-toggle');
    // เช็คค่าเริ่มต้น
    if (localStorage.getItem('theme') === 'dark') document.body.classList.add('dark-mode');
    
    // ติ๊กปุ่มถ้า Navbar โหลดมาแล้วและมีปุ่ม
    if (themeToggle && document.body.classList.contains('dark-mode')) themeToggle.checked = true;

    if (themeToggle) {
        themeToggle.addEventListener('change', function() {
            if (this.checked) {
                document.body.classList.add('dark-mode'); localStorage.setItem('theme', 'dark');
            } else {
                document.body.classList.remove('dark-mode'); localStorage.setItem('theme', 'light');
            }
        });
    }

    // Payment Selector Logic
    const paymentRadios = document.querySelectorAll('.payment-selector input[type="radio"]');
    const paymentPanes = document.querySelectorAll('.payment-detail-pane');
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const targetId = this.dataset.target;
            paymentPanes.forEach(pane => pane.classList.remove('active'));
            const targetPane = document.getElementById(targetId);
            if (targetPane) targetPane.classList.add('active');
        });
    });
</script>

</body>
</html>