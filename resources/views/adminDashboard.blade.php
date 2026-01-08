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
        <div class="flex-1 flex flex-col mr-8 ml-8 mb-8 mt-5 overflow-y-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
                <div class="bg-white p-4 rounded-xl shadow-sm flex flex-col justify-between h-24">
                    <div class="flex justify-between items-start">
                        <h2 class="text-xl font-bold text-gray-800">₱{{ number_format($todaysSales ?? 18750, 2) }}</h2> 
                        <div class="p-2 bg-orange-100 rounded-lg text-orange-500">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><path fill="currentColor" d="M30 6V4h-3V2h-2v2h-1c-1.103 0-2 .898-2 2v2c0 1.103.897 2 2 2h4v2h-6v2h3v2h2v-2h1c1.103 0 2-.897 2-2v-2c0-1.102-.897-2-2-2h-4V6zm-6 14v2h2.586L23 25.586l-2.292-2.293a1 1 0 0 0-.706-.293H20a1 1 0 0 0-.706.293L14 28.586L15.414 30l4.587-4.586l2.292 2.293a1 1 0 0 0 1.414 0L28 23.414V26h2v-6zM4 30H2v-5c0-3.86 3.14-7 7-7h6c1.989 0 3.89.85 5.217 2.333l-1.49 1.334A5 5 0 0 0 15 20H9c-2.757 0-5 2.243-5 5zm8-14a7 7 0 1 0 0-14a7 7 0 0 0 0 14m0-12a5 5 0 1 1 0 10a5 5 0 0 1 0-10"/></svg>
                        </div>
                    </div>
                    <span class="text-gray-900 text-m font-medium">Today's Sales</span>
                </div>

                <div class="bg-white p-4 rounded-xl shadow-sm flex flex-col justify-between h-24">
                    <div class="flex justify-between items-start">
                        <h2 class="text-xl font-bold text-gray-800">{{ $mealsServed ?? 125 }}</h2>
                        <div class="p-2 bg-orange-100 rounded-lg text-orange-500">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="1024" height="1024" viewBox="0 0 1024 1024"><path fill="currentColor" d="M128 352.576V352a288 288 0 0 1 491.072-204.224a192 192 0 0 1 274.24 204.48a64 64 0 0 1 57.216 74.24C921.6 600.512 850.048 710.656 736 756.992V800a96 96 0 0 1-96 96H384a96 96 0 0 1-96-96v-43.008c-114.048-46.336-185.6-156.48-214.528-330.496A64 64 0 0 1 128 352.64zm64-.576h64a160 160 0 0 1 320 0h64a224 224 0 0 0-448 0m128 0h192a96 96 0 0 0-192 0m439.424 0h68.544A128.256 128.256 0 0 0 704 192c-15.36 0-29.952 2.688-43.52 7.616c11.328 18.176 20.672 37.76 27.84 58.304A64.128 64.128 0 0 1 759.424 352M672 768H352v32a32 32 0 0 0 32 32h256a32 32 0 0 0 32-32zm-342.528-64h365.056c101.504-32.64 165.76-124.928 192.896-288H136.576c27.136 163.072 91.392 255.36 192.896 288"/></svg>
                        </div>
                    </div>
                    <span class="text-gray-900 text-m font-medium">Meals Served</span>
                </div>

                <div class="bg-white p-4 rounded-xl shadow-sm flex flex-col justify-between h-24">
                    <div class="flex justify-between items-start">
                        <h2 class="text-xl font-bold text-gray-800">{{ $activeOrders ?? 13 }}</h2>
                        <div class="p-2 bg-orange-100 rounded-lg text-orange-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                    </div>
                    <span class="text-gray-900 text-m font-medium">Menu Products</span>
                </div>

                <div class="bg-white p-4 rounded-xl shadow-sm flex flex-col justify-between h-24">
                    <div class="flex justify-between items-start">
                         <h2 class="text-xl font-bold text-gray-800">{{ $lowStockCount ?? 4 }}</h2>
                        <div class="p-2 bg-orange-100 rounded-lg text-orange-500">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        </div>
                    </div>
                    <span class="text-gray-900 text-m font-medium">Low Stock Items</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-5">
                <div class="bg-white p-4 rounded-xl shadow-sm lg:col-span-2">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-4">
                            <h3 class="font-bold text-gray-800">Sales</h3>
                            <div class="flex items-center gap-2 text-s">
                                <span class="w-3 h-3 rounded-full bg-blue-500"></span> <span class="text-gray-900">This Month</span>
                                <span class="w-3 h-3 rounded-full bg-orange-400 ml-2"></span> <span class="text-gray-900">Previous Month</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <select class="bg-gray-50 border-none text-s text-gray-900 rounded-md focus:ring-0">
                                <option>Monthly</option>
                                <option>Yearly</option>
                            </select>
                            <a href="#" class="bg-orange-500 hover:bg-orange-600 text-white text-sm px-4 py-2 rounded-lg transition">
                                Order History
                            </a>
                        </div>
                    </div>
                    <div class="relative h-40 w-full">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl shadow-sm">
                    <h3 class="font-bold text-gray-800 mb-4">Popular Category</h3>
                    <div class="relative h-40 w-full flex justify-center">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                
                <div class="bg-white p-4 rounded-xl shadow-sm lg:col-span-2">
                    <h3 class="font-bold text-gray-800 mb-4">Top Selling Items</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600">
                            <thead>
                                <tr class="border-b border-gray-100 text-gray-900 font-medium">
                                    <th class="pb-2 pl-2">#</th>
                                    <th class="pb-2">Item Name</th>
                                    <th class="pb-2">Category</th>
                                    <th class="pb-2 text-center">Items Sold</th>
                                    <th class="pb-2 text-right pr-2">Revenue</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                {{-- Placeholder Static Data: Wrap this in @foreach later --}}
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 pl-2 font-bold text-gray-800">1</td>
                                    <td class="py-2 font-medium text-gray-800">Beef</td>
                                    <td class="py-2">Meat</td>
                                    <td class="py-2 text-center">357</td> 
                                    <td class="py-2 text-right pr-2">₱1,450.00</td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 pl-2 font-bold text-gray-800">2</td>
                                    <td class="py-2 font-medium text-gray-800">Oxtail</td>
                                    <td class="py-2">Meat</td>
                                    <td class="py-2 text-center">331</td>
                                    <td class="py-2 text-right pr-2">₱2,100.00</td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 pl-2 font-bold text-gray-800">3</td>
                                    <td class="py-2 font-medium text-gray-800">Garlic</td>
                                    <td class="py-2">Vegetable</td>
                                    <td class="py-2 text-center">210</td>
                                    <td class="py-2 text-right pr-2">₱400.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600">
                            <thead>
                                <tr class="border-b border-gray-100 text-gray-900 font-medium">
                                    <th class="pb-2 pl-2">Product Name</th>
                                    <th class="pb-2">Stock</th>
                                    <th class="pb-2 text-right pr-2">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                {{-- Placeholder Static Data: Wrap this in @foreach later --}}
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 pl-2 font-medium text-gray-800">Beef</td>
                                    <td class="py-2">15 kg</td>
                                    <td class="py-2 text-right pr-2 text-green-500 font-medium">In Stock</td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 pl-2 font-medium text-gray-800">Oxtail</td>
                                    <td class="py-2">5 pcs</td>
                                    <td class="py-2 text-right pr-2 text-yellow-500 font-medium">Low Stock</td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 pl-2 font-medium text-gray-800">Garlic</td>
                                    <td class="py-2">1 kg</td>
                                    <td class="py-2 text-right pr-2 text-yellow-500 font-medium">Low Stock</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // 1. Sales Line Chart
        const ctxSales = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctxSales, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [
                    {
                        label: 'This Month',
                        // NOTE: When DB is ready, replace the array below with: {{ json_encode($salesDataCurrent ?? []) }}
                        data: [12, 19, 3, 5, 2, 3, 20], 
                        borderColor: '#3B82F6', 
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Previous Month',
                        // NOTE: When DB is ready, replace the array below with: {{ json_encode($salesDataPrev ?? []) }}
                        data: [15, 12, 6, 8, 5, 8, 15], 
                        borderColor: '#F97316', 
                        borderDash: [5, 5],
                        tension: 0.4,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false } 
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f3f4f6' } },
                    x: { grid: { color: 'transparent' } }
                }
            }
        });

        // 2. Category Pie Chart
        const ctxCategory = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(ctxCategory, {
            type: 'pie',
            data: {
                labels: ['Beverages', 'Bakery', 'Groceries', 'Meat', 'Seafood'],
                datasets: [{
                    label: 'Popular Categories',
                    data: [300, 50, 100, 75, 125],
                    backgroundColor: [
                        '#3B82F6',
                        '#F97316',
                        '#4ADE80',
                        '#9333EA',
                        '#F43F5E'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>
@endsection