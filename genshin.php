<?php
session_start();
require_once 'db.php'; 

// --- ข้อมูล Genshin Impact ---
$current_game_data = [
    'id' => 'genshin',
    'name' => 'Genshin Impact',
    'image' => 'image/gent.png', 
    'description' => 'เติม Genesis Crystals สะดวกรวดเร็ว เพียงใช้ UID และ Server'
];

// --- เรทราคา Genshin (Genesis Crystals) ---
$current_price_list = [
    ['amount' => '60 Crystals', 'price' => 35, 'tag' => null],
    ['amount' => '300 + 30 Crystals', 'price' => 179, 'tag' => null],
    ['amount' => '980 + 110 Crystals', 'price' => 549, 'tag' => 'คุ้มค่า'],
    ['amount' => '1,980 + 260 Crystals', 'price' => 1100, 'tag' => null],
    ['amount' => '3,280 + 600 Crystals', 'price' => 1800, 'tag' => 'ยอดนิยม'],
    ['amount' => '6,480 + 1,600 Crystals', 'price' => 3700, 'tag' => 'Super Bonus'],
    ['amount' => 'พรแห่งดวงจันทร์ (Blessing)', 'price' => 179, 'tag' => 'Monthly'],
];
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>💎 เติมเงิน Genshin Impact - Elite Topup</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet" />
    
    <style>
        /* (ก๊อปปี้ CSS ชุดเดียวกับ freefire.php มาใส่ตรงนี้ได้เลยครับ หรือจะแยกไฟล์ style.css ก็ดีครับ) */
        /* เพื่อความกระชับ ผมขอละ CSS ซ้ำไว้ ให้อ้างอิงจากไฟล์ก่อนหน้าได้เลยครับ */
        /* หรือถ้าคุณแยกไฟล์ style.css แล้ว ให้เปลี่ยนเป็น: <link rel="stylesheet" href="style.css"> */
        
        body { font-family: 'Sarabun', sans-serif; background-color: #f4f7f6; color: #333; margin: 0; padding-top: 70px; min-height: 100vh; transition: 0.3s; }
        .topup-wrapper { max-width: 1300px; margin: 30px auto; padding: 0 20px; }
        .game-header-banner { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; display: flex; gap: 20px; margin-bottom: 30px; }
        .game-icon img { width: 90px; height: 90px; border-radius: 12px; object-fit: cover; }
        .main-content-grid { display: flex; gap: 30px; }
        .package-grid-container { flex: 2.5; }
        .price-grid-items { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; }
        .price-card { background: #fff; border: 2px solid #e5e7eb; border-radius: 8px; padding: 18px; cursor: pointer; display: flex; justify-content: space-between; position: relative; overflow: hidden; transition: 0.2s; }
        .price-card:hover, .price-card.selected { border-color: #DAA520; transform: translateY(-3px); }
        .price-card.selected { background-color: #fffaf0; box-shadow: 0 0 15px rgba(218, 165, 32, 0.5); border-color: #B8860B; }
        .price-card .tag { position: absolute; top: 0; left: 0; background: #e11d48; color: white; padding: 2px 10px; font-size: 10px; border-bottom-right-radius: 8px; }
        .package-info { display: flex; align-items: center; gap: 10px; font-weight: 600; }
        .coupon-icon { width: 30px; }
        .price { font-weight: 700; color: #DAA520; }
        .order-sidebar { flex: 1.5; }
        .sidebar-box { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; margin-bottom: 20px; }
        .input-group label { display: block; margin-bottom: 8px; }
        .input-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; }
        .btn { width: 100%; padding: 12px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; margin-top: 10px; }
        .btn-primary { background: #DAA520; color: #fff; }
        
        /* Dark Mode */
        body.dark-mode { background: #1a1a2e; color: #e0e0e0; }
        body.dark-mode .game-header-banner, body.dark-mode .sidebar-box, body.dark-mode .price-card { background: #283049; border-color: #374151; }
        body.dark-mode .price-card:hover { border-color: #a970ff; }
        body.dark-mode .price-card.selected { border-color: #ff4aa1; background: rgba(255, 74, 161, 0.1); }
        body.dark-mode .input-group input { background: #374151; border-color: #4b5563; color: #fff; }
        
        @media (max-width: 768px) { .main-content-grid { flex-direction: column; } .price-grid-items { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="topup-wrapper">
    <div class="game-header-banner">
        <div class="game-icon"><img src="<?php echo $current_game_data['image']; ?>"></div>
        <div class="game-title">
            <h1 style="margin:0;">เติมเงิน <?php echo $current_game_data['name']; ?></h1>
            <p style="margin:5px 0;">🌏 Server: Asia / Global</p>
            <p style="font-size:14px; color:#888;"><?php echo $current_game_data['description']; ?></p>
        </div>
    </div>

    <div class="main-content-grid">
        <div class="package-grid-container">
            <h2>เลือกแพ็กเกจ</h2>
            <div class="price-grid-items">
                <?php foreach ($current_price_list as $package) : ?>
                    <div class="price-card" onclick="selectPackage(this, <?php echo $package['price']; ?>)" data-amount="<?php echo $package['amount']; ?>">
                        <?php if ($package['tag']) : ?><div class="tag"><?php echo $package['tag']; ?></div><?php endif; ?>
                        <div class="package-info">
                            <img src="https://upload-os-bbs.hoyolab.com/upload/2021/03/04/76022329/2954947089bc88aa2791e6d97f724a33_4860332575673432474.png" class="coupon-icon">
                            <span><?php echo $package['amount']; ?></span>
                        </div>
                        <span class="price">฿<?php echo number_format($package['price']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="order-sidebar">
            <div class="sidebar-box">
                <h2>ข้อมูลตัวละคร</h2>
                <div class="input-group">
                    <label>UID</label>
                    <input type="text" id="player-id" placeholder="กรอก UID (เช่น 800xxxxx)">
                </div>
                <div class="input-group" style="margin-top:15px;">
                    <label>Server</label>
                    <select id="server-select" style="width:100%; padding:10px; border-radius:8px;">
                        <option value="asia">Asia</option>
                        <option value="america">America</option>
                        <option value="europe">Europe</option>
                        <option value="tw_hk_mo">TW, HK, MO</option>
                    </select>
                </div>
            </div>
            
            <div class="sidebar-box">
                <h2>สรุปยอด</h2>
                <div style="display:flex; justify-content:space-between; margin-bottom:15px;">
                    <span>ราคารวม:</span>
                    <span id="total-price-display" style="font-size:24px; font-weight:bold; color:#DAA520;">฿0.00</span>
                </div>
                <button class="btn btn-primary" id="buy-btn">ชำระเงิน</button>
            </div>
        </div>
    </div>
</div>

<script>
    const totalPriceDisplay = document.getElementById('total-price-display');
    function selectPackage(card, price) {
        document.querySelectorAll('.price-card').forEach(el => el.classList.remove('selected'));
        card.classList.add('selected');
        totalPriceDisplay.textContent = '฿' + price.toFixed(2);
    }
    
    // Theme Logic
    const themeToggle = document.getElementById('theme-toggle');
    if (localStorage.getItem('theme') === 'dark') document.body.classList.add('dark-mode');
    if (themeToggle && document.body.classList.contains('dark-mode')) themeToggle.checked = true;
    if (themeToggle) themeToggle.addEventListener('change', function() {
        if (this.checked) { document.body.classList.add('dark-mode'); localStorage.setItem('theme', 'dark'); }
        else { document.body.classList.remove('dark-mode'); localStorage.setItem('theme', 'light'); }
    });
</script>
</body>
</html>