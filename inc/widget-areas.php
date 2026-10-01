<?php
/**
 * Widget Areas Registration
 *
 * @package Loomaama
 */

function loomaama_widgets_init() {
    // Homepage Widget Area (for drag and drop sections)
    register_sidebar(array(
        'name' => __('Homepage Sections', 'loomaama'),
        'id' => 'homepage-sections',
        'description' => __('Drag and drop widgets to build your homepage', 'loomaama'),
        'before_widget' => '<section id="%1$s" class="widget %2$s homepage-widget" data-aos="fade-up" >',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title headline text-center mb-5" data-aos="fade-up">',
        'after_title' => '</h2>',
    ));

    // Product Category Grid Widget Area
    register_sidebar(array(
        'name' => __('Product Category Grids', 'loomaama'),
        'id' => 'category-grids',
        'description' => __('Product category grid widgets', 'loomaama'),
        'before_widget' => '<div id="%1$s" class="widget %2$s category-grid-widget" data-aos="fade-up">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title headline" data-aos="fade-up">',
        'after_title' => '</h3>',
    ));
    
    // Footer Widget Areas
    register_sidebar(array(
        'name' => __('Footer Column 1', 'loomaama'),
        'id' => 'footer-1',
        'description' => __('First footer column', 'loomaama'),
        'before_widget' => '<div id="%1$s" class="widget %2$s" data-aos="fade-up">',
        'after_widget' => '</div>',
        'before_title' => '<h6 class="headline border-bottom pb-3 mb-4">',
        'after_title' => '</h6>',
    ));
    
    register_sidebar(array(
        'name' => __('Footer Column 2', 'loomaama'),
        'id' => 'footer-2',
        'description' => __('Second footer column', 'loomaama'),
        'before_widget' => '<div id="%1$s" class="widget %2$s" data-aos="fade-up">',
        'after_widget' => '</div>',
        'before_title' => '<h6 class="headline border-bottom pb-3 mb-4">',
        'after_title' => '</h6>',
    ));
    
    register_sidebar(array(
        'name' => __('Footer Column 3', 'loomaama'),
        'id' => 'footer-3',
        'description' => __('Third footer column', 'loomaama'),
        'before_widget' => '<div id="%1$s" class="widget %2$s" data-aos="fade-up">',
        'after_widget' => '</div>',
        'before_title' => '<h6 class="headline border-bottom pb-3 mb-4">',
        'after_title' => '</h6>',
    ));
    
    register_sidebar(array(
        'name' => __('Footer Column 4', 'loomaama'),
        'id' => 'footer-4',
        'description' => __('Fourth footer column', 'loomaama'),
        'before_widget' => '<div id="%1$s" class="widget %2$s" data-aos="fade-up">',
        'after_widget' => '</div>',
        'before_title' => '<h6 class="headline border-bottom pb-3 mb-4">',
        'after_title' => '</h6>',
    ));
}
add_action('widgets_init', 'loomaama_widgets_init');

/**
 * Register Custom Widgets
 */
function loomaama_register_widgets() {
    // Register custom widgets with proper compatibility
    register_widget('Loomaama_Product_Category_Widget');
    register_widget('Loomaama_Featured_Products_Widget');
    register_widget('Loomaama_Hero_Widget');
    register_widget('Loomaama_Text_Block_Widget');
    register_widget('Loomaama_Image_Text_Widget');
    register_widget('Loomaama_Quote_Widget');
    register_widget('Loomaama_Collections_Grid_Widget');
    register_widget('Loomaama_Seasonal_Edit_Widget');
    register_widget('Loomaama_Promotion_Widget');
    register_widget('Loomaama_Parallax_Banner_Widget');
    register_widget('Loomaama_Nettle_Journey_Widget');
    register_widget('Loomaama_Sustainability_Editorial_Widget');
     register_widget( 'Loom_Seasonal_Edit_Widget' );
}
add_action('widgets_init', 'loomaama_register_widgets');

/**
 * Product Category Widget
 */
class Loomaama_Product_Category_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'loomaama_product_category',
            __('Loomaama: Product Category Grid', 'loomaama'),
            array('description' => __('Display products from a specific category with masonry layout', 'loomaama'))
        );
    }
    
    public function widget($args, $instance) {
        echo $args['before_widget'] ?? '';
        
        $category = !empty($instance['category']) ? $instance['category'] : '';
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $layout = !empty($instance['layout']) ? $instance['layout'] : 'main-masonry';
        $count = !empty($instance['count']) ? $instance['count'] : 8;
        
        if (!empty($title)) {
            echo '<h2 class="widget-title headline text-center mb-5" data-aos="fade-up">' . esc_html($title) . '</h2>';
        }
        
        if (!empty($category)) {
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => $count,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field' => 'slug',
                        'terms' => $category
                    )
                )
            );
            
            $products = new WP_Query($args);
            
            if ($products->have_posts()) {
                ?>
                <div class="container-fluid px-4 px-lg-5">
                    <div class="product-masonry <?php echo esc_attr($layout); ?>">
                        <?php
                        $i = 0;
                        while ($products->have_posts()) {
                            $products->the_post();
                            global $product;
                            
                            $class = '';
                            $aos_delay = 100 + ($i * 50);
                            $aos_animation = 'fade-up';
                            
                            if ($i === 0) $class = 'first-product';
                            elseif ($i === 1) $class = 'second-product';
                            elseif ($i === 2) $class = 'third-product';
                            elseif ($i === 3) $class = 'fourth-product';
                            elseif ($i === 4) $class = 'fifth-product';
                            ?>
                            <div class="<?php echo esc_attr($class); ?>" data-aos="<?php echo esc_attr($aos_animation); ?>">
                                <div class="lux-card">
                                    <div class="lux-img position-relative">
                                        <?php
                                            $hover_image = function_exists('get_field') ? get_field('hover_image') : null;
                                            $attributes = array();

                                            if ( !empty($hover_image) && isset($hover_image['url']) ) {
                                                $attributes['data-hover-image'] = esc_url($hover_image['url']);
                                                $attributes['class'] = 'wp-post-image wc-hover-switch'; 
                                            }

                                            echo $product->get_image( 'medium_large', $attributes );
                                            
                                         ?>
                                        <?php if ($product->is_on_sale()) : ?>
                                            <span class="badge-sale position-absolute top-0 start-0 m-3"><?php _e('SALE', 'loomaama'); ?></span>
                                        <?php endif; ?>
                                        <?php if ($product->is_featured()) : ?>
                                            <span class="badge-best position-absolute top-0 end-0 m-3"><?php _e('Best-Seller', 'loomaama'); ?></span>
                                        <?php endif; ?>
                                    </div>
                                   
                                    <div class="d-flex justify-content-between loom-title-the-price">
                                         <p class="lux-cat headline">
                                        <?php 
                                        $categories = wc_get_product_category_list($product->get_id());
                                        echo strip_tags($categories);
                                        ?>
                                    </p>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                                            <h6 class="mb-0 text-truncate"><?php the_title(); ?></h6>
                                        </a>
                                        <span class="text-secondary"><?php echo $product->get_price_html(); ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $i++;
                        }
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
                <?php
            }
        }
        
        echo $args['after_widget'] ?? '';
    }
    
    public function form($instance) {
        $category = !empty($instance['category']) ? $instance['category'] : '';
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $layout = !empty($instance['layout']) ? $instance['layout'] : 'main-masonry';
        $count = !empty($instance['count']) ? $instance['count'] : 8;
        
        // Get all product categories
        $categories = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
        ));
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Category:', 'loomaama'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>">
                <option value=""><?php _e('Select Category', 'loomaama'); ?></option>
                <?php foreach ($categories as $cat) : ?>
                    <option value="<?php echo $cat->slug; ?>" <?php selected($category, $cat->slug); ?>>
                        <?php echo $cat->name; ?> (<?php echo $cat->count; ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('layout'); ?>"><?php _e('Layout:', 'loomaama'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('layout'); ?>" name="<?php echo $this->get_field_name('layout'); ?>">
                <option value="main-masonry" <?php selected($layout, 'main-masonry'); ?>><?php _e('Main Masonry', 'loomaama'); ?></option>
                <option value="reverse-mansory" <?php selected($layout, 'reverse-mansory'); ?>><?php _e('Reverse Masonry', 'loomaama'); ?></option>
                <option value="default-grid" <?php selected($layout, 'default-grid'); ?>><?php _e('Default Grid', 'loomaama'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Number of products:', 'loomaama'); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="number" step="1" min="1" max="20" value="<?php echo esc_attr($count); ?>" size="3">
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['category'] = (!empty($new_instance['category'])) ? sanitize_text_field($new_instance['category']) : '';
        $instance['layout'] = (!empty($new_instance['layout'])) ? sanitize_text_field($new_instance['layout']) : 'main-masonry';
        $instance['count'] = (!empty($new_instance['count'])) ? absint($new_instance['count']) : 8;
        
        return $instance;
    }
}

/**
 * Featured Products Widget
 */
/**
 * Featured Products Widget - FIXED
 */
class Loomaama_Featured_Products_Widget extends WP_Widget {
    
    public function __construct() {
        $widget_ops = array(
            'classname' => 'loomaama_featured_products',
            'description' => __('Display products by type (Featured, Sale, etc.) in a carousel', 'loomaama'),
            'customize_selective_refresh' => true,
        );
        parent::__construct('loomaama_featured_products', __('Loomaama: Flexible Products Carousel', 'loomaama'), $widget_ops);
    }
    
    public function widget($args, $instance) {
        // Variables
        $title = !empty($instance['title']) ? $instance['title'] : __('Products', 'loomaama');
        $count = !empty($instance['count']) ? $instance['count'] : 8;
        $product_type = !empty($instance['product_type']) ? $instance['product_type'] : 'recent_products';

        // Fix: Dynamic Query Construction
        $query_args = array(
            'post_type'      => 'product',
            'posts_per_page' => $count,
            'post_status'    => 'publish',
        );

        // Handle Product Type Logic
        switch ($product_type) {
            case 'featured_products':
                $query_args['tax_query'][] = array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                );
                break;
            case 'sale_products':
                $query_args['post__in'] = array_merge(array(0), wc_get_product_ids_on_sale());
                break;
            case 'best_selling_products':
                $query_args['meta_key'] = 'total_sales';
                $query_args['orderby']  = 'meta_value_num';
                break;
            case 'top_rated_products':
                $query_args['meta_key'] = '_wc_average_rating';
                $query_args['orderby']  = 'meta_value_num';
                break;
            case 'recent_products':
            default:
                $query_args['orderby'] = 'date';
                $query_args['order']   = 'DESC';
                break;
        }

        $products = new WP_Query($query_args);

        echo $args['before_widget'] ?? '';
        
        if ($products->have_posts()) {
            ?>
            <div class="container-fluid px-4 px-lg-5" data-aos="fade-up">
                <div class="row mb-4" data-aos="fade-up">
                    <div class="col">
                        <h3 class="headline"><?php echo esc_html($title); ?></h3>
                    </div>
                    <div class="col-auto">
                        <div class="carousel-nav d-none d-md-flex gap-2">
                            <button class="btn-featured-prev btn btn-outline-dark btn-sm p-2">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="btn-featured-next btn btn-outline-dark btn-sm p-2">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="swiper featured-slider" data-aos="fade-up">
                    <div class="swiper-wrapper" data-aos="fade-up">
                        <?php 
                        $slide_index = 0;
                        while ($products->have_posts()) : $products->the_post(); 
                            global $product;
                            ?>
                            <div class="swiper-slide" data-aos="fade-up">
                                <div class="lux-card">
                                    <div class="lux-img">
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
                                    </div>
                                    
                                    <div class="d-flex justify-content-between loom-title-the-price">
                                        <p class="lux-cat headline">
                                        <?php 
                                        $categories = wc_get_product_category_list($product->get_id());
                                        echo strip_tags($categories);
                                        ?>
                                        </p>
                                      
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="<?php the_permalink(); ?>" class="text-decoration-none product-item-text-details">
                                            <h6 class="mb-0 text-truncate"><?php the_title(); ?></h6>
                                        </a>
                                        <?php echo $product->get_price_html(); ?>
                                    </div>
                                </div>
                            </div>
                        <?php 
                        $slide_index++;
                        endwhile; ?>
                    </div>
                    <div class="swiper-pagination mt-4 position-relative"></div>
                </div>
            </div>
            <?php
            wp_reset_postdata();
        }
        
        echo $args['after_widget'] ?? '';
    }
    
    public function form($instance) {
        $title        = !empty($instance['title']) ? $instance['title'] : __('Products', 'loomaama');
        $count        = !empty($instance['count']) ? $instance['count'] : 8;
        $product_type = !empty($instance['product_type']) ? $instance['product_type'] : 'recent_products';

        $options = array(
            'featured_products'     => __('Featured Products', 'loomaama'),
            'sale_products'         => __('Sale Products', 'loomaama'),
            'best_selling_products' => __('Best Selling', 'loomaama'),
            'recent_products'       => __('Recent Products', 'loomaama'),
            'top_rated_products'    => __('Top Rated', 'loomaama'),
        );
        ?>
        <div class="widget-form-content">
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'loomaama'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('product_type'); ?>"><?php _e('Display Type:', 'loomaama'); ?></label>
                <select class="widefat" id="<?php echo $this->get_field_id('product_type'); ?>" name="<?php echo $this->get_field_name('product_type'); ?>">
                    <?php foreach ($options as $value => $label) : ?>
                        <option value="<?php echo esc_attr($value); ?>" <?php selected($product_type, $value); ?>><?php echo esc_html($label); ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Number of products:', 'loomaama'); ?></label>
                <input class="tiny-text" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="number" step="1" min="1" max="20" value="<?php echo esc_attr($count); ?>" size="3">
            </p>
        </div>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title']        = sanitize_text_field(($new_instance['title'] ?? ''));
        $instance['count']        = absint(($new_instance['count'] ?? ''));
        $instance['product_type'] = sanitize_text_field(($new_instance['product_type'] ?? ''));
        return $instance;
    }
}

/**
 * Hero Widget
 */
class Loomaama_Hero_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'loomaama_hero',
            __('Loomaama: Hero Section', 'loomaama'),
            array('description' => __('Add a hero section with video or image background', 'loomaama'))
        );
    }
    
    public function widget($args, $instance) {
    echo $args['before_widget'] ?? '';
    
    $hero_type = !empty($instance['hero_type']) ? $instance['hero_type'] : 'image';
    $title = !empty($instance['title']) ? $instance['title'] : '';
    $subtitle = !empty($instance['subtitle']) ? $instance['subtitle'] : '';
    $button_text = !empty($instance['button_text']) ? $instance['button_text'] : __('', 'loomaama');
    
    // Handle button URL
    $button_url = '#';
    if (!empty($instance['button_url'])) {
        $button_url = $instance['button_url'];
        if (strpos($button_url, 'http') !== 0 && strpos($button_url, '/') !== 0 && $button_url !== '#') {
            $page = get_page_by_path($button_url);
            if ($page) {
                $button_url = get_permalink($page);
            } else {
                $category = get_term_by('slug', $button_url, 'product_cat');
                if ($category) {
                    $button_url = get_term_link($category);
                }
            }
        }
    }
    
    $video_url = !empty($instance['video_url']) ? $instance['video_url'] : '';
    $image_url = !empty($instance['image_url']) ? $instance['image_url'] : '';
    $overlay_opacity = !empty($instance['overlay_opacity']) ? $instance['overlay_opacity'] : '0.25';
    $text_position = !empty($instance['text_position']) ? $instance['text_position'] : 'center';
    ?>
    
    <section class="hero-video" data-aos="fade-up" >
        <?php if ($hero_type === 'video' && !empty($video_url)) : ?>
            <video id="hero-video-<?php echo $this->id; ?>" autoplay muted loop playsinline>
                <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
            </video>
            
            <!-- Mute/Unmute Control -->
            <div class="hero-video-control">
                <button class="hero-mute-toggle" aria-label="<?php esc_attr_e('Mute/Unmute', 'loomaama'); ?>">
                    <span class="mute-icon">🔇</span>
                    <span class="unmute-icon" style="display:none;">🔊</span>
                </button>
            </div>
        <?php elseif (!empty($image_url)) : ?>
            <div class="hero-image" style="background-image: url('<?php echo esc_url($image_url); ?>');"></div>
        <?php endif; ?>
        
        <div class="hero-overlay" style="opacity: <?php echo esc_attr($overlay_opacity); ?>;"></div>

        <div class="hero-text" style="text-align: <?php echo esc_attr($text_position); ?>; <?php echo $text_position !== 'center' ? $text_position . ': 10%; transform: translateY(-50%); left: auto;' : ''; ?>">
            <?php if (!empty($title)) : ?>
                <h2 class="headline mb-4 text-white" data-aos="fade-up" ><?php echo wp_kses_post($title); ?></h2>
            <?php endif; ?>
            
            <?php if (!empty($subtitle)) : ?>
                <p class="text-secondary mb-4 text-white" data-aos="fade-up" ><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>
           
            <?php if (!empty($button_text) && !empty($button_url)) : ?>
                <a href="<?php echo esc_url($button_url); ?>" class="btn btn-dark px-4 py-3 btn-lux headline" data-aos="fade-up">
                    <?php echo esc_html($button_text); ?>
                </a>
            <?php endif; ?>
        </div>
    </section>
    <script>
(function() {
    var videoId = 'hero-video-<?php echo $this->id; ?>';
    var video = document.getElementById(videoId);
    if (!video) return;
    
    var container = video.closest('.hero-video');
    var toggleBtn = container ? container.querySelector('.hero-mute-toggle') : null;
    if (!toggleBtn) return;
    
    var muteIcon = toggleBtn.querySelector('.mute-icon');
    var unmuteIcon = toggleBtn.querySelector('.unmute-icon');
    
    function updateButton() {
        if (video.muted) {
            muteIcon.style.display = 'inline';
            unmuteIcon.style.display = 'none';
        } else {
            muteIcon.style.display = 'none';
            unmuteIcon.style.display = 'inline';
        }
    }
    
    toggleBtn.addEventListener('click', function(e) {
        e.preventDefault();
        video.muted = !video.muted;
        updateButton();
    });
    
    // Initial state
    updateButton();
})();
</script>
    <?php
    echo $args['after_widget'] ?? '';
}
    
    public function form($instance) {
        $hero_type = !empty($instance['hero_type']) ? $instance['hero_type'] : 'image';
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $subtitle = !empty($instance['subtitle']) ? $instance['subtitle'] : '';
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : __('', 'loomaama');
        $button_url = !empty($instance['button_url']) ? $instance['button_url'] : '';
        $video_url = !empty($instance['video_url']) ? $instance['video_url'] : '';
        $image_url = !empty($instance['image_url']) ? $instance['image_url'] : '';
        $overlay_opacity = !empty($instance['overlay_opacity']) ? $instance['overlay_opacity'] : '0.25';
        $text_position = !empty($instance['text_position']) ? $instance['text_position'] : 'center';
        
        // Get all pages
        $pages = get_pages(array(
            'sort_order' => 'ASC',
            'sort_column' => 'post_title',
            'post_status' => 'publish'
        ));
        
        // Get all product categories if WooCommerce is active
        $categories = array();
        if (class_exists('WooCommerce')) {
            $categories = get_terms(array(
                'taxonomy' => 'product_cat',
                'hide_empty' => false,
                'orderby' => 'name',
                'order' => 'ASC'
            ));
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('hero_type'); ?>"><?php _e('Hero Type:', 'loomaama'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('hero_type'); ?>" name="<?php echo $this->get_field_name('hero_type'); ?>">
                <option value="image" <?php selected($hero_type, 'image'); ?>><?php _e('Image', 'loomaama'); ?></option>
                <option value="video" <?php selected($hero_type, 'video'); ?>><?php _e('Video', 'loomaama'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('subtitle'); ?>"><?php _e('Subtitle:', 'loomaama'); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id('subtitle'); ?>" name="<?php echo $this->get_field_name('subtitle'); ?>" rows="3"><?php echo esc_textarea($subtitle); ?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('button_text'); ?>"><?php _e('Button Text:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('button_text'); ?>" name="<?php echo $this->get_field_name('button_text'); ?>" type="text" value="<?php echo esc_attr($button_text); ?>">
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('button_url'); ?>"><?php _e('Button Link:', 'loomaama'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('button_url'); ?>" name="<?php echo $this->get_field_name('button_url'); ?>">
                <option value="" <?php selected($button_url, ''); ?>><?php _e('-- Select a Link (Optional) --', 'loomaama'); ?></option>
                <option value="#" <?php selected($button_url, '#'); ?>><?php _e('Link to Homepage (#)', 'loomaama'); ?></option>
                
                <?php if (!empty($pages)) : ?>
                    <optgroup label="<?php _e('Pages:', 'loomaama'); ?>">
                        <?php foreach ($pages as $page) : ?>
                            <option value="<?php echo esc_attr($page->post_name); ?>" <?php selected($button_url, $page->post_name); ?>>
                                <?php echo esc_html($page->post_title); ?>
                            </option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endif; ?>
                
                <?php if (!empty($categories) && !is_wp_error($categories)) : ?>
                    <optgroup label="<?php _e('Product Categories:', 'loomaama'); ?>">
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?php echo esc_attr($category->slug); ?>" <?php selected($button_url, $category->slug); ?>>
                                <?php echo esc_html($category->name); ?> (<?php echo $category->count; ?> products)
                            </option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endif; ?>
                
                <optgroup label="<?php _e('Custom URL:', 'loomaama'); ?>">
                    <option value="custom" <?php selected($button_url, 'custom'); ?>><?php _e('-- Enter Custom URL Below --', 'loomaama'); ?></option>
                </optgroup>
            </select>
        </p>
        
        <p id="custom-url-field-<?php echo $this->get_field_id('button_url'); ?>" style="<?php echo ($button_url !== 'custom' && strpos($button_url, 'http') !== 0 && $button_url !== '#' && !empty($button_url) && !get_page_by_path($button_url) && !get_term_by('slug', $button_url, 'product_cat')) ? '' : 'display: none;'; ?>">
            <label for="<?php echo $this->get_field_id('custom_url'); ?>"><?php _e('Custom URL:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('custom_url'); ?>" name="<?php echo $this->get_field_name('custom_url'); ?>" type="url" value="<?php echo esc_url((strpos($button_url, 'http') === 0) ? $button_url : ''); ?>" placeholder="https://example.com">
            <small><?php _e('Enter a full URL (e.g., https://example.com)', 'loomaama'); ?></small>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('image_url'); ?>"><?php _e('Image URL (for image type):', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('image_url'); ?>" name="<?php echo $this->get_field_name('image_url'); ?>" type="url" value="<?php echo esc_url($image_url); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('video_url'); ?>"><?php _e('Video URL (MP4 for video type):', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('video_url'); ?>" name="<?php echo $this->get_field_name('video_url'); ?>" type="url" value="<?php echo esc_url($video_url); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('overlay_opacity'); ?>"><?php _e('Overlay Opacity (0-1):', 'loomaama'); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('overlay_opacity'); ?>" name="<?php echo $this->get_field_name('overlay_opacity'); ?>" type="number" step="0.05" min="0" max="1" value="<?php echo esc_attr($overlay_opacity); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('text_position'); ?>"><?php _e('Text Position:', 'loomaama'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('text_position'); ?>" name="<?php echo $this->get_field_name('text_position'); ?>">
                <option value="left" <?php selected($text_position, 'left'); ?>><?php _e('Left', 'loomaama'); ?></option>
                <option value="center" <?php selected($text_position, 'center'); ?>><?php _e('Center', 'loomaama'); ?></option>
                <option value="right" <?php selected($text_position, 'right'); ?>><?php _e('Right', 'loomaama'); ?></option>
            </select>
        </p>
        
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            var selectId = '<?php echo $this->get_field_id('button_url'); ?>';
            var customFieldId = '#custom-url-field-' + selectId;
            
            $('#' + selectId).on('change', function() {
                if ($(this).val() === 'custom') {
                    $(customFieldId).show();
                } else {
                    $(customFieldId).hide();
                }
            });
        });
        </script>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['hero_type'] = (!empty($new_instance['hero_type'])) ? sanitize_text_field($new_instance['hero_type']) : 'image';
        $instance['title'] = (!empty($new_instance['title'])) ? wp_kses_post($new_instance['title']) : '';
        $instance['subtitle'] = (!empty($new_instance['subtitle'])) ? sanitize_textarea_field($new_instance['subtitle']) : '';
        $instance['button_text'] = (!empty($new_instance['button_text'])) ? sanitize_text_field($new_instance['button_text']) : '';
        
        // Handle button_url - could be page slug, category slug, or custom URL
        if (!empty($new_instance['button_url'])) {
            // Check if custom URL was selected
            if ($new_instance['button_url'] === 'custom' && !empty($new_instance['custom_url'])) {
                $instance['button_url'] = esc_url_raw(($new_instance['custom_url'] ?? ''));
            } 
            // If it's a URL (starts with http or #), treat as URL
            elseif (strpos(($new_instance['button_url'] ?? ''), 'http') === 0 || ($new_instance['button_url'] ?? '') === '#') {
                $instance['button_url'] = esc_url_raw(($new_instance['button_url'] ?? ''));
            } 
            else {
                // Otherwise treat as a page slug or category slug
                $instance['button_url'] = sanitize_text_field(($new_instance['button_url'] ?? ''));
            }
        } else {
            $instance['button_url'] = '#';
        }
        
        $instance['image_url'] = (!empty($new_instance['image_url'])) ? esc_url_raw($new_instance['image_url']) : '';
        $instance['video_url'] = (!empty($new_instance['video_url'])) ? esc_url_raw($new_instance['video_url']) : '';
        $instance['overlay_opacity'] = (!empty($new_instance['overlay_opacity'])) ? floatval($new_instance['overlay_opacity']) : '0.25';
        $instance['text_position'] = (!empty($new_instance['text_position'])) ? sanitize_text_field($new_instance['text_position']) : 'center';
        
        return $instance;
    }
}

/**
 * Text Block Widget with Image Option
 */
/**
 * Text Block Widget with Image Option and WooCommerce Categories
 */
class Loomaama_Text_Block_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'loomaama_text_block',
            __('Loomaama: Text Block', 'loomaama'),
            array('description' => __('Add a text block with image URL, page links, and WooCommerce links', 'loomaama'))
        );
    }
    
  public function widget($args, $instance) {
        $title       = !empty($instance['title']) ? $instance['title'] : '';
        $text        = !empty($instance['text']) ? $instance['text'] : '';
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : '';
        $button_url  = !empty($instance['button_url']) ? $instance['button_url'] : '';
        $alignment   = !empty($instance['alignment']) ? $instance['alignment'] : 'center';
        $background  = !empty($instance['background']) ? $instance['background'] : 'transparent';
        $image_url   = !empty($instance['image_url']) ? $instance['image_url'] : '';
        $image_pos   = !empty($instance['image_position']) ? $instance['image_position'] : 'none';
        $aos_enabled = !empty($instance['aos_enabled']) ? $instance['aos_enabled'] : true;
        
        // 1. Calculate the background style
        $bg_style = '';
        if ($background !== 'transparent' && !empty($background)) {
            if (filter_var($background, FILTER_VALIDATE_URL)) {
                // It's an image URL
                $bg_style = 'background-image: url(' . esc_url($background) . '); background-size: cover; background-position: center;';
            } else {
                // It's a hex code or color name
                $bg_style = 'background-color: ' . esc_attr($background) . ';';
            }
        }

        // 2. Inject the style attribute into the before_widget string
        if (!empty($bg_style)) {
            $style_attribute = 'style="' . $bg_style . '" ';
            // This looks for 'class="' and pastes our style right before it
            $args['before_widget'] = str_replace('class="', $style_attribute . 'class="', $args['before_widget']);
        }
        
        // Add AOS attributes if enabled
        $aos_attributes = '';
        if ($aos_enabled) {
            $aos_attributes = ' data-aos="fade-up" ';
        }
        $attachment_id = attachment_url_to_postid($image_url);

        $image_url = wp_get_attachment_image_url($attachment_id, 'medium_large');
        // 3. Now output the modified before_widget (your <section> tag)
        echo $args['before_widget'] ?? '';
        ?>
        
        <div class="container-fluid"<?php echo $aos_attributes; ?>>
            <div class="row align-items-center">
                <?php if (!empty($image_url) && $image_pos == 'left') : ?>
                <div class="col-lg-6 py-4 p-md-0" data-aos="fade-up">
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" class="img-fluid rounded">
                </div>
                <?php endif; ?>
                
                <div class="<?php echo (!empty($image_url) && $image_pos != 'none') ? 'col-lg-6 me-auto' : 'col-lg-6 '; ?> text-<?php echo esc_attr($alignment); ?>" data-aos="fade-up">
                    <div class="p-sm-3 p-lg-5">
                    <?php if (!empty($title)) : ?>
                        <h2 class="headline mb-4"><?php echo esc_html($title); ?></h2>
                    <?php endif; ?>
                    
                    <?php if (!empty($text)) : ?>
                        <div class="text-secondary" style="line-height: 1.8;"><?php echo wpautop(esc_html($text)); ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($button_text) && !empty($button_url)) : ?>
                        <a href="<?php echo esc_url($button_url); ?>" class="btn btn-outline-dark px-4 py-3 btn-lux headline mt-4" data-aos="fade-up">
                            <?php echo esc_html($button_text); ?>
                        </a>
                    <?php endif; ?>
                    </div>
                </div>
                
                <?php if (!empty($image_url) && $image_pos == 'right') : ?>
                <div class="col-lg-6 p-0" data-aos="fade-up" >
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" class="img-fluid rounded">
                </div>
                <?php endif; ?>
            </div>
        </div>        
        
        <?php
        // 4. Output the closing tag (your </section>)
        echo $args['after_widget'] ?? '';
    }
    
    public function form($instance) {
        $title      = isset($instance['title']) ? $instance['title'] : '';
        $text       = isset($instance['text']) ? $instance['text'] : '';
        $image_url  = isset($instance['image_url']) ? $instance['image_url'] : '';
        $image_pos  = isset($instance['image_position']) ? $instance['image_position'] : 'none';
        $button_text = isset($instance['button_text']) ? $instance['button_text'] : '';
        $button_url  = isset($instance['button_url']) ? $instance['button_url'] : '';
        $alignment   = isset($instance['alignment']) ? $instance['alignment'] : 'center';
        $background  = isset($instance['background']) ? $instance['background'] : 'transparent';
        $aos_enabled = isset($instance['aos_enabled']) ? $instance['aos_enabled'] : true;

        // Fetch WordPress Pages
        $pages = get_pages();

        // Fetch WooCommerce categories
        $categories = array();
        if (class_exists('WooCommerce')) {
            $categories = get_terms(array('taxonomy' => 'product_cat', 'hide_empty' => false));
        }
        ?>
        
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text:', 'loomaama'); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" rows="4"><?php echo esc_textarea($text); ?></textarea>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('image_url'); ?>"><?php _e('Image URL:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('image_url'); ?>" name="<?php echo $this->get_field_name('image_url'); ?>" type="text" value="<?php echo esc_url($image_url); ?>" placeholder="https://example.com/image.jpg">
            <small><?php _e('Paste the full URL of the image here.', 'loomaama'); ?></small>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('image_position'); ?>"><?php _e('Image Position:', 'loomaama'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('image_position'); ?>" name="<?php echo $this->get_field_name('image_position'); ?>">
                <option value="none" <?php selected($image_pos, 'none'); ?>><?php _e('No Image', 'loomaama'); ?></option>
                <option value="left" <?php selected($image_pos, 'left'); ?>><?php _e('Left', 'loomaama'); ?></option>
                <option value="right" <?php selected($image_pos, 'right'); ?>><?php _e('Right', 'loomaama'); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('button_text'); ?>"><?php _e('Button Text:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('button_text'); ?>" name="<?php echo $this->get_field_name('button_text'); ?>" type="text" value="<?php echo esc_attr($button_text); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('button_url'); ?>"><?php _e('Button Link (Page or Category):', 'loomaama'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('button_url'); ?>" name="<?php echo $this->get_field_name('button_url'); ?>">
                <option value=""><?php _e('Select a link destination...', 'loomaama'); ?></option>
                
                <optgroup label="<?php _e('WordPress Pages', 'loomaama'); ?>">
                    <?php foreach ($pages as $page) : $link = get_permalink($page->ID); ?>
                        <option value="<?php echo esc_url($link); ?>" <?php selected($button_url, $link); ?>>
                            <?php echo esc_html($page->post_title); ?>
                        </option>
                    <?php endforeach; ?>
                </optgroup>

                <?php if (!empty($categories) && !is_wp_error($categories)) : ?>
                <optgroup label="<?php _e('Product Categories', 'loomaama'); ?>">
                    <?php foreach ($categories as $cat) : $link = get_term_link($cat); ?>
                        <option value="<?php echo esc_url($link); ?>" <?php selected($button_url, $link); ?>>
                            <?php echo esc_html($cat->name); ?>
                        </option>
                    <?php endforeach; ?>
                </optgroup>
                <?php endif; ?>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('alignment'); ?>"><?php _e('Alignment:', 'loomaama'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('alignment'); ?>" name="<?php echo $this->get_field_name('alignment'); ?>">
                <option value="start" <?php selected($alignment, 'start'); ?>><?php _e('Left', 'loomaama'); ?></option>
                <option value="center" <?php selected($alignment, 'center'); ?>><?php _e('Center', 'loomaama'); ?></option>
                <option value="end" <?php selected($alignment, 'end'); ?>><?php _e('Right', 'loomaama'); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('background'); ?>"><?php _e('Background (Hex Code OR Image URL):', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('background'); ?>" name="<?php echo $this->get_field_name('background'); ?>" type="text" value="<?php echo esc_attr($background); ?>" placeholder="#f4f4f4 OR https://...">
        </p>
        
        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id('aos_enabled'); ?>" name="<?php echo $this->get_field_name('aos_enabled'); ?>" value="1" <?php checked($aos_enabled, 1); ?>>
            <label for="<?php echo $this->get_field_id('aos_enabled'); ?>"><?php _e('Enable Scroll Animation (AOS)', 'loomaama'); ?></label>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title']      = sanitize_text_field(($new_instance['title'] ?? ''));
        $instance['text']       = sanitize_textarea_field(($new_instance['text'] ?? ''));
        $instance['image_url']  = esc_url_raw(($new_instance['image_url'] ?? ''));
        $instance['image_position'] = sanitize_text_field(($new_instance['image_position'] ?? ''));
        $instance['button_text'] = sanitize_text_field(($new_instance['button_text'] ?? ''));
        $instance['button_url']  = esc_url_raw(($new_instance['button_url'] ?? ''));
        $instance['alignment']   = sanitize_text_field(($new_instance['alignment'] ?? ''));
        $instance['background']  = sanitize_text_field(($new_instance['background'] ?? ''));
        $instance['aos_enabled'] = !empty($new_instance['aos_enabled']) ? 1 : 0;
        return $instance;
    }
}
/**
 * Image Text Widget
 */
class Loomaama_Image_Text_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'loomaama_image_text',
            __('Loomaama: Image with Text', 'loomaama'),
            array('description' => __('Display an image with text side by side', 'loomaama'))
        );
    }
    
    public function widget($args, $instance) {
        echo $args['before_widget'] ?? '';
        
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $text = !empty($instance['text']) ? $instance['text'] : '';
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : '';
        $button_url = !empty($instance['button_url']) ? $instance['button_url'] : '';
        $image_url = !empty($instance['image_url']) ? $instance['image_url'] : '';
        $image_position = !empty($instance['image_position']) ? $instance['image_position'] : 'left';
        $background = !empty($instance['background']) ? $instance['background'] : 'transparent';
        $aos_enabled = !empty($instance['aos_enabled']) ? $instance['aos_enabled'] : true;
        
        $row_class = ($image_position === 'right') ? 'flex-row-reverse' : '';
        $image_aos = ($image_position === 'right') ? 'fade-left' : 'fade-right';
        $content_aos = ($image_position === 'right') ? 'fade-right' : 'fade-left';

        $attachment_id = attachment_url_to_postid($image_url);
        $image_url = wp_get_attachment_image_url($attachment_id, 'medium_large');
        ?>
        
        <div style="background: <?php echo esc_attr($background); ?>;" <?php echo $aos_enabled ? 'data-aos="fade-up"' : ''; ?>>
            <div class="container-fluid">
                <div class="row align-items-center <?php echo esc_attr($row_class); ?>">
                    <div class="col-lg-6" data-aos="fade-up" >
                        <img src="<?php echo esc_url($image_url); ?>" class="img-fluid w-100" alt="<?php echo esc_attr($title); ?>">
                    </div>
                    <div class="col-lg-6" data-aos="fade-up" >
                        <div class="pt-3 p-sm-4 p-lg-5">
                            <?php if (!empty($title)) : ?>
                                <h2 class="headline mb-4"><?php echo esc_html($title); ?></h2>
                            <?php endif; ?>
                            
                            <?php if (!empty($text)) : ?>
                                <p class="text-secondary mb-4"><?php echo wp_kses_post($text); ?></p>
                            <?php endif; ?>
                            
                            <?php if (!empty($button_text) && !empty($button_url)) : ?>
                                <a href="<?php echo esc_url($button_url); ?>" class="btn btn-outline-dark px-4 py-3 btn-lux headline" data-aos="fade-up" >
                                    <?php echo esc_html($button_text); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
         </div>
        
        <?php
        echo $args['after_widget'] ?? '';
    }
    
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $text = !empty($instance['text']) ? $instance['text'] : '';
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : '';
        $button_url = !empty($instance['button_url']) ? $instance['button_url'] : '';
        $image_url = !empty($instance['image_url']) ? $instance['image_url'] : '';
        $image_position = !empty($instance['image_position']) ? $instance['image_position'] : 'left';
        $background = !empty($instance['background']) ? $instance['background'] : 'transparent';
        $aos_enabled = isset($instance['aos_enabled']) ? $instance['aos_enabled'] : true;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text:', 'loomaama'); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" rows="3"><?php echo esc_textarea($text); ?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('button_text'); ?>"><?php _e('Button Text:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('button_text'); ?>" name="<?php echo $this->get_field_name('button_text'); ?>" type="text" value="<?php echo esc_attr($button_text); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('button_url'); ?>"><?php _e('Button URL:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('button_url'); ?>" name="<?php echo $this->get_field_name('button_url'); ?>" type="url" value="<?php echo esc_url($button_url); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('image_url'); ?>"><?php _e('Image URL:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('image_url'); ?>" name="<?php echo $this->get_field_name('image_url'); ?>" type="url" value="<?php echo esc_url($image_url); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('image_position'); ?>"><?php _e('Image Position:', 'loomaama'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('image_position'); ?>" name="<?php echo $this->get_field_name('image_position'); ?>">
                <option value="left" <?php selected($image_position, 'left'); ?>><?php _e('Left', 'loomaama'); ?></option>
                <option value="right" <?php selected($image_position, 'right'); ?>><?php _e('Right', 'loomaama'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('background'); ?>"><?php _e('Background Color (hex or transparent):', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('background'); ?>" name="<?php echo $this->get_field_name('background'); ?>" type="text" value="<?php echo esc_attr($background); ?>">
        </p>
        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id('aos_enabled'); ?>" name="<?php echo $this->get_field_name('aos_enabled'); ?>" value="1" <?php checked($aos_enabled, 1); ?>>
            <label for="<?php echo $this->get_field_id('aos_enabled'); ?>"><?php _e('Enable Scroll Animation (AOS)', 'loomaama'); ?></label>
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['text'] = (!empty($new_instance['text'])) ? wp_kses_post($new_instance['text']) : '';
        $instance['button_text'] = (!empty($new_instance['button_text'])) ? sanitize_text_field($new_instance['button_text']) : '';
        $instance['button_url'] = (!empty($new_instance['button_url'])) ? esc_url_raw($new_instance['button_url']) : '';
        $instance['image_url'] = (!empty($new_instance['image_url'])) ? esc_url_raw($new_instance['image_url']) : '';
        $instance['image_position'] = (!empty($new_instance['image_position'])) ? sanitize_text_field($new_instance['image_position']) : 'left';
        $instance['background'] = (!empty($new_instance['background'])) ? sanitize_text_field($new_instance['background']) : 'transparent';
        $instance['aos_enabled'] = !empty($new_instance['aos_enabled']) ? 1 : 0;
        
        return $instance;
    }
}

/**
 * Quote Widget
 */
class Loomaama_Quote_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'loomaama_quote',
            __('Loomaama: Quote Block', 'loomaama'),
            array('description' => __('Display a quote with attribution', 'loomaama'))
        );
    }
    
    public function widget($args, $instance) {
        echo $args['before_widget'] ?? '';
        
        $subtitle = !empty($instance['subtitle']) ? $instance['subtitle'] : '';
        $quote = !empty($instance['quote']) ? $instance['quote'] : '';
        $author = !empty($instance['author']) ? $instance['author'] : '';
        $background = !empty($instance['background']) ? $instance['background'] : '#F7F3EE';
        $aos_enabled = !empty($instance['aos_enabled']) ? $instance['aos_enabled'] : true;
        ?>
        
        <div class="text-center" style="background: <?php echo esc_attr($background); ?>;" <?php echo $aos_enabled ? 'data-aos="fade-up"' : ''; ?>>
            <div class="container py-5">
                <div class="col-md-8 offset-md-2">
                    <?php if (!empty($subtitle)) : ?>
                        <h6 class="headline text-secondary mb-4" data-aos="fade-up"><?php echo esc_html($subtitle); ?></h6>
                    <?php endif; ?>
                    
                    <?php if (!empty($quote)) : ?>
                        <blockquote class="headline mb-4 py-5 bs-1" style="text-align:justify;" >
                            <?php echo wp_kses_post($quote); ?>
                        </blockquote>
                    <?php endif; ?>
                    
                    <?php if (!empty($author)) : ?>
                        <p class="headline text-secondary" data-aos="fade-up">— <?php echo esc_html($author); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <?php
        echo $args['after_widget'] ?? '';
    }
    
    public function form($instance) {
        $subtitle = !empty($instance['subtitle']) ? $instance['subtitle'] : '';
        $quote = !empty($instance['quote']) ? $instance['quote'] : '';
        $author = !empty($instance['author']) ? $instance['author'] : '';
        $background = !empty($instance['background']) ? $instance['background'] : '#F7F3EE';
        $aos_enabled = isset($instance['aos_enabled']) ? $instance['aos_enabled'] : true;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('subtitle'); ?>"><?php _e('Subtitle (optional):', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('subtitle'); ?>" name="<?php echo $this->get_field_name('subtitle'); ?>" type="text" value="<?php echo esc_attr($subtitle); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('quote'); ?>"><?php _e('Quote:', 'loomaama'); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id('quote'); ?>" name="<?php echo $this->get_field_name('quote'); ?>" rows="3"><?php echo esc_textarea($quote); ?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('author'); ?>"><?php _e('Author:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('author'); ?>" name="<?php echo $this->get_field_name('author'); ?>" type="text" value="<?php echo esc_attr($author); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('background'); ?>"><?php _e('Background Color:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('background'); ?>" name="<?php echo $this->get_field_name('background'); ?>" type="text" value="<?php echo esc_attr($background); ?>">
        </p>
        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id('aos_enabled'); ?>" name="<?php echo $this->get_field_name('aos_enabled'); ?>" value="1" <?php checked($aos_enabled, 1); ?>>
            <label for="<?php echo $this->get_field_id('aos_enabled'); ?>"><?php _e('Enable Scroll Animation (AOS)', 'loomaama'); ?></label>
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['subtitle'] = (!empty($new_instance['subtitle'])) ? sanitize_text_field($new_instance['subtitle']) : '';
        $instance['quote'] = (!empty($new_instance['quote'])) ? wp_kses_post($new_instance['quote']) : '';
        $instance['author'] = (!empty($new_instance['author'])) ? sanitize_text_field($new_instance['author']) : '';
        $instance['background'] = (!empty($new_instance['background'])) ? sanitize_text_field($new_instance['background']) : '#F7F3EE';
        $instance['aos_enabled'] = !empty($new_instance['aos_enabled']) ? 1 : 0;
        
        return $instance;
    }
}

/**
 * Collections Grid Category Widget
 */
class Loomaama_Collections_Grid_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'loomaama_collections_grid',
            __('Loomaama: Collections Grid', 'loomaama'),
            array('description' => __('Display product categories in a grid', 'loomaama'))
        );
    }
    
    public function widget($args, $instance) {
        echo $args['before_widget'] ?? '';
        
        $title = !empty($instance['title']) ? $instance['title'] : __('Discover The Collections', 'loomaama');
        $category_count = !empty($instance['category_count']) ? $instance['category_count'] : 3;
        $selected_categories = !empty($instance['selected_categories']) ? $instance['selected_categories'] : array();
        $aos_enabled = !empty($instance['aos_enabled']) ? $instance['aos_enabled'] : true;
        
        // If specific categories are selected, use those
        if (!empty($selected_categories)) {
            $product_categories = get_terms(array(
                'taxonomy' => 'product_cat',
                'hide_empty' => true,
                'include' => $selected_categories,
                'orderby' => 'include', // Maintain the order of selected categories
            ));
        } else {
            // Otherwise get the latest categories
            $product_categories = get_terms(array(
                'taxonomy' => 'product_cat',
                'hide_empty' => true,
                'number' => $category_count,
            ));
        }
        
        if (!empty($product_categories)) :
        ?>
        <div class="container category_collection" <?php echo $aos_enabled ? '' : ''; ?>>
            <div class="title-section mb-5 text-center" data-aos="fade-up">
                <h3 class="headline"><?php echo esc_html($title); ?></h3>
            </div>

            <div class="row align-items-stretch custom-3x2">
                <?php
                $i=0;
                foreach ($product_categories as $index => $category) :
                    $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                    $image = wp_get_attachment_url($thumbnail_id, 'medium_large');
                    $count = $category->count;
                    $aos_delay = 100 + ($i * 100);
                    
                    if (!$image) {
                        $image = LOOMAAMA_URI . '/assets/images/model-outer-image.jpg';
                    }
                    
                    if ($i == 0) : ?>                  
                        <div class="col-md-8 col-lg-8 p-0" data-aos="fade-up">
                            <div class="product-item full-height bg-gray">
                                <div class="product-category">
                                    <a href="<?php echo esc_url(get_term_link($category)); ?>" class="btn btn-outline-dark " data-aos="fade-up-bottom">
                                        <?php echo esc_html($category->name); ?> <span><?php echo sprintf(_n('%s <br> item', '%s <br> items', $count, 'loomaama'), $count); ?></span>
                                    </a>
                                </div>
                                <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($category->name); ?>">

                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 p-0">
                    <?php else : ?>                      
                        <div class="product-item sm-height bg-gray" data-aos="fade-up">
                            <div class="product-category">
                                <a href="<?php echo esc_url(get_term_link($category)); ?>" class="btn btn-outline-dark" data-aos="fade-up-bottom" >
                                    <?php echo esc_html($category->name); ?> <span><?php echo sprintf(_n('%s <br> item', '%s <br> items', $count, 'loomaama'), $count); ?></span>
                                </a>
                            </div>
                            <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($category->name); ?>">
                        </div>
                        <?php if ($index === count($product_categories) - 1) echo '</div>'; ?>
                    <?php endif; ?>
                    <?php $i++; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        endif;
        
        echo $args['after_widget'] ?? '';
    }
    
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Discover The Collections', 'loomaama');
        $category_count = !empty($instance['category_count']) ? $instance['category_count'] : 3;
        $selected_categories = !empty($instance['selected_categories']) ? $instance['selected_categories'] : array();
        $aos_enabled = isset($instance['aos_enabled']) ? $instance['aos_enabled'] : true;
        
        // Get all product categories
        $all_categories = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'orderby' => 'name',
            'order' => 'ASC',
        ));
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('category_count'); ?>"><?php _e('Number of categories (if no specific categories selected):', 'loomaama'); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('category_count'); ?>" name="<?php echo $this->get_field_name('category_count'); ?>" type="number" step="1" min="1" max="10" value="<?php echo esc_attr($category_count); ?>" size="3">
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('selected_categories'); ?>"><?php _e('Select specific categories (optional):', 'loomaama'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('selected_categories'); ?>" name="<?php echo $this->get_field_name('selected_categories'); ?>[]" multiple="multiple" size="5">
                <?php foreach ($all_categories as $category) : ?>
                    <option value="<?php echo esc_attr($category->term_id); ?>" <?php echo in_array($category->term_id, $selected_categories) ? 'selected="selected"' : ''; ?>>
                        <?php echo esc_html($category->name); ?> (<?php echo $category->count; ?> items)
                    </option>
                <?php endforeach; ?>
            </select>
            <small><?php _e('Hold Ctrl/Cmd to select multiple categories. If you select categories here, they will be displayed instead of the latest ones.', 'loomaama'); ?></small>
        </p>
        
        <p>
            <small><?php _e('Note: For the grid layout to look best, select 3 categories total.', 'loomaama'); ?></small>
        </p>
        
        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id('aos_enabled'); ?>" name="<?php echo $this->get_field_name('aos_enabled'); ?>" value="1" <?php checked($aos_enabled, 1); ?>>
            <label for="<?php echo $this->get_field_id('aos_enabled'); ?>"><?php _e('Enable Scroll Animation (AOS)', 'loomaama'); ?></label>
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['category_count'] = (!empty($new_instance['category_count'])) ? absint($new_instance['category_count']) : 3;
        
        // Sanitize selected categories
        if (!empty($new_instance['selected_categories']) && is_array($new_instance['selected_categories'])) {
            $instance['selected_categories'] = array_map('absint', (array) ($new_instance['selected_categories'] ?? array()));
        } else {
            $instance['selected_categories'] = array();
        }
        
        $instance['aos_enabled'] = !empty($new_instance['aos_enabled']) ? 1 : 0;
        
        return $instance;
    }
}

/**
 * Seasonal Edit Widget with Product Carousel - FIXED
 */
class Loomaama_Seasonal_Edit_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'loomaama_seasonal_edit',
            __('Loomaama: Seasonal Edit with Carousel', 'loomaama'),
            array('description' => __('Display a seasonal edit section with text and product carousel', 'loomaama'))
        );
    }
    
    public function widget($args, $instance) {
        // Safely output before_widget (this already includes the opening section tag)
        if (isset($args['before_widget'])) {
            echo $args['before_widget'] ?? '';
        }
        
        $badge_text = !empty($instance['badge_text']) ? $instance['badge_text'] : __('The Seasonal Edit', 'loomaama');
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $description = !empty($instance['description']) ? $instance['description'] : '';
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : __('VIEW COLLECTION', 'loomaama');
        $aos_enabled = !empty($instance['aos_enabled']) ? $instance['aos_enabled'] : true;
        
        // Handle button URL
        $button_url = '#';
        if (!empty($instance['button_url'])) {
            if (strpos($instance['button_url'], 'http') === 0 || $instance['button_url'] === '#') {
                $button_url = $instance['button_url'];
            } else {
                $category = get_term_by('slug', $instance['button_url'], 'product_cat');
                if ($category) {
                    $button_url = get_term_link($category);
                }
            }
        }
        
        // Get selected category
        $selected_category = !empty($instance['selected_category']) ? $instance['selected_category'] : '';
        $products_per_page = !empty($instance['products_per_page']) ? intval($instance['products_per_page']) : 12;
        
        // Query products by category
        $query_args = array(
            'post_type' => 'product',
            'posts_per_page' => $products_per_page,
            'orderby' => 'date',
            'order' => 'DESC'
        );
        
        if (!empty($selected_category)) {
            $query_args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $selected_category
                )
            );
        }
        
        $products = new WP_Query($query_args);
        ?>
        
        <div class="container-fluid px-4 px-lg-5" <?php echo $aos_enabled ? 'data-aos="fade-up" ' : ''; ?>>
            <div class="row">
                <div class="col-lg-4 mb-4" data-aos="fade-up" >
                    <div class="Seasonal-edit-content">
                        <span class="headline text-secondary mb-3 d-block" style="font-size: 10px;" data-aos="fade-up" ><?php echo esc_html($badge_text); ?></span>
                        <h3 class="headline" data-aos="fade-up" ><?php echo esc_html($title); ?></h3>
                        <p class="text-secondary" data-aos="fade-up" ><?php echo esc_html($description); ?></p>
                        <a href="<?php echo esc_url($button_url); ?>" class="btn btn-outline-dark px-4 py-3 btn-lux headline mt-3" >
                            <?php echo esc_html($button_text); ?> 
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-8" data-aos="fade-up" >
                    <div class="swiper seasonal-slider">
                        <div class="swiper-wrapper">
                            <?php 
                            if ($products->have_posts()) :
                                $slide_index = 0;
                                while ($products->have_posts()) : $products->the_post();
                                    global $product;
                                    $categories = wc_get_product_category_list($product->get_id());
                                    $cat_name = strip_tags($categories);
                                    $first_cat = explode(',', $cat_name);
                                    $cat_display = !empty($first_cat[0]) ? trim($first_cat[0]) : __('NETTEL', 'loomaama');
                                    $slide_delay = 300 + ($slide_index * 50);
                            ?>
                            <div class="swiper-slide" data-aos="fade-up" >
                                <div class="lux-card">
                                    <div class="lux-img">
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
                                    </div>
                                    
                                     <div class="d-flex justify-content-between loom-title-the-price">
                                        <p class="lux-cat headline"><?php echo esc_html($cat_display); ?></p>
                                       
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="<?php the_permalink(); ?>" class="text-decoration-none product-item-text-details">
                                            <h6 class="mb-0 text-truncate "><?php the_title(); ?></h6>
                                        </a>
                                        <span class="text-secondary"><?php echo $product->get_price_html(); ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php 
                                $slide_index++;
                                endwhile;
                                wp_reset_postdata();
                            else:
                                echo '<div class="col-12"><p>' . __('No products found in this category.', 'loomaama') . '</p></div>';
                            endif; 
                            ?>
                        </div>
                    </div>      
                </div>
            </div>
        </div>
        
        <?php
        // Safely output after_widget (this closes the section tag)
        if (isset($args['after_widget'])) {
            echo $args['after_widget'] ?? '';
        }
    }
    
    public function form($instance) {
        $badge_text = !empty($instance['badge_text']) ? $instance['badge_text'] : __('The Seasonal Edit', 'loomaama');
        $title = !empty($instance['title']) ? $instance['title'] : __('TOGETHER WE\'RE RESHAPING THE TEXTILES INDUSTRY', 'loomaama');
        $description = !empty($instance['description']) ? $instance['description'] : '';
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : __('VIEW COLLECTION', 'loomaama');
        $button_url = !empty($instance['button_url']) ? $instance['button_url'] : '';
        $selected_category = !empty($instance['selected_category']) ? $instance['selected_category'] : '';
        $products_per_page = !empty($instance['products_per_page']) ? $instance['products_per_page'] : 12;
        $aos_enabled = isset($instance['aos_enabled']) ? $instance['aos_enabled'] : true;
        
        // Get WooCommerce categories
        $categories = array();
        if (class_exists('WooCommerce')) {
            $categories = get_terms(array(
                'taxonomy' => 'product_cat',
                'hide_empty' => false,
                'parent' => 0
            ));
        }
        
        // Helper function to display category hierarchy
        function display_category_options($categories, $selected, $level = 0) {
            $indent = str_repeat('&nbsp;&nbsp;&nbsp;', $level);
            foreach ($categories as $cat) {
                $selected_attr = ($selected == $cat->term_id) ? 'selected="selected"' : '';
                echo '<option value="' . esc_attr($cat->term_id) . '" ' . $selected_attr . '>' . $indent . esc_html($cat->name) . ' (' . $cat->count . ')</option>';
                
                $children = get_terms(array(
                    'taxonomy' => 'product_cat',
                    'parent' => $cat->term_id,
                    'hide_empty' => false
                ));
                if (!empty($children)) {
                    display_category_options($children, $selected, $level + 1);
                }
            }
        }
        ?>
        
        <style>
            .loomaama-widget-field {
                margin-bottom: 20px;
                padding-bottom: 15px;
                border-bottom: 1px solid #eee;
            }
            .loomaama-widget-field label {
                font-weight: bold;
                display: block;
                margin-bottom: 8px;
            }
            .loomaama-category-select {
                max-height: 250px;
                overflow-y: auto;
                border: 1px solid #ddd;
                border-radius: 4px;
                padding: 10px;
                background: #fff;
            }
            .loomaama-field-description {
                font-size: 12px;
                color: #666;
                margin-top: 5px;
                display: block;
            }
        </style>
        
        <div class="loomaama-widget-field">
            <label for="<?php echo $this->get_field_id('badge_text'); ?>"><?php _e('Badge Text:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('badge_text'); ?>" name="<?php echo $this->get_field_name('badge_text'); ?>" type="text" value="<?php echo esc_attr($badge_text); ?>">
            <small class="loomaama-field-description"><?php _e('Small text above the main title (e.g., "The Seasonal Edit")', 'loomaama'); ?></small>
        </div>
        
        <div class="loomaama-widget-field">
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'loomaama'); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" rows="3"><?php echo esc_textarea($title); ?></textarea>
        </div>
        
        <div class="loomaama-widget-field">
            <label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description:', 'loomaama'); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" rows="4"><?php echo esc_textarea($description); ?></textarea>
        </div>
        
        <div class="loomaama-widget-field">
            <label for="<?php echo $this->get_field_id('button_text'); ?>"><?php _e('Button Text:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('button_text'); ?>" name="<?php echo $this->get_field_name('button_text'); ?>" type="text" value="<?php echo esc_attr($button_text); ?>">
        </div>
        
        <div class="loomaama-widget-field">
            <label for="<?php echo $this->get_field_id('button_url'); ?>"><?php _e('Button Link:', 'loomaama'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('button_url'); ?>" name="<?php echo $this->get_field_name('button_url'); ?>">
                <option value=""><?php _e('-- Select a category --', 'loomaama'); ?></option>
                <option value="#" <?php selected($button_url, '#'); ?>><?php _e('Link to Homepage (#)', 'loomaama'); ?></option>
                <?php 
                $all_categories = get_terms(array('taxonomy' => 'product_cat', 'hide_empty' => false));
                foreach ($all_categories as $cat) : ?>
                    <option value="<?php echo esc_attr($cat->slug); ?>" <?php selected($button_url, $cat->slug); ?>>
                        <?php echo esc_html($cat->name); ?> (<?php echo $cat->count; ?> products)
                    </option>
                <?php endforeach; ?>
            </select>
            <small class="loomaama-field-description"><?php _e('Where the button will link to', 'loomaama'); ?></small>
        </div>
        
        <div class="loomaama-widget-field">
            <label><?php _e('Product Category:', 'loomaama'); ?></label>
            <div class="loomaama-category-select">
                <select id="<?php echo $this->get_field_id('selected_category'); ?>" name="<?php echo $this->get_field_name('selected_category'); ?>" style="width: 100%;">
                    <option value=""><?php _e('-- Select a Category --', 'loomaama'); ?></option>
                    <?php display_category_options($categories, $selected_category); ?>
                </select>
            </div>
            <small class="loomaama-field-description"><?php _e('Select a category to display products from (scroll to browse categories)', 'loomaama'); ?></small>
        </div>
        
        <div class="loomaama-widget-field">
            <label for="<?php echo $this->get_field_id('products_per_page'); ?>"><?php _e('Number of Products:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('products_per_page'); ?>" name="<?php echo $this->get_field_name('products_per_page'); ?>" type="number" min="1" max="50" value="<?php echo esc_attr($products_per_page); ?>">
            <small class="loomaama-field-description"><?php _e('How many products to show in the carousel', 'loomaama'); ?></small>
        </div>
        
        <div class="loomaama-widget-field">
            <input type="checkbox" id="<?php echo $this->get_field_id('aos_enabled'); ?>" name="<?php echo $this->get_field_name('aos_enabled'); ?>" value="1" <?php checked($aos_enabled, 1); ?>>
            <label for="<?php echo $this->get_field_id('aos_enabled'); ?>"><?php _e('Enable Scroll Animation (AOS)', 'loomaama'); ?></label>
        </div>
        
        <?php
        // Display preview of selected category
        if (!empty($selected_category)) {
            $category = get_term($selected_category, 'product_cat');
            if ($category && !is_wp_error($category)) {
                echo '<div class="loomaama-widget-field" style="background: #f0f8ff; padding: 10px; border-radius: 4px;">';
                echo '<strong>' . __('Selected Category Preview:', 'loomaama') . '</strong><br>';
                echo __('Category:', 'loomaama') . ' ' . esc_html($category->name) . '<br>';
                echo __('Total Products:', 'loomaama') . ' ' . $category->count;
                echo '</div>';
            }
        }
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['badge_text'] = sanitize_text_field(($new_instance['badge_text'] ?? ''));
        $instance['title'] = sanitize_textarea_field(($new_instance['title'] ?? ''));
        $instance['description'] = sanitize_textarea_field(($new_instance['description'] ?? ''));
        $instance['button_text'] = sanitize_text_field(($new_instance['button_text'] ?? ''));
        $instance['button_url'] = sanitize_text_field(($new_instance['button_url'] ?? ''));
        $instance['selected_category'] = absint(($new_instance['selected_category'] ?? ''));
        $instance['products_per_page'] = absint(($new_instance['products_per_page'] ?? ''));
        $instance['aos_enabled'] = !empty($new_instance['aos_enabled']) ? 1 : 0;
        
        // Validate products_per_page
        if ($instance['products_per_page'] < 1) {
            $instance['products_per_page'] = 12;
        }
        if ($instance['products_per_page'] > 50) {
            $instance['products_per_page'] = 50;
        }
        
        return $instance;
    }
}


/**
 * Promotion Section Widget
 */
class Loomaama_Promotion_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'loomaama_promotion',
            __('Loomaama: Promotion Section', 'loomaama'),
            array('description' => __('Display a promotion section with image and buttons', 'loomaama'))
        );
    }
    
    public function widget($args, $instance) {
        echo $args['before_widget'] ?? '';
        
        $badge_text = !empty($instance['badge_text']) ? $instance['badge_text'] : __('Limited Time Offer', 'loomaama');
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $description = !empty($instance['description']) ? $instance['description'] : '';
        $button1_text = !empty($instance['button1_text']) ? $instance['button1_text'] : __('Shop the Sale', 'loomaama');
        $button1_url = !empty($instance['button1_url']) ? $instance['button1_url'] : '#';
        $button2_text = !empty($instance['button2_text']) ? $instance['button2_text'] : '';
        $button2_url = !empty($instance['button2_url']) ? $instance['button2_url'] : '#';
        $image_url = !empty($instance['image_url']) ? $instance['image_url'] : '';
        $discount_badge = !empty($instance['discount_badge']) ? $instance['discount_badge'] : '';
        $background_color = !empty($instance['background_color']) ? $instance['background_color'] : 'var(--bg-editorial)';
        $aos_enabled = !empty($instance['aos_enabled']) ? $instance['aos_enabled'] : true;
        $attachment_id = attachment_url_to_postid($image_url);
        $image_url = wp_get_attachment_image_url($attachment_id, 'medium_large');
        ?>
        
        <section class="py-5 reveal" <?php echo $aos_enabled ? 'data-aos="fade-up" ' : ''; ?>>
            <div class="container">
                <div class="row g-0 align-items-center" style="background: <?php echo esc_attr($background_color); ?>; border: 1px solid var(--border);">
                    <div class="col-md-6 p-lg-3 py-3 p-md-5 px-4 " data-aos="fade-up" >
                        <span class="headline text-brand mb-3 d-block" style="font-size: 12px; color: var(--wild-nettle);" data-aos="fade-up" ><?php echo esc_html($badge_text); ?></span>
                        <h2 class="headline mb-4" data-aos="fade-up" ><?php echo esc_html($title); ?></h2>
                        <p class="text-secondary mb-4" data-aos="fade-up" ><?php echo esc_html($description); ?></p>
                        <div class="d-flex gap-3" data-aos="fade-up" >
                            <?php if (!empty($button1_text)) : ?>
                                <a href="<?php echo esc_url($button1_url); ?>" class="btn btn-lux px-4"><?php echo esc_html($button1_text); ?></a>
                            <?php endif; ?>
                            <?php if (!empty($button2_text)) : ?>
                                <a href="<?php echo esc_url($button2_url); ?>" class="btn btn-outline-dark px-4"><?php echo esc_html($button2_text); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6" data-aos="fade-up" >
                        <div class="position-relative" style="height: 450px;">
                            <?php if (!empty($image_url)) : ?>
                                <img src="<?php echo esc_url($image_url); ?>" class="w-100 h-100" style="object-fit: cover;" alt="<?php echo esc_attr($title); ?>">
                            <?php endif; ?>
                            <?php if (!empty($discount_badge)) : ?>
                                <div class="position-absolute bottom-0 end-0 p-4" data-aos="fade-up" >
                                    <span class="badge-wild-nettle shadow-sm"><?php echo esc_html($discount_badge); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <?php
        echo $args['after_widget'] ?? '';
    }
    
    public function form($instance) {
        $badge_text = !empty($instance['badge_text']) ? $instance['badge_text'] : __('Limited Time Offer', 'loomaama');
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $description = !empty($instance['description']) ? $instance['description'] : '';
        $button1_text = !empty($instance['button1_text']) ? $instance['button1_text'] : __('Shop the Sale', 'loomaama');
        $button1_url = !empty($instance['button1_url']) ? $instance['button1_url'] : '#';
        $button2_text = !empty($instance['button2_text']) ? $instance['button2_text'] : '';
        $button2_url = !empty($instance['button2_url']) ? $instance['button2_url'] : '#';
        $image_url = !empty($instance['image_url']) ? $instance['image_url'] : '';
        $discount_badge = !empty($instance['discount_badge']) ? $instance['discount_badge'] : '';
        $background_color = !empty($instance['background_color']) ? $instance['background_color'] : 'var(--bg-editorial)';
        $aos_enabled = isset($instance['aos_enabled']) ? $instance['aos_enabled'] : true;
        ?>
        
        <p>
            <label for="<?php echo $this->get_field_id('badge_text'); ?>"><?php _e('Badge Text:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('badge_text'); ?>" name="<?php echo $this->get_field_name('badge_text'); ?>" type="text" value="<?php echo esc_attr($badge_text); ?>">
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description:', 'loomaama'); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" rows="4"><?php echo esc_textarea($description); ?></textarea>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('button1_text'); ?>"><?php _e('Primary Button Text:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('button1_text'); ?>" name="<?php echo $this->get_field_name('button1_text'); ?>" type="text" value="<?php echo esc_attr($button1_text); ?>">
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('button1_url'); ?>"><?php _e('Primary Button URL:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('button1_url'); ?>" name="<?php echo $this->get_field_name('button1_url'); ?>" type="url" value="<?php echo esc_url($button1_url); ?>">
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('button2_text'); ?>"><?php _e('Secondary Button Text:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('button2_text'); ?>" name="<?php echo $this->get_field_name('button2_text'); ?>" type="text" value="<?php echo esc_attr($button2_text); ?>">
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('button2_url'); ?>"><?php _e('Secondary Button URL:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('button2_url'); ?>" name="<?php echo $this->get_field_name('button2_url'); ?>" type="url" value="<?php echo esc_url($button2_url); ?>">
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('image_url'); ?>"><?php _e('Image URL:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('image_url'); ?>" name="<?php echo $this->get_field_name('image_url'); ?>" type="url" value="<?php echo esc_url($image_url); ?>">
            <small><?php _e('Enter the full URL of the image', 'loomaama'); ?></small>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('discount_badge'); ?>"><?php _e('Discount Badge Text:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('discount_badge'); ?>" name="<?php echo $this->get_field_name('discount_badge'); ?>" type="text" value="<?php echo esc_attr($discount_badge); ?>" placeholder="e.g., 20% OFF">
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('background_color'); ?>"><?php _e('Background Color:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('background_color'); ?>" name="<?php echo $this->get_field_name('background_color'); ?>" type="text" value="<?php echo esc_attr($background_color); ?>" placeholder="var(--bg-editorial) or #f5f5f5">
        </p>
        
        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id('aos_enabled'); ?>" name="<?php echo $this->get_field_name('aos_enabled'); ?>" value="1" <?php checked($aos_enabled, 1); ?>>
            <label for="<?php echo $this->get_field_id('aos_enabled'); ?>"><?php _e('Enable Scroll Animation (AOS)', 'loomaama'); ?></label>
        </p>
        
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['badge_text'] = sanitize_text_field(($new_instance['badge_text'] ?? ''));
        $instance['title'] = sanitize_text_field(($new_instance['title'] ?? ''));
        $instance['description'] = sanitize_textarea_field(($new_instance['description'] ?? ''));
        $instance['button1_text'] = sanitize_text_field(($new_instance['button1_text'] ?? ''));
        $instance['button1_url'] = esc_url_raw(($new_instance['button1_url'] ?? ''));
        $instance['button2_text'] = sanitize_text_field(($new_instance['button2_text'] ?? ''));
        $instance['button2_url'] = esc_url_raw(($new_instance['button2_url'] ?? ''));
        $instance['image_url'] = esc_url_raw(($new_instance['image_url'] ?? ''));
        $instance['discount_badge'] = sanitize_text_field(($new_instance['discount_badge'] ?? ''));
        $instance['background_color'] = sanitize_text_field(($new_instance['background_color'] ?? ''));
        $instance['aos_enabled'] = !empty($new_instance['aos_enabled']) ? 1 : 0;
        
        return $instance;
    }
}

/**
 * Parallax Banner Widget
 */
class Loomaama_Parallax_Banner_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'loomaama_parallax_banner',
            __('Loomaama: Parallax Banner', 'loomaama'),
            array('description' => __('Display a full-width banner with parallax effect', 'loomaama'))
        );
    }
    
    public function widget($args, $instance) {
        echo $args['before_widget'] ?? '';
        
        $badge_text = !empty($instance['badge_text']) ? $instance['badge_text'] : __('', 'loomaama');
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $description = !empty($instance['description']) ? $instance['description'] : '';
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : __('Find a Store →', 'loomaama');
        $button_url = !empty($instance['button_url']) ? $instance['button_url'] : '#';
        $background_image = !empty($instance['background_image']) ? $instance['background_image'] : '';
        $height = !empty($instance['height']) ? $instance['height'] : '650';
        $brightness = !empty($instance['brightness']) ? $instance['brightness'] : '0.7';
        $aos_enabled = !empty($instance['aos_enabled']) ? $instance['aos_enabled'] : true;
        $attachment_id = attachment_url_to_postid($background_image);
        $background_image = wp_get_attachment_image_url($attachment_id, 'medium_large');
        ?>
        
        <div class="py-5 reveal jarallax" style="height: <?php echo esc_attr($height); ?>px; position: relative; " <?php echo $aos_enabled ? 'data-aos="fade-up" ' : ''; ?>>    
            <div class="container-fluid px-0 h-100 position-relative">
                <div class="position-absolute top-3 start-0 d-flex align-items-center justify-content-center text-center h-100 w-100">
                    <div class="px-4" style="max-width: 800px; z-index: 2;">
                        <?php if (!empty($badge_text)) : ?>
                        <h6 class="headline text-white mb-3" style="letter-spacing: 0.5em; font-size: 12px;" data-aos="fade-up"><?php echo esc_html($badge_text); ?></h6>
                        <?php endif; ?>
                        
                        <?php if (!empty($title)) : ?>
                        <h2 class="headline text-white display-5 mb-4" data-aos="fade-up" ><?php echo wp_kses_post($title); ?></h2>
                        <?php endif; ?>
                        
                        <?php if (!empty($description)) : ?>
                        <p class="text-white opacity-75 mb-5 headline" style="font-size: 14px; letter-spacing: 0.1em; line-height: 2;" data-aos="fade-up" >
                            <?php echo wp_kses_post($description); ?>
                        </p>
                        <?php endif; ?>
                        
                        <?php if (!empty($button_text)) : ?>
                        <a href="<?php echo esc_url($button_url); ?>" class="btn btn-outline-light px-5 py-3 rounded-0 headline" style="letter-spacing: var(--ls-btn); border-width: 1px;" data-aos="fade-up" >
                            <?php echo esc_html($button_text); ?>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
             <?php if (!empty($background_image)) : ?>
            <div class="jarallax-keep-img demo-float-right" data-speed="0.8">
                <img class="jarallax-img" src="<?php echo esc_url($background_image); ?>" alt="" width="400" loading="lazy" />
            </div>
            <?php endif; ?>
        </div>
        
        
        <?php
        echo $args['after_widget'] ?? '';
    }
    
    public function form($instance) {
        $badge_text = !empty($instance['badge_text']) ? $instance['badge_text'] : __('', 'loomaama');
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $description = !empty($instance['description']) ? $instance['description'] : '';
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : __('Find a Store →', 'loomaama');
        $button_url = !empty($instance['button_url']) ? $instance['button_url'] : '#';
        $background_image = !empty($instance['background_image']) ? $instance['background_image'] : '';
        $height = !empty($instance['height']) ? $instance['height'] : '650';
        $brightness = !empty($instance['brightness']) ? $instance['brightness'] : '0.7';
        $aos_enabled = isset($instance['aos_enabled']) ? $instance['aos_enabled'] : true;
        ?>
        
        <p>
            <label for="<?php echo $this->get_field_id('badge_text'); ?>"><?php _e('Badge Text:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('badge_text'); ?>" name="<?php echo $this->get_field_name('badge_text'); ?>" type="text" value="<?php echo esc_attr($badge_text); ?>">
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description:', 'loomaama'); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" rows="4"><?php echo esc_textarea($description); ?></textarea>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('button_text'); ?>"><?php _e('Button Text:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('button_text'); ?>" name="<?php echo $this->get_field_name('button_text'); ?>" type="text" value="<?php echo esc_attr($button_text); ?>">
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('button_url'); ?>"><?php _e('Button URL:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('button_url'); ?>" name="<?php echo $this->get_field_name('button_url'); ?>" type="url" value="<?php echo esc_url($button_url); ?>">
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('background_image'); ?>"><?php _e('Background Image URL:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('background_image'); ?>" name="<?php echo $this->get_field_name('background_image'); ?>" type="url" value="<?php echo esc_url($background_image); ?>">
            <small><?php _e('Enter the full URL of the background image', 'loomaama'); ?></small>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Section Height (px):', 'loomaama'); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="number" step="10" min="300" max="1000" value="<?php echo esc_attr($height); ?>">
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('brightness'); ?>"><?php _e('Opacity Filter (0-1):', 'loomaama'); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('brightness'); ?>" name="<?php echo $this->get_field_name('brightness'); ?>" type="number" step="0.05" min="0" max="1" value="<?php echo esc_attr($brightness); ?>">
            <small><?php _e('0 = bright, 1 = dark', 'loomaama'); ?></small>
        </p>
        
        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id('aos_enabled'); ?>" name="<?php echo $this->get_field_name('aos_enabled'); ?>" value="1" <?php checked($aos_enabled, 1); ?>>
            <label for="<?php echo $this->get_field_id('aos_enabled'); ?>"><?php _e('Enable Scroll Animation (AOS)', 'loomaama'); ?></label>
        </p>
        
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['badge_text'] = sanitize_text_field(($new_instance['badge_text'] ?? ''));
        $instance['title'] = wp_kses_post(($new_instance['title'] ?? ''));
        $instance['description'] = wp_kses_post(($new_instance['description'] ?? ''));
        $instance['button_text'] = sanitize_text_field(($new_instance['button_text'] ?? ''));
        $instance['button_url'] = esc_url_raw(($new_instance['button_url'] ?? ''));
        $instance['background_image'] = esc_url_raw(($new_instance['background_image'] ?? ''));
        $instance['height'] = absint(($new_instance['height'] ?? ''));
        $instance['brightness'] = floatval(($new_instance['brightness'] ?? ''));
        $instance['aos_enabled'] = !empty($new_instance['aos_enabled']) ? 1 : 0;
        
        return $instance;
    }
}

/*
* Nettle Journey Hero Widget - Dedicated Hero Section
 */
/**
 * Nettle Journey Text/Quote Widget
 */
class Loomaama_Nettle_Journey_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'loomaama_nettle_journey_text',
            __('Loomaama: Nettle Journey Text', 'loomaama'),
            array('description' => __('Display the Nettle Journey text section with blockquote and link', 'loomaama'))
        );
    }
    
    public function widget($args, $instance) {
        echo $args['before_widget'] ?? '';
        
        $subtitle = !empty($instance['subtitle']) ? $instance['subtitle'] : 'Nettle. Reclaimed.';
        $quote = !empty($instance['quote']) ? $instance['quote'] : "From the slopes of the Himalayas to the hands of our artisans.<br> Crafting timeless pieces with nature's rarest fiber";
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : 'Read the Nettle Journey →';
        $button_url = !empty($instance['button_url']) ? $instance['button_url'] : '#';
        $aos_enabled = !empty($instance['aos_enabled']) ? $instance['aos_enabled'] : true;

        // Resolve category slug to URL if necessary
        if (!empty($button_url) && strpos($button_url, 'http') !== 0 && $button_url !== '#') {
            $category = get_term_by('slug', $button_url, 'product_cat');
            if ($category) {
                $button_url = get_term_link($category);
            }
        }
        ?>
        
        <div class=" text-center reveal" <?php echo $aos_enabled ? 'data-aos="fade-up" ' : ''; ?>>
            <div class="container  pb-0">
                <div class="col-md-8 offset-md-2">
                    <?php if (!empty($subtitle)) : ?>
                        <h6 class="headline text-secondary mb-4" data-aos="fade-up" >
                            <?php echo esc_html($subtitle); ?>
                        </h6>
                    <?php endif; ?>

                    <?php if (!empty($quote)) : ?>
                        <blockquote class="blockquote-luxury" data-aos="fade-up">
                            <p><?php echo wp_kses_post($quote); ?></p>
                        </blockquote>
                    <?php endif; ?>

                    <?php if (!empty($button_text)) : ?>
                        <a href="<?php echo esc_url($button_url); ?>" class="btn btn-link-dark px-4 py-3" data-aos="fade-up" >
                            <?php echo esc_html($button_text); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <?php
        echo $args['after_widget'] ?? '';
    }
    
    public function form($instance) {
        $subtitle = !empty($instance['subtitle']) ? $instance['subtitle'] : 'Nettle. Reclaimed.';
        $quote = !empty($instance['quote']) ? $instance['quote'] : '';
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : 'Read the Nettle Journey →';
        $button_url = !empty($instance['button_url']) ? $instance['button_url'] : '';
        $aos_enabled = isset($instance['aos_enabled']) ? $instance['aos_enabled'] : true;

        $categories = array();
        if (class_exists('WooCommerce')) {
            $categories = get_terms(array('taxonomy' => 'product_cat', 'hide_empty' => false));
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('subtitle'); ?>"><?php _e('Subtitle:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('subtitle'); ?>" name="<?php echo $this->get_field_name('subtitle'); ?>" type="text" value="<?php echo esc_attr($subtitle); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('quote'); ?>"><?php _e('Quote/Text (HTML allowed):', 'loomaama'); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id('quote'); ?>" name="<?php echo $this->get_field_name('quote'); ?>" rows="4"><?php echo esc_textarea($quote); ?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('button_text'); ?>"><?php _e('Link Text:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('button_text'); ?>" name="<?php echo $this->get_field_name('button_text'); ?>" type="text" value="<?php echo esc_attr($button_text); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('button_url'); ?>"><?php _e('Link URL or Category Slug:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('button_url'); ?>" name="<?php echo $this->get_field_name('button_url'); ?>" type="text" value="<?php echo esc_attr($button_url); ?>">
        </p>
        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id('aos_enabled'); ?>" name="<?php echo $this->get_field_name('aos_enabled'); ?>" value="1" <?php checked($aos_enabled, 1); ?>>
            <label for="<?php echo $this->get_field_id('aos_enabled'); ?>"><?php _e('Enable Scroll Animation (AOS)', 'loomaama'); ?></label>
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['subtitle'] = sanitize_text_field(($new_instance['subtitle'] ?? ''));
        $instance['quote'] = wp_kses_post(($new_instance['quote'] ?? ''));
        $instance['button_text'] = sanitize_text_field(($new_instance['button_text'] ?? ''));
        $instance['button_url'] = sanitize_text_field(($new_instance['button_url'] ?? ''));
        $instance['aos_enabled'] = !empty($new_instance['aos_enabled']) ? 1 : 0;
        return $instance;
    }
}

/**
 * Sustainability Editorial Widget
 */
class Loomaama_Sustainability_Editorial_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'loomaama_sustainability_editorial',
            __('Loomaama: Sustainability Editorial', 'loomaama'),
            array('description' => __('Display the sustainability editorial section with commitment cards', 'loomaama'))
        );
    }
    
    public function widget($args, $instance) {
        echo $args['before_widget'] ?? '';
        
        // Get widget settings with defaults
        $subtitle = !empty($instance['subtitle']) ? $instance['subtitle'] : 'OUR COMMITMENT';
        $title = !empty($instance['title']) ? $instance['title'] : 'SUSTAINABILITY AT THE CORE';
        $description = !empty($instance['description']) ? $instance['description'] : 'Every garment tells a story of radical transparency. From our regenerative farms to your closet, we ensure the highest standards of animal welfare, environmental protection, and social responsibility.';
        
        // Card 1 settings
        $card1_bg_image = !empty($instance['card1_bg_image']) ? $instance['card1_bg_image'] : '';
        $card1_title = !empty($instance['card1_title']) ? $instance['card1_title'] : 'Authenticate Sale';
        $card1_description = !empty($instance['card1_description']) ? $instance['card1_description'] : 'Join us in building a circular future through Authenticated Resale';
        $card1_button_text = !empty($instance['card1_button_text']) ? $instance['card1_button_text'] : 'Learn More';
        $card1_button_url = !empty($instance['card1_button_url']) ? $instance['card1_button_url'] : '#';
        
        // Card 2 settings
        $card2_bg_image = !empty($instance['card2_bg_image']) ? $instance['card2_bg_image'] : '';
        $card2_title = !empty($instance['card2_title']) ? $instance['card2_title'] : 'SUSTAINABILITY';
        $card2_description = !empty($instance['card2_description']) ? $instance['card2_description'] : 'Our philosophy, policies, materials, and suppliers';
        $card2_button_text = !empty($instance['card2_button_text']) ? $instance['card2_button_text'] : 'Learn More →';
        $card2_button_url = !empty($instance['card2_button_url']) ? $instance['card2_button_url'] : '#';
        
        // Background color
        $background_color = !empty($instance['background_color']) ? $instance['background_color'] : '';
        $section_padding = !empty($instance['section_padding']) ? $instance['section_padding'] : 'py-5';
        $aos_enabled = !empty($instance['aos_enabled']) ? $instance['aos_enabled'] : true;
        
        // Handle category URLs for buttons
        if (!empty($card1_button_url) && strpos($card1_button_url, 'http') !== 0 && $card1_button_url !== '#') {
            $category = get_term_by('slug', $card1_button_url, 'product_cat');
            if ($category) {
                $card1_button_url = get_term_link($category);
            }
        }
        
        if (!empty($card2_button_url) && strpos($card2_button_url, 'http') !== 0 && $card2_button_url !== '#') {
            $category = get_term_by('slug', $card2_button_url, 'product_cat');
            if ($category) {
                $card2_button_url = get_term_link($category);
            }
        }
        
        $bg_style = !empty($background_color) ? 'style="background: ' . esc_attr($background_color) . ';"' : '';
        ?>
        
        <section class="editorial-section <?php echo esc_attr($section_padding); ?>" <?php echo $bg_style; ?> <?php echo $aos_enabled ? 'data-aos="fade-up" ' : ''; ?>>
            <div class="container">
                <div class="editorial-content">
                    <?php if (!empty($subtitle)) : ?>
                        <p class="section-subtitle" data-page="sustainability" data-aos="fade-up"><?php echo esc_html($subtitle); ?></p>
                    <?php endif; ?>
                    
                    <?php if (!empty($title)) : ?>
                        <h2 class="editorial-title display-6" data-aos="fade-up" ><?php echo esc_html($title); ?></h2>
                    <?php endif; ?>
                    
                    <?php if (!empty($description)) : ?>
                        <p class="mb-5 text-secondary" style="line-height: 1.8;" data-aos="fade-up" >
                            <?php echo esc_html($description); ?>
                        </p>
                    <?php endif; ?>

                    <div class="row mt-5 pt-5">
                        <div class="col-md-6 mb-4" data-aos="fade-up" >
                            <div class="d-flex align-items-center gap-3 mb-3"
                                style="background: url('<?php echo esc_url($card1_bg_image); ?>'); position: relative; background-size: cover; aspect-ratio: 2/3; background-position: center;">
                                <div class="p-4 editorial-card">
                                    <?php if (!empty($card1_title)) : ?>
                                        <h6 class="section-title mb-3"><?php echo esc_html($card1_title); ?></h6>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($card1_description)) : ?>
                                        <p class="text-secondary"><?php echo esc_html($card1_description); ?></p>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($card1_button_text) && !empty($card1_button_url)) : ?>
                                        <a href="<?php echo esc_url($card1_button_url); ?>" class="learn-more-link">
                                            <?php echo esc_html($card1_button_text); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4" data-aos="fade-up" >
                            <div class="d-flex align-items-center gap-3 mb-3"
                                style="background: url('<?php echo esc_url($card2_bg_image); ?>'); position: relative; background-size: cover; aspect-ratio: 2/3; background-position: center;">
                                <div class="p-4 editorial-card">
                                    <?php if (!empty($card2_title)) : ?>
                                        <h6 class="section-title mb-3"><?php echo esc_html($card2_title); ?></h6>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($card2_description)) : ?>
                                        <p class="text-secondary"><?php echo esc_html($card2_description); ?></p>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($card2_button_text) && !empty($card2_button_url)) : ?>
                                        <a href="<?php echo esc_url($card2_button_url); ?>" class="learn-more-link">
                                            <?php echo esc_html($card2_button_text); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <?php
        echo $args['after_widget'] ?? '';
    }
    
    public function form($instance) {
        // Default values
        $subtitle = !empty($instance['subtitle']) ? $instance['subtitle'] : 'OUR COMMITMENT';
        $title = !empty($instance['title']) ? $instance['title'] : 'SUSTAINABILITY AT THE CORE';
        $description = !empty($instance['description']) ? $instance['description'] : 'Every garment tells a story of radical transparency. From our regenerative farms to your closet, we ensure the highest standards of animal welfare, environmental protection, and social responsibility.';
        
        // Card 1 defaults
        $card1_bg_image = !empty($instance['card1_bg_image']) ? $instance['card1_bg_image'] : '';
        $card1_title = !empty($instance['card1_title']) ? $instance['card1_title'] : 'Authenticate Sale';
        $card1_description = !empty($instance['card1_description']) ? $instance['card1_description'] : 'Join us in building a circular future through Authenticated Resale';
        $card1_button_text = !empty($instance['card1_button_text']) ? $instance['card1_button_text'] : 'Learn More';
        $card1_button_url = !empty($instance['card1_button_url']) ? $instance['card1_button_url'] : '#';
        
        // Card 2 defaults
        $card2_bg_image = !empty($instance['card2_bg_image']) ? $instance['card2_bg_image'] : '';
        $card2_title = !empty($instance['card2_title']) ? $instance['card2_title'] : 'SUSTAINABILITY';
        $card2_description = !empty($instance['card2_description']) ? $instance['card2_description'] : 'Our philosophy, policies, materials, and suppliers';
        $card2_button_text = !empty($instance['card2_button_text']) ? $instance['card2_button_text'] : 'Learn More →';
        $card2_button_url = !empty($instance['card2_button_url']) ? $instance['card2_button_url'] : '#';
        
        $background_color = !empty($instance['background_color']) ? $instance['background_color'] : '';
        $section_padding = !empty($instance['section_padding']) ? $instance['section_padding'] : 'py-5';
        $aos_enabled = isset($instance['aos_enabled']) ? $instance['aos_enabled'] : true;
        
        // Get WooCommerce categories for button links
        $categories = array();
        if (class_exists('WooCommerce')) {
            $categories = get_terms(array(
                'taxonomy' => 'product_cat',
                'hide_empty' => false,
                'orderby' => 'name',
                'order' => 'ASC'
            ));
        }
        ?>
        
        <h4 style="margin: 0 0 10px; padding: 8px 0; border-bottom: 2px solid #ddd;">Main Content</h4>
        
        <p>
            <label for="<?php echo $this->get_field_id('subtitle'); ?>"><?php _e('Subtitle:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('subtitle'); ?>" name="<?php echo $this->get_field_name('subtitle'); ?>" type="text" value="<?php echo esc_attr($subtitle); ?>">
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description:', 'loomaama'); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" rows="4"><?php echo esc_textarea($description); ?></textarea>
        </p>
        
        <h4 style="margin: 20px 0 10px; padding: 8px 0; border-bottom: 2px solid #ddd;">Card 1 - Authenticate Sale</h4>
        
        <p>
            <label for="<?php echo $this->get_field_id('card1_bg_image'); ?>"><?php _e('Card 1 Background Image URL:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('card1_bg_image'); ?>" name="<?php echo $this->get_field_name('card1_bg_image'); ?>" type="url" value="<?php echo esc_url($card1_bg_image); ?>" placeholder="https://example.com/authenticate-sale.jpg">
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('card1_title'); ?>"><?php _e('Card 1 Title:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('card1_title'); ?>" name="<?php echo $this->get_field_name('card1_title'); ?>" type="text" value="<?php echo esc_attr($card1_title); ?>">
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('card1_description'); ?>"><?php _e('Card 1 Description:', 'loomaama'); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id('card1_description'); ?>" name="<?php echo $this->get_field_name('card1_description'); ?>" rows="3"><?php echo esc_textarea($card1_description); ?></textarea>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('card1_button_text'); ?>"><?php _e('Card 1 Button Text:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('card1_button_text'); ?>" name="<?php echo $this->get_field_name('card1_button_text'); ?>" type="text" value="<?php echo esc_attr($card1_button_text); ?>">
        </p>
        
        <?php if (!empty($categories)) : ?>
        <p>
            <label for="<?php echo $this->get_field_id('card1_button_url'); ?>"><?php _e('Card 1 Button Link:', 'loomaama'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('card1_button_url'); ?>" name="<?php echo $this->get_field_name('card1_button_url'); ?>">
                <option value="" <?php selected($card1_button_url, ''); ?>><?php _e('Select a Category', 'loomaama'); ?></option>
                <option value="#" <?php selected($card1_button_url, '#'); ?>><?php _e('Link to Homepage (#)', 'loomaama'); ?></option>
                <?php foreach ($categories as $category) : ?>
                    <option value="<?php echo esc_attr($category->slug); ?>" <?php selected($card1_button_url, $category->slug); ?>>
                        <?php echo esc_html($category->name); ?> (<?php echo $category->count; ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php else : ?>
        <p>
            <label for="<?php echo $this->get_field_id('card1_button_url'); ?>"><?php _e('Card 1 Button URL:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('card1_button_url'); ?>" name="<?php echo $this->get_field_name('card1_button_url'); ?>" type="url" value="<?php echo esc_url($card1_button_url); ?>">
        </p>
        <?php endif; ?>
        
        <h4 style="margin: 20px 0 10px; padding: 8px 0; border-bottom: 2px solid #ddd;">Card 2 - Sustainability</h4>
        
        <p>
            <label for="<?php echo $this->get_field_id('card2_bg_image'); ?>"><?php _e('Card 2 Background Image URL:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('card2_bg_image'); ?>" name="<?php echo $this->get_field_name('card2_bg_image'); ?>" type="url" value="<?php echo esc_url($card2_bg_image); ?>" placeholder="https://example.com/sustainability.jpg">
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('card2_title'); ?>"><?php _e('Card 2 Title:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('card2_title'); ?>" name="<?php echo $this->get_field_name('card2_title'); ?>" type="text" value="<?php echo esc_attr($card2_title); ?>">
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('card2_description'); ?>"><?php _e('Card 2 Description:', 'loomaama'); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id('card2_description'); ?>" name="<?php echo $this->get_field_name('card2_description'); ?>" rows="3"><?php echo esc_textarea($card2_description); ?></textarea>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('card2_button_text'); ?>"><?php _e('Card 2 Button Text:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('card2_button_text'); ?>" name="<?php echo $this->get_field_name('card2_button_text'); ?>" type="text" value="<?php echo esc_attr($card2_button_text); ?>">
        </p>
        
        <?php if (!empty($categories)) : ?>
        <p>
            <label for="<?php echo $this->get_field_id('card2_button_url'); ?>"><?php _e('Card 2 Button Link:', 'loomaama'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('card2_button_url'); ?>" name="<?php echo $this->get_field_name('card2_button_url'); ?>">
                <option value="" <?php selected($card2_button_url, ''); ?>><?php _e('Select a Category', 'loomaama'); ?></option>
                <option value="#" <?php selected($card2_button_url, '#'); ?>><?php _e('Link to Homepage (#)', 'loomaama'); ?></option>
                <?php foreach ($categories as $category) : ?>
                    <option value="<?php echo esc_attr($category->slug); ?>" <?php selected($card2_button_url, $category->slug); ?>>
                        <?php echo esc_html($category->name); ?> (<?php echo $category->count; ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php else : ?>
        <p>
            <label for="<?php echo $this->get_field_id('card2_button_url'); ?>"><?php _e('Card 2 Button URL:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('card2_button_url'); ?>" name="<?php echo $this->get_field_name('card2_button_url'); ?>" type="url" value="<?php echo esc_url($card2_button_url); ?>">
        </p>
        <?php endif; ?>
        
        <h4 style="margin: 20px 0 10px; padding: 8px 0; border-bottom: 2px solid #ddd;">Section Settings</h4>
        
        <p>
            <label for="<?php echo $this->get_field_id('background_color'); ?>"><?php _e('Background Color:', 'loomaama'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('background_color'); ?>" name="<?php echo $this->get_field_name('background_color'); ?>" type="text" value="<?php echo esc_attr($background_color); ?>" placeholder="#f5f5f5 or transparent">
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('section_padding'); ?>"><?php _e('Section Padding:', 'loomaama'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('section_padding'); ?>" name="<?php echo $this->get_field_name('section_padding'); ?>">
                <option value="py-3" <?php selected($section_padding, 'py-3'); ?>><?php _e('Small', 'loomaama'); ?></option>
                <option value="py-5" <?php selected($section_padding, 'py-5'); ?>><?php _e('Medium (Default)', 'loomaama'); ?></option>
                <option value="py-8" <?php selected($section_padding, 'py-8'); ?>><?php _e('Large', 'loomaama'); ?></option>
                <option value="py-0" <?php selected($section_padding, 'py-0'); ?>><?php _e('None', 'loomaama'); ?></option>
            </select>
        </p>
        
        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id('aos_enabled'); ?>" name="<?php echo $this->get_field_name('aos_enabled'); ?>" value="1" <?php checked($aos_enabled, 1); ?>>
            <label for="<?php echo $this->get_field_id('aos_enabled'); ?>"><?php _e('Enable Scroll Animation (AOS)', 'loomaama'); ?></label>
        </p>
        
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        
        // Main content
        $instance['subtitle'] = sanitize_text_field(($new_instance['subtitle'] ?? ''));
        $instance['title'] = sanitize_text_field(($new_instance['title'] ?? ''));
        $instance['description'] = sanitize_textarea_field(($new_instance['description'] ?? ''));
        
        // Card 1
        $instance['card1_bg_image'] = esc_url_raw(($new_instance['card1_bg_image'] ?? ''));
        $instance['card1_title'] = sanitize_text_field(($new_instance['card1_title'] ?? ''));
        $instance['card1_description'] = sanitize_textarea_field(($new_instance['card1_description'] ?? ''));
        $instance['card1_button_text'] = sanitize_text_field(($new_instance['card1_button_text'] ?? ''));
        
        // Handle card 1 button URL
        if (!empty($new_instance['card1_button_url'])) {
            if (strpos(($new_instance['card1_button_url'] ?? ''), 'http') === 0 || ($new_instance['card1_button_url'] ?? '') === '#') {
                $instance['card1_button_url'] = esc_url_raw(($new_instance['card1_button_url'] ?? ''));
            } else {
                $instance['card1_button_url'] = sanitize_text_field(($new_instance['card1_button_url'] ?? ''));
            }
        } else {
            $instance['card1_button_url'] = '#';
        }
        
        // Card 2
        $instance['card2_bg_image'] = esc_url_raw(($new_instance['card2_bg_image'] ?? ''));
        $instance['card2_title'] = sanitize_text_field(($new_instance['card2_title'] ?? ''));
        $instance['card2_description'] = sanitize_textarea_field(($new_instance['card2_description'] ?? ''));
        $instance['card2_button_text'] = sanitize_text_field(($new_instance['card2_button_text'] ?? ''));
        
        // Handle card 2 button URL
        if (!empty($new_instance['card2_button_url'])) {
            if (strpos(($new_instance['card2_button_url'] ?? ''), 'http') === 0 || ($new_instance['card2_button_url'] ?? '') === '#') {
                $instance['card2_button_url'] = esc_url_raw(($new_instance['card2_button_url'] ?? ''));
            } else {
                $instance['card2_button_url'] = sanitize_text_field(($new_instance['card2_button_url'] ?? ''));
            }
        } else {
            $instance['card2_button_url'] = '#';
        }
        
        // Section settings
        $instance['background_color'] = sanitize_text_field(($new_instance['background_color'] ?? ''));
        $instance['section_padding'] = sanitize_text_field(($new_instance['section_padding'] ?? ''));
        $instance['aos_enabled'] = !empty($new_instance['aos_enabled']) ? 1 : 0;
        
        return $instance;
    }
}

/**
 * Widget Name: Loom Seasonal Edit (3 Images)
 * Description: Displays a section with heading, content, and up to 3 image cards (title, link, button text).
 *              No Swiper, mobile-friendly, unique class names.
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Loom_Seasonal_Edit_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'loom_seasonal_edit', // Base ID
            'Loom Seasonal Edit (3 Images)', // Name
            array( 
                'description' => 'Displays a section with heading, content, and up to 3 image cards (title, link, button text). No Swiper.',
                'classname'   => 'widget-loom-seasonal-edit', // Unique class for styling
            )
        );
    }

    /**
     * Front-end display of widget.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'] ?? '';

        // Get main settings
        $section_heading   = ! empty( $instance['section_heading'] ) ? $instance['section_heading'] : 'TOGETHER WE\'RE RESHAPING THE TEXTILES INDUSTRY';
        $section_content   = ! empty( $instance['section_content'] ) ? $instance['section_content'] : 'Nettle fiber is nature\'s quiet genius; antibacterial, hypoallergenic, and breathable.';
        $headline_sub      = ! empty( $instance['headline_sub'] ) ? $instance['headline_sub'] : 'The Seasonal Edit';
        $button_text_global = ! empty( $instance['button_text_global'] ) ? $instance['button_text_global'] : 'VIEW COLLECTION';
        $button_link_global = ! empty( $instance['button_link_global'] ) ? esc_url( $instance['button_link_global'] ) : '#';

        // Image 1
        $image_1_id   = ! empty( $instance['image_1_id'] ) ? absint( $instance['image_1_id'] ) : 0;
        $image_1_url  = ! empty( $instance['image_1_url'] ) ? esc_url( $instance['image_1_url'] ) : '';
        $title_1      = ! empty( $instance['title_1'] ) ? $instance['title_1'] : 'The Nomad Mesh Tee';
        $link_1       = ! empty( $instance['link_1'] ) ? esc_url( $instance['link_1'] ) : '#';
        $btn_text_1   = ! empty( $instance['btn_text_1'] ) ? $instance['btn_text_1'] : 'Shop Now';

        // Image 2
        $image_2_id   = ! empty( $instance['image_2_id'] ) ? absint( $instance['image_2_id'] ) : 0;
        $image_2_url  = ! empty( $instance['image_2_url'] ) ? esc_url( $instance['image_2_url'] ) : '';
        $title_2      = ! empty( $instance['title_2'] ) ? $instance['title_2'] : 'The Alta Sleeveless Shell';
        $link_2       = ! empty( $instance['link_2'] ) ? esc_url( $instance['link_2'] ) : '#';
        $btn_text_2   = ! empty( $instance['btn_text_2'] ) ? $instance['btn_text_2'] : 'Shop Now';

        // Image 3
        $image_3_id   = ! empty( $instance['image_3_id'] ) ? absint( $instance['image_3_id'] ) : 0;
        $image_3_url  = ! empty( $instance['image_3_url'] ) ? esc_url( $instance['image_3_url'] ) : '';
        $title_3      = ! empty( $instance['title_3'] ) ? $instance['title_3'] : 'The Plateau Lounge Short';
        $link_3       = ! empty( $instance['link_3'] ) ? esc_url( $instance['link_3'] ) : '#';
        $btn_text_3   = ! empty( $instance['btn_text_3'] ) ? $instance['btn_text_3'] : 'Shop Now';

        // Helper to get image src
        function get_img_src( $id, $url, $fallback = 'https://via.placeholder.com/600x800/eeeeee/999999?text=Image' ) {
            if ( $id ) {
                $src = wp_get_attachment_image_url( $id, 'medium_large' );
                if ( $src ) return $src;
            }
            if ( $url ) return $url;
            return $fallback;
        }

        $img_1 = get_img_src( $image_1_id, $image_1_url );
        $img_2 = get_img_src( $image_2_id, $image_2_url );
        $img_3 = get_img_src( $image_3_id, $image_3_url );

        ?>
        <div class="loom-seasonal-wrapper">
            <div class="loom-seasonal-container">

                <!-- Section Header -->
                <div class="loom-seasonal-header">
                    <?php if ( ! empty( $headline_sub ) ) : ?>
                        <span class="loom-seasonal-headline-sub"><?php echo esc_html( $headline_sub ); ?></span>
                    <?php endif; ?>
                    <?php if ( ! empty( $section_heading ) ) : ?>
                        <h3 class="loom-seasonal-heading"><?php echo esc_html( $section_heading ); ?></h3>
                    <?php endif; ?>
                    <?php if ( ! empty( $section_content ) ) : ?>
                        <p class="loom-seasonal-text"><?php echo esc_html( $section_content ); ?></p>
                    <?php endif; ?>
                    <?php if ( ! empty( $button_text_global ) ) : ?>
                        <a href="<?php echo esc_url( $button_link_global ); ?>" class="loom-seasonal-btn-main">
                            <?php echo esc_html( $button_text_global ); ?>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>

                <!-- 3 Image Cards -->
                <div class="loom-seasonal-cards-grid">
                    
                    <!-- Card 1 -->
                    <div class="loom-seasonal-card">
                        <div class="loom-seasonal-img-wrapper">
                            <img src="<?php echo esc_url( $img_1 ); ?>" alt="<?php echo esc_attr( $title_1 ); ?>" class="loom-seasonal-img" loading="lazy">
                        </div>
                        <div class="loom-seasonal-card-body">
                            <h6 class="loom-seasonal-product-title"><?php echo esc_html( $title_1 ); ?></h6>
                            <?php if ( ! empty( $btn_text_1 ) ) : ?>
                                <a href="<?php echo esc_url( $link_1 ); ?>" class="loom-seasonal-card-btn">
                                    <?php echo esc_html( $btn_text_1 ); ?>
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="loom-seasonal-card">
                        <div class="loom-seasonal-img-wrapper">
                            <img src="<?php echo esc_url( $img_2 ); ?>" alt="<?php echo esc_attr( $title_2 ); ?>" class="loom-seasonal-img" loading="lazy">
                        </div>
                        <div class="loom-seasonal-card-body">
                            <h6 class="loom-seasonal-product-title"><?php echo esc_html( $title_2 ); ?></h6>
                            <?php if ( ! empty( $btn_text_2 ) ) : ?>
                                <a href="<?php echo esc_url( $link_2 ); ?>" class="loom-seasonal-card-btn">
                                    <?php echo esc_html( $btn_text_2 ); ?>
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="loom-seasonal-card">
                        <div class="loom-seasonal-img-wrapper">
                            <img src="<?php echo esc_url( $img_3 ); ?>" alt="<?php echo esc_attr( $title_3 ); ?>" class="loom-seasonal-img" loading="lazy">
                        </div>
                        <div class="loom-seasonal-card-body">
                            <h6 class="loom-seasonal-product-title"><?php echo esc_html( $title_3 ); ?></h6>
                            <?php if ( ! empty( $btn_text_3 ) ) : ?>
                                <a href="<?php echo esc_url( $link_3 ); ?>" class="loom-seasonal-card-btn">
                                    <?php echo esc_html( $btn_text_3 ); ?>
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                </div><!-- end grid -->
            </div><!-- end container -->
        </div><!-- end wrapper -->
        <?php

        echo $args['after_widget'] ?? '';
    }

    /**
     * Back-end widget form.
     */
    public function form( $instance ) {
        // Default values
        $section_heading   = ! empty( $instance['section_heading'] ) ? $instance['section_heading'] : 'TOGETHER WE\'RE RESHAPING THE TEXTILES INDUSTRY';
        $section_content   = ! empty( $instance['section_content'] ) ? $instance['section_content'] : 'Nettle fiber is nature\'s quiet genius; antibacterial, hypoallergenic, and breathable.';
        $headline_sub      = ! empty( $instance['headline_sub'] ) ? $instance['headline_sub'] : 'The Seasonal Edit';
        $button_text_global = ! empty( $instance['button_text_global'] ) ? $instance['button_text_global'] : 'VIEW COLLECTION';
        $button_link_global = ! empty( $instance['button_link_global'] ) ? esc_url( $instance['button_link_global'] ) : '#';

        // Image 1
        $image_1_id   = ! empty( $instance['image_1_id'] ) ? absint( $instance['image_1_id'] ) : 0;
        $image_1_url  = ! empty( $instance['image_1_url'] ) ? esc_url( $instance['image_1_url'] ) : '';
        $title_1      = ! empty( $instance['title_1'] ) ? $instance['title_1'] : 'The Nomad Mesh Tee';
        $link_1       = ! empty( $instance['link_1'] ) ? esc_url( $instance['link_1'] ) : '#';
        $btn_text_1   = ! empty( $instance['btn_text_1'] ) ? $instance['btn_text_1'] : 'Shop Now';

        // Image 2
        $image_2_id   = ! empty( $instance['image_2_id'] ) ? absint( $instance['image_2_id'] ) : 0;
        $image_2_url  = ! empty( $instance['image_2_url'] ) ? esc_url( $instance['image_2_url'] ) : '';
        $title_2      = ! empty( $instance['title_2'] ) ? $instance['title_2'] : 'The Alta Sleeveless Shell';
        $link_2       = ! empty( $instance['link_2'] ) ? esc_url( $instance['link_2'] ) : '#';
        $btn_text_2   = ! empty( $instance['btn_text_2'] ) ? $instance['btn_text_2'] : 'Shop Now';

        // Image 3
        $image_3_id   = ! empty( $instance['image_3_id'] ) ? absint( $instance['image_3_id'] ) : 0;
        $image_3_url  = ! empty( $instance['image_3_url'] ) ? esc_url( $instance['image_3_url'] ) : '';
        $title_3      = ! empty( $instance['title_3'] ) ? $instance['title_3'] : 'The Plateau Lounge Short';
        $link_3       = ! empty( $instance['link_3'] ) ? esc_url( $instance['link_3'] ) : '#';
        $btn_text_3   = ! empty( $instance['btn_text_3'] ) ? $instance['btn_text_3'] : 'Shop Now';

        ?>
        <p><strong>Section Settings</strong></p>
        <p>
            <label for="<?php echo $this->get_field_id( 'headline_sub' ); ?>">Headline Sub (small text):</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'headline_sub' ); ?>" name="<?php echo $this->get_field_name( 'headline_sub' ); ?>" type="text" value="<?php echo esc_attr( $headline_sub ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'section_heading' ); ?>">Section Heading:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'section_heading' ); ?>" name="<?php echo $this->get_field_name( 'section_heading' ); ?>" type="text" value="<?php echo esc_attr( $section_heading ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'section_content' ); ?>">Section Content:</label>
            <textarea class="widefat" id="<?php echo $this->get_field_id( 'section_content' ); ?>" name="<?php echo $this->get_field_name( 'section_content' ); ?>" rows="3"><?php echo esc_textarea( $section_content ); ?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'button_text_global' ); ?>">Main Button Text:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'button_text_global' ); ?>" name="<?php echo $this->get_field_name( 'button_text_global' ); ?>" type="text" value="<?php echo esc_attr( $button_text_global ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'button_link_global' ); ?>">Main Button Link:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'button_link_global' ); ?>" name="<?php echo $this->get_field_name( 'button_link_global' ); ?>" type="text" value="<?php echo esc_attr( $button_link_global ); ?>" />
        </p>

        <hr>
        <p><strong>Image Card 1</strong></p>
        <p>
            <label for="<?php echo $this->get_field_id( 'image_1_id' ); ?>">Image ID:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'image_1_id' ); ?>" name="<?php echo $this->get_field_name( 'image_1_id' ); ?>" type="number" value="<?php echo esc_attr( $image_1_id ); ?>" />
            <small>Media ID (overrides URL)</small>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'image_1_url' ); ?>">Image URL (fallback):</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'image_1_url' ); ?>" name="<?php echo $this->get_field_name( 'image_1_url' ); ?>" type="text" value="<?php echo esc_attr( $image_1_url ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'title_1' ); ?>">Title:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title_1' ); ?>" name="<?php echo $this->get_field_name( 'title_1' ); ?>" type="text" value="<?php echo esc_attr( $title_1 ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'link_1' ); ?>">Link URL:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'link_1' ); ?>" name="<?php echo $this->get_field_name( 'link_1' ); ?>" type="text" value="<?php echo esc_attr( $link_1 ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'btn_text_1' ); ?>">Button Text:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'btn_text_1' ); ?>" name="<?php echo $this->get_field_name( 'btn_text_1' ); ?>" type="text" value="<?php echo esc_attr( $btn_text_1 ); ?>" />
        </p>

        <hr>
        <p><strong>Image Card 2</strong></p>
        <p>
            <label for="<?php echo $this->get_field_id( 'image_2_id' ); ?>">Image ID:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'image_2_id' ); ?>" name="<?php echo $this->get_field_name( 'image_2_id' ); ?>" type="number" value="<?php echo esc_attr( $image_2_id ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'image_2_url' ); ?>">Image URL (fallback):</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'image_2_url' ); ?>" name="<?php echo $this->get_field_name( 'image_2_url' ); ?>" type="text" value="<?php echo esc_attr( $image_2_url ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'title_2' ); ?>">Title:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title_2' ); ?>" name="<?php echo $this->get_field_name( 'title_2' ); ?>" type="text" value="<?php echo esc_attr( $title_2 ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'link_2' ); ?>">Link URL:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'link_2' ); ?>" name="<?php echo $this->get_field_name( 'link_2' ); ?>" type="text" value="<?php echo esc_attr( $link_2 ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'btn_text_2' ); ?>">Button Text:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'btn_text_2' ); ?>" name="<?php echo $this->get_field_name( 'btn_text_2' ); ?>" type="text" value="<?php echo esc_attr( $btn_text_2 ); ?>" />
        </p>

        <hr>
        <p><strong>Image Card 3</strong></p>
        <p>
            <label for="<?php echo $this->get_field_id( 'image_3_id' ); ?>">Image ID:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'image_3_id' ); ?>" name="<?php echo $this->get_field_name( 'image_3_id' ); ?>" type="number" value="<?php echo esc_attr( $image_3_id ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'image_3_url' ); ?>">Image URL (fallback):</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'image_3_url' ); ?>" name="<?php echo $this->get_field_name( 'image_3_url' ); ?>" type="text" value="<?php echo esc_attr( $image_3_url ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'title_3' ); ?>">Title:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title_3' ); ?>" name="<?php echo $this->get_field_name( 'title_3' ); ?>" type="text" value="<?php echo esc_attr( $title_3 ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'link_3' ); ?>">Link URL:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'link_3' ); ?>" name="<?php echo $this->get_field_name( 'link_3' ); ?>" type="text" value="<?php echo esc_attr( $link_3 ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'btn_text_3' ); ?>">Button Text:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'btn_text_3' ); ?>" name="<?php echo $this->get_field_name( 'btn_text_3' ); ?>" type="text" value="<?php echo esc_attr( $btn_text_3 ); ?>" />
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();

        // Main fields
        $instance['section_heading']    = sanitize_text_field( ($new_instance['section_heading'] ?? '') );
        $instance['section_content']    = sanitize_textarea_field( ($new_instance['section_content'] ?? '') );
        $instance['headline_sub']       = sanitize_text_field( ($new_instance['headline_sub'] ?? '') );
        $instance['button_text_global'] = sanitize_text_field( ($new_instance['button_text_global'] ?? '') );
        $instance['button_link_global'] = esc_url_raw( ($new_instance['button_link_global'] ?? '') );

        // Card 1
        $instance['image_1_id']   = absint( ($new_instance['image_1_id'] ?? '') );
        $instance['image_1_url']  = esc_url_raw( ($new_instance['image_1_url'] ?? '') );
        $instance['title_1']      = sanitize_text_field( ($new_instance['title_1'] ?? '') );
        $instance['link_1']       = esc_url_raw( ($new_instance['link_1'] ?? '') );
        $instance['btn_text_1']   = sanitize_text_field( ($new_instance['btn_text_1'] ?? '') );

        // Card 2
        $instance['image_2_id']   = absint( ($new_instance['image_2_id'] ?? '') );
        $instance['image_2_url']  = esc_url_raw( ($new_instance['image_2_url'] ?? '') );
        $instance['title_2']      = sanitize_text_field( ($new_instance['title_2'] ?? '') );
        $instance['link_2']       = esc_url_raw( ($new_instance['link_2'] ?? '') );
        $instance['btn_text_2']   = sanitize_text_field( ($new_instance['btn_text_2'] ?? '') );

        // Card 3
        $instance['image_3_id']   = absint( ($new_instance['image_3_id'] ?? '') );
        $instance['image_3_url']  = esc_url_raw( ($new_instance['image_3_url'] ?? '') );
        $instance['title_3']      = sanitize_text_field( ($new_instance['title_3'] ?? '') );
        $instance['link_3']       = esc_url_raw( ($new_instance['link_3'] ?? '') );
        $instance['btn_text_3']   = sanitize_text_field( ($new_instance['btn_text_3'] ?? '') );

        return $instance;
    }

} // end class

