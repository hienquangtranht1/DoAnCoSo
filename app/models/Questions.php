<?php
class Questions {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function getQuestionsByDoctor($maBacSi) {
        $sql = "SELECT q.*, bn.HoTen as TenNguoiHoi, bn.SoBaoHiem, bn.MaNguoiDung
                FROM questions q 
                JOIN benhnhan bn ON q.user_id = bn.MaNguoiDung
                WHERE q.doctor_id = ? 
                ORDER BY q.status = 'pending' DESC, q.created_at DESC";
                
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            die("Prepare failed: " . $this->conn->error);
        }
        
        if (!$stmt->bind_param("i", $maBacSi)) {
            die("Binding parameters failed: " . $stmt->error);
        }
        
        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function answerQuestion($questionId, $answer) {
        $sql = "UPDATE questions 
                SET answer = ?, 
                    status = 'answered',
                    answered_at = CURRENT_TIMESTAMP 
                WHERE id = ?";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $answer, $questionId);
        return $stmt->execute();
    }
    
    public function deleteQuestion($questionId, $maBacSi) {
        $sql = "DELETE FROM questions 
                WHERE id = ? AND doctor_id = ?";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $questionId, $maBacSi);
        return $stmt->execute();
    }
    
    public function getQuestionById($questionId, $maBacSi) {
        $sql = "SELECT q.*, bn.HoTen as TenNguoiHoi, bn.SoBaoHiem 
                FROM questions q
                JOIN benhnhan bn ON q.user_id = bn.MaNguoiDung 
                WHERE q.id = ? AND q.doctor_id = ?";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $questionId, $maBacSi);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>