<?php
/**
 * My Account Navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );
?>

<style>
    /* ============================= */
    /* MY ACCOUNT NAVIGATION STYLES  */
    /* ============================= */
    :root {
        --bg-main: #FAF9F7;
        --bg-card: #F7F3EE;
        --text-primary: #2A2A2A;
        --text-secondary: #6F6A63;
        --brand: #9E8574;
        --brand-hover: #8a7363;
        --border: #DED7CD;
        --font-headline: 'Work Sans', sans-serif;
        --font-body: 'Inter', sans-serif;
        --transition-base: all 0.3s ease;
    }

    .woocommerce-MyAccount-navigation {
        background: var(--bg-product);
        border-radius: 1.5rem;
        border: 1px solid var(--border);
        overflow: hidden;
        width: 280px;
        flex-shrink: 0;
        align-self: start;
        position: sticky;
        top: 30px;
    }

    /* User Profile Section */
    .nav-user-profile {
        padding: 1.75rem 1.5rem;
        background: linear-gradient(135deg, var(--bg-card) 0%, var(--bg-product) 100%);
        border-bottom: 1px solid var(--border);
        text-align: center;
    }

    .user-avatar {
        width: 70px;
        height: 70px;
        margin: 0 auto 1rem;
        background: var(--brand);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .user-avatar i {
        font-size: 2.5rem;
        color: white;
    }

    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-name {
        font-family: var(--font-headline);
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .user-email {
        font-size: 0.75rem;
        color: var(--text-secondary);
        word-break: break-all;
    }

    /* Navigation Menu */
    .woocommerce-MyAccount-navigation ul {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .woocommerce-MyAccount-navigation li {
        margin: 0;
        padding: 0;
        border-bottom: 1px solid var(--border);
        transition: var(--transition-base);
    }

    .woocommerce-MyAccount-navigation li:last-child {
        border-bottom: none;
    }

    .woocommerce-MyAccount-navigation li a {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.5rem;
        font-family: var(--font-body);
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--text-secondary);
        text-decoration: none;
        transition: var(--transition-base);
        position: relative;
    }

    .woocommerce-MyAccount-navigation li a i {
        width: 1.25rem;
        font-size: 1rem;
        color: var(--brand);
        transition: var(--transition-base);
    }

    .woocommerce-MyAccount-navigation li a:hover {
        background: var(--bg-card);
        color: var(--brand);
        padding-left: 2rem;
    }

    .woocommerce-MyAccount-navigation li a:hover i {
        transform: translateX(3px);
    }

    .woocommerce-MyAccount-navigation li.is-active a {
        background: var(--bg-card);
        color: var(--brand);
        border-left: 3px solid var(--brand);
        font-weight: 600;
    }

    .woocommerce-MyAccount-navigation li.is-active a i {
        color: var(--brand);
    }

    /* Badge for Orders Count */
    .nav-badge {
        background: var(--brand);
        color: white;
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.2rem 0.5rem;
        border-radius: 1rem;
        margin-left: auto;
    }

    /* Logout Button Special Styling */
    .woocommerce-MyAccount-navigation li:last-child a {
        color: #c94f4f;
    }

    .woocommerce-MyAccount-navigation li:last-child a i {
        color: #c94f4f;
    }

    .woocommerce-MyAccount-navigation li:last-child a:hover {
        background: #fff5f5;
        color: #c94f4f;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .woocommerce-MyAccount-navigation {
            width: 100%;
            position: static;
            margin-bottom: 1.5rem;
        }
        
        .woocommerce-MyAccount-navigation ul {
            display: flex;
            flex-wrap: nowrap;
            gap: 0;
			width: 115px;
			overflow-x: auto;
        }
        
        .woocommerce-MyAccount-navigation li {
            flex: 1 1 auto;
            border-bottom: none;
            border-right: 1px solid var(--border);
        }
        
        .woocommerce-MyAccount-navigation li:last-child {
            border-right: none;
        }
        
        .woocommerce-MyAccount-navigation li a {
            justify-content: center;
            padding: 0.85rem 0.75rem;
            font-size: 0.75rem;
            text-align: center;
            flex-direction: column;
            gap: 0.35rem;
        }
        
        .woocommerce-MyAccount-navigation li a i {
            width: auto;
            font-size: 1.1rem;
        }
        
        .woocommerce-MyAccount-navigation li a:hover {
            padding-left: 0.75rem;
        }
        
        .woocommerce-MyAccount-navigation li.is-active a {
            border-left: none;
            border-top: 3px solid var(--brand);
        }
        
        .nav-user-profile {
            display: none;
        }
        
        .nav-badge {
            margin-left: 0;
            position: absolute;
            top: -5px;
            right: 10px;
        }
    }

    @media (max-width: 480px) {
        .woocommerce-MyAccount-navigation li a {
            font-size: 0.65rem;
            padding: 0.7rem 0.5rem;
        }
        
        .woocommerce-MyAccount-navigation li a i {
            font-size: 0.9rem;
        }
    }
</style>

<nav class="woocommerce-MyAccount-navigation" aria-label="<?php esc_html_e( 'Account pages', 'woocommerce' ); ?>">
    
    <!-- User Profile Section -->
    <?php if ( is_user_logged_in() ) : 
        $current_user = wp_get_current_user();
        $user_id = get_current_user_id();
        $avatar_url = get_avatar_url($user_id, array('size' => 70));
        $order_count = wc_get_customer_order_count($user_id);
    ?>
    <div class="nav-user-profile">
        <div class="user-avatar">
            <?php if ($avatar_url) : ?>
                <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($current_user->display_name); ?>">
            <?php else : ?>
                <i class="fas fa-user-circle"></i>
            <?php endif; ?>
        </div>
        <div class="user-name"><?php echo esc_html($current_user->display_name); ?></div>
        <div class="user-email"><?php echo esc_html($current_user->user_email); ?></div>
    </div>
    <?php endif; ?>
    
    <ul>
        <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : 
            $icon = loomaama_get_nav_icon($endpoint);
            $badge = '';
            
            // Add badge for orders count
            if ($endpoint === 'orders' && isset($order_count) && $order_count > 0) {
                $badge = '<span class="nav-badge">' . $order_count . '</span>';
            }
        ?>
            <li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
                <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" <?php echo wc_is_current_account_menu_item( $endpoint ) ? 'aria-current="page"' : ''; ?>>
                    <i class="fas <?php echo esc_attr($icon); ?>"></i>
                    <span><?php echo esc_html( $label ); ?></span>
                    <?php echo $badge; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
