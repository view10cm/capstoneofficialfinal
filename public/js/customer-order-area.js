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
let speechRecognition = null;
let isListening = false;
let recognitionTimeout = null;

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
    console.log('Initializing DOM elements...');
    
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
    
    console.log('Voice buttons found:', {
        startBtn: voiceStartBtn,
        stopBtn: voiceStopBtn,
        helpBtn: voiceHelpBtn
    });
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

// Create thank you modal
function createThankYouModal() {
    // Check if modal already exists
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
                            <div class="text-3xl font-bold text-amber-800 tracking-wider" id="thank-you-display-number">
                                <!-- Selected number will be displayed here -->
                            </div>
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

    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHTML);
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

// Show thank you modal
function showThankYouModal(paymentNumber, orderID) {
    // Create modal if it doesn't exist
    if (!document.getElementById('thank-you-modal')) {
        createThankYouModal();
    }
    
    // Update modal content
    document.getElementById('thank-you-display-number').textContent = `#${paymentNumber}`;
    document.getElementById('thank-you-order-id').textContent = orderID ? `#${orderID}` : '#0000';
    document.getElementById('thank-you-order-type').textContent = orderType === 'dine-in' ? 'Dine-in' : 'Takeout';
    document.getElementById('thank-you-total').textContent = totalElement.textContent;
    
    // Show modal
    const thankYouModal = document.getElementById('thank-you-modal');
    const thankYouModalContent = document.getElementById('thank-you-modal-content');
    
    thankYouModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Trigger animation
    setTimeout(() => {
        thankYouModal.classList.add('modal-bg-show');
        thankYouModal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
        
        thankYouModalContent.classList.add('modal-show');
        thankYouModalContent.style.opacity = '1';
        thankYouModalContent.style.transform = 'scale(1)';
    }, 10);
    
    // Start countdown
    let countdown = 5;
    const countdownElement = document.getElementById('countdown-timer');
    const progressBar = document.getElementById('countdown-progress');
    
    // Update countdown every second
    const countdownInterval = setInterval(() => {
        countdown--;
        countdownElement.textContent = countdown;
        
        // Update progress bar (100% to 0%)
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
    
    // Reverse animation
    thankYouModalContent.classList.remove('modal-show');
    thankYouModalContent.style.opacity = '0';
    thankYouModalContent.style.transform = 'scale(0.95)';
    
    thankYouModal.classList.remove('modal-bg-show');
    thankYouModal.style.backgroundColor = 'rgba(0, 0, 0, 0)';
    
    // Hide modal after animation completes
    setTimeout(() => {
        thankYouModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        
        // Reset modal content styles
        thankYouModalContent.classList.remove('modal-show');
        thankYouModalContent.style.opacity = '';
        thankYouModalContent.style.transform = '';
        thankYouModal.style.backgroundColor = '';
        
        // Clear order
        orderItems = [];
        renderOrderItems();
        calculateTotals();
        document.getElementById('order-notes').value = '';
        
        // Redirect to landing page after closing
        setTimeout(() => {
            window.location.href = '/customer/home';
        }, 500);
        
    }, 300);
}

// Confirm payment queue and redirect
async function confirmPaymentQueue() {
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
    
    // Get order notes
    const orderNotes = document.getElementById('order-notes').value;
    
    // Prepare order data for each item
    const orderData = {
        paymentNumber: parseInt(selectedPaymentNumber),
        orderType: orderType,
        orderPaymentMethod: paymentMethod,
        orderNotes: orderNotes,
        items: []
    };
    
    // Calculate totals for each item
    orderItems.forEach(item => {
        const itemTotalPrice = item.price * item.quantity;
        const itemTax = itemTotalPrice * TAX_RATE;
        
        orderData.items.push({
            productName: item.name,
            quantity: item.quantity,
            totalProductPrice: itemTotalPrice,
            totalProductTax: itemTax
        });
    });
    
    try {
        // Send order data to server
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
            // Store order data in localStorage
            localStorage.setItem('lastOrder', JSON.stringify({
                ...orderData,
                orderID: result.orderID,
                timestamp: new Date().toISOString()
            }));
            
            setTimeout(() => {
                // Close payment queue modal
                closePaymentQueueModal();
                
                // Show thank you modal after a brief delay
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
    orderItems.forEach((item, index) => {
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

    // Show/hide empty state based on items
    if (orderItems.length === 0) {
        emptyOrder.style.display = 'block';
        orderItemsList.style.display = 'none';
    } else {
        emptyOrder.style.display = 'none';
        orderItemsList.style.display = 'block';
    }

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

// ==================== VOICE TRANSCRIPTION FUNCTIONS ====================

// Start silence timeout
function startSilenceTimeout() {
    // Clear any existing timeout
    if (recognitionTimeout) {
        clearTimeout(recognitionTimeout);
    }
    
    // Set timeout for 3 seconds of silence
    recognitionTimeout = setTimeout(() => {
        if (isListening) {
            console.log('No speech detected for 3 seconds, stopping...');
            voiceFeedback.textContent = 'No speech detected. Stopping...';
            stopVoiceAssistant();
        }
    }, 3000); // 3 seconds
}

// Reset silence timeout
function resetSilenceTimeout() {
    // Clear existing timeout and start a new one
    if (recognitionTimeout) {
        clearTimeout(recognitionTimeout);
    }
    startSilenceTimeout();
}

// Start voice assistant with microphone
function startVoiceAssistant() {
    console.log('Start voice assistant clicked');
    
    // Check if browser supports speech recognition
    if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
        alert('Sorry, your browser does not support speech recognition. Please use Chrome, Edge, or Safari.');
        return;
    }

    // Initialize speech recognition
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    speechRecognition = new SpeechRecognition();
    
    // Configure recognition
    speechRecognition.continuous = false; // Stop automatically when user stops speaking
    speechRecognition.interimResults = false; // Only final results
    speechRecognition.lang = 'en-US'; // Set language to English

    // Start listening
    try {
        speechRecognition.start();
        console.log('Speech recognition started successfully');
    } catch (error) {
        console.error('Failed to start speech recognition:', error);
        alert('Failed to start microphone. Please check your microphone settings.');
        return;
    }
    
    // Update UI
    isListening = true;
    voiceStatus.textContent = 'Status: Listening...';
    voiceFeedback.textContent = 'Speak now. I\'m listening...';
    voiceCommandDisplay.classList.remove('hidden');
    voiceStartBtn.disabled = true;
    voiceStopBtn.disabled = false;
    
    // Start timeout for 3 seconds of silence
    startSilenceTimeout();

    // Event handlers for speech recognition
    speechRecognition.onstart = function() {
        console.log('Speech recognition started');
        voiceTranscript.textContent = 'Listening...';
    };

    speechRecognition.onresult = function(event) {
        // Reset silence timeout when speech is detected
        resetSilenceTimeout();
        
        const transcript = event.results[0][0].transcript;
        console.log('Transcript:', transcript);
        
        // Update UI with transcript
        voiceTranscript.textContent = `"${transcript}"`;
        voiceFeedback.textContent = 'Processing your command...';
        
        // Save transcript to database
        saveTranscript(transcript);
        
        // Process voice command
        processVoiceCommand(transcript);
    };

    speechRecognition.onerror = function(event) {
        console.error('Speech recognition error:', event.error);
        
        if (event.error === 'not-allowed') {
            voiceFeedback.textContent = 'Microphone access denied. Please allow microphone access.';
            alert('Microphone access is required for voice commands. Please allow microphone access in your browser settings.');
        } else if (event.error === 'no-speech') {
            voiceFeedback.textContent = 'No speech detected. Try speaking louder.';
        } else {
            voiceFeedback.textContent = `Error: ${event.error}`;
        }
        
        stopVoiceAssistant();
    };

    speechRecognition.onend = function() {
        console.log('Speech recognition ended');
        
        if (isListening) {
            // Auto-restart if still in listening mode
            setTimeout(() => {
                if (isListening) {
                    try {
                        speechRecognition.start();
                    } catch (e) {
                        console.error('Failed to restart speech recognition:', e);
                    }
                }
            }, 500);
        }
    };
}

// Stop voice assistant
function stopVoiceAssistant() {
    console.log('Stop voice assistant clicked');
    isListening = false;
    
    // Stop speech recognition if active
    if (speechRecognition) {
        try {
            speechRecognition.stop();
        } catch (e) {
            console.log('Speech recognition already stopped');
        }
        speechRecognition = null;
    }
    
    // Clear silence timeout
    if (recognitionTimeout) {
        clearTimeout(recognitionTimeout);
        recognitionTimeout = null;
    }
    
    // Update UI
    voiceStatus.textContent = 'Status: Ready';
    voiceFeedback.textContent = 'Click Start to begin voice ordering';
    voiceCommandDisplay.classList.add('hidden');
    voiceStartBtn.disabled = false;
    voiceStopBtn.disabled = true;
}

// Save transcript to database
async function saveTranscript(transcript) {
    console.log('Voice transcript recorded:', transcript);
    
    // Store transcript locally for now
    const transcripts = JSON.parse(localStorage.getItem('voiceTranscripts') || '[]');
    transcripts.push({
        text: transcript,
        timestamp: new Date().toISOString()
    });
    localStorage.setItem('voiceTranscripts', JSON.stringify(transcripts));
    
    // NEW: Try to match the transcript with utterance gallery
    const matchResult = await matchTranscriptWithMenuItem(transcript);
    const matchedMenuItem = matchResult ? matchResult.menuItem : null;
    const confidenceLevel = matchResult ? matchResult.confidence : 'Not Confident';
    
    // Save to server
    try {
        const response = await fetch('/customer/save-transcript', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                transcribedData: transcript,
                matchedMenuItem: matchedMenuItem,
                confidenceLevel: confidenceLevel
            })
        });
        
        if (response.ok) {
            const result = await response.json();
            console.log('Transcript saved to server:', result);
            
            // If we found a match, show it in the UI
            if (matchedMenuItem) {
                showMatchedMenuItem(transcript, matchedMenuItem, confidenceLevel, result.addedToGallery);
            }
        } else {
            console.log('Server save failed, transcript stored locally');
            // Still show match if found locally
            if (matchedMenuItem) {
                showMatchedMenuItem(transcript, matchedMenuItem, confidenceLevel, false);
            }
        }
    } catch (error) {
        console.log('Could not reach server, transcript stored locally');
        // Still show match if found locally
        if (matchedMenuItem) {
            showMatchedMenuItem(transcript, matchedMenuItem, confidenceLevel, false);
        }
    }
}

async function matchTranscriptWithMenuItem(transcript) {
    const normalizedTranscript = transcript.toLowerCase().trim();
    
    try {
        // Fetch all utterance gallery data or search for match
        const response = await fetch('/customer/match-utterance', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                transcript: normalizedTranscript
            })
        });
        
        if (response.ok) {
            const result = await response.json();
            if (result.success && result.matchedMenuItem) {
                console.log('Found matching menu item:', result.matchedMenuItem);
                return {
                    menuItem: result.matchedMenuItem,
                    confidence: result.confidence || 'Not Confident',
                    similarity: result.similarity || 0
                };
            }
        }
    } catch (error) {
        console.error('Error matching utterance:', error);
    }
    
    return null; // No match found
}

// NEW: Show matched menu item in UI
 function showMatchedMenuItem(transcript, menuItem, confidenceLevel, addedToGallery = false) {
    // Create or update a display element
    let matchDisplay = document.getElementById('voice-match-display');
    
    if (!matchDisplay) {
        matchDisplay = document.createElement('div');
        matchDisplay.id = 'voice-match-display';
        matchDisplay.className = 'mt-3 border rounded-lg p-3 fade-in';
        voiceCommandDisplay.parentNode.insertBefore(matchDisplay, voiceCommandDisplay.nextSibling);
    }
    
    // Determine styling based on confidence level with NEW thresholds
    let containerClass = 'bg-red-50 border-red-200';
    let badgeColor = 'bg-red-100 text-red-600 border border-red-200';
    let badgeText = 'Low Confidence (<80%)';
    let iconColor = 'text-red-500';
    let thresholdInfo = 'Similarity: 40-79%';
    
    if (confidenceLevel === 'Partially Confident') {
        containerClass = 'bg-yellow-50 border-yellow-200';
        badgeColor = 'bg-yellow-100 text-yellow-600 border border-yellow-200';
        badgeText = 'Medium Confidence (≥60%)';
        iconColor = 'text-yellow-500';
        thresholdInfo = 'Similarity: 60-79%';
    } else if (confidenceLevel === 'Confident') {
        containerClass = 'bg-green-50 border-green-200';
        badgeColor = 'bg-green-100 text-green-600 border border-green-200';
        badgeText = 'High Confidence (≥80%)';
        iconColor = 'text-green-500';
        thresholdInfo = 'Similarity: 80-100%';
    }
    
    // Update container class
    matchDisplay.className = `mt-3 ${containerClass} border rounded-lg p-3 fade-in`;
    
    // Gallery addition badge
    let galleryBadge = '';
    if (addedToGallery && confidenceLevel !== 'Not Confident') {
        galleryBadge = `
            <div class="mt-2 flex items-center text-xs text-green-600">
                <i class="fas fa-save mr-1"></i>
                <span>This utterance was added to the learning database for future matches</span>
            </div>
        `;
    }
    
    matchDisplay.innerHTML = `
        <div class="flex items-start">
            <div class="${iconColor} p-1.5 rounded-full mr-2">
                <i class="fas fa-check-circle text-sm"></i>
            </div>
            <div class="flex-1">
                <div class="flex justify-between items-start mb-1">
                    <h4 class="font-bold text-gray-800 text-sm">Menu Item Found!</h4>
                    <span class="${badgeColor} text-xs px-2 py-0.5 rounded-full font-medium">
                        ${badgeText}
                    </span>
                </div>
                <p class="text-gray-700 text-sm mb-1">You said: "<span class="font-medium">${transcript}</span>"</p>
                <p class="text-gray-700 text-sm mb-1">Matched: <span class="font-bold ${confidenceLevel === 'Confident' ? 'text-green-600' : confidenceLevel === 'Partially Confident' ? 'text-yellow-600' : 'text-red-600'}">${menuItem}</span></p>
                <p class="text-xs text-gray-500 mb-2">${thresholdInfo}</p>
                
                ${galleryBadge}
                
                <!-- Auto-add button -->
                <div class="mt-2">
                    <button class="auto-add-menu-btn ${confidenceLevel === 'Confident' ? 'bg-green-600 hover:bg-green-700' : confidenceLevel === 'Partially Confident' ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-red-600 hover:bg-red-700'} text-white px-3 py-1.5 rounded-lg font-medium transition-colors duration-200 flex items-center text-xs"
                            data-menu-item="${menuItem}">
                        <i class="fas fa-plus mr-1"></i> Auto-Add "${menuItem}" to Order
                    </button>
                </div>
            </div>
        </div>
    `;
    
    // Add event listener to auto-add button
    const autoAddBtn = matchDisplay.querySelector('.auto-add-menu-btn');
    if (autoAddBtn) {
        autoAddBtn.addEventListener('click', function() {
            const menuItemName = this.getAttribute('data-menu-item');
            autoAddMenuItemToOrder(menuItemName, confidenceLevel);
        });
    }
    
    // Auto-hide after 7 seconds (longer to show gallery addition message)
    setTimeout(() => {
        if (matchDisplay && matchDisplay.parentNode) {
            matchDisplay.classList.add('hidden');
            setTimeout(() => {
                if (matchDisplay && matchDisplay.parentNode) {
                    matchDisplay.remove();
                }
            }, 500);
        }
    }, 7000);
}

// NEW: Auto-add menu item to order
async function autoAddMenuItemToOrder(menuItemName, confidenceLevel) {
    console.log('Attempting to auto-add:', menuItemName, 'with confidence:', confidenceLevel);
    
    // NEW LOGIC: Only add items with at least "Partially Confident" confidence (≥60%)
    if (confidenceLevel === 'Not Confident') {
        // For 40-59% similarity, DO NOT add to order - show a rejection message instead
        const confirmed = await showLowConfidenceRejection(menuItemName, '40-59%');
        if (confirmed) {
            // If user confirms despite low confidence, proceed with manual addition
            voiceFeedback.textContent = `Manually adding "${menuItemName}" despite low confidence match`;
            voiceFeedback.style.color = '#F59E0B';
            // Fall through to continue with manual addition
        } else {
            voiceFeedback.textContent = `Low confidence match (40-59%). "${menuItemName}" was NOT added to order.`;
            voiceFeedback.style.color = '#EF4444';
            
            // Show a more specific rejection message
            showRejectionMessage(menuItemName, '40-59%');
            return;
        }
    }
    
    if (confidenceLevel === 'Partially Confident') {
        // For 60-79% similarity, show a less intrusive confirmation
        const confirmed = await showPartialConfirmation(menuItemName, '60-79%');
        if (!confirmed) {
            voiceFeedback.textContent = 'Cancelled adding item to order';
            voiceFeedback.style.color = '#EF4444';
            return;
        }
    }
    
    // For Confident (≥80%), add automatically without confirmation
    
    // Search for matching product in the current view
    const addButtons = document.querySelectorAll('.add-to-order-btn');
    let found = false;
    
    addButtons.forEach(btn => {
        const name = btn.getAttribute('data-name');
        // Simple matching - check if menuItemName contains product name or vice versa
        if (name.toLowerCase().includes(menuItemName.toLowerCase()) || 
            menuItemName.toLowerCase().includes(name.toLowerCase())) {
            
            const price = btn.getAttribute('data-price');
            const category = btn.getAttribute('data-category');
            const image = btn.getAttribute('data-image');
            addToOrder(name, price, category, image);
            
            // Show success feedback with confidence level
            let confidenceText = '';
            if (confidenceLevel === 'Confident') {
                confidenceText = ' (High confidence - ≥80% match)';
            } else if (confidenceLevel === 'Partially Confident') {
                confidenceText = ' (Medium confidence - 60-79% match)';
            }
            // Note: No success message for "Not Confident" since we don't add those
            
            voiceFeedback.textContent = `Added "${name}" to order${confidenceText}`;
            voiceFeedback.style.color = '#10B981';
            
            found = true;
        }
    });
    
    if (!found) {
        // If not found in current view, try to load the appropriate category
        voiceFeedback.textContent = `"${menuItemName}" not found in current view. Try navigating to the correct category.`;
        voiceFeedback.style.color = '#EF4444';
        
        // Try to guess category based on menu item name
        let categoryToLoad = 'main-course'; // default
        
        if (menuItemName.toLowerCase().includes('hot') || 
            menuItemName.toLowerCase().includes('iced') ||
            menuItemName.toLowerCase().includes('frappe') ||
            menuItemName.toLowerCase().includes('milktea') ||
            menuItemName.toLowerCase().includes('cappuccino') ||
            menuItemName.toLowerCase().includes('latte') ||
            menuItemName.toLowerCase().includes('espresso') ||
            menuItemName.toLowerCase().includes('mocha') ||
            menuItemName.toLowerCase().includes('americano') ||
            menuItemName.toLowerCase().includes('choco') ||
            menuItemName.toLowerCase().includes('caramel') ||
            menuItemName.toLowerCase().includes('vanilla') ||
            menuItemName.toLowerCase().includes('matcha')) {
            categoryToLoad = 'drinks';
        } else if (menuItemName.toLowerCase().includes('salad') ||
                  menuItemName.toLowerCase().includes('nachos') ||
                  menuItemName.toLowerCase().includes('fries') ||
                  menuItemName.toLowerCase().includes('sandwich') ||
                  menuItemName.toLowerCase().includes('quesadilla') ||
                  menuItemName.toLowerCase().includes('wrap')) {
            categoryToLoad = 'appetizers';
        } else if (menuItemName.toLowerCase().includes('pasta') ||
                  menuItemName.toLowerCase().includes('noodles') ||
                  menuItemName.toLowerCase().includes('lasagna') ||
                  menuItemName.toLowerCase().includes('paella') ||
                  menuItemName.toLowerCase().includes('crispy') ||
                  menuItemName.toLowerCase().includes('grilled') ||
                  menuItemName.toLowerCase().includes('roasted') ||
                  menuItemName.toLowerCase().includes('pork') ||
                  menuItemName.toLowerCase().includes('chicken') ||
                  menuItemName.toLowerCase().includes('beef') ||
                  menuItemName.toLowerCase().includes('seafood') ||
                  menuItemName.toLowerCase().includes('fish')) {
            categoryToLoad = 'main-course';
        }
        
        // Switch to the guessed category
        const categoryBtn = document.querySelector(`[data-category="${categoryToLoad}"]`);
        if (categoryBtn) {
            categoryBtn.click();
            
            // After switching category, try to find and add the item
            setTimeout(() => {
                const newAddButtons = document.querySelectorAll('.add-to-order-btn');
                let itemFound = false;
                
                newAddButtons.forEach(btn => {
                    const name = btn.getAttribute('data-name');
                    if (name.toLowerCase().includes(menuItemName.toLowerCase()) || 
                        menuItemName.toLowerCase().includes(name.toLowerCase())) {
                        
                        const price = btn.getAttribute('data-price');
                        const category = btn.getAttribute('data-category');
                        const image = btn.getAttribute('data-image');
                        addToOrder(name, price, category, image);
                        
                        voiceFeedback.textContent = `Successfully added "${name}" to order!`;
                        voiceFeedback.style.color = '#10B981';
                        itemFound = true;
                    }
                });
                
                if (!itemFound) {
                    voiceFeedback.textContent = `"${menuItemName}" not found. Please try searching manually.`;
                    voiceFeedback.style.color = '#EF4444';
                }
            }, 1000); // Wait for category to load
        }
    }
}

// NEW: Show rejection modal for low confidence matches
function showLowConfidenceRejection(menuItemName, similarityRange) {
    return new Promise((resolve) => {
        // Create rejection modal
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        modal.innerHTML = `
            <div class="bg-white rounded-lg p-6 max-w-sm mx-4 animate__animated animate__fadeIn">
                <div class="flex items-center mb-4">
                    <div class="bg-red-100 text-red-600 p-2 rounded-full mr-3">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Low Confidence Match</h3>
                </div>
                <p class="text-gray-600 mb-4">
                    This match has <span class="font-bold">${similarityRange} similarity</span>, which is below the acceptable threshold.
                    <br><br>
                    Match: <span class="font-bold text-red-600">"${menuItemName}"</span>
                    <br><br>
                    <span class="font-bold">This item will NOT be added to your order automatically.</span>
                    <br><br>
                    You can:
                    <ol class="list-decimal pl-4 mt-2 text-sm">
                        <li>Search for the item manually</li>
                        <li>Speak more clearly and try again</li>
                        <li>Use the menu buttons instead</li>
                    </ol>
                </p>
                <div class="flex space-x-3">
                    <button id="ok-rejection" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 rounded-lg font-medium transition-colors">
                        OK, I'll Search Manually
                    </button>
                    <button id="add-anyway" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg font-medium transition-colors">
                        Add Anyway (Not Recommended)
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Add event listeners
        modal.querySelector('#ok-rejection').addEventListener('click', () => {
            modal.remove();
            resolve(false);
        });
        
        modal.querySelector('#add-anyway').addEventListener('click', () => {
            modal.remove();
            resolve(true);
        });
        
        // Close on outside click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
                resolve(false);
            }
        });
    });
}

// NEW: Show rejection message in the UI
function showRejectionMessage(menuItemName, similarityRange) {
    // Create rejection display
    let rejectionDisplay = document.getElementById('voice-rejection-display');
    
    if (!rejectionDisplay) {
        rejectionDisplay = document.createElement('div');
        rejectionDisplay.id = 'voice-rejection-display';
        rejectionDisplay.className = 'mt-3 border border-red-200 bg-red-50 rounded-lg p-3 fade-in';
        voiceCommandDisplay.parentNode.insertBefore(rejectionDisplay, voiceCommandDisplay.nextSibling);
    }
    
    rejectionDisplay.innerHTML = `
        <div class="flex items-start">
            <div class="text-red-500 p-1.5 rounded-full mr-2">
                <i class="fas fa-exclamation-circle text-sm"></i>
            </div>
            <div class="flex-1">
                <h4 class="font-bold text-gray-800 text-sm mb-1">Item Not Added</h4>
                <p class="text-gray-700 text-sm mb-1">"${menuItemName}" was NOT added to your order.</p>
                <p class="text-xs text-gray-500 mb-2">Reason: Low confidence match (${similarityRange} similarity)</p>
                <p class="text-xs text-gray-600 mb-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    For better results: Speak clearly, specify the full item name, or use manual selection.
                </p>
                <div class="mt-2">
                    <button onclick="window.location.reload()" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg font-medium transition-colors duration-200 flex items-center text-xs">
                        <i class="fas fa-search mr-1"></i> Search Manually
                    </button>
                </div>
            </div>
        </div>
    `;
    
    // Auto-hide after 10 seconds
    setTimeout(() => {
        if (rejectionDisplay && rejectionDisplay.parentNode) {
            rejectionDisplay.classList.add('hidden');
            setTimeout(() => {
                if (rejectionDisplay && rejectionDisplay.parentNode) {
                    rejectionDisplay.remove();
                }
            }, 500);
        }
    }, 10000);
}

function showPartialConfirmation(menuItemName, similarityRange) {
    return new Promise((resolve) => {
        // Create confirmation modal
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        modal.innerHTML = `
            <div class="bg-white rounded-lg p-6 max-w-sm mx-4 animate__animated animate__fadeIn">
                <div class="flex items-center mb-4">
                    <div class="bg-yellow-100 text-yellow-600 p-2 rounded-full mr-3">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Medium Confidence Match</h3>
                </div>
                <p class="text-gray-600 mb-4">
                    This match has <span class="font-bold">${similarityRange} similarity</span>.
                    <br><br>
                    Match: <span class="font-bold text-yellow-600">"${menuItemName}"</span>
                    <br><br>
                    Do you want to add this to your order?
                </p>
                <div class="flex space-x-3">
                    <button id="cancel-partial" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 rounded-lg font-medium transition-colors">
                        No, Cancel
                    </button>
                    <button id="confirm-partial" class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white py-2 rounded-lg font-medium transition-colors">
                        Yes, Add to Order
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Add event listeners
        modal.querySelector('#cancel-partial').addEventListener('click', () => {
            modal.remove();
            resolve(false);
        });
        
        modal.querySelector('#confirm-partial').addEventListener('click', () => {
            modal.remove();
            resolve(true);
        });
        
        // Close on outside click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
                resolve(false);
            }
        });
    });
}

// Process voice commands
function processVoiceCommand(transcript) {
    const command = transcript.toLowerCase();
    let feedback = '';
    
    // First check if it's a command
    if (command.includes('add') || command.includes('order')) {
        // Extract product name from command
        const words = command.split(' ');
        const addIndex = words.findIndex(w => w === 'add' || w === 'order');
        
        if (addIndex !== -1 && words.length > addIndex + 1) {
            // Extract the product name from command
            const productNameWords = words.slice(addIndex + 1);
            const productName = productNameWords.join(' ');
            
            // Try to match with utterance gallery first
            matchTranscriptWithMenuItem(productName).then(matchResult => {
                if (matchResult && matchResult.menuItem) {
                    // If we found a match, auto-add it
                    autoAddMenuItemToOrder(matchResult.menuItem);
                    feedback = `Found "${matchResult.menuItem}" in menu`;
                } else {
                    // Fall back to old search method
                    const addButtons = document.querySelectorAll('.add-to-order-btn');
                    let found = false;
                    
                    addButtons.forEach(btn => {
                        const name = btn.getAttribute('data-name').toLowerCase();
                        if (name.includes(productName.toLowerCase()) || 
                            productName.toLowerCase().includes(name)) {
                            
                            const price = btn.getAttribute('data-price');
                            const category = btn.getAttribute('data-category');
                            const image = btn.getAttribute('data-image');
                            addToOrder(btn.getAttribute('data-name'), price, category, image);
                            
                            feedback = `Added ${btn.getAttribute('data-name')} to your order`;
                            found = true;
                        }
                    });
                    
                    if (!found) {
                        feedback = `Product "${productName}" not found. Please try again.`;
                    }
                }
                voiceFeedback.textContent = feedback;
                
                // Auto-stop after processing command
                setTimeout(() => {
                    stopVoiceAssistant();
                }, 2000);
            });
            
            return; // Exit early since we're handling asynchronously
        } else {
            feedback = 'Please specify what you want to add. Example: "Add pork barbecue"';
        }
    } 
    // ... rest of the processVoiceCommand function remains the same ...
    else {
        // If it's not a recognized command, try to match it as a menu item
        matchTranscriptWithMenuItem(transcript).then(matchResult => {
            if (matchResult && matchResult.menuItem) {
                autoAddMenuItemToOrder(matchResult.menuItem);
                feedback = `Found "${matchResult.menuItem}" in menu`;
            } else {
                feedback = 'Command not recognized. Try: "Add [item]", "Show specials", "Clear order", or "Checkout"';
            }
            voiceFeedback.textContent = feedback;
            
            // Auto-stop after processing command
            setTimeout(() => {
                stopVoiceAssistant();
            }, 2000);
        });
        
        return; // Exit early since we're handling asynchronously
    }
    
    // Update feedback for synchronous commands
    voiceFeedback.textContent = feedback;
    
    // Auto-stop after processing command
    setTimeout(() => {
        stopVoiceAssistant();
    }, 2000);
}

// Also update the showVoiceHelp function to reflect the new behavior
function showVoiceHelp() {
    alert('Voice Commands:\n\n' +
          '• "Add [item name]" - Add item to cart\n' +
          '• "Show specials" - Show specials\n' +
          '• "Clear order" - Clear all items\n' +
          '• "Checkout" - Proceed to checkout\n' +
          '• "Help" - Show this help\n\n' +
          'Confidence Levels:\n' +
          '• High (≥80% match) - Auto-adds to order\n' +
          '• Medium (60-79% match) - Quick confirmation required\n' +
          '• Low (40-59% match) - NOT added to order automatically\n' +
          '• <40% match - Not recognized');
}

// ==================== CAROUSEL FUNCTIONS ====================

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

// ==================== INITIALIZATION FUNCTIONS ====================

// Initialize event listeners
function initializeEventListeners() {
    console.log('Initializing event listeners...');
    
    // Set Main Course as active
    const mainCourseBtn = document.querySelector('[data-category="main-course"]');
    if (mainCourseBtn) {
        setActiveUpperNav(mainCourseBtn);
    }

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
    if (carouselPrev) carouselPrev.addEventListener('click', prevSlide);
    if (carouselNext) carouselNext.addEventListener('click', nextSlide);

    // Add event listener to order type dropdown
    if (orderTypeDropdown) {
        orderTypeDropdown.addEventListener('change', function() {
            setOrderType(this.value);
        });
    }

    // Add event listeners to payment method buttons
    if (cashBtn) {
        cashBtn.addEventListener('click', function() {
            setPaymentMethod('cash');
        });
    }

    if (electronicBtn) {
        electronicBtn.addEventListener('click', function() {
            setPaymentMethod('electronic');
        });
    }

    // Add event listeners to order action buttons
    if (clearOrderBtn) clearOrderBtn.addEventListener('click', clearOrder);
    if (checkoutBtn) checkoutBtn.addEventListener('click', showCheckoutModal);

    // Add event listeners to voice chat buttons
    if (voiceStartBtn) {
        console.log('Adding click event to voice start button');
        voiceStartBtn.addEventListener('click', startVoiceAssistant);
    } else {
        console.error('Voice start button not found!');
    }
    
    if (voiceStopBtn) {
        console.log('Adding click event to voice stop button');
        voiceStopBtn.addEventListener('click', stopVoiceAssistant);
    } else {
        console.error('Voice stop button not found!');
    }
    
    if (voiceHelpBtn) {
        console.log('Adding click event to voice help button');
        voiceHelpBtn.addEventListener('click', showVoiceHelp);
    } else {
        console.error('Voice help button not found!');
    }
    
    console.log('Event listeners initialized');
}

// Initialize the application
function initializeApp() {
    console.log('Initializing app...');
    
    try {
        // Initialize DOM elements
        initializeDOMElements();
        
        // Create checkout modal
        createCheckoutModal();
        
        // Create payment queue modal
        createPaymentQueueModal();
        
        // Create thank you modal (it will be created dynamically when needed)
        
        // Initialize event listeners
        initializeEventListeners();
        
        // Initialize payment method
        setPaymentMethod('cash');
        
        // Initialize order type
        if (orderTypeDropdown) {
            setOrderType(orderTypeDropdown.value);
        }
        
        // Initialize voice stop button as disabled
        if (voiceStopBtn) {
            voiceStopBtn.disabled = true;
        }
        
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
        
        console.log('App initialization complete');
    } catch (error) {
        console.error('Error during app initialization:', error);
    }
}

// Initialize when DOM is fully loaded
document.addEventListener('DOMContentLoaded', initializeApp);

// Also try to initialize if DOM is already loaded
if (document.readyState === 'complete' || document.readyState === 'interactive') {
    setTimeout(initializeApp, 100);
}