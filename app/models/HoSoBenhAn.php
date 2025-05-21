<?php
class HoSoBenhAn {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Lấy danh sách tất cả hồ sơ bệnh án,
    // bao gồm tên bệnh nhân (bn.HoTen) và tên bác sĩ (bs.HoTen)
    public function getAllRecords() {
        $sql = "SELECT h.*, bn.HoTen AS TenBenhNhan, bs.HoTen AS TenBacSi 
                FROM HoSoBenhAn h
                JOIN BenhNhan bn ON h.MaBenhNhan = bn.MaBenhNhan
                JOIN BacSi bs ON h.MaBacSi = bs.MaBacSi";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy thông tin hồ sơ bệnh án theo mã hồ sơ,
    // bao gồm tên bệnh nhân và tên bác sĩ
    public function getRecordById($maHoSo) {
        $sql = "SELECT h.*, bn.HoTen AS TenBenhNhan, bs.HoTen AS TenBacSi 
                FROM HoSoBenhAn h
                JOIN BenhNhan bn ON h.MaBenhNhan = bn.MaBenhNhan
                JOIN BacSi bs ON h.MaBacSi = bs.MaBacSi
                WHERE h.MaHoSo = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maHoSo);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Thêm hồ sơ bệnh án
    public function addRecord($maBenhNhan, $maBacSi, $ngayKham, $chanDoan, $phuongAnDieuTri) {
        $sql = "INSERT INTO HoSoBenhAn (MaBenhNhan, MaBacSi, NgayKham, ChanDoan, PhuongAnDieuTri) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iisss", $maBenhNhan, $maBacSi, $ngayKham, $chanDoan, $phuongAnDieuTri);
        return $stmt->execute();
    }

    // Sửa hồ sơ bệnh án
    public function updateRecord($maHoSo, $chanDoan, $phuongAnDieuTri) {
        $sql = "UPDATE HoSoBenhAn SET ChanDoan = ?, PhuongAnDieuTri = ? WHERE MaHoSo = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $chanDoan, $phuongAnDieuTri, $maHoSo);
        return $stmt->execute();
    }

    // Tìm kiếm hồ sơ bệnh án dựa trên tên bệnh nhân
    public function searchRecordsByPatientName($searchTerm) {
        $sql = "SELECT h.*, bn.HoTen AS TenBenhNhan, bs.HoTen AS TenBacSi
                FROM HoSoBenhAn h
                JOIN BenhNhan bn ON h.MaBenhNhan = bn.MaBenhNhan
                JOIN BacSi bs ON h.MaBacSi = bs.MaBacSi
                WHERE bn.HoTen LIKE ?";
        if ($stmt = $this->conn->prepare($sql)) {
            $likeTerm = "%" . $searchTerm . "%";
            $stmt->bind_param("s", $likeTerm);
            $stmt->execute();
            $result = $stmt->get_result();
            $records = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $records;
        }
        return [];
    }

    // Tìm kiếm hồ sơ bệnh án dựa trên chẩn đoán (ChanDoan)
    public function searchRecordsByChanDoan($searchTerm) {
        $sql = "SELECT h.*, bn.HoTen AS TenBenhNhan, bs.HoTen AS TenBacSi
                FROM HoSoBenhAn h
                JOIN BenhNhan bn ON h.MaBenhNhan = bn.MaBenhNhan
                JOIN BacSi bs ON h.MaBacSi = bs.MaBacSi
                WHERE h.ChanDoan LIKE ?";
        if ($stmt = $this->conn->prepare($sql)) {
            $likeTerm = "%" . $searchTerm . "%";
            $stmt->bind_param("s", $likeTerm);
            $stmt->execute();
            $result = $stmt->get_result();
            $records = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $records;
        }
        return [];
    }
}
?>
