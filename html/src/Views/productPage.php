<?php
use App\Http\View;

View::startSection('content');
?>


<div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-6">Product List</h1>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="px-4 py-2 border-b border-gray-300 bg-gray-200 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Product Name</th>
                    <th class="px-4 py-2 border-b border-gray-300 bg-gray-200 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Category</th>
                    <th class="px-4 py-2 border-b border-gray-300 bg-gray-200 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Price</th>
                    <th class="px-4 py-2 border-b border-gray-300 bg-gray-200 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Stock Status</th>
                    <th class="px-4 py-2 border-b border-gray-300 bg-gray-200 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($products as $product): ?> 
                <tr class="bg-white hover:bg-gray-100">
                    <td class="px-4 py-2 border-b border-gray-300 text-gray-700"><?= $product['product_name'] ?></td>
                    <td class="px-4 py-2 border-b border-gray-300 text-gray-700">Category 1</td>
                    <td class="px-4 py-2 border-b border-gray-300 text-gray-700">$10.00</td>
                    <td class="px-4 py-2 border-b border-gray-300 text-gray-700">In Stock</td>
                    <td class="px-4 py-2 border-b border-gray-300 text-gray-700">
                        <button class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">View</button>
                        <button class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600">Edit</button>
                        <button class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
View::endSection('content');
?>
