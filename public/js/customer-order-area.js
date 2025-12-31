// Navigation data - updated based on database categories
const navigationData = {
    'main-course': ['Pork', 'Chicken', 'Beef', 'Fish & Seafood', 'Pasta', 'Noodles'],
    'appetizers': ['Salads', 'Knick/Knacks', 'Sandwiches'],
    'drinks': ['Hot', 'Iced', 'Frappe', 'Milktea'],
    'specials': []
};

// Category mapping for database
const categoryMap = {
    'main-course': 'main-course',
    'appetizers': 'appetizers',
    'drinks': 'drinks',
    'specials': 'specials'
};

// Subcategory mapping for database
const subcategoryMap = {
    'Pork': 'pork',
    'Chicken': 'chicken',
    'Beef': 'beef',
    'Fish & Seafood': 'fish-seafood',
    'Pasta': 'pasta',
    'Noodles': 'noodles',
    'Salads': 'salads',
    'Knick/Knacks': 'knick-knacks',
    'Sandwiches': 'sandwiches',
    'Hot': 'hot',
    'Iced': 'iced',
    'Frappe': 'frappe',
    'Milktea': 'milktea'
};

// Order data
let orderItems = [];
const TAX_RATE = 0.12;
let orderType = 'dine-in'; // Default order type
let paymentMethod = 'cash'; // Default payment method

// Voice chat state
let isListening = false;

// Carousel state
let currentSlide = 0;
let currentCategory = 'main-course';
let currentSubcategory = 'pork';
let totalSlides = 1;
let allSlides = []; // Store all slide HTML

// Get DOM elements
let upperNavBtns, lowerNav, orderItemsList, emptyOrder, subtotalElement, taxElement, totalElement;
let estimatedTime, timeProgress, clearOrderBtn, checkoutBtn, voiceStartBtn, voiceStopBtn;
let voiceHelpBtn, voiceStatus, voiceFeedback, voiceCommandDisplay, voiceTranscript;
let orderTypeDropdown, cashBtn, electronicBtn, orderTypeInfo, paymentInfo;
let carouselSlides, carouselPrev, carouselNext, carouselIndicators;

// Modal elements
let checkoutModal, confirmOrderBtn, cancelOrderBtn, orderDetailsList;

// Initialize DOM elements
function initializeDOMElements() {
    upperNavBtns = document.querySelectorAll('.upper-nav-btn');
    lowerNav = document.getElementById('lower-nav');
    orderItemsList = document.getElementById('order-items-list');
    emptyOrder = document.getElementById('empty-order');
    subtotalElement = document.getElementById('subtotal');
    taxElement = document.getElementById('tax');
    totalElement = document.getElementById('total');
    estimatedTime = document.getElementById('estimated-time');
    timeProgress = document.getElementById('time-progress');
    clearOrderBtn = document.getElementById('clear-order-btn');
    checkoutBtn = document.getElementById('checkout-btn');
    voiceStartBtn = document.getElementById('voice-start');
    voiceStopBtn = document.getElementById('voice-stop');
    voiceHelpBtn = document.getElementById('voice-help');
    voiceStatus = document.getElementById('voice-status');
    voiceFeedback = document.getElementById('voice-feedback');
    voiceCommandDisplay = document.getElementById('voice-command-display');
    voiceTranscript = document.getElementById('voice-transcript');
    orderTypeDropdown = document.getElementById('order-type-dropdown');
    cashBtn = document.getElementById('cash-btn');
    electronicBtn = document.getElementById('electronic-btn');
    orderTypeInfo = document.getElementById('order-type-info');
    paymentInfo = document.getElementById('payment-info');
    carouselSlides = document.getElementById('carousel-slides');
    carouselPrev = document.getElementById('carousel-prev');
    carouselNext = document.getElementById('carousel-next');
    carouselIndicators = document.getElementById('carousel-indicators');
}

// Create checkout modal
function createCheckoutModal() {
    // Check if modal already exists
    if (document.getElementById('checkout-modal')) return;

    const modalHTML = `
        <div id="checkout-modal" class="fixed inset-0 bg-black bg-opacity-0 flex items-center justify-center z-50 hidden">
            <div id="checkout-modal-content" class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-hidden flex flex-col opacity-0">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-green-600 to-green-500 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-white">
                                <i class="fas fa-shopping-cart mr-3"></i>Order Confirmation
                            </h2>
                            <p class="text-green-100 text-sm mt-1">Please review your order before confirming</p>
                        </div>
                        <button id="close-modal" class="text-white hover:text-green-200 text-xl transition-transform hover:rotate-90 duration-300">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Modal Body -->
                <div class="flex-1 p-6 overflow-y-auto">
                    <!-- Order Type & Payment -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 transform transition-all duration-500 hover:scale-[1.02]">
                            <h4 class="font-bold text-gray-800 text-sm mb-1 flex items-center">
                                <i class="fas fa-store mr-2 text-blue-500"></i> Order Type
                            </h4>
                            <p id="modal-order-type" class="text-gray-700 font-medium">Dine-in</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg border border-green-100 transform transition-all duration-500 hover:scale-[1.02]">
                            <h4 class="font-bold text-gray-800 text-sm mb-1 flex items-center">
                                <i class="fas fa-credit-card mr-2 text-green-500"></i> Payment Method
                            </h4>
                            <p id="modal-payment-method" class="text-gray-700 font-medium">Cash</p>
                        </div>
                    </div>
                    
                    <!-- Order Items -->
                    <div class="mb-6">
                        <h3 class="font-bold text-gray-800 text-lg mb-3 flex items-center">
                            <i class="fas fa-utensils mr-2 text-amber-600"></i> Order Items
                        </h3>
                        <div id="order-details-list" class="space-y-3 max-h-60 overflow-y-auto p-2">
                            <!-- Order items will be dynamically added here -->
                        </div>
                    </div>
                    
                    <!-- Order Notes -->
                    <div class="mb-6">
                        <h4 class="font-bold text-gray-800 text-sm mb-2 flex items-center">
                            <i class="fas fa-sticky-note mr-2 text-gray-500"></i> Order Notes
                        </h4>
                        <div id="modal-order-notes" class="bg-gray-50 p-3 rounded-lg border border-gray-200 text-gray-700 text-sm">
                            <!-- Order notes will be displayed here -->
                        </div>
                    </div>
                    
                    <!-- Order Totals -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 transform transition-all duration-500 hover:scale-[1.02]">
                        <div class="space-y-2">
                            <div class="flex justify-between text-gray-600 text-sm">
                                <span>Subtotal:</span>
                                <span id="modal-subtotal">₱0.00</span>
                            </div>
                            <div class="flex justify-between text-gray-600 text-sm">
                                <span>Tax (12%):</span>
                                <span id="modal-tax">₱0.00</span>
                            </div>
                            <div class="flex justify-between font-bold text-gray-800 pt-2 border-t border-gray-300 text-lg">
                                <span>Total:</span>
                                <span id="modal-total">₱0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="border-t border-gray-200 p-6 bg-gray-50">
                    <div class="flex flex-col md:flex-row gap-3">
                        <button id="cancel-order-btn" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-3 rounded-lg font-bold transition-all duration-300 flex items-center justify-center transform hover:-translate-y-1">
                            <i class="fas fa-times mr-2"></i> Cancel
                        </button>
                        <button id="confirm-order-btn" class="flex-1 bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white py-3 rounded-lg font-bold transition-all duration-300 flex items-center justify-center hover-lift pulse-once">
                            <i class="fas fa-check-circle mr-2"></i> Confirm Order
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 text-center mt-4">
                        <i class="fas fa-shield-alt mr-1"></i> Your order is secure and will be processed immediately
                    </p>
                </div>
            </div>
        </div>
    `;

    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHTML);

    // Get modal elements
    checkoutModal = document.getElementById('checkout-modal');
    const checkoutModalContent = document.getElementById('checkout-modal-content');
    confirmOrderBtn = document.getElementById('confirm-order-btn');
    cancelOrderBtn = document.getElementById('cancel-order-btn');
    orderDetailsList = document.getElementById('order-details-list');
    
    // Add event listeners to modal
    document.getElementById('close-modal').addEventListener('click', closeCheckoutModal);
    cancelOrderBtn.addEventListener('click', closeCheckoutModal);
    confirmOrderBtn.addEventListener('click', confirmOrder);
    
    // Close modal when clicking outside
    checkoutModal.addEventListener('click', function(e) {
        if (e.target === checkoutModal) {
            closeCheckoutModal();
        }
    });
}

// Create payment queue modal
function createPaymentQueueModal() {
    // Check if modal already exists
    if (document.getElementById('payment-queue-modal')) return;

    const modalHTML = `
        <div id="payment-queue-modal" class="fixed inset-0 bg-black bg-opacity-0 flex items-center justify-center z-50 hidden">
            <div id="payment-queue-modal-content" class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 opacity-0 transform scale-95">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-500 p-6 rounded-t-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-white p-2 rounded-full mr-3">
                                <i class="fas fa-receipt text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white">
                                    Payment Queue
                                </h2>
                                <p class="text-blue-100 text-sm mt-1">Order Processing</p>
                            </div>
                        </div>
                        <button id="close-payment-modal" class="text-white hover:text-blue-200 text-lg transition-transform hover:rotate-90 duration-300">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Modal Body -->
                <div class="p-6">
                    <!-- Success Icon -->
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-3xl"></i>
                        </div>
                    </div>
                    
                    <!-- Message -->
                    <div class="text-center mb-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Order Sent to Payment Queue</h3>
                        <p class="text-gray-600 mb-4">
                            Please collect your payment number beside the Order System.
                        </p>
                        
                        <!-- Payment Number Dropdown -->
                        <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4 mb-4">
                            <label for="payment-number-dropdown" class="block text-sm font-medium text-gray-700 mb-2 text-center">
                                <i class="fas fa-ticket-alt mr-1"></i> Select Your Payment Number
                            </label>
                            <div class="relative">
                                <select id="payment-number-dropdown" class="w-full bg-white border border-blue-300 rounded-lg py-2.5 px-4 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none text-center text-lg font-bold">
                                    <option value="">-- Select a number --</option>
                                    <!-- Numbers 1-20 will be dynamically added -->
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 text-center">
                                <i class="fas fa-info-circle mr-1"></i> Present this number at the payment counter
                            </p>
                        </div>
                        
                        <!-- Selected Payment Number Display -->
                        <div id="selected-number-display" class="hidden mb-4">
                            <div class="bg-green-50 border-2 border-green-300 rounded-lg p-3">
                                <p class="text-sm text-gray-600 mb-1 text-center">Your Selected Payment Number:</p>
                                <div class="text-3xl font-bold text-green-700 tracking-wider text-center" id="display-selected-number">
                                    <!-- Selected number will be displayed here -->
                                </div>
                            </div>
                        </div>
                        
                        <!-- Estimated Wait Time -->
                        <div class="bg-amber-50 border border-amber-100 rounded-lg p-3">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-clock text-amber-500 mr-2"></i>
                                <span class="text-sm text-amber-700">
                                    Estimated wait time: <span class="font-bold">5-10 minutes</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Order Summary -->
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Total Items:</span>
                            <span id="queue-item-count">0</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Order Type:</span>
                            <span id="queue-order-type">Dine-in</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Payment Method:</span>
                            <span id="queue-payment-method">Cash</span>
                        </div>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="border-t border-gray-200 p-6 bg-gray-50 rounded-b-xl">
                    <button id="confirm-payment-btn" class="w-full bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white py-3 rounded-lg font-bold transition-all duration-300 flex items-center justify-center hover-lift disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <i class="fas fa-check-circle mr-2"></i> Confirm
                    </button>
                    <p class="text-xs text-gray-500 text-center mt-4">
                        <i class="fas fa-info-circle mr-1"></i> Select a payment number to continue
                    </p>
                </div>
            </div>
        </div>
    `;

    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHTML);

    // Get modal elements
    const paymentQueueModal = document.getElementById('payment-queue-modal');
    const paymentQueueModalContent = document.getElementById('payment-queue-modal-content');
    const confirmPaymentBtn = document.getElementById('confirm-payment-btn');
    const paymentNumberDropdown = document.getElementById('payment-number-dropdown');
    const selectedNumberDisplay = document.getElementById('selected-number-display');
    const displaySelectedNumber = document.getElementById('display-selected-number');
    
    // Populate dropdown with numbers 1-20
    for (let i = 1; i <= 20; i++) {
        const option = document.createElement('option');
        option.value = i;
        option.textContent = `#${i}`;
        paymentNumberDropdown.appendChild(option);
    }
    
    // Add event listener to dropdown
    paymentNumberDropdown.addEventListener('change', function() {
        const selectedValue = this.value;
        const confirmBtn = document.getElementById('confirm-payment-btn');
        
        if (selectedValue) {
            // Show selected number display
            selectedNumberDisplay.classList.remove('hidden');
            displaySelectedNumber.textContent = `#${selectedValue}`;
            
            // Enable confirm button
            confirmBtn.disabled = false;
            confirmBtn.classList.remove('disabled:opacity-50', 'disabled:cursor-not-allowed');
            
            // Update instruction text
            document.querySelector('#payment-queue-modal-content .text-xs.text-gray-500.text-center').innerHTML = `
                <i class="fas fa-info-circle mr-1"></i> Your order is now being prepared
            `;
        } else {
            // Hide selected number display
            selectedNumberDisplay.classList.add('hidden');
            
            // Disable confirm button
            confirmBtn.disabled = true;
            confirmBtn.classList.add('disabled:opacity-50', 'disabled:cursor-not-allowed');
            
            // Reset instruction text
            document.querySelector('#payment-queue-modal-content .text-xs.text-gray-500.text-center').innerHTML = `
                <i class="fas fa-info-circle mr-1"></i> Select a payment number to continue
            `;
        }
    });
    
    // Add event listeners
    document.getElementById('close-payment-modal').addEventListener('click', closePaymentQueueModal);
    confirmPaymentBtn.addEventListener('click', confirmPaymentQueue);
    
    // Close modal when clicking outside
    paymentQueueModal.addEventListener('click', function(e) {
        if (e.target === paymentQueueModal) {
            closePaymentQueueModal();
        }
    });
}

// Show checkout modal with animation
function showCheckoutModal() {
    if (orderItems.length === 0) {
        // Add shake animation to checkout button
        checkoutBtn.classList.add('animate-pulse');
        // Add shake animation to empty cart
        emptyOrder.classList.add('empty-cart-shake');
        
        setTimeout(() => {
            checkoutBtn.classList.remove('animate-pulse');
            emptyOrder.classList.remove('empty-cart-shake');
        }, 500);
        
        alert('Please add items to your order before checking out.');
        return;
    }

    // Update modal content
    updateModalContent();
    
    // Show modal
    checkoutModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Trigger animation
    setTimeout(() => {
        checkoutModal.classList.add('modal-bg-show');
        checkoutModal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
        
        const modalContent = document.getElementById('checkout-modal-content');
        modalContent.classList.add('modal-show');
        modalContent.style.opacity = '1';
        
        // Add staggered animation to items
        const orderItems = document.querySelectorAll('#order-details-list > div');
        orderItems.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateX(-20px)';
            
            setTimeout(() => {
                item.style.transition = 'all 0.4s ease';
                item.style.opacity = '1';
                item.style.transform = 'translateX(0)';
            }, 100 * index);
        });
    }, 10);
}

// Close checkout modal with animation
function closeCheckoutModal() {
    // Reverse animation
    const modalContent = document.getElementById('checkout-modal-content');
    modalContent.classList.remove('modal-show');
    modalContent.style.opacity = '0';
    modalContent.style.transform = 'translateY(-50px) scale(0.95)';
    
    checkoutModal.classList.remove('modal-bg-show');
    checkoutModal.style.backgroundColor = 'rgba(0, 0, 0, 0)';
    
    // Hide modal after animation completes
    setTimeout(() => {
        checkoutModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        
        // Reset modal content styles
        modalContent.classList.remove('modal-show');
        modalContent.style.opacity = '';
        modalContent.style.transform = '';
        checkoutModal.style.backgroundColor = '';
    }, 300);
}

// Show payment queue modal
function showPaymentQueueModal() {
    // Update order info
    const totalItems = orderItems.reduce((sum, item) => sum + item.quantity, 0);
    document.getElementById('queue-item-count').textContent = totalItems;
    document.getElementById('queue-order-type').textContent = orderType === 'dine-in' ? 'Dine-in' : 'Takeout';
    document.getElementById('queue-payment-method').textContent = paymentMethod === 'cash' ? 'Cash' : 'Electronic Payment';
    
    // Reset dropdown and display
    const paymentNumberDropdown = document.getElementById('payment-number-dropdown');
    const selectedNumberDisplay = document.getElementById('selected-number-display');
    const confirmBtn = document.getElementById('confirm-payment-btn');
    
    paymentNumberDropdown.value = '';
    selectedNumberDisplay.classList.add('hidden');
    confirmBtn.disabled = true;
    confirmBtn.classList.add('disabled:opacity-50', 'disabled:cursor-not-allowed');
    
    // Reset instruction text
    const instructionText = document.querySelector('#payment-queue-modal-content .text-xs.text-gray-500.text-center');
    if (instructionText) {
        instructionText.innerHTML = `
            <i class="fas fa-info-circle mr-1"></i> Select a payment number to continue
        `;
    }
    
    // Show modal
    const paymentQueueModal = document.getElementById('payment-queue-modal');
    const paymentQueueModalContent = document.getElementById('payment-queue-modal-content');
    
    paymentQueueModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Trigger animation
    setTimeout(() => {
        paymentQueueModal.classList.add('modal-bg-show');
        paymentQueueModal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
        
        paymentQueueModalContent.classList.add('modal-show');
        paymentQueueModalContent.style.opacity = '1';
        paymentQueueModalContent.style.transform = 'scale(1)';
    }, 10);
}

// Close payment queue modal
function closePaymentQueueModal() {
    const paymentQueueModal = document.getElementById('payment-queue-modal');
    const paymentQueueModalContent = document.getElementById('payment-queue-modal-content');
    
    // Reverse animation
    paymentQueueModalContent.classList.remove('modal-show');
    paymentQueueModalContent.style.opacity = '0';
    paymentQueueModalContent.style.transform = 'scale(0.95)';
    
    paymentQueueModal.classList.remove('modal-bg-show');
    paymentQueueModal.style.backgroundColor = 'rgba(0, 0, 0, 0)';
    
    // Hide modal after animation completes
    setTimeout(() => {
        paymentQueueModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        
        // Reset modal content styles
        paymentQueueModalContent.classList.remove('modal-show');
        paymentQueueModalContent.style.opacity = '';
        paymentQueueModalContent.style.transform = '';
        paymentQueueModal.style.backgroundColor = '';
    }, 300);
}

// Confirm payment queue and redirect
function confirmPaymentQueue() {
    // Add animation to confirm button
    const confirmBtn = document.getElementById('confirm-payment-btn');
    confirmBtn.classList.add('success-animation');
    
    // Get selected payment number
    const paymentNumberDropdown = document.getElementById('payment-number-dropdown');
    const selectedPaymentNumber = paymentNumberDropdown.value;
    
    if (!selectedPaymentNumber) {
        alert('Please select a payment number before confirming.');
        confirmBtn.classList.remove('success-animation');
        return;
    }
    
    // Store order data before clearing
    const orderData = {
        items: [...orderItems],
        orderType: orderType,
        paymentMethod: paymentMethod,
        total: calculateOrderTotal(),
        paymentNumber: selectedPaymentNumber,
        timestamp: new Date().toISOString()
    };
    
    // You can save this to localStorage or send to server here
    localStorage.setItem('lastOrder', JSON.stringify(orderData));
    
    setTimeout(() => {
        // Close modal
        closePaymentQueueModal();
        
        // Clear order
        orderItems = [];
        renderOrderItems();
        calculateTotals();
        document.getElementById('order-notes').value = '';
        
        // Redirect to landing page after a brief delay
        setTimeout(() => {
            window.location.href = '/customer/home';
        }, 500);
        
        confirmBtn.classList.remove('success-animation');
    }, 300);
}

// Confirm order with animation
function confirmOrder() {
    // Add success animation to confirm button
    confirmOrderBtn.classList.add('success-animation');
    
    setTimeout(() => {
        // First, close the checkout modal
        closeCheckoutModal();
        
        // Then show the payment queue confirmation
        setTimeout(() => {
            showPaymentQueueModal();
        }, 100);
        
        // Remove animation class
        confirmOrderBtn.classList.remove('success-animation');
    }, 300);
}

// Helper function to calculate total
function calculateOrderTotal() {
    let subtotal = 0;
    orderItems.forEach(item => {
        subtotal += item.price * item.quantity;
    });
    const tax = subtotal * TAX_RATE;
    return subtotal + tax;
}

// Update modal content
function updateModalContent() {
    // Update order type
    const orderTypeText = orderType === 'dine-in' ? 'Dine-in' : 'Takeout';
    document.getElementById('modal-order-type').textContent = orderTypeText;
    
    // Update payment method
    const paymentMethodText = paymentMethod === 'cash' ? 'Cash' : 'Electronic Payment';
    document.getElementById('modal-payment-method').textContent = paymentMethodText;
    
    // Update order items
    orderDetailsList.innerHTML = '';
    orderItems.forEach(item => {
        const itemTotal = item.price * item.quantity;
        const itemElement = document.createElement('div');
        itemElement.className = 'flex justify-between items-center p-3 bg-white rounded-lg border border-gray-100';
        itemElement.innerHTML = `
            <div class="flex-1">
                <h4 class="font-bold text-gray-800 text-sm">${item.name}</h4>
                <p class="text-xs text-gray-500">${item.category} • ${item.quantity}x</p>
            </div>
            <span class="font-bold text-green-600 text-sm">${formatPrice(itemTotal)}</span>
        `;
        orderDetailsList.appendChild(itemElement);
    });
    
    // Update order notes
    const notes = document.getElementById('order-notes').value;
    document.getElementById('modal-order-notes').textContent = notes || 'No special instructions';
    
    // Update totals
    let subtotal = 0;
    orderItems.forEach(item => {
        subtotal += item.price * item.quantity;
    });
    const tax = subtotal * TAX_RATE;
    const total = subtotal + tax;
    
    document.getElementById('modal-subtotal').textContent = formatPrice(subtotal);
    document.getElementById('modal-tax').textContent = formatPrice(tax);
    document.getElementById('modal-total').textContent = formatPrice(total);
}

// Format price to Philippine Peso
function formatPrice(price) {
    return `₱${parseFloat(price).toFixed(2)}`;
}

// Set order type
function setOrderType(type) {
    orderType = type;

    // Update info text
    if (type === 'dine-in') {
        orderTypeInfo.innerHTML = `
            <i class="fas fa-info-circle text-blue-500 mr-1"></i>
            <span>Dine-in selected. Your order will be served at your table.</span>
        `;
        orderTypeInfo.className = 'mb-4 text-xs text-gray-600 p-2 bg-blue-50 rounded border border-blue-100';
    } else {
        orderTypeInfo.innerHTML = `
            <i class="fas fa-info-circle text-green-500 mr-1"></i>
            <span>Takeout selected. Your order will be prepared for pickup.</span>
        `;
        orderTypeInfo.className = 'mb-4 text-xs text-gray-600 p-2 bg-green-50 rounded border border-green-100';
    }

    console.log(`Order type set to: ${type}`);
}

// Set payment method
function setPaymentMethod(method) {
    paymentMethod = method;
    
    // Update button styles
    if (method === 'cash') {
        cashBtn.classList.add('active', 'bg-green-600', 'text-white');
        cashBtn.classList.remove('bg-gray-200', 'text-gray-800');
        electronicBtn.classList.add('bg-gray-200', 'text-gray-800');
        electronicBtn.classList.remove('active', 'bg-blue-600', 'text-white');

        // Update info text
        paymentInfo.innerHTML = `
            <i class="fas fa-money-bill-wave text-green-500 mr-1"></i>
            <span>Cash payment selected. Pay at the counter.</span>
        `;
        paymentInfo.className = 'mt-2 text-xs text-gray-600 p-2 bg-green-50 rounded border border-green-100';
    } else {
        electronicBtn.classList.add('active', 'bg-blue-600', 'text-white');
        electronicBtn.classList.remove('bg-gray-200', 'text-gray-800');
        cashBtn.classList.add('bg-gray-200', 'text-gray-800');
        cashBtn.classList.remove('active', 'bg-green-600', 'text-white');

        // Update info text
        paymentInfo.innerHTML = `
            <i class="fas fa-credit-card text-blue-500 mr-1"></i>
            <span>Electronic payment selected. Pay via QR code at checkout.</span>
        `;
        paymentInfo.className = 'mt-2 text-xs text-gray-600 p-2 bg-blue-50 rounded border border-blue-100';
    }

    console.log(`Payment method set to: ${method}`);
}

// Calculate order totals
function calculateTotals() {
    let subtotal = 0;
    orderItems.forEach(item => {
        subtotal += item.price * item.quantity;
    });

    const tax = subtotal * TAX_RATE;
    const total = subtotal + tax;

    subtotalElement.textContent = formatPrice(subtotal);
    taxElement.textContent = formatPrice(tax);
    totalElement.textContent = formatPrice(total);

    // Update item count
    const totalItems = orderItems.reduce((sum, item) => sum + item.quantity, 0);
    
    // Update estimated time based on order type
    let additionalTime = Math.floor(totalItems / 2) * 5;

    // Takeout orders take 5 minutes less preparation time
    if (orderType === 'takeout') {
        additionalTime = Math.max(0, additionalTime - 5);
    }

    const minTime = 15 + additionalTime;
    const maxTime = 20 + additionalTime;
    estimatedTime.textContent = `${minTime}-${maxTime} mins`;

    // Update progress bar width (30% base + 5% per item, max 90%)
    const progressWidth = Math.min(30 + (totalItems * 5), 90);
    timeProgress.style.width = `${progressWidth}%`;

    // Show/hide empty state
    if (orderItems.length === 0) {
        emptyOrder.classList.remove('hidden');
        orderItemsList.classList.add('hidden');
    } else {
        emptyOrder.classList.add('hidden');
        orderItemsList.classList.remove('hidden');
    }
}

// Add item to order
function addToOrder(name, price, category, image = '') {
    // Check if item already exists in order
    const existingItemIndex = orderItems.findIndex(item => item.name === name);

    if (existingItemIndex !== -1) {
        // Increment quantity if item already exists
        orderItems[existingItemIndex].quantity++;
    } else {
        // Add new item
        orderItems.push({
            name: name,
            price: parseFloat(price),
            category: category,
            quantity: 1,
            image: image
        });
    }

    // Update UI
    renderOrderItems();
    calculateTotals();

    // Show confirmation animation
    const addBtn = event.target.closest('.add-to-order-btn');
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

// Remove item from order
function removeFromOrder(index) {
    orderItems.splice(index, 1);
    renderOrderItems();
    calculateTotals();
}

// Update item quantity
function updateQuantity(index, change) {
    orderItems[index].quantity += change;

    // Remove item if quantity becomes 0
    if (orderItems[index].quantity <= 0) {
        orderItems.splice(index, 1);
    }

    renderOrderItems();
    calculateTotals();
}

// Render order items list
function renderOrderItems() {
    orderItemsList.innerHTML = '';

    orderItems.forEach((item, index) => {
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
        orderItemsList.appendChild(itemElement);
    });

    // Add event listeners to new buttons
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

// Clear entire order
function clearOrder() {
    if (orderItems.length > 0) {
        if (confirm('Clear your order?')) {
            orderItems = [];
            renderOrderItems();
            calculateTotals();
            document.getElementById('order-notes').value = '';
        }
    }
}

// Voice chat functions
function startVoiceAssistant() {
    isListening = true;
    voiceStatus.textContent = 'Status: Listening...';
    voiceFeedback.textContent = 'Speak now. Try: "Add pork barbecue"';
    voiceCommandDisplay.classList.remove('hidden');
    voiceStartBtn.disabled = true;
    voiceStopBtn.disabled = false;

    // Simulate voice recognition
    simulateVoiceRecognition();
}

function stopVoiceAssistant() {
    isListening = false;
    voiceStatus.textContent = 'Status: Stopped';
    voiceFeedback.textContent = 'Voice assistant stopped';
    voiceCommandDisplay.classList.add('hidden');
    voiceStartBtn.disabled = false;
    voiceStopBtn.disabled = true;
}

function showVoiceHelp() {
    alert('Voice Commands:\n\n' +
          '• "Add [item name]" - Add item to cart\n' +
          '• "Show specials" - Show specials\n' +
          '• "Clear order" - Clear all items\n' +
          '• "Checkout" - Proceed to checkout\n' +
          '• "Help" - Show this help');
}

function simulateVoiceRecognition() {
    if (!isListening) return;

    // Simulate random voice commands for demo
    const commands = [
        "Add pork barbecue",
        "Show specials",
        "Add iced caramel macchiato",
        "Clear order",
        "Show chicken items"
    ];

    // Randomly show a command after 2-4 seconds
    setTimeout(() => {
        if (!isListening) return;

        const randomCommand = commands[Math.floor(Math.random() * commands.length)];
        voiceTranscript.textContent = `"${randomCommand}"`;

        // Process the command (simulated)
        if (randomCommand.includes("Add pork barbecue")) {
            setTimeout(() => {
                // Find and add pork barbecue
                const addBtns = document.querySelectorAll('.add-to-order-btn');
                addBtns.forEach(btn => {
                    if (btn.dataset.name && btn.dataset.name.includes('Pork Barbeque')) {
                        const name = btn.dataset.name;
                        const price = btn.dataset.price;
                        const category = btn.dataset.category;
                        const image = btn.dataset.image;
                        addToOrder(name, price, category, image);
                    }
                });
                voiceFeedback.textContent = 'Added Pork Barbecue';
            }, 800);
        }

        // Continue listening
        simulateVoiceRecognition();
    }, 2000 + Math.random() * 2000);
}

// Update carousel indicators
function updateCarouselIndicators() {
    carouselIndicators.innerHTML = '';
    for (let i = 0; i < totalSlides; i++) {
        const dot = document.createElement('div');
        dot.className = `carousel-dot ${i === currentSlide ? 'active' : ''}`;
        dot.setAttribute('data-slide', i);
        dot.addEventListener('click', () => goToSlide(i));
        carouselIndicators.appendChild(dot);
    }
    
    // Update arrow visibility
    carouselPrev.style.opacity = currentSlide === 0 ? '0.5' : '1';
    carouselPrev.style.cursor = currentSlide === 0 ? 'not-allowed' : 'pointer';
    
    carouselNext.style.opacity = currentSlide === totalSlides - 1 ? '0.5' : '1';
    carouselNext.style.cursor = currentSlide === totalSlides - 1 ? 'not-allowed' : 'pointer';
}

// Go to specific slide
function goToSlide(slideIndex) {
    if (slideIndex < 0 || slideIndex >= totalSlides) return;
    
    currentSlide = slideIndex;
    
    // Update carousel position
    carouselSlides.style.transform = `translateX(-${currentSlide * 100}%)`;
    
    // Update indicators
    updateCarouselIndicators();
}

// Next slide
function nextSlide() {
    if (currentSlide < totalSlides - 1) {
        goToSlide(currentSlide + 1);
    }
}

// Previous slide
function prevSlide() {
    if (currentSlide > 0) {
        goToSlide(currentSlide - 1);
    }
}

// Load all slides for a category and subcategory
async function loadProducts(category, subcategory) {
    try {
        // Update current category and subcategory
        currentCategory = category;
        currentSubcategory = subcategory;
        
        // Show loading state
        carouselSlides.innerHTML = '<div class="flex items-center justify-center h-full" style="min-width: 100%"><div class="text-center"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-amber-600 mx-auto"></div><p class="mt-4 text-gray-600">Loading products...</p></div></div>';

        const response = await fetch('/customer/get-products', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                category: categoryMap[category] || category,
                subcategory: subcategory
            })
        });

        const data = await response.json();
        
        // Store all slides
        allSlides = data.slides || [];
        totalSlides = data.totalSlides || 1;
        currentSlide = 0;
        
        // Clear and rebuild carousel with ALL slides
        carouselSlides.innerHTML = '';
        
        allSlides.forEach((slideHtml, index) => {
            const slideContainer = document.createElement('div');
            slideContainer.className = 'carousel-page';
            slideContainer.style.minWidth = '100%';
            slideContainer.innerHTML = slideHtml;
            carouselSlides.appendChild(slideContainer);
        });
        
        // Update carousel indicators
        updateCarouselIndicators();
        
        // Reset to first slide
        goToSlide(0);
        
        // Re-attach event listeners to all product buttons
        reattachEventListeners();

    } catch (error) {
        console.error('Error loading products:', error);
        carouselSlides.innerHTML = '<div class="flex items-center justify-center h-full" style="min-width: 100%"><div class="text-center text-red-600"><i class="fas fa-exclamation-triangle text-4xl mb-3"></i><p>Error loading products. Please try again.</p></div></div>';
    }
}

// Re-attach event listeners to all product buttons in all slides
function reattachEventListeners() {
    document.querySelectorAll('.add-to-order-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const name = this.getAttribute('data-name');
            const price = this.getAttribute('data-price');
            const category = this.getAttribute('data-category');
            const image = this.getAttribute('data-image');
            addToOrder(name, price, category, image);
        });
    });
}

// Set active upper navigation button
function setActiveUpperNav(activeBtn) {
    upperNavBtns.forEach(btn => {
        btn.classList.remove('active-tab');
        btn.classList.remove('text-amber-900');
        btn.classList.add('text-gray-800');
    });

    activeBtn.classList.add('active-tab', 'text-amber-900');
    activeBtn.classList.remove('text-gray-800');
}

// Update lower navigation based on selected category
function updateLowerNav(category) {
    const subcategories = navigationData[category] || [];

    // Clear current lower navigation
    lowerNav.innerHTML = '';

    // Add new subcategory buttons
    subcategories.forEach(subcategory => {
        const button = document.createElement('button');
        button.className = 'subcategory-btn bg-white text-amber-900 border border-amber-200 px-3 py-1.5 rounded-full font-medium hover:bg-amber-100 hover-lift transition-all duration-200 shadow-sm text-sm md:text-base';
        button.textContent = subcategory;

        // Add click event to load products for this subcategory
        button.addEventListener('click', function() {
            // Get the database subcategory value
            const dbSubcategory = subcategoryMap[subcategory] || subcategory.toLowerCase().replace(/[^a-z0-9]+/g, '-');
            
            // Load products for this subcategory
            loadProducts(category, dbSubcategory);

            // Remove active state from all subcategory buttons
            document.querySelectorAll('.subcategory-btn').forEach(b => {
                b.classList.remove('bg-amber-200', 'text-amber-900', 'border-amber-400');
                b.classList.add('bg-white', 'text-amber-900', 'border-amber-200');
            });

            // Add active state to clicked button
            this.classList.remove('bg-white', 'border-amber-200');
            this.classList.add('bg-amber-200', 'border-amber-400');
        });

        lowerNav.appendChild(button);
    });

    // Click the first subcategory by default
    if (subcategories.length > 0) {
        const firstBtn = lowerNav.querySelector('.subcategory-btn');
        if (firstBtn) {
            firstBtn.click();
        }
    }
}

// Initialize event listeners
function initializeEventListeners() {
    // Set Main Course as active
    const mainCourseBtn = document.querySelector('[data-category="main-course"]');
    setActiveUpperNav(mainCourseBtn);

    // Add event listeners to upper navigation buttons
    upperNavBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            setActiveUpperNav(this);
            updateLowerNav(category);
        });
    });

    // Add event listeners to initial product buttons
    reattachEventListeners();

    // Add event listeners to carousel arrows
    carouselPrev.addEventListener('click', prevSlide);
    carouselNext.addEventListener('click', nextSlide);

    // Add event listener to order type dropdown
    orderTypeDropdown.addEventListener('change', function() {
        setOrderType(this.value);
    });

    // Add event listeners to payment method buttons
    cashBtn.addEventListener('click', function() {
        setPaymentMethod('cash');
    });

    electronicBtn.addEventListener('click', function() {
        setPaymentMethod('electronic');
    });

    // Add event listeners to order action buttons
    clearOrderBtn.addEventListener('click', clearOrder);
    checkoutBtn.addEventListener('click', showCheckoutModal);

    // Add event listeners to voice chat buttons
    voiceStartBtn.addEventListener('click', startVoiceAssistant);
    voiceStopBtn.addEventListener('click', stopVoiceAssistant);
    voiceHelpBtn.addEventListener('click', showVoiceHelp);
}

// Initialize the application
function initializeApp() {
    // Initialize DOM elements
    initializeDOMElements();
    
    // Create checkout modal
    createCheckoutModal();
    
    // Create payment queue modal
    createPaymentQueueModal();
    
    // Initialize event listeners
    initializeEventListeners();
    
    // Initialize payment method
    setPaymentMethod('cash');
    
    // Initialize order type
    setOrderType(orderTypeDropdown.value);
    
    // Initialize voice stop button as disabled
    voiceStopBtn.disabled = true;
    
    // Initialize calculations
    calculateTotals();
    
    // Initialize carousel
    const initialSlides = document.querySelectorAll('.carousel-page');
    totalSlides = initialSlides.length;
    updateCarouselIndicators();
    
    // Store initial slides
    initialSlides.forEach((slide, index) => {
        allSlides[index] = slide.innerHTML;
    });
    
    // Prevent scrolling on the entire page
    document.body.style.overflow = 'hidden';
}

// Initialize when DOM is fully loaded
document.addEventListener('DOMContentLoaded', initializeApp);