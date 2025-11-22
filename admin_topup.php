<?php
session_start();
require_once 'db.php';

// --- ตรวจสอบสิทธิ์แอดมิน (ถ้ามีระบบ Login Admin ให้เปิดใช้ส่วนนี้) ---
// if (!isset($_SESSION['is_admin'])) { header("Location: index.php"); exit(); }

// ดึงข้อมูลรายการที่รอตรวจสอบ (Join กับตาราง users เพื่อเอาชื่อคนเติมมาโชว์)
$sql = "SELECT p.*, u.username 
        FROM pending_topups p 
        JOIN users u ON p.user_id = u.id 
        WHERE p.status = 'pending' 
        ORDER BY p.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ระบบจัดการเติมเงิน (Admin)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 900px; margin-top: 50px; }
        .table-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="container">
        <div class="table-card">
            <h3 class="mb-4 text-primary">👮 รายการแจ้งโอนเงิน (รอตรวจสอบ)</h3>
            
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>ชื่อผู้ใช้ (Username)</th>
                        <th>ยอดเงินที่ต้องเข้า</th>
                        <th>เวลาที่แจ้ง</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($row['username']); ?></strong><br>
                                <small class="text-muted">ID: <?php echo $row['user_id']; ?></small>
                            </td>
                            <td>
                                <span class="badge bg-warning text-dark fs-6">
                                    ฿<?php echo number_format($row['amount'], 2); ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                            <td>
                                <a href="process_topup.php?id=<?php echo $row['id']; ?>&action=approve" 
                                   class="btn btn-success btn-sm"
                                   onclick="return confirm('ยอดเงินถูกต้อง ยืนยันการเติมเหรียญ?');">
                                   ✅ อนุมัติ
                                </a>
                                
                                <a href="process_topup.php?id=<?php echo $row['id']; ?>&action=reject" 
                                   class="btn btn-outline-danger btn-sm"
                                   onclick="return confirm('ต้องการยกเลิกรายการนี้ใช่หรือไม่?');">
                                   ❌ ยกเลิก
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                ยังไม่มีรายการแจ้งโอนใหม่
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <div class="mt-3 text-end">
                <a href="index.php" class="btn btn-secondary">กลับหน้าเว็บหลัก</a>
            </div>
        </div>
    </div>
</body>
</html>