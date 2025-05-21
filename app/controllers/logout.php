<?php
// app/controllers/logout.php

// Bắt đầu session
session_start();

// Xóa toàn bộ session
session_unset();
session_destroy();

// Chuyển hướng về trang đăng nhập (login_view.php)
header("Location: /booking/app/views/login_view.php");
exit();
?>
