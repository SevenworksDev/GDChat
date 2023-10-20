<?php
if (isset($_POST['submit'])) {
    $userName = $_POST['username'];
    $password = $_POST['password'];

    $data = array(
        "udid" => "605BE9FD-300E-49EA-A45C-B272EE64D3E0",
        "userName" => $userName,
        "password" => $password,
        "secret" => "Wmfv3899gc9"
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://www.boomlings.com/database/accounts/loginGJAccount.php');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, '');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

    $response = curl_exec($ch);
    $request_info = curl_getinfo($ch);
    $request_headers = curl_getinfo($ch, CURLINFO_HEADER_OUT);

    if (strpos($response, '-') === 0 || strpos($response, '<') === 0) {
        die('Login failed!');
    }

    session_start();
    $_SESSION['username'] = $userName;
    header('Location: chat.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
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
        a {
            text-color: black;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <h1>Login to Geometry Dash</h1>
    <form action="" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" name="password" required><br><br>
        <input type="submit" name="submit" value="Login">
    </form>
    <p>I should implement other verification methods in the future, Info is not being logged.</p>
    <a href="https://github.com/SevenworksDev/GDChat">GitHub</a>
</body>
</html>