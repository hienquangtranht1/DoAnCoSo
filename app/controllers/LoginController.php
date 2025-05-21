<?php
require_once __DIR__ . '/../models/Login.php';

class LoginController {
    public function index() {
        include __DIR__ . '/../views/login/login.php';
    }

    public function authenticate() {
        $loginModel = new Login();
        $username = $_POST['username'];
        $password = $_POST['password'];

        $user = $loginModel->authenticate($username, $password);

        if ($user) {
            session_start();
            $_SESSION['user'] = [
                'MaNguoiDung' => $user['MaNguoiDung'],
                'TenDangNhap' => $user['TenDangNhap'],
                'VaiTro' => $user['VaiTro']
            ];

            header("Location: /ProjectBooking/dashboard");
            exit();
        } else {
            $error = "Tên đăng nhập hoặc mật khẩu không đúng!";
            include __DIR__ . '/../views/login/login.php';
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: /ProjectBooking/login");
        exit();
    }
}
?>