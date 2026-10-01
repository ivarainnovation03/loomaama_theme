<?php
/* 
 * ============================================
 * SUBSCRIPTION / WAITLIST FUNCTIONALITY
 * ============================================
 */

// Create database table on theme activation
function loomaama_create_subscribers_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'loomaama_subscribers';
    
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        email varchar(100) NOT NULL,
        name varchar(100) DEFAULT '',
        subscribed_at datetime DEFAULT CURRENT_TIMESTAMP,
        status varchar(20) DEFAULT 'active',
        ip_address varchar(45) DEFAULT '',
        source varchar(50) DEFAULT 'coming_soon',
        PRIMARY KEY (id),
        UNIQUE KEY email (email)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'loomaama_create_subscribers_table');

// Alternative: Check and create table on admin_init
function loomaama_check_and_create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'loomaama_subscribers';
    
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        loomaama_create_subscribers_table();
    }
}
add_action('admin_init', 'loomaama_check_and_create_table');

// AJAX handler for subscription
add_action('wp_ajax_loomaama_subscribe', 'loomaama_handle_subscription');
add_action('wp_ajax_nopriv_loomaama_subscribe', 'loomaama_handle_subscription');

function loomaama_handle_subscription() {
    global $wpdb;
    
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'loomaama_subscribe_nonce')) {
        wp_send_json_error(array('message' => 'Security verification failed. Please refresh the page and try again.'));
        return;
    }
    
    // Get and validate email
    $email = isset($_POST['email']) ? sanitize_email(trim($_POST['email'])) : '';
    
    if (empty($email)) {
        wp_send_json_error(array('message' => 'Please enter your email address.'));
        return;
    }
    
    if (!is_email($email)) {
        wp_send_json_error(array('message' => 'Please enter a valid email address.'));
        return;
    }
    
    $table_name = $wpdb->prefix . 'loomaama_subscribers';
    
    // Check if email already exists
    $existing = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $table_name WHERE email = %s",
        $email
    ));
    
    if ($existing) {
        // Update existing record
        $wpdb->update(
            $table_name,
            array(
                'subscribed_at' => current_time('mysql'),
                'status' => 'active',
                'ip_address' => loomaama_get_client_ip(),
                'source' => 'coming_soon'
            ),
            array('email' => $email),
            array('%s', '%s', '%s', '%s'),
            array('%s')
        );
        
        wp_send_json_success(array('message' => 'You\'re already on our list! We\'ll keep you updated.'));
        return;
    }
    
    // Insert new subscriber
    $inserted = $wpdb->insert(
        $table_name,
        array(
            'email' => $email,
            'subscribed_at' => current_time('mysql'),
            'status' => 'active',
            'ip_address' => loomaama_get_client_ip(),
           'source' => isset($_POST['source']) ? sanitize_text_field($_POST['source']) : 'coming_soon'
        ),
        array('%s', '%s', '%s', '%s', '%s')
    );
    
    if ($inserted) {
        // Send confirmation email
        loomaama_send_subscription_confirmation($email);
        
        // Optional: Send notification to admin
        loomaama_notify_admin_new_subscriber($email);
        
        wp_send_json_success(array('message' => 'Thank you for subscribing! You\'ve been added to our waitlist. We\'ll notify you when we launch.'));
    } else {
        wp_send_json_error(array('message' => 'Unable to save your email. Please try again later.'));
    }
}

// Get client IP address
function loomaama_get_client_ip() {
    $ip_address = '';
    
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ip_address = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ip_address = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ip_address = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ip_address = $_SERVER['REMOTE_ADDR'];
    else
        $ip_address = 'UNKNOWN';
    
    return $ip_address;
}

// Send confirmation email to subscriber
function loomaama_send_subscription_confirmation($email) {
    $subject = sprintf(__('Welcome to Loomaama Waitlist', 'loomaama'), get_bloginfo('name'));
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: 'Inter', sans-serif; color: #2A2A2A; line-height: 1.6; }
            .container { max-width: 600px; margin: 0 auto; padding: 40px 20px; background: #FAF9F7; }
            .header { text-align: center; padding-bottom: 30px; border-bottom: 1px solid #DED7CD; }
            .logo { font-family: 'Work Sans', sans-serif; font-size: 24px; letter-spacing: 0.2em; text-transform: uppercase; }
            .content { padding: 40px 0; }
            .button { display: inline-block; padding: 12px 30px; background: #9E8574; color: white; text-decoration: none; text-transform: uppercase; letter-spacing: 0.1em; font-size: 12px; }
            .footer { text-align: center; padding-top: 30px; border-top: 1px solid #DED7CD; font-size: 12px; color: #6F6A63; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <div class='logo'>LOOMAAMA</div>
            </div>
            <div class='content'>
                <h2 style='font-family: \"Work Sans\", sans-serif; text-transform: uppercase; letter-spacing: 0.1em;'>Welcome to Our Circle</h2>
                <p>Thank you for joining the Loomaama waitlist. You'll be among the first to know when we launch our Summer 2026 collection.</p>
                <p>We're crafting something special — pieces that respect the earth, the body, and the quiet dignity of natural fibers.</p>
                <p style='margin: 30px 0; text-align: center;'>
                    <a href='" . home_url('/') . "' class='button'>Explore Loomaama</a>
                </p>
                <p>Stay tuned for updates on our journey.</p>
                <p>With gratitude,<br>The Loomaama Team</p>
            </div>
            <div class='footer'>
                <p>&copy; " . date('Y') . " Loomaama. All rights reserved.</p>
                <p><a href='" . home_url('/') . "' style='color: #9E8574;'>Unsubscribe</a> | <a href='" . home_url('/privacy-policy') . "' style='color: #9E8574;'>Privacy Policy</a></p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>',
    );
    
    wp_mail($email, $subject, $message, $headers);
}

// Notify admin about new subscriber
function loomaama_notify_admin_new_subscriber($email) {
    $admin_email = get_option('admin_email');
    $subject = sprintf(__('New Subscriber: %s', 'loomaama'), $email);
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>New Waitlist Subscriber</h2>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Date:</strong> " . current_time('mysql') . "</p>
            <p><strong>Source:</strong> Coming Soon Page</p>
            <hr>
            <p><a href='" . admin_url('admin.php?page=loomaama-subscribers') . "'>View All Subscribers</a></p>
        </div>
    </body>
    </html>
    ";
    
    $headers = array('Content-Type: text/html; charset=UTF-8');
    
    wp_mail($admin_email, $subject, $message, $headers);
}

// Add admin menu for subscribers list
add_action('admin_menu', 'loomaama_add_subscribers_menu');

function loomaama_add_subscribers_menu() {
    add_menu_page(
        __('Subscribers', 'loomaama'),
        __('Subscribers', 'loomaama'),
        'manage_options',
        'loomaama-subscribers',
        'loomaama_subscribers_page',
        'dashicons-email',
        30
    );
}

// Admin page to view subscribers
function loomaama_subscribers_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'loomaama_subscribers';
    
    // Handle export
    if (isset($_GET['export']) && $_GET['export'] == 'csv') {
        loomaama_export_subscribers_csv();
        return;
    }
    
    // Handle delete
    if (isset($_GET['delete']) && isset($_GET['id']) && wp_verify_nonce($_GET['_wpnonce'], 'delete_subscriber')) {
        $id = intval($_GET['id']);
        $wpdb->delete($table_name, array('id' => $id));
        echo '<div class="notice notice-success"><p>Subscriber deleted successfully.</p></div>';
    }
    
    // Get subscribers
    $subscribers = $wpdb->get_results("SELECT * FROM $table_name ORDER BY subscribed_at DESC");
    $total = count($subscribers);
    ?>
    <div class="wrap">
        <h1><?php _e('Subscribers', 'loomaama'); ?> <span class="count">(<?php echo $total; ?>)</span></h1>
        
        <div class="tablenav top">
            <div class="alignleft actions">
                <a href="?page=loomaama-subscribers&export=csv" class="button button-primary">Export to CSV</a>
            </div>
        </div>
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th width="50">ID</th>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Subscribed Date</th>
                    <th>Status</th>
                    <th>IP Address</th>
                    <th>Source</th>
                    <th width="100">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($subscribers) : ?>
                    <?php foreach ($subscribers as $subscriber) : ?>
                        <tr>
                            <td><?php echo $subscriber->id; ?></td>
                            <td><?php echo esc_html($subscriber->email); ?></td>
                            <td><?php echo esc_html($subscriber->name); ?></td>
                            <td><?php echo $subscriber->subscribed_at; ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $subscriber->status; ?>">
                                    <?php echo ucfirst($subscriber->status); ?>
                                </span>
                            </td>
                            <td><?php echo esc_html($subscriber->ip_address); ?></td>
                            <td><?php echo esc_html($subscriber->source); ?></td>
                            <td>
                                <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=loomaama-subscribers&delete=1&id=' . $subscriber->id), 'delete_subscriber'); ?>" 
                                   class="button button-small button-link-delete" 
                                   onclick="return confirm('Are you sure you want to delete this subscriber?')">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="8" style="text-align: center;">No subscribers yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <style>
            .status-badge {
                display: inline-block;
                padding: 3px 8px;
                border-radius: 3px;
                font-size: 11px;
                text-transform: uppercase;
            }
            .status-active {
                background: #d4edda;
                color: #155724;
            }
            .status-inactive {
                background: #f8d7da;
                color: #721c24;
            }
            .button-link-delete {
                color: #dc3545 !important;
                border-color: #dc3545 !important;
            }
            .button-link-delete:hover {
                background: #dc3545 !important;
                color: white !important;
            }
        </style>
    </div>
    <?php
}

// Export subscribers to CSV
function loomaama_export_subscribers_csv() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'loomaama_subscribers';
    
    $subscribers = $wpdb->get_results("SELECT * FROM $table_name ORDER BY subscribed_at DESC", ARRAY_A);
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="loomaama-subscribers-' . date('Y-m-d') . '.csv"');
    
    $output = fopen('php://output', 'w');
    
    // Add UTF-8 BOM for Excel compatibility
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Headers
    fputcsv($output, array('ID', 'Email', 'Name', 'Subscribed Date', 'Status', 'IP Address', 'Source'));
    
    // Data
    foreach ($subscribers as $subscriber) {
        fputcsv($output, array(
            $subscriber['id'],
            $subscriber['email'],
            $subscriber['name'],
            $subscriber['subscribed_at'],
            $subscriber['status'],
            $subscriber['ip_address'],
            $subscriber['source']
        ));
    }
    
    fclose($output);
    exit;
}

// Shortcode for subscription form
add_shortcode('loomaama_subscription_form', 'loomaama_subscription_form_shortcode');

function loomaama_subscription_form_shortcode($atts) {
    $atts = shortcode_atts(array(
        'title' => 'Join Our Waitlist',
        'subtitle' => 'Be the first to know when we launch',
        'button_text' => 'Subscribe',
        'placeholder' => 'Enter your email address',
        'source' => 'shortcode'
    ), $atts);
    
    ob_start();
    ?>
    <div class="loomaama-subscription-wrapper">
        <?php if ($atts['title']) : ?>
            <h3 class="subscription-title"><?php echo esc_html($atts['title']); ?></h3>
        <?php endif; ?>
        
        <?php if ($atts['subtitle']) : ?>
            <p class="subscription-subtitle"><?php echo esc_html($atts['subtitle']); ?></p>
        <?php endif; ?>
        
        <form class="loomaama-subscription-form" method="POST">
            <div class="subscription-input-group">
                <input type="email" 
                       class="subscription-email" 
                       placeholder="<?php echo esc_attr($atts['placeholder']); ?>" 
                       required>
                <button type="submit" class="subscription-button">
                    <?php echo esc_html($atts['button_text']); ?>
                </button>
            </div>
            <div class="subscription-message"></div>
        </form>
    </div>
    
    <style>
        .loomaama-subscription-wrapper {
            max-width: 500px;
            margin: 0 auto;
            text-align: center;
        }
        .subscription-title {
            font-family: var(--font-headline);
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .subscription-subtitle {
            color: var(--text-secondary);
            margin-bottom: 25px;
        }
        .subscription-input-group {
            display: flex;
            gap: 10px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 10px;
        }
        .subscription-email {
            flex: 1;
            background: transparent;
            border: none;
            padding: 10px 0;
            font-family: var(--font-body);
            outline: none;
        }
        .subscription-button {
            background: transparent;
            border: none;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.2em;
            cursor: pointer;
            transition: color 0.3s;
        }
        .subscription-button:hover {
            color: var(--brand);
        }
        .subscription-message {
            margin-top: 15px;
            font-size: 0.8rem;
        }
    </style>
    
    <script>
    jQuery(document).ready(function($) {
        $('.loomaama-subscription-form').on('submit', function(e) {
            e.preventDefault();
            var $form = $(this);
            var $email = $form.find('.subscription-email');
            var $message = $form.find('.subscription-message');
            var $button = $form.find('.subscription-button');
            
            var email = $email.val().trim();
            
            if (!email) {
                $message.html('<span style="color: #dc3545;">Please enter your email address.</span>');
                return;
            }
            
            $button.prop('disabled', true).text('Processing...');
            
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'loomaama_subscribe',
                    email: email,
                    nonce: '<?php echo wp_create_nonce('loomaama_subscribe_nonce'); ?>',
                    source: '<?php echo esc_js($atts['source']); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        $message.html('<span style="color: var(--brand);">' + response.data.message + '</span>');
                        $email.val('');
                    } else {
                        $message.html('<span style="color: #dc3545;">' + response.data.message + '</span>');
                    }
                },
                error: function() {
                    $message.html('<span style="color: #dc3545;">Network error. Please try again.</span>');
                },
                complete: function() {
                    $button.prop('disabled', false).text('<?php echo esc_js($atts['button_text']); ?>');
                }
            });
        });
    });
    </script>
    <?php
    return ob_get_clean();
}