<?php
// controller/ChatController.php
session_start();
require_once __DIR__ . "/../model/ChatModel.php";

$chatModel = new ChatModel();
$action = isset($_POST['action']) ? $_POST['action'] : "";
header("Content-Type: application/json");

if ($action === "sendMessage") {
    $sender_id = intval($_POST['sender_id']);
    $receiver_id = intval($_POST['receiver_id']);
    $message = trim($_POST['message']);

    // Kiểm tra dữ liệu đầu vào
    if (!$sender_id || !$receiver_id || $message === "") {
        echo json_encode(["success" => false, "message" => "Thiếu thông tin cần thiết"]);
        exit();
    }

    // Lưu tin nhắn vào cơ sở dữ liệu
    if ($chatModel->saveMessage($sender_id, $receiver_id, $message)) {
        echo json_encode(["success" => true, "message" => "Tin nhắn đã được gửi"]);
    } else {
        echo json_encode(["success" => false, "message" => "Gửi tin nhắn thất bại"]);
    }
    exit();
} elseif ($action === "getMessages") {
    $user1 = intval($_POST['user1']);
    $user2 = intval($_POST['user2']);

    // Kiểm tra dữ liệu đầu vào
    if (!$user1 || !$user2) {
        echo json_encode(["success" => false, "messages" => []]);
        exit();
    }

    // Lấy tin nhắn từ cơ sở dữ liệu
    $messages = $chatModel->getMessages($user1, $user2);
    echo json_encode(["success" => true, "messages" => $messages]);
    exit();
} else {
    echo json_encode(["success" => false, "message" => "Hành động không hợp lệ."]);
    exit();
}
?>
