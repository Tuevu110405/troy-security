<?php
session_start();
require_once 'db_connect.php';

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit();
}

// 2. Kiểm tra dữ liệu gửi lên
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['target_username'])) {
    $my_username = $_SESSION['username'];
    $target_username = trim($_POST['target_username']);

    try {
        // Bắt đầu Transaction để cập nhật đồng thời 2 dòng
        $conn->beginTransaction();

        // Câu lệnh SQL cập nhật trạng thái thành 'friend'
        $sql = "UPDATE friendships SET status = 'friend' 
                WHERE (username1 = :u1 AND username2 = :u2) 
                   OR (username1 = :u2 AND username2 = :u1)";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':u1' => $my_username,
            ':u2' => $target_username
        ]);

        // Xác nhận thay đổi vào database
        $conn->commit();
    } catch (PDOException $e) {
        // Nếu lỗi, hoàn tác toàn bộ dữ liệu
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        // Có thể ghi log lỗi $e->getMessage() nếu cần
    }
}

// 3. Quay lại trang index.php (trang chủ) [cite: 23]
header("Location: index.php");
exit();
?>
