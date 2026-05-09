<?php
// Bắt đầu session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Chặn người dùng chưa đăng nhập
if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit();
}

// Lấy tên file hiện tại để đánh dấu Tab Active
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống SocialNet</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background-color: #f0f2f5; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden; }
        
        /* Menu Tabs */
        .tab-menu { display: flex; background-color: #1877f2; }
        .tab-menu a {
            flex: 1; padding: 15px; text-align: center; color: white; text-decoration: none; font-size: 16px; font-weight: bold; transition: 0.3s;
        }
        .tab-menu a:hover { background-color: #166fe5; }
        .tab-menu a.active { background-color: #0c56c2; border-bottom: 3px solid white; }
        
        /* Khung chứa nội dung chung */
        .content { padding: 30px; min-height: 300px; }
        
        /* CSS cho form settings */
        textarea { width: 100%; height: 150px; padding: 10px; border: 1px solid #ccc; border-radius: 6px; resize: vertical; box-sizing: border-box; font-family: inherit;}
        .btn-save { padding: 10px 20px; background: #42b72a; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; margin-top: 10px; }
        .btn-save:hover { background: #36a420; }
        .alert { padding: 10px; background-color: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 15px;}
        
        /* CSS cho profile */
        .profile-display { background: #f8f9fa; padding: 15px; border-radius: 6px; border: 1px solid #ddd; white-space: pre-wrap; }
    </style>
</head>
<body>

<div class="container">
    <div class="tab-menu">
        <a href="home.php" class="<?= ($current_page == 'home.php') ? 'active' : '' ?>">Home</a>
        <a href="settings.php" class="<?= ($current_page == 'settings.php') ? 'active' : '' ?>">Settings</a>
        <a href="profile.php" class="<?= ($current_page == 'profile.php') ? 'active' : '' ?>">Profile</a>
	<a href="logout.php">Logout</a>
	<a href="about.php" class="<?= ($current_page == 'about.php') ? 'active' : '' ?>">About</a>
    </div>
    
    <!-- Phần thân nội dung mở ra ở đây, các file con sẽ đổ dữ liệu vào và đóng thẻ div lại -->
    <div class="content">
