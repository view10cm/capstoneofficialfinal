@extends('base')

@section('title', 'Caffe Arabica - Inventory Management')

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
                            <h1 class="text-2xl font-bold text-gray-800">Inventory</h1>
                            <p class="text-gray-600 mt-1">Manage your inventory items</p>
                        </div>
                        <!-- Oblong Search Bar - Centered but beside text -->
                        <div class="relative mt-4 lg:mt-0 lg:mx-6">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" 
                                   placeholder="Search inventory..." 
                                   class="pl-12 pr-6 py-3 w-full lg:w-96 border border-gray-300 rounded-full focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:outline-none transition-colors">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory Table Container -->
            <div class="flex-1 p-6">
                <!-- Header with Controls -->
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <!-- Left side: Inventory Text -->
                            <div class="flex items-center">
                                <h2 class="text-lg font-semibold text-gray-800">Inventory</h2>
                            </div>

                            <!-- Right side: Search, Export, and Add Button -->
                            <div class="flex flex-col sm:flex-row gap-4">
                                <!-- Search Bar -->
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           id="searchInput"
                                           placeholder="Search..." 
                                           class="pl-10 pr-4 py-2.5 w-full sm:w-64 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:outline-none transition-colors"
                                           onkeyup="searchInventory()">
                                </div>

                                <!-- Export Button -->
                                <button onclick="exportInventory()"
                                        class="flex items-center justify-center px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Export
                                </button>

                                <!-- Add Product Button -->
                                <button onclick="openAddProductModal()"
                                        class="flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-amber-600 to-amber-700 text-white rounded-lg font-medium hover:from-amber-700 hover:to-amber-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 shadow-sm hover:shadow transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Add Product
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div class="py-12 px-6 text-center">
                        <div class="max-w-md mx-auto">
                            <!-- Empty Inventory SVG -->
                            <div class="mb-6">
                                <img src="{{ asset('images/emptyInventory.svg') }}" 
                                     alt="No Ingredients" 
                                     class="mx-auto h-48 w-48">
                            </div>
                            
                            <!-- Message -->
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">No Ingredients found</h3>
                            <p class="text-gray-600 mb-6">Add your first ingredient to get started with inventory management</p>
                            
                            <!-- Add Ingredient Button -->
                            <button onclick="openAddProductModal()"
                                    class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-amber-600 to-amber-700 text-white rounded-lg font-medium hover:from-amber-700 hover:to-amber-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 shadow-sm hover:shadow transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add Your First Ingredient
                            </button>
                        </div>
                    </div>

                    <!-- Table Container (Hidden when empty) -->
                    <div class="overflow-x-auto hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex justify-center items-center">
                                            <input type="checkbox" 
                                                   id="selectAll" 
                                                   class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded"
                                                   onclick="toggleSelectAll()">
                                            <label for="selectAll" class="ml-2 sr-only">Select all</label>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Item ID
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Product Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Category
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Quantity
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Availability
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- Inventory rows will be populated here dynamically -->
                            </tbody>
                        </table>

                        <!-- Pagination - Exactly like the image -->
                        <div class="px-6 py-4 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <!-- Previous Button -->
                                <button class="flex items-center px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                    Previous
                                </button>

                                <!-- Page Info -->
                                <div class="text-gray-700 font-medium">
                                    Page <span class="text-amber-600 font-bold">1</span> of <span>10</span>
                                </div>

                                <!-- Next Button -->
                                <button class="flex items-center px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-200">
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
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-inventory.js') }}"></script>
@endpush