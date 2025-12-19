// Modal Functions
function openAddProductModal() {
    const modal = document.getElementById('addProductModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex', 'animate-fadeIn');
    document.body.style.overflow = 'hidden';
    
    // Load existing categories
    loadCategories();
    // Initialize availability status
    updateAvailabilityStatus();
    
    // Add pulsing animation to focus the first input
    setTimeout(() => {
        const productNameInput = document.getElementById('productName');
        if (productNameInput) {
            productNameInput.focus();
            productNameInput.classList.add('ring-2', 'ring-amber-300', 'animate-pulse');
            setTimeout(() => {
                productNameInput.classList.remove('animate-pulse');
            }, 1000);
        }
    }, 100);
}

function closeAddProductModal() {
    const modal = document.getElementById('addProductModal');
    modal.classList.add('animate-fadeOut');
    
    setTimeout(() => {
        modal.classList.remove('flex', 'animate-fadeIn', 'animate-fadeOut');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        resetAddProductForm();
    }, 300);
}

function resetAddProductForm() {
    document.getElementById('addIngredientForm').reset();
    updateAvailabilityStatus();
    
    // Remove any animation classes
    const inputs = document.querySelectorAll('#addProductModal input, #addProductModal select');
    inputs.forEach(input => {
        input.classList.remove('ring-2', 'ring-amber-300', 'animate-pulse');
    });
}

function updateAvailabilityStatus() {
    const quantityInput = document.getElementById('quantity');
    const availabilityInput = document.getElementById('availabilityStatus');
    
    if (!quantityInput || !availabilityInput) return;
    
    const quantity = parseInt(quantityInput.value) || 0;
    let status = '';
    let bgColor = '';
    let borderColor = '';
    
    if (quantity === 0) {
        status = '❌ No Stock';
        bgColor = 'bg-gradient-to-r from-red-50 to-red-100';
        borderColor = 'border-red-300';
        availabilityInput.style.color = '#DC2626'; // Red-600
    } else if (quantity >= 1 && quantity <= 9) {
        status = '⚠️ Low Stock';
        bgColor = 'bg-gradient-to-r from-amber-50 to-orange-100';
        borderColor = 'border-amber-300';
        availabilityInput.style.color = '#F59E0B'; // Amber-500
    } else if (quantity >= 10) {
        status = '✅ In Stock';
        bgColor = 'bg-gradient-to-r from-emerald-50 to-green-100';
        borderColor = 'border-emerald-300';
        availabilityInput.style.color = '#10B981'; // Emerald-500
    }
    
    availabilityInput.value = status;
    
    // Update styling
    availabilityInput.className = `w-full px-4 py-3 border-2 ${borderColor} ${bgColor} text-gray-800 rounded-xl focus:outline-none transition-all duration-300 font-semibold text-center shadow-inner`;
    
    // Add subtle animation when status changes
    availabilityInput.classList.add('animate-pulse');
    setTimeout(() => {
        availabilityInput.classList.remove('animate-pulse');
    }, 500);
}

function openAddCategoryModal() {
    closeAddProductModal();
    
    setTimeout(() => {
        const modal = document.getElementById('addCategoryModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex', 'animate-fadeIn');
        
        // Focus the category input
        setTimeout(() => {
            const categoryInput = document.getElementById('newCategoryName');
            if (categoryInput) {
                categoryInput.focus();
                categoryInput.classList.add('ring-2', 'ring-amber-300', 'animate-pulse');
                setTimeout(() => {
                    categoryInput.classList.remove('animate-pulse');
                }, 1000);
            }
        }, 100);
    }, 300);
}

function closeAddCategoryModal() {
    const modal = document.getElementById('addCategoryModal');
    modal.classList.add('animate-fadeOut');
    
    setTimeout(() => {
        modal.classList.remove('flex', 'animate-fadeIn', 'animate-fadeOut');
        modal.classList.add('hidden');
        document.getElementById('newCategoryName').value = '';
    }, 300);
}

function loadCategories() {
    // This function would typically fetch categories from the server
    // For now, we'll use a placeholder or load from localStorage
    const categorySelect = document.getElementById('productCategory');
    
    // Clear existing options except the first one
    while (categorySelect.options.length > 1) {
        categorySelect.remove(1);
    }
    
    // Add categories to select
    categories.forEach(category => {
        const option = document.createElement('option');
        option.value = category.toLowerCase().replace(/[^a-z0-9]/g, '_');
        option.textContent = category;
        option.className = 'text-gray-800';
        categorySelect.appendChild(option);
    });
}

function saveCategory(categoryName) {
    // Get existing categories
    let categories = JSON.parse(localStorage.getItem('productCategories')) || [
        'Vegetable',
        'Meat',
        'Coffee Base',
        'Syrup/Flavoring'
    ];
    
    // Add new category if it doesn't exist
    if (!categories.includes(categoryName)) {
        categories.push(categoryName);
        localStorage.setItem('productCategories', JSON.stringify(categories));
    }
    
    // Refresh category dropdown
    loadCategories();
    
    // Select the new category
    const categorySelect = document.getElementById('productCategory');
    const newCategoryValue = categoryName.toLowerCase().replace(/[^a-z0-9]/g, '_');
    
    for (let i = 0; i < categorySelect.options.length; i++) {
        if (categorySelect.options[i].value === newCategoryValue) {
            categorySelect.selectedIndex = i;
            // Add animation to the selected option
            categorySelect.classList.add('ring-2', 'ring-amber-300');
            setTimeout(() => {
                categorySelect.classList.remove('ring-2', 'ring-amber-300');
            }, 1500);
            break;
        }
    }
    
    // Reopen the product modal after adding category
    setTimeout(() => {
        openAddProductModal();
    }, 300);
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes fadeOut {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-20px); }
    }
    
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out forwards;
    }
    
    .animate-fadeOut {
        animation: fadeOut 0.3s ease-in forwards;
    }
    
    /* Custom scrollbar for select */
    select::-webkit-scrollbar {
        width: 8px;
    }
    
    select::-webkit-scrollbar-track {
        background: #FEF3C7;
        border-radius: 4px;
    }
    
    select::-webkit-scrollbar-thumb {
        background: #F59E0B;
        border-radius: 4px;
    }
    
    select::-webkit-scrollbar-thumb:hover {
        background: #D97706;
    }
`;
document.head.appendChild(style);

// Handle form submissions
document.addEventListener('DOMContentLoaded', function() {
    // Add Product Form
    const addIngredientForm = document.getElementById('addIngredientForm');
    
    if (addIngredientForm) {
        addIngredientForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const quantity = parseInt(document.getElementById('quantity').value) || 0;
            
            // Automatically determine availability based on quantity
            let availability = '';
            if (quantity === 0) {
                availability = 'no_stock';
            } else if (quantity >= 1 && quantity <= 9) {
                availability = 'low_stock';
            } else if (quantity >= 10) {
                availability = 'in_stock';
            }
            
            const data = {
                name: formData.get('name'),
                quantity: quantity,
                category: formData.get('category'),
                availability: availability,
                _token: '{{ csrf_token() }}'
            };
            
            // Validate data
            if (!data.name || data.name.trim() === '') {
                alertWithStyle('⚠️ Please enter a product name', 'warning');
                document.getElementById('productName').classList.add('ring-2', 'ring-red-500');
                return;
            }
            
            if (isNaN(data.quantity) || data.quantity < 0) {
                alertWithStyle('⚠️ Please enter a valid quantity', 'warning');
                document.getElementById('quantity').classList.add('ring-2', 'ring-red-500');
                return;
            }
            
            if (!data.category) {
                alertWithStyle('⚠️ Please select a category', 'warning');
                document.getElementById('productCategory').classList.add('ring-2', 'ring-red-500');
                return;
            }
            
            // Add loading state to submit button
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = `
                <svg class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Adding...
            `;
            submitBtn.disabled = true;
            
            // Simulate API call (replace with actual fetch)
            setTimeout(() => {
                // Send AJAX request to server
                fetch('{{ route("admin.inventory.create") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': data._token
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    
                    if (data.success) {
                        alertWithStyle('✅ Product added successfully!', 'success');
                        closeAddProductModal();
                        // Refresh the page or update table
                        location.reload();
                    } else {
                        alertWithStyle('❌ Error: ' + (data.message || 'Failed to add product'), 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    alertWithStyle('❌ An error occurred. Please try again.', 'error');
                });
            }, 1000);
        });
    }
    
    // Add Category Form
    const addCategoryForm = document.getElementById('addCategoryForm');
    
    if (addCategoryForm) {
        addCategoryForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const categoryName = document.getElementById('newCategoryName').value.trim();
            
            if (!categoryName) {
                alertWithStyle('⚠️ Please enter a category name', 'warning');
                document.getElementById('newCategoryName').classList.add('ring-2', 'ring-red-500');
                return;
            }
            
            // Add loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = `
                <svg class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Adding...
            `;
            submitBtn.disabled = true;
            
            setTimeout(() => {
                // Save category to localStorage (or send to server)
                saveCategory(categoryName);
                
                // Reset button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                
                // Show success message
                alertWithStyle(`✅ Category "${categoryName}" added successfully!`, 'success');
            }, 800);
        });
    }
    
    // Close modals when clicking outside
    window.addEventListener('click', function(e) {
        const productModal = document.getElementById('addProductModal');
        const categoryModal = document.getElementById('addCategoryModal');
        
        if (e.target === productModal) {
            closeAddProductModal();
        }
        if (e.target === categoryModal) {
            closeAddCategoryModal();
        }
    });
    
    // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAddProductModal();
            closeAddCategoryModal();
        }
    });
    
    // Initialize quantity field listeners
    const quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        quantityInput.addEventListener('input', updateAvailabilityStatus);
        quantityInput.addEventListener('change', updateAvailabilityStatus);
    }
    
    // Add input focus effects
    const inputs = document.querySelectorAll('#addProductModal input, #addProductModal select, #addCategoryModal input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.classList.add('ring-2', 'ring-amber-300');
            this.parentElement.classList.add('transform', 'scale-102');
        });
        
        input.addEventListener('blur', function() {
            this.classList.remove('ring-2', 'ring-amber-300');
            this.parentElement.classList.remove('transform', 'scale-102');
        });
    });
});

// Custom styled alert function
function alertWithStyle(message, type) {
    const alertDiv = document.createElement('div');
    const colors = {
        success: 'bg-gradient-to-r from-emerald-500 to-green-500',
        error: 'bg-gradient-to-r from-red-500 to-rose-500',
        warning: 'bg-gradient-to-r from-amber-500 to-orange-500'
    };
    
    alertDiv.className = `fixed top-4 right-4 text-white px-6 py-3 rounded-xl shadow-2xl z-50 ${colors[type] || colors.warning} animate-fadeIn`;
    alertDiv.innerHTML = `
        <div class="flex items-center">
            <span class="font-semibold">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-amber-100">
                ✕
            </button>
        </div>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        if (alertDiv.parentElement) {
            alertDiv.classList.add('animate-fadeOut');
            setTimeout(() => {
                if (alertDiv.parentElement) {
                    alertDiv.parentElement.removeChild(alertDiv);
                }
            }, 300);
        }
    }, 4000);
}

// Make functions available globally
window.openAddProductModal = openAddProductModal;
window.closeAddProductModal = closeAddProductModal;
window.openAddCategoryModal = openAddCategoryModal;
window.closeAddCategoryModal = closeAddCategoryModal;
window.updateAvailabilityStatus = updateAvailabilityStatus;