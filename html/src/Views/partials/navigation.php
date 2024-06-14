<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation</title>
    <link rel="stylesheet" href="public/css/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <nav class="fixed left-0 top-0 h-full bg-gray-900 p-6">
        <a href="#" class="flex items-center pb-4 border-b border-gray-700">
            <img src="https://placehold.co/32x32" alt="" class="w-10 h-10 rounded object-cover">
            <span class="text-lg font-bold text-[#84cc16] ml-3 hover:text-gray-300">YVI</span>
        </a>
        <ul class="mt-5 ml-2">
            <li class="mb-5">
                <a href="/dashboard" class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-700 rounded-md">
                    <i class="fa-sharp fa-solid fa-gauge mr-3 text-lg text-[#84cc16]"></i>
                    <span class="text-lg hover:text-white">Dashboard</span>
                </a>
            </li>
            <li class="mb-5 relative">
                <button onclick="toggleDropdown('productDropdown')" class="flex items-center py-2 px-4 w-full text-left text-gray-300 hover:bg-gray-700 rounded-md">
                    <i class="fa-sharp fa-solid fa-tag mr-3 text-lg text-[#84cc16]"></i>
                    <span class="text-lg hover:text-white">Product</span>
                </button>
                <div id="productDropdown" class="hidden flex flex-col pl-4">
                    <a href="/product" class="py-1 text-gray-300 hover:text-white">View Products</a>
                    <a href="/product/create" class="py-1 text-gray-300 hover:text-white">Add Product</a>
                    <a href="/category" class="py-1 text-gray-300 hover:text-white">View Categories</a>
                    <a href="/inventory" class="py-1 text-gray-300 hover:text-white">View Product Inventory</a>
                </div>
            </li>
            <li class="mb-5 relative">
                <button onclick="toggleDropdown('supplierDropdown')" class="flex items-center py-2 px-4 w-full text-left text-gray-300 hover:bg-gray-700 rounded-md">
                    <i class="fa-sharp fa-solid fa-truck mr-3 text-lg text-[#84cc16]"></i>
                    <span class="text-lg hover:text-white">Supplier</span>
                </button>
                <div id="supplierDropdown" class="hidden flex flex-col pl-4">
                    <a href="/supplier" class="py-1 text-gray-300 hover:text-white">View Suppliers</a>
                </div>
            </li>
            <li class="mb-5 relative">
                <button onclick="toggleDropdown('orderDropdown')" class="flex items-center py-2 px-4 w-full text-left text-gray-300 hover:bg-gray-700 rounded-md">
                    <i class="fa-sharp fa-solid fa-cart-shopping mr-3 text-lg text-[#84cc16]"></i>
                    <span class="text-lg hover:text-white">Purchase Order</span>
                </button>
                <div id="orderDropdown" class="hidden flex flex-col pl-4">
                    <a href="#" class="py-1 text-gray-300 hover:text-white">View Orders</a>
                    <a href="#" class="py-1 text-gray-300 hover:text-white">Create Order</a>
                </div>
            </li>
        </ul>
        <form action="/logout" method="POST" class="absolute bottom-0 left-0 w-full py-2 px-4">
            <button type="submit" class="flex items-center justify-center py-2 px-4 text-white bg-red-600 hover:bg-red-700 rounded-md">
                <i class="fa fa-sign-out-alt mr-2"></i> Logout
            </button>
        </form>
    </nav>

    <script>
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
            } else {
                dropdown.classList.add('hidden');
            }
        }
    </script>
</body>

</html>
