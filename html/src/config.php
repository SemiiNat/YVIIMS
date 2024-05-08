<?php


// Create a connection using MySQLi with error handling
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optionally, you can set the charset to ensure UTF-8 is used for communication with the database
$conn->set_charset("utf8mb4_general_ci");
?>
