<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Staff Landing Page - AFFE ARABICA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Cinzel:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .clock {
            font-variant-numeric: tabular-nums;
            letter-spacing: 1px;
        }
        .header-bg {
            background-color: #191C22;
        }
        .cinzel-font {
            font-family: 'Cinzel', serif;
        }
        .logo-placeholder {
            width: 60px;
            height: 60px;
            background: transparent;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 16px;
            box-shadow: none;
        }
        .coffee-icon {
            color: #F5DEB3;
            font-size: 32px;
        }
        .order-tracker-btn {
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .order-tracker-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            background-color: #92400e;
        }
        .order-tracker-btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .order-card {
            transition: all 0.3s ease;
        }
        .order-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        .status-timer {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
        }
        .pagination-btn {
            background-color: #374151;
            border: 1px solid #4B5563;
            transition: all 0.2s ease;
        }
        .pagination-btn:hover:not(:disabled) {
            background-color: #4B5563;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .pagination-btn:active:not(:disabled) {
            transform: translateY(0);
        }
        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .footer-link {
            color: #9CA3AF;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .footer-link:hover {
            color: #FFFFFF;
            text-decoration: underline;
        }
        /* Reduced footer height */
        .compact-footer {
            padding-top: 0.75rem !important;
            padding-bottom: 0.75rem !important;
        }
        .compact-footer .text-sm {
            font-size: 0.75rem !important;
        }
        .compact-footer .footer-link {
            font-size: 0.75rem !important;
        }
        /* Payment method badges */
        .payment-badge-cash {
            background-color: #047857;
            color: #D1FAE5;
        }
        .payment-badge-electronic {
            background-color: #7C3AED;
            color: #EDE9FE;
        }
        /* Payment number badge */
        .payment-number-badge {
            background-color: #DC2626;
            color: #FFFFFF;
        }
        /* Quantity badge */
        .quantity-badge {
            background-color: #1E40AF;
            color: #FFFFFF;
            font-size: 0.75rem;
            padding: 2px 6px;
            border-radius: 4px;
            margin-left: 8px;
            font-weight: 600;
        }
        /* Price styling */
        .price-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .price-tag {
            color: #FBBF24;
            font-weight: 500;
            font-size: 0.95rem;
        }
        .total-row {
            color: #10B981;
            font-weight: 600;
            border-top: 1px solid #4B5563;
            padding-top: 12px;
            margin-top: 12px;
            font-size: 1.1rem;
        }
        .price-section {
            background-color: rgba(30, 41, 59, 0.5);
            border-radius: 8px;
            padding: 12px;
            margin-top: 12px;
            border: 1px solid #334155;
        }
        .currency {
            font-family: 'Poppins', sans-serif;
            margin-right: 2px;
        }
        /* Order items text color - NEW */
        .order-item-text {
            color: #FFFFFF;
        }
        /* Ensure all text in order cards is white by default */
        .order-card span:not(.price-tag):not(.text-amber-400):not(.text-blue-400):not(.text-green-400):not(.text-red-500):not(.text-yellow-500):not(.text-gray-400) {
            color: #FFFFFF;
        }
        .order-card .text-gray-300 {
            color: #D1D5DB !important;
        }
        /* Item details container */
        .item-details {
            display: flex;
            align-items: center;
            flex-grow: 1;
        }
        /* Checkbox styling */
        .item-checkbox {
            appearance: none;
            -webkit-appearance: none;
            width: 20px;
            height: 20px;
            background-color: #374151;
            border: 2px solid #4B5563;
            border-radius: 4px;
            margin-right: 12px;
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
        }
        .item-checkbox:hover {
            border-color: #60A5FA;
            background-color: #4B5563;
        }
        .item-checkbox:checked {
            background-color: #10B981;
            border-color: #10B981;
        }
        .item-checkbox:checked::after {
            content: '✓';
            position: absolute;
            color: white;
            font-size: 14px;
            font-weight: bold;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .item-checkbox:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .item-checkbox-void {
            background-color: #DC2626;
            border-color: #DC2626;
        }
        .item-checkbox-void:checked {
            background-color: #DC2626;
            border-color: #DC2626;
        }
        .item-checkbox-void:hover {
            background-color: #B91C1C;
            border-color: #B91C1C;
        }
        /* Checked item styling */
        .item-checked {
            opacity: 0.7;
        }
        .item-checked .order-item-text {
            text-decoration: line-through;
            color: #9CA3AF;
        }
        .item-checked .quantity-badge {
            opacity: 0.7;
        }
        /* Select All section */
        .select-all-section {
            background-color: rgba(55, 65, 81, 0.7);
            border-radius: 6px;
            padding: 8px 12px;
            margin-top: 12px;
            margin-bottom: 16px;
            border: 1px solid #4B5563;
        }
        .select-all-checkbox {
            appearance: none;
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            background-color: #374151;
            border: 2px solid #4B5563;
            border-radius: 4px;
            margin-right: 8px;
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
        }
        .select-all-checkbox:hover {
            border-color: #60A5FA;
            background-color: #4B5563;
        }
        .select-all-checkbox:checked {
            background-color: #3B82F6;
            border-color: #3B82F6;
        }
        .select-all-checkbox:checked::after {
            content: '✓';
            position: absolute;
            color: white;
            font-size: 12px;
            font-weight: bold;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .select-all-checkbox-void {
            background-color: #DC2626;
            border-color: #DC2626;
        }
        /* Checkbox container */
        .checkbox-container {
            display: flex;
            align-items: center;
            width: 100%;
        }
        /* Void state styling */
        .void-state {
            border: 2px solid #DC2626;
            box-shadow: 0 0 0 1px rgba(220, 38, 38, 0.3);
        }
        .void-state .payment-number-badge {
            background-color: #991B1B;
        }
        .void-warning {
            background-color: rgba(220, 38, 38, 0.1);
            border: 1px solid #DC2626;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 15px;
        }
        .void-warning-text {
            color: #FCA5A5;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .void-check-icon {
            color: #DC2626;
            margin-right: 8px;
        }
        /* Removed item animation */
        .item-removing {
            animation: removeItem 0.3s ease-out forwards;
        }
        @keyframes removeItem {
            0% {
                opacity: 1;
                max-height: 100px;
                transform: translateX(0);
            }
            50% {
                opacity: 0.5;
                transform: translateX(-10px);
            }
            100% {
                opacity: 0;
                max-height: 0;
                padding: 0;
                margin: 0;
                transform: translateX(20px);
                display: none;
            }
        }
        /* Empty order message */
        .empty-order-message {
            background-color: rgba(55, 65, 81, 0.5);
            border-radius: 6px;
            padding: 20px;
            text-align: center;
            margin: 10px 0;
        }
        .empty-order-text {
            color: #9CA3AF;
            font-style: italic;
        }
        /* Modal styles for Confirm Payment */
        .modal-overlay {
            background-color: rgba(0, 0, 0, 0.5);
        }
        #amount-paid:focus {
            box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.2);
        }
        #confirm-payment-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        #confirm-payment-btn:disabled:hover {
            background-color: #059669;
        }
        /* Product list in modal */
        #modal-products-list {
            scrollbar-width: thin;
            scrollbar-color: #4B5563 #1F2937;
        }
        #modal-products-list::-webkit-scrollbar {
            width: 6px;
        }
        #modal-products-list::-webkit-scrollbar-track {
            background: #1F2937;
            border-radius: 3px;
        }
        #modal-products-list::-webkit-scrollbar-thumb {
            background-color: #4B5563;
            border-radius: 3px;
        }
        .product-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            background-color: rgba(30, 41, 59, 0.5);
            border-radius: 6px;
            border: 1px solid #374151;
        }
        .product-name {
            color: #E5E7EB;
            font-weight: 500;
        }
        .product-quantity {
            background-color: #1E40AF;
            color: white;
            font-size: 0.75rem;
            padding: 2px 6px;
            border-radius: 4px;
            margin-left: 8px;
            font-weight: 600;
        }
        .product-price {
            color: #FBBF24;
            font-weight: 500;
        }
        .product-status {
            font-size: 0.75rem;
            padding: 2px 8px;
            border-radius: 4px;
            margin-left: 8px;
        }
        .status-selected {
            background-color: #059669;
            color: #D1FAE5;
        }
        /* Item notes styling */
        .item-notes-section {
            background-color: rgba(245, 158, 11, 0.1);
            border-radius: 4px;
            padding: 6px 8px;
            margin-top: 4px;
            margin-left: 32px; /* Align with item text */
            border-left: 2px solid #F59E0B; /* Amber accent for notes */
            font-size: 0.85rem;
        }
        .notes-label {
            color: #F59E0B; /* Amber color for label */
            font-weight: 500;
            font-size: 0.85rem;
            margin-right: 6px;
        }
        .notes-content {
            color: #E5E7EB; /* Light gray for note text */
            font-size: 0.85rem;
            line-height: 1.3;
            word-wrap: break-word;
            white-space: pre-wrap; /* Preserve line breaks */
        }
        .notes-none {
            color: #9CA3AF; /* Gray color for "None" */
            font-style: italic;
            font-size: 0.85rem;
        }
        /* Order-level notes summary */
        .order-notes-summary {
            background-color: rgba(55, 65, 81, 0.5);
            border-radius: 6px;
            padding: 10px 12px;
            margin-top: 12px;
            margin-bottom: 16px;
            border: 1px solid #4B5563;
            border-left: 3px solid #F59E0B; /* Amber accent */
        }
        .order-notes-label {
            color: #F59E0B; /* Amber color for label */
            font-weight: 500;
            font-size: 0.9rem;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .order-notes-label i {
            font-size: 0.8rem;
        }
        .order-notes-content {
            color: #E5E7EB; /* Light gray for note text */
            font-size: 0.9rem;
            line-height: 1.4;
            word-wrap: break-word;
            white-space: pre-wrap; /* Preserve line breaks */
        }
        /* Admin Password Modal Styles */
        #admin-password-modal .modal-content {
            animation: modalSlideIn 0.3s ease-out;
        }
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .password-hide {
            display: none;
        }
        .password-show {
            display: block;
        }
    </style>
</head>
<body class="bg-gray-900 flex flex-col min-h-screen">
    <!-- Fixed Header -->
    <div class="header-bg shadow-md fixed top-0 left-0 right-0 z-50">
        <div class="flex items-center justify-between px-6 py-4">
            <!-- Left Section: Logo/Title -->
            <div class="flex items-center">
                <div class="logo-placeholder">
                    <div class="coffee-icon">
                        <img src="/images/Coffee.svg" alt="CAFFE ARABICA Logo" class="w-full h-full">
                    </div>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight cinzel-font" style="font-size: 32px;">CAFFE ARABICA</h1>
                    <p class="text-sm text-gray-400 cinzel-font" style="font-size: 24px;">Kitchen Display System</p>
                </div>
            </div>
            
            <!-- Right Section: Employee & Time Info -->
            <div class="flex items-center space-x-8">
                <!-- Employee Info -->
                <div class="text-right">
                    <p class="text-sm text-gray-400" style="font-size: 15px;">Employee</p>
                    <p class="font-semibold text-white" style="font-size: 18px;">{{ auth()->user()->name ?? 'Staff Member' }}</p>
                </div>
                
                <!-- Order Tracker Button -->
                <div class="relative">
                    <button id="order-tracker-btn" onclick="navigateToOrderTracker()" class="order-tracker-btn bg-amber-900 border border-amber-800 rounded-lg px-4 py-2">
                        <p class="text-amber-100 font-medium" style="font-size: 15px;">Order Tracker</p>
                    </button>
                    <div class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 rounded-full flex items-center justify-center">
                        <span id="order-count" class="text-white text-xs font-bold">0</span>
                    </div>
                </div>
                
                <!-- Live Clock -->
                <div class="bg-gray-800 text-white rounded-lg px-4 py-3 min-w-[130px] text-center clock">
                    <div id="live-clock" class="text-xl font-bold tracking-wider" style="font-size: 22px;">2:45:59 PM</div>
                    <div id="current-date" class="text-xs text-gray-400" style="font-size: 13px;">May 25, 2025</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area (Order Display) -->
    <div class="pt-36 pb-8 px-6 flex-grow">
        <div class="max-w-7xl mx-auto">
            <!-- Pagination Controls -->
            <div class="flex justify-between items-center mb-6">
                <!-- Left Pagination Button -->
                <button id="prev-page-btn" class="pagination-btn text-white px-5 py-3 rounded-lg flex items-center space-x-2" disabled>
                    <i class="fas fa-chevron-left"></i>
                    <span>Previous</span>
                </button>
                
                <!-- Page Indicator -->
                <div class="text-gray-300 text-lg font-medium">
                    Page <span id="current-page">1</span> of <span id="total-pages">1</span>
                </div>
                
                <!-- Right Pagination Button -->
                <button id="next-page-btn" class="pagination-btn text-white px-5 py-3 rounded-lg flex items-center space-x-2" disabled>
                    <span>Next</span>
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            
            <!-- Orders Grid -->
            <div id="orders-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <!-- Orders will be dynamically loaded via JavaScript -->
                <div class="text-center text-gray-400 col-span-full py-12">
                    <i class="fas fa-coffee text-5xl mb-4"></i>
                    <p class="text-xl">No orders found</p>
                    <p class="text-sm mt-2">Orders will appear here as they are created</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer with Reduced Height -->
    <footer class="bg-gray-900 border-t border-gray-800 compact-footer">
        <div class="max-w-7xl mx-auto px-6">
            <!-- First Line: All Rights Reserved (Centered) -->
            <div class="text-center mb-1">
                <p class="text-gray-500 text-sm">
                    © 2025 CAFFE ARABICA Kitchen Display System. All Rights Reserved.
                </p>
            </div>
            
            <!-- Second Line: Terms and Conditions (Left) and Privacy Policy (Right) -->
            <div class="flex flex-col sm:flex-row justify-between items-center">
                <!-- Left: Terms and Conditions -->
                <div class="mb-1 sm:mb-0">
                    <button id="terms-btn" class="footer-link text-sm">
                        Terms and Conditions
                    </button>
                </div>
                
                <!-- Right: Privacy Policy -->
                <div>
                    <button id="privacy-btn" class="footer-link text-sm">
                        Privacy Policy
                    </button>
                </div>
            </div>
        </div>
    </footer>

    <!-- Status Message (Hidden by default) -->
    <div id="status-message" class="fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transform translate-y-4 transition-all duration-300 z-50">
        Order tracker clicked! Opening order details...
    </div>

    <!-- Modal for Terms and Conditions -->
    <div id="terms-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-gray-800 rounded-xl p-6 max-w-2xl w-full mx-4 max-h-[80vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-white">Terms and Conditions</h2>
                <button id="close-terms" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="text-gray-300 space-y-4">
                <p>Last Updated: May 25, 2025</p>
                
                <h3 class="text-lg font-semibold text-white">1. Acceptance of Terms</h3>
                <p>By accessing and using the CAFFE ARABICA Kitchen Display System, you agree to be bound by these Terms and Conditions. If you do not agree with any part of these terms, you must not use the system.</p>
                
                <h3 class="text-lg font-semibold text-white">2. Employee Responsibilities</h3>
                <p>Employees are responsible for maintaining the confidentiality of their login credentials and for all activities that occur under their account. Any unauthorized use of the system must be reported immediately.</p>
                
                <h3 class="text-lg font-semibold text-white">3. Order Management</h3>
                <p>The kitchen display system is intended for internal use only. All orders must be processed accurately and in a timely manner. Cancellation or voiding of orders requires proper authorization.</p>
                
                <h3 class="text-lg font-semibold text-white">4. System Usage</h3>
                <p>The system should only be used for legitimate business purposes. Any misuse or unauthorized access may result in disciplinary action.</p>
                
                <h3 class="text-lg font-semibold text-white">5. Amendments</h3>
                <p>CAFFE ARABICA reserves the right to modify these terms at any time. Continued use of the system after changes constitutes acceptance of the modified terms.</p>
            </div>
            <div class="mt-6 flex justify-end">
                <button id="accept-terms" class="bg-amber-700 hover:bg-amber-800 text-white px-4 py-2 rounded-lg">
                    I Understand
                </button>
            </div>
        </div>
    </div>

    <!-- Modal for Privacy Policy -->
    <div id="privacy-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-gray-800 rounded-xl p-6 max-w-2xl w-full mx-4 max-h-[80vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-white">Privacy Policy</h2>
                <button id="close-privacy" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="text-gray-300 space-y-4">
                <p>Last Updated: May 25, 2025</p>
                
                <h3 class="text-lg font-semibold text-white">1. Information Collection</h3>
                <p>The CAFFE ARABICA Kitchen Display System collects employee login information, order data, and timestamps for operational purposes only.</p>
                
                <h3 class="text-lg font-semibold text-white">2. Use of Information</h3>
                <p>Collected information is used solely for order processing, kitchen management, and performance analytics. No personal customer data is stored in this system.</p>
                
                <h3 class="text-lg font-semibold text-white">3. Data Security</h3>
                <p>We implement appropriate security measures to protect against unauthorized access, alteration, or destruction of data. Access to the system is restricted to authorized personnel only.</p>
                
                <h3 class="text-lg font-semibold text-white">4. Data Retention</h3>
                <p>Order data is retained for 90 days for operational and analytical purposes, after which it is securely archived or deleted.</p>
                
                <h3 class="text-lg font-semibold text-white">5. Employee Privacy</h3>
                <p>Employee activity within the system is monitored for quality control and training purposes only. Individual performance data is confidential and accessible only to management.</p>
                
                <h3 class="text-lg font-semibold text-white">6. Third-Party Disclosure</h3>
                <p>We do not sell, trade, or otherwise transfer system data to outside parties, except as required by law or for essential business operations.</p>
            </div>
            <div class="mt-6 flex justify-end">
                <button id="accept-privacy" class="bg-amber-700 hover:bg-amber-800 text-white px-4 py-2 rounded-lg">
                    I Understand
                </button>
            </div>
        </div>
    </div>

    <!-- Modal for Confirm Payment -->
    <div id="confirm-payment-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-gray-800 rounded-xl p-6 max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-white">Confirm Payment</h2>
                <button id="close-confirm-payment" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Order Details -->
            <div class="space-y-4">
                <!-- Order ID and Payment Number -->
                <div class="flex justify-between items-center">
                    <span class="text-gray-300">Order ID:</span>
                    <span class="text-white font-medium" id="modal-order-id">-</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-300">Payment Number:</span>
                    <span class="text-white font-medium" id="modal-payment-number">-</span>
                </div>
                
                <hr class="border-gray-700 my-2">
                
                <!-- Order Type and Payment Method -->
                <div class="flex justify-between items-center">
                    <span class="text-gray-300">Order Type:</span>
                    <span class="text-white font-medium" id="modal-order-type">-</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-300">Payment Method:</span>
                    <span class="text-white font-medium" id="modal-payment-method">-</span>
                </div>
                
                <hr class="border-gray-700 my-2">
                
                <!-- Products/Items List -->
                <div class="bg-gray-900 rounded-lg p-4">
                    <h3 class="text-white font-medium mb-3">Products to be Paid:</h3>
                    <div id="modal-products-list" class="space-y-3 max-h-60 overflow-y-auto pr-2">
                        <!-- Products will be dynamically added here -->
                        <div class="text-center text-gray-500 py-4">
                            <i class="fas fa-shopping-basket mb-2"></i>
                            <p>Loading products...</p>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-gray-700">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Total Items:</span>
                            <span class="text-white font-medium" id="modal-total-items">0</span>
                        </div>
                        <div class="flex justify-between items-center mt-1">
                            <span class="text-gray-300">Selected Items:</span>
                            <span class="text-blue-400 font-medium" id="modal-selected-items">0</span>
                        </div>
                    </div>
                </div>
                
                <!-- Amount Details -->
                <div class="bg-gray-900 p-4 rounded-lg">
                    <!-- Vatable Sales (89.3% of Total) -->
                    <div class="flex justify-between items-center">
                        <span class="text-gray-300">Vatable Sales:</span>
                        <span class="text-amber-300 font-medium" id="modal-vatable-sales">₱0.00</span>
                    </div>
                    
                    <!-- Tax (10.7% of Total) -->
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-gray-300">Tax (10.7%):</span>
                        <span class="text-amber-300 font-medium" id="modal-tax">₱0.00</span>
                    </div>
                    
                    <!-- Total Amount -->
                    <div class="flex justify-between items-center mt-4 pt-3 border-t border-gray-700">
                        <span class="text-white font-semibold">Total Amount:</span>
                        <span class="text-green-400 font-bold text-lg" id="modal-total">₱0.00</span>
                    </div>
                </div>
                
                <hr class="border-gray-700 my-2">
                
                <!-- Staff Information -->
                <div class="flex justify-between items-center">
                    <span class="text-gray-300">Processed by:</span>
                    <span class="text-white font-medium">{{ auth()->user()->name ?? 'Staff Member' }}</span>
                </div>
                
                <!-- Amount Paid Input -->
                <div class="space-y-2">
                    <label for="amount-paid" class="block text-gray-300 text-sm font-medium">Amount Paid <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">₱</span>
                        <input 
                            type="number" 
                            id="amount-paid" 
                            step="0.01"
                            min="0"
                            placeholder="0.00"
                            class="w-full bg-gray-700 text-white pl-8 pr-4 py-3 rounded-lg border border-gray-600 focus:border-amber-500 focus:ring-2 focus:ring-amber-500 focus:outline-none transition"
                        >
                    </div>
                    <p class="text-xs text-gray-400">Enter the amount received from customer</p>
                </div>
                
                <!-- Reference Number (Optional) -->
                <div class="space-y-2">
                    <label class="block text-gray-300 text-sm font-medium">Reference Number (Optional)</label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="modal-reference-input" 
                            placeholder="e.g., TRANS-12345 or leave blank"
                            class="w-full bg-gray-700 text-gray-300 pl-4 pr-4 py-3 rounded-lg border border-gray-600 focus:border-amber-500 focus:ring-2 focus:ring-amber-500 focus:outline-none transition placeholder-gray-500"
                        >
                    </div>
                    <p class="text-xs text-gray-400">Enter transaction reference if available</p>
                </div>
                
                <!-- Change Calculation -->
                <div id="change-calculation" class="hidden">
                    <div class="flex justify-between items-center mt-2 pt-2 border-t border-gray-700">
                        <span class="text-gray-300">Change:</span>
                        <span class="text-green-400 font-medium" id="modal-change">₱0.00</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button id="cancel-payment" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                    Cancel
                </button>
                <button id="confirm-payment-btn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                    Confirm Payment
                </button>
            </div>
        </div>
    </div>

    <!-- Modal for Admin Password Confirmation -->
    <div id="admin-password-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-gray-800 rounded-xl p-6 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-white">Admin Authorization Required</h2>
                <button id="close-admin-password" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="space-y-6">
                <!-- Warning Icon and Message -->
                <div class="bg-red-900/20 border border-red-800 rounded-lg p-4 flex items-start">
                    <div class="mr-3 mt-1">
                        <i class="fas fa-shield-alt text-red-500 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-red-300 font-semibold">Confirm Product Voiding</h3>
                        <p class="text-gray-300 text-sm mt-1">This action requires Admin authorization. Please enter your Admin password to confirm.</p>
                    </div>
                </div>
                
                <!-- Order Details -->
                <div class="bg-gray-900 rounded-lg p-4">
                    <h4 class="text-gray-300 font-medium mb-2">Order Details</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Order ID:</span>
                            <span class="text-white font-medium" id="admin-modal-order-id">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Payment #:</span>
                            <span class="text-white font-medium" id="admin-modal-payment-number">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Selected Products:</span>
                            <span class="text-red-300 font-medium" id="admin-modal-product-count">0</span>
                        </div>
                    </div>
                </div>
                
                <!-- Password Input -->
                <div class="space-y-3">
                    <label for="admin-password-input" class="block text-gray-300 text-sm font-medium">
                        Admin Password <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input 
                            type="password" 
                            id="admin-password-input" 
                            placeholder="Enter Admin password"
                            class="w-full bg-gray-700 text-white pl-10 pr-4 py-3 rounded-lg border border-gray-600 focus:border-red-500 focus:ring-2 focus:ring-red-500 focus:outline-none transition"
                            autocomplete="current-password"
                        >
                        <button 
                            type="button" 
                            id="toggle-password-visibility"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-300"
                        >
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-400">Only users with Admin role can authorize voiding</p>
                    
                    <!-- Error Message -->
                    <div id="admin-password-error" class="hidden bg-red-900/30 border border-red-700 rounded-lg p-3 mt-2">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-400 mr-2"></i>
                            <span class="text-red-300 text-sm" id="admin-error-message"></span>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-700">
                    <button id="cancel-admin-auth" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                        Cancel
                    </button>
                    <button id="confirm-admin-auth" class="bg-red-700 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        Confirm Void
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Order data will be loaded from the server
        let allOrders = [];
        
        // Store checked items state
        let checkedItemsState = new Map();
        
        // Store void state for orders
        let voidStateOrders = new Map();
        
        // Store current void order data
        let currentVoidOrderData = null;
        
        // Helper function to format currency
        function formatCurrency(amount) {
            return `₱${parseFloat(amount).toFixed(2)}`;
        }
        
        // Helper function to calculate order totals WITHOUT TAX
        function calculateOrderTotals(order) {
            const subtotal = order.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const tax = subtotal * order.taxRate;
            const total = subtotal; // REMOVED TAX: total is now just subtotal without tax
            
            // Calculate vatable sales (89.3% of total) and tax (10.7% of total)
            const vatableSales = total * 0.893; // 100% - 10.7% = 89.3%
            const tax10_7 = total * 0.107; // 10.7% of total
            
            return {
                subtotal: subtotal,
                tax: tax,
                total: total, // This is now the subtotal (no tax included)
                vatableSales: vatableSales,
                tax10_7: tax10_7
            };
        }
        
        // Helper function to calculate all orders statistics
        function calculateRevenueStatistics() {
            let totalRevenue = 0;
            let totalTax = 0;
            
            allOrders.forEach(order => {
                const totals = calculateOrderTotals(order);
                totalRevenue += totals.total; // This is now subtotal without tax
                totalTax += totals.tax;
            });
            
            const avgOrderValue = allOrders.length > 0 ? totalRevenue / allOrders.length : 0;
            
            return {
                totalRevenue: totalRevenue,
                totalTax: totalTax,
                avgOrderValue: avgOrderValue
            };
        }
        
        // Helper function to calculate order totals after removing items
        function calculateOrderTotalsAfterRemoval(order, itemsToRemoveIndices) {
            const remainingItems = order.items.filter((item, index) => !itemsToRemoveIndices.includes(index.toString()));
            const subtotal = remainingItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const tax = subtotal * order.taxRate;
            const total = subtotal; // REMOVED TAX: total is now just subtotal without tax
            
            // Calculate vatable sales (89.3% of total) and tax (10.7% of total)
            const vatableSales = total * 0.893; // 100% - 10.7% = 89.3%
            const tax10_7 = total * 0.107; // 10.7% of total
            
            return {
                subtotal: subtotal,
                tax: tax,
                total: total, // This is now the subtotal (no tax included)
                vatableSales: vatableSales,
                tax10_7: tax10_7,
                remainingItems: remainingItems
            };
        }
        
        // Helper function for API calls with better error handling
        async function apiCall(url, method = 'GET', data = null) {
            try {
                const options = {
                    method: method,
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'same-origin'
                };
                
                if (data) {
                    options.body = JSON.stringify(data);
                }
                
                console.log(`Making API call to ${url}`, data);
                
                const response = await fetch(url, options);
                
                if (!response.ok) {
                    const errorText = await response.text();
                    console.error(`API error response:`, errorText);
                    throw new Error(`HTTP ${response.status}: ${errorText || response.statusText}`);
                }
                
                const responseData = await response.json();
                console.log(`API response from ${url}:`, responseData);
                return responseData;
            } catch (error) {
                console.error(`API call failed to ${url}:`, error);
                throw error;
            }
        }
        
        // Helper function to get unique key for an item
        function getItemKey(orderId, paymentNumber, itemIndex) {
            return `${orderId}-${paymentNumber}-${itemIndex}`;
        }
        
        // Helper function to get unique key for an order
        function getOrderKey(orderId, paymentNumber) {
            return `${orderId}-${paymentNumber}`;
        }
        
        // Helper function to update checked items count
        function updateCheckedItemsCount() {
            // This function could be used to update a counter if needed
            const checkedCount = Array.from(checkedItemsState.values()).filter(v => v).length;
            console.log(`Currently checked items: ${checkedCount}`);
        }
        
        // Check if an order is in void state
        function isOrderInVoidState(orderId, paymentNumber) {
            const orderKey = getOrderKey(orderId, paymentNumber);
            return voidStateOrders.get(orderKey) || false;
        }
        
        // Set void state for an order
        function setOrderVoidState(orderId, paymentNumber, isVoidState) {
            const orderKey = getOrderKey(orderId, paymentNumber);
            voidStateOrders.set(orderKey, isVoidState);
        }
        
        // Remove items from an order in the local data
        function removeItemsFromOrder(orderId, paymentNumber, itemIndices) {
            const orderIndex = allOrders.findIndex(order => 
                order.id === orderId && order.paymentNumber === paymentNumber
            );
            
            if (orderIndex === -1) return;
            
            // Filter out the items to remove
            allOrders[orderIndex].items = allOrders[orderIndex].items.filter((item, index) => 
                !itemIndices.includes(index.toString())
            );
            
            // If all items are removed, remove the entire order
            if (allOrders[orderIndex].items.length === 0) {
                allOrders.splice(orderIndex, 1);
                return true; // Order was completely removed
            }
            
            return false; // Order still has items
        }
        
        // SAFE SELECTOR FUNCTION - Fix for spaces in attribute values
        function getSafeSelector(attribute, value) {
            // Escape any special characters and handle spaces
            const escapedValue = CSS.escape(value.trim());
            return `[${attribute}="${escapedValue}"]`;
        }
        
        // Helper to get all checkboxes for an order
        function getCheckboxesForOrder(orderId, paymentNumber) {
            // Trim the values to remove spaces
            const cleanOrderId = orderId.trim();
            const cleanPaymentNumber = paymentNumber.toString().trim();
            
            // Use the safe selector
            const selector = `.item-checkbox${getSafeSelector('data-order-id', cleanOrderId)}${getSafeSelector('data-payment-number', cleanPaymentNumber)}`;
            console.log(`Looking for checkboxes with selector: ${selector}`);
            
            const checkboxes = document.querySelectorAll(selector);
            console.log(`Found ${checkboxes.length} checkboxes for order ${orderId}, payment ${paymentNumber}`);
            
            return checkboxes;
        }
        
        // Helper to get select all checkbox for an order
        function getSelectAllCheckboxForOrder(orderId, paymentNumber) {
            // Trim the values to remove spaces
            const cleanOrderId = orderId.trim();
            const cleanPaymentNumber = paymentNumber.toString().trim();
            
            // Use the safe selector
            return document.querySelector(
                `.select-all-checkbox${getSafeSelector('data-order-id', cleanOrderId)}${getSafeSelector('data-payment-number', cleanPaymentNumber)}`
            );
        }
        
        // Function to navigate to Order Tracker page
        function navigateToOrderTracker() {
            // Show status message
            showStatusMessage('Navigating to Order Tracker...', 'bg-green-600');
            
            // Add a small delay for better UX
            setTimeout(() => {
                window.location.href = '/staff/order-tracker';
            }, 500);
        }
        
        // Pagination variables
        const ordersPerPage = 4;
        let currentPage = 1;
        let totalPages = 1;
        
        // DOM Elements
        const ordersContainer = document.getElementById('orders-container');
        const prevPageBtn = document.getElementById('prev-page-btn');
        const nextPageBtn = document.getElementById('next-page-btn');
        const currentPageElement = document.getElementById('current-page');
        const totalPagesElement = document.getElementById('total-pages');
        const orderCountElement = document.getElementById('order-count');
        const orderTrackerBtn = document.getElementById('order-tracker-btn');
        const statusMessage = document.getElementById('status-message');
        
        // Footer elements
        const termsBtn = document.getElementById('terms-btn');
        const privacyBtn = document.getElementById('privacy-btn');
        const termsModal = document.getElementById('terms-modal');
        const privacyModal = document.getElementById('privacy-modal');
        const closeTerms = document.getElementById('close-terms');
        const closePrivacy = document.getElementById('close-privacy');
        const acceptTerms = document.getElementById('accept-terms');
        const acceptPrivacy = document.getElementById('accept-privacy');
        
        // Confirm Payment Modal Elements
        const confirmPaymentModal = document.getElementById('confirm-payment-modal');
        const closeConfirmPayment = document.getElementById('close-confirm-payment');
        const cancelPayment = document.getElementById('cancel-payment');
        const confirmPaymentBtn = document.getElementById('confirm-payment-btn');
        const amountPaidInput = document.getElementById('amount-paid');
        const referenceInput = document.getElementById('modal-reference-input');
        
        // Admin Password Modal Elements
        const adminPasswordModal = document.getElementById('admin-password-modal');
        const closeAdminPassword = document.getElementById('close-admin-password');
        const cancelAdminAuth = document.getElementById('cancel-admin-auth');
        const confirmAdminAuth = document.getElementById('confirm-admin-auth');
        const adminPasswordInput = document.getElementById('admin-password-input');
        const togglePasswordVisibility = document.getElementById('toggle-password-visibility');
        const adminPasswordError = document.getElementById('admin-password-error');
        const adminErrorElement = document.getElementById('admin-error-message');
        const adminModalOrderId = document.getElementById('admin-modal-order-id');
        const adminModalPaymentNumber = document.getElementById('admin-modal-payment-number');
        const adminModalProductCount = document.getElementById('admin-modal-product-count');
        
        // Initialize
        loadOrders();
        
        // Function to load orders from server
        async function loadOrders() {
            try {
                const data = await apiCall('/api/staff/orders');
                
                // Transform the data to match the expected format
                allOrders = transformOrderData(data);
                
                // Update pagination
                totalPages = Math.ceil(allOrders.length / ordersPerPage);
                updatePagination();
                
                // Render orders
                renderOrders();
                
                // Update order count
                orderCountElement.textContent = allOrders.length;
                
                // Update statistics
                updateStatistics();
                updateRevenueStats();
            } catch (error) {
                console.error('Error loading orders:', error);
                showStatusMessage('Error loading orders: ' + error.message, 'bg-red-600');
            }
        }
        
        // Function to transform database data to frontend format
        function transformOrderData(orders) {
            // Group by orderID and paymentNumber to combine multiple items into single orders
            const groupedOrders = {};
            
            orders.forEach(order => {
                // Create a unique key using orderID and paymentNumber
                const orderKey = `${order.orderID}-${order.paymentNumber}`;
                
                if (!groupedOrders[orderKey]) {
                    groupedOrders[orderKey] = {
                        id: order.orderID,
                        paymentNumber: order.paymentNumber.toString().trim(),
                        time: calculateOrderTime(order.orderCreateDateAndTime),
                        type: order.orderType === 'dine-in' ? 'Dine in' : 'Takeout',
                        typeColor: order.orderType === 'dine-in' ? 'bg-blue-900 text-blue-200' : 'bg-purple-900 text-purple-200',
                        payment: order.orderPaymentMethod === 'cash' ? 'Cash' : 'Electronic',
                        items: [],
                        taxRate: 0.12, // Still store tax rate but won't use it for display
                        status: order.orderProductStatus || 'For Payment',
                        notes: 'None' // Initialize as 'None' - will be updated if any items have notes
                    };
                }
                
                // Add item to the order with its individual notes
                groupedOrders[orderKey].items.push({
                    name: order.orderProductName,
                    price: parseFloat(order.orderTotalProductPrice) / order.orderQuantity,
                    quantity: order.orderQuantity,
                    totalPrice: parseFloat(order.orderTotalProductPrice),
                    status: order.orderProductStatus || 'active',
                    notes: order.orderNotes || null // Store item-specific notes
                });
            });
            
            // After grouping, process notes at both order and item level
            Object.values(groupedOrders).forEach(order => {
                // Collect all unique non-empty notes from items
                const itemNotes = order.items
                    .map(item => item.notes)
                    .filter(note => note && note.trim() !== '');
                
                // Remove duplicates
                const uniqueNotes = [...new Set(itemNotes)];
                
                // Combine notes for order-level display
                if (uniqueNotes.length > 0) {
                    order.notes = uniqueNotes.join(' | ');
                }
                
                // Also keep item-level notes for display
                order.items.forEach(item => {
                    // Clean up item notes for display
                    if (item.notes && item.notes.trim() !== '') {
                        item.displayNotes = item.notes.trim();
                    } else {
                        item.displayNotes = null;
                    }
                });
            });
            
            // Convert to array
            return Object.values(groupedOrders);
        }
        
        // Calculate time since order was created
        function calculateOrderTime(createDateTime) {
            const now = new Date();
            const orderTime = new Date(createDateTime);
            const diffMinutes = Math.floor((now - orderTime) / (1000 * 60));
            
            if (diffMinutes < 60) {
                return `${diffMinutes}m`;
            } else {
                const hours = Math.floor(diffMinutes / 60);
                return `${hours}h`;
            }
        }
        
        // Function to render products in the modal
        function renderProductsInModal(orderId, paymentNumber, items, selectedItems) {
            const productsListContainer = document.getElementById('modal-products-list');
            
            if (!items || items.length === 0) {
                productsListContainer.innerHTML = `
                    <div class="text-center text-gray-500 py-4">
                        <i class="fas fa-shopping-basket mb-2"></i>
                        <p>No products in this order</p>
                    </div>
                `;
                document.getElementById('modal-total-items').textContent = '0';
                document.getElementById('modal-selected-items').textContent = '0';
                return;
            }
            
            let totalItems = 0;
            let selectedCount = 0;
            
            // Create product items - only show selected items
            const productsHTML = items.map((item, index) => {
                const isSelected = selectedItems.includes(index.toString());
                if (isSelected) {
                    selectedCount++;
                    totalItems += item.quantity;
                    
                    // Check if item has notes
                    const hasNotes = item.displayNotes && item.displayNotes !== 'None';
                    
                    return `
                        <div class="product-item">
                            <div class="flex flex-col">
                                <div class="flex items-center">
                                    <span class="product-name">${item.name}</span>
                                    <span class="product-quantity">×${item.quantity}</span>
                                    <span class="product-status status-selected">
                                        Selected
                                    </span>
                                </div>
                            </div>
                            <span class="product-price">${formatCurrency(item.totalPrice)}</span>
                        </div>
                    `;
                }
                return ''; // Return empty string for non-selected items
            }).filter(html => html !== '').join(''); // Filter out empty strings
            
            // If no items are selected, show a message
            if (selectedCount === 0) {
                productsListContainer.innerHTML = `
                    <div class="text-center text-gray-500 py-4">
                        <i class="fas fa-info-circle mb-2"></i>
                        <p>No products selected for payment</p>
                        <p class="text-xs mt-1">Please select items in the order card first</p>
                    </div>
                `;
            } else {
                productsListContainer.innerHTML = productsHTML;
            }
            
            document.getElementById('modal-total-items').textContent = totalItems;
            document.getElementById('modal-selected-items').textContent = selectedCount;
        }
        
        // Function to open confirm payment modal
        function openConfirmPaymentModal(orderId, paymentNumber, orderType, paymentMethod, subtotal, tax, total, items, selectedItems) {
            // Set modal values
            document.getElementById('modal-order-id').textContent = orderId;
            document.getElementById('modal-payment-number').textContent = paymentNumber;
            document.getElementById('modal-order-type').textContent = orderType;
            document.getElementById('modal-payment-method').textContent = paymentMethod;
            
            // Display total WITHOUT tax
            document.getElementById('modal-total').textContent = formatCurrency(subtotal); // Use subtotal instead of total (which includes tax)
            
            // Calculate and display vatable sales and tax
            const vatableSales = subtotal * 0.893; // 89.3% of total
            const tax10_7 = subtotal * 0.107; // 10.7% of total
            
            document.getElementById('modal-vatable-sales').textContent = formatCurrency(vatableSales);
            document.getElementById('modal-tax').textContent = formatCurrency(tax10_7);
            
            // Render products in modal (only selected ones)
            renderProductsInModal(orderId, paymentNumber, items, selectedItems);
            
            // Clear reference number input
            referenceInput.value = '';
            
            // Reset amount paid
            amountPaidInput.value = '';
            document.getElementById('change-calculation').classList.add('hidden');
            confirmPaymentBtn.disabled = true;
            
            // Store order data for later use
            confirmPaymentBtn.dataset.orderId = orderId;
            confirmPaymentBtn.dataset.paymentNumber = paymentNumber;
            confirmPaymentBtn.dataset.totalAmount = subtotal; // Store subtotal (without tax) for payment calculation
            
            // Open modal
            confirmPaymentModal.classList.remove('hidden');
            confirmPaymentModal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            
            // Focus on amount paid input
            setTimeout(() => {
                amountPaidInput.focus();
            }, 100);
        }
        
        // Function to calculate change
        function calculateChange(amountPaid, totalAmount) {
            return amountPaid - totalAmount;
        }
        
        // Function to close payment modal
        function closePaymentModal() {
            confirmPaymentModal.classList.remove('flex');
            confirmPaymentModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        // Function to open admin password modal
        function openAdminPasswordModal(orderId, paymentNumber, selectedProducts) {
            // Set modal values
            adminModalOrderId.textContent = orderId;
            adminModalPaymentNumber.textContent = paymentNumber;
            adminModalProductCount.textContent = selectedProducts.length;
            
            // Reset form
            adminPasswordInput.value = '';
            adminPasswordError.classList.add('hidden');
            adminErrorElement.textContent = '';
            
            // Store current order data
            currentVoidOrderData = {
                orderId: orderId,
                paymentNumber: paymentNumber,
                selectedProducts: selectedProducts,
                actionType: 'void'
            };
            
            // Open modal
            adminPasswordModal.classList.remove('hidden');
            adminPasswordModal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            
            // Focus on password input
            setTimeout(() => {
                adminPasswordInput.focus();
            }, 100);
        }
        
        // Function to open admin password modal for cancel order
        function openAdminPasswordModalForCancel(orderId, paymentNumber) {
            // Set modal values
            adminModalOrderId.textContent = orderId;
            adminModalPaymentNumber.textContent = paymentNumber;
            adminModalProductCount.textContent = 'All Products'; // Since we're cancelling the entire order
            
            // Reset form
            adminPasswordInput.value = '';
            adminPasswordError.classList.add('hidden');
            adminErrorElement.textContent = '';
            
            // Update modal title and text for cancel order
            const modalTitle = document.querySelector('#admin-password-modal h2');
            const warningText = document.querySelector('#admin-password-modal .text-red-300');
            
            if (modalTitle) modalTitle.textContent = 'Confirm Order Cancellation';
            if (warningText) warningText.textContent = 'Confirm Order Cancellation';
            
            // Update confirm button text
            const confirmButton = document.querySelector('#confirm-admin-auth');
            if (confirmButton) {
                confirmButton.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Confirm Cancel';
                confirmButton.classList.remove('bg-red-700', 'hover:bg-red-600');
                confirmButton.classList.add('bg-amber-700', 'hover:bg-amber-600');
            }
            
            // Store current order data with action type
            currentVoidOrderData = {
                orderId: orderId,
                paymentNumber: paymentNumber,
                actionType: 'cancel' // Differentiate from void action
            };
            
            // Open modal
            adminPasswordModal.classList.remove('hidden');
            adminPasswordModal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            
            // Focus on password input
            setTimeout(() => {
                adminPasswordInput.focus();
            }, 100);
        }
        
        // Function to close admin password modal
        function closeAdminPasswordModal() {
            adminPasswordModal.classList.remove('flex');
            adminPasswordModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            
            // Reset modal content
            const modalTitle = document.querySelector('#admin-password-modal h2');
            const warningText = document.querySelector('#admin-password-modal .text-red-300');
            const confirmButton = document.querySelector('#confirm-admin-auth');
            
            if (modalTitle) modalTitle.textContent = 'Admin Authorization Required';
            if (warningText) warningText.textContent = 'Confirm Product Voiding';
            if (confirmButton) {
                confirmButton.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Confirm Void';
                confirmButton.classList.remove('bg-amber-700', 'hover:bg-amber-600');
                confirmButton.classList.add('bg-red-700', 'hover:bg-red-600');
            }
            
            currentVoidOrderData = null;
        }
        
        // Function to download 5-inch thermal receipt
        function downloadReceipt(orderId, paymentNumber, referenceNumber) {
            // Create a temporary form to submit
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = '/api/staff/receipt/generate';
            
            // Add order ID
            const orderIdInput = document.createElement('input');
            orderIdInput.type = 'hidden';
            orderIdInput.name = 'orderID';
            orderIdInput.value = orderId;
            form.appendChild(orderIdInput);
            
            // Add payment number
            const paymentNumberInput = document.createElement('input');
            paymentNumberInput.type = 'hidden';
            paymentNumberInput.name = 'paymentNumber';
            paymentNumberInput.value = paymentNumber;
            form.appendChild(paymentNumberInput);
            
            // Add reference number if exists
            if (referenceNumber) {
                const refInput = document.createElement('input');
                refInput.type = 'hidden';
                refInput.name = 'referenceNumber';
                refInput.value = referenceNumber;
                form.appendChild(refInput);
            }
            
            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
            form.appendChild(csrfInput);
            
            // Submit form to download PDF
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
            
            // Show message
            setTimeout(() => {
                showStatusMessage('5-inch thermal receipt downloaded!', 'bg-green-600');
            }, 1000);
        }
        
        // Helper function to show error in modal
        function showError(message) {
            adminErrorElement.textContent = message;
            adminPasswordError.classList.remove('hidden');
            
            // Auto-hide error after 5 seconds
            setTimeout(() => {
                adminPasswordError.classList.add('hidden');
            }, 5000);
        }
        
        // Pagination Functions
        function updatePagination() {
            totalPages = Math.ceil(allOrders.length / ordersPerPage);
            currentPageElement.textContent = currentPage;
            totalPagesElement.textContent = totalPages || 1;
            
            // Enable/disable buttons
            prevPageBtn.disabled = currentPage === 1;
            nextPageBtn.disabled = currentPage === totalPages || totalPages === 0;
            
            // Update button styles based on state
            if (prevPageBtn.disabled) {
                prevPageBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                prevPageBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
            
            if (nextPageBtn.disabled) {
                nextPageBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                nextPageBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }
        
        function renderOrders() {
            // Clear current orders
            ordersContainer.innerHTML = '';
            
            if (allOrders.length === 0) {
                ordersContainer.innerHTML = `
                    <div class="text-center text-gray-400 col-span-full py-12">
                        <i class="fas fa-coffee text-5xl mb-4"></i>
                        <p class="text-xl">No orders found</p>
                        <p class="text-sm mt-2">Orders will appear here as they are created</p>
                    </div>
                `;
                return;
            }
            
            // Calculate which orders to show
            const startIndex = (currentPage - 1) * ordersPerPage;
            const endIndex = startIndex + ordersPerPage;
            const currentOrders = allOrders.slice(startIndex, endIndex);
            
            // Render each order
            currentOrders.forEach((order, orderIndex) => {
                // Calculate totals for this order WITHOUT TAX
                const totals = calculateOrderTotals(order);
                
                // Determine timer badge color based on time
                let timerColor = 'bg-blue-500';
                const timeNum = parseInt(order.time);
                if (timeNum >= 20) timerColor = 'bg-red-500';
                else if (timeNum >= 10) timerColor = 'bg-yellow-500';
                else if (timeNum >= 5) timerColor = 'bg-green-500';
                
                // Determine payment badge
                const paymentClass = order.payment === 'Cash' ? 
                    'payment-badge-cash' : 'payment-badge-electronic';
                const paymentText = order.payment === 'Cash' ? 'Cash' : 'Electronic';
                
                // Check if order is in void state
                const isVoidState = isOrderInVoidState(order.id, order.paymentNumber);
                const voidStateClass = isVoidState ? 'void-state' : '';
                
                const orderCard = document.createElement('div');
                orderCard.className = `order-card bg-gray-800 rounded-xl border border-gray-700 overflow-hidden ${voidStateClass}`;
                
                // Check if order has items
                const hasItems = order.items.length > 0;
                
                // Check if any items have notes
                const hasItemNotes = order.items.some(item => item.displayNotes);
                
                orderCard.innerHTML = `
                    <div class="p-5">
                        <!-- Order ID and Payment Number in same row -->
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h2 class="text-xl font-bold text-white">${order.id}</h2>
                                <div class="flex items-center mt-1">
                                    <div class="payment-number-badge px-2 py-1 rounded text-xs font-semibold">
                                        Payment #${order.paymentNumber}
                                    </div>
                                </div>
                            </div>
                            <div class="${timerColor} text-white px-3 py-1 rounded-full text-sm font-semibold status-timer">
                                ${order.time}
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <!-- Order Type and Payment Method in same row -->
                            <div class="flex flex-wrap gap-2 mb-3">
                                <div class="${order.typeColor} px-3 py-1 rounded-md text-sm">
                                    ${order.type}
                                </div>
                                <div class="${paymentClass} px-3 py-1 rounded-md text-sm font-medium">
                                    ${paymentText}
                                </div>
                            </div>
                            
                            <!-- Order Notes Summary (only show if there are notes) -->
                            ${order.notes !== 'None' ? `
                            <div class="order-notes-summary mb-3">
                                <div class="order-notes-label">
                                    <i class="fas fa-sticky-note"></i>
                                    <span>Order Notes Summary:</span>
                                </div>
                                <div class="order-notes-content">
                                    ${order.notes}
                                </div>
                            </div>
                            ` : ''}
                            
                            <!-- Void State Warning -->
                            ${isVoidState ? `
                            <div class="void-warning mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle void-check-icon"></i>
                                    <span class="void-warning-text">Select products to void, then click Confirm Void</span>
                                </div>
                            </div>
                            ` : ''}
                            
                            ${hasItems ? `
                            <!-- Select All Checkbox -->
                            <div class="select-all-section ${isVoidState ? 'bg-red-900/30 border-red-700' : ''}">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           class="select-all-checkbox ${isVoidState ? 'select-all-checkbox-void' : ''}" 
                                           data-order-id="${order.id}" 
                                           data-payment-number="${order.paymentNumber}"
                                           ${isVoidState ? 'data-void-state="true"' : ''}>
                                    <span class="${isVoidState ? 'text-red-300' : 'text-gray-300'} text-sm font-medium">
                                        ${isVoidState ? 'Select All Products to Void' : 'Select All Items'}
                                    </span>
                                </label>
                            </div>
                            
                            <!-- Order Items with Checkboxes, Quantities and Prices -->
                            <ul class="space-y-2" id="items-list-${order.id}-${order.paymentNumber}">
                                ${order.items.map((item, itemIndex) => {
                                    const itemKey = getItemKey(order.id, order.paymentNumber, itemIndex);
                                    const isChecked = checkedItemsState.get(itemKey) || false;
                                    const itemClass = isChecked ? 'item-checked' : '';
                                    const isVoidable = isVoidState;
                                    const checkboxClass = isVoidState ? 'item-checkbox-void' : '';
                                    const hasNotes = item.displayNotes;
                                    
                                    return `
                                    <li class="price-item ${itemClass}" id="item-${order.id}-${order.paymentNumber}-${itemIndex}">
                                        <div class="checkbox-container">
                                            <input type="checkbox" 
                                                   class="item-checkbox ${checkboxClass}" 
                                                   id="${itemKey}"
                                                   data-order-id="${order.id}"
                                                   data-payment-number="${order.paymentNumber}"
                                                   data-item-index="${itemIndex}"
                                                   data-product-name="${item.name}"
                                                   data-product-notes="${item.displayNotes || ''}"
                                                   data-unit-price="${item.price}"
                                                   data-quantity="${item.quantity}"
                                                   data-total-price="${item.totalPrice}"
                                                   ${isChecked ? 'checked' : ''}>
                                            <div class="flex flex-col w-full">
                                                <div class="flex items-start">
                                                    <div class="item-details">
                                                        <span class="order-item-text">
                                                            ${item.name}
                                                        </span>
                                                        <span class="quantity-badge">×${item.quantity}</span>
                                                    </div>
                                                </div>
                                                ${hasNotes ? `
                                                <div class="item-notes-section">
                                                    <span class="notes-label">Note:</span>
                                                    <span class="notes-content">${item.displayNotes}</span>
                                                </div>
                                                ` : ''}
                                            </div>
                                        </div>
                                        <span class="price-tag">
                                            ${formatCurrency(item.totalPrice)}
                                        </span>
                                    </li>
                                `}).join('')}
                            </ul>
                            ` : `
                            <!-- Empty Order Message -->
                            <div class="empty-order-message">
                                <i class="fas fa-ban text-2xl text-gray-500 mb-2"></i>
                                <p class="empty-order-text">All products have been voided</p>
                            </div>
                            `}
                            
                            <!-- Total to Pay Section (WITHOUT TAX) -->
                            ${hasItems ? `
                            <div class="price-section">
                                <!-- Vatable Sales (89.3% of Total) -->
                                <div class="price-item">
                                    <span class="text-gray-300">Vatable Sales: </span>
                                    <span class="text-amber-300 font-medium">${formatCurrency(totals.vatableSales)}</span>
                                </div>
                                
                                <!-- Tax (10.7% of Total) -->
                                <div class="price-item">
                                    <span class="text-gray-300">Tax: </span>
                                    <span class="text-amber-300 font-medium">${formatCurrency(totals.tax10_7)}</span>
                                </div>
                                
                                <!-- Total to Pay (WITHOUT TAX) -->
                                <div class="price-item total-row">
                                    <span class="text-white">Total to Pay:</span>
                                    <span class="text-green-400 font-bold">${formatCurrency(totals.total)}</span>
                                </div>
                            </div>
                            ` : ''}
                            
                            <!-- Item count summary -->
                            <div class="mt-3 pt-3 border-t border-gray-700">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-400 text-sm">Total items in order:</span>
                                    <span class="text-amber-400 font-medium">${order.items.reduce((sum, item) => sum + item.quantity, 0)}</span>
                                </div>
                                <!-- Checked items summary -->
                                <div class="flex justify-between items-center mt-1">
                                    <span class="text-gray-400 text-sm">
                                        ${isVoidState ? 'Products selected for voiding:' : 'Selected items:'}
                                    </span>
                                    <span class="${isVoidState ? 'text-red-400' : 'text-blue-400'} font-medium" id="checked-count-${order.id}-${order.paymentNumber}">0</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            ${hasItems ? `
                            <button class="send-to-kitchen-btn w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 rounded-lg transition" data-order-id="${order.id}" data-payment-number="${order.paymentNumber}">
                                Confirm Payment
                            </button>
                            ` : `
                            <button class="send-to-kitchen-btn w-full bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 rounded-lg transition cursor-not-allowed" disabled data-order-id="${order.id}" data-payment-number="${order.paymentNumber}">
                                Order Empty
                            </button>
                            `}
                            <button class="cancel-order-btn w-full bg-amber-700 hover:bg-amber-800 text-white font-medium py-3 rounded-lg transition" data-order-id="${order.id}" data-payment-number="${order.paymentNumber}">
                                Cancel Order
                            </button>
                            ${hasItems ? `
                            ${!isVoidState ? `
                            <button class="void-product-btn w-full bg-red-700 hover:bg-red-800 text-white font-medium py-3 rounded-lg transition" data-order-id="${order.id}" data-payment-number="${order.paymentNumber}">
                                Void Product
                            </button>
                            ` : `
                            <button class="confirm-void-btn w-full bg-red-700 hover:bg-red-800 text-white font-medium py-3 rounded-lg transition" data-order-id="${order.id}" data-payment-number="${order.paymentNumber}">
                                Confirm Void
                            </button>
                            <button class="cancel-void-btn w-full bg-gray-700 hover:bg-gray-800 text-white font-medium py-3 rounded-lg transition" data-order-id="${order.id}" data-payment-number="${order.paymentNumber}">
                                Cancel Void
                            </button>
                            `}
                            ` : `
                            <button class="void-product-btn w-full bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 rounded-lg transition cursor-not-allowed" disabled>
                                No Products to Void
                            </button>
                            `}
                        </div>
                    </div>
                `;
                
                ordersContainer.appendChild(orderCard);
            });
            
            // Re-attach event listeners to new buttons
            attachOrderButtonListeners();
            attachCheckboxListeners();
            updateCheckedCounts();
        }
        
        function updateStatistics() {
            // Update counts
            const dineInCount = allOrders.filter(order => order.type === 'Dine in').length;
            const takeoutCount = allOrders.filter(order => order.type === 'Takeout').length;
            const cashCount = allOrders.filter(order => order.payment === 'Cash').length;
            const electronicCount = allOrders.filter(order => order.payment === 'Electronic').length;
            
            orderCountElement.textContent = allOrders.length;
        }
        
        function updateRevenueStats() {
            const revenueStats = calculateRevenueStatistics();
            
            // These would update elements if they existed
            // For now, we'll just calculate them
        }
        
        function attachCheckboxListeners() {
            // Individual item checkbox listeners
            document.querySelectorAll('.item-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const orderId = this.getAttribute('data-order-id');
                    const paymentNumber = this.getAttribute('data-payment-number');
                    const itemIndex = this.getAttribute('data-item-index');
                    const isChecked = this.checked;
                    const isVoidState = this.classList.contains('item-checkbox-void');
                    
                    // Update state
                    const itemKey = getItemKey(orderId, paymentNumber, itemIndex);
                    checkedItemsState.set(itemKey, isChecked);
                    
                    // Update UI
                    const listItem = this.closest('li');
                    if (isChecked) {
                        listItem.classList.add('item-checked');
                    } else {
                        listItem.classList.remove('item-checked');
                    }
                    
                    // Update "Select All" checkbox state
                    updateSelectAllCheckbox(orderId, paymentNumber);
                    
                    // Update checked items count
                    updateCheckedCount(orderId, paymentNumber);
                    updateCheckedItemsCount();
                    
                    const message = isVoidState ? 
                        `Product ${isChecked ? 'selected for voiding' : 'unselected'}` :
                        `Item ${isChecked ? 'selected' : 'unselected'}`;
                    
                    showStatusMessage(message, isVoidState ? 'bg-red-600' : 'bg-blue-600');
                });
            });
            
            // "Select All" checkbox listeners
            document.querySelectorAll('.select-all-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const orderId = this.getAttribute('data-order-id');
                    const paymentNumber = this.getAttribute('data-payment-number');
                    const selectAllChecked = this.checked;
                    const isVoidState = this.classList.contains('select-all-checkbox-void');
                    
                    // Find all checkboxes for this order using safe selector
                    const itemCheckboxes = getCheckboxesForOrder(orderId, paymentNumber);
                    
                    // Update all checkboxes
                    itemCheckboxes.forEach(itemCheckbox => {
                        const itemIndex = itemCheckbox.getAttribute('data-item-index');
                        const itemKey = getItemKey(orderId, paymentNumber, itemIndex);
                        
                        // Update state
                        checkedItemsState.set(itemKey, selectAllChecked);
                        
                        // Update UI
                        itemCheckbox.checked = selectAllChecked;
                        const listItem = itemCheckbox.closest('li');
                        if (selectAllChecked) {
                            listItem.classList.add('item-checked');
                        } else {
                            listItem.classList.remove('item-checked');
                        }
                    });
                    
                    // Update checked items count
                    updateCheckedCount(orderId, paymentNumber);
                    updateCheckedItemsCount();
                    
                    const message = isVoidState ?
                        (selectAllChecked ? 'All products selected for voiding' : 'All products unselected') :
                        (selectAllChecked ? 'All items selected' : 'All items unselected');
                    
                    showStatusMessage(message, isVoidState ? 'bg-red-600' : 'bg-blue-600');
                });
            });
        }
        
        function updateSelectAllCheckbox(orderId, paymentNumber) {
            const selectAllCheckbox = getSelectAllCheckboxForOrder(orderId, paymentNumber);
            
            if (!selectAllCheckbox) return;
            
            const isVoidState = selectAllCheckbox.classList.contains('select-all-checkbox-void');
            
            // Find all checkboxes for this order using safe selector
            const itemCheckboxes = getCheckboxesForOrder(orderId, paymentNumber);
            
            if (itemCheckboxes.length === 0) return;
            
            const allChecked = Array.from(itemCheckboxes).every(checkbox => checkbox.checked);
            const anyChecked = Array.from(itemCheckboxes).some(checkbox => checkbox.checked);
            
            // Update select all checkbox state
            selectAllCheckbox.checked = allChecked;
            
            // Set indeterminate state if some but not all are checked
            selectAllCheckbox.indeterminate = anyChecked && !allChecked;
        }
        
        function updateCheckedCount(orderId, paymentNumber) {
            const itemCheckboxes = getCheckboxesForOrder(orderId, paymentNumber);
            
            const checkedCount = Array.from(itemCheckboxes).filter(checkbox => checkbox.checked).length;
            
            const countElement = document.getElementById(`checked-count-${orderId}-${paymentNumber}`);
            if (countElement) {
                countElement.textContent = checkedCount;
            }
        }
        
        function updateCheckedCounts() {
            const allOrderCards = document.querySelectorAll('.order-card');
            
            allOrderCards.forEach(card => {
                const orderIdElement = card.querySelector('h2.text-xl');
                if (!orderIdElement) return;
                
                const orderId = orderIdElement.textContent;
                const paymentNumberBadge = card.querySelector('.payment-number-badge');
                if (!paymentNumberBadge) return;
                
                // Extract payment number and trim spaces
                const paymentNumber = paymentNumberBadge.textContent.replace('Payment #', '').trim();
                updateCheckedCount(orderId, paymentNumber);
            });
        }
        
        function attachOrderButtonListeners() {
            // Confirm Payment buttons
            document.querySelectorAll('.send-to-kitchen-btn').forEach(button => {
                button.addEventListener('click', async function(e) {
                    if (this.disabled) return;
                    
                    const orderId = this.getAttribute('data-order-id');
                    const paymentNumber = this.getAttribute('data-payment-number');
                    
                    // Check if order is in void state
                    if (isOrderInVoidState(orderId, paymentNumber)) {
                        showStatusMessage('Cannot send to kitchen while in void state. Cancel void first.', 'bg-red-600');
                        return;
                    }
                    
                    // Find the order in allOrders array
                    const order = allOrders.find(order => 
                        order.id === orderId && order.paymentNumber === paymentNumber
                    );
                    
                    if (!order) {
                        showStatusMessage('Order not found', 'bg-red-600');
                        return;
                    }
                    
                    // Get selected items for this order
                    const itemCheckboxes = getCheckboxesForOrder(orderId, paymentNumber);
                    const selectedItems = itemCheckboxes ? 
                        Array.from(itemCheckboxes)
                            .filter(checkbox => checkbox.checked)
                            .map(checkbox => checkbox.getAttribute('data-item-index')) : [];
                    
                    // Check if any items are selected
                    if (selectedItems.length === 0) {
                        showStatusMessage('Please select at least one item to pay', 'bg-yellow-600');
                        return;
                    }
                    
                    // Calculate totals WITHOUT TAX
                    const totals = calculateOrderTotals(order);
                    
                    // Open confirm payment modal with products
                    openConfirmPaymentModal(
                        order.id,
                        order.paymentNumber,
                        order.type,
                        order.payment,
                        totals.subtotal, // Pass subtotal (without tax)
                        totals.tax,
                        totals.subtotal, // Use subtotal as total (without tax)
                        order.items,
                        selectedItems
                    );
                });
            });
            
            // Cancel Order buttons
            document.querySelectorAll('.cancel-order-btn').forEach(button => {
                button.addEventListener('click', async function(e) {
                    const orderId = this.getAttribute('data-order-id');
                    const paymentNumber = this.getAttribute('data-payment-number');
                    
                    // Check if order is in void state
                    if (isOrderInVoidState(orderId, paymentNumber)) {
                        showStatusMessage('Cannot cancel order while in void state. Cancel void first.', 'bg-red-600');
                        return;
                    }
                    
                    // Open admin password modal for cancel order
                    openAdminPasswordModalForCancel(orderId, paymentNumber);
                });
            });
            
            // Void Product buttons
            document.querySelectorAll('.void-product-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    if (this.disabled) return;
                    
                    const orderId = this.getAttribute('data-order-id');
                    const paymentNumber = this.getAttribute('data-payment-number');
                    
                    // Set order to void state
                    setOrderVoidState(orderId, paymentNumber, true);
                    
                    // Reset checkboxes for this order
                    const itemCheckboxes = getCheckboxesForOrder(orderId, paymentNumber);
                    
                    itemCheckboxes.forEach(itemCheckbox => {
                        const itemIndex = itemCheckbox.getAttribute('data-item-index');
                        const itemKey = getItemKey(orderId, paymentNumber, itemIndex);
                        checkedItemsState.set(itemKey, false);
                    });
                    
                    // Re-render orders to show void state
                    renderOrders();
                    
                    showStatusMessage(`Order ${orderId} (Payment #${paymentNumber}) in void state. Select products to void.`, 'bg-red-600');
                });
            });
            
            // Confirm Void buttons
            document.querySelectorAll('.confirm-void-btn').forEach(button => {
                button.addEventListener('click', async function(e) {
                    const orderId = this.getAttribute('data-order-id');
                    const paymentNumber = this.getAttribute('data-payment-number');
                    
                    // Get selected items for voiding using safe selector
                    const itemCheckboxes = getCheckboxesForOrder(orderId, paymentNumber);
                    
                    // Get product names from selected checkboxes
                    const selectedProducts = Array.from(itemCheckboxes)
                        .filter(checkbox => checkbox.checked)
                        .map(checkbox => checkbox.getAttribute('data-product-name'));
                    
                    if (selectedProducts.length === 0) {
                        showStatusMessage('No products selected for voiding', 'bg-yellow-600');
                        return;
                    }
                    
                    // Open admin password modal instead of prompt
                    openAdminPasswordModal(orderId, paymentNumber, selectedProducts);
                });
            });
            
            // Cancel Void buttons
            document.querySelectorAll('.cancel-void-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    const orderId = this.getAttribute('data-order-id');
                    const paymentNumber = this.getAttribute('data-payment-number');
                    
                    // Reset void state
                    setOrderVoidState(orderId, paymentNumber, false);
                    
                    // Clear checkboxes for this order
                    const itemCheckboxes = getCheckboxesForOrder(orderId, paymentNumber);
                    
                    itemCheckboxes.forEach(itemCheckbox => {
                        const itemIndex = itemCheckbox.getAttribute('data-item-index');
                        const itemKey = getItemKey(orderId, paymentNumber, itemIndex);
                        checkedItemsState.set(itemKey, false);
                    });
                    
                    // Re-render orders to exit void state
                    renderOrders();
                    
                    showStatusMessage('Void cancelled', 'bg-gray-600');
                });
            });
        }
        
        // Event listener for amount paid input
        amountPaidInput.addEventListener('input', function() {
            const totalAmount = parseFloat(confirmPaymentBtn.dataset.totalAmount || 0);
            const amountPaid = parseFloat(this.value) || 0;
            
            if (amountPaid >= totalAmount) {
                const change = calculateChange(amountPaid, totalAmount);
                document.getElementById('modal-change').textContent = formatCurrency(change);
                document.getElementById('change-calculation').classList.remove('hidden');
                confirmPaymentBtn.disabled = false;
            } else {
                document.getElementById('change-calculation').classList.add('hidden');
                confirmPaymentBtn.disabled = true;
            }
        });
        
        // Password visibility toggle
        togglePasswordVisibility.addEventListener('click', function() {
            const type = adminPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            adminPasswordInput.setAttribute('type', type);
            
            // Toggle icon
            const icon = this.querySelector('i');
            if (type === 'text') {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
        // Confirm admin authentication
        confirmAdminAuth.addEventListener('click', async function() {
            const password = adminPasswordInput.value.trim();
            
            if (!password) {
                showError('Please enter the Admin password');
                return;
            }
            
            if (!currentVoidOrderData) {
                showError('No order data found. Please try again.');
                return;
            }
            
            const { orderId, paymentNumber, selectedProducts, actionType } = currentVoidOrderData;
            
            // Disable button and show loading state
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
            
            try {
                if (actionType === 'cancel') {
                    // Handle cancel order
                    await apiCall('/api/staff/orders/cancel', 'POST', {
                        orderID: orderId,
                        paymentNumber: paymentNumber,
                        adminPassword: password
                    });
                    
                    // Success - close modal
                    closeAdminPasswordModal();
                    
                    // Show success message
                    showStatusMessage(`Order ${orderId} (Payment #${paymentNumber}) cancelled successfully!`, 'bg-green-600');
                    
                    // Reload orders
                    setTimeout(() => {
                        loadOrders();
                    }, 1000);
                    
                } else {
                    // Handle void products (original logic)
                    await apiCall('/api/staff/orders/void-products', 'POST', {
                        orderID: orderId,
                        paymentNumber: paymentNumber,
                        items: selectedProducts,
                        status: 'Product Voided',
                        adminPassword: password
                    });
                    
                    // Success - close modal and proceed with voiding
                    closeAdminPasswordModal();
                    
                    // Animate removal of selected items
                    const itemCheckboxes = getCheckboxesForOrder(orderId, paymentNumber);
                    Array.from(itemCheckboxes)
                        .filter(checkbox => checkbox.checked)
                        .forEach(checkbox => {
                            const itemIndex = checkbox.getAttribute('data-item-index');
                            const itemElement = document.getElementById(`item-${orderId}-${paymentNumber}-${itemIndex}`);
                            if (itemElement) {
                                itemElement.classList.add('item-removing');
                            }
                        });
                    
                    // Wait for animation to complete
                    setTimeout(async () => {
                        // Get the indices of selected items for local removal
                        const selectedIndices = Array.from(itemCheckboxes)
                            .filter(checkbox => checkbox.checked)
                            .map(checkbox => parseInt(checkbox.getAttribute('data-item-index')));
                        
                        // Remove items from local data
                        const orderRemoved = removeItemsFromOrder(orderId, paymentNumber, selectedIndices.map(i => i.toString()));
                        
                        // Reset void state
                        setOrderVoidState(orderId, paymentNumber, false);
                        
                        // Clear checkboxes for this order
                        const allItemCheckboxes = getCheckboxesForOrder(orderId, paymentNumber);
                        
                        allItemCheckboxes.forEach(itemCheckbox => {
                            const itemIndex = itemCheckbox.getAttribute('data-item-index');
                            const itemKey = getItemKey(orderId, paymentNumber, itemIndex);
                            checkedItemsState.set(itemKey, false);
                        });
                        
                        // Update pagination and re-render
                        totalPages = Math.ceil(allOrders.length / ordersPerPage);
                        updatePagination();
                        renderOrders();
                        
                        showStatusMessage(`${selectedProducts.length} product(s) voided successfully`, 'bg-green-600');
                        
                    }, 300);
                }
                
            } catch (error) {
                console.warn('API call failed:', error);
                const errorMessage = error.message.includes('Invalid admin password') 
                    ? 'Invalid Admin password. Please try again.'
                    : error.message.includes('No admin user found')
                    ? 'No Admin user found in system.'
                    : 'Server error. Please try again.';
                
                showError(errorMessage);
                
                // Reset button state
                this.disabled = false;
                this.innerHTML = actionType === 'cancel' 
                    ? '<i class="fas fa-check-circle mr-2"></i> Confirm Cancel'
                    : '<i class="fas fa-check-circle mr-2"></i> Confirm Void';
                
                // Clear password field
                adminPasswordInput.value = '';
                adminPasswordInput.focus();
            }
        });
        
        // Event listener for confirm payment button in modal
        confirmPaymentBtn.addEventListener('click', async function() {
            const orderId = this.dataset.orderId;
            const paymentNumber = this.dataset.paymentNumber;
            const totalAmount = parseFloat(this.dataset.totalAmount);
            const amountPaid = parseFloat(amountPaidInput.value);
            const referenceNumber = referenceInput.value.trim();
            
            // Calculate vatable sales and tax for this payment
            const vatableSales = totalAmount * 0.893;
            const tax10_7 = totalAmount * 0.107;
            
            // Validate amount paid
            if (!amountPaid || amountPaid < totalAmount) {
                showStatusMessage('Amount paid must be equal to or greater than total amount', 'bg-red-600');
                amountPaidInput.focus();
                return;
            }
            
            // Calculate change
            const change = calculateChange(amountPaid, totalAmount);
            
            // Show processing message
            showStatusMessage('Processing payment...', 'bg-blue-600');
            
            try {
                // Get selected items for this order
                const selectedItems = [];
                const selectedProducts = [];
                
                // Debug: Log to see what's happening
                console.log('Processing payment for order:', orderId, paymentNumber);
                
                // Get checkboxes using the safe selector function
                const itemCheckboxes = getCheckboxesForOrder(orderId, paymentNumber);
                console.log('Found checkboxes:', itemCheckboxes ? itemCheckboxes.length : 0);
                
                if (itemCheckboxes && itemCheckboxes.length > 0) {
                    Array.from(itemCheckboxes)
                        .filter(checkbox => checkbox.checked)
                        .forEach(checkbox => {
                            const itemIndex = checkbox.getAttribute('data-item-index');
                            selectedItems.push(itemIndex);
                            
                            // Debug each checkbox
                            console.log('Selected checkbox:', {
                                itemIndex: itemIndex,
                                name: checkbox.getAttribute('data-product-name'),
                                unitPrice: checkbox.getAttribute('data-unit-price'),
                                quantity: checkbox.getAttribute('data-quantity'),
                                totalPrice: checkbox.getAttribute('data-total-price')
                            });
                            
                            // Get product details from data attributes
                            selectedProducts.push({
                                name: checkbox.getAttribute('data-product-name'),
                                notes: checkbox.getAttribute('data-product-notes') || null,
                                unitPrice: parseFloat(checkbox.getAttribute('data-unit-price')) || 0,
                                quantity: parseInt(checkbox.getAttribute('data-quantity')) || 1,
                                totalPrice: parseFloat(checkbox.getAttribute('data-total-price')) || 0
                            });
                        });
                }
                
                console.log('Selected products:', selectedProducts);
                
                // Check if any products are selected
                if (selectedProducts.length === 0) {
                    throw new Error('No products selected for payment. Please select at least one item.');
                }
                
                // Find the order to get tax rate
                const order = allOrders.find(order => 
                    order.id === orderId && order.paymentNumber === paymentNumber
                );
                
                if (!order) {
                    throw new Error('Order not found in local data');
                }
                
                // Prepare products data WITHOUT TAX for API call
                const productsData = selectedProducts.map((selectedProduct) => {
                    // REMOVE TAX CALCULATION - No tax included
                    return {
                        name: selectedProduct.name,
                        quantity: selectedProduct.quantity,
                        unitPrice: selectedProduct.unitPrice,
                        totalPrice: selectedProduct.totalPrice,
                        taxAmount: 0, // Set tax amount to 0 since we're removing tax
                        notes: selectedProduct.notes
                    };
                });
                
                console.log('Products data to send (without tax):', productsData);
                
                // First: Update order status to "In Progress"
                console.log('Updating order status...');
                await apiCall('/api/staff/orders/update-all-status', 'POST', {
                    orderID: orderId,
                    paymentNumber: paymentNumber,
                    status: 'In Progress',
                    selectedItems: selectedItems,
                    referenceNumber: referenceNumber || null
                });
                
                // Second: Save payment transaction to staff_to_kitchen_transaction table WITHOUT TAX
                console.log('Saving payment transaction...');
                
                // Prepare the request data WITHOUT TAX
                const paymentData = {
                    orderID: orderId,
                    paymentNumber: paymentNumber,
                    orderType: order.type.toLowerCase().replace(' ', '-'), // Convert to 'dine-in' or 'takeout'
                    paymentMethod: order.payment.toLowerCase(), // Convert to 'cash' or 'electronic'
                    products: productsData,
                    amountPaid: amountPaid,
                    changeAmount: change,
                    vatableSales: vatableSales, // Add vatable sales
                    tax10_7: tax10_7, // Add tax
                    referenceNumber: referenceNumber || null,
                    staffName: '{{ auth()->user()->name ?? "Staff Member" }}'
                };
                
                console.log('Payment data to save (without tax):', paymentData);
                
                const saveResult = await apiCall('/api/staff/orders/save-payment-transaction', 'POST', paymentData);
                console.log('Save result:', saveResult);
                
                // Create success message with optional reference
                let successMessage = `Payment confirmed for Order ${orderId} (${selectedProducts.length} product${selectedProducts.length > 1 ? 's' : ''}). `;
                
                if (referenceNumber) {
                    successMessage += `Reference: ${referenceNumber}. `;
                }
                
                successMessage += `Change: ${formatCurrency(change)}. Transaction saved to kitchen system.`;
                
                // Show success message
                showStatusMessage(successMessage, 'bg-green-600');
                
                // Close modal
                closePaymentModal();
                
                // Clear checkboxes for this order
                if (itemCheckboxes) {
                    Array.from(itemCheckboxes).forEach(checkbox => {
                        const itemIndex = checkbox.getAttribute('data-item-index');
                        const itemKey = getItemKey(orderId, paymentNumber, itemIndex);
                        checkedItemsState.set(itemKey, false);
                    });
                }
                
                // Update the button in the order card to "In Progress"
                const sendButton = document.querySelector(`.send-to-kitchen-btn[data-order-id="${orderId}"][data-payment-number="${paymentNumber}"]`);
                if (sendButton) {
                    sendButton.textContent = 'In Progress';
                    sendButton.classList.remove('bg-green-600', 'hover:bg-green-700');
                    sendButton.classList.add('bg-blue-600', 'hover:bg-blue-700');
                    sendButton.disabled = true;
                }
                
                // Update timer badge
                // Update timer badge - find the card by order ID
                const orderCards = document.querySelectorAll('.order-card');
                let cardToUpdate = null;
                
                for (const card of orderCards) {
                    const orderIdElement = card.querySelector('h2.text-xl');
                    if (orderIdElement && orderIdElement.textContent === orderId) {
                        const paymentBadge = card.querySelector('.payment-number-badge');
                        if (paymentBadge) {
                            const paymentText = paymentBadge.textContent.replace('Payment #', '').trim();
                            if (paymentText === paymentNumber) {
                                cardToUpdate = card;
                                break;
                            }
                        }
                    }
                }
                
                if (cardToUpdate) {
                    const timerBadge = cardToUpdate.querySelector('.status-timer');
                    if (timerBadge) {
                        timerBadge.textContent = '0m';
                        timerBadge.className = 'bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold status-timer';
                    }
                }
                
                // DOWNLOAD 5-INCH THERMAL RECEIPT AUTOMATICALLY
                downloadReceipt(orderId, paymentNumber, referenceNumber);
                
                // Force reload orders to reflect changes
                setTimeout(() => {
                    loadOrders();
                }, 1000);
                
            } catch (error) {
                console.error('Error processing payment:', error);
                console.error('Error details:', error.message);
                showStatusMessage('Error processing payment: ' + error.message, 'bg-red-600');
            }
        });
        
        // Event listeners for confirm payment modal
        closeConfirmPayment.addEventListener('click', closePaymentModal);
        cancelPayment.addEventListener('click', closePaymentModal);
        
        confirmPaymentModal.addEventListener('click', (e) => {
            if (e.target === confirmPaymentModal) closePaymentModal();
        });
        
        // Event listeners for admin password modal
        closeAdminPassword.addEventListener('click', closeAdminPasswordModal);
        cancelAdminAuth.addEventListener('click', closeAdminPasswordModal);
        
        adminPasswordModal.addEventListener('click', (e) => {
            if (e.target === adminPasswordModal) closeAdminPasswordModal();
        });
        
        // Submit form on Enter key
        adminPasswordInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                confirmAdminAuth.click();
            }
        });
        
        function showStatusMessage(message, bgColor) {
            statusMessage.textContent = message;
            statusMessage.className = `fixed bottom-4 right-4 ${bgColor} text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transform translate-y-4 transition-all duration-300 z-50`;
            
            // Show message
            statusMessage.classList.remove('opacity-0', 'translate-y-4');
            statusMessage.classList.add('opacity-100', 'translate-y-0');
            
            // Hide message after 3 seconds (longer for important messages)
            setTimeout(() => {
                statusMessage.classList.remove('opacity-100', 'translate-y-0');
                statusMessage.classList.add('opacity-0', 'translate-y-4');
            }, 3000);
        }
        
        // Footer Modal Functions
        function openModal(modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
        
        function closeModal(modal) {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        // Event Listeners for Footer
        termsBtn.addEventListener('click', () => openModal(termsModal));
        privacyBtn.addEventListener('click', () => openModal(privacyModal));
        
        closeTerms.addEventListener('click', () => closeModal(termsModal));
        closePrivacy.addEventListener('click', () => closeModal(privacyModal));
        
        acceptTerms.addEventListener('click', () => {
            closeModal(termsModal);
            showStatusMessage('Terms and Conditions acknowledged', 'bg-blue-600');
        });
        
        acceptPrivacy.addEventListener('click', () => {
            closeModal(privacyModal);
            showStatusMessage('Privacy Policy acknowledged', 'bg-blue-600');
        });
        
        // Close modals when clicking outside
        termsModal.addEventListener('click', (e) => {
            if (e.target === termsModal) closeModal(termsModal);
        });
        
        privacyModal.addEventListener('click', (e) => {
            if (e.target === privacyModal) closeModal(privacyModal);
        });
        
        // Close modals with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (!termsModal.classList.contains('hidden')) closeModal(termsModal);
                if (!privacyModal.classList.contains('hidden')) closeModal(privacyModal);
                if (!confirmPaymentModal.classList.contains('hidden')) closePaymentModal();
                if (!adminPasswordModal.classList.contains('hidden')) closeAdminPasswordModal();
            }
        });
        
        // Event Listeners for Pagination
        prevPageBtn.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                updatePagination();
                renderOrders();
            }
        });
        
        nextPageBtn.addEventListener('click', function() {
            if (currentPage < totalPages) {
                currentPage++;
                updatePagination();
                renderOrders();
            }
        });
        
        // Order Tracker Button click
        orderTrackerBtn.addEventListener('click', navigateToOrderTracker);
        
        // Live Clock Functionality
        function updateClock() {
            const now = new Date();
            
            // Format time (HH:MM:SS AM/PM)
            let hours = now.getHours();
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const seconds = now.getSeconds().toString().padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            
            hours = hours % 12;
            hours = hours ? hours : 12;
            hours = hours.toString().padStart(2, '0');
            
            const timeString = `${hours}:${minutes}:${seconds} ${ampm}`;
            document.getElementById('live-clock').textContent = timeString;
            
            // Format date
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            const dateString = now.toLocaleDateString('en-US', options);
            document.getElementById('current-date').textContent = dateString;
        }
        
        // Update clock immediately and then every second
        updateClock();
        setInterval(updateClock, 1000);
        
        // Auto-refresh orders every 30 seconds
        setInterval(loadOrders, 30000);
        
        // Keyboard navigation for pagination
        document.addEventListener('keydown', function(event) {
            if (event.key === 'ArrowLeft' && currentPage > 1) {
                currentPage--;
                updatePagination();
                renderOrders();
            } else if (event.key === 'ArrowRight' && currentPage < totalPages) {
                currentPage++;
                updatePagination();
                renderOrders();
            }
        });
    </script>
</body>
</html>