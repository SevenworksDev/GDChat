<?php
session_start();
include 'config/config.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

require_once('bots/testbot.php');
$TestBot = new TestBot();
$TestBot->read();

if (in_array($_SESSION['username'], file('config/banned.txt', FILE_IGNORE_NEW_LINES)) || array_reduce(file('config/banned.txt', FILE_IGNORE_NEW_LINES), function($carry, $item) { return $carry || fnmatch($item, $_SESSION['username']); }, false)) die('<h1>Banned for abuse, E-mail mail@sevenworks.eu.org if this is a mistake</h1> <audio controls><source src="assets/banned.mp3" type="audio/mpeg"></audio>');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (in_array($_SESSION['username'], file('config/muted.txt', FILE_IGNORE_NEW_LINES))) { die('Muted by admin due to bad behavior'); }

    $username = $_SESSION['username'];
    $message = $_POST['message'];
    (strlen($message) > 500) ? (file_put_contents('config/banned.txt', $_SESSION['username']) && die()) : null;
    if(isset($_SESSION['lastmessage']) && $_SESSION['lastmessage'] === $message && (time() - $_SESSION['lastmessage_time']) <= 7) { if (++$_SESSION['message_count'] >= 7) { file_put_contents('config/banned.txt', $_SESSION['username'].PHP_EOL , FILE_APPEND); } die("Please wait before sending another message!"); } else { $_SESSION['lastmessage'] = $message; $_SESSION['lastmessage_time'] = time(); $_SESSION['message_count'] = 1; if (isset($_SESSION['post_block_time']) && (time() - $_SESSION['post_block_time']) <= 1) die(); }


    if ($username && $message) {
        require 'config/emojis.php';

        if (preg_match($regexfilter, $message)) {
            $message = preg_replace_callback('/:([\w]+):/', function($match) use ($emojis) {
              $emojiName = $match[1];
              if (array_key_exists($emojiName, $emojis)) {
                  $emojiURL = $emojis[$emojiName];
                  return '<img width="16px" height="16px" src="' . $emojiURL . '" alt=":' . $emojiName . ':" class="emoji" />';
              } else {
                   return $match[0];
              }
        }, $message);

            if (in_array($username, $admins)) {
                $chatuser = '<span style="color: red;">[ADMIN]</span> ' . $username;
            } else {
                $chatuser = $username;
            }

            $message = $chatuser . ': ' . $message . '<br>';
            file_put_contents('chat.txt', $message . "\n", FILE_APPEND);
            header('Location: chat.php');
            exit;
        } else {
            echo "Only commonly used characters and :emoji: are allowed.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>GDChat</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(white, #aaa);
            zoom: 100%;
        }

        input {
            width: 100%;
            padding: 3px;
            font-size: 16px;
            background-color: #ccc;
        }

        button, select {
            background-color: #ccc;
        }

        #chatbox {
            width: 90%;
            height: 600px;
            overflow-y: scroll;
            border: 1px solid #000;
        }

        #chat {
            padding: 10px;
        }
    </style>
</head>
<body>
    <h1>GDChat</h1>
    <div style="background-color: #ddd; width: 100%; text-align: center; margin-top: -20px; margin-bottom: 10px;"><?php echo $banner; ?></div>
    <div style="background-color: red; width: 100%; font-size: 20px; color: white; text-align: center; margin-bottom: 10px;">DON'T TRUST ANONYMOUS USERS, THEY AREN'T VERIFIED WHICH CAN LEAD YOU TO GET SCAMMED! MAKE SURE TO PROVE THEY ARE THE USER YOU ARE TALKING TO!</div>
    <div id="chatbox">
        <div id="chat"></div>
    </div>
    <br>
    <form id="messageform" method="post">
        <input type="text" name="message" id="message" placeholder="Type something and press &#8629;" maxlength="120">
    </form>
    <button id="refreshchat">Refresh</button>
    <button onclick='window.open("actions/emojis.php", "Emojis", "width=1200,height=800");'>Emojis</button>
    <!--
    <button>Themes</button>
    <button>Private Chat</button>
    <button>Bots</button>
    -->
    <?php if (isset($_SESSION['username']) && in_array($_SESSION['username'], $admins)): ?>
<div>
    <form method="post" action="actions/moderate.php">
        <label for="target-username">Target Username (wildcards supported):</label>
        <input type="text" name="target-username" id="target-username" required>
        <select name="action" required>
            <option value="ban">Ban</option>
            <option value="unban">Unban</option>
            <option value="mute">Mute</option>
            <option value="unmute">Unmute</option>
        </select>
        <input type="submit" value="Submit">
    </form>
</div>
<?php endif; ?>
    <script>
        const chatbox = document.getElementById("chatbox");
        const chat = document.getElementById("chat");
        const messageform = document.getElementById("messageform");
        const refreshchat = document.getElementById("refreshchat");

        function updatechat(content) {
            chat.innerHTML = content;
            chatbox.scrollTop = chatbox.scrollHeight;
        }

        window.onload = function() {
            chatbox.scrollTop = chatbox.scrollHeight;
        };

        refreshchat.addEventListener("click", function() {
            get();
        });

        function get() {
            fetch("actions/frame.php")
                .then(response => response.text())
                .then(data => updatechat(data))
                .catch(error => console.error("Error: " + error));
        }

        get();
    </script>
</body>
</html>