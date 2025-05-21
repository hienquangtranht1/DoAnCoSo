<?php
class Patient {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "datlichkhambenh");

        if ($this->conn->connect_error) {
            die("Kết nối thất bại: " . $this->conn->connect_error);
        }
    }
    

    public function getAll() {
        $sql = "SELECT * FROM benhnhan";
        $result = $this->conn->query($sql);

        if (!$result) {
            die("Lỗi truy vấn: " . $this->conn->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC) ?: [];
    }

    public function add($data) {
        $stmt = $this->conn->prepare("INSERT INTO benhnhan (HoTen, NgaySinh, GioiTinh, SoDienThoai, DiaChi, HinhAnhBenhNhan) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $data['HoTen'], $data['NgaySinh'], $data['GioiTinh'], $data['SoDienThoai'], $data['DiaChi'], $data['HinhAnhBenhNhan']);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM benhnhan WHERE MaBenhNhan=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getPatientById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM benhnhan WHERE MaBenhNhan = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updatePatient($data) {
        $stmt = $this->conn->prepare("UPDATE benhnhan SET HoTen=?, NgaySinh=?, GioiTinh=?, SoDienThoai=?, DiaChi=?, HinhAnhBenhNhan=? WHERE MaBenhNhan=?");
        $stmt->bind_param("ssssssi", $data['HoTen'], $data['NgaySinh'], $data['GioiTinh'], $data['SoDienThoai'], $data['DiaChi'], $data['HinhAnhBenhNhan'], $data['MaBenhNhan']);
        return $stmt->execute();
    }
    public function countAll() {
    $sql = "SELECT COUNT(*) AS total FROM benhnhan";
    $result = $this->conn->query($sql);
    return $result->fetch_assoc()['total'];
}
}
?>
