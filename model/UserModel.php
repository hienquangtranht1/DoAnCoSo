<?php
require_once "Database.php";

class UserModel {
    private $conn;
    
    public function __construct($conn = null) {
        $this->conn = $conn ? $conn : Database::getInstance()->getConnection();
    }
    
    // Tạo tài khoản trong bảng NguoiDung
    public function createUser($username, $hashedPassword, $role) {
        $sql = "INSERT INTO NguoiDung (TenDangNhap, MatKhau, VaiTro) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) { 
            die("Prepare failed: " . $this->conn->error); 
        }
        $stmt->bind_param("sss", $username, $hashedPassword, $role);
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }
    
    // Tạo hồ sơ bệnh nhân trong bảng BenhNhan (bao gồm SoBaoHiem)
    public function createBenhNhan($maNguoiDung, $fullname, $dob, $gender, $phone, $email, $address, $soBaoHiem) {
        $sql = "INSERT INTO BenhNhan (MaNguoiDung, HoTen, NgaySinh, GioiTinh, SoDienThoai, Email, DiaChi, SoBaoHiem)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) { 
            die("Prepare failed: " . $this->conn->error); 
        }
        $stmt->bind_param("isssssss", $maNguoiDung, $fullname, $dob, $gender, $phone, $email, $address, $soBaoHiem);
        return $stmt->execute();
    }
    
    // Lấy thông tin người dùng theo tên đăng nhập, JOIN NguoiDung và BenhNhan
    public function getUserByUsername($username) {
        $sql = "SELECT nd.*, bn.HoTen, bn.NgaySinh, bn.GioiTinh, bn.SoDienThoai, bn.Email AS BN_Email, bn.DiaChi, bn.HinhAnhBenhNhan, bn.SoBaoHiem 
                FROM NguoiDung nd 
                LEFT JOIN BenhNhan bn ON nd.MaNguoiDung = bn.MaNguoiDung 
                WHERE nd.TenDangNhap = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) { 
            die("Prepare failed: " . $this->conn->error); 
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if ($result) {
            // Chuyển BN_Email thành Email để sử dụng chung
            $result['Email'] = isset($result['BN_Email']) ? $result['BN_Email'] : "";
        }
        return $result;
    }
    
    // Lấy thông tin người dùng theo ID, JOIN NguoiDung và BenhNhan
    public function getUserById($userId) {
        $sql = "SELECT nd.*, bn.HoTen, bn.NgaySinh, bn.GioiTinh, bn.SoDienThoai, bn.Email AS BN_Email, bn.DiaChi, bn.HinhAnhBenhNhan, bn.SoBaoHiem 
                FROM NguoiDung nd 
                LEFT JOIN BenhNhan bn ON nd.MaNguoiDung = bn.MaNguoiDung 
                WHERE nd.MaNguoiDung = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if ($result) {
            $result['Email'] = isset($result['BN_Email']) ? $result['BN_Email'] : "";
        }
        return $result;
    }
    
    // Lấy thông tin người dùng theo email, JOIN NguoiDung và BenhNhan
    public function getUserByEmail($email) {
        $sql = "SELECT nd.TenDangNhap, nd.MaNguoiDung, bn.*
                FROM NguoiDung nd
                JOIN BenhNhan bn ON nd.MaNguoiDung = bn.MaNguoiDung
                WHERE bn.Email = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    }
    
    // Cập nhật mật khẩu trong bảng NguoiDung
    public function updatePassword($userId, $hashedPassword) {
        $sql = "UPDATE nguoidung SET MatKhau = :hashedPassword WHERE MaNguoiDung = :userId";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':hashedPassword', $hashedPassword);
        $stmt->bindParam(':userId', $userId);
        return $stmt->execute();
    }
    
    // Cập nhật hồ sơ bệnh nhân, bao gồm SoBaoHiem và (nếu có) HinhAnhBenhNhan
    public function updateBenhNhanProfile($maNguoiDung, $fullname, $dob, $gender, $phone, $address, $soBaoHiem, $imagePath = null) {
        if ($imagePath) {
            $sql = "UPDATE BenhNhan SET HoTen = ?, NgaySinh = ?, GioiTinh = ?, SoDienThoai = ?, DiaChi = ?, SoBaoHiem = ?, HinhAnhBenhNhan = ? WHERE MaNguoiDung = ?";
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) { die("Prepare failed: " . $this->conn->error); }
            $stmt->bind_param("sssssssi", $fullname, $dob, $gender, $phone, $address, $soBaoHiem, $imagePath, $maNguoiDung);
        } else {
            $sql = "UPDATE BenhNhan SET HoTen = ?, NgaySinh = ?, GioiTinh = ?, SoDienThoai = ?, DiaChi = ?, SoBaoHiem = ? WHERE MaNguoiDung = ?";
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) { die("Prepare failed: " . $this->conn->error); }
            $stmt->bind_param("ssssssi", $fullname, $dob, $gender, $phone, $address, $soBaoHiem, $maNguoiDung);
        }
        return $stmt->execute();
    }
}
?>
