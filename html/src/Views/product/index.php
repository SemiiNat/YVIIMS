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
                    <td class="px-4 py-2 border-b border-gray-300 text-gray-700">
                        <a class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600" href="/product/edit/<?= $product['id'] ?>">Edit</a>
                        <button class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 delete-product" data-id="<?= $product['id'] ?>">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

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
    const closeCategoryButton = document.getElementById('close');

    openCategoryButton.addEventListener('click', function() {
        addCategoryDialog.showModal();
    });

    closeCategoryButton.addEventListener('click', function() {
        addCategoryDialog.close();
    });

    document.getElementById('categoryForm').addEventListener('htmx:afterRequest', async function(event) {
        if (event.detail.xhr.status === 201) {
            addCategoryDialog.close();

            Swal.fire({
                icon: "success",
                title: "Successfully saved category",
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
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
