<?php
session_start();
include 'db.php'; // เชื่อมต่อฐานข้อมูล

// 1. รับค่าจาก URL
$novel_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
$ep_id = isset($_GET['ep']) ? (int)$_GET['ep'] : 1;

// 2. ดึงเนื้อหาจาก Database
$sql = "SELECT * FROM chapters WHERE story_id = $novel_id AND ep_num = $ep_id";
$result = $conn->query($sql);
$current_chapter = $result->fetch_assoc();

// เตรียมตัวแปรสำหรับปุ่ม Next/Prev (ค่าเริ่มต้นเป็น false)
$has_prev_chapter = false;
$has_next_chapter = false;

// ถ้าเจอเนื้อหา ค่อยไปเช็คตอนก่อนหน้า/ถัดไป
if ($current_chapter) {
    // เช็คตอนก่อนหน้า
    $prev_ep = $ep_id - 1;
    $check_prev = $conn->query("SELECT id FROM chapters WHERE story_id = $novel_id AND ep_num = $prev_ep");
    $has_prev_chapter = ($check_prev->num_rows > 0);

    // เช็คตอนถัดไป
    $next_ep = $ep_id + 1;
    $check_next = $conn->query("SELECT id FROM chapters WHERE story_id = $novel_id AND ep_num = $next_ep");
    $has_next_chapter = ($check_next->num_rows > 0);
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title><?php echo $current_chapter ? $current_chapter['title'] : 'ไม่พบเนื้อหา'; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;600&display=swap" rel="stylesheet" />
<style>
    /* --- CSS (เหมือนเดิม) --- */
    body { 
        font-family: 'Sarabun', sans-serif; 
        background-color: #f4f7f6;
        color: #333;
        margin: 0; 
        padding-top: 80px; /* ปรับตรงนี้ให้พอดีกับ Navbar */
        line-height: 1.8; 
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    .reader-container { 
        max-width: 800px; 
        margin: 0 auto; 
        padding: 40px 20px; 
        background: white;
        min-height: 80vh; 
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
        transition: background-color 0.3s ease;
    }
    .chapter-title { text-align: center; color: #e91e63; font-size: 28px; margin-bottom: 40px; border-bottom: 1px solid #ddd; padding-bottom: 20px; }
    .novel-content { font-size: 18px; text-indent: 40px; }
    .novel-content p { margin-bottom: 20px; }
    
    .nav-buttons { display: flex; justify-content: space-between; margin-top: 50px; border-top: 1px solid #ddd; padding-top: 20px; }
    .btn { padding: 10px 25px; border-radius: 5px; text-decoration: none; color: white; background: #333; transition: 0.3s; display: inline-block;}
    .btn:hover { background: #e91e63; }
    .btn-disabled { background: #eee; color: #999; pointer-events: none; }
    .btn-home { border: 1px solid #999; background: transparent; color: #333; }

    /* Dark Mode */
    body.dark-mode { background-color: #1a1a1a; color: #ddd; }
    body.dark-mode .reader-container { background-color: #252525; box-shadow: none; }
    body.dark-mode .chapter-title { border-bottom-color: #444; }
    body.dark-mode .nav-buttons { border-top-color: #444; }
    body.dark-mode .btn-disabled { background: #222; color: #555; }
    body.dark-mode .btn-home { border-color: #555; color: #ddd; }
    
    /* สไตล์สำหรับกล่อง Error */
    .error-box { text-align: center; padding: 50px 0; }
    .error-box h1 { color: #555; }
    body.dark-mode .error-box h1 { color: #ccc; }
</style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="reader-container">
        
        <?php if ($current_chapter): ?>
            <h1 class="chapter-title"><?php echo $current_chapter['title']; ?></h1>
            
            <div class="novel-content">
                <?php echo html_entity_decode($current_chapter['content']); ?>
            </div>

            <div class="nav-buttons">
                <?php if ($has_prev_chapter): ?>
                    <a href="read.php?id=<?php echo $novel_id; ?>&ep=<?php echo $prev_ep; ?>" class="btn">< ตอนก่อนหน้า</a>
                <?php else: ?>
                    <a href="#" class="btn btn-disabled">< ตอนก่อนหน้า</a>
                <?php endif; ?>

                <a href="novel_detail.php?id=<?php echo $novel_id; ?>" class="btn btn-home">สารบัญ</a>

                <?php if ($has_next_chapter): ?>
                    <a href="read.php?id=<?php echo $novel_id; ?>&ep=<?php echo $next_ep; ?>" class="btn">ตอนต่อไป ></a>
                <?php else: ?>
                    <a href="#" class="btn btn-disabled">จบตอนล่าสุด</a>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <div class="error-box">
                <h1>❌ ไม่พบเนื้อหาตอนนี้</h1>
                <p>ตอนนี้อาจจะยังไม่ได้ลง หรือคุณพิมพ์ URL ผิด</p>
                <br>
                <a href="index.php" class="btn">กลับหน้าหลัก</a>
            </div>
        <?php endif; ?>

    </div>

    <script>
        const themeToggle = document.getElementById('theme-toggle');
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
            if(themeToggle) themeToggle.checked = true;
        }
        if(themeToggle){
            themeToggle.addEventListener('change', function() {
                if (this.checked) {
                    document.body.classList.add('dark-mode'); 
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.body.classList.remove('dark-mode'); 
                    localStorage.setItem('theme', 'light');
                }
            });
        }
    </script>

</body>
</html>