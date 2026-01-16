// customer-order-base.js
// Core functionality and initialization

// Global state (shared across all modules)
const globalState = {
    orderItems: [],
    orderType: 'dine-in',
    paymentMethod: 'cash',
    currentCategory: 'main-course',
    currentSubcategory: 'pork',
    currentSlide: 0,
    totalSlides: 1,
    allSlides: []
};

// Navigation data
const navigationData = {
    'main-course': ['Pork', 'Chicken', 'Beef', 'Fish & Seafood', 'Pasta', 'Noodles'],
    'appetizers': ['Salads', 'Knick/Knacks', 'Sandwiches'],
    'drinks': ['Hot', 'Iced', 'Frappe', 'Milktea'],
};

// Category and subcategory mappings
const categoryMap = {
    'main-course': 'main-course',
    'appetizers': 'appetizers',
    'drinks': 'drinks',
};

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

// DOM elements cache
const domElements = {};

// Format price to Philippine Peso
function formatPrice(price) {
    return `₱${parseFloat(price).toFixed(2)}`;
}

// Initialize DOM elements
function initializeDOMElements() {
    console.log('Initializing DOM elements...');
    
    // Store references to DOM elements
    domElements.upperNavBtns = document.querySelectorAll('.upper-nav-btn');
    domElements.lowerNav = document.getElementById('lower-nav');
    domElements.orderItemsList = document.getElementById('order-items-list');
    domElements.emptyOrder = document.getElementById('empty-order');
    domElements.subtotalElement = document.getElementById('subtotal');
    domElements.totalElement = document.getElementById('total');
    domElements.clearOrderBtn = document.getElementById('clear-order-btn');
    domElements.checkoutBtn = document.getElementById('checkout-btn');
    domElements.orderTypeDropdown = document.getElementById('order-type-dropdown');
    domElements.cashBtn = document.getElementById('cash-btn');
    domElements.electronicBtn = document.getElementById('electronic-btn');
    domElements.carouselSlides = document.getElementById('carousel-slides');
    domElements.carouselPrev = document.getElementById('carousel-prev');
    domElements.carouselNext = document.getElementById('carousel-next');
    domElements.carouselIndicators = document.getElementById('carousel-indicators');
    domElements.orderNotes = document.getElementById('order-notes');
    
    console.log('DOM elements initialized');
}

// Update lower navigation based on selected category
function updateLowerNav(category) {
    const subcategories = navigationData[category] || [];

    // Clear current lower navigation
    domElements.lowerNav.innerHTML = '';

    // Add new subcategory buttons
    subcategories.forEach(subcategory => {
        const button = document.createElement('button');
        button.className = 'subcategory-btn bg-white text-amber-900 border border-amber-200 px-2 py-1 rounded-full font-medium hover:bg-amber-100 hover-lift transition-all duration-200 shadow-sm text-xs md:text-sm';
        button.textContent = subcategory;

        button.addEventListener('click', function() {
            const dbSubcategory = subcategoryMap[subcategory] || subcategory.toLowerCase().replace(/[^a-z0-9]+/g, '-');
            loadProducts(category, dbSubcategory);

            document.querySelectorAll('.subcategory-btn').forEach(b => {
                b.classList.remove('bg-amber-200', 'text-amber-900', 'border-amber-400');
                b.classList.add('bg-white', 'text-amber-900', 'border-amber-200');
            });

            this.classList.remove('bg-white', 'border-amber-200');
            this.classList.add('bg-amber-200', 'border-amber-400');
        });

        domElements.lowerNav.appendChild(button);
    });

    if (subcategories.length > 0) {
        const firstBtn = domElements.lowerNav.querySelector('.subcategory-btn');
        if (firstBtn) {
            firstBtn.click();
        }
    }
}

// Set active upper navigation button
function setActiveUpperNav(activeBtn) {
    domElements.upperNavBtns.forEach(btn => {
        btn.classList.remove('active-tab');
        btn.classList.remove('text-amber-900');
        btn.classList.add('text-gray-800');
    });

    activeBtn.classList.add('active-tab', 'text-amber-900');
    activeBtn.classList.remove('text-gray-800');
}

// Load products for category and subcategory
async function loadProducts(category, subcategory) {
    try {
        globalState.currentCategory = category;
        globalState.currentSubcategory = subcategory;
        
        domElements.carouselSlides.innerHTML = '<div class="flex items-center justify-center h-full" style="min-width: 100%"><div class="text-center"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-amber-600 mx-auto"></div><p class="mt-4 text-gray-600">Loading products...</p></div></div>';

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
        
        globalState.allSlides = data.slides || [];
        globalState.totalSlides = data.totalSlides || 1;
        globalState.currentSlide = 0;
        
        domElements.carouselSlides.innerHTML = '';
        
        globalState.allSlides.forEach((slideHtml, index) => {
            const slideContainer = document.createElement('div');
            slideContainer.className = 'carousel-page';
            slideContainer.style.minWidth = '100%';
            slideContainer.innerHTML = slideHtml;
            domElements.carouselSlides.appendChild(slideContainer);
        });
        
        updateCarouselIndicators();
        goToSlide(0);
        reattachEventListeners();

    } catch (error) {
        console.error('Error loading products:', error);
        domElements.carouselSlides.innerHTML = '<div class="flex items-center justify-center h-full" style="min-width: 100%"><div class="text-center text-red-600"><i class="fas fa-exclamation-triangle text-4xl mb-3"></i><p>Error loading products. Please try again.</p></div></div>';
    }
}

// Reattach event listeners to product buttons
function reattachEventListeners() {
    document.querySelectorAll('.add-to-order-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const name = this.getAttribute('data-name');
            const price = this.getAttribute('data-price');
            const category = this.getAttribute('data-category');
            const image = this.getAttribute('data-image');
            addToOrder(name, price, category, image, 'manual');
        });
    });
}

// Carousel functions
function updateCarouselIndicators() {
    domElements.carouselIndicators.innerHTML = '';
    for (let i = 0; i < globalState.totalSlides; i++) {
        const dot = document.createElement('div');
        dot.className = `carousel-dot ${i === globalState.currentSlide ? 'active' : ''}`;
        dot.setAttribute('data-slide', i);
        dot.addEventListener('click', () => goToSlide(i));
        domElements.carouselIndicators.appendChild(dot);
    }
    
    domElements.carouselPrev.style.opacity = globalState.currentSlide === 0 ? '0.5' : '1';
    domElements.carouselPrev.style.cursor = globalState.currentSlide === 0 ? 'not-allowed' : 'pointer';
    
    domElements.carouselNext.style.opacity = globalState.currentSlide === globalState.totalSlides - 1 ? '0.5' : '1';
    domElements.carouselNext.style.cursor = globalState.currentSlide === globalState.totalSlides - 1 ? 'not-allowed' : 'pointer';
}

function goToSlide(slideIndex) {
    if (slideIndex < 0 || slideIndex >= globalState.totalSlides) return;
    
    globalState.currentSlide = slideIndex;
    domElements.carouselSlides.style.transform = `translateX(-${globalState.currentSlide * 100}%)`;
    updateCarouselIndicators();
}

function nextSlide() {
    if (globalState.currentSlide < globalState.totalSlides - 1) {
        goToSlide(globalState.currentSlide + 1);
    }
}

function prevSlide() {
    if (globalState.currentSlide > 0) {
        goToSlide(globalState.currentSlide - 1);
    }
}

// Order type and payment method
function setOrderType(type) {
    globalState.orderType = type;
    console.log(`Order type set to: ${type}`);
}

function setPaymentMethod(method) {
    globalState.paymentMethod = method;
    
    if (method === 'cash') {
        domElements.cashBtn.classList.add('active', 'bg-green-600', 'text-white');
        domElements.cashBtn.classList.remove('bg-gray-200', 'text-gray-800');
        domElements.electronicBtn.classList.add('bg-gray-200', 'text-gray-800');
        domElements.electronicBtn.classList.remove('active', 'bg-blue-600', 'text-white');
    } else {
        domElements.electronicBtn.classList.add('active', 'bg-blue-600', 'text-white');
        domElements.electronicBtn.classList.remove('bg-gray-200', 'text-gray-800');
        domElements.cashBtn.classList.add('bg-gray-200', 'text-gray-800');
        domElements.cashBtn.classList.remove('active', 'bg-green-600', 'text-white');
    }

    console.log(`Payment method set to: ${method}`);
}

// Initialize event listeners for base functionality
function initializeBaseEventListeners() {
    console.log('Initializing base event listeners...');
    
    // Set Main Course as active
    const mainCourseBtn = document.querySelector('[data-category="main-course"]');
    if (mainCourseBtn) {
        setActiveUpperNav(mainCourseBtn);
    }

    // Upper navigation buttons
    domElements.upperNavBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            setActiveUpperNav(this);
            updateLowerNav(category);
        });
    });

    // Carousel arrows
    if (domElements.carouselPrev) domElements.carouselPrev.addEventListener('click', prevSlide);
    if (domElements.carouselNext) domElements.carouselNext.addEventListener('click', nextSlide);

    // Order type dropdown
    if (domElements.orderTypeDropdown) {
        domElements.orderTypeDropdown.addEventListener('change', function() {
            setOrderType(this.value);
        });
    }

    // Payment method buttons
    if (domElements.cashBtn) {
        domElements.cashBtn.addEventListener('click', function() {
            setPaymentMethod('cash');
        });
    }

    if (domElements.electronicBtn) {
        domElements.electronicBtn.addEventListener('click', function() {
            setPaymentMethod('electronic');
        });
    }

    console.log('Base event listeners initialized');
}

// Initialize the base application
function initializeBaseApp() {
    console.log('Initializing base app...');
    
    try {
        initializeDOMElements();
        initializeBaseEventListeners();
        setPaymentMethod('cash');
        
        if (domElements.orderTypeDropdown) {
            setOrderType(domElements.orderTypeDropdown.value);
        }
        
        const initialSlides = document.querySelectorAll('.carousel-page');
        globalState.totalSlides = initialSlides.length;
        updateCarouselIndicators();
        
        initialSlides.forEach((slide, index) => {
            globalState.allSlides[index] = slide.innerHTML;
        });
        
        document.body.style.overflow = 'hidden';
        
        console.log('Base app initialization complete');
    } catch (error) {
        console.error('Error during base app initialization:', error);
    }
}

// Export global state and functions
window.globalState = globalState;
window.domElements = domElements;
window.navigationData = navigationData;
window.categoryMap = categoryMap;
window.subcategoryMap = subcategoryMap;
window.formatPrice = formatPrice;
window.initializeBaseApp = initializeBaseApp;
window.loadProducts = loadProducts;
window.reattachEventListeners = reattachEventListeners;
window.goToSlide = goToSlide;
window.nextSlide = nextSlide;
window.prevSlide = prevSlide;
window.setOrderType = setOrderType;
window.setPaymentMethod = setPaymentMethod;
window.updateLowerNav = updateLowerNav;
window.setActiveUpperNav = setActiveUpperNav;