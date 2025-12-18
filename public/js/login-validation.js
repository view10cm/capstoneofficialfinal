document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const signInBtn = document.getElementById('signInBtn');
    const emailError = document.getElementById('emailError');
    const loginForm = document.querySelector('form');
    
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    function updateButtonState() {
        const isEmailValid = validateEmail(emailInput.value);
        const isPasswordValid = passwordInput.value.length >= 6;
        
        // Show/hide email format error
        emailError.classList.toggle('hidden', isEmailValid || emailInput.value === '');
        
        // Enable/disable sign in button
        signInBtn.disabled = !(isEmailValid && isPasswordValid);
    }
    
    // Real-time validation on input
    emailInput.addEventListener('input', updateButtonState);
    passwordInput.addEventListener('input', updateButtonState);
    
    // Client-side form validation before submission
    loginForm.addEventListener('submit', function(event) {
        const isEmailValid = validateEmail(emailInput.value);
        const isPasswordValid = passwordInput.value.length >= 6;
        
        if (!isEmailValid || !isPasswordValid) {
            event.preventDefault();
            
            if (!isEmailValid) {
                emailError.textContent = 'Please enter a valid email address';
                emailError.classList.remove('hidden');
            }
            
            if (!isPasswordValid) {
                // If you want to show password error, you can add similar logic
                passwordInput.classList.add('border-red-500');
            }
            
            return false;
        }
        
        // All client-side validation passed
        signInBtn.disabled = true;
        signInBtn.innerHTML = 'Signing in...';
    });
    
    // Clear email error when user starts typing
    emailInput.addEventListener('focus', function() {
        emailError.classList.add('hidden');
        emailInput.classList.remove('border-red-500');
    });
    
    // Clear password error when user starts typing
    passwordInput.addEventListener('focus', function() {
        passwordInput.classList.remove('border-red-500');
    });
    
    // Initial validation
    updateButtonState();
});