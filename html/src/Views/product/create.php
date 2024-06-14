<?php

use App\Http\View;

View::startSection('content');
?>
    <style>
        .container {
            background-color: rgba(31, 41, 55, 0.9); /* Slightly transparent, matching the sidebar background */
        }
        .custom-choices {
            background-color: #1f2937 !important; /* Background color matching the form fields */
            color: #D1E8E2 !important; /* Text color matching the form fields */
            border-color: #116466 !important; /* Border color matching the form fields */
        }
        .choices__list--multiple .choices__item {
            background-color: #116466; /* Background color for selected items */
            color: #FFFFFF; /* Text color for selected items */
        }
        .choices__list--dropdown .choices__item--selectable {
            background-color: #1f2937 !important; /* Background color matching the form fields */
            color: #D1E8E2 !important; /* Text color matching the form fields */
        }
        .choices__list--dropdown .choices__item--selectable:hover {
            background-color: #3b3f45 !important; /* Slightly lighter background on hover */
            color: #FFFFFF !important; /* White text color on hover for better readability */
        }
    </style>

<body class="bg-gray-900 text-gray-100">
    <div class="container mx-auto p-6 rounded-xl shadow-lg">
        <form method="POST" id="saveForm" hx-post="/product" hx-trigger="submit" hx-swap="none">
            <div class="mb-4">
                <label for="product_name" class="block text-gray-200 text-sm font-bold mb-2">Product Name:</label>
                <input type="text" id="product_name" name="product_name" class="w-full border border-[#116466] rounded-md py-2 px-3 bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-[#84cc16]">
                <p id="product_name_err" class="error-validation text-red-500 text-sm hidden"></p>
            </div>
            <div class="mb-4">
                <label for="category" class="block text-gray-200 text-sm font-bold mb-2">Category:</label>
                <select id="category" name="category_id" class="w-full border border-[#116466] rounded-md py-2 px-3 bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-[#84cc16]">
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?= $category['id'] ?>"><?= $category['category_name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <p id="category_id_err" class="error-validation text-red-500 text-sm hidden"></p>
            </div>
            <div class="mb-4">
                <label for="suppliers" class="block text-gray-200 text-sm font-bold mb-2">Suppliers:</label>
                <select id="suppliers" name="supplier_ids[]" class="w-full border border-[#116466] rounded-md py-2 px-3 bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-[#84cc16]" multiple>
                    <?php foreach ($suppliers as $supplier) : ?>
                        <option value="<?= $supplier['id'] ?>"><?= $supplier['supplier_name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <p id="supplier_ids_err" class="error-validation text-red-500 text-sm hidden"></p>
            </div>
            <div class="mb-4">
                <label for="price" class="block text-gray-200 text-sm font-bold mb-2">Price:</label>
                <input type="text" id="price" name="price" class="w-full border border-[#116466] rounded-md py-2 px-3 bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-[#84cc16]">
                <p id="price_err" class="error-validation text-red-500 text-sm hidden"></p>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-200 text-sm font-bold mb-2">Description:</label>
                <textarea id="description" name="description" class="w-full border border-[#116466] rounded-md py-2 px-3 bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-[#84cc16]"></textarea>
            </div>
            <button type="submit" class="bg-[#84cc16] hover:bg-[#6b2b8e] text-white font-bold py-2 px-4 rounded">Submit</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
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
</body>



<?php
View::endSection('content');
?>