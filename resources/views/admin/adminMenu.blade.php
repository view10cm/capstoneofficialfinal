@extends('base')

@section('title', 'Caffe Arabica - Menu Management')

@section('content')
    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar -->
        @include('admin.adminSidebar')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <div class="bg-white shadow p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:space-x-6">
                        <div class="text-center lg:text-left">
                            <h1 class="text-2xl font-bold text-gray-800">Menu</h1>
                            <p class="text-gray-600 mt-1">Manage your menu items</p>
                        </div>
                        <!-- Oblong Search Bar - Centered but beside text -->
                        <div class="relative mt-4 lg:mt-0 lg:mx-6">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" 
                                   placeholder="Search Menu..." 
                                   class="pl-12 pr-6 py-3 w-full lg:w-96 border border-gray-300 rounded-full focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:outline-none transition-colors">
                        </div>
                    </div>
                </div>
                
                <!-- Second Row: Menu Icon, Text, Search, Filter, and Add Button -->
                <div class="mt-6 flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <!-- Left side: Inventory Icon and Text -->
                    <div class="flex items-center mb-4 lg:mb-0">
                        <!-- Inventory Icon -->
                        <div class="bg-amber-100 p-3 rounded-lg mr-3">
                            <img src="{{ asset('images/inventoryIcon.svg') }}" alt="Inventory Icon" class="h-6 w-6">
                        </div>
                        <!-- Menu Text -->
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">Menu Items</h2>
                        </div>
                    </div>
                    
                    <!-- Right side: Search, Filter Dropdown, and Add Button -->
                    <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4">
                        <!-- Search Bar -->
                        <div class="relative w-full sm:w-auto">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" 
                                   placeholder="Search items..." 
                                   class="pl-10 pr-4 py-2 w-full sm:w-64 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:outline-none transition-colors">
                        </div>
                        
                        <!-- Beautiful Orange-Themed Category Dropdown -->
                        <div class="relative w-full sm:w-56 group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                <svg class="h-5 w-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                            </div>
                            <select class="w-full pl-10 pr-10 py-2.5 bg-white border-2 border-amber-300 text-gray-800 rounded-xl focus:ring-2 focus:ring-amber-400 focus:border-amber-400 focus:outline-none transition-all duration-200 shadow-sm hover:border-amber-400 appearance-none cursor-pointer [&>option]:text-gray-800 [&>option]:py-2 [&>option]:px-4 [&>option]:hover:bg-amber-50 [&>option]:hover:text-amber-700 [&>option]:checked:bg-amber-100 [&>option]:checked:text-amber-800 [&>option]:font-medium">
                                <option value="">All Categories</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none z-10">
                                <svg class="h-5 w-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Add Menu Item Button -->
                        <button class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-semibold py-2.5 px-6 rounded-xl transition-all duration-200 shadow hover:shadow-md w-full sm:w-auto">
                            + Add Menu Item
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content area below the header -->
            <div class="flex-1 p-6">
                <!-- Table Container -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <!-- Checkbox Header -->
                                    <th scope="col" class="px-6 py-4 text-left">
                                        <div class="flex items-center">
                                            <input type="checkbox" class="h-5 w-5 text-amber-500 focus:ring-amber-400 border-gray-300 rounded">
                                        </div>
                                    </th>
                                    
                                    <!-- Image Header -->
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Image
                                    </th>
                                    
                                    <!-- Menu Name Header -->
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Menu Name
                                    </th>
                                    
                                    <!-- Category Header -->
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Category
                                    </th>
                                    
                                    <!-- Subcategory Header -->
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Subcategory
                                    </th>
                                    
                                    <!-- Price Header -->
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Price
                                    </th>
                                    
                                    <!-- Status Header -->
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Status
                                    </th>
                                    
                                    <!-- Actions Header -->
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- Table rows will be dynamically populated here -->
                                <!-- This is an empty table structure with no data -->
                                <tr>
                                    <td class="px-6 py-8 text-center text-gray-500 italic" colspan="8">
                                        No menu items found. Click "Add Menu Item" to create your first item.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="flex items-center justify-between px-6 py-4 bg-white border-t border-gray-200">
                        <!-- Previous Button -->
                        <button class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 disabled:opacity-50 disabled:cursor-not-allowed transition-colors" disabled>
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Previous
                        </button>
                        
                        <!-- Page Info -->
                        <div class="flex items-center space-x-1">
                            <span class="text-sm text-gray-700">Page</span>
                            <span class="text-sm font-semibold text-gray-900">1</span>
                            <span class="text-sm text-gray-700">of</span>
                            <span class="text-sm font-semibold text-gray-900">10</span>
                        </div>
                        
                        <!-- Next Button -->
                        <button class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-colors">
                            Next
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection