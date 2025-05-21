<?php
require_once __DIR__ . '/../models/Schedule.php';
require_once __DIR__ . '/../models/Doctor.php';

class LichLamViecController
{
    private $lichModel;
    private $bacSiModel;

    public function __construct()
    {
        $this->lichModel = new LichLamViec();
        $this->bacSiModel = new BacSi();
    }

    public function index()
    {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        if (empty($keyword)) {
            $lichs = $this->lichModel->getAll();
        } else {
            $lichs = $this->lichModel->searchByDoctor($keyword);
        }

        $bacSis = $this->bacSiModel->getAll();
        include __DIR__ . '/../views/lich/schedule.php';
    }

    public function store()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $data = [
                'MaBacSi' => filter_input(INPUT_POST, 'MaBacSi', FILTER_VALIDATE_INT),
                'NgayLamViec' => htmlspecialchars($_POST['NgayLamViec'], ENT_QUOTES, 'UTF-8'),
                'GioBatDau' => htmlspecialchars($_POST['GioBatDau'], ENT_QUOTES, 'UTF-8'),
                'GioKetThuc' => htmlspecialchars($_POST['GioKetThuc'], ENT_QUOTES, 'UTF-8'),
                'TrangThai' => htmlspecialchars($_POST['TrangThai'], ENT_QUOTES, 'UTF-8')
            ];

            if (!$data['MaBacSi'] || !$data['NgayLamViec'] || !$data['GioBatDau'] || !$data['GioKetThuc'] || !$data['TrangThai']) {
                die("Lỗi: Dữ liệu nhập vào không hợp lệ!");
            }

            if ($this->lichModel->add($data)) {
                header("Location: " . $_SERVER['SCRIPT_NAME'] . "?controller=lichkham&action=index");
                exit;
            } else {
                die("Lỗi: Không thể thêm lịch làm việc!");
            }
        }
    }

    public function update()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $data = [
                'MaLich' => filter_input(INPUT_POST, 'MaLich', FILTER_VALIDATE_INT),
                'MaBacSi' => filter_input(INPUT_POST, 'MaBacSi', FILTER_VALIDATE_INT),
                'NgayLamViec' => htmlspecialchars($_POST['NgayLamViec'], ENT_QUOTES, 'UTF-8'),
                'GioBatDau' => htmlspecialchars($_POST['GioBatDau'], ENT_QUOTES, 'UTF-8'),
                'GioKetThuc' => htmlspecialchars($_POST['GioKetThuc'], ENT_QUOTES, 'UTF-8'),
                'TrangThai' => htmlspecialchars($_POST['TrangThai'], ENT_QUOTES, 'UTF-8')
            ];

            if (!$data['MaLich'] || !$data['MaBacSi'] || !$data['NgayLamViec'] || !$data['GioBatDau'] || !$data['GioKetThuc'] || !$data['TrangThai']) {
                die("Lỗi: Dữ liệu nhập vào không hợp lệ!");
            }
            if ($this->lichModel->update($data)) {
                header("Location: " . $_SERVER['SCRIPT_NAME'] . "?controller=lichkham&action=index");
                exit;
            } else {
                die("Lỗi: Không thể cập nhật lịch làm việc!");
            }
        }
    }

    public function destroy()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            if ($id && $this->lichModel->delete($id)) {
                header("Location: " . $_SERVER['SCRIPT_NAME'] . "?controller=lichkham&action=index");
                exit;
            } else {
                die("Lỗi: Không thể xóa!");
            }
        }
    }

    public function updateStatus() {
        // Đặt header để trả về JSON
        header('Content-Type: application/json');
        
        try {
            // Đọc dữ liệu raw từ request
            $inputJSON = file_get_contents('php://input');
            
            // Log để debug
            error_log("Raw input: " . $inputJSON);
            
            // Parse JSON
            $data = json_decode($inputJSON, true);
            
            // Kiểm tra xem JSON có parse thành công không
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Invalid JSON format: " . json_last_error_msg());
            }
            
            // Kiểm tra dữ liệu
            if (!isset($data['MaLich']) || !isset($data['TrangThai'])) {
                throw new Exception("Missing required fields");
            }
            
            $maLich = intval($data['MaLich']);
            $trangThai = $data['TrangThai'];
            
            // Validate
            if ($maLich <= 0) {
                throw new Exception("Invalid schedule ID");
            }
            
            if (!in_array($trangThai, ['Trống', 'Đã đặt lịch'])) {
                throw new Exception("Invalid status value");
            }
            
            // Gọi model để cập nhật trạng thái
            $success = $this->lichModel->updateStatus($maLich, $trangThai);
            
            if ($success) {
                echo json_encode(['success' => true]);
            } else {
                throw new Exception("Could not update status");
            }
        } catch (Exception $e) {
            error_log("Error in updateStatus: " . $e->getMessage());
            echo json_encode([
                'success' => false, 
                'message' => $e->getMessage()
            ]);
        }
        
        exit; // Đảm bảo không có output nào khác
    }
}