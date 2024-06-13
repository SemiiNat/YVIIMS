<?php
use App\Http\View;

View::startSection('content');
?>

<div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-6">Product List</h1>
    <div class="flex items-center justify-between mb-4">
        <div>
            <input type="text" placeholder="Search..." class="border rounded py-2 px-4 text-gray-700 focus:outline-none focus:border-blue-500" id="searchText">
        </div>
        <div>
            <a class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" href="/product/create">Add Product</a>
            <button id="openButtonDialog" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Category</button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="px-4 py-2 border-b border-gray-300 bg-gray-200 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Product Name</th>
                    <th class="px-4 py-2 border-b border-gray-300 bg-gray-200 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Category</th>
                    <th class="px-4 py-2 border-b border-gray-300 bg-gray-200 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Price</th>
                    <th class="px-4 py-2 border-b border-gray-300 bg-gray-200 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Stock Status</th>
                    <th class="px-4 py-2 border-b border-gray-300 bg-gray-200 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">ROP</th>
                    <th class="px-4 py-2 border-b border-gray-300 bg-gray-200 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">EOQ</th>
                    <th class="px-4 py-2 border-b border-gray-300 bg-gray-200 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Critical Level</th>
                    <th class="px-4 py-2 border-b border-gray-300 bg-gray-200 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr class="bg-white hover:bg-gray-100">
                    <td class="px-4 py-2 border-b border-gray-300 text-gray-700"><?= htmlspecialchars($product['product_name']) ?></td>
                    <td class="px-4 py-2 border-b border-gray-300 text-gray-700"><?= htmlspecialchars($product['category_name'] ?? 'N/A') ?></td>
                    <td class="px-4 py-2 border-b border-gray-300 text-gray-700">₱<?= number_format($product['price'], 2) ?></td>
                    <td class="px-4 py-2 border-b border-gray-300 text-gray-700"><?= htmlspecialchars($product['stock_status']) ?></td>
                    <td class="px-4 py-2 border-b border-gray-300 text-gray-700"><?= htmlspecialchars($product['reorder_point']) ?></td>
                    <td class="px-4 py-2 border-b border-gray-300 text-gray-700"><?= htmlspecialchars($product['economic_order_quantity']) ?></td>
                    <td class="px-4 py-2 border-b border-gray-300 text-gray-700"><?= htmlspecialchars($product['critical_level']) ?></td>
                    <td class="px-4 py-2 border-b border-gray-300 text-gray-700">
                        <a class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600" href="/product/edit/<?= $product['id'] ?>">Edit</a>
                        <a class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600" href="/product/delete/<?= $product['id'] ?>">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Dialog Element of Category -->
<dialog id="addCategoryModal" class="p-6 max-w-lg mx-auto rounded shadow-lg bg-white relative">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-gray-900">Add Category</h2>
        <button id="close" class="text-gray-500 hover:text-gray-800">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <!-- Form inside the dialog -->
    <form method="POST" action="/category" id="categoryForm" hx-post="/category" hx-trigger="submit" hx-target="this" hx-swap="none" hx-redirect="/category">
        <label for="category_name" class="block text-sm font-medium text-gray-700">Category Name:</label>
        <input type="text" id="category_name" name="category_name" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        <p id="category_name_err" class="error-validation text-red-500 text-sm hidden"></p>
        <button type="submit" class="mt-4 py-2 px-4 bg-blue-500 text-white font-semibold rounded hover:bg-blue-700">
            Submit
        </button>
    </form>
</dialog>

<script src="public/js/serialize-helper.js"></script>
<script defer>
    // Category Part
    // Get the dialog element
    const addCategoryDialog = document.getElementById('addCategoryModal');

    // Get the button that opens the dialog
    const openCategoryButton = document.getElementById('openButtonDialog');

    // Get the button that closes the dialog
    const closeCategoryButton = document.getElementById('close');

    // When the user clicks the open button, show the dialog
    openCategoryButton.addEventListener('click', function() {
        addCategoryDialog.showModal(); // Use dialog.show() if you do not need it to be modal
    });

    // When the user clicks the close button, close the dialog
    closeCategoryButton.addEventListener('click', function() {
        addCategoryDialog.close();
    });

    document.getElementById('categoryForm').addEventListener('htmx:afterRequest', async function(event) {
        if (event.detail.xhr.status === 201) {
            addCategoryDialog.close(); // Close the add dialog after successful submission

            Swal.fire({
                icon: "success",
                title: "Successfully saved category",
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                // Redirect to the category page after the timer completes
                window.location.href = "/category";
            });
        } else {
            const errors = JSON.parse(event.detail.xhr.responseText);
            Object.keys(errors).forEach((key) => {
                const errorElement = document.getElementById(`${key}_err`);
                if (errorElement) {
                    errorElement.innerHTML = errors[key];
                    errorElement.classList.remove('hidden');
                }
            });
        }
    });
</script>

<?php
View::endSection('content');
?>
