<?php 
require_once 'menu.php'; 
require_once 'db_connect.php'; 

$my_username = $_SESSION['username'];
$error_message = null;

$friends = [];
$pending_sent = [];
$pending_received = [];
$strangers = [];

try {
    // Truy vấn tất cả user (trừ bản thân) và nối với bảng friendships để lấy trạng thái
    $sql = "SELECT u.username, u.fullname, u.profile_text, f.status 
            FROM users u
            LEFT JOIN friendships f ON u.username = f.username2 AND f.username1 = :my_username
            WHERE u.username != :my_username
            ORDER BY u.fullname ASC";
            
    $stmt = $conn->prepare($sql);
    $stmt->execute([':my_username' => $my_username]);
    $all_users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Phân loại người dùng dựa vào cột status
    foreach ($all_users as $u) {
        if ($u['status'] === 'friend') {
            $friends[] = $u;
        } elseif ($u['status'] === 'sent') {
            $pending_sent[] = $u; // Mình đã gửi lời mời cho họ
        } elseif ($u['status'] === 'received') {
            $pending_received[] = $u; // Họ gửi lời mời cho mình
        } else {
            $strangers[] = $u; // NULL: Chưa có tương tác
        }
    }

} catch(PDOException $e) {
    $error_message = "Lỗi lấy dữ liệu: " . $e->getMessage();
}
?>

    <style>
        .section-title { margin-top: 30px; border-bottom: 2px solid #ddd; padding-bottom: 10px; color: #1c1e21; }
        .user-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 15px; margin-top: 15px; }
        .user-card { background: #f8f9fa; border: 1px solid #ddd; border-radius: 8px; padding: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); text-align: center;}
        .user-card h4 { margin: 0 0 5px 0; color: #1877f2; font-size: 16px; }
        .user-card .username { margin: 0 0 15px 0; font-size: 12px; color: #606770; }
        
        /* Nút hành động */
        .btn-action { width: 100%; padding: 8px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 13px; }
        .btn-add { background-color: #1877f2; color: white; }
        .btn-add:hover { background-color: #166fe5; }
        .btn-waiting { background-color: #e4e6eb; color: #4b4f56; cursor: not-allowed; }
        .btn-accept { background-color: #42b72a; color: white; }
        .btn-friend { background-color: #e4e6eb; color: #050505; cursor: default; }
    </style>

    <h2>Xin chào, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h2>
    
    <?php if ($error_message): ?>
        <div class="alert" style="background-color: #f8d7da; color: #721c24;"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- 1. DANH SÁCH BẠN BÈ -->
    <h3 class="section-title">Bạn bè (<?php echo count($friends); ?>)</h3>
    <div class="user-grid">
        <?php foreach ($friends as $u): ?>
            <div class="user-card">
                <h4>
    			<a href="view_profile.php?u=<?php echo urlencode($u['username']); ?>" style="color: #1877f2; text-decoration: none;">
        <?php echo htmlspecialchars($u['fullname']); ?>
    			</a>
		</h4>
                <p class="username">@<?php echo htmlspecialchars($u['username']); ?></p>
                <button class="btn-action btn-friend">✓ Đã là bạn bè</button>
            </div>
        <?php endforeach; ?>
        <?php if(empty($friends)) echo "<p style='color: #606770; font-size: 14px;'>Chưa có bạn bè nào.</p>"; ?>
    </div>

    <!-- 2. LỜI MỜI KẾT BẠN (Người khác gửi cho mình) -->
    <?php if(!empty($pending_received)): ?>
    <h3 class="section-title">Lời mời kết bạn (<?php echo count($pending_received); ?>)</h3>
    <div class="user-grid">
        <?php foreach ($pending_received as $u): ?>
            <div class="user-card">
                <h4>
    			<a href="view_profile.php?u=<?php echo urlencode($u['username']); ?>" style="color: #1877f2; text-decoration: none;">
        <?php echo htmlspecialchars($u['fullname']); ?>
    			</a>
		</h4>
                <p class="username">@<?php echo htmlspecialchars($u['username']); ?></p>
                <!-- Nút này tạm thời hiển thị, có thể làm tính năng accept sau -->
                <button class="btn-action btn-accept">Chấp nhận</button>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- 3. GỢI Ý KẾT BẠN (Người lạ & Người mình đã gửi yêu cầu) -->
    <h3 class="section-title">Gợi ý kết bạn</h3>
    <div class="user-grid">
        <!-- Đã gửi yêu cầu -->
        <?php foreach ($pending_sent as $u): ?>
            <div class="user-card">
                <h4>
    			<a href="view_profile.php?u=<?php echo urlencode($u['username']); ?>" style="color: #1877f2; text-decoration: none;">
        <?php echo htmlspecialchars($u['fullname']); ?>
    			</a>
		</h4>
                <p class="username">@<?php echo htmlspecialchars($u['username']); ?></p>
                <button class="btn-action btn-waiting" disabled>Đã gửi yêu cầu...</button>
            </div>
        <?php endforeach; ?>

        <!-- Người lạ -->
        <?php foreach ($strangers as $u): ?>
            <div class="user-card">
                <h4><?php echo htmlspecialchars($u['fullname']); ?></h4>
                <p class="username">@<?php echo htmlspecialchars($u['username']); ?></p>
                
                <!-- Form gửi yêu cầu kết bạn chuyển hướng tới makefriend.php -->
                <form action="makefriend.php" method="POST">
                    <input type="hidden" name="target_username" value="<?php echo htmlspecialchars($u['username']); ?>">
                    <button type="submit" class="btn-action btn-add">Thêm bạn bè</button>
                </form>

            </div>
        <?php endforeach; ?>
        <?php if(empty($strangers) && empty($pending_sent)) echo "<p style='color: #606770; font-size: 14px;'>Không có gợi ý nào.</p>"; ?>
    </div>

    </div> <!-- Đóng thẻ .content mở ở menu.php -->
</div> <!-- Đóng thẻ .container mở ở menu.php -->
</body>
</html>
