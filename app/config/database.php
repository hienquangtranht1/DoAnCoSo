<?php
$servername = "localhost";
$username   = "root";
$password   = ""; // Thay đổi nếu MySQL của bạn có mật khẩu
$dbname     = "datlichkhambenh";

// Tạo kết nối MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>
