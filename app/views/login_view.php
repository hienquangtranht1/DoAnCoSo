<?php
// File: app/views/login_view.php

// Chỉ khởi động session nếu chưa có phiên chạy
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Nếu người dùng đã đăng nhập, chuyển hướng trực tiếp sang trang lịch hẹn
if (isset($_SESSION["MaNguoiDung"])) {
    header("Location: /BOOKING/DoctorProfile"); // hoặc một controller hợp lý khác

    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập | Hệ thống Quản lý Bác sĩ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #2c3e50, #3498db);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            width: 100%;
            max-width: 420px;
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo i {
            font-size: 48px;
            color: #2c3e50;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group i {
            position: absolute;
            left: 15px;
            top: calc(50% + 10px); /* Thay đổi từ top: 50% thành top: calc(50% + 10px) */
            transform: translateY(-50%);
            color: #7f8c8d;
            font-size: 16px; /* Thêm font-size để đồng bộ */
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 500;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px 12px 45px;
            font-size: 16px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 5px rgba(52,152,219,0.3);
        }

        .submit-btn {
            width: 100%;
            background-color: #2c3e50;
            color: #fff;
            border: none;
            padding: 14px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-weight: 500;
        }

        .submit-btn:hover {
            background-color: #34495e;
        }

        .error {
            background-color: #fff3f3;
            color: #e74c3c;
            padding: 12px;
            border-left: 4px solid #e74c3c;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 14px;
        }

        .system-name {
            text-align: center;
            color: #fff;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 30px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="system-name">HỆ THỐNG BÁC SĨ </div>
        <div class="container">
            <div class="logo">
                <i class="fas fa-user-md"></i>
            </div>
            <h2>Đăng nhập hệ thống</h2>
            <?php
            // Hiển thị thông báo lỗi nếu có
            if (isset($error)) {
                echo "<div class='error'><i class='fas fa-exclamation-circle'></i> " . htmlspecialchars($error) . "</div>";
            }
            ?>
            <form method="POST" action="/booking/app/controllers/AuthController.php?action=login">
                <div class="form-group">
                    <label for="username">Tên đăng nhập:</label>
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" id="username" required placeholder="Nhập tên đăng nhập">
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu:</label>
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="password" required placeholder="Nhập mật khẩu">
                </div>
                <button type="submit" class="submit-btn">
                    <i class="fas fa-sign-in-alt"></i> Đăng nhập
                </button>
            </form>
        </div>
    </div>
</body>
</html>
