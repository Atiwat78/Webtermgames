<?php
session_start();
require_once '../db.php';

// เช็คสิทธิ์ Admin อีกรอบเพื่อความปลอดภัย
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $topup_id = intval($_POST['topup_id']);
    $user_id = intval($_POST['user_id']);
    $amount = floatval($_POST['amount']);
    $action = $_POST['action'];

    if ($action === 'approve') {
        // 1. อัปเดตสถานะเป็น success
        $sql_update = "UPDATE pending_topups SET status = 'success' WHERE id = ?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("i", $topup_id);
        
        if ($stmt->execute()) {
            // 2. เพิ่มเหรียญให้ลูกค้า (ใช้ ceil ปัดเศษขึ้น ตามกติกาเว็บคุณ)
            $coins_to_add = ceil($amount);

            $sql_coin = "UPDATE users SET coins = coins + ? WHERE id = ?";
            $stmt_coin = $conn->prepare($sql_coin);
            $stmt_coin->bind_param("di", $coins_to_add, $user_id);
            $stmt_coin->execute();

            echo "<script>
                    alert('✅ อนุมัติเรียบร้อย! ลูกค้าได้รับ {$coins_to_add} เหรียญ');
                    window.location.href = 'manage_topup.php';
                  </script>";
        }

    } elseif ($action === 'reject') {
        // ปฏิเสธรายการ
        $sql_reject = "UPDATE pending_topups SET status = 'failed' WHERE id = ?";
        $stmt = $conn->prepare($sql_reject);
        $stmt->bind_param("i", $topup_id);
        $stmt->execute();

        echo "<script>
                alert('❌ ปฏิเสธรายการเรียบร้อย');
                window.location.href = 'manage_topup.php';
              </script>";
    }
} else {
    header("Location: manage_topup.php");
}
?>