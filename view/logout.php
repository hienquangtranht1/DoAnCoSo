<?php
session_start();

// Hủy toàn bộ dữ liệu session
session_destroy();

// Tùy chọn: xoá các biến session
$_SESSION = array();

// Chuyển hướng về trang chủ (hoặc trang đăng nhập)
header("Location: ../login.php");
exit();
?>
