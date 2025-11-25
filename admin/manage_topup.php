<?php include 'header.php'; ?>

    <h1>💰 จัดการการแจ้งโอนเงิน</h1>
    
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h3>⏳ รายการรอตรวจสอบ (Pending)</h3>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ผู้ใช้งาน (User)</th>
                    <th>ยอดเงิน</th>
                    <th>เวลาแจ้ง</th>
                    <th>หลักฐาน</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT t.*, u.username FROM pending_topups t 
                        JOIN users u ON t.user_id = u.id 
                        WHERE t.status = 'pending' ORDER BY t.created_at ASC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>#{$row['id']}</td>";
                        echo "<td><strong>{$row['username']}</strong></td>";
                        echo "<td style='color: #27ae60; font-weight: bold;'>฿" . number_format($row['amount'], 2) . "</td>";
                        echo "<td>" . date('d/m/Y H:i', strtotime($row['created_at'])) . "</td>";
                        
                        // ถ้ามีรูปลิงก์สลิป
                        echo "<td>-</td>"; 

                        echo "<td>
                                <form action='process_topup.php' method='POST' style='display:inline-block;'>
                                    <input type='hidden' name='topup_id' value='{$row['id']}'>
                                    <input type='hidden' name='user_id' value='{$row['user_id']}'>
                                    <input type='hidden' name='amount' value='{$row['amount']}'>
                                    
                                    <button type='submit' name='action' value='approve' class='btn btn-success' onclick=\"return confirm('ยืนยันยอดเงินถูกต้อง?');\">
                                        <i class='fas fa-check'></i> อนุมัติ
                                    </button>
                                    
                                    <button type='submit' name='action' value='reject' class='btn btn-danger' onclick=\"return confirm('ปฏิเสธรายการนี้?');\">
                                        <i class='fas fa-times'></i> ปฏิเสธ
                                    </button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align:center; padding: 30px; color: #999;'>✅ ไม่มีรายการรอตรวจสอบขณะนี้</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="card">
        <h3>📜 ประวัติการทำรายการล่าสุด (20 รายการ)</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>ยอดเงิน</th>
                    <th>สถานะ</th>
                    <th>เวลา</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_hist = "SELECT t.*, u.username FROM pending_topups t 
                             JOIN users u ON t.user_id = u.id 
                             WHERE t.status != 'pending' ORDER BY t.created_at DESC LIMIT 20";
                $res_hist = $conn->query($sql_hist);
                while ($row = $res_hist->fetch_assoc()) {
                    $status_badge = ($row['status'] == 'success' || $row['status'] == 'approved') 
                        ? '<span class="badge bg-success">สำเร็จ</span>' 
                        : '<span class="badge bg-danger">ปฏิเสธ/ล้มเหลว</span>';
                        
                    echo "<tr>
                            <td>#{$row['id']}</td>
                            <td>{$row['username']}</td>
                            <td>฿" . number_format($row['amount'], 2) . "</td>
                            <td>{$status_badge}</td>
                            <td>" . date('d/m/Y H:i', strtotime($row['created_at'])) . "</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>