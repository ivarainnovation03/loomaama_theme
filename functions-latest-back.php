<?php
/**
 * Loomaama Theme Functions
 *
 * @package Loomaama
 */

// Define theme constants
define('LOOMAAMA_VERSION', '3.2.9');
define('LOOMAAMA_DIR', get_template_directory());
define('LOOMAAMA_URI', get_template_directory_uri());


/**
 * WooCommerce Setup
 */
add_action('after_setup_theme', 'loomaama_load_woocommerce_setup');
function loomaama_load_woocommerce_setup() {
    if (class_exists('WooCommerce')) {
        require LOOMAAMA_DIR . '/inc/woocommerce-setup.php';
    }
}

/**
 * Theme Setup
 */
require LOOMAAMA_DIR . '/inc/theme-setup.php';

/**
 * Customizer Settings
 */
require LOOMAAMA_DIR . '/inc/customizer.php';

/**
 * Widget Areas
 */
require_once LOOMAAMA_DIR . '/inc/widget-areas.php';
require_once LOOMAAMA_DIR . '/inc/journey-widgets.php';


/**
 * Bootstrap Navwalker
 */
require LOOMAAMA_DIR . '/inc/WP_Bootstrap_Navwalker.php';


function loomaama_journey_widgets_register_assets() {
    $css_file = LOOMAAMA_DIR . '/assets/css/journey-widgets.css';
    $js_file  = LOOMAAMA_DIR . '/assets/js/journey-widgets.js';

    // Use file modification times so theme, browser and CDN caches refresh
    // automatically whenever either Journey asset is replaced.
    //$css_version = file_exists( $css_file ) ? LOOMAAMA_VERSION :(string) filemtime( $css_file ) ;
    //$js_version  = file_exists( $js_file ) ? LOOMAAMA_VERSION : (string) filemtime( $js_file );

    wp_register_style(
        'loomaama-journey-widgets',
        LOOMAAMA_URI . '/assets/css/journey-widgets.css',
        array( 'loomaama-main' ),
        LOOMAAMA_VERSION
    );

    wp_register_script(
        'loomaama-journey-widgets',
        LOOMAAMA_URI . '/assets/js/journey-widgets.js',
        array(),
        LOOMAAMA_VERSION,
        true
    );

    /*
     * Load through wp_enqueue_scripts rather than relying only on
     * is_active_widget(). Some builders, Customizer partials and custom
     * dynamic-sidebar implementations are not reliably detected early enough
     * for a stylesheet to be printed in wp_head().
     */
    wp_enqueue_style( 'loomaama-journey-widgets' );
    wp_enqueue_script( 'loomaama-journey-widgets' );
}
add_action( 'wp_enqueue_scripts', 'loomaama_journey_widgets_register_assets', 30 );
/**
 * Enqueue Scripts and Styles
 */
function loomaama_enqueue_scripts() {
    // Google Fonts
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Work+Sans:wght@500;600&display=swap', array(), null);
    
    // Font Awesome
    wp_enqueue_style('font-awesome', LOOMAAMA_URI . '/assets/vendor/fontawesome/css/all.min.css', array(), '6.0.0');
    
    // Bootstrap
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', array(), '5.3.3');
    
    // Swiper
    wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.0.0');
    // AOS
    wp_enqueue_style('aos', LOOMAAMA_URI . '/assets/css/aos.min.css', array(), '2.3.1');
    
    // PhotoSwipe (for product gallery)
    if (is_product()) {
        wp_enqueue_style('photoswipe', 'https://unpkg.com/photoswipe@5.4.4/dist/photoswipe.css', array(), '5.4.4');
    }
    
    // Main Theme CSS (core styles only)
    wp_enqueue_style('loomaama-main', LOOMAAMA_URI . '/assets/css/main.css', array(), LOOMAAMA_VERSION);
    wp_enqueue_style('parallax-css', LOOMAAMA_URI . '/assets/css/jarallax.min.css', array(), '3.0.0');
    
    // Page-specific CSS
    if (is_shop() || is_product_category() || is_product_tag()||  is_single()|| is_archive() || is_front_page()) {
        wp_enqueue_style('loomaama-shop', LOOMAAMA_URI . '/assets/css/shop.css', array('loomaama-main'), LOOMAAMA_VERSION);
    }
    
    if (is_product()) {
        wp_enqueue_style('loomaama-product', LOOMAAMA_URI . '/assets/css/product.css', array('loomaama-main'), LOOMAAMA_VERSION);
    }
    
    if (is_single()) {
        wp_enqueue_style('loomaama-post', LOOMAAMA_URI . '/assets/css/post.css', array('loomaama-main'), LOOMAAMA_VERSION);
    }
    
    if (is_checkout()) {
        wp_enqueue_style('loomaama-checkout', LOOMAAMA_URI . '/assets/css/checkout.css', array('loomaama-main'), LOOMAAMA_VERSION);
    }
    
    if (is_singular('artisan')|| is_archive('artisan')) {
        wp_enqueue_style('loomaama-artisan', LOOMAAMA_URI . '/assets/css/artisan.css', array('loomaama-main'), LOOMAAMA_VERSION);
    }
    
    if (is_front_page()) {
        wp_enqueue_style('loomaama-hero', LOOMAAMA_URI . '/assets/css/hero.css', array('loomaama-main'), LOOMAAMA_VERSION);
    }
    
    // Always load drawers and misc (they're used across the site)
    wp_enqueue_style('loomaama-custom-drawers', LOOMAAMA_URI . '/assets/css/custom-drawers.css', array('loomaama-main'), LOOMAAMA_VERSION);
    wp_enqueue_style('loomaama-misc', LOOMAAMA_URI . '/assets/css/misc.css', array('loomaama-main'), LOOMAAMA_VERSION);
    
    // Template Page specific css
    $page_templates = array(
        'page-template/page-home.php'         => 'page-home',
        'page-template/page-shop.php'         => 'page-shop',
        'page-template/page-product.php'      => 'page-product',
        'page-template/page-care-guide.php'         => 'page-care-guide',
        'page-template/page-packaging.php'     => 'page-packaging',
        'page-template/page-social-contract.php'    => 'page-social-contract',
        'page-template/page-archive.php'            => 'page-archive',
         'page-template/page-artisans.php'           => 'page-artisans',
        'page-template/page-forest.php'             => 'page-forest',
        'page-template/page-about.php'              => 'page-about',
		'page-template/page-the-making.php'        => 'page-the-making',
        'page-template/page-material-standard.php' => 'page-material-standard',
        'page-template/page-impact.php' => 'page-impact',
		'page-template/page-our-standard.php'       => 'page-our-standard',
		'page-template/page-size-fit-guide.php'       => 'page-size-fit-guide',
		'page-template/page-shipping-returns.php'       => 'page-shipping-returns',
 		'page-template/page-faq.php'                 => 'page-faq',
        'page-template/page-contact-us.php'          => 'page-contact-us',
		 'page-template/page-loomaama-archive.php'    => 'page-loomaama-archive',
    );

    foreach ($page_templates as $template_filename => $css_file) {
        if (is_page_template($template_filename)) {
            wp_enqueue_style('loomaama-' . $css_file, LOOMAAMA_URI . '/assets/css/' . $css_file . '.css', array('loomaama-main'), LOOMAAMA_VERSION);
            break;
        }
    }
    
    
    // jQuery
    wp_enqueue_script('jquery');
    
    // Bootstrap JS
    wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.3.3', true);
    
    // Swiper
    wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0.0', true);

    // parallex
    wp_enqueue_script('parallex',  LOOMAAMA_URI . '/assets/js/jarallax.min.js', array('jquery'), '3.0.0', true);
    wp_enqueue_script('jarallax-video', 'https://cdn.jsdelivr.net/npm/jarallax@3/dist/jarallax-video.min.js', array('parallex'), '3.0.0', true);
    // AOS
    wp_enqueue_script('aos', LOOMAAMA_URI . '/assets/js/aos.min.js', array(), '2.3.1', true);
    // PhotoSwipe
   if (is_product()) {
    // Required CSS for the lightbox to display
    wp_enqueue_style('photoswipe-css',  LOOMAAMA_URI . '/assets/css/glightbox.min.css', array(), '5.4.4');
    wp_enqueue_script('photoswipe-lightbox',  LOOMAAMA_URI . '/assets/js/glightbox.min.js', array(), '5.4.4', true);
   }
    
    // Customizer preview
    if (is_customize_preview()) {
        wp_enqueue_script('loomaama-customizer', LOOMAAMA_URI . '/assets/js/customizer.js', array('jquery', 'customize-preview'), LOOMAAMA_VERSION, true);
    }
    if (is_page_template('page-template/page-social-contract.php') || is_page_template('page-template/page-forest.php')) {
        wp_enqueue_script('loomaama-social-contract-counter', LOOMAAMA_URI . '/assets/js/jscounter.min.js', array('loomaama-main'), '1.0.0', true);
      
    }
    
    // Main Theme JS
    wp_enqueue_script('loomaama-main', LOOMAAMA_URI . '/assets/js/main.js', array('jquery', 'bootstrap', 'swiper', 'parallex'), LOOMAAMA_VERSION, true);
    wp_enqueue_script('photoswipe-lightbox',  LOOMAAMA_URI . '/assets/vendor/fontawesome/js/all.min.js', array(), '5.4.4', true);
    // Product Gallery JS
    if (is_product()) {
        wp_enqueue_script('loomaama-product-gallery', LOOMAAMA_URI . '/assets/js/product-gallery.js', array('jquery', 'photoswipe', 'photoswipe-lightbox'), LOOMAAMA_VERSION, true);
    }
    
    // Localize script for AJAX
    wp_localize_script('loomaama-main', 'loomaama_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('loomaama_nonce')
    ));

     if (is_404()) {
        // Enqueue 404 CSS
        wp_enqueue_style('loomaama-404', LOOMAAMA_URI . '/assets/css/404.css', array(), LOOMAAMA_VERSION);
     }
}
add_action('wp_enqueue_scripts', 'loomaama_enqueue_scripts');


/**
 * Add custom image sizes
 */
function loomaama_add_image_sizes() {
    add_image_size('loomaama-hero', 1920, 1080, true);
    add_image_size('loomaama-product-large', 800, 1000, true);
    add_image_size('loomaama-product-medium', 600, 750, true);
    add_image_size('loomaama-product-small', 400, 500, true);
    add_image_size('loomaama-category', 600, 400, true);
}
add_action('after_setup_theme', 'loomaama_add_image_sizes');

/**
 * Register Navigation Menus
 */
function loomaama_register_menus() {
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'loomaama'),
        'primary-secondary' => __('Primary Secondary Menu', 'loomaama'),
        'mobile' => __('Mobile Menu', 'loomaama'),
        'footer' => __('Footer Menu', 'loomaama'),
    ));
}
add_action('init', 'loomaama_register_menus');

/**
 * Add custom classes to body
 */
function loomaama_body_classes($classes) {
    if (is_front_page()) {
        $classes[] = 'front-page';
    }
    
    if (is_shop() || is_product_category() || is_product_tag()) {
        $classes[] = 'shop-page';
    }
    
    if (is_product()) {
        $classes[] = 'single-product-page';
    }
    
    return $classes;
}
add_filter('body_class', 'loomaama_body_classes');

/**
 * AJAX handler for loading more products
 */
function loomaama_load_more_products() {
    check_ajax_referer('loomaama_nonce', 'nonce');
    
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 12,
        'paged' => $page,
        'post_status' => 'publish'
    );
    
    if (!empty($category)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $category
            )
        );
    }
    
    $products = new WP_Query($args);
    
    if ($products->have_posts()) {
        ob_start();
        while ($products->have_posts()) {
            $products->the_post();
            wc_get_template_part('content', 'product');
        }
        $html = ob_get_clean();
        
        wp_send_json_success(array(
            'html' => $html,
            'has_more' => $products->max_num_pages > $page
        ));
    } else {
        wp_send_json_error('No more products');
    }
    
    wp_die();
}
add_action('wp_ajax_loomaama_load_more', 'loomaama_load_more_products');
add_action('wp_ajax_nopriv_loomaama_load_more', 'loomaama_load_more_products');

/**
 * Customize checkout field arguments to add CSS classes
 */
function loomaama_checkout_field_args($args, $key, $value) {
    // Add custom classes to form rows
    $args['class'][] = 'form-row-custom';
    
    // Add input class
    $args['input_class'][] = 'form-input';
    
    // Add label class
    $args['label_class'][] = 'form-label';
    
    return $args;
}


/**
 * ACF Fields Initialization
 */


require_once LOOMAAMA_DIR . '/inc/scf-init.php';
require_once LOOMAAMA_DIR . '/inc/my-account-customizer.php';
require_once LOOMAAMA_DIR . '/inc/subcription-funcitons.php';



/**
 * Force WooCommerce to use theme templates for My Account pages
 * Only use plugin templates as fallback when theme templates don't exist
 */
add_filter('woocommerce_locate_template', 'loomaama_override_myaccount_templates', 20, 3);
function loomaama_override_myaccount_templates($template, $template_name, $template_path) {
    // Only override My Account templates
    if (strpos($template_name, 'myaccount/') === 0) {
        // Check for template in child theme first
        $child_theme_template = get_stylesheet_directory() . '/woocommerce/' . $template_name;
        if (file_exists($child_theme_template)) {
            return $child_theme_template;
        }
        
        // Then check parent theme
        $parent_theme_template = get_template_directory() . '/woocommerce/' . $template_name;
        if (file_exists($parent_theme_template)) {
            return $parent_theme_template;
        }
        
        // Finally check root theme directory
        $root_theme_template = get_template_directory() . '/' . basename($template_name);
        if (file_exists($root_theme_template)) {
            return $root_theme_template;
        }
        
        // Only use plugin template as last resort
        $default_template = WC()->plugin_path() . '/templates/' . $template_name;
        if (file_exists($default_template)) {
            return $default_template;
        }
    }
    return $template;
}


/**
 * Save SCF custom fields for menu items
 */
function save_scf_menu_item_fields($menu_id, $menu_item_db_id, $args) {
    // Check for SCF fields
    if (function_exists('get_field')) {
        
        // Save the "show_as_image" checkbox
        $show_as_image_key = 'show_as_image'; // Your SCF field name
        if (isset($_POST['menu-item-' . $show_as_image_key][$menu_item_db_id])) {
            $value = $_POST['menu-item-' . $show_as_image_key][$menu_item_db_id];
            update_post_meta($menu_item_db_id, $show_as_image_key, $value);
        }
        
      
    }
}
add_action('wp_update_nav_menu_item', 'save_scf_menu_item_fields', 10, 3);

/**
 * Display SCF fields in menu item edit screen
 */
function add_scf_fields_to_menu_item($item_id, $item, $depth, $args) {
    if (function_exists('get_field')) {
        $show_as_image = get_post_meta($item_id, 'show_as_image', true);
        $menu_item_image = get_post_meta($item_id, 'menu_item_image', true);
        ?>
        <div class="scf-menu-fields description-wide" style="margin: 5px 0;">
            <h4>SCF Custom Fields</h4>
            
            <!-- Show as Image Checkbox -->
            <p class="description">
                <label for="edit-menu-item-show_as_image-<?php echo $item_id; ?>">
                    <input type="checkbox" 
                           id="edit-menu-item-show_as_image-<?php echo $item_id; ?>" 
                           class="widefat edit-menu-item-show_as_image" 
                           name="menu-item-show_as_image[<?php echo $item_id; ?>]" 
                           value="1" 
                           <?php checked($show_as_image, 1); ?> />
                    Show as Image (will be displayed in the last column)
                </label>
            </p>
            
           
        </div>
        <?php
    }
}
add_action('wp_nav_menu_item_custom_fields', 'add_scf_fields_to_menu_item', 10, 4);


/**
 * AJAX handler: Get product reviews (HTML for modal)
 */
function loomaama_ajax_get_reviews() {
    // 1. Security Check
    check_ajax_referer('loomaama_nonce', 'nonce');
    
    // 2. Input Validation
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    
    if (!$product_id) {
        wp_send_json_error(__('Invalid product.', 'loomaama'));
    }
    
    $product = wc_get_product($product_id);
    if (!$product) {
        wp_send_json_error(__('Product not found.', 'loomaama'));
    }
    
    // 3. Get reviews
    $args = array(
        'post_id' => $product_id,
        'status'  => 'approve',
        'type'    => 'review', // Standard WooCommerce review type
        'number'  => 20,
    );
    $reviews = get_comments($args);
    
    ob_start();
    if (empty($reviews)) {
        echo '<p class="text-center text-muted">' . esc_html__('No reviews yet. Be the first to write a review!', 'loomaama') . '</p>';
    } else {
        foreach ($reviews as $review) {
            $rating = intval(get_comment_meta($review->comment_ID, 'rating', true));
            // Check if your site actually uses a 'review_title' meta key
            $review_title = get_comment_meta($review->comment_ID, 'review_title', true); 
            ?>
            <div class="review-item mb-4 pb-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <strong><?php echo esc_html($review->comment_author); ?></strong>
                    <span class="text-muted small"><?php echo esc_html(get_comment_date('', $review)); ?></span>
                </div>

                <?php if ($rating) : ?>
                    <div class="rating-stars mb-1">
                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                            <i class="fa fa-star<?php echo ($i <= $rating) ? ' text-warning' : ' text-muted'; ?>"></i>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($review_title)) : ?>
                    <h6 class="mt-2 mb-1"><?php echo esc_html($review_title); ?></h6>
                <?php endif; ?>

                <p class="mb-0"><?php echo wp_kses_post($review->comment_content); ?></p>
            </div>
            <?php
        }
    }
    $html = ob_get_clean();
    
    wp_send_json_success(array('html' => $html));
}
add_action('wp_ajax_loomaama_get_reviews', 'loomaama_ajax_get_reviews');
add_action('wp_ajax_nopriv_loomaama_get_reviews', 'loomaama_ajax_get_reviews');

/**
 * AJAX handler: Submit a product review
 */
function loomaama_ajax_submit_review() {
    check_ajax_referer('loomaama_review_nonce', 'review_nonce');
    
    $product_id = intval($_POST['product_id']);
    $rating     = intval($_POST['rating']);
    $title      = sanitize_text_field($_POST['review_title']);
    $comment    = sanitize_textarea_field($_POST['comment']);
    $author     = sanitize_text_field($_POST['author']);
    $email      = sanitize_email($_POST['email']);
    
    if (!$product_id || !$rating || !$comment || !$author || !$email) {
        wp_send_json_error(__('Please fill in all required fields.', 'loomaama'));
    }
    
    if ($rating < 1 || $rating > 5) {
        wp_send_json_error(__('Invalid rating.', 'loomaama'));
    }
    
    $product = wc_get_product($product_id);
    if (!$product) {
        wp_send_json_error(__('Product not found.', 'loomaama'));
    }
    
    $commentdata = array(
        'comment_post_ID'      => $product_id,
        'comment_author'       => $author,
        'comment_author_email' => $email,
        'comment_content'      => $comment,
        'comment_type'         => 'review',
        'comment_approved'     => 0,  // pending approval
        'user_id'              => get_current_user_id(),
    );
    
    if (!empty($title)) {
        $commentdata['comment_title'] = $title;
    }
    
    $comment_id = wp_new_comment($commentdata);
    
    if ($comment_id) {
        update_comment_meta($comment_id, 'rating', $rating);
        wp_send_json_success(array('message' => __('Thank you for your review! It will be visible after approval.', 'loomaama')));
    } else {
        wp_send_json_error(__('Could not submit your review. Please try again.', 'loomaama'));
    }
}
add_action('wp_ajax_loomaama_submit_review', 'loomaama_ajax_submit_review');
add_action('wp_ajax_nopriv_loomaama_submit_review', 'loomaama_ajax_submit_review');



// Debug: Check if coming soon template exists (remove after testing)
add_action('init', function() {
    if (current_user_can('administrator')) {
        $template_path = get_stylesheet_directory() . '/custom-coming-soon.php';
        $woo_template_path = get_stylesheet_directory() . '/woocommerce/custom-coming-soon.php';
        
        if (file_exists($template_path)) {
            error_log('Coming soon template found at: ' . $template_path);
        } elseif (file_exists($woo_template_path)) {
            error_log('Coming soon template found at: ' . $woo_template_path);
        } else {
            error_log('Coming soon template NOT found. Checked: ' . $template_path . ' and ' . $woo_template_path);
        }
    }
});


/**
 * Get hex color from slug or name (with better mapping)
 * 
 * @param string $slug Color slug
 * @param string $name Color name (optional)
 * @return string Hex color code
 */
function loomaama_get_color_hex_from_slug($color_slug, $color_name = '') {
    // Define exact hex codes for the 5 main colors
    $color_map = array(
        'natural-cream' => '#E8E5DF',
        'natural_cream' => '#E8E5DF',
        'natural cream' => '#E8E5DF',
        'Natural Cream' => '#E8E5DF',
        'cream' => '#E8E5DF',
        'oatmeal' => '#D8CFC4',
        'Oatmeal' => '#D8CFC4',
        'oat' => '#D8CFC4',
        'sand' => '#C2B29F',
        'Sand' => '#C2B29F',
        'taupe' => '#A3917F',
        'Taupe' => '#A3917F',
        'earth-brown' => '#8B7355',
        'earth_brown' => '#8B7355',
        'earth brown' => '#8B7355',
        'Earth Brown' => '#8B7355',
        'brown' => '#8B7355',
        'Brown' => '#8B7355',
        'red' => '#FF0000',
        'Red' => '#FF0000',
        'charcoal' => '#36454F',
        'Charcoal' => '#36454F',
        'green' => '#008000',
        'Green' => '#008000',
        'blue' => '#0000FF',
        'Blue' => '#0000FF',
    );
    
    // Try the slug first
    $normalized_slug = strtolower(trim($color_slug));
    if (isset($color_map[$normalized_slug])) {
        return $color_map[$normalized_slug];
    }
    
    // Try the name
    if (!empty($color_name)) {
        $normalized_name = strtolower(trim($color_name));
        if (isset($color_map[$normalized_name])) {
            return $color_map[$normalized_name];
        }
    }
    
    // Check for partial matches
    if (strpos($normalized_slug, 'cream') !== false || (!empty($color_name) && strpos(strtolower($color_name), 'cream') !== false)) {
        return '#E8E5DF';
    }
    if (strpos($normalized_slug, 'oat') !== false || (!empty($color_name) && strpos(strtolower($color_name), 'oat') !== false)) {
        return '#D8CFC4';
    }
    if (strpos($normalized_slug, 'sand') !== false || (!empty($color_name) && strpos(strtolower($color_name), 'sand') !== false)) {
        return '#C2B29F';
    }
    if (strpos($normalized_slug, 'taupe') !== false || (!empty($color_name) && strpos(strtolower($color_name), 'taupe') !== false)) {
        return '#A3917F';
    }
    if (strpos($normalized_slug, 'brown') !== false || (!empty($color_name) && strpos(strtolower($color_name), 'brown') !== false)) {
        return '#8B7355';
    }
    
    // Default fallback - natural cream
    return '#E8E5DF';
}



/**
 * Get all colors from WooCommerce pa_color attribute
 * 
 * @return array Array of color terms with slug, name, and automatically generated hex code
 */
function loomaama_get_color_attributes() {
    $colors = array();
    
    // Get terms from pa_color taxonomy
    $color_terms = get_terms(array(
        'taxonomy' => 'pa_color',
        'hide_empty' => false,
    ));
    
    if (!empty($color_terms) && !is_wp_error($color_terms)) {
        foreach ($color_terms as $term) {
            $colors[] = array(
                'slug' => $term->slug,
                'name' => $term->name,
                'hex' => loomaama_get_color_hex_from_name($term->name, $term->slug),
            );
        }
    }
    
    return $colors;
}

/**
 * Get all patterns from WooCommerce pa_pattern attribute
 * 
 * @return array Array of pattern terms with slug and name
 */
function loomaama_get_pattern_attributes() {
    $patterns = array();
    
    // Get terms from pa_pattern taxonomy
    $pattern_terms = get_terms(array(
        'taxonomy' => 'pa_pattern',
        'hide_empty' => false,
    ));
    
    if (!empty($pattern_terms) && !is_wp_error($pattern_terms)) {
        foreach ($pattern_terms as $term) {
            $patterns[] = array(
                'slug' => $term->slug,
                'name' => $term->name,
            );
        }
    }
    
    return $patterns;
}

/**
 * Get all sizes from WooCommerce pa_size attribute
 * 
 * @return array Array of size terms with slug and name
 */
function loomaama_get_size_attributes() {
    $sizes = array();
    
    // Get terms from pa_size taxonomy
    $size_terms = get_terms(array(
        'taxonomy' => 'pa_size',
        'hide_empty' => false,
    ));
    
    if (!empty($size_terms) && !is_wp_error($size_terms)) {
        foreach ($size_terms as $term) {
            $sizes[] = array(
                'slug' => $term->slug,
                'name' => $term->name,
            );
        }
    }
    
    return $sizes;
}

/**
 * Generate hex color code from color name or slug
 * 
 * @param string $name Color name
 * @param string $slug Color slug
 * @return string Hex color code
 */
function loomaama_get_color_hex_from_name($name, $slug) {
    // First check if the main function exists (from functions.php)
    if (function_exists('loomaama_get_color_hex_from_slug')) {
        return loomaama_get_color_hex_from_slug($slug, $name);
    }
    
    // Fallback color mapping if functions.php function doesn't exist
    $color_map = array(
        'natural-cream' => '#E8E5DF',
        'oatmeal' => '#D8CFC4',
        'sand' => '#C2B29F',
        'taupe' => '#A3917F',
        'earth-brown' => '#8B7355',
        'red' => '#FF0000',
        'charcoal' => '#36454F',
    );
    
    $normalized_name = strtolower($name);
    $normalized_slug = strtolower($slug);
    
    if (isset($color_map[$normalized_name])) {
        return $color_map[$normalized_name];
    }
    if (isset($color_map[$normalized_slug])) {
        return $color_map[$normalized_slug];
    }
    
    return '#E8E5DF';
}

/**
 * Get available variations for a product with all attributes
 * 
 * @param WC_Product $product Product object
 * @return array Available variations
 */
function loomaama_get_available_variations_with_attributes($product) {
    if (!$product || !$product->is_type('variable')) {
        return array();
    }
    
    $variations = $product->get_available_variations();
    $formatted_variations = array();
    
    foreach ($variations as $variation) {
        if (!$variation['is_in_stock']) {
            continue;
        }
        
        $attributes = array();
        foreach ($variation['attributes'] as $key => $value) {
            $attr_name = str_replace('attribute_', '', $key);
            $attributes[$attr_name] = $value;
        }
        
        $formatted_variations[] = array(
            'variation_id' => $variation['variation_id'],
            'attributes' => $attributes,
            'price_html' => $variation['price_html'],
            'is_in_stock' => $variation['is_in_stock'],
            'is_purchasable' => $variation['is_purchasable'],
            'image' => isset($variation['image']) ? $variation['image'] : null,
        );
    }
    
    return $formatted_variations;
}

/**
 * Register SCF field for color term hex code (optional - for admin to set custom colors)
 */
function loomaama_register_color_term_fields() {
    if (!function_exists('register_scf_field')) {
        return;
    }
    
    $color_terms = get_terms(array(
        'taxonomy' => 'pa_color',
        'hide_empty' => false,
    ));
    
    foreach ($color_terms as $term) {
        register_scf_field('term', 'color_hex_' . $term->term_id, array(
            'label' => __('Color Hex Code', 'loomaama'),
            'type' => 'color',
            'instructions' => __('Enter hex color code for this color (e.g., #FF0000 for red)', 'loomaama'),
            'location' => array(
                array(
                    array(
                        'param' => 'term',
                        'operator' => '==',
                        'value' => 'pa_color:' . $term->slug,
                    ),
                ),
            ),
        ));
    }
}
// Uncomment the line below if you want to register color hex fields for terms
// add_action('init', 'loomaama_register_color_term_fields', 20);


require_once LOOMAAMA_DIR . '/inc/scf-variant-fields.php';

require_once LOOMAAMA_DIR . '/inc/scf-variant-fields-for-products.php';


/* for performance */

/**
 * Clear design group cache when product is saved
 */
add_action('save_post_product', 'loomaama_clear_design_group_cache', 10, 3);
function loomaama_clear_design_group_cache($post_id, $post, $update) {
    $design_group = get_post_meta($post_id, 'design_group_id', true);
    if (!empty($design_group)) {
        $cache_key = 'design_group_' . md5($design_group);
        delete_transient($cache_key);
    }
}

/**
 * Clear variation cache when product is saved
 */
add_action('save_post_product', 'loomaama_clear_variation_cache', 10, 3);
function loomaama_clear_variation_cache($post_id, $post, $update) {
    // Clear BOTH cache keys
    delete_transient('product_variations_' . $post_id);
    delete_transient('product_variations_full_' . $post_id);
}

/**
 * Clear variation cache when a variation is saved directly
 */
add_action('save_post_product_variation', 'loomaama_clear_variation_on_variation_save', 10, 2);
function loomaama_clear_variation_on_variation_save($variation_id, $post) {
    $product_id = wp_get_post_parent_id($variation_id);
    if ($product_id) {
        delete_transient('product_variations_' . $product_id);
        delete_transient('product_variations_full_' . $product_id);
    }
}

/**
 * Clear related products cache when product is saved
 */
add_action('save_post_product', 'loomaama_clear_related_cache', 10, 3);
function loomaama_clear_related_cache($post_id, $post, $update) {
    $cache_key = 'related_products_' . $post_id;
    delete_transient($cache_key);
}

/**
 * Optional: Admin notice helper to manually clear cache
 */
add_action('admin_bar_menu', 'loomaama_add_clear_cache_button', 100);
function loomaama_add_clear_cache_button($wp_admin_bar) {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    if (is_admin() && isset($_GET['post']) && get_post_type($_GET['post']) === 'product') {
        $wp_admin_bar->add_node(array(
            'id' => 'clear-product-cache',
            'title' => 'Clear Variation Cache',
            'href' => add_query_arg('clear_variation_cache', $_GET['post']),
            'meta' => array('title' => 'Clear cached variations for this product')
        ));
    }
}

// Handle manual cache clear from admin bar
add_action('init', function() {
    if (isset($_GET['clear_variation_cache']) && current_user_can('manage_options')) {
        $product_id = intval($_GET['clear_variation_cache']);
        if ($product_id) {
            delete_transient('product_variations_' . $product_id);
            delete_transient('product_variations_full_' . $product_id);
            wp_redirect(remove_query_arg('clear_variation_cache'));
            exit;
        }
    }
});
/**
 * Lazy load related products via AJAX (recommended for better performance)
 */
add_action('wp_ajax_loomaama_load_related_products', 'loomaama_ajax_load_related_products');
add_action('wp_ajax_nopriv_loomaama_load_related_products', 'loomaama_ajax_load_related_products');

function loomaama_ajax_load_related_products() {
    check_ajax_referer('loomaama_nonce', 'nonce');
    
    $product_id = intval($_POST['product_id']);
    $product = wc_get_product($product_id);
    
    if (!$product) {
        wp_send_json_error();
    }
    
    $related_count = get_theme_mod('related_products_count', 4);
    $related_products = wc_get_related_products($product_id, $related_count);
    
    ob_start();
    // Output related products HTML here
    wp_send_json_success(array('html' => ob_get_clean()));
}

/**
 * Add deferred loading for images
 */
add_filter('wp_get_attachment_image_attributes', 'loomaama_add_lazy_loading', 10, 3);
function loomaama_add_lazy_loading($attr, $attachment, $size) {
    if (!is_admin() && !isset($attr['loading'])) {
        $attr['loading'] = 'lazy';
    }
    return $attr;
}


/**
 * Get pattern image URL from slug
 * 
 * @param string $pattern_slug Pattern slug
 * @return string URL to pattern image or empty string if not found
 */
function loomaama_get_pattern_image_url($pattern_slug) {
    // Define pattern image mapping
    $pattern_images = array(
        'chevron' => 'chevron-pattern.jpg',
        'eyelet' => 'eyelet-pattern.jpg',
        'diamond' => 'diamond-pattern.jpg',
        'zigzag' => 'zigzag-pattern.jpg',
        'open-mesh' => 'open-mesh-pattern.jpg',
        'open-weave' => 'open-weave-pattern.jpg',
        'geometric' => 'geometric-pattern.jpg',
        'trellis' => 'trellis-pattern.jpg',
        'mixed-pattern' => 'mixed-pattern.jpg',
    );
    
    // Normalize the slug
    $normalized_slug = strtolower(trim($pattern_slug));
    
    // Check if we have a mapping for this pattern
    if (isset($pattern_images[$normalized_slug])) {
        return LOOMAAMA_URI . '/assets/images/pattern/' . $pattern_images[$normalized_slug];
    }
    
    // Try partial matches
    if (strpos($normalized_slug, 'chevron') !== false) {
        return LOOMAAMA_URI . '/assets/images/pattern/chevron-pattern.jpg';
    }
    if (strpos($normalized_slug, 'eyelet') !== false) {
        return LOOMAAMA_URI . '/assets/images/pattern/eyelet-pattern.jpg';
    }
    if (strpos($normalized_slug, 'diamond') !== false) {
        return LOOMAAMA_URI . '/assets/images/pattern/diamond-pattern.jpg';
    }
    if (strpos($normalized_slug, 'zigzag') !== false) {
        return LOOMAAMA_URI . '/assets/images/pattern/zigzag-pattern.jpg';
    }
    if (strpos($normalized_slug, 'open-mesh') !== false || strpos($normalized_slug, 'openmesh') !== false) {
        return LOOMAAMA_URI . '/assets/images/pattern/open-mesh-pattern.jpg';
    }
    if (strpos($normalized_slug, 'open-weave') !== false || strpos($normalized_slug, 'openweave') !== false) {
        return LOOMAAMA_URI . '/assets/images/pattern/open-weave-pattern.jpg';
    }
    if (strpos($normalized_slug, 'geometric') !== false) {
        return LOOMAAMA_URI . '/assets/images/pattern/geometric-pattern.jpg';
    }
    if (strpos($normalized_slug, 'trellis') !== false) {
        return LOOMAAMA_URI . '/assets/images/pattern/trellis-pattern.jpg';
    }
    if (strpos($normalized_slug, 'mixed') !== false) {
        return LOOMAAMA_URI . '/assets/images/pattern/mixed-pattern.jpg';
    }
    
    // Return placeholder if pattern image not found
    return LOOMAAMA_URI . '/assets/images/pattern/placeholder-pattern.jpg';
}


/**
 * Get all patterns from WooCommerce pa_pattern attribute with auto-mapped images
 * 
 * @return array Array of pattern terms with slug, name, and image URL
 */
function loomaama_get_pattern_attributes_with_images() {
    $patterns = array();
    
    // Get terms from pa_pattern taxonomy
    $pattern_terms = get_terms(array(
        'taxonomy' => 'pa_pattern',
        'hide_empty' => false,
    ));
    
    if (!empty($pattern_terms) && !is_wp_error($pattern_terms)) {
        foreach ($pattern_terms as $term) {
            $patterns[] = array(
                'slug' => $term->slug,
                'name' => $term->name,
                'image' => loomaama_get_pattern_image_url($term->slug),
            );
        }
    }
    
    return $patterns;
}

/**
 * Get available pattern slugs for a specific design group
 * 
 * @param string $design_group Design group ID
 * @return array Array of available pattern slugs
 */
function loomaama_get_available_pattern_slugs_for_design_group($design_group) {
    $available_patterns = array();
    
    if (empty($design_group)) {
        return $available_patterns;
    }
    
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_key' => 'design_group_id',
        'meta_value' => $design_group,
        'fields' => 'ids',
    );
    
    $product_ids = get_posts($args);
    
    foreach ($product_ids as $product_id) {
        $pattern_terms = wp_get_post_terms($product_id, 'pa_pattern', array('fields' => 'slugs'));
        if (!empty($pattern_terms)) {
            $available_patterns = array_merge($available_patterns, $pattern_terms);
        }
    }
    
    return array_unique($available_patterns);
}

/**
 * Get available patterns for a specific design group
 * 
 * @param string $design_group Design group ID
 * @param string $current_color_slug Current color slug (optional)
 * @return array Array of available patterns with product IDs
 */
function loomaama_get_available_patterns_for_design_group($design_group, $current_color_slug = '') {
    $available_patterns = array();
    
    if (empty($design_group)) {
        return $available_patterns;
    }
    
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => 'design_group_id',
                'value' => $design_group,
                'compare' => '='
            )
        ),
    );
    
    // Filter by color if provided
    if (!empty($current_color_slug)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'pa_color',
                'field' => 'slug',
                'terms' => $current_color_slug,
            )
        );
    }
    
    $products = get_posts($args);
    
    foreach ($products as $product) {
        $pattern_terms = wp_get_post_terms($product->ID, 'pa_pattern');
        if (!empty($pattern_terms)) {
            $pattern = $pattern_terms[0];
            $pattern_slug = $pattern->slug;
            
            // Only add if not already in array
            if (!isset($available_patterns[$pattern_slug])) {
                $available_patterns[$pattern_slug] = array(
                    'slug' => $pattern_slug,
                    'name' => $pattern->name,
                    'image' => loomaama_get_pattern_image_url($pattern_slug),
                    'product_id' => $product->ID,
                    'is_current' => ($product->ID == get_the_ID())
                );
            }
        }
    }
    
    return $available_patterns;
}


/**
 * Add rewrite rules for artisans archive
 */
function loomaama_artisans_rewrite_rules() {
    add_rewrite_rule(
        '^artisan/page/([0-9]+)/?$',
        'index.php?post_type=artisan&paged=$matches[1]',
        'top'
    );
    add_rewrite_rule(
        '^artisan/?$',
        'index.php?post_type=artisan',
        'top'
    );
}
add_action('init', 'loomaama_artisans_rewrite_rules');

/**
 * Set custom archive slug for artisans
 */
function loomaama_artisans_archive_slug($args, $post_type) {
    if ($post_type === 'artisan') {
        $args['rewrite']['slug'] = 'artisan';
    }
    return $args;
}
add_filter('register_post_type_args', 'loomaama_artisans_archive_slug', 10, 2);

// Flush rewrite rules after adding
function loomaama_flush_rewrites() {
    loomaama_artisans_rewrite_rules();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'loomaama_flush_rewrites');

/**
 * AJAX handler to load filter options dynamically
 */
add_action('wp_ajax_loomaama_get_filter_options', 'loomaama_ajax_get_filter_options');
add_action('wp_ajax_nopriv_loomaama_get_filter_options', 'loomaama_ajax_get_filter_options');

function loomaama_ajax_get_filter_options() {
    check_ajax_referer('loomaama_nonce', 'nonce');
    
    $options = loomaama_get_available_filter_options();
    
    ob_start();
    ?>
    <!-- Categories Accordion Item -->
    <div class="accordion-filter-item">
        <div class="accordion-filter-header">
            <h6 class="filter-title mb-0"><?php _e('CATEGORIES', 'loomaama'); ?></h6>
            <span class="accordion-icon">+</span>
        </div>
        <div class="accordion-filter-content">
            <div class="filter-options">
                <?php
                $product_categories = get_terms(array(
                    'taxonomy' => 'product_cat',
                    'hide_empty' => true,
                ));
                
                if (!empty($product_categories) && !is_wp_error($product_categories)) :
                    foreach ($product_categories as $category) : ?>
                        <label class="filter-option">
                            <input type="checkbox" class="filter-checkbox" data-type="category" value="<?php echo esc_attr($category->slug); ?>">
                            <span><?php echo esc_html($category->name); ?></span>
                        </label>
                    <?php endforeach;
                endif; ?>
            </div>
        </div>
    </div>

    <!-- Sizes Accordion Item (Dynamic from pa_size) -->
    <?php if (!empty($options['sizes'])) : ?>
    <div class="accordion-filter-item">
        <div class="accordion-filter-header">
            <h6 class="filter-title mb-0"><?php _e('SIZES', 'loomaama'); ?></h6>
            <span class="accordion-icon">+</span>
        </div>
        <div class="accordion-filter-content">
            <div class="size-grid">
                <?php foreach ($options['sizes'] as $size) : ?>
                    <button class="size-btn" data-size="<?php echo esc_attr($size['slug']); ?>" data-type="size">
                        <?php echo esc_html($size['name']); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Widths Accordion Item (Dynamic from pa_width) -->
    <?php if (!empty($options['widths'])) : ?>
    <div class="accordion-filter-item">
        <div class="accordion-filter-header">
            <h6 class="filter-title mb-0"><?php _e('WIDTHS', 'loomaama'); ?></h6>
            <span class="accordion-icon">+</span>
        </div>
        <div class="accordion-filter-content">
            <div class="width-grid">
                <?php foreach ($options['widths'] as $width) : ?>
                    <button class="width-btn" data-width="<?php echo esc_attr($width['slug']); ?>" data-type="width">
                        <?php echo esc_html($width['name']); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Patterns Accordion Item (Dynamic from pa_pattern) -->
    <?php if (!empty($options['patterns'])) : ?>
    <div class="accordion-filter-item">
        <div class="accordion-filter-header">
            <h6 class="filter-title mb-0"><?php _e('PATTERNS', 'loomaama'); ?></h6>
            <span class="accordion-icon">+</span>
        </div>
        <div class="accordion-filter-content">
            <div class="pattern-grid">
                <?php foreach ($options['patterns'] as $pattern) : ?>
                    <button class="pattern-btn" data-pattern="<?php echo esc_attr($pattern['slug']); ?>" data-type="pattern">
                        <?php echo esc_html($pattern['name']); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Colors Accordion Item (Dynamic from pa_color) -->
    <?php if (!empty($options['colors'])) : ?>
    <div class="accordion-filter-item">
        <div class="accordion-filter-header">
            <h6 class="filter-title mb-0"><?php _e('COLORS', 'loomaama'); ?></h6>
            <span class="accordion-icon">+</span>
        </div>
        <div class="accordion-filter-content">
            <div class="color-grid">
                <?php foreach ($options['colors'] as $color) : ?>
                    <button class="color-btn" 
                            style="background-color: <?php echo esc_attr($color['hex']); ?>; border: 1px solid <?php echo $color['hex'] == '#FFFFFF' ? '#ddd' : 'transparent'; ?>;" 
                            data-color="<?php echo esc_attr($color['slug']); ?>" 
                            data-type="color"
                            title="<?php echo esc_attr($color['name']); ?>">
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Price Range Accordion Item -->
    <div class="accordion-filter-item">
        <div class="accordion-filter-header">
            <h6 class="filter-title mb-0"><?php _e('PRICE RANGE', 'loomaama'); ?></h6>
            <span class="accordion-icon">+</span>
        </div>
        <div class="accordion-filter-content">
            <div class="price-range">
                <div class="price-inputs">
                    <input type="number" class="price-input" id="min-price" placeholder="<?php _e('Min', 'loomaama'); ?>" 
                           value="<?php echo esc_attr($options['price_range']['min']); ?>" 
                           min="<?php echo esc_attr($options['price_range']['min']); ?>" 
                           max="<?php echo esc_attr($options['price_range']['max']); ?>">
                    <span>—</span>
                    <input type="number" class="price-input" id="max-price" placeholder="<?php _e('Max', 'loomaama'); ?>" 
                           value="<?php echo esc_attr($options['price_range']['max']); ?>" 
                           min="<?php echo esc_attr($options['price_range']['min']); ?>" 
                           max="<?php echo esc_attr($options['price_range']['max']); ?>">
                </div>
                <div class="price-slider">
                    <input type="range" class="range-slider" id="price-min-slider" 
                           min="<?php echo esc_attr($options['price_range']['min']); ?>" 
                           max="<?php echo esc_attr($options['price_range']['max']); ?>" 
                           value="<?php echo esc_attr($options['price_range']['min']); ?>" 
                           step="10">
                    <input type="range" class="range-slider" id="price-max-slider" 
                           min="<?php echo esc_attr($options['price_range']['min']); ?>" 
                           max="<?php echo esc_attr($options['price_range']['max']); ?>" 
                           value="<?php echo esc_attr($options['price_range']['max']); ?>" 
                           step="10">
                </div>
            </div>
        </div>
    </div>
    
    <!-- SORT BY Accordion Item (MOVED HERE - Previously missing) -->
    <div class="accordion-filter-item">
        <div class="accordion-filter-header">
            <h6 class="filter-title mb-0"><?php _e('SORT BY', 'loomaama'); ?></h6>
            <span class="accordion-icon">+</span>
        </div>
        <div class="accordion-filter-content">
            <div class="filter-group">
                <ul class="sort-options-list p-0">
                    <li class="sort-option list-style-none">
                        <label>
                            <input type="radio" name="sort_by" class="sort-radio" value="popularity">
                            <span><?php _e('Sort by Popularity', 'loomaama'); ?></span>
                        </label>
                    </li>
                    <li class="sort-option list-style-none">
                        <label>
                            <input type="radio" name="sort_by" class="sort-radio" value="rating">
                            <span><?php _e('Sort by Average Rating', 'loomaama'); ?></span>
                        </label>
                    </li>
                    <li class="sort-option list-style-none">
                        <label>
                            <input type="radio" name="sort_by" class="sort-radio" value="latest">
                            <span><?php _e('Sort by Latest', 'loomaama'); ?></span>
                        </label>
                    </li>
                    <li class="sort-option list-style-none">
                        <label>
                            <input type="radio" name="sort_by" class="sort-radio" value="price_desc">
                            <span><?php _e('Sort by Price: High to Low', 'loomaama'); ?></span>
                        </label>
                    </li>
                    <li class="sort-option list-style-none">
                        <label>
                            <input type="radio" name="sort_by" class="sort-radio" value="price_asc">
                            <span><?php _e('Sort by Price: Low to High', 'loomaama'); ?></span>
                        </label>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <?php
    $html = ob_get_clean();
    
    wp_send_json_success(array(
        'html' => $html,
        'options' => $options
    ));
}
/**
 * Get all available filter options based on product variations
 * 
 * @return array Filter options including sizes, widths, patterns, colors
 */
function loomaama_get_available_filter_options() {
    $options = array(
        'sizes' => array(),
        'widths' => array(),
        'patterns' => array(),
        'colors' => array(),
        'price_range' => array(
            'min' => 0,
            'max' => 1000
        )
    );
    
    // Get all published products
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
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
    
    $products = get_posts($args);
    $all_prices = array();
    $processed_sizes = array();
    $processed_widths = array();
    $processed_patterns = array();
    $processed_colors = array();
    
    foreach ($products as $product_post) {
        $product = wc_get_product($product_post->ID);
        if (!$product) continue;
        
        // Get price for range
        $price = $product->get_price();
        if ($price) {
            $all_prices[] = floatval($price);
        }
        
        // For variable products, get variation attributes
        if ($product->is_type('variable')) {
            $variations = $product->get_available_variations();
            foreach ($variations as $variation) {
                if (!$variation['is_in_stock']) continue;
                
                // Get size attribute
                if (isset($variation['attributes']['attribute_pa_size'])) {
                    $size_slug = $variation['attributes']['attribute_pa_size'];
                    if ($size_slug && !isset($processed_sizes[$size_slug])) {
                        $term = get_term_by('slug', $size_slug, 'pa_size');
                        if ($term && !is_wp_error($term)) {
                            $options['sizes'][] = array(
                                'slug' => $size_slug,
                                'name' => $term->name,
                            );
                            $processed_sizes[$size_slug] = true;
                        }
                    }
                }
                
                // Get width attribute
                if (isset($variation['attributes']['attribute_pa_width'])) {
                    $width_slug = $variation['attributes']['attribute_pa_width'];
                    if ($width_slug && !isset($processed_widths[$width_slug])) {
                        $term = get_term_by('slug', $width_slug, 'pa_width');
                        if ($term && !is_wp_error($term)) {
                            $options['widths'][] = array(
                                'slug' => $width_slug,
                                'name' => $term->name,
                            );
                            $processed_widths[$width_slug] = true;
                        }
                    }
                }
            }
        }
        
        // Get pattern attribute from simple/variable products
        $pattern_terms = wp_get_post_terms($product_post->ID, 'pa_pattern');
        if (!empty($pattern_terms) && !is_wp_error($pattern_terms)) {
            foreach ($pattern_terms as $term) {
                if (!isset($processed_patterns[$term->slug])) {
                    $options['patterns'][] = array(
                        'slug' => $term->slug,
                        'name' => $term->name,
                    );
                    $processed_patterns[$term->slug] = true;
                }
            }
        }
        
        // Get color attribute
        $color_terms = wp_get_post_terms($product_post->ID, 'pa_color');
        if (!empty($color_terms) && !is_wp_error($color_terms)) {
            foreach ($color_terms as $term) {
                if (!isset($processed_colors[$term->slug])) {
                    $hex = loomaama_get_color_hex_from_name($term->name, $term->slug);
                    $options['colors'][] = array(
                        'slug' => $term->slug,
                        'name' => $term->name,
                        'hex' => $hex,
                    );
                    $processed_colors[$term->slug] = true;
                }
            }
        }
    }
    
    // Set price range
    if (!empty($all_prices)) {
        $options['price_range']['min'] = floor(min($all_prices));
        $options['price_range']['max'] = ceil(max($all_prices));
    }
    
    // Sort sizes numerically if possible
    usort($options['sizes'], function($a, $b) {
        $order = array('xs', 's', 'm', 'l', 'xl', 'xxl', 'xxxl');
        $pos_a = array_search(strtolower($a['slug']), $order);
        $pos_b = array_search(strtolower($b['slug']), $order);
        if ($pos_a !== false && $pos_b !== false) {
            return $pos_a - $pos_b;
        }
        return strcmp($a['slug'], $b['slug']);
    });
    
    return $options;
}

/**
 * Enqueue hover image handler script
 */
function loomaama_enqueue_hover_handler() {
    wp_enqueue_script('loomaama-hover-handler', LOOMAAMA_URI . '/assets/js/hover-handler.js', array('jquery'), LOOMAAMA_VERSION, true);
    
    wp_localize_script('loomaama-hover-handler', 'loomaama_hover', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('loomaama_hover_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'loomaama_enqueue_hover_handler', 20);
/**
 * Exclude hover images from lazy-load to ensure they work properly
 */
function loomaama_litespeed_exclude_hover_images($exclude) {
    if (!is_array($exclude)) {
        $exclude = array();
    }
    
    // Don't lazy-load images with data-hover-image attribute
    $exclude[] = 'data-hover-image';
    
    return $exclude;
}
add_filter('litespeed_optimize_attr_excludes', 'loomaama_litespeed_exclude_hover_images');

/**
 * Add custom JS to handle Lightspeed Cache lazy-load
 */
function loomaama_litespeed_script() {
    if (defined('LSCACHE_VARY_VALUE')) {
        ?>
        <script>
        (function() {
            // Wait for Lightspeed Cache to initialize
            document.addEventListener('lazy-load-images:updated', function() {
                if (window.loomaamaHoverHandler) {
                    setTimeout(function() {
                        window.loomaamaHoverHandler.refresh();
                    }, 100);
                }
            });
        })();
        </script>
        <?php
    }
}
add_action('wp_footer', 'loomaama_litespeed_script', 100);

/**
 * Add Lightspeed Cache exclusions for hover images
 */
function loomaama_litespeed_exclude_config() {
    if (!defined('LSCACHE_VARY_VALUE')) return;
    
    // Exclude images with data-hover-image from being lazy-loaded
    add_filter('litespeed_optimize_attr_excludes', function($exclude) {
        $exclude[] = 'data-hover-image';
        $exclude[] = 'hover-loading';
        return $exclude;
    });
    
    // Add to lazy-load exclude list
    add_filter('litespeed_media_lazy_img_excludes', function($list) {
        $list[] = 'data-hover-image';
        $list[] = 'hover-image';
        return $list;
    });
}
add_action('init', 'loomaama_litespeed_exclude_config');

/**
 * Alternative method using template_redirect action
 */
function loomaama_custom_404_redirect() {
    if (is_404()) {
        // Define possible template paths
        $custom_template = null;
        
        // Check for 404.php in theme
        if (file_exists(get_template_directory() . '/404.php')) {
            $custom_template = get_template_directory() . '/404.php';
        } elseif (file_exists(get_template_directory() . '/error404.php')) {
            $custom_template = get_template_directory() . '/error404.php';
        }
        
        if ($custom_template) {
            // Ensure proper 404 header
            status_header(404);
            
            // Load the template
            include($custom_template);
            exit; // Stop further execution
        }
    }
}
add_action('template_redirect', 'loomaama_custom_404_redirect', 1);
