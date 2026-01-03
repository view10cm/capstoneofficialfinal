<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Order Tracker - CAFFE ARABICA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Cinzel:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .header-bg {
            background-color: #191C22;
        }
        .cinzel-font {
            font-family: 'Cinzel', serif;
        }
        .back-btn {
            transition: all 0.2s ease;
        }
        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body class="bg-gray-900 min-h-screen">
    <!-- Fixed Header -->
    <div class="header-bg shadow-md fixed top-0 left-0 right-0 z-50">
        <div class="flex items-center justify-between px-6 py-4">
            <!-- Left Section: Back Button and Title -->
            <div class="flex items-center">
                <button onclick="goBack()" class="back-btn bg-amber-900 border border-amber-800 rounded-lg px-4 py-2 mr-4">
                    <i class="fas fa-arrow-left text-amber-100 mr-2"></i>
                    <span class="text-amber-100 font-medium">Back</span>
                </button>
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight cinzel-font">CAFFE ARABICA</h1>
                    <p class="text-sm text-gray-400 cinzel-font">Order Tracker System</p>
                </div>
            </div>
            
            <!-- Right Section: Employee Info -->
            <div class="flex items-center space-x-8">
                <!-- Employee Info -->
                <div class="text-right">
                    <p class="text-sm text-gray-400">Employee</p>
                    <p class="font-semibold text-white">{{ auth()->user()->name ?? 'Staff Member' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="pt-24 px-6 pb-8">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl font-bold text-white mb-2">Order Tracker</h2>
            <p class="text-gray-400 mb-8">Track and manage all orders in real-time</p>
            
            <!-- Order Status Tabs -->
            <div class="flex space-x-4 mb-8">
                <button class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium">All Orders</button>
                <button class="px-6 py-3 bg-gray-700 text-gray-300 rounded-lg font-medium hover:bg-gray-600">Pending</button>
                <button class="px-6 py-3 bg-gray-700 text-gray-300 rounded-lg font-medium hover:bg-gray-600">In Progress</button>
                <button class="px-6 py-3 bg-gray-700 text-gray-300 rounded-lg font-medium hover:bg-gray-600">Completed</button>
            </div>
            
            <!-- Orders Table -->
            <div class="bg-gray-800 rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-left text-gray-300 font-semibold">Order ID</th>
                                <th class="px-6 py-4 text-left text-gray-300 font-semibold">Payment #</th>
                                <th class="px-6 py-4 text-left text-gray-300 font-semibold">Type</th>
                                <th class="px-6 py-4 text-left text-gray-300 font-semibold">Status</th>
                                <th class="px-6 py-4 text-left text-gray-300 font-semibold">Items</th>
                                <th class="px-6 py-4 text-left text-gray-300 font-semibold">Total</th>
                                <th class="px-6 py-4 text-left text-gray-300 font-semibold">Time</th>
                                <th class="px-6 py-4 text-left text-gray-300 font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            <!-- Orders will be loaded here via JavaScript -->
                            <tr id="no-orders">
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-clipboard-list text-4xl mb-4"></i>
                                    <p class="text-xl">No orders found</p>
                                    <p class="text-sm mt-2">Orders will appear here as they are processed</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to go back to previous page
        function goBack() {
            window.history.back();
        }
        
        // You can add more JavaScript here to load orders dynamically
        // For now, this is a basic template
        
        // Auto-refresh every 30 seconds
        setInterval(() => {
            console.log('Refreshing order tracker data...');
            // Add your data refresh logic here
        }, 30000);
    </script>
</body>
</html>