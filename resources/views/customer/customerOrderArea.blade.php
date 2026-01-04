<!-- CUSTOMER ORDER AREA VIEW -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Order Area - Caffé Arabica</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
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
        
        /* Voice recording animation */
        .voice-recording {
            animation: recordingPulse 1.5s infinite;
        }
        
        @keyframes recordingPulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
            100% { transform: scale(1); opacity: 1; }
        }
        
        .voice-level-indicator {
            display: flex;
            gap: 2px;
            align-items: center;
            margin-left: 10px;
        }
        
        .voice-level-bar {
            width: 3px;
            height: 20px;
            background-color: #3B82F6;
            border-radius: 2px;
            animation: voiceLevel 1.5s infinite;
        }
        
        .voice-level-bar:nth-child(2) { animation-delay: 0.2s; }
        .voice-level-bar:nth-child(3) { animation-delay: 0.4s; }
        .voice-level-bar:nth-child(4) { animation-delay: 0.6s; }
        .voice-level-bar:nth-child(5) { animation-delay: 0.8s; }
        
        @keyframes voiceLevel {
            0%, 100% { height: 5px; opacity: 0.5; }
            50% { height: 20px; opacity: 1; }
        }
        
        /* Order type button styles */
        .order-type-btn {
            transition: all 0.2s ease;
        }
        
        .order-type-btn.active {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        /* Payment method button styles */
        .payment-btn {
            transition: all 0.2s ease;
        }
        
        .payment-btn.active {
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
            display: flex;
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

        /* Dropdown styling */
        .order-type-dropdown {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .order-type-dropdown option {
            padding: 8px;
            color: #1f2937;
        }
        
        /* Modal Animation */
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .modal-show {
            animation: modalSlideIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        .modal-bg-show {
            animation: modalFadeIn 0.3s ease-out forwards;
        }
        
        /* Button pulse animation */
        @keyframes pulse-once {
            0% {
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(34, 197, 94, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
            }
        }

        .pulse-once {
            animation: pulse-once 2s ease-out;
        }

        /* Item fade in animation */
        .order-item-animate {
            animation: itemFadeIn 0.5s ease-out forwards;
        }

        @keyframes itemFadeIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Shake animation for checkout button */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        .animate-pulse {
            animation: pulse 0.5s ease-in-out 3;
        }

        /* Success animation for confirmation */
        @keyframes successScale {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .success-animation {
            animation: successScale 0.3s ease-out;
        }
        
        /* Empty cart shake */
        @keyframes emptyCartShake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .empty-cart-shake {
            animation: emptyCartShake 0.5s ease-in-out;
        }
        
    </style>
</head>
<body class="bg-gray-50 h-full">
    <!-- Main Container -->
    <div class="flex h-full">
        <!-- Left Panel: Menu Content -->
        <div class="flex-1 flex flex-col h-full overflow-hidden">
            <!-- Blue Area: Brand Header -->
            <div class="bg-black w-full flex items-center justify-center overflow-hidden">
                <div class="w-full max-h-[200px]"> 
                    <img src="{{ asset('images/Brand Header.svg') }}" 
                        alt="Caffé Arabica" 
                        class="w-full h-full object-cover block">
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
                        @foreach($slides as $slideIndex => $slideProducts)
                            <div class="carousel-page" data-page="{{ $slideIndex }}" style="min-width: 100%; transition: transform 0.5s ease;">
                                <div class="product-grid">
                                    @forelse($slideProducts as $product)
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
                                    @empty
                                        <div class="col-span-3 row-span-2 flex items-center justify-center">
                                            <div class="text-center">
                                                <i class="fas fa-utensils text-gray-300 text-6xl mb-4"></i>
                                                <h3 class="text-lg font-semibold text-gray-500 mb-2">No products found</h3>
                                                <p class="text-gray-400 text-sm">No items available in this category</p>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Carousel Indicators -->
                    <div class="carousel-indicator" id="carousel-indicators">
                        @for($i = 0; $i < count($slides); $i++)
                            <div class="carousel-dot {{ $i === 0 ? 'active' : '' }}" data-slide="{{ $i }}"></div>
                        @endfor
                    </div>
                </div>
            </div>
            
            <!-- Yellow Area: Voice Chat -->
            <div class="bg-gradient-to-r from-amber-100 to-yellow-100 border-t border-amber-200 p-3">
                <div class="max-w-6xl mx-auto">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-2">
                        <div class="flex items-center">
                            <div id="voice-icon" class="bg-blue-500 text-white p-2 rounded-full mr-3 voice-pulse">
                                <i class="fas fa-microphone"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 text-sm">Voice Assistant</h3>
                                <p class="text-gray-600 text-xs">Say "Hey Arabica" to start voice ordering</p>
                            </div>
                            <!-- Add voice level indicator -->
                            <div id="voice-level-indicator" class="voice-level-indicator hidden">
                                <div class="voice-level-bar"></div>
                                <div class="voice-level-bar"></div>
                                <div class="voice-level-bar"></div>
                                <div class="voice-level-bar"></div>
                                <div class="voice-level-bar"></div>
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
                                <span id="voice-status-badge" class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded">Ready</span>
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
            <!-- Order Summary Header with Order Type Dropdown -->
            <div class="bg-white p-4 border-b border-gray-200 h-[94px] lg:h-[107px] flex flex-col justify-center transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">
                            <i class="fas fa-shopping-cart mr-2 text-amber-700"></i>Order Summary
                        </h2>
                        <p class="text-gray-500 text-sm mt-1">Review your order</p>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <span class="text-gray-600 text-sm font-medium">Order Type:</span>
                        <div class="relative">
    <select id="order-type-dropdown" class="bg-amber-600 text-white text-sm font-medium rounded-lg py-2 pl-3 pr-8 appearance-none focus:outline-none focus:ring-2 focus:ring-amber-300 border border-amber-600 cursor-pointer shadow-sm">
        <option value="dine-in" class="text-gray-800 bg-white">Dine-in</option>
        <option value="takeout" class="text-gray-800 bg-white">Takeout</option>
    </select>
    
    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-white">
        <i class="fas fa-chevron-down text-xs"></i>
    </div>
</div>
                    </div>
                </div>
            </div>
            
            <!-- Order Items -->
            <div class="flex-1 p-4 overflow-hidden">
                <!-- Payment Method Selection (Now as buttons) -->
                <div class="mb-4">
                    <h4 class="font-bold text-gray-800 text-sm mb-2">
                        <i class="fas fa-credit-card mr-1"></i> Payment Method
                    </h4>
                    <div class="grid grid-cols-2 gap-2">
                        <button id="cash-btn" class="payment-btn active bg-green-600 text-white py-1 rounded-lg font-medium transition-all duration-200 flex flex-col items-center justify-center">
                            <i class="fas fa-money-bill-wave text-sm mb-0.5"></i>
                            <span class="font-bold text-sm leading-none">Cash</span>
                            <span class="text-[10px] opacity-90 leading-tight mt-0.5">Pay at counter</span>
                        </button>
                        
                        <button id="electronic-btn" class="payment-btn bg-gray-200 text-gray-800 py-1 rounded-lg font-medium transition-all duration-200 flex flex-col items-center justify-center">
                            <i class="fas fa-qrcode text-sm mb-0.5"></i>
                            <span class="font-bold text-sm leading-none">Electronic</span>
                            <span class="text-[10px] opacity-90 leading-tight mt-0.5">QR Code</span>
                        </button>
                    </div>
                    {{-- <!-- Payment Method Info -->
                    <div id="payment-info" class="mt-2 text-xs text-gray-600 p-2 bg-green-50 rounded border border-green-100">
                        <i class="fas fa-money-bill-wave text-green-500 mr-1"></i>
                        <span>Cash payment selected. Pay at the counter.</span>
                    </div> --}}
                </div>
                
                {{-- <!-- Order Type Info -->
                <div id="order-type-info" class="mb-4 text-xs text-gray-600 p-2 bg-blue-50 rounded border border-blue-100">
                    <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                    <span>Dine-in selected. Your order will be served at your table.</span>
                </div> --}}
                
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
                
                
            </div>
            
            <!-- Order Totals and Actions -->
            <div class="border-t border-gray-200 p-4 bg-gray-50 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] z-10">

                <!-- Order Notes -->
                <div class="mb-1">
                    <label for="order-notes" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-sticky-note mr-1 text-xs"></i> Order Notes
                    </label>
                    <textarea id="order-notes" rows="2" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" placeholder="Special instructions..."></textarea>
                </div>

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
                
                {{-- <!-- Order Status -->
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
                </div> --}}
                
                <!-- Action Buttons -->
                <div class="space-y-2">
                    <button id="clear-order-btn" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center text-sm">
                        <i class="fas fa-trash-alt mr-1"></i> Clear Order
                    </button>
                    <button id="checkout-btn" class="w-full bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white py-2.5 rounded-lg font-bold transition-all duration-200 hover-lift flex items-center justify-center">
                        <i class="fas fa-check-circle mr-2"></i> Checkout
                    </button>
                </div>
                
                {{-- <!-- Footer Note -->
                <div class="mt-4 pt-3 border-t border-gray-300">
                    <p class="text-xs text-gray-500 text-center">
                        <i class="fas fa-shield-alt mr-1"></i> Secure checkout
                    </p>
                </div> --}}
            </div>
        </div>
    </div>

    <!-- Include external JavaScript file -->
    <script src="{{ asset('js/customer-order-area.js') }}"></script>
</body>
</html>