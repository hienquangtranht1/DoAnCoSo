<?php
session_start();
$loggedInUser = isset($_SESSION['user']) ? $_SESSION['user'] : null;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Four Rock - Chăm sóc sức khỏe hàng đầu</title>

  <!-- Google Fonts & Bootstrap CSS -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="public/css/style.css">

  <!-- Inline CSS cho một số tùy chỉnh -->
  <style>
    /* HERO SECTION & Carousel */
    .hero-section {
      position: relative;
      height: 100vh;
      overflow: hidden;
    }
    .hero-section .carousel-item img {
      height: 100vh;
      object-fit: cover;
    }

    /* Registration Form Overlay */
    .registration-overlay {
      position: absolute;
      top: 50%;
      right: 5%;
      transform: translateY(-50%);
      width: 90%;
      max-width: 300px;
      z-index: 2;
    }
    .registration-overlay .card {
      border: none;
      border-radius: 8px;
      overflow: hidden;
      background: rgba(255, 255, 255, 0.95);
    }
    .registration-overlay .card-header {
      background-color: #007bff;
      padding: 0.75rem;
      text-align: center;
    }
    .registration-overlay .card-header h4 {
      margin: 0;
      font-size: 1.1rem;
    }
    .registration-overlay .card-body {
      padding: 0.75rem;
    }
    .registration-overlay .form-control {
      font-size: 0.85rem;
      padding: 0.4rem 0.65rem;
    }
    .registration-overlay .btn {
      font-size: 0.9rem;
      padding: 0.45rem 1rem;
    }
    /* Service Card Hover Effect */
    #services .card {
      border: none;
      border-radius: 10px;
      overflow: hidden;
      transition: transform 0.3s, box-shadow 0.3s;
    }
    #services .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    /* Chat styles */
    /* Đây là phần chat tích hợp trực tiếp trong index */
    #chat-icon {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: #007bff;
      color: #fff;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      text-align: center;
      line-height: 60px;
      font-size: 28px;
      cursor: pointer;
      z-index: 9999;
      box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }
    #chat-box {
      position: fixed;
      bottom: 90px;
      right: 20px;
      width: 300px;
      max-height: 400px;
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      display: none;
      flex-direction: column;
      z-index: 9999;
      transition: opacity 0.3s ease;
    }
    #chat-header {
      background: #007bff;
      color: #fff;
      padding: 10px;
      border-top-left-radius: 8px;
      border-top-right-radius: 8px;
    }
    #chat-messages {
      flex: 1;
      padding: 10px;
      overflow-y: auto;
    }
    #chat-input {
      border-top: 1px solid #ddd;
      display: flex;
    }
    #chat-input input {
      flex: 1;
      border: none;
      padding: 10px;
    }
    #chat-input button {
      border: none;
      padding: 10px;
      background: #007bff;
      color: #fff;
    }
    /* Chatbot floating button */


    /* Chatbot box */


    #chatbot-header {
      background: #28a745;
      color: #fff;
      padding: 10px;
      border-top-left-radius: 8px;
      border-top-right-radius: 8px;
    }

    #chatbot-messages {
      flex: 1;
      padding: 10px;
      overflow-y: auto;
    }

    #chatbot-input {
      border-top: 1px solid #ddd;
      display: flex;
    }

    #chatbot-input input {
      flex: 1;
      border: none;
      padding: 10px;
    }

    #chatbot-input button {
      border: none;
      padding: 10px;
      background: #28a745;
      color: #fff;
    }
  </style>
</head>
<body>
  <!-- Header Navigation -->
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
  
  <!-- Hero Section với Carousel -->
  <section id="hero" class="hero-section">
    <div id="heroCarousel" class="carousel slide" data-ride="carousel">
      <ol class="carousel-indicators">
        <li data-target="#heroCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#heroCarousel" data-slide-to="1"></li>
        <li data-target="#heroCarousel" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="public/images/hero1.jpg" class="d-block w-100" alt="Slide 1">
          <div class="carousel-caption d-none d-md-block">
            <h2>Chăm sóc sức khỏe toàn diện</h2>
            <p>Tiên phong trong đổi mới dịch vụ y tế</p>
          </div>
        </div>
        <div class="carousel-item">
          <img src="public/images/hero2.jpg" class="d-block w-100" alt="Slide 2">
          <div class="carousel-caption d-none d-md-block">
            <h2>Công nghệ hiện đại</h2>
            <p>Ứng dụng thiết bị y tế tiên tiến và AI</p>
          </div>
        </div>
        <div class="carousel-item">
          <img src="public/images/hero3.jpg" class="d-block w-100" alt="Slide 3">
          <div class="carousel-caption d-none d-md-block">
            <h2>Đội ngũ bác sĩ chuyên nghiệp</h2>
            <p>Luôn sẵn sàng chăm sóc sức khỏe của bạn</p>
          </div>
        </div>
      </div>
      <a class="carousel-control-prev" href="#heroCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#heroCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
    
    <!-- Registration Form Overlay -->
    <div class="registration-overlay">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
          <h4 class="mb-0">Đăng ký khám bệnh</h4>
        </div>
        <div class="card-body">
          <form action="register_process.php" method="post">
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="fullname">Họ và tên</label>
                <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Nhập họ và tên" required>
              </div>
              <div class="form-group col-md-6">
                <label for="dob">Ngày sinh</label>
                <input type="date" class="form-control" id="dob" name="dob" required>
              </div>
            </div>
            <div class="form-group">
              <label for="email_reg">Email</label>
              <input type="email" class="form-control" id="email_reg" name="email" placeholder="Nhập email" required>
            </div>
            <div class="form-group">
              <label for="phone_reg">Số điện thoại</label>
              <input type="text" class="form-control" id="phone_reg" name="phone" placeholder="Nhập số điện thoại" required>
            </div>
            <div class="form-group">
              <label for="address_reg">Địa chỉ</label>
              <input type="text" class="form-control" id="address_reg" name="address" placeholder="Nhập địa chỉ">
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="appointment_date">Ngày khám</label>
                <input type="date" class="form-control" id="appointment_date" name="appointment_date" required>
              </div>
              <div class="form-group col-md-6">
                <label for="appointment_time">Giờ khám</label>
                <input type="time" class="form-control" id="appointment_time" name="appointment_time" required>
              </div>
            </div>
            <div class="form-group">
              <label for="symptoms">Triệu chứng</label>
              <textarea class="form-control" id="symptoms" name="symptoms" rows="3" placeholder="Mô tả triệu chứng"></textarea>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary btn-lg">Đăng ký</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
  
  <!-- Services Section -->
  <section id="services" class="py-5 bg-light">
    <div class="container text-center">
      <h2 class="mb-4">Dịch vụ nổi bật</h2>
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="card border-0 shadow-sm">
            <div class="card-body">
              <i class="fas fa-stethoscope fa-3x text-primary mb-3"></i>
              <h4 class="card-title">Khám tổng quát</h4>
              <p class="card-text">Khám sức khỏe ban đầu, phát hiện sớm các bệnh lý tiềm ẩn.</p>
              <a href="general-checkup.html" class="btn btn-outline-primary btn-sm">Xem chi tiết</a>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card border-0 shadow-sm">
            <div class="card-body">
              <i class="fas fa-heartbeat fa-3x text-primary mb-3"></i>
              <h4 class="card-title">Tim mạch</h4>
              <p class="card-text">Giám sát và điều trị bệnh về tim mạch với chuyên gia hàng đầu.</p>
              <a href="cardiology.html" class="btn btn-outline-primary btn-sm">Xem chi tiết</a>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card border-0 shadow-sm">
            <div class="card-body">
              <i class="fas fa-x-ray fa-3x text-primary mb-3"></i>
              <h4 class="card-title">Xét nghiệm</h4>
              <p class="card-text">Cung cấp các dịch vụ xét nghiệm hiện đại với độ chính xác cao.</p>
              <a href="testing.html" class="btn btn-outline-primary btn-sm">Xem chi tiết</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <!-- About Section -->
  <section id="about" class="py-5 bg-light">
    <div class="container text-center">
      <h2 class="mb-4">Giới thiệu về Four Rock</h2>
      <p>Four Rock tự hào là địa chỉ chăm sóc sức khỏe hàng đầu với cơ sở vật chất hiện đại, đội ngũ bác sĩ chuyên nghiệp và dịch vụ y tế toàn diện. Chúng tôi cam kết mang lại sự an tâm và hài lòng cho bệnh nhân thông qua chất lượng dịch vụ và ứng dụng công nghệ tiên tiến.</p>
      <a href="view/register.php" class="btn btn-primary mt-3">Đăng ký ngay</a>
    </div>
  </section>
  
  <!-- News Section -->
  <section id="news" class="py-5">
    <div class="container">
      <h2 class="text-center mb-4">Tin tức & Sự kiện</h2>
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="card shadow-sm">
            <img src="public/images/news1.jpg" class="card-img-top" alt="Tin tức 1">
            <div class="card-body">
              <h4 class="card-title">Hành trình 7 năm đồng hành cùng khách hàng</h4>
              <p class="card-text">Four Rock kỷ niệm 7 năm với các cột mốc ấn tượng trong việc chăm sóc sức khỏe cộng đồng.</p>
              <a href="#" class="btn btn-primary">Đọc tiếp</a>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card shadow-sm">
            <img src="public/images/news2.jpg" class="card-img-top" alt="Tin tức 2">
            <div class="card-body">
              <h4 class="card-title">Triển lãm công nghệ y tế 2025</h4>
              <p class="card-text">Cập nhật xu hướng công nghệ mới trong ngành y tế.</p>
              <a href="#" class="btn btn-primary">Đọc tiếp</a>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card shadow-sm">
            <img src="public/images/news3.jpg" class="card-img-top" alt="Tin tức 3">
            <div class="card-body">
              <h4 class="card-title">Chương trình khuyến mãi đặc biệt</h4>
              <p class="card-text">Những ưu đãi hấp dẫn dành cho khách hàng thân thiết.</p>
              <a href="#" class="btn btn-primary">Đọc tiếp</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <!-- Contact Section -->
  <section id="contact" class="py-5 bg-light">
    <div class="container">
      <h2 class="text-center mb-4">Liên hệ</h2>
      <div class="row">
        <div class="col-md-4">
          <div class="contact-info text-center">
            <a href="https://www.google.com/maps" target="_blank">
              <i class="fas fa-map-marker-alt fa-2x text-primary"></i>
            </a>
            <p>Khu E Hutech, Quận 9, TP. Hồ Chí Minh</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="contact-info text-center">
            <i class="fas fa-phone fa-2x text-primary"></i>
            <p>(0123) 456-789</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="contact-info text-center">
            <i class="fas fa-envelope fa-2x text-primary"></i>
            <p>info@Fourrock.com</p>
          </div>
        </div>
      </div>
      <div class="map-responsive mt-4">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.143663055253!2d106.68521381404032!3d10.762622292341966!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752ec52f001e69%3A0x3fbe3105faaaab16!2sKhu%20E%20Hutech!5e0!3m2!1svi!2s!4v1689066892370!5m2!1svi!2s" frameborder="0" style="border:0; width:100%; height:300px;" allowfullscreen="" loading="lazy"></iframe>
        <p class="mt-2">Địa chỉ: Khu E Hutech, Quận 9, TP. Hồ Chí Minh</p>
      </div>
    </div>
  </section>
  
  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-3">
    <div class="container">
      <p>&copy; <?php echo date("Y"); ?> Four Rock. All Rights Reserved.</p>
    </div>
  </footer>
  
  <!-- PHẦN CHAT TÍCH HỢP TRỰC TIẾP TRONG INDEX -->
  <?php
    $customer_id = isset($_SESSION['user']['MaNguoiDung']) ? $_SESSION['user']['MaNguoiDung'] : null;
    $customer_name = isset($_SESSION['user']['TenDangNhap']) ? $_SESSION['user']['TenDangNhap'] : "Khách";
    $cskh_id = 1; // ID cố định của CSKH
  ?>
  <!-- Chat Icon -->
  <div id="chat-icon">💬</div>
  <!-- Chat Box -->
  <div id="chat-box" class="d-flex flex-column">
    <div id="chat-header">
        Chat với CSKH
        <button type="button" style="float:right; background:none; border:none; color:#fff;" onclick="toggleChatBox()">×</button>
    </div>
    <div id="chat-messages">
        <!-- Danh sách tin nhắn sẽ được load qua AJAX -->
    </div>
    <div id="chat-input">
        <input type="text" id="messageInput" placeholder="Nhập tin nhắn...">
        <button onclick="sendMessage()">Gửi</button>
    </div>
  </div>
  <script>
    const customer_id = <?php echo isset($_SESSION['user']['MaNguoiDung']) ? $_SESSION['user']['MaNguoiDung'] : 'null'; ?>;
    const cskh_id = 1;

    function loadMessages() {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "controller/ChatController.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                try {
                    const res = JSON.parse(xhr.responseText);
                    if (res.success) {
                        let msgsHtml = "";
                        res.messages.forEach(function(msg) {
                            if (parseInt(msg.sender_id) === customer_id) {
                                msgsHtml += `<div style='text-align:right;'><strong>Bạn:</strong> ${msg.message}</div>`;
                            } else {
                                msgsHtml += `<div style='text-align:left;'><strong>CSKH:</strong> ${msg.message}</div>`;
                            }
                        });
                        document.getElementById("chat-messages").innerHTML = msgsHtml;
                        document.getElementById("chat-messages").scrollTop = document.getElementById("chat-messages").scrollHeight;
                    } else {
                        document.getElementById("chat-messages").innerHTML = "<div>Không có tin nhắn nào.</div>";
                    }
                } catch (e) {
                    console.error("Lỗi parse JSON:", e);
                }
            }
        };
        xhr.send(`action=getMessages&user1=${customer_id}&user2=${cskh_id}`);
    }

    function sendMessage() {
        if (!customer_id || customer_id === null) {
            window.location.href = 'view/login.php';
            return;
        }

        const message = document.getElementById("messageInput").value.trim();
        if (message === "") {
            alert("Vui lòng nhập tin nhắn!");
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "controller/ChatController.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                try {
                    const res = JSON.parse(xhr.responseText);
                    if (res.success) {
                        document.getElementById("messageInput").value = "";
                        loadMessages();
                    } else {
                        alert(res.message || "Không thể gửi tin nhắn!");
                    }
                } catch (e) {
                    console.error("Lỗi parse JSON:", e);
                }
            }
        };
        xhr.send(`action=sendMessage&sender_id=${customer_id}&receiver_id=${cskh_id}&message=${encodeURIComponent(message)}`);
    }

    // Thêm sự kiện Enter để gửi tin nhắn
    document.getElementById("messageInput").addEventListener("keypress", function(e) {
        if (e.key === "Enter") {
            e.preventDefault();
            sendMessage();
        }
    });

    // Sửa lại interval (thêm đơn vị milliseconds)
    setInterval(function() {
        const chatBox = document.getElementById("chat-box");
        if (window.getComputedStyle(chatBox).display === "flex") {
            loadMessages();
        }
    }, 5);  // 5 giây

    // Thêm hàm kiểm tra đăng nhập khi mở chat
    function toggleChatBox() {
        if (!customer_id || customer_id === null) {
            window.location.href = 'view/login.php';
            return;
        }

        const chatBox = document.getElementById("chat-box");
        if (chatBox.style.display === "none" || chatBox.style.display === "") {
            chatBox.style.display = "flex";
            loadMessages();
        } else {
            chatBox.style.display = "none";
        }
    }

    // Cập nhật sự kiện click cho chat icon
    document.getElementById("chat-icon").addEventListener("click", toggleChatBox);
  </script>

  <!-- Chatbot Icon -->
  <div id="chatbot-icon">🤖</div>

  <!-- Chatbot Box -->
  <div id="chatbot-box" class="d-flex flex-column">
    <div id="chatbot-header">
      Chat với Chatbot
      <button type="button" style="float:right; background:none; border:none; color:#fff;" onclick="toggleChatbotBox()">×</button>
    </div>
    <div id="chatbot-messages">
      <!-- Danh sách tin nhắn sẽ được load qua JavaScript -->
    </div>
    <div id="chatbot-input">
      <input type="text" id="chatbotMessageInput" placeholder="Nhập tin nhắn...">
      <button onclick="sendChatbotMessage()">Gửi</button>
    </div>
  </div>
  
  <script>
  // Hàm toggle hiển thị/ẩn chatbot box
  function toggleChatbotBox() {
    var chatbotBox = document.getElementById("chatbot-box");
    if (chatbotBox.style.display === "none" || chatbotBox.style.display === "") {
      chatbotBox.style.display = "flex";
    } else {
      chatbotBox.style.display = "none";
    }
  }

  // Gán sự kiện click cho icon chatbot
  document.getElementById("chatbot-icon").addEventListener("click", toggleChatbotBox);

  // Hàm gửi tin nhắn đến chatbot
  function sendChatbotMessage() {
    var message = document.getElementById("chatbotMessageInput").value.trim();
    if (message === "") return;

    var chatbotMessages = document.getElementById("chatbot-messages");
    chatbotMessages.innerHTML += "<div style='text-align:right;'><strong>Bạn:</strong> " + message + "</div>";

    fetch("chatbot.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "message=" + encodeURIComponent(message)
    })
    .then(response => response.json())
    .then(data => {
      chatbotMessages.innerHTML += "<div style='text-align:left;'><strong>Bot:</strong> " + data.reply + "</div>";
      document.getElementById("chatbotMessageInput").value = "";
      chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    })
    .catch(error => {
      console.error("Lỗi:", error);
      chatbotMessages.innerHTML += "<div style='text-align:left; color:red;'><strong>Bot:</strong> Lỗi khi kết nối đến chatbot.</div>";
    });
  }
  </script>
  
  <!-- Load jQuery và Bootstrap JS từ CDN -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
