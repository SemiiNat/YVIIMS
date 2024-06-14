<?php
use App\Http\View;

View::startSection('content');
?>

<div class="container mx-auto p-6 bg-[#2C3531] rounded-xl shadow-lg">
    <h1 class="text-3xl font-bold mb-6 text-[#D1E8E2]">Category List</h1>
    <div class="flex items-center justify-between mb-4">
        <div>
            <input type="text" placeholder="Search..." class="border border-[#116466] rounded py-2 px-4 bg-[#2C3531] text-[#D1E8E2] focus:outline-none focus:ring-2 focus:ring-[#116466]" id="searchText">
        </div>
        <div>
            <button id="openButtonDialog" class="bg-[#116466] text-white px-4 py-2 rounded hover:bg-[#D9B08C]">Add Category</button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table id="categoryTable" class="min-w-full bg-transparent border border-[#116466]">
            <thead>
                <tr>
                    <th class="px-4 py-2 border-b border-[#116466] bg-[#2C3531] text-left text-sm font-medium text-[#D1E8E2] uppercase tracking-wider">Category Name</th>
                    <th class="px-4 py-2 border-b border-[#116466] bg-[#2C3531] text-left text-sm font-medium text-[#D1E8E2] uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody id="categoryTableBody" hx-trigger="load">
                <!-- Initial table content will be populated by htmx -->
            </tbody>
        </table>
    </div>
</div>

<!-- Dialog Element -->
<dialog id="addModal" class="p-6 max-w-lg mx-auto rounded shadow-lg bg-[#2C3531] text-[#D1E8E2]">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold">Add Category</h2>
        <button id="close" class="text-gray-500 hover:text-gray-800">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <!-- Form inside the dialog -->
    <form method="POST" id="categoryForm" hx-post="/category" hx-trigger="submit" hx-swap="none" hx-on="htmx:afterRequest: loadCategories">
        <label for="category_name" class="block text-sm font-medium">Category Name:</label>
        <input type="text" id="category_name" name="category_name" class="mt-1 block w-full px-3 py-2 bg-[#2C3531] border border-[#116466] rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#116466]">
        <p id="category_name_err" class="error-validation text-red-500 text-sm hidden"></p>
        <button type="submit" class="mt-4 py-2 px-4 bg-[#116466] text-white font-semibold rounded hover:bg-[#D9B08C]">
            Submit
        </button>
    </form>
</dialog>

<dialog id="editModal" class="p-6 max-w-lg mx-auto rounded shadow-lg bg-[#2C3531] text-[#D1E8E2]">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold">Edit Category</h2>
        <button onclick="closeEditDialog()"  class="text-gray-500 hover:text-gray-800">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <!-- Form inside the dialog -->
    <form method="POST" id="editCategoryForm" hx-post="/category" hx-trigger="submit" hx-swap="none" hx-on="htmx:afterRequest: loadCategories">
        <input value="id" type="hidden" name="id" />
        <label for="category_name" class="block text-sm font-medium">Category Name:</label>
        <input type="text" id="category_name" name="category_name" class="mt-1 block w-full px-3 py-2 bg-[#2C3531] border border-[#116466] rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#116466]">
        <p id="edit_category_name_err" class="error-validation text-red-500 text-sm hidden"></p>
        <button type="submit" class="mt-4 py-2 px-4 bg-[#116466] text-white font-semibold rounded hover:bg-[#D9B08C]">
            Update
        </button>
    </form>
</dialog>

<script src="public/js/serialize-helper.js"></script>
<script defer>
// Get the dialog element
const addDialog = document.getElementById('addModal');
const editDialog = document.getElementById('editModal');
const closeEditDialogButton = document.getElementById('closeEditDialog');

// Get the button that opens the addDialog
const openButton = document.getElementById('openButtonDialog');

// Get the button that closes the addDialog
const closeButton = document.getElementById('close');

const clearFormErrors = () => {
    const errorElements = document.querySelectorAll('.error-validation');
    errorElements.forEach(element => {
        element.innerHTML = '';
        element.classList.add('hidden');
    });
}

const showEditDialog =  async (categoryid) => {
    const dataEdited = await fetch(`/category/${categoryid}`);
    const jsonObj = await dataEdited.json();
    const form = document.getElementById("editCategoryForm");

    fillForm(form, jsonObj);
    editDialog.showModal();
    clearFormErrors();
}

const closeEditDialog = () => {
    editDialog.close();
    clearFormErrors();
}

// When the user clicks the close button, close the addDialog
closeButton.addEventListener('click', function() {
    addDialog.close();
    clearFormErrors();
    clearForm();
});

openButton.addEventListener('click', function(){
    addDialog.showModal();
    clearFormErrors();
    clearForm();
})

document.getElementById('categoryForm').addEventListener('htmx:afterRequest', async function(event) {
    if (event.detail.xhr.status === 201) {
        addDialog.close(); // Close the add dialog after successful submission
        await loadCategories();
        clearForm(event.detail.target);

        Swal.fire({
            icon: "success",
            title: "Successfully saved category",
            showConfirmButton: false,
            timer: 1500
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

document.getElementById('editCategoryForm').addEventListener('htmx:afterRequest', async function(event) {
    if (event.detail.xhr.status === 201) {
        editDialog.close(); // Close the add dialog after successful submission
        await loadCategories();
        clearForm(event.detail.target);

        Swal.fire({
            icon: "success",
            title: "Successfully saved category",
            showConfirmButton: false,
            timer: 1500
        });
    } else {
        const errors = JSON.parse(event.detail.xhr.responseText);
        Object.keys(errors).forEach((key) => {
            const errorElement = document.getElementById(`edit_${key}_err`);
            if (errorElement) {
                errorElement.innerHTML = errors[key];
                errorElement.classList.remove('hidden');
            }
        });
    }
});

const loadCategories = async () => {
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
            row.classList.add('bg-[#2C3531]', 'hover:bg-gray-100');
            row.innerHTML = `
                <td class="px-4 py-2 border-b border-[#116466] text-[#D1E8E2]">${category.category_name}</td>
                <td class="px-4 py-2 border-b border-[#116466] text-[#D1E8E2]">
                    <button onClick="showEditDialog(${category.id})"  class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600">Edit</button>
                    <button onclick="deleteCategory(${category.id})" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Delete</button>
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

const deleteCategory = async (id) => {
    const result = await Swal.fire({
        title: "Delete?",
        text: "Do you want to delete this Category",
        icon: "warning",
        confirmButtonText: "Yes",
        showDenyButton: true,
        denyButtonText: "No",
    });

    if (result.isConfirmed) {
        const response = await fetch(`/category/${id}`, {
            method: "DELETE"
        });

        if (response.ok) {
            Swal.fire("Deleted!", "Category has been deleted.", "success");
            await loadCategories(); // Refresh the categories after deletion
        } else {
            Swal.fire("Error!", "Category could not be deleted.", "error");
        }
    }
}

// Initial load of categories
loadCategories();
</script>

<?php
View::endSection('content');
?>
