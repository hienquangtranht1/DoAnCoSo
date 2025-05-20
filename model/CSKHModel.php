<?php
// model/CSKHModel.php
require_once "Database.php";

class CSKHModel {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    // Lấy thông tin CSKH theo username
    public function getUserByUsername($username) {
        $sql = "SELECT * FROM cs_kh WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) die("Prepare failed: " . $this->conn->error);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }
    
    // Cập nhật hồ sơ của CSKH
    public function updateProfile($id, $full_name, $email, $phone) {
        $sql = "UPDATE cs_kh SET full_name = ?, email = ?, phone = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) die("Prepare failed: " . $this->conn->error);
        $stmt->bind_param("sssi", $full_name, $email, $phone, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
?>
