<?php
session_start();
require_once 'db.php'; 

// รับค่า ID จากลิงก์ที่กดมา (ถ้าไม่มีให้เป็น 1)
$id = $_GET['id'] ?? 1;

// --- ข้อมูลสินค้า (ต้องเหมือนกับหน้า index.php) ---
// ในระบบจริงเราจะดึงจาก Database แต่ตอนนี้ใช้ Array ไปก่อนครับ
$wallpapers = [
    1 => ['name' => 'Golden Liquid', 'price' => 19, 'image' => 'https://images.unsplash.com/photo-1634152962476-4b8a00e1915c?q=80&w=1200&auto=format&fit=crop', 'desc' => 'ลายหินอ่อนทองคำ ความละเอียด 4K เหมาะสำหรับหน้าจอคอมพิวเตอร์และมือถือ'],
    2 => ['name' => 'Cyberpunk City', 'price' => 29, 'image' => 'https://images.unsplash.com/photo-1515630278258-407f66498911?q=80&w=1200&auto=format&fit=crop', 'desc' => 'เมืองอนาคตธีมกลางคืน แสงไฟนีออน สไตล์ Cyberpunk 2077'],
    3 => ['name' => 'Black Geometry', 'price' => 15, 'image' => 'https://images.unsplash.com/photo-1614850523459-c2f4c699c52e?q=80&w=1200&auto=format&fit=crop', 'desc' => 'ทรงเรขาคณิตสีดำด้าน ตัดขอบทอง เรียบหรู มินิมอล'],
    4 => ['name' => 'Golden Samurai', 'price' => 39, 'image' => 'https://images.unsplash.com/photo-1615570773285-990222b4796a?q=80&w=1200&auto=format&fit=crop', 'desc' => 'ศิลปะซามูไรสไตล์ญี่ปุ่น โทนสีดำทอง ดุดัน'],
    5 => ['name' => 'Elite Skull', 'price' => 25, 'image' => 'https://images.unsplash.com/photo-1618331835717-801e976710b2?q=80&w=1200&auto=format&fit=crop', 'desc' => 'หัวกะโหลกศิลปะ งานละเอียด ระดับ Masterpiece']
];

// ดึงข้อมูลตาม ID
$art = $wallpapers[$id] ?? $wallpapers[1]; 
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8" />
    <title>ซื้อ - <?php echo $art['name']; ?></title>
    <link rel="icon" type="image/png" sizes="32x32" href="image/Elite Logo black favni.png">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet" />
    
    <style>
        body { font-family: 'Sarabun', sans-serif; background-color: #f4f7f6; color: #333; margin: 0; padding-top: 100px; transition: all 0.3s; }
        
        /* Container จัดกึ่งกลาง */
        .container { max-width: 1000px; margin: 0 auto; display: flex; gap: 50px; padding: 20px; align-items: flex-start; }
        
        /* รูปภาพด้านซ้าย */
        .preview-box { flex: 1.2; }
        .preview-img { width: 100%; border-radius: 15px; box-shadow: 0 20px 40px rgba(0,0,0,0.15); border: 1px solid #ddd; }
        
        /* ข้อมูลด้านขวา */
        .info-box { flex: 1; padding-top: 10px; }
        h1 { font-size: 36px; margin: 0 0 15px 0; color: #111; }
        .desc { color: #666; line-height: 1.6; margin-bottom: 30px; font-size: 16px; }
        
        /* ราคาสีทอง */
        .price-tag { 
            font-size: 42px; font-weight: 800; color: #DAA520; 
            display: flex; align-items: center; gap: 10px; margin-bottom: 40px;
        }
        
        /* ปุ่มกดซื้อ */
        .btn-buy {
            background: linear-gradient(45deg, #DAA520, #ffc107); 
            color: #000; border: none; padding: 18px 40px;
            font-size: 20px; font-weight: 800; border-radius: 50px; cursor: pointer;
            width: 100%; transition: transform 0.2s, box-shadow 0.2s;
            display: flex; justify-content: center; align-items: center; gap: 10px;
            box-shadow: 0 10px 20px rgba(218, 165, 32, 0.3);
        }
        .btn-buy:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(218, 165, 32, 0.5); }
        
        /* กล่องดาวน์โหลด (ซ่อนก่อน) */
        .download-area {
            display: none; 
            background: #e8f5e9; border: 2px solid #4caf50; padding: 30px; 
            border-radius: 15px; text-align: center; animation: fadeIn 0.5s;
        }
        @keyframes fadeIn { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }

        .btn-download {
            display: inline-block; background: #2e7d32; color: white; 
            padding: 12px 30px; border-radius: 8px; text-decoration: none; 
            font-weight: bold; margin-top: 15px;
        }

        /* Dark Mode */
        body.dark-mode { background-color: #111; color: #eee; }
        body.dark-mode h1 { color: #fff; }
        body.dark-mode .desc { color: #aaa; }
        body.dark-mode .preview-img { border-color: #333; box-shadow: 0 20px 40px rgba(0,0,0,0.5); }

        /* Responsive มือถือ */
        @media (max-width: 768px) { 
            .container { flex-direction: column; } 
            .price-tag { font-size: 32px; }
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <div class="preview-box">
        <img src="<?php echo $art['image']; ?>" class="preview-img">
    </div>
    
    <div class="info-box">
        <h1><?php echo $art['name']; ?></h1>
        <p class="desc"><?php echo $art['desc']; ?></p>
        
        <div class="price-tag">
            <?php echo $art['price']; ?> 
            <img src="image/coingold.png" style="width: 40px;">
        </div>
        
        <button class="btn-buy" onclick="buyWallpaper()">
            🛒 ซื้อภาพนี้ทันที
        </button>

        <div id="download-box" class="download-area">
            <h3 style="color:#2e7d32; margin:0;">🎉 ชำระเงินสำเร็จ!</h3>
            <p>คุณเป็นเจ้าของภาพนี้แล้ว สามารถดาวน์โหลดได้เลย</p>
            <a href="<?php echo $art['image']; ?>" target="_blank" class="btn-download" download>
                ⬇️ ดาวน์โหลดไฟล์ต้นฉบับ (4K)
            </a>
        </div>
    </div>
</div>

<script>
    // Sync Theme
    if (localStorage.getItem('theme') === 'dark') document.body.classList.add('dark-mode');

    function buyWallpaper() {
        // จำลองการตัดเงิน (ของจริงต้องเขียน AJAX ไปตัดใน DB)
        if(confirm('ยืนยันการซื้อภาพนี้ในราคา <?php echo $art['price']; ?> เหรียญ?')) {
            
            // 1. ซ่อนปุ่มซื้อ
            document.querySelector('.btn-buy').style.display = 'none';
            
            // 2. โชว์กล่องดาวน์โหลด
            document.getElementById('download-box').style.display = 'block';
            
            alert('ขอบคุณที่อุดหนุน! ยอดเงินของคุณถูกหักแล้ว');
        }
    }
</script>

</body>
</html>