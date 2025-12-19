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
                            class="px-4 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 text-white font-medium rounded-lg hover:from-amber-600 hover:to-amber-700 transition-all duration-200 shadow hover:shadow-md text-sm">
                        Create Item
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        #createMenuItemPopup {
            animation: fadeIn 0.2s ease-out;
        }
        
        #createMenuItemPopup > div {
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
        
        /* Custom scrollbar for the form */
        #createMenuItemForm::-webkit-scrollbar {
            width: 6px;
        }
        
        #createMenuItemForm::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        
        #createMenuItemForm::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 3px;
        }
        
        #createMenuItemForm::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
    </style>

    <script>
        // Popup functions
        function openCreatePopup() {
            document.getElementById('createMenuItemPopup').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeCreatePopup() {
            document.getElementById('createMenuItemPopup').classList.add('hidden');
            document.body.style.overflow = 'auto';
            resetForm();
        }
        
        // Image preview function
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
        
        // Remove image function
        function removeImage() {
            document.getElementById('productImage').value = '';
            document.getElementById('imagePreview').classList.add('hidden');
            document.getElementById('previewImage').src = '';
        }
        
        // Category-Subcategory filtering
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
        
        // Form submission
        document.getElementById('createMenuItemForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Here you would typically send the form data to your server
            // For now, we'll just close the popup and show an alert
            alert('Menu item created successfully!');
            closeCreatePopup();
            
            // In a real application, you would:
            // 1. Collect form data
            // 2. Send to server via AJAX
            // 3. Handle response
            // 4. Update the table with new data
        });
        
        // Reset form function
        function resetForm() {
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
        
        // Close popup on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCreatePopup();
            }
        });
        
        // Close popup when clicking outside
        document.getElementById('createMenuItemPopup').addEventListener('click', function(e) {
            if (e.target.id === 'createMenuItemPopup') {
                closeCreatePopup();
            }
        });
    </script>
@endsection