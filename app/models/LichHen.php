<?php
// app/models/LichHen.php
class LichHen {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function getAppointmentsByDoctorAndDate($maBacSi, $selectedDate) {
        $sql = "SELECT LH.MaLich, LH.NgayGio, LH.TrangThai, LH.TrieuChung,
                       BN.HoTen, BN.DiaChi, BN.GioiTinh
                FROM LichHen LH 
                INNER JOIN BenhNhan BN ON LH.MaBenhNhan = BN.MaBenhNhan 
                WHERE LH.MaBacSi = ? AND DATE(LH.NgayGio) = ?
                ORDER BY LH.NgayGio ASC";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }
        $stmt->bind_param("is", $maBacSi, $selectedDate);
        $stmt->execute();
        $result = $stmt->get_result();
        $appointments = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $appointments;
    }
}
?>
