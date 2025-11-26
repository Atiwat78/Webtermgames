<?php
include 'header.php'; 

// 1. ดึงรายชื่อนิยายทั้งหมดออกมาก่อน (เรียงจากเรื่องล่าสุด)
$stories_sql = "SELECT * FROM stories ORDER BY id DESC";
$stories_result = $conn->query($stories_sql);
?>

<style>
    .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .btn-add { background: #e91e63; color: white; padding: 10px 20px; border-radius: 50px; text-decoration: none; font-weight: bold; box-shadow: 0 4px 10px rgba(233, 30, 99, 0.3); transition: 0.3s; display: inline-flex; align-items: center; gap: 5px; border: none; }
    .btn-add:hover { background: #c2185b; transform: translateY(-2px); color: white; }
    
    /* สไตล์ของกล่องแต่ละเรื่อง */
    .story-block { margin-bottom: 40px; } /* เว้นระยะห่างแต่ละเรื่อง */
    .story-header { 
        display: flex; align-items: center; gap: 10px; 
        margin-bottom: 10px; padding-left: 5px; border-left: 5px solid #e91e63; 
    }
    .story-header h3 { margin: 0; color: #1e293b; font-size: 20px; }
    .badge-cat { background: #f1f5f9; color: #64748b; padding: 2px 8px; border-radius: 4px; font-size: 12px; }

    .table-card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
    .custom-table th { background: #334155; color: white; border: none; padding: 12px 15px; font-weight: 500; font-size: 14px; }
    .custom-table td { border-bottom: 1px solid #f1f5f9; padding: 12px 15px; vertical-align: middle; font-size: 14px; }
    .custom-table tr:hover { background: #f8fafc; }
    
    .badge-ep { background: #e0f2fe; color: #0284c7; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 12px; }
    .action-btn { padding: 4px 8px; border-radius: 4px; font-size: 12px; margin-right: 3px; }
</style>

<div class="header-flex">
    <h2 style="margin:0; color:#334155;">
        <i class="fas fa-layer-group"></i> จัดการตอนนิยาย (แยกตามเรื่อง)
    </h2>
    <a href="add_chapter.php" class="btn-add">
        <i class="fas fa-plus"></i> เพิ่มตอนใหม่
    </a>
</div>

<?php while($story = $stories_result->fetch_assoc()): ?>
    
    <?php 
        // 2. ดึงตอนของเรื่องนี้ออกมา (WHERE story_id = ...)
        $s_id = $story['id'];
        $chapters_sql = "SELECT * FROM chapters WHERE story_id = $s_id ORDER BY ep_num ASC";
        $chapters_result = $conn->query($chapters_sql);
    ?>

    <div class="story-block">
        <div class="story-header">
            <h3><?php echo $story['title']; ?></h3>
            <span class="badge-cat"><?php echo $story['category']; ?></span>
            <span style="font-size:12px; color:#999;">(ทั้งหมด <?php echo $chapters_result->num_rows; ?> ตอน)</span>
        </div>

        <div class="table-card">
            <table class="custom-table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="10%">ตอนที่</th>
                        <th>ชื่อตอน</th>
                        <th width="20%">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($chapters_result->num_rows > 0): ?>
                        <?php while($row = $chapters_result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <span class="badge-ep">EP. <?php echo $row['ep_num']; ?></span>
                            </td>
                            <td style="font-weight: 500; color: #334155;">
                                <?php echo $row['title']; ?>
                            </td>
                            <td>
                                <a href="edit_chapter.php?id=<?php echo $row['id']; ?>" class="btn btn-warning action-btn" style="color: #7c2d12; background-color: #ffedd5; border: 1px solid #fed7aa;">
                                    <i class="fas fa-edit"></i> แก้ไข
                                </a>
                                <a href="delete_chapter.php?id=<?php echo $row['id']; ?>" class="btn btn-danger action-btn" style="color: #991b1b; background-color: #fee2e2; border: 1px solid #fecaca;" onclick="return confirm('⚠️ ยืนยันลบตอนนี้?')">
                                    <i class="fas fa-trash-alt"></i> ลบ
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 20px; color: #94a3b8;">
                                -- ยังไม่มีตอนในเรื่องนี้ --
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php endwhile; ?>
</div> 
</div> 
</body>
</html>