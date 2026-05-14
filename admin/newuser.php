<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information Registration</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            margin-top: 0;
            color: #1c1e21;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            font-size: 14px;
            color: #4b4f56;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #dddfe2;
            border-radius: 6px;
            box-sizing: border-box; /* Ensures padding doesn't affect width */
            font-size: 16px;
        }
        input:focus {
            outline: none;
            border-color: #1877f2;
            box-shadow: 0 0 0 2px rgba(24, 119, 242, 0.2);
        }
        .char-limit {
            font-size: 11px;
            color: #90949c;
            margin-top: 3px;
            text-align: right;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #1877f2;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.2s;
        }
        button:hover {
            background-color: #166fe5;
        }
    </style>
</head>
<body>
<?php
session_start();
require_once '../socialnet/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $full = $_POST['fullname'];
    $pass = $_POST['password'];

    // 1. Mã hóa mật khẩu
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    try {
        // 2. Chuẩn bị câu lệnh SQL (chống SQL Injection)
        $sql = "INSERT INTO account (username, fullname, password) VALUES (:username, :fullname, :password)";
        $stmt = $conn->prepare($sql);
        
        // 3. Thực thi
        $stmt->execute([
            ':username' => $user,
            ':fullname' => $full,
            ':password' => $hashed_password
        ]);

	echo "Đăng ký thành công!";
	$_SESSION['username'] = $user;
        $_SESSION['fullname'] = $full;

        // Chuyển hướng về trang index.php
        // Dùng dấu ../ để lùi ra khỏi thư mục admin, sau đó vào thư mục socialnet
        header("Location: ../socialnet/index.php");
        exit();
    } catch(PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "Lỗi: Username đã tồn tại.";
        } else {
            echo "Lỗi hệ thống: " . $e->getMessage();
        }
    }
}
?>
<div class="container">
    <h2>Create Account</h2>
    <form action="newuser.php" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" maxlength="20" required>
            <div class="char-limit">Max 20 characters</div>
        </div>

        <div class="form-group">
            <label for="fullname">Full Name</label>
            <input type="text" id="fullname" name="fullname" maxlength="200" required>
            <div class="char-limit">Max 200 characters</div>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" maxlength="20" required>
            <div class="char-limit">Max 20 characters</div>
        </div>

        <button type="submit">Sign Up</button>
    </form>
</div>

</body>
</html>
