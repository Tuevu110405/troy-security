<?php 
require_once 'menu.php'; 
require_once 'db_connect.php';

// Kiểm tra xem có tham số 'u' trên URL không
if (!isset($_GET['u']) || empty(trim($_GET['u']))) {
    // Nếu không có, đẩy về trang chủ
    echo "<script>window.location.href='home.php';</script>";
    exit();
}

$target_username = trim($_GET['u']);
$my_username = $_SESSION['username'];

// Nếu vô tình click vào tên mình, tự động chuyển về tab Profile cá nhân
if ($target_username === $my_username) {
    echo "<script>window.location.href='profile.php';</script>";
    exit();
}

$user_info = null;
$error_message = "";

try {
    // Truy vấn thông tin của user đó + Trạng thái kết bạn với mình
    $sql = "SELECT u.username, u.fullname, u.profile_text, f.status 
            FROM users u
            LEFT JOIN friendships f ON u.username = f.username2 AND f.username1 = :me
            WHERE u.username = :target";
            
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':me' => $my_username, 
        ':target' => $target_username
    ]);
    
    $user_info = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user_info) {
        $error_message = "Không tìm thấy người dùng này!";
    }
} catch(PDOException $e) {
    $error_message = "Lỗi lấy dữ liệu: " . $e->getMessage();
}
?>

    <style>
        .profile-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ddd; padding-bottom: 15px; margin-bottom: 20px;}
        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: bold; }
        .bg-friend { background: #e7f3ff; color: #1877f2; }
        .bg-sent { background: #f0f2f5; color: #606770; }
        .bg-received { background: #e8f5e9; color: #2e7d32; }
    </style>

    <?php if ($error_message): ?>
        <div class="alert" style="background-color: #f8d7da; color: #721c24;"><?php echo $error_message; ?></div>
        <a href="home.php" style="color: #1877f2; text-decoration: none;">&larr; Quay lại trang chủ</a>
    <?php elseif ($user_info): ?>
        
        <div class="profile-header">
            <div>
                <h2 style="margin: 0 0 5px 0;"><?php echo htmlspecialchars($user_info['fullname']); ?></h2>
                <p style="color: gray; margin: 0;">@<?php echo htmlspecialchars($user_info['username']); ?></p>
            </div>
            
            <!-- Hiển thị huy hiệu trạng thái tùy theo mối quan hệ -->
            <div>
                <?php if ($user_info['status'] === 'friend'): ?>
                    <span class="status-badge bg-friend">✓ Đã là bạn bè</span>
                <?php elseif ($user_info['status'] === 'sent'): ?>
                    <span class="status-badge bg-sent">⌛ Đã gửi lời mời</span>
                <?php elseif ($user_info['status'] === 'received'): ?>
                    <span class="status-badge bg-received">⬇️ Đang chờ bạn phản hồi</span>
                <?php else: ?>
                    <!-- Nếu là người lạ, hiện nút kết bạn ngay trong Profile -->
                    <form action="makefriend.php" method="POST" style="margin: 0;">
                        <input type="hidden" name="target_username" value="<?php echo htmlspecialchars($user_info['username']); ?>">
                        <button type="submit" style="background: #1877f2; color: white; border: none; padding: 8px 15px; border-radius: 6px; font-weight: bold; cursor: pointer;">+ Thêm bạn bè</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <h4>Giới thiệu bản thân:</h4>
        <div class="profile-display">
            <?php 
                echo !empty($user_info['profile_text']) 
                    ? nl2br(htmlspecialchars($user_info['profile_text'])) 
                    : "<em style='color: #90949c;'>Người dùng này chưa cập nhật thông tin giới thiệu.</em>"; 
            ?>
        </div>

        <div style="margin-top: 30px;">
            <a href="home.php" style="color: #1877f2; text-decoration: none;">&larr; Quay lại trang chủ</a>
        </div>

    <?php endif; ?>

    </div> <!-- Đóng thẻ .content từ menu.php -->
</div> <!-- Đóng thẻ .container từ menu.php -->
</body>
</html>
