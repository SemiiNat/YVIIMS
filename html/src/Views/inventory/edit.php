<?php

use App\Http\View;

View::startSection('content');
?>
<div class="container mx-auto p-6 bg-[#2C3531] rounded-xl shadow-lg">
    <h1 class="text-3xl font-bold mb-6 text-gray-200">Edit Inventory</h1>
    <form action="/inventory/update/<?= htmlspecialchars($inventory['id']) ?>" method="POST" class="w-full">
        <div class="mb-4">
            <label class="block text-gray-200 text-sm font-bold mb-2" for="product_id">
                Product
            </label>
            <select name="product_id" id="product_id" class="shadow appearance-none border border-[#116466] rounded w-full py-2 px-3 bg-gray-800 text-gray-200 leading-tight focus:outline-none focus:ring-2 focus:ring-[#84cc16]">
                <?php foreach ($products as $product): ?>
                    <option value="<?= htmlspecialchars($product['id']) ?>" <?= $inventory['product_id'] == $product['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($product['product_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-gray-200 text-sm font-bold mb-2" for="quantity">
                Quantity
            </label>
            <input type="number" name="quantity" id="quantity" value="<?= htmlspecialchars($inventory['quantity'] ?? '') ?>" class="shadow appearance-none border border-[#116466] rounded w-full py-2 px-3 bg-gray-800 text-gray-200 leading-tight focus:outline-none focus:ring-2 focus:ring-[#84cc16]">
        </div>
        <div class="mb-4">
            <label class="block text-gray-200 text-sm font-bold mb-2" for="manufacturing_date">
                Manufacturing Date
            </label>
            <input type="date" name="manufacturing_date" id="manufacturing_date" value="<?= htmlspecialchars($inventory['manufacturing_date'] ?? '') ?>" class="shadow appearance-none border border-[#116466] rounded w-full py-2 px-3 bg-gray-800 text-gray-200 leading-tight focus:outline-none focus:ring-2 focus:ring-[#84cc16]">
        </div>
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-[#84cc16] hover:bg-[#6b2b8e] text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Update Inventory
            </button>
            <a href="/inventory" class="inline-block align-baseline font-bold text-sm text-[#84cc16] hover:text-[#6b2b8e]">
                Cancel
            </a>
        </div>
    </form>
</div>

<?php

View::endSection('content');
?>
