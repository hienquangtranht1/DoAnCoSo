<?php
// Chỉ gọi session_start() nếu session chưa được kích hoạt
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/HoSoBenhAn.php';

if (!defined('BASE_URL')) {
    define('BASE_URL', '/BOOKING/');
}

class HoSoBenhAnController {
    private $hosoBenhAnModel;
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->hosoBenhAnModel = new HoSoBenhAn($conn);
    }
    
    private function getMaBacSi() {
        if (!isset($_SESSION["MaNguoiDung"])) {
            echo "Bạn chưa đăng nhập.";
            exit();
        }
        $maNguoiDung = $_SESSION["MaNguoiDung"];
        $sql = "SELECT MaBacSi FROM BacSi WHERE MaNguoiDung = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("i", $maNguoiDung);
            $stmt->execute();
            $result = $stmt->get_result();
            $doctor = $result->fetch_assoc();
            $stmt->close();

            if (!$doctor || empty($doctor["MaBacSi"])) {
                echo "Không tìm thấy thông tin bác sĩ liên quan. Vui lòng liên hệ ban quản trị.";
                exit();
            }
            return $doctor["MaBacSi"];
        } else {
            echo "Lỗi truy vấn: " . $this->conn->error;
            exit();
        }
    }
    
    public function index() {
        if (!isset($_SESSION["MaNguoiDung"])) {
            header("Location: " . BASE_URL . "app/controllers/AuthController.php?action=login");
            exit();
        }
        $currentDoctorId = $this->getMaBacSi();
        
        if (isset($_GET['search']) && trim($_GET['search']) !== '') {
            $searchTerm = trim($_GET['search']);
            $mode = isset($_GET['mode']) ? $_GET['mode'] : 'patient';
            if ($mode == 'chanDoan') {
                $records = $this->hosoBenhAnModel->searchRecordsByChanDoan($searchTerm);
            } else {
                $records = $this->hosoBenhAnModel->searchRecordsByPatientName($searchTerm);
            }
        } else {
            $records = $this->hosoBenhAnModel->getAllRecords();
        }
        require_once __DIR__ . '/../views/hoso_benhan_view.php';
        exit();
    }
    
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $maBenhNhan = $_POST['MaBenhNhan'];
            $ngayKham   = $_POST['NgayKham'];
            $chanDoan   = $_POST['ChanDoan'];
            $phuongAnDieuTri = $_POST['PhuongAnDieuTri'];
            $maBacSi = $this->getMaBacSi();

            if ($this->hosoBenhAnModel->addRecord($maBenhNhan, $maBacSi, $ngayKham, $chanDoan, $phuongAnDieuTri)) {
                header("Location: " . BASE_URL . "HoSoBenhAn");
                exit();
            } else {
                echo "Lỗi khi thêm hồ sơ bệnh án!";
            }
        }
    }
    
    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['MaHoSo'])) {
            $maHoSo = $_POST['MaHoSo'];
            $chanDoan = $_POST['ChanDoan'];
            $phuongAnDieuTri = $_POST['PhuongAnDieuTri'];
            
            // Kiểm tra quyền cập nhật: chỉ cho phép bác sĩ cập nhật hồ sơ của chính mình
            $record = $this->hosoBenhAnModel->getRecordById($maHoSo);
            $currentDoctorId = $this->getMaBacSi();
            if ($record['MaBacSi'] != $currentDoctorId) {
                echo "Bạn không có quyền cập nhật hồ sơ bệnh án này!";
                exit();
            }
            
            if ($this->hosoBenhAnModel->updateRecord($maHoSo, $chanDoan, $phuongAnDieuTri)) {
                // Giữ lại các tham số tìm kiếm nếu có (được gửi qua POST)
                $redirectUrl = BASE_URL . "HoSoBenhAn";
                if (isset($_POST['search']) && trim($_POST['search']) !== '') {
                    $search = urlencode(trim($_POST['search']));
                    $mode   = isset($_POST['mode']) ? urlencode($_POST['mode']) : 'patient';
                    $redirectUrl .= "?action=index&search={$search}&mode={$mode}";
                }
                header("Location: " . $redirectUrl);
                exit();
            } else {
                echo "Lỗi khi cập nhật hồ sơ bệnh án!";
            }
        }
    }
}

$hosoBenhAnController = new HoSoBenhAnController($conn);
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

if ($action === 'index') {
    $hosoBenhAnController->index();
} elseif ($action === 'add') {
    $hosoBenhAnController->add();
} elseif ($action === 'edit') {
    $hosoBenhAnController->edit();
} else {
    $hosoBenhAnController->index();
}
?>
