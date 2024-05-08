<?php
// Database connection settings
$servername = "mysql-server"; // Changed from localhost to the service name
$username = "root";
$password = "secret"; // Ensure this matches the environment password set in docker-compose.yaml
$dbname = "ims_yvi";

// Create a connection using MySQLi with error handling
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optionally, you can set the charset to ensure UTF-8 is used for communication with the database
$conn->set_charset("utf8mb4_general_ci");
?>
