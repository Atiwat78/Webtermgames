<?php
// 1. ดึง Header มาใช้ (ได้ทั้งเมนูและการเชื่อมต่อฐานข้อมูล)
include 'header.php'; 

$msg = "";

// 2. ถ้ามีการกดปุ่ม "บันทึก" (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $story_id = $_POST['story_id'];
    $ep_num = $_POST['ep_num'];
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);

    // บันทึกลงฐานข้อมูล
    $sql = "INSERT INTO chapters (story_id, ep_num, title, content) 
            VALUES ('$story_id', '$ep_num', '$title', '$content')";

    if ($conn->query($sql) === TRUE) {
        $msg = "<div class='alert alert-success'><i class='fas fa-check-circle'></i> เพิ่มตอนใหม่เรียบร้อยแล้ว!</div>";
    } else {
        $msg = "<div class='alert alert-danger'>เกิดข้อผิดพลาด: " . $conn->error . "</div>";
    }
}

// 3. ดึงรายชื่อนิยายมาใส่ใน Dropdown (เพื่อให้เลือกว่าจะลงเรื่องไหน)
$stories_result = $conn->query("SELECT * FROM stories ORDER BY id DESC");
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
        <i class="fas fa-plus-circle"></i> เพิ่มตอนนิยายใหม่
    </h2>
</div>

<div class="form-card">
    
    <?php echo $msg; ?>

    <form method="post">
        
        <div class="form-group">
            <label class="form-label">เลือกเรื่องที่จะลง (Select Novel)</label>
            <select name="story_id" class="form-control" required>
                <option value="">-- กรุณาเลือกเรื่อง --</option>
                <?php while($story = $stories_result->fetch_assoc()): ?>
                    <option value="<?php echo $story['id']; ?>">
                        <?php echo $story['title']; ?> (หมวด: <?php echo $story['category']; ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="row" style="display:flex; gap: 20px;">
            <div class="form-group" style="flex: 1;">
                <label class="form-label">ตอนที่ (EP Number)</label>
                <input type="number" name="ep_num" class="form-control" placeholder="เช่น 1" required>
            </div>

            <div class="form-group" style="flex: 3;">
                <label class="form-label">ชื่อตอน (Episode Title)</label>
                <input type="text" name="title" class="form-control" placeholder="เช่น บทนำ : จุดเริ่มต้นความสยอง" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">เนื้อหานิยาย (Content)</label>
            <textarea name="content" class="form-control" style="height: 400px;" placeholder="วางเนื้อหานิยายที่นี่... (แนะนำให้ใส่ใน <p>...</p>)" required></textarea>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> บันทึกตอนใหม่
            </button>
            <a href="manage_chapters.php" class="btn-back">
                กลับหน้ารายการ
            </a>
        </div>

    </form>
</div>

</div> </div> </body>
</html>