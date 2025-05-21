<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/LichLamViec.php';

if (!defined('BASE_URL')) {
    define('BASE_URL', '/BOOKING/');
}

class LichLamViecController {
    private $lichLamViecModel;
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
        $this->lichLamViecModel = new LichLamViec($conn);
    }
    
    // Lấy MaBacSi dựa trên MaNguoiDung trong session
    private function getMaBacSi() {
        if (!isset($_SESSION["MaNguoiDung"])) {
            echo "Bạn chưa đăng nhập.";
            exit();
        }
        $maNguoiDung = $_SESSION["MaNguoiDung"];
        $sql = "SELECT MaBacSi FROM bacsi WHERE MaNguoiDung = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("i", $maNguoiDung);
            $stmt->execute();
            $result = $stmt->get_result();
            $doctor = $result->fetch_assoc();
            $stmt->close();
            if (!$doctor || empty($doctor["MaBacSi"])) {
                echo "Không tìm thấy thông tin bác sĩ. Vui lòng liên hệ ban quản trị.";
                exit();
            }
            return $doctor["MaBacSi"];
        } else {
            echo "Lỗi truy vấn: " . $this->conn->error;
            exit();
        }
    }
    
    // Hiển thị lịch làm việc dạng danh sách theo tuần
    public function index() {
        if (!isset($_SESSION["MaNguoiDung"])) {
            header("Location: " . BASE_URL . "Auth?action=login");
            exit();
        }
        $weekStart = isset($_GET['weekStart']) ? $_GET['weekStart'] : date("Y-m-d", strtotime("monday this week"));
        $monday = new DateTime($weekStart);
        $sunday = clone $monday;
        $sunday->modify("+6 days");
        $maBacSi = $this->getMaBacSi();
        $schedules = $this->lichLamViecModel->getSchedulesByDoctorAndWeek($maBacSi, $monday->format("Y-m-d"), $sunday->format("Y-m-d"));
        
        require_once __DIR__ . '/../views/lichlamviec_view.php';
    }
    
    // Xử lý thêm lịch làm việc; sau đó chuyển về index để cập nhật bảng
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ngayLamViec = $_POST['NgayLamViec'];
            $gioBatDau = $_POST['GioBatDau'];
            $gioKetThuc = $_POST['GioKetThuc'];
            $maBacSi = $this->getMaBacSi();
            
            if ($this->lichLamViecModel->addSchedule($maBacSi, $ngayLamViec, $gioBatDau, $gioKetThuc)) {
                $timestamp = strtotime($ngayLamViec);
                $dayOfWeek = date('N', $timestamp); // 1 (Mon) - 7 (Sun)
                $monday = date('Y-m-d', strtotime("-" . ($dayOfWeek - 1) . " days", $timestamp));

                header("Location: " . BASE_URL . "LichLamViec?weekStart=" . urlencode($monday));
                exit();
            } else {
                echo "Lỗi khi thêm lịch làm việc! (Có thể do ngày được chọn là Chủ nhật)";
            }
        }
    }
    
    // Xử lý cập nhật lịch làm việc; sau đó chuyển về index
    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['MaLich'])) {
            $maLich = $_POST['MaLich'];
            $gioBatDau = $_POST['GioBatDau'];
            $gioKetThuc = $_POST['GioKetThuc'];
            $ngayLamViec = $_POST['NgayLamViec'];
            if ($this->lichLamViecModel->updateSchedule($maLich, $gioBatDau, $gioKetThuc)) {
                $timestamp = strtotime($ngayLamViec);
                $dayOfWeek = date('N', $timestamp); // 1 (Mon) - 7 (Sun)
                $monday = date('Y-m-d', strtotime("-" . ($dayOfWeek - 1) . " days", $timestamp));

                header("Location: " . BASE_URL . "LichLamViec?weekStart=" . urlencode($monday));
                exit();
            } else {
                echo "Lỗi khi cập nhật lịch làm việc!";
            }
        }
    }
    
    // Xử lý xóa lịch làm việc; sau đó chuyển về index
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['MaLich'])) {
            $maLich = $_POST['MaLich'];
            $ngayLamViec = isset($_POST['NgayLamViec']) ? $_POST['NgayLamViec'] : '';
            if ($this->lichLamViecModel->deleteSchedule($maLich)) {
                $timestamp = strtotime($ngayLamViec);
                $dayOfWeek = date('N', $timestamp); // 1 (Mon) - 7 (Sun)
                $monday = date('Y-m-d', strtotime("-" . ($dayOfWeek - 1) . " days", $timestamp));

                header("Location: " . BASE_URL . "LichLamViec?weekStart=" . urlencode($monday));
                exit();
            } else {
                echo "Lỗi khi xóa lịch làm việc!";
            }
        }
    }
}

$lichLamViecController = new LichLamViecController($conn);
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

if ($action === 'index') {
    $lichLamViecController->index();
} elseif ($action === 'add') {
    $lichLamViecController->add();
} elseif ($action === 'edit') {
    $lichLamViecController->edit();
} elseif ($action === 'delete') {
    $lichLamViecController->delete();
} else {
    $lichLamViecController->index();
}
?>
