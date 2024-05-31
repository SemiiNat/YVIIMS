<?php

use App\Http\View;

View::startSection('content');
?>
<div class="container mx-auto"></div>
<form>
    <div class="mb-4">
        <label for="product_name" class="block text-gray-700 text-sm font-bold mb-2">Product Name:</label>
        <input type="text" id="product_name" name="product_name" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-blue-500">
    </div>
    <div class="mb-4">
        <label for="sku" class="block text-gray-700 text-sm font-bold mb-2">SKU:</label>
        <input type="text" id="sku" name="sku" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-blue-500">
    </div>
    <div class="mb-4">
        <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Category:</label>
        <select id="category" name="category" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-blue-500">
            <?php foreach ($categories as $category) : ?>
                <option value="<?= $category['id'] ?>"><?= $category['category_name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-4">
        <label for="supplier" class="block text-gray-700 text-sm font-bold mb-2">Supplier:</label>
        <select id="supplier" name="supplier" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-blue-500">
            <?php foreach ($suppliers as $supplier) : ?>
                <option value="<?= $supplier['id'] ?>"><?= $supplier['supplier_name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
    <textarea id="description" name="description" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>

    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Submit</button>
</form>
</div>

<?php
View::endSection('content');
?>