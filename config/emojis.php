<?php
$emojiFolder = './emojis/';
$emojis = [];
$allowedExtensions = ['webp', 'gif'];

foreach ($allowedExtensions as $extension) {
   foreach (glob($emojiFolder . '*.' . $extension) as $file) {
       $emojiName = pathinfo($file, PATHINFO_FILENAME);
       $emojis[$emojiName] = $file;
   }
}