<?php
class Login {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "datlichkhambenh");

        if ($this->conn->connect_error) {
            die("Kết nối thất bại: " . $this->conn->connect_error);
        }
    }

    public function authenticate($username, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM nguoidung WHERE TenDangNhap = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if ($password === $user['MatKhau']) { // So sánh mật khẩu trực tiếp
                return $user;
            }
        }
        return false;
    }
}
?>