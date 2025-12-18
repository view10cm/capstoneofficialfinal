<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Caffe Arabica</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&display=swap" rel="stylesheet">
</head>
<body class="bg-amber-50 min-h-screen flex items-center justify-center"
    style="background: url('{{ asset('/images/Login.svg') }}') center center / cover no-repeat;">
    <div class="bg-white p-8 rounded-[12px] shadow-md w-[400px]">
        <h2 class="mb-6 text-center font-bold" style="font-family: 'Cinzel', serif; font-size: 32px;">Reset Password</h2>
        <p class="mb-6 text-center text-gray-600">Enter your new password below</p>
        
        <!-- Display success/error messages -->
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded text-green-600 text-sm">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded text-red-600 text-sm">
                {{ session('error') }}
            </div>
        @endif
        
        <!-- Display validation errors -->
        @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded text-red-600 text-sm">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        
        <form method="POST" action="{{ route('password.reset.submit') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">
            
            <div class="mb-4">
                <label class="block mb-1 font-medium" for="password"
                    style="font-family: 'Inter', sans-serif; font-size: 12px;">New Password</label>
                <input class="w-full h-[50px] px-3 py-2 border rounded border-[#9CA3AF] focus:border-orange-500 transition-colors"
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter new password"
                    required>
            </div>
            
            <div class="mb-6">
                <label class="block mb-1 font-medium" for="password_confirmation"
                    style="font-family: 'Inter', sans-serif; font-size: 12px;">Confirm New Password</label>
                <input class="w-full h-[50px] px-3 py-2 border rounded border-[#9CA3AF] focus:border-orange-500 transition-colors"
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Confirm new password"
                    required>
            </div>
            
            <button
                class="w-full bg-amber-600 text-white py-3 rounded hover:bg-amber-700 transition hover:cursor-pointer mb-4"
                type="submit"
            >
                Reset Password
            </button>
            
            <div class="text-center">
                <a href="{{ route('login') }}" class="text-amber-700 hover:underline text-sm">
                    ← Back to Login
                </a>
            </div>
        </form>
    </div>
</body>
</html>