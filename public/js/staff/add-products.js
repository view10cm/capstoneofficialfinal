// Add Products Feature for Staff Landing Page
class AddProductsFeature {
    constructor() {
        this.modal = null;
        this.orderData = null;
        this.selectedProducts = [];
        this.currentOrderId = null;
        this.currentPaymentNumber = null;
        this.searchInput = null;
        this.productsList = null;
        this.selectedProductsList = null;
        this.totalAmountElement = null;
        this.notesInput = null;
        this.adminPasswordModal = null;
        this.adminPasswordInput = null;
        this.adminPasswordError = null;
        this.adminErrorElement = null;
        this.confirmAddProductsWithAuthBtn = null;
        this.initialize();
    }

    initialize() {
        this.createModal();
        this.createAdminPasswordModal();
        this.setupEventListeners();
    }

    createModal() {
        // Modal is already created in the HTML, just get references
        this.modal = document.getElementById('add-products-modal');
        this.searchInput = document.getElementById('product-search');
        this.productsList = document.getElementById('products-grid');
        this.selectedProductsList = document.getElementById('selected-products-list');
        this.totalAmountElement = document.getElementById('total-display');
        this.notesInput = document.getElementById('product-notes');
    }

    createAdminPasswordModal() {
        // Create admin password modal for Add Products
        const modalHTML = `
            <div id="add-products-admin-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                <div class="bg-gray-800 rounded-xl p-6 max-w-md w-full mx-4">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-white">Admin Authorization Required</h2>
                        <button id="close-add-products-admin" class="text-gray-400 hover:text-white">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    <div class="space-y-6">
                        <!-- Warning Icon and Message -->
                        <div class="bg-blue-900/20 border border-blue-800 rounded-lg p-4 flex items-start">
                            <div class="mr-3 mt-1">
                                <i class="fas fa-shield-alt text-blue-500 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-blue-300 font-semibold">Confirm Adding Products</h3>
                                <p class="text-gray-300 text-sm mt-1">This action requires Admin authorization. Please enter your Admin password to confirm.</p>
                            </div>
                        </div>
                        
                        <!-- Order Details -->
                        <div class="bg-gray-900 rounded-lg p-4">
                            <h4 class="text-gray-300 font-medium mb-2">Order Details</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Order ID:</span>
                                    <span class="text-white font-medium" id="add-products-admin-order-id">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Payment #:</span>
                                    <span class="text-white font-medium" id="add-products-admin-payment-number">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Products to Add:</span>
                                    <span class="text-green-300 font-medium" id="add-products-admin-product-count">0</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Products Summary -->
                        <div class="bg-gray-900 rounded-lg p-4">
                            <h4 class="text-gray-300 font-medium mb-2">Selected Products Summary</h4>
                            <div id="add-products-admin-products-list" class="space-y-1 max-h-32 overflow-y-auto text-sm">
                                <!-- Products will be listed here -->
                            </div>
                            <div class="mt-3 pt-3 border-t border-gray-700">
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Total Amount:</span>
                                    <span class="text-green-400 font-medium" id="add-products-admin-total">₱0.00</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Password Input -->
                        <div class="space-y-3">
                            <label for="add-products-admin-password-input" class="block text-gray-300 text-sm font-medium">
                                Admin Password <span class="text-red-400">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input 
                                    type="password" 
                                    id="add-products-admin-password-input" 
                                    placeholder="Enter Admin password"
                                    class="w-full bg-gray-700 text-white pl-10 pr-4 py-3 rounded-lg border border-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
                                    autocomplete="current-password"
                                >
                                <button 
                                    type="button" 
                                    id="add-products-toggle-password"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-300"
                                >
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-400">Only users with Admin role can authorize adding products to existing orders</p>
                            
                            <!-- Error Message -->
                            <div id="add-products-admin-password-error" class="hidden bg-red-900/30 border border-red-700 rounded-lg p-3 mt-2">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-circle text-red-400 mr-2"></i>
                                    <span class="text-red-300 text-sm" id="add-products-admin-error-message"></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-700">
                            <button id="cancel-add-products-admin" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                                Cancel
                            </button>
                            <button id="confirm-add-products-admin" class="bg-green-700 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                Confirm Add Products
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Add modal to body
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        
        // Store references
        this.adminPasswordModal = document.getElementById('add-products-admin-modal');
        this.adminPasswordInput = document.getElementById('add-products-admin-password-input');
        this.adminPasswordError = document.getElementById('add-products-admin-password-error');
        this.adminErrorElement = document.getElementById('add-products-admin-error-message');
        this.confirmAddProductsWithAuthBtn = document.getElementById('confirm-add-products-admin');
    }

    setupEventListeners() {
        // Close modal buttons
        document.getElementById('close-add-products').addEventListener('click', () => this.closeModal());
        document.getElementById('cancel-add-products').addEventListener('click', () => this.closeModal());
        
        // Search functionality
        this.searchInput.addEventListener('input', (e) => this.filterProducts(e.target.value));
        
        // Category filter
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active', 'bg-blue-600', 'text-white'));
                e.target.classList.add('active', 'bg-blue-600', 'text-white');
                this.filterByCategory(e.target.dataset.category);
            });
        });
        
        // Clear selection
        document.getElementById('clear-selection').addEventListener('click', () => this.clearSelection());
        
        // Quantity controls
        document.getElementById('decrease-qty').addEventListener('click', () => this.adjustQuantity(-1));
        document.getElementById('increase-qty').addEventListener('click', () => this.adjustQuantity(1));
        document.getElementById('update-qty').addEventListener('click', () => this.updateQuantity());
        
        // Confirm add products button in main modal - now opens admin modal
        document.getElementById('confirm-add-products').addEventListener('click', () => this.openAdminPasswordModal());
        
        // Admin modal buttons
        document.getElementById('close-add-products-admin').addEventListener('click', () => this.closeAdminPasswordModal());
        document.getElementById('cancel-add-products-admin').addEventListener('click', () => this.closeAdminPasswordModal());
        this.confirmAddProductsWithAuthBtn.addEventListener('click', () => this.confirmAddProductsWithAuth());
        
        // Password visibility toggle
        document.getElementById('add-products-toggle-password').addEventListener('click', () => this.togglePasswordVisibility());
        
        // Close modal when clicking outside
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) this.closeModal();
        });
        
        this.adminPasswordModal.addEventListener('click', (e) => {
            if (e.target === this.adminPasswordModal) this.closeAdminPasswordModal();
        });
        
        // Escape key to close modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (!this.modal.classList.contains('hidden')) this.closeModal();
                if (!this.adminPasswordModal.classList.contains('hidden')) this.closeAdminPasswordModal();
            }
        });
        
        // Submit form on Enter key in admin modal
        this.adminPasswordInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.confirmAddProductsWithAuthBtn.click();
            }
        });
    }

    togglePasswordVisibility() {
        const type = this.adminPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        this.adminPasswordInput.setAttribute('type', type);
        
        // Toggle icon
        const icon = document.querySelector('#add-products-toggle-password i');
        if (type === 'text') {
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    async openModal(orderId, paymentNumber) {
        this.currentOrderId = orderId;
        this.currentPaymentNumber = paymentNumber;
        this.selectedProducts = [];
        
        // Update modal display
        document.getElementById('modal-order-id-display').textContent = orderId;
        document.getElementById('modal-payment-number-display').textContent = paymentNumber;
        
        // Load current order items count
        await this.loadCurrentOrderItems(orderId, paymentNumber);
        
        // Load available products
        await this.loadProducts();
        
        // Reset UI
        this.updateSelectedProductsList();
        this.updateTotals();
        this.notesInput.value = '';
        
        // Show modal
        this.modal.classList.remove('hidden');
        this.modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        
        // Focus on search input
        setTimeout(() => this.searchInput.focus(), 100);
    }

    closeModal() {
        this.modal.classList.remove('flex');
        this.modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        this.selectedProducts = [];
        this.currentOrderId = null;
        this.currentPaymentNumber = null;
    }

    openAdminPasswordModal() {
        if (this.selectedProducts.length === 0) {
            this.showStatusMessage('Please select at least one product to add', 'bg-yellow-600');
            return;
        }
        
        // Set modal values
        document.getElementById('add-products-admin-order-id').textContent = this.currentOrderId;
        document.getElementById('add-products-admin-payment-number').textContent = this.currentPaymentNumber;
        document.getElementById('add-products-admin-product-count').textContent = this.selectedProducts.length;
        
        // Calculate and display total
        const subtotal = this.selectedProducts.reduce((sum, product) => sum + product.totalPrice, 0);
        document.getElementById('add-products-admin-total').textContent = `₱${subtotal.toFixed(2)}`;
        
        // Display products list
        const productsList = document.getElementById('add-products-admin-products-list');
        productsList.innerHTML = this.selectedProducts.map(product => `
            <div class="flex justify-between items-center">
                <span class="text-gray-300 truncate">${product.menuName}</span>
                <div class="flex items-center space-x-2">
                    <span class="text-blue-400 text-xs">×${product.quantity}</span>
                    <span class="text-amber-400 text-xs">₱${product.totalPrice.toFixed(2)}</span>
                </div>
            </div>
        `).join('');
        
        // Reset form
        this.adminPasswordInput.value = '';
        this.adminPasswordError.classList.add('hidden');
        this.adminErrorElement.textContent = '';
        
        // Open admin modal
        this.adminPasswordModal.classList.remove('hidden');
        this.adminPasswordModal.classList.add('flex');
        
        // Focus on password input
        setTimeout(() => this.adminPasswordInput.focus(), 100);
    }

    closeAdminPasswordModal() {
        this.adminPasswordModal.classList.remove('flex');
        this.adminPasswordModal.classList.add('hidden');
    }

    async loadCurrentOrderItems(orderId, paymentNumber) {
        try {
            // Fetch current order items from API
            const response = await fetch(`/api/staff/orders?orderId=${orderId}&paymentNumber=${paymentNumber}`, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                const currentItems = data.length || 0;
                document.getElementById('modal-current-items').textContent = currentItems;
            }
        } catch (error) {
            console.error('Error loading current order items:', error);
            document.getElementById('modal-current-items').textContent = '0';
        }
    }

    async loadProducts() {
        try {
            // Show loading state
            this.productsList.innerHTML = `
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                    <p>Loading products...</p>
                </div>
            `;
            
            // Fetch products from API
            const response = await fetch('/api/staff/menu/products', {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            console.log('Response status:', response.status);
            console.log('Response URL:', response.url);
            
            if (!response.ok) {
                // Try to get error details
                let errorText = '';
                try {
                    const errorData = await response.json();
                    errorText = JSON.stringify(errorData);
                } catch (e) {
                    errorText = await response.text();
                }
                
                console.error('Error details:', errorText);
                throw new Error(`HTTP ${response.status}: ${response.statusText}. Details: ${errorText}`);
            }
            
            const products = await response.json();
            console.log('Products loaded:', products.length);
            this.displayProducts(products);
            
        } catch (error) {
            console.error('Error loading products:', error);
            this.productsList.innerHTML = `
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                    <p>Failed to load products</p>
                    <p class="text-sm">Error: ${error.message}</p>
                    <p class="text-xs mt-2">Check console for details</p>
                </div>
            `;
        }
    }

    displayProducts(products) {
        if (!products || products.length === 0) {
            this.productsList.innerHTML = `
                <div class="text-center text-gray-500 py-8 col-span-full">
                    <i class="fas fa-box-open text-2xl mb-2"></i>
                    <p>No products available</p>
                </div>
            `;
            return;
        }
        
        this.productsList.innerHTML = products.map(product => `
            <div class="product-card bg-gray-800 rounded-lg p-3 border border-gray-700 hover:border-blue-500 transition cursor-pointer" data-product-id="${product.menuID}" data-category="${product.menuCategory}">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h4 class="text-white font-medium text-sm">${product.menuName}</h4>
                        <div class="flex items-center mt-1">
                            <span class="text-xs px-2 py-1 rounded ${this.getCategoryClass(product.menuCategory)}">
                                ${this.formatCategory(product.menuCategory)}
                            </span>
                        </div>
                    </div>
                    <span class="text-amber-400 font-bold">₱${parseFloat(product.menuPrice).toFixed(2)}</span>
                </div>
                <div class="text-xs text-gray-400 mb-3">
                    ID: ${product.menuID}
                </div>
                <button class="add-product-btn w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded text-sm transition" data-product='${JSON.stringify(product).replace(/'/g, "\\'")}'>
                    <i class="fas fa-plus mr-1"></i> Add to Order
                </button>
            </div>
        `).join('');
        
        // Add event listeners to product cards and buttons
        document.querySelectorAll('.add-product-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const product = JSON.parse(btn.dataset.product);
                this.addProduct(product);
            });
        });
        
        // Also make entire card clickable
        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('click', (e) => {
                if (!e.target.closest('.add-product-btn')) {
                    const product = JSON.parse(card.querySelector('.add-product-btn').dataset.product);
                    this.addProduct(product);
                }
            });
        });
    }

    getCategoryClass(category) {
        switch(category) {
            case 'main-course': return 'bg-red-900 text-red-200';
            case 'appetizers': return 'bg-green-900 text-green-200';
            case 'drinks': return 'bg-blue-900 text-blue-200';
            default: return 'bg-gray-700 text-gray-300';
        }
    }

    formatCategory(category) {
        return category.split('-').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
    }

    addProduct(product) {
        // Check if product already exists in selection
        const existingIndex = this.selectedProducts.findIndex(p => p.menuID === product.menuID);
        
        if (existingIndex > -1) {
            // Increase quantity if already exists
            this.selectedProducts[existingIndex].quantity += 1;
            this.selectedProducts[existingIndex].totalPrice = this.selectedProducts[existingIndex].quantity * this.selectedProducts[existingIndex].unitPrice;
        } else {
            // Add new product
            this.selectedProducts.push({
                ...product,
                quantity: 1,
                unitPrice: parseFloat(product.menuPrice),
                totalPrice: parseFloat(product.menuPrice),
                notes: ''
            });
        }
        
        // Update UI
        this.updateSelectedProductsList();
        this.updateTotals();
        
        // Show success message
        this.showStatusMessage(`Added ${product.menuName} to selection`, 'bg-green-600');
    }

    updateSelectedProductsList() {
        if (this.selectedProducts.length === 0) {
            this.selectedProductsList.innerHTML = `
                <div class="text-center text-gray-500 py-4">
                    <i class="fas fa-shopping-cart text-lg mb-2"></i>
                    <p class="text-sm">No products selected</p>
                </div>
            `;
            document.getElementById('quantity-controls').classList.add('hidden');
            return;
        }
        
        this.selectedProductsList.innerHTML = this.selectedProducts.map((product, index) => `
            <div class="selected-product-item bg-gray-800 rounded p-3 border border-gray-700">
                <div class="flex justify-between items-center mb-1">
                    <div class="flex-1">
                        <span class="text-white text-sm font-medium">${product.menuName}</span>
                        <span class="text-blue-400 text-xs ml-2">×${product.quantity}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-amber-400 text-sm font-medium">₱${product.totalPrice.toFixed(2)}</span>
                        <button class="edit-product-btn text-blue-400 hover:text-blue-300" data-index="${index}" title="Edit quantity">
                            <i class="fas fa-edit text-xs"></i>
                        </button>
                        <button class="remove-product-btn text-red-400 hover:text-red-300" data-index="${index}" title="Remove">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>
                </div>
                ${product.notes ? `
                <div class="text-xs text-gray-400 mt-1">
                    <i class="fas fa-sticky-note mr-1"></i>${product.notes}
                </div>
                ` : ''}
            </div>
        `).join('');
        
        // Add event listeners to edit and remove buttons
        document.querySelectorAll('.edit-product-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const index = parseInt(e.target.closest('button').dataset.index);
                this.editProduct(index);
            });
        });
        
        document.querySelectorAll('.remove-product-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const index = parseInt(e.target.closest('button').dataset.index);
                this.removeProduct(index);
            });
        });
    }

    editProduct(index) {
        const product = this.selectedProducts[index];
        document.getElementById('selected-product-name').textContent = product.menuName;
        document.getElementById('quantity-display').textContent = product.quantity;
        document.getElementById('quantity-controls').classList.remove('hidden');
        
        // Store current editing index
        document.getElementById('update-qty').dataset.editingIndex = index;
        
        // Focus on quantity controls
        document.getElementById('decrease-qty').focus();
    }

    adjustQuantity(change) {
        const currentQty = parseInt(document.getElementById('quantity-display').textContent);
        const newQty = Math.max(1, currentQty + change);
        document.getElementById('quantity-display').textContent = newQty;
    }

    updateQuantity() {
        const index = parseInt(document.getElementById('update-qty').dataset.editingIndex);
        const newQty = parseInt(document.getElementById('quantity-display').textContent);
        
        if (index >= 0 && this.selectedProducts[index]) {
            this.selectedProducts[index].quantity = newQty;
            this.selectedProducts[index].totalPrice = newQty * this.selectedProducts[index].unitPrice;
            
            // Update UI
            this.updateSelectedProductsList();
            this.updateTotals();
            document.getElementById('quantity-controls').classList.add('hidden');
            
            this.showStatusMessage('Quantity updated', 'bg-blue-600');
        }
    }

    removeProduct(index) {
        if (index >= 0 && this.selectedProducts[index]) {
            const productName = this.selectedProducts[index].menuName;
            this.selectedProducts.splice(index, 1);
            this.updateSelectedProductsList();
            this.updateTotals();
            this.showStatusMessage(`Removed ${productName} from selection`, 'bg-red-600');
        }
    }

    clearSelection() {
        if (this.selectedProducts.length === 0) return;
        
        this.selectedProducts = [];
        this.updateSelectedProductsList();
        this.updateTotals();
        this.showStatusMessage('All products cleared from selection', 'bg-gray-600');
    }

    updateTotals() {
        const subtotal = this.selectedProducts.reduce((sum, product) => sum + product.totalPrice, 0);
        const vat = subtotal * 0.107; // 10.7% VAT
        const total = subtotal; // Total without VAT included
        
        document.getElementById('subtotal-display').textContent = `₱${subtotal.toFixed(2)}`;
        document.getElementById('vat-display').textContent = `₱${vat.toFixed(2)}`;
        document.getElementById('total-display').textContent = `₱${total.toFixed(2)}`;
    }

    filterProducts(searchTerm) {
        const products = document.querySelectorAll('.product-card');
        const term = searchTerm.toLowerCase().trim();
        
        products.forEach(card => {
            const productName = card.querySelector('h4').textContent.toLowerCase();
            const productId = card.querySelector('.text-xs').textContent.toLowerCase();
            const category = card.dataset.category;
            
            const matchesSearch = productName.includes(term) || productId.includes(term);
            const matchesCategory = document.querySelector('.category-btn.active').dataset.category === 'all' || 
                                  category === document.querySelector('.category-btn.active').dataset.category;
            
            card.style.display = matchesSearch && matchesCategory ? 'block' : 'none';
        });
    }

    filterByCategory(category) {
        const products = document.querySelectorAll('.product-card');
        
        products.forEach(card => {
            if (category === 'all' || card.dataset.category === category) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    async confirmAddProductsWithAuth() {
        const password = this.adminPasswordInput.value.trim();
        
        if (!password) {
            this.showAdminError('Please enter the Admin password');
            return;
        }
        
        if (this.selectedProducts.length === 0) {
            this.showAdminError('No products selected to add');
            return;
        }
        
        if (!this.currentOrderId || !this.currentPaymentNumber) {
            this.showAdminError('Order information missing');
            return;
        }
        
        try {
            // Prepare data for API call
            const productsData = this.selectedProducts.map(product => ({
                name: product.menuName,
                quantity: product.quantity,
                unitPrice: product.unitPrice,
                totalPrice: product.totalPrice,
                notes: this.notesInput.value.trim() || null,
                menuID: product.menuID
            }));
            
            const requestData = {
                orderID: this.currentOrderId,
                paymentNumber: this.currentPaymentNumber,
                products: productsData,
                staffName: document.querySelector('.font-semibold.text-white').textContent || 'Staff Member',
                adminPassword: password // Include admin password
            };
            
            // Show loading state
            const confirmBtn = this.confirmAddProductsWithAuthBtn;
            const originalText = confirmBtn.innerHTML;
            confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Adding...';
            confirmBtn.disabled = true;
            
            // Call API to add products to order with admin authentication
            const response = await fetch('/api/staff/orders/add-products', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(requestData)
            });
            
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || `HTTP ${response.status}: ${response.statusText}`);
            }
            
            const result = await response.json();
            
            if (result.success) {
                // Close both modals
                this.closeAdminPasswordModal();
                this.closeModal();
                
                // Show success message
                this.showStatusMessage(`${result.added_count} product(s) added to order successfully`, 'bg-green-600');
                
                // Reload orders to reflect changes
                if (typeof loadOrders === 'function') {
                    setTimeout(() => loadOrders(), 1000);
                }
            } else {
                throw new Error(result.message || 'Failed to add products');
            }
            
        } catch (error) {
            console.error('Error adding products:', error);
            
            // Check if it's an admin password error
            const errorMessage = error.message.includes('Invalid admin password') 
                ? 'Invalid Admin password. Please try again.'
                : error.message.includes('No admin user found')
                ? 'No Admin user found in system.'
                : `Error: ${error.message}`;
            
            this.showAdminError(errorMessage);
            
            // Reset button state
            const confirmBtn = this.confirmAddProductsWithAuthBtn;
            confirmBtn.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Confirm Add Products';
            confirmBtn.disabled = false;
            
            // Clear password field
            this.adminPasswordInput.value = '';
            this.adminPasswordInput.focus();
        }
    }

    showAdminError(message) {
        this.adminErrorElement.textContent = message;
        this.adminPasswordError.classList.remove('hidden');
        
        // Auto-hide error after 5 seconds
        setTimeout(() => {
            this.adminPasswordError.classList.add('hidden');
        }, 5000);
    }

    showStatusMessage(message, bgColor = 'bg-blue-600') {
        // Create status message element if it doesn't exist
        let statusMsg = document.getElementById('add-products-status-message');
        if (!statusMsg) {
            statusMsg = document.createElement('div');
            statusMsg.id = 'add-products-status-message';
            statusMsg.className = `fixed bottom-20 right-4 ${bgColor} text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transform translate-y-4 transition-all duration-300 z-50`;
            document.body.appendChild(statusMsg);
        }
        
        statusMsg.textContent = message;
        statusMsg.className = `fixed bottom-20 right-4 ${bgColor} text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transform translate-y-4 transition-all duration-300 z-50`;
        
        // Show message
        statusMsg.classList.remove('opacity-0', 'translate-y-4');
        statusMsg.classList.add('opacity-100', 'translate-y-0');
        
        // Hide message after 3 seconds
        setTimeout(() => {
            statusMsg.classList.remove('opacity-100', 'translate-y-0');
            statusMsg.classList.add('opacity-0', 'translate-y-4');
        }, 3000);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.addProductsFeature = new AddProductsFeature();
});

// Function to open the add products modal from other scripts
function openAddProductsModal(orderId, paymentNumber) {
    if (window.addProductsFeature) {
        window.addProductsFeature.openModal(orderId, paymentNumber);
    }
}