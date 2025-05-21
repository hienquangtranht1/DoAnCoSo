<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/doimatkhau.php';

if (!defined('BASE_URL')) {
    define('BASE_URL', '/BOOKING/');
}

class ChangePasswordController {
    private $conn;
    private $userModel; // Dùng model DoiMatKhau

    public function __construct($conn) {
        $this->conn = $conn;
        $this->userModel = new DoiMatKhau($conn);
    }

    // Hiển thị form đổi mật khẩu
    public function index() {
        if (!isset($_SESSION["MaNguoiDung"])) {
            header("Location: " . BASE_URL . "Auth?action=login");
            exit();
        }
        require_once __DIR__ . '/../views/change_password.php';
    }

    // Xử lý đổi mật khẩu (so sánh mật khẩu cũ và cập nhật mật khẩu mới trực tiếp)
    public function update() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (!isset($_SESSION["MaNguoiDung"])) {
                header("Location: " . BASE_URL . "Auth?action=login");
                exit();
            }
            $maNguoiDung = $_SESSION["MaNguoiDung"];
            $oldPassword = isset($_POST["oldPassword"]) ? trim($_POST["oldPassword"]) : "";
            $newPassword = isset($_POST["newPassword"]) ? trim($_POST["newPassword"]) : "";
            $confirmPassword = isset($_POST["confirmPassword"]) ? trim($_POST["confirmPassword"]) : "";

            // Kiểm tra mật khẩu mới có khớp với xác nhận không
            if ($newPassword !== $confirmPassword) {
                header("Location: " . BASE_URL . "ChangePassword?action=index&error=confirm");
                exit();
            }
            
            // Lấy thông tin người dùng (mật khẩu được lưu dưới dạng plaintext)
            $user = $this->userModel->getUserById($maNguoiDung);
            if (!$user) {
                header("Location: " . BASE_URL . "ChangePassword?action=index&error=user");
                exit();
            }
            
            // So sánh mật khẩu cũ nhập vào với giá trị trong CSDL (plaintext)
            if ($oldPassword !== $user["MatKhau"]) {
                header("Location: " . BASE_URL . "ChangePassword?action=index&error=old");
                exit();
            }
            
            // Cập nhật mật khẩu mới (lưu trực tiếp plaintext)
            if ($this->userModel->updatePassword($maNguoiDung, $newPassword)) {
                header("Location: " . BASE_URL . "ChangePassword?action=index&success=1");
                exit();
            } else {
                header("Location: " . BASE_URL . "ChangePassword?action=index&error=update");
                exit();
            }
        }
    }
}

$controller = new ChangePasswordController($conn);
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

if ($action === 'update') {
    $controller->update();
} else {
    $controller->index();
}
?>
