<?php
// dashboard_cskh.php
session_start();
if (!isset($_SESSION['cskh'])) {
    header("Location: login_cskh.php");
    exit();
}
$cskh = $_SESSION['cskh'];
$customer_id = 8; // Ví dụ: CSKH chọn chat với khách hàng có id = 8
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Dashboard CSKH - Chat</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .chat-box { height: 300px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2>Chào CSKH: <?php echo htmlspecialchars($cskh['full_name']); ?> (ID: <?php echo $cskh['id']; ?>)</h2>
    <h4>Chat với khách hàng (ID: <?php echo $customer_id; ?>)</h4>
    <div class="chat-box" id="chatBox">
        <!-- Lịch sử tin nhắn sẽ load ở đây -->
    </div>
    <div class="mt-2">
        <input type="text" id="chatInput" class="form-control" placeholder="Nhập tin nhắn...">
        <button class="btn btn-success mt-2" onclick="sendChatMessage()">Gửi</button>
    </div>
</div>

<script>
const cs_kh_id = <?php echo $cskh['id']; ?>; // 1
const customer_id = <?php echo $customer_id; ?>; // 8

function loadConversation() {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "controller/ChatController.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            const res = JSON.parse(xhr.responseText);
            if (res.success) {
                const chatBox = document.getElementById("chatBox");
                chatBox.innerHTML = "";
                res.messages.forEach(function (msg) {
                    if (parseInt(msg.sender_id) === cs_kh_id) {
                        chatBox.innerHTML += `<div style='text-align:right;'><strong>CSKH:</strong> ${msg.message}</div>`;
                    } else {
                        chatBox.innerHTML += `<div style='text-align:left;'><strong>Khách:</strong> ${msg.message}</div>`;
                    }
                });
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        }
    };
    xhr.send(`action=getMessages&user1=${cs_kh_id}&user2=${customer_id}`);
}

function sendChatMessage() {
    var message = document.getElementById("chatInput").value.trim();
    if (message === "") return;
    
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "controller/ChatController.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function(){
        if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200){
            var res = JSON.parse(xhr.responseText);
            if (res.success) {
                document.getElementById("chatInput").value = "";
                loadConversation();
            } else {
                alert("Gửi tin thất bại!");
            }
        }
    };
    xhr.send("action=sendMessage&sender_id=" + cs_kh_id + "&receiver_id=" + customer_id + "&message=" + encodeURIComponent(message));
}

setInterval(loadConversation, 5000);
loadConversation();
</script>
</body>
</html>
