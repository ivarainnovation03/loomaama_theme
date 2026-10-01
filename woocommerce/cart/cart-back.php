<?php
/**
 * Cart Page
 *
 * @package Loomaama
 */

get_header(); ?>

<main class="cart-page py-5">
    <div class="container">
        <h1 class="headline text-center mb-5"><?php _e('Shopping Cart', 'loomaama'); ?></h1>
        
        <?php if (WC()->cart->get_cart_contents_count() > 0) : ?>
            <div class="row">
                <div class="col-lg-8">
                    <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
                        <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents">
                            <thead>
                                <tr>
                                    <th class="product-remove">&nbsp;</th>
                                    <th class="product-thumbnail">&nbsp;</th>
                                    <th class="product-name"><?php _e('Product', 'loomaama'); ?></th>
                                    <th class="product-price"><?php _e('Price', 'loomaama'); ?></th>
                                    <th class="product-quantity"><?php _e('Quantity', 'loomaama'); ?></th>
                                    <th class="product-subtotal"><?php _e('Subtotal', 'loomaama'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php do_action('woocommerce_before_cart_contents'); ?>
                                
                                <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
                                    $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                                    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
                                    
                                    if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) :
                                ?>
                                    <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
                                        <td class="product-remove">
                                            <?php
                                            echo apply_filters(
                                                'woocommerce_cart_item_remove_link',
                                                sprintf(
                                                    '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                                                    esc_url(wc_get_cart_remove_url($cart_item_key)),
                                                    __('Remove this item', 'loomaama'),
                                                    esc_attr($product_id),
                                                    esc_attr($_product->get_sku())
                                                ),
                                                $cart_item_key
                                            );
                                            ?>
                                        </td>
                                        
                                        <td class="product-thumbnail">
                                            <?php
                                            $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
                                            if (!$_product->is_visible()) {
                                                echo $thumbnail;
                                            } else {
                                                printf('<a href="%s">%s</a>', esc_url($_product->get_permalink()), $thumbnail);
                                            }
                                            ?>
                                        </td>
                                        
                                        <td class="product-name" data-title="<?php _e('Product', 'loomaama'); ?>">
                                            <?php
                                            if (!$_product->is_visible()) {
                                                echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key));
                                            } else {
                                                echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($_product->get_permalink()), $_product->get_name()), $cart_item, $cart_item_key));
                                            }
                                            
                                            do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);
                                            
                                            // Meta data
                                            echo wc_get_formatted_cart_item_data($cart_item);
                                            
                                            // Backorder notification
                                            if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                                                echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . __('Available on backorder', 'loomaama') . '</p>', $product_id));
                                            }
                                            ?>
                                        </td>
                                        
                                        <td class="product-price" data-title="<?php _e('Price', 'loomaama'); ?>">
                                            <?php
                                            echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
                                            ?>
                                        </td>
                                        
                                        <td class="product-quantity" data-title="<?php _e('Quantity', 'loomaama'); ?>">
                                            <?php
                                            if ($_product->is_sold_individually()) {
                                                $product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
                                            } else {
                                                $product_quantity = woocommerce_quantity_input(
                                                    array(
                                                        'input_name'   => "cart[{$cart_item_key}][qty]",
                                                        'input_value'  => $cart_item['quantity'],
                                                        'max_value'    => $_product->get_max_purchase_quantity(),
                                                        'min_value'    => '0',
                                                        'product_name' => $_product->get_name(),
                                                    ),
                                                    $_product,
                                                    false
                                                );
                                            }
                                            
                                            echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item);
                                            ?>
                                        </td>
                                        
                                        <td class="product-subtotal" data-title="<?php _e('Subtotal', 'loomaama'); ?>">
                                            <?php
                                            echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
                                            ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <?php endforeach; ?>
                                
                                <?php do_action('woocommerce_cart_contents'); ?>
                                
                                <tr>
                                    <td colspan="6" class="actions">
                                        <?php if (wc_coupons_enabled()) { ?>
                                            <div class="coupon">
                                                <label for="coupon_code" class="screen-reader-text"><?php _e('Coupon:', 'loomaama'); ?></label>
                                                <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php _e('Coupon code', 'loomaama'); ?>" />
                                                <button type="submit" class="button" name="apply_coupon" value="<?php _e('Apply coupon', 'loomaama'); ?>"><?php _e('Apply coupon', 'loomaama'); ?></button>
                                                <?php do_action('woocommerce_cart_coupon'); ?>
                                            </div>
                                        <?php } ?>
                                        
                                        <button type="submit" class="button" name="update_cart" value="<?php _e('Update cart', 'loomaama'); ?>"><?php _e('Update cart', 'loomaama'); ?></button>
                                        
                                        <?php do_action('woocommerce_cart_actions'); ?>
                                        
                                        <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                                    </td>
                                </tr>
                                
                                <?php do_action('woocommerce_after_cart_contents'); ?>
                            </tbody>
                        </table>
                    </form>
                </div>
                
                <div class="col-lg-4">
                    <div class="cart-collaterals">
                        <?php
                        do_action('woocommerce_cart_collaterals');
                        ?>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <div class="text-center py-5">
                <p class="mb-4"><?php _e('Your cart is currently empty.', 'loomaama'); ?></p>
                <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn btn-dark px-4 py-3">
                    <?php _e('Continue Shopping', 'loomaama'); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>