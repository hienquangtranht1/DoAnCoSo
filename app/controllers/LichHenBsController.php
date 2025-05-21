<?php
// File: app/controllers/LichHenBsController.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/LichHen.php';

if (!defined('BASE_URL')) {
    define('BASE_URL', '/BOOKING/');
}

class LichHenBsController {
    private $lichHenModel;
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
        $this->lichHenModel = new LichHen($conn);
    }
    
    public function index() {
        if (!isset($_SESSION["MaNguoiDung"])) {
            header("Location: " . BASE_URL . "Auth?action=login");
            exit();
        }
        if (!isset($_SESSION["VaiTro"]) || $_SESSION["VaiTro"] !== "Bác sĩ") {
            echo "Tài khoản này không phải của bác sĩ. Vui lòng đăng nhập bằng tài khoản bác sĩ.";
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
            $maBacSi = $doctor["MaBacSi"];
        } else {
            echo "Lỗi truy vấn: " . $this->conn->error;
            exit();
        }
        
        $selectedDate = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");
        $appointments = $this->lichHenModel->getAppointmentsByDoctorAndDate($maBacSi, $selectedDate);
        require_once __DIR__ . '/../views/lichhen_view.php'; // Sử dụng require_once thay vì include
        exit(); 
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST'
            && isset($_POST['MaLich'])
            && isset($_POST['new_status'])
            && isset($_POST['selectedDate'])) {
            
            $maLich       = $_POST['MaLich'];
            $newStatus    = $_POST['new_status'];
            $selectedDate = $_POST['selectedDate'];
            
            $sql = "UPDATE LichHen SET TrangThai = ? WHERE MaLich = ?";
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                die("Prepare failed: " . $this->conn->error);
            }
            $stmt->bind_param("si", $newStatus, $maLich);
            
            if ($stmt->execute()) {
                // Nếu có yêu cầu AJAX
                if ((isset($_POST['ajax']) && $_POST['ajax'] == 1) || (isset($_GET['ajax']) && $_GET['ajax'] == 1)) {
                    if (!isset($_SESSION["MaNguoiDung"])) {
                        echo "Chưa đăng nhập.";
                        exit();
                    }
                    $maNguoiDung = $_SESSION["MaNguoiDung"];
                    $sqlDoctor = "SELECT MaBacSi FROM bacsi WHERE MaNguoiDung = ?";
                    if ($stmtDoctor = $this->conn->prepare($sqlDoctor)) {
                        $stmtDoctor->bind_param("i", $maNguoiDung);
                        $stmtDoctor->execute();
                        $resultDoctor = $stmtDoctor->get_result();
                        $doctor = $resultDoctor->fetch_assoc();
                        $stmtDoctor->close();
                        if (!$doctor || empty($doctor["MaBacSi"])) {
                            echo "Không tìm thấy thông tin bác sĩ. Vui lòng liên hệ ban quản trị.";
                            exit();
                        }
                        $maBacSi = $doctor["MaBacSi"];
                    } else {
                        echo "Lỗi truy vấn: " . $this->conn->error;
                        exit();
                    }
                    
                    $appointments = $this->lichHenModel->getAppointmentsByDoctorAndDate($maBacSi, $selectedDate);
                    require_once __DIR__ . '/../views/lichhen_view.php'; // Sử dụng require_once thay vì include
                    exit(); 
                } else {
                    // Sử dụng URL clean của controller: chuyển hướng tới "LichHenBs"
                    header("Location: " . BASE_URL . "LichHenBs?date=" . urlencode($selectedDate));
                    exit();
                }
            } else {
                echo "Lỗi cập nhật: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Yêu cầu không hợp lệ.";
        }
    }
}

// Khởi tạo controller
$lichHenBsController = new LichHenBsController($conn);

// Xử lý request
if (isset($_GET['action']) && $_GET['action'] === 'update') {
    $lichHenBsController->update();
} else {
    $lichHenBsController->index();
}