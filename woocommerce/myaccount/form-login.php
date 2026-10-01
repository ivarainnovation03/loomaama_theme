    <style>
        /* ============================= */
        /* GLOBAL STYLES & VARIABLES     */
        /* ============================= */
        :root {
            /* Primary Backgrounds */
            --bg-main: #FAF9F7;
            --bg-editorial: #F4EEE4;
            --bg-product: #FFFFFF;
            --bg-card: #F7F3EE;
            --bg-dark: #2A2A2A;
            --bg-footer: #F4EEE4;
            --bg-footer-light: #F7F3EE;

            /* Text Colors */
            --text-primary: #2A2A2A;
            --text-secondary: #6F6A63;
            --text-btn: #FFFFFF;
            --text-editorial: #F4EEE4;

            /* Brand Accent */
            --brand: #9E8574;
            --brand-hover: #8a7363;

            /* UI Elements */
            --border: #DED7CD;
            --border-light: rgba(222, 215, 205, 0.5);

            /* Typography */
            --font-headline: 'Work Sans', sans-serif;
            --font-body: 'Inter', sans-serif;

            /* Spacing */
            --nav-height: 80px;
            --section-spacing: 110px;
            --section-spacing-mobile: 70px;
            --grid-gap: 30px;

            /* Transitions */
            --transition-base: all 0.3s ease;
            --transition-slow: all 0.6s cubic-bezier(0.19, 1, 0.22, 1);
            --transition-bounce: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

       

        /* Main container – modern card with depth */
        .modern-account-container {
            max-width: 900px;
            width: 100%;
            margin: 80px auto;
			
        }

        /* card layout: two columns with elegant separation */
        .auth-grid {
            display: flex;
            flex-wrap: wrap;
            background: var(--bg-product);
            border-radius: 2rem;
            box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.2), 0 1px 2px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            transition: transform 0.2s ease;
        }

        .auth-col {
            flex: 1;
            padding: 2.8rem 2.5rem;
            transition: all 0.3s ease;
        }

        .auth-col:first-child {
            border-right: 1px solid var(--border);
            background-color: var(--bg-product);
        }

        .auth-col:last-child {
            background-color: #fefefe;
        }

        /* Hide register column initially */
        .auth-col-register {
            display: none;
        }

        .auth-col-register.active {
            display: block;
            animation: fadeInRight 0.4s ease;
        }

        .auth-col-login {
            display: block;
        }

        .auth-col-login.hide {
            display: none;
        }

        /* Animations */
        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* headings */
        .auth-heading {
            font-size: 1.85rem;
            font-weight: 600;
            letter-spacing: -0.3px;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .auth-heading i {
            color: var(--brand);
            font-size: 1.8rem;
            font-weight: 400;
        }

        .auth-sub {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 2rem;
            border-left: 3px solid var(--brand);
            padding-left: 0.75rem;
            font-weight: 450;
        }

        /* Tab Navigation */
        .auth-tabs {
            display: flex;
            gap: 0;
            margin-bottom: 2rem;
            border-bottom: 2px solid var(--border);
        }

        .auth-tab {
            flex: 1;
            text-align: center;
            padding: 1rem 1.5rem;
            background: transparent;
            border: none;
            font-family: var(--font-headline);
            font-size: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-secondary);
            cursor: pointer;
            transition: var(--transition-base);
            position: relative;
        }

        .auth-tab:hover {
            color: var(--brand);
        }

        .auth-tab.active {
            color: var(--brand);
        }

        .auth-tab.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--brand);
        }

        /* form styling */
        .modern-form .form-row-modern {
            margin-bottom: 1.5rem;
        }

        .modern-form label {
            display: flex;
            align-items: center;
            font-weight: 500;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .modern-form label i {
            margin-right: 0.5rem;
            font-size: 0.85rem;
            color: var(--brand);
            width: 1.2rem;
        }

        .required-star {
            color: #d14545;
            margin-left: 4px;
            font-size: 0.9rem;
        }

        .input-modern {
            width: 100%;
            padding: 0.85rem 1rem;
            font-size: 0.95rem;
            font-family: var(--font-body);
            border: 1.5px solid var(--border);
            border-radius: 1rem;
            background-color: #fff;
            transition: var(--transition-base);
            outline: none;
            color: var(--text-primary);
            font-weight: 450;
        }

        .input-modern:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 4px rgba(158, 133, 116, 0.15);
        }

        .input-modern::placeholder {
            color: #b9c3d0;
            font-weight: 400;
        }

        /* checkbox row */
        .checkbox-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 1.8rem;
        }

        .checkbox-label-modern {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-secondary);
        }

        .checkbox-label-modern input {
            width: 1rem;
            height: 1rem;
            accent-color: var(--brand);
            margin: 0;
        }

        .lost-password-link {
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--text-secondary);
            text-decoration: none;
            transition: var(--transition-base);
        }

        .lost-password-link:hover {
            color: var(--brand);
            text-decoration: underline;
        }

        /* button modern */
        .btn-modern-primary {
            background: var(--brand);
            border: none;
            padding: 0.9rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 600;
            font-family: var(--font-headline);
            border-radius: 2rem;
            color: white;
            width: 100%;
            cursor: pointer;
            transition: var(--transition-base);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .btn-modern-primary i {
            font-size: 1rem;
        }

        .btn-modern-primary:hover {
            background: var(--brand-hover);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(158, 133, 116, 0.2);
        }

        .btn-modern-secondary {
            background: #ffffff;
            border: 1.5px solid var(--border);
            padding: 0.85rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 600;
            font-family: var(--font-headline);
            border-radius: 2rem;
            width: 100%;
            cursor: pointer;
            transition: var(--transition-base);
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
        }

        .btn-modern-secondary:hover {
            background: var(--bg-card);
            border-color: var(--brand);
            color: var(--brand);
        }

        /* notice / extra messages */
        .info-message {
            background: var(--bg-card);
            padding: 0.9rem 1rem;
            border-radius: 1rem;
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            border-left: 4px solid var(--brand);
        }

        .info-message i {
            font-size: 1rem;
            color: var(--brand);
        }

        hr.divider-light {
            margin: 1rem 0;
            border: 0;
            height: 1px;
            background: linear-gradient(to right, var(--border), transparent);
        }

        /* Switch link */
        .switch-auth-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .switch-auth-link a {
            color: var(--brand);
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
        }

        .switch-auth-link a:hover {
            text-decoration: underline;
        }

        /* responsive elegance */
        @media (max-width: 780px) {
            .auth-grid {
                flex-direction: column;
                border-radius: 1.5rem;
            }
            .auth-col:first-child {
                border-right: none;
                border-bottom: 1px solid var(--border);
            }
            .auth-col {
                padding: 2rem 1.5rem;
            }
            .auth-heading {
                font-size: 1.5rem;
            }
            .auth-tab {
                padding: 0.75rem 1rem;
                font-size: 0.85rem;
            }
        }

        .screen-reader-text {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
        }
    </style>

<div class="modern-account-container">
    <?php
    /**
     * Professional version of WooCommerce My Account Login/Register template
     * Enhanced with modern UI, improved accessibility, professional look & feel.
     * Optimized with tab-based interface - shows login first, register on click
     */
    
    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly.
    }
    
    do_action( 'woocommerce_before_customer_login_form' );
    ?>
    
    <?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>
    
    <div class="auth-grid" id="customer_login">
        <!-- Tab Navigation -->
       

        <!-- LOGIN COLUMN -->
        <div class="auth-col auth-col-login" id="login-col">
            <div class="auth-heading">
                <i class="fas fa-sign-in"></i> 
                <span><?php esc_html_e( 'Welcome Back', 'woocommerce' ); ?></span>
            </div>
            <div class="auth-sub"><?php esc_html_e( 'Sign in to access your account & orders', 'woocommerce' ); ?></div>
            
            <form class="modern-form woocommerce-form woocommerce-form-login login" method="post" novalidate>
                <?php do_action( 'woocommerce_login_form_start' ); ?>
                
                <div class="form-row-modern">
                    <label for="username">
                        <i class="fas fa-envelope"></i> 
                        <?php esc_html_e( 'Username or email', 'woocommerce' ); ?>
                        <span class="required-star" aria-hidden="true">*</span>
                        <span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span>
                    </label>
                    <input type="text" 
                           class="input-modern woocommerce-Input woocommerce-Input--text input-text" 
                           name="username" 
                           id="username" 
                           autocomplete="username" 
                           value="<?php echo ( ! empty( $_POST['username'] ) && is_string( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" 
                           required 
                           aria-required="true"
                           placeholder="<?php esc_attr_e( 'your@email.com or username', 'woocommerce' ); ?>" />
                </div>
                
                <div class="form-row-modern">
                    <label for="password">
                        <i class="fas fa-lock"></i> 
                        <?php esc_html_e( 'Password', 'woocommerce' ); ?>
                        <span class="required-star" aria-hidden="true">*</span>
                        <span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span>
                    </label>
                    <input class="input-modern woocommerce-Input woocommerce-Input--text input-text" 
                           type="password" 
                           name="password" 
                           id="password" 
                           autocomplete="current-password" 
                           required 
                           aria-required="true"
                           placeholder="••••••••" />
                </div>
                
                <?php do_action( 'woocommerce_login_form' ); ?>
                
                <div class="checkbox-row">
                    <label class="checkbox-label-modern">
                        <input class="woocommerce-form__input woocommerce-form__input-checkbox" 
                               name="rememberme" 
                               type="checkbox" 
                               id="rememberme" 
                               value="forever" />
                        <span><?php esc_html_e( 'Keep me signed in', 'woocommerce' ); ?></span>
                    </label>
                    <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="lost-password-link">
                        <i class="fas fa-key"></i> <?php esc_html_e( 'Forgot password?', 'woocommerce' ); ?>
                    </a>
                </div>
                
                <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
                
                <button type="submit" 
                        class="btn-modern-primary woocommerce-button button woocommerce-form-login__submit" 
                        name="login" 
                        value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>">
                    <i class="fas fa-arrow-right-to-bracket"></i> <?php esc_html_e( 'Log In', 'woocommerce' ); ?>
                </button>
                
                <hr class="divider-light" />
                
                <div class="switch-auth-link">
                    <?php esc_html_e( "Don't have an account?", 'woocommerce' ); ?> 
                    <a class="switch-to-register"><?php esc_html_e( 'Create one', 'woocommerce' ); ?></a>
                </div>
                
                <div class="info-message" style="margin-top: 0.5rem;">
                    <i class="fas fa-shield-alt"></i> 
                    <span><?php esc_html_e( 'Secure login with encrypted connection.', 'woocommerce' ); ?></span>
                </div>
                
                <?php do_action( 'woocommerce_login_form_end' ); ?>
            </form>
        </div>
        
        <!-- REGISTER COLUMN -->
        <div class="auth-col auth-col-register" id="register-col">
            <div class="auth-heading">
                <i class="fas fa-user"></i> 
                <span><?php esc_html_e( 'Create Account', 'woocommerce' ); ?></span>
            </div>
            <div class="auth-sub"><?php esc_html_e( 'Join for faster checkout & exclusive benefits', 'woocommerce' ); ?></div>
            
            <form method="post" class="modern-form woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?>>
                <?php do_action( 'woocommerce_register_form_start' ); ?>
                
                <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
                    <div class="form-row-modern">
                        <label for="reg_username">
                            <i class="fas fa-user-circle"></i> 
                            <?php esc_html_e( 'Username', 'woocommerce' ); ?>
                            <span class="required-star" aria-hidden="true">*</span>
                            <span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span>
                        </label>
                        <input type="text" 
                               class="input-modern woocommerce-Input woocommerce-Input--text input-text" 
                               name="username" 
                               id="reg_username" 
                               autocomplete="username" 
                               value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" 
                               required 
                               aria-required="true"
                               placeholder="<?php esc_attr_e( 'Choose a username', 'woocommerce' ); ?>" />
                    </div>
                <?php endif; ?>
                
                <div class="form-row-modern">
                    <label for="reg_email">
                        <i class="fas fa-at"></i> 
                        <?php esc_html_e( 'Email address', 'woocommerce' ); ?>
                        <span class="required-star" aria-hidden="true">*</span>
                        <span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span>
                    </label>
                    <input type="email" 
                           class="input-modern woocommerce-Input woocommerce-Input--text input-text" 
                           name="email" 
                           id="reg_email" 
                           autocomplete="email" 
                           value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" 
                           required 
                           aria-required="true"
                           placeholder="<?php esc_attr_e( 'hello@example.com', 'woocommerce' ); ?>" />
                </div>
                
                <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
                    <div class="form-row-modern">
                        <label for="reg_password">
                            <i class="fas fa-lock"></i> 
                            <?php esc_html_e( 'Password', 'woocommerce' ); ?>
                            <span class="required-star" aria-hidden="true">*</span>
                            <span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span>
                        </label>
                        <input type="password" 
                               class="input-modern woocommerce-Input woocommerce-Input--text input-text" 
                               name="password" 
                               id="reg_password" 
                               autocomplete="new-password" 
                               required 
                               aria-required="true"
                               placeholder="<?php esc_attr_e( 'Create strong password', 'woocommerce' ); ?>" />
                    </div>
                <?php else : ?>
                    <div class="info-message">
                        <i class="fas fa-envelope-open-text"></i>
                        <span><?php esc_html_e( 'A link to set a new password will be sent to your email address.', 'woocommerce' ); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php do_action( 'woocommerce_register_form' ); ?>
                
                <div class="form-row-modern" style="margin-top: 0.5rem;">
                    <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
                    <button type="submit" 
                            class="btn-modern-secondary woocommerce-Button woocommerce-button button woocommerce-form-register__submit" 
                            name="register" 
                            value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>">
                        <i class="fas fa-user-check"></i> <?php esc_html_e( 'Create Account', 'woocommerce' ); ?>
                    </button>
                </div>
                
                <hr class="divider-light" />
                
                <div class="switch-auth-link">
                    <?php esc_html_e( 'Already have an account?', 'woocommerce' ); ?> 
                    <a class="switch-to-login"><?php esc_html_e( 'Sign in', 'woocommerce' ); ?></a>
                </div>
                
                <div class="info-message" style="margin-top: 1rem; background: var(--bg-card);">
                    <i class="fas fa-lock"></i>
                    <span><?php esc_html_e( 'Your data is safe with us. We never share personal info.', 'woocommerce' ); ?></span>
                </div>
                
                <?php do_action( 'woocommerce_register_form_end' ); ?>
            </form>
        </div>
    </div>
    
    <?php else : ?>
        <!-- If registration disabled: show only login with elegant centered look -->
        <div class="auth-grid" style="max-width: 550px; margin: 0 auto;">
            <div class="auth-col" style="flex: 1; border-right: none;">
                <div class="auth-heading">
                    <i class="fas fa-key"></i> 
                    <span><?php esc_html_e( 'Sign in to your account', 'woocommerce' ); ?></span>
                </div>
                <div class="auth-sub"><?php esc_html_e( 'Access your orders and saved details', 'woocommerce' ); ?></div>
                
                <form class="modern-form woocommerce-form woocommerce-form-login login" method="post" novalidate>
                    <?php do_action( 'woocommerce_login_form_start' ); ?>
                    <div class="form-row-modern">
                        <label for="username"><i class="fas fa-envelope"></i> <?php esc_html_e( 'Username or email', 'woocommerce' ); ?> <span class="required-star">*</span></label>
                        <input type="text" class="input-modern" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) && is_string( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required placeholder="email@domain.com or username" />
                    </div>
                    <div class="form-row-modern">
                        <label for="password"><i class="fas fa-lock"></i> <?php esc_html_e( 'Password', 'woocommerce' ); ?> <span class="required-star">*</span></label>
                        <input class="input-modern" type="password" name="password" id="password" autocomplete="current-password" required placeholder="••••••••" />
                    </div>
                    <?php do_action( 'woocommerce_login_form' ); ?>
                    <div class="checkbox-row">
                        <label class="checkbox-label-modern">
                            <input name="rememberme" type="checkbox" value="forever" /> <span><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
                        </label>
                        <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="lost-password-link"><i class="fas fa-question-circle"></i> <?php esc_html_e( 'Lost password?', 'woocommerce' ); ?></a>
                    </div>
                    <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
                    <button type="submit" class="btn-modern-primary" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"><i class="fas fa-sign-in-alt"></i> <?php esc_html_e( 'Log in', 'woocommerce' ); ?></button>
                    <?php do_action( 'woocommerce_login_form_end' ); ?>
                </form>
            </div>
        </div>
    <?php endif; ?>
    
    <?php do_action( 'woocommerce_after_customer_login_form' ); ?>
</div>

<script>
    // Tab switching functionality
    document.addEventListener('DOMContentLoaded', function() {
        const loginCol = document.getElementById('login-col');
        const registerCol = document.getElementById('register-col');
        const loginTab = document.querySelector('.auth-tab[data-tab="login"]');
        const registerTab = document.querySelector('.auth-tab[data-tab="register"]');
        const switchToRegister = document.querySelector('.switch-to-register');
        const switchToLogin = document.querySelector('.switch-to-login');
        
        // Function to show login form
        function showLogin() {
            if (loginCol) {
                loginCol.classList.remove('hide');
                loginCol.style.display = 'block';
                loginCol.style.animation = 'fadeInLeft 0.4s ease';
            }
            if (registerCol) {
                registerCol.classList.remove('active');
                registerCol.style.display = 'none';
            }
            if (loginTab) loginTab.classList.add('active');
            if (registerTab) registerTab.classList.remove('active');
        }
        
        // Function to show register form
        function showRegister() {
            if (loginCol) {
                loginCol.classList.add('hide');
                loginCol.style.display = 'none';
            }
            if (registerCol) {
                registerCol.classList.add('active');
                registerCol.style.display = 'block';
                registerCol.style.animation = 'fadeInRight 0.4s ease';
            }
            if (loginTab) loginTab.classList.remove('active');
            if (registerTab) registerTab.classList.add('active');
        }
        
        // Tab click handlers
        if (loginTab) {
            loginTab.addEventListener('click', showLogin);
        }
        
        if (registerTab) {
            registerTab.addEventListener('click', showRegister);
        }
        
        // Switch link handlers
        if (switchToRegister) {
            switchToRegister.addEventListener('click', function(e) {
                e.preventDefault();
                showRegister();
            });
        }
        
        if (switchToLogin) {
            switchToLogin.addEventListener('click', function(e) {
                e.preventDefault();
                showLogin();
            });
        }
        
        // Ensure login is shown by default
        showLogin();
        
        // Handle URL hash for direct access
        if (window.location.hash === '#register') {
            showRegister();
        }
    });
</script>
