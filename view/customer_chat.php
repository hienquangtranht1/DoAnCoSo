<?php
// customer_chat.php
session_start();
// Ở ví dụ này, giả sử khách hàng có id = 8 (bạn có hệ thống đăng nhập khách hàng thực tế)
$customer_id = 8;
$cskh_id = 1;  // CSKH hiện tại có id = 1
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chat với CSKH</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .chat-box { height: 300px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2>Chat với CSKH</h2>
    <div class="chat-box" id="chatBox">
        <!-- Lịch sử tin nhắn sẽ hiển thị ở đây -->
    </div>
    <div class="mt-2">
        <input type="text" id="chatInput" class="form-control" placeholder="Nhập tin nhắn...">
        <button class="btn btn-success mt-2" onclick="sendMessage()">Gửi</button>
    </div>
</div>
<script>
const customer_id = <?php echo $customer_id; ?>;
const cskh_id = <?php echo $cskh_id; ?>;

function loadChat() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "controller/ChatController.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function(){
        if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200){
            var res = JSON.parse(xhr.responseText);
            if(res.success){
                var chatBox = document.getElementById("chatBox");
                chatBox.innerHTML = "";
                res.messages.forEach(function(msg){
                    if (parseInt(msg.sender_id) === customer_id) {
                        chatBox.innerHTML += "<div style='text-align:right;'><strong>Bạn:</strong> " + msg.message + "</div>";
                    } else {
                        chatBox.innerHTML += "<div style='text-align:left;'><strong>CSKH:</strong> " + msg.message + "</div>";
                    }
                });
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        }
    };
    xhr.send("action=getMessages&user1=" + customer_id + "&user2=" + cskh_id);
}

function sendMessage() {
    var message = document.getElementById("chatInput").value.trim();
    if (message === "") return;
    
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "controller/ChatController.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function(){
        if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200){
            var res = JSON.parse(xhr.responseText);
            if(res.success) {
                document.getElementById("chatInput").value = "";
                loadChat();
            } else {
                alert("Gửi tin thất bại!");
            }
        }
    };
    xhr.send("action=sendMessage&sender_id=" + customer_id + "&receiver_id=" + cskh_id + "&message=" + encodeURIComponent(message));
}

setInterval(loadChat, 5000);
loadChat();
</script>
</body>
</html>
