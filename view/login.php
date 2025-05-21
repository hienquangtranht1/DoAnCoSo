<?php
session_start();
$message = isset($_SESSION["login_message"]) ? $_SESSION["login_message"] : "";
unset($_SESSION["login_message"]);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng nhập - Four Rock</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body { 
      font-family: 'Roboto', sans-serif; 
      background-color: #f9f9f9;
    }
    .login-container {
      margin-top: 80px;
      padding: 30px;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Container đăng nhập -->
    <div class="login-container mx-auto col-md-6">
      <h2 class="text-center mb-4">Đăng nhập</h2>
      <?php if ($message): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div>
      <?php endif; ?>
      
      <!-- Form đăng nhập -->
      <form action="../controller/UserController.php" method="post">
        <input type="hidden" name="action" value="login">
        <div class="form-group">
          <label for="username">Tên đăng nhập:</label>
          <input type="text" id="username" name="username" class="form-control" required>
        </div>
        <div class="form-group">
          <label for="password">Mật khẩu:</label>
          <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
      </form>
      
      <!-- Liên kết quên mật khẩu -->
      <div class="text-center mt-3">
        <button class="btn btn-link" data-toggle="modal" data-target="#forgotPasswordModal">
          Quên mật khẩu?
        </button>
      </div>
    </div>
  </div>

  <!-- Modal Quên mật khẩu -->
  <div class="modal fade" id="forgotPasswordModal" tabindex="-1" role="dialog" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="forgotPasswordModalLabel">Quên mật khẩu</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Form gửi email để nhận OTP -->
          <!-- Form sẽ đẩy dữ liệu đến Controller (UserController.php) với action "sendForgotPasswordOtp".
               Sau khi OTP được gửi thành công, Controller sẽ chuyển hướng sang fg_password.php. -->
          <form id="forgotPasswordForm" action="../controller/UserController.php" method="post">
            <input type="hidden" name="action" value="sendForgotPasswordOtp">
            <div class="form-group">
              <label for="email">Nhập email đã đăng ký:</label>
              <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <button type="submit" id="sendOtpBtn" class="btn btn-primary btn-block">Tiếp theo</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Load thư viện jQuery và Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
