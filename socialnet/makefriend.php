<?php
session_start();
require_once 'db_connect.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit();
}

// Kiểm tra xem có dữ liệu POST gửi lên không
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['target_username'])) {
    $my_username = $_SESSION['username'];
    $target_username = trim($_POST['target_username']);

    // Bảo mật cơ bản: Không thể tự kết bạn với chính mình
    if ($my_username === $target_username) {
        header("Location: index.php");
        exit();
    }

    try {
        // 1. Kiểm tra xem giữa 2 người đã có quan hệ nào chưa 
        // (đề phòng user spam click hoặc hack form gửi lại)
        $check_sql = "SELECT status FROM friendships WHERE username1 = :me AND username2 = :target";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->execute([':me' => $my_username, ':target' => $target_username]);
        
        if ($check_stmt->rowCount() == 0) {
            
            // 2. Bắt đầu Transaction (Đảm bảo chèn 2 dòng cùng lúc)
            $conn->beginTransaction();

            $sql = "INSERT INTO friendships (username1, username2, status) VALUES (:u1, :u2, :status)";
            $insert_stmt = $conn->prepare($sql);

            // Dòng 1: Mình gửi yêu cầu cho họ -> Trạng thái của mình là 'sent'
            $insert_stmt->execute([
                ':u1' => $my_username,
                ':u2' => $target_username,
                ':status' => 'sent'
            ]);

            // Dòng 2: Họ nhận yêu cầu từ mình -> Trạng thái của họ là 'received'
            $insert_stmt->execute([
                ':u1' => $target_username,
                ':u2' => $my_username,
                ':status' => 'received'
            ]);

            // 3. Nếu cả 2 lệnh đều ổn, xác nhận lưu vĩnh viễn vào Database
            $conn->commit();
        }
    } catch (PDOException $e) {
        // Nếu có bất kỳ lỗi gì xảy ra giữa chừng, Hoàn tác (Rollback) lại toàn bộ
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        // Có thể lưu log lỗi vào file ở đây nếu cần thiết
        // error_log("Lỗi MakeFriend: " . $e->getMessage());
    }
}

// Xử lý xong thì tự động quay về trang Home
header("Location: index.php");
exit();
?>
