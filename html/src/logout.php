<?php
ini_set('log_errors', 1);
ini_set('error_log', 'path_to_error_log');
error_reporting(E_ALL);

ob_start();
session_start();

$_SESSION = array();

session_destroy();

header("Location: ../public/login.html");
exit;
?>
