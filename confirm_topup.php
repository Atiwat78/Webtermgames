<?php
session_start();
require_once 'db.php'; 

// --- 1. รับค่าและตรวจสอบข้อมูล ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['amount'])) {
    header("Location: termcoin.php"); 
    exit();
}

$base_amount = floatval($_POST['amount']);
$user_id = $_SESSION['user_id'] ?? 0;
$selected_bank = $_POST['bank'] ?? 'promptpay'; 

// --- 2. ตั้งค่าข้อมูลบัญชีธนาคาร ---
$bank_details = [
    'promptpay' => [
        'name' => 'พร้อมเพย์ (PromptPay)',
        'account' => '065-345-5229', // ⚠️ เบอร์ของคุณ
        'owner' => 'อธิวัฒน์ เปรียบจัตุรัส',
        'color' => '#003D6B', 
        'logo_class' => 'fas fa-qrcode'
    ],
    'kbank' => [
        'name' => 'ธนาคารกสิกรไทย (KBANK)',
        'account' => '123-4-56789-0',
        'owner' => 'บจก. เติมเกมออนไลน์',
        'color' => '#138f2d', 
        'logo_class' => 'fas fa-university'
    ],
    'truemoney' => [ 
        'name' => 'TrueMoney Wallet',
        'account' => '065-345-5229', 
        'owner' => 'อธิวัฒน์ เปรียบจัตุรัส',
        'color' => '#f58220', 
        'logo_class' => 'fas fa-wallet'
    ],
    'scb' => [
        'name' => 'ธนาคารไทยพาณิชย์ (SCB)',
        'account' => '987-6-54321-0',
        'owner' => 'ชื่อบัญชี SCB',
        'color' => '#4e2e7f', 
        'logo_class' => 'fas fa-university'
    ]
];

$current_bank = $bank_details[$selected_bank] ?? $bank_details['promptpay'];

// --- 3. คำนวณเศษสตางค์ ---
$random_satang = rand(1, 99) / 100;
$total_amount = $base_amount - $random_satang; 
$total_amount_show = number_format($total_amount, 2);

// --- 4. บันทึกลงฐานข้อมูล (แก้ไขจุดที่ทำให้ Error) ---
// ตัด bank_type ออก และบังคับใส่ status = pending
$sql = "INSERT INTO pending_topups (user_id, amount, status, created_at) VALUES (?, ?, 'pending', NOW())";

$stmt = $conn->prepare($sql);
if ($stmt) {
    // i = integer, d = double/float
    $stmt->bind_param("id", $user_id, $total_amount);
    
    if ($stmt->execute()) {
        $transaction_id = $conn->insert_id; // ✅ สำเร็จ!
    } else {
        die("❌ บันทึกข้อมูลไม่สำเร็จ (Execute Error): " . $stmt->error);
    }
    $stmt->close();
} else {
    die("❌ เชื่อมต่อฐานข้อมูลผิดพลาด (Prepare Error): " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ยืนยันการเติมเงิน - ntyztermgame</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
    /* --- CSS เดิมของคุณ --- */
    body {
        font-family: 'Sarabun', sans-serif;
        background-color: #f4f7f6;
        color: #333;
        margin: 0; padding: 0; padding-top: 70px;
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
        padding: 0 10px; color: white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2); z-index: 1000; box-sizing: border-box;
    }
    .navbar .logo { font-size: 22px; font-weight: 700; color: #fff; text-decoration: none; display: flex; align-items: center; gap: 12px; }
    .navbar .logo img { height: 66px; width: auto; padding: -4px; }
    .navbar-links { display: flex; align-items: center; }
    .navbar ul { list-style: none; display: flex; align-items: center; margin: 0; padding: 0; }
    .navbar ul li { margin-left: 25px; }
    .navbar ul li a { color: white; text-decoration: none; font-weight: 500; transition: color 0.3s ease; font-size: 16px; }
    .navbar ul li a:hover { color: #DAA520; }
    .login-btn, .logout-btn { background-color: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); padding: 8px 18px; border-radius: 20px; transition: background-color 0.3s ease, border-color 0.3s ease; font-weight: 500; }
    .login-btn:hover, .logout-btn:hover { background-color: rgba(255, 255, 255, 0.2); border-color: rgba(255, 255, 255, 0.4); color: #fff !important; }
    
    /* Toggle Switch */
    .theme-switch { position: relative; display: inline-block; width: 50px; height: 26px; margin-left: 20px; }
    .theme-switch input { display: none; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px; }
    .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%; }
    input:checked + .slider { background-color: #a970ff; }
    input:checked + .slider:before { transform: translateX(24px); }

    .topup-wrapper { max-width: 600px; margin: 50px auto; padding: 0 20px; }

    .sidebar-box {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 20px;
        transition: background-color 0.3s ease, border-color 0.3s ease;
        text-align: center;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .amount-box {
        font-size: 2.5rem;
        color: #059669;
        font-weight: 800;
        border: 2px dashed #059669;
        padding: 20px;
        border-radius: 12px;
        background-color: #ecfdf5;
        margin: 20px 0;
    }

    h2 { font-size: 24px; font-weight: 700; color: #111827; margin-bottom: 10px; }
    p { color: #4b5563; font-size: 16px; line-height: 1.6; }
    .small-note { font-size: 14px; color: #6b7280; margin-top: -15px; margin-bottom: 20px; display: block; }

    /* Style ส่วน Bank Info แบบ Dynamic */
    .bank-info {
        background: #f3f4f6;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        border: 1px solid #e5e7eb;
        border-left: 5px solid <?php echo $current_bank['color']; ?>; /* สีขอบซ้ายเปลี่ยนตามธนาคาร */
        text-align: left;
    }
    .bank-header {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        font-weight: 700;
        color: <?php echo $current_bank['color']; ?>;
    }
    .bank-header i { font-size: 24px; margin-right: 10px; }

    .btn { display: block; width: 100%; padding: 14px; border: none; border-radius: 8px; cursor: pointer; font-weight: 700; font-size: 16px; font-family: 'Sarabun', sans-serif; transition: all 0.3s ease; text-decoration: none; box-sizing: border-box; margin-top: 10px; }
    .btn-primary { background-color: #DAA520; color: #fff; box-shadow: 0 5px 15px rgba(218, 165, 32, 0.3); }
    .btn-primary:hover { box-shadow: 0 8px 20px rgba(218, 165, 32, 0.4); transform: translateY(-2px); }
    .btn-secondary { background-color: #fff; color: #4b5563; border: 1px solid #d1d5db; }
    .btn-secondary:hover { background-color: #f9fafb; border-color: #9ca3af; }

    .footer { background-color: #111827; color: #9ca3af; text-align: center; padding: 25px 20px; margin-top: 100px; border-top: 1px solid #374151; }

    /* Dark Mode */
    body.dark-mode { background: radial-gradient(ellipse at top, #3a3a50, #1a1a2e); color: #d1d5db; }
    body.dark-mode .sidebar-box { background: rgba(0, 0, 0, 0.25); border: 1px solid rgba(255, 255, 255, 0.1); }
    body.dark-mode h2 { color: #fff; }
    body.dark-mode p, body.dark-mode .small-note { color: #9ca3af; }
    
    body.dark-mode .bank-info { 
        background: rgba(255, 255, 255, 0.05); 
        border-color: rgba(255, 255, 255, 0.1); 
        border-left-color: <?php echo $current_bank['color']; ?>; 
        color: #d1d5db;
    }
    
    body.dark-mode .btn-primary { background: linear-gradient(90deg, #a970ff, #ff4aa1); box-shadow: 0 5px 15px rgba(169, 112, 255, 0.3); }
    body.dark-mode .btn-secondary { background-color: transparent; border-color: rgba(255, 255, 255, 0.2); color: #d1d5db; }
    body.dark-mode .btn-secondary:hover { background-color: rgba(255, 255, 255, 0.1); }
    body.dark-mode .amount-box { background-color: rgba(5, 150, 105, 0.1); border-color: #34d399; color: #34d399; }
    </style>
</head>
<body>

<script>
    if (localStorage.getItem('theme') === 'dark') { document.body.classList.add('dark-mode'); }
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
            <li><a href="profile.php" style="color: #ffc107; font-weight: 600;"><?php echo htmlspecialchars($_SESSION['username']); ?></a></li>
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
    <div class="sidebar-box">
        <h2>ยืนยันการชำระเงิน (ส่วนลด)</h2>
        <p>กรุณาโอนเงินยอดที่แสดงด้านล่าง<b>ให้ตรงทุกจุดทศนิยม</b></p>
        
        <div class="amount-box">
            ฿<?php echo $total_amount_show; ?>
        </div>
        
        <span class="small-note">
            (ราคาปกติ <?php echo number_format($base_amount); ?> - ส่วนลด <?php echo $random_satang; ?> บาท)
        </span>

        <div class="bank-info" style="text-align: center;">
            <div class="bank-header" style="justify-content: center;">
                <i class="<?php echo $current_bank['logo_class']; ?>"></i>
                <?php echo $current_bank['name']; ?>
            </div>
            
            <?php if ($selected_bank == 'promptpay') : ?>
                <?php 
                    $pp_number = "0653455229"; 
                ?> 
                
                <div style="background: white; padding: 10px; display: inline-block; border-radius: 8px; margin: 15px 0; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <img src="https://promptpay.io/<?php echo $pp_number; ?>/<?php echo $total_amount; ?>" 
                         alt="QR Code" 
                         style="width: 200px; height: 200px; display: block;">
                </div>
                
                <p style="color: #e11d48; font-size: 14px; margin-top: 5px; font-weight: bold;">
                    *สแกนได้เลย ยอดเงินจะขึ้นอัตโนมัติ
                </p>

                <?php elseif ($selected_bank == 'truemoney') : ?> 
                <div style="background: white; padding: 10px; display: inline-block; border-radius: 8px; margin: 15px 0; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <img src="image/Qr me true.jpg" 
                         alt="TrueMoney QR" style="width: 170px; height: auto; display: block;">
                </div>
            <?php endif; ?>

            <div style="margin-top: 15px;">
                <p style="margin: 5px 0; font-size: 18px;">
                    <strong>เลขบัญชี:</strong> 
                    <span style="font-family: monospace; font-size: 20px; letter-spacing: 1px;">
                        <?php echo $current_bank['account']; ?>
                    </span>
                </p>
                <p style="margin: 0;"><strong>ชื่อบัญชี:</strong> <?php echo $current_bank['owner']; ?></p>
            </div>
        </div>
        <form action="check_status.php" method="POST">
            <input type="hidden" name="transaction_id" value="<?php echo $transaction_id; ?>">
            <button type="submit" class="btn btn-primary">
                แจ้งชำระเงินเรียบร้อยแล้ว
            </button>
        </form>
        
        <a href="termcoin.php" class="btn btn-secondary">ยกเลิก / กลับไปเลือกราคา</a>
    </div>
</div>

<div class="footer">
    <p>&copy; <?php echo date('Y'); ?> ntyztermgame. สงวนลิขสิทธิ์.</p>
</div>

<script>
    const themeToggle = document.getElementById('theme-toggle');
    if (document.body.classList.contains('dark-mode')) { themeToggle.checked = true; }
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