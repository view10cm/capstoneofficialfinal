<!-- CUSTOMER ORDER AREA VIEW -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Order Area - Caffé Arabica</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap');
        
        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            overflow: hidden;
        }
        
        .brand-font {
            font-family: 'Playfair Display', serif;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #c2410c;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #9a3412;
        }
        
        /* Active tab indicator */
        .active-tab {
            position: relative;
        }
        
        .active-tab::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 70%;
            height: 3px;
            background-color: #c2410c;
            border-radius: 3px;
        }
        
        /* Hover animation for buttons */
        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        /* Order summary scrollable area */
        .order-items-container {
            max-height: 250px;
            overflow-y: auto;
        }
        
        /* Smooth transitions */
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Voice chat styling */
        .voice-pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
            100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
        }
        
        /* Order type button styles */
        .order-type-btn {
            transition: all 0.2s ease;
        }
        
        .order-type-btn.active {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        /* Carousel styles */
        .carousel-container {
            position: relative;
            width: 100%;
            height: 100%;
        }
        
        .carousel-slide {
            transition: transform 0.5s ease-in-out;
            width: 100%;
            height: 100%;
        }
        
        .carousel-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 50px;
            height: 50px;
            background-color: rgba(255, 255, 255, 0.9);
            border: 2px solid #c2410c;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .carousel-arrow:hover {
            background-color: #c2410c;
            color: white;
            transform: translateY(-50%) scale(1.1);
        }
        
        .carousel-arrow-left {
            left: 10px;
        }
        
        .carousel-arrow-right {
            right: 10px;
        }
        
        .carousel-arrow i {
            font-size: 1.5rem;
        }
        
        .carousel-indicator {
            position: absolute;
            bottom: 15px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 8px;
            z-index: 10;
        }
        
        .carousel-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.5);
            border: 1px solid #c2410c;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .carousel-dot.active {
            background-color: #c2410c;
            transform: scale(1.2);
        }
        
        /* Product grid styles */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(2, 1fr);
            gap: 16px;
            height: 100%;
            width: 100%;
        }

        /* Product card image styles */
        .product-image-container {
            height: 150px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f8f8;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="bg-gray-50 h-full">
    <!-- Main Container -->
    <div class="flex h-full">
        <!-- Left Panel: Menu Content -->
        <div class="flex-1 flex flex-col h-full overflow-hidden">
            <!-- Blue Area: Brand Header -->
            <div class="bg-white p-4 flex items-center justify-center">
                <div class="w-full max-w-3xl">
                    <img src="{{ asset('images/Brand Header.svg') }}" alt="Caffé Arabica" class="w-full h-auto">
                </div>
            </div>
            
            <!-- Green Area: Double Navigation Bars -->
            <div class="bg-white border-b border-gray-200">
                <!-- Upper Navigation Bar -->
                <div class="px-4 py-2">
                    <nav class="flex justify-center space-x-1 md:space-x-4">
                        <button data-category="main-course" class="upper-nav-btn active-tab px-4 py-3 text-base md:text-lg font-medium text-gray-800 hover:text-amber-900 transition-colors duration-200">
                            <i class="fas fa-utensils mr-2"></i>Main Course
                        </button>
                        <button data-category="appetizers" class="upper-nav-btn px-4 py-3 text-base md:text-lg font-medium text-gray-800 hover:text-amber-900 transition-colors duration-200">
                            <i class="fas fa-seedling mr-2"></i>Appetizers
                        </button>
                        <button data-category="drinks" class="upper-nav-btn px-4 py-3 text-base md:text-lg font-medium text-gray-800 hover:text-amber-900 transition-colors duration-200">
                            <i class="fas fa-glass-whiskey mr-2"></i>Drinks
                        </button>
                        <button data-category="specials" class="upper-nav-btn px-4 py-3 text-base md:text-lg font-medium text-gray-800 hover:text-amber-900 transition-colors duration-200">
                            <i class="fas fa-star mr-2"></i>Specials
                        </button>
                    </nav>
                </div>
                
                <!-- Lower Navigation Bar -->
                <div class="bg-amber-50 border-y border-amber-100">
                    <div class="px-4">
                        <div id="lower-nav" class="py-2 flex flex-wrap justify-center gap-1 md:gap-3">
                            <!-- Main Course subcategories (default) -->
                            <button class="subcategory-btn bg-white text-amber-900 border border-amber-200 px-3 py-1.5 rounded-full font-medium hover:bg-amber-100 hover-lift transition-all duration-200 shadow-sm text-sm md:text-base">
                                Pork
                            </button>
                            <button class="subcategory-btn bg-white text-amber-900 border border-amber-200 px-3 py-1.5 rounded-full font-medium hover:bg-amber-100 hover-lift transition-all duration-200 shadow-sm text-sm md:text-base">
                                Chicken
                            </button>
                            <button class="subcategory-btn bg-white text-amber-900 border border-amber-200 px-3 py-1.5 rounded-full font-medium hover:bg-amber-100 hover-lift transition-all duration-200 shadow-sm text-sm md:text-base">
                                Beef
                            </button>
                            <button class="subcategory-btn bg-white text-amber-900 border border-amber-200 px-3 py-1.5 rounded-full font-medium hover:bg-amber-100 hover-lift transition-all duration-200 shadow-sm text-sm md:text-base">
                                Fish & Seafood
                            </button>
                            <button class="subcategory-btn bg-white text-amber-900 border border-amber-200 px-3 py-1.5 rounded-full font-medium hover:bg-amber-100 hover-lift transition-all duration-200 shadow-sm text-sm md:text-base">
                                Pasta
                            </button>
                            <button class="subcategory-btn bg-white text-amber-900 border border-amber-200 px-3 py-1.5 rounded-full font-medium hover:bg-amber-100 hover-lift transition-all duration-200 shadow-sm text-sm md:text-base">
                                Noodles
                            </button>
                            <button class="subcategory-btn bg-amber-100 text-amber-900 border border-amber-300 px-3 py-1.5 rounded-full font-medium hover:bg-amber-200 hover-lift transition-all duration-200 shadow-sm text-sm md:text-base">
                                <i class="fas fa-star mr-1"></i>Specials
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Black Area: Menu Products (Carousel) -->
            <div class="flex-1 p-4 overflow-hidden relative">
                <!-- Carousel Container -->
                <div class="carousel-container">
                    <!-- Left Arrow -->
                    <div class="carousel-arrow carousel-arrow-left" id="carousel-prev">
                        <i class="fas fa-chevron-left"></i>
                    </div>
                    
                    <!-- Right Arrow -->
                    <div class="carousel-arrow carousel-arrow-right" id="carousel-next">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                    
                    <!-- Carousel Slides Container -->
                    <div id="carousel-slides" class="carousel-slide">
                        <!-- Slide 1 (Main Course - Pork & Chicken) -->
                        <div class="product-grid">
                            <!-- Products will be loaded dynamically from database -->
                            @foreach($products as $product)
                                @if($product->menuCategory == 'main-course' && in_array($product->menuSubcategory, ['pork', 'chicken']))
                                    <div class="product-card bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 hover-lift h-full flex flex-col">
                                        <!-- Product Image -->
                                        <div class="product-image-container">
                                            @if($product->menuImage)
                                                <img src="{{ asset('storage/' . $product->menuImage) }}" 
                                                     alt="{{ $product->menuName }}" 
                                                     class="product-image">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                                    <i class="fas fa-utensils text-gray-400 text-4xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="p-4 flex-1">
                                            <div class="flex justify-between items-start mb-2">
                                                <h3 class="text-lg font-bold text-gray-800 truncate">{{ $product->menuName }}</h3>
                                                <span class="bg-amber-100 text-amber-800 text-xs font-semibold px-2 py-0.5 rounded-full whitespace-nowrap">
                                                    {{ ucfirst($product->menuSubcategory) }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between items-center mt-auto">
                                                <span class="text-xl font-bold text-amber-700">₱{{ number_format($product->menuPrice, 2) }}</span>
                                                <button class="add-to-order-btn bg-amber-600 hover:bg-amber-700 text-white px-3 py-1.5 rounded-lg font-medium transition-colors duration-200 flex items-center text-sm"
                                                        data-name="{{ $product->menuName }}" 
                                                        data-price="{{ $product->menuPrice }}" 
                                                        data-category="{{ $product->menuCategory }}"
                                                        data-image="{{ $product->menuImage ? asset('storage/' . $product->menuImage) : '' }}">
                                                    <i class="fas fa-plus mr-1"></i> Add
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Carousel Indicators -->
                    <div class="carousel-indicator">
                        <div class="carousel-dot active" data-slide="0"></div>
                        <div class="carousel-dot" data-slide="1"></div>
                        <div class="carousel-dot" data-slide="2"></div>
                    </div>
                </div>
            </div>
            
            <!-- Yellow Area: Voice Chat -->
            <div class="bg-gradient-to-r from-amber-100 to-yellow-100 border-t border-amber-200 p-3">
                <div class="max-w-6xl mx-auto">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-2">
                        <div class="flex items-center">
                            <div class="bg-blue-500 text-white p-2 rounded-full mr-3 voice-pulse">
                                <i class="fas fa-microphone"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 text-sm">Voice Assistant</h3>
                                <p class="text-gray-600 text-xs">Say "Hey Arabica" to start voice ordering</p>
                            </div>
                        </div>
                        
                        <div class="flex space-x-2">
                            <button id="voice-start" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg font-medium transition-colors duration-200 flex items-center text-sm">
                                <i class="fas fa-play mr-1"></i> Start
                            </button>
                            <button id="voice-stop" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-3 py-1.5 rounded-lg font-medium transition-colors duration-200 flex items-center text-sm">
                                <i class="fas fa-stop mr-1"></i> Stop
                            </button>
                            <button id="voice-help" class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded-lg font-medium transition-colors duration-200 flex items-center text-sm">
                                <i class="fas fa-question-circle mr-1"></i> Help
                            </button>
                        </div>
                        
                        <div class="text-center md:text-right">
                            <div id="voice-status" class="text-xs font-medium text-gray-700">Status: Ready</div>
                            <div id="voice-feedback" class="text-xs text-gray-500">Click Start to begin</div>
                        </div>
                    </div>
                    
                    <!-- Voice Command Display -->
                    <div id="voice-command-display" class="mt-3 hidden">
                        <div class="bg-white rounded-lg p-3 border border-blue-200">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-bold text-gray-800 text-sm">Voice Command</h4>
                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded">Listening...</span>
                            </div>
                            <div id="voice-transcript" class="text-gray-700 p-2 bg-gray-50 rounded border text-sm">
                                Speak now...
                            </div>
                            <div class="mt-2 text-xs text-gray-600">
                                <i class="fas fa-lightbulb mr-1"></i> Try: "Add pork barbecue" or "Show specials"
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Panel: Order Summary -->
        <div class="w-1/3 min-w-96 bg-white border-l border-gray-200 flex flex-col h-full overflow-hidden">
            <!-- Order Summary Header -->
            <div class="bg-gradient-to-r from-red-600 to-red-500 p-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-white">
                        <i class="fas fa-shopping-cart mr-2"></i>Order Summary
                    </h2>
                    <span id="item-count" class="bg-white text-red-600 font-bold rounded-full w-7 h-7 flex items-center justify-center text-sm">0</span>
                </div>
                <p class="text-red-100 text-sm mt-1">Review your order</p>
            </div>
            
            <!-- Order Items -->
            <div class="flex-1 p-4 overflow-hidden">
                <!-- Dine-in / Takeout Selection -->
                <div class="mb-4">
                    <h4 class="font-bold text-gray-800 text-sm mb-2">
                        <i class="fas fa-store mr-1"></i> Order Type
                    </h4>
                    <div class="grid grid-cols-2 gap-2">
                        <button id="dine-in-btn" class="order-type-btn active bg-blue-600 text-white py-2.5 rounded-lg font-medium transition-all duration-200 flex flex-col items-center justify-center">
                            <i class="fas fa-utensils text-lg mb-1"></i>
                            <span class="font-bold">Dine-in</span>
                            <span class="text-xs opacity-90">Table Service</span>
                        </button>
                        <button id="takeout-btn" class="order-type-btn bg-gray-200 text-gray-800 py-2.5 rounded-lg font-medium transition-all duration-200 flex flex-col items-center justify-center">
                            <i class="fas fa-box text-lg mb-1"></i>
                            <span class="font-bold">Takeout</span>
                            <span class="text-xs opacity-90">To-go Order</span>
                        </button>
                    </div>
                    <div id="order-type-info" class="mt-2 text-xs text-gray-600 p-2 bg-blue-50 rounded border border-blue-100">
                        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                        <span>Dine-in selected. Your order will be served at your table.</span>
                    </div>
                </div>
                
                <div id="order-items-container" class="order-items-container mb-4">
                    <!-- Empty State -->
                    <div id="empty-order" class="text-center py-6 empty-state">
                        <div class="text-gray-300 text-4xl mb-3">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-500 mb-1">Your order is empty</h3>
                        <p class="text-gray-400 text-sm">Add items to get started</p>
                    </div>
                    
                    <!-- Order items will be dynamically added here -->
                    <div id="order-items-list" class="space-y-3"></div>
                </div>
                
                <!-- Order Notes -->
                <div class="mb-4">
                    <label for="order-notes" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-sticky-note mr-1 text-xs"></i> Order Notes
                    </label>
                    <textarea id="order-notes" rows="2" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" placeholder="Special instructions..."></textarea>
                </div>
            </div>
            
            <!-- Order Totals and Actions -->
            <div class="border-t border-gray-200 p-4 bg-gray-50">
                <!-- Order Totals -->
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-gray-600 text-sm">
                        <span>Subtotal:</span>
                        <span id="subtotal">₱0.00</span>
                    </div>
                    <div class="flex justify-between text-gray-600 text-sm">
                        <span>Tax (12%):</span>
                        <span id="tax">₱0.00</span>
                    </div>
                    <div class="flex justify-between font-bold text-gray-800 pt-2 border-t border-gray-300">
                        <span>Total:</span>
                        <span id="total">₱0.00</span>
                    </div>
                </div>
                
                <!-- Order Status -->
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-1">
                        <h4 class="font-bold text-gray-800 text-sm">
                            <i class="fas fa-clock text-red-500 mr-1"></i> Wait Time
                        </h4>
                        <span id="estimated-time" class="font-bold text-red-600 text-sm">15-20 mins</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                        <div id="time-progress" class="bg-red-500 h-1.5 rounded-full" style="width: 30%"></div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="space-y-2">
                    <button id="clear-order-btn" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center text-sm">
                        <i class="fas fa-trash-alt mr-1"></i> Clear Order
                    </button>
                    <button id="checkout-btn" class="w-full bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white py-2.5 rounded-lg font-bold transition-all duration-200 hover-lift flex items-center justify-center">
                        <i class="fas fa-check-circle mr-2"></i> Checkout
                    </button>
                </div>
                
                <!-- Footer Note -->
                <div class="mt-4 pt-3 border-t border-gray-300">
                    <p class="text-xs text-gray-500 text-center">
                        <i class="fas fa-shield-alt mr-1"></i> Secure checkout
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Navigation data - updated based on database categories
        const navigationData = {
            'main-course': ['Pork', 'Chicken', 'Beef', 'Fish & Seafood', 'Pasta', 'Noodles'],
            'appetizers': ['Salads', 'Knick/Knacks', 'Sandwiches'],
            'drinks': ['Hot', 'Iced', 'Frappe', 'Milktea'],
            'specials': []
        };
        
        // Order data
        let orderItems = [];
        const TAX_RATE = 0.12;
        let orderType = 'dine-in'; // Default order type
        
        // Voice chat state
        let isListening = false;
        
        // Carousel state
        let currentSlide = 0;
        const totalSlides = 3; // 3 slides for demonstration
        
        // Get DOM elements
        const upperNavBtns = document.querySelectorAll('.upper-nav-btn');
        const lowerNav = document.getElementById('lower-nav');
        const orderItemsList = document.getElementById('order-items-list');
        const emptyOrder = document.getElementById('empty-order');
        const itemCount = document.getElementById('item-count');
        const subtotalElement = document.getElementById('subtotal');
        const taxElement = document.getElementById('tax');
        const totalElement = document.getElementById('total');
        const estimatedTime = document.getElementById('estimated-time');
        const timeProgress = document.getElementById('time-progress');
        const clearOrderBtn = document.getElementById('clear-order-btn');
        const checkoutBtn = document.getElementById('checkout-btn');
        const voiceStartBtn = document.getElementById('voice-start');
        const voiceStopBtn = document.getElementById('voice-stop');
        const voiceHelpBtn = document.getElementById('voice-help');
        const voiceStatus = document.getElementById('voice-status');
        const voiceFeedback = document.getElementById('voice-feedback');
        const voiceCommandDisplay = document.getElementById('voice-command-display');
        const voiceTranscript = document.getElementById('voice-transcript');
        const dineInBtn = document.getElementById('dine-in-btn');
        const takeoutBtn = document.getElementById('takeout-btn');
        const orderTypeInfo = document.getElementById('order-type-info');
        const carouselPrev = document.getElementById('carousel-prev');
        const carouselNext = document.getElementById('carousel-next');
        const carouselSlides = document.getElementById('carousel-slides');
        const carouselDots = document.querySelectorAll('.carousel-dot');
        
        // Format price to Philippine Peso
        function formatPrice(price) {
            return `₱${parseFloat(price).toFixed(2)}`;
        }
        
        // Set order type
        function setOrderType(type) {
            orderType = type;
            
            // Update button styles
            if (type === 'dine-in') {
                dineInBtn.classList.add('active', 'bg-blue-600', 'text-white');
                dineInBtn.classList.remove('bg-gray-200', 'text-gray-800');
                takeoutBtn.classList.add('bg-gray-200', 'text-gray-800');
                takeoutBtn.classList.remove('active', 'bg-green-600', 'text-white');
                
                // Update info text
                orderTypeInfo.innerHTML = `
                    <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                    <span>Dine-in selected. Your order will be served at your table.</span>
                `;
                orderTypeInfo.className = 'mt-2 text-xs text-gray-600 p-2 bg-blue-50 rounded border border-blue-100';
            } else {
                takeoutBtn.classList.add('active', 'bg-green-600', 'text-white');
                takeoutBtn.classList.remove('bg-gray-200', 'text-gray-800');
                dineInBtn.classList.add('bg-gray-200', 'text-gray-800');
                dineInBtn.classList.remove('active', 'bg-blue-600', 'text-white');
                
                // Update info text
                orderTypeInfo.innerHTML = `
                    <i class="fas fa-info-circle text-green-500 mr-1"></i>
                    <span>Takeout selected. Your order will be prepared for pickup.</span>
                `;
                orderTypeInfo.className = 'mt-2 text-xs text-gray-600 p-2 bg-green-50 rounded border border-green-100';
            }
            
            console.log(`Order type set to: ${type}`);
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
            itemCount.textContent = totalItems;
            
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
            
            alert(`Order Submitted!\n\nOrder Type: ${orderTypeText}\n\nItems:\n${orderDetails}\n\nTotal: ${total}\n\nNotes: ${notes || 'None'}\n\nThank you for your order!`);
            
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
        
        // Carousel functions
        function goToSlide(slideIndex) {
            if (slideIndex < 0) slideIndex = totalSlides - 1;
            if (slideIndex >= totalSlides) slideIndex = 0;
            
            currentSlide = slideIndex;
            
            // Update slide position
            const translateX = -currentSlide * 100;
            carouselSlides.style.transform = `translateX(${translateX}%)`;
            
            // Update indicators
            carouselDots.forEach((dot, index) => {
                if (index === currentSlide) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
        }
        
        function nextSlide() {
            goToSlide(currentSlide + 1);
        }
        
        function prevSlide() {
            goToSlide(currentSlide - 1);
        }
        
        // Fetch products for a specific category and subcategory
        async function loadProducts(category, subcategory) {
            try {
                // This would be an AJAX call in a real application
                // For now, we'll just filter the existing products in the DOM
                console.log(`Loading ${subcategory} from ${category}`);
                
                // In a real application, you would make an AJAX request here:
                // const response = await fetch(`/api/products/${category}/${subcategory}`);
                // const products = await response.json();
                // Then render these products in the carousel
                
            } catch (error) {
                console.error('Error loading products:', error);
            }
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
                button.setAttribute('data-subcategory', subcategory.toLowerCase());
                button.textContent = subcategory;
                
                // Add click event to load products for this subcategory
                button.addEventListener('click', function() {
                    const subcategory = this.getAttribute('data-subcategory');
                    loadProducts(category, subcategory);
                    
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
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
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
            
            // Add event listeners to product buttons
            document.querySelectorAll('.add-to-order-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const name = this.getAttribute('data-name');
                    const price = this.getAttribute('data-price');
                    const category = this.getAttribute('data-category');
                    const image = this.getAttribute('data-image');
                    addToOrder(name, price, category, image);
                });
            });
            
            // Add event listeners to initial subcategory buttons
            document.querySelectorAll('.subcategory-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    // Remove active state from all subcategory buttons
                    document.querySelectorAll('.subcategory-btn').forEach(b => {
                        b.classList.remove('bg-amber-200', 'text-amber-900', 'border-amber-400');
                        b.classList.add('bg-white', 'text-amber-900', 'border-amber-200');
                        
                        // Re-add special styling for specials button
                        if (b.innerHTML.includes('fa-star')) {
                            b.classList.add('bg-amber-100', 'border-amber-300');
                        }
                    });
                    
                    // Add active state to clicked button
                    this.classList.remove('bg-white', 'border-amber-200', 'bg-amber-100', 'border-amber-300');
                    this.classList.add('bg-amber-200', 'border-amber-400');
                });
            });
            
            // Add event listeners to order action buttons
            clearOrderBtn.addEventListener('click', clearOrder);
            checkoutBtn.addEventListener('click', processCheckout);
            
            // Add event listeners to voice chat buttons
            voiceStartBtn.addEventListener('click', startVoiceAssistant);
            voiceStopBtn.addEventListener('click', stopVoiceAssistant);
            voiceHelpBtn.addEventListener('click', showVoiceHelp);
            
            // Add event listeners to order type buttons
            dineInBtn.addEventListener('click', function() {
                setOrderType('dine-in');
            });
            
            takeoutBtn.addEventListener('click', function() {
                setOrderType('takeout');
            });
            
            // Add event listeners to carousel controls
            carouselPrev.addEventListener('click', prevSlide);
            carouselNext.addEventListener('click', nextSlide);
            
            // Add event listeners to carousel dots
            carouselDots.forEach(dot => {
                dot.addEventListener('click', function() {
                    const slideIndex = parseInt(this.getAttribute('data-slide'));
                    goToSlide(slideIndex);
                });
            });
            
            // Initialize voice stop button as disabled
            voiceStopBtn.disabled = true;
            
            // Initialize calculations
            calculateTotals();
            
            // Prevent scrolling on the entire page
            document.body.style.overflow = 'hidden';
        });
    </script>
</body>
</html>