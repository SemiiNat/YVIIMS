<?php
use App\Http\View;

View::startSection('content');
?>
<div class="container mx-auto p-6 bg-[#2C3531] rounded-xl shadow-lg">
    <h1 class="text-3xl font-bold mb-6 text-[#D1E8E2]">Inventory List</h1>
    <div class="flex items-center justify-between mb-4">
        <div>
            <input type="text" placeholder="Search..." class="border border-[#116466] rounded py-2 px-4 bg-[#2C3531] text-[#D1E8E2] focus:outline-none focus:ring-2 focus:ring-[#116466]" id="searchText">
        </div>
        <a class="bg-[#116466] text-white px-4 py-2 rounded hover:bg-[#D9B08C]" href="/inventory/add">Add Inventory</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-transparent border border-[#116466]">
            <thead>
                <tr>
                    <th class="px-4 py-2 border-b border-[#116466] bg-[#2C3531] text-left text-sm font-medium text-[#D1E8E2] uppercase tracking-wider">Product Name</th>
                    <th class="px-4 py-2 border-b border-[#116466] bg-[#2C3531] text-left text-sm font-medium text-[#D1E8E2] uppercase tracking-wider">SKU</th>
                    <th class="px-4 py-2 border-b border-[#116466] bg-[#2C3531] text-left text-sm font-medium text-[#D1E8E2] uppercase tracking-wider">Quantity</th>
                    <th class="px-4 py-2 border-b border-[#116466] bg-[#2C3531] text-left text-sm font-medium text-[#D1E8E2] uppercase tracking-wider">Manufacturing Date</th>
                    <th class="px-4 py-2 border-b border-[#116466] bg-[#2C3531] text-left text-sm font-medium text-[#D1E8E2] uppercase tracking-wider">Expiration Date</th>
                    <th class="px-4 py-2 border-b border-[#116466] bg-[#2C3531] text-left text-sm font-medium text-[#D1E8E2] uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inventory as $item): ?>
                <tr class="bg-[#2C3531] hover:bg-[#116466]">
                    <td class="px-4 py-2 border-b border-[#116466] text-[#D1E8E2]"><?= htmlspecialchars($item['product_name']) ?></td>
                    <td class="px-4 py-2 border-b border-[#116466] text-[#D1E8E2]"><?= htmlspecialchars($item['sku']) ?></td>
                    <td class="px-4 py-2 border-b border-[#116466] text-[#D1E8E2]"><?= htmlspecialchars($item['quantity']) ?></td>
                    <td class="px-4 py-2 border-b border-[#116466] text-[#D1E8E2]"><?= htmlspecialchars($item['manufacturing_date']) ?></td>
                    <td class="px-4 py-2 border-b border-[#116466] text-[#D1E8E2]"><?= htmlspecialchars($item['expiration_date']) ?></td>
                    <td class="px-4 py-2 border-b border-[#116466] text-[#D1E8E2]">
                        <a class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600" href="/inventory/edit/<?= $item['id'] ?>">Edit</a>
                        <button class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600" onclick="deleteInventory(<?= $item['id'] ?>)">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function deleteInventory(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/inventory/delete/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    Swal.fire(
                        'Deleted!',
                        'Your record has been deleted.',
                        'success'
                    );
                    // Refresh the page or remove the deleted row from the DOM
                    location.reload();
                } else {
                    Swal.fire(
                        'Error!',
                        'An error occurred while deleting the record.',
                        'error'
                    );
                }
            })
            .catch(error => {
                Swal.fire(
                    'Error!',
                    'An error occurred while deleting the record.',
                    'error'
                );
            });
        }
    });
}
</script>
<?php
View::endSection('content');
?>
