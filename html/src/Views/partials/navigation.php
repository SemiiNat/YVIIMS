<nav class="fixed left-0 top-0 h-full bg-lime-400 p-6">
    <a href="#" class="flex items-center pb-4 border-b border-lime-500">
        <img src="https://placehold.co/32x32" alt="" class="w-10 h-10 rounded object-cover">
        <span class="text-lg font-bold text-black ml-3 hover:text-black">Logo</span>
    </a>
    <ul class="mt-5 ml-2">
        <li class="mb-5">
            <a href="/dashboard" class="flex items-center py-2 px-4 text-black hover:bg-lime-500 rounded-md">
                <i class="fa-sharp fa-solid fa-gauge mr-3 text-lg"></i>
                <span class="text-lg hover:text-white">Dashboard</span>
            </a>
        </li>
        <li class="mb-5 relative">
            <button onclick="toggleDropdown('productDropdown')" class="flex items-center py-2 px-4 w-full text-left text-black hover:bg-lime-500 rounded-md">
                <i class="fa-sharp fa-solid fa-tag mr-3 text-lg"></i>
                <span class="text-lg hover:text-white">Product</span>
            </button>
            <div id="productDropdown" class="hidden flex flex-col pl-4">
                <a href="/product" class="py-1">View Products</a>
                <a href="/product/create" class="py-1">Add Product</a>
                <a href="/category" class="py-1">View Categories</a>
                <a href="/category/create" class="py-1">Add Categories</a>
            </div>
        </li>
        <li class="mb-5 relative">
            <button onclick="toggleDropdown('supplierDropdown')" class="flex items-center py-2 px-4 w-full text-left text-black hover:bg-lime-500 rounded-md">
                <i class="fa-sharp fa-solid fa-truck mr-3 text-lg"></i>
                <span class="text-lg hover:text-white">Supplier</span>
            </button>
            <div id="supplierDropdown" class="hidden flex flex-col pl-4">
                <a href="#" class="py-1">View Suppliers</a>
                <a href="#" class="py-1">Add Supplier</a>
            </div>
        </li>
        <li class="mb-5 relative">
            <button onclick="toggleDropdown('orderDropdown')" class="flex items-center py-2 px-4 w-full text-left text-black hover:bg-lime-500 rounded-md">
                <i class="fa-sharp fa-solid fa-cart-shopping mr-3 text-lg"></i>
                <span class="text-lg hover:text-white">Purchase Order</span>
            </button>
            <div id="orderDropdown" class="hidden flex flex-col pl-4">
                <a href="#" class="py-1">View Orders</a>
                <a href="#" class="py-1">Create Order</a>
            </div>
        </li>
    </ul>
    <form action="/logout" method="POST" class="left-0 items-center py-2 px-4 bottom-0 w-full">
        <button type="submit" class="flex items-center justify-center py-2 px-4 text-white bg-red-500 hover:bg-red-700 rounded-md">
            <i class="fa fa-sign-out-alt mr-2"></i> Logout
        </button>
    </form>
</nav>