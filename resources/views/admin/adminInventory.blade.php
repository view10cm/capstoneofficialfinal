@extends('base')

@section('title', 'Caffe Arabica - Inventory Management')

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
                            <h1 class="text-3xl font-bold text-gray-900">Inventory</h1>
                            <p class="text-gray-500 text-sm">Manage your inventory items</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory Table Container -->
            <div class="flex-1 p-5">
                <!-- Header with Controls -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-3 border-b border-gray-200">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <!-- Left side: Inventory Text -->
                            <div class="flex items-center mb-4 lg:mb-0">
                                <div class="bg-white p-3 rounded-lg mr-3">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5 22C4.45 22 3.97933 21.8043 3.588 21.413C3.19667 21.0217 3.00067 20.5507 3 20V8.725C2.7 8.54167 2.45833 8.30433 2.275 8.013C2.09167 7.72167 2 7.384 2 7V4C2 3.45 2.196 2.97933 2.588 2.588C2.98 2.19667 3.45067 2.00067 4 2H20C20.55 2 21.021 2.196 21.413 2.588C21.805 2.98 22.0007 3.45067 22 4V7C22 7.38333 21.9083 7.721 21.725 8.013C21.5417 8.305 21.3 8.542 21 8.724V20C21 20.55 20.8043 21.021 20.413 21.413C20.0217 21.805 19.5507 22.0007 19 22H5ZM5 9V20H19V9H5ZM4 7H20V4H4V7ZM9 14H15V12H9V14Z" fill="#E67809"/>
                                </svg>
                                </div>
                                <!-- Products Text -->
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-800 ml-4">Products</h2>
                                </div>
                            </div>

                            <!-- Right side: Search, Export, and Add Button -->
                            <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4">
                                <!-- Search Bar -->
                                <div class="relative w-full sm:w-auto">
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
                                    Export PDF
                                </button>

                                <!-- Add Ingredient Button -->
                                <button onclick="openAddProductModal()"
                                        class="flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-amber-600 to-amber-700 text-white rounded-lg font-medium hover:from-amber-700 hover:to-amber-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 shadow-sm hover:shadow transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Add Ingredient
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
                                    <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Item ID
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ingredient Name
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Category
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Quantity
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Availability
                                    </th>
                                    <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- Inventory rows will be populated here dynamically -->
                            </tbody>
                        </table>

                        <!-- Pagination - Exactly like the image -->
                        <div class="px-5 py-3 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <!-- Previous Button -->
                                <button id="prevPage" class="flex items-center px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                    Previous
                                </button>

                                <!-- Page Info -->
                                <div class="text-gray-700 font-medium">
                                    Page <span id="currentPage" class="text-amber-600 font-bold">1</span> of <span id="totalPages">1</span>
                                </div>

                                <!-- Next Button -->
                                <button id="nextPage" class="flex items-center px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-200">
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

<!-- Add Ingredient Modal -->
<div id="addProductModal" class="fixed inset-0 bg-transparent bg-opacity-50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 w-full max-w-md">
        <!-- Modal Content -->
        <div class="bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200 shadow-2xl rounded-2xl">
            <!-- Modal Header -->
            <div class="px-6 py-5 bg-gradient-to-r from-amber-500 to-orange-500 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-white">Add Ingredients</h3>
                    <button onclick="closeAddProductModal()" 
                            class="text-white hover:text-amber-100 transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <form id="addIngredientForm" class="space-y-5">
                    @csrf
                    <!-- Ingredient Name -->
                    <div class="space-y-2">
                        <label for="productName" class="block text-sm font-semibold text-amber-900">
                            Ingredient Name *
                        </label>
                        <input type="text" 
                               id="productName" 
                               name="name"
                               required
                               class="w-full px-4 py-3 border-2 border-amber-200 bg-white text-gray-800 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:outline-none transition-all duration-300 shadow-sm hover:border-amber-300"
                               placeholder="Enter product name">
                    </div>

                    <!-- Quantity -->
                    <div class="space-y-2">
                        <label for="quantity" class="block text-sm font-semibold text-amber-900">
                            Quantity *
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   id="quantity" 
                                   name="quantity"
                                   required
                                   min="0"
                                   step="1"
                                   class="w-full px-4 py-3 border-2 border-amber-200 bg-white text-gray-800 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:outline-none transition-all duration-300 shadow-sm hover:border-amber-300 pr-12"
                                   placeholder="0"
                                   oninput="updateAvailabilityStatus()">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-amber-600 font-medium text-sm">units</span>
                            </div>
                        </div>
                    </div>

                    <!-- Category -->
                    <div class="space-y-2">
                        <label for="productCategory" class="block text-sm font-semibold text-amber-900">
                            Category *
                        </label>
                        <div class="flex items-center gap-2">
                            <select id="productCategory" 
                                    name="category"
                                    required
                                    class="flex-1 px-4 py-3 border-2 border-amber-200 bg-white text-gray-800 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:outline-none transition-all duration-300 shadow-sm hover:border-amber-300 appearance-none">
                                <!-- Categories will be populated dynamically -->
                            </select>
                            <button type="button" 
                                    onclick="openAddCategoryModal()"
                                    class="px-4 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl hover:from-amber-600 hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition-all duration-300 shadow-md hover:shadow-lg">
                                + Add
                            </button>
                        </div>
                    </div>

                    <!-- Availability (Read-only) -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-amber-900">
                            Availability
                        </label>
                        <input type="text" 
                               id="availabilityStatus"
                               readonly
                               class="w-full px-4 py-3 border-2 border-amber-200 bg-gradient-to-r from-amber-50 to-orange-50 text-gray-800 rounded-xl focus:outline-none transition-all duration-300 font-semibold text-center shadow-inner">
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-amber-200">
                        <button type="button"
                                onclick="closeAddProductModal()"
                                class="px-5 py-2.5 border-2 border-amber-300 text-amber-700 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl font-semibold hover:from-amber-100 hover:to-orange-100 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition-all duration-300 shadow-sm hover:shadow">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl font-semibold hover:from-amber-600 hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            + Add Ingredient
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div id="addCategoryModal" class="fixed inset-0 bg-transparent bg-opacity-50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 w-full max-w-md">
        <!-- Modal Content -->
        <div class="bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200 shadow-2xl rounded-2xl">
            <!-- Modal Header -->
            <div class="px-6 py-5 bg-gradient-to-r from-amber-500 to-orange-500 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-white">Add New Category</h3>
                    <button onclick="closeAddCategoryModal()" 
                            class="text-white hover:text-amber-100 transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <form id="addCategoryForm" class="space-y-5">
                    @csrf
                    <!-- Category Name -->
                    <div class="space-y-2">
                        <label for="newCategoryName" class="block text-sm font-semibold text-amber-900">
                            Category Name *
                        </label>
                        <input type="text" 
                               id="newCategoryName" 
                               name="category_name"
                               required
                               class="w-full px-4 py-3 border-2 border-amber-200 bg-white text-gray-800 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:outline-none transition-all duration-300 shadow-sm hover:border-amber-300"
                               placeholder="Enter category name">
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-amber-200">
                        <button type="button"
                                onclick="closeAddCategoryModal()"
                                class="px-5 py-2.5 border-2 border-amber-300 text-amber-700 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl font-semibold hover:from-amber-100 hover:to-orange-100 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition-all duration-300 shadow-sm hover:shadow">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl font-semibold hover:from-amber-600 hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            Add Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <script src="{{ asset('js/admin-inventory.js') }}"></script>
<script src="{{ asset('js/admin/admin-inventory-actions.js') }}"></script>
@endsection