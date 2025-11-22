<?php

session_start();
require_once 'db.php'; // เรียกไฟล์เชื่อมต่อฐานข้อมูล

// --- 1. ตรวจสอบ Login และ กำหนดค่าตัวแปร (ต้องทำก่อนเพื่อน!) ---
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// ✅ ประกาศตัวแปร user_id ตรงนี้ ก่อนจะเอาไปใช้ดึงข้อมูล
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// --- 2. ดึงยอดเหรียญล่าสุดจากฐานข้อมูล ---
$my_coins = 0; 
$sql_user = "SELECT coins FROM users WHERE id = ?";
if ($stmt = $conn->prepare($sql_user)) {
    $stmt->bind_param("i", $user_id); // ✅ ตอนนี้ $user_id มีค่าแล้ว ทำงานได้แน่นอน
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $my_coins = $row['coins'];
    }
    $stmt->close();
}

// --- 3. ดึงประวัติการเติมเงิน (จากตาราง pending_topups) ---
$topup_history = [];
$sql_topup = "SELECT * FROM pending_topups WHERE user_id = ? ORDER BY id DESC LIMIT 5"; 

if ($stmt = $conn->prepare($sql_topup)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $topup_history[] = $row;
    }
    $stmt->close();
}

// --- 4. ดึงประวัติการซื้อ (จากตาราง orders เชื่อมกับ packages) ---
$order_history = [];
$sql_order = "SELECT orders.*, packages.name as package_name, packages.price 
              FROM orders 
              LEFT JOIN packages ON orders.package_id = packages.id 
              WHERE orders.user_id = ? 
              ORDER BY orders.id DESC LIMIT 5";

if ($stmt = $conn->prepare($sql_order)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $order_history[] = $row;
    }
    $stmt->close();
}

?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>โปรไฟล์ของฉัน | เติมเกม</title>
<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
/* Reset */
* { box-sizing: border-box; }

/* Main Style */
body {
    font-family: 'Sarabun', sans-serif;
    min-height: 100vh;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: flex-start; /* เปลี่ยนเป็น flex-start เพื่อให้ scroll ได้ถ้าเนื้อหายาว */
    padding-top: 100px;
    padding-bottom: 50px;
    background-color: #f4f7f6;
    color: #333;
    transition: background-color 0.3s ease, color 0.3s ease;
}

/* Navbar (Copy เดิมมา) */
.navbar {
    position: fixed; top: 0; left: 0; width: 100%; height: 70px;
    background: rgba(26, 26, 46, 0.95); backdrop-filter: blur(15px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    display: flex; justify-content: space-between; align-items: center; padding: 0 50px;
    color: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1); z-index: 1000;
}
.navbar .logo img { height: 250px; width: auto; padding: 0; }

/* Logic Logo & Theme (เหมือนเดิม) */
.logo-black { display: none; } .logo-white { display: block; }
body:not(.dark-mode) .logo-black { display: block !important; }
body:not(.dark-mode) .logo-white { display: none !important; }
body.dark-mode .logo-black { display: none !important; }
body.dark-mode .logo-white { display: block !important; }
body:not(.dark-mode) .navbar { background: rgba(255, 255, 255, 0.95); color: #333; }
body:not(.dark-mode) .navbar ul li a { color: #333; }
body.dark-mode {
    background: linear-gradient(-45deg, #1A1A2E, #283049, #1a1a2e, #0f0f1a);
    background-size: 400% 400%; animation: gradientShift 15s ease infinite; color: #fff;
}
@keyframes gradientShift { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
body.dark-mode .navbar { background: rgba(26, 26, 46, 0.95); border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
body.dark-mode .navbar ul li a { color: #e0e0e0; }

/* Navbar Components */
.navbar-links ul { list-style: none; display: flex; align-items: center; margin: 0; padding: 0; }
.navbar-links li { margin-left: 20px; }
.navbar-links a { text-decoration: none; transition: color 0.3s; }
.theme-switch { position: relative; display: inline-block; width: 50px; height: 26px; margin-left: 20px; }
.theme-switch input { display: none; }
.slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px; }
.slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%; }
input:checked + .slider { background-color: #FFC107; }
input:checked + .slider:before { transform: translateX(24px); }
.logout-btn { background: #FFC107; color: #1A1A2E !important; padding: 5px 15px; border-radius: 20px; font-weight: 600; }


/* ▼▼▼ Profile Container ▼▼▼ */
.profile-container {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    padding: 30px 40px 50px;
    border-radius: 18px; 
    box-shadow: 0 10px 40px rgba(0,0,0,0.1); 
    width: 900px; /* กว้างขึ้นเพื่อใส่ 2 ตาราง */
    max-width: 95%;
    text-align: center;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}
.profile-container::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 5px;
    background: linear-gradient(90deg, transparent, #FFC107, transparent);
    animation: border-animate 2s linear infinite;
}
@keyframes border-animate { 0% { transform: translateX(-100%); } 100% { transform: translateX(100%); } }

.form-logo img { height: 60px; width: auto; margin-bottom: 10px; } /* Logo เล็กๆ หัวกระดาษ */
h2 { margin: 10px 0; font-size: 28px; }
.username-display { font-size: 22px; color: #FFC107; font-weight: 600; margin-bottom: 30px; }

/* ▼▼▼ Table Styles ▼▼▼ */
.section-header {
    text-align: left; font-size: 18px; font-weight: 600; margin: 30px 0 15px; 
    border-left: 4px solid #FFC107; padding-left: 10px; display: flex; justify-content: space-between; align-items: center;
}
.table-wrapper { width: 100%; overflow-x: auto; margin-bottom: 20px; }
table { width: 100%; border-collapse: collapse; font-size: 15px; }
th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
th { background-color: #f9f9f9; font-weight: 600; color: #555; white-space: nowrap; }
tr:hover { background-color: rgba(0,0,0,0.02); }

/* Status Badges */
.badge { padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block; }
.badge-success { background: rgba(39, 174, 96, 0.15); color: #27ae60; }
.badge-pending { background: rgba(255, 193, 7, 0.15); color: #f39c12; }
.badge-failed { background: rgba(231, 76, 60, 0.15); color: #e74c3c; }

/* Dark Mode Overrides */
body.dark-mode .profile-container { background: rgba(30, 30, 45, 0.8); border-color: rgba(255,255,255,0.1); }
body.dark-mode h2 { color: #fff; }
body.dark-mode th { background-color: rgba(255,255,255,0.05); color: #ccc; border-bottom-color: rgba(255,255,255,0.1); }
body.dark-mode td { color: #eee; border-bottom-color: rgba(255,255,255,0.1); }
body.dark-mode tr:hover { background-color: rgba(255,255,255,0.02); }
</style>
</head>
<body>

<script>
    if (localStorage.getItem('theme') === 'dark') document.body.classList.add('dark-mode');
</script>

<nav class="navbar">
    <a href="topup.php" class="logo">
        <img src="image/Elite Logo black.png" class="logo-black" alt="Elite Logo">
        <img src="image/Elite Logo white.png" class="logo-white" alt="Elite Logo">
    </a> 
    <div class="navbar-links">
        <ul>
            <li><a href="topup.php">หน้าแรก</a></li>
            <li><a href="topup.php">เติมเกม</a></li>
            <li><a href="profile.php" style="color:#FFC107; font-weight:600;"><?php echo htmlspecialchars($username); ?></a></li>
            <li><a href="logout.php" class="logout-btn">ออกจากระบบ</a></li>
            <li>
                <label class="theme-switch">
                    <input type="checkbox" id="theme-toggle">
                    <span class="slider"></span>
                </label>
            </li>
        </ul>
    </div>
</nav>

<div class="profile-container">
    

    
    <h2>โปรไฟล์ของฉัน</h2>
    <div class="username-display">สวัสดี, <?php echo htmlspecialchars($username); ?></div>

    <div class="balance-box" style="margin-bottom: 40px;">
        <div style="font-size: 16px; opacity: 0.7; margin-bottom: 5px;">ยอดเงินคงเหลือ</div>
        <div style="font-size: 42px; font-weight: 800; color: #FFC107; text-shadow: 0 0 20px rgba(255, 193, 7, 0.3);">
            <?php echo number_format($my_coins); ?> <span style="font-size: 24px;">🪙</span>
        </div>
    </div>

    <div class="section-header">
        <span>💰 ประวัติการเติมเงิน (ล่าสุด)</span>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th width="10%">#</th>
                    <th width="20%">จำนวนเงิน (บาท)</th> <th width="20%">เครดิตที่ได้ (Coins)</th> <th width="20%">สถานะ</th>
                    <th width="30%">วันที่เวลา</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($topup_history)): ?>
                    <tr><td colspan="5" style="text-align:center; padding:20px; color:#999;">ไม่มีประวัติการเติมเงิน</td></tr>
                <?php else: ?>
                    <?php foreach ($topup_history as $row): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            
                            <td style="font-weight:bold;">
                                <?php echo number_format($row['amount'], 2); ?> ฿
                            </td>

                            <td style="color: #FFC107; font-weight:bold;">
                                +<?php echo number_format(ceil($row['amount'])); ?> 🪙
                            </td>

                            <td>
                                <?php 
                                    $st = $row['status'];
                                    if ($st == 'approved' || $st == 'success' || $st == '1') echo '<span class="badge badge-success">สำเร็จ</span>';
                                    else if ($st == 'pending' || $st == '0') echo '<span class="badge badge-pending">รอตรวจสอบ</span>';
                                    else echo '<span class="badge badge-failed">ยกเลิก</span>';
                                ?>
                            </td>
                            <td style="font-size: 14px; color:#888;">
                                <?php echo isset($row['created_at']) ? date('d/m/Y H:i', strtotime($row['created_at'])) : '-'; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="section-header">
        <span>🛒 ประวัติการซื้อสินค้า (ล่าสุด)</span>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>รายการแพ็กเกจ</th>
                    <th>ราคา</th>
                    <th>สถานะ</th>
                    <th>วันที่เวลา</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($order_history)): ?>
                    <tr><td colspan="5" style="text-align:center; padding:20px; color:#999;">ไม่มีประวัติการซื้อ</td></tr>
                <?php else: ?>
                    <?php foreach ($order_history as $row): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['package_name'] ?? 'แพ็กเกจไม่ทราบชื่อ'); ?></td>
                            <td style="color: #e74c3c; font-weight:bold;">-<?php echo number_format($row['price']); ?></td>
                            <td>
                                <?php 
                                    $st = $row['status'];
                                    if ($st == 'completed' || $st == 'success' || $st == '1') echo '<span class="badge badge-success">สำเร็จ</span>';
                                    else if ($st == 'pending' || $st == '0') echo '<span class="badge badge-pending">กำลังดำเนินการ</span>';
                                    else echo '<span class="badge badge-failed">ล้มเหลว</span>';
                                ?>
                            </td>
                            <td><?php echo isset($row['created_at']) ? date('d/m/Y H:i', strtotime($row['created_at'])) : '-'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
    const themeToggle = document.getElementById('theme-toggle');
    if (document.body.classList.contains('dark-mode')) themeToggle.checked = true;
    themeToggle.addEventListener('change', function() {
        if (this.checked) {
            document.body.classList.add('dark-mode'); localStorage.setItem('theme', 'dark');
        } else {
            document.body.classList.remove('dark-mode'); localStorage.setItem('theme', 'light');
        }
    });
</script>

</body>
</html>