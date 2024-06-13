<?php

use App\Http\View;

View::startSection('content');
?>
<div class="container mx-auto">
    <form method="POST" id="saveForm" hx-post="/product" hx-trigger="submit" hx-swap="none">
        <div class="mb-4">
            <label for="product_name" class="block text-gray-700 text-sm font-bold mb-2">Product Name:</label>
            <input type="text" id="product_name" name="product_name" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-blue-500">
            <p id="product_name_err" class="error-validation text-red-500 text-sm hidden"></p>
        </div>
        <div class="mb-4">
            <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Category:</label>
            <select id="category" name="category_id" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-blue-500">
                <?php foreach ($categories as $category) : ?>
                    <option value="<?= $category['id'] ?>"><?= $category['category_name'] ?></option>
                <?php endforeach; ?>
            </select>
            <p id="category_id_err" class="error-validation text-red-500 text-sm hidden"></p>
        </div>
        <div class="mb-4">
            <label for="suppliers" class="block text-gray-700 text-sm font-bold mb-2">Suppliers:</label>
            <select id="suppliers" name="supplier_ids[]" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-blue-500" multiple>
                <?php foreach ($suppliers as $supplier) : ?>
                    <option value="<?= $supplier['id'] ?>"><?= $supplier['supplier_name'] ?></option>
                <?php endforeach; ?>
            </select>
            <p id="supplier_ids_err" class="error-validation text-red-500 text-sm hidden"></p>
        </div>
        <div class="mb-4">
            <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Price:</label>
            <input type="text" id="price" name="price" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-blue-500">
            <p id="price_err" class="error-validation text-red-500 text-sm hidden"></p>
        </div>
        <div class="mb-4">
            <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">Quantity:</label>
            <input type="text" id="quantity" name="quantity" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-blue-500">
            <p id="quantity_err" class="error-validation text-red-500 text-sm hidden"></p>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
            <textarea id="description" name="description" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>
        </div>
        <div class="mb-4">
            <label for="manufacturing_date" class="block text-gray-700 text-sm font-bold mb-2">Manufacturing Date:</label>
            <input type="date" id="manufacturing_date" name="manufacturing_date" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-blue-500">
            <p id="manufacturing_date_err" class="error-validation text-red-500 text-sm hidden"></p>
        </div>
        <div class="mb-4">
            <label for="reorder_point" class="block text-gray-700 text-sm font-bold mb-2">Reorder Point (ROP):</label>
            <input type="text" id="reorder_point" name="reorder_point" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-blue-500">
            <p id="reorder_point_err" class="error-validation text-red-500 text-sm hidden"></p>
        </div>
        <div class="mb-4">
            <label for="economic_order_quantity" class="block text-gray-700 text-sm font-bold mb-2">Economic Order Quantity (EOQ):</label>
            <input type="text" id="economic_order_quantity" name="economic_order_quantity" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-blue-500">
            <p id="economic_order_quantity_err" class="error-validation text-red-500 text-sm hidden"></p>
        </div>
        <div class="mb-4">
            <label for="critical_level" class="block text-gray-700 text-sm font-bold mb-2">Critical Level:</label>
            <input type="text" id="critical_level" name="critical_level" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-1 focus:ring-blue-500">
            <p id="critical_level_err" class="error-validation text-red-500 text-sm hidden"></p>
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Submit</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const supplierSelect = new Choices('#suppliers', {
            removeItemButton: true,
            searchEnabled: true
        });
    });
    document.getElementById('saveForm').addEventListener('htmx:afterRequest', async function(event) {
        if (event.detail.xhr.status === 201) {
            Swal.fire({
                icon: "success",
                title: "Successfully saved product",
                showConfirmButton: false,
                timer: 1500
            });
            setTimeout(() => {
                window.location.href = '/product';
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
