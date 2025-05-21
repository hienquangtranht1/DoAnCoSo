<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Questions.php';

if (!defined('BASE_URL')) {
    define('BASE_URL', '/BOOKING/');
}

class QuestionsController {
    private $questionsModel;
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
        $this->questionsModel = new Questions($conn);
    }
    
    // Lấy MaBacSi từ session
    private function getMaBacSi() {
        if (!isset($_SESSION["MaNguoiDung"])) {
            header("Location: " . BASE_URL . "Auth?action=login");
            exit();
        }
        
        $sql = "SELECT MaBacSi FROM bacsi WHERE MaNguoiDung = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $_SESSION["MaNguoiDung"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $doctor = $result->fetch_assoc();
        
        if (!$doctor) {
            die("Không tìm thấy thông tin bác sĩ");
        }
        
        return $doctor["MaBacSi"];
    }
    
    // Hiển thị danh sách câu hỏi
    public function index() {
        $maBacSi = $this->getMaBacSi();
        $questions = $this->questionsModel->getQuestionsByDoctor($maBacSi);
        require_once __DIR__ . '/../views/questions_view.php';
    }
    
    // Xử lý trả lời câu hỏi
    public function answer() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $maBacSi = $this->getMaBacSi();
            $questionId = $_POST["question_id"];
            $answer = $_POST["answer"];
            
            // Kiểm tra câu hỏi có thuộc về bác sĩ không
            $question = $this->questionsModel->getQuestionById($questionId, $maBacSi);
            if (!$question) {
                die("Không tìm thấy câu hỏi");
            }
            
            if ($this->questionsModel->answerQuestion($questionId, $answer)) {
                header("Location: " . BASE_URL . "Questions?success=answer");
                exit();
            } else {
                header("Location: " . BASE_URL . "Questions?error=answer");
                exit();
            }
        }
    }
    
    // Xử lý xóa câu hỏi
    public function delete() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $maBacSi = $this->getMaBacSi();
            $questionId = $_POST["question_id"];
            
            if ($this->questionsModel->deleteQuestion($questionId, $maBacSi)) {
                header("Location: " . BASE_URL . "Questions?success=delete");
                exit();
            } else {
                header("Location: " . BASE_URL . "Questions?error=delete");
                exit();
            }
        }
    }
}

$questionsController = new QuestionsController($conn);
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

switch ($action) {
    case 'answer':
        $questionsController->answer();
        break;
    case 'delete':
        $questionsController->delete();
        break;
    default:
        $questionsController->index();
        break;
}
?>