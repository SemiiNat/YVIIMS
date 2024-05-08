<?php
ini_set('log_errors', 1);
ini_set('error_log', 'path_to_error_log');
error_reporting(E_ALL);

ob_start();
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session.
session_destroy();

// Redirect to login page
header("Location: ../public/login.html");
exit;
?>
