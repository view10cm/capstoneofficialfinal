@extends('base')

@section('title', 'Caffe Arabica - Dashboard')

@section('content')
    <div class="flex min-h-screen bg-gray-50 font-sans">
        @include('admin.adminSidebar')

        <div class="flex-1 flex flex-col overflow-y-auto">

            <div class="bg-white p-5 shadow-sm shadow-gray-500/50">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
                    <p class="text-gray-500 text-sm">Inventory and Sales Summary</p>
                </div>
            </div>

            <!-- Dashboard Content -->
            <div class="p-6">
                <!-- Overall Sales Card -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <!-- Overall Sales Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Overall Sales</p>
                                <h3 class="text-3xl font-bold text-gray-900 mt-2">
                                    ₱{{ number_format($overallSales->overall_sales ?? 0, 2) }}
                                </h3>
                            </div>
                            <div class="bg-green-100 p-4 rounded-full">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Total Revenue
                            </span>
                        </div>
                    </div>

                    <!-- Meals Served Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Meals Served</p>
                                <h3 class="text-3xl font-bold text-gray-900 mt-2" id="meals-served-today">
                                    {{ $mealsData['overall_meals'] ?? 0 }}
                                </h3>
                            </div>
                            <div class="bg-orange-100 p-4 rounded-full">
                                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Total Meals
                            </span>
                        </div>
                    </div>

                    <!-- Menu Products Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Menu Products</p>
                                <h3 class="text-3xl font-bold text-gray-900 mt-2" id="menu-products-count">
                                    {{ $menuProductsData['active_menu'] ?? 0 }}
                                </h3>
                            </div>
                            <div class="bg-blue-100 p-4 rounded-full">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                <span id="menu-products-badge">Current Products</span>
                            </span>
                        </div>
                    </div>

                    <!-- Low Stock Items Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Low Stock Items</p>
                                <h3 class="text-3xl font-bold text-gray-900 mt-2" id="low-stock-count">
                                    {{ $lowStockData['low_stock_count'] ?? 0 }}
                                </h3>
                            </div>
                            <div class="bg-red-100 p-4 rounded-full">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('admin.inventory') }}" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 hover:bg-red-200 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                View Details
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Sales Chart Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Monthly Sales Overview</h3>
                            <p class="text-gray-500 text-sm">Overall sales revenue for the last 12 months</p>
                        </div>
                        <div>
                            <button id="refreshChartBtn" class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-200">
                                Refresh Data
                            </button>
                        </div>
                    </div>
                    
                    <!-- Chart Container -->
                    <div class="h-96 relative">
                        <canvas id="salesChart"></canvas>
                        <div id="chartLoading" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-80">
                            <div class="text-center">
                                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                                <p class="text-gray-500">Loading sales data...</p>
                            </div>
                        </div>
                        <div id="chartError" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-80 hidden">
                            <div class="text-center">
                                <svg class="w-16 h-16 text-red-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-gray-700 font-medium">Unable to load sales data</p>
                                <p class="text-gray-500 text-sm mt-1">Please try again later</p>
                                <button id="retryChartBtn" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                    Retry
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Summary -->
                    <div id="chartStats" class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4 hidden">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">Total Sales (12 Months)</p>
                            <h4 class="text-xl font-bold text-gray-900" id="totalSalesStat">₱0.00</h4>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">Average Monthly</p>
                            <h4 class="text-xl font-bold text-gray-900" id="avgSalesStat">₱0.00</h4>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">Highest Month</p>
                            <h4 class="text-xl font-bold text-gray-900" id="maxSalesStat">₱0.00</h4>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">Growth Rate</p>
                            <h4 class="text-xl font-bold text-gray-900" id="growthRateStat">0%</h4>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Recent Orders</h3>
                        <div class="space-y-4">
                            <!-- Placeholder for recent orders -->
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                <p class="text-gray-500">No recent orders</p>
                                <p class="text-gray-400 text-sm">Connect to order system to display data</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Inventory Alerts</h3>
                        <div class="space-y-4">
                            @if($lowStockData['low_stock_count'] > 0)
                                <!-- Low stock items list -->
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-900">Low Stock Items</span>
                                        <span class="text-xs font-semibold px-2 py-1 rounded-full bg-red-100 text-red-800">
                                            {{ $lowStockData['low_stock_count'] }} items
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        There are {{ $lowStockData['low_stock_count'] }} ingredients that are low in stock.
                                    </p>
                                    <div class="pt-2">
                                        <a href="{{ route('admin.inventory') }}" class="inline-flex items-center text-sm font-medium text-red-600 hover:text-red-800">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Go to Inventory to restock
                                        </a>
                                    </div>
                                </div>
                            @else
                                <!-- No inventory alerts -->
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 text-green-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-gray-700 font-medium">All items are sufficiently stocked</p>
                                    <p class="text-gray-500 text-sm mt-1">No inventory alerts at this time</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Load Chart.js with integrity check to avoid tracking prevention -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" integrity="sha384-RRr8fZgFyWlTyk3JOjIyT6Hnw60cOWbqgZLk0VvB4qC5v2w/mNkNx7f6p6VhZ7qY" crossorigin="anonymous"></script>
    
    <script>
    // Make functions global so they can be called from onclick attributes
    window.salesChart = null;
    
    // Function to show/hide loading and error states
    window.showLoading = function() {
        document.getElementById('chartLoading').classList.remove('hidden');
        document.getElementById('chartError').classList.add('hidden');
    }
    
    window.hideLoading = function() {
        document.getElementById('chartLoading').classList.add('hidden');
    }
    
    window.showError = function() {
        document.getElementById('chartError').classList.remove('hidden');
        document.getElementById('chartLoading').classList.add('hidden');
    }
    
    // Function to initialize an empty chart
    window.initializeEmptyChart = function() {
        const ctx = document.getElementById('salesChart');
        if (!ctx) {
            console.error('Canvas element not found!');
            return;
        }
        
        // Destroy existing chart if it exists
        if (window.salesChart) {
            window.salesChart.destroy();
        }
        
        // Create empty chart
        window.salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Monthly Sales',
                    data: [],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '₱' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        },
                        title: {
                            display: true,
                            text: 'Sales Amount'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    }
                }
            }
        });
    }
    
    // Function to update chart with real data
    window.updateChartWithData = function(labels, data) {
        if (!window.salesChart) {
            console.error('Chart not initialized!');
            return;
        }
        
        // Update chart data
        window.salesChart.data.labels = labels;
        window.salesChart.data.datasets[0].data = data;
        
        // Update and render
        window.salesChart.update();
        
        console.log('Chart updated with data');
    }
    
    // Function to update statistics
    window.updateStatistics = function(stats) {
        document.getElementById('totalSalesStat').textContent = 
            '₱' + stats.total_sales.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        
        document.getElementById('avgSalesStat').textContent = 
            '₱' + stats.average_sales.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        
        document.getElementById('maxSalesStat').textContent = 
            '₱' + stats.max_sales.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        
        document.getElementById('growthRateStat').textContent = 
            stats.growth_rate.toFixed(1) + '%';
        
        // Show statistics section
        document.getElementById('chartStats').classList.remove('hidden');
        
        // Color code growth rate
        const growthElement = document.getElementById('growthRateStat');
        if (stats.growth_rate > 0) {
            growthElement.classList.add('text-green-600');
            growthElement.classList.remove('text-red-600', 'text-gray-600');
        } else if (stats.growth_rate < 0) {
            growthElement.classList.add('text-red-600');
            growthElement.classList.remove('text-green-600', 'text-gray-600');
        } else {
            growthElement.classList.add('text-gray-600');
            growthElement.classList.remove('text-green-600', 'text-red-600');
        }
    }
    
    // Function to load sales data
    window.loadSalesData = function() {
        showLoading();
        console.log('Loading sales data...');
        
        fetch('{{ route("admin.sales.monthly") }}')
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Data received:', data);
                hideLoading();
                
                if (data.success) {
                    // Extract data from response
                    const labels = data.data.labels;
                    const salesData = data.data.datasets[0].data;
                    const statistics = data.data.statistics;
                    
                    // Update chart with data
                    updateChartWithData(labels, salesData);
                    
                    // Update statistics
                    updateStatistics(statistics);
                } else {
                    showError();
                    console.error('Error in response:', data.message);
                }
            })
            .catch(error => {
                hideLoading();
                showError();
                console.error('Error loading chart data:', error);
            });
    }
    
    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing chart...');
        
        // Check if Chart.js is loaded
        if (typeof Chart === 'undefined') {
            console.error('Chart.js is not loaded!');
            showError();
            return;
        }
        
        console.log('Chart.js is loaded, version:', Chart.version);
        
        // Initialize empty chart first
        initializeEmptyChart();
        
        // Then load data
        loadSalesData();
        
        // Set up event listeners
        document.getElementById('refreshChartBtn').addEventListener('click', loadSalesData);
        document.getElementById('retryChartBtn').addEventListener('click', loadSalesData);
        
        // Set up intervals for refreshing data
        setInterval(loadSalesData, 300000); // Refresh every 5 minutes
        
        // Function to refresh other dashboard data
        function refreshMealsData() {
            fetch('{{ route("admin.meals.data") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('meals-served-today').textContent = data.data.today_meals;
                    }
                })
                .catch(error => console.error('Error refreshing meals data:', error));
        }
        
        function refreshMenuProductsData() {
            fetch('{{ route("admin.menu-products.data") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('menu-products-count').textContent = data.data.active_menu;
                    }
                })
                .catch(error => console.error('Error refreshing menu products data:', error));
        }
        
        function refreshLowStockData() {
            fetch('{{ route("admin.low-stock.count") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('low-stock-count').textContent = data.data.low_stock_count;
                    }
                })
                .catch(error => console.error('Error refreshing low stock data:', error));
        }
        
        // Set intervals for refreshing other data
        setInterval(refreshMealsData, 30000);
        setInterval(refreshMenuProductsData, 30000);
        setInterval(refreshLowStockData, 30000);
        
        // Initial refresh of other data
        refreshMealsData();
        refreshMenuProductsData();
        refreshLowStockData();
    });
    </script>
    <script src="{{ asset('js/chart.js') }}"></script>
@endsection