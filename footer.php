<footer class="site-footer" role="contentinfo">
    <div class="footer-container">
        <!-- Top Section: Brand & Newsletter -->
        <div class="footer-top">
            <div class="footer-brand">
                <h2 class="footer-logo"><?php echo esc_html(get_theme_mod('footer_brand_name', 'Loomaama')); ?></h2>
                <p class="footer-tagline"><?php echo esc_html(get_theme_mod('footer_tagline', 'Nature, softened for your skin.')); ?></p>
                <p class="footer-description">
                    <?php echo esc_html(get_theme_mod('footer_description', 'Clothing from 100% Wild Himalayan Nettle, shaped slowly by hand with respect for fibre, people, and earth.')); ?>
                </p>
            </div>

            <div class="footer-newsletter">
                <h3><?php echo esc_html(get_theme_mod('footer_newsletter_title', 'Become a Witness')); ?></h3>
                <p><?php echo esc_html(get_theme_mod('footer_newsletter_text', 'Receive quiet notes from Loomaama: material stories, artisan updates, care guidance, and first access to new pieces.')); ?></p>
                
                <?php if (function_exists('mc4wp_show_form')) : ?>
                    <?php mc4wp_show_form(); ?>
                <?php else : ?>
                    <form id="subscriptionForm" class="newsletter-form" method="POST">
                        <input type="email" id="subscriberEmail" placeholder="<?php esc_attr_e('Email address', 'loomaama'); ?>" required />
                        <button type="submit" class="btn-join"><?php _e('Join the Circle', 'loomaama'); ?></button>
                    </form>
                    <div id="messageContainer" class="message-container"></div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Middle Section: Navigation -->
        <div class="footer-middle">
            <?php if (is_active_sidebar('footer-1')) : ?>
                <div class="footer-column">
                    <?php dynamic_sidebar('footer-1'); ?>
                </div>
            <?php else : ?>
                <div class="footer-column">
                    <h4><?php _e('Material Standard', 'loomaama'); ?></h4>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer-1',
                        'container' => false,
                        'fallback_cb' => false,
                        'depth' => 1,
                    ));
                    ?>
                </div>
            <?php endif; ?>

            <?php if (is_active_sidebar('footer-2')) : ?>
                <div class="footer-column">
                    <?php dynamic_sidebar('footer-2'); ?>
                </div>
            <?php else : ?>
                <div class="footer-column">
                    <h4><?php _e('Client Care', 'loomaama'); ?></h4>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer-2',
                        'container' => false,
                        'fallback_cb' => false,
                        'depth' => 1,
                    ));
                    ?>
                </div>
            <?php endif; ?>

            <?php if (is_active_sidebar('footer-3')) : ?>
                <div class="footer-column">
                    <?php dynamic_sidebar('footer-3'); ?>
                </div>
            <?php else : ?>
                <div class="footer-column">
                    <h4><?php _e('World', 'loomaama'); ?></h4>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer-3',
                        'container' => false,
                        'fallback_cb' => false,
                        'depth' => 1,
                    ));
                    ?>
                </div>
            <?php endif; ?>

            <div class="footer-column">
                <h4><?php _e('Follow', 'loomaama'); ?></h4>
                <ul class="social-links">
                    <?php
                    $social_links = array(
                        'instagram' => get_theme_mod('footer_instagram', '#'),
                        'pinterest' => get_theme_mod('footer_pinterest', '#'),
                        'tiktok' => get_theme_mod('footer_tiktok', '#'),
                        'email' => get_theme_mod('footer_email', '#'),
                    );
                    foreach ($social_links as $platform => $url) :
                        if (!empty($url) && $url !== '#') :
                    ?>
                        <li><a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html(ucfirst($platform)); ?></a></li>
                    <?php endif; endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="footer-bottom">
            <p class="copyright">
                <?php echo wp_kses_post(get_theme_mod('copyright_text', '&copy; 2026 Loomaama. All rights reserved.')); ?>
            </p>
            
            <?php if (has_nav_menu('footer-legal')) : ?>
                <nav class="footer-legal-nav">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer-legal',
                        'container' => false,
                        'menu_class' => 'legal-links',
                        'fallback_cb' => false,
                        'depth' => 1,
                    ));
                    ?>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</footer>

<div id="scrollTopBtn" aria-label="<?php esc_attr_e('Scroll to top', 'loomaama'); ?>">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="18 15 12 9 6 15"></polyline>
    </svg>
</div>

<?php wp_footer(); ?>


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

    // Shared AJAX configuration for the custom cart drawer.
    const loomaamaCartAjax = {
        url: <?php echo wp_json_encode(admin_url('admin-ajax.php')); ?>,
        nonce: <?php echo wp_json_encode(wp_create_nonce('loomaama_cart_nonce')); ?>
    };

    // Keep the header badge and drawer heading in sync. The header badge always
    // exists in the markup and is only visually hidden when the count is zero.
    function updateHeaderCartCount(count) {
        const cartCount = Math.max(0, parseInt(count, 10) || 0);
        let headerCartCount = document.getElementById('header-cart-count');

        if (!headerCartCount) {
            const cartTrigger = document.querySelector('a[onclick*="toggleCart"]');
            if (cartTrigger) {
                headerCartCount = document.createElement('span');
                headerCartCount.id = 'header-cart-count';
                headerCartCount.className = 'cart-count';
                cartTrigger.appendChild(headerCartCount);
            }
        }

        document.querySelectorAll('.cart-count, #header-cart-count, .cart-icon .count').forEach(function(element) {
            element.textContent = cartCount;
            element.style.display = cartCount > 0 ? '' : 'none';
        });

        document.querySelectorAll('.cart-count-drawer, .drawer__heading .cart-count-drawer').forEach(function(element) {
            element.textContent = cartCount;
            element.style.display = '';
        });

        const cartTrigger = document.querySelector('a[onclick*="toggleCart"]');
        if (cartTrigger) {
            cartTrigger.setAttribute('aria-label', '<?php echo esc_js(__('Cart', 'loomaama')); ?> (' + cartCount + ')');
        }
    }

    function applyCartDrawerPayload(payload) {
        if (!payload) return;

        const cartItemsContainer = document.getElementById('cart-drawer-items');
        const footerContainer = document.getElementById('cart-drawer-footer');

        if (cartItemsContainer && typeof payload.items_html === 'string') {
            cartItemsContainer.innerHTML = payload.items_html;
        }

        if (footerContainer && typeof payload.footer_html === 'string') {
            footerContainer.innerHTML = payload.footer_html;
        }

        if (typeof payload.cart_count !== 'undefined') {
            updateHeaderCartCount(payload.cart_count);
        }

        reinitializeCartDrawer();
    }

    function cartAjaxRequest(action, fields) {
        const formData = new URLSearchParams();
        formData.append('action', action);
        formData.append('security', loomaamaCartAjax.nonce);

        Object.keys(fields || {}).forEach(function(key) {
            formData.append(key, fields[key]);
        });

        return fetch(loomaamaCartAjax.url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            },
            body: formData.toString()
        }).then(function(response) {
            return response.json().catch(function() {
                throw new Error('The cart server returned an invalid response.');
            }).then(function(data) {
                if (!response.ok || !data.success) {
                    const message = data && data.data && data.data.message
                        ? data.data.message
                        : '<?php echo esc_js(__('The cart could not be updated.', 'loomaama')); ?>';
                    throw new Error(message);
                }
                return data.data;
            });
        });
    }

    // Update or remove one drawer item. The legacy nonce argument is retained
    // for existing inline buttons, but all requests use the shared cart nonce.
    function updateCartQuantity(cartItemKey, action, nonce) {
        const cartItem = Array.from(document.querySelectorAll('[data-cart-item-key]')).find(function(item) {
            return item.getAttribute('data-cart-item-key') === cartItemKey;
        });

        if (!cartItem) {
            console.error('Cart item not found:', cartItemKey);
            return;
        }

        const displayQuantity = cartItem.querySelector('.item-quantity');
        const currentQuantity = Math.max(1, parseInt(displayQuantity ? displayQuantity.textContent : '1', 10) || 1);
        let quantity = currentQuantity;

        if (action === 'add') quantity = currentQuantity + 1;
        if (action === 'remove') quantity = Math.max(1, currentQuantity - 1);
        if (action === 'delete') quantity = 0;

        cartItem.classList.add('is-updating');
        cartItem.style.position = 'relative';

        if (!cartItem.querySelector('.cart-item-loading')) {
            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'cart-item-loading';
            loadingDiv.innerHTML = '<div class="spinner"></div>';
            cartItem.appendChild(loadingDiv);
        }

        cartItem.querySelectorAll('button').forEach(function(button) {
            button.disabled = true;
        });

        cartAjaxRequest('loomaama_update_cart_item', {
            cart_item_key: cartItemKey,
            quantity: quantity
        }).then(function(payload) {
            applyCartDrawerPayload(payload);

            if (window.jQuery) {
                window.jQuery(document.body).trigger('wc_fragment_refresh');
                window.jQuery(document).trigger('cart_updated', [payload.cart_count]);
            }
        }).catch(function(error) {
            console.error('Cart update failed:', error);
            cartItem.classList.remove('is-updating');
            const loader = cartItem.querySelector('.cart-item-loading');
            if (loader) loader.remove();
            cartItem.querySelectorAll('button').forEach(function(button) {
                button.disabled = false;
            });
            showNotification(error.message || '<?php echo esc_js(__('Error updating cart', 'loomaama')); ?>', 'error');
        });
    }

    // Reload the custom drawer after an add-to-cart operation or when another
    // component changes the WooCommerce cart.
    function refreshCartDrawer(options) {
        options = options || {};

        return cartAjaxRequest('loomaama_get_cart_state', {}).then(function(payload) {
            applyCartDrawerPayload(payload);
            return payload;
        }).catch(function(error) {
            console.error('Error refreshing cart:', error);
            if (!options.silent) {
                showNotification(error.message || '<?php echo esc_js(__('Error updating cart', 'loomaama')); ?>', 'error');
            }
            throw error;
        });
    }

    window.updateCartQuantity = updateCartQuantity;
    window.refreshCartDrawer = refreshCartDrawer;
    window.updateHeaderCartCount = updateHeaderCartCount;

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

    // Toggle cart drawer
    function toggleCart() {
        const cartDrawer = document.getElementById('cart-drawer');
        const overlay = document.getElementById('overlay');
        
        if (cartDrawer) {
            cartDrawer.classList.toggle('active');
            if (overlay) {
                overlay.classList.toggle('active');
            }
            document.body.classList.toggle('cart-open');
        }
    }

    // Toggle search drawer
     function toggleSearch() {
        const searchDrawer = document.getElementById('search-drawer');
        const overlay = document.getElementById('overlay');
        
        if (searchDrawer) {
            searchDrawer.classList.toggle('active');
            if (overlay) {
                overlay.classList.toggle('active');
            }
            
             document.body.classList.toggle('search-open');
        }
    }
   // Toggle menu
    function toggleMenu() {
        const cartDrawer =document.body.classList.toggle("menu-open");
        
    }
    // Close all panels
    function closeAllPanels() {
        document.getElementById('cart-drawer')?.classList.remove('active');
        document.getElementById('search-drawer')?.classList.remove('active');
        document.getElementById('overlay')?.classList.remove('active');
        document.body.classList.remove('cart-open');
    }



  


</script>

<script>

<?php if (!is_product() && (is_single() || is_page_template( '/page-template/template-front-page.php' ))): ?>
const adjustSectionPadding = () => {
  const nav = document.querySelector('header.main-header'); 
  const firstSection = document.querySelector('main'); 
  
  if (nav && firstSection) {
    const navHeight = nav.offsetHeight;
    // Using setProperty to guarantee the !important priority
    firstSection.style.setProperty('padding-top', `${navHeight}px`, 'important');
  }
};

document.addEventListener('DOMContentLoaded', adjustSectionPadding);
window.addEventListener('resize', adjustSectionPadding);
<?php endif; ?>
    document.addEventListener('DOMContentLoaded', function() {
    // Newsletter form submission
    const form = document.getElementById('subscriptionForm');
    const emailInput = document.getElementById('subscriberEmail');
    const messageContainer = document.getElementById('messageContainer');

    if (form && emailInput && messageContainer) {
        function showMessage(message, type = 'success') {
            const messageDiv = document.createElement('div');
            messageDiv.className = `${type}-message`;
            messageDiv.textContent = message;
            
            messageContainer.innerHTML = '';
            messageContainer.appendChild(messageDiv);
            
            if (type === 'success') {
                setTimeout(() => {
                    messageDiv.style.opacity = '0';
                    messageDiv.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => {
                        if (messageDiv.parentNode) {
                            messageDiv.remove();
                        }
                    }, 500);
                }, 5000);
            }
        }

        function validateEmail(email) {
            return /^[^\s@]+@([^\s@.,]+\.)+[^\s@.,]{2,}$/.test(email);
        }

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = emailInput.value.trim();
            
            if (!email) {
                showMessage('<?php esc_attr_e('Please enter your email address.', 'loomaama'); ?>', 'error');
                emailInput.focus();
                return;
            }
            
            if (!validateEmail(email)) {
                showMessage('<?php esc_attr_e('Please enter a valid email address.', 'loomaama'); ?>', 'error');
                emailInput.focus();
                return;
            }
            
            const formData = new URLSearchParams();
            formData.append('action', 'loomaama_subscribe');
            formData.append('email', email);
            formData.append('nonce', '<?php echo wp_create_nonce('loomaama_subscribe_nonce'); ?>');
            
            try {
                const response = await fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showMessage(data.data.message || '<?php esc_attr_e('Thank you for subscribing!', 'loomaama'); ?>', 'success');
                    emailInput.value = '';
                } else {
                    showMessage(data.data.message || '<?php esc_attr_e('Something went wrong. Please try again.', 'loomaama'); ?>', 'error');
                }
            } catch (error) {
                showMessage('<?php esc_attr_e('Network error. Please check your connection and try again.', 'loomaama'); ?>', 'error');
            }
        });
    }

    // Scroll to top button
    const scrollBtn = document.getElementById('scrollTopBtn');
    if (scrollBtn) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                scrollBtn.classList.add('visible');
            } else {
                scrollBtn.classList.remove('visible');
            }
        });

        scrollBtn.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // Promo bar slider (if exists)
    const promoSlides = document.querySelectorAll('.promo-bar__slide');
    if (promoSlides.length > 1) {
        let currentSlide = 0;
        
        setInterval(() => {
            promoSlides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % promoSlides.length;
            promoSlides[currentSlide].classList.add('active');
        }, 3000);
    }
});
</script>

<?php 
global $wp;
$current_url = home_url(add_query_arg(array(), $wp->request));
?>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
   "name": "100% Wild Himalayan Nettle",
  "url": "<?php echo esc_url($current_url); ?>"
}
</script>

</style>
</body>
</html>