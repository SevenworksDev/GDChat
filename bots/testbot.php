<?php
class TestBot {
    private $chatFile = 'chat.txt';
    private $botName = 'TestBot';

    public function read() {
        $lines = file($this->chatFile, FILE_IGNORE_NEW_LINES);

        if (!$lines) {
            return;
        }

        $lastMessage = end($lines);

        if (strpos($lastMessage, '!hello') !== false) {
            $this->reply('hello');
        } elseif (strpos($lastMessage, '!bye') !== false) {
            $this->reply('bye');
        }
    }

    private function reply($type) {
        $newMessage = '<span style="color: blue;">[BOT]</span> ' . $this->botName . ': ';

        switch ($type) {
            case 'hello':
                $newMessage .= 'hello';
                break;

            case 'bye':
                $newMessage .= 'bye';
                break;

            default:
                return;
        }

        file_put_contents($this->chatFile, $newMessage . "<br>\n", FILE_APPEND);
    }
}
?>