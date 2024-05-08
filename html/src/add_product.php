<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Assuming session check and database connection are handled globally
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process the form
    include 'config.php';  // Make sure your database connection file path is correct
    $name = $_POST['name'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];

    $sql = "INSERT INTO product (name, description, category_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssi", $name, $description, $category_id);
        $stmt->execute();
        echo "Product added successfully!";
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
    $conn->close();
    exit;
}
?>

<div>
    <h2>Add New Product</h2>
    <form method="POST" onsubmit="submitForm(event)">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" required><br>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br>
        <label for="category_id">Category:</label>
        <select id="category_id" name="category_id" required>
            <!-- Dynamically loading categories should be handled separately or hardcoded for this example -->
            <option value="1">Category 1</option>
            <option value="2">Category 2</option>
        </select><br>
        <button type="submit">Add Product</button>
    </form>
</div>

<script>
function submitForm(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    fetch('src/add_product.php', {
        method: 'POST',
        body: formData
    }).then(response => response.text()).then(data => {
        alert(data);
        // Clear form or handle UI updates
    }).catch(error => console.error('Error:', error));
}
</script>
