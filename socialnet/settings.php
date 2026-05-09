<?php 
require_once 'menu.php'; 
require_once 'db_connect.php';

$username = $_SESSION['username'];
$message = "";

// Xử lý khi nhấn nút Lưu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $new_profile = trim($_POST['profile_text']);
    try {
        $sql = "UPDATE users SET profile_text = :profile_text WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':profile_text' => $new_profile, ':username' => $username]);
        $message = "Cập nhật profile thành công!";
    } catch(PDOException $e) {
        $message = "Lỗi cập nhật: " . $e->getMessage();
    }
}

// Lấy dữ liệu cũ hiển thị ra textarea
$profile_text = "";
try {
    $sql = "SELECT profile_text FROM users WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':username' => $username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && $row['profile_text']) {
        $profile_text = $row['profile_text'];
    }
} catch(PDOException $e) {}
?>

    <h2>Cài đặt Profile</h2>
    <?php if ($message): ?>
        <div class="alert"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <form action="settings.php" method="POST">
        <label style="font-weight: bold; margin-bottom: 10px; display: block;">Giới thiệu bản thân:</label>
        <textarea name="profile_text" placeholder="Nhập nội dung profile của bạn vào đây..."><?php echo htmlspecialchars($profile_text); ?></textarea>
        <button type="submit" name="update_profile" class="btn-save">Lưu thay đổi</button>
    </form>

    </div> <!-- Đóng thẻ .content -->
</div> <!-- Đóng thẻ .container -->
</body>
</html>
