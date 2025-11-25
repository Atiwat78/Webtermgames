<?php
session_start();
require_once 'db.php';

// 1. รับค่า ID นิยาย
$novel_id = isset($_GET['novel_id']) ? (int)$_GET['novel_id'] : 0;

// 2. สมมติข้อมูลตอน (ในของจริงต้องดึงจาก Database ตาราง chapters)
// สังเกต: ผมตั้งราคาตอนที่ 1-2 เป็น 0 (ฟรี) ส่วนตอนที่ 3 เป็น 300 (ติดเหรียญ)
$chapters = [
    ['id' => 101, 'title' => 'บทนำ: จุดเริ่มต้นของความสยอง', 'price' => 0],
    ['id' => 102, 'title' => 'บทที่ 1: เสียงเตือนหน้าร้าน', 'price' => 0],
    ['id' => 103, 'title' => 'บทที่ 2: ลูกค้าปริศนา', 'price' => 0],
    ['id' => 104, 'title' => 'บทที่ 3: ห้ามหันหลัง (ติดเหรียญ)', 'price' => 300], 
    ['id' => 105, 'title' => 'บทที่ 4: ความจริงที่ถูกเปิดเผย', 'price' => 300],
];

// *อนาคตต้องเขียน SQL เช็คว่า user เคยซื้อตอนนั้นๆ ไปหรือยัง*
// $purchased_chapters = [104]; // สมมติว่าเคยซื้อบทที่ 3 ไปแล้ว (เอาไว้เช็ค)
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เลือกตอนอ่าน</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body { font-family: 'Sarabun', sans-serif; background-color: #f4f7f6; max-width: 800px; margin: 40px auto; padding: 20px; }
        
        .header { text-align: center; margin-bottom: 30px; }
        .back-btn { display: inline-block; margin-bottom: 20px; text-decoration: none; color: #555; }

        .chapter-list { list-style: none; padding: 0; }
        
        .chapter-item { 
            background: white; 
            border-bottom: 1px solid #eee; 
            padding: 15px 20px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            transition: 0.2s;
            text-decoration: none;
            color: #333;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        
        .chapter-item:hover { background-color: #fff8e1; transform: translateX(5px); }

        .ch-title { font-weight: 500; font-size: 16px; }
        
        .status-badge { 
            font-size: 14px; 
            padding: 5px 12px; 
            border-radius: 20px; 
            min-width: 80px;
            text-align: center;
        }

        /* สไตล์สำหรับตอนฟรี */
        .status-free { background-color: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
        
        /* สไตล์สำหรับตอนเสียเงิน */
        .status-locked { background-color: #ffebee; color: #c62828; border: 1px solid #ffcdd2; display: flex; align-items: center; gap: 5px; justify-content: center;}

    </style>
</head>
<body>

<a href="index.php" class="back-btn"><i class="fas fa-arrow-left"></i> กลับหน้าหลัก</a>

<div class="header">
    <h1>สารบัญตอน</h1>
    <p>เลือกตอนที่ต้องการอ่าน</p>
</div>

<div class="chapter-list">
    <?php foreach ($chapters as $ch): ?>
        
        <?php 
            // ลอจิกการสร้างลิงก์
            if ($ch['price'] == 0) {
                // ถ้าฟรี -> ลิงก์ไปหน้าอ่านเลย (read.php)
                $link = "read.php?id=" . $ch['id'];
                $status = '<span class="status-badge status-free">อ่านฟรี</span>';
                $icon = '<i class="far fa-file-alt" style="color:#aaa; margin-right:10px;"></i>';
            } else {
                // ถ้าไม่ฟรี -> ลิงก์ไปหน้าจ่ายเงิน (confirm_buy.php)
                // (ในอนาคตถ้าซื้อแล้ว ให้เปลี่ยนลิงก์เป็น read.php ตรงนี้)
                $link = "confirm_buy.php?id=" . $ch['id']; 
                $status = '<span class="status-badge status-locked"><i class="fas fa-lock"></i> ' . $ch['price'] . '</span>';
                $icon = '<i class="fas fa-lock" style="color:#DAA520; margin-right:10px;"></i>';
            }
        ?>

        <a href="<?php echo $link; ?>" class="chapter-item">
            <div style="display: flex; align-items: center;">
                <?php echo $icon; ?>
                <span class="ch-title"><?php echo $ch['title']; ?></span>
            </div>
            <div>
                <?php echo $status; ?>
            </div>
        </a>

    <?php endforeach; ?>
</div>

</body>
</html>