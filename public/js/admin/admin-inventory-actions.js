// public/js/admin/admin-inventory-actions.js

// Handle Add Product form submission
function setupAddIngredientForm() {
    const addIngredientForm = document.getElementById('addIngredientForm');
    if (!addIngredientForm) return;

    addIngredientForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Adding...
        `;
        
        try {
            const response = await fetch('/admin/inventory/create', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                showNotification('Ingredient added successfully!', 'success');
                closeAddProductModal();
                
                // Refresh the inventory table
                await refreshInventoryTable();
            } else {
                showNotification(data.message || 'Failed to add ingredient', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'error');
        } finally {
            // Restore button state
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });
}

// Refresh inventory table with data from server
async function refreshInventoryTable() {
    try {
        const response = await fetch('/admin/inventory/list', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success && data.ingredients) {
                updateInventoryTable(data.ingredients);
            }
        }
    } catch (error) {
        console.error('Error refreshing inventory:', error);
        showNotification('Failed to refresh inventory', 'error');
    }
}

// Update inventory table with data
function updateInventoryTable(ingredients) {
    const tableContainer = document.querySelector('.overflow-x-auto');
    const emptyState = document.querySelector('.py-12.px-6.text-center');
    const tableBody = document.querySelector('tbody');
    
    if (!tableBody || !tableContainer || !emptyState) return;
    
    if (ingredients.length === 0) {
        // Show empty state
        tableContainer.classList.add('hidden');
        emptyState.classList.remove('hidden');
        return;
    }
    
    // Hide empty state and show table
    emptyState.classList.add('hidden');
    tableContainer.classList.remove('hidden');
    
    // Clear existing rows
    tableBody.innerHTML = '';
    
    // Add new rows
    ingredients.forEach(ingredient => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50 transition-colors duration-200';
        
        // Determine availability badge classes
        let availabilityClass = '';
        let availabilityText = ingredient.ingredientAvailability;
        
        switch(ingredient.ingredientAvailability) {
            case 'Available':
                availabilityClass = 'bg-green-100 text-green-800';
                availabilityText = 'Available';
                break;
            case 'Low Stock':
                availabilityClass = 'bg-yellow-100 text-yellow-800';
                availabilityText = 'Low Stock';
                break;
            case 'Out of Stock':
                availabilityClass = 'bg-red-100 text-red-800';
                availabilityText = 'Out of Stock';
                break;
        }
        
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex justify-center">
                    <input type="checkbox" 
                           class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded">
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                ${ingredient.id}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-900">
                ${escapeHtml(ingredient.ingredientName)}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                ${escapeHtml(ingredient.ingredientCategory)}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                ${ingredient.ingredientQuantity}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ${availabilityClass}">
                    ${availabilityText}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                <div class="flex justify-center space-x-3">
                    <button onclick="editIngredient(${ingredient.id})" 
                            class="text-amber-600 hover:text-amber-900 transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </button>
                    <button onclick="deleteIngredient(${ingredient.id})" 
                            class="text-red-600 hover:text-red-900 transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </td>
        `;
        
        tableBody.appendChild(row);
    });
}

// Export inventory functionality
async function exportInventory() {
    try {
        showNotification('Exporting inventory data...', 'info');
        
        const response = await fetch('/admin/inventory/export');
        
        if (response.ok) {
            // Create blob and download
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `inventory_${new Date().toISOString().split('T')[0]}.csv`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
            
            showNotification('Inventory exported successfully!', 'success');
        } else {
            showNotification('Failed to export inventory', 'error');
        }
    } catch (error) {
        console.error('Export error:', error);
        showNotification('Failed to export inventory', 'error');
    }
}

// Search inventory functionality
async function searchInventory() {
    const searchTerm = searchInput.value;
    
    if (!searchTerm.trim()) {
        // If search is empty, load all ingredients
        await refreshInventoryTable();
        return;
    }
    
    try {
        const response = await fetch(`/admin/inventory/search?search=${encodeURIComponent(searchTerm)}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success && data.ingredients) {
                updateInventoryTable(data.ingredients);
            }
        }
    } catch (error) {
        console.error('Search error:', error);
        showNotification('Failed to search inventory', 'error');
    }
}

// Helper function to escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Edit ingredient (placeholder for future implementation)
function editIngredient(id) {
    showNotification('Edit functionality coming soon!', 'info');
}

// Delete ingredient (placeholder for future implementation)
function deleteIngredient(id) {
    if (confirm('Are you sure you want to delete this ingredient?')) {
        showNotification('Delete functionality coming soon!', 'info');
    }
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Set up form submission
    setupAddIngredientForm();
    
    // Load initial inventory data
    refreshInventoryTable();
    
    // Update export button functionality
    const exportBtn = document.querySelector('button[onclick="exportInventory()"]');
    if (exportBtn) {
        exportBtn.onclick = exportInventory;
    }
    
    // Update search functionality
    if (searchInput) {
        searchInput.addEventListener('input', debounce(searchInventory, 300));
    }
});

// Debounce function for search
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}