<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Caffe Arabica</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&display=swap" rel="stylesheet">
    <style>
        .responsive-heading {
            font-size: clamp(1.8rem, 4vw + 1rem, 3rem); 
            line-height: 1.2;
        }

        .responsive-text {
            font-size: clamp(1rem, 2vw + 0.5rem, 1.5rem); 
            letter-spacing: 0.1em;
            line-height: 1.6;
        }

        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
        }
        
        .audio-bar {
            animation: audioWave 1.5s ease-in-out infinite;
        }
        
        @keyframes audioWave {
            0%, 100% { transform: scaleY(1); }
            50% { transform: scaleY(1.5); }
        }
    </style>
</head>
<body class="bg-amber-50 min-h-screen relative overflow-hidden select-none"
      style="background: url('{{ asset('/images/WELCOME (1).svg') }}') center center / cover no-repeat; cursor: pointer;">

    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-5xl px-6 flex flex-col items-center justify-center gap-6 md:gap-10">
        <h1 class="text-white font-bold text-center responsive-heading text-shadow" 
            style="font-family: 'Cinzel', serif;">
            YOU ARE USING AN AI POWERED KIOSK
        </h1>
        <p class="text-white font-normal text-center responsive-text text-shadow max-w-3xl" 
           style="font-family:'Times New Roman', Times, serif;">
            This Kiosk is powered by AI Voice Technology to make your 
            ordering experience faster and more convenient. With voice 
            assisted features and minimal touch interaction, you can place 
            your order with ease and comfort.
        </p>
        
        <p class="text-white/70 text-sm mt-4 font-light animate-pulse">
            (Tap screen to skip)
        </p>
    </div>

    <div id="audioIndicator" class="absolute bottom-16 left-1/2 -translate-x-1/2 flex items-center space-x-2 opacity-0 transition-opacity duration-300">
        <div class="flex items-center space-x-1">
            <div class="w-1 h-3 bg-blue-400 audio-bar"></div>
            <div class="w-1 h-5 bg-blue-400 audio-bar" style="animation-delay: 0.1s;"></div>
            <div class="w-1 h-4 bg-blue-400 audio-bar" style="animation-delay: 0.2s;"></div>
            <div class="w-1 h-6 bg-blue-400 audio-bar" style="animation-delay: 0.3s;"></div>
            <div class="w-1 h-3 bg-blue-400 audio-bar" style="animation-delay: 0.4s;"></div>
        </div>
        <span class="text-white text-sm font-light text-shadow">Audio playing...</span>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const textToSpeak = "This Kiosk is powered by AI Voice Technology to make your ordering experience faster and more convenient. With voice assisted features and minimal touch interaction, you can place your order with ease and comfort.";
            let isRedirecting = false;

            // --- REDIRECT FUNCTION (Called by End of Speech OR User Click) ---
            function proceedToNextPage() {
                if (isRedirecting) return;
                isRedirecting = true;

                // 1. Stop Audio Immediately
                if ('speechSynthesis' in window) {
                    speechSynthesis.cancel();
                }

                // 2. Visual Feedback (Fade out)
                document.body.style.opacity = '0';
                document.body.style.transition = 'opacity 0.5s';

                // 3. Redirect
                setTimeout(() => {
                    window.location.href = "{{ route('customer.orderArea') }}";
                }, 500);
            }

            // --- SPEECH FUNCTION ---
            function playAudio() {
                if ('speechSynthesis' in window) {
                    const speech = new SpeechSynthesisUtterance();
                    const audioIndicator = document.getElementById('audioIndicator');
                    
                    speech.text = textToSpeak;
                    speech.volume = 1;
                    speech.rate = 0.95;
                    speech.lang = 'en-US';

                    // Voice Selection (Tries to find a good one)
                    const voices = speechSynthesis.getVoices();
                    const preferredVoice = voices.find(v => v.name.includes('Zira')) || 
                                           voices.find(v => v.name.includes('Google US')) || 
                                           voices.find(v => v.name.includes('Female')) || 
                                           voices[0];
                    if (preferredVoice) speech.voice = preferredVoice;

                    // Show Indicator on Start
                    speech.onstart = () => {
                        audioIndicator.classList.remove('opacity-0');
                        audioIndicator.classList.add('opacity-100');
                    };

                    // Redirect on End
                    speech.onend = () => {
                        proceedToNextPage();
                    };

                    speech.onerror = (e) => {
                        console.error("Speech Error", e);
                        // If blocked by browser policy, proceed anyway after short delay
                        setTimeout(proceedToNextPage, 2000);
                    };

                    // Start Speaking
                    speechSynthesis.cancel();
                    speechSynthesis.speak(speech);
                } else {
                    // Fallback if no TTS support
                    setTimeout(proceedToNextPage, 3000);
                }
            }

            // --- EVENT LISTENERS ---

            // 1. "Touch to Skip" - Any click on the body skips immediately
            document.body.addEventListener('click', proceedToNextPage);
            document.body.addEventListener('touchstart', proceedToNextPage);

            // 2. Auto-Play on Load
            // We verify voices are loaded, then play.
            if ('speechSynthesis' in window) {
                // Chrome/Brave need this to ensure voices are ready
                if (speechSynthesis.getVoices().length === 0) {
                    speechSynthesis.onvoiceschanged = playAudio;
                } else {
                    playAudio();
                }
            } else {
                setTimeout(proceedToNextPage, 3000);
            }
        });
    </script>
</body>
</html>