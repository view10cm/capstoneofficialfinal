/**
 * Admin Inventory Management JavaScript
 * File: public/js/admin-inventory.js
 */

// Search inventory items in the table
function searchInventory() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.querySelector('tbody');
    const rows = table.getElementsByTagName('tr');

    for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let found = false;
        
        // Start from index 1 to skip checkbox column
        for (let j = 1; j < cells.length; j++) {
            if (cells[j]) {
                if (cells[j].textContent.toLowerCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
        }
        
        rows[i].style.display = found ? '' : 'none';
    }
}

// Toggle select all checkboxes
function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const itemCheckboxes = document.querySelectorAll('input[name="selected_items[]"]');
    
    itemCheckboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
}

// Export inventory functionality
function exportInventory() {
    // TODO: Implement export functionality
    alert('Export functionality would be implemented here');
    
    // Example implementation:
    // const selectedItems = Array.from(document.querySelectorAll('input[name="selected_items[]"]:checked'))
    //     .map(checkbox => checkbox.value);
    
    // if (selectedItems.length === 0) {
    //     alert('Please select items to export');
    //     return;
    // }
    
    // // Make API call to export selected items
    // // window.location.href = `/admin/inventory/export?items=${selectedItems.join(',')}`;
}

// Open add product modal
function openAddProductModal() {
    // TODO: Implement modal opening for adding new product
    alert('Add Product modal would open here');
    
    // Example implementation:
    // const modal = document.getElementById('addProductModal');
    // if (modal) {
    //     modal.classList.remove('hidden');
    //     modal.classList.add('flex');
    // }
}

// Edit product functionality
function editProduct(productId) {
    // TODO: Implement edit functionality
    alert(`Editing product: ${productId}`);
    
    // Example implementation:
    // window.location.href = `/admin/inventory/${productId}/edit`;
    // OR
    // openEditModal(productId);
}

// Delete product functionality
function deleteProduct(productId) {
    if (confirm(`Are you sure you want to delete product ${productId}?`)) {
        // TODO: Implement delete functionality
        alert(`Product ${productId} would be deleted`);
        
        // Example implementation:
        // fetch(`/admin/inventory/${productId}`, {
        //     method: 'DELETE',
        //     headers: {
        //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        //         'Content-Type': 'application/json'
        //     }
        // })
        // .then(response => response.json())
        // .then(data => {
        //     if (data.success) {
        //         // Remove the row from the table
        //         const row = document.querySelector(`tr[data-product-id="${productId}"]`);
        //         if (row) {
        //             row.remove();
        //         }
        //         alert('Product deleted successfully');
        //     }
        // })
        // .catch(error => {
        //     console.error('Error:', error);
        //     alert('Failed to delete product');
        // });
    }
}

// Initialize inventory page functionality when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Add any initialization code here
    
    // Example: Initialize search input event listener
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', searchInventory);
    }
    
    // Example: Add event listeners for pagination buttons
    const prevButton = document.querySelector('.pagination button:first-of-type');
    const nextButton = document.querySelector('.pagination button:last-of-type');
    
    if (prevButton) {
        prevButton.addEventListener('click', function() {
            // TODO: Implement previous page functionality
            alert('Previous page functionality would be implemented here');
        });
    }
    
    if (nextButton) {
        nextButton.addEventListener('click', function() {
            // TODO: Implement next page functionality
            alert('Next page functionality would be implemented here');
        });
    }
    
    // Example: Show/hide table based on data availability
    // This would be typically handled by the backend
    // const hasData = false; // Set this based on actual data
    // const emptyState = document.querySelector('.empty-state');
    // const tableContainer = document.querySelector('.table-container');
    
    // if (hasData) {
    //     emptyState.classList.add('hidden');
    //     tableContainer.classList.remove('hidden');
    // } else {
    //     emptyState.classList.remove('hidden');
    //     tableContainer.classList.add('hidden');
    // }
});

// Utility function to show/hide empty state
function toggleEmptyState(hasData) {
    const emptyState = document.querySelector('.py-12.px-6.text-center');
    const tableContainer = document.querySelector('.overflow-x-auto.hidden');
    
    if (hasData) {
        // Hide empty state, show table
        if (emptyState) emptyState.classList.add('hidden');
        if (tableContainer) tableContainer.classList.remove('hidden');
    } else {
        // Show empty state, hide table
        if (emptyState) emptyState.classList.remove('hidden');
        if (tableContainer) tableContainer.classList.add('hidden');
    }
}

// Make functions available globally
window.searchInventory = searchInventory;
window.toggleSelectAll = toggleSelectAll;
window.exportInventory = exportInventory;
window.openAddProductModal = openAddProductModal;
window.editProduct = editProduct;
window.deleteProduct = deleteProduct;
window.toggleEmptyState = toggleEmptyState;