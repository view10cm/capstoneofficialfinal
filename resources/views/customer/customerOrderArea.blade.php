@extends('customerBase')

@section('title', 'Caffe Arabica - Order Area')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        .active-category {
            background-color: #f97316;
            /* orange-500 */
            color: white;
        }

        .active-subcategory {
            border-bottom: 2px solid #f97316;
            /* orange-500 */
            color: #f97316;
        }

        /* Custom scrollbar for order items */
        .order-items-container {
            max-height: 300px;
            overflow-y: auto;
        }

        .order-items-container::-webkit-scrollbar {
            width: 6px;
        }

        .order-items-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .order-items-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        .order-items-container::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Loading spinner */
        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-left: 4px solid #f97316;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-right: 10px;
            vertical-align: middle;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush

@section('content')
    <div class="flex min-h-screen bg-gray-50">
        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col relative">
            <!-- Brand Header -->
            <div class="">
                <img src="{{ asset('images/Brand Header.svg') }}" alt="Caffe Arabica" class="w-full object-cover"
                    style="height: 120px; max-height: 120px;">
            </div>

            @include('customer.productsGallery')

            <!-- Spacer to push the conversation panel and bottom image down -->
            <div class="flex-1"></div>

            <!-- Bottom Customer Order Area Image with Microphone Button -->
            <div class="mt-auto relative">
                <!-- Conversation Panel - Positioned behind the microphone, full width of main content area -->
                <div class="absolute bottom-0 left-0 right-0 z-10 mb-6">
                    <div class="bg-white shadow-lg border border-gray-200 max-h-48 overflow-hidden"
                        style="font-family: 'Manrope', 'Arial', sans-serif;">
                        <!-- Conversation Messages Area -->
                        <div id="conversation-panel" class="p-6 max-h-40 overflow-y-auto scrollbar-hide"
                            style="scrollbar-width: none; -ms-overflow-style: none;">
                            <style>
                                .scrollbar-hide::-webkit-scrollbar {
                                    display: none;
                                }
                            </style>
                            <!-- Welcome Message -->
                            <div class="mb-4">
                                <div class="flex items-start space-x-3">
                                    <div class="bg-orange-100 rounded-full p-2 flex-shrink-0">
                                        <img src="{{ asset('images/voiceFill.svg') }}" alt="Voice Assistant"
                                            class="w-4 h-4">
                                    </div>
                                    <div class="flex-1">
                                        <div class="bg-gray-100 rounded-lg px-4 py-3 max-w-md">
                                            <p class="text-gray-800" style="font-size: 16px;">Hello! Welcome to Caffe
                                                Arabica. My name is Dinevo! What would you like to order today?</p>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Assistant</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Placeholder for conversation messages -->
                            <div id="conversation-messages" class="text-center text-gray-400 py-8">
                                <!-- Transcribed messages will appear here -->
                            </div>
                        </div>
                    </div>
                </div>

                <img src="{{ asset('images/orderSpeakOrange.svg') }}" alt="Customer Order Area"
                    class="w-full h-auto relative z-20">

                <!-- Microphone Button Overlay -->
                <div class="absolute inset-0 flex items-center justify-center z-30" style="transform: translateY(-25%)">
                    <button
                        class="bg-white rounded-full p-4 shadow-lg hover:shadow-xl transition-shadow duration-200 hover:bg-gray-50 active:scale-95 transform transition-transform border-2 border-gray-300"
                        id="microphone-btn" type="button">
                        <svg class="w-8 h-8 text-gray-700" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3z" />
                            <path
                                d="M17 11c0 2.76-2.24 5-5 5s-5-2.24-5-5H5c0 3.53 2.61 6.43 6 6.92V21h2v-3.08c3.39-.49 6-3.39 6-6.92h-2z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Sidebar - Order Summary -->
        <div class="w-80 bg-white shadow-lg border-l border-gray-200 rounded-tl-xl rounded-tr-xl">
            <div class="p-6 h-full flex flex-col">
                <!-- Header -->
                <div class="mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Order Summary</h2>

                    <!-- Order Type Selection -->
                    <div class="flex mt-4 space-x-2">
                        <button id="dine-in-btn"
                            class="flex-1 py-2 bg-orange-500 text-white rounded-md font-medium text-sm">
                            Dine-in
                        </button>
                        <button id="takeout-btn"
                            class="flex-1 py-2 bg-gray-200 text-gray-700 rounded-md font-medium text-sm">
                            Takeout
                        </button>
                    </div>
                </div>

                <!-- Order Items Container -->
                <div id="order-items-container" class="order-items-container mb-4">
                    <!-- Order items will be dynamically added here -->
                </div>

                <!-- Order Summary Details -->
                <div class="mt-auto border-t border-gray-200 pt-4">
                    <!-- Special Request -->
                    <div class="mb-4">
                        <textarea id="special-request" placeholder="Special Request"
                            class="w-full p-2 border border-gray-300 rounded-md text-sm resize-none" rows="2"></textarea>
                    </div>

                    <!-- Summary -->
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span>Items (<span id="items-count">0</span>)</span>
                            <span>₱<span id="subtotal">0.00</span></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Tax</span>
                            <span>₱<span id="tax">0.00</span></span>
                        </div>
                        <div class="flex justify-between font-semibold text-lg mt-2">
                            <span>Total Amount</span>
                            <span>₱<span id="total-amount">0.00</span></span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-3 mt-6">
                        <button id="cancel-order" class="flex-1 py-3 bg-gray-200 text-gray-700 rounded-md font-medium">
                            Cancel
                        </button>
                        <button id="checkout-btn" class="flex-1 py-3 bg-orange-500 text-white rounded-md font-medium"
                            disabled style="cursor: not-allowed; opacity: 0.6;">
                            Checkout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Confirmation Modal -->
    <div id="order-confirm-modal" class="fixed inset-0 flex items-center justify-center bg-transparent backdrop-blur-md bg-opacity-50 z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg p-8 max-w-sm w-full text-center">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Confirm Order?</h3>
            <p class="text-gray-600 mb-6">Your order will be sent to Caffe Arabica Staff for payment.</p>
            <div class="flex space-x-4 justify-center">
                <button id="confirm-order-btn" class="bg-orange-500 text-white px-6 py-2 rounded font-medium">Confirm</button>
                <button id="cancel-modal-btn" class="bg-gray-200 text-gray-700 px-6 py-2 rounded font-medium">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Order Success Modal -->
    <div id="order-success-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg p-8 max-w-sm w-full text-center">
            <h3 class="text-xl font-semibold mb-4 text-green-600">Order placed successfully!</h3>
            <p class="text-gray-700 mb-6">Your order ID is: <span id="order-id-success" class="font-bold text-orange-500"></span>
            </p>
            <button id="close-success-modal-btn"
                class="bg-orange-500 text-white px-6 py-2 rounded font-medium">Close</button>
        </div>
    </div>

@endsection