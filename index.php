<?php
if (isset($_SESSION['username'])) {
  if (in_array($_SESSION['username'], file('config/banned.txt', FILE_IGNORE_NEW_LINES)) || array_reduce(file('config/banned.txt', FILE_IGNORE_NEW_LINES), function($carry, $item) { return $carry || fnmatch($item, $_SESSION['username']); }, false)) die('<h1>Banned for abuse, E-mail mail@sevenworks.eu.org if this is a mistake</h1> <audio controls><source src="assets/banned.mp3" type="audio/mpeg"></audio>');
}

if (in_array($_SERVER['HTTP_CF_CONNECTING_IP'], file('config/banned_ips.txt', FILE_IGNORE_NEW_LINES))) die("");
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
            background: linear-gradient(white, #ddd);
        }
    </style>
</head>
<body>
    <h1>GDChat (v0.1.2 [beta] - 11/12/2023)</h1>
    <h2>Chat with Geometry Dash players around the world!</h2>

    <button style="width: 80px; height: 40px; font-size: 25px;" onclick="window.location.href = 'chat.php'">Login</button><br>
    <button style="width: 90px; height: 40px; font-size: 25px;" onclick="window.location.href = 'https://github.com/SevenworksDev/GDChat'">GitHub</button><br>
    <img src="assets/preview.png">
</body>
</html>