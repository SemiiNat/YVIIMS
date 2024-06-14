<?php
use App\Http\View;

View::startSection('content');
?>
<div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-6">Inventory List</h1>
    <div class="flex items-center justify-between mb-4">
        <div>
            <input type="text" placeholder="Search..." class="border rounded py-2 px-4 text-gray-700 focus:outline-none focus:border-blue-500" id="searchText">
        </div>
        <a class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" href="/inventory/add">Add Inventory</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="px-4 py-2 border-b border-gray-300 bg-gray-200 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Product Name</th>
                    <th class="px-4 py-2 border-b border-gray-300 bg-gray-200 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">SKU</th>
                    <th class="px-4 py-2 border-b border-gray-300 bg-gray-200 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Quantity</th>
                    <th class="px-4 py-2 border-b border-gray-300 bg-gray-200 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Manufacturing Date</th>
                    <th class="px-4 py-2 border-b border-gray-300 bg-gray-200 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Expiration Date</th>
                    <th class="px-4 py-2 border-b border-gray-300 bg-gray-200 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inventory as $item): ?>
                <tr class="bg-white hover:bg-gray-100">
                    <td class="px-4 py-2 border-b border-gray-300 text-gray-700"><?= htmlspecialchars($item['product_name']) ?></td>
                    <td class="px-4 py-2 border-b border-gray-300 text-gray-700"><?= htmlspecialchars($item['sku']) ?></td>
                    <td class="px-4 py-2 border-b border-gray-300 text-gray-700"><?= htmlspecialchars($item['quantity']) ?></td>
                    <td class="px-4 py-2 border-b border-gray-300 text-gray-700"><?= htmlspecialchars($item['manufacturing_date']) ?></td>
                    <td class="px-4 py-2 border-b border-gray-300 text-gray-700"><?= htmlspecialchars($item['expiration_date']) ?></td>
                    <td class="px-4 py-2 border-b border-gray-300 text-gray-700">
                        <a class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600" href="/inventory/edit/<?= $item['id'] ?>">Edit</a>
                        <a class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600" href="/inventory/delete/<?= $item['id'] ?>">Delete</a>
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
