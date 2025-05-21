<?php
require_once 'Database.php';

class QAModel {
    private $db;

    public function __construct() {
        require_once(__DIR__ . '../Database.php');
        $this->db = Database::getInstance()->getConnection();
    }

    public function addQuestion($userId, $doctorId, $title, $content) {
        try {
            $sql = "INSERT INTO questions (user_id, doctor_id, title, content, status) 
                    VALUES (?, ?, ?, ?, 'pending')";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("iiss", $userId, $doctorId, $title, $content);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error adding question: " . $e->getMessage());
            return false;
        }
    }

public function getQuestions() {
    try {
        $sql = "SELECT 
                    q.id,
                    q.title,
                    q.content,
                    q.answer,
                    q.status,
                    q.created_at,
                    q.answered_at, 
                    n.HoTen AS user_name,
                    b.HoTen AS doctor_name
                FROM questions q
                LEFT JOIN nguoidung u ON q.user_id = u.MaNguoiDung
                LEFT JOIN benhnhan n ON n.MaBenhNhan = u.MaNguoiDung
                LEFT JOIN bacsi b ON q.doctor_id = b.MaBacSi
                WHERE q.status = 'answered'
                ORDER BY q.created_at DESC";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            die("Lỗi SQL: " . $this->db->error);
        }               

        $stmt->execute();
        $result = $stmt->get_result();
        
        $questions = [];
        while ($row = $result->fetch_assoc()) {
            $questions[] = [
                'id' => $row['id'],
                'title' => $row['title'],
                'content' => $row['content'],
                'answer' => $row['answer'],
                'status' => $row['status'],
                'user_name' => $row['user_name'] ?? 'Ẩn danh',
                'doctor_name' => $row['doctor_name'],
                'created_at' => $row['created_at'],
                'answered_at' => $row['answered_at']
            ];
        }
        return $questions;
    } catch (Exception $e) {
        error_log("Database error: " . $e->getMessage());
        throw $e;
    }
}

}