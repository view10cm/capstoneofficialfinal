// public/js/auto-navigate.js
document.addEventListener('DOMContentLoaded', function() {
    console.log('Auto-navigation script loaded. Will redirect in 7 seconds...');
    
    // Set timeout for 7 seconds (7000 milliseconds)
    setTimeout(function() {
        console.log('Redirecting to login page...');
        
        // Navigate to login page
        // You can use either:
        // 1. Direct URL
        window.location.href = '/login';
        
        // OR if you prefer named routes in JavaScript:
        // window.location.href = route('login');
        
    }, 7000); // 7000 milliseconds = 7 seconds
});