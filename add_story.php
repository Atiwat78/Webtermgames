<?php
// เชื่อมต่อฐานข้อมูล (ใช้ไฟล์ db.php เดิมของคุณ หรือก๊อปส่วนนี้ไป)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "games"; // เชื่อมต่อฐานข้อมูล games

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $category = $conn->real_escape_string($_POST['category']);
    $description = $conn->real_escape_string($_POST['description']);
    // (อนาคตค่อยทำระบบอัพโหลดรูปปก ตอนนี้เอาชื่อไฟล์ไปก่อน)
    
    $sql = "INSERT INTO stories (title, category, description) VALUES ('$title', '$category', '$description')";

    if ($conn->query($sql) === TRUE) {
        $message = "✅ สร้างเรื่องใหม่สำเร็จ!";
    } else {
        $message = "❌ Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>สร้างเรื่องใหม่</title>
    <style>
        body { font-family: sans-serif; padding: 20px; max-width: 600px; margin: auto; }
        input, select, textarea { width: 100%; padding: 10px; margin: 5px 0 15px; }
        button { background: #007bff; color: white; border: none; padding: 10px 20px; cursor: pointer; }
        .alert { padding: 10px; background: #d4edda; color: #155724; margin-bottom: 15px; }
    </style>
</head>
<body>
    <h2>📚 สร้างนิยายเรื่องใหม่</h2>
    
    <?php if($message) echo "<div class='alert'>$message</div>"; ?>

    <form method="post">
        <label>ชื่อเรื่อง:</label>
        <input type="text" name="title" required placeholder="เช่น กะดึก... กะหลอน">

        <label>หมวดหมู่:</label>
        <select name="category">
            <option value="Horror">สยองขวัญ (Horror)</option>
            <option value="Romance">รักโรแมนติก (Romance)</option>
            <option value="Fantasy">แฟนตาซี (Fantasy)</option>
            <option value="Comedy">ตลก (Comedy)</option>
        </select>

        <label>เรื่องย่อ:</label>
        <textarea name="description" rows="4" placeholder="เกริ่นนำเรื่องย่อ..."></textarea>

        <button type="submit">บันทึกข้อมูล</button>
    </form>
    <br>
    <a href="add_chapter.php">ไปหน้าเพิ่มตอนนิยาย ></a>
</body>
</html>