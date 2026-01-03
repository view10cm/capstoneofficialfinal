<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Caffe Arabica</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&display=swap" rel="stylesheet">
    <style>
        /* Using CSS Clamp for smooth sizing without complex media queries.
           Syntax: clamp(minimum-size, preferred-size, maximum-size)
        */
        .responsive-quote {
            font-size: clamp(0.9rem, 1.5vw + 0.5rem, 1.5rem);
            line-height: 1.4;
        }

        .responsive-heading {
            /* Text will shrink/grow smoothly between 2rem and 4.5rem */
            font-size: clamp(2rem, 5vw + 1rem, 4.5rem); 
            line-height: 1.1;
        }

        .responsive-subheading {
            font-size: clamp(0.8rem, 2vw + 0.5rem, 1.5rem); 
            letter-spacing: 0.15em;
        }

        .responsive-btn {
            font-size: clamp(1.2rem, 3vw, 2rem);
        }
        
        /* Optional: Adds a shadow to text to make it readable on any background */
        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0,0,0,0.6);
        }
    </style>
</head>
<body class="bg-amber-50 min-h-screen relative overflow-hidden"
      style="background: url('{{ asset('/images/START (1).svg') }}') center center / cover no-repeat;">

    <div class="absolute top-[15%] left-0 w-full px-4 text-center">
        <p class="text-white font-normal responsive-quote text-shadow"
           style="font-family:'Times New Roman', Times, serif;">
            A cup of coffee a day without God is tasteless
        </p>
    </div>
    
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-5xl px-6 flex flex-col items-center justify-center gap-4 md:gap-6">
        
        <h1 class="text-white font-bold text-center responsive-heading text-shadow"
            style="font-family: 'Cinzel', serif;">
            WELCOME TO<br>CAFFE ARABICA
        </h1>
        
        <p class="text-white font-normal text-center responsive-subheading text-shadow"
           style="font-family:'Times New Roman', Times, serif;">
            A PREMIUM DINING EXPERIENCE
        </p>

    </div>

    <a href="{{ route('customer.notification') }}" 
       class="fixed bottom-0 left-0 w-full bg-black bg-opacity-60 flex flex-col items-center py-6 cursor-pointer backdrop-blur-sm hover:bg-opacity-70 transition-all duration-300 group text-decoration-none">
       
        <span class="text-white font-semibold mb-2 responsive-btn group-hover:scale-105 transition-transform duration-300" 
              style="font-family: 'Cinzel', serif;">
            Touch to start
        </span>
        
        <span class="text-white text-sm md:text-base opacity-80"
              style="font-family:'Times New Roman', Times, serif;">
            Ready to order? Tap anywhere to begin.
        </span>
    </a>

</body>
</html>