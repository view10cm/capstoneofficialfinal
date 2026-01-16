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
        * { font-family: 'Poppins', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; overflow: hidden; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: #c2410c; border-radius: 10px; }
        .active-tab { position: relative; }
        .active-tab::after { content: ''; position: absolute; bottom: -2px; left: 50%; transform: translateX(-50%); width: 70%; height: 3px; background-color: #c2410c; border-radius: 3px; }
        .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .hover-lift:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
        .order-items-container { max-height: 250px; overflow-y: auto; overflow-x: hidden; }
        .voice-recording { animation: recordingPulse 1.5s infinite; }
        @keyframes recordingPulse { 0% { transform: scale(1); opacity: 1; } 50% { transform: scale(1.1); opacity: 0.9; } 100% { transform: scale(1); opacity: 1; } }
        .carousel-container { position: relative; width: 100%; height: 100%; }
        .carousel-slide { transition: transform 0.5s ease-in-out; width: 100%; height: 100%; display: flex; }
        .carousel-arrow { position: absolute; top: 50%; transform: translateY(-50%); width: 50px; height: 50px; background-color: rgba(255, 255, 255, 0.9); border: 2px solid #c2410c; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 10; transition: all 0.3s ease; }
        .carousel-arrow-left { left: 10px; } .carousel-arrow-right { right: 10px; }
        .carousel-indicator { position: absolute; bottom: 15px; left: 0; right: 0; display: flex; justify-content: center; gap: 8px; z-index: 10; }
        .carousel-dot { width: 10px; height: 10px; border-radius: 50%; background-color: rgba(255, 255, 255, 0.5); border: 1px solid #c2410c; cursor: pointer; }
        .carousel-dot.active { background-color: #c2410c; transform: scale(1.2); }
        .product-grid { display: grid; grid-template-columns: repeat(3, 1fr); grid-template-rows: repeat(2, 1fr); gap: 16px; height: 100%; width: 100%; }
        .product-image-container { height: 150px; overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: #f8f8f8; }
        .product-image { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease; }
        .product-card:hover .product-image { transform: scale(1.05); }
        .payment-btn.active { background-color: #16a34a; color: white; }
        .payment-btn { background-color: #e5e7eb; color: #1f2937; }
        .modal-show { animation: modalSlideIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }
        .modal-bg-show { animation: modalFadeIn 0.3s ease-out forwards; }
        @keyframes modalSlideIn { from { opacity: 0; transform: translateY(-50px) scale(0.95); } to { opacity: 1; transform: translateY(0); } }
        @keyframes modalFadeIn { from { opacity: 0; } to { opacity: 1; } }
    </style>
</head>
<body class="bg-gray-50 h-full">
    <div class="flex h-full">
        <div class="flex-1 flex flex-col h-full overflow-hidden">
            <div class="bg-black w-full flex items-center justify-center overflow-hidden">
                <div class="w-full max-h-[200px]"> 
                    <img src="{{ asset('images/Brand Header.svg') }}" alt="Caffé Arabica" class="w-full h-full object-cover block">
                </div>
            </div>
            
            <div class="bg-white border-b border-gray-200">
                <div class="px-4 py-2">
                    <nav class="flex justify-center space-x-1 md:space-x-2">
                        <button data-category="main-course" class="upper-nav-btn active-tab px-4 py-3 text-sm md:text-base font-medium text-gray-800 hover:text-amber-900 transition-colors duration-200"><i class="fas fa-utensils mr-2"></i>Main Course</button>
                        <button data-category="appetizers" class="upper-nav-btn px-4 py-3 text-sm md:text-base font-medium text-gray-800 hover:text-amber-900 transition-colors duration-200"><i class="fas fa-seedling mr-2"></i>Appetizers</button>
                        <button data-category="drinks" class="upper-nav-btn px-4 py-3 text-sm md:text-base font-medium text-gray-800 hover:text-amber-900 transition-colors duration-200"><i class="fas fa-glass-whiskey mr-2"></i>Drinks</button>
                    </nav>
                </div>
                <div class="bg-amber-50 border-y border-amber-100">
                    <div class="px-4">
                        <div id="lower-nav" class="py-2 flex flex-wrap justify-center gap-1 md:gap-3">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex-1 p-4 overflow-hidden relative">
                <div class="carousel-container">
                    <div class="carousel-arrow carousel-arrow-left" id="carousel-prev"><i class="fas fa-chevron-left"></i></div>
                    <div class="carousel-arrow carousel-arrow-right" id="carousel-next"><i class="fas fa-chevron-right"></i></div>
                    <div id="carousel-slides" class="carousel-slide">
                        <div class="flex items-center justify-center h-full" style="min-width: 100%">
                            <div class="text-center">
                                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-amber-600 mx-auto"></div>
                                <p class="mt-4 text-gray-600">Loading products...</p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-indicator hidden" id="carousel-indicators">
                    </div>
                </div>
            </div>
            
            <div id="voice-trigger-area" class="bg-gradient-to-r from-amber-100 to-yellow-100 border-t border-amber-200 p-3 cursor-pointer hover:bg-amber-200 transition-colors duration-300">
                <div class="max-w-6xl mx-auto">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-2">
                        <div class="flex items-center">
                            <div id="voice-icon" class="bg-blue-500 text-white p-3 rounded-full mr-3 shadow-md transition-all duration-300"><i class="fas fa-microphone text-xl"></i></div>
                            <div>
                                <h3 class="font-bold text-gray-800 text-sm">Tap to Order</h3>
                                <p class="text-gray-600 text-xs" id="voice-feedback">Click here to speak your order</p>
                            </div>
                        </div>
                        <div class="text-center md:text-right">
                            <div id="voice-status" class="text-xs font-medium text-blue-700 bg-blue-100 px-2 py-1 rounded-full">Ready</div>
                        </div>
                    </div>
                    <div id="voice-command-display" class="mt-3 hidden transition-all duration-300">
                        <div class="bg-white rounded-lg p-3 border border-blue-200 shadow-sm">
                            <div class="flex justify-between items-center mb-1">
                                <h4 class="font-bold text-gray-800 text-xs uppercase tracking-wide">Your Request:</h4>
                            </div>
                            <div id="voice-transcript" class="text-gray-800 font-medium text-sm pl-1">...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="w-1/3 min-w-96 bg-white border-l border-gray-200 flex flex-col h-full overflow-hidden">
            <div class="bg-white pl-4 pr-4 pt-4 pb-2 border-b border-gray-200 flex flex-col justify-center">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-gray-800"><i class="fas fa-shopping-cart mr-1 text-amber-700 text-sm"></i>Order Summary</h2>
                        <p class="text-gray-500 text-xs mt-1">Review your order</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-gray-600 text-xs font-medium">Type:</span>
                        <div class="relative">
                            <select id="order-type-dropdown" class="bg-amber-600 text-white text-xs font-medium rounded-lg py-1.5 pl-2 pr-6 focus:outline-none shadow-sm cursor-pointer appearance-none">
                                <option value="dine-in" class="text-gray-800 bg-white">Dine-in</option>
                                <option value="takeout" class="text-gray-800 bg-white">Takeout</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-1.5 text-white"><i class="fas fa-chevron-down text-xs"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex-1 pl-4 pr-4 pt-2 pb-2 overflow-hidden">
                <div class="mb-2">
                    <h4 class="font-bold text-gray-800 text-sm mb-2"><i class="fas fa-credit-card mr-1"></i> Payment Method</h4>
                    <div class="grid grid-cols-2 gap-2">
                        <button id="cash-btn" class="payment-btn active py-1 rounded-lg font-medium flex flex-col items-center justify-center"><i class="fas fa-money-bill-wave text-sm mb-0.5"></i><span class="font-bold text-sm">Cash</span></button>
                        <button id="electronic-btn" class="payment-btn py-1 rounded-lg font-medium flex flex-col items-center justify-center"><i class="fas fa-qrcode text-sm mb-0.5"></i><span class="font-bold text-sm">Electronic</span></button>
                    </div>
                </div>
                <div id="order-items-container" class="order-items-container">
                    <div id="empty-order" class="text-center py-20"><i class="fas fa-shopping-cart text-gray-300 text-4xl mb-3"></i><h3 class="text-lg font-semibold text-gray-500">Your order is empty</h3></div>
                    <div id="order-items-list" class="space-y-3 mt-1"></div>
                </div>
            </div>
            
            <div class="border-t border-gray-200 p-4 bg-gray-50 shadow-md z-10">
                <div class="mb-1">
                    <label for="order-notes" class="block text-sm font-medium text-gray-700 mb-1"><i class="fas fa-sticky-note mr-1 text-xs"></i> Notes</label>
                    <textarea id="order-notes" rows="1" maxlength="50" class="w-full p-2 border border-gray-300 rounded-lg text-sm" placeholder="Special instructions..."></textarea>
                </div>
                <div class="space-y-2 mb-2">
                    <div class="flex justify-between text-gray-600 text-sm hidden"><span>Subtotal:</span><span id="subtotal">₱0.00</span></div>
                    <div class="flex justify-between font-bold text-gray-800 pt-2 border-t border-gray-300"><span>Total:</span><span id="total">₱0.00</span></div>
                </div>
                <div class="flex gap-2">
                    <button id="clear-order-btn" class="w-1/2 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 rounded-lg font-medium text-sm"><i class="fas fa-trash-alt mr-1"></i> Clear Order</button>
                    <button id="checkout-btn" class="w-1/2 bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white py-2.5 rounded-lg font-bold hover-lift"><i class="fas fa-check-circle mr-2"></i> Checkout</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/customer-order-base.js') }}"></script>
    <script src="{{ asset('js/customer-order-cart.js') }}"></script>
    <script src="{{ asset('js/customer-order-modal.js') }}"></script>
    <script src="{{ asset('js/customer-order-voice.js') }}?v={{ time() }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeBaseApp();
            initializeVoiceDOMElements();
            initializeCartEventListeners();
            initializeModalEventListeners();
            initializeVoiceEventListeners();
            createCheckoutModal();
            createPaymentQueueModal();
            calculateTotals();
            
            // Load initial category and subcategory
            const mainCourseBtn = document.querySelector('[data-category="main-course"]');
            if (mainCourseBtn) {
                updateLowerNav('main-course');
                loadProducts('main-course', 'pork');
            }
        });
    </script>
</body>
</html>