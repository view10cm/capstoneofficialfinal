<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Caffe Arabica</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&display=swap" rel="stylesheet">
    <style>
        .staff-header {
            font-family: 'Cinzel', serif;
        }
    </style>
</head>
<body class="bg-amber-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-amber-800 text-white shadow-lg">
        <div class="container mx-auto px-6 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <h1 class="staff-header text-2xl font-bold">Caffe Arabica - Staff Dashboard</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-amber-100">Welcome, {{ Auth::user()->name }}!</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-6 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Order Management Card -->
            <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
                <h2 class="text-xl font-bold text-amber-800 mb-4">Order Management</h2>
                <p class="text-gray-600 mb-4">View and manage customer orders, update order status, and handle payments.</p>
                <a href="#" class="inline-block bg-amber-600 text-white px-4 py-2 rounded hover:bg-amber-700 transition">
                    Manage Orders
                </a>
            </div>

            <!-- Menu Management Card -->
            <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
                <h2 class="text-xl font-bold text-amber-800 mb-4">Menu Items</h2>
                <p class="text-gray-600 mb-4">View available menu items, check inventory, and update item availability.</p>
                <a href="#" class="inline-block bg-amber-600 text-white px-4 py-2 rounded hover:bg-amber-700 transition">
                    View Menu
                </a>
            </div>

            <!-- Customer Service Card -->
            <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
                <h2 class="text-xl font-bold text-amber-800 mb-4">Customer Service</h2>
                <p class="text-gray-600 mb-4">Handle customer inquiries, reservations, and special requests.</p>
                <a href="#" class="inline-block bg-amber-600 text-white px-4 py-2 rounded hover:bg-amber-700 transition">
                    Customer Service
                </a>
            </div>

            <!-- Reports Card -->
            <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
                <h2 class="text-xl font-bold text-amber-800 mb-4">Daily Reports</h2>
                <p class="text-gray-600 mb-4">View daily sales reports, popular items, and customer statistics.</p>
                <a href="#" class="inline-block bg-amber-600 text-white px-4 py-2 rounded hover:bg-amber-700 transition">
                    View Reports
                </a>
            </div>

            <!-- Table Management Card -->
            <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
                <h2 class="text-xl font-bold text-amber-800 mb-4">Table Management</h2>
                <p class="text-gray-600 mb-4">Manage table reservations, seating arrangements, and table status.</p>
                <a href="#" class="inline-block bg-amber-600 text-white px-4 py-2 rounded hover:bg-amber-700 transition">
                    Manage Tables
                </a>
            </div>

            <!-- Inventory Card -->
            <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
                <h2 class="text-xl font-bold text-amber-800 mb-4">Inventory Check</h2>
                <p class="text-gray-600 mb-4">Check ingredient levels and notify kitchen when supplies are low.</p>
                <a href="#" class="inline-block bg-amber-600 text-white px-4 py-2 rounded hover:bg-amber-700 transition">
                    Check Inventory
                </a>
            </div>
        </div>

        <!-- Quick Stats Section -->
        <div class="mt-8 bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-amber-800 mb-4">Today's Quick Stats</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-amber-50 rounded">
                    <p class="text-3xl font-bold text-amber-700">15</p>
                    <p class="text-gray-600">Pending Orders</p>
                </div>
                <div class="text-center p-4 bg-amber-50 rounded">
                    <p class="text-3xl font-bold text-amber-700">42</p>
                    <p class="text-gray-600">Completed Orders</p>
                </div>
                <div class="text-center p-4 bg-amber-50 rounded">
                    <p class="text-3xl font-bold text-amber-700">8</p>
                    <p class="text-gray-600">Reservations</p>
                </div>
                <div class="text-center p-4 bg-amber-50 rounded">
                    <p class="text-3xl font-bold text-amber-700">₱12,450</p>
                    <p class="text-gray-600">Today's Revenue</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-amber-800 text-white py-4 mt-8">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; {{ date('Y') }} Caffe Arabica. All rights reserved.</p>
            <p class="text-amber-200 text-sm mt-1">Staff Dashboard - For internal use only</p>
        </div>
    </footer>
</body>
</html>