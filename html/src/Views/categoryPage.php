<?php
use App\Http\View;

View::startSection('content');
?>

<div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-6">Category List</h1>
    <div class="flex items-center justify-between mb-4">
        <div>
            <input type="text" placeholder="Search..." class="border rounded py-2 px-4 text-gray-700 focus:outline-none focus:border-blue-500" id="searchText">
        </div>
        <div>
            <button id="openButtonDialog" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Category</button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table id="categoryTable" class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="px-4 py-2 border-b border-gray-300 bg-gray-200 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Category Name</th>
                    <th class="px-4 py-2 border-b border-gray-300 bg-gray-200 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody id="categoryTableBody" hx-trigger="load">
                <!-- Initial table content will be populated by htmx -->
            </tbody>
        </table>
    </div>
</div>

<!-- Dialog Element -->
<dialog id="myModal" class="p-6 max-w-lg mx-auto rounded shadow-lg bg-white relative">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-gray-900">Add Category</h2>
        <button id="close" class="text-gray-500 hover:text-gray-800">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <!-- Form inside the dialog -->
    <form method="POST" id="categoryForm" hx-post="/category" hx-trigger="submit" hx-swap="none" hx-on="htmx:afterRequest: updateCategories">
        <label for="category_name" class="block text-sm font-medium text-gray-700">Category Name:</label>
        <input type="text" id="category_name" name="category_name" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        <p id="category_name_err" class="text-red-500 text-sm hidden"></p>
        <button type="submit" class="mt-4 py-2 px-4 bg-blue-500 text-white font-semibold rounded hover:bg-blue-700">
            Submit
        </button>
    </form>
</dialog>

<script defer>
// Get the dialog element


const dialog = document.getElementById('myModal');

// Get the button that opens the dialog
const openButton = document.getElementById('openButtonDialog');

// Get the button that closes the dialog
const closeButton = document.getElementById('close');

// When the user clicks the open button, show the dialog
openButton.addEventListener('click', function() {
    dialog.showModal(); // Use dialog.show() if you do not need it to be modal
});

// When the user clicks the close button, close the dialog
closeButton.addEventListener('click', function() {
    dialog.close();
});

document.addEventListener('htmx:afterRequest', async function(event) {
    if (event.detail.target.id === 'categoryForm') {
        if (event.detail.xhr.status === 201) {
            dialog.close(); // Close the dialog after successful submission
            updateCategories();
            clearForm();
            Swal.fire({
                icon: "success",
                title: "Successfully saved category",
                showConfirmButton: false,
                timer: 1500
            });
        } else {
            const responseText = event.detail.xhr.responseText;
            const jsonObj = JSON.parse(responseText);
            Object.keys(jsonObj).forEach(function(key) {
                const errorElement = document.getElementById(`${key}_err`);
                if (errorElement) {
                    errorElement.innerHTML = jsonObj[key];
                    errorElement.classList.remove('hidden');
                }
            });
        }
    }
});


const updateCategories = async () => {
    try {
        const response = await fetch('/api/category', {
            method: 'GET'
        });

        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.message || 'Something went wrong');
        }

        const categories = await response.json();
        const tableBody = document.getElementById('categoryTableBody');

        // Clear the existing table body content
        tableBody.innerHTML = '';

        // Populate the table with the new data
        categories.forEach(category => {
            const row = document.createElement('tr');
            row.classList.add('bg-white', 'hover:bg-gray-100');
            row.innerHTML = `
                <td class="px-4 py-2 border-b border-gray-300 text-gray-700">${category.category_name}</td>
                <td class="px-4 py-2 border-b border-gray-300 text-gray-700">
                    <a class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">View</a>
                    <a class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600">Edit</a>
                    <a class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Delete</a>
                </td>
            `;
            tableBody.appendChild(row);
        });

    } catch (error) {
        alert("Error: " + error.message);
    }
}

function clearForm() {
    document.getElementById('categoryForm').reset();
}

// Initial load of categories
updateCategories();
</script>

<?php
View::endSection('content');
?>
