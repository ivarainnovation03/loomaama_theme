<?php
/**
 * Checkout Form
 *
 * @package Loomaama
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get checkout object
$checkout = WC()->checkout();

// If checkout registration is disabled and not logged in, the user cannot checkout
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'loomaama')));
    return;
}

do_action('woocommerce_before_checkout_form', $checkout);
?>
<div class="loomaama-checkout">
    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">
        
        <div class="checkout-layout">
            <!-- LEFT COLUMN - CHECKOUT FORMS -->
            <div class="checkout-form-section">
                
                <!-- INFORMATION SECTION -->
                <div class="section-header">
                    <span class="section-number">1</span>
                    <h3 class="section-title"><?php _e('Information', 'loomaama'); ?></h3>
                </div>
                
                <?php if ($checkout->get_checkout_fields()) : ?>
                    
                    <?php do_action('woocommerce_checkout_before_customer_details'); ?>
                    
                    <div id="customer_details">
                        <!-- BILLING FIELDS -->
                        <div class="billing-fields">
                            <div class="section-header" style="margin-top: 20px;">
                                <span class="section-number">2</span>
                                <h3 class="section-title"><?php _e('Billing Address', 'loomaama'); ?></h3>
                            </div>
                            
                            <?php 
                            // Filter to add custom classes to billing fields
                            add_filter('woocommerce_form_field_args', 'loomaama_checkout_field_args', 10, 3);
                            do_action('woocommerce_checkout_billing'); 
                            remove_filter('woocommerce_form_field_args', 'loomaama_checkout_field_args', 10);
                            ?>
                        </div>

                        <!-- SHIPPING FIELDS -->
                        <div class="shipping-fields">
                            <div class="section-header" style="margin-top: 40px;">
                                <span class="section-number">3</span>
                                <h3 class="section-title"><?php _e('Shipping Address', 'loomaama'); ?></h3>
                            </div>
                            
                            <?php 
                            add_filter('woocommerce_form_field_args', 'loomaama_checkout_field_args', 10, 3);
                            do_action('woocommerce_checkout_shipping'); 
                            remove_filter('woocommerce_form_field_args', 'loomaama_checkout_field_args', 10);
                            ?>
                        </div>

                        <?php do_action('woocommerce_checkout_after_customer_details'); ?>
                    </div>
                    
                <?php endif; ?>
                
                <!-- PAYMENT SECTION -->
                <div class="section-header" style="margin-top: 40px;">
                    <span class="section-number">4</span>
                    <h3 class="section-title"><?php _e('Payment', 'loomaama'); ?></h3>
                </div>
                
                <div class="payment-methods-wrapper">
                    <?php woocommerce_checkout_payment(); ?>
                </div>
                
            </div> <!-- /.checkout-form-section -->

            <!-- RIGHT COLUMN - ORDER SUMMARY WITH PRODUCT IMAGES -->
            <div class="order-summary">
                <div class="summary-header">
                    <h4 class="summary-title"><?php _e('Your Order', 'loomaama'); ?></h4>
                    <span class="summary-item-count">
                        <?php echo sprintf(_n('%s item', '%s items', WC()->cart->get_cart_contents_count(), 'loomaama'), WC()->cart->get_cart_contents_count()); ?>
                    </span>
                </div>
                
                <div class="cart-items">
                    <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
                        $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                        $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
                        
                        if ($_product && $_product->exists() && $cart_item['quantity'] > 0) :
                            
                            // Get product categories
                            $categories = wc_get_product_category_list($product_id);
                            $category_name = strip_tags($categories);
                            $first_category = explode(',', $category_name)[0];
                            
                            $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                            $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('woocommerce_thumbnail'), $cart_item, $cart_item_key);
                            $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
                            $product_price = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
                            $product_subtotal = apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
                    ?>
                        <div class="cart-item">
                            <div class="cart-item-image">
                                <?php if (!$_product->is_visible()) : ?>
                                    <?php echo $thumbnail; ?>
                                <?php else : ?>
                                    <a href="<?php echo esc_url($product_permalink); ?>">
                                        <?php echo $thumbnail; ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                            
                            <div class="cart-item-details">
                                <?php if (!$_product->is_visible()) : ?>
                                    <span class="cart-item-title"><?php echo wp_kses_post($product_name); ?></span>
                                <?php else : ?>
                                    <a href="<?php echo esc_url($product_permalink); ?>" class="cart-item-title">
                                        <?php echo wp_kses_post($product_name); ?>
                                    </a>
                                <?php endif; ?>
                                
                                <div class="cart-item-variation">
                                    <?php if (!empty($first_category)) : ?>
                                        <span class="variation-item">
                                            <span class="variation-label"><?php _e('Category:', 'loomaama'); ?></span>
                                            <span class="variation-value"><?php echo esc_html(trim($first_category)); ?></span>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php 
                                    // Display variation attributes
                                    if ($cart_item['variation']) :
                                        foreach ($cart_item['variation'] as $attribute_name => $attribute_value) :
                                            $taxonomy = str_replace('attribute_', '', $attribute_name);
                                            $attribute_label = wc_attribute_label($taxonomy);
                                            $attribute_value = urldecode($attribute_value);
                                    ?>
                                        <span class="variation-item">
                                            <span class="variation-label"><?php echo esc_html($attribute_label); ?>:</span>
                                            <span class="variation-value"><?php echo esc_html($attribute_value); ?></span>
                                        </span>
                                    <?php 
                                        endforeach;
                                    endif; 
                                    ?>
                                </div>
                                
                                <div class="cart-item-price"><?php echo $product_price; ?></div>
                                
                                <div class="cart-item-quantity">
                                    <span><?php _e('Qty:', 'loomaama'); ?></span>
                                    <span class="quantity-badge"><?php echo esc_html($cart_item['quantity']); ?></span>
                                </div>
                            </div>
                            
                            <div class="cart-item-actions">
                                <?php
                                echo apply_filters(
                                    'woocommerce_cart_item_remove_link',
                                    sprintf(
                                        '<a href="%s" class="cart-item-remove" aria-label="%s" data-product_id="%s" data-product_sku="%s" title="%s">&times;</a>',
                                        esc_url(wc_get_cart_remove_url($cart_item_key)),
                                        __('Remove this item', 'loomaama'),
                                        esc_attr($product_id),
                                        esc_attr($_product->get_sku()),
                                        __('Remove', 'loomaama')
                                    ),
                                    $cart_item_key
                                );
                                ?>
                                <span class="cart-item-subtotal"><?php echo $product_subtotal; ?></span>
                            </div>
                        </div>
                    <?php 
                        endif;
                    endforeach; ?>
                </div> <!-- /.cart-items -->
                
                <!-- PRICE BREAKDOWN -->
                <div class="price-breakdown">
                    <div class="price-row">
                        <span class="price-label"><?php _e('Subtotal', 'loomaama'); ?></span>
                        <span class="price-value"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
                    </div>
                    
                    <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
                        <div class="price-row">
                            <span class="price-label">
                                <i class="fas fa-tag"></i>
                                <?php _e('Coupon:', 'loomaama'); ?> <?php echo esc_html($code); ?>
                            </span>
                            <span class="price-value">-<?php echo wc_price(WC()->cart->get_coupon_discount_amount($code)); ?></span>
                        </div>
                    <?php endforeach; ?>
                    
                    <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>
                        <?php do_action('woocommerce_review_order_before_shipping'); ?>
                        <?php woocommerce_cart_totals_shipping_html(); ?>
                        <?php do_action('woocommerce_review_order_after_shipping'); ?>
                    <?php endif; ?>
                    
                    <?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) : ?>
                        <?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : ?>
                            <div class="price-row">
                                <span class="price-label">
                                    <i class="fas fa-receipt"></i>
                                    <?php echo esc_html($tax->label); ?>
                                </span>
                                <span class="price-value"><?php echo wp_kses_post($tax->formatted_amount); ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <div class="price-row total">
                        <span class="price-label"><?php _e('Total', 'loomaama'); ?></span>
                        <span class="price-value"><?php echo WC()->cart->get_total(); ?></span>
                    </div>
                </div> <!-- /.price-breakdown -->
                
                <!-- PROMO CODE -->
                <?php if (wc_coupons_enabled()) : ?>
                    <div class="promo-code">
                        <input type="text" name="coupon_code" class="promo-input" id="coupon_code" value="" placeholder="<?php _e('Promo code', 'loomaama'); ?>" />
                        <button type="submit" class="promo-button" name="apply_coupon" value="<?php _e('Apply', 'loomaama'); ?>"><?php _e('Apply', 'loomaama'); ?></button>
                        <?php do_action('woocommerce_cart_coupon'); ?>
                    </div>
                <?php endif; ?>
                
                <?php do_action('woocommerce_review_order_before_submit'); ?>
                
                <!-- PLACE ORDER BUTTON - This will be replaced by WooCommerce's button -->
                <?php // Do NOT add a manual place order button here - WooCommerce adds it automatically ?>
                
                <?php do_action('woocommerce_review_order_after_submit'); ?>
                
                <!-- PAYMENT ICONS -->
                <div class="payment-icons">
                    <img src="<?php echo esc_url(LOOMAAMA_URI . '/assets/images/payment/visa.svg'); ?>" class="payment-icon" alt="Visa" onerror="this.style.display='none'">
                    <img src="<?php echo esc_url(LOOMAAMA_URI . '/assets/images/payment/mastercard.svg'); ?>" class="payment-icon" alt="Mastercard" onerror="this.style.display='none'">
                    <img src="<?php echo esc_url(LOOMAAMA_URI . '/assets/images/payment/amex.svg'); ?>" class="payment-icon" alt="American Express" onerror="this.style.display='none'">
                    <img src="<?php echo esc_url(LOOMAAMA_URI . '/assets/images/payment/paypal.svg'); ?>" class="payment-icon" alt="PayPal" onerror="this.style.display='none'">
                    <img src="<?php echo esc_url(LOOMAAMA_URI . '/assets/images/payment/apple-pay.svg'); ?>" class="payment-icon" alt="Apple Pay" onerror="this.style.display='none'">
                </div>
                
                <!-- TRUST BADGES -->
                <div class="trust-badges">
                    <span class="trust-badge">
                        <i class="fas fa-lock"></i>
                        <?php _e('Secure Payment', 'loomaama'); ?>
                    </span>
                    <span class="trust-badge">
                        <i class="fas fa-shield-alt"></i>
                        <?php _e('Buyer Protection', 'loomaama'); ?>
                    </span>
                    <span class="trust-badge">
                        <i class="fas fa-undo-alt"></i>
                        <?php _e('30-Day Returns', 'loomaama'); ?>
                    </span>
                </div>
                
            </div> <!-- /.order-summary -->
        </div> <!-- /.checkout-layout -->
        
        <?php do_action('woocommerce_checkout_after_order_review'); ?>
        
    </form>
</div> <!-- /.loomaama-checkout -->

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>

<script>
jQuery(document).ready(function($) {
    // Add loading state to place order button
    $('form.checkout').on('submit', function() {
        $('#place_order').prop('disabled', true).addClass('loading');
    });
    
    // Copy billing to shipping checkbox styling
    $('#ship-to-different-address-checkbox').on('change', function() {
        if ($(this).is(':checked')) {
            $('.shipping-fields').show();
        } else {
            $('.shipping-fields').hide();
        }
    });
    
    // Initialize shipping fields visibility
    if (!$('#ship-to-different-address-checkbox').is(':checked')) {
        $('.shipping-fields').hide();
    }
    
    // Handle payment method selection styling
    $(document).on('change', 'input[name="payment_method"]', function() {
        $('.wc_payment_method').removeClass('selected');
        $(this).closest('.wc_payment_method').addClass('selected');
    });
    
    // Style form inputs on focus
    $('.woocommerce-checkout .form-row input, .woocommerce-checkout .form-row select, .woocommerce-checkout .form-row textarea').on('focus', function() {
        $(this).closest('.form-row').addClass('focused');
    }).on('blur', function() {
        $(this).closest('.form-row').removeClass('focused');
    });
    
    // Ensure shipping methods are styled properly
    $(document).on('updated_checkout', function() {
        $('.woocommerce-shipping-methods .shipping_method').each(function() {
            var $this = $(this);
            var $row = $this.closest('li');
            if ($this.is(':checked')) {
                $row.addClass('selected');
            } else {
                $row.removeClass('selected');
            }
        });
        
        $('.woocommerce-shipping-methods .shipping_method').on('change', function() {
            $('.woocommerce-shipping-methods li').removeClass('selected');
            $(this).closest('li').addClass('selected');
        });
    });
});
</script>