// customer-order-voice.js
// Voice assistant functionality with wake word detection and TTS responses

let speechRecognition = null;
let isListening = false;
let silenceTimeout = null;
let isProcessingVoiceCommand = false;
let isWaitingForWakeWord = true;
let wakeWordDetected = false;
let lastSpeechTime = 0;
let audioContext = null;
let analyser = null;
let microphone = null;
let javascriptNode = null;
let isAudioContextInitialized = false;
let speechSynthesis = window.speechSynthesis;
let isSpeaking = false;

// Initialize voice DOM elements
function initializeVoiceDOMElements() {
    domElements.voiceStatus = document.getElementById('voice-status');
    domElements.voiceFeedback = document.getElementById('voice-feedback');
    domElements.voiceCommandDisplay = document.getElementById('voice-command-display');
    domElements.voiceTranscript = document.getElementById('voice-transcript');
}

// Initialize audio context for voice activity detection
async function initializeAudioContext() {
    if (isAudioContextInitialized) return true;
    
    try {
        const AudioContext = window.AudioContext || window.webkitAudioContext;
        audioContext = new AudioContext();
        
        // Get microphone access
        const stream = await navigator.mediaDevices.getUserMedia({ 
            audio: {
                echoCancellation: true,
                noiseSuppression: true,
                autoGainControl: true
            } 
        });
        
        // Create audio nodes
        microphone = audioContext.createMediaStreamSource(stream);
        analyser = audioContext.createAnalyser();
        analyser.fftSize = 512;
        analyser.smoothingTimeConstant = 0.8;
        
        microphone.connect(analyser);
        
        // Create script processor for analyzing audio
        javascriptNode = audioContext.createScriptProcessor(2048, 1, 1);
        analyser.connect(javascriptNode);
        javascriptNode.connect(audioContext.destination);
        
        isAudioContextInitialized = true;
        console.log('Audio context initialized for voice activity detection');
        return true;
        
    } catch (error) {
        console.error('Error initializing audio context:', error);
        return false;
    }
}


function playChime() {
    try {
        // Create audio context for chime
        const chimeContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = chimeContext.createOscillator();
        const gainNode = chimeContext.createGain();
        
        // Configure chime sound
        oscillator.type = 'sine';
        oscillator.frequency.setValueAtTime(800, chimeContext.currentTime); // Higher pitch
        oscillator.frequency.exponentialRampToValueAtTime(1200, chimeContext.currentTime + 0.3); // Rise
        
        // Configure volume envelope
        gainNode.gain.setValueAtTime(0, chimeContext.currentTime);
        gainNode.gain.linearRampToValueAtTime(0.3, chimeContext.currentTime + 0.1); // Fade in
        gainNode.gain.exponentialRampToValueAtTime(0.01, chimeContext.currentTime + 1); // Fade out
        
        // Connect nodes
        oscillator.connect(gainNode);
        gainNode.connect(chimeContext.destination);
        
        // Play chime
        oscillator.start();
        oscillator.stop(chimeContext.currentTime + 1); // Stop after 1 second
        
        // Also add visual feedback
        const voiceIcon = document.getElementById('voice-icon');
        if (voiceIcon) {
            voiceIcon.classList.add('wake-word-detected');
            setTimeout(() => {
                voiceIcon.classList.remove('wake-word-detected');
            }, 500);
        }
        
    } catch (error) {
        console.log('Could not play chime sound:', error);
        // Fallback visual feedback
        const voiceIcon = document.getElementById('voice-icon');
        if (voiceIcon) {
            voiceIcon.classList.add('wake-word-detected');
            setTimeout(() => {
                voiceIcon.classList.remove('wake-word-detected');
            }, 500);
        }
    }
}

function playWelcomeChime() {
    try {
        // Create audio context for welcome chime
        const chimeContext = new (window.AudioContext || window.webkitAudioContext)();
        
        // First tone
        const oscillator1 = chimeContext.createOscillator();
        const gainNode1 = chimeContext.createGain();
        
        oscillator1.type = 'sine';
        oscillator1.frequency.setValueAtTime(523.25, chimeContext.currentTime); // C5
        gainNode1.gain.setValueAtTime(0, chimeContext.currentTime);
        gainNode1.gain.linearRampToValueAtTime(0.2, chimeContext.currentTime + 0.1);
        gainNode1.gain.exponentialRampToValueAtTime(0.01, chimeContext.currentTime + 0.8);
        
        oscillator1.connect(gainNode1);
        gainNode1.connect(chimeContext.destination);
        
        // Second tone (delayed)
        const oscillator2 = chimeContext.createOscillator();
        const gainNode2 = chimeContext.createGain();
        
        oscillator2.type = 'sine';
        oscillator2.frequency.setValueAtTime(659.25, chimeContext.currentTime + 0.3); // E5
        gainNode2.gain.setValueAtTime(0, chimeContext.currentTime + 0.3);
        gainNode2.gain.linearRampToValueAtTime(0.2, chimeContext.currentTime + 0.4);
        gainNode2.gain.exponentialRampToValueAtTime(0.01, chimeContext.currentTime + 1.2);
        
        oscillator2.connect(gainNode2);
        gainNode2.connect(chimeContext.destination);
        
        // Third tone (higher, delayed)
        const oscillator3 = chimeContext.createOscillator();
        const gainNode3 = chimeContext.createGain();
        
        oscillator3.type = 'sine';
        oscillator3.frequency.setValueAtTime(783.99, chimeContext.currentTime + 0.6); // G5
        gainNode3.gain.setValueAtTime(0, chimeContext.currentTime + 0.6);
        gainNode3.gain.linearRampToValueAtTime(0.2, chimeContext.currentTime + 0.7);
        gainNode3.gain.exponentialRampToValueAtTime(0.01, chimeContext.currentTime + 1.8);
        
        oscillator3.connect(gainNode3);
        gainNode3.connect(chimeContext.destination);
        
        // Play all tones
        oscillator1.start();
        oscillator1.stop(chimeContext.currentTime + 0.8);
        
        oscillator2.start(chimeContext.currentTime + 0.3);
        oscillator2.stop(chimeContext.currentTime + 1.2);
        
        oscillator3.start(chimeContext.currentTime + 0.6);
        oscillator3.stop(chimeContext.currentTime + 1.8);
        
        // Visual feedback with longer animation
        const voiceIcon = document.getElementById('voice-icon');
        if (voiceIcon) {
            voiceIcon.classList.add('wake-word-detected');
            
            // Add pulsing animation
            voiceIcon.style.animation = 'wakeWordPulse 0.5s ease-in-out 4';
            
            setTimeout(() => {
                voiceIcon.classList.remove('wake-word-detected');
                voiceIcon.style.animation = '';
            }, 2000);
        }
        
    } catch (error) {
        console.log('Could not play welcome chime:', error);
        // Simple fallback
        playChime();
    }
}


// Text-to-Speech function
function speakText(text) {
    if (!speechSynthesis) {
        console.error('Speech synthesis not supported');
        return Promise.resolve();
    }
    
    // Cancel any ongoing speech
    speechSynthesis.cancel();
    
    return new Promise((resolve) => {
        const utterance = new SpeechSynthesisUtterance(text);
        
        // Configure voice settings
        utterance.volume = 1;
        utterance.rate = 1;
        utterance.pitch = 1;
        utterance.lang = 'en-US';
        
        // Try to get a female voice if available
        const voices = speechSynthesis.getVoices();
        const femaleVoice = voices.find(voice => 
            voice.lang.includes('en') && 
            voice.name.toLowerCase().includes('female')
        );
        
        if (femaleVoice) {
            utterance.voice = femaleVoice;
        }
        
        isSpeaking = true;
        
        utterance.onstart = function() {
            console.log('Speaking:', text);
            updateVoiceUIForSpeaking();
        };
        
        utterance.onend = function() {
            console.log('Finished speaking');
            isSpeaking = false;
            updateVoiceUIForListening(); // Go back to listening mode
            resolve();
        };
        
        utterance.onerror = function(event) {
            console.error('Speech synthesis error:', event);
            isSpeaking = false;
            updateVoiceUIForListening();
            resolve();
        };
        
        speechSynthesis.speak(utterance);
    });
}

// Update UI for system speaking
function updateVoiceUIForSpeaking() {
    const voiceIcon = document.getElementById('voice-icon');
    const statusBadge = document.getElementById('voice-status-badge');
    const levelIndicator = document.getElementById('voice-level-indicator');
    
    if (voiceIcon) {
        voiceIcon.style.backgroundColor = '#8B5CF6';
        voiceIcon.innerHTML = '<i class="fas fa-volume-up"></i>';
    }
    if (statusBadge) {
        statusBadge.textContent = 'Speaking...';
        statusBadge.className = 'text-xs bg-purple-100 text-purple-800 px-2 py-0.5 rounded';
    }
    if (levelIndicator) levelIndicator.classList.add('hidden');
}

// Start voice activity detection
function startVoiceActivityDetection() {
    if (!isAudioContextInitialized || !javascriptNode) return;
    
    let speechActive = false;
    let consecutiveSilenceFrames = 0;
    const SILENCE_THRESHOLD = 15;
    const SILENCE_FRAMES_REQUIRED = 60;
    
    javascriptNode.onaudioprocess = function() {
        if (!isListening || isWaitingForWakeWord || isSpeaking) return;
        
        const array = new Uint8Array(analyser.frequencyBinCount);
        analyser.getByteFrequencyData(array);
        
        // Calculate average volume
        let values = 0;
        for (let i = 0; i < array.length; i++) {
            values += array[i];
        }
        const average = values / array.length;
        
        // Check if speech is detected
        if (average > SILENCE_THRESHOLD) {
            consecutiveSilenceFrames = 0;
            speechActive = true;
            resetSilenceTimeout();
        } else {
            consecutiveSilenceFrames++;
            if (consecutiveSilenceFrames > SILENCE_FRAMES_REQUIRED && speechActive) {
                speechActive = false;
                consecutiveSilenceFrames = 0;
                
                const currentTranscript = domElements.voiceTranscript.textContent;
                if (currentTranscript && !currentTranscript.includes('Listening for') && 
                    !currentTranscript.includes('Say "Hey Arabica"') &&
                    !currentTranscript.includes('What would you like')) {
                    console.log('12 seconds of silence detected, processing command...');
                    processDetectedCommand(currentTranscript.replace(/[""]/g, '').trim());
                }
            }
        }
    };
}

// Process detected command after silence
function processDetectedCommand(transcript) {
    if (transcript && transcript.length > 2 && !isProcessingVoiceCommand && !isWaitingForWakeWord && !isSpeaking) {
        console.log('Processing command after silence:', transcript);
        saveTranscript(transcript);
        processVoiceCommand(transcript);
    }
}

// Process voice command with TTS responses
function processVoiceCommandWithTTS(transcript) {
    const command = transcript.toLowerCase();
    
    if (isProcessingVoiceCommand) {
        console.log('Already processing a voice command, skipping...');
        return;
    }
    
    isProcessingVoiceCommand = true;
    
    // Check for different command types
    if (command.includes('add') || command.includes('order') || command.includes('want')) {
        const words = command.split(' ');
        const orderKeywords = ['add', 'order', 'want', 'get', 'take', 'have'];
        let addIndex = -1;
        
        for (let i = 0; i < words.length; i++) {
            if (orderKeywords.includes(words[i])) {
                addIndex = i;
                break;
            }
        }
        
        if (addIndex !== -1 && words.length > addIndex + 1) {
            const productNameWords = [];
            for (let i = addIndex + 1; i < words.length; i++) {
                const fillerWords = ['a', 'an', 'the', 'some', 'please', 'i', 'would', 'like', 'to'];
                if (!fillerWords.includes(words[i])) {
                    productNameWords.push(words[i]);
                }
            }
            const productName = productNameWords.join(' ');
            
            if (productName.trim() === '') {
                const response = "Please specify what you want to add. For example, say 'Add pad Thai' or 'I want pad Thai'";
                domElements.voiceFeedback.textContent = response;
                speakText(response);
                isProcessingVoiceCommand = false;
                return;
            }
            
            console.log('Extracted product name from command:', productName);
            
            // Speak acknowledgment
            speakText(`Looking for ${productName} in our menu. One moment please.`, function() {
                matchAndProcessProduct(productName);
            });
            
            return;
        } else {
            const response = "Please specify what you want to add. For example, say 'Add pad Thai' or 'I want pad Thai'";
            domElements.voiceFeedback.textContent = response;
            speakText(response);
            isProcessingVoiceCommand = false;
            return;
        }
    } 
    else if (command.includes('show') || command.includes('display')) {
        if (command.includes('specials')) {
            const response = "Showing you our specials menu.";
            domElements.voiceFeedback.textContent = response;
            speakText(response, function() {
                const specialsBtn = document.querySelector('[data-category="specials"]');
                if (specialsBtn) {
                    specialsBtn.click();
                }
                isProcessingVoiceCommand = false;
                setTimeout(() => resetToWakeWordDetection(), 3000);
            });
        } else if (command.includes('drinks')) {
            const response = "Showing you our drinks menu.";
            domElements.voiceFeedback.textContent = response;
            speakText(response, function() {
                const drinksBtn = document.querySelector('[data-category="drinks"]');
                if (drinksBtn) {
                    drinksBtn.click();
                }
                isProcessingVoiceCommand = false;
                setTimeout(() => resetToWakeWordDetection(), 3000);
            });
        } else if (command.includes('appetizers')) {
            const response = "Showing you our appetizers menu.";
            domElements.voiceFeedback.textContent = response;
            speakText(response, function() {
                const appetizersBtn = document.querySelector('[data-category="appetizers"]');
                if (appetizersBtn) {
                    appetizersBtn.click();
                }
                isProcessingVoiceCommand = false;
                setTimeout(() => resetToWakeWordDetection(), 3000);
            });
        } else if (command.includes('main course') || command.includes('main-course')) {
            const response = "Showing you our main course menu.";
            domElements.voiceFeedback.textContent = response;
            speakText(response, function() {
                const mainCourseBtn = document.querySelector('[data-category="main-course"]');
                if (mainCourseBtn) {
                    mainCourseBtn.click();
                }
                isProcessingVoiceCommand = false;
                setTimeout(() => resetToWakeWordDetection(), 3000);
            });
        } else {
            const response = "What would you like me to show? You can say 'show specials', 'show drinks', or 'show appetizers'.";
            domElements.voiceFeedback.textContent = response;
            speakText(response);
            isProcessingVoiceCommand = false;
        }
    }
    else if (command.includes('clear') || command.includes('remove all')) {
        const response = "Clearing your order. All items have been removed.";
        domElements.voiceFeedback.textContent = response;
        speakText(response, function() {
            clearOrder();
            isProcessingVoiceCommand = false;
            setTimeout(() => resetToWakeWordDetection(), 3000);
        });
    }
    else if (command.includes('checkout') || command.includes('pay')) {
        const response = "Opening checkout for you.";
        domElements.voiceFeedback.textContent = response;
        speakText(response, function() {
            showCheckoutModal();
            isProcessingVoiceCommand = false;
        });
    }
    else if (command.includes('thank you') || command.includes('thanks')) {
        const response = "You're welcome! Is there anything else I can help you with?";
        domElements.voiceFeedback.textContent = response;
        speakText(response);
        isProcessingVoiceCommand = false;
        setTimeout(() => resetToWakeWordDetection(), 3000);
    }
    else if (command.includes('help')) {
        showVoiceHelp();
        isProcessingVoiceCommand = false;
    }
    else {
        const response = "I heard you say: " + transcript + ". I'm not sure how to help with that. You can say things like 'Add pork barbecue', 'Show specials', or 'Clear order'.";
        domElements.voiceFeedback.textContent = response;
        speakText(response);
        isProcessingVoiceCommand = false;
        setTimeout(() => resetToWakeWordDetection(), 3000);
    }
}

// Match and process product with TTS feedback
async function matchAndProcessProduct(productName) {
    try {
        const matchResult = await matchTranscriptWithMenuItem(productName);
        
        if (matchResult && matchResult.matchedMenuItem) {
            const confidenceLevel = matchResult.confidence;
            const matchedItem = matchResult.matchedMenuItem;
            const productDetails = matchResult.product;
            
            // Speak based on confidence level
            if (confidenceLevel === 'Confident' && productDetails) {
                const response = `I found ${matchedItem} in our menu. Adding it to your order now.`;
                domElements.voiceFeedback.textContent = response;
                
                speakText(response, function() {
                    autoAddProductToOrder(productDetails, 'voice-auto');
                    isProcessingVoiceCommand = false;
                    setTimeout(() => resetToWakeWordDetection(), 3000);
                });
                
            } else if (confidenceLevel === 'Partially Confident') {
                const response = `I think you might be looking for ${matchedItem}. Should I add this to your order?`;
                domElements.voiceFeedback.textContent = response;
                
                speakText(response, function() {
                    showPartialConfirmation(matchedItem, '60-79%').then(confirmed => {
                        if (confirmed) {
                            const confirmResponse = `Adding ${matchedItem} to your order.`;
                            speakText(confirmResponse, function() {
                                autoAddMenuItemToOrder(matchedItem, confidenceLevel);
                                isProcessingVoiceCommand = false;
                                setTimeout(() => resetToWakeWordDetection(), 3000);
                            });
                        } else {
                            const cancelResponse = "Okay, I won't add that item. You can try saying the item name more clearly or search for it manually.";
                            speakText(cancelResponse);
                            isProcessingVoiceCommand = false;
                            setTimeout(() => resetToWakeWordDetection(), 3000);
                        }
                    });
                });
                
            } else {
                const response = `I'm not confident about matching "${productName}" to a menu item. Please try saying the item name more clearly or search for it manually.`;
                domElements.voiceFeedback.textContent = response;
                
                speakText(response, function() {
                    showLowConfidenceRejection(matchedItem || productName, '40-59%').then(addAnyway => {
                        if (addAnyway) {
                            const addResponse = `Adding ${matchedItem || productName} to your order anyway.`;
                            speakText(addResponse, function() {
                                autoAddMenuItemToOrder(matchedItem || productName, confidenceLevel);
                                isProcessingVoiceCommand = false;
                                setTimeout(() => resetToWakeWordDetection(), 3000);
                            });
                        } else {
                            isProcessingVoiceCommand = false;
                            setTimeout(() => resetToWakeWordDetection(), 3000);
                        }
                    });
                });
            }
        } else {
            const response = `I couldn't find "${productName}" in our menu. Please check if you said the correct item name or try searching manually.`;
            domElements.voiceFeedback.textContent = response;
            
            speakText(response);
            isProcessingVoiceCommand = false;
            setTimeout(() => resetToWakeWordDetection(), 3000);
        }
        
    } catch (error) {
        console.error('Error processing product:', error);
        const response = "I'm having trouble processing your request. Please try again or use the manual menu.";
        domElements.voiceFeedback.textContent = response;
        
        speakText(response);
        isProcessingVoiceCommand = false;
        setTimeout(() => resetToWakeWordDetection(), 3000);
    }
}

// Start wake word detection
async function startWakeWordDetection() {
    console.log('Starting wake word detection...');
    
    if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
        console.log('Browser does not support speech recognition');
        domElements.voiceFeedback.textContent = 'Voice assistant not supported in this browser';
        return;
    }

    // Initialize audio context for voice activity detection
    await initializeAudioContext();
    
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    speechRecognition = new SpeechRecognition();
    
    speechRecognition.continuous = true;
    speechRecognition.interimResults = true;
    speechRecognition.lang = 'en-US';
    speechRecognition.maxAlternatives = 3;
    
    try {
        speechRecognition.start();
        console.log('Wake word detection started');
        domElements.voiceFeedback.textContent = 'Listening for "Hey Arabica"...';
        domElements.voiceStatus.textContent = 'Status: Listening for wake word';
    } catch (error) {
        console.error('Failed to start speech recognition:', error);
        domElements.voiceFeedback.textContent = 'Failed to start microphone. Please check permissions.';
        return;
    }
    
    isListening = true;
    isWaitingForWakeWord = true;
    
    // Start voice activity detection
    startVoiceActivityDetection();
    
    speechRecognition.onstart = function() {
        console.log('Wake word detection started');
        domElements.voiceTranscript.textContent = 'Say "Hey Arabica" to activate...';
    };

    speechRecognition.onresult = function(event) {
        if (isSpeaking) return;
        
        let finalTranscript = '';
        let interimTranscript = '';
        
        for (let i = event.resultIndex; i < event.results.length; i++) {
            const transcript = event.results[i][0].transcript;
            
            if (event.results[i].isFinal) {
                finalTranscript += transcript;
            } else {
                interimTranscript += transcript;
            }
        }
        
        const allTranscript = (interimTranscript + ' ' + finalTranscript).toLowerCase();
        
        if (isWaitingForWakeWord) {
            if (allTranscript.includes('hey arabica') || allTranscript.includes('hey arabika')) {
                console.log('Wake word detected!');
                wakeWordDetected = true;
                isWaitingForWakeWord = false;
                
                // Play 2-second welcome chime
                playWelcomeChime();
                
                domElements.voiceStatus.textContent = 'Status: Wake word detected!';
                domElements.voiceCommandDisplay.classList.remove('hidden');
                
                updateVoiceUIForListening();
                startSilenceTimeout(12000);
                
                // Wait for chime to finish, then speak welcome message
                setTimeout(() => {
                    speakText("Hello! I'm your Arabica voice assistant. What would you like to order today?")
                        .then(() => {
                            domElements.voiceTranscript.textContent = 'What would you like to order?';
                            domElements.voiceFeedback.textContent = 'Listening for your order...';
                        });
                }, 2000); // Wait 2 seconds for chime to complete
            }
            
            if (interimTranscript) {
                domElements.voiceTranscript.textContent = `Listening: "${interimTranscript}"...`;
            }
        } else {
            if (interimTranscript || finalTranscript) {
                const displayText = interimTranscript || finalTranscript;
                if (displayText) {
                    domElements.voiceTranscript.textContent = `"${displayText}"`;
                    domElements.voiceFeedback.textContent = 'Listening...';
                }
                resetSilenceTimeout();
            }
        }
    };

    speechRecognition.onerror = function(event) {
        console.error('Speech recognition error:', event);
        
        if (event.error === 'not-allowed') {
            domElements.voiceFeedback.textContent = 'Microphone access denied. Please allow microphone access.';
        } else if (event.error === 'no-speech') {
            console.log('No speech detected (normal for wake word detection)');
        } else {
            domElements.voiceFeedback.textContent = `Error: ${event.error}`;
        }
        
        setTimeout(() => {
            if (isListening) {
                resetToWakeWordDetection();
            }
        }, 1000);
    };

    speechRecognition.onend = function() {
        console.log('Speech recognition ended');
        
        if (isListening && !isSpeaking) {
            setTimeout(() => {
                if (isListening && !isSpeaking) {
                    try {
                        speechRecognition.start();
                    } catch (e) {
                        console.error('Failed to restart speech recognition:', e);
                    }
                }
            }, 500);
        }
    };
}

// Start silence timeout (fallback method)
function startSilenceTimeout(duration) {
    if (silenceTimeout) {
        clearTimeout(silenceTimeout);
    }
    
    silenceTimeout = setTimeout(() => {
        if (isListening && !isSpeaking) {
            if (isWaitingForWakeWord) {
                console.log('No wake word detected for ' + (duration/1000) + ' seconds. Still listening...');
                startSilenceTimeout(duration);
            } else {
                const currentTranscript = domElements.voiceTranscript.textContent;
                if (currentTranscript && !currentTranscript.includes('Listening for') && 
                    !currentTranscript.includes('Say "Hey Arabica"') && 
                    !currentTranscript.includes('What would you like') &&
                    !currentTranscript.includes('Speak naturally')) {
                    
                    console.log('12-second silence timeout in command mode, processing:', currentTranscript);
                    processDetectedCommand(currentTranscript.replace(/[""]/g, '').trim());
                } else {
                    console.log('No speech detected for 12 seconds in command mode, resetting...');
                    resetToWakeWordDetection();
                }
            }
        }
    }, duration);
}

// Reset silence timeout
function resetSilenceTimeout() {
    if (isWaitingForWakeWord) {
        startSilenceTimeout(30000);
    } else {
        startSilenceTimeout(12000);
    }
}

// Reset to wake word detection
function resetToWakeWordDetection() {
    console.log('Resetting to wake word detection mode');
    
    isWaitingForWakeWord = true;
    wakeWordDetected = false;
    isProcessingVoiceCommand = false;
    
    if (silenceTimeout) {
        clearTimeout(silenceTimeout);
    }
    
    domElements.voiceStatus.textContent = 'Status: Ready';
    domElements.voiceFeedback.textContent = 'Say "Hey Arabica" to start voice ordering';
    domElements.voiceCommandDisplay.classList.add('hidden');
    domElements.voiceTranscript.textContent = 'Listening for "Hey Arabica"...';
    
    updateVoiceUIForReady();
    startSilenceTimeout(30000);
}

// Update voice UI for listening
function updateVoiceUIForListening() {
    const voiceIcon = document.getElementById('voice-icon');
    const statusBadge = document.getElementById('voice-status-badge');
    const levelIndicator = document.getElementById('voice-level-indicator');
    
    if (voiceIcon) {
        voiceIcon.style.backgroundColor = '#10B981';
        voiceIcon.innerHTML = '<i class="fas fa-microphone"></i>';
    }
    if (statusBadge) {
        statusBadge.textContent = 'Listening...';
        statusBadge.className = 'text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded';
    }
    if (levelIndicator) levelIndicator.classList.remove('hidden');
}

// Update voice UI for ready
function updateVoiceUIForReady() {
    const voiceIcon = document.getElementById('voice-icon');
    const statusBadge = document.getElementById('voice-status-badge');
    const levelIndicator = document.getElementById('voice-level-indicator');
    
    if (voiceIcon) {
        voiceIcon.style.backgroundColor = '#3B82F6';
        voiceIcon.innerHTML = '<i class="fas fa-microphone"></i>';
    }
    if (statusBadge) {
        statusBadge.textContent = 'Ready';
        statusBadge.className = 'text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded';
    }
    if (levelIndicator) levelIndicator.classList.add('hidden');
}

// Stop voice assistant
function stopVoiceAssistant() {
    console.log('Stopping voice assistant...');
    isListening = false;
    
    if (silenceTimeout) {
        clearTimeout(silenceTimeout);
        silenceTimeout = null;
    }
    
    if (speechRecognition) {
        try {
            speechRecognition.stop();
        } catch (e) {
            console.log('Speech recognition already stopped');
        }
        speechRecognition = null;
    }
    
    if (speechSynthesis && speechSynthesis.speaking) {
        speechSynthesis.cancel();
    }
    
    if (audioContext && audioContext.state !== 'closed') {
        audioContext.close();
        isAudioContextInitialized = false;
    }
    
    domElements.voiceStatus.textContent = 'Status: Stopped';
    domElements.voiceFeedback.textContent = 'Voice assistant stopped';
    domElements.voiceCommandDisplay.classList.add('hidden');
    
    updateVoiceUIForReady();
}


// Match transcript with menu item
async function matchTranscriptWithMenuItem(transcript) {
    const normalizedTranscript = transcript.toLowerCase().trim();
    
    try {
        const response = await fetch('/customer/match-utterance', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                transcript: normalizedTranscript
            })
        });
        
        if (response.ok) {
            const result = await response.json();
            if (result.success && result.matchedMenuItem) {
                console.log('Found matching menu item:', result.matchedMenuItem);
                console.log('Confidence level:', result.confidence);
                console.log('Product details:', result.product);
                
                const matchResult = {
                    menuItem: result.matchedMenuItem,
                    confidence: result.confidence || 'Not Confident',
                    similarity: result.similarity || 0,
                    product: result.product || null
                };
                
                return matchResult;
            }
        }
    } catch (error) {
        console.error('Error matching utterance:', error);
    }
    
    return null;
}

// Save transcript
async function saveTranscript(transcript) {
    console.log('Voice transcript recorded:', transcript);
    
    const transcripts = JSON.parse(localStorage.getItem('voiceTranscripts') || '[]');
    transcripts.push({
        text: transcript,
        timestamp: new Date().toISOString(),
        wakeWordDetected: wakeWordDetected
    });
    localStorage.setItem('voiceTranscripts', JSON.stringify(transcripts));
    
    if (isProcessingVoiceCommand) {
        console.log('Already processing a voice command, skipping...');
        return;
    }
    
    isProcessingVoiceCommand = true;
    
    try {
        const matchResult = await matchTranscriptWithMenuItem(transcript);
        const matchedMenuItem = matchResult ? matchResult.menuItem : null;
        const confidenceLevel = matchResult ? matchResult.confidence : 'Not Confident';
        const productDetails = matchResult ? matchResult.product : null;
        
        let responseMessage = '';
        
        if (confidenceLevel === 'Confident' && productDetails) {
            console.log('Auto-adding product to order:', productDetails.name);
            autoAddProductToOrder(productDetails, 'voice-auto');
            responseMessage = `I've added ${productDetails.name} to your order. Is there anything else you'd like?`;
        } else if (confidenceLevel === 'Partially Confident' && matchedMenuItem) {
            responseMessage = `I think you said ${matchedMenuItem}. I'll add it to your order now.`;
            if (productDetails) {
                autoAddProductToOrder(productDetails, 'voice-partial');
            }
        } else if (confidenceLevel === 'Not Confident' && matchedMenuItem) {
            responseMessage = `I'm not sure I understood correctly. Did you mean ${matchedMenuItem}?`;
        } else {
            responseMessage = "I'm sorry, I didn't understand that. Could you please repeat or try saying the item name more clearly?";
        }
        
        // Play a short confirmation chime before speaking
        playChime();
        
        // Wait a moment, then speak the response
        setTimeout(async () => {
            await speakText(responseMessage);
            
            // Save to server after speaking
            try {
                const serverResponse = await fetch('/customer/save-transcript', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        transcribedData: transcript,
                        matchedMenuItem: matchedMenuItem,
                        confidenceLevel: confidenceLevel
                    })
                });
                
                if (serverResponse.ok) {
                    const result = await serverResponse.json();
                    console.log('Transcript saved to server:', result);
                    
                    if (matchedMenuItem) {
                        showMatchedMenuItem(transcript, matchedMenuItem, confidenceLevel, result.addedToGallery);
                    }
                }
            } catch (error) {
                console.log('Could not reach server, transcript stored locally');
            }
        }, 500);
        
    } catch (error) {
        console.log('Could not reach server, transcript stored locally');
        console.log('Error:', error);
        playChime();
        await speakText("I heard your request. Let me process that for you.");
    } finally {
        isProcessingVoiceCommand = false;
        
        // Return to listening mode after speaking
        if (!isWaitingForWakeWord) {
            setTimeout(() => {
                domElements.voiceFeedback.textContent = 'Listening for more items...';
                domElements.voiceTranscript.textContent = 'What else would you like?';
                updateVoiceUIForListening();
                startSilenceTimeout(12000);
            }, 1000);
        }
    }
}

// Show matched menu item
function showMatchedMenuItem(transcript, menuItem, confidenceLevel, addedToGallery = false) {
    let matchDisplay = document.getElementById('voice-match-display');
    
    if (!matchDisplay) {
        matchDisplay = document.createElement('div');
        matchDisplay.id = 'voice-match-display';
        matchDisplay.className = 'mt-3 border rounded-lg p-3 fade-in';
        domElements.voiceCommandDisplay.parentNode.insertBefore(matchDisplay, domElements.voiceCommandDisplay.nextSibling);
    }
    
    let containerClass = 'bg-red-50 border-red-200';
    let badgeColor = 'bg-red-100 text-red-600 border border-red-200';
    let badgeText = 'Low Confidence (<80%)';
    let iconColor = 'text-red-500';
    let thresholdInfo = 'Similarity: 40-79%';
    
    if (confidenceLevel === 'Partially Confident') {
        containerClass = 'bg-yellow-50 border-yellow-200';
        badgeColor = 'bg-yellow-100 text-yellow-600 border border-yellow-200';
        badgeText = 'Medium Confidence (≥60%)';
        iconColor = 'text-yellow-500';
        thresholdInfo = 'Similarity: 60-79%';
    } else if (confidenceLevel === 'Confident') {
        containerClass = 'bg-green-50 border-green-200';
        badgeColor = 'bg-green-100 text-green-600 border border-green-200';
        badgeText = 'High Confidence (≥80%)';
        iconColor = 'text-green-500';
        thresholdInfo = 'Similarity: 80-100%';
    }
    
    matchDisplay.className = `mt-3 ${containerClass} border rounded-lg p-3 fade-in`;
    
    let galleryBadge = '';
    if (addedToGallery && confidenceLevel !== 'Not Confident') {
        galleryBadge = `
            <div class="mt-2 flex items-center text-xs text-green-600">
                <i class="fas fa-save mr-1"></i>
                <span>This utterance was added to the learning database for future matches</span>
            </div>
        `;
    }
    
    matchDisplay.innerHTML = `
        <div class="flex items-start">
            <div class="${iconColor} p-1.5 rounded-full mr-2">
                <i class="fas fa-check-circle text-sm"></i>
            </div>
            <div class="flex-1">
                <div class="flex justify-between items-start mb-1">
                    <h4 class="font-bold text-gray-800 text-sm">Menu Item Found!</h4>
                    <span class="${badgeColor} text-xs px-2 py-0.5 rounded-full font-medium">
                        ${badgeText}
                    </span>
                </div>
                <p class="text-gray-700 text-sm mb-1">You said: "<span class="font-medium">${transcript}</span>"</p>
                <p class="text-gray-700 text-sm mb-1">Matched: <span class="font-bold ${confidenceLevel === 'Confident' ? 'text-green-600' : confidenceLevel === 'Partially Confident' ? 'text-yellow-600' : 'text-red-600'}">${menuItem}</span></p>
                <p class="text-xs text-gray-500 mb-2">${thresholdInfo}</p>
                ${galleryBadge}
            </div>
        </div>
    `;
    
    setTimeout(() => {
        if (matchDisplay && matchDisplay.parentNode) {
            matchDisplay.classList.add('hidden');
            setTimeout(() => {
                if (matchDisplay && matchDisplay.parentNode) {
                    matchDisplay.remove();
                }
            }, 500);
        }
    }, 7000);
}

// Auto-add menu item to order
async function autoAddMenuItemToOrder(menuItemName, confidenceLevel) {
    console.log('Manual auto-add attempt:', menuItemName, 'with confidence:', confidenceLevel);
    
    if (isProcessingVoiceCommand && confidenceLevel === 'Confident') {
        console.log('Voice command already processing this item, skipping manual add');
        return;
    }
    
    if (confidenceLevel === 'Confident') {
        try {
            const response = await fetch('/customer/get-product-by-name', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    productName: menuItemName
                })
            });
            
            if (response.ok) {
                const result = await response.json();
                if (result.success && result.product) {
                    console.log('Found product details:', result.product);
                    autoAddProductToOrder(result.product, 'manual-confident');
                    
                    playChime();
                    speakText(`I've added ${result.product.name} to your order.`);
                    return;
                }
            }
        } catch (error) {
            console.error('Error fetching product details:', error);
        }
    }
    
    const addButtons = document.querySelectorAll('.add-to-order-btn');
    let found = false;
    
    addButtons.forEach(btn => {
        const name = btn.getAttribute('data-name');
        if (name.toLowerCase().includes(menuItemName.toLowerCase()) || 
            menuItemName.toLowerCase().includes(name.toLowerCase())) {
            
            const price = btn.getAttribute('data-price');
            const category = btn.getAttribute('data-category');
            const image = btn.getAttribute('data-image');
            
            addToOrder(name, price, category, image);
            
            playChime();
            speakText(`Added ${name} to your order.`);
            
            found = true;
        }
    });
    
    if (!found) {
        playChime();
        speakText(`I couldn't find ${menuItemName} in the current menu. Please try navigating to the correct category or say the item name more clearly.`);
    }
}

// Show low confidence rejection modal
function showLowConfidenceRejection(menuItemName, similarityRange) {
    return new Promise((resolve) => {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        modal.innerHTML = `
            <div class="bg-white rounded-lg p-6 max-w-sm mx-4 animate__animated animate__fadeIn">
                <div class="flex items-center mb-4">
                    <div class="bg-red-100 text-red-600 p-2 rounded-full mr-3">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Low Confidence Match</h3>
                </div>
                <p class="text-gray-600 mb-4">
                    This match has <span class="font-bold">${similarityRange} similarity</span>, which is below the acceptable threshold.
                    <br><br>
                    Match: <span class="font-bold text-red-600">"${menuItemName}"</span>
                    <br><br>
                    <span class="font-bold">This item will NOT be added to your order automatically.</span>
                    <br><br>
                    You can:
                    <ol class="list-decimal pl-4 mt-2 text-sm">
                        <li>Search for the item manually</li>
                        <li>Speak more clearly and try again</li>
                        <li>Use the menu buttons instead</li>
                    </ol>
                </p>
                <div class="flex space-x-3">
                    <button id="ok-rejection" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 rounded-lg font-medium transition-colors">
                        OK, I'll Search Manually
                    </button>
                    <button id="add-anyway" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg font-medium transition-colors">
                        Add Anyway (Not Recommended)
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        modal.querySelector('#ok-rejection').addEventListener('click', () => {
            modal.remove();
            resolve(false);
        });
        
        modal.querySelector('#add-anyway').addEventListener('click', () => {
            modal.remove();
            resolve(true);
        });
        
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
                resolve(false);
            }
        });
    });
}

// Show rejection message
function showRejectionMessage(menuItemName, similarityRange) {
    let rejectionDisplay = document.getElementById('voice-rejection-display');
    
    if (!rejectionDisplay) {
        rejectionDisplay = document.createElement('div');
        rejectionDisplay.id = 'voice-rejection-display';
        rejectionDisplay.className = 'mt-3 border border-red-200 bg-red-50 rounded-lg p-3 fade-in';
        domElements.voiceCommandDisplay.parentNode.insertBefore(rejectionDisplay, domElements.voiceCommandDisplay.nextSibling);
    }
    
    rejectionDisplay.innerHTML = `
        <div class="flex items-start">
            <div class="text-red-500 p-1.5 rounded-full mr-2">
                <i class="fas fa-exclamation-circle text-sm"></i>
            </div>
            <div class="flex-1">
                <h4 class="font-bold text-gray-800 text-sm mb-1">Item Not Added</h4>
                <p class="text-gray-700 text-sm mb-1">"${menuItemName}" was NOT added to your order.</p>
                <p class="text-xs text-gray-500 mb-2">Reason: Low confidence match (${similarityRange} similarity)</p>
                <p class="text-xs text-gray-600 mb-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    For better results: Speak clearly, specify the full item name, or use manual selection.
                </p>
                <div class="mt-2">
                    <button onclick="window.location.reload()" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg font-medium transition-colors duration-200 flex items-center text-xs">
                        <i class="fas fa-search mr-1"></i> Search Manually
                    </button>
                </div>
            </div>
        </div>
    `;
    
    setTimeout(() => {
        if (rejectionDisplay && rejectionDisplay.parentNode) {
            rejectionDisplay.classList.add('hidden');
            setTimeout(() => {
                if (rejectionDisplay && rejectionDisplay.parentNode) {
                    rejectionDisplay.remove();
                }
            }, 500);
        }
    }, 10000);
}

// Show partial confirmation modal
function showPartialConfirmation(menuItemName, similarityRange) {
    return new Promise((resolve) => {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        modal.innerHTML = `
            <div class="bg-white rounded-lg p-6 max-w-sm mx-4 animate__animated animate__fadeIn">
                <div class="flex items-center mb-4">
                    <div class="bg-yellow-100 text-yellow-600 p-2 rounded-full mr-3">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Medium Confidence Match</h3>
                </div>
                <p class="text-gray-600 mb-4">
                    This match has <span class="font-bold">${similarityRange} similarity</span>.
                    <br><br>
                    Match: <span class="font-bold text-yellow-600">"${menuItemName}"</span>
                    <br><br>
                    Do you want to add this to your order?
                </p>
                <div class="flex space-x-3">
                    <button id="cancel-partial" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 rounded-lg font-medium transition-colors">
                        No, Cancel
                    </button>
                    <button id="confirm-partial" class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white py-2 rounded-lg font-medium transition-colors">
                        Yes, Add to Order
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        modal.querySelector('#cancel-partial').addEventListener('click', () => {
            modal.remove();
            resolve(false);
        });
        
        modal.querySelector('#confirm-partial').addEventListener('click', () => {
            modal.remove();
            resolve(true);
        });
        
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
                resolve(false);
            }
        });
    });
}

// Process voice commands (legacy function, using new one with TTS)
function processVoiceCommand(transcript) {
    const command = transcript.toLowerCase();
    
    if (isProcessingVoiceCommand) {
        console.log('Already processing a voice command, skipping...');
        return;
    }
    
    if (command.includes('add') || command.includes('order') || command.includes('want') || command.includes('get') || command.includes('take')) {
        const words = command.split(' ');
        const orderKeywords = ['add', 'order', 'want', 'get', 'take', 'have', 'i\'d like', 'i would like'];
        let addIndex = -1;
        
        for (let i = 0; i < words.length; i++) {
            if (orderKeywords.includes(words[i])) {
                addIndex = i;
                break;
            }
        }
        
        if (addIndex !== -1 && words.length > addIndex + 1) {
            const productNameWords = [];
            for (let i = addIndex + 1; i < words.length; i++) {
                const fillerWords = ['a', 'an', 'the', 'some', 'please', 'i', 'would', 'like', 'to'];
                if (!fillerWords.includes(words[i])) {
                    productNameWords.push(words[i]);
                }
            }
            const productName = productNameWords.join(' ');
            
            if (productName.trim() === '') {
                playChime();
                speakText('Please specify what you want to add. For example, say "Add pad Thai" or "I want to order pad Thai"');
                return;
            }
            
            console.log('Extracted product name from command:', productName);
            return;
        } else {
            playChime();
            speakText('Please specify what you want to add. For example, say "Add pad Thai" or "I want pad Thai"');
        }
    } 
    else if (command.includes('show') || command.includes('display') || command.includes('see')) {
        if (command.includes('specials')) {
            const specialsBtn = document.querySelector('[data-category="specials"]');
            if (specialsBtn) {
                playChime();
                specialsBtn.click();
                speakText('Showing you our specials menu.');
            } else {
                playChime();
                speakText('Specials menu is not available at the moment.');
            }
        } else if (command.includes('drinks') || command.includes('beverage') || command.includes('coffee')) {
            const drinksBtn = document.querySelector('[data-category="drinks"]');
            if (drinksBtn) {
                playChime();
                drinksBtn.click();
                speakText('Here are our drinks and beverages.');
            }
        } else if (command.includes('appetizers') || command.includes('starter') || command.includes('snack')) {
            const appetizersBtn = document.querySelector('[data-category="appetizers"]');
            if (appetizersBtn) {
                playChime();
                appetizersBtn.click();
                speakText('Showing you our appetizers and snacks.');
            }
        } else if (command.includes('main course') || command.includes('main-course') || command.includes('entree') || command.includes('main')) {
            const mainCourseBtn = document.querySelector('[data-category="main-course"]');
            if (mainCourseBtn) {
                playChime();
                mainCourseBtn.click();
                speakText('Here are our main course options.');
            }
        } else {
            playChime();
            speakText('What would you like to see? You can say "show specials", "show drinks", or "show appetizers".');
        }
    }
    else if (command.includes('clear') || command.includes('remove all') || command.includes('empty')) {
        clearOrder();
        playChime();
        speakText('I have cleared your order. You can start adding items again.');
    }
    else if (command.includes('checkout') || command.includes('pay') || command.includes('finish') || command.includes('done')) {
        if (globalState.orderItems.length === 0) {
            playChime();
            speakText('Your order is empty. Please add some items before checking out.');
        } else {
            playChime();
            speakText('Taking you to checkout now.').then(() => {
                setTimeout(() => {
                    showCheckoutModal();
                }, 500);
            });
        }
    }
    else if (command.includes('thank you') || command.includes('thanks')) {
        playChime();
        speakText('You\'re welcome! Is there anything else I can help you with?');
    }
    else if (command.includes('hello') || command.includes('hi') || command.includes('hey')) {
        playChime();
        speakText('Hello! How can I help you with your order today?');
    }
    else if (command.includes('help')) {
        playChime();
        speakText('I can help you add items to your order, show different menus, clear your order, or proceed to checkout. Just tell me what you\'d like to do.');
    }
    else if (command.includes('how much') || command.includes('total') || command.includes('price')) {
        const total = calculateOrderTotal();
        if (total > 0) {
            playChime();
            speakText(`Your current order total is ${formatPrice(total).replace('₱', '')} pesos.`);
        } else {
            playChime();
            speakText('Your order is currently empty.');
        }
    }
    else if (command.includes('what\'s in') || command.includes('what is in') || command.includes('my order')) {
        if (globalState.orderItems.length > 0) {
            const itemList = globalState.orderItems.map(item => 
                `${item.quantity} ${item.name}${item.quantity > 1 ? 's' : ''}`
            ).join(', ');
            playChime();
            speakText(`You have ${itemList} in your order.`);
        } else {
            playChime();
            speakText('Your order is currently empty.');
        }
    }
    else if (command.includes('goodbye') || command.includes('bye') || command.includes('exit') || command.includes('quit')) {
        playChime();
        speakText('Goodbye! Thank you for visiting Caffé Arabica.').then(() => {
            setTimeout(() => {
                resetToWakeWordDetection();
            }, 1000);
        });
    }
    else {
        playChime();
        speakText('I\'m here to help you order. You can say things like "Add pork barbecue", "Show me drinks", "What\'s in my order?", or "Checkout". What would you like to do?');
    }
}



// Show voice help
function showVoiceHelp() {
    speakText(`Here's how to use the voice assistant:
        Say "Hey Arabica" to activate me.
        You can then say things like:
        "Add pork barbecue" to add items to your order.
        "Show drinks" to see beverage options.
        "What's in my order?" to hear your current items.
        "Checkout" to proceed to payment.
        "Clear order" to remove all items.
        I'll speak back to confirm your actions.
        Take your time speaking - I'll listen for 12 seconds.
        Say "Goodbye" when you're done.`);
}

// Initialize voice event listeners
function initializeVoiceEventListeners() {
    console.log('Initializing voice event listeners with wake word detection...');
    
    // Initialize speech synthesis voices
    if (speechSynthesis) {
        speechSynthesis.getVoices();
        
        speechSynthesis.addEventListener('voiceschanged', function() {
            console.log('Voices loaded:', speechSynthesis.getVoices().length);
        });
    }
    
    // Start wake word detection when page loads
    setTimeout(() => {
        startWakeWordDetection();
    }, 1000);
    
    console.log('Voice wake word detection initialized');
}

// Export functions
window.initializeVoiceDOMElements = initializeVoiceDOMElements;
window.startWakeWordDetection = startWakeWordDetection;
window.stopVoiceAssistant = stopVoiceAssistant;
window.matchTranscriptWithMenuItem = matchTranscriptWithMenuItem;
window.saveTranscript = saveTranscript;
window.speakText = speakText;
window.playChime = playChime;
window.playWelcomeChime = playWelcomeChime;
window.showMatchedMenuItem = showMatchedMenuItem;
window.autoAddMenuItemToOrder = autoAddMenuItemToOrder;
window.processVoiceCommand = processVoiceCommand;
window.processVoiceCommandWithTTS = processVoiceCommandWithTTS;
window.showVoiceHelp = showVoiceHelp;
window.initializeVoiceEventListeners = initializeVoiceEventListeners;
window.showLowConfidenceRejection = showLowConfidenceRejection;
window.showRejectionMessage = showRejectionMessage;
window.showPartialConfirmation = showPartialConfirmation;
window.resetToWakeWordDetection = resetToWakeWordDetection;