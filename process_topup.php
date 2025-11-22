<?php
session_start();
require_once 'db.php';

if (!isset($_GET['id']) || !isset($_GET['action'])) {
    header("Location: admin_topup.php");
    exit();
}

$id = intval($_GET['id']);
$action = $_GET['action'];

// 1. ดึงข้อมูลรายการเติมเงินมาก่อน
$sql = "SELECT * FROM pending_topups WHERE id = ? AND status = 'pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$transaction = $result->fetch_assoc();

if (!$transaction) {
    echo "<script>alert('ไม่พบรายการ หรือรายการถูกดำเนินการไปแล้ว'); window.location='admin_topup.php';</script>";
    exit();
}

$user_id = $transaction['user_id'];
$amount = $transaction['amount']; 

if ($action == 'approve') {
    // --- ✅ กรณีอนุมัติ (Approve) ---
    
    // A. ปรับสถานะใน pending_topups เป็น completed
    $update_sql = "UPDATE pending_topups SET status = 'completed' WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // B. เพิ่มเงินเข้าช่อง 'coins' ในตาราง users
    // (จุดที่แก้ไข: เปลี่ยนจาก points เป็น coins ให้ตรงกับ database ของคุณ)
    $coin_sql = "UPDATE users SET coins = coins + ? WHERE id = ?";
    $stmt = $conn->prepare($coin_sql);
    $stmt->bind_param("di", $amount, $user_id);
    $stmt->execute();

} elseif ($action == 'reject') {
    // --- ❌ กรณียกเลิก (Reject) ---
    $update_sql = "UPDATE pending_topups SET status = 'cancelled' WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// ทำเสร็จแล้วเด้งกลับไปหน้า Admin
header("Location: admin_topup.php");
exit();
?>