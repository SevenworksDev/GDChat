<?php
session_start();
include '../config/config.php';
if (!isset($_SESSION['username'])) { die(); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username'];
    $targetUsername = $_POST['target-username'];
    $action = $_POST['action'];
	
    if ($targetUsername === ""*) { die("* not allowed"); }
    if (in_array($username, $admins)) {
        if ($action === 'ban') {
            file_put_contents('../config/banned.txt', $targetUsername . "\n", FILE_APPEND);
        } elseif ($action === 'unban') {
            $banned = file('../config/banned.txt', FILE_IGNORE_NEW_LINES);
            $banned = array_diff($banned, [$targetUsername]);
            file_put_contents('../config/banned.txt', implode("\n", $banned));
        } elseif ($action === 'mute') {
            file_put_contents('../config/muted.txt', $targetUsername . "\n", FILE_APPEND);
        } elseif ($action === 'unmute') {
            $muted = file('../config/muted.txt', FILE_IGNORE_NEW_LINES);
            $muted = array_diff($muted, [$targetUsername]);
            file_put_contents('../config/muted.txt', implode("\n", $muted));
        }

        header('Location: ../chat.php');
        exit;
    }
}
?>