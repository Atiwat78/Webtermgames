<?php
// ไฟล์: api/check_player.php
header('Content-Type: application/json');

$player_id = $_POST['player_id'] ?? '';

if (empty($player_id)) {
    echo json_encode(['status' => 'error']);
    exit;
}

// --- จำลองการเช็คชื่อ (Mockup) ---
// ตัวอย่าง: ถ้า ID ลงท้ายด้วยเลข 1 ถือว่าเจอ
if (substr($player_id, -1) == '1') {
    echo json_encode([
        'status' => 'success',
        'name' => 'Gamer_' . substr($player_id, 0, 4) // ชื่อสมมติ
    ]);
} else {
    echo json_encode(['status' => 'fail']);
}
?>