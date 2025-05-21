<header>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top bg-light shadow-sm">
      <div class="container">
        <a class="navbar-brand" href="index.php">Four Rock</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ml-auto">
            <!-- Menu chính -->
            <li class="nav-item"><a class="nav-link" href="#hero">Trang chủ</a></li>
            <li class="nav-item"><a class="nav-link" href="hospital-blog.html">Blog</a></li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="serviceDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Dịch vụ
              </a>
              <div class="dropdown-menu" aria-labelledby="serviceDropdown">
                <a class="dropdown-item" href="general-checkup.html">Khám tổng quát</a>
                <a class="dropdown-item" href="cardiology.html">Tim mạch</a>
                <a class="dropdown-item" href="testing.html">Xét nghiệm</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="aboutDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Giới thiệu
              </a>
              <div class="dropdown-menu" aria-labelledby="aboutDropdown">
                <a class="dropdown-item" href="hospital-blog.html#benhvien">Bệnh viện</a>
                <a class="dropdown-item" href="hospital-blog.html#bacsi">Bác sĩ</a>
              </div>
            </li>
            <li class="nav-item"><a class="nav-link" href="#news">Tin tức</a></li>
                    <li class="nav-item">
                <a class="nav-link" href="view/qa_doctor.php">Hỏi đáp với bác sĩ</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="#contact">Liên hệ</a></li>
            <!-- Menu người dùng -->
            <?php if ($loggedInUser): ?>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                  Xin chào, <?php echo htmlspecialchars($loggedInUser["TenDangNhap"]); ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                  <a class="dropdown-item" href="view/change_password.php">Đổi mật khẩu</a>
                  <a class="dropdown-item" href="view/update_profile.php">Thay đổi thông tin cá nhân</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="view/logout.php">Đăng xuất</a>
                </div>
              </li>
            <?php else: ?>
              <li class="nav-item"><a class="btn btn-primary mr-2" href="view/register.php">Đăng ký</a></li>
              <li class="nav-item"><a class="btn btn-outline-primary" href="view/login.php">Đăng nhập</a></li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>
</header>
