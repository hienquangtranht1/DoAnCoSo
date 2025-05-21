<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php include __DIR__ . '/../../header.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đổi mật khẩu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            
        }
        
        .container {
            background-color: #fff;
            padding: 30px;
            max-width: 500px;
            margin: 30px auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        form label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        form input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        form input[type="submit"] {
            display: block;
            width: 100%;
            margin-top: 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        form input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .message {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>


  <div class="container">
    <h2>Đổi mật khẩu</h2>
    <?php if (isset($_GET["success"])): ?>
        <div class="message success">Đổi mật khẩu thành công!</div>
    <?php endif; ?>
    <?php if (isset($_GET["error"])): ?>
      <?php 
         $error = $_GET["error"];
         $msg = "";
         if ($error == "confirm") {
              $msg = "Mật khẩu mới và xác nhận mật khẩu không khớp!";
         } elseif ($error == "old") {
              $msg = "Mật khẩu cũ không đúng!";
         } elseif ($error == "update") {
              $msg = "Lỗi khi cập nhật mật khẩu!";
         } elseif ($error == "user") {
              $msg = "Không tìm thấy thông tin người dùng!";
         }
      ?>
        <div class="message error"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>
    <form method="POST" action="<?= BASE_URL ?>app/controllers/ChangePasswordController.php?action=update">
        <label for="oldPassword">Mật khẩu cũ:</label>
        <input type="password" id="oldPassword" name="oldPassword" required>

        <label for="newPassword">Mật khẩu mới:</label>
        <input type="password" id="newPassword" name="newPassword" required>

        <label for="confirmPassword">Xác nhận mật khẩu mới:</label>
        <input type="password" id="confirmPassword" name="confirmPassword" required>

        <input type="submit" value="Đổi mật khẩu">
    </form>
  </div>
</body>
</html>
