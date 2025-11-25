<?php
// --- ✨ ส่วน PHP บนสุด ---
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php'; 

$user_points = 0; 
$user_role = '';

// ส่วนเช็ค User เหมือนเดิม ไม่ต้องแก้
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

// --- 🎞️ ข้อมูลสไลด์ (Hero Slider) - ธีมนิยาย ---
// --- 🎞️ ข้อมูลสไลด์ (Hero Slider) - ธีมนิยาย ---
$slider_images = [
    [
        'title' => 'The Late Shift',
        'desc' => 'กะดึก... กะหลอน',
        'image' => 'image/ผีี.jpg', 
        'novel_id' => 1 // ลิงก์ตรงไปที่หน้ารายละเอียดของเรื่องนี้
    ],
    [
        'title' => 'ROMANTIC LOVE',
        'desc' => 'นิยายรักหวานซึ้ง กินใจ จนต้องเสียน้ำตา',
        'image' => 'https://images.unsplash.com/photo-1518621736915-f3b1c41bfd00?q=80&w=1920&auto=format&fit=crop',
        'novel_id' => 0 // ลิงก์ไปยังหน้าหมวดหมู่รวม (หรือตั้ง ID ที่เหมาะสม)
    ],
    [
        'title' => 'HORROR NIGHT',
        'desc' => 'รวมเรื่องหลอน ขวัญผวา อ่านแล้วนอนไม่หลับ',
        'image' => 'https://images.unsplash.com/photo-1505635552518-3448ff116af3?q=80&w=1920&auto=format&fit=crop',
        'novel_id' => 0 // ลิงก์ไปยังหน้าหมวดหมู่รวม (หรือตั้ง ID ที่เหมาะสม)
    ]
];

// --- 📚 ข้อมูลนิยาย (Mock Data) ---
// *เปลี่ยนชื่อตัวแปรจาก $sheets เป็น $novels เพื่อความเข้าใจง่าย*
$novels = [
    [
        'id' => 1, 
        'name' => 'กะดึก... กะหลอน (The Late Shift)', 
        'price' => 0, // ราคาปลดล็อคทั้งเรื่อง หรือต่อตอน
        'image' => 'image/ผีี.jpg', 
        'desc' => 'อย่าหันมอง... อย่าสบตา... เพราะกะดึกคืนนี้ พวกเขา กำลังจ้องมองคุณอยู่',
        'badge' => 'Hot'
        
    ],
    [
        'id' => 2, 
        'name' => 'เล่ห์รักท่านประธาน', 
        'price' => 300, 
        'image' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=600&auto=format&fit=crop', 
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
        'image' => 'https://images.unsplash.com/photo-1574158622682-e40e69881006?q=80&w=600&auto=format&fit=crop', 
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

// --- 🏷️ ข้อมูลหมวดหมู่ (Categories Mock Data) ---
$categories = [
    ['name' => 'สยองขวัญ', 'icon' => '👻', 'color' => '#880e4f'],
    ['name' => 'โรแมนติก', 'icon' => '💖', 'color' => '#c2185b'],
    ['name' => 'แอคชั่น', 'icon' => '💥', 'color' => '#e91e63'],
    ['name' => 'แฟนตาซี', 'icon' => '✨', 'color' => '#d81b60'],
    ['name' => 'จีน/กำลังภายใน', 'icon' => '🐉', 'color' => '#ad1457'],
    ['name' => 'ไซไฟ', 'icon' => '🚀', 'color' => '#9c27b0'],
    ['name' => 'ตลก/คอมเมดี้', 'icon' => '😂', 'color' => '#6a1b9a'],
];


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
    
    /* --- Hero Slider --- */
    .hero-slider { width: 100%; height: 420px; position: relative; } /* เพิ่มความสูงนิดหน่อย */
    .hero-slider .swiper-slide { background-size: cover; background-position: center; position: relative; }
    .hero-slider .swiper-slide::before { content:''; position: absolute; top:0; left:0; width:100%; height:100%; background: linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(0,0,0,0.8)); }
    
    .hero-slider .slide-content { 
        position: relative; z-index: 10;
        display: flex; flex-direction: column; justify-content: center; align-items: center; 
        width: 100%; height: 100%; text-align: center; color: white; 
    }
    .hero-slider h1 { font-size: 60px; font-weight: 800; margin: 0; color: #fff; text-shadow: 0 4px 15px rgba(0,0,0,0.6); letter-spacing: 1px; }
    .hero-slider p { font-size: 22px; margin: 15px 0 30px; text-shadow: 0 2px 5px rgba(0,0,0,0.5); color: #ddd; max-width: 600px; }
    
    .hero-slider .cta-button { 
        background: #e91e63; /* สีชมพูเข้มแบบเว็บนิยาย/การ์ตูน */
        color: #fff; padding: 12px 40px; border-radius: 50px; 
        font-size: 18px; font-weight: 700; text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(233, 30, 99, 0.4);
    }
    .hero-slider .cta-button:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(233, 30, 99, 0.6); background: #c2185b; }
    
    .hero-slider .swiper-button-next, .hero-slider .swiper-button-prev { color: #fff; text-shadow: 0 0 10px rgba(0,0,0,0.5); }
    .hero-slider .swiper-pagination-bullet-active { background: #e91e63; }

    /* --- Grid นิยาย --- */
    .container { max-width: 1200px; margin: 50px auto; padding: 0 20px; }
    
    .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 2px solid #ddd; padding-bottom: 10px; }
    .section-title { font-size: 28px; color: #333; font-weight: 700; margin: 0; }
    .see-all { color: #e91e63; text-decoration: none; font-weight: 600; }
    
    .novel-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 25px; } /* ปรับขนาดการ์ดให้แคบลงเหมาะกับปกหนังสือ */
    
    .novel-card { 
        background: transparent; border-radius: 10px; 
        transition: transform 0.3s; cursor: pointer; text-decoration: none; color: inherit;
        display: flex; flex-direction: column;
    }
    .novel-card:hover { transform: translateY(-8px); }
    
    .novel-cover-wrapper { 
        width: 100%; padding-top: 150%; /* Aspect Ratio 2:3 (ปกหนังสือ) */
        position: relative; border-radius: 10px; overflow: hidden; 
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }
    .novel-img { 
        position: absolute; top: 0; left: 0; width: 100%; height: 100%; 
        object-fit: cover; transition: transform 0.5s; 
    }
    .novel-card:hover .novel-img { transform: scale(1.1); } 

    /* Badge มุมภาพ */
    .novel-badge {
        position: absolute; top: 10px; right: 10px;
        background: #e91e63; color: white; padding: 2px 8px;
        font-size: 10px; font-weight: bold; border-radius: 4px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    .novel-info { padding: 12px 5px; }
    .novel-name { font-size: 16px; font-weight: 700; margin-bottom: 5px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 44px;}
    .novel-desc { font-size: 12px; color: #777; margin-bottom: 10px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    
    .novel-price-row { 
        display: flex; justify-content: space-between; align-items: center; 
    }
    .coin-tag { 
        font-size: 16px; font-weight: bold; color: #DAA520; 
        display: flex; align-items: center; gap: 4px; 
    }
    .read-btn {
        font-size: 12px; color: #e91e63; border: 1px solid #e91e63; padding: 2px 8px; border-radius: 20px;
    }

    /* Dark Mode */
    body.dark-mode { background-color: #121212; color: #eee; }
    body.dark-mode .section-title { color: #fff; }
    body.dark-mode .section-header { border-bottom-color: #333; }
    body.dark-mode .novel-name { color: #fff; }
    body.dark-mode .novel-desc { color: #aaa; }
    
    .footer { background-color: #1a1a1a; color: #666; text-align: center; padding: 40px; margin-top: 80px; border-top: 1px solid #333; }
.hero-slider .swiper-button-next, 
.hero-slider .swiper-button-prev { 
    color: #fff; /* ลูกศรสีขาว */
    background-color: transparent !important; /* <<< แก้ไข: บังคับให้พื้นหลังเป็นโปร่งใส */
    padding: 0; /* ลบ padding ส่วนเกิน */
    width: 30px; /* กำหนดความกว้างมาตรฐาน (ถ้าไม่กำหนด Swiper อาจมีขนาดใหญ่เอง) */
    height: 30px; /* กำหนดความสูงมาตรฐาน */
    transition: all 0.3s;
    z-index: 20; 
}

.hero-slider .swiper-button-next:hover, 
.hero-slider .swiper-button-prev:hover {
    background-color: transparent !important; /* <<< แก้ไข: บังคับให้โปร่งใสแม้ตอนเมาส์ชี้ */
    opacity: 0.8; /* ลดความทึบลงเล็กน้อยตอน hover เพื่อให้มีลูกเล่น */
}
</style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="swiper hero-slider">
    <div class="swiper-wrapper">
        <?php foreach ($slider_images as $slide) : ?>
            <div class="swiper-slide" style="background-image: url('<?php echo $slide['image']; ?>');">
                
                <a href="novel_detail.php?id=<?php echo $slide['novel_id']; ?>" style="
                    position: absolute; 
                    top: 0; 
                    left: 0; 
                    width: 100%; 
                    height: 100%; 
                    z-index: 10; 
                    text-decoration: none;
                    display: flex; 
                    flex-direction: column; 
                    justify-content: center; 
                    align-items: center;
                ">
                
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
    </div>

<div class="container" id="novel-section">
    <div class="section-header">
        <h2 class="section-title">🔥 นิยายมาแรง (Hot Novels)</h2>
        <a href="#" class="see-all">ดูทั้งหมด ></a>
    </div>
    
    <div class="novel-grid">
        <?php foreach ($novels as $novel) : ?>
            <a href="novel_detail.php?id=<?php echo $novel['id']; ?>" class="novel-card">
                <div class="novel-cover-wrapper">
                    <img src="<?php echo $novel['image']; ?>" class="novel-img" alt="<?php echo $novel['name']; ?>">
                    <?php if(isset($novel['badge'])): ?>
                        <div class="novel-badge"><?php echo $novel['badge']; ?></div>
                    <?php endif; ?>
                </div>
                <div class="novel-info">
                    <div class="novel-name"><?php echo $novel['name']; ?></div>
                    <div class="novel-desc"><?php echo $novel['desc']; ?></div>
                    <div class="novel-price-row">
                        <div class="coin-tag" style="color: <?php echo $novel['price'] == 0 ? '#10b981' : '#DAA520'; ?>;">
    <?php if ($novel['price'] == 0) : ?>
        ฟรี
    <?php else : ?>
        <?php echo $novel['price']; ?> 
        <img src="image/coingold.png" style="width: 16px; height: 16px;">
    <?php endif; ?>
</div>
                        <span class="read-btn">อ่าน</span>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<div class="footer"><p>&copy; 2025 Elite Novel. อ่านนิยายออนไลน์ สนุกได้ทุกที่.</p></div>

<script>
    // Theme Sync (เหมือนเดิม)
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

    const heroSwiper = new Swiper('.hero-slider', {
        loop: true,
        speed: 1000, 
        effect: 'fade',
        fadeEffect: { crossFade: true },
        autoplay: { delay: 2500, disableOnInteraction: false },
        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
        pagination: { el: '.swiper-pagination', clickable: true },
    });
</script>

</body>
</html>