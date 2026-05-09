<?php 
require_once 'menu.php'; 
require_once 'db_connect.php';

$username = $_SESSION['username'];
$fullname = $_SESSION['fullname'];

// Lấy dữ liệu profile
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

    <h2>Hồ sơ của bạn</h2>
    <h3><?php echo htmlspecialchars($fullname); ?></h3>
    <p style="color: gray;">@<?php echo htmlspecialchars($username); ?></p>
    
    <h4>Giới thiệu:</h4>
    <div class="profile-display">
        <?php 
            echo $profile_text ? nl2br(htmlspecialchars($profile_text)) : "Chưa có thông tin giới thiệu."; 
        ?>
    </div>

    </div> <!-- Đóng thẻ .content -->
</div> <!-- Đóng thẻ .container -->
</body>
</html>
