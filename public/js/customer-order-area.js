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

// Process checkout
function processCheckout() {
    if (orderItems.length === 0) {
        alert('Please add items to your order before checking out.');
        return;
    }

    const orderDetails = orderItems.map(item => 
        `${item.quantity}x ${item.name} - ${formatPrice(item.price * item.quantity)}`
    ).join('\n');

    const notes = document.getElementById('order-notes').value;
    const total = totalElement.textContent;
    const orderTypeText = orderType === 'dine-in' ? 'Dine-in' : 'Takeout';
    const paymentMethodText = paymentMethod === 'cash' ? 'Cash' : 'Electronic Payment';

    alert(`Order Submitted!\n\nOrder Type: ${orderTypeText}\nPayment Method: ${paymentMethodText}\n\nItems:\n${orderDetails}\n\nTotal: ${total}\n\nNotes: ${notes || 'None'}\n\nThank you for your order!`);

    // Reset order
    orderItems = [];
    renderOrderItems();
    calculateTotals();
    document.getElementById('order-notes').value = '';
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
    checkoutBtn.addEventListener('click', processCheckout);

    // Add event listeners to voice chat buttons
    voiceStartBtn.addEventListener('click', startVoiceAssistant);
    voiceStopBtn.addEventListener('click', stopVoiceAssistant);
    voiceHelpBtn.addEventListener('click', showVoiceHelp);
}

// Initialize the application
function initializeApp() {
    // Initialize DOM elements
    initializeDOMElements();
    
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