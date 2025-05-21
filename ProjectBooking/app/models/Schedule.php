<?php
class LichLamViec
{
    private $db;
    private $conn;

    public function __construct()
    {
        $this->conn = new mysqli("localhost", "root", "", "datlichkhambenh");
        if ($this->conn->connect_error) {
            die("❌ Kết nối thất bại: " . $this->conn->connect_error);
        }
    }

    public function getAll()
    {
        $sql = "SELECT llv.*, bs.HoTen AS TenBacSi FROM lichlamviec llv
                JOIN bacsi bs ON llv.MaBacSi = bs.MaBacSi";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function searchByDoctor($keyword)
    {
        $keyword = "%" . $this->conn->real_escape_string($keyword) . "%";
        $sql = "SELECT llv.*, bs.HoTen AS TenBacSi FROM lichlamviec llv
                JOIN bacsi bs ON llv.MaBacSi = bs.MaBacSi
                WHERE bs.HoTen LIKE ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $keyword);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }


    public function add($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO lichlamviec (MaBacSi, NgayLamViec, GioBatDau, GioKetThuc, TrangThai) 
                                      VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $data['MaBacSi'], $data['NgayLamViec'], $data['GioBatDau'], $data['GioKetThuc'], $data['TrangThai']);
        return $stmt->execute();
    }

    public function update($data)
    {
        $stmt = $this->conn->prepare("UPDATE lichlamviec SET MaBacSi=?, NgayLamViec=?, GioBatDau=?, GioKetThuc=?, TrangThai=? WHERE MaLich=?");
        $stmt->bind_param("issssi", $data['MaBacSi'], $data['NgayLamViec'], $data['GioBatDau'], $data['GioKetThuc'], $data['TrangThai'], $data['MaLich']);
        return $stmt->execute();
    }

    public function delete($MaLich)
    {
        $stmt = $this->conn->prepare("DELETE FROM lichlamviec WHERE MaLich=?");
        $stmt->bind_param("i", $MaLich);
        if (!$stmt->execute()) {
            error_log("SQL Error: " . $stmt->error);
            return false;
        }
        return true;
    }
    
    public function countAll()
    {
        $sql = "SELECT COUNT(*) AS total FROM lichlamviec";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc()['total'];
    }

    public function updateStatus($maLich, $trangThai)
    {
        try {
            // Thêm logging để debug
            error_log("Updating status: MaLich={$maLich}, TrangThai={$trangThai}");
            
            // Chuẩn bị statement
            $stmt = $this->conn->prepare("UPDATE lichlamviec SET TrangThai = ? WHERE MaLich = ?");
            if (!$stmt) {
                error_log("Prepare failed: " . $this->conn->error);
                return false;
            }
            
            // Bind parameters
            $stmt->bind_param("si", $trangThai, $maLich);
            
            // Execute query
            $result = $stmt->execute();
            if (!$result) {
                error_log("Execute failed: " . $stmt->error);
                return false;
            }
            
            // Kiểm tra xem có row nào bị ảnh hưởng không
            if ($stmt->affected_rows === 0) {
                error_log("No rows affected. MaLich might not exist: " . $maLich);
                // Vẫn trả về true nếu không có lỗi nhưng không có row nào bị ảnh hưởng
                // (có thể MaLich không tồn tại hoặc TrangThai đã có giá trị đó rồi)
                return true;
            }
            
            error_log("Update successful: " . $stmt->affected_rows . " row(s) affected");
            return true;
        } catch (Exception $e) {
            error_log("Exception in updateStatus: " . $e->getMessage());
            return false;
        }
    }
}