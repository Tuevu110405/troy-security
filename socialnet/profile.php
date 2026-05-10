<?php
require_once 'menu.php'; // Đã bao gồm session_start() và check đăng nhập [cite: 46]
require_once 'db_connect.php';

// 1. Xác định chủ sở hữu của trang profile [cite: 42]
$my_username = $_SESSION['username'];
$owner_username = isset($_GET['owner']) ? trim($_GET['owner']) : $my_username;

$user_info = null;

try {
    // 2. Truy vấn thông tin từ bảng account và trạng thái bạn bè [cite: 8, 45]
    $sql = "SELECT u.username, u.fullname, u.description, f.status
            FROM account u
            LEFT JOIN friendships f ON u.username = f.username2 AND f.username1 = :me
            WHERE u.username = :owner";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':me' => $my_username,
        ':owner' => $owner_username
    ]);

    $user_info = $stmt->fetch(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Lỗi truy vấn: " . $e->getMessage();
}
?>

    <style>
        .profile-container { max-width: 600px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 8px; }
        .status-badge { padding: 6px 12px; border-radius: 15px; font-size: 13px; font-weight: bold; }
        .badge-friend { background: #e7f3ff; color: #1877f2; }
        .badge-sent { background: #f0f2f5; color: #606770; }
        .badge-received { background: #e8f5e9; color: #2e7d32; }
        .btn-save { background: #1877f2; color: white; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-weight: bold; }
        .btn-save:hover { background: #166fe5; }
        .profile-display { background: #f8f9fa; padding: 15px; border-radius: 6px; margin-top: 10px; border: 1px solid #ddd; }
    </style>

    <div class="profile-container">
        <?php if ($user_info): ?>
            <h2 style="margin-bottom: 5px;">Hồ sơ của: <?php echo htmlspecialchars($user_info['fullname']); ?></h2>
            <p style="color: gray; margin-top: 0;">@<?php echo htmlspecialchars($user_info['username']); ?></p>

            <div style="margin: 15px 0;">
                <?php if ($owner_username !== $my_username): ?>
                    <?php if ($user_info['status'] === 'friend'): ?>
                        <span class="status-badge badge-friend">✓ Đã là bạn bè</span>
                    <?php elseif ($user_info['status'] === 'sent'): ?>
                        <span class="status-badge badge-sent">⌛ Đã gửi yêu cầu kết bạn</span>
                    <?php elseif ($user_info['status'] === 'received'): ?>
                        <span class="status-badge badge-received">⬇️ Đang chờ bạn chấp nhận</span>
                    <?php else: ?>
                        <form action="makefriend.php" method="POST">
                            <input type="hidden" name="target_username" value="<?php echo htmlspecialchars($user_info['username']); ?>">
                            <button type="submit" class="btn-save">+ Thêm bạn bè</button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <h4>Giới thiệu bản thân:</h4>
            <div class="profile-display">
                <?php
                    // Đã dọn dẹp sạch lỗi syntax rác
                    echo !empty($user_info['description'])
                        ? nl2br(htmlspecialchars($user_info['description']))
                        : "<em>Người dùng này chưa cập nhật thông tin giới thiệu.</em>";
                ?>
            </div>
        <?php else: ?>
            <p style="color: red;">Không tìm thấy thông tin người dùng này trong hệ thống.</p>
        <?php endif; ?>

        <div style="margin-top: 20px;">
            <a href="index.php" style="color: #1877f2; text-decoration: none; font-weight: 600;">&larr; Quay lại trang chủ</a>
        </div>
    </div>

    </div> </div> </body>
</html>
