<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard - Caffe Arabica</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .cinzel-font {
            font-family: 'Cinzel', serif;
        }
    </style>
</head>
<body class="bg-amber-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold cinzel-font" style="color: #92400e;">
                        Caffe Arabica
                    </h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-amber-700 font-medium">
                        Welcome, {{ Auth::user()->name }}!
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-amber-600 text-white px-4 py-2 rounded hover:bg-amber-700 transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-amber-800 mb-4 cinzel-font">
                    Welcome to Caffe Arabica
                </h2>
                <p class="text-gray-600 text-lg">
                    Your favorite coffee shop experience
                </p>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Menu Card -->
                <div class="bg-amber-50 rounded-lg p-6 shadow-md border border-amber-100">
                    <div class="text-center">
                        <div class="text-amber-600 mb-4">
                            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-amber-800 mb-2">Browse Menu</h3>
                        <p class="text-gray-600 mb-4">Explore our delicious coffee and pastry selection</p>
                        <button class="bg-amber-600 text-white px-4 py-2 rounded hover:bg-amber-700 transition">
                            View Menu
                        </button>
                    </div>
                </div>

                <!-- Order Card -->
                <div class="bg-amber-50 rounded-lg p-6 shadow-md border border-amber-100">
                    <div class="text-center">
                        <div class="text-amber-600 mb-4">
                            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-amber-800 mb-2">Place Order</h3>
                        <p class="text-gray-600 mb-4">Order your favorite items for pickup or delivery</p>
                        <button class="bg-amber-600 text-white px-4 py-2 rounded hover:bg-amber-700 transition">
                            Order Now
                        </button>
                    </div>
                </div>

                <!-- Profile Card -->
                <div class="bg-amber-50 rounded-lg p-6 shadow-md border border-amber-100">
                    <div class="text-center">
                        <div class="text-amber-600 mb-4">
                            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-amber-800 mb-2">My Profile</h3>
                        <p class="text-gray-600 mb-4">Manage your account and preferences</p>
                        <button class="bg-amber-600 text-white px-4 py-2 rounded hover:bg-amber-700 transition">
                            View Profile
                        </button>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Section -->
            <div class="mt-12">
                <h3 class="text-2xl font-bold text-amber-800 mb-6 cinzel-font">
                    Recent Orders
                </h3>
                <div class="bg-white border border-amber-100 rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-amber-100">
                        <thead class="bg-amber-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">
                                    Order #
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">
                                    Items
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">
                                    Total
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-amber-100">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    #CA-00123
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ date('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Arabica Latte, Croissant
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    $12.50
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Completed
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    #CA-00122
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ date('M d, Y', strtotime('-1 day')) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Cappuccino, Blueberry Muffin
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    $9.75
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Completed
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Special Offers Section -->
            <div class="mt-12 bg-gradient-to-r from-amber-100 to-orange-100 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-amber-800 cinzel-font">Today's Special</h3>
                        <p class="text-amber-700 mt-2">Get 20% off on all Espresso drinks!</p>
                        <p class="text-amber-600 text-sm mt-1">Valid until end of day</p>
                    </div>
                    <div class="text-amber-600">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-amber-800 text-white py-8 mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-amber-100">&copy; {{ date('Y') }} Caffe Arabica. All rights reserved.</p>
                <p class="text-amber-200 mt-2">Enjoy your coffee experience!</p>
                <div class="mt-4">
                    <p class="text-amber-100 text-sm">Contact us: info@caffearabica.com | (123) 456-7890</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Simple interactivity for buttons
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('button');
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    if (!this.closest('form')) { // Don't show alert for logout button
                        alert('Feature coming soon!');
                    }
                });
            });
        });
    </script>
</body>
</html>