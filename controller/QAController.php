<?php
// filepath: c:\xampp\htdocs\booking\controller\QAController.php

require_once '../model/QAModel.php';

$qaModel = new QAModel();
$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');
switch($action) {
    case 'ask':
        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            exit;
        }

        $userId = $_SESSION['user']['MaNguoiDung'];
        $doctorId = intval($_POST['MaBacSi']);
        $title = $_POST['title'];
        $content = $_POST['content'];

        if (!$doctorId) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng chọn bác sĩ']);
            exit;
        }

        $result = $qaModel->addQuestion($userId, $doctorId, $title, $content);
        echo json_encode(['success' => $result]);
        break;

    case 'getQuestions':
        $questions = $qaModel->getQuestions();
        $html = '<div class="qa-list">';
        if (empty($questions)) {
            $html .= '<div class="alert alert-info">Chưa có câu hỏi nào được trả lời</div>';
        } else {
            foreach ($questions as $question) {
                $html .= '
                <div class="card question-card">
                    <div class="question-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">'.htmlspecialchars($question['title']).'</h5>
                            <span class="status-badge badge badge-success">Đã trả lời</span>
                        </div>
                        <div class="meta-info mt-2">
                            <i class="fas fa-user-circle"></i> '.htmlspecialchars($question['user_name'] ?? 'Ẩn danh').'
                            <i class="fas fa-user-md ml-3"></i> BS. '.htmlspecialchars($question['doctor_name'] ?? '').'
                            <i class="far fa-clock ml-3"></i> '.(isset($question['created_at']) ? date('d/m/Y', strtotime($question['created_at'])) : '').'
                        </div>
                    </div>
                    <div class="question-content">
                        <p class="card-text mt-3">'.nl2br(htmlspecialchars($question['content'])).'</p>
                        <div class="answer-section">
                            <h6 class="text-primary"><i class="fas fa-comment-medical"></i> Trả lời từ bác sĩ:</h6>
                            <p class="mb-0">'.nl2br(htmlspecialchars($question['answer'])).'</p>
                        </div>
                    </div>
                </div>';
            }
        }
        $html .= '</div>';
        echo $html;
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}