<?php
session_start();
$loggedInUser = isset($_SESSION['user']) ? $_SESSION['user'] : null;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hỏi đáp với bác sĩ - Four Rock</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .qa-list {
            max-width: 900px;
            margin: 0 auto;
        }
        .question-card {
            margin-bottom: 25px;
            border: none;
            border-radius: 15px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .question-card:hover {
            transform: translateY(-3px);
        }
        .question-header {
            background: #f8f9fa;
            padding: 15px 20px;
            border-radius: 15px 15px 0 0;
            border-bottom: 1px solid #eee;
        }
        .question-content {
            padding: 20px;
        }
        .answer-section {
            background: #e8f4ff;
            margin: 15px -20px -20px;
            padding: 20px;
            border-radius: 0 0 15px 15px;
            border-top: 1px solid #c8e3ff;
        }
        .doctor-info {
            display: flex;
            align-items: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #c8e3ff;
        }
        .doctor-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            background: #007bff;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        .meta-info {
            color: #6c757d;
            font-size: 0.9rem;
            margin-top: 10px;
        }
        .status-badge {
            font-size: 0.8rem;
            padding: 5px 12px;
            border-radius: 20px;
        }
    </style>
</head>
<body>
    <!-- Header Navigation -->
    <?php include(__DIR__ . '/../view/header.php'); ?>

    <div class="container mt-5 pt-5">
        <h2 class="text-center mb-4">Hỏi đáp với bác sĩ</h2>
        
        <?php if ($loggedInUser): ?>
        <!-- Form đặt câu hỏi -->
        <div class="question-form mb-5">
            <h4>Đặt câu hỏi mới</h4>
            <form id="questionForm" method="POST" action="../controller/QAController.php">
                <input type="hidden" name="action" value="ask">
                <div class="form-group">
                    <label for="doctor">Chọn bác sĩ</label>
                    <select class="form-control" id="bacsi" name="MaBacSi" required>
                        <option value="">Chọn bác sĩ</option>
                        <?php
                        require_once(__DIR__ . '/../model/Database.php');
                        $db = Database::getInstance()->getConnection();
                        $sql = "SELECT MaBacSi, HoTen, MaKhoa FROM bacsi WHERE 1";
                        $result = $db->query($sql);
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['MaBacSi'] . "'>" . 
                                 "BS. " . $row['HoTen'] . " - " . $row['MaKhoa'] . 
                                 "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="title">Tiêu đề câu hỏi</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="content">Nội dung chi tiết</label>
                    <textarea class="form-control" id="content" name="content" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Gửi câu hỏi</button>
            </form>
        </div>
        <?php else: ?>
        <div class="alert alert-info text-center mb-5">
            <i class="fas fa-info-circle"></i> 
            Vui lòng <a href="login.php">đăng nhập</a> để đặt câu hỏi cho bác sĩ.
        </div>
        <?php endif; ?>

        <!-- Tiêu đề cho phần danh sách câu hỏi -->
        <div class="mb-4">
            <h4 class="text-center">Các câu hỏi đã được trả lời</h4>
            <p class="text-center text-muted">Danh sách những vấn đề đã được bác sĩ tư vấn và giải đáp</p>
        </div>

        <!-- Danh sách câu hỏi -->
        <div id="questionsList">
            <!-- Câu hỏi sẽ được load động qua AJAX -->
        </div>
    </div>

    <script>
    function loadQuestions() {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', '../controller/QAController.php?action=getQuestions', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('questionsList').innerHTML = xhr.responseText;
            }
        };
        xhr.onerror = function() {
            document.getElementById('questionsList').innerHTML = '<div class="alert alert-danger">Không thể kết nối với máy chủ</div>';
        };
        xhr.send();
    }

    // Load câu hỏi khi trang được tải
    document.addEventListener('DOMContentLoaded', loadQuestions);
    // Refresh định kỳ mỗi 30 giây
    setInterval(loadQuestions, 30000);
    </script>

    <!-- Footer -->
    <footer class="bg-light text-center text-lg-start mt-5">
        <div class="text-center p-3">
            © 2023 Four Rock. All rights reserved.
        </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>