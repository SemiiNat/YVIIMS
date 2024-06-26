<?php
use App\Http\View;

View::startSection('content');
?>

<div class="container mx-auto p-6 rounded-xl shadow-lg bg-[#2C3531]">
    <h1 class="text-3xl font-bold mb-6 text-[#D1E8E2]">Product List</h1>
    <div class="flex items-center justify-between mb-4">
        <div>
            <input type="text" placeholder="Search..." class="border rounded py-2 px-4 text-gray-700 focus:outline-none focus:border-[#116466]" id="searchText">
        </div>
        <div>
            <a class="bg-[#116466] text-white px-4 py-2 rounded hover:bg-[#D9B08C]" href="/product/create">Add Product</a>
            <button id="openButtonDialog" class="bg-[#116466] text-white px-4 py-2 rounded hover:bg-[#D9B08C]">Add Category</button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-[#2C3531] border border-gray-300">
            <thead>
                <tr>
                    <th class="px-4 py-2 border-b border-gray-300 bg-[#116466] text-left text-sm font-medium text-white uppercase tracking-wider">Product Name</th>
                    <th class="px-4 py-2 border-b border-gray-300 bg-[#116466] text-left text-sm font-medium text-white uppercase tracking-wider">Category</th>
                    <th class="px-4 py-2 border-b border-gray-300 bg-[#116466] text-left text-sm font-medium text-white uppercase tracking-wider">Price</th>
                    <th class="px-4 py-2 border-b border-gray-300 bg-[#116466] text-left text-sm font-medium text-white uppercase tracking-wider">Stock Status</th>
                    <th class="px-4 py-2 border-b border-gray-300 bg-[#116466] text-left text-sm font-medium text-white uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr class="bg-[#2C3531] hover:bg-[#3b3f45]">
                    <td class="px-4 py-2 border-b border-gray-300 text-[#D1E8E2]"><?= htmlspecialchars($product['product_name']) ?></td>
                    <td class="px-4 py-2 border-b border-gray-300 text-[#D1E8E2]"><?= htmlspecialchars($product['category_name'] ?? 'N/A') ?></td>
                    <td class="px-4 py-2 border-b border-gray-300 text-[#D1E8E2]">₱<?= number_format($product['price'], 2) ?></td>
                    <td class="px-4 py-2 border-b border-gray-300 text-[#D1E8E2]"><?= htmlspecialchars($product['stock_status']) ?></td>
                    <td class="px-4 py-2 border-b border-gray-300 text-[#D1E8E2]">
                        <a class="bg-[#116466] text-white px-2 py-1 rounded hover:bg-[#D9B08C]" href="/product/edit/<?= $product['id'] ?>">Edit</a>
                        <button class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 delete-product" data-id="<?= $product['id'] ?>">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for Adding Category -->
<dialog id="addCategoryModal" class="bg-white p-6 rounded shadow-lg w-1/3">
    <form id="categoryForm" action="/category" method="POST">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="category_name">
                Category Name
            </label>
            <input type="text" name="category_name" id="category_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <span id="category_name_err" class="text-red-500 text-xs italic hidden"></span>
        </div>
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-[#116466] hover:bg-[#D9B08C] text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Save
            </button>
            <button type="button" id="closeButtonDialog" class="bg-[#116466] hover:bg-[#D9B08C] text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Cancel
            </button>

        </div>
    </form>
</dialog>

<script src="public/js/serialize-helper.js"></script>
<script defer>
    // Delete Product
    document.querySelectorAll('.delete-product').forEach(button => {
        button.addEventListener('click', async function() {
            const productId = this.dataset.id;
            const response = await fetch(`/product/delete/${productId}`, {
                method: 'DELETE',
            });
            const result = await response.json();

            if (response.ok) {
                Swal.fire({
                    icon: "success",
                    title: result.message,
                    showConfirmButton: false,
                    timer: 1500
                });
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: result.error,
                    showConfirmButton: true
                });
            }
        });
    });

    // Category Part
    const addCategoryDialog = document.getElementById('addCategoryModal');
    const openCategoryButton = document.getElementById('openButtonDialog');
    const closeCategoryButton = document.getElementById('closeButtonDialog');

    openCategoryButton.addEventListener('click', function() {
        addCategoryDialog.showModal();
    });

    closeCategoryButton.addEventListener('click', function() {
        addCategoryDialog.close();
        clearFormErrors();
        clearForm();
    });

    document.getElementById('categoryForm').addEventListener('submit', async function(event) {
        event.preventDefault();

        const formData = new FormData(this);
        const response = await fetch('/category', {
            method: 'POST',
            body: formData,
        });

        if (response.ok) {
            const result = await response.json();
            if (result.success) {
                addCategoryDialog.close();

                Swal.fire({
                    icon: "success",
                    title: "Successfully saved category",
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = "/category";
                });
            }
        } else {
            const errors = await response.json();
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
