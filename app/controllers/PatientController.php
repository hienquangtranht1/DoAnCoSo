<?php
require_once __DIR__ . '/../models/Patient.php';

class PatientController
{
    private $patientModel;

    public function __construct()
    {
        $this->patientModel = new Patient();

        // Kiểm tra nếu class không tồn tại (debug)
        if (!class_exists('Patient')) {
            die("Lỗi: Class Patient không tồn tại. Kiểm tra lại đường dẫn hoặc định nghĩa class.");
        }
    }

    public function index()
    {
        $benhNhans = $this->patientModel->getAll();

        // Đảm bảo biến $benhNhans luôn là mảng
        if (!is_array($benhNhans)) {
            $benhNhans = [];
        }

        include __DIR__ . '/../views/benhnhan/index.php';
    }

    public function store()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $targetDir = "public/img/";
            $targetFile = $targetDir . basename($_FILES["HinhAnh"]["name"]);
            move_uploaded_file($_FILES["HinhAnh"]["tmp_name"], $targetFile);

            $data = [
                'HoTen' => $_POST['HoTen'],
                'NgaySinh' => $_POST['NgaySinh'],
                'GioiTinh' => $_POST['GioiTinh'],
                'SoDienThoai' => $_POST['SoDienThoai'],
                'DiaChi' => $_POST['DiaChi'],
                'HinhAnh' => $targetFile
            ];

            $this->patientModel->add($data);
            header("Location: /ProjectBooking/benhnhan");
            exit;
        }
    }

    public function destroy()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['id'])) {
                $id = intval($_POST['id']); // Chuyển ID thành số nguyên để tránh lỗi SQL Injection
                if ($this->patientModel->delete($id)) {
                    header("Location: /ProjectBooking/benhnhan?deleted=true");
                    exit();
                } else {
                    echo "Lỗi: Không thể xóa bệnh nhân!";
                }
            } else {
                echo "Lỗi: ID không hợp lệ!";
            }
        } else {
            echo "Lỗi: Chỉ chấp nhận phương thức POST!";
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $targetDir = "public/img/";
            $targetFile = "";

            if (!empty($_FILES["HinhAnh"]["name"])) {
                $targetFile = $targetDir . basename($_FILES["HinhAnh"]["name"]);
                move_uploaded_file($_FILES["HinhAnh"]["tmp_name"], $targetFile);
            } else {
                $targetFile = $_POST['OldHinhAnh'];
            }

            $data = [
                'MaBenhNhan' => $_POST['MaBenhNhan'],
                'HoTen' => $_POST['HoTen'],
                'NgaySinh' => $_POST['NgaySinh'],
                'GioiTinh' => $_POST['GioiTinh'],
                'SoDienThoai' => $_POST['SoDienThoai'],
                'DiaChi' => $_POST['DiaChi'],
                'HinhAnhBenhNhan' => $targetFile
            ];

            if ($this->patientModel->updatePatient($data)) {
                header("Location: /ProjectBooking/benhnhan?updated=true");
            } else {
                echo "Cập nhật thất bại.";
            }
        }
    }
}
