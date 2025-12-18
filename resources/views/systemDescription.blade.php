<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Caffe Arabica</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <script>
        // Inline JavaScript for auto-navigation
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                window.location.href = '{{ route("login") }}';
                // OR: window.location.href = '/login';
            }, 7000); // 7 seconds
        });
    </script>
</head>
<body class="bg-amber-50 min-h-screen flex items-center justify-center relative"
      style="background: url('{{ asset('/images/WELCOME (1).svg') }}') center center / cover no-repeat;">

    <h1 class="absolute top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 text-white font-bold text-[48px] responsive-font" style="font-family: 'Cinzel', serif;">
        YOU ARE USING AN AI POWERED KIOSK
    </h1>
    <span class="absolute left-1/2 top-[45%] -translate-x-1/2 text-white text-[24px] font-normal responsive-font text-center w-full px-4" style="font-family:'Times New Roman', Times, serif; letter-spacing: 0.1em;">
        This Kiosk is powered by AI Voice Technology to make your <br>
        ordering experience faster and more convenient. With voice<br>
        assisted features and minimal touch interaction, you can place <br>
        your order with ease and comfort.
    </span>
</body>
</html>