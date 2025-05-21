<?php
session_start();

// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['user'])) {
    header("Location: /ProjectBooking/login");
    exit();
}

// Kiểm tra vai trò của người dùng
if ($_SESSION['user']['VaiTro'] !== 'Quản trị') {
    die("Bạn không có quyền truy cập vào trang này!");
}
?>