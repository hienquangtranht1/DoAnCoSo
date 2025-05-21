<?php
session_start();

if (!defined('BASE_URL')) {
    define('BASE_URL', '/BOOKING/');
}

require_once __DIR__ . '/app/config/database.php';

// Lấy tên controller và action từ URL, mặc định nếu không có
$controllerName = isset($_GET['controller']) ? $_GET['controller'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Lọc ký tự không hợp lệ để bảo vệ khỏi tấn công
$controllerName = preg_replace('/[^a-zA-Z0-9]/', '', $controllerName);
$action = preg_replace('/[^a-zA-Z0-9]/', '', $action);

// Xây dựng đường dẫn đến file controller dựa trên tên chuỗi
$controllerFile = __DIR__ . '/app/controllers/' . ucfirst($controllerName) . 'Controller.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    // Ví dụ, nếu URL có controller=LichHenBs thì tên lớp sẽ là LichHenBsController
    $controllerClass = ucfirst($controllerName) . 'Controller';
    if (class_exists($controllerClass)) {
        $controllerInstance = new $controllerClass($conn);
        if (method_exists($controllerInstance, $action)) {
            $controllerInstance->$action();
            exit(); // Chấm dứt sau khi controller xử lý để tránh gọi view thêm lần nữa.
        } else {
            header("HTTP/1.0 404 Not Found");
            echo "Action '$action' không tồn tại trong controller '$controllerClass'.";
            exit();
        }
    } else {
        header("HTTP/1.0 404 Not Found");
        echo "Controller class '$controllerClass' không tồn tại.";
        exit();
    }
} else {
    header("HTTP/1.0 404 Not Found");
    echo "Controller '$controllerName' không tồn tại.";
    exit();
}
?>
