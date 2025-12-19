// public/js/admin-inventory.js

// DOM Elements
const addProductModal = document.getElementById('addProductModal');
const addCategoryModal = document.getElementById('addCategoryModal');
const addIngredientForm = document.getElementById('addIngredientForm');
const addCategoryForm = document.getElementById('addCategoryForm');
const searchInput = document.getElementById('searchInput');
const selectAllCheckbox = document.getElementById('selectAll');

// Modal Functions
function openAddProductModal() {
    addProductModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAddProductModal() {
    addProductModal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    addIngredientForm.reset();
    updateAvailabilityStatus(); // Reset availability status
}

function openAddCategoryModal() {
    // Close product modal if open
    closeAddProductModal();
    addCategoryModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAddCategoryModal() {
    addCategoryModal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    addCategoryForm.reset();
}

// Update availability status based on quantity
function updateAvailabilityStatus() {
    const quantityInput = document.getElementById('quantity');
    const availabilityStatus = document.getElementById('availabilityStatus');
    const quantity = parseInt(quantityInput.value) || 0;
    
    if (quantity === 0) {
        availabilityStatus.value = 'Out of Stock';
        availabilityStatus.className = 'w-full px-4 py-3 border-2 border-red-200 bg-gradient-to-r from-red-50 to-pink-50 text-red-800 rounded-xl focus:outline-none transition-all duration-300 font-semibold text-center shadow-inner';
    } else if (quantity <= 10) {
        availabilityStatus.value = 'Low Stock';
        availabilityStatus.className = 'w-full px-4 py-3 border-2 border-yellow-200 bg-gradient-to-r from-yellow-50 to-orange-50 text-yellow-800 rounded-xl focus:outline-none transition-all duration-300 font-semibold text-center shadow-inner';
    } else {
        availabilityStatus.value = 'In Stock';
        availabilityStatus.className = 'w-full px-4 py-3 border-2 border-green-200 bg-gradient-to-r from-green-50 to-emerald-50 text-green-800 rounded-xl focus:outline-none transition-all duration-300 font-semibold text-center shadow-inner';
    }
}

// Load categories from server
async function loadCategories() {
    try {
        const response = await fetch('/admin/categories/list', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                updateCategoryDropdown(data.categories);
            }
        }
    } catch (error) {
        console.error('Error loading categories:', error);
        showNotification('Failed to load categories', 'error');
    }
}

// Update category dropdown with data
function updateCategoryDropdown(categories) {
    const categorySelect = document.getElementById('productCategory');
    if (!categorySelect) return;
    
    // Store current value
    const currentValue = categorySelect.value;
    
    // Clear existing options except the first one (placeholder)
    categorySelect.innerHTML = '';
    
    // Add placeholder option
    const placeholderOption = document.createElement('option');
    placeholderOption.value = '';
    placeholderOption.textContent = 'Select a category';
    placeholderOption.disabled = true;
    placeholderOption.selected = true;
    categorySelect.appendChild(placeholderOption);
    
    // Add categories
    categories.forEach(category => {
        const option = document.createElement('option');
        option.value = category.id;
        option.textContent = category.ingredientCategoryName;
        categorySelect.appendChild(option);
    });
    
    // Restore selected value if it exists
    if (currentValue) {
        categorySelect.value = currentValue;
    }
}

// Handle Add Category form submission
if (addCategoryForm) {
    addCategoryForm.addEventListener('submit', async function(e) {
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
            const response = await fetch('/admin/categories/create', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Show success message
                showNotification('Category added successfully!', 'success');
                
                // Close modal
                closeAddCategoryModal();
                
                // Reset form
                this.reset();
                
                // Reload categories
                await loadCategories();
                
                // Reopen add product modal with new category selected
                setTimeout(() => {
                    openAddProductModal();
                    if (data.category) {
                        const categorySelect = document.getElementById('productCategory');
                        categorySelect.value = data.category.id;
                    }
                }, 300);
            } else {
                showNotification(data.message || 'Failed to add category', 'error');
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

// Handle Add Product form submission
if (addIngredientForm) {
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
            // In a real application, you would send this to your server
            // For now, we'll simulate success
            setTimeout(() => {
                showNotification('Product added successfully!', 'success');
                closeAddProductModal();
                // In a real app, you would refresh the inventory table here
                // refreshInventory();
                
                // Reset button
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }, 1500);
            
            // Actual fetch code would look like:
            /*
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
                showNotification('Product added successfully!', 'success');
                closeAddProductModal();
                refreshInventory();
            } else {
                showNotification(data.message || 'Failed to add product', 'error');
            }
            */
            
        } catch (error) {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });
}

// Search functionality
function searchInventory() {
    const searchTerm = searchInput.value.toLowerCase();
    const tableBody = document.querySelector('tbody');
    const rows = tableBody.querySelectorAll('tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
}

// Select all checkbox functionality
function toggleSelectAll() {
    const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
    const isChecked = selectAllCheckbox.checked;
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = isChecked;
    });
}

// Export inventory functionality
function exportInventory() {
    showNotification('Exporting inventory data...', 'info');
    // In a real application, this would trigger a file download
    // window.location.href = '/admin/inventory/export';
}

// Show notification function
function showNotification(message, type = 'success') {
    // Remove any existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => {
        notification.remove();
    });
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
        type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 
        type === 'error' ? 'bg-red-100 text-red-800 border border-red-200' :
        'bg-blue-100 text-blue-800 border border-blue-200'
    }`;
    notification.textContent = message;
    
    // Add icon based on type
    const icon = document.createElement('span');
    icon.className = 'mr-2';
    if (type === 'success') {
        icon.innerHTML = '✓';
    } else if (type === 'error') {
        icon.innerHTML = '✗';
    } else {
        icon.innerHTML = 'ⓘ';
    }
    notification.prepend(icon);
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
        notification.classList.add('translate-x-0');
    }, 10);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (addCategoryModal && !addCategoryModal.classList.contains('hidden')) {
        if (e.target === addCategoryModal) {
            closeAddCategoryModal();
        }
    }
    
    if (addProductModal && !addProductModal.classList.contains('hidden')) {
        if (e.target === addProductModal) {
            closeAddProductModal();
        }
    }
});

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        if (addCategoryModal && !addCategoryModal.classList.contains('hidden')) {
            closeAddCategoryModal();
        }
        
        if (addProductModal && !addProductModal.classList.contains('hidden')) {
            closeAddProductModal();
        }
    }
});

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Load categories
    loadCategories();
    
    // Set up event listeners
    if (searchInput) {
        searchInput.addEventListener('input', searchInventory);
    }
    
    // Initialize availability status
    updateAvailabilityStatus();
    
    // Watch quantity input for changes
    const quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        quantityInput.addEventListener('input', updateAvailabilityStatus);
    }
});

// Refresh inventory table (for future use)
function refreshInventory() {
    // This function would fetch updated inventory data from the server
    // and update the table
    showNotification('Refreshing inventory...', 'info');
    // fetch('/admin/inventory/data')
    //     .then(response => response.json())
    //     .then(data => {
    //         // Update table with new data
    //     });
}

// Load additional inventory functionality
const script = document.createElement('script');
script.src = "{{ asset('js/admin/admin-inventory-actions.js') }}";
document.body.appendChild(script);