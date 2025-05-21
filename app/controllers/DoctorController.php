<?php
require_once __DIR__ . '/../models/Doctor.php';

class BacSiController
{
    private $bacSiModel;

    public function __construct()
    {
        $this->bacSiModel = new BacSi();

        if (!class_exists('BacSi')) {
            die("Lỗi: Class BacSi không tồn tại. Kiểm tra lại đường dẫn hoặc định nghĩa class.");
        }
    }

    // Hiển thị danh sách bác sĩ
    public function index()
    {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $bacSis = !empty($keyword)
            ? $this->bacSiModel->search($keyword)
            : $this->bacSiModel->getAll();
        $departments = $this->bacSiModel->getAllDepartments();
        include __DIR__ . '/../views/bacsi/bacsi.php';
    }

    // Thêm bác sĩ mới
    // Thêm bác sĩ mới
    public function store()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                // Khởi tạo transaction
                $this->bacSiModel->getConnection()->begin_transaction();

                // Xử lý upload hình ảnh
                $hinhAnhBacSi = ''; // Giá trị mặc định
                if (isset($_FILES['HinhAnh']) && $_FILES['HinhAnh']['error'] == 0) {
                    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/public/uploads/';

                    // Đảm bảo thư mục uploads tồn tại
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    $fileName = time() . '_' . basename($_FILES['HinhAnh']['name']);
                    $targetFile = $uploadDir . $fileName;

                    if (move_uploaded_file($_FILES['HinhAnh']['tmp_name'], $targetFile)) {
                        $hinhAnhBacSi = 'public/uploads/' . $fileName; // Lưu đường dẫn tương đối
                    } else {
                        throw new Exception("Không thể upload hình ảnh");
                    }
                }

                // Tạo tài khoản người dùng trước
                $tenDangNhap = $_POST['Email'];
                $matKhau = password_hash('password123', PASSWORD_DEFAULT); // Mật khẩu mặc định
                $vaiTro = 'Bác sĩ';

                $userSql = "INSERT INTO nguoidung (TenDangNhap, MatKhau, VaiTro) VALUES (?, ?, ?)";
                $userStmt = $this->bacSiModel->getConnection()->prepare($userSql);
                $userStmt->bind_param("sss", $tenDangNhap, $matKhau, $vaiTro);
                $userResult = $userStmt->execute();

                if (!$userResult) {
                    throw new Exception("Lỗi khi tạo tài khoản người dùng");
                }

                $maNguoiDung = $this->bacSiModel->getConnection()->insert_id;

                // Thêm dữ liệu vào bảng bacsi
                $data = [
                    'HoTen' => $_POST['HoTen'],
                    'MaKhoa' => $_POST['MaKhoa'],
                    'SoDienThoai' => $_POST['SoDienThoai'],
                    'Email' => $_POST['Email'],                
                    'HinhAnhBacSi' => $hinhAnhBacSi,
                    'MaNguoiDung' => $maNguoiDung // Liên kết với người dùng vừa tạo
                ];

                $bacSiResult = $this->bacSiModel->add($data);

                if (!$bacSiResult) {
                    throw new Exception("Lỗi khi thêm thông tin bác sĩ");
                }

                // Commit transaction nếu mọi thứ OK
                $this->bacSiModel->getConnection()->commit();

                header("Location: " . BASE_URL . "/bacsi?success=true");
                exit;
            } catch (Exception $e) {
                // Rollback transaction nếu có lỗi
                $this->bacSiModel->getConnection()->rollback();
                echo "Lỗi: " . $e->getMessage();
            }
        }
    }
    // Cập nhật bác sĩ
    public function update()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $uploadDir = __DIR__ . '/../public/uploads/';

            // Tạo data array trước
            $data = [
                'MaBacSi' => $_POST['MaBacSi'],
                'HoTen' => $_POST['HoTen'],
                'MaKhoa' => $_POST['MaKhoa'],
                'SoDienThoai' => $_POST['SoDienThoai'],
                'Email' => $_POST['Email'],

            ];

            // Xử lý upload hình ảnh
            if (isset($_FILES["HinhAnh"]) && $_FILES["HinhAnh"]["error"] === UPLOAD_ERR_OK) {
                // Thay đổi đường dẫn upload
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/public/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $timestamp = time();
                $fileName = basename($_FILES["HinhAnh"]["name"]);
                if (!empty($fileName)) {
                    $targetFile = $uploadDir . $timestamp . "_" . $fileName;
                    if (move_uploaded_file($_FILES["HinhAnh"]["tmp_name"], $targetFile)) {
                        // Lưu đường dẫn tương đối vào database
                        $data['HinhAnhBacSi'] = 'public/uploads/' . $timestamp . "_" . $fileName;
                    } else {
                        echo "Lỗi khi lưu file upload.";
                        exit();
                    }
                }
            } else if (!empty($_POST['OldHinhAnh'])) {
                // Giữ lại đường dẫn ảnh cũ
                $data['HinhAnhBacSi'] = $_POST['OldHinhAnh'];
            }

            if ($this->bacSiModel->update($data)) {
                header("Location: " . BASE_URL . "/bacsi?updated=true");
                exit;
            }
        }
    }

    // Xóa bác sĩ
    public function destroy()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['id'])) {
                $id = intval($_POST['id']);
                if ($this->bacSiModel->delete($id)) {
                    header("Location: " . BASE_URL . "/bacsi?deleted=true");
                    exit();
                } else {
                    echo "Lỗi: Không thể xóa bác sĩ!";
                }
            } else {
                echo "Lỗi: ID không hợp lệ!";
            }
        } else {
            echo "Lỗi: Chỉ chấp nhận phương thức POST!";
        }
    }
}
