<?php

use App\Http\View;

View::startSection('content');
?>
<div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-6">Edit Inventory</h1>
    <form action="/inventory/update/<?= htmlspecialchars($inventory['id']) ?>" method="POST" class="w-full max-w-lg">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="product_id">
                Product
            </label>
            <select name="product_id" id="product_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <?php foreach ($products as $product): ?>
                    <option value="<?= htmlspecialchars($product['id']) ?>" <?= $inventory['product_id'] == $product['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($product['product_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="quantity">
                Quantity
            </label>
            <input type="number" name="quantity" id="quantity" value="<?= htmlspecialchars($inventory['quantity'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="manufacturing_date">
                Manufacturing Date
            </label>
            <input type="date" name="manufacturing_date" id="manufacturing_date" value="<?= htmlspecialchars($inventory['manufacturing_date'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Update Inventory
            </button>
            <a href="/inventory" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                Cancel
            </a>
        </div>
    </form>
</div>

<?php

View::endSection('content');
?>
