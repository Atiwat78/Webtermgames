<?php
// 1. ดึง Header มาใช้ (แก้เรื่อง Path DB และได้เมนูสวยๆ ทันที)
include 'header.php'; 

// ตรวจสอบว่ามี ID ส่งมาไหม
if (!isset($_GET['id'])) {
    echo "<script>alert('ไม่พบรหัสตอน!'); window.location='manage_chapters.php';</script>";
    exit();
}

$id = $_GET['id'];
$msg = "";

// 2. ถ้ามีการกดปุ่ม "บันทึกแก้ไข" (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ep_num = $_POST['ep_num'];
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);

    // อัพเดทข้อมูลลง Database
    $sql = "UPDATE chapters SET ep_num='$ep_num', title='$title', content='$content' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        // บันทึกสำเร็จ ให้ขึ้นข้อความแจ้งเตือน
        $msg = "<div class='alert alert-success'><i class='fas fa-check-circle'></i> บันทึกการแก้ไขเรียบร้อยแล้ว!</div>";
    } else {
        $msg = "<div class='alert alert-danger'>เกิดข้อผิดพลาด: " . $conn->error . "</div>";
    }
}

// 3. ดึงข้อมูลเดิมออกมาแสดงในฟอร์ม
$sql_get = "SELECT * FROM chapters WHERE id = $id";
$result = $conn->query($sql_get);
$row = $result->fetch_assoc();

if(!$row) { die("ไม่พบข้อมูลตอนนี้ในระบบ"); }
?>

<style>
    .form-card { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
    .form-group { margin-bottom: 20px; }
    .form-label { font-weight: 600; color: #334155; display: block; margin-bottom: 8px; }
    .form-control { width: 100%; padding: 10px 15px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: 'Sarabun', sans-serif; font-size: 16px; transition: 0.3s; box-sizing: border-box; }
    .form-control:focus { border-color: #e91e63; outline: none; box-shadow: 0 0 0 3px rgba(233, 30, 99, 0.1); }
    
    .btn-save { background: #e91e63; color: white; border: none; padding: 12px 25px; border-radius: 50px; font-weight: bold; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 10px rgba(233, 30, 99, 0.3); }
    .btn-save:hover { background: #c2185b; transform: translateY(-2px); }
    
    .btn-back { background: #f1f5f9; color: #64748b; text-decoration: none; padding: 12px 20px; border-radius: 50px; font-weight: 600; margin-left: 10px; display: inline-block; }
    .btn-back:hover { background: #e2e8f0; color: #334155; }

    .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
    .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .alert-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
</style>

<div class="header-flex" style="margin-bottom: 20px;">
    <h2 style="margin:0; color:#334155;">
        <i class="fas fa-edit"></i> แก้ไขตอนนิยาย
    </h2>
</div>

<div class="form-card">
    
    <?php echo $msg; ?>

    <form method="post">
        
        <div class="form-group">
            <label class="form-label">ชื่อตอนนิยาย (Title)</label>
            <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($row['title']); ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label">ลำดับตอนที่ (Episode Number)</label>
            <input type="number" name="ep_num" class="form-control" value="<?php echo $row['ep_num']; ?>" style="width: 150px;" required>
        </div>

        <div class="form-group">
            <label class="form-label">เนื้อหานิยาย (Content)</label>
            <textarea name="content" class="form-control" style="height: 400px;" required><?php echo htmlspecialchars($row['content']); ?></textarea>
            <small style="color: #94a3b8; display: block; margin-top: 5px;">* รองรับ HTML Tags เช่น &lt;p&gt;, &lt;b&gt;, &lt;br&gt;</small>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> บันทึกการแก้ไข
            </button>
            <a href="manage_chapters.php" class="btn-back">
                ยกเลิก / กลับ
            </a>
        </div>

    </form>
</div>

</div> </div> </body>
</html>