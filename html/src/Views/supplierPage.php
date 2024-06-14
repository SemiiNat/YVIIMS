<?php

use App\Http\View;

View::startSection('content');
?>

<div class="container mx-auto p-6 bg-[#2C3531] rounded-xl shadow-lg">
    <h1 class="text-3xl font-bold mb-6 text-gray-200">Supplier List</h1>
    <div class="flex items-center justify-between mb-4">
        <div>
            <input type="text" placeholder="Search..." class="border border-[#116466] rounded py-2 px-4 bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-[#84cc16]" id="searchText">
        </div>
        <div>
            <button id="openButtonDialog" class="bg-[#84cc16] text-white px-4 py-2 rounded hover:bg-[#6b2b8e]">Add Supplier</button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table id="supplierTable" class="min-w-full bg-gray-800 border border-[#116466]">
            <thead>
                <tr>
                    <th class="px-4 py-2 border-b border-[#116466] bg-gray-900 text-left text-sm font-medium text-gray-200 uppercase tracking-wider">Supplier Name</th>
                    <th class="px-4 py-2 border-b border-[#116466] bg-gray-900 text-left text-sm font-medium text-gray-200 uppercase tracking-wider">Phone Number</th>
                    <th class="px-4 py-2 border-b border-[#116466] bg-gray-900 text-left text-sm font-medium text-gray-200 uppercase tracking-wider">Email</th>
                    <th class="px-4 py-2 border-b border-[#116466] bg-gray-900 text-left text-sm font-medium text-gray-200 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody id="supplierTableBody" hx-trigger="load">
                <!-- Initial table content will be populated by htmx -->
            </tbody>
        </table>
    </div>
</div>

<!-- Add Supplier Dialog -->
<dialog id="addModal" class="p-6 max-w-lg mx-auto rounded shadow-lg bg-[#2C3531] text-gray-200">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold">Add Supplier</h2>
        <button id="close" class="text-gray-500 hover:text-gray-800">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <!-- Form inside the dialog -->
    <form method="POST" id="supplierForm" hx-post="/supplier" hx-trigger="submit" hx-swap="none" hx-on="htmx:afterRequest: loadSupplier">
        <label for="supplier_name" class="block text-sm font-medium">Supplier Name:</label>
        <input type="text" id="supplier_name" name="supplier_name" class="mt-1 block w-full px-3 py-2 bg-gray-800 border border-[#116466] rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#84cc16] text-gray-200">
        <p id="supplier_name_err" class="error-validation text-red-500 text-sm hidden"></p>
        <label for="phone_number" class="block text-sm font-medium mt-4">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number" class="mt-1 block w-full px-3 py-2 bg-gray-800 border border-[#116466] rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#84cc16] text-gray-200">
        <p id="supplier_phone_err" class="error-validation text-red-500 text-sm hidden"></p>
        <label for="email" class="block text-sm font-medium mt-4">Email:</label>
        <input type="text" id="email" name="email" class="mt-1 block w-full px-3 py-2 bg-gray-800 border border-[#116466] rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#84cc16] text-gray-200">
        <p id="supplier_email_err" class="error-validation text-red-500 text-sm hidden"></p>
        <button type="submit" class="mt-4 py-2 px-4 bg-[#84cc16] text-white font-semibold rounded hover:bg-[#6b2b8e]">
            Submit
        </button>
    </form>
</dialog>

<!-- Edit Supplier Dialog -->
<dialog id="editModal" class="p-6 max-w-lg mx-auto rounded shadow-lg bg-[#2C3531] text-gray-200">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold">Edit Supplier</h2>
        <button id="closeEditDialog" class="text-gray-500 hover:text-gray-800" onclick="closeEditDialog()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <!-- Form inside the dialog -->
    <form method="POST" id="editSupplierForm" hx-trigger="submit" hx-swap="none" hx-on="htmx:afterRequest: loadSupplier">
        <label for="edit_supplier_name" class="block text-sm font-medium">Supplier Name:</label>
        <input type="text" id="edit_supplier_name" name="supplier_name" class="mt-1 block w-full px-3 py-2 bg-gray-800 border border-[#116466] rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#84cc16] text-gray-200">
        <p id="edit_supplier_name_err" class="error-validation text-red-500 text-sm hidden"></p>
        <label for="edit_phone_number" class="block text-sm font-medium mt-4">Phone Number:</label>
        <input type="text" id="edit_phone_number" name="phone_number" class="mt-1 block w-full px-3 py-2 bg-gray-800 border border-[#116466] rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#84cc16] text-gray-200">
        <p id="edit_supplier_phone_err" class="error-validation text-red-500 text-sm hidden"></p>
        <label for="edit_email" class="block text-sm font-medium mt-4">Email:</label>
        <input type="text" id="edit_email" name="email" class="mt-1 block w-full px-3 py-2 bg-gray-800 border border-[#116466] rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#84cc16] text-gray-200">
        <p id="edit_supplier_email_err" class="error-validation text-red-500 text-sm hidden"></p>
        <button type="submit" class="mt-4 py-2 px-4 bg-[#84cc16] text-white font-semibold rounded hover:bg-[#6b2b8e]">
            Update
        </button>
    </form>
</dialog>

<script src="public/js/serialize-helper.js"></script>
<script defer>
    // Get the dialog elements
    const addDialog = document.getElementById('addModal');
    const editDialog = document.getElementById('editModal');

    // Get the buttons that open the dialogs
    const openButton = document.getElementById('openButtonDialog');
    const closeButton = document.getElementById('close');

    const clearFormErrors = () => {
        const errorElements = document.querySelectorAll('.error-validation');
        errorElements.forEach(element => {
            element.innerHTML = '';
            element.classList.add('hidden');
        });
    };

    const clearForm = (form) => {
        form.reset();
        clearFormErrors();
    };

    const showEditDialog = async (supplierId) => {
        const response = await fetch(`/supplier/${supplierId}`);
        const data = await response.json();

        const form = document.getElementById("editSupplierForm");
        form.setAttribute('hx-put', `/supplier/${supplierId}`);
        form.action = `/supplier/${supplierId}`;

        document.getElementById('edit_supplier_name').value = data.supplier_name;
        document.getElementById('edit_phone_number').value = data.phone_number;
        document.getElementById('edit_email').value = data.email;

        editDialog.showModal();
        clearFormErrors();
    };

    const closeEditDialog = () => {
        editDialog.close();
        clearForm(document.getElementById('editSupplierForm'));
    };

    closeButton.addEventListener('click', function() {
        addDialog.close();
        clearForm(document.getElementById('supplierForm'));
    });

    openButton.addEventListener('click', function() {
        addDialog.showModal();
        clearForm(document.getElementById('supplierForm'));
    });

    document.getElementById('supplierForm').addEventListener('htmx:afterRequest', async function(event) {
        if (event.detail.xhr.status === 201) {
            addDialog.close();
            await loadSupplier();
            clearForm(event.detail.target);

            Swal.fire({
                icon: "success",
                title: "Successfully saved supplier",
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

    document.getElementById('editSupplierForm').addEventListener('submit', async function(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        const supplierId = event.target.action.split('/').pop(); // Extract supplier ID from action URL

        // Convert FormData to JSON
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });

        const response = await fetch(`/supplier/${supplierId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        if (response.ok) {
            editDialog.close();
            await loadSupplier();
            clearForm(event.target);

            Swal.fire({
                icon: "success",
                title: "Successfully updated supplier",
                showConfirmButton: false,
                timer: 1500
            });
        } else {
            const errors = await response.json();
            Object.keys(errors).forEach((key) => {
                const errorElement = document.getElementById(`edit_${key}_err`);
                if (errorElement) {
                    errorElement.innerHTML = errors[key];
                    errorElement.classList.remove('hidden');
                }
            });
        }
    });

    const loadSupplier = async () => {
        try {
            const response = await fetch('/api/supplier', {
                method: 'GET'
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Something went wrong');
            }

            const suppliers = await response.json();
            const tableBody = document.getElementById('supplierTableBody');

            tableBody.innerHTML = '';

            suppliers.forEach(supplier => {
                const row = document.createElement('tr');
                row.classList.add('bg-gray-800', 'hover:bg-gray-700');
                row.innerHTML = `
                    <td class="px-4 py-2 border-b border-[#116466] text-gray-200">${supplier.supplier_name}</td>
                    <td class="px-4 py-2 border-b border-[#116466] text-gray-200">${supplier.phone_number}</td>
                    <td class="px-4 py-2 border-b border-[#116466] text-gray-200">${supplier.email}</td>
                    <td class="px-4 py-2 border-b border-[#116466] text-gray-200">
                        <button onClick="showEditDialog(${supplier.id})" class="bg-[#84cc16] text-white px-2 py-1 rounded hover:bg-[#6b2b8e]">Edit</button>
                        <button onclick="deleteSupplier(${supplier.id})" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Delete</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });

        } catch (error) {
            alert("Error: " + error.message);
        }
    };

    const deleteSupplier = async (id) => {
        const result = await Swal.fire({
            title: "Delete?",
            text: "Do you want to delete this Supplier?",
            icon: "warning",
            confirmButtonText: "Yes",
            showDenyButton: true,
            denyButtonText: "No",
        });

        if (result.isConfirmed) {
            const response = await fetch(`/supplier/${id}`, {
                method: "DELETE"
            });

            if (response.ok) {
                Swal.fire("Deleted!", "Supplier has been deleted.", "success");
                await loadSupplier();
            } else {
                Swal.fire("Error!", "Supplier could not be deleted.", "error");
            }
        }
    };

    loadSupplier();
</script>

<?php View::endSection('content'); ?>
