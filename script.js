// DOM Elements
const loginBtn = document.getElementById('loginBtn');
const registerBtn = document.getElementById('registerBtn');
const viewProductsBtn = document.getElementById('viewProductsBtn');
const learnMoreBtn = document.getElementById('learnMoreBtn');
const buyButtons = document.querySelectorAll('.buy-btn');
const logoutBtn = document.getElementById('logoutBtn');
const dashboardBtn = document.getElementById('dashboardBtn');

const loginModal = document.getElementById('loginModal');
const registerModal = document.getElementById('registerModal');
const paymentModal = document.getElementById('paymentModal');
const successModal = document.getElementById('successModal');

const closeLoginModal = document.getElementById('closeLoginModal');
const closeRegisterModal = document.getElementById('closeRegisterModal');
const closePaymentModal = document.getElementById('closePaymentModal');
const closeSuccessModal = document.getElementById('closeSuccessModal');

const closeLoginBtn = document.getElementById('closeLoginBtn');
const closeRegisterBtn = document.getElementById('closeRegisterBtn');
const closePaymentBtn = document.getElementById('closePaymentBtn');
const closeSuccessBtn = document.getElementById('closeSuccessBtn');

const loginForm = document.getElementById('loginForm');
const registerForm = document.getElementById('registerForm');
const paymentForm = document.getElementById('paymentForm');

const selectedProduct = document.getElementById('selectedProduct');
const selectedPrice = document.getElementById('selectedPrice');
const paymentProduct = document.getElementById('paymentProduct');
const paymentAmount = document.getElementById('paymentAmount');
const paymentMethod = document.getElementById('paymentMethod');
const paypalFields = document.getElementById('paypalFields');
const creditCardFields = document.getElementById('creditCardFields');

const authButtons = document.getElementById('authButtons');
const userMenu = document.getElementById('userMenu');
const userWelcome = document.getElementById('userWelcome');

// Check if user is logged in
function checkAuthStatus() {
    const user = localStorage.getItem('currentUser');
    if (user) {
        try {
            const userData = JSON.parse(user);
            authButtons.style.display = 'none';
            userMenu.style.display = 'flex';
            userWelcome.textContent = `Welcome, ${userData.username}!`;
        } catch (e) {
            console.error('Error parsing user data:', e);
            localStorage.removeItem('currentUser');
        }
    } else {
        authButtons.style.display = 'flex';
        userMenu.style.display = 'none';
    }
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    checkAuthStatus();
});

loginBtn.addEventListener('click', () => {
    loginModal.style.display = 'flex';
    loginForm.reset();
});

registerBtn.addEventListener('click', () => {
    registerModal.style.display = 'flex';
    registerForm.reset();
});

viewProductsBtn.addEventListener('click', () => document.getElementById('products').scrollIntoView({behavior: 'smooth'}));
learnMoreBtn.addEventListener('click', () => document.getElementById('security').scrollIntoView({behavior: 'smooth'}));

closeLoginModal.addEventListener('click', () => loginModal.style.display = 'none');
closeRegisterModal.addEventListener('click', () => registerModal.style.display = 'none');
closePaymentModal.addEventListener('click', () => paymentModal.style.display = 'none');
closeSuccessModal.addEventListener('click', () => successModal.style.display = 'none');

closeLoginBtn.addEventListener('click', () => loginModal.style.display = 'none');
closeRegisterBtn.addEventListener('click', () => registerModal.style.display = 'none');
closePaymentBtn.addEventListener('click', () => paymentModal.style.display = 'none');
closeSuccessBtn.addEventListener('click', () => successModal.style.display = 'none');

logoutBtn.addEventListener('click', function() {
    localStorage.removeItem('currentUser');
    checkAuthStatus();
    showAlert('Logged out successfully!', 'success');
});

dashboardBtn.addEventListener('click', function() {
    window.location.href = 'dashboard.php';
});

// Buy buttons
buyButtons.forEach(button => {
    button.addEventListener('click', function() {
        const product = this.getAttribute('data-product');
        const price = this.getAttribute('data-price');
        
        selectedProduct.textContent = product;
        selectedPrice.textContent = price;
        paymentProduct.value = product;
        paymentAmount.value = price;
        
        // Pre-fill email if user is logged in
        const user = localStorage.getItem('currentUser');
        if (user) {
            try {
                const userData = JSON.parse(user);
                document.getElementById('paymentEmail').value = userData.email;
            } catch (e) {
                console.error('Error parsing user data:', e);
            }
        }
        
        paymentModal.style.display = 'flex';
        paymentForm.reset();
        paypalFields.style.display = 'none';
        creditCardFields.style.display = 'none';
    });
});

// Payment method selection
paymentMethod.addEventListener('change', function() {
    if (this.value === 'paypal') {
        paypalFields.style.display = 'block';
        creditCardFields.style.display = 'none';
    } else if (this.value === 'creditcard') {
        paypalFields.style.display = 'none';
        creditCardFields.style.display = 'block';
    } else {
        paypalFields.style.display = 'none';
        creditCardFields.style.display = 'none';
    }
});

// Form submissions
loginForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Logging in...';
    submitBtn.disabled = true;
    
    try {
        const formData = new FormData(this);
        
        const response = await fetch('login.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            localStorage.setItem('currentUser', JSON.stringify(data.user));
            loginModal.style.display = 'none';
            checkAuthStatus();
            showAlert('Login successful!', 'success');
            this.reset();
        } else {
            showAlert(data.message, 'error');
        }
    } catch (error) {
        console.error('Login error:', error);
        showAlert('An error occurred. Please try again.', 'error');
    } finally {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }
});

registerForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    const password = document.getElementById('registerPassword').value;
    const confirmPassword = document.getElementById('registerConfirmPassword').value;
    
    if (password !== confirmPassword) {
        showAlert('Passwords do not match!', 'error');
        return;
    }
    
    if (password.length < 6) {
        showAlert('Password must be at least 6 characters long!', 'error');
        return;
    }
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Registering...';
    submitBtn.disabled = true;
    
    try {
        const formData = new FormData(this);
        
        const response = await fetch('register.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            registerModal.style.display = 'none';
            showAlert('Registration successful! Please login.', 'success');
            this.reset();
        } else {
            showAlert(data.message, 'error');
        }
    } catch (error) {
        console.error('Registration error:', error);
        showAlert('An error occurred. Please try again.', 'error');
    } finally {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }
});

paymentForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Processing...';
    submitBtn.disabled = true;
    
    try {
        const formData = new FormData(this);
        
        const response = await fetch('payment.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            paymentModal.style.display = 'none';
            
            // Update download links based on product
            const downloadLinks = document.querySelectorAll('#downloadLinks a');
            const product = document.getElementById('paymentProduct').value;
            
            if (product === 'Nitro Sniping Tool') {
                downloadLinks[0].style.display = 'block';
                downloadLinks[1].style.display = 'none';
            } else if (product === 'Token Sniping Tool') {
                downloadLinks[0].style.display = 'none';
                downloadLinks[1].style.display = 'block';
            } else {
                downloadLinks[0].style.display = 'block';
                downloadLinks[1].style.display = 'block';
            }
            
            successModal.style.display = 'flex';
            this.reset();
        } else {
            showAlert(data.message, 'error');
        }
    } catch (error) {
        console.error('Payment error:', error);
        showAlert('An error occurred. Please try again.', 'error');
    } finally {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }
});

// Close modals when clicking outside
window.addEventListener('click', function(e) {
    if (e.target === loginModal) loginModal.style.display = 'none';
    if (e.target === registerModal) registerModal.style.display = 'none';
    if (e.target === paymentModal) paymentModal.style.display = 'none';
    if (e.target === successModal) successModal.style.display = 'none';
});

// Smooth scrolling for navigation links
document.querySelectorAll('nav a').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href');
        if (targetId.startsWith('#')) {
            document.querySelector(targetId).scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});

// Show alert function
function showAlert(message, type) {
    // Remove existing alerts
    const existingAlert = document.querySelector('.alert');
    if (existingAlert) {
        existingAlert.remove();
    }
    
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.textContent = message;
    
    document.body.appendChild(alert);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alert.parentNode) {
            alert.parentNode.removeChild(alert);
        }
    }, 5000);
}