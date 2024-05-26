<?php
// Start the session
session_start();

// Include database configuration
include 'config.php'; // Adjust the path as necessary

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../public/login.html");
    exit;
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $category_id = $_POST['category_id'];
    $sku = $_POST['sku'];
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $manufacturing_date = $_POST['manufacturing_date'];
    $expiration_date = $_POST['expiration_date'];
    $critical_level = $_POST['critical_level'];
    $reorder_point = $_POST['reorder_point'];
    $eoq = $_POST['eoq'];

    // Debug: print out the form data
    error_log("Form Data: CategoryID: $category_id, SKU: $sku, ProductName: $product_name, Description: $description, ManufacturingDate: $manufacturing_date, ExpirationDate: $expiration_date, CriticalLevel: $critical_level, ReorderPoint: $reorder_point, EOQ: $eoq");

    // Insert the data into the Product table
    $sql = "INSERT INTO Product (CategoryID, SKU, ProductName, Description, ManufacturingDate, ExpirationDate, CriticalLevel, ReorderPoint, EOQ)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("isssssiii", $category_id, $sku, $product_name, $description, $manufacturing_date, $expiration_date, $critical_level, $reorder_point, $eoq);
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Product added successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Execute Error: " . $stmt->error]);
            error_log("Execute Error: " . $stmt->error);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Prepare Error: " . $conn->error]);
        error_log("Prepare Error: " . $conn->error);
    }
    $conn->close();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - IMS YVI</title>
    <link rel="stylesheet" href="../public/css/output.css"> <!-- Ensure the path to Tailwind CSS is correct -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto px-4 py-8">
        <div id="message"></div>
        <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-8">
            <h1 class="text-3xl font-bold mb-6 text-gray-800">Add New Product</h1>
            <form id="addProductForm" method="POST">
                <div class="mb-4">
                    <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">Category:</label>
                    <select name="category_id" id="category_id" class="block appearance-none w-full bg-white border border-gray-300 hover:border-gray-400 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select a Category</option>
                        <?php
                        $sql = "SELECT CategoryID, CategoryName FROM Category";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['CategoryID'] . "'>" . htmlspecialchars($row['CategoryName']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="sku" class="block text-gray-700 text-sm font-bold mb-2">SKU:</label>
                    <input type="text" id="sku" name="sku" class="block w-full bg-white border border-gray-300 hover:border-gray-400 px-4 py-2 rounded shadow leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="product_name" class="block text-gray-700 text-sm font-bold mb-2">Product Name:</label>
                    <input type="text" id="product_name" name="product_name" class="block w-full bg-white border border-gray-300 hover:border-gray-400 px-4 py-2 rounded shadow leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                    <textarea id="description" name="description" class="block w-full bg-white border border-gray-300 hover:border-gray-400 px-4 py-2 rounded shadow leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label for="manufacturing_date" class="block text-gray-700 text-sm font-bold mb-2">Manufacturing Date:</label>
                        <input type="date" id="manufacturing_date" name="manufacturing_date" class="block w-full bg-white border border-gray-300 hover:border-gray-400 px-4 py-2 rounded shadow leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <label for="expiration_date" class="block text-gray-700 text-sm font-bold mb-2">Expiration Date:</label>
                        <input type="date" id="expiration_date" name="expiration_date" class="block w-full bg-white border border-gray-300 hover:border-gray-400 px-4 py-2 rounded shadow leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label for="critical_level" class="block text-gray-700 text-sm font-bold mb-2">Critical Level:</label>
                        <input type="number" id="critical_level" name="critical_level" class="block w-full bg-white border border-gray-300 hover:border-gray-400 px-4 py-2 rounded shadow leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <label for="reorder_point" class="block text-gray-700 text-sm font-bold mb-2">Reorder Point:</label>
                        <input type="number" id="reorder_point" name="reorder_point" class="block w-full bg-white border border-gray-300 hover:border-gray-400 px-4 py-2 rounded shadow leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="mb-4">
                    <label for="eoq" class="block text-gray-700 text-sm font-bold mb-2">EOQ:</label>
                    <input type="number" id="eoq" name="eoq" class="block w-full bg-white border border-gray-300 hover:border-gray-400 px-4 py-2 rounded shadow leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" name="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">Add Product</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    $('#addProductForm').submit(function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        $.ajax({
            url: 'add_product.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                let result = JSON.parse(response);
                if (result.status === "success") {
                    alert(result.message);
                    window.location.href = "dashboard.php";
                } else {
                    alert(result.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert("An error occurred while adding the product.");
            }
        });
    });
    </script>
</body>
</html>
