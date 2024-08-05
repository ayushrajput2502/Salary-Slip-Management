<?php
include '../config/config.php';
require_once 'logger.php';
$logger = new Logger('log.txt');

session_start();

if($_SESSION['type']=='accountant'){
    $logger->log($_SESSION['name'] . ' logged out');
}

session_unset();
session_destroy();

header('location:index.php');
?>