<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="style.css" rel="stylesheet">
</head>
<body>
<div id="chatbot" class="chatbot">
        <div class="toggle-button closed" id="ia-button">Chatbot</div>
        <div class="chat closed">
            <div class="history">
                <p class="welcome-msg">Hey buddy, how can I help you ?</p>
                <?php
                if(isset($_SESSION['chat_history'])){
                    foreach($_SESSION['chat_history'] as $msg){
                        echo '<p class="' . $msg->sender . '">' . $msg->message . '</p>';
                    } 
                }
                ?>
            </div>
            <div class="user-input collapsed">
                <textarea name="" id=""></textarea>
                <button class="erase-history"><img src="https://b3data.com/wp-content/uploads/2025/02/refresh-page-option.png"></button>
                <button class="send-query"><img src="https://b3data.com/wp-content/uploads/2025/02/send.png"></button>
            </div>
            <div class="loader hidden">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
        </div>
    </div>
    
<script src="main.js"></script>
</body>
</html>