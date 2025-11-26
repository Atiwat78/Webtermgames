<?php
include 'header.php'; 

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $category = $conn->real_escape_string($_POST['category']);
    $description = $conn->real_escape_string($_POST['description']);
    
    // --- ส่วนจัดการอัพโหลดรูป ---
    $cover_image = ""; // ค่าเริ่มต้น (ไม่มีรูป)
    
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] == 0) {
        // ตั้งชื่อไฟล์ใหม่เป็นเวลา (กันชื่อซ้ำ) เช่น cover_1709234.jpg
        $ext = pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION);
        $new_name = "cover_" . time() . "." . $ext;
        
        // ย้ายไฟล์ไปที่โฟลเดอร์ uploads/covers/
        $upload_path = "../uploads/covers/" . $new_name;
        
        // สร้างโฟลเดอร์ถ้ายังไม่มี
        if (!file_exists('../uploads/covers/')) {
            mkdir('../uploads/covers/', 0777, true);
        }

        if (move_uploaded_file($_FILES['cover']['tmp_name'], $upload_path)) {
            $cover_image = $new_name; // เก็บชื่อไฟล์ไว้ลง DB
        }
    }
    // --------------------------

    // บันทึกข้อมูลลง DB
    $sql = "INSERT INTO stories (title, category, description, cover_image) 
            VALUES ('$title', '$category', '$description', '$cover_image')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('✅ สร้างนิยายเรียบร้อย!'); window.location='manage_stories.php';</script>";
    } else {
        $msg = "<div style='color:red; margin-bottom:10px;'>Error: " . $conn->error . "</div>";
    }
}
?>

<div class="header-flex" style="max-width: 800px; margin: 0 auto 20px auto;">
    <h2 style="margin:0; color:#334155;"><i class="fas fa-plus-circle"></i> สร้างนิยายเรื่องใหม่</h2>
</div>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <?php echo $msg; ?>
    
    <form method="post" enctype="multipart/form-data">
        
        <div style="margin-bottom: 15px;">
            <label style="font-weight:bold;">ชื่อเรื่อง</label>
            <input type="text" name="title" class="form-control" required placeholder="เช่น กะดึก... กะหลอน" style="width:100%; padding:10px; margin-top:5px; border:1px solid #ddd; border-radius:5px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="font-weight:bold;">หมวดหมู่</label>
            <select name="category" style="width:100%; padding:10px; margin-top:5px; border:1px solid #ddd; border-radius:5px;">
                <option value="Horror">👻 สยองขวัญ (Horror)</option>
                <option value="Romance">💖 รักโรแมนติก (Romance)</option>
                <option value="Action">💥 แอคชั่น (Action)</option>
                <option value="Fantasy">✨ แฟนตาซี (Fantasy)</option>
                <option value="Comedy">😂 ตลก (Comedy)</option>
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="font-weight:bold;">รูปหน้าปก (แนะนำแนวตั้ง)</label>
            <input type="file" name="cover" accept="image/*" style="margin-top:5px;">
            <div style="font-size:12px; color:#888; margin-top:5px;">รองรับไฟล์ .jpg, .png, .jpeg</div>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="font-weight:bold;">เรื่องย่อ</label>
            <textarea name="description" rows="5" placeholder="เกริ่นนำเรื่องย่อ..." style="width:100%; padding:10px; margin-top:5px; border:1px solid #ddd; border-radius:5px;"></textarea>
        </div>

        <button type="submit" class="btn btn-success" style="width:100%; padding:12px; background:#e91e63; border:none;">บันทึกข้อมูล</button>
        <a href="manage_stories.php" style="display:block; text-align:center; margin-top:15px; color:#666; text-decoration:none;">ยกเลิก</a>
    </form>
</div>

</div></div></body></html>