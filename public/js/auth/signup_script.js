
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

document.getElementById('toggle-confirm-password').addEventListener('click', function() {
    const confirmPasswordInput = document.getElementById('confirm_password');
    const icon = this.querySelector('i');
    
    if (confirmPasswordInput.type === 'password') {
        confirmPasswordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        confirmPasswordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});

// Password validation and strength meter
document.getElementById('password').addEventListener('input', function() {
    var password = this.value;
    var confirmPassword = document.getElementById('confirm_password');
    var meter = document.getElementById('password-meter');
    var strengthText = document.getElementById('password-strength-text');
    
    // Check password match
    if (confirmPassword.value && password !== confirmPassword.value) {
        confirmPassword.classList.add('border-red-500');
        confirmPassword.classList.add('bg-red-50');
    } else if (confirmPassword.value) {
        confirmPassword.classList.remove('border-red-500');
        confirmPassword.classList.remove('bg-red-50');
    }
    
    // Check password strength
    var strength = 0;
    if (password.match(/[a-z]+/)) {
        strength += 1;
    }
    if (password.match(/[A-Z]+/)) {
        strength += 1;
    }
    if (password.match(/[0-9]+/)) {
        strength += 1;
    }
    if (password.match(/[^a-zA-Z0-9]+/)) {
        strength += 1;
    }
    
    // Update strength meter
    meter.className = 'h-full transition-all duration-300';
    
    if (password.length === 0) {
        meter.classList.add('w-0');
        meter.classList.remove('w-1/4', 'w-1/2', 'w-3/4', 'w-full', 'bg-red-500', 'bg-yellow-500', 'bg-green-500');
        strengthText.textContent = 'Strength';
        strengthText.className = 'text-xs text-gray-500 whitespace-nowrap';
    } else if (password.length < 8) {
        meter.classList.add('w-1/4', 'bg-red-500');
        meter.classList.remove('w-0', 'w-1/2', 'w-3/4', 'w-full', 'bg-yellow-500', 'bg-green-500');
        strengthText.textContent = 'Weak';
        strengthText.className = 'text-xs text-red-500 whitespace-nowrap';
    } else if (strength < 3) {
        meter.classList.add('w-1/2', 'bg-yellow-500');
        meter.classList.remove('w-0', 'w-1/4', 'w-3/4', 'w-full', 'bg-red-500', 'bg-green-500');
        strengthText.textContent = 'Fair';
        strengthText.className = 'text-xs text-yellow-600 whitespace-nowrap';
    } else {
        meter.classList.add('w-full', 'bg-green-500');
        meter.classList.remove('w-0', 'w-1/4', 'w-1/2', 'w-3/4', 'bg-red-500', 'bg-yellow-500');
        strengthText.textContent = 'Strong';
        strengthText.className = 'text-xs text-green-600 whitespace-nowrap';
    }
});

document.getElementById('confirm_password').addEventListener('input', function() {
    var password = document.getElementById('password').value;
    if (this.value !== password) {
        this.classList.add('border-red-500');
        this.classList.add('bg-red-50');
    } else {
        this.classList.remove('border-red-500');
        this.classList.remove('bg-red-50');
    }
});

// Form validation and submission
document.getElementById('signup-form').addEventListener('submit', function(e) {
    let isValid = true;
    const firstName = document.getElementById('first_name');
    const lastName = document.getElementById('last_name');
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    const terms = document.getElementById('terms');
    
    // Basic validation
    if (!firstName.value.trim() || !lastName.value.trim() || !email.value.trim() || 
        !password.value.trim() || !confirmPassword.value.trim() || !terms.checked) {
        isValid = false;
    }
    
    // Validate email format
    if (!email.value.includes('@')) {
        email.classList.add('border-red-500');
        email.classList.add('bg-red-50');
        isValid = false;
    } else {
        email.classList.remove('border-red-500');
        email.classList.remove('bg-red-50');
    }
    
    // Validate password
    if (password.value.length < 8 || !password.value.match(/[A-Z]/) || 
        !password.value.match(/[a-z]/) || !password.value.match(/[0-9]/)) {
        password.classList.add('border-red-500');
        password.classList.add('bg-red-50');
        isValid = false;
    } else {
        password.classList.remove('border-red-500');
        password.classList.remove('bg-red-50');
    }
    
    // Validate password match
    if (password.value !== confirmPassword.value) {
        confirmPassword.classList.add('border-red-500');
        confirmPassword.classList.add('bg-red-50');
        isValid = false;
    } else {
        confirmPassword.classList.remove('border-red-500');
        confirmPassword.classList.remove('bg-red-50');
    }
    
    if (!isValid) {
        e.preventDefault();
        return;
    }
    
    // Show loading indicator
    const submitBtn = document.getElementById('signup-btn');
    submitBtn.innerHTML = `
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Creating your account...
    `;
    submitBtn.disabled = true;
    submitBtn.classList.add('bg-primary-400');
    submitBtn.classList.remove('hover:bg-primary-700');
    submitBtn.classList.remove('hover:-translate-y-0.5');
});

// Terms and Conditions Modal functionality
const modal = document.getElementById('terms-modal');
const modalBackdrop = document.getElementById('terms-modal-backdrop');
const modalTrigger = document.getElementById('terms-modal-trigger');
const modalClose = document.getElementById('terms-modal-close');

function openModal() {
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    
    // Add animation classes
    setTimeout(() => {
        modalBackdrop.classList.add('opacity-100');
        modalBackdrop.classList.remove('opacity-0');
        modal.querySelector('div > div:nth-child(2)').classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
        modal.querySelector('div > div:nth-child(2)').classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
    }, 10);
}

function closeModal() {
    // Add animation classes
    modalBackdrop.classList.add('opacity-0');
    modalBackdrop.classList.remove('opacity-100');
    modal.querySelector('div > div:nth-child(2)').classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
    modal.querySelector('div > div:nth-child(2)').classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
    
    // Hide modal after animation
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }, 300);
}

modalTrigger.addEventListener('click', function(e) {
    e.preventDefault();
    openModal();
});

modalClose.addEventListener('click', closeModal);
modalBackdrop.addEventListener('click', closeModal);

// Close modal with escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
        closeModal();
    }
});

// Focus on first name field by default
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('first_name').focus();
});
