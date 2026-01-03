@extends('base')

@section('title', 'Caffe Arabica - Order History')

@section('content')
    <div class="flex min-h-screen bg-gray-50 font-sans">
        @include('admin.adminSidebar')

        <div class="flex-1 flex flex-col p-8 overflow-y-auto">
            
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
                    <p class="text-gray-500 text-sm">Inventory and sales summary</p>
                </div>
                
                <div class="relative w-96">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" placeholder="Search..." class="w-full py-2 pl-10 pr-4 bg-gray-100 rounded-full text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-300">
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    
                    <div class="flex items-center gap-2">
                        <div class="text-orange-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">Order History</h2>
                    </div>

                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </span>
                            <input type="text" placeholder="Search" class="py-2 pl-9 pr-4 bg-gray-50 border border-gray-200 rounded-md text-sm focus:outline-none focus:border-orange-400 w-64">
                        </div>

                        <a href="#" class="bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition shadow-sm">
                            Back to Dashboard
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-700 text-sm font-bold border-b border-gray-100">
                                <th class="py-3 px-4">Order ID</th>
                                <th class="py-3 px-4">
                                    <div class="flex items-center gap-1 cursor-pointer hover:text-orange-600">
                                        Items
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
                                    </div>
                                </th>
                                <th class="py-3 px-4">
                                    <div class="flex items-center gap-1 cursor-pointer hover:text-orange-600">
                                        Total
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
                                    </div>
                                </th>
                                <th class="py-3 px-4">
                                    <div class="flex items-center gap-1 cursor-pointer hover:text-orange-600">
                                        Date Completed
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-600">
                            <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                                <td class="py-4 px-4 font-bold text-gray-900">Order#003</td>
                                <td class="py-4 px-4 font-medium">4 items</td>
                                <td class="py-4 px-4 font-bold text-gray-800">₱359.00</td>
                                <td class="py-4 px-4">2025-05-15 2:30</td>
                            </tr>
                            
                            <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                                <td class="py-4 px-4 font-bold text-gray-900">Order#002</td>
                                <td class="py-4 px-4 font-medium">6 items</td>
                                <td class="py-4 px-4 font-bold text-gray-800">₱665.00</td>
                                <td class="py-4 px-4">2025-05-15 3:03</td>
                            </tr>

                            <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                                <td class="py-4 px-4 font-bold text-gray-900">Order#001</td>
                                <td class="py-4 px-4 font-medium">3 items</td>
                                <td class="py-4 px-4 font-bold text-gray-800">₱240.00</td>
                                <td class="py-4 px-4">2025-05-15 3:13</td>
                            </tr>

                            {{-- 
                                DYNAMIC DATA NOTE:
                                When backend is ready, replace rows above with:
                                @foreach($orders as $order)
                                    <tr class="...">
                                        <td>Order#{{ $order->id }}</td>
                                        <td>{{ $order->items_count }} items</td>
                                        <td>₱{{ number_format($order->total, 2) }}</td>
                                        <td>{{ $order->created_at }}</td>
                                    </tr>
                                @endforeach
                            --}}
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-center mt-6 pt-4 border-t border-gray-100">
                    <button class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 disabled:opacity-50">
                        Previous
                    </button>
                    
                    <span class="text-sm text-gray-600">
                        Page <span class="font-bold text-gray-900">1</span> of 1
                    </span>
                    
                    <button class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">
                        Next
                    </button>
                </div>

            </div>
        </div>
    </div>
@endsection