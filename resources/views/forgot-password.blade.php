<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Caffe Arabica</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&display=swap" rel="stylesheet">
</head>
<body class="bg-amber-50 min-h-screen flex items-center justify-center"
    style="background: url('{{ asset('/images/Login.svg') }}') center center / cover no-repeat;">
    <div class="bg-white p-8 rounded-[12px] shadow-md w-[400px]">
        <h2 class="mb-6 text-center font-bold" style="font-family: 'Cinzel', serif; font-size: 32px;">Caffe Arabica</h2>
        <p class="mb-6 text-center text-gray-600">Enter your email to reset your password</p>
        
        <!-- Display success message -->
        @if(session('status'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded text-green-600 text-sm">
                {{ session('status') }}
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
        
        <form method="POST" action="{{ route('forgot-password.send') }}">
            @csrf
            <div class="mb-6">
                <label class="block mb-1 font-medium" for="email"
                    style="font-family: 'Inter', sans-serif; font-size: 12px;">Email Address</label>
                <input class="w-full h-[50px] px-3 py-2 border rounded border-[#9CA3AF] focus:border-orange-500 transition-colors"
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    required
                    autofocus
                    value="{{ old('email') }}">
            </div>
            
            <button
                class="w-full bg-amber-600 text-white py-3 rounded hover:bg-amber-700 transition hover:cursor-pointer mb-4"
                type="submit"
            >
                Send Reset Link
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