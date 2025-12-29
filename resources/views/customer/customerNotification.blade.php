<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Caffe Arabica</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
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

    <!-- Subtle audio indicator (optional visual feedback) -->
    <div id="audioIndicator" class="absolute bottom-10 left-1/2 -translate-x-1/2 flex items-center space-x-2 opacity-0 transition-opacity duration-300">
        <div class="flex items-center space-x-1">
            <div class="w-1 h-3 bg-blue-400 animate-pulse audio-bar"></div>
            <div class="w-1 h-5 bg-blue-400 animate-pulse audio-bar" style="animation-delay: 0.1s;"></div>
            <div class="w-1 h-4 bg-blue-400 animate-pulse audio-bar" style="animation-delay: 0.2s;"></div>
            <div class="w-1 h-6 bg-blue-400 animate-pulse audio-bar" style="animation-delay: 0.3s;"></div>
            <div class="w-1 h-3 bg-blue-400 animate-pulse audio-bar" style="animation-delay: 0.4s;"></div>
        </div>
        <span class="text-white text-sm font-light">Audio playing...</span>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the text to speak
            const textToSpeak = "This Kiosk is powered by AI Voice Technology to make your ordering experience faster and more convenient. With voice assisted features and minimal touch interaction, you can place your order with ease and comfort.";
            
            // Check if browser supports speech synthesis
            if ('speechSynthesis' in window) {
                let speech = new SpeechSynthesisUtterance();
                const audioIndicator = document.getElementById('audioIndicator');
                
                // Configure speech settings
                speech.text = textToSpeak;
                speech.volume = 0.8; // Medium volume
                speech.rate = 0.9; // Slightly slower for clarity
                speech.pitch = 1;
                speech.lang = 'en-US';
                
                // Try to set a pleasant voice
                function setVoice() {
                    const voices = speechSynthesis.getVoices();
                    // Prefer natural-sounding voices
                    const preferredVoice = voices.find(voice => 
                        voice.lang.includes('en') && 
                        (voice.name.includes('Google') || voice.name.includes('Natural'))
                    ) || voices.find(voice => voice.lang.includes('en'));
                    
                    if (preferredVoice) {
                        speech.voice = preferredVoice;
                    }
                }
                
                // Wait for voices to load
                if (speechSynthesis.onvoiceschanged !== undefined) {
                    speechSynthesis.onvoiceschanged = setVoice;
                }
                setVoice(); // Call immediately in case voices are already loaded
                
                // Show/hide audio indicator
                speech.onstart = function() {
                    audioIndicator.classList.remove('opacity-0');
                    audioIndicator.classList.add('opacity-100');
                };
                
                speech.onend = function() {
                    audioIndicator.classList.remove('opacity-100');
                    audioIndicator.classList.add('opacity-0');
                    
                    // Redirect to customerOrderArea page after speech ends
                    setTimeout(() => {
                        // Use Laravel route helper for the redirect
                        window.location.href = "{{ route('customer.orderArea') }}";
                    }, 1000); // 1 second delay before redirect
                };
                
                speech.onerror = function(event) {
                    console.error('Speech synthesis error:', event);
                    audioIndicator.classList.remove('opacity-100');
                    audioIndicator.classList.add('opacity-0');
                    
                    // Even if speech fails, redirect after a delay
                    setTimeout(() => {
                        window.location.href = "{{ route('customer.orderArea') }}";
                    }, 2000); // 2 second delay before redirect on error
                };
                
                // Start speaking after a short delay to let page load
                setTimeout(() => {
                    // Check if speech is already in progress
                    if (!speechSynthesis.speaking) {
                        setVoice();
                        speechSynthesis.speak(speech);
                    }
                }, 1500); // 1.5 second delay for page to fully load
                
            } else {
                // Browser doesn't support speech synthesis
                console.warn('Speech synthesis not supported in this browser.');
                document.getElementById('audioIndicator').style.display = 'none';
                
                // Redirect immediately if no speech support
                setTimeout(() => {
                    window.location.href = "{{ route('customer.orderArea') }}";
                }, 3000); // Wait 3 seconds then redirect
            }
            
            // Optional: Add CSS for audio bar animation
            const style = document.createElement('style');
            style.textContent = `
                .audio-bar {
                    animation: audioWave 1.5s ease-in-out infinite;
                }
                
                @keyframes audioWave {
                    0%, 100% { transform: scaleY(1); }
                    50% { transform: scaleY(1.5); }
                }
            `;
            document.head.appendChild(style);
        });
        
        // Optional: Re-speak when page becomes visible again (for kiosk screensavers)
        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'visible' && 'speechSynthesis' in window) {
                // Wait a moment then speak if not already speaking
                setTimeout(() => {
                    if (!speechSynthesis.speaking) {
                        const textToSpeak = "This Kiosk is powered by AI Voice Technology to make your ordering experience faster and more convenient. With voice assisted features and minimal touch interaction, you can place your order with ease and comfort.";
                        const speech = new SpeechSynthesisUtterance(textToSpeak);
                        speech.volume = 0.8;
                        speech.rate = 0.9;
                        speech.lang = 'en-US';
                        speechSynthesis.speak(speech);
                    }
                }, 1000);
            }
        });
    </script>
</body>
</html>