<?php
define('BASE_URL', 'http://localhost:8080/ProjectBooking');
require_once "app/controllers/DashboardController.php";
require_once "app/controllers/PatientController.php";
require_once "app/controllers/DoctorController.php";
require_once "app/controllers/ScheduleController.php";
require_once __DIR__ . '/app/controllers/LoginController.php';

$controller = $_GET['controller'] ?? 'dashboard';
$action = $_GET['action'] ?? 'index';
$action = $_GET['action'] ?? 'index';

// Xử lý login
if ($controller === 'login') {
    $loginController = new LoginController();
    if ($action === 'index') {
        $loginController->index();
    } elseif ($action === 'authenticate') {
        $loginController->authenticate();
    } elseif ($action === 'logout') {
        $loginController->logout();
    }
    exit(); // Dừng xử lý các phần khác sau khi xử lý login
}
switch ($controller) {
    case 'dashboard':
        $dashboardController = new DashboardController();
        $dashboardController->index();
        break;

    case 'benhnhan':
        $benhNhanController = new PatientController();
        if ($action === 'index') {
            $benhNhanController->index();
        } elseif ($action === 'update') {
            $benhNhanController->update();
        } elseif ($action === 'destroy') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $benhNhanController->destroy();
            } else {
                echo "Lỗi: Phương thức HTTP không hợp lệ!";
            }
        } else {
            echo "Lỗi: Hành động không hợp lệ!";
        }
        break;
        
    case 'bacsi':
        $bacSiController = new BacSiController();
        if ($action === 'index') {
            $bacSiController->index();
        } elseif ($action === 'store') {
            $bacSiController->store(); // Xử lý thêm/sửa bác sĩ
        } elseif ($action === 'update') {
            $bacSiController->update();
        } elseif ($action === 'destroy') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $bacSiController->destroy();
            } else {
                echo "Lỗi: Phương thức HTTP không hợp lệ!";
            }
        } else {
            echo "Lỗi: Hành động không hợp lệ!";
        }
        break;
    case 'lichkham':
        $lichKhamController = new LichLamViecController();
        if ($action === 'index') {
            $lichKhamController->index();
        } elseif ($action === 'store') {
            $lichKhamController->store();
        } elseif ($action === 'update') {
            $lichKhamController->update();
        } elseif ($action === 'destroy') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $lichKhamController->destroy();
            } else {
                echo "Lỗi: Phương thức HTTP không hợp lệ!";
            }
        } elseif ($action === 'updateStatus') {
            // Xử lý AJAX request để cập nhật trạng thái
            $lichKhamController->updateStatus();
        } else {
            echo "Lỗi: Hành động không hợp lệ!";
        }
        break;

    default:
        echo "Lỗi: Trang không tồn tại!";
    case 'dashboard':
        $dashboardController = new DashboardController();
        $dashboardController->index();
        break;
}
