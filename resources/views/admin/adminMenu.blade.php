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
                                   id="searchInput"
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
                            <select id="categoryFilter" 
                                    class="w-full pl-10 pr-10 py-2.5 bg-white border-2 border-amber-300 text-gray-800 rounded-xl focus:ring-2 focus:ring-amber-400 focus:border-amber-400 focus:outline-none transition-all duration-200 shadow-sm hover:border-amber-400 appearance-none cursor-pointer [&>option]:text-gray-800 [&>option]:py-2 [&>option]:px-4 [&>option]:hover:bg-amber-50 [&>option]:hover:text-amber-700 [&>option]:checked:bg-amber-100 [&>option]:checked:text-amber-800 [&>option]:font-medium">
                                <option value="">All Categories</option>
                                <option value="main-course">Main Course</option>
                                <option value="appetizers">Appetizers</option>
                                <option value="drinks">Drinks</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none z-10">
                                <svg class="h-5 w-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Add Menu Item Button -->
                        <button onclick="openCreatePopup()" class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-semibold py-2.5 px-6 rounded-xl transition-all duration-200 shadow hover:shadow-md w-full sm:w-auto">
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
                                            <input type="checkbox" id="selectAll" class="h-5 w-5 text-amber-500 focus:ring-amber-400 border-gray-300 rounded">
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
                            
                            <tbody id="menuTableBody" class="bg-white divide-y divide-gray-200">
                                <!-- Table rows will be dynamically populated here -->
                                <tr>
                                    <td class="px-6 py-8 text-center text-gray-500 italic" colspan="8">
                                        Loading menu items...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="flex items-center justify-between px-6 py-4 bg-white border-t border-gray-200">
                        <!-- Previous Button -->
                        <button id="prevPage" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 disabled:opacity-50 disabled:cursor-not-allowed transition-colors" disabled>
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Previous
                        </button>
                        
                        <!-- Page Info -->
                        <div class="flex items-center space-x-1">
                            <span class="text-sm text-gray-700">Page</span>
                            <span id="currentPage" class="text-sm font-semibold text-gray-900">1</span>
                            <span class="text-sm text-gray-700">of</span>
                            <span id="totalPages" class="text-sm font-semibold text-gray-900">1</span>
                        </div>
                        
                        <!-- Next Button -->
                        <button id="nextPage" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-colors">
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

    <!-- Create Menu Item Popup -->
    <div id="createMenuItemPopup" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black bg-opacity-50">
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all">
            <!-- Popup header -->
            <div class="px-6 py-4 bg-gradient-to-r from-amber-500 to-amber-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-white/20 p-2 rounded-lg mr-3">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white">Create Menu Item</h3>
                    </div>
                    <button type="button" onclick="closeCreatePopup()" class="text-white hover:text-amber-100 transition-colors">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Popup form -->
            <form id="createMenuItemForm" class="px-6 py-5 space-y-4 max-h-[70vh] overflow-y-auto">
                <!-- Product Image Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Product Image <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1">
                        <!-- Upload Area -->
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-amber-400 transition-colors">
                            <input type="file" 
                                   id="productImage" 
                                   name="productImage" 
                                   accept="image/*" 
                                   class="hidden" 
                                   onchange="previewImage(event)" 
                                   required>
                            
                            <label for="productImage" class="cursor-pointer block">
                                <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="mt-1 text-sm text-gray-600">Click to upload product image</p>
                                <p class="text-xs text-gray-500 mt-0.5">PNG, JPG, GIF up to 5MB</p>
                            </label>
                        </div>
                        <!-- Image Preview -->
                        <div id="imagePreview" class="hidden mt-2">
                            <div class="relative w-20 h-20 rounded-lg overflow-hidden border border-gray-300">
                                <img id="previewImage" class="w-full h-full object-cover" src="" alt="Preview">
                                <button type="button" onclick="removeImage()" class="absolute top-0 right-0 bg-red-500 text-white p-1 rounded-full -mr-1 -mt-1">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Product Name -->
                <div>
                    <label for="productName" class="block text-sm font-medium text-gray-700 mb-1">
                        Product Name <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                            </svg>
                        </div>
                        <input type="text" 
                               id="productName" 
                               name="productName" 
                               placeholder="Enter product name" 
                               class="pl-9 pr-4 py-2.5 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:outline-none transition-colors text-sm"
                               required>
                    </div>
                </div>
                
                <!-- Product Category and Subcategory -->
                <div class="grid grid-cols-2 gap-3">
                    <!-- Category -->
                    <div>
                        <label for="productCategory" class="block text-sm font-medium text-gray-700 mb-1">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                            </div>
                            <select id="productCategory" 
                                    name="productCategory" 
                                    class="pl-9 pr-8 py-2.5 w-full bg-white border border-amber-300 text-gray-800 rounded-lg focus:ring-2 focus:ring-amber-400 focus:border-amber-400 focus:outline-none transition-all duration-200 appearance-none cursor-pointer text-sm"
                                    required>
                                <option value="">Select Category</option>
                                <option value="main-course">Main Course</option>
                                <option value="appetizers">Appetizers</option>
                                <option value="drinks">Drinks</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Subcategory -->
                    <div>
                        <label for="productSubcategory" class="block text-sm font-medium text-gray-700 mb-1">
                            Subcategory <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <select id="productSubcategory" 
                                    name="productSubcategory" 
                                    class="pl-9 pr-8 py-2.5 w-full bg-white border border-amber-300 text-gray-800 rounded-lg focus:ring-2 focus:ring-amber-400 focus:border-amber-400 focus:outline-none transition-all duration-200 appearance-none cursor-pointer text-sm"
                                    required>
                                <option value="">Select Subcategory</option>
                                <!-- Main Course Subcategories -->
                                <option value="pork" class="category-main-course">Pork</option>
                                <option value="chicken" class="category-main-course">Chicken</option>
                                <option value="beef" class="category-main-course">Beef</option>
                                <option value="fish-seafood" class="category-main-course">Fish & Seafood</option>
                                <option value="pasta" class="category-main-course">Pasta</option>
                                <option value="noodles" class="category-main-course">Noodles</option>
                                
                                <!-- Appetizers Subcategories -->
                                <option value="knick-knacks" class="category-appetizers">Knick/Knacks</option>
                                <option value="sandwiches" class="category-appetizers">Sandwiches</option>
                                <option value="salads" class="category-appetizers">Salads</option>
                                
                                <!-- Drinks Subcategories -->
                                <option value="hot" class="category-drinks">Hot</option>
                                <option value="iced" class="category-drinks">Iced</option>
                                <option value="frappe" class="category-drinks">Frappe</option>
                                <option value="milktea" class="category-drinks">Milktea</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Price -->
                <div>
                    <label for="productPrice" class="block text-sm font-medium text-gray-700 mb-1">
                        Price <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm">PHP</span>
                        </div>
                        <input type="number" 
                               id="productPrice" 
                               name="productPrice" 
                               placeholder="0.00" 
                               min="0" 
                               step="0.01" 
                               class="pl-9 pr-12 py-2.5 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:outline-none transition-colors text-sm"
                               required>
                    </div>
                </div>
                
                <!-- Popup footer -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" 
                            onclick="closeCreatePopup()" 
                            class="px-4 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-sm">
                        Cancel
                    </button>
                    <button type="submit" 
                            id="createSubmitBtn"
                            class="px-4 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-medium rounded-lg hover:from-amber-600 hover:to-amber-700 transition-all duration-200 shadow hover:shadow-md text-sm">
                        Create Item
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Menu Item Popup -->
    <div id="editMenuItemPopup" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black bg-opacity-50">
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all">
            <!-- Popup header -->
            <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-white/20 p-2 rounded-lg mr-3">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white">Edit Menu Item</h3>
                    </div>
                    <button type="button" onclick="closeEditPopup()" class="text-white hover:text-blue-100 transition-colors">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Popup form -->
            <form id="editMenuItemForm" class="px-6 py-5 space-y-4 max-h-[70vh] overflow-y-auto">
                <input type="hidden" id="editMenuID" name="menuID">
                
                <!-- Product Image Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Product Image
                    </label>
                    <div class="mt-1">
                        <!-- Current Image -->
                        <div id="currentImageContainer" class="mb-3 hidden">
                            <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                            <div class="relative w-20 h-20 rounded-lg overflow-hidden border border-gray-300">
                                <img id="currentImage" class="w-full h-full object-cover" src="" alt="Current">
                            </div>
                        </div>
                        
                        <!-- Upload Area -->
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition-colors">
                            <input type="file" 
                                   id="editProductImage" 
                                   name="productImage" 
                                   accept="image/*" 
                                   class="hidden" 
                                   onchange="previewEditImage(event)">
                            
                            <label for="editProductImage" class="cursor-pointer block">
                                <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="mt-1 text-sm text-gray-600">Click to change product image</p>
                                <p class="text-xs text-gray-500 mt-0.5">PNG, JPG, GIF up to 5MB</p>
                            </label>
                        </div>
                        <!-- New Image Preview -->
                        <div id="editImagePreview" class="hidden mt-2">
                            <p class="text-sm text-gray-600 mb-2">New Image:</p>
                            <div class="relative w-20 h-20 rounded-lg overflow-hidden border border-gray-300">
                                <img id="editPreviewImage" class="w-full h-full object-cover" src="" alt="Preview">
                                <button type="button" onclick="removeEditImage()" class="absolute top-0 right-0 bg-red-500 text-white p-1 rounded-full -mr-1 -mt-1">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Product Name -->
                <div>
                    <label for="editProductName" class="block text-sm font-medium text-gray-700 mb-1">
                        Product Name <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                            </svg>
                        </div>
                        <input type="text" 
                               id="editProductName" 
                               name="productName" 
                               placeholder="Enter product name" 
                               class="pl-9 pr-4 py-2.5 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors text-sm"
                               required>
                    </div>
                </div>
                
                <!-- Product Category and Subcategory -->
                <div class="grid grid-cols-2 gap-3">
                    <!-- Category -->
                    <div>
                        <label for="editProductCategory" class="block text-sm font-medium text-gray-700 mb-1">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                            </div>
                            <select id="editProductCategory" 
                                    name="productCategory" 
                                    class="pl-9 pr-8 py-2.5 w-full bg-white border border-blue-300 text-gray-800 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400 focus:outline-none transition-all duration-200 appearance-none cursor-pointer text-sm"
                                    required>
                                <option value="">Select Category</option>
                                <option value="main-course">Main Course</option>
                                <option value="appetizers">Appetizers</option>
                                <option value="drinks">Drinks</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Subcategory -->
                    <div>
                        <label for="editProductSubcategory" class="block text-sm font-medium text-gray-700 mb-1">
                            Subcategory <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <select id="editProductSubcategory" 
                                    name="productSubcategory" 
                                    class="pl-9 pr-8 py-2.5 w-full bg-white border border-blue-300 text-gray-800 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400 focus:outline-none transition-all duration-200 appearance-none cursor-pointer text-sm"
                                    required>
                                <option value="">Select Subcategory</option>
                                <!-- Options will be populated by JavaScript -->
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Price -->
                <div>
                    <label for="editProductPrice" class="block text-sm font-medium text-gray-700 mb-1">
                        Price <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm">PHP</span>
                        </div>
                        <input type="number" 
                               id="editProductPrice" 
                               name="productPrice" 
                               placeholder="0.00" 
                               min="0" 
                               step="0.01" 
                               class="pl-9 pr-12 py-2.5 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors text-sm"
                               required>
                    </div>
                </div>
                
                <!-- Status -->
                <div>
                    <label for="editMenuStatus" class="block text-sm font-medium text-gray-700 mb-1">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="editMenuStatus" 
                            name="menuStatus" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-colors text-sm">
                        <option value="Available">Available</option>
                        <option value="Out of Stock">Out of Stock</option>
                        <option value="Discontinued">Discontinued</option>
                    </select>
                </div>
                
                <!-- Popup footer -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" 
                            onclick="closeEditPopup()" 
                            class="px-4 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-sm">
                        Cancel
                    </button>
                    <button type="submit" 
                            id="editSubmitBtn"
                            class="px-4 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow hover:shadow-md text-sm">
                        Update Item
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div id="statusModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black bg-opacity-50">
        <div class="relative w-full max-w-sm bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all">
            <div class="px-6 py-5">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Update Status</h3>
                <p class="text-gray-600 mb-4">Select new status for <span id="statusItemName" class="font-semibold"></span></p>
                
                <input type="hidden" id="statusMenuID">
                
                <select id="statusSelect" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:outline-none transition-colors mb-4">
                    <option value="Available">Available</option>
                    <option value="Out of Stock">Out of Stock</option>
                    <option value="Discontinued">Discontinued</option>
                </select>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeStatusModal()" 
                            class="px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-sm">
                        Cancel
                    </button>
                    <button type="button" 
                            onclick="updateStatus()" 
                            class="px-4 py-2 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-medium rounded-lg hover:from-amber-600 hover:to-amber-700 transition-all duration-200 text-sm">
                        Update Status
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        #createMenuItemPopup, #editMenuItemPopup, #statusModal {
            animation: fadeIn 0.2s ease-out;
        }
        
        #createMenuItemPopup > div,
        #editMenuItemPopup > div,
        #statusModal > div {
            animation: popupIn 0.3s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes popupIn {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(-10px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
        
        /* Custom scrollbar for the forms */
        #createMenuItemForm::-webkit-scrollbar,
        #editMenuItemForm::-webkit-scrollbar {
            width: 6px;
        }
        
        #createMenuItemForm::-webkit-scrollbar-track,
        #editMenuItemForm::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        
        #createMenuItemForm::-webkit-scrollbar-thumb,
        #editMenuItemForm::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 3px;
        }
        
        #createMenuItemForm::-webkit-scrollbar-thumb:hover,
        #editMenuItemForm::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
    </style>

    <script>
        // Global variables for pagination
        let currentPage = 1;
        let totalPages = 1;
        let currentSearch = '';
        let currentCategory = '';

        // Popup functions
        function openCreatePopup() {
            document.getElementById('createMenuItemPopup').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeCreatePopup() {
            document.getElementById('createMenuItemPopup').classList.add('hidden');
            document.body.style.overflow = 'auto';
            resetCreateForm();
        }
        
        function openEditPopup() {
            document.getElementById('editMenuItemPopup').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeEditPopup() {
            document.getElementById('editMenuItemPopup').classList.add('hidden');
            document.body.style.overflow = 'auto';
            resetEditForm();
        }
        
        function openStatusModal(menuID, menuName) {
            document.getElementById('statusModal').classList.remove('hidden');
            document.getElementById('statusMenuID').value = menuID;
            document.getElementById('statusItemName').textContent = menuName;
            document.body.style.overflow = 'hidden';
        }
        
        function closeStatusModal() {
            document.getElementById('statusModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Image preview functions
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('imagePreview');
            const previewImage = document.getElementById('previewImage');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        function previewEditImage(event) {
            const input = event.target;
            const preview = document.getElementById('editImagePreview');
            const previewImage = document.getElementById('editPreviewImage');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        // Remove image functions
        function removeImage() {
            document.getElementById('productImage').value = '';
            document.getElementById('imagePreview').classList.add('hidden');
            document.getElementById('previewImage').src = '';
        }
        
        function removeEditImage() {
            document.getElementById('editProductImage').value = '';
            document.getElementById('editImagePreview').classList.add('hidden');
            document.getElementById('editPreviewImage').src = '';
        }
        
        // Category-Subcategory filtering for create form
        document.getElementById('productCategory').addEventListener('change', function() {
            const category = this.value;
            const subcategorySelect = document.getElementById('productSubcategory');
            const allOptions = subcategorySelect.querySelectorAll('option');
            
            // Reset subcategory
            subcategorySelect.value = '';
            
            // Show/hide options based on category
            allOptions.forEach(option => {
                if (option.value === '') {
                    option.style.display = 'block';
                } else {
                    const optionCategory = option.className.replace('category-', '');
                    if (optionCategory === category) {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                }
            });
        });

        // Category-Subcategory filtering for edit form
        document.getElementById('editProductCategory').addEventListener('change', function() {
            const category = this.value;
            populateSubcategoryOptions(category, 'editProductSubcategory');
        });

        // Function to populate subcategory options
        function populateSubcategoryOptions(category, selectId) {
            const subcategorySelect = document.getElementById(selectId);
            const subcategories = getSubcategoriesForCategory(category);
            
            // Clear existing options except the first one
            while (subcategorySelect.options.length > 1) {
                subcategorySelect.remove(1);
            }
            
            // Add new options
            subcategories.forEach(subcategory => {
                const option = document.createElement('option');
                option.value = subcategory.value;
                option.textContent = subcategory.label;
                subcategorySelect.appendChild(option);
            });
            
            // Reset to first option
            subcategorySelect.value = subcategories.length > 0 ? subcategories[0].value : '';
        }

        // Function to get subcategories for a category
        function getSubcategoriesForCategory(category) {
            const subcategories = {
                'main-course': [
                    { value: 'pork', label: 'Pork' },
                    { value: 'chicken', label: 'Chicken' },
                    { value: 'beef', label: 'Beef' },
                    { value: 'fish-seafood', label: 'Fish & Seafood' },
                    { value: 'pasta', label: 'Pasta' },
                    { value: 'noodles', label: 'Noodles' }
                ],
                'appetizers': [
                    { value: 'knick-knacks', label: 'Knick/Knacks' },
                    { value: 'sandwiches', label: 'Sandwiches' },
                    { value: 'salads', label: 'Salads' }
                ],
                'drinks': [
                    { value: 'hot', label: 'Hot' },
                    { value: 'iced', label: 'Iced' },
                    { value: 'frappe', label: 'Frappe' },
                    { value: 'milktea', label: 'Milktea' }
                ]
            };
            
            return subcategories[category] || [];
        }

// Create form submission
document.getElementById('createMenuItemForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    console.log('Form submission started');
    
    // Show loading state
    const submitBtn = document.getElementById('createSubmitBtn');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Creating...';
    submitBtn.disabled = true;
    
    try {
        // Collect form data
        const formData = new FormData();
        formData.append('productName', document.getElementById('productName').value);
        formData.append('productCategory', document.getElementById('productCategory').value);
        formData.append('productSubcategory', document.getElementById('productSubcategory').value);
        formData.append('productPrice', document.getElementById('productPrice').value);
        
        // Add image if selected
        const imageInput = document.getElementById('productImage');
        console.log('Image input:', imageInput);
        console.log('Files:', imageInput.files);
        
        if (imageInput.files[0]) {
            console.log('File selected:', imageInput.files[0]);
            console.log('File size:', imageInput.files[0].size);
            console.log('File type:', imageInput.files[0].type);
            formData.append('productImage', imageInput.files[0]);
        } else {
            console.log('No image selected');
        }
        
        // Send request to server
        console.log('Sending request...');
        const response = await fetch('{{ route("admin.menu.create") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                // Don't set Content-Type for FormData, let browser set it
            },
            body: formData
        });
        
        console.log('Response status:', response.status);
        const result = await response.json();
        console.log('Response result:', result);
        
        if (result.success) {
            // Show success message
            showNotification('Menu item created successfully!', 'success');
            
            // Close popup
            closeCreatePopup();
            
            // Reload the table data
            loadMenuItems(currentSearch, currentCategory, currentPage);
        } else {
            // Show error message
            showNotification(result.message || 'Failed to create menu item', 'error');
        }
        
    } catch (error) {
        console.error('Error:', error);
        showNotification('An error occurred. Please try again.', 'error');
    } finally {
        // Reset button state
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }
});

        // Edit form submission
        document.getElementById('editMenuItemForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const menuID = document.getElementById('editMenuID').value;
            
            // Show loading state
            const submitBtn = document.getElementById('editSubmitBtn');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Updating...';
            submitBtn.disabled = true;
            
            try {
                // Collect form data
                const formData = new FormData();
                formData.append('productName', document.getElementById('editProductName').value);
                formData.append('productCategory', document.getElementById('editProductCategory').value);
                formData.append('productSubcategory', document.getElementById('editProductSubcategory').value);
                formData.append('productPrice', document.getElementById('editProductPrice').value);
                formData.append('menuStatus', document.getElementById('editMenuStatus').value);
                
                // Add image if selected
                const imageInput = document.getElementById('editProductImage');
                if (imageInput.files[0]) {
                    formData.append('productImage', imageInput.files[0]);
                }
                
                // Send request to server
                const response = await fetch(`{{ route("admin.menu.update", ":id") }}`.replace(':id', menuID), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Show success message
                    showNotification('Menu item updated successfully!', 'success');
                    
                    // Close popup
                    closeEditPopup();
                    
                    // Reload the table data
                    loadMenuItems(currentSearch, currentCategory, currentPage);
                } else {
                    // Show error message
                    showNotification(result.message || 'Failed to update menu item', 'error');
                }
                
            } catch (error) {
                console.error('Error:', error);
                showNotification('An error occurred. Please try again.', 'error');
            } finally {
                // Reset button state
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }
        });

        // Function to load menu items into the table
        async function loadMenuItems(search = '', category = '', page = 1) {
            try {
                // Update global variables
                currentSearch = search;
                currentCategory = category;
                currentPage = page;
                
                // Show loading state in table
                const tableBody = document.getElementById('menuTableBody');
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500 italic">
                            Loading menu items...
                        </td>
                    </tr>
                `;
                
                // Build query parameters
                const params = new URLSearchParams();
                if (search) params.append('search', search);
                if (category) params.append('category', category);
                params.append('page', page);
                
                // Fetch data from server
                const response = await fetch(`{{ route("admin.menu.list") }}?${params.toString()}`);
                const result = await response.json();
                
                if (result.success) {
                    updateTableWithData(result.data);
                    updatePagination(result.data);
                } else {
                    throw new Error(result.message);
                }
                
            } catch (error) {
                console.error('Error loading menu items:', error);
                const tableBody = document.getElementById('menuTableBody');
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-red-500">
                            Failed to load menu items. Please try again.
                        </td>
                    </tr>
                `;
            }
        }

        // Function to update table with data
function updateTableWithData(data) {
    const tableBody = document.getElementById('menuTableBody');
    
    if (!data.data || data.data.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="8" class="px-6 py-8 text-center text-gray-500 italic">
                    No menu items found. Click "Add Menu Item" to create your first item.
                </td>
            </tr>
        `;
        return;
    }
    
    let html = '';
    
    data.data.forEach(item => {
        // Get category display name
        const categoryNames = {
            'main-course': 'Main Course',
            'appetizers': 'Appetizers',
            'drinks': 'Drinks'
        };
        
        // Get subcategory display name
        const subcategoryNames = {
            'pork': 'Pork',
            'chicken': 'Chicken',
            'beef': 'Beef',
            'fish-seafood': 'Fish & Seafood',
            'pasta': 'Pasta',
            'noodles': 'Noodles',
            'knick-knacks': 'Knick/Knacks',
            'sandwiches': 'Sandwiches',
            'salads': 'Salads',
            'hot': 'Hot',
            'iced': 'Iced',
            'frappe': 'Frappe',
            'milktea': 'Milktea'
        };
        
        // Status badge classes
        const statusClasses = {
            'Available': 'bg-green-100 text-green-800',
            'Out of Stock': 'bg-red-100 text-red-800',
            'Discontinued': 'bg-gray-100 text-gray-800'
        };
        
        // FIXED: Image path - Use the correct storage URL
        let imageUrl = '{{ asset("images/default-menu.png") }}';
        
        if (item.menuImage) {
            // Check if the path already contains 'storage/'
            if (item.menuImage.includes('storage/')) {
                imageUrl = `{{ asset('') }}${item.menuImage}`;
            } else if (item.menuImage.includes('menu-images/')) {
                // If it's just 'menu-images/filename.png'
                imageUrl = `{{ asset('storage') }}/${item.menuImage}`;
            } else {
                // For any other format
                imageUrl = `{{ asset('storage/menu-images') }}/${item.menuImage}`;
            }
        }
        
        console.log('Image path for', item.menuName, ':', item.menuImage);
        console.log('Image URL:', imageUrl);
        
        html += `
            <tr class="hover:bg-gray-50 transition-colors">
                <!-- Checkbox -->
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <input type="checkbox" class="item-checkbox h-5 w-5 text-amber-500 focus:ring-amber-400 border-gray-300 rounded" value="${item.menuID}">
                    </div>
                </td>
                
                <!-- Image -->
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="h-10 w-10 rounded-lg overflow-hidden">
                        <img src="${imageUrl}" 
                             alt="${item.menuName}" 
                             class="h-full w-full object-cover"
                             onerror="this.onerror=null; this.src='{{ asset('images/default-menu.png') }}'">
                    </div>
                </td>
                
                <!-- Menu Name -->
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">${item.menuName}</div>
                    <div class="text-sm text-gray-500">${item.menuID}</div>
                </td>
                
                <!-- Category -->
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${categoryNames[item.menuCategory] || item.menuCategory}</div>
                </td>
                
                <!-- Subcategory -->
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${subcategoryNames[item.menuSubcategory] || item.menuSubcategory}</div>
                </td>
                
                <!-- Price -->
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">PHP ${parseFloat(item.menuPrice).toFixed(2)}</div>
                </td>
                
                <!-- Status -->
                <td class="px-6 py-4 whitespace-nowrap">
                    <button onclick="openStatusModal('${item.menuID}', '${item.menuName}')" 
                            class="px-2.5 py-1 text-xs font-medium rounded-full ${statusClasses[item.menuStatus]} hover:opacity-80 transition-opacity">
                        ${item.menuStatus}
                    </button>
                </td>
                
                <!-- Actions -->
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center space-x-3">
                        <button onclick="editMenuItem('${item.menuID}')" class="text-blue-600 hover:text-blue-900 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button onclick="deleteMenuItem('${item.menuID}')" class="text-red-600 hover:text-red-900 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    tableBody.innerHTML = html;
            
            // Add event listener to select all checkbox
            const selectAllCheckbox = document.getElementById('selectAll');
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');
            
            selectAllCheckbox.addEventListener('change', function() {
                itemCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
            
            // Add event listener to individual checkboxes
            itemCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const allChecked = Array.from(itemCheckboxes).every(cb => cb.checked);
                    selectAllCheckbox.checked = allChecked;
                });
            });
        }

        // Function to update pagination controls
        function updatePagination(data) {
            const prevBtn = document.getElementById('prevPage');
            const nextBtn = document.getElementById('nextPage');
            const currentPageSpan = document.getElementById('currentPage');
            const totalPagesSpan = document.getElementById('totalPages');
            
            currentPage = data.current_page;
            totalPages = data.last_page;
            
            // Update page info
            currentPageSpan.textContent = currentPage;
            totalPagesSpan.textContent = totalPages;
            
            // Update button states
            prevBtn.disabled = currentPage <= 1;
            nextBtn.disabled = currentPage >= totalPages;
            
            // Update button event listeners
            prevBtn.onclick = () => {
                if (currentPage > 1) {
                    loadMenuItems(currentSearch, currentCategory, currentPage - 1);
                }
            };
            
            nextBtn.onclick = () => {
                if (currentPage < totalPages) {
                    loadMenuItems(currentSearch, currentCategory, currentPage + 1);
                }
            };
        }

        // Function to show notification
        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white font-medium transform transition-all duration-300 translate-x-full`;
            
            if (type === 'success') {
                notification.classList.add('bg-green-500');
            } else if (type === 'error') {
                notification.classList.add('bg-red-500');
            } else {
                notification.classList.add('bg-amber-500');
            }
            
            notification.textContent = message;
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
                notification.classList.add('translate-x-0');
            }, 10);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.classList.remove('translate-x-0');
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Function to edit menu item
async function editMenuItem(menuID) {
    try {
        const response = await fetch(`{{ route("admin.menu.show", ":id") }}`.replace(':id', menuID));
        const result = await response.json();
        
        if (result.success) {
            const item = result.data;
            
            // Populate edit form
            document.getElementById('editMenuID').value = item.menuID;
            document.getElementById('editProductName').value = item.menuName;
            document.getElementById('editProductCategory').value = item.menuCategory;
            document.getElementById('editProductPrice').value = item.menuPrice;
            document.getElementById('editMenuStatus').value = item.menuStatus;
            
            // Populate subcategory options
            populateSubcategoryOptions(item.menuCategory, 'editProductSubcategory');
            document.getElementById('editProductSubcategory').value = item.menuSubcategory;
            
            // Handle image display - FIXED
            const currentImageContainer = document.getElementById('currentImageContainer');
            const currentImage = document.getElementById('currentImage');
            
            if (item.menuImage) {
                // Build the correct image URL
                let imageUrl;
                if (item.menuImage.includes('storage/')) {
                    imageUrl = `{{ asset('') }}${item.menuImage}`;
                } else if (item.menuImage.includes('menu-images/')) {
                    imageUrl = `{{ asset('storage') }}/${item.menuImage}`;
                } else {
                    imageUrl = `{{ asset('storage/menu-images') }}/${item.menuImage}`;
                }
                
                currentImage.src = imageUrl;
                currentImage.onerror = function() {
                    this.src = '{{ asset("images/default-menu.png") }}';
                };
                currentImageContainer.classList.remove('hidden');
            } else {
                currentImageContainer.classList.add('hidden');
            }
            
            // Open edit popup
            openEditPopup();
        } else {
            showNotification(result.message, 'error');
        }
    } catch (error) {
        showNotification('Failed to fetch menu item details', 'error');
    }
}

        // Function to delete menu item
        async function deleteMenuItem(menuID) {
            if (confirm('Are you sure you want to delete this menu item?')) {
                try {
                    const response = await fetch(`{{ route("admin.menu.delete", ":id") }}`.replace(':id', menuID), {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        }
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        showNotification('Menu item deleted successfully!', 'success');
                        loadMenuItems(currentSearch, currentCategory, currentPage);
                    } else {
                        showNotification(result.message, 'error');
                    }
                } catch (error) {
                    showNotification('Failed to delete menu item', 'error');
                }
            }
        }

        // Function to update status
        async function updateStatus() {
            const menuID = document.getElementById('statusMenuID').value;
            const status = document.getElementById('statusSelect').value;
            
            try {
                const response = await fetch(`{{ route("admin.menu.updateStatus", ":id") }}`.replace(':id', menuID), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ status: status })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification('Status updated successfully!', 'success');
                    closeStatusModal();
                    loadMenuItems(currentSearch, currentCategory, currentPage);
                } else {
                    showNotification(result.message, 'error');
                }
            } catch (error) {
                showNotification('Failed to update status', 'error');
            }
        }

        // Reset form functions
        function resetCreateForm() {
            document.getElementById('createMenuItemForm').reset();
            document.getElementById('imagePreview').classList.add('hidden');
            document.getElementById('previewImage').src = '';
            
            // Reset all subcategory options to visible
            const subcategorySelect = document.getElementById('productSubcategory');
            const allOptions = subcategorySelect.querySelectorAll('option');
            allOptions.forEach(option => {
                option.style.display = 'block';
            });
        }
        
        function resetEditForm() {
            document.getElementById('editMenuItemForm').reset();
            document.getElementById('editImagePreview').classList.add('hidden');
            document.getElementById('editPreviewImage').src = '';
            document.getElementById('currentImageContainer').classList.add('hidden');
            document.getElementById('editProductImage').value = '';
        }

        // Load menu items when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadMenuItems();
            
            // Add event listener to search input
            const searchInput = document.getElementById('searchInput');
            const categorySelect = document.getElementById('categoryFilter');
            
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    loadMenuItems(this.value, categorySelect.value, 1);
                }, 500);
            });
            
            categorySelect.addEventListener('change', function() {
                loadMenuItems(searchInput.value, this.value, 1);
            });
            
            // Add event listener to select all checkbox
            document.getElementById('selectAll').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.item-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        });

        // Close popup on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (!document.getElementById('createMenuItemPopup').classList.contains('hidden')) {
                    closeCreatePopup();
                }
                if (!document.getElementById('editMenuItemPopup').classList.contains('hidden')) {
                    closeEditPopup();
                }
                if (!document.getElementById('statusModal').classList.contains('hidden')) {
                    closeStatusModal();
                }
            }
        });

        // Close popup when clicking outside
        document.getElementById('createMenuItemPopup').addEventListener('click', function(e) {
            if (e.target.id === 'createMenuItemPopup') {
                closeCreatePopup();
            }
        });
        
        document.getElementById('editMenuItemPopup').addEventListener('click', function(e) {
            if (e.target.id === 'editMenuItemPopup') {
                closeEditPopup();
            }
        });
        
        document.getElementById('statusModal').addEventListener('click', function(e) {
            if (e.target.id === 'statusModal') {
                closeStatusModal();
            }
        });
    </script>
@endsection