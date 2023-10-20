<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username'];
    $message = $_POST['message'];

    if ($username && $message) {
        $chatLine = "<strong><b>$username</b></strong>: $message<br>";
        file_put_contents('chat.txt', $chatLine, FILE_APPEND);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chatroom</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(white, #ddd);
        }

        input[type="text"] {
            width: 100%;
            padding: 3px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <h1>GDChat</h1>
    <iframe src="frame.php" style="width: 600px; height: 400px; border: 1px solid #000;"></iframe>
    <br>
    <form method="post">
        <input type="text" name="message" placeholder="Type something and press &#8629;" maxlength="120">
    </form>
</body>
</html>