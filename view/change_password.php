<?php
// view/change_password.php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
$user = $_SESSION['user'];
$message = isset($_SESSION['cp_message']) ? $_SESSION['cp_message'] : "";
unset($_SESSION['cp_message']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
   <meta charset="UTF-8">
   <title>Đổi mật khẩu - Four Rock</title>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <!-- Bootstrap, Font Awesome và Google Fonts -->
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
   <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
   <style>
      body {
         color: #000;
         font-family: 'Roboto', sans-serif;
      }
      /* Header giống trang index */
      .navbar-brand,
      .navbar-nav .nav-link {
         color: #000 !important;
      }
      .navbar-nav .nav-link:hover,
      .navbar-brand:hover {
         color: #000 !important;
      }
      /* Đặt khoảng cách khi header fixed */
      .profile-container {
         margin-top: 120px;
      }
      /* Input có icon toggle */
      .position-relative {
         position: relative;
      }
      .position-relative input.form-control {
         padding-right: 40px;
      }
      .toggle-password {
         cursor: pointer;
         position: absolute;
         right: 15px;
         top: 50%;
         transform: translateY(-50%);
         color: #333;
         z-index: 2;
      }
      /* Vùng thông báo OTP */
      #otpMessage {
         font-size: 0.9rem;
         color: #28a745; /* Màu xanh cho thông báo thành công */
         margin-top: 5px;
      }
   </style>
</head>
<body>
   <!-- Header giống index -->
   <header>
      <nav class="navbar navbar-expand-lg navbar-light fixed-top bg-light shadow-sm">
         <div class="container">
            <a class="navbar-brand" href="/booking/index.php">Four Rock</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
               <ul class="navbar-nav ml-auto">
                  <li class="nav-item"><a class="nav-link" href="/booking/index.php#hero">Trang chủ</a></li>
                  <li class="nav-item"><a class="nav-link" href="/booking/hospital-blog.html">Blog</a></li>
                  <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="serviceDropdown" role="button" data-toggle="dropdown">Dịch vụ</a>
                     <div class="dropdown-menu" aria-labelledby="serviceDropdown">
                        <a class="dropdown-item" href="/booking/general-checkup.html">Khám tổng quát</a>
                        <a class="dropdown-item" href="/booking/cardiology.html">Tim mạch</a>
                        <a class="dropdown-item" href="/booking/testing.html">Xét nghiệm</a>
                     </div>
                  </li>
                  <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="aboutDropdown" role="button" data-toggle="dropdown">Giới thiệu</a>
                     <div class="dropdown-menu" aria-labelledby="aboutDropdown">
                        <a class="dropdown-item" href="/booking/hospital-blog.html#benhvien">Bệnh viện</a>
                        <a class="dropdown-item" href="/booking/hospital-blog.html#bacsi">Bác sĩ</a>
                     </div>
                  </li>
                  <li class="nav-item"><a class="nav-link" href="/booking/index.php#news">Tin tức</a></li>
                  <li class="nav-item"><a class="nav-link" href="/booking/index.php#contact">Liên hệ</a></li>
                  <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                        Xin chào, <?php echo htmlspecialchars($user["TenDangNhap"]); ?>
                     </a>
                     <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="change_password.php">Đổi mật khẩu</a>
                        <a class="dropdown-item" href="update_profile.php">Cập nhật thông tin cá nhân</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/booking/view/logout.php">Đăng xuất</a>
                     </div>
                  </li>
               </ul>
            </div>
         </div>
      </nav>
   </header>

   <div class="container profile-container">
      <h2 class="text-center mb-4">Đổi mật khẩu</h2>
      <?php if ($message): ?>
         <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
      <?php endif; ?>

      <!-- Form đổi mật khẩu -->
      <form id="changePasswordForm" action="/booking/controller/UserController.php" method="post">
         <input type="hidden" name="action" value="changePassword">
         <div class="form-group position-relative">
            <label for="old_password">Mật khẩu cũ:</label>
            <input type="password" name="old_password" id="old_password" class="form-control" required>
            <i class="fas fa-eye toggle-password" data-target="#old_password"></i>
         </div>
         <div class="form-group position-relative">
            <label for="new_password">Mật khẩu mới:</label>
            <input type="password" name="new_password" id="new_password" class="form-control" required>
            <i class="fas fa-eye toggle-password" data-target="#new_password"></i>
         </div>
         <div class="form-group position-relative">
            <label for="confirm_password">Xác nhận mật khẩu mới:</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
            <i class="fas fa-eye toggle-password" data-target="#confirm_password"></i>
         </div>
         <div class="form-group">
            <label for="verification_code">Mã xác nhận (OTP):</label>
            <input type="text" name="verification_code" id="verification_code" class="form-control" required disabled>
         </div>
         <div id="otpMessage" class="text-success mb-3"></div>
         <div class="text-center">
            <button type="button" id="sendOtpBtn" class="btn btn-primary">Đổi mật khẩu</button>
            <button type="submit" id="submitPasswordBtn" class="btn btn-primary" style="display: none;">Xác nhận đổi mật khẩu</button>
         </div>
      </form>
   </div>

   <!-- jQuery và Bootstrap JS -->
   <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
   <script>
      $(document).ready(function(){
         // Toggle hiển thị mật khẩu qua icon
         $(".toggle-password").click(function(){
            var targetInput = $($(this).data("target"));
            var type = targetInput.attr("type") === "password" ? "text" : "password";
            targetInput.attr("type", type);
            $(this).toggleClass("fa-eye fa-eye-slash");
         });

         // Xử lý khi nhấn nút "Đổi mật khẩu" để gửi OTP
         $("#sendOtpBtn").click(function(e){
            e.preventDefault();
            $(this).prop("disabled", true).text("Đang gửi mã OTP...");
            $.ajax({
               url: "/booking/controller/UserController.php",
               type: "POST",
               data: { action: "sendVerificationCode" },
               success: function(response){
                  $("#otpMessage").html("Mã OTP đã được gửi. Vui lòng nhập mã để đổi mật khẩu.");
                  $("#verification_code").prop("disabled", false);
                  $("#sendOtpBtn").hide();
                  $("#submitPasswordBtn").show();
               },
               error: function(){
                  $("#otpMessage").html("Có lỗi xảy ra khi gửi mã OTP.");
                  $("#sendOtpBtn").prop("disabled", false).text("Đổi mật khẩu");
               }
            });
         });
      });
   </script>
</body>
</html>
