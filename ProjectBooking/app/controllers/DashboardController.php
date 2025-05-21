<?php
require_once __DIR__ . '/../models/Doctor.php';
require_once __DIR__ . '/../models/Patient.php';
require_once __DIR__ . '/../models/Schedule.php';

class DashboardController {
    public function index() {
        // Thống kê
        $bacSiModel = new BacSi();
        $benhNhanModel = new Patient();
        $lichLamViecModel = new LichLamViec();

        $stats = [
            'totalDoctors' => $bacSiModel->countAll(),
            'totalPatients' => $benhNhanModel->countAll(),
            'totalSchedules' => $lichLamViecModel->countAll()
        ];

        include __DIR__ . '/../views/dashboard/dashboard.php';
    }
}

?>
