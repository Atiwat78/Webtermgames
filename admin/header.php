<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// เชื่อมต่อฐานข้อมูล (ใช้ ../db.php เพราะไฟล์นี้อยู่ใน folder admin)
require_once '../db.php'; 

// เช็คสิทธิ์ Admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Elite Topup</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* --- Reset & Core --- */
        * { box-sizing: border-box; }
        body { 
            font-family: 'Sarabun', sans-serif; 
            background-color: #f3f4f6; /* พื้นหลังสีเทาอ่อนสบายตา */
            margin: 0; 
            display: flex;
            min-height: 100vh;
        }

        /* --- 1. Sidebar (เมนูซ้าย) --- */
        .sidebar {
            width: 260px;
            background: #1e293b; /* สีน้ำเงินเข้มทันสมัย */
            color: #fff;
            position: fixed;
            top: 0; left: 0; height: 100%;
            display: flex; flex-direction: column;
            transition: 0.3s;
            z-index: 1000;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar-brand {
            height: 70px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; font-weight: 800;
            color: #FFC107; /* สีทอง */
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background: #0f172a;
        }
        
        .sidebar-menu { padding: 20px 0; flex: 1; }
        
        .menu-item {
            padding: 15px 25px;
            display: flex; align-items: center;
            color: #94a3b8;
            text-decoration: none;
            transition: 0.3s;
            font-size: 16px;
            border-left: 4px solid transparent;
        }
        .menu-item i { width: 30px; font-size: 18px; }
        
        .menu-item:hover, .menu-item.active {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
            border-left-color: #FFC107;
        }

        /* --- 2. Main Layout --- */
        .main-wrapper {
            margin-left: 260px;
            width: calc(100% - 260px);
            display: flex; flex-direction: column;
        }

        /* --- 3. Top Navbar (แถบบน) --- */
        .top-navbar {
            height: 70px;
            background: #fff;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            position: sticky; top: 0; z-index: 900;
        }
        
        .nav-title { font-size: 18px; font-weight: 600; color: #334155; }
        
        .user-profile {
            display: flex; align-items: center; gap: 15px;
        }
        .user-info { text-align: right; }
        .user-info span { display: block; font-size: 14px; font-weight: 700; color: #334155; }
        .user-info small { display: block; font-size: 12px; color: #27ae60; font-weight: 600; }
        
        .avatar-circle {
            width: 40px; height: 40px;
            background: #FFC107; color: #1e293b;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: bold; font-size: 18px;
        }

        /* --- 4. Content Area --- */
        .content { padding: 30px; }

        /* --- Card Style --- */
        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
            padding: 25px;
            border: 1px solid #e2e8f0;
            margin-bottom: 25px;
        }

        /* --- Table Style --- */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #f8fafc; padding: 15px; text-align: left; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0; }
        td { padding: 15px; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: middle; }
        tr:hover { background: #f8fafc; }

        /* --- Buttons & Badges --- */
        .btn { padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer; font-size: 14px; transition: 0.2s; text-decoration:none; display:inline-block;}
        .btn-success { background: #10b981; color: white; } .btn-success:hover { background: #059669; }
        .btn-danger { background: #ef4444; color: white; } .btn-danger:hover { background: #dc2626; }
        
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .bg-warning { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
        .bg-success { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; }
        .bg-danger { background: #fef2f2; color: #b91c1c; border: 1px solid #fee2e2; }

    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-gem" style="margin-right: 10px;"></i> ELITE ADMIN
    </div>
    
    <div class="sidebar-menu">
        <a href="index.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
            <i class="fas fa-chart-pie"></i> &nbsp;ภาพรวมระบบ
        </a>

        <a href="manage_topup.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'manage_topup.php' ? 'active' : ''; ?>">
            <i class="fas fa-wallet"></i> &nbsp;อนุมัติเติมเงิน
        </a>
        
        <div style="border-top: 1px solid rgba(255,255,255,0.1); margin: 10px 20px;"></div>
        <small style="padding: 0 25px; color: #64748b; text-transform: uppercase; font-size: 12px; font-weight: bold;">เมนูนิยาย</small>

        <a href="manage_stories.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'manage_stories.php' || basename($_SERVER['PHP_SELF']) == 'add_story.php' ? 'active' : ''; ?>">
            <i class="fas fa-book"></i> &nbsp;จัดการเรื่อง/หน้าปก
        </a>

        <a href="manage_chapters.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'manage_chapters.php' ? 'active' : ''; ?>">
            <i class="fas fa-list-ul"></i> &nbsp;จัดการตอนเนื้อหา
        </a>

        <a href="add_chapter.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'add_chapter.php' ? 'active' : ''; ?>" style="color: #fda4af;">
            <i class="fas fa-plus-circle"></i> &nbsp;เพิ่มตอนใหม่ด่วน
        </a>

        <div style="border-top: 1px solid rgba(255,255,255,0.1); margin: 10px 20px;"></div>

        <a href="../topup.php" class="menu-item" target="_blank">
            <i class="fas fa-external-link-alt"></i> &nbsp;ไปหน้าเว็บไซต์หลัก
        </a>
    </div>

    <div style="margin-top: auto; padding: 20px;">
        <a href="../logout.php" class="btn btn-danger" style="width: 100%; text-align: center;">
            <i class="fas fa-sign-out-alt"></i> ออกจากระบบ
        </a>
    </div>
</div>

<div class="main-wrapper">

    <div class="top-navbar">
        <div class="nav-title">
            <?php 
                $page = basename($_SERVER['PHP_SELF']);
                if($page == 'index.php') echo 'Dashboard (ภาพรวม)';
                elseif($page == 'manage_topup.php') echo 'จัดการการเติมเงิน';
                elseif($page == 'manage_chapters.php') echo 'จัดการตอนนิยาย';
                elseif($page == 'add_chapter.php') echo 'เพิ่มตอนนิยายใหม่';
                elseif($page == 'edit_chapter.php') echo 'แก้ไขตอนนิยาย';
                else echo 'Admin Panel';
            ?>
        </div>
        
        <div class="user-profile">
            <div class="user-info">
                <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <small>Super Admin</small>
            </div>
            <div class="avatar-circle">
                <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
            </div>
        </div>
    </div>

    <div class="content">