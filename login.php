<?php
require('config/config.php');

if (isset($_POST['submit']) && (isset($_POST['h-captcha-response']) ? json_decode(file_get_contents('https://hcaptcha.com/siteverify?secret=' . $hCaptchaSecretKey . '&response=' . $_POST['h-captcha-response']))->success : false)) {
    if (isset($_POST['username'])) {

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

        if (strpos($response, '-') === 0 || strpos($response, '<') === 0) {
            die('Login failed!');
        }

        session_start();
        $_SESSION['username'] = $userName;
        header('Location: chat.php');
        exit;
    } elseif (isset($_POST['anon'])) {
        (strlen($message) > 14) ? (die()) : null;
        #die("Anonymous login disabled due to security issues, try again later.");
        $userName = "[ANON {ID: " . substr(md5(mt_rand()), 0, 5) . "}] ".$_POST['anon'];
        session_start();
        $_SESSION['username'] = $userName;
        header('Location: chat.php');
        exit;
    } else {
        die('Invalid request');
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login to GDChat</title>
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
    <script src="https://hcaptcha.com/1/api.js" async defer></script>
</head>
<body>
    <h1>Login to GDChat (using Geometry Dash Account)</h1>
    <p>Your information is not stored and will only be used to verify its you</p>
    <form action="" method="POST" id="loginForm">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" name="password" required><br><br>
        <div class="h-captcha" data-sitekey="<?php echo $hCaptchaSiteKey; ?>"></div>
        <input type="submit" name="submit" value="Login">
    </form>
    <h3>or chat anonymously (not recommended)</h3>
    <form action="" method="POST">
        <label for="anon">Username:</label>
        <input type="text" name="anon" maxlength="14" required><br><br>
        <div class="h-captcha" data-sitekey="<?php echo $hCaptchaSiteKey; ?>"></div>
        <input type="submit" name="submit" value="Chat">
    </form>
</body>
</html>