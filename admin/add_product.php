<?php
session_start();
// เชื่อมต่อฐานข้อมูล (ถอยกลับไป 1 ชั้นเพื่อหา db.php)
require_once '../db.php';

// 🔒 เช็คว่าเป็น Admin ไหม (ถ้าไม่ใช่ เตะออก)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$message = ""; // ตัวแปรไว้โชว์แจ้งเตือน

// 📥 เมื่อมีการกดปุ่ม "บันทึกสินค้า"
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $desc = $_POST['description'];

    // 📂 จัดการอัปโหลดรูปภาพ
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
        
        // ตั้งชื่อไฟล์ใหม่กันซ้ำ (เช่น product_17012345.jpg)
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_name = "art_" . time() . "." . $ext;
        
        // path ที่จะย้ายไฟล์ไปเก็บ (เก็บในโฟลเดอร์ image หน้าบ้าน)
        $upload_path = "../image/" . $new_name;
        // path ที่จะบันทึกลง Database (ต้องเรียกจากหน้าบ้าน)
        $db_path = "image/" . $new_name;

        // ย้ายไฟล์จาก Temp ไปโฟลเดอร์จริง
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            
            // ✅ บันทึกลงฐานข้อมูล
            $sql = "INSERT INTO products (name, price, image, description) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("siss", $name, $price, $db_path, $desc);

            if ($stmt->execute()) {
                $message = "<div class='alert success'>✅ เพิ่มสินค้าเรียบร้อยแล้ว!</div>";
            } else {
                $message = "<div class='alert error'>❌ Database Error: " . $stmt->error . "</div>";
            }
        } else {
            $message = "<div class='alert error'>❌ อัปโหลดรูปภาพไม่สำเร็จ (ตรวจสอบโฟลเดอร์ image)</div>";
        }
    } else {
        $message = "<div class='alert error'>❌ กรุณาเลือกรูปภาพ</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Admin - เพิ่มสินค้าใหม่</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; background-color: #f4f7f6; padding: 20px; }
        
        .container {
            max-width: 600px; margin: 0 auto; background: white;
            padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        h2 { text-align: center; color: #333; margin-bottom: 25px; }
        
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 600; color: #555; }
        
        input[type="text"], input[type="number"], textarea, input[type="file"] {
            width: 100%; padding: 10px; border: 1px solid #ddd; 
            border-radius: 8px; box-sizing: border-box; font-family: 'Sarabun';
        }
        
        .btn-save {
            width: 100%; background: #28a745; color: white; border: none;
            padding: 12px; border-radius: 8px; font-size: 16px; font-weight: bold;
            cursor: pointer; margin-top: 10px; transition: 0.3s;
        }
        .btn-save:hover { background: #218838; }
        
        .btn-back {
            display: block; text-align: center; margin-top: 15px; 
            color: #666; text-decoration: none;
        }
        
        .alert { padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

<div class="container">
    <h2>📦 เพิ่มสินค้าใหม่</h2>
    
    <?php echo $message; ?>

    <form action="" method="post" enctype="multipart/form-data">
        
        <div class="form-group">
            <label>ชื่อภาพ / สินค้า</label>
            <input type="text" name="name" required placeholder="เช่น Golden Dragon">
        </div>

        <div class="form-group">
            <label>ราคา (เหรียญ)</label>
            <input type="number" name="price" required placeholder="เช่น 50">
        </div>

        <div class="form-group">
            <label>รายละเอียด</label>
            <textarea name="description" rows="3" placeholder="คำอธิบายสั้นๆ..."></textarea>
        </div>

        <div class="form-group">
            <label>รูปภาพ (เลือกไฟล์จากเครื่อง)</label>
            <input type="file" name="image" accept="image/*" required>
        </div>

        <button type="submit" name="submit" class="btn-save">💾 บันทึกสินค้า</button>
    </form>

    <a href="index.php" class="btn-back">← กลับไปหน้า Dashboard</a>
</div>

</body>
</html>