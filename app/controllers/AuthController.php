<?php
// File: app/controllers/AuthController.php

// Khởi tạo session nếu chưa có phiên làm việc
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/NguoiDung.php';
require_once __DIR__ . '/../models/BacSi.php'; // Để lấy thông tin bác sĩ

// Kiểm tra và định nghĩa BASE_URL nếu chưa có
if (!defined('BASE_URL')) {
    define('BASE_URL', '/BOOKING/');
}

class AuthController {
    private $nguoiDungModel;
    private $doctorModel; // Model BacSi

    public function __construct($conn) {
        $this->nguoiDungModel = new NguoiDung(conn: $conn);
        $this->doctorModel = new BacSi(conn: $conn);
    }
    
    public function login() {
        // Nếu người dùng đã đăng nhập, chuyển hướng tới trang DoctorProfile (URL clean)
        if (isset($_SESSION["MaNguoiDung"])) {
            if (isset($_SESSION["VaiTro"]) && $_SESSION["VaiTro"] === "Bác sĩ") {
                header("Location: " . BASE_URL . "DoctorProfile");
            } else {
                echo "Tài khoản này không phải của bác sĩ. Vui lòng đăng nhập bằng tài khoản bác sĩ.";
            }
            exit();
        }
        
        // Xử lý đăng nhập qua POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            
            if (empty($username) || empty($password)) {
                $error = "Vui lòng nhập đầy đủ thông tin.";
                include __DIR__ . '/../views/login_view.php';
                return;
            }
            
            $user = $this->nguoiDungModel->authenticate($username, $password);
            
            if ($user) {
                // Cho phép đăng nhập nếu VaiTro là "Bác sĩ"
                if ($user["VaiTro"] === "Bác sĩ") {
                    $_SESSION["MaNguoiDung"] = $user["MaNguoiDung"];
                    $_SESSION["VaiTro"]      = $user["VaiTro"];
                    
                    // Lấy thông tin bác sĩ từ bảng BacSi và lưu tên bác sĩ vào session
                    $doctor = $this->doctorModel->getDoctorByUserId($user["MaNguoiDung"]);
                    if ($doctor && !empty($doctor["HoTen"])) {
                        $_SESSION["HoTenBacSi"] = $doctor["HoTen"];
                    } else {
                        $_SESSION["HoTenBacSi"] = $user["TenDangNhap"];
                    }
                    
                    // Chuyển hướng clean tới trang DoctorProfile
                    header("Location: " . BASE_URL . "DoctorProfile");
                    exit();
                } else {
                    $error = "Tài khoản này không phải của bác sĩ. Vui lòng đăng nhập bằng tài khoản bác sĩ.";
                    require_once __DIR__ . '/../views/login_view.php';
                    return;
                }
            } else {
                $error = "Tên đăng nhập hoặc mật khẩu không đúng.";
                require_once __DIR__ . '/../views/login_view.php';
                return;
            }
        }
        require_once __DIR__ . '/../views/login_view.php';
    }
    
    public function logout() {
        $_SESSION = [];
        session_unset();
        session_destroy();
        // Chuyển hướng về trang đăng nhập dạng clean (URL clean)
        header("Location: " . BASE_URL . "Login");
        exit();
    }
}

// Khởi tạo AuthController với kết nối $conn và xử lý hành động dựa theo GET parameter `action`
$authController = new AuthController($conn);
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'logout') {
    $authController->logout();
} else {
    $authController->login();
}
?>
