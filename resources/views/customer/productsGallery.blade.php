<!-- Double Navbar System -->
<div class="bg-white shadow-sm">
    <!-- Primary Navbar - Categories -->
    <div class="flex justify-center space-x-4 px-4 py-3">
        <button onclick="changeCategory('drinks')" id="drinks-btn"
            class="px-6 py-2 rounded-full font-medium text-gray-800 hover:bg-orange-100 transition-colors active-category">
            Drinks
        </button>
        <button onclick="changeCategory('main-course')" id="main-course-btn"
            class="px-6 py-2 rounded-full font-medium text-gray-800 hover:bg-orange-100 transition-colors">
            Main Course
        </button>
        <button onclick="changeCategory('appetizers')" id="appetizers-btn"
            class="px-6 py-2 rounded-full font-medium text-gray-800 hover:bg-orange-100 transition-colors">
            Appetizers
        </button>
    </div>

    <!-- Secondary Navbar - Subcategories -->
    <div class="flex justify-center space-x-6 px-4 py-3 bg-gray-50 border-t border-gray-200 overflow-x-auto">
        <!-- Drinks Subcategories (default visible) -->
        <div id="drinks-subcategories" class="flex space-x-6">
            <button onclick="changeSubcategory('Hot')"
                class="px-3 py-1 font-medium text-gray-700 hover:text-orange-500 transition-colors active-subcategory">
                Hot
            </button>
            <button onclick="changeSubcategory('Iced')"
                class="px-3 py-1 font-medium text-gray-700 hover:text-orange-500 transition-colors">
                Iced
            </button>
            <button onclick="changeSubcategory('Frappe')"
                class="px-3 py-1 font-medium text-gray-700 hover:text-orange-500 transition-colors">
                Frappe
            </button>
            <button onclick="changeSubcategory('Milktea')"
                class="px-3 py-1 font-medium text-gray-700 hover:text-orange-500 transition-colors">
                Milktea
            </button>
            <button onclick="changeSubcategory('Specials')"
                class="px-3 py-1 font-medium text-gray-700 hover:text-orange-500 transition-colors">
                Specials
            </button>
        </div>

        <!-- Main Course Subcategories (hidden by default) -->
        <div id="main-course-subcategories" class="flex space-x-6 hidden">
            <button onclick="changeSubcategory('Pork')"
                class="px-3 py-1 font-medium text-gray-700 hover:text-orange-500 transition-colors">
                Pork
            </button>
            <button onclick="changeSubcategory('Chicken')"
                class="px-3 py-1 font-medium text-gray-700 hover:text-orange-500 transition-colors">
                Chicken
            </button>
            <button onclick="changeSubcategory('Beef')"
                class="px-3 py-1 font-medium text-gray-700 hover:text-orange-500 transition-colors">
                Beef
            </button>
            <button onclick="changeSubcategory('Fish and Seafood')"
                class="px-3 py-1 font-medium text-gray-700 hover:text-orange-500 transition-colors">
                Fish & Seafood
            </button>
            <button onclick="changeSubcategory('Pasta')"
                class="px-3 py-1 font-medium text-gray-700 hover:text-orange-500 transition-colors">
                Pasta
            </button>
            <button onclick="changeSubcategory('Noodles')"
                class="px-3 py-1 font-medium text-gray-700 hover:text-orange-500 transition-colors">
                Noodles
            </button>
            <button onclick="changeSubcategory('Specials')"
                class="px-3 py-1 font-medium text-gray-700 hover:text-orange-500 transition-colors">
                Specials
            </button>
        </div>

        <!-- Appetizers Subcategories (hidden by default) -->
        <div id="appetizers-subcategories" class="flex space-x-6 hidden">
            <button onclick="changeSubcategory('Sandwiches')"
                class="px-3 py-1 font-medium text-gray-700 hover:text-orange-500 transition-colors">
                Sandwiches
            </button>
            <button onclick="changeSubcategory('Knick/Knacks')"
                class="px-3 py-1 font-medium text-gray-700 hover:text-orange-500 transition-colors">
                Knick/Knacks
            </button>
            <button onclick="changeSubcategory('Salads')"
                class="px-3 py-1 font-medium text-gray-700 hover:text-orange-500 transition-colors">
                Salads
            </button>
            <button onclick="changeSubcategory('Specials')"
                class="px-3 py-1 font-medium text-gray-700 hover:text-orange-500 transition-colors">
                Specials
            </button>
        </div>
    </div>
</div>
