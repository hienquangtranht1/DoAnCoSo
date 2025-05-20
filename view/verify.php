<?php
// view/verify.php
session_start();
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enteredOTP = $_POST['otp'];
    if (!isset($_SESSION['registration'])) {
        $message = "Dữ liệu đăng ký đã hết hạn. Vui lòng đăng ký lại.";
    } else {
        $storedOTP = $_SESSION['registration']['otp'];
        if ($enteredOTP == $storedOTP) {
            // Nếu mã OTP chính xác, tiến hành đăng ký chính thức
            require_once __DIR__ . "/../controller/UserController.php";
            $controller = new UserController();
            $registrationData = $_SESSION['registration'];
            // Loại bỏ thông tin OTP khỏi dữ liệu đăng ký
            unset($registrationData['otp']);
            $result = $controller->register($registrationData);
            if ($result === true) {
                // Xóa dữ liệu đăng ký khỏi session
                unset($_SESSION['registration']);
                // Chuyển hướng tới trang đăng nhập
                header("Location: /booking/view/login.php");  // Chỉnh sửa đường dẫn nếu file login.php nằm ở vị trí khác
                exit();
            } else {
                $message = "Đăng ký thất bại: " . $result;
            }
        } else {
            $message = "Mã xác nhận không đúng. Vui lòng kiểm tra lại.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Xác nhận mã đăng ký</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f9f9f9;
    }
    .verify-container {
      margin-top: 100px;
      padding: 30px;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="verify-container mx-auto col-md-6">
      <h2 class="text-center mb-4">Nhập mã xác nhận</h2>
      <?php if ($message != ""): ?>
         <div class="alert alert-info"><?php echo $message; ?></div>
      <?php endif; ?>
      <form action="" method="POST">
         <div class="form-group">
           <label for="otp">Mã OTP</label>
           <input type="text" class="form-control" id="otp" name="otp" placeholder="Nhập mã OTP" required>
         </div>
         <button type="submit" class="btn btn-primary btn-block">Xác nhận</button>
      </form>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
