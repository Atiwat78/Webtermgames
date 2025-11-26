<?php
include 'header.php'; 

// 1. ตรวจสอบ ID
if (!isset($_GET['id'])) {
    echo "<script>window.location='manage_stories.php';</script>";
    exit();
}

$id = $_GET['id'];
$msg = "";

// 2. ดึงข้อมูลเดิมออกมา
$sql = "SELECT * FROM stories WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if (!$row) { die("ไม่พบข้อมูลนิยาย"); }

// 3. ถ้ามีการกดบันทึก (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $category = $conn->real_escape_string($_POST['category']);
    $description = $conn->real_escape_string($_POST['description']);
    
    // --- จัดการรูปภาพ (Logic สำคัญ) ---
    $cover_sql_part = ""; // ตัวแปรสำหรับเก็บคำสั่ง SQL ส่วนรูปภาพ

    // ถ้ามีการเลือกไฟล์รูปใหม่เข้ามา
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] == 0) {
        
        // A. ลบรูปเก่าทิ้งก่อน (เพื่อไม่ให้รก Server)
        if (!empty($row['cover_image'])) {
            $old_file = "../uploads/covers/" . $row['cover_image'];
            if (file_exists($old_file)) {
                unlink($old_file); // คำสั่งลบไฟล์
            }
        }

        // B. อัพโหลดรูปใหม่
        $ext = pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION);
        $new_name = "cover_" . time() . "." . $ext;
        $upload_path = "../uploads/covers/" . $new_name;
        
        if (move_uploaded_file($_FILES['cover']['tmp_name'], $upload_path)) {
            // เพิ่มคำสั่ง SQL สำหรับอัพเดทชื่อรูป
            $cover_sql_part = ", cover_image = '$new_name'"; 
        }
    }
    // --------------------------------

    // 4. อัพเดทข้อมูลลง Database
    $sql_update = "UPDATE stories SET 
                   title = '$title', 
                   category = '$category', 
                   description = '$description' 
                   $cover_sql_part 
                   WHERE id = $id";

    if ($conn->query($sql_update) === TRUE) {
        // รีเฟรชหน้าเพื่อดึงข้อมูลใหม่มาแสดง
        echo "<script>alert('✅ แก้ไขข้อมูลเรียบร้อย!'); window.location='manage_stories.php';</script>";
    } else {
        $msg = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}
?>

<style>
    .form-card { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); max-width: 800px; margin: auto; }
    .form-group { margin-bottom: 20px; }
    .form-label { font-weight: 600; color: #334155; display: block; margin-bottom: 8px; }
    .form-control { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; }
    
    .btn-save { background: #e91e63; color: white; border: none; padding: 12px 25px; border-radius: 50px; font-weight: bold; cursor: pointer; width: 100%; }
    .btn-save:hover { background: #c2185b; }
    
    .current-img { width: 100px; height: 140px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd; margin-top: 10px; }
</style>

<div class="header-flex" style="max-width: 800px; margin: 0 auto 20px auto;">
    <h2 style="margin:0; color:#334155;"><i class="fas fa-edit"></i> แก้ไขข้อมูลนิยาย</h2>
</div>

<div class="form-card">
    <?php echo $msg; ?>
    
    <form method="post" enctype="multipart/form-data">
        
        <div class="form-group">
            <label class="form-label">ชื่อเรื่อง</label>
            <input type="text" name="title" class="form-control" required value="<?php echo htmlspecialchars($row['title']); ?>">
        </div>

        <div class="form-group">
            <label class="form-label">หมวดหมู่</label>
            <select name="category" class="form-control">
                <option value="Horror" <?php echo ($row['category']=='Horror')?'selected':''; ?>>👻 สยองขวัญ</option>
                <option value="Romance" <?php echo ($row['category']=='Romance')?'selected':''; ?>>💖 รักโรแมนติก</option>
                <option value="Action" <?php echo ($row['category']=='Action')?'selected':''; ?>>💥 แอคชั่น</option>
                <option value="Fantasy" <?php echo ($row['category']=='Fantasy')?'selected':''; ?>>✨ แฟนตาซี</option>
                <option value="Comedy" <?php echo ($row['category']=='Comedy')?'selected':''; ?>>😂 ตลก</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">รูปหน้าปก</label>
            <input type="file" name="cover" class="form-control" accept="image/*">
            <div style="font-size: 13px; color: #888; margin-top: 5px;">* หากไม่ต้องการเปลี่ยนรูป ให้เว้นว่างไว้</div>
            
            <?php if (!empty($row['cover_image'])): ?>
                <div style="margin-top: 10px;">
                    <p style="font-size:14px; margin-bottom:5px;">รูปปัจจุบัน:</p>
                    <img src="../uploads/covers/<?php echo $row['cover_image']; ?>" class="current-img">
                </div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label class="form-label">เรื่องย่อ</label>
            <textarea name="description" class="form-control" rows="5"><?php echo htmlspecialchars($row['description']); ?></textarea>
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit" class="btn-save">บันทึกการแก้ไข</button>
            <a href="manage_stories.php" style="background:#f1f5f9; color:#334155; padding:12px 25px; border-radius:50px; text-decoration:none; font-weight:bold; display:inline-block; text-align:center;">ยกเลิก</a>
        </div>
        
    </form>
</div>

</div></div></body></html>