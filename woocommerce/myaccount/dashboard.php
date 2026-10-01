<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
);
?>

<style>
    /* ============================= */
    /* DASHBOARD STYLES              */
    /* ============================= */
    :root {
        --bg-main: #FAF9F7;
        --bg-editorial: #F4EEE4;
        --bg-product: #FFFFFF;
        --bg-card: #F7F3EE;
        --bg-dark: #2A2A2A;
        --bg-footer: #F4EEE4;
        --text-primary: #2A2A2A;
        --text-secondary: #6F6A63;
        --brand: #9E8574;
        --brand-hover: #8a7363;
        --border: #DED7CD;
        --font-headline: 'Work Sans', sans-serif;
        --font-body: 'Inter', sans-serif;
        --transition-base: all 0.3s ease;
    }

    .woocommerce-MyAccount-content {
        background: var(--bg-product);
        border-radius: 1.5rem;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
    }

    /* Welcome Card */
    .dashboard-welcome-card {
        background: linear-gradient(135deg, var(--bg-card) 0%, var(--bg-product) 100%);
        border-radius: 1.5rem;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border);
        position: relative;
        overflow: hidden;
    }

    .dashboard-welcome-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(158,133,116,0.05) 0%, transparent 70%);
        pointer-events: none;
    }

    .welcome-greeting {
        font-family: var(--font-headline);
        font-size: 1.8rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .welcome-greeting i {
        color: var(--brand);
        font-size: 2rem;
    }

    .welcome-name {
        color: var(--brand);
        position: relative;
        display: inline-block;
    }

    .welcome-name::after {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--brand);
        opacity: 0.3;
    }

    .logout-link {
        font-size: 0.85rem;
        color: var(--text-secondary);
        text-decoration: none;
        margin-left: auto;
        transition: var(--transition-base);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(158,133,116,0.1);
        padding: 0.5rem 1rem;
        border-radius: 2rem;
    }

    .logout-link:hover {
        background: var(--brand);
        color: white;
    }

    .dashboard-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .stat-card {
        background: var(--bg-product);
        border-radius: 1rem;
        padding: 1.25rem;
        text-align: center;
        border: 1px solid var(--border);
        transition: var(--transition-base);
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
        border-color: var(--brand);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        background: var(--bg-card);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .stat-icon i {
        font-size: 1.5rem;
        color: var(--brand);
    }

    .stat-number {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-primary);
        font-family: var(--font-headline);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--text-secondary);
    }

    /* Quick Actions */
    .quick-actions-section {
        margin-bottom: 2rem;
    }

    .section-title {
        font-family: var(--font-headline);
        font-size: 1.2rem;
        font-weight: 600;
        letter-spacing: 0.05em;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--border);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-title i {
        color: var(--brand);
        font-size: 1.2rem;
    }

    .quick-actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 1rem;
    }

    .quick-action-btn {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 1rem;
        padding: 1rem;
        text-align: center;
        text-decoration: none;
        color: var(--text-primary);
        transition: var(--transition-base);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }

    .quick-action-btn i {
        font-size: 1.5rem;
        color: var(--brand);
        transition: var(--transition-base);
    }

    .quick-action-btn span {
        font-size: 0.8rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .quick-action-btn:hover {
        background: var(--brand);
        border-color: var(--brand);
        transform: translateY(-2px);
    }

    .quick-action-btn:hover i,
    .quick-action-btn:hover span {
        color: white;
    }

    /* Info Cards */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .info-card {
        background: var(--bg-product);
        border: 1px solid var(--border);
        border-radius: 1rem;
        overflow: hidden;
    }

    .info-card-header {
        background: var(--bg-card);
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .info-card-header h3 {
        font-family: var(--font-headline);
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-card-header h3 i {
        color: var(--brand);
    }

    .info-card-header .edit-link {
        font-size: 0.75rem;
        color: var(--brand);
        text-decoration: none;
        transition: var(--transition-base);
    }

    .info-card-header .edit-link:hover {
        text-decoration: underline;
    }

    .info-card-body {
        padding: 1.25rem;
    }

    .address-content {
        font-size: 0.85rem;
        line-height: 1.6;
        color: var(--text-secondary);
    }

    .address-content p {
        margin: 0;
    }

    .address-empty {
        color: var(--text-secondary);
        font-style: italic;
        font-size: 0.85rem;
    }

    /* Recent Orders Table */
    .recent-orders-section {
        margin-top: 1rem;
    }

    .orders-table-wrapper {
        overflow-x: auto;
        margin-top: 1rem;
    }

    .dashboard-orders-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }

    .dashboard-orders-table th {
        text-align: left;
        padding: 1rem;
        background: var(--bg-card);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 0.7rem;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--border);
    }

    .dashboard-orders-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border);
        color: var(--text-primary);
    }

    .dashboard-orders-table tr:hover {
        background: rgba(158, 133, 116, 0.02);
    }

    .order-status {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 2rem;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .order-status-completed {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .order-status-processing {
        background: #e3f2fd;
        color: #1565c0;
    }

    .order-status-pending {
        background: #fff3e0;
        color: #ef6c00;
    }

    .order-status-on-hold {
        background: #fbe9e7;
        color: #bf360c;
    }

    .order-status-cancelled {
        background: #fafafa;
        color: #757575;
    }

    .order-status-refunded {
        background: #f3e5f5;
        color: #6a1b9a;
    }

    .order-status-failed {
        background: #ffebee;
        color: #c62828;
    }

    .order-view-link {
        color: var(--brand);
        text-decoration: none;
        font-weight: 500;
        transition: var(--transition-base);
    }

    .order-view-link:hover {
        text-decoration: underline;
    }

    .no-orders-message {
        text-align: center;
        padding: 3rem;
        color: var(--text-secondary);
    }

    .no-orders-message i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .shop-now-btn {
        display: inline-block;
        margin-top: 1rem;
        padding: 0.75rem 1.5rem;
        background: var(--brand);
        color: white;
        text-decoration: none;
        border-radius: 2rem;
        font-size: 0.8rem;
        font-weight: 500;
        transition: var(--transition-base);
    }

    .shop-now-btn:hover {
        background: var(--brand-hover);
        transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .woocommerce-MyAccount-content {
            padding: 1.25rem;
        }

        .welcome-greeting {
            font-size: 1.3rem;
        }

        .dashboard-stats {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .dashboard-orders-table th,
        .dashboard-orders-table td {
            padding: 0.75rem;
        }
    }

    /* Font Awesome fallback */
    .fa, .fas, .far {
        font-family: 'Font Awesome 6 Free';
        font-weight: 400;
    }
</style>

<?php
// Get customer stats
$customer_orders_count = wc_get_customer_order_count(get_current_user_id());
$customer = new WC_Customer(get_current_user_id());

// Get recent orders
$recent_orders = wc_get_orders(array(
    'customer_id' => get_current_user_id(),
    'limit' => 5,
    'orderby' => 'date',
    'order' => 'DESC',
));
?>

<div class="dashboard-wrapper">
    <!-- Welcome Card -->
    <div class="dashboard-welcome-card">
        <div class="welcome-greeting">
            <i class="fas fa-hand-wave"></i>
            <span>
                <?php
                $greeting = '';
                $hour = current_time('H');
                if ($hour < 12) {
                    $greeting = __('Good morning', 'woocommerce');
                } elseif ($hour < 18) {
                    $greeting = __('Good afternoon', 'woocommerce');
                } else {
                    $greeting = __('Good evening', 'woocommerce');
                }
                printf(esc_html__('%s,', 'woocommerce'), $greeting);
                ?>
            </span>
            <span class="welcome-name"><?php echo esc_html($current_user->display_name); ?></span>
            <a href="<?php echo esc_url(wc_logout_url()); ?>" class="logout-link">
                <i class="fas fa-sign-out-alt"></i>
                <?php esc_html_e('Log out', 'woocommerce'); ?>
            </a>
        </div>
        <p class="welcome-description" style="color: var(--text-secondary); margin-top: 0.5rem;">
            <?php esc_html_e('Welcome to your account dashboard. Here you can manage your orders, addresses, and account details.', 'woocommerce'); ?>
        </p>

        <!-- Stats Cards -->
        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="stat-number"><?php echo esc_html($customer_orders_count); ?></div>
                <div class="stat-label"><?php esc_html_e('Total Orders', 'woocommerce'); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="stat-number">
                    <?php
                    $billing_address = $customer->get_billing_address_1();
                    echo !empty($billing_address) ? '✓' : '—';
                    ?>
                </div>
                <div class="stat-label"><?php esc_html_e('Address Saved', 'woocommerce'); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-number">
                    <?php
                    $member_since = strtotime($current_user->user_registered);
                    echo date('M Y', $member_since);
                    ?>
                </div>
                <div class="stat-label"><?php esc_html_e('Member Since', 'woocommerce'); ?></div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions-section">
        <div class="section-title">
            <i class="fas fa-bolt"></i>
            <span><?php esc_html_e('Quick Actions', 'woocommerce'); ?></span>
        </div>
        <div class="quick-actions-grid">
            <a href="<?php echo esc_url(wc_get_endpoint_url('orders')); ?>" class="quick-action-btn">
                <i class="fas fa-shopping-cart"></i>
                <span><?php esc_html_e('My Orders', 'woocommerce'); ?></span>
            </a>
            <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address', 'billing')); ?>" class="quick-action-btn">
                <i class="fas fa-map-pin"></i>
                <span><?php esc_html_e('Manage Addresses', 'woocommerce'); ?></span>
            </a>
            <a href="<?php echo esc_url(wc_get_endpoint_url('edit-account')); ?>" class="quick-action-btn">
                <i class="fas fa-user-edit"></i>
                <span><?php esc_html_e('Account Details', 'woocommerce'); ?></span>
            </a>
            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="quick-action-btn">
                <i class="fas fa-store"></i>
                <span><?php esc_html_e('Continue Shopping', 'woocommerce'); ?></span>
            </a>
        </div>
    </div>

    <!-- Address Information -->
    <div class="info-grid">
        <!-- Billing Address -->
        <div class="info-card">
            <div class="info-card-header">
                <h3><i class="fas fa-credit-card"></i> <?php esc_html_e('Billing Address', 'woocommerce'); ?></h3>
                <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address', 'billing')); ?>" class="edit-link">
                    <i class="fas fa-pen"></i> <?php esc_html_e('Edit', 'woocommerce'); ?>
                </a>
            </div>
            <div class="info-card-body">
                <div class="address-content">
                    <?php
                    $billing_address_formatted = $customer->get_billing_address_1();
                    if (!empty($billing_address_formatted)) {
                        echo wp_kses_post($customer->get_formatted_billing_address());
                    } else {
                        echo '<p class="address-empty"><i class="fas fa-info-circle"></i> ' . esc_html__('No billing address set. Add one for faster checkout.', 'woocommerce') . '</p>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Shipping Address -->
        <div class="info-card">
            <div class="info-card-header">
                <h3><i class="fas fa-truck"></i> <?php esc_html_e('Shipping Address', 'woocommerce'); ?></h3>
                <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address', 'shipping')); ?>" class="edit-link">
                    <i class="fas fa-pen"></i> <?php esc_html_e('Edit', 'woocommerce'); ?>
                </a>
            </div>
            <div class="info-card-body">
                <div class="address-content">
                    <?php
                    $shipping_address_formatted = $customer->get_shipping_address_1();
                    if (!empty($shipping_address_formatted)) {
                        echo wp_kses_post($customer->get_formatted_shipping_address());
                    } else {
                        echo '<p class="address-empty"><i class="fas fa-info-circle"></i> ' . esc_html__('No shipping address set. Add one for faster checkout.', 'woocommerce') . '</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="recent-orders-section">
        <div class="section-title">
            <i class="fas fa-history"></i>
            <span><?php esc_html_e('Recent Orders', 'woocommerce'); ?></span>
            <?php if ($customer_orders_count > 0) : ?>
                <a href="<?php echo esc_url(wc_get_endpoint_url('orders')); ?>" style="margin-left: auto; font-size: 0.75rem; color: var(--brand); text-decoration: none;">
                    <?php esc_html_e('View All', 'woocommerce'); ?> <i class="fas fa-arrow-right"></i>
                </a>
            <?php endif; ?>
        </div>

        <?php if (!empty($recent_orders)) : ?>
            <div class="orders-table-wrapper">
                <table class="dashboard-orders-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Order', 'woocommerce'); ?></th>
                            <th><?php esc_html_e('Date', 'woocommerce'); ?></th>
                            <th><?php esc_html_e('Status', 'woocommerce'); ?></th>
                            <th><?php esc_html_e('Total', 'woocommerce'); ?></th>
                            <th><?php esc_html_e('Actions', 'woocommerce'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_orders as $order) : ?>
                            <tr>
                                <td data-title="<?php esc_attr_e('Order', 'woocommerce'); ?>">
                                    #<?php echo esc_html($order->get_order_number()); ?>
                                </td>
                                <td data-title="<?php esc_attr_e('Date', 'woocommerce'); ?>">
                                    <?php echo esc_html($order->get_date_created()->date_i18n(get_option('date_format'))); ?>
                                </td>
                                <td data-title="<?php esc_attr_e('Status', 'woocommerce'); ?>">
                                    <span class="order-status order-status-<?php echo esc_attr($order->get_status()); ?>">
                                        <?php echo esc_html(wc_get_order_status_name($order->get_status())); ?>
                                    </span>
                                </td>
                                <td data-title="<?php esc_attr_e('Total', 'woocommerce'); ?>">
                                    <?php echo wp_kses_post($order->get_formatted_order_total()); ?>
                                </td>
                                <td data-title="<?php esc_attr_e('Actions', 'woocommerce'); ?>">
                                    <a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="order-view-link">
                                        <?php esc_html_e('View', 'woocommerce'); ?> <i class="fas fa-arrow-right"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <div class="no-orders-message">
                <i class="fas fa-shopping-bag"></i>
                <p><?php esc_html_e('You haven\'t placed any orders yet.', 'woocommerce'); ?></p>
                <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="shop-now-btn">
                    <?php esc_html_e('Start Shopping', 'woocommerce'); ?> <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

