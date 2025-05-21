
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bác sĩ</title>
  <!-- Nhúng Font Awesome 6 từ CDN (loại bỏ thuộc tính integrity nếu cần) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    .header {
      background-color: #007bff;
      color: #ffffff;
      padding: 10px 20px;
      text-align: right;
    }
    .header span {
      margin-right: 15px;
    }
    .logout-btn {
      background-color: #dc3545;
      color: #fff;
      padding: 5px 12px;
      border: none;
      border-radius: 3px;
      text-decoration: none;
      cursor: pointer;
      margin-left: 15px;
    }
    .logout-btn:hover {
      background-color: #c82333;
    }
    .sidebar {
      width: 250px;
      background-color: #2c3e50;
      color: #ecf0f1;
      position: fixed;
      left: 0;
      top: 0;
      bottom: 0;
      overflow-y: auto;
      padding-top: 20px;
    }
    .sidebar ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    .sidebar ul li { 
      border-bottom: 1px solid #34495e; 
    }
    .sidebar ul li a {
      display: block;
      color: #ecf0f1;
      text-decoration: none;
      padding: 15px 20px;
      transition: background-color 0.3s;
      font-size: 1rem;
    }
    .sidebar ul li a:hover,
    .sidebar ul li a.active { 
      background-color: #16a085;
    }
    .sidebar ul li a .icon { 
      margin-right: 10px;
    }
  </style>
</head>
<body>
  <div class="header">
    <span>Welcome, <?= isset($_SESSION["HoTenBacSi"]) ? htmlspecialchars($_SESSION["HoTenBacSi"]) : "Guest"; ?></span>
    <a href="<?= BASE_URL ?>app/controllers/logout.php" class="logout-btn">Đăng xuất</a>
  </div>
  <div class="sidebar">
    <ul>
  <!-- Lịch hẹn -->
  <li>
    <a href="<?php echo BASE_URL; ?>LichHenBs" id="lichhen-link">
      <i class="fa-solid fa-calendar icon"></i>Lịch hẹn
    </a>
  </li>
  <!-- Hồ sơ bệnh án -->
  <li>
    <a href="<?php echo BASE_URL; ?>HoSoBenhAn" id="hosobenhan-link">
      <i class="fa-solid fa-file-medical icon"></i>Hồ sơ bệnh án
    </a>
  </li>
  <!-- Lịch làm việc -->
  <li>
    <a href="<?php echo BASE_URL; ?>LichLamViec" id="lichlamviec-link">
      <i class="fa-solid fa-clock icon"></i>Lịch làm việc
    </a>
  </li>
  <!-- Thông tin cá nhân -->
  <li>
    <a href="<?php echo BASE_URL; ?>DoctorProfile" id="BacSi-link">
      <i class="fa-solid fa-user icon"></i>Thông tin cá nhân
    </a>
  </li>
  <!-- Đổi mật khẩu -->
  <li>
    <a href="<?php echo BASE_URL; ?>ChangePassword" id="doimatkhau-link">
      <i class="fa-solid fa-key icon"></i>Đổi mật khẩu
    </a>
  </li>
</ul>

  </div>
