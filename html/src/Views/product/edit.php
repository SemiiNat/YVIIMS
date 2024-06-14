<?php

use App\Http\View;

View::startSection('content');

?>
<div class="container mx-auto p-6 bg-[#2C3531] rounded-xl shadow-lg">
    <form method="POST" id="saveForm" hx-post="/product/update/<?= $product['id'] ?>" hx-trigger="submit" hx-swap="none">
        <div class="mb-4">
            <label for="product_name" class="block text-[#D1E8E2] text-sm font-bold mb-2">Product Name:</label>
            <input type="text" id="product_name" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>" class="w-full border border-[#116466] rounded-md py-2 px-3 bg-[#2C3531] text-[#D1E8E2] focus:outline-none focus:ring-2 focus:ring-[#116466]">
            <p id="product_name_err" class="error-validation text-[#FFCB9A] text-sm hidden"></p>
        </div>
        <div class="mb-4">
            <label for="category" class="block text-[#D1E8E2] text-sm font-bold mb-2">Category:</label>
            <select id="category" name="category_id" class="w-full border border-[#116466] rounded-md py-2 px-3 bg-[#2C3531] text-[#D1E8E2] focus:outline-none focus:ring-2 focus:ring-[#116466]">
                <?php foreach ($categories as $category) : ?>
                    <option value="<?= $category['id'] ?>" <?= $category['id'] == $product['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($category['category_name']) ?></option>
                <?php endforeach; ?>
            </select>
            <p id="category_id_err" class="error-validation text-[#FFCB9A] text-sm hidden"></p>
        </div>
        <div class="mb-4">
            <label for="suppliers" class="block text-[#D1E8E2] text-sm font-bold mb-2">Suppliers:</label>
            <select id="suppliers" name="supplier_ids[]" class="w-full border border-[#116466] rounded-md py-2 px-3 bg-[#2C3531] text-[#D1E8E2] focus:outline-none focus:ring-2 focus:ring-[#116466]" multiple>
                <?php foreach ($suppliers as $supplier) : ?>
                    <option value="<?= $supplier['id'] ?>" <?= in_array($supplier['id'], $product['supplier_ids']) ? 'selected' : '' ?>><?= htmlspecialchars($supplier['supplier_name']) ?></option>
                <?php endforeach; ?>
            </select>
            <p id="supplier_ids_err" class="error-validation text-[#FFCB9A] text-sm hidden"></p>
        </div>
        <div class="mb-4">
            <label for="price" class="block text-[#D1E8E2] text-sm font-bold mb-2">Price:</label>
            <input type="text" id="price" name="price" value="<?= htmlspecialchars($product['price']) ?>" class="w-full border border-[#116466] rounded-md py-2 px-3 bg-[#2C3531] text-[#D1E8E2] focus:outline-none focus:ring-2 focus:ring-[#116466]">
            <p id="price_err" class="error-validation text-[#FFCB9A] text-sm hidden"></p>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-[#D1E8E2] text-sm font-bold mb-2">Description:</label>
            <textarea id="description" name="description" class="w-full border border-[#116466] rounded-md py-2 px-3 bg-[#2C3531] text-[#D1E8E2] focus:outline-none focus:ring-2 focus:ring-[#116466]"><?= htmlspecialchars($product['description']) ?></textarea>
            <p id="description_err" class="error-validation text-[#FFCB9A] text-sm hidden"></p>
        </div>
        <button type="submit" class="bg-[#116466] hover:bg-[#D9B08C] text-white font-bold py-2 px-4 rounded">Update</button>
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
        if (event.detail.xhr.status === 200) {
            Swal.fire({
                icon: "success",
                title: "Successfully updated product",
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
