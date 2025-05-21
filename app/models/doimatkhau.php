<?php
class DoiMatKhau {
    private $conn;
    private $table = "NguoiDung"; // Sử dụng bảng NguoiDung theo SQL đã cho

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Lấy thông tin người dùng theo MaNguoiDung
    public function getUserById($maNguoiDung) {
        $sql = "SELECT * FROM $this->table WHERE MaNguoiDung = ?";
        $stmt = $this->conn->prepare($sql) or die("Lỗi SQL: " . $this->conn->error);
        $stmt->bind_param("i", $maNguoiDung);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    // Cập nhật mật khẩu cho người dùng (lưu trực tiếp mật khẩu dưới dạng plaintext)
    public function updatePassword($maNguoiDung, $newPassword) {
        $sql = "UPDATE $this->table SET MatKhau = ? WHERE MaNguoiDung = ?";
        $stmt = $this->conn->prepare($sql) or die("Lỗi SQL: " . $this->conn->error);
        $stmt->bind_param("si", $newPassword, $maNguoiDung);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
?>
