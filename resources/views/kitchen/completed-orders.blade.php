<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Completed Orders - CAFFE ARABICA Kitchen</title>
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
        .cinzel-font {
            font-family: 'Cinzel', serif;
        }
        .header-bg {
            background-color: #191C22;
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
        }
        .coffee-icon {
            color: #F5DEB3;
            font-size: 32px;
        }
        .clock {
            font-variant-numeric: tabular-nums;
            letter-spacing: 1px;
        }
        .order-card {
            transition: all 0.3s ease;
        }
        .order-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        .status-completed {
            background-color: #10B981;
            color: #FFFFFF;
            font-size: 0.75rem;
            padding: 4px 10px;
            border-radius: 4px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .order-type-badge {
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .payment-badge {
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .product-quantity-small {
            background-color: #1E40AF;
            color: white;
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 4px;
            margin-right: 10px;
            font-weight: 600;
            min-width: 24px;
            text-align: center;
        }
        .back-btn {
            background-color: #374151;
            border: 1px solid #4B5563;
            transition: all 0.2s ease;
        }
        .back-btn:hover {
            background-color: #4B5563;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .stats-card {
            background: linear-gradient(135deg, #1F2937 0%, #111827 100%);
            border: 1px solid #374151;
            border-radius: 12px;
            padding: 20px;
            transition: all 0.3s ease;
        }
        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
        .stats-icon-orders {
            background-color: rgba(16, 185, 129, 0.2);
            color: #10B981;
        }
        .stats-icon-items {
            background-color: rgba(59, 130, 246, 0.2);
            color: #3B82F6;
        }
        .stats-icon-revenue {
            background-color: rgba(245, 158, 11, 0.2);
            color: #F59E0B;
        }
        .stats-value {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
        }
        .stats-label {
            font-size: 0.875rem;
            color: #9CA3AF;
            margin-top: 4px;
        }
        .empty-state {
            background-color: rgba(55, 65, 81, 0.5);
            border-radius: 12px;
            padding: 60px 40px;
            text-align: center;
            border: 2px dashed #4B5563;
        }
        .empty-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #6B7280;
        }
        .empty-title {
            font-size: 1.5rem;
            color: #9CA3AF;
            margin-bottom: 10px;
        }
        .empty-subtitle {
            color: #6B7280;
            margin-bottom: 30px;
        }
        .time-filter-btn {
            background-color: #374151;
            border: 1px solid #4B5563;
            padding: 8px 16px;
            border-radius: 6px;
            color: #D1D5DB;
            transition: all 0.2s ease;
        }
        .time-filter-btn:hover {
            background-color: #4B5563;
            color: white;
        }
        .time-filter-btn.active {
            background-color: #3B82F6;
            border-color: #3B82F6;
            color: white;
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
        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        /* Pagination controls */
        .pagination-controls {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-top: 30px;
            margin-bottom: 20px;
        }
        .pagination-arrow {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #374151;
            border: 2px solid #4B5563;
            color: white;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .pagination-arrow:hover:not(:disabled) {
            background-color: #4B5563;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .pagination-arrow:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }
        .pagination-info {
            color: white;
            font-size: 1rem;
            font-weight: 500;
            min-width: 120px;
            text-align: center;
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
        .completed-date {
            background-color: rgba(16, 185, 129, 0.1);
            border-left: 4px solid #10B981;
            padding-left: 12px;
            margin-bottom: 20px;
        }
        /* Order groups container */
        .order-groups-container {
            position: relative;
            margin: 0 auto;
            max-width: 1400px;
        }
        .order-group {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            transition: opacity 0.3s ease;
        }
        .order-group.hidden {
            display: none;
        }
    </style>
</head>
<body class="bg-gray-900 min-h-screen">
    <!-- Fixed Header -->
    <div class="header-bg shadow-md fixed top-0 left-0 right-0 z-50">
        <div class="flex items-center justify-between px-6 py-4">
            <!-- Left Section: Logo/Title with Back Button -->
            <div class="flex items-center">
                <a href="{{ route('kitchen.dashboard') }}" class="back-btn rounded-lg px-4 py-2 mr-4">
                    <i class="fas fa-arrow-left text-white"></i>
                </a>
                <div class="logo-placeholder">
                    <div class="coffee-icon">
                        <img src="/images/Coffee.svg" alt="CAFFE ARABICA Logo" class="w-full h-full">
                    </div>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight cinzel-font" style="font-size: 32px;">CAFFE ARABICA</h1>
                    <p class="text-sm text-gray-400 cinzel-font" style="font-size: 24px;">Completed Orders History</p>
                </div>
            </div>
            
            <!-- Right Section: Time Info -->
            <div class="flex items-center space-x-8">
                <!-- Live Clock -->
                <div class="bg-gray-800 text-white rounded-lg px-4 py-3 min-w-[180px] text-center clock">
                    <div id="live-clock" class="text-xl font-bold tracking-wider">2:45:59 PM</div>
                    <div id="current-date" class="text-xs text-gray-400">May 25, 2025</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="pt-28 px-6 pb-20">
        <div class="max-w-6xl mx-auto">
            <!-- Stats Overview -->
            <div class="mb-3">
                <h2 class="text-2xl font-bold text-white mb-4">Completed Orders Overview</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-3">
                    <!-- Total Orders Card -->
                    <div class="stats-card">
                        <div class="flex items-center">
                            <div class="stats-icon stats-icon-orders">
                                <i class="fas fa-clipboard-check text-2xl"></i>
                            </div>
                            <div>
                                <div class="stats-value text-white">
                                    {{ isset($groupedOrders) ? count($groupedOrders) : 0 }}
                                </div>
                                <div class="stats-label">Total Orders Completed</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Total Items Card -->
                    <div class="stats-card">
                        <div class="flex items-center">
                            <div class="stats-icon stats-icon-items">
                                <i class="fas fa-utensils text-2xl"></i>
                            </div>
                            <div>
                                @php
                                    $totalItems = 0;
                                    if (isset($groupedOrders)) {
                                        foreach ($groupedOrders as $orderGroup) {
                                            $totalItems += count($orderGroup);
                                        }
                                    }
                                @endphp
                                <div class="stats-value text-white">{{ $totalItems }}</div>
                                <div class="stats-label">Total Items Prepared</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Time Filter -->
                <div class="flex gap-2 mb-2">
                    <button class="time-filter-btn active" onclick="filterOrders('today')">Today</button>
                    <button class="time-filter-btn" onclick="filterOrders('week')">This Week</button>
                    <button class="time-filter-btn" onclick="filterOrders('month')">This Month</button>
                    <button class="time-filter-btn" onclick="filterOrders('all')">All Time</button>
                </div>
            </div>
            
            <!-- Orders List -->
            <div>
                @if(isset($groupedOrders) && count($groupedOrders) > 0)
                    <!-- Group orders by date -->
                    @php
                        $ordersByDate = [];
                        foreach ($groupedOrders as $orderID => $orderGroup) {
                            $date = date('Y-m-d', strtotime($orderGroup[0]->paymentProcessedAt));
                            if (!isset($ordersByDate[$date])) {
                                $ordersByDate[$date] = [];
                            }
                            $ordersByDate[$date][$orderID] = $orderGroup;
                        }
                        
                        // Sort dates in descending order
                        krsort($ordersByDate);
                    @endphp
                    
                    <div class="order-groups-container">
                        @php
                            // Flatten all orders into a single array for pagination
                            $allOrders = [];
                            foreach ($ordersByDate as $date => $orders) {
                                foreach ($orders as $orderID => $orderGroup) {
                                    $allOrders[] = [
                                        'orderID' => $orderID,
                                        'orderGroup' => $orderGroup,
                                        'date' => $date
                                    ];
                                }
                            }
                            
                            // Split orders into groups of 3 (single column, 3 rows)
                            $orderGroups = array_chunk($allOrders, 3);
                        @endphp
                        
                        @foreach($orderGroups as $groupIndex => $group)
                        <div class="order-group @if($groupIndex > 0) hidden @endif" data-group-index="{{ $groupIndex }}">
                            @foreach($group as $order)
                                @php
                                    $orderID = $order['orderID'];
                                    $orderGroup = $order['orderGroup'];
                                @endphp
                                    <div class="order-card bg-gray-800 rounded-xl p-5 border border-gray-700 paginated-order">
                                        <!-- Order Header -->
                                        <div class="mb-4">
                                            <!-- Top Row: Order Number and Completed Badge -->
                                            <div class="flex items-center justify-between mb-1">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-white font-bold text-lg">Order #{{ $orderGroup[0]->paymentNumber }}</span>
                                                    <span class="status-completed">Completed</span>
                                                </div>
                                                <div class="text-right">
                                                    <div class="text-gray-400 text-xs mb-1">Completed At</div>
                                                    <div class="text-white font-semibold text-lg">
                                                        {{ date('h:i A', strtotime($orderGroup[0]->paymentProcessedAt)) }}
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Bottom Row: ID and Badges -->
                                            <div class="flex items-center gap-3">
                                                <span class="text-gray-300 text-sm">ID: {{ $orderID }}</span>
                                                <span class="order-type-badge 
                                                    @if($orderGroup[0]->orderType == 'dine-in') bg-blue-900 text-blue-200
                                                    @else bg-green-900 text-green-200 @endif">
                                                    {{ ucfirst($orderGroup[0]->orderType) }}
                                                </span>
                                                <span class="payment-badge 
                                                    @if($orderGroup[0]->paymentMethod == 'cash') bg-green-900 text-green-200
                                                    @else bg-purple-900 text-purple-200 @endif">
                                                    {{ ucfirst($orderGroup[0]->paymentMethod) }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Order Items -->
                                        <div class="space-y-2 mb-4">
                                            @foreach($orderGroup as $item)
                                                <div class="flex items-center justify-between py-2 border-b border-gray-700 last:border-b-0">
                                                    <div class="flex items-center">
                                                        <span class="product-quantity-small">{{ $item->quantity }}x</span>
                                                        <span class="text-white">{{ $item->productName }}</span>
                                                    </div>
                                                    <div class="text-right">
                                                        <div class="text-gray-300 text-sm">${{ number_format($item->totalPrice, 2) }}</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Order Summary -->
                                        <div class="pt-4 border-t border-gray-700">
                                            <div class="flex justify-between items-center mb-2">
                                                <span class="text-gray-400">Total Items:</span>
                                                <span class="text-white font-bold">{{ count($orderGroup) }}</span>
                                            </div>
                                            @php
                                                $orderTotal = 0;
                                                foreach ($orderGroup as $item) {
                                                    $orderTotal += $item->totalPrice;
                                                }
                                            @endphp
                                            <div class="flex justify-between items-center mt-2 pt-2 border-t border-gray-700">
                                                <span class="text-gray-400">Order Total:</span>
                                                <span class="text-green-400 font-bold text-lg">${{ number_format($orderTotal, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination Controls -->
                    @if(count($orderGroups) > 1)
                    <div class="pagination-controls mb-2 mt-2">
                        <button id="prev-page" class="pagination-arrow" onclick="previousPage()" disabled>
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        
                        <div class="pagination-info">
                            <span id="current-page">1</span> / <span id="total-pages">{{ count($orderGroups) }}</span>
                        </div>
                        
                        <button id="next-page" class="pagination-arrow" onclick="nextPage()" @if(count($orderGroups) <= 1) disabled @endif>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    @endif
                    
                @else
                    <!-- Empty State -->
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <h3 class="empty-title">No Completed Orders Yet</h3>
                        <p class="empty-subtitle">Orders marked as completed will appear here</p>
                        <a href="{{ route('kitchen.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-amber-700 hover:bg-amber-800 text-white rounded-lg transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Kitchen Dashboard
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer with Reduced Height -->
    <footer class="bg-gray-900 border-t border-gray-800 compact-footer fixed bottom-0 left-0 right-0 z-40">
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
        // Live Clock
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { 
                hour12: true, 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit' 
            });
            const dateString = now.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            
            document.getElementById('live-clock').textContent = timeString;
            document.getElementById('current-date').textContent = dateString;
        }
        
        setInterval(updateClock, 1000);
        updateClock();

        // Pagination functionality
        let currentPage = 1;
        const orderGroups = document.querySelectorAll('.order-group');
        const totalPages = orderGroups.length;

        function updatePagination() {
            // Update order groups visibility
            orderGroups.forEach((group, index) => {
                if (index + 1 === currentPage) {
                    group.classList.remove('hidden');
                } else {
                    group.classList.add('hidden');
                }
            });

            // Update page indicator
            const currentPageElement = document.getElementById('current-page');
            if (currentPageElement && totalPages > 0) {
                currentPageElement.textContent = currentPage;
            }

            // Update button states
            const prevBtn = document.getElementById('prev-page');
            const nextBtn = document.getElementById('next-page');

            if (prevBtn) prevBtn.disabled = currentPage === 1;
            if (nextBtn) nextBtn.disabled = currentPage === totalPages;

            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Initialize pagination when page loads
        if (orderGroups.length > 0) {
            updatePagination();
        }

        function previousPage() {
            if (currentPage > 1) {
                currentPage--;
                updatePagination();
            }
        }

        function nextPage() {
            if (currentPage < totalPages) {
                currentPage++;
                updatePagination();
            }
        }

        // Modal functionality
        document.getElementById('terms-btn').addEventListener('click', () => {
            document.getElementById('terms-modal').classList.remove('hidden');
        });

        document.getElementById('privacy-btn').addEventListener('click', () => {
            document.getElementById('privacy-modal').classList.remove('hidden');
        });

        document.getElementById('close-terms').addEventListener('click', () => {
            document.getElementById('terms-modal').classList.add('hidden');
        });

        document.getElementById('close-privacy').addEventListener('click', () => {
            document.getElementById('privacy-modal').classList.add('hidden');
        });

        document.getElementById('accept-terms').addEventListener('click', () => {
            document.getElementById('terms-modal').classList.add('hidden');
        });

        document.getElementById('accept-privacy').addEventListener('click', () => {
            document.getElementById('privacy-modal').classList.add('hidden');
        });

        // Time Filter Functionality
        function filterOrders(filterType) {
            // Update active button
            document.querySelectorAll('.time-filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            
            // For now, just show a message - in production, this would make an AJAX request
            const messages = {
                'today': 'Showing orders from today',
                'week': 'Showing orders from this week',
                'month': 'Showing orders from this month',
                'all': 'Showing all completed orders'
            };
            
            showNotification(messages[filterType]);
        }
        
        function showNotification(message) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';
            notification.textContent = message;
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(20px)';
            notification.style.transition = 'all 0.3s ease';
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.style.opacity = '1';
                notification.style.transform = 'translateY(0)';
            }, 10);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }
    </script>
</body>
</html>