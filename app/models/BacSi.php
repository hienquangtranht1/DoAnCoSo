<?php
class BacSi {
    private $conn;
    private $table = "BacSi";
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    // Lấy thông tin bác sĩ theo MaNguoiDung (user ID)
    public function getDoctorByUserId($maNguoiDung) {
        $sql = "SELECT * FROM $this->table WHERE MaNguoiDung = ?";
        $stmt = $this->conn->prepare($sql) or die("Lỗi SQL: " . $this->conn->error);
        $stmt->bind_param("i", $maNguoiDung);
        $stmt->execute();
        $result = $stmt->get_result();
        $doctor = $result->fetch_assoc();
        $stmt->close();
        return $doctor;
    }
    
    // Cập nhật thông tin bác sĩ, bao gồm hình ảnh (nếu có)
    public function updateDoctorByUserId($maNguoiDung, $hoTen, $soDienThoai, $email, $moTa, $hinhAnhBacSi = null) {
        if (!empty($hinhAnhBacSi)) {
            $sql = "UPDATE $this->table 
                    SET HoTen = ?, SoDienThoai = ?, Email = ?, MoTa = ?, HinhAnhBacSi = ? 
                    WHERE MaNguoiDung = ?";
            $stmt = $this->conn->prepare($sql) or die("Lỗi SQL: " . $this->conn->error);
            $stmt->bind_param("sssssi", $hoTen, $soDienThoai, $email, $moTa, $hinhAnhBacSi, $maNguoiDung);

        } else {
            $sql = "UPDATE $this->table 
                    SET HoTen = ?, SoDienThoai = ?, Email = ?, MoTa = ? 
                    WHERE MaNguoiDung = ?";
            $stmt = $this->conn->prepare($sql) or die("Lỗi SQL: " . $this->conn->error);
            $stmt->bind_param("ssssi", $hoTen, $soDienThoai, $email, $moTa, $maNguoiDung);
        }
        $result = $stmt->execute();
        if (!$result) {
            die("Lỗi khi thực thi câu lệnh: " . $stmt->error);
        }
        $stmt->close();
        return $result;

        
    }
    
}
?>
