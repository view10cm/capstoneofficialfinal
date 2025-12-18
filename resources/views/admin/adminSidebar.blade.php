<div class="flex flex-col h-screen bg-white px-6 py-8" style="width: 310px;">
    <!-- Logo Section -->
    <div class="flex flex-col items-center mb-10">
        <img src="{{ asset('images/caffeArabicaSidebarLogo.svg') }}" alt="Caffé Arabica Logo" class="w-50 h-22 mb-2 mr-2">
    </div>
    <!-- Navigation -->
    <nav class="flex-1">
        <ul class="space-y-4">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center text-black hover:text-orange-600 font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-orange-600' : '' }}">
                    <!-- Dashboard Icon -->
                    <img src="{{ asset('images/sidebarDashboard.svg') }}" alt="Dashboard Icon" class="h-6 w-6 mr-3">
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.inventory') }}" class="flex items-center text-black hover:text-orange-600 font-medium transition-colors {{ request()->routeIs('admin.inventory') ? 'text-orange-600' : '' }}">
                    <!-- Inventory Icon -->
                    <img src="{{ asset('images/sidebarInventory.svg') }}" alt="Inventory Icon" class="h-5 w-5 mr-3">
                    Inventory
                </a>
            </li>
            <li>
                <a href="{{ route('admin.menu') }}" class="flex items-center text-black hover:text-orange-600 font-medium transition-colors {{ request()->routeIs('admin.menu') ? 'text-orange-600' : '' }}">
                    <!-- Menu Icon -->
                    <img src="{{ asset('images/sidebarMenu.svg') }}" alt="Menu Icon" class="h-6 w-6 mr-3">
                    Menu
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users') }}" class="flex items-center text-black hover:text-orange-600 font-medium transition-colors {{ request()->routeIs('admin.users') ? 'text-orange-600' : '' }}">
                    <!-- Users Icon -->
                    <img src="{{ asset('images/sidebarUsers.svg') }}" alt="Users Icon" class="h-6 w-6 mr-3">
                    Users
                </a>
            </li>
            <li>
                <a href="{{ route('admin.order-history') }}" class="flex items-center text-black hover:text-orange-600 font-medium transition-colors {{ request()->routeIs('admin.order-history') ? 'text-orange-600' : '' }}">
                    <!-- Order History Icon -->
                    <img src="{{ asset('images/sidebarOrderHistory.svg') }}" alt="Order History Icon" class="h-6 w-6 mr-3">
                    Order History
                </a>
            </li>
        </ul>
    </nav>
    <!-- Logout -->
    <div class="mt-auto">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center text-black hover:text-orange-600 font-medium transition-colors">
                <!-- Logout Icon -->
                <img src="{{ asset('images/sidebarLogout.svg') }}" alt="Logout Icon" class="h-6 w-6 mr-3">
                Log Out
            </button>
        </form>
    </div>
</div>