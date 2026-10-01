
    <style>
       

       

        .container {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 0 10%;
            text-align: center;
        }

        .logo {
            font-family: var(--font-headline);
            font-weight: 600;
            font-size: 1.5rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-bottom: 80px;
            position: absolute;
            top: 60px;
        }

        .logo a {
            text-decoration: none;
            color: var(--text-primary);
        }

        .content-box {
            animation: fadeIn 1.5s ease-out;
			min-height: 80vh;
			display: flex;
			flex-direction: column;
			justify-content: center;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .subtitle {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.3em;
            color: var(--brand);
            margin-bottom: 30px;
            display: block;
        }

        .title {
            font-family: var(--font-headline);
            font-size: clamp(2.5rem, 6vw, 4rem);
            font-weight: 500;
            line-height: 1.1;
            margin-bottom: 40px;
            letter-spacing: -0.02em;
        }

        .vow-text {
            font-size: 1rem;
            line-height: 1.8;
            color: var(--text-secondary);
            margin-bottom: 60px;
            font-weight: 300;
            max-width: 450px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Email Capture */
        .newsletter-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }

        .input-wrapper {
            width: 100%;
            border-bottom: 1px solid var(--text-primary);
            display: flex;
            padding-bottom: 10px;
            margin-bottom: 20px;
            transition: border-color 0.3s ease;
        }

        .input-wrapper:focus-within {
            border-bottom-color: var(--brand);
        }

        .email-input {
            background: transparent;
            border: none;
            flex: 1;
            font-family: var(--font-body);
            font-size: 0.9rem;
            padding: 10px 0;
            outline: none;
            color: var(--text-primary);
        }

        .email-input::placeholder {
            color: var(--text-secondary);
            opacity: 0.6;
        }

        .submit-btn {
            background: transparent;
            border: none;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.2em;
            font-weight: 600;
            cursor: pointer;
            color: var(--text-primary);
            transition: color 0.3s;
            padding: 10px 0 10px 15px;
        }

        .submit-btn:hover:not(:disabled) {
            color: var(--brand);
        }

        .submit-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Message Styles */
        .message-container {
            margin-top: 20px;
            min-height: 60px;
        }

        .success-message, .error-message, .info-message {
            font-size: 0.8rem;
            letter-spacing: 0.05em;
            padding: 12px 20px;
            border-radius: 0;
            animation: slideDown 0.5s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-message {
            background: rgba(158, 133, 116, 0.1);
            color: var(--brand);
            border-left: 3px solid var(--brand);
        }

        .error-message {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border-left: 3px solid #dc3545;
        }

        .info-message {
            background: rgba(111, 106, 99, 0.1);
            color: var(--text-secondary);
            border-left: 3px solid var(--text-secondary);
        }

        /* Loading Spinner */
        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid var(--text-primary);
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            margin-left: 8px;
            vertical-align: middle;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Social/Footer */
        .footer-links {
            bottom: 60px;
            display: flex;
            gap: 40px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .footer-links a {
            text-decoration: none;
            color: var(--text-primary);
            transition: opacity 0.3s;
        }

        .footer-links a:hover {
            opacity: 0.6;
        }

        @media (max-width: 768px) {
            .logo { top: 40px; font-size: 1.2rem; }
            .footer-links { bottom: 40px; gap: 20px; }
            .vow-text { font-size: 0.9rem; }
        }
    </style>
<div class="hero-bg"></div>

<div class="container">
   

    <main class="content-box">
        <span class="subtitle">A Vow of Clarity</span>
        <h1 class="title dissplay-6">The First Collection <br> Coming Soon</h1>
        <p class="vow-text">
            We are crafting a collection that respects the earth, the body, and the quiet dignity of natural fibers. <br>Made in Nepal. Shaped by hand.<br>Our pieces are being prepared with care. 
        </p>

        <form id="subscriptionForm" class="newsletter-form" method="POST">
            <div class="input-wrapper">
                <input type="email" id="subscriberEmail" class="email-input" placeholder="Enter your email address" required>
                <button type="submit" id="subscribeBtn" class="submit-btn">Notify Me</button>
            </div>
        </form>
        
        <div id="messageContainer" class="message-container"></div>

		 <div class="footer-links mt-3">
			<a href="https://www.facebook.com/profile.php?id=61581963423128" target="_blank">Facebook</a>
			<a href="https://www.instagram.com/loomaama.official/" target="_blank">Instagram</a>
			<a href="#" target="_blank">LinkedIn</a>
		</div>
    </main>

   
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('subscriptionForm');
    const emailInput = document.getElementById('subscriberEmail');
    const submitBtn = document.getElementById('subscribeBtn');
    const messageContainer = document.getElementById('messageContainer');

    // Function to show message
    function showMessage(message, type = 'success') {
        const messageDiv = document.createElement('div');
        messageDiv.className = `${type}-message`;
        messageDiv.innerHTML = message;
        
        // Clear previous messages
        messageContainer.innerHTML = '';
        messageContainer.appendChild(messageDiv);
        
        // Auto-hide after 5 seconds for success messages
        if (type === 'success') {
            setTimeout(() => {
                messageDiv.style.opacity = '0';
                messageDiv.style.transition = 'opacity 0.5s ease';
                setTimeout(() => {
                    if (messageDiv.parentNode) {
                        messageDiv.remove();
                    }
                }, 500);
            }, 5000);
        }
    }

    // Function to set loading state
    function setLoading(isLoading) {
        if (isLoading) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Processing <span class="spinner"></span>';
        } else {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Notify Me';
        }
    }

    // Validate email
    function validateEmail(email) {
        const re = /^[^\s@]+@([^\s@.,]+\.)+[^\s@.,]{2,}$/;
        return re.test(email);
    }

    // Handle form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const email = emailInput.value.trim();
        
        // Validation
        if (!email) {
            showMessage('Please enter your email address.', 'error');
            emailInput.focus();
            return;
        }
        
        if (!validateEmail(email)) {
            showMessage('Please enter a valid email address.', 'error');
            emailInput.focus();
            return;
        }
        
        // Set loading state
        setLoading(true);
        
        // Prepare form data
        const formData = new URLSearchParams();
        formData.append('action', 'loomaama_subscribe');
        formData.append('email', email);
        formData.append('nonce', '<?php echo wp_create_nonce('loomaama_subscribe_nonce'); ?>');
        
        try {
            const response = await fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                showMessage(data.data.message || 'Thank you for subscribing! You\'ve been added to our waitlist.', 'success');
                emailInput.value = ''; // Clear the input
            } else {
                showMessage(data.data.message || 'Something went wrong. Please try again.', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showMessage('Network error. Please check your connection and try again.', 'error');
        } finally {
            setLoading(false);
        }
    });
});
</script>

