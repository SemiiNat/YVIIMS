<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'config.php';  // Make sure your database connection file path is correct

$sql = "SELECT p.name, p.description, c.name as category_name FROM product p JOIN category c ON p.category_id = c.category_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<ul>";
    while($row = $result->fetch_assoc()) {
        echo "<li>" . htmlspecialchars($row['name']) . " - " . htmlspecialchars($row['description']) . " (Category: " . htmlspecialchars($row['category_name']) . ")</li>";
    }
    echo "</ul>";
} else {
    echo "No products found.";
}
$conn->close();
?>
