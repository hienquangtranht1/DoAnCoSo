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
    <title>Quản lý câu hỏi</title>
    <style>
        .container {
            margin-left: 260px;
            padding: 20px;
            max-width: 1000px;
        }
        .question-card {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .question-meta {
            font-size: 14px;
            color: #666;
        }
        .question-content {
            margin: 15px 0;
        }
        .answer-form {
            margin-top: 15px;
        }
        .answer-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .answered {
            background: #e8f5e9;
        }
        .pending {
            background: #fff3e0;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }
        .status-answered {
            background: #4caf50;
            color: white;
        }
        .status-pending {
            background: #ff9800;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Quản lý câu hỏi từ bệnh nhân</h2>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php 
                    if ($_GET['success'] === 'answer') echo "Đã trả lời câu hỏi thành công!";
                    if ($_GET['success'] === 'delete') echo "Đã xóa câu hỏi thành công!";
                ?>
            </div>
        <?php endif; ?>
        
        <?php foreach ($questions as $question): ?>
            <div class="question-card <?= $question['status'] === 'answered' ? 'answered' : 'pending' ?>">
                <div class="question-header">
                    <div class="question-meta">
                        <strong>Người hỏi:</strong> <?= htmlspecialchars($question['TenNguoiHoi']) ?>
                        <br>
                        <strong>Thời gian:</strong> <?= date('d/m/Y H:i', strtotime($question['created_at'])) ?>
                    </div>
                    <span class="status-badge status-<?= $question['status'] ?>">
                        <?= $question['status'] === 'answered' ? 'Đã trả lời' : 'Chờ trả lời' ?>
                    </span>
                </div>
                
                <div class="question-content">
                    <h4><?= htmlspecialchars($question['title']) ?></h4>
                    <p><?= nl2br(htmlspecialchars($question['content'])) ?></p>
                </div>
                
                <?php if ($question['status'] === 'answered'): ?>
                    <div class="answer-content">
                        <strong>Câu trả lời:</strong>
                        <p><?= nl2br(htmlspecialchars($question['answer'])) ?></p>
                        <small>Trả lời lúc: <?= date('d/m/Y H:i', strtotime($question['answered_at'])) ?></small>
                    </div>
                <?php else: ?>
                    <form class="answer-form" method="POST" action="<?= BASE_URL ?>Questions?action=answer">
                        <input type="hidden" name="question_id" value="<?= $question['id'] ?>">
                        <textarea name="answer" rows="3" placeholder="Nhập câu trả lời của bạn..." required></textarea>
                        <button type="submit" class="btn btn-primary">Trả lời</button>
                    </form>
                <?php endif; ?>
                
                <form method="POST" action="<?= BASE_URL ?>Questions?action=delete" style="display: inline;">
                    <input type="hidden" name="question_id" value="<?= $question['id'] ?>">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa câu hỏi này?')">
                        Xóa câu hỏi
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
        
        <?php if (empty($questions)): ?>
            <p>Chưa có câu hỏi nào.</p>
        <?php endif; ?>
    </div>
</body>
</html>