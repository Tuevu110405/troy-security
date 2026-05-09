<?php
session_start();
// Hủy toàn bộ dữ liệu session
session_unset();
session_destroy();

// Chuyển hướng về trang đăng nhập
header("Location: signin.php");
exit();
?>
