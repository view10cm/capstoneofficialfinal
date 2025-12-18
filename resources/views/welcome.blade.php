<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Caffe Arabica</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&display=swap" rel="stylesheet">
    <style>
        /* Responsive typography for tablet */
        @media (max-width: 1024px) {
            .responsive-font {
                font-size: clamp(0.875rem, 2vw + 0.5rem, 1.5rem);
            }
            
            .responsive-heading {
                font-size: clamp(1.5rem, 5vw + 1rem, 3rem);
                line-height: 1.2;
            }
            
            .responsive-subheading {
                font-size: clamp(0.75rem, 2vw + 0.5rem, 1.25rem);
                letter-spacing: 0.05em;
            }
        }
        
        @media (max-width: 768px) {
            .responsive-font {
                font-size: clamp(0.75rem, 3vw + 0.5rem, 1.25rem);
            }
            
            .responsive-heading {
                font-size: clamp(1.25rem, 6vw + 1rem, 2.5rem);
                text-align: center;
                padding: 0 1rem;
            }
            
            .responsive-subheading {
                font-size: clamp(0.625rem, 2.5vw + 0.5rem, 1rem);
                text-align: center;
                padding: 0 1rem;
            }
            
            .bottom-overlay {
                padding: 1rem 0;
            }
            
            .bottom-overlay button {
                font-size: clamp(1rem, 4vw + 0.5rem, 1.75rem);
                margin-bottom: 0.5rem;
            }
            
            .bottom-overlay span {
                font-size: clamp(0.75rem, 2vw + 0.5rem, 0.875rem);
            }
        }
        
        /* Ensure proper spacing on tablets */
        @media (min-width: 769px) and (max-width: 1024px) {
            body {
                padding: 2rem;
            }
            
            .top-quote {
                top: 15%;
                max-width: 80%;
                text-align: center;
            }
            
            .main-heading {
                top: 45%;
                max-width: 90%;
                text-align: center;
            }
            
            .sub-heading {
                top: 58%;
                max-width: 80%;
                text-align: center;
            }
        }
        
        /* Prevent text overflow on all devices */
        .text-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }
    </style>
</head>
<body class="bg-amber-50 min-h-screen flex items-center justify-center relative"
      style="background: url('{{ asset('/images/START (1).svg') }}') center center / cover no-repeat;">

    <!-- Top Quote -->
    <div class="absolute top-20 left-1/2 -translate-x-1/2 text-white text-[24px] font-normal responsive-font top-quote text-container px-4"
         style="font-family:'Times New Roman', Times, serif;">
        <p class="text-center">A cup of coffee a day without God is tasteless</p>
    </div>
    
    <!-- Main Heading -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-white font-bold text-[48px] responsive-heading main-heading text-container px-4"
         style="font-family: 'Cinzel', serif;">
        <h1 class="text-center leading-tight">WELCOME TO<br>CAFFE ARABICA</h1>
    </div>
    
    <!-- Sub Heading -->
    <div class="absolute left-1/2 top-[53%] -translate-x-1/2 text-white text-[24px] font-normal responsive-subheading sub-heading text-container px-4"
         style="font-family:'Times New Roman', Times, serif; letter-spacing: 0.1em;">
        <p class="text-center">A PREMIUM DINING EXPERIENCE</p>
    </div>

    <!-- Bottom "Touch to start" button overlay -->
    <div class="fixed bottom-0 left-0 w-full bg-black bg-opacity-50 flex flex-col items-center py-6 cursor-pointer bottom-overlay"
         style="cursor: pointer;">
        <!-- Updated: Added route to system-description -->
        <a href="{{ route('system-description') }}">
            <button class="text-white text-3xl font-semibold mb-2 focus:outline-none responsive-font" 
                    style="font-family: 'Cinzel', serif; cursor: pointer;">
                Touch to start
            </button>
        </a>
        <span class="text-white text-base opacity-80 responsive-font text-center px-4"
              style="font-family:'Times New Roman', Times, serif; cursor: pointer;">
            Ready to order? Tap to begin.
        </span>
    </div>
</body>
</html>