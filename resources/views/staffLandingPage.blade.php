<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Staff - CAFFE ARABICA</title>
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
        /* Product Card Styles */
        .product-card {
            background-color: #1F2937;
            border: 1px solid #374151;
            border-radius: 8px;
            padding: 16px;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .product-card:hover {
            transform: translateY(-2px);
            border-color: #4B5563;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        .product-card.selected {
            border-color: #10B981;
            background-color: rgba(16, 185, 129, 0.1);
        }
        .modal-product-name {
            color: #FFFFFF;
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 4px;
        }
        .modal-product-category {
            color: #9CA3AF;
            font-size: 0.8rem;
            margin-bottom: 8px;
        }
        .modal-product-price {
            color: #FBBF24;
            font-weight: 600;
            font-size: 1rem;
        }
        .modal-product-status-badge {
            font-size: 0.75rem;
            padding: 2px 8px;
            border-radius: 4px;
            margin-top: 8px;
            display: inline-block;
        }
        .status-available {
            background-color: #059669;
            color: #D1FAE5;
        }
        .status-out-of-stock {
            background-color: #DC2626;
            color: #FEE2E2;
        }
        .status-discontinued {
            background-color: #6B7280;
            color: #F3F4F6;
        }
        /* Selected Product Item */
        .selected-product-item {
            background-color: rgba(16, 185, 129, 0.1);
            border: 1px solid #10B981;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 8px;
        }
        .selected-product-name {
            color: #FFFFFF;
            font-weight: 500;
            font-size: 0.9rem;
        }
        .selected-product-quantity {
            background-color: #1E40AF;
            color: white;
            font-size: 0.75rem;
            padding: 2px 6px;
            border-radius: 4px;
            margin-left: 8px;
            font-weight: 600;
        }
        .remove-product-btn {
            color: #EF4444;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 0.8rem;
            padding: 2px 6px;
            border-radius: 4px;
            transition: all 0.2s ease;
        }
        .remove-product-btn:hover {
            background-color: rgba(239, 68, 68, 0.1);
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
                    <p class="text-sm text-gray-400 cinzel-font" style="font-size: 24px;">Staff Display System</p>
                </div>
            </div>
            
            <!-- Right Section: Employee & Time Info -->
            <div class="flex items-center space-x-8">
                <!-- Employee Info -->
                <div class="text-center">
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
                <div class="bg-gray-800 text-white rounded-lg px-4 py-3 w-[180px] text-center clock">
                    <div id="live-clock" class="text-xl font-bold tracking-wider" style="font-size: 20px;">2:45:59 PM</div>
                    <div id="current-date" class="text-xs text-gray-400" style="font-size: 13px;">May 25, 2025</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area (Order Display) -->
    <div class="pt-32 pb-8 px-6 flex-grow">
        <div class="max-w-7xl mx-auto">
            <!-- Pagination Controls -->
            <div class="flex justify-between items-center mb-5">
                <!-- Left Pagination Button -->
                <button id="prev-page-btn" class="pagination-btn text-white px-4 py-2 rounded-lg flex items-center space-x-2" disabled>
                    <i class="fas fa-chevron-left"></i>
                    <span>Previous</span>
                </button>
                
                <!-- Page Indicator -->
                <div class="text-gray-300 text-m font-medium">
                    Page <span id="current-page">1</span> of <span id="total-pages">1</span>
                </div>
                
                <!-- Right Pagination Button -->
                <button id="next-page-btn" class="pagination-btn text-white px-4 py-2 rounded-lg flex items-center space-x-2" disabled>
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
            <div class="flex justify-between items-center">
                <!-- Left: Terms and Conditions -->
                <button id="terms-btn" class="footer-link text-sm">
                    Terms and Conditions
                </button>
                
                <!-- Center: Copyright -->
                <p class="text-gray-500 text-sm">
                    © 2025 CAFFE ARABICA Kitchen Display System. All Rights Reserved.
                </p>
                
                <!-- Right: Privacy Policy -->
                <button id="privacy-btn" class="footer-link text-sm">
                    Privacy Policy
                </button>
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

    <!-- Modal for Add Product -->
    <div id="add-product-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-gray-800 rounded-xl p-6 max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-white">Add Product to Order</h2>
                <button id="close-add-product" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Order Information -->
            <div class="bg-gray-900 rounded-lg p-4 mb-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-gray-400 text-sm">Order ID:</span>
                        <span class="text-white font-medium ml-2" id="add-modal-order-id">-</span>
                    </div>
                    <div>
                        <span class="text-gray-400 text-sm">Payment #:</span>
                        <span class="text-white font-medium ml-2" id="add-modal-payment-number">-</span>
                    </div>
                </div>
            </div>
            
            <!-- Search and Filter -->
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row gap-4">
                    <!-- Search Input -->
                    <div class="flex-1">
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-search"></i>
                            </span>
                            <input 
                                type="text" 
                                id="product-search-input" 
                                placeholder="Search products by name..."
                                class="w-full bg-gray-700 text-white pl-10 pr-4 py-3 rounded-lg border border-gray-600 focus:border-amber-500 focus:ring-2 focus:ring-amber-500 focus:outline-none transition"
                            >
                        </div>
                    </div>
                    
                    <!-- Category Filter -->
                    <div class="w-full sm:w-64">
                        <select id="category-filter" class="w-full bg-gray-700 text-white px-4 py-3 rounded-lg border border-gray-600 focus:border-amber-500 focus:ring-2 focus:ring-amber-500 focus:outline-none transition">
                            <option value="">All Categories</option>
                            <!-- Categories will be populated dynamically -->
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Products Grid -->
            <div class="mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" id="products-container">
                    <!-- Products will be loaded here -->
                    <div class="col-span-full text-center py-8">
                        <i class="fas fa-spinner fa-spin text-2xl text-gray-400 mb-3"></i>
                        <p class="text-gray-400">Loading products...</p>
                    </div>
                </div>
            </div>
            
            <!-- Selected Products Preview -->
            <div class="bg-gray-900 rounded-lg p-4 mb-6">
                <h3 class="text-white font-medium mb-3">Selected Products</h3>
                <div id="selected-products-list" class="space-y-2 max-h-40 overflow-y-auto">
                    <p class="text-gray-500 text-center py-2">No products selected yet</p>
                </div>
                <div class="flex justify-between items-center mt-3 pt-3 border-t border-gray-700">
                    <span class="text-gray-300">Total Selected:</span>
                    <span class="text-white font-medium" id="selected-count">0</span>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-700">
                <button id="cancel-add-product" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                    Cancel
                </button>
                <button id="confirm-add-product" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                    Add to Order
                </button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/staff/staff-landing-page.js') }}"></script>
</body>
</html>