<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emojis</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1, h2 {
            text-align: center;
        }
        .emoji-container {
            display: grid;
            grid-template-columns: repeat(16, 1fr);
            gap: 10px;
            max-width: 800px;
            margin: 0 auto;
        }
        .emoji-container img {
            width: 100%;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Emojis (Click an emoji to copy to clipboard)</h1>
    <h2>Usage: :emoji:</h2>
    <hr>
    <div class="emoji-container">
        <?php
        $emojiFolder = '../emojis/';
        $emojis = [];
        $allowedExtensions = ['webp', 'gif'];

        foreach ($allowedExtensions as $extension) {
            foreach (glob($emojiFolder . '*.' . $extension) as $file) {
                $emojiName = pathinfo($file, PATHINFO_FILENAME);
                $emojis[$emojiName] = $file;
            }
        }

        foreach ($emojis as $name => $url) {
            echo '<img src="'.$url.'" alt="'.$name.'" onclick="copyemoji(\''.$name.'\')">';
        }
        ?>
    </div>

    <script>
        function copyemoji(text) {
            var textarea = document.createElement("textarea");
            textarea.value = ':' + text + ':';
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
        }
    </script>
</body>
</html>