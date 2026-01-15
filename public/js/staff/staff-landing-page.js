// Order data will be loaded from the server
let allOrders = [];

// Store checked items state
let checkedItemsState = new Map();

// Store void state for orders
let voidStateOrders = new Map();

// Store current void order data
let currentVoidOrderData = null;

// Store current order data for adding products
let currentAddProductOrder = null;
let allProducts = [];
let filteredProducts = [];
let selectedProducts = new Map(); // Map of productID -> quantity

// Helper function to format currency
function formatCurrency(amount) {
    return `₱${parseFloat(amount).toFixed(2)}`;
}

// Helper function to calculate order totals WITHOUT TAX
function calculateOrderTotals(order) {
    const subtotal = order.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const tax = subtotal * order.taxRate;
    const total = subtotal; // REMOVED TAX: total is now just subtotal without tax
    
    // Calculate vatable sales (89.3% of total) and tax (10.7% of total)
    const vatableSales = total * 0.893; // 100% - 10.7% = 89.3%
    const tax10_7 = total * 0.107; // 10.7% of total
    
    return {
        subtotal: subtotal,
        tax: tax,
        total: total, // This is now the subtotal (no tax included)
        vatableSales: vatableSales,
        tax10_7: tax10_7
    };
}

// Helper function to calculate all orders statistics
function calculateRevenueStatistics() {
    let totalRevenue = 0;
    let totalTax = 0;
    
    allOrders.forEach(order => {
        const totals = calculateOrderTotals(order);
        totalRevenue += totals.total; // This is now subtotal without tax
        totalTax += totals.tax;
    });
    
    const avgOrderValue = allOrders.length > 0 ? totalRevenue / allOrders.length : 0;
    
    return {
        totalRevenue: totalRevenue,
        totalTax: totalTax,
        avgOrderValue: avgOrderValue
    };
}

// Helper function to calculate order totals after removing items
function calculateOrderTotalsAfterRemoval(order, itemsToRemoveIndices) {
    const remainingItems = order.items.filter((item, index) => !itemsToRemoveIndices.includes(index.toString()));
    const subtotal = remainingItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const tax = subtotal * order.taxRate;
    const total = subtotal; // REMOVED TAX: total is now just subtotal without tax
    
    // Calculate vatable sales (89.3% of total) and tax (10.7% of total)
    const vatableSales = total * 0.893; // 100% - 10.7% = 89.3%
    const tax10_7 = total * 0.107; // 10.7% of total
    
    return {
        subtotal: subtotal,
        tax: tax,
        total: total, // This is now the subtotal (no tax included)
        vatableSales: vatableSales,
        tax10_7: tax10_7,
        remainingItems: remainingItems
    };
}

// Helper function for API calls with better error handling
async function apiCall(url, method = 'GET', data = null) {
    try {
        const options = {
            method: method,
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            credentials: 'same-origin'
        };
        
        if (data) {
            options.body = JSON.stringify(data);
        }
        
        console.log(`Making API call to ${url}`, data);
        
        const response = await fetch(url, options);
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error(`API error response:`, errorText);
            throw new Error(`HTTP ${response.status}: ${errorText || response.statusText}`);
        }
        
        const responseData = await response.json();
        console.log(`API response from ${url}:`, responseData);
        return responseData;
    } catch (error) {
        console.error(`API call failed to ${url}:`, error);
        throw error;
    }
}

// Helper function to get unique key for an item
function getItemKey(orderId, paymentNumber, itemIndex) {
    return `${orderId}-${paymentNumber}-${itemIndex}`;
}

// Helper function to get unique key for an order
function getOrderKey(orderId, paymentNumber) {
    return `${orderId}-${paymentNumber}`;
}

// Helper function to update checked items count
function updateCheckedItemsCount() {
    // This function could be used to update a counter if needed
    const checkedCount = Array.from(checkedItemsState.values()).filter(v => v).length;
    console.log(`Currently checked items: ${checkedCount}`);
}

// Check if an order is in void state
function isOrderInVoidState(orderId, paymentNumber) {
    const orderKey = getOrderKey(orderId, paymentNumber);
    return voidStateOrders.get(orderKey) || false;
}

// Set void state for an order
function setOrderVoidState(orderId, paymentNumber, isVoidState) {
    const orderKey = getOrderKey(orderId, paymentNumber);
    voidStateOrders.set(orderKey, isVoidState);
}

// Remove items from an order in the local data
function removeItemsFromOrder(orderId, paymentNumber, itemIndices) {
    const orderIndex = allOrders.findIndex(order => 
        order.id === orderId && order.paymentNumber === paymentNumber
    );
    
    if (orderIndex === -1) return;
    
    // Filter out the items to remove
    allOrders[orderIndex].items = allOrders[orderIndex].items.filter((item, index) => 
        !itemIndices.includes(index.toString())
    );
    
    // If all items are removed, remove the entire order
    if (allOrders[orderIndex].items.length === 0) {
        allOrders.splice(orderIndex, 1);
        return true; // Order was completely removed
    }
    
    return false; // Order still has items
}

// SAFE SELECTOR FUNCTION - Fix for spaces in attribute values
function getSafeSelector(attribute, value) {
    // Escape any special characters and handle spaces
    const escapedValue = CSS.escape(value.trim());
    return `[${attribute}="${escapedValue}"]`;
}

// Helper to get all checkboxes for an order
function getCheckboxesForOrder(orderId, paymentNumber) {
    // Trim the values to remove spaces
    const cleanOrderId = orderId.trim();
    const cleanPaymentNumber = paymentNumber.toString().trim();
    
    // Use the safe selector
    const selector = `.item-checkbox${getSafeSelector('data-order-id', cleanOrderId)}${getSafeSelector('data-payment-number', cleanPaymentNumber)}`;
    console.log(`Looking for checkboxes with selector: ${selector}`);
    
    const checkboxes = document.querySelectorAll(selector);
    console.log(`Found ${checkboxes.length} checkboxes for order ${orderId}, payment ${paymentNumber}`);
    
    return checkboxes;
}

// Helper to get select all checkbox for an order
function getSelectAllCheckboxForOrder(orderId, paymentNumber) {
    // Trim the values to remove spaces
    const cleanOrderId = orderId.trim();
    const cleanPaymentNumber = paymentNumber.toString().trim();
    
    // Use the safe selector
    return document.querySelector(
        `.select-all-checkbox${getSafeSelector('data-order-id', cleanOrderId)}${getSafeSelector('data-payment-number', cleanPaymentNumber)}`
    );
}

// Function to navigate to Order Tracker page
function navigateToOrderTracker() {
    // Show status message
    showStatusMessage('Navigating to Order Tracker...', 'bg-green-600');
    
    // Add a small delay for better UX
    setTimeout(() => {
        window.location.href = '/staff/order-tracker';
    }, 500);
}

// Pagination variables
const ordersPerPage = 4;
let currentPage = 1;
let totalPages = 1;

// DOM Elements
const ordersContainer = document.getElementById('orders-container');
const prevPageBtn = document.getElementById('prev-page-btn');
const nextPageBtn = document.getElementById('next-page-btn');
const currentPageElement = document.getElementById('current-page');
const totalPagesElement = document.getElementById('total-pages');
const orderCountElement = document.getElementById('order-count');
const orderTrackerBtn = document.getElementById('order-tracker-btn');
const statusMessage = document.getElementById('status-message');

// Footer elements
const termsBtn = document.getElementById('terms-btn');
const privacyBtn = document.getElementById('privacy-btn');
const termsModal = document.getElementById('terms-modal');
const privacyModal = document.getElementById('privacy-modal');
const closeTerms = document.getElementById('close-terms');
const closePrivacy = document.getElementById('close-privacy');
const acceptTerms = document.getElementById('accept-terms');
const acceptPrivacy = document.getElementById('accept-privacy');

// Confirm Payment Modal Elements
const confirmPaymentModal = document.getElementById('confirm-payment-modal');
const closeConfirmPayment = document.getElementById('close-confirm-payment');
const cancelPayment = document.getElementById('cancel-payment');
const confirmPaymentBtn = document.getElementById('confirm-payment-btn');
const amountPaidInput = document.getElementById('amount-paid');
const referenceInput = document.getElementById('modal-reference-input');

// Admin Password Modal Elements
const adminPasswordModal = document.getElementById('admin-password-modal');
const closeAdminPassword = document.getElementById('close-admin-password');
const cancelAdminAuth = document.getElementById('cancel-admin-auth');
const confirmAdminAuth = document.getElementById('confirm-admin-auth');
const adminPasswordInput = document.getElementById('admin-password-input');
const togglePasswordVisibility = document.getElementById('toggle-password-visibility');
const adminPasswordError = document.getElementById('admin-password-error');
const adminErrorElement = document.getElementById('admin-error-message');
const adminModalOrderId = document.getElementById('admin-modal-order-id');
const adminModalPaymentNumber = document.getElementById('admin-modal-payment-number');
const adminModalProductCount = document.getElementById('admin-modal-product-count');

// Add Product Modal Elements
const addProductModal = document.getElementById('add-product-modal');
const closeAddProduct = document.getElementById('close-add-product');
const cancelAddProduct = document.getElementById('cancel-add-product');
const confirmAddProduct = document.getElementById('confirm-add-product');
const productSearchInput = document.getElementById('product-search-input');
const categoryFilter = document.getElementById('category-filter');
const productsContainer = document.getElementById('products-container');
const selectedProductsList = document.getElementById('selected-products-list');
const selectedCountElement = document.getElementById('selected-count');

// Initialize
loadOrders();
loadProducts();

// Function to load orders from server
async function loadOrders() {
    try {
        const data = await apiCall('/api/staff/orders');
        
        // Transform the data to match the expected format
        allOrders = transformOrderData(data);
        
        // Update pagination
        totalPages = Math.ceil(allOrders.length / ordersPerPage);
        updatePagination();
        
        // Render orders
        renderOrders();
        
        // Update order count
        orderCountElement.textContent = allOrders.length;
        
        // Update statistics
        updateStatistics();
        updateRevenueStats();
    } catch (error) {
        console.error('Error loading orders:', error);
        showStatusMessage('Error loading orders: ' + error.message, 'bg-red-600');
    }
}

// Function to load products from menu_products table
async function loadProducts() {
    try {
        const response = await apiCall('/api/staff/products');
        allProducts = response;
        filteredProducts = [...allProducts];
        updateCategoryFilter();
        renderProducts();
    } catch (error) {
        console.error('Error loading products:', error);
        showStatusMessage('Error loading products: ' + error.message, 'bg-red-600');
    }
}

// Function to update category filter dropdown
function updateCategoryFilter() {
    const categories = [...new Set(allProducts.map(p => p.menuCategory))];
    categories.sort();
    
    categoryFilter.innerHTML = '<option value="">All Categories</option>';
    categories.forEach(category => {
        const option = document.createElement('option');
        option.value = category;
        option.textContent = category;
        categoryFilter.appendChild(option);
    });
}

// Function to render products in the modal
function renderProducts() {
    if (filteredProducts.length === 0) {
        productsContainer.innerHTML = `
            <div class="col-span-full text-center py-8">
                <i class="fas fa-box-open text-2xl text-gray-400 mb-3"></i>
                <p class="text-gray-400">No products found</p>
                <p class="text-gray-500 text-sm mt-1">Try a different search term</p>
            </div>
        `;
        return;
    }
    
    productsContainer.innerHTML = filteredProducts.map(product => {
        const isSelected = selectedProducts.has(product.menuID);
        const statusClass = product.menuStatus === 'Available' ? 'status-available' : 
                          product.menuStatus === 'Out of Stock' ? 'status-out-of-stock' : 
                          'status-discontinued';
        
        return `
            <div class="product-card ${isSelected ? 'selected' : ''}" 
                 data-product-id="${product.menuID}"
                 data-product-name="${product.menuName}"
                 data-product-price="${product.menuPrice}"
                 data-product-status="${product.menuStatus}">
                <div class="modal-product-name">${product.menuName}</div>
                <div class="modal-product-category">${product.menuCategory} / ${product.menuSubcategory}</div>
                <div class="flex justify-between items-center">
                    <span class="modal-product-price">${formatCurrency(product.menuPrice)}</span>
                    <span class="modal-product-status-badge ${statusClass}">
                        ${product.menuStatus}
                    </span>
                </div>
            </div>
        `;
    }).join('');
    
    // Add click event listeners to product cards
    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const productName = this.getAttribute('data-product-name');
            const productPrice = parseFloat(this.getAttribute('data-product-price'));
            const productStatus = this.getAttribute('data-product-status');
            
            // Only allow selection of available products
            if (productStatus !== 'Available') {
                showStatusMessage('Cannot select unavailable product', 'bg-yellow-600');
                return;
            }
            
            if (!selectedProducts.has(productId)) {
                selectedProducts.set(productId, {
                    name: productName,
                    price: productPrice,
                    quantity: 1,
                    id: productId
                });
                
                // Update UI
                this.classList.add('selected');
                updateSelectedProductsList();
                updateSelectedCount();
            }
        });
    });
}

// Function to update selected products list
function updateSelectedProductsList() {
    if (selectedProducts.size === 0) {
        selectedProductsList.innerHTML = `
            <p class="text-gray-500 text-center py-2">No products selected yet</p>
        `;
        return;
    }
    
    selectedProductsList.innerHTML = Array.from(selectedProducts.entries()).map(([id, product]) => `
        <div class="selected-product-item">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <span class="selected-product-name">${product.name}</span>
                    <span class="selected-product-quantity">×${product.quantity}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-amber-300 font-medium">${formatCurrency(product.price * product.quantity)}</span>
                    <button class="remove-product-btn" data-product-id="${id}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="flex items-center mt-2">
                <button class="quantity-btn minus bg-gray-700 hover:bg-gray-600 text-white px-2 py-1 rounded-l text-sm" data-product-id="${id}">
                    <i class="fas fa-minus"></i>
                </button>
                <input type="number" 
                       class="quantity-input w-12 bg-gray-800 text-white text-center py-1 border-y border-gray-700"
                       value="${product.quantity}"
                       min="1"
                       data-product-id="${id}">
                <button class="quantity-btn plus bg-gray-700 hover:bg-gray-600 text-white px-2 py-1 rounded-r text-sm" data-product-id="${id}">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
    `).join('');
    
    // Add event listeners to quantity controls
    document.querySelectorAll('.quantity-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const product = selectedProducts.get(productId);
            
            if (this.classList.contains('minus')) {
                if (product.quantity > 1) {
                    product.quantity--;
                }
            } else if (this.classList.contains('plus')) {
                product.quantity++;
            }
            
            updateSelectedProductsList();
            updateSelectedCount();
        });
    });
    
    // Add event listeners to quantity inputs
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const productId = this.getAttribute('data-product-id');
            const product = selectedProducts.get(productId);
            const newQuantity = parseInt(this.value) || 1;
            
            product.quantity = Math.max(1, newQuantity);
            updateSelectedProductsList();
            updateSelectedCount();
        });
    });
    
    // Add event listeners to remove buttons
    document.querySelectorAll('.remove-product-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            selectedProducts.delete(productId);
            
            // Update product card selection
            const productCard = document.querySelector(`.product-card[data-product-id="${productId}"]`);
            if (productCard) {
                productCard.classList.remove('selected');
            }
            
            updateSelectedProductsList();
            updateSelectedCount();
        });
    });
}

// Function to update selected count
function updateSelectedCount() {
    selectedCountElement.textContent = selectedProducts.size;
}

// Function to open Add Product modal
function openAddProductModal(orderId, paymentNumber) {
    currentAddProductOrder = { orderId, paymentNumber };
    
    // Set modal header info
    document.getElementById('add-modal-order-id').textContent = orderId;
    document.getElementById('add-modal-payment-number').textContent = paymentNumber;
    
    // Reset selections
    selectedProducts.clear();
    productSearchInput.value = '';
    categoryFilter.value = '';
    filteredProducts = [...allProducts];
    
    // Render products and selected list
    renderProducts();
    updateSelectedProductsList();
    updateSelectedCount();
    
    // Open modal
    addProductModal.classList.remove('hidden');
    addProductModal.classList.add('flex');
    document.body.style.overflow = 'hidden';
    
    // Focus on search input
    setTimeout(() => {
        productSearchInput.focus();
    }, 100);
}

// Function to close Add Product modal
function closeAddProductModal() {
    addProductModal.classList.remove('flex');
    addProductModal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    
    currentAddProductOrder = null;
    selectedProducts.clear();
}

// Function to filter products
function filterProducts() {
    const searchTerm = productSearchInput.value.toLowerCase();
    const selectedCategory = categoryFilter.value;
    
    filteredProducts = allProducts.filter(product => {
        const matchesSearch = product.menuName.toLowerCase().includes(searchTerm) ||
                            product.menuCategory.toLowerCase().includes(searchTerm) ||
                            product.menuSubcategory.toLowerCase().includes(searchTerm);
        
        const matchesCategory = !selectedCategory || product.menuCategory === selectedCategory;
        
        return matchesSearch && matchesCategory;
    });
    
    renderProducts();
}

// Function to transform database data to frontend format
function transformOrderData(orders) {
    // Group by orderID and paymentNumber to combine multiple items into single orders
    const groupedOrders = {};
    
    orders.forEach(order => {
        // Create a unique key using orderID and paymentNumber
        const orderKey = `${order.orderID}-${order.paymentNumber}`;
        
        if (!groupedOrders[orderKey]) {
            groupedOrders[orderKey] = {
                id: order.orderID,
                paymentNumber: order.paymentNumber.toString().trim(),
                time: calculateOrderTime(order.orderCreateDateAndTime),
                type: order.orderType === 'dine-in' ? 'Dine in' : 'Takeout',
                typeColor: order.orderType === 'dine-in' ? 'bg-blue-900 text-blue-200' : 'bg-purple-900 text-purple-200',
                payment: order.orderPaymentMethod === 'cash' ? 'Cash' : 'Electronic',
                items: [],
                taxRate: 0.12,
                status: order.orderProductStatus || 'For Payment',
                notes: null // Initialize notes as null - will be set only once
            };
        }
        
        // Set notes ONLY on the first item (to avoid duplication)
        if (groupedOrders[orderKey].items.length === 0 && order.orderNotes) {
            groupedOrders[orderKey].notes = order.orderNotes.trim();
        }
        
        // Add item to the order with its individual notes
        groupedOrders[orderKey].items.push({
            name: order.orderProductName,
            price: parseFloat(order.orderTotalProductPrice) / order.orderQuantity,
            quantity: order.orderQuantity,
            totalPrice: parseFloat(order.orderTotalProductPrice),
            status: order.orderProductStatus || 'active',
            notes: order.orderNotes || null // Store item-specific notes
        });
    });
    
    // After grouping, process notes at item level only
    Object.values(groupedOrders).forEach(order => {
        // Clean up item notes for display
        order.items.forEach(item => {
            // Only keep item-level notes if they exist
            if (item.notes && item.notes.trim() !== '') {
                item.displayNotes = item.notes.trim();
            } else {
                item.displayNotes = null;
            }
        });
    });
    
    // Convert to array
    return Object.values(groupedOrders);
}

// Calculate time since order was created
function calculateOrderTime(createDateTime) {
    const now = new Date();
    const orderTime = new Date(createDateTime);
    const diffMinutes = Math.floor((now - orderTime) / (1000 * 60));
    
    if (diffMinutes < 60) {
        return `${diffMinutes}m`;
    } else {
        const hours = Math.floor(diffMinutes / 60);
        return `${hours}h`;
    }
}

// Function to render products in the modal
function renderProductsInModal(orderId, paymentNumber, items, selectedItems) {
    const productsListContainer = document.getElementById('modal-products-list');
    
    if (!items || items.length === 0) {
        productsListContainer.innerHTML = `
            <div class="text-center text-gray-500 py-4">
                <i class="fas fa-shopping-basket mb-2"></i>
                <p>No products in this order</p>
            </div>
        `;
        document.getElementById('modal-total-items').textContent = '0';
        document.getElementById('modal-selected-items').textContent = '0';
        return;
    }
    
    let totalItems = 0;
    let selectedCount = 0;
    
    // Create product items - only show selected items
    const productsHTML = items.map((item, index) => {
        const isSelected = selectedItems.includes(index.toString());
        if (isSelected) {
            selectedCount++;
            totalItems += item.quantity;
            
            // Check if item has notes
            const hasNotes = item.displayNotes && item.displayNotes !== 'None';
            
            return `
                <div class="product-item">
                    <div class="flex flex-col">
                        <div class="flex items-center">
                            <span class="product-name">${item.name}</span>
                            <span class="product-quantity">×${item.quantity}</span>
                            <span class="product-status status-selected">
                                Selected
                            </span>
                        </div>
                    </div>
                    <span class="product-price">${formatCurrency(item.totalPrice)}</span>
                </div>
            `;
        }
        return ''; // Return empty string for non-selected items
    }).filter(html => html !== '').join(''); // Filter out empty strings
    
    // If no items are selected, show a message
    if (selectedCount === 0) {
        productsListContainer.innerHTML = `
            <div class="text-center text-gray-500 py-4">
                <i class="fas fa-info-circle mb-2"></i>
                <p>No products selected for payment</p>
                <p class="text-xs mt-1">Please select items in the order card first</p>
            </div>
        `;
    } else {
        productsListContainer.innerHTML = productsHTML;
    }
    
    document.getElementById('modal-total-items').textContent = totalItems;
    document.getElementById('modal-selected-items').textContent = selectedCount;
}

// Function to open confirm payment modal
function openConfirmPaymentModal(orderId, paymentNumber, orderType, paymentMethod, subtotal, tax, total, items, selectedItems) {
    // Set modal values
    document.getElementById('modal-order-id').textContent = orderId;
    document.getElementById('modal-payment-number').textContent = paymentNumber;
    document.getElementById('modal-order-type').textContent = orderType;
    document.getElementById('modal-payment-method').textContent = paymentMethod;
    
    // Display total WITHOUT tax
    document.getElementById('modal-total').textContent = formatCurrency(subtotal); // Use subtotal instead of total (which includes tax)
    
    // Calculate and display vatable sales and tax
    const vatableSales = subtotal * 0.893; // 89.3% of total
    const tax10_7 = subtotal * 0.107; // 10.7% of total
    
    document.getElementById('modal-vatable-sales').textContent = formatCurrency(vatableSales);
    document.getElementById('modal-tax').textContent = formatCurrency(tax10_7);
    
    // Render products in modal (only selected ones)
    renderProductsInModal(orderId, paymentNumber, items, selectedItems);
    
    // Clear reference number input
    referenceInput.value = '';
    
    // Reset amount paid
    amountPaidInput.value = '';
    document.getElementById('change-calculation').classList.add('hidden');
    confirmPaymentBtn.disabled = true;
    
    // Store order data for later use
    confirmPaymentBtn.dataset.orderId = orderId;
    confirmPaymentBtn.dataset.paymentNumber = paymentNumber;
    confirmPaymentBtn.dataset.totalAmount = subtotal; // Store subtotal (without tax) for payment calculation
    
    // Open modal
    confirmPaymentModal.classList.remove('hidden');
    confirmPaymentModal.classList.add('flex');
    document.body.style.overflow = 'hidden';
    
    // Focus on amount paid input
    setTimeout(() => {
        amountPaidInput.focus();
    }, 100);
}

// Function to calculate change
function calculateChange(amountPaid, totalAmount) {
    return amountPaid - totalAmount;
}

// Function to close payment modal
function closePaymentModal() {
    confirmPaymentModal.classList.remove('flex');
    confirmPaymentModal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Function to open admin password modal
function openAdminPasswordModal(orderId, paymentNumber, selectedProducts) {
    // Set modal values
    adminModalOrderId.textContent = orderId;
    adminModalPaymentNumber.textContent = paymentNumber;
    adminModalProductCount.textContent = selectedProducts.length;
    
    // Reset form
    adminPasswordInput.value = '';
    adminPasswordError.classList.add('hidden');
    adminErrorElement.textContent = '';
    
    // Store current order data
    currentVoidOrderData = {
        orderId: orderId,
        paymentNumber: paymentNumber,
        selectedProducts: selectedProducts,
        actionType: 'void'
    };
    
    // Open modal
    adminPasswordModal.classList.remove('hidden');
    adminPasswordModal.classList.add('flex');
    document.body.style.overflow = 'hidden';
    
    // Focus on password input
    setTimeout(() => {
        adminPasswordInput.focus();
    }, 100);
}

// Function to open admin password modal for cancel order
function openAdminPasswordModalForCancel(orderId, paymentNumber) {
    // Set modal values
    adminModalOrderId.textContent = orderId;
    adminModalPaymentNumber.textContent = paymentNumber;
    adminModalProductCount.textContent = 'All Products'; // Since we're cancelling the entire order
    
    // Reset form
    adminPasswordInput.value = '';
    adminPasswordError.classList.add('hidden');
    adminErrorElement.textContent = '';
    
    // Update modal title and text for cancel order
    const modalTitle = document.querySelector('#admin-password-modal h2');
    const warningText = document.querySelector('#admin-password-modal .text-red-300');
    
    if (modalTitle) modalTitle.textContent = 'Confirm Order Cancellation';
    if (warningText) warningText.textContent = 'Confirm Order Cancellation';
    
    // Update confirm button text
    const confirmButton = document.querySelector('#confirm-admin-auth');
    if (confirmButton) {
        confirmButton.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Confirm Cancel';
        confirmButton.classList.remove('bg-red-700', 'hover:bg-red-600');
        confirmButton.classList.add('bg-amber-700', 'hover:bg-amber-600');
    }
    
    // Store current order data with action type
    currentVoidOrderData = {
        orderId: orderId,
        paymentNumber: paymentNumber,
        actionType: 'cancel' // Differentiate from void action
    };
    
    // Open modal
    adminPasswordModal.classList.remove('hidden');
    adminPasswordModal.classList.add('flex');
    document.body.style.overflow = 'hidden';
    
    // Focus on password input
    setTimeout(() => {
        adminPasswordInput.focus();
    }, 100);
}

// Function to close admin password modal
function closeAdminPasswordModal() {
    adminPasswordModal.classList.remove('flex');
    adminPasswordModal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    
    // Reset modal content
    const modalTitle = document.querySelector('#admin-password-modal h2');
    const warningText = document.querySelector('#admin-password-modal .text-red-300');
    const confirmButton = document.querySelector('#confirm-admin-auth');
    
    if (modalTitle) modalTitle.textContent = 'Admin Authorization Required';
    if (warningText) warningText.textContent = 'Confirm Product Voiding';
    if (confirmButton) {
        confirmButton.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Confirm Void';
        confirmButton.classList.remove('bg-amber-700', 'hover:bg-amber-600');
        confirmButton.classList.add('bg-red-700', 'hover:bg-red-600');
    }
    
    currentVoidOrderData = null;
}

// Function to download 5-inch thermal receipt
function downloadReceipt(orderId, paymentNumber, referenceNumber) {
    // Create a temporary form to submit
    const form = document.createElement('form');
    form.method = 'GET';
    form.action = '/api/staff/receipt/generate';
    
    // Add order ID
    const orderIdInput = document.createElement('input');
    orderIdInput.type = 'hidden';
    orderIdInput.name = 'orderID';
    orderIdInput.value = orderId;
    form.appendChild(orderIdInput);
    
    // Add payment number
    const paymentNumberInput = document.createElement('input');
    paymentNumberInput.type = 'hidden';
    paymentNumberInput.name = 'paymentNumber';
    paymentNumberInput.value = paymentNumber;
    form.appendChild(paymentNumberInput);
    
    // Add reference number if exists
    if (referenceNumber) {
        const refInput = document.createElement('input');
        refInput.type = 'hidden';
        refInput.name = 'referenceNumber';
        refInput.value = referenceNumber;
        form.appendChild(refInput);
    }
    
    // Add CSRF token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
    form.appendChild(csrfInput);
    
    // Submit form to download PDF
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
    
    // Show message
    setTimeout(() => {
        showStatusMessage('5-inch thermal receipt downloaded!', 'bg-green-600');
    }, 1000);
}

// Helper function to show error in modal
function showError(message) {
    adminErrorElement.textContent = message;
    adminPasswordError.classList.remove('hidden');
    
    // Auto-hide error after 5 seconds
    setTimeout(() => {
        adminPasswordError.classList.add('hidden');
    }, 5000);
}

// Pagination Functions
function updatePagination() {
    totalPages = Math.ceil(allOrders.length / ordersPerPage);
    currentPageElement.textContent = currentPage;
    totalPagesElement.textContent = totalPages || 1;
    
    // Enable/disable buttons
    prevPageBtn.disabled = currentPage === 1;
    nextPageBtn.disabled = currentPage === totalPages || totalPages === 0;
    
    // Update button styles based on state
    if (prevPageBtn.disabled) {
        prevPageBtn.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        prevPageBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
    
    if (nextPageBtn.disabled) {
        nextPageBtn.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        nextPageBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
}

function renderOrders() {
    // Clear current orders
    ordersContainer.innerHTML = '';
    
    if (allOrders.length === 0) {
        ordersContainer.innerHTML = `
            <div class="text-center text-gray-400 col-span-full py-12">
                <i class="fas fa-coffee text-5xl mb-4"></i>
                <p class="text-xl">No orders found</p>
                <p class="text-sm mt-2">Orders will appear here as they are created</p>
            </div>
        `;
        return;
    }
    
    // Calculate which orders to show
    const startIndex = (currentPage - 1) * ordersPerPage;
    const endIndex = startIndex + ordersPerPage;
    const currentOrders = allOrders.slice(startIndex, endIndex);
    
    // Render each order
    currentOrders.forEach((order, orderIndex) => {
        // Calculate totals for this order WITHOUT TAX
        const totals = calculateOrderTotals(order);
        
        // Determine timer badge color based on time
        let timerColor = 'bg-blue-500';
        const timeNum = parseInt(order.time);
        if (timeNum >= 20) timerColor = 'bg-red-500';
        else if (timeNum >= 10) timerColor = 'bg-yellow-500';
        else if (timeNum >= 5) timerColor = 'bg-green-500';
        
        // Determine payment badge
        const paymentClass = order.payment === 'Cash' ? 
            'payment-badge-cash' : 'payment-badge-electronic';
        const paymentText = order.payment === 'Cash' ? 'Cash' : 'Electronic';
        
        // Check if order is in void state
        const isVoidState = isOrderInVoidState(order.id, order.paymentNumber);
        const voidStateClass = isVoidState ? 'void-state' : '';
        
        const orderCard = document.createElement('div');
        orderCard.className = `order-card bg-gray-800 rounded-xl border border-gray-700 overflow-hidden ${voidStateClass}`;
        
        // Check if order has items
        const hasItems = order.items.length > 0;
        
        orderCard.innerHTML = `
            <div class="p-5">
                <!-- Order ID and Payment Number in same row -->
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-white">${order.id}</h2>
                        <div class="flex items-center mt-1">
                            <div class="payment-number-badge px-2 py-1 rounded text-xs font-semibold">
                                Payment #${order.paymentNumber}
                            </div>
                        </div>
                    </div>
                    <div class="${timerColor} text-white px-3 py-1 rounded-full text-sm font-semibold status-timer">
                        ${order.time}
                    </div>
                </div>
                
                <div class="mb-6">
                    <!-- Order Type and Payment Method in same row -->
                    <div class="flex flex-wrap gap-2 mb-3">
                        <div class="${order.typeColor} px-3 py-1 rounded-md text-sm">
                            ${order.type}
                        </div>
                        <div class="${paymentClass} px-3 py-1 rounded-md text-sm font-medium">
                            ${paymentText}
                        </div>
                    </div>
                    
                    <!-- Order Notes Summary (only show if there are notes) 
                    ${order.notes !== 'None' ? `
                    <div class="order-notes-summary mb-3">
                        <div class="order-notes-label">
                            <i class="fas fa-sticky-note"></i>
                            <span>Order Notes Summary:</span>
                        </div>
                        <div class="order-notes-content">
                            ${order.notes}
                        </div>
                    </div>
                    ` : ''}-->
                    <!-- Order Notes (Display once above items) -->
                    ${order.notes && order.notes !== 'None' ? `
                    <div class="order-notes-summary mb-3">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-sticky-note text-amber-600 mt-1"></i>
                            <div>
                                <p class="text-xs font-semibold text-white mb-1">Order Notes Summary:</p>
                                <p class="text-sm text-white whitespace-pre-wrap">${order.notes}</p>
                            </div>
                        </div>
                    </div>
                    ` : ''}
                    
                    <!-- Void State Warning -->
                    ${isVoidState ? `
                    <div class="void-warning mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle void-check-icon"></i>
                            <span class="void-warning-text">Select products to void, then click Confirm Void</span>
                        </div>
                    </div>
                    ` : ''}
                    
                    ${hasItems ? `
                    <!-- Select All Checkbox -->
                    <div class="select-all-section ${isVoidState ? 'bg-red-900/30 border-red-700' : ''}">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   class="select-all-checkbox ${isVoidState ? 'select-all-checkbox-void' : ''}" 
                                   data-order-id="${order.id}" 
                                   data-payment-number="${order.paymentNumber}"
                                   ${isVoidState ? 'data-void-state="true"' : ''}>
                            <span class="${isVoidState ? 'text-red-300' : 'text-gray-300'} text-sm font-medium">
                                ${isVoidState ? 'Select All Products to Void' : 'Select All Items'}
                            </span>
                        </label>
                    </div>
                    
                    
                    
                    <!-- Order Items with Checkboxes, Quantities and Prices -->
                    <ul class="space-y-2" id="items-list-${order.id}-${order.paymentNumber}">
                        ${order.items.map((item, itemIndex) => {
                            const itemKey = getItemKey(order.id, order.paymentNumber, itemIndex);
                            const isChecked = checkedItemsState.get(itemKey) || false;
                            const itemClass = isChecked ? 'item-checked' : '';
                            const checkboxClass = isVoidState ? 'item-checkbox-void' : '';
                            
                            return `
                            <li class="price-item ${itemClass}" id="item-${order.id}-${order.paymentNumber}-${itemIndex}">
                                <div class="checkbox-container">
                                    <input type="checkbox" 
                                           class="item-checkbox ${checkboxClass}" 
                                           id="${itemKey}"
                                           data-order-id="${order.id}"
                                           data-payment-number="${order.paymentNumber}"
                                           data-item-index="${itemIndex}"
                                           data-product-name="${item.name}"
                                           data-product-notes="${item.displayNotes || ''}"
                                           data-unit-price="${item.price}"
                                           data-quantity="${item.quantity}"
                                           data-total-price="${item.totalPrice}"
                                           ${isChecked ? 'checked' : ''}>
                                    <div class="flex flex-col w-full">
                                        <div class="flex items-start">
                                            <div class="item-details">
                                                <span class="order-item-text">
                                                    ${item.name}
                                                </span>
                                                <span class="quantity-badge">×${item.quantity}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <span class="price-tag">
                                    ${formatCurrency(item.totalPrice)}
                                </span>
                            </li>
                        `}).join('')}
                    </ul>
                    ` : `
                    <!-- Empty Order Message -->
                    <div class="empty-order-message">
                        <i class="fas fa-ban text-2xl text-gray-500 mb-2"></i>
                        <p class="empty-order-text">All products have been voided</p>
                    </div>
                    `}
                    
                    <!-- Total to Pay Section (WITHOUT TAX) -->
                    ${hasItems ? `
                    <div class="price-section">
                        <!-- Vatable Sales (89.3% of Total) -->
                        <div class="price-item">
                            <span class="text-gray-300">Vatable Sales: </span>
                            <span class="text-amber-300 font-medium">${formatCurrency(totals.vatableSales)}</span>
                        </div>
                        
                        <!-- Tax (10.7% of Total) -->
                        <div class="price-item">
                            <span class="text-gray-300">Tax: </span>
                            <span class="text-amber-300 font-medium">${formatCurrency(totals.tax10_7)}</span>
                        </div>
                        
                        <!-- Total to Pay (WITHOUT TAX) -->
                        <div class="price-item total-row">
                            <span class="text-white">Total to Pay:</span>
                            <span class="text-green-400 font-bold">${formatCurrency(totals.total)}</span>
                        </div>
                    </div>
                    ` : ''}
                    
                    <!-- Item count summary -->
                    <div class="mt-3 pt-3 border-t border-gray-700">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 text-sm">Total items in order:</span>
                            <span class="text-amber-400 font-medium">${order.items.reduce((sum, item) => sum + item.quantity, 0)}</span>
                        </div>
                        <!-- Checked items summary -->
                        <div class="flex justify-between items-center mt-1">
                            <span class="text-gray-400 text-sm">
                                ${isVoidState ? 'Products selected for voiding:' : 'Selected items:'}
                            </span>
                            <span class="${isVoidState ? 'text-red-400' : 'text-blue-400'} font-medium" id="checked-count-${order.id}-${order.paymentNumber}">0</span>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-3">
                    ${hasItems ? `
                    <button class="send-to-kitchen-btn w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 rounded-lg transition" data-order-id="${order.id}" data-payment-number="${order.paymentNumber}">
                        Confirm Payment
                    </button>
                    ` : `
                    <button class="send-to-kitchen-btn w-full bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 rounded-lg transition cursor-not-allowed" disabled data-order-id="${order.id}" data-payment-number="${order.paymentNumber}">
                        Order Empty
                    </button>
                    `}
                    <button class="cancel-order-btn w-full bg-amber-700 hover:bg-amber-800 text-white font-medium py-3 rounded-lg transition" data-order-id="${order.id}" data-payment-number="${order.paymentNumber}">
                        Cancel Order
                    </button>
                    ${hasItems ? `
                    ${!isVoidState ? `
                    <button class="void-product-btn w-full bg-red-700 hover:bg-red-800 text-white font-medium py-3 rounded-lg transition" data-order-id="${order.id}" data-payment-number="${order.paymentNumber}">
                        Void Product
                    </button>
                    ` : `
                    <button class="confirm-void-btn w-full bg-red-700 hover:bg-red-800 text-white font-medium py-3 rounded-lg transition" data-order-id="${order.id}" data-payment-number="${order.paymentNumber}">
                        Confirm Void
                    </button>
                    <button class="cancel-void-btn w-full bg-gray-700 hover:bg-gray-800 text-white font-medium py-3 rounded-lg transition" data-order-id="${order.id}" data-payment-number="${order.paymentNumber}">
                        Cancel Void
                    </button>
                    `}
                    ` : `
                    <button class="void-product-btn w-full bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 rounded-lg transition cursor-not-allowed" disabled>
                        No Products to Void
                    </button>
                    `}
                    <button class="add-product-btn w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 rounded-lg transition" data-order-id="${order.id}" data-payment-number="${order.paymentNumber}">
                        <i class="fas fa-plus mr-2"></i>Add Product
                    </button>
                </div>
            </div>
        `;
        
        ordersContainer.appendChild(orderCard);
    });
    
    // Re-attach event listeners to new buttons
    attachOrderButtonListeners();
    attachCheckboxListeners();
    updateCheckedCounts();
}

function updateStatistics() {
    // Update counts
    const dineInCount = allOrders.filter(order => order.type === 'Dine in').length;
    const takeoutCount = allOrders.filter(order => order.type === 'Takeout').length;
    const cashCount = allOrders.filter(order => order.payment === 'Cash').length;
    const electronicCount = allOrders.filter(order => order.payment === 'Electronic').length;
    
    orderCountElement.textContent = allOrders.length;
}

function updateRevenueStats() {
    const revenueStats = calculateRevenueStatistics();
    
    // These would update elements if they existed
    // For now, we'll just calculate them
}

function attachCheckboxListeners() {
    // Individual item checkbox listeners
    document.querySelectorAll('.item-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const orderId = this.getAttribute('data-order-id');
            const paymentNumber = this.getAttribute('data-payment-number');
            const itemIndex = this.getAttribute('data-item-index');
            const isChecked = this.checked;
            const isVoidState = this.classList.contains('item-checkbox-void');
            
            // Update state
            const itemKey = getItemKey(orderId, paymentNumber, itemIndex);
            checkedItemsState.set(itemKey, isChecked);
            
            // Update UI
            const listItem = this.closest('li');
            if (isChecked) {
                listItem.classList.add('item-checked');
            } else {
                listItem.classList.remove('item-checked');
            }
            
            // Update "Select All" checkbox state
            updateSelectAllCheckbox(orderId, paymentNumber);
            
            // Update checked items count
            updateCheckedCount(orderId, paymentNumber);
            updateCheckedItemsCount();
            
            const message = isVoidState ? 
                `Product ${isChecked ? 'selected for voiding' : 'unselected'}` :
                `Item ${isChecked ? 'selected' : 'unselected'}`;
            
            showStatusMessage(message, isVoidState ? 'bg-red-600' : 'bg-blue-600');
        });
    });
    
    // "Select All" checkbox listeners
    document.querySelectorAll('.select-all-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const orderId = this.getAttribute('data-order-id');
            const paymentNumber = this.getAttribute('data-payment-number');
            const selectAllChecked = this.checked;
            const isVoidState = this.classList.contains('select-all-checkbox-void');
            
            // Find all checkboxes for this order using safe selector
            const itemCheckboxes = getCheckboxesForOrder(orderId, paymentNumber);
            
            // Update all checkboxes
            itemCheckboxes.forEach(itemCheckbox => {
                const itemIndex = itemCheckbox.getAttribute('data-item-index');
                const itemKey = getItemKey(orderId, paymentNumber, itemIndex);
                
                // Update state
                checkedItemsState.set(itemKey, selectAllChecked);
                
                // Update UI
                itemCheckbox.checked = selectAllChecked;
                const listItem = itemCheckbox.closest('li');
                if (selectAllChecked) {
                    listItem.classList.add('item-checked');
                } else {
                    listItem.classList.remove('item-checked');
                }
            });
            
            // Update checked items count
            updateCheckedCount(orderId, paymentNumber);
            updateCheckedItemsCount();
            
            const message = isVoidState ?
                (selectAllChecked ? 'All products selected for voiding' : 'All products unselected') :
                (selectAllChecked ? 'All items selected' : 'All items unselected');
            
            showStatusMessage(message, isVoidState ? 'bg-red-600' : 'bg-blue-600');
        });
    });
}

function updateSelectAllCheckbox(orderId, paymentNumber) {
    const selectAllCheckbox = getSelectAllCheckboxForOrder(orderId, paymentNumber);
    
    if (!selectAllCheckbox) return;
    
    const isVoidState = selectAllCheckbox.classList.contains('select-all-checkbox-void');
    
    // Find all checkboxes for this order using safe selector
    const itemCheckboxes = getCheckboxesForOrder(orderId, paymentNumber);
    
    if (itemCheckboxes.length === 0) return;
    
    const allChecked = Array.from(itemCheckboxes).every(checkbox => checkbox.checked);
    const anyChecked = Array.from(itemCheckboxes).some(checkbox => checkbox.checked);
    
    // Update select all checkbox state
    selectAllCheckbox.checked = allChecked;
    
    // Set indeterminate state if some but not all are checked
    selectAllCheckbox.indeterminate = anyChecked && !allChecked;
}

function updateCheckedCount(orderId, paymentNumber) {
    const itemCheckboxes = getCheckboxesForOrder(orderId, paymentNumber);
    
    const checkedCount = Array.from(itemCheckboxes).filter(checkbox => checkbox.checked).length;
    
    const countElement = document.getElementById(`checked-count-${orderId}-${paymentNumber}`);
    if (countElement) {
        countElement.textContent = checkedCount;
    }
}

function updateCheckedCounts() {
    const allOrderCards = document.querySelectorAll('.order-card');
    
    allOrderCards.forEach(card => {
        const orderIdElement = card.querySelector('h2.text-xl');
        if (!orderIdElement) return;
        
        const orderId = orderIdElement.textContent;
        const paymentNumberBadge = card.querySelector('.payment-number-badge');
        if (!paymentNumberBadge) return;
        
        // Extract payment number and trim spaces
        const paymentNumber = paymentNumberBadge.textContent.replace('Payment #', '').trim();
        updateCheckedCount(orderId, paymentNumber);
    });
}

function attachOrderButtonListeners() {
    // Confirm Payment buttons
    document.querySelectorAll('.send-to-kitchen-btn').forEach(button => {
        button.addEventListener('click', async function(e) {
            if (this.disabled) return;
            
            const orderId = this.getAttribute('data-order-id');
            const paymentNumber = this.getAttribute('data-payment-number');
            
            // Check if order is in void state
            if (isOrderInVoidState(orderId, paymentNumber)) {
                showStatusMessage('Cannot send to kitchen while in void state. Cancel void first.', 'bg-red-600');
                return;
            }
            
            // Find the order in allOrders array
            const order = allOrders.find(order => 
                order.id === orderId && order.paymentNumber === paymentNumber
            );
            
            if (!order) {
                showStatusMessage('Order not found', 'bg-red-600');
                return;
            }
            
            // Get selected items for this order
            const itemCheckboxes = getCheckboxesForOrder(orderId, paymentNumber);
            const selectedItems = itemCheckboxes ? 
                Array.from(itemCheckboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.getAttribute('data-item-index')) : [];
            
            // Check if any items are selected
            if (selectedItems.length === 0) {
                showStatusMessage('Please select at least one item to pay', 'bg-yellow-600');
                return;
            }
            
            // Calculate totals WITHOUT TAX
            const totals = calculateOrderTotals(order);
            
            // Open confirm payment modal with products
            openConfirmPaymentModal(
                order.id,
                order.paymentNumber,
                order.type,
                order.payment,
                totals.subtotal, // Pass subtotal (without tax)
                totals.tax,
                totals.subtotal, // Use subtotal as total (without tax)
                order.items,
                selectedItems
            );
        });
    });
    
    // Cancel Order buttons
    document.querySelectorAll('.cancel-order-btn').forEach(button => {
        button.addEventListener('click', async function(e) {
            const orderId = this.getAttribute('data-order-id');
            const paymentNumber = this.getAttribute('data-payment-number');
            
            // Check if order is in void state
            if (isOrderInVoidState(orderId, paymentNumber)) {
                showStatusMessage('Cannot cancel order while in void state. Cancel void first.', 'bg-red-600');
                return;
            }
            
            // Open admin password modal for cancel order
            openAdminPasswordModalForCancel(orderId, paymentNumber);
        });
    });
    
    // Void Product buttons
    document.querySelectorAll('.void-product-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            if (this.disabled) return;
            
            const orderId = this.getAttribute('data-order-id');
            const paymentNumber = this.getAttribute('data-payment-number');
            
            // Set order to void state
            setOrderVoidState(orderId, paymentNumber, true);
            
            // Reset checkboxes for this order
            const itemCheckboxes = getCheckboxesForOrder(orderId, paymentNumber);
            
            itemCheckboxes.forEach(itemCheckbox => {
                const itemIndex = itemCheckbox.getAttribute('data-item-index');
                const itemKey = getItemKey(orderId, paymentNumber, itemIndex);
                checkedItemsState.set(itemKey, false);
            });
            
            // Re-render orders to show void state
            renderOrders();
            
            showStatusMessage(`Order ${orderId} (Payment #${paymentNumber}) in void state. Select products to void.`, 'bg-red-600');
        });
    });
    
    // Confirm Void buttons
    document.querySelectorAll('.confirm-void-btn').forEach(button => {
        button.addEventListener('click', async function(e) {
            const orderId = this.getAttribute('data-order-id');
            const paymentNumber = this.getAttribute('data-payment-number');
            
            // Get selected items for voiding using safe selector
            const itemCheckboxes = getCheckboxesForOrder(orderId, paymentNumber);
            
            // Get product names from selected checkboxes
            const selectedProducts = Array.from(itemCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.getAttribute('data-product-name'));
            
            if (selectedProducts.length === 0) {
                showStatusMessage('No products selected for voiding', 'bg-yellow-600');
                return;
            }
            
            // Open admin password modal instead of prompt
            openAdminPasswordModal(orderId, paymentNumber, selectedProducts);
        });
    });
    
    // Cancel Void buttons
    document.querySelectorAll('.cancel-void-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            const orderId = this.getAttribute('data-order-id');
            const paymentNumber = this.getAttribute('data-payment-number');
            
            // Reset void state
            setOrderVoidState(orderId, paymentNumber, false);
            
            // Clear checkboxes for this order
            const itemCheckboxes = getCheckboxesForOrder(orderId, paymentNumber);
            
            itemCheckboxes.forEach(itemCheckbox => {
                const itemIndex = itemCheckbox.getAttribute('data-item-index');
                const itemKey = getItemKey(orderId, paymentNumber, itemIndex);
                checkedItemsState.set(itemKey, false);
            });
            
            // Re-render orders to exit void state
            renderOrders();
            
            showStatusMessage('Void cancelled', 'bg-gray-600');
        });
    });
    
    // Add Product buttons
    document.querySelectorAll('.add-product-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            const orderId = this.getAttribute('data-order-id');
            const paymentNumber = this.getAttribute('data-payment-number');
            
            // Check if order is in void state
            if (isOrderInVoidState(orderId, paymentNumber)) {
                showStatusMessage('Cannot add products while in void state. Cancel void first.', 'bg-red-600');
                return;
            }
            
            // Load products if not already loaded
            if (allProducts.length === 0) {
                loadProducts().then(() => {
                    openAddProductModal(orderId, paymentNumber);
                });
            } else {
                openAddProductModal(orderId, paymentNumber);
            }
        });
    });
}

// Event listener for amount paid input
amountPaidInput.addEventListener('input', function() {
    const totalAmount = parseFloat(confirmPaymentBtn.dataset.totalAmount || 0);
    const amountPaid = parseFloat(this.value) || 0;
    
    if (amountPaid >= totalAmount) {
        const change = calculateChange(amountPaid, totalAmount);
        document.getElementById('modal-change').textContent = formatCurrency(change);
        document.getElementById('change-calculation').classList.remove('hidden');
        confirmPaymentBtn.disabled = false;
    } else {
        document.getElementById('change-calculation').classList.add('hidden');
        confirmPaymentBtn.disabled = true;
    }
});

// Password visibility toggle
togglePasswordVisibility.addEventListener('click', function() {
    const type = adminPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    adminPasswordInput.setAttribute('type', type);
    
    // Toggle icon
    const icon = this.querySelector('i');
    if (type === 'text') {
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});

// Confirm admin authentication
confirmAdminAuth.addEventListener('click', async function() {
    const password = adminPasswordInput.value.trim();
    
    if (!password) {
        showError('Please enter the Admin password');
        return;
    }
    
    if (!currentVoidOrderData) {
        showError('No order data found. Please try again.');
        return;
    }
    
    const { orderId, paymentNumber, selectedProducts, actionType } = currentVoidOrderData;
    
    // Disable button and show loading state
    this.disabled = true;
    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
    
    try {
        if (actionType === 'cancel') {
            // Handle cancel order
            await apiCall('/api/staff/orders/cancel', 'POST', {
                orderID: orderId,
                paymentNumber: paymentNumber,
                adminPassword: password
            });
            
            // Success - close modal
            closeAdminPasswordModal();
            
            // Show success message
            showStatusMessage(`Order ${orderId} (Payment #${paymentNumber}) cancelled successfully!`, 'bg-green-600');
            
            // Reload orders
            setTimeout(() => {
                loadOrders();
            }, 1000);
            
        } else {
            // Handle void products (original logic)
            await apiCall('/api/staff/orders/void-products', 'POST', {
                orderID: orderId,
                paymentNumber: paymentNumber,
                items: selectedProducts,
                status: 'Product Voided',
                adminPassword: password
            });
            
            // Success - close modal and proceed with voiding
            closeAdminPasswordModal();
            
            // Animate removal of selected items
            const itemCheckboxes = getCheckboxesForOrder(orderId, paymentNumber);
            Array.from(itemCheckboxes)
                .filter(checkbox => checkbox.checked)
                .forEach(checkbox => {
                    const itemIndex = checkbox.getAttribute('data-item-index');
                    const itemElement = document.getElementById(`item-${orderId}-${paymentNumber}-${itemIndex}`);
                    if (itemElement) {
                        itemElement.classList.add('item-removing');
                    }
                });
            
            // Wait for animation to complete
            setTimeout(async () => {
                // Get the indices of selected items for local removal
                const selectedIndices = Array.from(itemCheckboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => parseInt(checkbox.getAttribute('data-item-index')));
                
                // Remove items from local data
                const orderRemoved = removeItemsFromOrder(orderId, paymentNumber, selectedIndices.map(i => i.toString()));
                
                // Reset void state
                setOrderVoidState(orderId, paymentNumber, false);
                
                // Clear checkboxes for this order
                const allItemCheckboxes = getCheckboxesForOrder(orderId, paymentNumber);
                
                allItemCheckboxes.forEach(itemCheckbox => {
                    const itemIndex = itemCheckbox.getAttribute('data-item-index');
                    const itemKey = getItemKey(orderId, paymentNumber, itemIndex);
                    checkedItemsState.set(itemKey, false);
                });
                
                // Update pagination and re-render
                totalPages = Math.ceil(allOrders.length / ordersPerPage);
                updatePagination();
                renderOrders();
                
                showStatusMessage(`${selectedProducts.length} product(s) voided successfully`, 'bg-green-600');
                
            }, 300);
        }
        
    } catch (error) {
        console.warn('API call failed:', error);
        const errorMessage = error.message.includes('Invalid admin password') 
            ? 'Invalid Admin password. Please try again.'
            : error.message.includes('No admin user found')
            ? 'No Admin user found in system.'
            : 'Server error. Please try again.';
        
        showError(errorMessage);
        
        // Reset button state
        this.disabled = false;
        this.innerHTML = actionType === 'cancel' 
            ? '<i class="fas fa-check-circle mr-2"></i> Confirm Cancel'
            : '<i class="fas fa-check-circle mr-2"></i> Confirm Void';
        
        // Clear password field
        adminPasswordInput.value = '';
        adminPasswordInput.focus();
    }
});

// Event listener for confirm payment button in modal
confirmPaymentBtn.addEventListener('click', async function() {
    const orderId = this.dataset.orderId;
    const paymentNumber = this.dataset.paymentNumber;
    const totalAmount = parseFloat(this.dataset.totalAmount);
    const amountPaid = parseFloat(amountPaidInput.value);
    const referenceNumber = referenceInput.value.trim();
    
    // Calculate vatable sales and tax for this payment
    const vatableSales = totalAmount * 0.893;
    const tax10_7 = totalAmount * 0.107;
    
    // Validate amount paid
    if (!amountPaid || amountPaid < totalAmount) {
        showStatusMessage('Amount paid must be equal to or greater than total amount', 'bg-red-600');
        amountPaidInput.focus();
        return;
    }
    
    // Calculate change
    const change = calculateChange(amountPaid, totalAmount);
    
    // Show processing message
    showStatusMessage('Processing payment...', 'bg-blue-600');
    
    try {
        // Get selected items for this order
        const selectedItems = [];
        const selectedProducts = [];
        
        // Debug: Log to see what's happening
        console.log('Processing payment for order:', orderId, paymentNumber);
        
        // Get checkboxes using the safe selector function
        const itemCheckboxes = getCheckboxesForOrder(orderId, paymentNumber);
        console.log('Found checkboxes:', itemCheckboxes ? itemCheckboxes.length : 0);
        
        if (itemCheckboxes && itemCheckboxes.length > 0) {
            Array.from(itemCheckboxes)
                .filter(checkbox => checkbox.checked)
                .forEach(checkbox => {
                    const itemIndex = checkbox.getAttribute('data-item-index');
                    selectedItems.push(itemIndex);
                    
                    // Debug each checkbox
                    console.log('Selected checkbox:', {
                        itemIndex: itemIndex,
                        name: checkbox.getAttribute('data-product-name'),
                        unitPrice: checkbox.getAttribute('data-unit-price'),
                        quantity: checkbox.getAttribute('data-quantity'),
                        totalPrice: checkbox.getAttribute('data-total-price')
                    });
                    
                    // Get product details from data attributes
                    selectedProducts.push({
                        name: checkbox.getAttribute('data-product-name'),
                        notes: checkbox.getAttribute('data-product-notes') || null,
                        unitPrice: parseFloat(checkbox.getAttribute('data-unit-price')) || 0,
                        quantity: parseInt(checkbox.getAttribute('data-quantity')) || 1,
                        totalPrice: parseFloat(checkbox.getAttribute('data-total-price')) || 0
                    });
                });
        }
        
        console.log('Selected products:', selectedProducts);
        
        // Check if any products are selected
        if (selectedProducts.length === 0) {
            throw new Error('No products selected for payment. Please select at least one item.');
        }
        
        // Find the order to get tax rate
        const order = allOrders.find(order => 
            order.id === orderId && order.paymentNumber === paymentNumber
        );
        
        if (!order) {
            throw new Error('Order not found in local data');
        }
        
        // Prepare products data WITHOUT TAX for API call
        const productsData = selectedProducts.map((selectedProduct) => {
            // REMOVE TAX CALCULATION - No tax included
            return {
                name: selectedProduct.name,
                quantity: selectedProduct.quantity,
                unitPrice: selectedProduct.unitPrice,
                totalPrice: selectedProduct.totalPrice,
                taxAmount: 0, // Set tax amount to 0 since we're removing tax
                notes: selectedProduct.notes
            };
        });
        
        console.log('Products data to send (without tax):', productsData);
        
        // First: Update order status to "In Progress"
        console.log('Updating order status...');
        await apiCall('/api/staff/orders/update-all-status', 'POST', {
            orderID: orderId,
            paymentNumber: paymentNumber,
            status: 'In Progress',
            selectedItems: selectedItems,
            referenceNumber: referenceNumber || null
        });
        
        // Second: Save payment transaction to staff_to_kitchen_transaction table WITHOUT TAX
        console.log('Saving payment transaction...');
        
        // Prepare the request data WITHOUT TAX
        const paymentData = {
            orderID: orderId,
            paymentNumber: paymentNumber,
            orderType: order.type.toLowerCase().replace(' ', '-'), // Convert to 'dine-in' or 'takeout'
            paymentMethod: order.payment.toLowerCase(), // Convert to 'cash' or 'electronic'
            products: productsData,
            amountPaid: amountPaid,
            changeAmount: change,
            vatableSales: vatableSales, // Add vatable sales
            tax10_7: tax10_7, // Add tax
            referenceNumber: referenceNumber || null,
            staffName: '{{ auth()->user()->name ?? "Staff Member" }}'
        };
        
        console.log('Payment data to save (without tax):', paymentData);
        
        const saveResult = await apiCall('/api/staff/orders/save-payment-transaction', 'POST', paymentData);
        console.log('Save result:', saveResult);
        
        // Create success message with optional reference
        let successMessage = `Payment confirmed for Order ${orderId} (${selectedProducts.length} product${selectedProducts.length > 1 ? 's' : ''}). `;
        
        if (referenceNumber) {
            successMessage += `Reference: ${referenceNumber}. `;
        }
        
        successMessage += `Change: ${formatCurrency(change)}. Transaction saved to kitchen system.`;
        
        // Show success message
        showStatusMessage(successMessage, 'bg-green-600');
        
        // Close modal
        closePaymentModal();
        
        // Clear checkboxes for this order
        if (itemCheckboxes) {
            Array.from(itemCheckboxes).forEach(checkbox => {
                const itemIndex = checkbox.getAttribute('data-item-index');
                const itemKey = getItemKey(orderId, paymentNumber, itemIndex);
                checkedItemsState.set(itemKey, false);
            });
        }
        
        // Update the button in the order card to "In Progress"
        const sendButton = document.querySelector(`.send-to-kitchen-btn[data-order-id="${orderId}"][data-payment-number="${paymentNumber}"]`);
        if (sendButton) {
            sendButton.textContent = 'In Progress';
            sendButton.classList.remove('bg-green-600', 'hover:bg-green-700');
            sendButton.classList.add('bg-blue-600', 'hover:bg-blue-700');
            sendButton.disabled = true;
        }
        
        // Update timer badge
        // Update timer badge - find the card by order ID
        const orderCards = document.querySelectorAll('.order-card');
        let cardToUpdate = null;
        
        for (const card of orderCards) {
            const orderIdElement = card.querySelector('h2.text-xl');
            if (orderIdElement && orderIdElement.textContent === orderId) {
                const paymentBadge = card.querySelector('.payment-number-badge');
                if (paymentBadge) {
                    const paymentText = paymentBadge.textContent.replace('Payment #', '').trim();
                    if (paymentText === paymentNumber) {
                        cardToUpdate = card;
                        break;
                    }
                }
            }
        }
        
        if (cardToUpdate) {
            const timerBadge = cardToUpdate.querySelector('.status-timer');
            if (timerBadge) {
                timerBadge.textContent = '0m';
                timerBadge.className = 'bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold status-timer';
            }
        }
        
        // DOWNLOAD 5-INCH THERMAL RECEIPT AUTOMATICALLY
        downloadReceipt(orderId, paymentNumber, referenceNumber);
        
        // Force reload orders to reflect changes
        setTimeout(() => {
            loadOrders();
        }, 1000);
        
    } catch (error) {
        console.error('Error processing payment:', error);
        console.error('Error details:', error.message);
        showStatusMessage('Error processing payment: ' + error.message, 'bg-red-600');
    }
});

// Event listeners for confirm payment modal
closeConfirmPayment.addEventListener('click', closePaymentModal);
cancelPayment.addEventListener('click', closePaymentModal);

confirmPaymentModal.addEventListener('click', (e) => {
    if (e.target === confirmPaymentModal) closePaymentModal();
});

// Event listeners for admin password modal
closeAdminPassword.addEventListener('click', closeAdminPasswordModal);
cancelAdminAuth.addEventListener('click', closeAdminPasswordModal);

adminPasswordModal.addEventListener('click', (e) => {
    if (e.target === adminPasswordModal) closeAdminPasswordModal();
});

// Submit form on Enter key
adminPasswordInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        e.preventDefault();
        confirmAdminAuth.click();
    }
});

// Event listeners for Add Product modal
closeAddProduct.addEventListener('click', closeAddProductModal);
cancelAddProduct.addEventListener('click', closeAddProductModal);

addProductModal.addEventListener('click', (e) => {
    if (e.target === addProductModal) closeAddProductModal();
});

// Search and filter event listeners
productSearchInput.addEventListener('input', filterProducts);
categoryFilter.addEventListener('change', filterProducts);

// Confirm adding products
confirmAddProduct.addEventListener('click', async function() {
    if (!currentAddProductOrder || selectedProducts.size === 0) {
        showStatusMessage('Please select at least one product', 'bg-yellow-600');
        return;
    }
    
    const { orderId, paymentNumber } = currentAddProductOrder;
    
    try {
        // Prepare products data
        const productsData = Array.from(selectedProducts.values()).map(product => ({
            productId: product.id,
            name: product.name,
            price: product.price,
            quantity: product.quantity
        }));
        
        // Call API to add products to order
        const result = await apiCall('/api/staff/orders/add-products', 'POST', {
            orderID: orderId,
            paymentNumber: paymentNumber,
            products: productsData
        });
        
        // Show success message
        showStatusMessage(`${productsData.length} product(s) added to order ${orderId}`, 'bg-green-600');
        
        // Close modal
        closeAddProductModal();
        
        // Reload orders to show new products
        setTimeout(() => {
            loadOrders();
        }, 1000);
        
    } catch (error) {
        console.error('Error adding products:', error);
        showStatusMessage('Error adding products: ' + error.message, 'bg-red-600');
    }
});

function showStatusMessage(message, bgColor) {
    statusMessage.textContent = message;
    statusMessage.className = `fixed bottom-4 right-4 ${bgColor} text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transform translate-y-4 transition-all duration-300 z-50`;
    
    // Show message
    statusMessage.classList.remove('opacity-0', 'translate-y-4');
    statusMessage.classList.add('opacity-100', 'translate-y-0');
    
    // Hide message after 3 seconds (longer for important messages)
    setTimeout(() => {
        statusMessage.classList.remove('opacity-100', 'translate-y-0');
        statusMessage.classList.add('opacity-0', 'translate-y-4');
    }, 3000);
}

// Footer Modal Functions
function openModal(modal) {
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeModal(modal) {
    modal.classList.remove('flex');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Event Listeners for Footer
termsBtn.addEventListener('click', () => openModal(termsModal));
privacyBtn.addEventListener('click', () => openModal(privacyModal));


closeTerms.addEventListener('click', () => closeModal(termsModal));
closePrivacy.addEventListener('click', () => closeModal(privacyModal));

acceptTerms.addEventListener('click', () => {
    closeModal(termsModal);
    showStatusMessage('Terms and Conditions acknowledged', 'bg-blue-600');
});

acceptPrivacy.addEventListener('click', () => {
    closeModal(privacyModal);
    showStatusMessage('Privacy Policy acknowledged', 'bg-blue-600');
});

// Close modals when clicking outside
termsModal.addEventListener('click', (e) => {
    if (e.target === termsModal) closeModal(termsModal);
});

privacyModal.addEventListener('click', (e) => {
    if (e.target === privacyModal) closeModal(privacyModal);
});

// Close modals with Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        if (!termsModal.classList.contains('hidden')) closeModal(termsModal);
        if (!privacyModal.classList.contains('hidden')) closeModal(privacyModal);
        if (!confirmPaymentModal.classList.contains('hidden')) closePaymentModal();
        if (!adminPasswordModal.classList.contains('hidden')) closeAdminPasswordModal();
        if (!addProductModal.classList.contains('hidden')) closeAddProductModal();
    }
});

// Event Listeners for Pagination
prevPageBtn.addEventListener('click', function() {
    if (currentPage > 1) {
        currentPage--;
        updatePagination();
        renderOrders();
    }
});

nextPageBtn.addEventListener('click', function() {
    if (currentPage < totalPages) {
        currentPage++;
        updatePagination();
        renderOrders();
    }
});

// Order Tracker Button click
orderTrackerBtn.addEventListener('click', navigateToOrderTracker);

// Live Clock Functionality
function updateClock() {
    const now = new Date();
    
    // Format time (HH:MM:SS AM/PM)
    let hours = now.getHours();
    const minutes = now.getMinutes().toString().padStart(2, '0');
    const seconds = now.getSeconds().toString().padStart(2, '0');
    const ampm = hours >= 12 ? 'PM' : 'AM';
    
    hours = hours % 12;
    hours = hours ? hours : 12;
    hours = hours.toString().padStart(2, '0');
    
    const timeString = `${hours}:${minutes}:${seconds} ${ampm}`;
    document.getElementById('live-clock').textContent = timeString;
    
    // Format date
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const dateString = now.toLocaleDateString('en-US', options);
    document.getElementById('current-date').textContent = dateString;
}

// Update clock immediately and then every second
updateClock();
setInterval(updateClock, 1000);

// Auto-refresh orders every 30 seconds
setInterval(loadOrders, 30000);

// Keyboard navigation for pagination
document.addEventListener('keydown', function(event) {
    if (event.key === 'ArrowLeft' && currentPage > 1) {
        currentPage--;
        updatePagination();
        renderOrders();
    } else if (event.key === 'ArrowRight' && currentPage < totalPages) {
        currentPage++;
        updatePagination();
        renderOrders();
    }
});