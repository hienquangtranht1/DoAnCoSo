<?php
// Xử lý yêu cầu POST: gửi tin nhắn của người dùng đến API Google Gemini
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Cấu hình API Key và endpoint (sử dụng gemini-2.0-flash)
    $apiKey = "AIzaSyC1uBICLSzhzjSMH8r1iAC2hf_XZgGpbo0";
    $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . $apiKey;
    
    // Nhận tin nhắn từ phía người dùng
    $userMessage = isset($_POST["message"]) ? trim($_POST["message"]) : "";
    if (empty($userMessage)) {
        echo json_encode(["reply" => "Vui lòng nhập tin nhắn."]);
        exit();
    }
    
    // Tạo payload JSON theo cấu trúc yêu cầu của API: sử dụng key "contents" với mảng "parts"
    $payload = [
        "contents" => [
            [
                "parts" => [
                    ["text" => $userMessage]
                ]
            ]
        ]
    ];
    $jsonData = json_encode($payload);
    
    // Khởi tạo cURL và gửi yêu cầu POST đến API Gemini
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json"
    ]);
    
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        echo json_encode(["reply" => "Lỗi kết nối API: " . $error]);
        exit();
    }
    curl_close($ch);
    
    // Ghi log phản hồi từ API (để debug)
    file_put_contents("api_response.log", $response);
    
    // Giải mã json phản hồi
    $result = json_decode($response, true);
    $botReply = "";
    if (isset($result["candidates"]) && is_array($result["candidates"]) && count($result["candidates"]) > 0) {
        if (isset($result["candidates"][0]["output"]) && $result["candidates"][0]["output"] !== "") {
            $botReply = $result["candidates"][0]["output"];
        } else {
            $botReply = json_encode($result["candidates"][0], JSON_PRETTY_PRINT);
        }
    } else {
        $botReply = json_encode($result, JSON_PRETTY_PRINT);
    }
    
    // Trả về kết quả dạng JSON cho phần frontend
    echo json_encode(["reply" => $botReply]);
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chatbot với Google Gemini</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Font cho giao diện -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600&display=swap" rel="stylesheet">
    <style>
        /* Reset CSS cơ bản */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        /* Định dạng nền và font */
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #74ABE2, #5563DE);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        /* Tạo khung chat trung tâm với shadow và border radius */
        .chat-container {
            width: 100%;
            max-width: 600px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }
        /* Phần header của chatbot */
        .chat-header {
            background: #5563DE;
            color: #fff;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: 600;
        }
        /* Hộp chứa các tin nhắn chat */
        .chat-box {
            height: 300px;
            overflow-y: auto;
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        /* Định dạng tin nhắn trong chat */
        .chat-message {
            margin-bottom: 15px;
        }
        .chat-message p {
            padding: 10px 15px;
            border-radius: 8px;
            display: inline-block;
            max-width: 80%;
        }
        /* Tin nhắn của người dùng có nền xanh nhạt */
        .user p {
            background: #74ABE2;
            color: #fff;
        }
        /* Tin nhắn của bot có nền xám nhẹ */
        .bot p {
            background: #eee;
            color: #333;
        }
        /* Phần input và nút gửi tin nhắn */
        .chat-input {
            display: flex;
            padding: 15px;
            background: #f9f9f9;
        }
        .chat-input input {
            flex: 1;
            border: none;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            margin-right: 10px;
        }
        .chat-input button {
            border: none;
            background: #5563DE;
            color: #fff;
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Container chứa giao diện chatbot -->
    <div class="chat-container">
        <div class="chat-header">
            Chatbot với Google Gemini
        </div>
        <div class="chat-box" id="chatBox">
            <!-- Lịch sử cuộc hội thoại sẽ hiển thị ở đây -->
        </div>
        <div class="chat-input">
            <input type="text" id="userInput" placeholder="Nhập tin nhắn của bạn..." autocomplete="off">
            <button onclick="sendMessage()">Gửi</button>
        </div>
    </div>
    
    <script>
        // Hàm gửi tin nhắn bằng Fetch API và hiển thị kết quả
        function sendMessage() {
            var inputField = document.getElementById("userInput");
            var message = inputField.value;
            if (message.trim() === "") return;
    
            var chatBox = document.getElementById("chatBox");
            
            // Tạo và chèn tin nhắn của người dùng vào khung chat
            var userMessageDiv = document.createElement("div");
            userMessageDiv.className = "chat-message user";
            var pUser = document.createElement("p");
            pUser.innerHTML = "<strong>Bạn:</strong> " + message;
            userMessageDiv.appendChild(pUser);
            chatBox.appendChild(userMessageDiv);
            inputField.value = "";
            chatBox.scrollTop = chatBox.scrollHeight;
    
            // Gửi yêu cầu POST tới chính file PHP này
            fetch(window.location.href, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "message=" + encodeURIComponent(message)
            })
            .then(response => response.json())
            .then(data => {
                var botMessageDiv = document.createElement("div");
                botMessageDiv.className = "chat-message bot";
                var pBot = document.createElement("p");
                pBot.innerHTML = "<strong>Bot:</strong> " + data.reply;
                botMessageDiv.appendChild(pBot);
                chatBox.appendChild(botMessageDiv);
                chatBox.scrollTop = chatBox.scrollHeight;
            })
            .catch(error => console.error("Lỗi:", error));
        }
    </script>
</body>
</html>
