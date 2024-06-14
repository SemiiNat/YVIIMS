<?php

use App\Http\View;

View::startSection('content');
?>
<div class="container mx-auto">
    <form method="POST" id="saveInventoryForm" hx-post="/inventory/add" hx-trigger="submit" hx-swap="none">
        <div class="mb-4">
            <label for="product_id" class="block text-gray-700 text-sm font-bold mb-2">Product Name:</label>
            <select id="product_id" name="product_id" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-blue-500">
                <?php foreach ($products as $product) : ?>
                    <option value="<?= $product['id'] ?>"><?= htmlspecialchars($product['product_name']) ?></option>
                <?php endforeach; ?>
            </select>
            <p id="product_id_err" class="error-validation text-red-500 text-sm hidden"></p>
        </div>
        <div class="mb-4">
            <label for="manufacturing_date" class="block text-gray-700 text-sm font-bold mb-2">Manufacturing Date:</label>
            <input type="date" id="manufacturing_date" name="manufacturing_date" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-blue-500">
            <p id="manufacturing_date_err" class="error-validation text-red-500 text-sm hidden"></p>
        </div>
        <div class="mb-4">
            <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">Quantity:</label>
            <input type="number" id="quantity" name="quantity" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-blue-500">
            <p id="quantity_err" class="error-validation text-red-500 text-sm hidden"></p>
        </div>
        <div class="mb-4">
            <label for="reorder_point" class="block text-gray-700 text-sm font-bold mb-2">Reorder Point (ROP):</label>
            <input type="number" id="reorder_point" name="reorder_point" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-blue-500">
            <p id="reorder_point_err" class="error-validation text-red-500 text-sm hidden"></p>
        </div>
        <div class="mb-4">
            <label for="safety_stock" class="block text-gray-700 text-sm font-bold mb-2">Safety Stock:</label>
            <input type="number" id="safety_stock" name="safety_stock" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-blue-500">
            <p id="safety_stock_err" class="error-validation text-red-500 text-sm hidden"></p>
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Inventory</button>
    </form>
</div>

<script>
    document.getElementById('saveInventoryForm').addEventListener('htmx:afterRequest', async function(event) {
        if (event.detail.xhr.status === 201) {
            Swal.fire({
                icon: "success",
                title: "Successfully added inventory",
                showConfirmButton: false,
                timer: 1500
            });
            setTimeout(() => {
                window.location.href = '/inventory';
            }, 1500);
        } else if (event.detail.xhr.status === 422) {
            const errors = JSON.parse(event.detail.xhr.responseText);
            Object.keys(errors).forEach((key) => {
                const errorElement = document.getElementById(`${key}_err`);
                if (errorElement) {
                    errorElement.innerHTML = errors[key];
                    errorElement.classList.remove('hidden');
                }
            });
        } else {
            Swal.fire({
                icon: "warning",
                title: "Something went wrong!",
                showConfirmButton: false,
                timer: 1500
            });
        }
    });
</script>

<?php
View::endSection('content');
?>
