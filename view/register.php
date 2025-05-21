<?php
// view/register.php
session_start();
require_once __DIR__ . "/../vendor/autoload.php"; // Autoload từ Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = "";

// Nếu form được submit, xử lý gửi OTP qua email
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu đăng ký từ form
    $registrationData = [
        'username' => $_POST['username'],
        'password' => $_POST['password'],
        'fullname' => $_POST['fullname'],
        'dob'      => $_POST['dob'],
        'gender'   => $_POST['gender'],
        'phone'    => $_POST['phone'],
        'email'    => $_POST['email'],
        'address'  => $_POST['address']
    ];
    
    // Sinh mã OTP (6 chữ số)
    $otp = rand(100000, 999999);
    $registrationData['otp'] = $otp;
    
    // Lưu tạm thông tin đăng ký và OTP vào session
    $_SESSION['registration'] = $registrationData;
    
    // Gửi email OTP
    $mail = new PHPMailer(true);
    try {
        // Cấu hình SMTP – sử dụng Gmail
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'hienquangtranht1@gmail.com';          // Thay bằng email của bạn
        $mail->Password   = 'qewg mrze brpz lncf';               // Thay bằng App Password nếu dùng xác thực 2 bước
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        
        // Cài đặt và gửi thông tin email
        $mail->setFrom('your-email@gmail.com', 'Your Name');
        $mail->addAddress($registrationData['email'], $registrationData['fullname']);
        $mail->isHTML(true);
        $mail->Subject = 'Mã xác nhận đăng ký tài khoản';
        $mail->Body    = "Mã xác nhận của bạn là: <b>{$otp}</b>";
        $mail->AltBody = "Mã xác nhận của bạn là: {$otp}";
        
        $mail->send();
        // Chuyển hướng sang trang verify.php để nhập OTP
        header("Location: verify.php");
        exit();
    } catch (Exception $e) {
        $message = "Không gửi được email xác nhận. Lỗi: " . $mail->ErrorInfo;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng ký tài khoản</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body { font-family: 'Roboto', sans-serif; background-color: #f9f9f9; }
    .register-container {
      margin-top: 50px;
      padding: 30px;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="register-container mx-auto col-md-6">
      <h2 class="text-center mb-4">Đăng ký tài khoản</h2>
      <?php if ($message != ""): ?>
         <div class="alert alert-danger"><?php echo $message; ?></div>
      <?php endif; ?>
      <form action="" method="POST">
         <div class="form-group">
           <label for="username">Tên đăng nhập</label>
           <input type="text" class="form-control" id="username" name="username" required>
         </div>
         <div class="form-group">
           <label for="password">Mật khẩu</label>
           <input type="password" class="form-control" id="password" name="password" required>
         </div>
         <div class="form-group">
           <label for="fullname">Họ và tên</label>
           <input type="text" class="form-control" id="fullname" name="fullname" required>
         </div>
         <div class="form-group">
           <label for="dob">Ngày sinh</label>
           <input type="date" class="form-control" id="dob" name="dob" required>
         </div>
         <div class="form-group">
           <label for="gender">Giới tính</label>
           <select class="form-control" id="gender" name="gender" required>
             <option value="Nam">Nam</option>
             <option value="Nữ">Nữ</option>
             <option value="Khác">Khác</option>
           </select>
         </div>
         <div class="form-group">
           <label for="phone">Số điện thoại</label>
           <input type="text" class="form-control" id="phone" name="phone" required>
         </div>
         <div class="form-group">
           <label for="email">Email</label>
           <input type="email" class="form-control" id="email" name="email" required>
         </div>
         <div class="form-group">
           <label for="address">Địa chỉ</label>
           <input type="text" class="form-control" id="address" name="address">
         </div>
         <button type="submit" class="btn btn-primary btn-block">Gửi mã xác nhận</button>
      </form>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
