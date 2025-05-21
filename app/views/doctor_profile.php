<?php
// Giả định biến $doctor được truyền từ controller
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php include __DIR__ . '/../../header.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin cá nhân của bác sĩ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Style như cũ */
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            
        }
        
        .container {
            background-color: #fff;
            padding: 30px;
            max-width: 600px;
            margin: 30px auto;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
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
        form input[type="text"],
        form input[type="email"],
        form textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        form input[type="file"] {
            margin-top: 5px;
        }
        form input[type="submit"] {
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
        .profile-image {
            text-align: center;
            margin-bottom: 15px;
        }
        .profile-image img {
            max-width: 150px;
            border-radius: 50%;
        }
        .message {
            text-align: center;
            color: green;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Thông tin cá nhân của bác sĩ</h2>
    <?php if (isset($_GET["success"])): ?>
        <div class="message">Cập nhật thông tin thành công!</div>
    <?php endif; ?>
    <form method="POST" action="<?= BASE_URL ?>DoctorProfile?action=update" enctype="multipart/form-data">
        <div class="profile-image">
            <?php if (!empty($doctor['HinhAnhBacSi'])): ?>
                <!-- Sửa đường dẫn hiển thị hình ảnh -->
                <img src="<?= BASE_URL . $doctor['HinhAnhBacSi'] ?>?v=<?= time() ?>" alt="Hình ảnh bác sĩ">
            <?php else: ?>
                <p>Chưa có hình ảnh</p>
            <?php endif; ?>
        </div>

        <label for="HoTen">Họ và tên:</label>
        <input type="text" id="HoTen" name="HoTen" value="<?= htmlspecialchars($doctor['HoTen']); ?>" required>

        <label for="SoDienThoai">Số điện thoại:</label>
        <input type="text" id="SoDienThoai" name="SoDienThoai" value="<?= htmlspecialchars($doctor['SoDienThoai']); ?>" required>

        <label for="Email">Email:</label>
        <input type="email" id="Email" name="Email" value="<?= htmlspecialchars($doctor['Email']); ?>" required>

        <label for="MoTa">Mô tả:</label>
        <textarea id="MoTa" name="MoTa" rows="4"><?= htmlspecialchars($doctor['MoTa']); ?></textarea>

        <label for="HinhAnhBacSi">Hình ảnh bác sĩ:</label>
        <input type="file" id="HinhAnhBacSi" name="HinhAnhBacSi" accept="image/*">

        <input type="submit" value="Cập nhật thông tin">
    </form>
</div>
</body>
</html>
