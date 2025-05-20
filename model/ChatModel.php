<?php
// model/ChatModel.php
require_once "Database.php";

class ChatModel {
    private $conn;
    
    public function __construct(){
        $this->conn = Database::getInstance()->getConnection();
    }
    
    public function saveMessage($sender_id, $receiver_id, $message) {
        $sql = "INSERT INTO chat_messages (sender_id, receiver_id, message) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }
        $stmt->bind_param("iis", $sender_id, $receiver_id, $message);
        $result = $stmt->execute();
        if (!$result) {
            error_log("Lỗi lưu tin nhắn: " . $stmt->error);
        }
        $stmt->close();
        return $result;
    }
    
    public function getMessages($user1, $user2) {
        $sql = "SELECT * FROM chat_messages 
                WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
                ORDER BY created_at ASC";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }
        $stmt->bind_param("iiii", $user1, $user2, $user2, $user1);
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = [];
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
        $stmt->close();
        return $messages;
    }
}
?>
