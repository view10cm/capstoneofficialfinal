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
        .tax-row {
            color: #60A5FA;
            border-top: 1px dashed #4B5563;
            padding-top: 8px;
            margin-top: 8px;
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
                    <button id="order-tracker-btn" class="order-tracker-btn bg-amber-900 border border-amber-800 rounded-lg px-4 py-2">
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

    <script>
        // Order data will be loaded from the server
        let allOrders = [];
        
        // Helper function to format currency
        function formatCurrency(amount) {
            return `₱${parseFloat(amount).toFixed(2)}`;
        }
        
        // Helper function to calculate order totals
        function calculateOrderTotals(order) {
            const subtotal = order.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const tax = subtotal * order.taxRate;
            const total = subtotal + tax;
            
            return {
                subtotal: subtotal,
                tax: tax,
                total: total
            };
        }
        
        // Helper function to calculate all orders statistics
        function calculateRevenueStatistics() {
            let totalRevenue = 0;
            let totalTax = 0;
            
            allOrders.forEach(order => {
                const totals = calculateOrderTotals(order);
                totalRevenue += totals.total;
                totalTax += totals.tax;
            });
            
            const avgOrderValue = allOrders.length > 0 ? totalRevenue / allOrders.length : 0;
<<<<<<< Updated upstream
=======
            
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
            const total = subtotal + tax;
>>>>>>> Stashed changes
            
            return {
                totalRevenue: totalRevenue,
                totalTax: totalTax,
                avgOrderValue: avgOrderValue
            };
        }
        
        // Helper function for API calls
        async function apiCall(url, method = 'GET', data = null) {
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
            
            const response = await fetch(url, options);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            return await response.json();
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
                        paymentNumber: order.paymentNumber,
                        time: calculateOrderTime(order.orderCreateDateAndTime),
                        type: order.orderType === 'dine-in' ? 'Dine in' : 'Takeout',
                        typeColor: order.orderType === 'dine-in' ? 'bg-blue-900 text-blue-200' : 'bg-purple-900 text-purple-200',
                        payment: order.orderPaymentMethod === 'cash' ? 'Cash' : 'Electronic',
                        items: [],
                        taxRate: 0.12
                    };
                }
                
                // Add item to the order - include quantity as a separate property
                groupedOrders[orderKey].items.push({
                    name: order.orderProductName,
                    price: parseFloat(order.orderTotalProductPrice) / order.orderQuantity,
                    quantity: order.orderQuantity,
                    totalPrice: parseFloat(order.orderTotalProductPrice)
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
                // Calculate totals for this order
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
                
                const orderCard = document.createElement('div');
                orderCard.className = 'order-card bg-gray-800 rounded-xl border border-gray-700 overflow-hidden';
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
                            <div class="flex flex-wrap gap-2 mb-4">
                                <div class="${order.typeColor} px-3 py-1 rounded-md text-sm">
                                    ${order.type}
                                </div>
                                <div class="${paymentClass} px-3 py-1 rounded-md text-sm font-medium">
                                    ${paymentText}
                                </div>
                            </div>
                            
                            <!-- Order Items with Quantities and Prices -->
                            <ul class="space-y-2">
                                ${order.items.map((item, itemIndex) => `
                                    <li class="price-item">
                                        <div class="flex items-start w-full">
                                            <div class="item-details">
                                                <span class="order-item-text">${item.name}</span>
                                                <span class="quantity-badge">×${item.quantity}</span>
                                            </div>
                                        </div>
                                        <span class="price-tag">${formatCurrency(item.totalPrice)}</span>
                                    </li>
                                `).join('')}
                            </ul>
                            
                            <!-- Price Summary Section -->
                            <div class="price-section">
                                <!-- Subtotal -->
                                <div class="price-item">
                                    <span class="text-gray-300">Subtotal:</span>
                                    <span class="text-gray-300">${formatCurrency(totals.subtotal)}</span>
                                </div>
                                
                                <!-- Tax -->
                                <div class="price-item tax-row">
                                    <span class="text-gray-300">Tax (${(order.taxRate * 100).toFixed(0)}%):</span>
                                    <span class="text-blue-400 font-medium">${formatCurrency(totals.tax)}</span>
                                </div>
                                
                                <!-- Total -->
                                <div class="price-item total-row">
                                    <span class="text-white">Total to Pay:</span>
                                    <span class="text-green-400 font-bold">${formatCurrency(totals.total)}</span>
                                </div>
                            </div>
                            
                            <!-- Item count summary -->
                            <div class="mt-3 pt-3 border-t border-gray-700">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-400 text-sm">Total items in order:</span>
                                    <span class="text-amber-400 font-medium">${order.items.reduce((sum, item) => sum + item.quantity, 0)}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <button class="send-to-kitchen-btn w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 rounded-lg transition" data-order-id="${order.id}" data-payment-number="${order.paymentNumber}">
                                Send to Kitchen
                            </button>
                            <button class="cancel-order-btn w-full bg-amber-700 hover:bg-amber-800 text-white font-medium py-3 rounded-lg transition" data-order-id="${order.id}" data-payment-number="${order.paymentNumber}">
                                Cancel Order
                            </button>
                            <button class="void-order-btn w-full bg-red-700 hover:bg-red-800 text-white font-medium py-3 rounded-lg transition" data-order-id="${order.id}" data-payment-number="${order.paymentNumber}">
                                Void Order
                            </button>
                        </div>
                    </div>
                `;
                
                ordersContainer.appendChild(orderCard);
            });
            
            // Re-attach event listeners to new buttons
            attachOrderButtonListeners();
<<<<<<< Updated upstream
=======
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
>>>>>>> Stashed changes
        }
        
        function attachOrderButtonListeners() {
            // Send to Kitchen buttons
            document.querySelectorAll('.send-to-kitchen-btn').forEach(button => {
                button.addEventListener('click', async function(e) {
                    const orderId = this.getAttribute('data-order-id');
                    const paymentNumber = this.getAttribute('data-payment-number');
                    
                    try {
                        await apiCall('/api/staff/orders/update-all-status', 'POST', {
                            orderID: orderId,
                            paymentNumber: paymentNumber,
                            status: 'In Progress'
                        });
                    } catch (error) {
                        console.error('Error sending to kitchen:', error);
                        showStatusMessage('Error updating order', 'bg-red-600');
                        return;
                    }
                    
                    showStatusMessage(`Order ${orderId} (Payment #${paymentNumber}) sent to kitchen!`, 'bg-green-600');
                    
                    // Update button to "In Progress"
                    this.textContent = 'In Progress';
                    this.classList.remove('bg-green-600', 'hover:bg-green-700');
                    this.classList.add('bg-blue-600', 'hover:bg-blue-700');
                    
                    // Update timer badge
                    const card = this.closest('.order-card');
                    const timerBadge = card.querySelector('.status-timer');
                    timerBadge.textContent = '0m';
                    timerBadge.className = 'bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold status-timer';
                });
            });
            
            // Cancel Order buttons
            document.querySelectorAll('.cancel-order-btn').forEach(button => {
                button.addEventListener('click', async function(e) {
                    const orderId = this.getAttribute('data-order-id');
                    const paymentNumber = this.getAttribute('data-payment-number');
                    
                    try {
                        await apiCall('/api/staff/orders/cancel', 'POST', {
                            orderID: orderId,
                            paymentNumber: paymentNumber
                        });
                    } catch (error) {
                        console.error('Error cancelling order:', error);
                        showStatusMessage('Error cancelling order', 'bg-red-600');
                        return;
                    }
                    
                    showStatusMessage(`Order ${orderId} (Payment #${paymentNumber}) cancelled!`, 'bg-amber-600');
                    
                    // Reload orders
                    loadOrders();
                });
            });
            
            // Void Order buttons
            document.querySelectorAll('.void-order-btn').forEach(button => {
                button.addEventListener('click', async function(e) {
                    const orderId = this.getAttribute('data-order-id');
                    const paymentNumber = this.getAttribute('data-payment-number');
                    
                    try {
                        await apiCall('/api/staff/orders/void', 'POST', {
                            orderID: orderId,
                            paymentNumber: paymentNumber
                        });
                    } catch (error) {
                        console.error('Error voiding order:', error);
                        showStatusMessage('Error voiding order', 'bg-red-600');
                        return;
                    }
                    
                    showStatusMessage(`Order ${orderId} (Payment #${paymentNumber}) voided!`, 'bg-red-600');
                    
                    // Reload orders
                    loadOrders();
                });
            });
        }
        
        function showStatusMessage(message, bgColor) {
            statusMessage.textContent = message;
            statusMessage.className = `fixed bottom-4 right-4 ${bgColor} text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transform translate-y-4 transition-all duration-300 z-50`;
            
            // Show message
            statusMessage.classList.remove('opacity-0', 'translate-y-4');
            statusMessage.classList.add('opacity-100', 'translate-y-0');
            
            // Hide message after 2 seconds
            setTimeout(() => {
                statusMessage.classList.remove('opacity-100', 'translate-y-0');
                statusMessage.classList.add('opacity-0', 'translate-y-4');
            }, 2000);
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
        
        orderTrackerBtn.addEventListener('click', function() {
            showStatusMessage('Order tracker clicked! Opening order details...', 'bg-green-600');
        });
        
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