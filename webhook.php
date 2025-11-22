<?php
// ไฟล์: webhook.php
header('Content-Type: application/json');
require_once 'db.php'; // เรียกฐานข้อมูล

// รับค่าจากที่ส่งมา
$secret_key = "nty55"; // 🔑 รหัสลับต้องตรงกัน
$incoming_secret = $_POST['secret'] ?? '';
$amount = $_POST['amount'] ?? 0;

// 1. เช็ครหัสลับ
if ($incoming_secret !== $secret_key) {
    echo json_encode(['status' => 'error', 'message' => 'รหัสลับไม่ถูกต้อง']);
    exit();
}

// 2. ค้นหารายการที่ "รอตรวจสอบ" (Pending)
// ค้นหาช่วงราคา (เผื่อทศนิยมไม่ตรงกันเป๊ะ) เช่น โอน 20 หาตั้งแต่ 19.00 - 20.50
$min = $amount - 1;
$max = $amount + 0.5;

// แก้ไขบรรทัด SQL เดิม ให้เป็นบรรทัดนี้ครับ
$sql = "SELECT * FROM pending_topups 
        WHERE status = 'pending' 
        AND amount >= ? AND amount <= ? 
        ORDER BY id DESC 
        LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("dd", $min, $max);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    // เจอรายการ! เติมเหรียญให้เลย
    $user_id = $row['user_id'];
    
    // ✅ [แก้ตรงนี้] ใช้ยอดเงินจากในบิลมาปัดเศษขึ้น (19.xx -> 20, 49.xx -> 50)
    $points = ceil($row['amount']); 

    // อัปเดตสถานะเป็น Success
    $conn->query("UPDATE pending_topups SET status = 'success' WHERE id = " . $row['id']);
    
    // เพิ่มเหรียญเข้าตัว

    $conn->query("UPDATE users SET coins = coins + $points WHERE id = $user_id");

    echo json_encode(['status' => 'success', 'message' => 'เติมเงินสำเร็จ! ได้รับ ' . $points . ' เหรียญ']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'ไม่พบรายการสั่งซื้อที่ยอดเงินใกล้เคียงนี้']);
}
?>