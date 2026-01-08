<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Order Tracker - CAFFE ARABICA</title>
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
        .header-bg {
            background-color: #191C22;
        }
        .cinzel-font {
            font-family: 'Cinzel', serif;
        }
        .back-btn {
            transition: all 0.2s ease;
        }
        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-in-progress {
            background-color: #F59E0B;
            color: #FFFFFF;
        }
        .status-cooking {
            background-color: #3B82F6;
            color: #FFFFFF;
        }
        .status-product-ready {
            background-color: #10B981;
            color: #FFFFFF;
        }
        .status-completed {
            background-color: #6B7280;
            color: #FFFFFF;
        }
        .order-type-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .type-dine-in {
            background-color: #1E40AF;
            color: #FFFFFF;
        }
        .type-takeout {
            background-color: #7C3AED;
            color: #FFFFFF;
        }
        .refresh-btn {
            transition: all 0.2s ease;
        }
        .refresh-btn:hover {
            transform: rotate(180deg);
        }
        .loading-spinner {
            border: 3px solid rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            border-top: 3px solid #3B82F6;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .no-orders {
            background-color: rgba(30, 41, 59, 0.5);
            border-radius: 12px;
            padding: 60px 40px;
            text-align: center;
        }
        .no-orders-icon {
            color: #4B5563;
            margin-bottom: 20px;
        }
        .order-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        .order-card-in-progress {
            border-left-color: #F59E0B;
        }
        .order-card-cooking {
            border-left-color: #3B82F6;
        }
        .order-card-product-ready {
            border-left-color: #10B981;
        }
        .order-card-completed {
            border-left-color: #6B7280;
        }
        .pagination-btn {
            transition: all 0.2s ease;
        }
        .pagination-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>
<body class="bg-gray-900 min-h-screen">
    <!-- Fixed Header -->
    <div class="header-bg shadow-md fixed top-0 left-0 right-0 z-50">
        <div class="flex items-center justify-between px-6 py-4">
            <!-- Left Section: Back Button and Title -->
            <div class="flex items-center">
                <button onclick="goBack()" class="back-btn bg-amber-900 border border-amber-800 rounded-lg px-4 py-2 mr-4">
                    <i class="fas fa-arrow-left text-amber-100 mr-2"></i>
                    <span class="text-amber-100 font-medium">Back to Dashboard</span>
                </button>
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight cinzel-font">CAFFE ARABICA</h1>
                    <p class="text-sm text-gray-400 cinzel-font">Order Tracker System</p>
                </div>
            </div>
            
            <!-- Right Section: Employee Info and Refresh Button -->
            <div class="flex items-center space-x-8">
                <!-- Refresh Button -->
                <button id="refresh-btn" onclick="loadOrders()" class="refresh-btn bg-blue-600 hover:bg-blue-700 border border-blue-700 rounded-lg px-4 py-2">
                    <i class="fas fa-sync-alt text-white"></i>
                    <span class="text-white font-medium ml-2">Refresh</span>
                </button>
                
                <!-- Employee Info -->
                <div class="text-right">
                    <p class="text-sm text-gray-400">Employee</p>
                    <p class="font-semibold text-white">{{ auth()->user()->name ?? 'Staff Member' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="pt-24 px-6 pb-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header with Stats -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-white mb-2">Order Tracker</h2>
                    <p class="text-gray-400">Track and monitor all kitchen orders in real-time</p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-white" id="total-orders">0</div>
                    <div class="text-gray-400 text-sm">Total Orders</div>
                </div>
            </div>
            
            <!-- Status Filter Tabs -->
            <div class="flex flex-wrap gap-3 mb-8">
                <button onclick="filterOrders('all')" class="filter-tab active px-6 py-3 bg-blue-600 text-white rounded-lg font-medium transition" data-status="all">
                    All Orders
                </button>
                <button onclick="filterOrders('In Progress')" class="filter-tab px-6 py-3 bg-gray-700 text-gray-300 rounded-lg font-medium hover:bg-gray-600 transition" data-status="In Progress">
                    <i class="fas fa-clock mr-2"></i>In Progress
                </button>
                <button onclick="filterOrders('Cooking')" class="filter-tab px-6 py-3 bg-gray-700 text-gray-300 rounded-lg font-medium hover:bg-gray-600 transition" data-status="Cooking">
                    <i class="fas fa-utensils mr-2"></i>Cooking
                </button>
                <button onclick="filterOrders('Product Ready')" class="filter-tab px-6 py-3 bg-gray-700 text-gray-300 rounded-lg font-medium hover:bg-gray-600 transition" data-status="Product Ready">
                    <i class="fas fa-check-circle mr-2"></i>Product Ready
                </button>
                <button onclick="filterOrders('Completed')" class="filter-tab px-6 py-3 bg-gray-700 text-gray-300 rounded-lg font-medium hover:bg-gray-600 transition" data-status="Completed">
                    <i class="fas fa-check mr-2"></i>Completed
                </button>
            </div>
            
            <!-- Stats Summary -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-gray-800 rounded-xl p-5 border-l-4 border-amber-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="text-2xl font-bold text-white" id="in-progress-count">0</div>
                            <div class="text-gray-400 text-sm">In Progress</div>
                        </div>
                        <i class="fas fa-clock text-2xl text-amber-500"></i>
                    </div>
                </div>
                <div class="bg-gray-800 rounded-xl p-5 border-l-4 border-blue-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="text-2xl font-bold text-white" id="cooking-count">0</div>
                            <div class="text-gray-400 text-sm">Cooking</div>
                        </div>
                        <i class="fas fa-utensils text-2xl text-blue-500"></i>
                    </div>
                </div>
                <div class="bg-gray-800 rounded-xl p-5 border-l-4 border-green-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="text-2xl font-bold text-white" id="ready-count">0</div>
                            <div class="text-gray-400 text-sm">Product Ready</div>
                        </div>
                        <i class="fas fa-check-circle text-2xl text-green-500"></i>
                    </div>
                </div>
                <div class="bg-gray-800 rounded-xl p-5 border-l-4 border-gray-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="text-2xl font-bold text-white" id="completed-count">0</div>
                            <div class="text-gray-400 text-sm">Completed</div>
                        </div>
                        <i class="fas fa-check text-2xl text-gray-400"></i>
                    </div>
                </div>
            </div>
            
            <!-- Pagination Info -->
            <div id="pagination-info" class="flex justify-between items-center mb-4 hidden">
                <div class="text-gray-300">
                    Showing <span id="current-start">1</span> - <span id="current-end">3</span> of <span id="total-filtered">0</span> orders
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="previousPage()" id="prev-btn" class="pagination-btn bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition" disabled>
                        <i class="fas fa-chevron-left mr-1"></i> Previous
                    </button>
                    <span class="text-gray-300 mx-2" id="page-indicator">Page 1</span>
                    <button onclick="nextPage()" id="next-btn" class="pagination-btn bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition" disabled>
                        Next <i class="fas fa-chevron-right ml-1"></i>
                    </button>
                </div>
            </div>
            
            <!-- Loading State -->
            <div id="loading-container" class="text-center py-12">
                <div class="loading-spinner mx-auto mb-4"></div>
                <p class="text-gray-300">Loading orders...</p>
            </div>
            
            <!-- Orders Grid -->
            <div id="orders-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 hidden">
                <!-- Orders will be dynamically loaded here -->
            </div>
            
            <!-- No Orders Message -->
            <div id="no-orders" class="no-orders hidden">
                <i class="fas fa-clipboard-list text-6xl no-orders-icon"></i>
                <h3 class="text-xl font-semibold text-white mb-2">No orders found</h3>
                <p class="text-gray-400 mb-6">Orders will appear here as they are sent to the kitchen</p>
                <button onclick="loadOrders()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition">
                    <i class="fas fa-sync-alt mr-2"></i>Refresh Orders
                </button>
            </div>
        </div>
    </div>

    <!-- Status Message -->
    <div id="status-message" class="fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transform translate-y-4 transition-all duration-300 z-50">
        Status updated successfully!
    </div>

    <script>
        // Global variables
        let allOrders = [];
        let currentFilter = 'all';
        let currentPage = 1;
        const ordersPerPage = 3;
        
        // DOM Elements
        const ordersContainer = document.getElementById('orders-container');
        const loadingContainer = document.getElementById('loading-container');
        const noOrdersMessage = document.getElementById('no-orders');
        const totalOrdersElement = document.getElementById('total-orders');
        const inProgressCountElement = document.getElementById('in-progress-count');
        const cookingCountElement = document.getElementById('cooking-count');
        const readyCountElement = document.getElementById('ready-count');
        const completedCountElement = document.getElementById('completed-count');
        const statusMessage = document.getElementById('status-message');
        const paginationInfo = document.getElementById('pagination-info');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadOrders();
            
            // Set up auto-refresh every 30 seconds
            setInterval(loadOrders, 30000);
        });
        
        // Function to go back to previous page
        function goBack() {
            window.history.back();
        }
        
        // Function to load orders from API
        async function loadOrders() {
            try {
                // Show loading state
                loadingContainer.classList.remove('hidden');
                ordersContainer.classList.add('hidden');
                noOrdersMessage.classList.add('hidden');
                paginationInfo.classList.add('hidden');
                
                // Fetch orders from API
                const response = await fetch('/api/staff/order-tracker', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const data = await response.json();
                
                // Store all orders
                allOrders = data.orders || [];
                
                // Update stats
                updateStats();
                
                // Reset to first page when loading new data
                currentPage = 1;
                
                // Render orders based on current filter
                renderOrders();
                
            } catch (error) {
                console.error('Error loading orders:', error);
                showStatusMessage('Error loading orders: ' + error.message, 'bg-red-600');
                
                // Show error state
                ordersContainer.innerHTML = `
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-exclamation-triangle text-5xl text-red-500 mb-4"></i>
                        <h3 class="text-xl font-semibold text-white mb-2">Failed to load orders</h3>
                        <p class="text-gray-400 mb-6">${error.message}</p>
                        <button onclick="loadOrders()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition">
                            <i class="fas fa-sync-alt mr-2"></i>Try Again
                        </button>
                    </div>
                `;
                ordersContainer.classList.remove('hidden');
                loadingContainer.classList.add('hidden');
            }
        }
        
        // Function to update statistics
        function updateStats() {
            const stats = {
                total: allOrders.length,
                inProgress: allOrders.filter(order => order.cookingStatus === 'In Progress').length,
                cooking: allOrders.filter(order => order.cookingStatus === 'Cooking').length,
                ready: allOrders.filter(order => order.cookingStatus === 'Product Ready').length,
                completed: allOrders.filter(order => order.cookingStatus === 'Completed').length
            };
            
            totalOrdersElement.textContent = stats.total;
            inProgressCountElement.textContent = stats.inProgress;
            cookingCountElement.textContent = stats.cooking;
            readyCountElement.textContent = stats.ready;
            completedCountElement.textContent = stats.completed;
        }
        
        // Function to filter orders
        function filterOrders(status) {
            currentFilter = status;
            currentPage = 1; // Reset to first page when filtering
            renderOrders();
            
            // Update active tab
            document.querySelectorAll('.filter-tab').forEach(tab => {
                if (tab.getAttribute('data-status') === status) {
                    tab.classList.remove('bg-gray-700', 'text-gray-300');
                    tab.classList.add('bg-blue-600', 'text-white');
                } else {
                    tab.classList.remove('bg-blue-600', 'text-white');
                    tab.classList.add('bg-gray-700', 'text-gray-300');
                }
            });
        }
        
        // Function to render orders with pagination
        function renderOrders() {
            // Filter orders based on current filter
            let filteredOrders = allOrders;
            if (currentFilter !== 'all') {
                filteredOrders = allOrders.filter(order => order.cookingStatus === currentFilter);
            }
            
            // Calculate pagination
            const totalPages = Math.ceil(filteredOrders.length / ordersPerPage);
            const startIndex = (currentPage - 1) * ordersPerPage;
            const endIndex = Math.min(startIndex + ordersPerPage, filteredOrders.length);
            const paginatedOrders = filteredOrders.slice(startIndex, endIndex);
            
            // Clear container
            ordersContainer.innerHTML = '';
            
            // Hide loading
            loadingContainer.classList.add('hidden');
            
            if (filteredOrders.length === 0) {
                // Show no orders message
                noOrdersMessage.classList.remove('hidden');
                ordersContainer.classList.add('hidden');
                paginationInfo.classList.add('hidden');
                return;
            }
            
            // Show orders container
            noOrdersMessage.classList.add('hidden');
            ordersContainer.classList.remove('hidden');
            
            // Update pagination info
            document.getElementById('current-start').textContent = filteredOrders.length > 0 ? startIndex + 1 : 0;
            document.getElementById('current-end').textContent = endIndex;
            document.getElementById('total-filtered').textContent = filteredOrders.length;
            document.getElementById('page-indicator').textContent = `Page ${currentPage} of ${totalPages}`;
            
            // Update pagination button states
            prevBtn.disabled = currentPage === 1;
            nextBtn.disabled = currentPage === totalPages;
            
            // Show/hide pagination based on need
            if (filteredOrders.length > ordersPerPage) {
                paginationInfo.classList.remove('hidden');
            } else {
                paginationInfo.classList.add('hidden');
            }
            
            // Render each order
            paginatedOrders.forEach(order => {
                const orderCard = document.createElement('div');
                
                // Determine card border color based on status
                let cardBorderClass = '';
                switch(order.cookingStatus) {
                    case 'In Progress':
                        cardBorderClass = 'order-card-in-progress';
                        break;
                    case 'Cooking':
                        cardBorderClass = 'order-card-cooking';
                        break;
                    case 'Product Ready':
                        cardBorderClass = 'order-card-product-ready';
                        break;
                    case 'Completed':
                        cardBorderClass = 'order-card-completed';
                        break;
                }
                
                // Determine status badge class
                let statusBadgeClass = '';
                let statusIcon = '';
                switch(order.cookingStatus) {
                    case 'In Progress':
                        statusBadgeClass = 'status-in-progress';
                        statusIcon = 'fa-clock';
                        break;
                    case 'Cooking':
                        statusBadgeClass = 'status-cooking';
                        statusIcon = 'fa-utensils';
                        break;
                    case 'Product Ready':
                        statusBadgeClass = 'status-product-ready';
                        statusIcon = 'fa-check-circle';
                        break;
                    case 'Completed':
                        statusBadgeClass = 'status-completed';
                        statusIcon = 'fa-check';
                        break;
                }
                
                // Determine order type badge
                const typeBadgeClass = order.orderType === 'dine-in' ? 'type-dine-in' : 'type-takeout';
                const typeText = order.orderType === 'dine-in' ? 'Dine In' : 'Takeout';
                
                // Format time
                const orderTime = new Date(order.paymentProcessedAt);
                const timeString = orderTime.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });
                
                // Format date
                const dateString = orderTime.toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                });
                
                orderCard.className = `order-card bg-gray-800 rounded-xl p-5 border border-gray-700 ${cardBorderClass}`;
                orderCard.innerHTML = `
                    <!-- Order Header -->
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-white mb-1">${order.orderID}</h3>
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-300">Payment #${order.paymentNumber}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="${statusBadgeClass} status-badge inline-flex items-center gap-1">
                                <i class="fas ${statusIcon} text-xs"></i>
                                <span>${order.cookingStatus}</span>
                            </div>
                            <div class="text-gray-400 text-sm mt-2">${timeString}</div>
                            <div class="text-gray-500 text-xs">${dateString}</div>
                        </div>
                    </div>
                    
                    <!-- Order Details -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Order Type:</span>
                            <span class="${typeBadgeClass} order-type-badge">${typeText}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Payment Method:</span>
                            <span class="text-white font-medium">${order.paymentMethod}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Total Items:</span>
                            <span class="text-white font-bold">${order.totalItems}</span>
                        </div>
                        
                        <div class="pt-4 border-t border-gray-700">
                            <div class="text-sm text-gray-400 mb-2">Order Items:</div>
                            <div class="space-y-2 max-h-40 overflow-y-auto pr-2">
                                ${order.items.map(item => `
                                    <div class="flex justify-between items-center bg-gray-700/30 rounded px-3 py-2">
                                        <div class="flex items-center">
                                            <span class="text-white text-sm">${item.productName}</span>
                                            <span class="text-blue-400 text-xs ml-2">×${item.quantity}</span>
                                            ${item.productNotes ? `
                                            <div class="ml-2 text-xs text-amber-400" title="${item.productNotes}">
                                                <i class="fas fa-sticky-note"></i>
                                            </div>
                                            ` : ''}
                                        </div>
                                        <span class="text-amber-400 text-sm">₱${parseFloat(item.totalPrice).toFixed(2)}</span>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Order Summary - Only Total -->
                    <div class="mt-4 pt-4 border-t border-gray-700">
                        <div class="flex justify-between items-center">
                            <span class="text-white font-semibold">Total:</span>
                            <span class="text-green-400 font-bold">
                                ₱${(order.items.reduce((sum, item) => sum + parseFloat(item.totalPrice), 0) + 
                                   order.items.reduce((sum, item) => sum + parseFloat(item.taxAmount || 0), 0)).toFixed(2)}
                            </span>
                        </div>
                    </div>
                `;
                
                ordersContainer.appendChild(orderCard);
            });
        }
        
        // Function to go to next page
        function nextPage() {
            // Filter orders based on current filter
            let filteredOrders = allOrders;
            if (currentFilter !== 'all') {
                filteredOrders = allOrders.filter(order => order.cookingStatus === currentFilter);
            }
            
            const totalPages = Math.ceil(filteredOrders.length / ordersPerPage);
            
            if (currentPage < totalPages) {
                currentPage++;
                renderOrders();
                scrollToTop();
            }
        }
        
        // Function to go to previous page
        function previousPage() {
            if (currentPage > 1) {
                currentPage--;
                renderOrders();
                scrollToTop();
            }
        }
        
        // Function to scroll to top of orders section
        function scrollToTop() {
            const ordersSection = document.getElementById('orders-container');
            ordersSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        
        // Function to show status message
        function showStatusMessage(message, bgColor = 'bg-green-600') {
            statusMessage.textContent = message;
            statusMessage.className = `fixed bottom-4 right-4 ${bgColor} text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transform translate-y-4 transition-all duration-300 z-50`;
            
            // Show message
            statusMessage.classList.remove('opacity-0', 'translate-y-4');
            statusMessage.classList.add('opacity-100', 'translate-y-0');
            
            // Hide message after 3 seconds
            setTimeout(() => {
                statusMessage.classList.remove('opacity-100', 'translate-y-0');
                statusMessage.classList.add('opacity-0', 'translate-y-4');
            }, 3000);
        }
    </script>
</body>
</html>