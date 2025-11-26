<?php
session_start();
require_once 'db.php'; // เผื่อใช้ connect user coins

// --- 1. เอาข้อมูลนิยายชุดเดิมมาวาง (ในอนาคตควรดึงจาก Database) ---
$novels = [
    [
        'id' => 1, 
        'name' => 'กะดึก... กะหลอน (The Late Shift)', 
        'price' => 0,
        'image' => 'image/ghost1.png', 
        'desc' => 'อย่าหันมอง... อย่าสบตา... เพราะกะดึกคืนนี้ พวกเขา กำลังจ้องมองคุณอยู่ใกล้แค่ลมหายใจรดต้นคอ เรื่องราวสุดหลอนของพนักงานกะดึกที่ชีวิตต้องเปลี่ยนไปเพราะ...',
        'badge' => 'Hot'
    ],
    [
        'id' => 2, 
        'name' => 'เพื่อนในจินตนาการ (The Imaginary Friend)', 
        'price' => 0, 
        'image' => 'image/ghost2.png', 
        'desc' => 'ความรักวุ่นๆ ของเลขาหน้าใส กับท่านประธานจอมเผด็จการ',
        'badge' => 'New'
    ],
    [
        'id' => 3, 
        'name' => 'ระบบเทพเจ้าสงคราม', 
        'price' => 450, 
        'image' => 'https://images.unsplash.com/photo-1630325667355-087093557e0f?q=80&w=600&auto=format&fit=crop', 
        'desc' => 'ย้อนเวลามาแก้ไขอดีต พร้อมระบบที่ทำให้เขาแข็งแกร่งที่สุดในปฐพี',
        'badge' => 'Action'
    ],
    [
        'id' => 4, 
        'name' => 'ปริศนาโรงเรียนหลอน', 
        'price' => 250, 
        'image' => 'https://images.unsplash.com/photo-1509248961158-e54f6934749c?q=80&w=600&auto=format&fit=crop', 
        'desc' => 'อย่าหันหลังกลับ... ถ้าไม่อยากเจอสิ่งที่ซ่อนอยู่ในความมืด',
        'badge' => 'Horror'
    ],
    [
        'id' => 5, 
        'name' => 'ข้ามภพสยบมาร', 
        'price' => 390, 
        'image' => 'https://images.unsplash.com/photo-1531988042231-d39a9cc12a9a?q=80&w=600&auto=format&fit=crop', 
        'desc' => 'นางร้ายที่ใครๆ ก็เกลียดชัง กลับกลายเป็นวีรสตรีผู้กอบกู้แผ่นดิน',
        'badge' => 'China'
    ],
    [
        'id' => 6, 
        'name' => 'My Cat is Human', 
        'price' => 200, 
        'image' => 'image/เเมววว.jpg', 
        'desc' => 'เมื่อแมวเหมียวที่เก็บมาเลี้ยง กลายร่างเป็นหนุ่มหล่อในคืนพระจันทร์เต็มดวง',
        'badge' => 'Yuri/Yaoi'
    ],
    [
        'id' => 7, 
        'name' => 'The Last Survivor', 
        'price' => 150, 
        'image' => 'https://images.unsplash.com/photo-1536440136628-849c177e76a1?q=80&w=600&auto=format&fit=crop', 
        'desc' => 'วันสิ้นโลกมาถึงแล้ว... และผมคือมนุษย์คนสุดท้าย',
        'badge' => 'Sci-Fi'
    ],
    [
        'id' => 8, 
        'name' => 'ปรุงรักฉบับเชฟ', 
        'price' => 290, 
        'image' => 'https://images.unsplash.com/photo-1556910103-1c02745a30bf?q=80&w=600&auto=format&fit=crop', 
        'desc' => 'สูตรอาหารพิชิตใจนายปากแข็ง',
        'badge' => 'Love'
    ],
];

// --- 2. รับค่า ID จาก URL ---
$novel_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$current_novel = null;

// ค้นหานิยายที่ตรงกับ ID
foreach ($novels as $n) {
    if ($n['id'] === $novel_id) {
        $current_novel = $n;
        break;
    }
}

// ถ้าไม่เจอนิยาย ให้ redirect กลับหน้าแรก
if (!$current_novel) {
    header("Location: index.php"); // หรือชื่อไฟล์หน้าหลักของคุณ
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title><?php echo $current_novel['name']; ?> - Elite Novel</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Sarabun', sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; color: #333; padding-top: 80px;}
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
        
        .novel-detail-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            overflow: hidden;
            display: flex;
            flex-wrap: wrap;
        }

        .cover-section {
            flex: 1;
            min-width: 300px;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            background: #f9f9f9;
        }

        .cover-section img {
            width: 100%;
            max-width: 300px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .info-section {
            flex: 2;
            padding: 30px;
            min-width: 300px;
        }

        .novel-title { font-size: 32px; font-weight: 800; margin-bottom: 10px; color: #222; }
        .novel-badge { display: inline-block; background: #e91e63; color: white; padding: 4px 10px; border-radius: 4px; font-size: 14px; margin-bottom: 15px; }
        .novel-price { font-size: 24px; color: #DAA520; font-weight: bold; margin-bottom: 20px; display: flex; align-items: center; gap: 5px; }
        .novel-desc { font-size: 16px; line-height: 1.8; color: #555; margin-bottom: 30px; }

        .action-buttons { display: flex; gap: 15px; }
        .btn { padding: 12px 30px; border-radius: 50px; font-weight: bold; text-decoration: none; cursor: pointer; transition: 0.3s; border: none; font-size: 16px; }
        .btn-read { background: #e91e63; color: white; }
        .btn-read:hover { background: #c2185b; transform: translateY(-2px); }
        .btn-back { background: #ddd; color: #333; }
        .btn-back:hover { background: #ccc; }

        /* Dark Mode support (Basic) */
        body.dark-mode { background-color: #121212; color: #eee; }
        body.dark-mode .novel-detail-card { background: #1e1e1e; }
        body.dark-mode .cover-section { background: #252525; }
        body.dark-mode .novel-title { color: #fff; }
        body.dark-mode .novel-desc { color: #aaa; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <div class="novel-detail-card">
        <div class="cover-section">
            <img src="<?php echo $current_novel['image']; ?>" alt="<?php echo $current_novel['name']; ?>">
        </div>

        <div class="info-section">
            <?php if(isset($current_novel['badge'])): ?>
                <span class="novel-badge"><?php echo $current_novel['badge']; ?></span>
            <?php endif; ?>
            
            <h1 class="novel-title"><?php echo $current_novel['name']; ?></h1>
            
            <div class="novel-price">
    <?php if ($current_novel['price'] == 0) : ?>
        <span style="color: #10b981;">อ่านฟรี</span>
    <?php else : ?>
        <?php echo $current_novel['price']; ?> 
        <img src="image/coingold.png" style="width: 24px; height: 24px;" alt="Coins">
    <?php endif; ?>
</div>
            <p class="novel-desc">
                <?php echo nl2br($current_novel['desc']); ?>
                <br><br>
                Don't look back... Don't make eye contact. On the late shift tonight, 'They' are watching you—close enough to feel their cold breath on your neck. The chilling story of a night worker whose life will be changed forever..
            </p>

<a href="read.php?id=<?php echo $current_novel['id']; ?>&ep=1" class="btn btn-read">อ่านเลย (บทที่ 1)</a>
                <a href="topup.php" class="btn btn-back">กลับหน้าหลัก</a>
            </div>
        </div>
    </div>
</div>

<script>
    // 1. ดึงปุ่ม Toggle มาจาก Navbar (ต้องมั่นใจว่าใน navbar.php ปุ่มมี id="theme-toggle")
    const toggleSwitch = document.getElementById('theme-toggle');
    const currentTheme = localStorage.getItem('theme');

    // 2. เช็คค่าเริ่มต้นตอนโหลดหน้าเว็บ
    if (currentTheme === 'dark') {
        document.body.classList.add('dark-mode');
        // ถ้ามีปุ่ม ให้ติ๊กถูกไว้เลย
        if (toggleSwitch) {
            toggleSwitch.checked = true;
        }
    }

    // 3. สั่งให้ทำงานเมื่อมีการกดปุ่ม (Event Listener)
    if (toggleSwitch) {
        toggleSwitch.addEventListener('change', function(e) {
            if (e.target.checked) {
                // ถ้าติ๊กถูก -> เปิด Dark Mode
                document.body.classList.add('dark-mode');
                localStorage.setItem('theme', 'dark');
            } else {
                // ถ้าเอาออก -> ปิด Dark Mode
                document.body.classList.remove('dark-mode');
                localStorage.setItem('theme', 'light');
            }
        });
    }
</script>

</body>
</html>