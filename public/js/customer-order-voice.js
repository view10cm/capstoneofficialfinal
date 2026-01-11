// customer-order-voice.js
// Voice assistant functionality with wake word detection

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

// Start voice activity detection
function startVoiceActivityDetection() {
    if (!isAudioContextInitialized || !javascriptNode) return;
    
    let speechActive = false;
    let consecutiveSilenceFrames = 0;
    const SILENCE_THRESHOLD = 15; // Adjust this based on testing
    const SILENCE_FRAMES_REQUIRED = 60; // ~12 seconds at 2048 buffer size (12 seconds ÷ 0.2 seconds per buffer = 60 frames)
    
    javascriptNode.onaudioprocess = function() {
        if (!isListening || isWaitingForWakeWord) return;
        
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
            resetSilenceTimeout(); // Reset the 12-second timeout when speech is detected
        } else {
            consecutiveSilenceFrames++;
            if (consecutiveSilenceFrames > SILENCE_FRAMES_REQUIRED && speechActive) {
                // 12 seconds of silence after speech - user has finished speaking
                speechActive = false;
                consecutiveSilenceFrames = 0;
                
                // If we have a final transcript, process it
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
    if (transcript && transcript.length > 2 && !isProcessingVoiceCommand && !isWaitingForWakeWord) {
        console.log('Processing command after silence:', transcript);
        saveTranscript(transcript);
        processVoiceCommand(transcript);
        
        // Reset to wake word detection after processing
        setTimeout(() => {
            resetToWakeWordDetection();
        }, 3000);
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
        
        // Check for wake word in both interim and final results
        const allTranscript = (interimTranscript + ' ' + finalTranscript).toLowerCase();
        
        if (isWaitingForWakeWord) {
            if (allTranscript.includes('hey arabica') || allTranscript.includes('hey arabika')) {
                console.log('Wake word detected!');
                wakeWordDetected = true;
                isWaitingForWakeWord = false;
                
                // Show visual feedback for wake word detection
                const voiceIcon = document.getElementById('voice-icon');
                if (voiceIcon) {
                    voiceIcon.classList.add('wake-word-detected');
                    setTimeout(() => {
                        voiceIcon.classList.remove('wake-word-detected');
                    }, 500);
                }
                
                domElements.voiceStatus.textContent = 'Status: Wake word detected!';
                domElements.voiceFeedback.textContent = 'Listening for your order... (Take your time, I\'ll wait)';
                domElements.voiceCommandDisplay.classList.remove('hidden');
                domElements.voiceTranscript.textContent = 'What would you like to order? Speak naturally, I\'ll listen for 12 seconds...';
                
                updateVoiceUIForListening();
                
                // Start a generous timeout for command mode (12 seconds)
                startSilenceTimeout(12000);
            }
            
            if (interimTranscript) {
                domElements.voiceTranscript.textContent = `Listening: "${interimTranscript}"...`;
            }
        } else {
            // We're in command mode after wake word
            if (interimTranscript || finalTranscript) {
                // Update transcript display
                const displayText = interimTranscript || finalTranscript;
                if (displayText) {
                    domElements.voiceTranscript.textContent = `"${displayText}"`;
                    domElements.voiceFeedback.textContent = 'Listening... (You have 12 seconds to finish speaking)';
                }
                
                // Reset silence timeout when we get speech
                resetSilenceTimeout();
            }
        }
    };

    speechRecognition.onerror = function(event) {
        console.error('Speech recognition error:', event.error);
        
        if (event.error === 'not-allowed') {
            domElements.voiceFeedback.textContent = 'Microphone access denied. Please allow microphone access.';
        } else if (event.error === 'no-speech') {
            // This is normal when no one is speaking
            console.log('No speech detected (normal for wake word detection)');
        } else {
            domElements.voiceFeedback.textContent = `Error: ${event.error}`;
        }
        
        // Try to restart after error
        setTimeout(() => {
            if (isListening) {
                resetToWakeWordDetection();
            }
        }, 1000);
    };

    speechRecognition.onend = function() {
        console.log('Speech recognition ended');
        
        if (isListening) {
            setTimeout(() => {
                if (isListening) {
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
        if (isListening) {
            if (isWaitingForWakeWord) {
                console.log('No wake word detected for ' + (duration/1000) + ' seconds. Still listening...');
                // Don't reset for wake word detection, just keep listening
                startSilenceTimeout(duration); // Restart the timeout
            } else {
                // In command mode, check if we have a transcript to process
                const currentTranscript = domElements.voiceTranscript.textContent;
                if (currentTranscript && !currentTranscript.includes('Listening for') && 
                    !currentTranscript.includes('Say "Hey Arabica"') && 
                    !currentTranscript.includes('What would you like') &&
                    !currentTranscript.includes('Speak naturally')) {
                    
                    console.log('12-second silence timeout in command mode, processing:', currentTranscript);
                    processDetectedCommand(currentTranscript.replace(/[""]/g, '').trim());
                } else {
                    console.log('No speech detected for 12 seconds in command mode, resetting...');
                    domElements.voiceFeedback.textContent = 'No speech detected. Say "Hey Arabica" to try again.';
                    resetToWakeWordDetection();
                }
            }
        }
    }, duration);
}

// Reset silence timeout
function resetSilenceTimeout() {
    if (isWaitingForWakeWord) {
        // In wake word mode, use 30-second timeout
        startSilenceTimeout(30000);
    } else {
        // In command mode, use 12-second timeout (generous time for speaking)
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
    
    // Restart with wake word timeout
    startSilenceTimeout(30000);
}

// Update voice UI for listening
function updateVoiceUIForListening() {
    const voiceIcon = document.getElementById('voice-icon');
    const statusBadge = document.getElementById('voice-status-badge');
    const levelIndicator = document.getElementById('voice-level-indicator');
    
    if (voiceIcon) {
        voiceIcon.classList.add('voice-recording');
        voiceIcon.style.backgroundColor = '#10B981'; // Green when listening
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
        voiceIcon.classList.remove('voice-recording');
        voiceIcon.style.backgroundColor = '#3B82F6'; // Blue when ready
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
    
    // Clean up audio context
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
        
        if (confidenceLevel === 'Confident' && productDetails) {
            console.log('Auto-adding product to order:', productDetails.name);
            autoAddProductToOrder(productDetails, 'voice-auto');
        }
        
        const response = await fetch('/customer/save-transcript', {
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
        
        if (response.ok) {
            const result = await response.json();
            console.log('Transcript saved to server:', result);
            
            if (matchedMenuItem) {
                showMatchedMenuItem(transcript, matchedMenuItem, confidenceLevel, result.addedToGallery);
            }
        } else {
            console.log('Server save failed, transcript stored locally');
            const errorText = await response.text();
            console.log('Error response:', errorText);
            if (matchedMenuItem) {
                showMatchedMenuItem(transcript, matchedMenuItem, confidenceLevel, false);
            }
        }
    } catch (error) {
        console.log('Could not reach server, transcript stored locally');
        console.log('Error:', error);
        if (matchedMenuItem) {
            showMatchedMenuItem(transcript, matchedMenuItem, confidenceLevel, false);
        }
    } finally {
        isProcessingVoiceCommand = false;
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
                
                <!-- Auto-add button -->
                <div class="mt-2">
                    <button class="auto-add-menu-btn ${confidenceLevel === 'Confident' ? 'bg-green-600 hover:bg-green-700' : confidenceLevel === 'Partially Confident' ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-red-600 hover:bg-red-700'} text-white px-3 py-1.5 rounded-lg font-medium transition-colors duration-200 flex items-center text-xs"
                            data-menu-item="${menuItem}">
                        <i class="fas fa-plus mr-1"></i> Auto-Add "${menuItem}" to Order
                    </button>
                </div>
            </div>
        </div>
    `;
    
    const autoAddBtn = matchDisplay.querySelector('.auto-add-menu-btn');
    if (autoAddBtn) {
        autoAddBtn.addEventListener('click', function() {
            const menuItemName = this.getAttribute('data-menu-item');
            autoAddMenuItemToOrder(menuItemName, confidenceLevel);
        });
    }
    
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
                    return;
                }
            }
        } catch (error) {
            console.error('Error fetching product details:', error);
        }
    }
    
    if (confidenceLevel === 'Not Confident') {
        const confirmed = await showLowConfidenceRejection(menuItemName, '40-59%');
        if (confirmed) {
            domElements.voiceFeedback.textContent = `Manually adding "${menuItemName}" despite low confidence match`;
            domElements.voiceFeedback.style.color = '#F59E0B';
        } else {
            domElements.voiceFeedback.textContent = `Low confidence match (40-59%). "${menuItemName}" was NOT added to order.`;
            domElements.voiceFeedback.style.color = '#EF4444';
            
            showRejectionMessage(menuItemName, '40-59%');
            return;
        }
    }
    
    if (confidenceLevel === 'Partially Confident') {
        const confirmed = await showPartialConfirmation(menuItemName, '60-79%');
        if (!confirmed) {
            domElements.voiceFeedback.textContent = 'Cancelled adding item to order';
            domElements.voiceFeedback.style.color = '#EF4444';
            return;
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
            
            let confidenceText = '';
            if (confidenceLevel === 'Confident') {
                confidenceText = ' (High confidence - ≥80% match)';
            } else if (confidenceLevel === 'Partially Confident') {
                confidenceText = ' (Medium confidence - 60-79% match)';
            }
            
            domElements.voiceFeedback.textContent = `Added "${name}" to order${confidenceText}`;
            domElements.voiceFeedback.style.color = '#10B981';
            
            found = true;
        }
    });
    
    if (!found) {
        domElements.voiceFeedback.textContent = `"${menuItemName}" not found in current view. Try navigating to the correct category.`;
        domElements.voiceFeedback.style.color = '#EF4444';
        
        let categoryToLoad = 'main-course';
        
        if (menuItemName.toLowerCase().includes('hot') || 
            menuItemName.toLowerCase().includes('iced') ||
            menuItemName.toLowerCase().includes('frappe') ||
            menuItemName.toLowerCase().includes('milktea') ||
            menuItemName.toLowerCase().includes('cappuccino') ||
            menuItemName.toLowerCase().includes('latte') ||
            menuItemName.toLowerCase().includes('espresso') ||
            menuItemName.toLowerCase().includes('mocha') ||
            menuItemName.toLowerCase().includes('americano') ||
            menuItemName.toLowerCase().includes('choco') ||
            menuItemName.toLowerCase().includes('caramel') ||
            menuItemName.toLowerCase().includes('vanilla') ||
            menuItemName.toLowerCase().includes('matcha')) {
            categoryToLoad = 'drinks';
        } else if (menuItemName.toLowerCase().includes('salad') ||
                  menuItemName.toLowerCase().includes('nachos') ||
                  menuItemName.toLowerCase().includes('fries') ||
                  menuItemName.toLowerCase().includes('sandwich') ||
                  menuItemName.toLowerCase().includes('quesadilla') ||
                  menuItemName.toLowerCase().includes('wrap')) {
            categoryToLoad = 'appetizers';
        } else if (menuItemName.toLowerCase().includes('pasta') ||
                  menuItemName.toLowerCase().includes('noodles') ||
                  menuItemName.toLowerCase().includes('lasagna') ||
                  menuItemName.toLowerCase().includes('paella') ||
                  menuItemName.toLowerCase().includes('crispy') ||
                  menuItemName.toLowerCase().includes('grilled') ||
                  menuItemName.toLowerCase().includes('roasted') ||
                  menuItemName.toLowerCase().includes('pork') ||
                  menuItemName.toLowerCase().includes('chicken') ||
                  menuItemName.toLowerCase().includes('beef') ||
                  menuItemName.toLowerCase().includes('seafood') ||
                  menuItemName.toLowerCase().includes('fish')) {
            categoryToLoad = 'main-course';
        }
        
        const categoryBtn = document.querySelector(`[data-category="${categoryToLoad}"]`);
        if (categoryBtn) {
            categoryBtn.click();
            
            setTimeout(() => {
                const newAddButtons = document.querySelectorAll('.add-to-order-btn');
                let itemFound = false;
                
                newAddButtons.forEach(btn => {
                    const name = btn.getAttribute('data-name');
                    if (name.toLowerCase().includes(menuItemName.toLowerCase()) || 
                        menuItemName.toLowerCase().includes(name.toLowerCase())) {
                        
                        const price = btn.getAttribute('data-price');
                        const category = btn.getAttribute('data-category');
                        const image = btn.getAttribute('data-image');
                        addToOrder(name, price, category, image);
                        
                        domElements.voiceFeedback.textContent = `Successfully added "${name}" to order!`;
                        domElements.voiceFeedback.style.color = '#10B981';
                        itemFound = true;
                    }
                });
                
                if (!itemFound) {
                    domElements.voiceFeedback.textContent = `"${menuItemName}" not found. Please try searching manually.`;
                    domElements.voiceFeedback.style.color = '#EF4444';
                }
            }, 1000);
        }
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

// Process voice commands
function processVoiceCommand(transcript) {
    const command = transcript.toLowerCase();
    let feedback = '';
    
    if (isProcessingVoiceCommand) {
        console.log('Already processing a voice command, skipping...');
        return;
    }
    
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
                feedback = 'Please specify what you want to add. Example: "Add pad Thai" or "I want to order pad Thai"';
                domElements.voiceFeedback.textContent = feedback;
                return;
            }
            
            console.log('Extracted product name from command:', productName);
            feedback = `Processing "${productName}"...`;
            domElements.voiceFeedback.textContent = feedback;
            
            return;
        } else {
            feedback = 'Please specify what you want to add. Example: "Add pad Thai" or "I want pad Thai"';
        }
    } 
    else if (command.includes('show') || command.includes('display')) {
        if (command.includes('specials')) {
            const specialsBtn = document.querySelector('[data-category="specials"]');
            if (specialsBtn) {
                specialsBtn.click();
                feedback = 'Showing specials menu';
            } else {
                feedback = 'Specials menu not available';
            }
        } else if (command.includes('drinks')) {
            const drinksBtn = document.querySelector('[data-category="drinks"]');
            if (drinksBtn) {
                drinksBtn.click();
                feedback = 'Showing drinks menu';
            }
        } else if (command.includes('appetizers')) {
            const appetizersBtn = document.querySelector('[data-category="appetizers"]');
            if (appetizersBtn) {
                appetizersBtn.click();
                feedback = 'Showing appetizers menu';
            }
        } else if (command.includes('main course') || command.includes('main-course')) {
            const mainCourseBtn = document.querySelector('[data-category="main-course"]');
            if (mainCourseBtn) {
                mainCourseBtn.click();
                feedback = 'Showing main course menu';
            }
        }
    }
    else if (command.includes('clear') || command.includes('remove all')) {
        clearOrder();
        feedback = 'Order cleared';
    }
    else if (command.includes('checkout') || command.includes('pay')) {
        showCheckoutModal();
        feedback = 'Opening checkout';
    }
    else if (command.includes('help')) {
        showVoiceHelp();
        feedback = 'Showing voice help';
    }
    else {
        feedback = 'Processing your request...';
    }
    
    domElements.voiceFeedback.textContent = feedback;
}

// Show voice help
function showVoiceHelp() {
    alert('🎤 Voice Assistant Guide:\n\n' +
          '🗣️ HOW TO USE:\n' +
          '• Say "Hey Arabica" to activate the voice assistant\n' +
          '• After activation, speak your order naturally\n' +
          '• Take your time - you have 12 seconds to finish speaking\n' +
          '• The system will wait patiently until you stop speaking\n' +
          '• Speak clearly and include the full item name\n\n' +
          '🎯 VOICE COMMANDS:\n' +
          '• "Add [item name]" - Add item to cart\n' +
          '• "Show specials" - Show specials\n' +
          '• "Clear order" - Clear all items\n' +
          '• "Checkout" - Proceed to checkout\n\n' +
          '🎚️ CONFIDENCE LEVELS:\n' +
          '• High (≥80% match) - Auto-adds to order\n' +
          '• Medium (60-79% match) - Quick confirmation needed\n' +
          '• Low (40-59% match) - NOT added automatically\n\n' +
          '💡 TIPS:\n' +
          '• Speak at a normal pace - you have plenty of time\n' +
          '• Pause naturally when thinking - the system will wait 12 seconds\n' +
          '• Include details like "pork barbecue" instead of just "barbecue"\n' +
          '• Say "Hey Arabica" clearly to activate');
}

// Initialize voice event listeners
function initializeVoiceEventListeners() {
    console.log('Initializing voice event listeners with wake word detection...');
    
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
window.showMatchedMenuItem = showMatchedMenuItem;
window.autoAddMenuItemToOrder = autoAddMenuItemToOrder;
window.processVoiceCommand = processVoiceCommand;
window.showVoiceHelp = showVoiceHelp;
window.initializeVoiceEventListeners = initializeVoiceEventListeners;
window.showLowConfidenceRejection = showLowConfidenceRejection;
window.showRejectionMessage = showRejectionMessage;
window.showPartialConfirmation = showPartialConfirmation;
window.resetToWakeWordDetection = resetToWakeWordDetection;