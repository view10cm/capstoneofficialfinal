<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kitchen - CAFFE ARABICA</title>
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
            font-size: 0.75rem;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: 600;
            margin-left: 8px;
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
        /* Order removal animation */
        .order-removing {
            animation: removeOrder 0.5s ease-out forwards;
        }
        @keyframes removeOrder {
            0% {
                opacity: 1;
                max-height: 500px;
                transform: translateY(0);
            }
            50% {
                opacity: 0.3;
                transform: translateY(-10px);
            }
            100% {
                opacity: 0;
                max-height: 0;
                padding: 0;
                margin: 0;
                transform: translateY(20px);
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
        /* Action buttons styling */
        .action-btn {
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
        }
        .begin-preparing-btn {
            background-color: #3B82F6;
            color: white;
        }
        .begin-preparing-btn:hover:not(:disabled) {
            background-color: #2563EB;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(37, 99, 235, 0.3);
        }
        .begin-preparing-btn:disabled {
            background-color: #60A5FA;
            opacity: 0.6;
            cursor: not-allowed;
        }
        .send-to-staff-btn {
            background-color: #10B981;
            color: white;
        }
        .send-to-staff-btn:hover:not(:disabled) {
            background-color: #059669;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
        }
        .send-to-staff-btn:disabled {
            background-color: #34D399;
            opacity: 0.6;
            cursor: not-allowed;
        }
        /* Order status badges */
        .order-status {
            font-size: 0.75rem;
            padding: 4px 10px;
            border-radius: 4px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-pending {
            background-color: #F59E0B;
            color: #FFFFFF;
        }
        .status-preparing {
            background-color: #3B82F6;
            color: #FFFFFF;
        }
        .status-ready {
            background-color: #10B981;
            color: #FFFFFF;
        }
        .status-completed {
            background-color: #6B7280;
            color: #FFFFFF;
        }
        /* Order items list */
        .order-items-list {
            margin-top: 12px;
            padding-left: 4px;
        }
        .order-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #374151;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .order-item-details {
            display: flex;
            align-items: center;
            flex-grow: 1;
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
        /* Main content area */
        .main-content {
            flex: 1;
            overflow-y: auto;
            padding-bottom: 80px;
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
        /* Order groups container */
        .order-groups-container {
            position: relative;
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
        /* Page indicator dots */
        .page-indicators {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }
        .page-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #4B5563;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .page-dot.active {
            background-color: #F59E0B;
            transform: scale(1.2);
        }
        .page-dot:hover {
            background-color: #6B7280;
        }
        /* Payment Number Display */
        .payment-number-display {
            background-color: #1E40AF;
            color: white;
            font-size: 0.8rem;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: 600;
            margin-left: 8px;
        }
        /* Order header info container */
        .order-header-info {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 4px;
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
                <div class="text-center">
                    <p class="text-sm text-gray-400" style="font-size: 15px;">Employee</p>
                    <p class="font-semibold text-white" style="font-size: 18px;">{{ auth()->user()->name ?? 'Staff Member' }}</p>
                </div>
                
                <!-- Order Tracker Button -->
                <div class="relative">
                    <a href="{{ route('kitchen.completed-orders') }}" id="order-tracker-btn" class="order-tracker-btn bg-amber-900 border border-amber-800 rounded-lg px-4 py-2 inline-flex items-center">
                        <i class="fas fa-clipboard-check text-amber-100 mr-2"></i>
                        <p class="text-amber-100 font-medium" style="font-size: 15px;">Completed Orders</p>
                    </a>
                    <div class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 rounded-full flex items-center justify-center">
                        @php
                            $completedCount = DB::table('staff_to_kitchen_transaction')
                                ->where('cookingStatus', 'Completed')
                                ->distinct('orderID')
                                ->count();
                        @endphp
                        <span id="order-count" class="text-white text-xs font-bold">{{ $completedCount }}</span>
                    </div>
                </div>
                
                <!-- Live Clock -->
                <div class="bg-gray-800 text-white rounded-lg px-4 py-3 min-w-[180px] text-center clock">
                    <div id="live-clock" class="text-xl font-bold tracking-wider" style="font-size: 20px;">2:45:59 PM</div>
                    <div id="current-date" class="text-xs text-gray-400" style="font-size: 13px;">May 25, 2025</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area for Orders -->
    <div class="main-content pt-20 pb-1 px-6">
        <div class="max-w-6xl mx-auto">
            <div class="mt-8 mb-4">
                <h2 class="text-2xl font-bold text-white">Kitchen Orders</h2>
                <p class="text-gray-400">Manage and prepare orders from staff</p>
            </div>

            <!-- Orders Container with Pagination -->
            <div class="flex items-center justify-center gap-6">
                <!-- Previous Button (Left Side) -->
                <button id="prev-page" class="pagination-arrow" disabled>
                    <i class="fas fa-chevron-left"></i>
                </button>

                <!-- Order Groups Container (Center) -->
                <div class="order-groups-container flex-1">
                    @if(isset($groupedOrders) && count($groupedOrders) > 0)
                        @php
                            // Convert grouped orders to array for easier manipulation
                            $ordersArray = [];
                            foreach($groupedOrders as $orderID => $orderGroup) {
                                $ordersArray[] = [
                                    'orderID' => $orderID,
                                    'orderGroup' => $orderGroup,
                                    'status' => $orderGroup[0]->status ?? 'pending',
                                    'cookingStatus' => $orderGroup[0]->cookingStatus ?? 'In Progress',
                                    'paymentNumber' => $orderGroup[0]->paymentNumber,
                                    'orderType' => $orderGroup[0]->orderType,
                                    'paymentMethod' => $orderGroup[0]->paymentMethod,
                                    'paymentProcessedAt' => $orderGroup[0]->paymentProcessedAt
                                ];
                            }
                            
                            // Split orders into groups of 4
                            $orderGroups = array_chunk($ordersArray, 3);
                        @endphp
                        
                        <div id="order-groups">
                            @foreach($orderGroups as $groupIndex => $group)
                            <div class="order-group @if($groupIndex > 0) hidden @endif" data-group-index="{{ $groupIndex }}">
                                @foreach($group as $order)
                                <div class="order-card bg-gray-800 rounded-xl p-5 border border-gray-700 flex flex-col" data-order-id="{{ $order['orderID'] }}" data-payment-number="{{ $order['paymentNumber'] }}">
                                    <!-- Order Header -->
                                    <div class="mb-2">
                                        <!-- Top Row: Order Number, Payment Badge, Status, and Time -->
                                        {{-- <div class="flex items-center justify-between mb-3"> --}}
                                            <div class="flex items-center justify-between mb-1">
                                                <div class="flex items-center gap-2">
                                                <span class="text-white font-bold text-lg">Order #{{ $order['paymentNumber'] }}</span>
                                                <span class="order-status 
                                                    @if($order['status'] == 'pending') status-pending
                                                    @elseif($order['status'] == 'preparing') status-preparing
                                                    @elseif($order['status'] == 'ready') status-ready
                                                    @else status-completed @endif">
                                                    {{ ucfirst($order['status']) }}
                                                </span>
                                                </div>
                                                <div class="text-right">
                                                    <div class="text-gray-400 text-xs mb-1 mr-6">Time</div>
                                                    <div class="text-white font-semibold text-lg">{{ date('h:i A', strtotime($order['paymentProcessedAt'])) }}</div>
                                                </div> 
                                            </div>
                                        {{-- </div> --}}
                                        
                                        <!-- Bottom Row: ID and Badges -->
                                        <div class="flex items-center gap-3 mb-1">
                                            <span class="text-gray-300 text-sm">ID: {{ $order['orderID'] }}</span>
                                            <span class="px-3 py-1 rounded-[4px] font-semibold 
                                                @if($order['orderType'] == 'dine-in') bg-blue-900 text-blue-200
                                                @else bg-green-900 text-green-200 @endif">
                                                {{ ucfirst($order['orderType']) }}
                                            </span>
                                            <span class="px-3 py-1 rounded-[4px] font-semibold
                                                @if($order['paymentMethod'] == 'cash') payment-badge-cash
                                                @else payment-badge-electronic @endif">
                                                {{ ucfirst($order['paymentMethod']) }}
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="payment-number-display text-[14px] font-semibold">
                                                Payment: {{ $order['paymentNumber'] }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Order Items -->
                                    <div class="order-items-list flex-1">
                                        <div class="grid grid-cols-1 gap-2 max-h-56 overflow-y-auto pr-2">
                                            @foreach($order['orderGroup'] as $item)
                                            <div class="flex items-center justify-between py-2 px-3 border-b border-gray-700 last:border-b-0 bg-gray-700/30 rounded">
                                                <div class="flex items-center flex-1">
                                                    <span class="product-quantity-small">{{ $item->quantity }}x</span>
                                                    <span class="text-white text-sm flex-grow">{{ $item->productName }}</span>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Order Summary -->
                                    <div class="mt-4 pt-4 border-t border-gray-700 mt-auto">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-gray-400">Payment Number:</span>
                                            <span class="text-white font-bold">{{ $order['paymentNumber'] }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-400">Total Items:</span>
                                            <span class="text-white font-bold">{{ count($order['orderGroup']) }}</span>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="mt-6 flex gap-3">
                                        <button 
                                            class="action-btn begin-preparing-btn flex-1"
                                            onclick="beginPreparing('{{ $order['orderID'] }}', '{{ $order['paymentNumber'] }}')"
                                            @if($order['status'] != 'pending') disabled @endif
                                        >
                                            <i class="fas fa-utensils mr-2"></i>
                                            @if($order['status'] == 'preparing')
                                                Cooking...
                                            @else
                                                Begin Preparing
                                            @endif
                                        </button>
                                        
                                        <button 
                                            class="action-btn send-to-staff-btn flex-1"
                                            onclick="sendToStaff('{{ $order['orderID'] }}', '{{ $order['paymentNumber'] }}')"
                                            @if($order['status'] != 'preparing') disabled @endif
                                        >
                                            <i class="fas fa-paper-plane mr-2"></i>
                                            Send to Staff
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                                
                                <!-- Fill empty slots if less than 3 orders in this group -->
                                @if(count($group) < 3)
                                    @for($i = count($group); $i < 3; $i++)
                                    <div class="bg-gray-800/30 rounded-xl p-5 border border-gray-700/50 border-dashed flex items-center justify-center min-h-[300px]">
                                        <div class="text-center">
                                            <i class="fas fa-clipboard-list text-4xl text-gray-600 mb-3"></i>
                                            <p class="text-gray-500">No order</p>
                                        </div>
                                    </div>
                                    @endfor
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-order-message">
                            <i class="fas fa-utensils text-4xl text-gray-600 mb-3"></i>
                            <p class="empty-order-text text-lg">No pending orders at the moment.</p>
                        </div>
                    @endif
                </div>

                <!-- Next Button (Right Side) -->
                <button id="next-page" class="pagination-arrow" @if(isset($groupedOrders) && count($groupedOrders) > 0 && count(array_chunk(array_values($groupedOrders), 3)) <= 1) disabled @endif>
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <!-- Pagination Info (Centered Below) -->
            @if(isset($groupedOrders) && count($groupedOrders) > 0 && count(array_chunk(array_values($groupedOrders), 3)) > 1)
            <div class="flex justify-center mt-6 mb-4">
                <div class="pagination-info">
                    <span id="current-page">1</span> / <span id="total-pages">{{ count(array_chunk(array_values($groupedOrders), 3)) }}</span>
                </div>
            </div>
            @endif
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
        Redirecting to completed orders...
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

        // Order Tracker Button - Redirect to completed orders page
        document.getElementById('order-tracker-btn').addEventListener('click', function(e) {
            e.preventDefault();
            
            // Show status message
            const statusMessage = document.getElementById('status-message');
            statusMessage.textContent = "Opening completed orders...";
            statusMessage.classList.remove('opacity-0', 'translate-y-4');
            statusMessage.classList.add('opacity-100', 'translate-y-0');
            
            // Navigate after a brief delay
            setTimeout(() => {
                window.location.href = this.href;
            }, 500);
        });

        // Pagination functionality
        let currentPage = 1;
        const orderGroups = document.querySelectorAll('.order-group');
        const totalPages = orderGroups.length;
        const prevButton = document.getElementById('prev-page');
        const nextButton = document.getElementById('next-page');
        const currentPageElement = document.getElementById('current-page');
        const totalPagesElement = document.getElementById('total-pages');
        const pageDots = document.querySelectorAll('.page-dot');

        // Initialize pagination
        if (totalPagesElement) {
            totalPagesElement.textContent = totalPages;
        }

        function updatePagination() {
            // Update current page display
            if (currentPageElement) {
                currentPageElement.textContent = currentPage;
            }
            
            // Update order groups visibility
            orderGroups.forEach((group, index) => {
                if (index + 1 === currentPage) {
                    group.classList.remove('hidden');
                } else {
                    group.classList.add('hidden');
                }
            });
            
            // Update arrow buttons
            if (prevButton) {
                prevButton.disabled = currentPage === 1;
            }
            if (nextButton) {
                nextButton.disabled = currentPage === totalPages;
            }
            
            // Update page dots
            pageDots.forEach((dot, index) => {
                if (index + 1 === currentPage) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
            
            // Update order count
            updateOrderCount();
        }

        // Previous page button
        if (prevButton) {
            prevButton.addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    updatePagination();
                }
            });
        }

        // Next page button
        if (nextButton) {
            nextButton.addEventListener('click', () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    updatePagination();
                }
            });
        }

        // Page dot click events
        pageDots.forEach(dot => {
            dot.addEventListener('click', () => {
                const pageNumber = parseInt(dot.getAttribute('data-page'));
                if (pageNumber && pageNumber !== currentPage) {
                    currentPage = pageNumber;
                    updatePagination();
                }
            });
        });

        // Order Action Functions
        function beginPreparing(orderID, paymentNumber) {
            // Update cookingStatus to 'Cooking' via AJAX
            updateCookingStatus(orderID, 'Cooking');
            
            // Update UI immediately for better UX
            updateOrderStatus(orderID, 'preparing');
            
            // Show status message
            showStatusMessage(`Order #${paymentNumber} is now being prepared`, 'bg-blue-600');
        }

        function sendToStaff(orderID, paymentNumber) {
            // Update cookingStatus to 'Completed' via AJAX (not 'Product Ready')
            updateCookingStatus(orderID, 'Completed');
            
            // Update UI immediately for better UX - mark as completed and remove
            markOrderAsCompleted(orderID, paymentNumber);
            
            // Show status message
            showStatusMessage(`Order #${paymentNumber} completed and sent to staff`, 'bg-green-600');
            
            // Update the completed orders count
            updateCompletedOrdersCount();
        }

        // Function to update cookingStatus via AJAX
        function updateCookingStatus(orderID, status) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch('/kitchen/update-cooking-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    orderID: orderID,
                    cookingStatus: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.error('Failed to update cooking status:', data.message);
                    // Revert UI changes if update failed
                    const orderCard = document.querySelector(`.order-card[data-order-id="${orderID}"]`);
                    const paymentNumber = orderCard ? orderCard.getAttribute('data-payment-number') : orderID;
                    showStatusMessage(`Failed to update order #${paymentNumber}`, 'bg-red-600');
                }
            })
            .catch(error => {
                console.error('Error updating cooking status:', error);
                const orderCard = document.querySelector(`.order-card[data-order-id="${orderID}"]`);
                const paymentNumber = orderCard ? orderCard.getAttribute('data-payment-number') : orderID;
                showStatusMessage(`Error updating order #${paymentNumber}`, 'bg-red-600');
            });
        }

        function markOrderAsCompleted(orderID, paymentNumber) {
            // Find the order card
            const orderCard = document.querySelector(`.order-card[data-order-id="${orderID}"]`);
            if (!orderCard) return;
            
            // Update status badge
            const statusBadge = orderCard.querySelector('.order-status');
            if (statusBadge) {
                statusBadge.textContent = 'Completed';
                statusBadge.className = 'order-status status-completed';
            }
            
            // Update button states
            const beginBtn = orderCard.querySelector('.begin-preparing-btn');
            const sendBtn = orderCard.querySelector('.send-to-staff-btn');
            
            if (beginBtn) {
                beginBtn.disabled = true;
                beginBtn.innerHTML = '<i class="fas fa-utensils mr-2"></i>Completed';
            }
            if (sendBtn) {
                sendBtn.disabled = true;
                sendBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Sent!';
            }
            
            // Add removing animation class
            orderCard.classList.add('order-removing');
            
            // Remove card after animation
            setTimeout(() => {
                // Check if there are other cards in this group
                const currentGroup = orderCard.closest('.order-group');
                orderCard.remove();
                
                // Update order count
                updateOrderCount();
                
                // Check if this group is now empty
                const remainingCards = currentGroup.querySelectorAll('.order-card');
                if (remainingCards.length === 0) {
                    // Remove placeholder cards if they exist
                    const placeholders = currentGroup.querySelectorAll('.bg-gray-800\\/30');
                    placeholders.forEach(placeholder => placeholder.remove());
                    
                    // Add a message for empty group
                    const emptyMessage = document.createElement('div');
                    emptyMessage.className = 'col-span-4 empty-order-message';
                    emptyMessage.innerHTML = `
                        <i class="fas fa-check-circle text-4xl text-green-600 mb-3"></i>
                        <p class="empty-order-text text-lg">All orders completed in this group!</p>
                    `;
                    currentGroup.appendChild(emptyMessage);
                    
                    // Recalculate pagination if needed
                    const totalGroups = document.querySelectorAll('.order-group').length;
                    if (totalGroups > 1) {
                        setTimeout(() => {
                            // If all groups are empty, reload the page
                            const allEmpty = Array.from(document.querySelectorAll('.order-group')).every(group => {
                                return group.querySelectorAll('.order-card').length === 0;
                            });
                            
                            if (allEmpty) {
                                location.reload();
                            }
                        }, 1000);
                    }
                }
            }, 500); // Match animation duration
        }

        function updateOrderStatus(orderID, status) {
            // Find the order card
            const orderCard = document.querySelector(`.order-card[data-order-id="${orderID}"]`);
            if (!orderCard) return;
            
            // Update status badge
            const statusBadge = orderCard.querySelector('.order-status');
            if (statusBadge) {
                statusBadge.textContent = ucfirst(status);
                statusBadge.className = 'order-status ' + 
                    (status === 'pending' ? 'status-pending' :
                     status === 'preparing' ? 'status-preparing' :
                     status === 'ready' ? 'status-ready' : 'status-completed');
            }
            
            // Update button states
            const beginBtn = orderCard.querySelector('.begin-preparing-btn');
            const sendBtn = orderCard.querySelector('.send-to-staff-btn');
            
            if (status === 'preparing') {
                if (beginBtn) {
                    beginBtn.disabled = true;
                    beginBtn.innerHTML = '<i class="fas fa-utensils mr-2"></i>Cooking...';
                }
                if (sendBtn) {
                    sendBtn.disabled = false;
                }
            }
        }

        function showStatusMessage(message, bgColor) {
            const statusMessage = document.getElementById('status-message');
            statusMessage.textContent = message;
            statusMessage.className = `fixed bottom-4 right-4 ${bgColor} text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transform translate-y-4 transition-all duration-300 z-50`;
            
            setTimeout(() => {
                statusMessage.classList.remove('opacity-0', 'translate-y-4');
                statusMessage.classList.add('opacity-100', 'translate-y-0');
            }, 10);
            
            setTimeout(() => {
                statusMessage.classList.remove('opacity-100', 'translate-y-0');
                statusMessage.classList.add('opacity-0', 'translate-y-4');
            }, 3000);
        }

        function updateOrderCount() {
            // Count only visible order cards
            const visibleGroups = document.querySelectorAll('.order-group:not(.hidden)');
            let totalOrders = 0;
            
            visibleGroups.forEach(group => {
                const cards = group.querySelectorAll('.order-card');
                totalOrders += cards.length;
            });
            
            // You might want to update a different counter for active orders
            // This function currently updates the active orders count
        }

        function updateCompletedOrdersCount() {
            // Update the completed orders badge count
            fetch('/kitchen/completed-orders-count')
                .then(response => response.json())
                .then(data => {
                    if (data.count !== undefined) {
                        const orderCountBadge = document.getElementById('order-count');
                        if (orderCountBadge) {
                            orderCountBadge.textContent = data.count;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error updating completed orders count:', error);
                });
        }

        function ucfirst(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        // Initial pagination and order count update
        if (orderGroups.length > 0) {
            updatePagination();
        } else {
            updateOrderCount();
        }

        // Initial update of completed orders count
        updateCompletedOrdersCount();
    </script>
</body>
</html>
</html>