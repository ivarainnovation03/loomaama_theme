<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="google-site-verification" content="hfX7qVX5AGN1qOgKJGMuWIDLEBk2OuyTxqDBcHBShIc" />
	<meta name="google-site-verification" content="jgsU8qxeON4CVk5q5KTDG-9ZatRxnluhKA7YrJ1yiXU" />
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Overlay -->
<div id="overlay" onclick="closeAllPanels()"></div>

<!-- Cart Drawer -->
<aside id="cart-drawer" class="cart-drawer">
    <div class="drawer__inner">
        <div class="drawer__header">
            <h5 class="drawer__heading headline" data-aos="fade-right">
                <?php _e('Bag', 'loomaama'); ?> 
                (<span class="cart-count-drawer"><?php echo class_exists('WooCommerce') ? WC()->cart->get_cart_contents_count() : 0; ?></span>)
            </h5>
            <button class="drawer__close" onclick="toggleCart()" aria-label="<?php _e('Close', 'loomaama'); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M14.1908 0.706913L0.706913 14.1908L0 13.4838L13.4838 0L14.1908 0.706913Z" fill="currentColor"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0.706913 0L14.1908 13.4838L13.4838 14.1908L0 0.706913L0.706913 0Z" fill="currentColor"/>
                </svg>
            </button>
        </div>

        <!-- Promo Bar -->
        <div class="promo-bar" id="cart-promo-bar" data-aos="fade-up">
            <div class="promo-bar__slides">
                <div class="promo-bar__slide active">
                    <div class="promo-bar__content">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/lock.svg'); ?>" alt="" class="promo-bar__icon" width="13" height="16">
                        <p><?php _e('Your transactions are protected by the highest security standards.', 'loomaama'); ?></p>
                    </div>
                </div>
                <div class="promo-bar__slide">
                    <div class="promo-bar__content">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/truck.svg'); ?>" alt="" class="promo-bar__icon" width="19" height="14">
                        <p><?php _e('Complimentary shipping. International shipping $25 flat rate.', 'loomaama'); ?></p>
                    </div>
                </div>
                <div class="promo-bar__slide">
                    <div class="promo-bar__content">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/reorder.svg'); ?>" alt="" class="promo-bar__icon" width="19" height="18">
                        <p><?php _e('Complimentary returns within 30 days.', 'loomaama'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart Items Container -->
        <div class="drawer__contents-wrapper">
            <div class="drawer__contents-inner">
                <!-- Cart Items -->
                <div class="drawer__body" id="cart-drawer-items">
                    <?php if (class_exists('WooCommerce') && WC()->cart->get_cart_contents_count() > 0) : ?>
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
                            <div class="cart-item" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>" data-aos="fade-up">
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

                <!-- Recently Viewed Products -->
                <?php if (class_exists('WooCommerce')) : 
                    $viewed_products = !empty($_COOKIE['woocommerce_recently_viewed']) ? (array) explode('|', wp_unslash($_COOKIE['woocommerce_recently_viewed'])) : array();
                    $viewed_products = array_reverse(array_filter(array_map('absint', $viewed_products)));
                    
                    if (!empty($viewed_products)) :
                        $viewed_products = array_slice($viewed_products, 0, 6);
                        $args = array(
                            'post_type' => 'product',
                            'post__in' => $viewed_products,
                            'orderby' => 'post__in',
                            'posts_per_page' => 6,
                        );
                        $recent_products = new WP_Query($args);
                        
                        if ($recent_products->have_posts()) :
                ?>
                <div class="recently-viewed-products">
                    <div class="recently-viewed-products__container">
                        <h6 class="recently-viewed-products__heading"><?php _e('Recently Viewed', 'loomaama'); ?></h6>
                        
                        <div class="recently-viewed-products__slider">
                            <?php while ($recent_products->have_posts()) : $recent_products->the_post(); 
                                global $product;
                                if (empty($product) || !$product->is_visible()) continue;
                                
                                $product_id = $product->get_id();
                                $product_image = wp_get_attachment_image_url($product->get_image_id(), 'woocommerce_thumbnail');
                                $product_name = $product->get_name();
                                $product_price = $product->get_price_html();
                                $product_categories = wc_get_product_category_list($product_id);
                                $category_name = strip_tags($product_categories);
                                $first_category = explode(',', $category_name)[0];
                            ?>
                            <div class="recently-viewed-products__item">
                                <div class="recent-product-card">
                                    <a href="<?php echo esc_url(get_permalink($product_id)); ?>" class="recent-product-card__link">
                                        <div class="recent-product-card__image">
                                            <img src="<?php echo esc_url($product_image ?: wc_placeholder_img_src()); ?>" alt="<?php echo esc_attr($product_name); ?>" loading="lazy">
                                        </div>
                                        <div class="recent-product-card__content">
                                            <h6 class="recent-product-card__title"><?php echo esc_html($product_name); ?></h6>
                                            <?php if (!empty($first_category)) : ?>
                                                <span class="recent-product-card__category"><?php echo esc_html(trim($first_category)); ?></span>
                                            <?php endif; ?>
                                            <span class="recent-product-card__price"><?php echo $product_price; ?></span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </div>
                        
                        <div class="recently-viewed-products__nav">
                            <button class="slider-nav slider-nav--prev" aria-label="<?php _e('Previous', 'loomaama'); ?>" disabled>←</button>
                            <button class="slider-nav slider-nav--next" aria-label="<?php _e('Next', 'loomaama'); ?>">→</button>
                        </div>
                    </div>
                </div>
                <?php endif; endif; endif; ?>
            </div>
        </div>

        <!-- Footer with totals -->
        <div class="drawer__footer" id="cart-drawer-footer">
            <?php if (class_exists('WooCommerce') && WC()->cart->get_cart_contents_count() > 0) : 
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
    </div>
</aside>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Promo bar slider
        const promoSlides = document.querySelectorAll('.promo-bar__slide');
        if (promoSlides.length > 1) {
            let currentSlide = 0;
            
            setInterval(() => {
                promoSlides[currentSlide].classList.remove('active');
                currentSlide = (currentSlide + 1) % promoSlides.length;
                promoSlides[currentSlide].classList.add('active');
            }, 3000);
        }
        
        // Recently viewed slider
        const slider = document.querySelector('.recently-viewed-products__slider');
        const prevBtn = document.querySelector('.slider-nav--prev');
        const nextBtn = document.querySelector('.slider-nav--next');
        
        if (slider && prevBtn && nextBtn) {
            const scrollAmount = 140;
            
            const updateButtons = () => {
                prevBtn.disabled = slider.scrollLeft <= 0;
                nextBtn.disabled = slider.scrollLeft + slider.clientWidth >= slider.scrollWidth;
            };
            
            prevBtn.addEventListener('click', () => {
                slider.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            });
            
            nextBtn.addEventListener('click', () => {
                slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            });
            
            slider.addEventListener('scroll', updateButtons);
            updateButtons();
        }
    });

    // Toggle modify mode for cart items
    function toggleModifyMode(button) {
        const cartItem = button.closest('.cart-item');
        const quantityControls = cartItem.querySelector('.cart-item__quantity-controls');
        const details = cartItem.querySelector('.cart-item__details-properties');
        
        if (quantityControls.style.display === 'none') {
            quantityControls.style.display = 'block';
            details.style.display = 'none';
            button.textContent = '<?php _e('Cancel', 'loomaama'); ?>';
        } else {
            quantityControls.style.display = 'none';
            details.style.display = 'block';
            button.textContent = '<?php _e('Modify', 'loomaama'); ?>';
        }
    }

    // Cart quantity updates and drawer refreshes are defined once in footer.php.
    // Keeping a single implementation prevents older AJAX callbacks from
    // overriding the current cart state logic.

    // Reinitialize cart drawer
    function reinitializeCartDrawer() {
        // Reinitialize promo bar slider
        const promoSlides = document.querySelectorAll('.promo-bar__slide');
        if (promoSlides.length > 1) {
            let currentSlide = 0;
            
            promoSlides.forEach((slide, index) => {
                slide.classList.remove('active');
                if (index === 0) slide.classList.add('active');
            });
            
            if (window.promoInterval) clearInterval(window.promoInterval);
            
            window.promoInterval = setInterval(() => {
                promoSlides[currentSlide].classList.remove('active');
                currentSlide = (currentSlide + 1) % promoSlides.length;
                promoSlides[currentSlide].classList.add('active');
            }, 3000);
        }
    }

    // Show notification
    function showNotification(message, type = 'success') {
        const existing = document.querySelector('.cart-notification');
        if (existing) existing.remove();
        
        const notification = document.createElement('div');
        notification.className = `cart-notification cart-notification--${type}`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#4CAF50' : '#f44336'};
            color: white;
            padding: 12px 20px;
            border-radius: 4px;
            z-index: 10000;
            animation: slideIn 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

</script>

<!-- Search Drawer -->
<aside id="search-drawer" class="search-drawer">
    <div class="drawer__inner">
        <div class="drawer__header">
            <h5 class="drawer__heading headline"><?php _e('Search', 'loomaama'); ?></h5>
            <button class="drawer__close" onclick="toggleSearch()">✕</button>
        </div>

        <div class="drawer__body">
            <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="search-drawer-form">
                <div class="search-input-wrapper position-relative">
                    <input type="text" 
                        id="search-drawer-input"
                        class="form-control" 
                        placeholder="<?php esc_attr_e('Search products...', 'loomaama'); ?>" 
                        value="<?php echo get_search_query(); ?>" 
                        name="s"
                        autocomplete="off">
                    
                    <div id="search-suggestions-container" class="list-group position-absolute w-100 mt-1 shadow-sm" style="z-index: 999; display:none;"></div>
                </div>

                <input type="hidden" name="post_type" value="product">
                <button type="submit" class="btn btn-dark w-100 mt-3"><?php _e('Search', 'loomaama'); ?></button>
            </form>
            
            <!-- Live search results will appear here -->
            <div class="drawer_search_reasult">
                <!-- Results dynamically loaded here -->
            </div>
        </div>
    </div>
</aside>
<aside id="side-menu">
    <div class="d-flex justify-content-between align-items-center mb-5 mobile-header">
      <h2 class="headline"> <a href="<?php echo esc_url(home_url('/')); ?>" class="brand-logo headline position-absolute d-flex justify-content-center w-100">
                <?php if (has_custom_logo()) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <img src="<?php echo esc_url(LOOMAAMA_URI . '/assets/images/logo.png'); ?>" height="50" alt="<?php bloginfo('name'); ?>">
                <?php endif; ?>
            </a></h2>
      <button onclick="toggleMenu()" class="btn btn-sm btn-outline-dark">✕</button>
    </div>

    <nav class="d-flex flex-column gap-4 fs-4 headline h-100">
          <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'items_wrap'     => '%3$s',
                    'walker'         => class_exists('Bootstrap_Mega_Menu_Walker') ? new Bootstrap_Mega_Menu_Walker() : null,
                ) );
                ?>
            </nav>
</aside>

<!-- Header -->
<header class="main-header position-fixed top-0 w-100 py-xl-0 py-3 z-3">
    <div class="container-fluid px-4 px-lg-5">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Left Navigation (Desktop) -->
            <div class="d-none d-xl-flex align-items-center gap-4 nav-top">
               <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'items_wrap'     => '%3$s',
                    'walker'         => class_exists('Bootstrap_Mega_Menu_Walker') ? new Bootstrap_Mega_Menu_Walker() : null,
                ) );
                ?>
            </div>

            <button onclick="toggleMenu()" class="menu-btn d-flex align-items-center d-xl-none gap-3">
                <span class="hamburger-lines">
                    <span></span>
                    <span></span>
                </span>
                <?php _e('Menu', 'loomaama'); ?>
            </button>

            <!-- Center Logo -->
            <a href="<?php echo esc_url(home_url('/')); ?>" class="brand-logo headline position-absolute d-flex justify-content-center w-100">
                <?php if (has_custom_logo()) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <img src="<?php echo esc_url(LOOMAAMA_URI . '/assets/images/logo.png'); ?>" height="50" alt="<?php bloginfo('name'); ?>">
                <?php endif; ?>
            </a>

            <!-- Right Icons -->
            <div class="d-flex align-items-center gap-3 gap-xl-4 nav-top">
                <a href="#" class="nav-link-top left-main" onclick="toggleSearch(); return false;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </a>
                <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" class="nav-link-top left-main">
                    <i class="fa-solid fa-user"></i>
                </a>
                <a href="#" class="nav-link-top left-main" onclick="toggleCart(); return false;">
                    <i class="fa-solid fa-cart-shopping"></i>
                   
                        <?php
                        $header_cart_count = (class_exists('WooCommerce') && WC()->cart)
                            ? WC()->cart->get_cart_contents_count()
                            : 0;
                        ?>
                        <span class="cart-count" id="header-cart-count"
                            <?php echo $header_cart_count > 0 ? '' : 'style="display:none;"'; ?>>
                            <?php echo esc_html($header_cart_count); ?>
                        </span>
                    
                </a>
            </div>
        </div>
    </div>
</header>

