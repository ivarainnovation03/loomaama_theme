<?php
/**
 * Template Name: My Account
 * 
 * A professional implementation that properly handles
 * WooCommerce template overrides with fallbacks
 */

// Prevent direct 

/*
if (!defined('ABSPATH')) {
    exit;
}

get_header();

// Add custom body class for styling
add_filter('body_class', function($classes) {
    if (!is_user_logged_in()) {
        $classes[] = 'woocommerce-account';
        $classes[] = 'woocommerce-form-login-page';
    } else {
        $classes[] = 'woocommerce-account';
        $classes[] = 'woocommerce-account-logged-in';
    }
    return $classes;
});

// Get current WooCommerce endpoint
global $wp;
$current_endpoint = '';

if (!empty($wp->query_vars)) {
    $current_endpoint = key($wp->query_vars);
}
var_dump($current_endpoint); // Debug: Check current endpoint
// Check for lost-password endpoint
if ($current_endpoint === 'lost-password' || isset($_GET['action']) && $_GET['action'] === 'lost-password') {
    // Show lost password form
    ?>
    <div class="woocommerce-login-container" style="max-width: 900px; margin: 80px auto; padding: 20px;">
        <?php wc_get_template('myaccount/form-lost-password.php'); ?>
    </div>
    <?php
} 
// Check for reset-password endpoint
elseif (isset($_GET['key']) && isset($_GET['login'])) {
    // Show reset password form
    ?>
    <div class="woocommerce-login-container" style="max-width: 900px; margin: 80px auto; padding: 20px;">
        <?php wc_get_template('myaccount/form-reset-password.php'); ?>
    </div>
    <?php
}
elseif (is_user_logged_in()) {
    // User is logged in - display full account dashboard
    ?>
    <div class="woocommerce-account-container" style="max-width: 1400px; margin: 0 auto; padding: 40px 20px;">
        <div class="woocommerce-MyAccount-wrapper" style="display: flex; flex-wrap: wrap; gap: 40px;">
            
            <?php
            // Load navigation
            wc_get_template('myaccount/navigation.php');
            ?>
            
            <div class="woocommerce-MyAccount-content" style="flex: 1; min-width: 250px;">
                <?php
                // Let WooCommerce handle all account content
                do_action('woocommerce_account_content');
                ?>
            </div>
        </div>
    </div>
    <?php
} else {
    // User not logged in - show login/registration form
    ?>
    <div class="woocommerce-login-container" style="max-width: 900px; margin: 80px auto; padding: 20px;">
        <?php
        // Use WooCommerce's native form which includes the lost password link
        wc_get_template('myaccount/form-login.php');
        ?>
    </div>
    <?php
}

get_footer();

*/


/**
 * Template Name: My Account
 * 
 * A professional implementation that properly handles
 * WooCommerce template overrides with fallbacks
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header();

global $wp;
$current_endpoint = '';

// Get current endpoint from query vars
if (!empty($wp->query_vars)) {
    $endpoint_keys = array_keys($wp->query_vars);
    $current_endpoint = !empty($endpoint_keys) ? $endpoint_keys[0] : '';
}

// DEBUG: Uncomment to see what endpoint is detected
// echo '<!-- Current endpoint: ' . $current_endpoint . ' -->';

// Check if this is a lost password or reset password request
if ($current_endpoint === 'lost-password') {
    // Show lost password form
    ?>
    <div class="woocommerce-login-container" style="max-width: 900px; margin: 80px auto; padding: 20px;">
        <h2 class="text-center mb-4"><?php _e('Lost Password', 'loomaama'); ?></h2>
        <?php 
        // Try multiple methods to load the form
        if (file_exists(WC()->plugin_path() . '/templates/myaccount/form-lost-password.php')) {
            wc_get_template('myaccount/form-lost-password.php');
        } else {
            echo '<p>Template not found. Please check WooCommerce installation.</p>';
        }
        ?>
    </div>
    <?php
} 
elseif (isset($_GET['key']) && isset($_GET['login'])) {
    // Show reset password form
    ?>
    <div class="woocommerce-login-container" style="max-width: 900px; margin: 80px auto; padding: 20px;">
        <h2 class="text-center mb-4"><?php _e('Reset Password', 'loomaama'); ?></h2>
        <?php wc_get_template('myaccount/form-reset-password.php'); ?>
    </div>
    <?php
}
elseif (is_user_logged_in()) {
    // User is logged in - display full account dashboard
    ?>
    <div class="woocommerce-account-container" style="max-width: 1400px; margin: 0 auto; padding: 40px 20px;">
        <div class="woocommerce-MyAccount-wrapper" style="display: flex; flex-wrap: wrap; gap: 40px;">
            <?php wc_get_template('myaccount/navigation.php'); ?>
            <div class="woocommerce-MyAccount-content" style="flex: 1; min-width: 250px;">
                <?php do_action('woocommerce_account_content'); ?>
            </div>
        </div>
    </div>
    <?php
} else {
    // User not logged in - show login/registration form
    ?>
    <div class="woocommerce-login-container" style="max-width: 900px; margin: 80px auto; padding: 20px;">
        <?php wc_get_template('myaccount/form-login.php'); ?>
    </div>
    <?php
}

get_footer();