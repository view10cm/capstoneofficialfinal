@extends('base')

@section('title', 'Caffe Arabica - Order History')

@section('content')
    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar -->
        @include('admin.adminSidebar')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-y-auto">
            <!-- Header -->
            <div class="bg-white p-5 shadow-sm shadow-gray-500/50">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:space-x-6">
                        <div class="text-center lg:text-left">
                            <h1 class="text-3xl font-bold text-gray-900">Order History</h1>
                            <p class="text-gray-500 text-sm">View and manage completed orders</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order History Table Container -->
            <div class="flex-1 p-5">
                <!-- Header with Controls -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-3 border-b border-gray-200">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <!-- Left side: Order History Icon and Text -->
                            <div class="flex items-center mb-4 lg:mb-0">
                                <div class="bg-white p-3 rounded-lg mr-3">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke="#E67809" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-800 ml-4">Order History</h2>
                                </div>
                            </div>

                            <!-- Right side: Search and Buttons -->
                            <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4">
                                <!-- Search Bar -->
                                <form method="GET" action="{{ route('admin.order-history') }}" class="w-full sm:w-auto">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <input type="text" 
                                               name="search" 
                                               placeholder="Search by Order ID or Product..." 
                                               value="{{ request('search') }}"
                                               class="pl-10 pr-4 py-2.5 w-full sm:w-64 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:outline-none transition-colors">
                                    </div>
                                </form>

                                <!-- Clear Search Button -->
                                @if(request('search'))
                                    <a href="{{ route('admin.order-history') }}" 
                                       class="flex items-center justify-center px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all duration-200">
                                        Clear Search
                                    </a>
                                @endif

                                <!-- Back to Dashboard Button -->
                                <a href="{{ route('admin.dashboard') }}" 
                                   class="flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-amber-600 to-amber-700 text-white rounded-lg font-medium hover:from-amber-700 hover:to-amber-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 shadow-sm hover:shadow transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Back to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Table Container -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Order ID
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Product
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Quantity
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date Completed
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @if($orders->count() > 0)
                                    @foreach($orders as $order)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-3 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-900">{{ $order->orderID }}</td>
                                            <td class="px-3 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-800">{{ $order->productName }}</td>
                                            <td class="px-3 py-4 whitespace-nowrap text-center text-sm text-gray-600">{{ $order->quantity }} items</td>
                                            <td class="px-3 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-800">₱{{ number_format($order->totalPrice, 2) }}</td>
                                            <td class="px-3 py-4 whitespace-nowrap text-center text-sm text-gray-600">{{ \Carbon\Carbon::parse($order->paymentProcessedAt)->format('Y-m-d h:i A') }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="py-12 px-6 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                                    @if(request('search'))
                                                        No orders found
                                                    @else
                                                        No order history found
                                                    @endif
                                                </h3>
                                                <p class="text-gray-500">
                                                    @if(request('search'))
                                                        No orders found matching your search criteria.
                                                    @else
                                                        Order history will appear here once orders are completed.
                                                    @endif
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="px-5 py-3 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <!-- Previous Button -->
                                @if ($orders->onFirstPage())
                                    <button class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-400 cursor-not-allowed disabled:opacity-50" disabled>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                        </svg>
                                        <span>Previous</span>
                                    </button>
                                @else
                                    <a href="{{ $orders->previousPageUrl() }}{{ request('search') ? '&search=' . request('search') : '' }}" 
                                       class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 hover:text-amber-700 transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                        </svg>
                                        <span>Previous</span>
                                    </a>
                                @endif
                                
                                <!-- Page Info -->
                                <div class="text-sm text-gray-700">
                                    Page <span class="font-semibold text-amber-700">{{ $orders->currentPage() }}</span> of 
                                    <span class="font-semibold">{{ $orders->lastPage() }}</span>
                                </div>
                                
                                <!-- Next Button -->
                                @if ($orders->hasMorePages())
                                    <a href="{{ $orders->nextPageUrl() }}{{ request('search') ? '&search=' . request('search') : '' }}" 
                                       class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 hover:text-amber-700 transition-all duration-200">
                                        <span>Next</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                @else
                                    <button class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-400 cursor-not-allowed disabled:opacity-50" disabled>
                                        <span>Next</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection