<?php
class LichLamViec {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function getSchedulesByDoctor($maBacSi) {
        $sql = "SELECT * FROM LichLamViec WHERE MaBacSi = ? ORDER BY NgayLamViec ASC, GioBatDau ASC";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("i", $maBacSi);
            $stmt->execute();
            $result = $stmt->get_result();
            $schedules = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $schedules;
        }
        return [];
    }
    
    public function getSchedulesByDoctorAndWeek($maBacSi, $startDate, $endDate) {
        $sql = "SELECT * FROM LichLamViec WHERE MaBacSi = ? AND NgayLamViec BETWEEN ? AND ? ORDER BY NgayLamViec ASC, GioBatDau ASC";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("iss", $maBacSi, $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();
            $schedules = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $schedules;
        }
        return [];
    }
    
    public function addSchedule($maBacSi, $ngayLamViec, $gioBatDau, $gioKetThuc) {
        $timestamp = strtotime($ngayLamViec);
        $dayNumber = date("N", $timestamp);
        if ($dayNumber == 7) { // 7 là Chủ nhật
            return false; 
        }
        $mapping = [
            1 => 'Thứ 2',
            2 => 'Thứ 3',
            3 => 'Thứ 4',
            4 => 'Thứ 5',
            5 => 'Thứ 6',
            6 => 'Thứ 7'
        ];
        $thuTrongTuan = $mapping[$dayNumber];
        $sql = "INSERT INTO LichLamViec (MaBacSi, NgayLamViec, ThuTrongTuan, GioBatDau, GioKetThuc) VALUES (?, ?, ?, ?, ?)";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("issss", $maBacSi, $ngayLamViec, $thuTrongTuan, $gioBatDau, $gioKetThuc);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }
        return false;
    }
    
    public function updateSchedule($maLich, $gioBatDau, $gioKetThuc) {
        $sql = "UPDATE LichLamViec SET GioBatDau = ?, GioKetThuc = ? WHERE MaLich = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("ssi", $gioBatDau, $gioKetThuc, $maLich);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }
        return false;
    }
    
    public function deleteSchedule($maLich) {
        $sql = "DELETE FROM LichLamViec WHERE MaLich = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("i", $maLich);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }
        return false;
    }
}
?>
