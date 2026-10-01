<?php
/**
 * 404 Not Found Template
 *
 * @package Loomaama
 */

get_header();
?>

<!-- 404 Error Section -->
<section class="error-404">
    <div class="error-content">
        
        <!-- Optional SVG Illustration -->
        <div class="error-illustration">
            <svg width="150" height="150" viewBox="0 0 150 150" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="75" cy="75" r="70" stroke="#C2B29F" stroke-width="3" fill="none"/>
                <text x="75" y="85" text-anchor="middle" font-size="50" font-weight="bold" fill="#8B7355" font-family="Work Sans, sans-serif">404</text>
                <path d="M45,95 Q75,110 105,95" stroke="#C2B29F" stroke-width="3" fill="none" stroke-linecap="round"/>
                <circle cx="55" cy="65" r="5" fill="#8B7355"/>
                <circle cx="95" cy="65" r="5" fill="#8B7355"/>
            </svg>
        </div>
        
        <!-- 404 Number -->
        <div class="error-number">404</div>
        
        <!-- Error Title -->
        <h1 class="error-title">
            <?php esc_html_e('Page Not Found', 'loomaama'); ?>
        </h1>
        
        <!-- Error Message -->
        <p class="error-message">
            <?php esc_html_e('Oops! The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'loomaama'); ?>
        </p>
        
        <!-- Search Form - Version 1: With Search Button Inside Input -->
        <div class="search-form">
            <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                <div class="search-input-wrapper">
                    <input type="search" 
                           class="search-input" 
                           name="s" 
                           placeholder="<?php esc_attr_e('Search for products...', 'loomaama'); ?>" 
                           value="<?php echo get_search_query(); ?>"
                           aria-label="<?php esc_attr_e('Search', 'loomaama'); ?>">
                    <button type="submit" class="search-button" aria-label="<?php esc_attr_e('Search', 'loomaama'); ?>">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Alternative Search Form - Version 2: Input Group with Button Outside -->
        <!-- Uncomment this if you prefer this style instead -->
        <!--
        <div class="input-group">
            <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" style="display: flex; gap: 10px; width: 100%;">
                <input type="search" 
                       class="search-input" 
                       name="s" 
                       placeholder="<?php esc_attr_e('Search for products...', 'loomaama'); ?>" 
                       value="<?php echo get_search_query(); ?>"
                       aria-label="<?php esc_attr_e('Search', 'loomaama'); ?>">
                <button type="submit" class="search-button">
                    <?php esc_html_e('Search', 'loomaama'); ?>
                </button>
            </form>
        </div>
        -->
        
        <!-- Home Button -->
        <a href="<?php echo esc_url(home_url('/')); ?>" class="home-button">
            <i class="fas fa-arrow-left"></i>
            <?php esc_html_e('Back to Homepage', 'loomaama'); ?>
        </a>
        
        <!-- Popular Sections -->
        <div class="popular-sections">
            <h5 class="popular-title">
                <?php esc_html_e('Popular Categories', 'loomaama'); ?>
            </h5>
            
            <div class="categories-grid">
                <?php
                // Get product categories
                $categories = get_terms(array(
                    'taxonomy' => 'product_cat',
                    'hide_empty' => true,
                    'number' => 6,
                    'orderby' => 'count',
                    'order' => 'DESC'
                ));
                
                if (!empty($categories) && !is_wp_error($categories)) {
                    foreach ($categories as $category) {
                        ?>
                        <a href="<?php echo esc_url(get_term_link($category)); ?>" class="category-link">
                            <?php echo esc_html($category->name); ?>
                        </a>
                        <?php
                    }
                } else {
                    // Fallback categories if none exist
                    ?>
                    <a href="<?php echo esc_url(home_url('/shop')); ?>" class="category-link">
                        <?php esc_html_e('Shop All', 'loomaama'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/product-category/new-arrivals')); ?>" class="category-link">
                        <?php esc_html_e('New Arrivals', 'loomaama'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/product-category/bestsellers')); ?>" class="category-link">
                        <?php esc_html_e('Bestsellers', 'loomaama'); ?>
                    </a>
                    <?php
                }
                ?>
            </div>
            
            <div class="help-links">
                <a href="<?php echo esc_url(home_url('/shop')); ?>" class="help-link">
                    <i class="fas fa-store"></i> 
                    <?php esc_html_e('Visit Shop', 'loomaama'); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/contact-us')); ?>" class="help-link">
                    <i class="fas fa-envelope"></i> 
                    <?php esc_html_e('Contact Support', 'loomaama'); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/faq')); ?>" class="help-link">
                    <i class="fas fa-question-circle"></i> 
                    <?php esc_html_e('FAQ', 'loomaama'); ?>
                </a>
                <?php if (class_exists('WooCommerce')) : ?>
                <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="help-link">
                    <i class="fas fa-shopping-cart"></i> 
                    <?php esc_html_e('View Cart', 'loomaama'); ?>
                </a>
                <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" class="help-link">
                    <i class="fas fa-user"></i> 
                    <?php esc_html_e('My Account', 'loomaama'); ?>
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Optional: Recent Products Section (Uncomment if needed) -->
        <!--
        <div class="popular-sections">
            <h5 class="popular-title">
                <?php esc_html_e('Recent Products', 'loomaama'); ?>
            </h5>
            <div class="recent-products-grid">
                <?php
                $recent_products = wc_get_products(array(
                    'limit' => 4,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'status' => 'publish',
                ));
                
                if (!empty($recent_products)) {
                    foreach ($recent_products as $product) {
                        ?>
                        <a href="<?php echo esc_url($product->get_permalink()); ?>" class="recent-product-link">
                            <?php echo esc_html($product->get_name()); ?>
                        </a>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
        -->
        
    </div>
</section>

<?php
get_footer();