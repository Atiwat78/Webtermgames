<?php
include 'header.php'; 

// ดึงข้อมูลนิยายทั้งหมด
$sql = "SELECT * FROM stories ORDER BY id DESC";
$result = $conn->query($sql);
?>

<style>
    .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .btn-add { background: #e91e63; color: white; padding: 10px 20px; border-radius: 50px; text-decoration: none; font-weight: bold; box-shadow: 0 4px 10px rgba(233, 30, 99, 0.3); transition: 0.3s; display: inline-flex; align-items: center; gap: 5px; border: none; }
    .btn-add:hover { background: #c2185b; transform: translateY(-2px); color: white; }
    
    .story-card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); transition: 0.3s; display: flex; flex-direction: row; margin-bottom: 20px; border: 1px solid #e2e8f0; }
    .story-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
    
    .story-img { width: 120px; height: 160px; object-fit: cover; flex-shrink: 0; }
    .story-info { padding: 20px; flex: 1; }
    .story-title { font-size: 18px; font-weight: bold; color: #334155; margin: 0 0 10px 0; }
    .story-badge { background: #f1f5f9; color: #64748b; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; display: inline-block; margin-bottom: 10px; }
    .action-bar { margin-top: 15px; }
</style>

<div class="header-flex">
    <h2 style="margin:0; color:#334155;">
        <i class="fas fa-book"></i> จัดการรายชื่อนิยาย
    </h2>
    <a href="add_story.php" class="btn-add">
        <i class="fas fa-plus-circle"></i> สร้างเรื่องใหม่
    </a>
</div>

<?php while($row = $result->fetch_assoc()): ?>
    <div class="story-card">
        <?php 
            // เช็คว่ามีรูปไหม ถ้าไม่มีใช้รูปเปล่า
            $img_path = "../uploads/covers/" . $row['cover_image'];
            $img_show = (!empty($row['cover_image']) && file_exists($img_path)) ? $img_path : "https://via.placeholder.com/150x200?text=No+Cover"; 
        ?>
        <img src="<?php echo $img_show; ?>" class="story-img">
        
        <div class="story-info">
            <h3 class="story-title"><?php echo $row['title']; ?></h3>
            <span class="story-badge"><i class="fas fa-tag"></i> <?php echo $row['category']; ?></span>
            <p style="color: #666; font-size: 14px; margin:0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                <?php echo $row['description']; ?>
            </p>
            
            <div class="action-bar">
                <a href="edit_story.php?id=<?php echo $row['id']; ?>" class="btn btn-warning" style="padding: 5px 15px; font-size: 14px; margin-right:5px; background-color:#f59e0b; color:white; text-decoration:none; border-radius:4px;">
    <i class="fas fa-edit"></i> แก้ไข
</a>
                <a href="delete_story.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" style="padding: 5px 15px; font-size: 14px;" onclick="return confirm('⚠️ ยืนยันลบเรื่องนี้? (ตอนนิยายทั้งหมดในเรื่องนี้จะหายไปด้วย)')">
                    <i class="fas fa-trash"></i> ลบเรื่องนี้
                </a>
            </div>
        </div>
    </div>
<?php endwhile; ?>

</div></div></body></html>