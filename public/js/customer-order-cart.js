// customer-order-cart.js
// Cart/order management functionality

// Add item to order
function addToOrder(name, price, category, image = '', source = 'manual') {
    console.log(`Adding item to order (source: ${source}):`, name);
    
    const existingItemIndex = globalState.orderItems.findIndex(item => item.name === name);

    if (existingItemIndex !== -1) {
        if (source === 'voice-auto') {
            console.log('Voice auto-add: Item already exists, not incrementing:', name);
        } else {
            globalState.orderItems[existingItemIndex].quantity++;
            console.log('Increased quantity for existing item:', name);
        }
    } else {
        globalState.orderItems.push({
            name: name,
            price: parseFloat(price),
            category: category,
            quantity: 1,
            image: image
        });
        console.log('Added new item to order:', name);
    }

    renderOrderItems();
    calculateTotals();

    if (source === 'manual') {
        const addBtn = event?.target?.closest('.add-to-order-btn');
        if (addBtn) {
            const originalText = addBtn.innerHTML;
            addBtn.innerHTML = '<i class="fas fa-check mr-1"></i> Added!';
            addBtn.classList.remove('bg-amber-600');
            addBtn.classList.add('bg-green-600');

            setTimeout(() => {
                addBtn.innerHTML = originalText;
                addBtn.classList.remove('bg-green-600');
                addBtn.classList.add('bg-amber-600');
            }, 1000);
        }
    }
}

// Auto-add product to order
function autoAddProductToOrder(product, source = 'unknown') {
    console.log(`Auto-adding product to order (source: ${source}):`, product);
    
    const existingItemIndex = globalState.orderItems.findIndex(item => item.name === product.name);

    if (existingItemIndex !== -1) {
        if (source !== 'voice-auto') {
            globalState.orderItems[existingItemIndex].quantity++;
            console.log('Increased quantity for existing item:', product.name);
        } else {
            console.log('Item already in order from voice command, not incrementing:', product.name);
        }
    } else {
        globalState.orderItems.push({
            name: product.name,
            price: parseFloat(product.price),
            category: product.category,
            quantity: 1,
            image: product.image || ''
        });
        console.log('Added new item to order:', product.name);
    }

    renderOrderItems();
    calculateTotals();
    showAutoAddNotification(product.name);
}

// Show auto-add notification
function showAutoAddNotification(productName) {
    const existingNotification = document.getElementById('auto-add-notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    const notification = document.createElement('div');
    notification.id = 'auto-add-notification';
    notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg z-50 animate__animated animate__fadeInDown';
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2 text-lg"></i>
            <div>
                <p class="font-bold">Auto-added to order!</p>
                <p class="text-sm">"${productName}" has been added to your order summary.</p>
            </div>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('animate__fadeOutUp');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 3000);
}

// Remove item from order
function removeFromOrder(index) {
    globalState.orderItems.splice(index, 1);
    renderOrderItems();
    calculateTotals();
}

// Update item quantity
function updateQuantity(index, change) {
    globalState.orderItems[index].quantity += change;

    if (globalState.orderItems[index].quantity <= 0) {
        globalState.orderItems.splice(index, 1);
    }

    renderOrderItems();
    calculateTotals();
}

// Render order items list
function renderOrderItems() {
    domElements.orderItemsList.innerHTML = '';

    globalState.orderItems.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        const itemElement = document.createElement('div');
        itemElement.className = 'bg-gray-50 p-3 rounded-lg border border-gray-200 fade-in';
        itemElement.innerHTML = `
            <div class="flex items-start mb-2">
                ${item.image ? `
                <div class="w-12 h-12 rounded overflow-hidden mr-3 flex-shrink-0">
                    <img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover">
                </div>
                ` : ''}
                <div class="flex-1">
                    <div class="flex justify-between items-start">
                        <h4 class="font-bold text-gray-800 text-sm truncate">${item.name}</h4>
                        <button class="remove-item-btn text-gray-400 hover:text-red-500 ml-1 text-sm" data-index="${index}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500">${item.category}</p>
                </div>
            </div>
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <button class="quantity-btn w-6 h-6 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center" data-index="${index}" data-change="-1">
                        <i class="fas fa-minus text-xs"></i>
                    </button>
                    <span class="font-bold text-gray-800 w-6 text-center text-sm">${item.quantity}</span>
                    <button class="quantity-btn w-6 h-6 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center" data-index="${index}" data-change="1">
                        <i class="fas fa-plus text-xs"></i>
                    </button>
                </div>
                <span class="font-bold text-red-600 text-sm">${formatPrice(itemTotal)}</span>
            </div>
        `;
        domElements.orderItemsList.appendChild(itemElement);
    });

    if (globalState.orderItems.length === 0) {
        domElements.emptyOrder.style.display = 'block';
        domElements.orderItemsList.style.display = 'none';
    } else {
        domElements.emptyOrder.style.display = 'none';
        domElements.orderItemsList.style.display = 'block';
    }

    document.querySelectorAll('.remove-item-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const index = parseInt(this.getAttribute('data-index'));
            removeFromOrder(index);
        });
    });

    document.querySelectorAll('.quantity-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const index = parseInt(this.getAttribute('data-index'));
            const change = parseInt(this.getAttribute('data-change'));
            updateQuantity(index, change);
        });
    });
}

// Calculate order totals
function calculateTotals() {
    let subtotal = 0;
    globalState.orderItems.forEach(item => {
        subtotal += item.price * item.quantity;
    });

    const total = subtotal;

    domElements.subtotalElement.textContent = formatPrice(subtotal);
    domElements.totalElement.textContent = formatPrice(total);

    const totalItems = globalState.orderItems.reduce((sum, item) => sum + item.quantity, 0);

    if (globalState.orderItems.length === 0) {
        domElements.emptyOrder.classList.remove('hidden');
        domElements.orderItemsList.classList.add('hidden');
    } else {
        domElements.emptyOrder.classList.add('hidden');
        domElements.orderItemsList.classList.remove('hidden');
    }
}

// Clear entire order
function clearOrder() {
    if (globalState.orderItems.length > 0) {
        if (confirm('Clear your order?')) {
            globalState.orderItems = [];
            renderOrderItems();
            calculateTotals();
            domElements.orderNotes.value = '';
        }
    }
}

// Calculate order total for external use
function calculateOrderTotal() {
    let subtotal = 0;
    globalState.orderItems.forEach(item => {
        subtotal += item.price * item.quantity;
    });
    return subtotal;
}

// Initialize cart event listeners
function initializeCartEventListeners() {
    if (domElements.clearOrderBtn) domElements.clearOrderBtn.addEventListener('click', clearOrder);
}

// Export functions
window.addToOrder = addToOrder;
window.autoAddProductToOrder = autoAddProductToOrder;
window.removeFromOrder = removeFromOrder;
window.updateQuantity = updateQuantity;
window.renderOrderItems = renderOrderItems;
window.calculateTotals = calculateTotals;
window.calculateOrderTotal = calculateOrderTotal;
window.clearOrder = clearOrder;
window.initializeCartEventListeners = initializeCartEventListeners;