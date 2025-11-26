<?php
session_start();
include '../db.php';

// เช็คสิทธิ์ Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { exit(); }

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 1. ลบรูปปกออกจากโฟลเดอร์ (เพื่อไม่ให้รก Server)
    $q = $conn->query("SELECT cover_image FROM stories WHERE id = $id");
    $row = $q->fetch_assoc();
    if ($row && !empty($row['cover_image'])) {
        $file_path = "../uploads/covers/" . $row['cover_image'];
        if (file_exists($file_path)) {
            unlink($file_path); // คำสั่งลบไฟล์
        }
    }

    // 2. ลบข้อมูลนิยาย (Stories)
    $conn->query("DELETE FROM stories WHERE id = $id");

    // 3. ลบตอนทั้งหมดของนิยายเรื่องนี้ (Chapters)
    $conn->query("DELETE FROM chapters WHERE story_id = $id");

    echo "<script>alert('ลบข้อมูลเรียบร้อย'); window.location='manage_stories.php';</script>";
}
?>