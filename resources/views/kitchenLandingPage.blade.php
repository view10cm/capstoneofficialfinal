<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen Dashboard - Caffe Arabica</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&display=swap" rel="stylesheet">
</head>
<body class="bg-amber-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <header class="mb-8">
            <h1 class="text-4xl font-bold text-center" style="font-family: 'Cinzel', serif; color: #92400e;">
                Kitchen Dashboard
            </h1>
            <p class="text-center text-gray-600 mt-2">Welcome, {{ Auth::user()->name }}!</p>
        </header>

        <!-- Navigation -->
        <nav class="bg-white rounded-lg shadow p-4 mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <span class="font-semibold">Role: Kitchen Staff</span>
                </div>
                <div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Orders Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold text-amber-700 mb-4">Pending Orders</h2>
                <div class="space-y-4">
                    <div class="border-l-4 border-amber-500 pl-4">
                        <p class="font-semibold">Order #1234</p>
                        <p class="text-sm text-gray-600">2x Cappuccino, 1x Croissant</p>
                        <p class="text-sm text-gray-500">10 minutes ago</p>
                    </div>
                    <div class="border-l-4 border-amber-500 pl-4">
                        <p class="font-semibold">Order #1235</p>
                        <p class="text-sm text-gray-600">1x Latte, 2x Muffins</p>
                        <p class="text-sm text-gray-500">5 minutes ago</p>
                    </div>
                </div>
                <button class="mt-4 w-full bg-amber-500 text-white py-2 rounded hover:bg-amber-600 transition">
                    View All Orders
                </button>
            </div>

            <!-- In Progress Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold text-amber-700 mb-4">In Progress</h2>
                <div class="space-y-4">
                    <div class="border-l-4 border-blue-500 pl-4">
                        <p class="font-semibold">Order #1233</p>
                        <p class="text-sm text-gray-600">3x Espresso, 1x Sandwich</p>
                        <div class="mt-2">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 75%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">75% complete</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completed Today Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold text-amber-700 mb-4">Completed Today</h2>
                <p class="text-4xl font-bold text-green-600">24</p>
                <p class="text-gray-600 mt-2">orders completed</p>
                <div class="mt-4">
                    <p class="text-sm text-gray-500">Average time: 8.5 minutes</p>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-amber-700 mb-4">Recent Activity</h2>
            <div class="space-y-3">
                <div class="flex items-center space-x-3">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <p>Order #1232 marked as completed</p>
                    <span class="text-sm text-gray-500 ml-auto">15 min ago</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                    <p>Started preparing Order #1233</p>
                    <span class="text-sm text-gray-500 ml-auto">20 min ago</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-3 h-3 bg-amber-500 rounded-full"></div>
                    <p>Received Order #1234</p>
                    <span class="text-sm text-gray-500 ml-auto">25 min ago</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>