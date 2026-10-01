<?php
/**
 * My Account Page Customizations
 *
 * @package Loomaama
 */

/**
 * Customize My Account Navigation
 */
add_filter('woocommerce_account_menu_items', 'loomaama_custom_account_menu', 40);
function loomaama_custom_account_menu($items) {
    // Reorder or rename menu items
    $custom_items = array();
    
    foreach ($items as $key => $label) {
        // Rename existing items
        switch ($key) {
            case 'dashboard':
                $label = __('Dashboard', 'loomaama');
                break;
            case 'orders':
                $label = __('My Orders', 'loomaama');
                break;
            case 'downloads':
                $label = __('Downloads', 'loomaama');
                break;
            case 'edit-address':
                $label = __('Addresses', 'loomaama');
                break;
            case 'edit-account':
                $label = __('Account Details', 'loomaama');
                break;
            case 'customer-logout':
                $label = __('Logout', 'loomaama');
                break;
        }
        
        $custom_items[$key] = $label;
        
        // Add custom menu items after specific positions
        if ($key === 'orders') {
            $custom_items['wishlist'] = __('Wishlist', 'loomaama');
        }
        if ($key === 'edit-account') {
            $custom_items['newsletter'] = __('Newsletter', 'loomaama');
        }
    }
    
    return $custom_items;
}

/**
 * Register Custom Endpoints
 */
add_action('init', 'loomaama_register_account_endpoints');
function loomaama_register_account_endpoints() {
    add_rewrite_endpoint('wishlist', EP_ROOT | EP_PAGES);
    add_rewrite_endpoint('newsletter', EP_ROOT | EP_PAGES);
}

/**
 * Custom Endpoint Content
 */
add_action('woocommerce_account_wishlist_endpoint', 'loomaama_wishlist_page_content');
function loomaama_wishlist_page_content() {
    if (function_exists('YITH_WCWL')) {
        echo '<div class="woocommerce-MyAccount-wishlist">';
        echo '<h2 class="headline mb-4">' . __('My Wishlist', 'loomaama') . '</h2>';
        echo do_shortcode('[yith_wcwl_wishlist]');
        echo '</div>';
    } else {
        ?>
        <div class="woocommerce-MyAccount-wishlist text-center py-5">
            <i class="fas fa-heart fa-3x mb-3 text-secondary"></i>
            <h3 class="headline"><?php _e('Your wishlist is empty', 'loomaama'); ?></h3>
            <p class="text-secondary mb-4"><?php _e('Start adding your favorite products to your wishlist!', 'loomaama'); ?></p>
            <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="btn btn-dark px-4 py-3">
                <?php _e('Browse Products', 'loomaama'); ?>
            </a>
        </div>
        <?php
    }
}

add_action('woocommerce_account_newsletter_endpoint', 'loomaama_newsletter_page_content');
function loomaama_newsletter_page_content() {
    ?>
    <div class="woocommerce-MyAccount-newsletter">
        <h2 class="headline mb-4"><?php _e('Newsletter Preferences', 'loomaama'); ?></h2>
        
        <div class="card p-4 mb-4">
            <form id="newsletter-preference-form" class="newsletter-form">
                <?php wp_nonce_field('update_newsletter_preference', 'newsletter_nonce'); ?>
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="receive_newsletter" name="receive_newsletter" 
                           <?php checked(get_user_meta(get_current_user_id(), 'receive_newsletter', true), 'yes'); ?>>
                    <label class="form-check-label" for="receive_newsletter">
                        <?php _e('Receive exclusive offers, new collections, and sustainability updates', 'loomaama'); ?>
                    </label>
                </div>
                
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="receive_events" name="receive_events"
                           <?php checked(get_user_meta(get_current_user_id(), 'receive_events', true), 'yes'); ?>>
                    <label class="form-check-label" for="receive_events">
                        <?php _e('Invitations to local events and workshops', 'loomaama'); ?>
                    </label>
                </div>
                
                <button type="submit" class="btn btn-dark px-4 py-3 mt-3">
                    <?php _e('Save Preferences', 'loomaama'); ?>
                </button>
                <div id="newsletter-message" class="mt-3"></div>
            </form>
        </div>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        $('#newsletter-preference-form').on('submit', function(e) {
            e.preventDefault();
            
            $.ajax({
                url: loomaama_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'update_newsletter_preference',
                    nonce: $('#newsletter_nonce').val(),
                    receive_newsletter: $('#receive_newsletter').is(':checked') ? 'yes' : 'no',
                    receive_events: $('#receive_events').is(':checked') ? 'yes' : 'no'
                },
                success: function(response) {
                    if (response.success) {
                        $('#newsletter-message').html('<div class="alert alert-success">' + response.data.message + '</div>');
                        setTimeout(function() {
                            $('#newsletter-message').fadeOut();
                        }, 3000);
                    }
                }
            });
        });
    });
    </script>
    <?php
}

/**
 * AJAX Handler for Newsletter Preferences
 */
add_action('wp_ajax_update_newsletter_preference', 'loomaama_update_newsletter_preference');
function loomaama_update_newsletter_preference() {
    check_ajax_referer('update_newsletter_preference', 'nonce');
    
    $user_id = get_current_user_id();
    update_user_meta($user_id, 'receive_newsletter', sanitize_text_field($_POST['receive_newsletter']));
    update_user_meta($user_id, 'receive_events', sanitize_text_field($_POST['receive_events']));
    
    wp_send_json_success(['message' => __('Preferences updated successfully!', 'loomaama')]);
}

/**
 * Customize Dashboard Content
 */
add_action('woocommerce_account_dashboard', 'loomaama_custom_dashboard_content', 5);
function loomaama_custom_dashboard_content() {
    $current_user = wp_get_current_user();
    $order_count = wc_get_customer_order_count(get_current_user_id());
    $customer_orders = wc_get_orders(array(
        'customer' => get_current_user_id(),
        'limit' => 5,
        'orderby' => 'date',
        'order' => 'DESC',
    ));
    ?>
    <div class="dashboard-welcome mb-5">
        <h2 class="headline">
            <?php printf(__('Welcome back, %s!', 'loomaama'), esc_html($current_user->display_name)); ?>
        </h2>
        <p class="text-secondary">
            <?php _e('From your account dashboard you can view your recent orders, manage your addresses, and edit your account details.', 'loomaama'); ?>
        </p>
    </div>
    
    <div class="dashboard-stats row mb-5">
        <div class="col-md-3 col-6 mb-4">
            <div class="stat-card text-center p-4 border">
                <i class="fas fa-shopping-bag fa-2x mb-3 text-brand"></i>
                <h3 class="headline mb-0"><?php echo esc_html($order_count); ?></h3>
                <p class="text-secondary small mb-0"><?php _e('Total Orders', 'loomaama'); ?></p>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-4">
            <div class="stat-card text-center p-4 border">
                <i class="fas fa-map-marker-alt fa-2x mb-3 text-brand"></i>
                <h3 class="headline mb-0">
                    <?php 
                    $address = wc_get_account_formatted_address('billing');
                    echo empty($address) ? '0' : '1';
                    ?>
                </h3>
                <p class="text-secondary small mb-0"><?php _e('Saved Addresses', 'loomaama'); ?></p>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-4">
            <div class="stat-card text-center p-4 border">
                <i class="fas fa-heart fa-2x mb-3 text-brand"></i>
                <h3 class="headline mb-0">0</h3>
                <p class="text-secondary small mb-0"><?php _e('Wishlist Items', 'loomaama'); ?></p>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-4">
            <div class="stat-card text-center p-4 border">
                <i class="fas fa-star fa-2x mb-3 text-brand"></i>
                <h3 class="headline mb-0"><?php echo get_comments(array('user_id' => get_current_user_id(), 'count' => true)); ?></h3>
                <p class="text-secondary small mb-0"><?php _e('Reviews Written', 'loomaama'); ?></p>
            </div>
        </div>
    </div>
    
    <?php if (!empty($customer_orders)) : ?>
    <div class="recent-orders">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="headline mb-0"><?php _e('Recent Orders', 'loomaama'); ?></h3>
            <a href="<?php echo wc_get_account_endpoint_url('orders'); ?>" class="text-brand">
                <?php _e('View All →', 'loomaama'); ?>
            </a>
        </div>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th><?php _e('Order', 'loomaama'); ?></th>
                        <th><?php _e('Date', 'loomaama'); ?></th>
                        <th><?php _e('Status', 'loomaama'); ?></th>
                        <th><?php _e('Total', 'loomaama'); ?></th>
                        <th><?php _e('Actions', 'loomaama'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customer_orders as $order) : ?>
                    <tr>
                        <td>#<?php echo $order->get_order_number(); ?></td>
                        <td><?php echo wc_format_datetime($order->get_date_created()); ?></td>
                        <td><span class="order-status status-<?php echo esc_attr($order->get_status()); ?>"><?php echo wc_get_order_status_name($order->get_status()); ?></span></td>
                        <td><?php echo $order->get_formatted_order_total(); ?></td>
                        <td>
                            <a href="<?php echo $order->get_view_order_url(); ?>" class="btn btn-sm btn-outline-dark">
                                <?php _e('View', 'loomaama'); ?>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Quick Actions -->
    <div class="quick-actions mt-5">
        <h3 class="headline mb-4"><?php _e('Quick Actions', 'loomaama'); ?></h3>
        <div class="row">
            <div class="col-md-3 col-6 mb-3">
                <a href="<?php echo wc_get_account_endpoint_url('edit-address'); ?>" class="btn btn-outline-dark w-100 py-3">
                    <i class="fas fa-plus-circle me-2"></i> <?php _e('Add Address', 'loomaama'); ?>
                </a>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <a href="<?php echo wc_get_account_endpoint_url('edit-account'); ?>" class="btn btn-outline-dark w-100 py-3">
                    <i class="fas fa-user-edit me-2"></i> <?php _e('Edit Profile', 'loomaama'); ?>
                </a>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <a href="<?php echo wc_get_page_permalink('shop'); ?>" class="btn btn-outline-dark w-100 py-3">
                    <i class="fas fa-store me-2"></i> <?php _e('Continue Shopping', 'loomaama'); ?>
                </a>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <a href="<?php echo wc_get_account_endpoint_url('customer-logout'); ?>" class="btn btn-outline-dark w-100 py-3">
                    <i class="fas fa-sign-out-alt me-2"></i> <?php _e('Logout', 'loomaama'); ?>
                </a>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Customize Order Status Badges
 */
add_filter('woocommerce_get_order_statuses', 'loomaama_custom_order_statuses');
function loomaama_custom_order_statuses($order_statuses) {
    $custom_statuses = array();
    foreach ($order_statuses as $key => $status) {
        $custom_statuses[$key] = $status;
    }
    return $custom_statuses;
}

/**
 * Add Custom CSS for My Account
 */
add_action('wp_head', 'loomaama_myaccount_custom_css');
function loomaama_myaccount_custom_css() {
    if (!is_account_page()) return;
    ?>
    <style>
        .stat-card {
            transition: all 0.3s ease;
            background: var(--bg-card, #fff);
            border-radius: 0;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        .order-status {
            display: inline-block;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .order-status.status-completed { background: #d4edda; color: #155724; }
        .order-status.status-processing { background: #fff3cd; color: #856404; }
        .order-status.status-pending { background: #e2e3e5; color: #383d41; }
        .order-status.status-on-hold { background: #f8d7da; color: #721c24; }
        .order-status.status-cancelled { background: #f5c6cb; color: #721c24; }
        .order-status.status-refunded { background: #d1ecf1; color: #0c5460; }
        
        @media (max-width: 768px) {
            .stat-card {
                margin-bottom: 15px;
            }
            .quick-actions .btn {
                font-size: 12px;
                padding: 12px;
            }
        }
    </style>
    <?php
}

/**
 * Add Custom JavaScript for My Account
 */
add_action('wp_footer', 'loomaama_myaccount_custom_js');
function loomaama_myaccount_custom_js() {
    if (!is_account_page()) return;
    ?>
    <script>
    jQuery(document).ready(function($) {
        // Add fade-in effect to dashboard sections
        $('.woocommerce-MyAccount-content > div').each(function(index) {
            $(this).css('animation-delay', (index * 0.1) + 's');
        });
        
        // Confirmation before logout
        $('.woocommerce-MyAccount-navigation a[href*="customer-logout"]').on('click', function(e) {
            if (!confirm('<?php _e('Are you sure you want to logout?', 'loomaama'); ?>')) {
                e.preventDefault();
            }
        });
        
        // Highlight current navigation item
        var currentUrl = window.location.href;
        $('.woocommerce-MyAccount-navigation a').each(function() {
            if (currentUrl.indexOf($(this).attr('href')) !== -1) {
                $(this).closest('li').addClass('is-active');
            }
        });
    });
    </script>
    <?php
}

/**
 * Customize Address Display
 */
add_action('woocommerce_before_edit_address_form', 'loomaama_before_edit_address');
function loomaama_before_edit_address() {
    echo '<div class="address-form-wrapper">';
    echo '<h2 class="headline mb-4">' . __('Address Information', 'loomaama') . '</h2>';
}

add_action('woocommerce_after_edit_address_form', 'loomaama_after_edit_address');
function loomaama_after_edit_address() {
    echo '</div>';
}

/**
 * Customize Edit Account Form
 */
add_action('woocommerce_before_edit_account_form', 'loomaama_before_edit_account');
function loomaama_before_edit_account() {
    echo '<div class="account-form-wrapper">';
    echo '<h2 class="headline mb-4">' . __('Account Information', 'loomaama') . '</h2>';
}

add_action('woocommerce_after_edit_account_form', 'loomaama_after_edit_account');
function loomaama_after_edit_account() {
    echo '</div>';
}

/**
 * Add Extra Profile Fields
 */
add_action('woocommerce_edit_account_form', 'loomaama_add_extra_profile_fields');
function loomaama_add_extra_profile_fields() {
    $user_id = get_current_user_id();
    $phone = get_user_meta($user_id, 'billing_phone', true);
    $newsletter = get_user_meta($user_id, 'receive_newsletter', true);
    ?>
    <div class="extra-fields mt-4">
        <h3 class="headline mb-3"><?php _e('Additional Information', 'loomaama'); ?></h3>
        
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="phone"><?php _e('Phone Number', 'loomaama'); ?></label>
            <input type="tel" class="woocommerce-Input woocommerce-Input--text input-text" name="phone" id="phone" value="<?php echo esc_attr($phone); ?>">
        </p>
        
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="receive_newsletter" type="checkbox" id="receive_newsletter" <?php checked($newsletter, 'yes'); ?>>
                <span><?php _e('Subscribe to our newsletter', 'loomaama'); ?></span>
            </label>
        </p>
    </div>
    <?php
}

/**
 * Save Extra Profile Fields
 */
add_action('woocommerce_save_account_details', 'loomaama_save_extra_profile_fields', 12);
function loomaama_save_extra_profile_fields($user_id) {
    if (isset($_POST['phone'])) {
        update_user_meta($user_id, 'billing_phone', sanitize_text_field($_POST['phone']));
    }
    
    $newsletter = isset($_POST['receive_newsletter']) ? 'yes' : 'no';
    update_user_meta($user_id, 'receive_newsletter', $newsletter);
}

/**
 * Customize Orders Page
 */
add_action('woocommerce_before_account_orders', 'loomaama_before_account_orders');
function loomaama_before_account_orders() {
    echo '<h2 class="headline mb-4">' . __('Order History', 'loomaama') . '</h2>';
}

/**
 * Add Support for Custom Endpoints
 * Remember to flush permalinks after adding new endpoints!
 */
function loomaama_flush_rewrite_rules() {
    loomaama_register_account_endpoints();
    flush_rewrite_rules();
}


/**
 * Helper function to get icon for each navigation item
 */
if ( ! function_exists( 'loomaama_get_nav_icon' ) ) {
    function loomaama_get_nav_icon($endpoint) {
        $icons = array(
            'dashboard'       => 'fa-tachometer-alt',
            'orders'          => 'fa-shopping-bag',
            'edit-address'    => 'fa-map-marker-alt',
            'payment-methods' => 'fa-credit-card',
            'edit-account'    => 'fa-user-edit',
            'customer-logout' => 'fa-sign-out-alt',
        );
        
        return isset($icons[$endpoint]) ? $icons[$endpoint] : 'fa-circle';
    }
}

do_action( 'woocommerce_after_account_navigation' );



add_action( 'wp_enqueue_scripts', 'custom_enqueue_myaccount_css' );

function custom_enqueue_myaccount_css() {
    // Check if user is logged in AND is on WooCommerce My Account page
    if ( is_user_logged_in() && is_account_page() ) {
        
        // Enqueue your custom CSS file
        wp_enqueue_style(
            'woocommerce-myaccount-dashboard', // Handle name
            get_template_directory_uri() . '/assets/css/woocommerce-myaccount.css', // Path to CSS file
            array(), // Dependencies
            '1.0.0', // Version
            'all' // Media
        );
        
       
    }
}

/**
 * Directly handle lost-password URL without relying on is_account_page()
 */
add_action('template_redirect', 'loomaama_direct_lost_password_handler');
function loomaama_direct_lost_password_handler() {
    $request_uri = $_SERVER['REQUEST_URI'];
    
    // Check if we're on /my-account/lost-password/
    if (preg_match('/\/my-account\/lost-password\/?$/i', $request_uri)) {
        // Prevent any other redirects
        remove_all_actions('template_redirect');
        
        // Load the lost password form directly
        status_header(200);
        ?>
        <!DOCTYPE html>
        <html <?php language_attributes(); ?>>
        <head>
            <meta charset="<?php bloginfo('charset'); ?>">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <?php wp_head(); ?>
            <style>
                body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 0; }
                .container { max-width: 500px; margin: 80px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                h2 { text-align: center; margin-bottom: 20px; }
                form p { margin-bottom: 15px; }
                label { display: block; margin-bottom: 5px; font-weight: bold; }
                input[type="text"], input[type="email"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
                button { background: #000; color: #fff; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; width: 100%; }
                .woocommerce-message { background: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 4px; }
                .woocommerce-error { background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 4px; }
            </style>
        </head>
        <body>
            <div class="container">
                <?php
                // Process form submission
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_login'])) {
                    $user_login = sanitize_text_field($_POST['user_login']);
                    $user = get_user_by('email', $user_login);
                    if (!$user) {
                        $user = get_user_by('login', $user_login);
                    }
                    
                    if (!$user) {
                        echo '<div class="woocommerce-error">' . __('No user found with that email or username.', 'woocommerce') . '</div>';
                    } else {
                        $reset_key = get_password_reset_key($user);
                        if (!is_wp_error($reset_key)) {
                            $reset_url = add_query_arg([
                                'key' => $reset_key,
                                'login' => rawurlencode($user->user_login),
                            ], wc_get_page_permalink('myaccount'));
                            
                            // Send email using WooCommerce email class
                            if (class_exists('WC_Email_Customer_Reset_Password')) {
                                $email = new WC_Email_Customer_Reset_Password();
                                $email->trigger($user->ID, $reset_key);
                                echo '<div class="woocommerce-message">' . __('Password reset email sent. Please check your inbox.', 'woocommerce') . '</div>';
                            } else {
                                // Fallback to wp_mail
                                $subject = __('Password Reset', 'loomaama');
                                $message = "Click here to reset your password: $reset_url";
                                wp_mail($user->user_email, $subject, $message);
                                echo '<div class="woocommerce-message">' . __('Reset link sent to your email.', 'loomaama') . '</div>';
                            }
                        } else {
                            echo '<div class="woocommerce-error">' . $reset_key->get_error_message() . '</div>';
                        }
                    }
                }
                ?>
                <h2><?php _e('Lost Password', 'loomaama'); ?></h2>
                <form method="post">
                    <p>
                        <label for="user_login"><?php _e('Email or username', 'loomaama'); ?></label>
                        <input type="text" name="user_login" id="user_login" required>
                    </p>
                    <p>
                        <button type="submit"><?php _e('Reset Password', 'loomaama'); ?></button>
                    </p>
                </form>
            </div>
            <?php wp_footer(); ?>
        </body>
        </html>
        <?php
        exit;
    }
    
    // Also handle reset-password link (with key and login)
    if (isset($_GET['key']) && isset($_GET['login'])) {
        $key = sanitize_text_field($_GET['key']);
        $login = sanitize_text_field($_GET['login']);
        $user = get_user_by('login', $login);
        
        if (!$user || !check_password_reset_key($key, $login)) {
            wp_die(__('Invalid reset link. Please request a new one.', 'loomaama'));
        }
        
        // Show reset password form
        remove_all_actions('template_redirect');
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width">
            <?php wp_head(); ?>
            <style>body { background: #f5f5f5; } .container { max-width: 500px; margin: 80px auto; background: #fff; padding: 20px; border-radius: 8px; }</style>
        </head>
        <body>
            <div class="container">
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password_1'])) {
                    $password = $_POST['password_1'];
                    reset_password($user, $password);
                    echo '<div class="woocommerce-message">' . __('Password reset successfully. You can now login.', 'loomaama') . '</div>';
                    echo '<a href="' . wc_get_page_permalink('myaccount') . '">' . __('Login', 'loomaama') . '</a>';
                } else {
                    ?>
                    <form method="post">
                        <p>
                            <label for="password_1"><?php _e('New password', 'loomaama'); ?></label>
                            <input type="password" name="password_1" id="password_1" required>
                        </p>
                        <p>
                            <label for="password_2"><?php _e('Confirm password', 'loomaama'); ?></label>
                            <input type="password" name="password_2" id="password_2" required>
                        </p>
                        <p><button type="submit"><?php _e('Save', 'loomaama'); ?></button></p>
                    </form>
                    <?php
                }
                ?>
            </div>
            <?php wp_footer(); ?>
        </body>
        </html>
        <?php
        exit;
    }
}