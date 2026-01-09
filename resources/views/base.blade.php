<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Default Title')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/Mug.svg') }}">
    <style>
    .hidden {
        display: none !important;
    }
    
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
    
    /* Debug styles */
    #salesChart {
        background-color: #f9fafb !important;
        border: 1px dashed #d1d5db !important;
        min-height: 384px !important; /* 96 * 4 = 384px */
        display: block !important;
    }
    
    .chart-container {
        position: relative;
        width: 100%;
        height: 100%;
    }
</style>
    @vite('resources/css/app.css')
</head>
<body class="bg-[#DBD6D6] min-h-screen">
    @yield('content')
</body>
</html>