<?php
session_start();
// เรียกใช้ไฟล์เชื่อมต่อ Database (เผื่ออนาคตดึงราคาจาก DB)
require_once 'db.php'; 

// --- ข้อมูล ROV (Hardcode ไว้ก่อน หรือจะดึงจาก DB ก็ได้) ---
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
    <title>💎 เติมเงิน ROV - Elite Topup</title>
    
    <style>
    /* --- CSS เฉพาะของหน้าเติมเกม --- */
    body {
        font-family: 'Sarabun', sans-serif;
        background-color: #f4f7f6; color: #333; margin: 0; padding-top: 70px; min-height: 100vh;
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    
    /* Layout */
    .topup-wrapper { max-width: 1300px; margin: 30px auto; padding: 0 20px; }

    /* Game Header */
    .game-header-banner {
        background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px;
        display: flex; align-items: center; gap: 20px; margin-bottom: 30px;
    }
    .game-icon img { width: 90px; height: 90px; border-radius: 12px; }
    .game-title h1 { color: #111827; font-size: 24px; margin: 0 0 5px 0; font-weight: 700; }
    .game-title p { margin: 0; font-size: 16px; color: #6b7280; }
    .game-title p.note { font-size: 14px; color: #6b7280; margin-top: 10px; }

    /* Grid Layout */
    .main-content-grid { display: flex; gap: 30px; }
    .package-grid-container { flex: 2.5; }
    .order-sidebar { flex: 1.5; }

    /* Headings */
    h2 { font-size: 20px; font-weight: 600; color: #111827; margin: 0 0 20px 0; }

    /* Package Cards */
    .price-grid-items { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; }
    .price-card {
        background: #ffffff; border: 2px solid #e5e7eb; border-radius: 8px; padding: 18px 22px;
        cursor: pointer; display: flex; justify-content: space-between; align-items: center;
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

    /* Sidebar Form */
    .sidebar-box {
        background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; margin-bottom: 20px;
    }
    .input-group { display: flex; flex-direction: column; }
    .input-group label { font-size: 15px; color: #374151; margin-bottom: 8px; font-weight: 500; }
    .input-group input[type="text"] {
        background: #f9fafb; border: 1px solid #d1d5db; color: #111827; border-radius: 8px;
        padding: 12px 15px; font-size: 16px; font-family: 'Sarabun', sans-serif; transition: all 0.3s ease;
    }
    .input-group input[type="text"]:focus { border-color: #DAA520; box-shadow: 0 0 8px rgba(218, 165, 32, 0.3); outline: none; }
    .input-group small { font-size: 13px; color: #6b7280; margin-top: 8px; }

    /* Total & Buttons */
    .total-summary { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-top: 10px; }
    .total-summary span { font-size: 16px; color: #374151; }
    .total-summary #total-price-display { font-size: 28px; font-weight: 700; color: #DAA520; }
    
    .payment-buttons { display: flex; flex-direction: column; gap: 12px; }
    .btn { padding: 14px; border: none; border-radius: 8px; cursor: pointer; font-weight: 700; font-size: 16px; font-family: 'Sarabun', sans-serif; transition: all 0.3s ease; width: 100%; }
    .btn.btn-primary { background-color: #DAA520; color: #fff; box-shadow: 0 5px 15px rgba(218, 165, 32, 0.3); }
    .btn.btn-primary:hover { box-shadow: 0 8px 20px rgba(218, 165, 32, 0.4); opacity: 0.9; }
    .btn.btn-secondary { background-color: #e5e7eb; color: #374151; }
    .btn.btn-secondary:hover { background-color: #d1d5db; }
    .save-for-future { display: flex; align-items: center; gap: 10px; margin-top: 20px; font-size: 14px; color: #6b7280; }
    .save-for-future input { accent-color: #DAA520; }

    /* Payment Methods */
    .payment-methods { margin-top: 25px; padding-top: 20px; border-top: 1px solid #e5e7eb; display: flex; flex-direction: column; align-items: center; gap: 20px; }
    .payment-logos-grid { display: flex; flex-wrap: wrap; justify-content: center; gap: 12px; }
    .payment-logos-grid img { height: 32px; width: auto; border-radius: 4px; object-fit: contain; }
    .payment-qr-code { display: flex; flex-direction: column; align-items: center; gap: 10px; }
    .payment-qr-code img { width: 150px; height: 150px; border: 1px solid #ddd; border-radius: 8px; padding: 5px; background: #fff; object-fit: contain; }
    .payment-qr-code span { font-size: 14px; font-weight: 500; color: #555; }

    /* Footer */
    .footer { background-color: #111827; color: #9ca3af; text-align: center; padding: 25px 20px; margin-top: 100px; border-top: 1px solid #374151; }

    /* --- Dark Mode Overrides (สำหรับหน้า Content) --- */
    body.dark-mode { background: radial-gradient(ellipse at top, #3a3a50, #1a1a2e); color: #d1d5db; }
    
    body.dark-mode .game-header-banner { background: rgba(0, 0, 0, 0.15); border: 1px solid rgba(255, 255, 255, 0.1); }
    body.dark-mode .game-title h1 { color: #fff; }
    body.dark-mode .game-title p { color: #9ca3af; }
    
    body.dark-mode h2 { color: #fff; }
    
    body.dark-mode .price-card { background: rgba(0, 0, 0, 0.2); border: 2px solid rgba(255, 255, 255, 0.1); }
    body.dark-mode .price-card:hover { border-color: #a970ff; }
    body.dark-mode .price-card.selected { border-color: #ff4aa1; background-color: rgba(255, 74, 161, 0.1); box-shadow: 0 0 20px rgba(255, 74, 161, 0.6); }
    body.dark-mode .price-card .amount { color: #fff; }
    body.dark-mode .price-card .price { color: #a970ff; }
    
    body.dark-mode .sidebar-box { background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.1); }
    body.dark-mode .input-group label { color: #d1d5db; }
    body.dark-mode .input-group input[type="text"] { background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(255, 255, 255, 0.2); color: #fff; }
    body.dark-mode .input-group input[type="text"]:focus { border-color: #a970ff; box-shadow: 0 0 10px rgba(169, 112, 255, 0.5); }
    body.dark-mode .input-group small { color: #9ca3af; }
    
    body.dark-mode .total-summary span { color: #d1d5db; }
    body.dark-mode .total-summary #total-price-display { color: #a970ff; }
    
    body.dark-mode .btn.btn-primary { background: linear-gradient(90deg, #a970ff, #ff4aa1); color: #fff; box-shadow: 0 5px 15px rgba(169, 112, 255, 0.3); }
    body.dark-mode .btn.btn-primary:hover { box-shadow: 0 8px 20px rgba(169, 112, 255, 0.5); }
    body.dark-mode .btn.btn-secondary { background-color: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); color: #fff; }
    body.dark-mode .btn.btn-secondary:hover { background-color: rgba(255, 255, 255, 0.2); }
    body.dark-mode .save-for-future input { accent-color: #a970ff; }
    
    body.dark-mode .payment-methods { border-top: 1px solid rgba(255, 255, 255, 0.1); }
    body.dark-mode .payment-qr-code img { border: 1px solid rgba(255, 255, 255, 0.2); }
    body.dark-mode .payment-qr-code span { color: #d1d5db; }

    /* Responsive */
    @media (max-width: 992px) { .price-grid-items { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 768px) {
        .game-header-banner { flex-direction: column; align-items: flex-start; }
        .main-content-grid { flex-direction: column; }
        .price-grid-items { grid-template-columns: repeat(1, 1fr); }
        .order-sidebar { order: -1; }
    }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

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
    // --- Logic การเลือก Package ---
    const totalPriceDisplay = document.getElementById('total-price-display');

    function selectPackage(cardElement, price) {
        const currentSelected = document.querySelector('.price-card.selected');
        if (currentSelected) {
            currentSelected.classList.remove('selected');
        }
        cardElement.classList.add('selected');
        totalPriceDisplay.textContent = '฿' + price.toFixed(2);
    }

    // --- Logic ปุ่มยืนยันการสั่งซื้อ ---
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
        
        // ตรงนี้เดี๋ยวเราจะเขียน AJAX ส่งข้อมูลไปตัดเงินจริงใน Database กันต่อครับ
        alert(
            `--- สรุปการสั่งซื้อ (Demo) ---\n\n` +
            `Player ID: ${playerId}\n` +
            `แพ็กเกจ: ${amount}\n` +
            `ราคารวม: ${price}\n`
        );
    });

    // --- Theme Toggle Logic (ต้องมีไว้เพื่อ Sync กับ Navbar) ---
    const themeToggle = document.getElementById('theme-toggle');
    if (localStorage.getItem('theme') === 'dark') document.body.classList.add('dark-mode');
    if (themeToggle && document.body.classList.contains('dark-mode')) themeToggle.checked = true;

    if(themeToggle){
        themeToggle.addEventListener('change', function() {
            if (this.checked) {
                document.body.classList.add('dark-mode'); localStorage.setItem('theme', 'dark');
            } else {
                document.body.classList.remove('dark-mode'); localStorage.setItem('theme', 'light');
            }
        });
    }
</script>

</body>
</html>