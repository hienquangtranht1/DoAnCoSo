<?php
// view/update_profile.php
session_start();
if (!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}
$user = $_SESSION['user'];
$notification = isset($_SESSION['notification']) ? $_SESSION['notification'] : "";
unset($_SESSION['notification']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cập nhật thông tin cá nhân - Four Rock</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/booking/public/css/style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    body { 
      color: #000; 
    }
    .profile-container { 
      margin-top: 100px; 
    }
    .profile-image { 
      max-width: 150px; 
      max-height: 150px; 
    }
    /* Các quy tắc CSS ở header để đặt chữ thành màu đen */
    .navbar-light .navbar-nav .nav-link,
    .navbar-light .navbar-brand {
      color: #000 !important;
    }
    /* Nếu cần, đặt màu cho các link hover trong header */
    .navbar-light .navbar-nav .nav-link:hover,
    .navbar-light .navbar-brand:hover {
      color: #000 !important;
    }
  </style>
</head>
<body>
  <!-- Header với chữ màu đen được áp dụng qua CSS -->
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
              <a class="nav-link dropdown-toggle" href="#" id="serviceDropdown" role="button" data-toggle="dropdown">
                Dịch vụ
              </a>
              <div class="dropdown-menu" aria-labelledby="serviceDropdown">
                <a class="dropdown-item" href="/booking/general-checkup.html">Khám tổng quát</a>
                <a class="dropdown-item" href="/booking/cardiology.html">Tim mạch</a>
                <a class="dropdown-item" href="/booking/testing.html">Xét nghiệm</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="aboutDropdown" role="button" data-toggle="dropdown">
                Giới thiệu
              </a>
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
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
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
  
  <!-- Form cập nhật thông tin cá nhân -->
  <div class="container profile-container">
    <h2 class="text-center mb-4">Cập nhật thông tin cá nhân</h2>
    <?php if ($notification): ?>
      <div class="alert alert-info"><?php echo htmlspecialchars($notification); ?></div>
    <?php endif; ?>
    <form action="/booking/controller/UserController.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="action" value="updateProfile">
      
      <!-- Tên đăng nhập (không thay đổi) -->
      <div class="form-group">
        <label for="username">Tên đăng nhập</label>
        <input type="text" id="username" class="form-control" value="<?php echo htmlspecialchars($user["TenDangNhap"]); ?>" disabled>
      </div>
      
      <!-- Họ và tên -->
      <div class="form-group">
        <label for="fullname">Họ và tên</label>
        <input type="text" name="fullname" id="fullname" class="form-control" required value="<?php echo isset($user['HoTen']) ? htmlspecialchars($user['HoTen']) : ''; ?>">
      </div>
      
      <!-- Ngày sinh -->
      <div class="form-group">
        <label for="dob">Ngày sinh</label>
        <input type="date" name="dob" id="dob" class="form-control" required value="<?php echo isset($user['NgaySinh']) ? htmlspecialchars($user['NgaySinh']) : ''; ?>">
      </div>
      
      <!-- Giới tính -->
      <div class="form-group">
        <label for="gender">Giới tính</label>
        <select name="gender" id="gender" class="form-control" required>
          <option value="Nam" <?php echo (isset($user['GioiTinh']) && $user['GioiTinh'] == 'Nam') ? 'selected' : ''; ?>>Nam</option>
          <option value="Nữ" <?php echo (isset($user['GioiTinh']) && $user['GioiTinh'] == 'Nữ') ? 'selected' : ''; ?>>Nữ</option>
          <option value="Khác" <?php echo (isset($user['GioiTinh']) && $user['GioiTinh'] == 'Khác') ? 'selected' : ''; ?>>Khác</option>
        </select>
      </div>
      
      <!-- Email (không cho sửa) -->
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" class="form-control" value="<?php echo isset($user['Email']) ? htmlspecialchars($user['Email']) : ''; ?>" disabled>
        <input type="hidden" name="email" value="<?php echo isset($user['Email']) ? htmlspecialchars($user['Email']) : ''; ?>">
      </div>
      
      <!-- Số điện thoại -->
      <div class="form-group">
        <label for="phone">Số điện thoại</label>
        <input type="text" name="phone" id="phone" class="form-control" required value="<?php echo isset($user['SoDienThoai']) ? htmlspecialchars($user['SoDienThoai']) : ''; ?>">
      </div>
      
      <!-- Địa chỉ -->
      <div class="form-group">
        <label for="address">Địa chỉ</label>
        <input type="text" name="address" id="address" class="form-control" value="<?php echo isset($user['DiaChi']) ? htmlspecialchars($user['DiaChi']) : ''; ?>">
      </div>
      
      <!-- Số bảo hiểm -->
      <div class="form-group">
        <label for="soBaoHiem">Số bảo hiểm</label>
        <input type="text" name="soBaoHiem" id="soBaoHiem" class="form-control" value="<?php echo isset($user['SoBaoHiem']) ? htmlspecialchars($user['SoBaoHiem']) : ''; ?>">
      </div>
      
      <!-- Hình ảnh cá nhân -->
      <div class="form-group">
        <label for="HinhAnhBenhNhan">Hình ảnh cá nhân</label>
        <?php if (isset($user['HinhAnhBenhNhan']) && !empty($user['HinhAnhBenhNhan'])): ?>
          <div style="margin-bottom: 10px;">
            <img src="/booking/public/uploads/<?php echo htmlspecialchars($user['HinhAnhBenhNhan']); ?>" alt="Hình ảnh cá nhân" class="profile-image">
          </div>
        <?php endif; ?>
        <input type="file" name="HinhAnhBenhNhan" id="HinhAnhBenhNhan" class="form-control-file" accept="image/*" capture="camera">
      </div>
      
      <!-- Nút submit -->
      <div class="text-center">
        <button type="submit" class="btn btn-primary">Cập nhật thông tin</button>
      </div>
    </form>
  </div>
  
  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-3 mt-5">
    <div class="container">
      <p>&copy; 2025 Four Rock. All Rights Reserved.</p>
    </div>
  </footer>
  
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
