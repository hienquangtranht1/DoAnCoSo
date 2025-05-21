<?php
class BacSi
{
    private $conn;

    public function __construct()
    {
        $this->conn = new mysqli("localhost", "root", "", "datlichkhambenh");

        if ($this->conn->connect_error) {
            error_log("Kết nối cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
            die("Kết nối thất bại: " . $this->conn->connect_error);
        }
    }

    public function getAll()
    {
        // Sửa lại câu SQL - thiếu FROM bacsi
        $sql = "SELECT bacsi.*, khoa.TenKhoa 
                FROM bacsi
                JOIN khoa ON bacsi.MaKhoa = khoa.MaKhoa";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function search($keyword)
    {
        $keyword = "%" . $this->conn->real_escape_string($keyword) . "%";
        $sql = "SELECT bacsi.*, khoa.TenKhoa 
                FROM bacsi 
                JOIN khoa ON bacsi.MaKhoa = khoa.MaKhoa
                WHERE bacsi.HoTen LIKE ? 
                   OR khoa.TenKhoa LIKE ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $keyword, $keyword);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getAllDepartments()
    {
        $sql = "SELECT * FROM khoa";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function add($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO bacsi (HoTen, MaKhoa, SoDienThoai, Email, HinhAnhBacSi, MaNguoiDung) 
                                      VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "sissssi",
            $data['HoTen'],
            $data['MaKhoa'],
            $data['SoDienThoai'],
            $data['Email'],
            $data['HinhAnhBacSi'],
            $data['MaNguoiDung']
        );
        return $stmt->execute();
    }

    public function update($data)
    {
        if (empty($data['HinhAnhBacSi'])) {
            $stmt = $this->conn->prepare("UPDATE bacsi 
                                          SET HoTen=?, MaKhoa=?, SoDienThoai=?, Email=?
                                          WHERE MaBacSi=?");
            $stmt->bind_param("sisssi", $data['HoTen'], $data['MaKhoa'], $data['SoDienThoai'], $data['Email'], $data['MaBacSi']);
        } else {
            $stmt = $this->conn->prepare("UPDATE bacsi 
                                          SET HoTen=?, MaKhoa=?, SoDienThoai=?, Email=?, HinhAnhBacSi=? 
                                          WHERE MaBacSi=?");
            $stmt->bind_param("sisssi", $data['HoTen'], $data['MaKhoa'], $data['SoDienThoai'], $data['Email'], $data['HinhAnhBacSi'], $data['MaBacSi']);
        }
        return $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM bacsi WHERE MaBacSi=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function countAll()
    {
        $sql = "SELECT COUNT(*) AS total FROM bacsi";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc()['total'];
    }

    public function getConnection()
    {
        return $this->conn;
    }
}