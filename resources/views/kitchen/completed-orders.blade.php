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
        .completed-date {
            background-color: rgba(16, 185, 129, 0.1);
            border-left: 4px solid #10B981;
            padding-left: 12px;
            margin-bottom: 20px;
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
                    <h1 class="text-2xl font-bold text-white tracking-tight cinzel-font">CAFFE ARABICA</h1>
                    <p class="text-sm text-gray-400 cinzel-font">Completed Orders History</p>
                </div>
            </div>
            
            <!-- Right Section: Time Info -->
            <div class="flex items-center space-x-8">
                <!-- Live Clock -->
                <div class="bg-gray-800 text-white rounded-lg px-4 py-3 min-w-[130px] text-center clock">
                    <div id="live-clock" class="text-xl font-bold tracking-wider">2:45:59 PM</div>
                    <div id="current-date" class="text-xs text-gray-400">May 25, 2025</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="pt-24 px-6 pb-10">
        <div class="max-w-6xl mx-auto">
            <!-- Stats Overview -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-white mb-6">Completed Orders Overview</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
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
                <div class="flex gap-2 mb-6">
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
                    
                    @foreach($ordersByDate as $date => $orders)
                        <div class="mb-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($orders as $orderID => $orderGroup)
                                    <div class="order-card bg-gray-800 rounded-xl p-5 border border-gray-700">
                                        <!-- Order Header -->
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <div class="flex items-center gap-2 mb-2">
                                                    <span class="text-white font-bold text-lg">Order #{{ $orderGroup[0]->paymentNumber }}</span>
                                                    <span class="status-completed">Completed</span>
                                                </div>
                                                <div class="flex items-center gap-4 text-sm">
                                                    <span class="text-gray-300">ID: {{ $orderID }}</span>
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
                                            <div class="text-right">
                                                <div class="text-gray-400 text-sm">Completed At</div>
                                                <div class="text-white font-semibold">
                                                    {{ date('H:i', strtotime($orderGroup[0]->paymentProcessedAt)) }}
                                                </div>
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
                        </div>
                    @endforeach
                    
                    <!-- Pagination -->
                    <div class="flex justify-center gap-4 mt-10">
                        <button id="prev-page" class="pagination-btn rounded-lg px-4 py-2 text-white disabled">
                            <i class="fas fa-chevron-left mr-2"></i> Previous
                        </button>
                        <button id="next-page" class="pagination-btn rounded-lg px-4 py-2 text-white">
                            Next <i class="fas fa-chevron-right ml-2"></i>
                        </button>
                    </div>
                    
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

        // Simple Pagination (for demo - in production you would implement actual pagination)
        document.getElementById('next-page')?.addEventListener('click', () => {
            showNotification('Next page would load more orders');
        });
        
        document.getElementById('prev-page')?.addEventListener('click', () => {
            showNotification('Previous page would load earlier orders');
        });
    </script>
</body>
</html>