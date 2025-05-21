<?php
session_start();

if (!isset($_SESSION['forgot_password_otp']) || !isset($_SESSION['forgot_password_user_id'])) {
    echo "OTP đã hết hạn. Vui lòng yêu cầu gửi lại OTP từ trang <a href='login.php'>Đăng nhập</a>.";
    exit();
}

require_once __DIR__ . "/../model/Database.php";
require_once __DIR__ . "/../model/UserModel.php";

$error = "";
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['step']) && $_POST['step'] === "verify_otp") {
        $otpInput = trim($_POST['otp']);
        if ($otpInput != $_SESSION['forgot_password_otp']) {
            $error = "OTP không đúng. Vui lòng thử lại.";
        } else {
            // Nếu OTP đúng, đánh dấu xác thực thành công
            $_SESSION['otp_verified'] = true;
            $message = "OTP hợp lệ. Vui lòng nhập mật khẩu mới.";
        }
    } elseif (isset($_POST['step']) && $_POST['step'] === "reset_password") {
        // Kiểm tra xem OTP đã được xác thực hay chưa
        if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
            $error = "OTP chưa được xác thực.";
        } else {
            $newPassword     = trim($_POST['new_password']);
            $confirmPassword = trim($_POST['confirm_password']);
            
            if ($newPassword !== $confirmPassword) {
                $error = "Mật khẩu mới và xác nhận mật khẩu không khớp.";
            } else {
                // Cập nhật mật khẩu mới vào cơ sở dữ liệu
                $conn = Database::getInstance()->getConnection();
                $userModel = new UserModel($conn);
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $userId = $_SESSION['forgot_password_user_id'];
                
                if ($userModel->updatePassword($userId, $hashedPassword)) {
                    // Xóa dữ liệu OTP khỏi session sau khi cập nhật thành công
                    unset($_SESSION['forgot_password_otp']);
                    unset($_SESSION['forgot_password_user_id']);
                    unset($_SESSION['otp_verified']);
                    
                    // Chuyển hướng về trang đăng nhập
                    header("Location: login.php");
                    exit();
                } else {
                    $error = "Có lỗi xảy ra khi cập nhật mật khẩu.";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt lại mật khẩu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { 
            font-family: 'Roboto', sans-serif; 
            background-color: #f2f2f2; 
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="text-center mb-4">Đặt lại mật khẩu</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    
    <?php 
    // Nếu OTP chưa được xác thực, hiển thị form nhập OTP.
    // Nếu đã được xác thực, hiển thị form nhập mật khẩu mới.
    if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true): ?>
        <form method="post" action="">
            <input type="hidden" name="step" value="verify_otp">
            <div class="form-group">
                <label for="otp">Nhập mã OTP:</label>
                <input type="text" name="otp" id="otp" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Xác nhận OTP</button>
        </form>
    <?php else: ?>
        <form method="post" action="">
            <input type="hidden" name="step" value="reset_password">
            <div class="form-group">
                <label for="new_password">Mật khẩu mới:</label>
                <input type="password" name="new_password" id="new_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Xác nhận mật khẩu mới:</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success btn-block">Đặt lại mật khẩu</button>
        </form>
    <?php endif; ?>
    
    <div class="mt-3 text-center">
        <a href="login.php">Quay lại trang đăng nhập</a>
    </div>
</div>
</body>
</html>
