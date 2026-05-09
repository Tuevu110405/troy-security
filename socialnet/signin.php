<?php
session_start(); // Bắt đầu session
require_once 'db_connect.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    try {
        // Tìm user trong database
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':username' => $user]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Kiểm tra mật khẩu (dùng password_verify vì bạn đã mã hóa hash ở signup)
        if ($row && password_verify($pass, $row['password'])) {
            // Đăng nhập thành công -> Lưu session
            $_SESSION['username'] = $row['username'];
            $_SESSION['fullname'] = $row['fullname'];
            
            // Chuyển hướng đến trang home
            header("Location: home.php");
            exit();
        } else {
            $error = "Tài khoản hoặc mật khẩu không đúng!";
        }
    } catch(PDOException $e) {
        $error = "Lỗi hệ thống: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<!-- Phần HTML bên dưới giữ nguyên của bạn, chỉ thêm hiển thị lỗi -->
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập hệ thống</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,0.1); width: 100%; max-width: 350px; }
        h2 { text-align: center; color: #1c1e21; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 600; font-size: 14px; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background-color: #1877f2; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; margin-top: 10px; }
        button:hover { background-color: #166fe5; }
        .footer { text-align: center; margin-top: 15px; font-size: 13px; color: #606770; }
        .footer a { color: #1877f2; text-decoration: none; }
        .error { color: red; text-align: center; margin-bottom: 15px; font-size: 14px; }
    </style>
</head>
<body>

    <div class="login-box">
        <h2>Đăng nhập</h2>
        <?php if ($error) echo "<div class='error'>$error</div>"; ?>
        <form action="signin.php" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Đăng nhập</button>
        </form>
        <div class="footer">
            Chưa có tài khoản? <a href="signup.php">Đăng ký ngay</a>
        </div>
    </div>
</body>
</html>
