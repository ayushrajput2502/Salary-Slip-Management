<?php
// Logger.php

class Logger {
    private $logFile;

    public function __construct($logFile) {
        $this->logFile = $logFile;
    }

    public function log($message, $level = 'info') {
        $logEntry = date('Y-m-d H:i:s') . " [$level]: $message" . PHP_EOL;
        file_put_contents($this->logFile, $logEntry, FILE_APPEND);
    }
}


?>