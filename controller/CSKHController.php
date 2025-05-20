<?php
// controller/CSKHController.php
session_start();
require_once "../model/CSKHModel.php";
$model = new CSKHModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Xử lý đăng nhập
    if (isset($_POST['action']) && $_POST['action'] === "login") {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $user = $model->getUserByUsername($username);
        if ($user && $password === $user['password']) { // So sánh trực tiếp với mật khẩu không mã hóa
            $_SESSION['cskh'] = $user;
            header("Location: ../dashboard_cskh.php");
            exit();
        } else {
            $_SESSION['login_error'] = "Tên đăng nhập hoặc mật khẩu không đúng.";
            header("Location: ../view/login_cskh.php");
            exit();
        }
    }
    // Xử lý cập nhật hồ sơ
    if (isset($_POST['action']) && $_POST['action'] === "update_profile") {
        $id = intval($_POST['id']);
        $full_name = trim($_POST['full_name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        if ($model->updateProfile($id, $full_name, $email, $phone)) {
            $_SESSION['profile_msg'] = "Cập nhật hồ sơ thành công.";
        } else {
            $_SESSION['profile_msg'] = "Cập nhật hồ sơ thất bại.";
        }
        header("Location: dashboard_cskh.php");
        exit();
    }
}
?>
