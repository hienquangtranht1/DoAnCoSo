<?php
class NguoiDung {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function authenticate($username, $password) {
        $sql = "SELECT ND.MaNguoiDung, ND.TenDangNhap, ND.VaiTro, BS.MaBacSi
                FROM NguoiDung ND
                LEFT JOIN BacSi BS ON ND.MaNguoiDung = BS.MaNguoiDung
                WHERE ND.TenDangNhap = ? AND ND.MatKhau = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }
}

?>
