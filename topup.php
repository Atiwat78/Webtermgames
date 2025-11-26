<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php'; 

$user_points = 0; 
$user_role = '';

// เช็คข้อมูล User
if (isset($_SESSION['user_id'])) {
    $sql_balance = "SELECT coins, role FROM users WHERE id = ?";
    if ($stmt_balance = $conn->prepare($sql_balance)) {
        $stmt_balance->bind_param("i", $_SESSION['user_id']);
        $stmt_balance->execute();
        $result_balance = $stmt_balance->get_result();
        if ($row_balance = $result_balance->fetch_assoc()) {
            $user_points = $row_balance['coins']; 
            $user_role = $row_balance['role'];
        }
        $stmt_balance->close();
    }
}

// --- 🎞️ ข้อมูลสไลด์ (Manual) ---
$slider_images = [
    ['title' => 'The Late Shift', 'desc' => 'กะดึก... กะหลอน', 'image' => 'image/ghost1.png', 'novel_id' => 1],
    ['title' => 'ROMANTIC LOVE', 'desc' => 'นิยายรักหวานซึ้ง', 'image' => 'https://images.unsplash.com/photo-1518621736915-f3b1c41bfd00', 'novel_id' => 0],
    ['title' => 'HORROR NIGHT', 'desc' => 'รวมเรื่องหลอน', 'image' => 'https://images.unsplash.com/photo-1505635552518-3448ff116af3', 'novel_id' => 0]
];

// --- 🔥 ส่วนที่แก้เพิ่ม: รับค่าค้นหาและหมวดหมู่ ---
$search_query = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';
$category_filter = isset($_GET['cat']) ? $conn->real_escape_string($_GET['cat']) : '';

// สร้าง Query พื้นฐาน
$sql_stories = "SELECT * FROM stories WHERE 1=1";

// ถ้ามีการค้นหาชื่อ
if (!empty($search_query)) {
    $sql_stories .= " AND (title LIKE '%$search_query%' OR description LIKE '%$search_query%')";
}

// ถ้ามีการเลือกหมวดหมู่
if (!empty($category_filter)) {
    $sql_stories .= " AND category = '$category_filter'";
}

// เรียงลำดับและดึงข้อมูล
$sql_stories .= " ORDER BY id DESC";
$result_stories = $conn->query($sql_stories);

// ดึงรายชื่อหมวดหมู่ทั้งหมดที่มีในระบบ (สำหรับใส่ใน Dropdown)
$sql_cats = "SELECT DISTINCT category FROM stories WHERE category IS NOT NULL AND category != ''";
$result_cats = $conn->query($sql_cats);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8" />
    <title>Elite Novel - คลังนิยายออนไลน์</title> 
    <link rel="icon" type="image/png" sizes="32x32" href="image/Elite Logo black favni.png">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<style>
    body { font-family: 'Sarabun', sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; color: #333; padding-top: 70px; transition: background-color 0.3s ease, color 0.3s ease; }
    
    /* Hero Slider */
    .hero-slider { width: 100%; height: 420px; position: relative; }
    .hero-slider .swiper-slide { background-size: cover; background-position: center; position: relative; }
    .hero-slider .swiper-slide::before { content:''; position: absolute; top:0; left:0; width:100%; height:100%; background: linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(0,0,0,0.8)); }
    
    .hero-slider .slide-content { position: relative; z-index: 10; display: flex; flex-direction: column; justify-content: center; align-items: center; width: 100%; height: 100%; text-align: center; color: white; }
    .hero-slider h1 { font-size: 60px; font-weight: 800; margin: 0; color: #fff; text-shadow: 0 4px 15px rgba(0,0,0,0.6); }
    .hero-slider p { font-size: 22px; margin: 15px 0 30px; text-shadow: 0 2px 5px rgba(0,0,0,0.5); color: #ddd; }
    .hero-slider .cta-button { background: #e91e63; color: #fff; padding: 12px 40px; border-radius: 50px; font-size: 18px; font-weight: 700; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(233, 30, 99, 0.4); }
    .hero-slider .cta-button:hover { transform: translateY(-3px); background: #c2185b; }
    .hero-slider .swiper-button-next, .hero-slider .swiper-button-prev { color: #fff; background-color: transparent !important; width: 30px; height: 30px; }

    /* --- ส่วนค้นหา (เพิ่มใหม่) --- */
    .search-bar-container { background: white; padding: 20px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-bottom: 30px; }
    .search-form { display: flex; gap: 15px; flex-wrap: wrap; align-items: center; }
    .input-group { flex: 1; min-width: 200px; }
    .input-group input, .input-group select { width: 100%; padding: 12px 15px; border: 2px solid #eee; border-radius: 50px; font-family: 'Sarabun', sans-serif; font-size: 16px; outline: none; transition: border-color 0.3s; background-color: #f9f9f9; box-sizing: border-box; }
    .input-group input:focus, .input-group select:focus { border-color: #e91e63; }
    .search-btn { background: #e91e63; color: white; border: none; padding: 12px 30px; border-radius: 50px; font-weight: bold; cursor: pointer; transition: background 0.3s; white-space: nowrap; }
    .search-btn:hover { background: #c2185b; }
    .clear-btn { color: #777; text-decoration: none; font-size: 14px; white-space: nowrap; }
    .clear-btn:hover { color: #e91e63; }

    /* Grid นิยาย */
    .container { max-width: 1200px; margin: 50px auto; padding: 0 20px; }
    .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 2px solid #ddd; padding-bottom: 10px; }
    .section-title { font-size: 28px; color: #333; font-weight: 700; margin: 0; }
    .see-all { color: #e91e63; text-decoration: none; font-weight: 600; }
    .novel-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 25px; }
    
    .novel-card { background: transparent; border-radius: 10px; transition: transform 0.3s; cursor: pointer; text-decoration: none; color: inherit; display: flex; flex-direction: column; }
    .novel-card:hover { transform: translateY(-8px); }
    
    .novel-cover-wrapper { width: 100%; padding-top: 150%; position: relative; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.15); background-color: #ddd; }
    .novel-img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
    .novel-card:hover .novel-img { transform: scale(1.1); } 
    
    .novel-badge { position: absolute; top: 10px; right: 10px; background: #e91e63; color: white; padding: 2px 8px; font-size: 10px; font-weight: bold; border-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
    
    .novel-info { padding: 12px 5px; }
    .novel-name { font-size: 16px; font-weight: 700; margin-bottom: 5px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 44px;}
    .novel-desc { font-size: 12px; color: #777; margin-bottom: 10px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .novel-price-row { display: flex; justify-content: space-between; align-items: center; }
    .coin-tag { font-size: 16px; font-weight: bold; color: #DAA520; }
    .read-btn { font-size: 12px; color: #e91e63; border: 1px solid #e91e63; padding: 2px 8px; border-radius: 20px; }

    /* Dark Mode */
    body.dark-mode { background-color: #121212; color: #eee; }
    body.dark-mode .section-title, body.dark-mode .novel-name { color: #fff; }
    body.dark-mode .section-header { border-bottom-color: #333; }
    body.dark-mode .novel-desc { color: #aaa; }
    body.dark-mode .search-bar-container { background: #1f1f1f; }
    body.dark-mode .input-group input, body.dark-mode .input-group select { background: #2a2a2a; border-color: #444; color: #eee; }
    .footer { background-color: #1a1a1a; color: #666; text-align: center; padding: 40px; margin-top: 80px; border-top: 1px solid #333; }
</style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="swiper hero-slider">
    <div class="swiper-wrapper">
        <?php foreach ($slider_images as $slide) : ?>
            <div class="swiper-slide" style="background-image: url('<?php echo $slide['image']; ?>');">
                <a href="novel_detail.php?id=<?php echo $slide['novel_id']; ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 10; text-decoration: none; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                    <div class="slide-content">
                        <h1><?php echo $slide['title']; ?></h1>
                        <p><?php echo $slide['desc']; ?></p>
                        <span class="cta-button">เริ่มอ่านเลย</span> 
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
    <div class="swiper-pagination"></div>
</div>

<div class="container" id="novel-section">
    
    <div class="search-bar-container">
        <form action="" method="GET" class="search-form">
            <div class="input-group">
                <input type="text" name="q" placeholder="ค้นหานิยาย..." value="<?php echo htmlspecialchars($search_query); ?>">
            </div>
            <div class="input-group">
                <select name="cat" onchange="this.form.submit()">
                    <option value="">ทุกหมวดหมู่</option>
                    <?php 
                    if ($result_cats) {
                        while($cat = $result_cats->fetch_assoc()) {
                            $selected = ($category_filter == $cat['category']) ? 'selected' : '';
                            echo "<option value='{$cat['category']}' $selected>{$cat['category']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="search-btn">ค้นหา</button>
            <?php if($search_query || $category_filter): ?>
                <a href="topup.php" class="clear-btn">ล้างค่า</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="section-header">
        <h2 class="section-title">🔥 นิยายมาแรง (New Arrivals)</h2>
        <a href="#" class="see-all">ดูทั้งหมด ></a>
    </div>
    
    <div class="novel-grid">
        <?php if ($result_stories && $result_stories->num_rows > 0): ?>
            <?php while ($novel = $result_stories->fetch_assoc()) : ?>
                
                <?php 
                    // หารูปภาพจากโฟลเดอร์ uploads
                    $img_source = "image/no-cover.jpg"; // รูป Default
                    if (!empty($novel['cover_image'])) {
                        $check_path = "uploads/covers/" . $novel['cover_image'];
                        if (file_exists($check_path)) {
                            $img_source = $check_path;
                        }
                    }
                ?>

                <a href="novel_detail.php?id=<?php echo $novel['id']; ?>" class="novel-card">
                    <div class="novel-cover-wrapper">
                        <img src="<?php echo $img_source; ?>" class="novel-img">
                        <div class="novel-badge"><?php echo $novel['category']; ?></div>
                    </div>
                    
                    <div class="novel-info">
                        <div class="novel-name"><?php echo $novel['title']; ?></div>
                        <div class="novel-desc"><?php echo $novel['description']; ?></div>
                        
                        <div class="novel-price-row">
                            <div class="coin-tag" style="color: #10b981;">ฟรี</div>
                            <span class="read-btn">อ่าน</span>
                        </div>
                    </div>
                </a>

            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center; color:#999; grid-column: 1 / -1; padding: 40px;">ไม่พบนิยายที่คุณค้นหา...</p>
        <?php endif; ?>
    </div>
</div>

<div class="footer"><p>&copy; 2025 Elite Novel. อ่านนิยายออนไลน์ สนุกได้ทุกที่.</p></div>

<script>
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

    new Swiper('.hero-slider', {
        loop: true, speed: 1000, effect: 'fade', fadeEffect: { crossFade: true },
        autoplay: { delay: 2500, disableOnInteraction: false },
        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
        pagination: { el: '.swiper-pagination', clickable: true },
    });
</script>

</body>
</html>