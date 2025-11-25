<?php
session_start();
// 1. ✅ แก้ Path Database (ถอยหลัง 1 ขั้น)
require_once '../db.php';

// 2. ✅ เพิ่มระบบป้องกัน: ต้องเป็น Admin เท่านั้นถึงจะรันไฟล์นี้ได้
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // ถ้าไม่ใช่ Admin ดีดออกไปหน้า Login
    header("Location: ../login.php");
    exit();
}

// 3. ✅ เปลี่ยนรับค่าเป็น POST (ตามฟอร์มในหน้า manage_topup.php)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id = intval($_POST['topup_id']);
    $user_id = intval($_POST['user_id']); // รับค่า user_id มาเลย ไม่ต้อง query ซ้ำ
    $amount = floatval($_POST['amount']);
    $action = $_POST['action'];

    if ($action == 'approve') {
        // --- ✅ กรณีอนุมัติ (Approve) ---
        
        // A. ปรับสถานะเป็น 'success' (เพื่อให้ตรงกับหน้าแสดงผล)
        $update_sql = "UPDATE pending_topups SET status = 'success' WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            // B. เพิ่มเงินเข้าช่อง 'coins'
            // 💡 สำคัญ: ใช้ ceil() ปัดเศษขึ้นตามกฎร้านคุณ (เช่น 19.15 -> 20 Coins)
            $coins_to_add = ceil($amount); 

            $coin_sql = "UPDATE users SET coins = coins + ? WHERE id = ?";
            $stmt = $conn->prepare($coin_sql);
            $stmt->bind_param("di", $coins_to_add, $user_id);
            $stmt->execute();
            
            // แจ้งเตือนและกลับหน้าเดิม
            echo "<script>
                    alert('✅ อนุมัติสำเร็จ! ลูกค้าได้รับ {$coins_to_add} เหรียญ');
                    window.location.href = 'manage_topup.php';
                  </script>";
        } else {
            echo "Error: " . $conn->error;
        }

    } elseif ($action == 'reject') {
        // --- ❌ กรณียกเลิก (Reject) ---
        
        // ปรับสถานะเป็น 'failed' (เพื่อให้ตรงกับหน้าแสดงผล)
        $update_sql = "UPDATE pending_topups SET status = 'failed' WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        echo "<script>
                alert('❌ ปฏิเสธรายการเรียบร้อย');
                window.location.href = 'manage_topup.php';
              </script>";
    }

} else {
    // ถ้าไม่ได้ส่ง POST มา ให้เด้งกลับ
    header("Location: manage_topup.php");
    exit();
}
?>