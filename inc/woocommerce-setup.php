<?php
/**
 * WooCommerce Setup and Customizations
 *
 * @package Loomaama
 */

/**
 * Remove default WooCommerce wrappers
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

// Remove default WooCommerce breadcrumbs
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

// Remove product page title
add_filter('woocommerce_show_page_title', '__return_false');

/**
 * Add custom theme wrappers
 */
add_action('woocommerce_before_main_content', 'loomaama_woocommerce_wrapper_start', 10);
function loomaama_woocommerce_wrapper_start() {
    echo '<main class="woocommerce-page" style="margin-top: 120px;"><div class="container-fluid px-4 px-lg-5">';
}

add_action('woocommerce_after_main_content', 'loomaama_woocommerce_wrapper_end', 10);
function loomaama_woocommerce_wrapper_end() {
    echo '</div></main>';
}

/**
 * Customize product catalog columns
 */
add_filter('loop_shop_columns', 'loomaama_loop_shop_columns');
function loomaama_loop_shop_columns() {
    return 3;
}

/**
 * Customize related products columns
 */
add_filter('woocommerce_output_related_products_args', 'loomaama_related_products_args');
function loomaama_related_products_args($args) {
    $args['posts_per_page'] = get_theme_mod('related_products_count', 4);
    $args['columns'] = 4;
    return $args;
}

/**
 * Customize upsell products
 */
add_filter('woocommerce_upsell_display_args', 'loomaama_upsell_display_args');
function loomaama_upsell_display_args($args) {
    $args['posts_per_page'] = 4;
    $args['columns'] = 4;
    return $args;
}

/**
 * Remove default WooCommerce tabs and add custom ones
 */
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
add_action('woocommerce_after_single_product_summary', 'loomaama_output_product_accordion', 10);

function loomaama_output_product_accordion() {
    global $product;
    ?>
    <div class="details-accordion">
        <div class="accordion-item">
            <div class="accordion-header" onclick="toggleAccordion(this)">
                <?php _e('Details & Fit', 'loomaama'); ?> <i class="fa fa-plus"></i>
            </div>
            <div class="accordion-content">
                <?php echo wp_kses_post($product->get_description()); ?>
            </div>
        </div>
        
        <?php if ($product->has_attributes() || $product->has_dimensions() || $product->has_weight()) : ?>
        <div class="accordion-item">
            <div class="accordion-header" onclick="toggleAccordion(this)">
                <?php _e('Provenance', 'loomaama'); ?> <i class="fa fa-plus"></i>
            </div>
            <div class="accordion-content">
                <?php do_action('woocommerce_product_additional_information', $product); ?>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="accordion-item">
            <div class="accordion-header" onclick="toggleAccordion(this)">
                <?php _e('Delivery & Returns', 'loomaama'); ?> <i class="fa fa-plus"></i>
            </div>
            <div class="accordion-content">
                <p><?php _e('Enjoy free carbon-neutral shipping on this item. Returns are accepted within 14 days of receipt for a full refund.', 'loomaama'); ?></p>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Change number of products per row in shop
 */
add_filter('loop_shop_columns', 'loomaama_shop_columns', 20);
function loomaama_shop_columns($columns) {
    if (is_shop() || is_product_category() || is_product_tag()) {
        $columns = 3;
    }
    return $columns;
}

/**
 * Add custom badges to products
 */
add_action('woocommerce_before_shop_loop_item_title', 'loomaama_product_badges', 5);
function loomaama_product_badges() {
    global $product;
    
    if ($product->is_on_sale()) {
        $regular_price = $product->get_regular_price();
        $sale_price = $product->get_sale_price();
        if ($regular_price && $sale_price) {
            $savings = round((($regular_price - $sale_price) / $regular_price) * 100);
            echo '<span class="badge-sale position-absolute top-0 start-0 m-3">-' . $savings . '%</span>';
        }
    }
    
    if ($product->is_featured()) {
        echo '<span class="badge-best position-absolute top-0 end-0 m-3">' . __('Best-Seller', 'loomaama') . '</span>';
    }
}

/**
 * Remove sale flash
 */
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);

/**
 * Customize product loop item structure
 */
remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);

add_action('woocommerce_before_shop_loop_item', 'loomaama_product_loop_start', 5);
function loomaama_product_loop_start() {
    echo '<div class="lux-card">';
}

add_action('woocommerce_before_shop_loop_item_title', 'loomaama_product_loop_image', 10);
function loomaama_product_loop_image() {
    global $product;
    echo '<div class="lux-img position-relative">';
    echo '<a href="' . esc_url(get_permalink()) . '">';
    
    $thumbnail_html = woocommerce_get_product_thumbnail('medium_large');
    if (function_exists('get_field') && get_field('hover_image')) {
        $hover_image_url = get_field('hover_image')['sizes']['medium_large'];
        $thumbnail_html = str_replace('<img', '<img data-hover-image="' . esc_attr($hover_image_url) . '"', $thumbnail_html);
    }
    
    echo $thumbnail_html;
    
    echo '</a></div>';
}

add_action('woocommerce_before_shop_loop_item_title', 'loomaama_product_loop_badges', 15);
function loomaama_product_loop_badges() {
    // Badges are already added via separate hook
}

add_action('woocommerce_before_shop_loop_item_title', 'loomaama_product_loop_image_close', 20);
function loomaama_product_loop_image_close() {
    echo '</div>';
}

add_action('woocommerce_shop_loop_item_title', 'loomaama_product_loop_category', 5);
function loomaama_product_loop_category() {
    global $product;
    $categories = wc_get_product_category_list($product->get_id());
    if ($categories) {
        echo '<p class="lux-cat headline">' . strip_tags($categories) . '</p>';
    }
}

add_action('woocommerce_shop_loop_item_title', 'loomaama_product_loop_title', 10);
function loomaama_product_loop_title() {
    global $product;
    echo '<div class="d-flex justify-content-between align-items-start">';
    echo '<a href="' . get_permalink() . '" class="text-decoration-none">';
    echo '<h6 class="mb-0 text-truncate" style="font-size: 13px;">' . get_the_title() . '</h6>';
    echo '</a>';
}

add_action('woocommerce_after_shop_loop_item_title', 'loomaama_product_loop_price', 5);
function loomaama_product_loop_price() {
    global $product;
    echo '<span class="text-secondary" style="font-size: 13px;">' . $product->get_price_html() . '</span>';
    echo '</div>'; // Close d-flex
}

add_action('woocommerce_after_shop_loop_item', 'loomaama_product_loop_end', 10);
function loomaama_product_loop_end() {
    echo '</div>'; // Close lux-card
}

/**
 * Newsletter subscription AJAX
 */
add_action('wp_ajax_loomaama_newsletter_subscribe', 'loomaama_newsletter_subscribe');
add_action('wp_ajax_nopriv_loomaama_newsletter_subscribe', 'loomaama_newsletter_subscribe');

function loomaama_newsletter_subscribe() {
    check_ajax_referer('loomaama_nonce', 'nonce');
    
    $email = sanitize_email($_POST['email']);
    
    if (!is_email($email)) {
        wp_send_json_error(array('message' => __('Invalid email address', 'loomaama')));
    }
    
    // If you have a newsletter plugin like MailChimp, hook into it here
    // For now, just store in options or send email
    $subscribers = get_option('loomaama_newsletter_subscribers', array());
    if (!in_array($email, $subscribers)) {
        $subscribers[] = $email;
        update_option('loomaama_newsletter_subscribers', $subscribers);
    }
    
    wp_send_json_success(array('message' => __('Thank you for subscribing!', 'loomaama')));
}



// Handle cart quantity updates via AJAX
add_action('wp_ajax_update_cart_quantity', 'loomaama_update_cart_quantity');
add_action('wp_ajax_nopriv_update_cart_quantity', 'loomaama_update_cart_quantity');

function loomaama_update_cart_quantity() {
    // Verify nonce
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'cart_quantity_nonce')) {
        wp_send_json_error('Invalid security token');
        return;
    }
    
    if (!isset($_POST['cart_item_key']) || !isset($_POST['quantity'])) {
        wp_send_json_error('Missing parameters');
        return;
    }
    
    $cart_item_key = sanitize_text_field($_POST['cart_item_key']);
    $quantity = intval($_POST['quantity']);
    
    // Initialize cart if needed
    if (function_exists('WC')) {
        WC()->cart->calculate_totals();
    }
    
    if ($quantity <= 0) {
        // Remove item if quantity is 0 or less
        WC()->cart->remove_cart_item($cart_item_key);
    } else {
        // Update quantity
        WC()->cart->set_quantity($cart_item_key, $quantity);
    }
    
    WC()->cart->calculate_totals();
    
    // Return success with cart data
    wp_send_json_success(array(
        'cart_count' => WC()->cart->get_cart_contents_count(),
        'cart_subtotal' => WC()->cart->get_cart_subtotal(),
        'cart_total' => WC()->cart->get_total()
    ));
}

// Get cart drawer HTML via AJAX
/*
add_action('wp_ajax_get_cart_drawer', 'loomaama_get_cart_drawer');
add_action('wp_ajax_nopriv_get_cart_drawer', 'loomaama_get_cart_drawer');

function loomaama_get_cart_drawer() {
    // Verify nonce
    if (!isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'get_cart_nonce')) {
        wp_send_json_error('Invalid security token');
        return;
    }
    
    ob_start();
    
    if (WC()->cart->get_cart_contents_count() > 0) {
        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $_product = $cart_item['data'];
            $product_id = $cart_item['product_id'];
            
            // Get product categories
            $categories = wc_get_product_category_list($product_id);
            $category_name = strip_tags($categories);
            $first_category = explode(',', $category_name)[0];
            
            // Get variation attributes
            $variation_display = '';
            if ($cart_item['variation'] && is_array($cart_item['variation'])) {
                $variation_display = wc_get_formatted_variation($cart_item['variation'], true);
            }
            ?>
            <div class="cart-item" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">
                <div class="cart-item__media">
                    <a href="<?php echo esc_url($_product->get_permalink($cart_item)); ?>" class="cart-item__link" tabindex="-1" aria-hidden="true">&nbsp;</a>
                    <?php echo $_product->get_image('woocommerce_thumbnail'); ?>
                </div>

                <div class="cart-item__details">
                    <div class="cart-item__details-title-price">
                        <a href="<?php echo esc_url($_product->get_permalink($cart_item)); ?>" class="cart-item__name headline">
                            <?php echo esc_html($_product->get_name()); ?>
                        </a>
                        <div class="cart-item__price">
                            <?php echo WC()->cart->get_product_price($_product); ?>
                        </div>
                    </div>

                    <dl class="cart-item__details-properties">
                        <?php if (!empty($first_category)) : ?>
                            <div class="product-option">
                                <dt><?php _e('Category', 'loomaama'); ?>:</dt>
                                <dd><?php echo esc_html(trim($first_category)); ?></dd>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($variation_display)) : ?>
                            <div class="product-option">
                                <dt><?php _e('Size', 'loomaama'); ?>:</dt>
                                <dd><?php echo $variation_display; ?></dd>
                            </div>
                        <?php endif; ?>
                        
                        <div class="product-option">
                            <dt><?php _e('Quantity', 'loomaama'); ?>:</dt>
                            <dd><?php echo esc_html($cart_item['quantity']); ?></dd>
                        </div>
                    </dl>

                    <!-- Quantity controls (hidden by default, shown on modify) -->
                    <div class="cart-item__quantity-controls" style="display: none;">
                        <div class="quantity-selector">
                            <button class="quantity-selector__btn" 
                                    onclick="updateCartQuantity('<?php echo esc_js($cart_item_key); ?>', 'remove', '<?php echo wp_create_nonce('cart_quantity_nonce'); ?>')" 
                                    <?php echo ($cart_item['quantity'] <= 1) ? 'disabled' : ''; ?>>
                                −
                            </button>
                            <span class="quantity-selector__value"><?php echo $cart_item['quantity']; ?></span>
                            <button class="quantity-selector__btn" 
                                    onclick="updateCartQuantity('<?php echo esc_js($cart_item_key); ?>', 'add', '<?php echo wp_create_nonce('cart_quantity_nonce'); ?>')">
                                +
                            </button>
                        </div>
                    </div>

                    <div class="cart-item__actions">
                        <button type="button" class="cart-item__action cart-item__modify" onclick="toggleModifyMode(this)">
                            <?php _e('Modify', 'loomaama'); ?>
                        </button>
                        <button type="button" class="cart-item__action cart-item__remove" 
                                onclick="updateCartQuantity('<?php echo esc_js($cart_item_key); ?>', 'delete', '<?php echo wp_create_nonce('cart_quantity_nonce'); ?>')">
                            <?php _e('Remove', 'loomaama'); ?>
                        </button>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        ?>
        <div class="cart-empty">
            <p class="text-secondary"><?php _e('Your cart is empty.', 'loomaama'); ?></p>
        </div>
        <?php
    }
    
    $html = ob_get_clean();
    
    wp_send_json_success(array(
        'html' => $html,
        'cart_item_key' =>$cart_item_key,
        'cart_count' => WC()->cart->get_cart_contents_count(),
        'cart_subtotal' => WC()->cart->get_cart_subtotal(),
        'cart_total' => WC()->cart->get_total()
    ));
}
*/
// Get cart drawer HTML via AJAX
add_action('wp_ajax_get_cart_drawer', 'loomaama_get_cart_drawer');
add_action('wp_ajax_nopriv_get_cart_drawer', 'loomaama_get_cart_drawer');

function loomaama_get_cart_drawer() {
    // Verify nonce
    if (!isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'get_cart_nonce')) {
        wp_send_json_error('Invalid security token');
        return;
    }
    
    // Calculate totals to ensure everything is up to date
    WC()->cart->calculate_totals();
    
    ob_start();
    ?>
    <div class="drawer__body" id="cart-drawer-items">
        <?php if (WC()->cart->get_cart_contents_count() > 0) : ?>
            <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) : 
                $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                $product_id = $_product->get_id();
                
                // Get product categories
                $categories = wc_get_product_category_list($product_id);
                $category_name = strip_tags($categories);
                $first_category = explode(',', $category_name)[0];
                
                // Get variation attributes
                $variation_display = '';
                if ($cart_item['variation'] && is_array($cart_item['variation'])) {
                    $variation_display = wc_get_formatted_variation($cart_item['variation'], true);
                }
                ?>
                <div class="cart-item" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">
                    <div class="cart-item__media">
                        <a href="<?php echo esc_url($_product->get_permalink($cart_item)); ?>" class="cart-item__link" tabindex="-1" aria-hidden="true">&nbsp;</a>
                        <?php echo $_product->get_image('woocommerce_thumbnail'); ?>
                    </div>

                    <div class="cart-item__details">
                        <div class="cart-item__details-title-price">
                            <a href="<?php echo esc_url($_product->get_permalink($cart_item)); ?>" class="cart-item__name headline">
                                <?php echo esc_html($_product->get_name()); ?>
                            </a>
                            <div class="cart-item__price">
                                <?php echo WC()->cart->get_product_price($_product); ?>
                            </div>
                        </div>

                        <dl class="cart-item__details-properties">
                            <?php if (!empty($first_category)) : ?>
                                <div class="product-option">
                                    <dt><?php _e('Category', 'loomaama'); ?>:</dt>
                                    <dd><?php echo esc_html(trim($first_category)); ?></dd>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($variation_display)) : ?>
                                <div class="product-option">
                                    <dt><?php _e('Size', 'loomaama'); ?>:</dt>
                                    <dd><?php echo $variation_display; ?></dd>
                                </div>
                            <?php endif; ?>
                            
                            <div class="product-option">
                                <dt><?php _e('Quantity', 'loomaama'); ?>:</dt>
                                <dd class="item-quantity"><?php echo esc_html($cart_item['quantity']); ?></dd>
                            </div>
                        </dl>

                        <!-- Quantity controls (hidden by default, shown on modify) -->
                        <div class="cart-item__quantity-controls" style="display: none;">
                            <div class="quantity-selector">
                                <button class="quantity-selector__btn" 
                                        onclick="updateCartQuantity('<?php echo esc_js($cart_item_key); ?>', 'remove', '<?php echo wp_create_nonce('cart_quantity_nonce'); ?>')" 
                                        <?php echo ($cart_item['quantity'] <= 1) ? 'disabled' : ''; ?>>
                                    −
                                </button>
                                <span class="quantity-selector__value"><?php echo $cart_item['quantity']; ?></span>
                                <button class="quantity-selector__btn" 
                                        onclick="updateCartQuantity('<?php echo esc_js($cart_item_key); ?>', 'add', '<?php echo wp_create_nonce('cart_quantity_nonce'); ?>')">
                                    +
                                </button>
                            </div>
                        </div>

                        <div class="cart-item__actions">
                            <button type="button" class="cart-item__action cart-item__modify" onclick="toggleModifyMode(this)">
                                <?php _e('Modify', 'loomaama'); ?>
                            </button>
                            <button type="button" class="cart-item__action cart-item__remove" 
                                    onclick="updateCartQuantity('<?php echo esc_js($cart_item_key); ?>', 'delete', '<?php echo wp_create_nonce('cart_quantity_nonce'); ?>')">
                                <?php _e('Remove', 'loomaama'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="cart-empty">
                <p class="text-secondary"><?php _e('Your cart is empty.', 'loomaama'); ?></p>
            </div>
        <?php endif; ?>
    </div>

    <div class="drawer__footer" id="cart-drawer-footer">
        <?php if (WC()->cart->get_cart_contents_count() > 0) : 
            $subtotal = WC()->cart->get_cart_subtotal();
            $total = WC()->cart->get_total();
        ?>
        <div class="cart-footer">
            <div class="cart-footer__row">
                <span class="cart-footer__label"><?php _e('Subtotal', 'loomaama'); ?></span>
                <span class="cart-footer__value"><?php echo $subtotal; ?></span>
            </div>
            <div class="cart-footer__row">
                <span class="cart-footer__label"><?php _e('Shipping & Taxes', 'loomaama'); ?></span>
                <span class="cart-footer__value"><?php _e('Calculated at checkout', 'loomaama'); ?></span>
            </div>
            <div class="cart-footer__row cart-footer__row--total">
                <span class="cart-footer__label"><?php _e('Total', 'loomaama'); ?></span>
                <span class="cart-footer__value"><?php echo $total; ?></span>
            </div>
        </div>
          
        <div class="cart-actions">
            <button type="button" class="cart-checkout-btn" onclick="window.location.href='<?php echo esc_url(wc_get_checkout_url()); ?>'">
                <?php _e('Proceed To Checkout', 'loomaama'); ?>
            </button>
            
        </div>
        
        <?php else : ?>
        <div class="cart-actions">
            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="cart-checkout-btn">
                <?php _e('Continue Shopping', 'loomaama'); ?>
            </a>
        </div>
        <?php endif; ?>
    </div>
    <?php
    $html = ob_get_clean();
    
    wp_send_json_success(array(
        'html' => $html,
        'cart_count' => WC()->cart->get_cart_contents_count(),
        'cart_subtotal' => WC()->cart->get_cart_subtotal(),
        'cart_total' => WC()->cart->get_total()
    ));
}
// Get cart count via AJAX
add_action('wp_ajax_get_cart_count', 'get_cart_count_callback');
add_action('wp_ajax_nopriv_get_cart_count', 'get_cart_count_callback');

function get_cart_count_callback() {
    // Verify nonce
    if (!isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'cart_count_nonce')) {
        wp_send_json_error('Invalid security token');
        return;
    }
    
    wp_send_json_success(array(
        'count' => WC()->cart->get_cart_contents_count()
    ));
}




/**
 * AJAX handler for getting cart count
 */

add_action('wp_ajax_get_cart_count', 'loomaama_get_cart_count');
add_action('wp_ajax_nopriv_get_cart_count', 'loomaama_get_cart_count');

function loomaama_get_cart_count() {
    // Verify nonce
    if (!isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'cart_count_nonce')) {
        wp_send_json_error('Invalid security token');
        return;
    }
    
    wp_send_json_success(array(
        'count' => WC()->cart->get_cart_contents_count()
    ));
}

/**
 * AJAX add to cart for variable products
 */

/*
add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'loomaama_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'loomaama_ajax_add_to_cart');

function loomaama_ajax_add_to_cart() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'loomaama_nonce')) {
        wp_send_json_error(['message' => 'Invalid nonce']);
        wp_die();
    }

    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount(wp_unslash($_POST['quantity']));
    $variation_id = isset($_POST['variation_id']) ? absint($_POST['variation_id']) : 0;
    $variation = array();

    // Parse variation attributes
    if (isset($_POST['variation']) && is_array($_POST['variation'])) {
        foreach ($_POST['variation'] as $key => $value) {
            $variation[$key] = sanitize_text_field($value);
        }
    }

    // Validate product
    if (!$product_id) {
        wp_send_json_error(['message' => 'Invalid product']);
        wp_die();
    }

    // Get product
    $product = wc_get_product($product_id);
    if (!$product) {
        wp_send_json_error(['message' => 'Product not found']);
        wp_die();
    }

    // Validate variation for variable products
    if ($product->is_type('variable')) {
        if (!$variation_id) {
            wp_send_json_error(['message' => 'Please select product options']);
            wp_die();
        }

        // Get variation
        $variation_product = wc_get_product($variation_id);
        if (!$variation_product) {
            wp_send_json_error(['message' => 'Variation not found']);
            wp_die();
        }

        // Check if variation is purchasable
        if (!$variation_product->is_purchasable()) {
            wp_send_json_error(['message' => 'This product is not available for purchase']);
            wp_die();
        }

        // Check stock
        if (!$variation_product->has_enough_stock($quantity)) {
            wp_send_json_error(['message' => 'Not enough stock']);
            wp_die();
        }
    } else {
        // Simple product validation
        if (!$product->is_purchasable()) {
            wp_send_json_error(['message' => 'This product is not available for purchase']);
            wp_die();
        }

        if (!$product->has_enough_stock($quantity)) {
            wp_send_json_error(['message' => 'Not enough stock']);
            wp_die();
        }
    }

    // Add to cart
    $added = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);

    if ($added) {
        do_action('woocommerce_ajax_added_to_cart', $product_id);

        wp_send_json_success([
            'message' => sprintf(__('%s has been added to your cart', 'loomaama'), $product->get_name()),
            'cart_count' => WC()->cart->get_cart_contents_count(),
            'cart_total' => WC()->cart->get_cart_total(),
            'product_id' => $product_id,
            'variation_id' => $variation_id,
            'quantity' => $quantity
        ]);
    } else {
        wp_send_json_error(['message' => 'Unable to add product to cart']);
    }

    wp_die();
}

*/

add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'loomaama_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'loomaama_ajax_add_to_cart');
/*
* change date to 2024-06-05

function loomaama_ajax_add_to_cart() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'loomaama_nonce')) {
        wp_send_json_error(['message' => 'Invalid nonce']);
        wp_die();
    }

    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount(wp_unslash($_POST['quantity']));
    $variation_id = isset($_POST['variation_id']) ? absint($_POST['variation_id']) : 0;
    $variation = array();

    // Parse variation attributes
    if (isset($_POST['variation']) && is_array($_POST['variation'])) {
        foreach ($_POST['variation'] as $key => $value) {
            $variation[$key] = sanitize_text_field($value);
        }
    }

    // Validate product
    if (!$product_id) {
        wp_send_json_error(['message' => 'Invalid product']);
        wp_die();
    }

    // Get product
    $product = wc_get_product($product_id);
    if (!$product) {
        wp_send_json_error(['message' => 'Product not found']);
        wp_die();
    }

    // Validate variation for variable products
    if ($product->is_type('variable')) {
        if (!$variation_id) {
            wp_send_json_error(['message' => 'Please select product options']);
            wp_die();
        }

        // Get variation
        $variation_product = wc_get_product($variation_id);
        if (!$variation_product) {
            wp_send_json_error(['message' => 'Variation not found']);
            wp_die();
        }

        // Check if variation is purchasable
        if (!$variation_product->is_purchasable()) {
            wp_send_json_error(['message' => 'This product is not available for purchase']);
            wp_die();
        }

        // Check stock
        if (!$variation_product->has_enough_stock($quantity)) {
            wp_send_json_error(['message' => 'Not enough stock']);
            wp_die();
        }
    } else {
        // Simple product validation
        if (!$product->is_purchasable()) {
            wp_send_json_error(['message' => 'This product is not available for purchase']);
            wp_die();
        }

        if (!$product->has_enough_stock($quantity)) {
            wp_send_json_error(['message' => 'Not enough stock']);
            wp_die();
        }
    }

    // Add to cart
    $added = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);

    if ($added) {
        do_action('woocommerce_ajax_added_to_cart', $product_id);

        // Get cart fragments
        ob_start();
        woocommerce_mini_cart();
        $mini_cart = ob_get_clean();

        // Get cart count
        $cart_count = WC()->cart->get_cart_contents_count();

        // Get cart total
        $cart_total = WC()->cart->get_cart_total();

        wp_send_json_success([
            'message' => sprintf(__('%s has been added to your cart', 'loomaama'), $product->get_name()),
            'cart_count' => $cart_count,
            'cart_total' => $cart_total,
            'mini_cart' => $mini_cart,
            'product_id' => $product_id,
            'variation_id' => $variation_id,
            'quantity' => $quantity
        ]);
    } else {
        wp_send_json_error(['message' => 'Unable to add product to cart']);
    }

    wp_die();
}*/


function loomaama_ajax_add_to_cart() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'loomaama_nonce')) {
        wp_send_json_error(['message' => 'Invalid nonce']);
        wp_die();
    }

    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount(wp_unslash($_POST['quantity']));
    $variation_id = isset($_POST['variation_id']) ? absint($_POST['variation_id']) : 0;
    $variation = array();

    // Parse variation attributes
    if (isset($_POST['variation']) && is_array($_POST['variation'])) {
        foreach ($_POST['variation'] as $key => $value) {
            $variation[$key] = sanitize_text_field($value);
        }
    }

    // Validate product
    if (!$product_id) {
        wp_send_json_error(['message' => 'Invalid product']);
        wp_die();
    }

    // Get product
    $product = wc_get_product($product_id);
    if (!$product) {
        wp_send_json_error(['message' => 'Product not found']);
        wp_die();
    }

    // Validate variation for variable products
    if ($product->is_type('variable')) {
        if (!$variation_id) {
            wp_send_json_error(['message' => 'Please select product options']);
            wp_die();
        }

        // Get variation
        $variation_product = wc_get_product($variation_id);
        if (!$variation_product) {
            wp_send_json_error(['message' => 'Variation not found']);
            wp_die();
        }

        // Check if variation is purchasable
        if (!$variation_product->is_purchasable()) {
            wp_send_json_error(['message' => 'This product is not available for purchase']);
            wp_die();
        }

        // Check stock
        if (!$variation_product->has_enough_stock($quantity)) {
            wp_send_json_error(['message' => 'Not enough stock']);
            wp_die();
        }
    } else {
        // Simple product validation
        if (!$product->is_purchasable()) {
            wp_send_json_error(['message' => 'This product is not available for purchase']);
            wp_die();
        }

        if (!$product->has_enough_stock($quantity)) {
            wp_send_json_error(['message' => 'Not enough stock']);
            wp_die();
        }
    }

    // Add to cart
    $added = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);

    if ($added) {
        do_action('woocommerce_ajax_added_to_cart', $product_id);
        
        // Force cart calculation
        WC()->cart->calculate_totals();

        wp_send_json_success([
            'message' => sprintf(__('%s has been added to your cart', 'loomaama'), $product->get_name()),
            'cart_count' => WC()->cart->get_cart_contents_count(),
            'cart_total' => WC()->cart->get_cart_total(),
            'product_id' => $product_id,
            'variation_id' => $variation_id,
            'quantity' => $quantity
        ]);
    } else {
        wp_send_json_error(['message' => 'Unable to add product to cart']);
    }

    wp_die();
}

/**
 * AJAX handler for updating checkout fragments
 */
add_action('wp_ajax_woocommerce_update_order_review', 'loomaama_update_order_review');
add_action('wp_ajax_nopriv_woocommerce_update_order_review', 'loomaama_update_order_review');

function loomaama_update_order_review() {
    check_ajax_referer('update-order-review', 'security');
    
    if (!defined('WOOCOMMERCE_CHECKOUT')) {
        define('WOOCOMMERCE_CHECKOUT', true);
    }
    
    if (WC()->cart->is_empty()) {
        wp_die();
    }
    
    do_action('woocommerce_checkout_update_order_review', $_POST['post_data']);
    
    $chosen_shipping_methods = WC()->session->get('chosen_shipping_methods');
    $posted_shipping_methods = isset($_POST['shipping_method']) ? $_POST['shipping_method'] : '';
    
    if (is_array($posted_shipping_methods)) {
        foreach ($posted_shipping_methods as $i => $value) {
            $chosen_shipping_methods[$i] = wc_clean($value);
        }
        WC()->session->set('chosen_shipping_methods', $chosen_shipping_methods);
    }
    
    if (isset($_POST['calc_shipping']) && ('yes' === $_POST['calc_shipping'])) {
        WC()->shipping()->reset_shipping();
        WC()->cart->calculate_totals();
    }
    
    WC()->cart->calculate_totals();
    
    ob_start();
    woocommerce_order_review();
    $woocommerce_order_review = ob_get_clean();
    
    ob_start();
    woocommerce_checkout_payment();
    $woocommerce_checkout_payment = ob_get_clean();
    
    wp_send_json_success(array(
        'result' => 'success',
        'messages' => '',
        'reload' => false,
        'fragments' => apply_filters('woocommerce_update_order_review_fragments', array(
            '.woocommerce-checkout-review-order-table' => $woocommerce_order_review,
            '.woocommerce-checkout-payment' => $woocommerce_checkout_payment,
        )),
    ));
}

/**
 * Ensure checkout pages are using the correct template
 */
add_filter('template_include', 'loomaama_checkout_template', 100);

function loomaama_checkout_template($template) {
    if (function_exists('is_checkout') && is_checkout()) {
        $checkout_template = locate_template('woocommerce/checkout/checkout.php');
        if ($checkout_template) {
            return $checkout_template;
        }
    }
    return $template;
}

/**
 * Get refreshed cart fragments after AJAX add to cart
 */
add_filter('woocommerce_add_to_cart_fragments', 'loomaama_cart_fragments');

function loomaama_cart_fragments($fragments) {
    // Update cart count
    ob_start();
    ?>
    <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
    <?php
    $fragments['.cart-count'] = ob_get_clean();

    return $fragments;
}


/**
 * AJAX handler for filtering sale products
 */
/**
 * AJAX handler for filtering sale products (with search support)
 */
/**
 * AJAX handler for filtering sale products (with search support)
 */
/**
 * AJAX handler for filtering sale products (with search support)
 */
add_action('wp_ajax_loomaama_filter_sale_products', 'loomaama_filter_sale_products');
add_action('wp_ajax_nopriv_loomaama_filter_sale_products', 'loomaama_filter_sale_products');

function loomaama_filter_sale_products() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'loomaama_filter_nonce')) {
        wp_send_json_error('Invalid nonce');
        return;
    }
    
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'tax_query' => array(),
        'meta_query' => array(),
        's' => '',
    );

    // ✅ SEARCH TERM FILTER
    if (isset($_POST['search_term']) && !empty($_POST['search_term'])) {
        $search_term = sanitize_text_field($_POST['search_term']);
        $args['s'] = $search_term;
    }
    
    // ✅ CATEGORY FILTER
    if (isset($_POST['categories']) && !empty($_POST['categories'])) {
        $categories = $_POST['categories'];
        
        if (is_array($categories)) {
            $filtered_cats = array_filter($categories, function($cat) {
                return $cat !== 'all';
            });
            
            if (!empty($filtered_cats)) {
                $args['tax_query'][] = array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $filtered_cats,
                    'operator' => 'IN'
                );
            }
        } elseif (is_string($categories) && $categories !== 'all') {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $categories,
                'operator' => 'IN'
            );
        }
    }

    // ✅ PRICE RANGE FILTER
    if (isset($_POST['min_price']) && isset($_POST['max_price']) && !empty($_POST['min_price']) && !empty($_POST['max_price'])) {
        $args['meta_query'][] = array(
            'key' => '_price',
            'value' => array(floatval($_POST['min_price']), floatval($_POST['max_price'])),
            'compare' => 'BETWEEN',
            'type' => 'NUMERIC'
        );
    }

    // ✅ COLOR FILTER - FIXED: Using ACF meta field 'variant_color'
    if (isset($_POST['colors']) && !empty($_POST['colors'])) {
        $colors = (array) $_POST['colors'];
        
        // Build meta query for color variants
        $color_meta_query = array('relation' => 'OR');
        foreach ($colors as $color_slug) {
            $color_meta_query[] = array(
                'key' => 'variant_color',
                'value' => $color_slug,
                'compare' => '='
            );
        }
        
        if (count($colors) === 1) {
            // Single color - simple meta query
            $args['meta_query'][] = array(
                'key' => 'variant_color',
                'value' => $colors[0],
                'compare' => '='
            );
        } else {
            // Multiple colors - OR relation
            $args['meta_query'][] = $color_meta_query;
        }
    }

    // ✅ PATTERN FILTER - FIXED: Using ACF meta field 'variant_pattern'
    if (isset($_POST['patterns']) && !empty($_POST['patterns'])) {
        $patterns = (array) $_POST['patterns'];
        
        // Build meta query for pattern variants
        $pattern_meta_query = array('relation' => 'OR');
        foreach ($patterns as $pattern_slug) {
            $pattern_meta_query[] = array(
                'key' => 'variant_pattern',
                'value' => $pattern_slug,
                'compare' => '='
            );
        }
        
        if (count($patterns) === 1) {
            // Single pattern - simple meta query
            $args['meta_query'][] = array(
                'key' => 'variant_pattern',
                'value' => $patterns[0],
                'compare' => '='
            );
        } else {
            // Multiple patterns - OR relation
            $args['meta_query'][] = $pattern_meta_query;
        }
    }

    // ✅ SIZE FILTER - Using WooCommerce attribute (assuming pa_size exists)
    if (isset($_POST['sizes']) && !empty($_POST['sizes'])) {
        $sizes = (array) $_POST['sizes'];
        
        // Check if pa_size taxonomy exists
        if (taxonomy_exists('pa_size')) {
            $args['tax_query'][] = array(
                'taxonomy' => 'pa_size',
                'field' => 'slug',
                'terms' => $sizes,
                'operator' => 'IN'
            );
        } else {
            // Fallback: try to filter by size from variations
            $args['meta_query'][] = array(
                'key' => '_variation_attributes',
                'value' => $sizes,
                'compare' => 'LIKE'
            );
        }
    }

    // ✅ SORTING
    if (isset($_POST['sort_by']) && !empty($_POST['sort_by'])) {
        switch ($_POST['sort_by']) {
            case 'popularity':
                $args['meta_key'] = 'total_sales';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                break;

            case 'rating':
                $args['meta_key'] = '_wc_average_rating';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                break;

            case 'latest':
                $args['orderby'] = 'date';
                $args['order'] = 'DESC';
                break;

            case 'price_desc':
                $args['meta_key'] = '_price';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                break;

            case 'price_asc':
                $args['meta_key'] = '_price';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'ASC';
                break;
        }
    } else {
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
    }

    // ✅ Add visibility filter to exclude hidden products
    $args['tax_query'][] = array(
        'taxonomy' => 'product_visibility',
        'field' => 'name',
        'terms' => array('exclude-from-catalog', 'exclude-from-search'),
        'operator' => 'NOT IN'
    );

    // ✅ Set relation for tax_query if multiple conditions
    if (count($args['tax_query']) > 1) {
        $args['tax_query']['relation'] = 'AND';
    }

    // ✅ Set relation for meta_query if multiple conditions
    if (count($args['meta_query']) > 1) {
        $args['meta_query']['relation'] = 'AND';
    }

    // Debug - uncomment to see the query
    // error_log('Filter Query: ' . print_r($args, true));

    $products = new WP_Query($args);
    
    ob_start();
    $total_posts = $products->post_count;
    $product_count = 0;
    
    if ($products->have_posts()) :
        while ($products->have_posts()) : $products->the_post();
            global $product;
            $product_count++;
            
            $col_class = '';
            if ($total_posts >= 10) {
                if ($product_count === 1) {
                    $col_class .= ' d-change-product';
                } elseif ($product_count === 6) {
                    $col_class .= 'reverse-change-product';
                }
            }
            
            $categories = wc_get_product_category_list($product->get_id());
            $category_name = strip_tags($categories);
            $first_category = explode(',', $category_name)[0];
            
            $regular_price = $product->get_regular_price();
            $sale_price = $product->get_sale_price();
            $discount = '';
            if ($regular_price && $sale_price && $regular_price > 0) {
                $discount = round((($regular_price - $sale_price) / $regular_price) * 100);
            }
            ?>
            <div class="<?php echo esc_attr($col_class); ?> reveal" data-aos="fade-up">
                <div class="lux-card">
                    <div class="lux-img position-relative">
                        <a href="<?php the_permalink(); ?>">
                            <?php 
                                $thumbnail_html = woocommerce_get_product_thumbnail('medium_large');
                                $hover_image_url = '';
                                if (function_exists('get_field') && get_field('hover_image')) {
                                    $hover_image_url = get_field('hover_image')['sizes']['medium_large'];
                                }
                                echo $thumbnail_html;
                                
                                // Add hover overlay image
                                if ($hover_image_url) {
                                    echo '<img class="hover-overlay-img" src="' . esc_attr($hover_image_url) . '" alt="' . esc_attr(get_the_title()) . '" loading="lazy" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0; transition: opacity 0.3s ease; z-index: 2;">';
                                }
                            ?>
                        </a>
                        
                        <?php if ($product->is_on_sale() && $discount) : ?>
                            <span class="badge-sale position-absolute top-0 start-0 m-3"><?php echo '-' . $discount . '%'; ?></span>
                        <?php endif; ?>
                        
                        <?php if ($product->is_featured()) : ?>
                            <span class="badge-best position-absolute top-0 end-0 m-3"><?php _e('Best-Seller', 'loomaama'); ?></span>
                        <?php endif; ?>
                        
                        <?php if (!$product->is_in_stock()) : ?>
                            <span class="badge-soldout position-absolute top-0 start-0 m-3"><?php _e('Sold out', 'loomaama'); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="lux-content">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="lux-cat headline">
                                <?php echo esc_html(strtoupper($first_category ?: __('SALE', 'loomaama'))); ?>
                            </p>
                           
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-start loom-title-the-price">
                            <a href="<?php the_permalink(); ?>" class="text-decoration-none flex-grow-1" title="<?php the_title(); ?>">
                                <h6 class="mb-0 text-truncate" style="font-size: 13px;"><?php the_title(); ?></h6>
                            </a>
                            <span class="text-secondary <?php echo ($product->is_on_sale() && $discount) ? 'sale-on' : 'sale-off'; ?>" style="font-size: 13px;">
                                <?php echo $product->get_price_html(); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else : ?>
        <div class="col-12 text-center py-5" data-aos="fade-up">
            <p class="text-secondary">
                <?php 
                $search_term = isset($_POST['search_term']) ? sanitize_text_field($_POST['search_term']) : '';
                if (!empty($search_term)) {
                    echo sprintf(__('No products found matching "%s".', 'loomaama'), esc_html($search_term));
                } else {
                    _e('No products found.', 'loomaama');
                }
                ?>
            </p>
        </div>
    <?php endif;
    
    wp_reset_postdata();
    
    $html = ob_get_clean();
    
    wp_send_json_success(array(
        'html' => $html,
        'count' => $products->post_count,
        'found_posts' => $products->found_posts,
        'total_posts' => $total_posts,
    ));
}
/**
 * AJAX handler for loading more sale products
 */
/*
add_action('wp_ajax_loomaama_load_more_sale', 'loomaama_load_more_sale');
add_action('wp_ajax_nopriv_loomaama_load_more_sale', 'loomaama_load_more_sale');

function loomaama_load_more_sale() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'loomaama_nonce')) {
        wp_send_json_error('Invalid nonce');
    }
    
    $page = intval($_POST['page']);
    
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 12,
        'paged' => $page,
        
    );
    
    $products = new WP_Query($args);
    
    ob_start();
    
    if ($products->have_posts()) :
        while ($products->have_posts()) : $products->the_post();
            global $product;
            
            $categories = wc_get_product_category_list($product->get_id());
            $category_name = strip_tags($categories);
            $first_category = explode(',', $category_name)[0];
            
            $regular_price = $product->get_regular_price();
            $sale_price = $product->get_sale_price();
            $discount = '';
            if ($regular_price && $sale_price && $regular_price > 0) {
                $discount = round((($regular_price - $sale_price) / $regular_price) * 100);
            }
            ?>
              <div class="<?php echo $col_class; ?> reveal">
                        <div class="lux-card">
                            <div class="lux-img position-relative">
                                <?php echo woocommerce_get_product_thumbnail('large'); ?>
                                <!-- badges here -->                                 
                                  <?php if ($product->is_on_sale() && $discount) : ?>
                                    <span class="badge-sale position-absolute top-0 start-0 m-3">
                                        <?php echo '-' . $discount . '%'; ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($product->is_featured()) : ?>
                                    <span class="badge-best position-absolute top-0 end-0 m-3">
                                        <?php _e('Best-Seller', 'loomaama'); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if (!$product->is_in_stock()) : ?>
                                    <span class="badge-soldout position-absolute top-0 start-0 m-3">
                                        <?php _e('Sold out', 'loomaama'); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="lux-content"> <!-- Add this wrapper -->
                                <p class="lux-cat headline">
                                    <?php echo esc_html(strtoupper($first_category ?: __('SALE', 'loomaama'))); ?>
                                </p>
                                
                                <div class="d-flex justify-content-between align-items-start loom-title-the-price">
                                    <a href="<?php the_permalink(); ?>" class="text-decoration-none flex-grow-1" title="<?php the_title(); ?>">
                                        <h6 class="mb-0 text-truncate" style="font-size: 13px;" ><?php the_title(); ?></h6>
                                    </a>
                                     <span class="text-secondary <?php echo ($product->is_on_sale() && $discount)?' sale-on':'sale-off'; ?>" style="font-size: 13px;">
                                        <?php echo $product->get_price_html(); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
        <?php endwhile; ?>
    <?php endif;
    
    wp_reset_postdata();
    
    $html = ob_get_clean();
    
    wp_send_json_success(array(
        'html' => $html
    ));
}
*/

/* Search section  */

// Enqueue search scripts
/**
 * Enqueue Styles and Scripts for Search Drawer
 */
add_action('wp_enqueue_scripts', 'enqueue_search_drawer_styles');
function enqueue_search_drawer_styles() {
    if (class_exists('WooCommerce')) {
        wp_enqueue_style('woocommerce-search-drawer', get_template_directory_uri() . '/assets/css/search-drawer.css', array(), '1.1');
    }
}

add_action('wp_enqueue_scripts', 'enqueue_search_drawer_scripts');
function enqueue_search_drawer_scripts() {
    if (class_exists('WooCommerce')) {
        wp_enqueue_script('woocommerce-search-drawer', get_template_directory_uri() . '/assets/js/search-drawer.js', array('jquery'), '1.1', true);
        
        wp_localize_script('woocommerce-search-drawer', 'woocommerce_search_params', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('live_search_nonce'),
            'more_results_text' => __('View All Results', 'loomaama'),
            'no_results_text' => __('No products found.', 'loomaama'),
            'products_text' => __('products found', 'loomaama'),
            'archive_url' => get_permalink(wc_get_page_id('shop'))
        ));
    }
}

/**
 * Updated AJAX handler: Searches Categories THEN Products
 */
add_action('wp_ajax_live_product_search', 'handle_live_product_search');
add_action('wp_ajax_nopriv_live_product_search', 'handle_live_product_search');

function handle_live_product_search() {
    if (!wp_verify_nonce($_POST['nonce'], 'live_search_nonce')) {
        wp_send_json_error('Invalid nonce');
        wp_die();
    }
    
    $search_term = sanitize_text_field($_POST['search_term']);
    $limit = 6; 

    // --- PRODUCT SEARCH ---
    $args = array(
        'post_type'      => 'product',
        'post_status'    => 'publish',
        's'              => $search_term,
        'posts_per_page' => $limit,
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => array('exclude-from-catalog', 'exclude-from-search'),
                'operator' => 'NOT IN',
            ),
        ),
    );
    
    $products = new WP_Query($args);
    $product_data = array();
    $suggested_words = array(); // Array to store suggested search words

    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();
            $title = get_the_title();
            $product = wc_get_product(get_the_ID());
            
            // Logic: Split title into words and find ones matching the search
            $words = explode(' ', strtolower($title));
            foreach($words as $word) {
                $clean_word = preg_replace('/[^a-z0-9]/', '', $word);
                if (strpos($clean_word, strtolower($search_term)) !== false && strlen($clean_word) > 2) {
                    $suggested_words[] = ucfirst($clean_word);
                }
            }

            $product_data[] = array(
                'id'    => get_the_ID(),
                'title' => $title,
                'url'   => get_permalink(),
                'price' => $product->get_price_html(),
                'image' => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail')
            );
        }
        wp_reset_postdata();
    }

    // Clean up duplicate suggested words
    $suggested_words = array_unique($suggested_words);
    $suggested_words = array_slice($suggested_words, 0, 5); // Only show top 5

    wp_send_json_success(array(
        'suggestions' => $suggested_words,
        'products'    => $product_data,
        'total'       => $products->found_posts,
        'limit'       => $limit,
        'search_term' => $search_term
    ));
}

/**
 * Modernized Search Filter for Results Page
 */
add_action('pre_get_posts', 'restrict_search_to_products');
function restrict_search_to_products($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        $post_type = isset($_GET['post_type']) ? $_GET['post_type'] : '';
        
        if ($post_type === 'product') {
            $query->set('post_type', 'product');
            
            // Use the tax_query instead of meta_query for better performance/accuracy
            $tax_query = (array) $query->get('tax_query');
            $tax_query[] = array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => array('exclude-from-catalog', 'exclude-from-search'),
                'operator' => 'NOT IN',
            );
            $query->set('tax_query', $tax_query);
        }
    }
}


/**
 * AJAX handler for loading more products (with search support and color dots)
 */
add_action('wp_ajax_loomaama_load_more_sale', 'loomaama_load_more_sale');
add_action('wp_ajax_nopriv_loomaama_load_more_sale', 'loomaama_load_more_sale');

function loomaama_load_more_sale() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'loomaama_nonce')) {
        wp_send_json_error('Invalid nonce');
        return;
    }
    
    $page = intval($_POST['page']);
    $search_term = isset($_POST['search_term']) ? sanitize_text_field($_POST['search_term']) : '';
    $posts_per_page = 12;
    
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $posts_per_page,
        'paged' => $page,
        'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_visibility',
                'field' => 'name',
                'terms' => array('exclude-from-catalog', 'exclude-from-search'),
                'operator' => 'NOT IN',
            ),
        ),
    );
    
    // Add search term if present
    if (!empty($search_term)) {
        $args['s'] = $search_term;
    }
    
    $products = new WP_Query($args);
    
    ob_start();
    
    if ($products->have_posts()) :
        while ($products->have_posts()) : $products->the_post();
            global $product;
            
            $categories = wc_get_product_category_list($product->get_id());
            $category_name = strip_tags($categories);
            $first_category = explode(',', $category_name)[0];
            
            $regular_price = $product->get_regular_price();
            $sale_price = $product->get_sale_price();
            $discount = '';
            if ($regular_price && $sale_price && $regular_price > 0) {
                $discount = round((($regular_price - $sale_price) / $regular_price) * 100);
            }
            
            // Get current product's color for active state
            $product_id = get_the_ID();
            $design_group = get_post_meta($product_id, 'design_group_id', true);
            $current_color_slug = get_post_meta($product_id, 'variant_color', true);
            ?>
            <div class="reveal" data-aos="fade-up">
                <div class="lux-card">
                    <div class="lux-img position-relative">
                        <a href="<?php the_permalink(); ?>">
                            <?php 
                                $thumbnail_html = woocommerce_get_product_thumbnail('medium_large');
                                $hover_image_url = '';
                                if (function_exists('get_field') && get_field('hover_image')) {
                                    $hover_image_url = get_field('hover_image')['sizes']['medium_large'];
                                }
                                echo $thumbnail_html;
                                
                                // Add hover overlay image
                                if ($hover_image_url) {
                                    echo '<img class="hover-overlay-img" src="' . esc_attr($hover_image_url) . '" alt="' . esc_attr(get_the_title()) . '" loading="lazy">';
                                }
                            ?>
                        </a>
                        
                        <?php if ($product->is_on_sale() && $discount) : ?>
                            <span class="badge-sale position-absolute top-0 start-0 m-3"><?php echo '-' . $discount . '%'; ?></span>
                        <?php endif; ?>
                        
                        <?php if ($product->is_featured()) : ?>
                            <span class="badge-best position-absolute top-0 end-0 m-3"><?php _e('Best-Seller', 'loomaama'); ?></span>
                        <?php endif; ?>
                        
                        <?php if (!$product->is_in_stock()) : ?>
                            <span class="badge-soldout position-absolute top-0 start-0 m-3"><?php _e('Sold out', 'loomaama'); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="lux-content">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="lux-cat headline mb-0">
                                <?php echo esc_html(strtoupper($first_category ?: __('SALE', 'loomaama'))); ?>
                            </p>
                            
                            
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-start loom-title-the-price mt-2">
                            <a href="<?php the_permalink(); ?>" class="text-decoration-none flex-grow-1" title="<?php the_title(); ?>">
                                <h6 class="mb-0 text-truncate" style="font-size: 13px;"><?php the_title(); ?></h6>
                            </a>
                            <span class="text-secondary <?php echo ($product->is_on_sale() && $discount) ? 'sale-on' : 'sale-off'; ?>" style="font-size: 13px;">
                                <?php echo $product->get_price_html(); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif;
    
    wp_reset_postdata();
    
    $html = ob_get_clean();
    
    wp_send_json_success(array(
        'html' => $html
    ));
}

/**
 * Register lost password endpoints
 */
add_action('init', 'loomaama_register_lost_password_endpoint');
function loomaama_register_lost_password_endpoint() {
    add_rewrite_endpoint('lost-password', EP_ROOT | EP_PAGES);
    add_rewrite_endpoint('reset-password', EP_ROOT | EP_PAGES);
}

/**
 * Add lost password to query vars
 */
add_filter('query_vars', 'loomaama_add_lost_password_query_vars');
function loomaama_add_lost_password_query_vars($query_vars) {
    $query_vars[] = 'lost-password';
    $query_vars[] = 'reset-password';
    return $query_vars;
}

/**
 * Handle lost password content in custom template
 */
add_action('template_redirect', 'loomaama_handle_lost_password_page');
function loomaama_handle_lost_password_page() {
    global $wp;
    
    // Check if we're on the my account page with lost-password endpoint
    if (is_account_page() && isset($wp->query_vars['lost-password'])) {
        // Don't redirect, just let the template handle it
        return;
    }
}

/**
 * Ensure the lost password form is displayed
 */
add_action('woocommerce_account_lost-password_endpoint', 'loomaama_display_lost_password_form');
function loomaama_display_lost_password_form() {
    wc_get_template('myaccount/form-lost-password.php');
}

add_action('woocommerce_account_reset-password_endpoint', 'loomaama_display_reset_password_form');
function loomaama_display_reset_password_form() {
    wc_get_template('myaccount/form-reset-password.php');
}

/**
 * Flush rewrite rules on theme activation
 */
add_action('after_switch_theme', 'loomaama_password_flush_rewrite_rules');
function loomaama_password_flush_rewrite_rules() {
    loomaama_register_lost_password_endpoint();
    flush_rewrite_rules();
}



add_action('init', function() {
    if (isset($_GET['test_template'])) {
        $template_path = WC()->plugin_path() . '/templates/myaccount/form-lost-password.php';
        echo 'Template exists: ' . (file_exists($template_path) ? 'Yes' : 'No');
        echo '<br>Path: ' . $template_path;
        exit;
    }
});


/**
 * AJAX handler to get patterns for a specific color using WooCommerce attributes
 */
add_action('wp_ajax_loomaama_get_patterns_for_color', 'loomaama_ajax_get_patterns_for_color');
add_action('wp_ajax_nopriv_loomaama_get_patterns_for_color', 'loomaama_ajax_get_patterns_for_color');

function loomaama_ajax_get_patterns_for_color() {
    check_ajax_referer('loomaama_variant_nonce', 'nonce');
    
    $design_group = sanitize_text_field($_POST['design_group']);
    $color = sanitize_text_field($_POST['color']);
    
    if (empty($design_group) || empty($color)) {
        wp_send_json_error('Missing parameters');
    }
    
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => 'pa_color',
                'field' => 'slug',
                'terms' => $color,
            ),
        ),
        'meta_query' => array(
            array(
                'key' => 'design_group_id',
                'value' => $design_group,
                'compare' => '='
            )
        ),
    );
    
    $products = new WP_Query($args);
    
    ob_start();
    if ($products->have_posts()) :
        while ($products->have_posts()) : $products->the_post();
            $variant_id = get_the_ID();
            $pattern_terms = wp_get_post_terms($variant_id, 'pa_pattern');
            $pattern = !empty($pattern_terms) ? $pattern_terms[0] : null;
            
            if (!$pattern) continue;
            
            $pattern_name = $pattern->name;
            $pattern_slug = $pattern->slug;
            
            // Get pattern thumbnail from SCF/ACF
            $pattern_thumb = '';
            if (function_exists('get_field')) {
                $pattern_thumb = get_field('pattern_thumbnail', $variant_id);
            } else {
                $pattern_thumb = get_post_meta($variant_id, 'pattern_thumbnail', true);
            }
            ?>
            <a href="<?php echo get_permalink($variant_id); ?>" 
               class="pattern-option-link text-decoration-none text-center">
                
                <?php if ($pattern_thumb && is_array($pattern_thumb)) : ?>
                    <div class="pattern-thumb mb-2" 
                         style="width: 60px; height: 60px; background-image: url('<?php echo esc_url($pattern_thumb['url']); ?>'); background-size: cover; background-position: center; border: 2px solid var(--border);">
                    </div>
                <?php else : ?>
                    <div class="pattern-thumb mb-2 d-flex align-items-center justify-content-center bg-light" 
                         style="width: 60px; height: 60px; border: 2px solid var(--border); font-size: 10px; text-transform: uppercase;">
                        <?php echo esc_html(substr($pattern_name, 0, 3)); ?>
                    </div>
                <?php endif; ?>
                
                <span class="pattern-name small" style="font-size: 9px; letter-spacing: 0.1em; color: var(--text-secondary); display: block;">
                    <?php echo esc_html($pattern_name); ?>
                </span>
            </a>
        <?php endwhile;
    else : ?>
        <p class="text-secondary small"><?php _e('No patterns available for this color.', 'loomaama'); ?></p>
    <?php endif;
    wp_reset_postdata();
    
    $html = ob_get_clean();
    
    wp_send_json_success(array('html' => $html));
}


/**
 * Get color variants for a product by design group
 * 
 * @param int $product_id Product ID
 * @return array Array of color variants
 */
function loomaama_get_product_color_variants($product_id) {
    $color_variants = array();
    
    $design_group = get_post_meta($product_id, 'design_group_id', true);
    
    if (empty($design_group)) {
        return $color_variants;
    }
    
    // Cache the results by design group (not by product)
    $cache_key = 'color_variants_group_' . md5($design_group);
    $cached = get_transient($cache_key);
    
    if ($cached !== false) {
        // For cached data, we need to update which color is current for THIS product
        $current_color_slug = get_post_meta($product_id, 'variant_color', true);
        foreach ($cached as $slug => &$variant) {
            $variant['is_current'] = ($slug == $current_color_slug);
        }
        return $cached;
    }
    
    // Find all products in the same design group
    $variant_ids = get_posts(array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_key' => 'design_group_id',
        'meta_value' => $design_group,
        'fields' => 'ids',
        'no_found_rows' => true,
    ));
    
    if (!empty($variant_ids) && count($variant_ids) > 1) {
        // Get unique colors and their corresponding product IDs
        foreach ($variant_ids as $vid) {
            $color_slug = get_post_meta($vid, 'variant_color', true);
            if ($color_slug && !isset($color_variants[$color_slug])) {
                $color_term = get_term_by('slug', $color_slug, 'pa_color');
                $color_name = $color_term ? $color_term->name : $color_slug;
                
                // Get hex color
                $hex = '#E8E5DF';
                if (function_exists('loomaama_get_color_hex_from_slug')) {
                    $hex = loomaama_get_color_hex_from_slug($color_slug, $color_name);
                } else {
                    $hex_map = array(
                        'natural-cream' => '#E8E5DF',
                        'oatmeal' => '#D8CFC4',
                        'sand' => '#C2B29F',
                        'taupe' => '#A3917F',
                        'earth-brown' => '#8B7355',
                        'red' => '#FF0000',
                        'charcoal' => '#4A4A4A',
                    );
                    $hex = isset($hex_map[$color_slug]) ? $hex_map[$color_slug] : '#E8E5DF';
                }
                
                $color_variants[$color_slug] = array(
                    'product_id' => $vid,
                    'slug' => $color_slug,
                    'name' => $color_name,
                    'hex' => $hex,
                    'is_current' => false // Will be set when rendering for specific product
                );
            }
        }
        
        // Cache for 12 hours
        set_transient($cache_key, $color_variants, 12 * HOUR_IN_SECONDS);
    }
    
    // Set current color for this specific product
    $current_color_slug = get_post_meta($product_id, 'variant_color', true);
    if (isset($color_variants[$current_color_slug])) {
        $color_variants[$current_color_slug]['is_current'] = true;
    }
    
    return $color_variants;
}

/**
 * Render color variant dots HTML for a specific product
 * 
 * @param int $product_id Product ID (optional, will use current product if not provided)
 * @return string HTML for color dots
 */
function loomaama_render_color_variant_dots($product_id = null) {
    if (!$product_id) {
        $product_id = get_the_ID();
    }
    
    $color_variants = loomaama_get_product_color_variants($product_id);
    
    if (empty($color_variants)) {
        return '';
    }
    
    ob_start();
    ?>
    <div class="variant-indicators" style="display: flex; flex-wrap: wrap; gap: 6px; align-items: center;">
        <div class="color-dots d-flex gap-1 align-items-center">
            <?php foreach ($color_variants as $color_slug => $color_data) : 
                $active_class = $color_data['is_current'] ? 'active' : '';
                $variant_url = get_permalink($color_data['product_id']);
            ?>
                <a href="<?php echo esc_url($variant_url); ?>" class="color-dot-link" 
                   title="<?php echo esc_attr($color_data['name']); ?>"
                   <?php if ($color_data['is_current']) echo 'data-current="true"'; ?>>
                    <span class="color-dot rounded-circle <?php echo $active_class; ?>" 
                          style="background-color: <?php echo esc_attr($color_data['hex']); ?>;">
                    </span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

