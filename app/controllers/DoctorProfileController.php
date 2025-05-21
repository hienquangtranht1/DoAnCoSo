<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/BacSi.php';

if (!defined('BASE_URL')) {
    define('BASE_URL', '/BOOKING/');
}

class DoctorProfileController {
    private $conn;
    private $doctorModel;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->doctorModel = new BacSi($conn);
    }
    
    // Lấy thông tin bác sĩ hiện tại theo session
    private function getCurrentDoctor() {
        if (!isset($_SESSION["MaNguoiDung"])) {
            header("Location: " . BASE_URL . "Auth?action=login");
            exit();
        }
        $maNguoiDung = $_SESSION["MaNguoiDung"];
        $doctor = $this->doctorModel->getDoctorByUserId($maNguoiDung);
        if (!$doctor) {
            die("Không tìm thấy thông tin bác sĩ.");
        }
        return $doctor;
    }
    
    // Hiển thị trang thông tin bác sĩ
    public function index() {
        $doctor = $this->getCurrentDoctor();
        require_once __DIR__ . '/../views/doctor_profile.php';
    }

    // Xử lý cập nhật thông tin của bác sĩ
    public function update() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (!isset($_SESSION["MaNguoiDung"])) {
                header("Location: " . BASE_URL . "Auth?action=login");
                exit();
            }
            $maNguoiDung  = $_SESSION["MaNguoiDung"];
            $hoTen        = trim($_POST["HoTen"]);
            $soDienThoai  = trim($_POST["SoDienThoai"]);
            $email        = trim($_POST["Email"]);
            $moTa         = trim($_POST["MoTa"]);
            $hinhAnhBacSi = null;
            
            // Xử lý upload hình ảnh nếu có file được chọn
            if (isset($_FILES["HinhAnhBacSi"])) {
                if ($_FILES["HinhAnhBacSi"]["error"] === UPLOAD_ERR_NO_FILE) {
                    $hinhAnhBacSi = null;
                } elseif ($_FILES["HinhAnhBacSi"]["error"] === UPLOAD_ERR_OK) {
                    // Thay đổi đường dẫn upload thành public/uploads/
                    $uploadDir = __DIR__ . '/../../public/uploads/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    $timestamp = time();
                    $fileName  = basename($_FILES["HinhAnhBacSi"]["name"]);
                    if (!empty($fileName)) {
                        $targetFile = $uploadDir . $timestamp . "_" . $fileName;
                        if (move_uploaded_file($_FILES["HinhAnhBacSi"]["tmp_name"], $targetFile)) {
                            // Sửa đường dẫn lưu vào database
                            $hinhAnhBacSi = 'public/uploads/' . $timestamp . "_" . $fileName;
                        } else {
                            echo "Lỗi khi lưu file upload tại: " . $targetFile;
                            exit();
                        }
                    } else {
                        $hinhAnhBacSi = null;
                    }
                } else {
                    echo "Lỗi upload file, mã lỗi: " . $_FILES["HinhAnhBacSi"]["error"];
                    exit();
                }
            }
            // Debug: Bạn có thể uncomment dòng bên dưới để kiểm tra giá trị của $hinhAnhBacSi
            //var_dump($hinhAnhBacSi); exit();
    
            if ($this->doctorModel->updateDoctorByUserId($maNguoiDung, $hoTen, $soDienThoai, $email, $moTa, $hinhAnhBacSi)) {
                header("Location: " . BASE_URL . "DoctorProfile?success=1");
                exit();
            } else {
                echo "Lỗi khi cập nhật thông tin: " . $this->conn->error;
            }
        }
    }
}

$controller = new DoctorProfileController($conn);
$action = isset($_GET['action']) ? $_GET['action'] : 'index';
if ($action === 'update') {
    $controller->update();
} else {
    $controller->index();
}
?>
