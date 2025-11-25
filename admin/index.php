<?php include 'header.php'; ?>

<h2 style="margin-bottom: 20px; color: #334155;">ยินดีต้อนรับกลับ, คุณ <?php echo htmlspecialchars($_SESSION['username']); ?> 👋</h2>

<div style="display: flex; gap: 20px; flex-wrap: wrap;">
    
    <div class="card" style="flex: 1; min-width: 250px; border-top: 4px solid #3498db;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <p style="margin:0; color:#64748b;">สมาชิกทั้งหมด</p>
                <?php
                    $q1 = $conn->query("SELECT COUNT(*) as c FROM users");
                    echo "<h1 style='margin:5px 0; font-size:36px; color:#334155;'>" . $q1->fetch_assoc()['c'] . "</h1>";
                ?>
            </div>
            <i class="fas fa-users" style="font-size: 40px; color: #e2e8f0;"></i>
        </div>
    </div>

    <div class="card" style="flex: 1; min-width: 250px; border-top: 4px solid #f59e0b;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <p style="margin:0; color:#64748b;">รอตรวจสอบโอนเงิน</p>
                <?php
                    $q2 = $conn->query("SELECT COUNT(*) as c FROM pending_topups WHERE status = 'pending'");
                    $pending = $q2->fetch_assoc()['c'];
                    echo "<h1 style='margin:5px 0; font-size:36px; color:#f59e0b;'>" . $pending . "</h1>";
                ?>
                <a href="manage_topup.php" style="font-size:13px; text-decoration:none; color:#f59e0b;">ไปจัดการรายการ &rarr;</a>
            </div>
            <i class="fas fa-clock" style="font-size: 40px; color: #fef3c7;"></i>
        </div>
    </div>

    <div class="card" style="flex: 1; min-width: 250px; border-top: 4px solid #10b981;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <p style="margin:0; color:#64748b;">เติมเงินวันนี้</p>
                <?php
                    $q3 = $conn->query("SELECT SUM(amount) as total FROM pending_topups WHERE status = 'success' AND DATE(created_at) = CURDATE()");
                    $total = $q3->fetch_assoc()['total'] ?? 0;
                    echo "<h1 style='margin:5px 0; font-size:36px; color:#10b981;'>฿" . number_format($total) . "</h1>";
                ?>
            </div>
            <i class="fas fa-coins" style="font-size: 40px; color: #d1fae5;"></i>
        </div>
    </div>

</div>

</div> </div> </body>
</html>