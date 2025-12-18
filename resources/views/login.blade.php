<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - Caffe Arabica</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&display=swap" rel="stylesheet">
</head>

<body class="bg-amber-50 min-h-screen flex items-center justify-center"
    style="background: url('{{ asset('/images/Login.svg') }}') center center / cover no-repeat;">
    <div class="bg-white p-8 rounded-[12px] shadow-md w-[400px]">
        <h2 class="mb-6 text-center font-bold" style="font-family: 'Cinzel', serif; font-size: 32px;">Caffe Arabica</h2>

        <!-- Display error message if account doesn't exist -->
        @if ($errors->has('email'))
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded text-red-600 text-sm">
                {{ $errors->first('email') }}
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf
            <div class="mb-4">
                <label class="block mb-1 font-medium" for="email"
                    style="font-family: 'Inter', sans-serif; font-size: 12px;">Email Address</label>
                <input class="w-[336px] h-[50px] px-3 py-2 border rounded border-[#9CA3AF] focus:border-orange-500 transition-colors"
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    required
                    autofocus
                    value="{{ old('email') }}">
                <p id="emailError" class="text-red-500 text-xs mt-1 hidden">Invalid Email address</p>
            </div>
            <div class="mb-6">
                <label class="block mb-1 font-medium" for="password"
                    style="font-family: 'Inter', sans-serif; font-size: 12px;">Password</label>
                <input class="w-[336px] h-[50px] px-3 border rounded border-[#9CA3AF] focus:border-orange-500 transition-colors"
                    type="password" id="password"
                    name="password" placeholder="Enter your password" required>
            </div>
            <button
                id="signInBtn"
                class="w-full bg-amber-600 text-white py-2 rounded hover:bg-amber-700 transition disabled:bg-amber-400 disabled:cursor-not-allowed hover:cursor-pointer"
                type="submit"
                disabled
            >
                Sign in
            </button>
        </form>
        <div class="m-2 text-center">
            <a href="" class="text-amber-700 hover:underline text-sm">Forgot
                Password?</a>
        </div>
    </div>
    
    <!-- Include external JavaScript file -->
    <script src="{{ asset('js/login-validation.js') }}"></script>
</body>

</html>