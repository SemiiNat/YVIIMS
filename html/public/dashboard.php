<?php
// Start the session
session_start();

// Check if the user is not logged in, redirect them to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script>
        function toggleDropdown(dropdownId) {
            var element = document.getElementById(dropdownId);
            element.classList.toggle("hidden");
        }

        function loadContent(page) {
            const url = '../src/' + page + '.php';
            console.log("Attempting to load:", url); // For debugging
            fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/html; charset=UTF-8',
                    'Authorization': 'Bearer ' + sessionStorage.getItem('token') // Assuming token-based auth
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok for ' + url);
                }
                return response.text();
            })
            .then(html => {
                document.getElementById('main-content').innerHTML = html;
            })
            .catch(error => {
                console.error('Error loading the page:', error);
                alert('Error loading the page: ' + error.message);
            });
        }
    </script>
    <title>IMS - YVI Skin Care Products</title>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="fixed left-0 top-0 w-64 h-full bg-lime-600 p-6 text-white">
            <a href="dashboard.php" class="flex items-center pb-4 border-b border-lime-500">
                <img src="https://placehold.co/32x32" alt="" class="w-10 h-10 rounded object-cover">
                <span class="text-lg font-bold ml-3">Logo</span>
            </a>
            <ul class="mt-5">
                <!-- Dynamic content loading through AJAX -->
                <li class="mb-5">
                    <a href="javascript:loadContent('dashboard_content');" class="flex items-center py-2 px-4 rounded-md hover:bg-lime-700">
                        <i class="fa-sharp fa-solid fa-gauge mr-3 text-lg"></i>
                        <span class="text-lg">Dashboard</span>
                    </a>
                </li>
                <!-- Product management dropdown -->
                <li class="mb-5 relative">
                    <button onclick="toggleDropdown('productDropdown')" class="w-full text-left flex items-center py-2 px-4 rounded-md hover:bg-lime-700">
                        <i class="fa-sharp fa-solid fa-tag mr-3 text-lg"></i>
                        <span class="text-lg">Product</span>
                    </button>
                    <div id="productDropdown" class="hidden flex flex-col pl-4">
                        <a href="javascript:loadContent('view_products');" class="py-1 hover:bg-lime-700">View Products</a>
                        <a href="javascript:loadContent('add_product');" class="py-1 hover:bg-lime-700">Add Product</a>
                    </div>
                </li>
                <!-- Supplier management dropdown -->
                <li class="mb-5 relative">
                    <button onclick="toggleDropdown('supplierDropdown')" class="w-full text-left flex items-center py-2 px-4 rounded-md hover:bg-lime-700">
                        <i class="fa-sharp fa-solid fa-truck mr-3 text-lg"></i>
                        <span class="text-lg">Supplier</span>
                    </button>
                    <div id="supplierDropdown" class="hidden flex flex-col pl-4">
                        <a href="javascript:loadContent('view_suppliers');" class="py-1 hover:bg-lime-700">View Suppliers</a>
                        <a href="javascript:loadContent('add_supplier');" class="py-1 hover:bg-lime-700">Add Supplier</a>
                    </div>
                </li>
                <!-- Order management dropdown -->
                <li class="mb-5 relative">
                    <button onclick="toggleDropdown('orderDropdown')" class="w-full text-left flex items-center py-2 px-4 rounded-md hover:bg-lime-700">
                        <i class="fa-sharp fa-solid fa-cart-shopping mr-3 text-lg"></i>
                        <span class="text-lg">Purchase Order</span>
                    </button>
                    <div id="orderDropdown" class="hidden flex flex-col pl-4">
                        <a href="javascript:loadContent('view_orders');" class="py-1 hover:bg-lime-700">View Orders</a>
                        <a href="javascript:loadContent('create_order');" class="py-1 hover:bg-lime-700">Create Order</a>
                    </div>
                </li>
            </ul>
            <!-- Logout -->
            <div class="py-2 px-4 bottom-0 w-full">
                <a href="../src/logout.php" class="flex items-center justify-center py-2 px-4 bg-red-500 hover:bg-red-700 rounded-md">
                    <i class="fa fa-sign-out-alt mr-2"></i> Logout
                </a>
            </div>
        </div>
        <!-- Main content area -->
        <div id="main-content" class="ml-64 p-6">
            <!-- Content dynamically loaded here -->
        </div>
    </div>
</body>
</html>
