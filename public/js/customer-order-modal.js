// customer-order-modal.js
// Modal handling functionality

let checkoutModal, confirmOrderBtn, cancelOrderBtn, orderDetailsList;

// Create checkout modal
function createCheckoutModal() {
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

    document.body.insertAdjacentHTML('beforeend', modalHTML);

    checkoutModal = document.getElementById('checkout-modal');
    const checkoutModalContent = document.getElementById('checkout-modal-content');
    confirmOrderBtn = document.getElementById('confirm-order-btn');
    cancelOrderBtn = document.getElementById('cancel-order-btn');
    orderDetailsList = document.getElementById('order-details-list');
    
    document.getElementById('close-modal').addEventListener('click', closeCheckoutModal);
    cancelOrderBtn.addEventListener('click', closeCheckoutModal);
    confirmOrderBtn.addEventListener('click', confirmOrder);
    
    checkoutModal.addEventListener('click', function(e) {
        if (e.target === checkoutModal) {
            closeCheckoutModal();
        }
    });
}

// Create payment queue modal
function createPaymentQueueModal() {
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
                                <div class="text-3xl font-bold text-green-700 tracking-wider text-center" id="display-selected-number"></div>
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

    document.body.insertAdjacentHTML('beforeend', modalHTML);

    const paymentQueueModal = document.getElementById('payment-queue-modal');
    const paymentQueueModalContent = document.getElementById('payment-queue-modal-content');
    const confirmPaymentBtn = document.getElementById('confirm-payment-btn');
    const paymentNumberDropdown = document.getElementById('payment-number-dropdown');
    const selectedNumberDisplay = document.getElementById('selected-number-display');
    const displaySelectedNumber = document.getElementById('display-selected-number');
    
    for (let i = 1; i <= 20; i++) {
        const option = document.createElement('option');
        option.value = i;
        option.textContent = `#${i}`;
        paymentNumberDropdown.appendChild(option);
    }
    
    paymentNumberDropdown.addEventListener('change', function() {
        const selectedValue = this.value;
        const confirmBtn = document.getElementById('confirm-payment-btn');
        
        if (selectedValue) {
            selectedNumberDisplay.classList.remove('hidden');
            displaySelectedNumber.textContent = `#${selectedValue}`;
            
            confirmBtn.disabled = false;
            confirmBtn.classList.remove('disabled:opacity-50', 'disabled:cursor-not-allowed');
            
            document.querySelector('#payment-queue-modal-content .text-xs.text-gray-500.text-center').innerHTML = `
                <i class="fas fa-info-circle mr-1"></i> Your order is now being prepared
            `;
        } else {
            selectedNumberDisplay.classList.add('hidden');
            
            confirmBtn.disabled = true;
            confirmBtn.classList.add('disabled:opacity-50', 'disabled:cursor-not-allowed');
            
            document.querySelector('#payment-queue-modal-content .text-xs.text-gray-500.text-center').innerHTML = `
                <i class="fas fa-info-circle mr-1"></i> Select a payment number to continue
            `;
        }
    });
    
    document.getElementById('close-payment-modal').addEventListener('click', closePaymentQueueModal);
    confirmPaymentBtn.addEventListener('click', confirmPaymentQueue);
    
    paymentQueueModal.addEventListener('click', function(e) {
        if (e.target === paymentQueueModal) {
            closePaymentQueueModal();
        }
    });
}

// Create thank you modal
function createThankYouModal() {
    if (document.getElementById('thank-you-modal')) return;

    const modalHTML = `
        <div id="thank-you-modal" class="fixed inset-0 bg-black bg-opacity-0 flex items-center justify-center z-50 hidden">
            <div id="thank-you-modal-content" class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 opacity-0 transform scale-95">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-amber-600 to-amber-500 p-6 rounded-t-xl">
                    <div class="flex items-center justify-center">
                        <div class="bg-white p-3 rounded-full mr-3">
                            <i class="fas fa-mug-hot text-amber-600 text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">
                                Caffé Arabica
                            </h2>
                            <p class="text-amber-100 text-sm mt-1">Thank You for Your Order!</p>
                        </div>
                    </div>
                </div>
                
                <!-- Modal Body -->
                <div class="p-6 text-center">
                    <!-- Success Icon -->
                    <div class="flex justify-center mb-4">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center animate-pulse">
                            <i class="fas fa-check-circle text-green-600 text-4xl"></i>
                        </div>
                    </div>
                    
                    <!-- Message -->
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Thank you for Ordering!</h3>
                        <p class="text-gray-600 mb-4">
                            Please wait for your payment at the counter.
                        </p>
                        
                        <!-- Payment Number Display -->
                        <div id="thank-you-payment-number" class="bg-amber-50 border-2 border-amber-200 rounded-lg p-4 mb-4">
                            <p class="text-sm text-amber-700 mb-1">Your Payment Number:</p>
                            <div class="text-3xl font-bold text-amber-800 tracking-wider" id="thank-you-display-number"></div>
                            <p class="text-xs text-amber-600 mt-2">
                                <i class="fas fa-info-circle mr-1"></i> Present this number at the payment counter
                            </p>
                        </div>
                        
                        <!-- Order Summary -->
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span>Order ID:</span>
                                <span id="thank-you-order-id">#0000</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span>Order Type:</span>
                                <span id="thank-you-order-type">Dine-in</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Total Amount:</span>
                                <span id="thank-you-total">₱0.00</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Countdown Timer -->
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">This message will close in:</p>
                        <div class="flex justify-center items-center">
                            <div class="text-2xl font-bold text-amber-600" id="countdown-timer">5</div>
                            <span class="ml-1 text-gray-600">seconds</span>
                        </div>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="w-full bg-gray-200 rounded-full h-1.5 mb-2">
                        <div id="countdown-progress" class="bg-amber-500 h-1.5 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="border-t border-gray-200 p-4 bg-gray-50 rounded-b-xl">
                    <p class="text-xs text-gray-500 text-center">
                        <i class="fas fa-clock mr-1"></i> Your order is being prepared now
                    </p>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

// Show checkout modal
function showCheckoutModal() {
    if (globalState.orderItems.length === 0) {
        domElements.checkoutBtn.classList.add('animate-pulse');
        domElements.emptyOrder.classList.add('empty-cart-shake');
        
        setTimeout(() => {
            domElements.checkoutBtn.classList.remove('animate-pulse');
            domElements.emptyOrder.classList.remove('empty-cart-shake');
        }, 500);
        
        alert('Please add items to your order before checking out.');
        return;
    }

    updateModalContent();
    
    checkoutModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        checkoutModal.classList.add('modal-bg-show');
        checkoutModal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
        
        const modalContent = document.getElementById('checkout-modal-content');
        modalContent.classList.add('modal-show');
        modalContent.style.opacity = '1';
        
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

// Close checkout modal
function closeCheckoutModal() {
    const modalContent = document.getElementById('checkout-modal-content');
    modalContent.classList.remove('modal-show');
    modalContent.style.opacity = '0';
    modalContent.style.transform = 'translateY(-50px) scale(0.95)';
    
    checkoutModal.classList.remove('modal-bg-show');
    checkoutModal.style.backgroundColor = 'rgba(0, 0, 0, 0)';
    
    setTimeout(() => {
        checkoutModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        
        modalContent.classList.remove('modal-show');
        modalContent.style.opacity = '';
        modalContent.style.transform = '';
        checkoutModal.style.backgroundColor = '';
    }, 300);
}

// Show payment queue modal
function showPaymentQueueModal() {
    const totalItems = globalState.orderItems.reduce((sum, item) => sum + item.quantity, 0);
    document.getElementById('queue-item-count').textContent = totalItems;
    document.getElementById('queue-order-type').textContent = globalState.orderType === 'dine-in' ? 'Dine-in' : 'Takeout';
    document.getElementById('queue-payment-method').textContent = globalState.paymentMethod === 'cash' ? 'Cash' : 'Electronic Payment';
    
    const paymentNumberDropdown = document.getElementById('payment-number-dropdown');
    const selectedNumberDisplay = document.getElementById('selected-number-display');
    const confirmBtn = document.getElementById('confirm-payment-btn');
    
    paymentNumberDropdown.value = '';
    selectedNumberDisplay.classList.add('hidden');
    confirmBtn.disabled = true;
    confirmBtn.classList.add('disabled:opacity-50', 'disabled:cursor-not-allowed');
    
    const instructionText = document.querySelector('#payment-queue-modal-content .text-xs.text-gray-500.text-center');
    if (instructionText) {
        instructionText.innerHTML = `
            <i class="fas fa-info-circle mr-1"></i> Select a payment number to continue
        `;
    }
    
    const paymentQueueModal = document.getElementById('payment-queue-modal');
    const paymentQueueModalContent = document.getElementById('payment-queue-modal-content');
    
    paymentQueueModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
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
    
    paymentQueueModalContent.classList.remove('modal-show');
    paymentQueueModalContent.style.opacity = '0';
    paymentQueueModalContent.style.transform = 'scale(0.95)';
    
    paymentQueueModal.classList.remove('modal-bg-show');
    paymentQueueModal.style.backgroundColor = 'rgba(0, 0, 0, 0)';
    
    setTimeout(() => {
        paymentQueueModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        
        paymentQueueModalContent.classList.remove('modal-show');
        paymentQueueModalContent.style.opacity = '';
        paymentQueueModalContent.style.transform = '';
        paymentQueueModal.style.backgroundColor = '';
    }, 300);
}

// Show thank you modal
function showThankYouModal(paymentNumber, orderID) {
    if (!document.getElementById('thank-you-modal')) {
        createThankYouModal();
    }
    
    document.getElementById('thank-you-display-number').textContent = `#${paymentNumber}`;
    document.getElementById('thank-you-order-id').textContent = orderID ? `#${orderID}` : '#0000';
    document.getElementById('thank-you-order-type').textContent = globalState.orderType === 'dine-in' ? 'Dine-in' : 'Takeout';
    document.getElementById('thank-you-total').textContent = domElements.totalElement.textContent;
    
    const thankYouModal = document.getElementById('thank-you-modal');
    const thankYouModalContent = document.getElementById('thank-you-modal-content');
    
    thankYouModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        thankYouModal.classList.add('modal-bg-show');
        thankYouModal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
        
        thankYouModalContent.classList.add('modal-show');
        thankYouModalContent.style.opacity = '1';
        thankYouModalContent.style.transform = 'scale(1)';
    }, 10);
    
    let countdown = 5;
    const countdownElement = document.getElementById('countdown-timer');
    const progressBar = document.getElementById('countdown-progress');
    
    const countdownInterval = setInterval(() => {
        countdown--;
        countdownElement.textContent = countdown;
        
        const progressWidth = (countdown / 5) * 100;
        progressBar.style.width = `${progressWidth}%`;
        
        if (countdown <= 0) {
            clearInterval(countdownInterval);
            closeThankYouModal();
        }
    }, 1000);
}

// Close thank you modal
function closeThankYouModal() {
    const thankYouModal = document.getElementById('thank-you-modal');
    const thankYouModalContent = document.getElementById('thank-you-modal-content');
    
    if (!thankYouModal) return;
    
    thankYouModalContent.classList.remove('modal-show');
    thankYouModalContent.style.opacity = '0';
    thankYouModalContent.style.transform = 'scale(0.95)';
    
    thankYouModal.classList.remove('modal-bg-show');
    thankYouModal.style.backgroundColor = 'rgba(0, 0, 0, 0)';
    
    setTimeout(() => {
        thankYouModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        
        thankYouModalContent.classList.remove('modal-show');
        thankYouModalContent.style.opacity = '';
        thankYouModalContent.style.transform = '';
        thankYouModal.style.backgroundColor = '';
        
        globalState.orderItems = [];
        renderOrderItems();
        calculateTotals();
        domElements.orderNotes.value = '';
        
        setTimeout(() => {
            window.location.href = '/customer/home';
        }, 500);
        
    }, 300);
}

// Confirm payment queue
async function confirmPaymentQueue() {
    const confirmBtn = document.getElementById('confirm-payment-btn');
    confirmBtn.classList.add('success-animation');
    
    const paymentNumberDropdown = document.getElementById('payment-number-dropdown');
    const selectedPaymentNumber = paymentNumberDropdown.value;
    
    if (!selectedPaymentNumber) {
        alert('Please select a payment number before confirming.');
        confirmBtn.classList.remove('success-animation');
        return;
    }
    
    const orderNotes = domElements.orderNotes.value;
    
    const orderData = {
        paymentNumber: parseInt(selectedPaymentNumber),
        orderType: globalState.orderType,
        orderPaymentMethod: globalState.paymentMethod,
        orderNotes: orderNotes,
        items: []
    };
    
    globalState.orderItems.forEach(item => {
        const itemTotalPrice = item.price * item.quantity;
        
        orderData.items.push({
            productName: item.name,
            quantity: item.quantity,
            totalProductPrice: itemTotalPrice,
            totalProductTax: 0.00
        });
    });
    
    try {
        const response = await fetch('/customer/save-order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(orderData)
        });

        const result = await response.json();
        
        if (result.success) {
            localStorage.setItem('lastOrder', JSON.stringify({
                ...orderData,
                orderID: result.orderID,
                timestamp: new Date().toISOString()
            }));
            
            setTimeout(() => {
                closePaymentQueueModal();
                
                setTimeout(() => {
                    showThankYouModal(selectedPaymentNumber, result.orderID);
                }, 300);
                
                confirmBtn.classList.remove('success-animation');
            }, 300);
        } else {
            alert('Error saving order: ' + result.message);
            confirmBtn.classList.remove('success-animation');
        }
    } catch (error) {
        console.error('Error saving order:', error);
        alert('Error saving order. Please try again.');
        confirmBtn.classList.remove('success-animation');
    }
}

// Confirm order
function confirmOrder() {
    confirmOrderBtn.classList.add('success-animation');
    
    setTimeout(() => {
        closeCheckoutModal();
        
        setTimeout(() => {
            showPaymentQueueModal();
        }, 100);
        
        confirmOrderBtn.classList.remove('success-animation');
    }, 300);
}

// Update modal content
function updateModalContent() {
    const orderTypeText = globalState.orderType === 'dine-in' ? 'Dine-in' : 'Takeout';
    document.getElementById('modal-order-type').textContent = orderTypeText;
    
    const paymentMethodText = globalState.paymentMethod === 'cash' ? 'Cash' : 'Electronic Payment';
    document.getElementById('modal-payment-method').textContent = paymentMethodText;
    
    orderDetailsList.innerHTML = '';
    globalState.orderItems.forEach((item, index) => {
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
    
    const notes = domElements.orderNotes.value;
    document.getElementById('modal-order-notes').textContent = notes || 'No special instructions';
    
    let subtotal = 0;
    globalState.orderItems.forEach(item => {
        subtotal += item.price * item.quantity;
    });
    const total = subtotal;
    
    document.getElementById('modal-subtotal').textContent = formatPrice(subtotal);
    document.getElementById('modal-total').textContent = formatPrice(total);
}

// Initialize modal event listeners
function initializeModalEventListeners() {
    if (domElements.checkoutBtn) domElements.checkoutBtn.addEventListener('click', showCheckoutModal);
}

// Export functions
window.createCheckoutModal = createCheckoutModal;
window.createPaymentQueueModal = createPaymentQueueModal;
window.createThankYouModal = createThankYouModal;
window.showCheckoutModal = showCheckoutModal;
window.closeCheckoutModal = closeCheckoutModal;
window.showPaymentQueueModal = showPaymentQueueModal;
window.closePaymentQueueModal = closePaymentQueueModal;
window.showThankYouModal = showThankYouModal;
window.closeThankYouModal = closeThankYouModal;
window.confirmPaymentQueue = confirmPaymentQueue;
window.confirmOrder = confirmOrder;
window.updateModalContent = updateModalContent;
window.initializeModalEventListeners = initializeModalEventListeners;