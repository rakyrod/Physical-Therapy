
// Toggle password visibility
document.getElementById('toggle-password').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const icon = this.querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});

// Form validation and submission
document.getElementById('login-form').addEventListener('submit', function(e) {
    let isValid = true;
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const emailError = document.getElementById('email-error');
    const passwordError = document.getElementById('password-error');
    
    // Validate email
    if (!email.value.trim() || !email.value.includes('@')) {
        emailError.classList.remove('hidden');
        email.classList.add('border-red-500');
        email.classList.add('bg-red-50');
        isValid = false;
    } else {
        emailError.classList.add('hidden');
        email.classList.remove('border-red-500');
        email.classList.remove('bg-red-50');
    }
    
    // Validate password
    if (!password.value.trim()) {
        passwordError.classList.remove('hidden');
        password.classList.add('border-red-500');
        password.classList.add('bg-red-50');
        isValid = false;
    } else {
        passwordError.classList.add('hidden');
        password.classList.remove('border-red-500');
        password.classList.remove('bg-red-50');
    }
    
    if (!isValid) {
        e.preventDefault();
        return;
    }
    
    // Show loading indicator
    const submitBtn = document.getElementById('login-btn');
    submitBtn.innerHTML = `
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Signing in...
    `;
    submitBtn.disabled = true;
    submitBtn.classList.add('bg-primary-400');
    submitBtn.classList.remove('hover:bg-primary-700');
    submitBtn.classList.remove('hover:-translate-y-0.5');
});

// Auto-fill remembered user if cookie exists
document.addEventListener('DOMContentLoaded', function() {
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }
    
    const rememberToken = getCookie('remember_token');
    if (rememberToken) {
        document.getElementById('remember').checked = true;
    }
    
    // Focus on email field by default
    document.getElementById('email').focus();
});
