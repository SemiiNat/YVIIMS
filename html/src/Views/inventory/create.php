<?php

use App\Http\View;

View::startSection('content');
?>
<div class="container mx-auto p-6 bg-[#2C3531] rounded-xl shadow-lg">
    <form method="POST" id="saveForm" hx-post="/inventory/add" hx-trigger="submit" hx-swap="none">
        <div class="mb-4">
            <label for="product_id" class="block text-gray-200 text-sm font-bold mb-2">Product Name:</label>
            <select id="product_id" name="product_id" class="w-full border border-[#116466] rounded-md py-2 px-3 bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-[#84cc16]">
                <?php foreach ($products as $product) : ?>
                    <option value="<?= $product['id'] ?>"><?= $product['product_name'] ?></option>
                <?php endforeach; ?>
            </select>
            <p id="product_id_err" class="error-validation text-red-500 text-sm hidden"></p>
        </div>
        <div class="mb-4">
            <label for="manufacturing_date" class="block text-gray-200 text-sm font-bold mb-2">Manufacturing Date:</label>
            <input type="date" id="manufacturing_date" name="manufacturing_date" class="w-full border border-[#116466] rounded-md py-2 px-3 bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-[#84cc16]">
            <p id="manufacturing_date_err" class="error-validation text-red-500 text-sm hidden"></p>
        </div>
        <div class="mb-4">
            <label for="quantity" class="block text-gray-200 text-sm font-bold mb-2">Quantity:</label>
            <input type="number" id="quantity" name="quantity" class="w-full border border-[#116466] rounded-md py-2 px-3 bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-[#84cc16]">
            <p id="quantity_err" class="error-validation text-red-500 text-sm hidden"></p>
        </div>
        <button type="submit" class="bg-[#84cc16] hover:bg-[#6b2b8e] text-white font-bold py-2 px-4 rounded">Submit</button>
    </form>
</div>
<script>
    document.getElementById('saveForm').addEventListener('htmx:afterRequest', async function(event) {
        if (event.detail.xhr.status === 201) {
            Swal.fire({
                icon: "success",
                title: "Successfully saved inventory",
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
