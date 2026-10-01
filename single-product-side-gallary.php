<?php
/**
 * The Template for displaying single products
 * 
 * Handles ALL scenarios dynamically:
 * - Only pa_size → shows Size section only
 * - Only pa_width → shows Width section only  
 * - Both pa_size and pa_width → shows both sections
 * - Neither → shows no size/width sections
 *
 * @package Loomaama
 * @version 2.1.4
 */

get_header();

$product_id = get_the_ID();
$product = wc_get_product($product_id);

if (!$product) {
    get_footer();
    return;
}

// ==============================================
// DETECT WHICH ATTRIBUTES THIS PRODUCT ACTUALLY HAS
// ==============================================
$product_attributes = $product->get_attributes();

$has_size = false;
$has_width = false;

// Check for pa_size attribute on THIS product
if (isset($product_attributes['pa_size']) && $product_attributes['pa_size'] instanceof WC_Product_Attribute) {
    $size_terms = $product_attributes['pa_size']->get_terms();
    $has_size = !empty($size_terms);
}

// Check for pa_width attribute on THIS product
if (isset($product_attributes['pa_width']) && $product_attributes['pa_width'] instanceof WC_Product_Attribute) {
    $width_terms = $product_attributes['pa_width']->get_terms();
    $has_width = !empty($width_terms);
}

// Only show variation selectors for variable products
$is_variable_product = $product->is_type('variable');
$has_size = $has_size && $is_variable_product;
$has_width = $has_width && $is_variable_product;

// Get design group ID
$design_group = get_post_meta($product_id, 'design_group_id', true);

// OPTIMIZATION: Cache design group products
$variant_ids = array();
$design_group_products = array();

if (!empty($design_group)) {
    $cache_key = 'design_group_' . md5($design_group);
    $cached_data = get_transient($cache_key);
    
    if ($cached_data !== false) {
        $variant_ids = $cached_data['variant_ids'];
        $design_group_products = $cached_data['design_group_products'];
    } else {
        $variant_ids = get_posts(array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'meta_key' => 'design_group_id',
            'meta_value' => $design_group,
            'fields' => 'ids',
            'no_found_rows' => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
        ));
        
        if (!empty($variant_ids) && count($variant_ids) <= 20) {
            foreach ($variant_ids as $vid) {
                $design_group_products[$vid] = wc_get_product($vid);
            }
        }
        
        set_transient($cache_key, array(
            'variant_ids' => $variant_ids,
            'design_group_products' => $design_group_products
        ), HOUR_IN_SECONDS);
    }
}

// Get current product attributes
$current_color_slug = get_post_meta($product_id, 'variant_color', true);
$current_pattern_slug = get_post_meta($product_id, 'variant_pattern', true);

// Lazy load attributes if needed
if ((!$current_color_slug || !$current_pattern_slug) && $product->is_type('variable')) {
    $attributes = $product->get_attributes();
    
    if (!$current_color_slug && isset($attributes['pa_color'])) {
        $color_terms = wp_get_post_terms($product_id, 'pa_color', array('fields' => 'slugs'));
        $current_color_slug = !empty($color_terms) ? $color_terms[0] : '';
    }
    
    if (!$current_pattern_slug && isset($attributes['pa_pattern'])) {
        $pattern_terms = wp_get_post_terms($product_id, 'pa_pattern', array('fields' => 'slugs'));
        $current_pattern_slug = !empty($pattern_terms) ? $pattern_terms[0] : '';
    }
}

// Build variant arrays for colors and patterns
$group_variants = array(
    'colors' => array(),
    'patterns' => array()
);

if (!empty($variant_ids)) {
    $all_color_slugs = array();
    $all_pattern_slugs = array();
    
    foreach ($variant_ids as $vid) {
        $group_product = isset($design_group_products[$vid]) ? $design_group_products[$vid] : wc_get_product($vid);
        if (!$group_product) continue;
        
        // Get color
        $color_slug = get_post_meta($vid, 'variant_color', true);
        if (!$color_slug && $group_product->is_type('variable')) {
            $color_terms = wp_get_post_terms($vid, 'pa_color', array('fields' => 'slugs'));
            $color_slug = !empty($color_terms) ? $color_terms[0] : '';
        }
        
        if ($color_slug && !isset($group_variants['colors'][$color_slug])) {
            $all_color_slugs[] = $color_slug;
            $group_variants['colors'][$color_slug] = array(
                'slug' => $color_slug,
                'name' => $color_slug,
                'hex' => '',
                'product_id' => $vid,
                'is_current' => ($vid == $product_id)
            );
        }
        
        // Get pattern
        $pattern_slug = get_post_meta($vid, 'variant_pattern', true);
        if (!$pattern_slug && $group_product->is_type('variable')) {
            $pattern_terms = wp_get_post_terms($vid, 'pa_pattern', array('fields' => 'slugs'));
            $pattern_slug = !empty($pattern_terms) ? $pattern_terms[0] : '';
        }
        
        if ($pattern_slug && !isset($group_variants['patterns'][$pattern_slug])) {
            $all_pattern_slugs[] = $pattern_slug;
            $group_variants['patterns'][$pattern_slug] = array(
                'slug' => $pattern_slug,
                'name' => $pattern_slug,
                'product_id' => $vid,
                'is_current' => ($vid == $product_id)
            );
        }
    }
    
    // Batch fetch term data for colors
    if (!empty($all_color_slugs)) {
        $color_terms = get_terms(array(
            'taxonomy' => 'pa_color',
            'slug' => $all_color_slugs,
            'hide_empty' => false,
        ));
        
        $term_map = array();
        foreach ($color_terms as $term) {
            $term_map[$term->slug] = $term;
        }
        
        foreach ($group_variants['colors'] as $slug => &$color_data) {
            if (isset($term_map[$slug])) {
                $color_data['name'] = $term_map[$slug]->name;
            }
            
            $hex = get_post_meta($color_data['product_id'], 'color_swatch_hex', true);
            if (empty($hex) && function_exists('loomaama_get_color_hex_from_slug')) {
                $hex = loomaama_get_color_hex_from_slug($slug, $color_data['name']);
            }
            $color_data['hex'] = $hex;
        }
    }
    
    // Batch fetch pattern term data
    if (!empty($all_pattern_slugs)) {
        $pattern_terms = get_terms(array(
            'taxonomy' => 'pa_pattern',
            'slug' => $all_pattern_slugs,
            'hide_empty' => false,
        ));
        
        $pattern_map = array();
        foreach ($pattern_terms as $term) {
            $pattern_map[$term->slug] = $term;
        }
        
        foreach ($group_variants['patterns'] as $slug => &$pattern_data) {
            if (isset($pattern_map[$slug])) {
                $pattern_data['name'] = $pattern_map[$slug]->name;
            }
        }
    }
}

$has_color = !empty($group_variants['colors']);
$has_pattern = !empty($group_variants['patterns']);

// ==============================================
// BUILD AVAILABLE VARIATIONS WITH ALL ATTRIBUTES
// ==============================================

$available_variations = array();
$has_any_size_width = false;
$first_available_variation = null;
$pre_selected_variation_id = '';
$pre_selected_max_qty = 0;
$pre_selected_price_html = '';
$pre_selected_size_slug = '';
$pre_selected_width_slug = '';

if ($product->is_type('variable')) {
    $variation_cache_key = 'product_variations_full_' . $product_id;
    $cached_variations = get_transient($variation_cache_key);
    
    if ($cached_variations !== false) {
        $available_variations = $cached_variations;
        $has_any_size_width = !empty($available_variations);
        if (!empty($available_variations)) {
            $first_available_variation = $available_variations[0];
        }
    } else {
        $variations = $product->get_available_variations();
        
        foreach ($variations as $variation) {
            if (!$variation['is_in_stock']) continue;
            
            $attrs = $variation['attributes'];
            
            // Get attributes if they exist
            $size_slug = '';
            $width_slug = '';
            
            if ($has_size) {
                if (isset($attrs['attribute_pa_size'])) {
                    $size_slug = $attrs['attribute_pa_size'];
                } elseif (isset($attrs['pa_size'])) {
                    $size_slug = $attrs['pa_size'];
                }
            }
            
            if ($has_width) {
                if (isset($attrs['attribute_pa_width'])) {
                    $width_slug = $attrs['attribute_pa_width'];
                } elseif (isset($attrs['pa_width'])) {
                    $width_slug = $attrs['pa_width'];
                }
            }
            
            $available_variations[] = array(
                'variation_id' => $variation['variation_id'],
                'size_slug'    => $size_slug,
                'width_slug'   => $width_slug,
                'price_html'   => $variation['price_html'],
                'max_qty'      => $variation['max_qty'] ?? 99,
                'is_in_stock'  => $variation['is_in_stock'],
                'attributes'   => $attrs
            );
        }
        
        $has_any_size_width = !empty($available_variations);
        
        if ($has_any_size_width) {
            set_transient($variation_cache_key, $available_variations, 12 * HOUR_IN_SECONDS);
        }
        
        if (!empty($available_variations)) {
            $first_available_variation = $available_variations[0];
        }
    }
    
    // Build lookup with combined key (only includes attributes that exist)
    $available_variations_lookup = array();
    foreach ($available_variations as $v) {
        if ($has_size && $has_width) {
            $key = $v['size_slug'] . '|' . $v['width_slug'];
        } elseif ($has_size) {
            $key = $v['size_slug'];
        } elseif ($has_width) {
            $key = $v['width_slug'];
        } else {
            $key = $v['variation_id']; // fallback
        }
        $available_variations_lookup[$key] = $v;
    }
      
    // Get URL parameters for pre-selection
    $url_selected_size = isset($_GET['size']) ? sanitize_text_field($_GET['size']) : '';
    $url_selected_width = isset($_GET['width']) ? sanitize_text_field($_GET['width']) : '';
    
    // Determine pre-selected variation
    if (!empty($available_variations)) {
        if ($has_size && $has_width) {
            $selected_key = $url_selected_size . '|' . $url_selected_width;
        } elseif ($has_size) {
            $selected_key = $url_selected_size;
        } elseif ($has_width) {
            $selected_key = $url_selected_width;
        } else {
            $selected_key = '';
        }
        
        if (isset($available_variations_lookup[$selected_key])) {
            $pre_selected = $available_variations_lookup[$selected_key];
            $pre_selected_variation_id = $pre_selected['variation_id'];
            $pre_selected_max_qty = $pre_selected['max_qty'];
            $pre_selected_price_html = $pre_selected['price_html'];
            $pre_selected_size_slug = $pre_selected['size_slug'];
            $pre_selected_width_slug = $pre_selected['width_slug'];
            $first_available_variation = $pre_selected;
        } else {
            // Use first available
            $pre_selected_variation_id = $first_available_variation['variation_id'];
            $pre_selected_max_qty = $first_available_variation['max_qty'];
            $pre_selected_price_html = $first_available_variation['price_html'];
            $pre_selected_size_slug = $first_available_variation['size_slug'];
            $pre_selected_width_slug = $first_available_variation['width_slug'];
        }
    }
}

// ==============================================
// GET TERMS FOR DISPLAY (ONLY FOR ATTRIBUTES THAT EXIST)
// ==============================================

// Get all size terms (only if product has size)
$all_size_terms = array();
if ($has_size) {
    $size_terms = get_terms(array(
        'taxonomy' => 'pa_size',
        'hide_empty' => false,
        'orderby' => 'meta_value_num',
        'order' => 'ASC',
    ));
    if (!empty($size_terms) && !is_wp_error($size_terms)) {
        foreach ($size_terms as $term) {
            $all_size_terms[] = array(
                'slug' => $term->slug,
                'name' => $term->name,
            );
        }
    }
}

// Get all width terms (only if product has width)
$all_width_terms = array();
if ($has_width) {
    $width_terms = get_terms(array(
        'taxonomy' => 'pa_width',
        'hide_empty' => false,
        'orderby' => 'meta_value_num',
        'order' => 'ASC',
    ));
    if (!empty($width_terms) && !is_wp_error($width_terms)) {
        foreach ($width_terms as $term) {
            $all_width_terms[] = array(
                'slug' => $term->slug,
                'name' => $term->name,
            );
        }
    }
}

$gallery_layout = get_theme_mod('product_gallery_layout', 'grid');


// Get product data
$product_name = $product->get_name();
$product_price_html = $product->get_price_html();
$product_long_description = $product->get_description();
$product_short_description = $product->get_short_description();
$product_review_count = $product->get_review_count();
$product_gallery_ids = $product->get_gallery_image_ids();
$featured_image_id = $product->get_image_id();
// Get ACF fields
$acf_fields = array();
if (function_exists('get_fields')) {
    $acf_fields = get_fields($product_id);
}
$product_details = isset($acf_fields['details']) ? $acf_fields['details'] : '';
$artisan_story = isset($acf_fields['artisan_story']) ? $acf_fields['artisan_story'] : '';
$care_description = isset($acf_fields['care_description']) ? $acf_fields['care_description'] : '';
$sensory_description = isset($acf_fields['sensory_description']) ? $acf_fields['sensory_description'] : '';
$featured_video = isset($acf_fields['video_gallary']) ? $acf_fields['video_gallary'] : '';

// Get all global colors
$all_global_colors = function_exists('loomaama_get_color_attributes') ? loomaama_get_color_attributes() : array();

// Get related products
$show_related = get_theme_mod('show_related_products', true);
$related_products = array();

if ($show_related) {
    $related_cache_key = 'related_products_' . $product_id;
    $related_products = get_transient($related_cache_key);
    
    if ($related_products === false) {
        $related_count = get_theme_mod('related_products_count', 4);
        $related_products = wc_get_related_products($product_id, $related_count);
        set_transient($related_cache_key, $related_products, 6 * HOUR_IN_SECONDS);
    }
}
?>

<!-- Size Guide Drawer -->
<aside class="size-guide-drawer" id="sizeGuideDrawer">
    <div class="drawer-header" data-aos="fade-up">
        <h5 class="headline mb-0"><?php _e('Size Guide', 'loomaama'); ?></h5>
        <button class="drawer-close" id="closeSizeGuide">✕</button>
    </div>

    <p class="text-secondary small mb-5">
        <?php _e('Our measurements are based on body dimensions and provided in centimeters for a precise fit.', 'loomaama'); ?>
    </p>

    <div class="mb-5">
        <h6 class="headline" style="font-size: 11px; margin-bottom: 20px;" data-aos="fade-up">
            <?php _e('Ready to Wear Conversion', 'loomaama'); ?></h6>
        <table class="size-table" data-aos="fade-up">
            <thead>
                <tr>
                    <th><?php _e('Size', 'loomaama'); ?></th>
                    <th><?php _e('Chest', 'loomaama'); ?></th>
                    <th><?php _e('Waist', 'loomaama'); ?></th>
                    <th><?php _e('Hips', 'loomaama'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>IT 38 (XS)</td>
                    <td>80-84</td <td>62-66</td <td>88-92</td </tr>
                <tr>
                    <td>IT 40 (S)</td <td>84-88</td <td>66-70</td <td>92-96</td </tr>
                <tr>
                    <td>IT 42 (M)</td <td>88-92</td <td>70-74</td <td>96-100</td </tr>
                <tr>
                    <td>IT 44 (L)</td <td>92-96</td <td>74-78</td <td>100-104</td </tr>
                <tr>
                    <td>IT 46 (XL)</td <td>96-100</td <td>78-82</td <td>104-108</td </tr>
            </tbody>
        </table>
    </div>

    <div>
        <h6 class="headline" style="font-size: 11px; margin-bottom: 20px;" data-aos="fade-up">
            <?php _e('How to Measure', 'loomaama'); ?>
        </h6>
        <div class="mb-4"><span class="d-block text-primary small fw-bold headline"
                style="font-size: 10px; margin-bottom: 5px;"><?php _e('Chest', 'loomaama'); ?></span><span
                class="text-secondary small"><?php _e('Measure around the fullest part of your chest, keeping the tape horizontal.', 'loomaama'); ?></span>
        </div>
        <div class="mb-4"><span class="d-block text-primary small fw-bold headline"
                style="font-size: 10px; margin-bottom: 5px;"><?php _e('Waist', 'loomaama'); ?></span><span
                class="text-secondary small"><?php _e('Measure around the narrowest part of your waistline (usually where your body bends side to side).', 'loomaama'); ?></span>
        </div>
        <div class="mb-4"><span class="d-block text-primary small fw-bold headline"
                style="font-size: 10px; margin-bottom: 5px;"><?php _e('Hips', 'loomaama'); ?></span><span
                class="text-secondary small"><?php _e('Measure around the fullest part of your hips, about 20cm below your waistline.', 'loomaama'); ?></span>
        </div>
    </div>
</aside>


<style id="loomaama-single-product-gallery-styles">
/*
 * Loomaama single-product gallery
 * Large editorial product image with a vertical thumbnail rail, inspired by
 * the supplied reference while retaining the existing WooCommerce gallery data.
 */
#productGallery.loomaama-gallery {
    display: grid !important;
    grid-template-columns: minmax(0, 1fr) clamp(88px, 8vw, 116px);
    align-items: start;
    gap: clamp(12px, 1.5vw, 22px);
    width: 100%;
    overflow: visible !important;
}

#productGallery.loomaama-gallery.is-single-media {
    grid-template-columns: minmax(0, 1fr);
}

#productGallery.loomaama-gallery.is-single-media .loomaama-gallery-thumbs {
    display: none;
}

#productGallery.loomaama-gallery .loomaama-gallery-main {
    position: relative;
    min-width: 0;
    height: min(78vh, 860px);
    min-height: 600px;
    overflow: hidden;
    background: #FAF9F7;
}

#productGallery.loomaama-gallery .gallery-main-link,
#productGallery.loomaama-gallery .gallery-main-link figure {
    display: block;
    width: 100%;
    height: 100%;
    margin: 0;
}

#productGallery.loomaama-gallery .gallery-main-link {
    cursor: zoom-in;
}

#productGallery.loomaama-gallery .gallery-main-link img,
#productGallery.loomaama-gallery .gallery-main-link video {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: contain;
    object-position: center;
    background: #FAF9F7;
}

#productGallery.loomaama-gallery .loomaama-gallery-thumbs {
    display: flex;
    flex-direction: column;
    gap: 12px;
    max-height: min(78vh, 860px);
    overflow-x: hidden;
    overflow-y: auto;
    overscroll-behavior: contain;
    scrollbar-width: thin;
    scrollbar-color: #9E8574 transparent;
    padding: 0 3px 2px 0;
}

#productGallery.loomaama-gallery .loomaama-gallery-thumbs::-webkit-scrollbar {
    width: 4px;
}

#productGallery.loomaama-gallery .loomaama-gallery-thumbs::-webkit-scrollbar-thumb {
    background: #9E8574;
}

#productGallery.loomaama-gallery .gallery-thumb {
    position: relative;
    flex: 0 0 auto;
    width: 100%;
    aspect-ratio: 3 / 4;
    padding: 0;
    overflow: hidden;
    border: 1px solid transparent;
    border-radius: 0;
    background: #FAF9F7;
    cursor: pointer;
    transition: border-color 180ms ease, opacity 180ms ease;
}

#productGallery.loomaama-gallery .gallery-thumb:hover {
    border-color: rgba(158, 133, 116, 0.55);
}

#productGallery.loomaama-gallery .gallery-thumb.is-active {
    border-color: #9E8574;
}

#productGallery.loomaama-gallery .gallery-thumb img,
#productGallery.loomaama-gallery .gallery-thumb video {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    pointer-events: none;
}

#productGallery.loomaama-gallery .gallery-thumb-video-badge {
    position: absolute;
    inset: 50% auto auto 50%;
    transform: translate(-50%, -50%);
    display: grid;
    place-items: center;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    color: #2A2A2A;
    background: rgba(250, 249, 247, 0.88);
    font-size: 10px;
    pointer-events: none;
}

#productGallery.loading-gallery .loomaama-gallery-main {
    opacity: 0.62;
}

@media (max-width: 1199.98px) and (min-width: 992px) {
    #productGallery.loomaama-gallery {
        grid-template-columns: minmax(0, 1fr) 88px;
        gap: 12px;
    }

    #productGallery.loomaama-gallery .loomaama-gallery-main {
        height: 650px;
        min-height: 520px;
    }

    #productGallery.loomaama-gallery .loomaama-gallery-thumbs {
        max-height: 650px;
        gap: 10px;
    }
}

@media (max-width: 991.98px) {
    #productGallery.loomaama-gallery {
        display: flex !important;
        flex-direction: column;
        gap: 12px;
        overflow: hidden !important;
    }

    #productGallery.loomaama-gallery .loomaama-gallery-main {
        width: 100%;
        height: auto;
        min-height: 0;
        aspect-ratio: 4 / 5;
    }

    #productGallery.loomaama-gallery .loomaama-gallery-thumbs {
        width: 100%;
        max-height: none;
        flex-direction: row;
        gap: 9px;
        overflow-x: auto;
        overflow-y: hidden;
        padding: 0 0 4px;
        scrollbar-width: none;
        scroll-snap-type: x proximity;
    }

    #productGallery.loomaama-gallery .loomaama-gallery-thumbs::-webkit-scrollbar {
        display: none;
    }

    #productGallery.loomaama-gallery .gallery-thumb {
        width: 76px;
        scroll-snap-align: start;
    }

    .product-gallery-wrap .gallery-nav {
        align-items: center;
        justify-content: space-between;
        width: 100%;
        margin-top: 10px;
    }
}

@media (max-width: 575.98px) {
    #productGallery.loomaama-gallery .gallery-thumb {
        width: 68px;
    }
}
</style>

<main>
    <?php while (have_posts()) : the_post(); ?>

    <section class="product-container">
        <div class="product-gallery-wrap position-relative d-flex flex-direction-column" data-aos="fade-up">
            <div class="product-gallery position-relative <?php echo esc_attr($gallery_layout); ?>" id="productGallery">
                <?php if ($featured_video && isset($featured_video['url'])): ?>
                <a href="<?php echo esc_url($featured_video['url']); ?>" class="glightbox " data-aos="fade-up">
                    <video autoplay muted loop playsinline class="hover-video" loading="lazy">
                        <source src="<?php echo esc_url($featured_video['url']); ?>" type="video/mp4">
                    </video>
                </a>
                <?php endif;
                
                if ($featured_image_id) {
                    $full_image_url = wp_get_attachment_image_url($featured_image_id, 'full');
                    $image_url = wp_get_attachment_image_url($featured_image_id, 'medium_large');
                    $image_alt = get_post_meta($featured_image_id, '_wp_attachment_image_alt', true);
                ?>
                <a href="<?php echo esc_url($full_image_url); ?>" class="glightbox " data-aos="fade-up">
                    <figure>
                        <img id="main-product-image" src="<?php echo esc_url($image_url); ?>"
                            alt="<?php echo esc_attr($image_alt); ?>" loading="eager">
                    </figure>
                </a>
                <?php
                }
                
                foreach ($product_gallery_ids as $attachment_id) {
                    $full_image_url = wp_get_attachment_image_url($attachment_id, 'full');
                    $image_url = wp_get_attachment_image_url($attachment_id, 'medium_large');
                    $image_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
                ?>
                <a href="<?php echo esc_url($image_url); ?>" class="glightbox " data-aos="fade-up">
                    <figure>
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>"
                            loading="lazy">
                    </figure>
                </a>
                <?php
                }
                ?>
            </div>
            <div class="gallery-nav">
                <button id="prevBtn" class="nav-btn"><i class="fa fa-arrow-left"></i></button>
                <button id="nextBtn" class="nav-btn"><i class="fa fa-arrow-right"></i></button>
            </div>
        </div>

        <div class="product-info">
            <div class="breadcrumb" data-aos="fade-up"  data-aos-delay="200" data-aos-once="true">
                <div class="breadcrumb-item d-block w-100"><?php 
                    if (function_exists('woocommerce_breadcrumb')) {
                        woocommerce_breadcrumb();
                    } 
                ?></div>
                <div><?php echo wc_get_product_category_list($product_id); ?></div>
            </div>

            <h1 class="product-title fs-3" data-aos="fade-up"  data-aos-delay="300" data-aos-once="true"><?php echo esc_html($product_name); ?></h1>
            <div class="product-price" id="product-price" data-aos="fade-up" data-aos-delay="400" data-aos-once="true"><?php echo $product_price_html; ?></div>

            <?php if (!empty($all_global_colors) && !empty($current_color_slug)) : ?>
                <div class="color-section" data-aos="fade-up" data-aos-delay="500" data-aos-once="true">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="label-title"><?php _e('Color:', 'loomaama'); ?></span>
                        <?php
                        // Display current color name from URL parameter or current product
                        $display_color_name = '';
                        if (isset($_GET['color']) && !empty($_GET['color'])) {
                            $url_color = sanitize_text_field($_GET['color']);
                            foreach ($all_global_colors as $color) {
                                if ($color['slug'] === $url_color) {
                                    $display_color_name = $color['name'];
                                    break;
                                }
                            }
                        } elseif (!empty($current_color_slug)) {
                            foreach ($all_global_colors as $color) {
                                if ($color['slug'] === $current_color_slug) {
                                    $display_color_name = $color['name'];
                                    break;
                                }
                            }
                        }
                        if ($display_color_name) {
                            echo '<span class="selected-color">' . esc_html($display_color_name) . '</span>';
                        }
                        ?>
                    </div>
                    <div class="color-pills">
                        <?php
                        foreach ($all_global_colors as $color) {
                            $color_slug = $color['slug'];
                            $color_name = $color['name'];
                            $color_hex  = $color['hex'];
                            
                            // Check if this color exists in the current design group
                            $is_in_group = isset($group_variants['colors'][$color_slug]);
                            
                            // Determine if this color is active (matches URL parameter OR current product color)
                            $url_color = isset($_GET['color']) ? sanitize_text_field($_GET['color']) : '';
                            $is_active = false;
                            
                            if ($is_in_group) {
                                if (!empty($url_color)) {
                                    $is_active = ($color_slug === $url_color);
                                } else {
                                    $is_active = ($color_slug === $current_color_slug);
                                }
                            }
                            
                            if ($is_in_group) {
                                // Find the product that has BOTH this color AND the current pattern
                                $target_product_id = 0;
                                $lookup_key = $color_slug . '|' . $current_pattern_slug;
                                
                                if (isset($design_group_products_map[$lookup_key])) {
                                    $target_product_id = $design_group_products_map[$lookup_key];
                                } else {
                                    // Fallback: just get any product with this color
                                    $target_product_id = $group_variants['colors'][$color_slug]['product_id'];
                                }
                                
                                // Build URL that preserves the current pattern
                                $variant_url = add_query_arg('pattern', $current_pattern_slug, get_permalink($target_product_id));
                                ?>
                                <a href="<?php echo esc_url($variant_url); ?>" 
                                class="color-pill-link" 
                                title="<?php echo esc_attr($color_name); ?>">
                                    <button type="button" 
                                            class="color-pill <?php echo $is_active ? 'active' : ''; ?>" 
                                            style="background-color: <?php echo esc_attr($color_hex); ?>;">
                                        <?php echo esc_html($color_name); ?>
                                    </button>
                                </a>
                            <?php } else { ?>
                                <button type="button" class="color-pill disabled" disabled
                                    title="<?php esc_attr_e('Not available for this design', 'loomaama'); ?>"
                                    style="background-color: <?php echo esc_attr($color_hex); ?>;">
                                    <?php echo esc_html($color_name); ?>
                                </button>
                            <?php }
                        } ?>
                    </div>
                </div>
            <?php endif; ?>


            <!-- PATTERN OPTIONS - FIXED: Pattern links respect current color -->
            <?php 
            // Get all patterns from pa_pattern attribute
            $all_patterns = function_exists('loomaama_get_pattern_attributes_with_images') 
                ? loomaama_get_pattern_attributes_with_images() 
                : array();

            // Get current color from URL parameter first, then from product
            $url_color = isset($_GET['color']) ? sanitize_text_field($_GET['color']) : '';
            $current_color_slug = !empty($url_color) ? $url_color : get_post_meta($product_id, 'variant_color', true);

            // FALLBACK 1: Try to get from pa_color taxonomy
            if (empty($current_color_slug)) {
                $color_terms = wp_get_post_terms($product_id, 'pa_color', array('fields' => 'slugs'));
                $current_color_slug = !empty($color_terms) ? $color_terms[0] : '';
            }

            // FALLBACK 2: Try to get from ACF field differently
            if (empty($current_color_slug) && function_exists('get_field')) {
                $current_color_slug = get_field('variant_color', $product_id);
            }

            // Get current pattern slug
            $current_pattern_slug = get_post_meta($product_id, 'variant_pattern', true);

            // FALLBACK: Try to get from pa_pattern taxonomy
            if (empty($current_pattern_slug)) {
                $pattern_terms = wp_get_post_terms($product_id, 'pa_pattern', array('fields' => 'slugs'));
                $current_pattern_slug = !empty($pattern_terms) ? $pattern_terms[0] : '';
            }

            // Build the lookup map for products in this design group (color|pattern -> product_id)
            $design_group_products_map = array();
            if (!empty($design_group)) {
                $group_products = get_posts(array(
                    'post_type' => 'product',
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'meta_key' => 'design_group_id',
                    'meta_value' => $design_group,
                    'fields' => 'ids',
                ));
                
                foreach ($group_products as $pid) {
                    // Get color from product
                    $color = get_post_meta($pid, 'variant_color', true);
                    
                    // FALLBACK: Try to get color from pa_color taxonomy
                    if (empty($color)) {
                        $color_terms = wp_get_post_terms($pid, 'pa_color', array('fields' => 'slugs'));
                        $color = !empty($color_terms) ? $color_terms[0] : '';
                    }
                    
                    // Get pattern from product
                    $pattern = get_post_meta($pid, 'variant_pattern', true);
                    
                    // FALLBACK: Try to get pattern from pa_pattern taxonomy
                    if (empty($pattern)) {
                        $pattern_terms = wp_get_post_terms($pid, 'pa_pattern', array('fields' => 'slugs'));
                        $pattern = !empty($pattern_terms) ? $pattern_terms[0] : '';
                    }
                    
                    if ($color && $pattern) {
                        $key = $color . '|' . $pattern;
                        $design_group_products_map[$key] = $pid;
                    }
                }
            }

            // Also build a map of what patterns are available for each color
            $color_patterns_map = array();
            foreach ($design_group_products_map as $key => $pid) {
                list($color, $pattern) = explode('|', $key);
                if (!isset($color_patterns_map[$color])) {
                    $color_patterns_map[$color] = array();
                }
                $color_patterns_map[$color][$pattern] = $pid;
            }

            $has_patterns = !empty($all_patterns);
            ?>
            <?php if ($has_patterns && !empty($current_pattern_slug)) : ?>
            <div class="pattern-section" data-aos="fade-up" data-aos-delay="600" data-aos-once="true">
                <div class="pattern-pills">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="label-title"><?php _e('Pattern:', 'loomaama'); ?></span>
                        <?php if ($current_pattern_slug) : ?>
                            <span class="selected-pattern"><?php echo esc_html(ucfirst(str_replace('-', ' ', $current_pattern_slug))); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="pattern-grid">
                        <?php
                        foreach ($all_patterns as $pattern) {
                            $pattern_slug = $pattern['slug'];
                            $pattern_name = $pattern['name'];
                            $pattern_image = $pattern['image'];
                            
                            // Check if this pattern is available for the CURRENT color
                            $target_product_id = 0;
                            $is_available = false;
                            
                            if (!empty($current_color_slug) && isset($color_patterns_map[$current_color_slug])) {
                                if (isset($color_patterns_map[$current_color_slug][$pattern_slug])) {
                                    $target_product_id = $color_patterns_map[$current_color_slug][$pattern_slug];
                                    $is_available = true;
                                }
                            }
                            
                            $is_current = ($is_available && $pattern_slug == $current_pattern_slug);
                            
                            if ($is_available) {
                                // Build URL that preserves the current color
                                $variant_url = add_query_arg('color', $current_color_slug, get_permalink($target_product_id));
                                ?>
                                <a href="<?php echo esc_url($variant_url); ?>" 
                                class="pattern-pill-link text-decoration-none text-center" 
                                title="<?php echo esc_attr($pattern_name); ?>">
                                    <button type="button" 
                                            class="pattern-pill d-flex flex-column align-items-center <?php echo $is_current ? 'active' : ''; ?>" 
                                            data-pattern="<?php echo esc_attr($pattern_slug); ?>">
                                        <div class="pattern-image-wrapper" style="border: 2px solid <?php echo $is_current ? 'var(--brand)' : 'var(--brand-hover)'; ?>;">
                                            <?php if (!empty($pattern_image)) : ?>
                                                <img src="<?php echo esc_url($pattern_image); ?>" 
                                                    alt="<?php echo esc_attr($pattern_name); ?>" 
                                                    class="pattern-img" 
                                                    loading="lazy">
                                            <?php else : ?>
                                                <div class="d-flex align-items-center justify-content-center h-100 w-100 bg-light">
                                                    <?php echo esc_html(substr($pattern_name, 0, 3)); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </button>
                                </a>
                                <?php
                            } else {
                                // Pattern not available for current color - show disabled
                                ?>
                                <div class="pattern-pill-disabled text-center" 
                                    title="<?php echo esc_attr($pattern_name . ' not available for this color'); ?>">
                                    <button type="button" class="pattern-pill disabled" disabled style="opacity: 0.4; cursor: not-allowed;">
                                        <div class="pattern-image-wrapper" style="opacity: 0.5; filter: grayscale(0.3);">
                                            <?php if (!empty($pattern_image)) : ?>
                                                <img src="<?php echo esc_url($pattern_image); ?>" 
                                                    alt="<?php echo esc_attr($pattern_name); ?>" 
                                                    class="pattern-img" 
                                                    loading="lazy">
                                            <?php else : ?>
                                                <div class="d-flex align-items-center justify-content-center h-100 w-100 bg-light">
                                                    <?php echo esc_html(substr($pattern_name, 0, 3)); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </button>
                                </div>
                                <?php
                            }
                        } 
                        ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <!-- SIZE OPTIONS (ONLY if product has size attribute) -->
            <?php if ($has_size && !empty($all_size_terms)) : ?>
            <div class="size-section" data-aos="fade-up" data-aos-delay="700" data-aos-once="true">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="size-pills">
                        <span class="label-title"><?php _e('Size:', 'loomaama'); ?></span>
                        <div class="size-buttons-wrapper">
                            <?php
                                foreach ($all_size_terms as $size_term) {
                                    $size_name = $size_term['name'];
                                    $size_slug = $size_term['slug'];
                                    
                                    // Build key based on what attributes exist
                                    if ($has_size && $has_width) {
                                        $current_width = isset($_GET['width']) ? sanitize_text_field($_GET['width']) : $pre_selected_width_slug;
                                        $key = $size_slug . '|' . $current_width;
                                    } elseif ($has_size) {
                                        $key = $size_slug;
                                    } else {
                                        $key = '';
                                    }
                                    
                                    $is_available = isset($available_variations_lookup[$key]);
                                    $variation_data = $is_available ? $available_variations_lookup[$key] : null;
                                    
                                    $is_active = ($is_available && $variation_data && $pre_selected_variation_id == $variation_data['variation_id']);
                                    $active_class = $is_active ? 'active' : '';
                                    ?>

                            <?php if ($is_available && $variation_data) : ?>
                            <button type="button" class="size-pill <?php echo $active_class; ?>"
                                data-variation-id="<?php echo esc_attr($variation_data['variation_id']); ?>"
                                data-size-slug="<?php echo esc_attr($size_slug); ?>"
                                data-width-slug="<?php echo esc_attr($variation_data['width_slug']); ?>"
                                data-price-html="<?php echo esc_attr($variation_data['price_html']); ?>"
                                data-max-qty="<?php echo esc_attr($variation_data['max_qty']); ?>"
                                title="<?php echo esc_attr($size_name); ?>">
                                <?php echo esc_html($size_name); ?>
                            </button>
                            <?php else : ?>
                            <button type="button" class="size-pill disabled" disabled  title="<?php echo esc_attr($size_name . ' not available'); ?>" >
                                <?php echo esc_html($size_name); ?>
                            </button>
                            <?php endif; ?>
                            <?php } ?>
                        </div>
                    </div>
                    <button type="button" class="headline opensizeguide"
                        id="openSizeGuide"><?php _e('Size Guide', 'loomaama'); ?></button>
                </div>
                <div class="size-error-message"
                    style="color: #dc3545; font-size: 12px; margin-top: 8px; display: none;"></div>
            </div>
            <?php endif; ?>

            <!-- WIDTH OPTIONS (ONLY if product has width attribute) -->
            <?php if ($has_width && !empty($all_width_terms)) : ?>
            <div class="width-section" data-aos="fade-up" data-aos-delay="800" data-aos-once="true">
                <div class="width-pills">
                    <span class="label-title"><?php _e('Width:', 'loomaama'); ?></span>

                    <select id="width-select" class="form-select width-select" style="max-width: 220px;">
                        <option value=""><?php _e('Select Width', 'loomaama'); ?></option>
                        <?php
                            foreach ($all_width_terms as $width_term) {
                                $width_name = $width_term['name'];
                                $width_slug = $width_term['slug'];

                                // A width is available if at least one in-stock variation has this width.
                                $width_available = false;
                                foreach ($available_variations as $available_variation) {
                                    if ($available_variation['width_slug'] === $width_slug) {
                                        $width_available = true;
                                        break;
                                    }
                                }

                                $selected = ($pre_selected_width_slug === $width_slug) ? 'selected' : '';
                                ?>
                        <option value="<?php echo esc_attr($width_slug); ?>" <?php echo $selected; ?>
                            <?php echo !$width_available ? 'disabled' : ''; ?>>
                            <?php echo esc_html($width_name . (!$width_available ? ' (Not available)' : '')); ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <?php endif; ?>

            <!-- Hidden inputs -->
            <input type="hidden" id="selected_variation_id" name="variation_id"
                value="<?php echo esc_attr($pre_selected_variation_id); ?>">
            <input type="hidden" id="selected_size_slug" value="<?php echo esc_attr($pre_selected_size_slug); ?>">
            <input type="hidden" id="selected_width_slug" value="<?php echo esc_attr($pre_selected_width_slug); ?>">
            <input type="hidden" id="has_size" value="<?php echo $has_size ? '1' : '0'; ?>">
            <input type="hidden" id="has_width" value="<?php echo $has_width ? '1' : '0'; ?>">
            <input type="hidden" id="is_variable_product" value="<?php echo $is_variable_product ? '1' : '0'; ?>">

            <!-- Quantity Selector -->
            <div class="quantity-section" data-aos="fade-up" data-aos-delay="900" data-aos-once="true">
                <span class="label-title"><?php _e('Quantity:', 'loomaama'); ?></span>
                <div class="quantity-selector">
                    <button type="button" class="quantity-btn quantity-minus"
                        aria-label="<?php _e('Decrease quantity', 'loomaama'); ?>">−</button>
                    <input type="number" id="quantity" name="quantity" class="quantity-input" value="1" min="1"
                        max="<?php echo $pre_selected_max_qty ?: 99; ?>" step="1">
                    <button type="button" class="quantity-btn quantity-plus"
                        aria-label="<?php _e('Increase quantity', 'loomaama'); ?>">+</button>
                </div>
            </div>

            <button type="button" id="add-to-cart-btn" class="add-to-cart-btn"
                data-product-id="<?php echo esc_attr($product_id); ?>" data-aos="fade-up" data-aos-delay="1000" data-aos-once="true">
                <?php _e('Add to Cart', 'loomaama'); ?>
            </button>

            <p style="font-size: 0.8rem; text-align: center; color: var(--text-secondary);">
                <?php _e('(Please note that the colours of handwoven fabrics are unique and may vary slightly from the images shown.)', 'loomaama'); ?>
            </p>

            <p class="product-description" id="variation-short-description-item">
                <div id="variation-short-description-content">
                    <?php echo wp_kses_post($product_short_description); ?>
                </div>
              
            </p>

          <!-- Accordion Details -->
            <div class="details-accordion" >
                 <div class="accordion-item"data-aos="fade-up" data-aos-delay="1100" data-aos-once="true" id="variation-long-description-item" style="<?php echo empty($product_long_description) ? 'display:none;' : ''; ?>">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <?php _e('Long Description', 'loomaama'); ?> <i class="fa fa-plus"></i>
                    </div>
                    <div class="accordion-content" id="variation-long-description-content">
                        <?php echo wp_kses_post($product_long_description); ?>
                    </div>
                </div>

                <div class="accordion-item" data-aos="fade-up" data-aos-delay="1200" data-aos-once="true" id="variation-details-item" style="<?php echo empty($product_details) ? 'display:none;' : ''; ?>">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <?php _e('Details', 'loomaama'); ?> <i class="fa fa-plus"></i>
                    </div>
                    <div class="accordion-content" id="variation-details-content">
                        <?php echo wp_kses_post($product_details); ?>
                    </div>
                </div>

                <?php if ($product->has_attributes() || $product->has_dimensions() || $product->has_weight()) : ?>
                <div class="accordion-item" data-aos="fade-up" data-aos-delay="1300" data-aos-once="true">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <?php _e('Provenance', 'loomaama'); ?> <i class="fa fa-plus"></i>
                    </div>
                    <div class="accordion-content">
                        <?php do_action('woocommerce_product_additional_information', $product); ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="accordion-item" data-aos="fade-up" data-aos-delay="1400" data-aos-once="true" id="variation-artisan-item" style="<?php echo empty($artisan_story) ? 'display:none;' : ''; ?>">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <?php _e('Artisan Story', 'loomaama'); ?> <i class="fa fa-plus"></i>
                    </div>
                    <div class="accordion-content" id="variation-artisan-content">
                        <?php echo wp_kses_post($artisan_story); ?>
                    </div>
                </div>

                <div class="accordion-item" data-aos="fade-up" data-aos-delay="1500" data-aos-once="true" id="variation-care-item" style="<?php echo empty($care_description) ? 'display:none;' : ''; ?>">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <?php _e('Care Instructions', 'loomaama'); ?> <i class="fa fa-plus"></i>
                    </div>
                    <div class="accordion-content" id="variation-care-content">
                        <?php echo wp_kses_post($care_description); ?>
                    </div>
                </div>

                <div class="accordion-item" data-aos="fade-up" data-aos-delay="1600" data-aos-once="true" id="variation-sensory-item" style="<?php echo empty($sensory_description) ? 'display:none;' : ''; ?>">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <?php _e('Sensory Description', 'loomaama'); ?> <i class="fa fa-plus"></i>
                    </div>
                    <div class="accordion-content" id="variation-sensory-content">
                        <?php echo wp_kses_post($sensory_description); ?>
                    </div>
                </div>

                <div class="accordion-item" data-aos="fade-up" data-aos-delay="1700" data-aos-once="true">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <?php _e('Delivery & Returns', 'loomaama'); ?> <i class="fa fa-plus"></i>
                    </div>
                    <div class="accordion-content">
                        <p>
                            <?php _e('Enjoy free carbon-neutral shipping on this item. Returns are accepted within 14 days of receipt for a full refund.', 'loomaama'); ?>
                        </p>
                    </div>
                </div>

                <div class="review-buttons d-flex justify-content-center gap-3 my-3" data-aos="fade-up" data-aos-delay="1800" data-aos-once="true">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#viewReviewsModal">
                        <i class="fas fa-star"></i> <?php _e('Read Reviews', 'loomaama'); ?>
                        (<?php echo esc_html($product_review_count); ?>)
                    </button>

                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#writeReviewModal">
                        <i class="fas fa-pen"></i> <?php _e('Write a Review', 'loomaama'); ?>
                    </button>
                </div>

            </div>
        </div>
    </section>

    <!-- Related Products -->
    <?php if ($show_related && !empty($related_products)) : ?>
    <section class="py-5 reveal" data-aos="fade-up">
        <div class="container-fluid px-4 py-5 px-lg-5">
            <div class="row mb-4">
                <div class="col">
                    <h3 class="headline" data-aos="fade-up"><?php _e('Related Products', 'loomaama'); ?></h3>
                </div>
                <div class="col-auto" data-aos="fade-up">
                    <div class="carousel-nav d-none d-md-flex gap-2">
                        <button class="btn-related-prev btn btn-outline-dark btn-sm p-2"><i
                                class="fas fa-chevron-left"></i></button>
                        <button class="btn-related-next btn btn-outline-dark btn-sm p-2"><i
                                class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
            <div class="swiper related-slider" data-aos="fade-up">
                <div class="swiper-wrapper">
                    <?php foreach ($related_products as $related_id) : 
                    $related_product = wc_get_product($related_id);
                    if (!$related_product) continue;
                    
                    $product_image = wp_get_attachment_image_url($related_product->get_image_id(), 'medium_large');
                    $product_name = $related_product->get_name();
                    $product_price = $related_product->get_price_html();
                    $product_categories = wp_get_post_terms($related_id, 'product_cat');
                    $category_name = !empty($product_categories) ? $product_categories[0]->name : __('NETTLE', 'loomaama');
                    
                    $regular_price = $related_product->get_regular_price();
                    $sale_price = $related_product->get_sale_price();
                    $discount = ($regular_price && $sale_price && $regular_price > 0) ? round((($regular_price - $sale_price) / $regular_price) * 100) : '';
                    
                    $main_img_id = get_post_thumbnail_id($related_id);
                    $main_img_url = wp_get_attachment_image_url($main_img_id, 'medium_large');
                    if (!$main_img_url) $main_img_url = wc_placeholder_img_src('medium_large');
                    
                    $hover_img_url = '';
                
                    if (function_exists('get_field')) {
                        $hover_field = get_field('hover_image', $related_id);
                        if ($hover_field) {
                            if (is_array($hover_field)) $hover_img_url = $hover_field['sizes']['medium_large'];
                            elseif (is_numeric($hover_field)) $hover_img_url = wp_get_attachment_image_url($hover_field, 'medium_large');
                            else $hover_img_url = $hover_field;
                        }
                    }
                    
                ?>
                    <div class="swiper-slide" data-aos="fade-up">
                        <div class="lux-card">
                            <div class="lux-img">
                                <a href="<?php echo esc_url(get_permalink($related_id)); ?>">
                                    <img src="<?php echo esc_url($main_img_url); ?>" class="img-fluid main-image"
                                        alt="<?php echo esc_attr($product_name); ?>" loading="lazy">
                                    <?php if ($hover_img_url) : ?>
                                        <img class="hover-overlay-img" src="<?php echo esc_url($hover_img_url); ?>" 
                                            alt="<?php echo esc_attr($product_name); ?>" loading="lazy">
                                    <?php endif; ?>
                                </a>
                                <?php if ($related_product->is_on_sale() && $discount) : ?><span
                                    class="badge-sale position-absolute top-0 start-0 m-3">-<?php echo $discount; ?>%</span><?php endif; ?>
                                <?php if ($related_product->is_featured()) : ?><span
                                    class="badge-best position-absolute top-0 end-0 m-3"><?php _e('Best-Seller', 'loomaama'); ?></span><?php endif; ?>
                                <?php if (!$related_product->is_in_stock()) : ?><span
                                    class="badge-soldout position-absolute top-0 start-0 m-3"><?php _e('Sold out', 'loomaama'); ?></span><?php endif; ?>
                            </div>
                           <div class="d-flex justify-content-between align-items-center">
                                <p class="lux-cat headline"><?php echo esc_html(strtoupper($category_name)); ?></p>
                                
                            </div>
                            <div class="d-flex justify-content-between loom-title-the-price">
                                <a href="<?php echo esc_url(get_permalink($related_id)); ?>"
                                    class="text-decoration-none">
                                    <h6 class="mb-0 text-truncate"><?php echo esc_html($product_name); ?></h6>
                                </a>
                                <span
                                    class="text-secondary <?php echo ($related_product->is_on_sale() && $discount) ? 'sale-on' : 'sale-off'; ?>"
                                    style="font-size: 13px;"><?php echo $product_price; ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination mt-4 position-relative"></div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php endwhile; ?>
</main>

<div class="modal fade" id="viewReviewsModal" tabindex="-1" aria-labelledby="viewReviewsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php _e('Customer Reviews', 'loomaama'); ?></h5><button type="button"
                    class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="reviews-list-container">
                <div class="text-center py-4">
                    <div class="spinner-border text-secondary" role="status"></div>
                    <p><?php _e('Loading reviews...', 'loomaama'); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="writeReviewModal" tabindex="-1" aria-labelledby="writeReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php _e('Write a Review', 'loomaama'); ?></h5><button type="button"
                    class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="submit-review-form" method="post">
                    <input type="hidden" name="product_id" value="<?php echo esc_attr($product_id); ?>">
                    <?php wp_nonce_field('loomaama_review_nonce', 'review_nonce'); ?>
                    <div class="mb-3"><label class="form-label"><?php _e('Rating', 'loomaama'); ?> <span
                                class="text-danger">*</span></label>
                        <select name="rating" class="form-select" required>
                            <option value=""><?php _e('Select rating', 'loomaama'); ?></option>
                            <option value="5">5 – <?php _e('Excellent', 'loomaama'); ?></option>
                            <option value="4">4 – <?php _e('Very Good', 'loomaama'); ?></option>
                            <option value="3">3 – <?php _e('Average', 'loomaama'); ?></option>
                            <option value="2">2 – <?php _e('Poor', 'loomaama'); ?></option>
                            <option value="1">1 – <?php _e('Terrible', 'loomaama'); ?></option>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label"><?php _e('Review Title', 'loomaama'); ?></label><input
                            type="text" name="review_title" class="form-control"
                            placeholder="<?php _e('Summarize your experience', 'loomaama'); ?>"></div>
                    <div class="mb-3"><label class="form-label"><?php _e('Your Review', 'loomaama'); ?> <span
                                class="text-danger">*</span></label><textarea name="comment" rows="4"
                            class="form-control" required
                            placeholder="<?php _e('Share your thoughts about this product...', 'loomaama'); ?>"></textarea>
                    </div>
                    <div class="mb-3"><label class="form-label"><?php _e('Your Name', 'loomaama'); ?> <span
                                class="text-danger">*</span></label><input type="text" name="author"
                            class="form-control" required
                            value="<?php echo esc_attr(wp_get_current_user()->display_name); ?>"></div>
                    <div class="mb-3"><label class="form-label"><?php _e('Email', 'loomaama'); ?> <span
                                class="text-danger">*</span></label><input type="email" name="email"
                            class="form-control" required
                            value="<?php echo esc_attr(wp_get_current_user()->user_email); ?>"></div>
                    <button type="submit"
                        class="btn btn-primary w-100"><?php _e('Submit Review', 'loomaama'); ?></button>
                </form>
                <div id="review-form-message" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>


<script id="loomaama-single-product-gallery-script">
(function($) {
    'use strict';

    var productLightbox = null;

    function destroyProductLightbox() {
        if (productLightbox && typeof productLightbox.destroy === 'function') {
            productLightbox.destroy();
        }
        productLightbox = null;
    }

    function initialiseProductLightbox() {
        destroyProductLightbox();

        if (typeof GLightbox !== 'undefined') {
            productLightbox = GLightbox({
                selector: '#productGallery .gallery-main-link',
                touchNavigation: true,
                zoomable: true,
                preload: true,
                loop: true,
                autoplayVideos: true
            });
        }
    }

    function collectGalleryItems($gallery) {
        var items = [];

        $gallery.children('a').each(function() {
            var $link = $(this);
            var $video = $link.find('video').first();
            var $image = $link.find('img').first();

            if ($video.length) {
                var videoSrc = $video.find('source').first().attr('src') || $video.attr('src') || $link.attr('href');
                if (!videoSrc) {
                    return;
                }

                items.push({
                    type: 'video',
                    src: videoSrc,
                    full: $link.attr('href') || videoSrc,
                    poster: $video.attr('poster') || '',
                    alt: $video.attr('aria-label') || '<?php echo esc_js(__('Product video', 'loomaama')); ?>'
                });
                return;
            }

            if ($image.length) {
                var imageSrc = $image.attr('src');
                if (!imageSrc) {
                    return;
                }

                items.push({
                    type: 'image',
                    src: imageSrc,
                    full: $link.attr('href') || imageSrc,
                    alt: $image.attr('alt') || '<?php echo esc_js($product_name); ?>'
                });
            }
        });

        return items;
    }

    function updateGalleryNavigation(itemsLength) {
        var shouldShow = window.innerWidth < 992 && itemsLength > 1;
        $('.product-gallery-wrap .gallery-nav').css('display', shouldShow ? 'flex' : 'none');
    }

    function renderMainItem($gallery, item, index) {
        var $main = $gallery.find('.loomaama-gallery-main');
        var $link = $('<a>', {
            'class': 'gallery-main-link glightbox',
            'href': item.full || item.src,
            'aria-label': '<?php echo esc_js(__('Open product media', 'loomaama')); ?>'
        });
        var $figure = $('<figure>');

        if (item.type === 'video') {
            var $video = $('<video>', {
                autoplay: true,
                muted: true,
                loop: true,
                playsinline: true,
                preload: 'metadata',
                poster: item.poster || ''
            });
            $video.append($('<source>', {
                src: item.src,
                type: 'video/mp4'
            }));
            $figure.append($video);
        } else {
            $figure.append($('<img>', {
                id: 'main-product-image',
                src: item.src,
                alt: item.alt || '',
                loading: 'eager',
                decoding: 'async'
            }));
        }

        $link.append($figure);
        $main.empty().append($link);

        $gallery.find('.gallery-thumb')
            .removeClass('is-active')
            .attr('aria-selected', 'false');

        var $activeThumb = $gallery.find('.gallery-thumb[data-gallery-index="' + index + '"]');
        $activeThumb.addClass('is-active').attr('aria-selected', 'true');

        var thumbRail = $gallery.find('.loomaama-gallery-thumbs').get(0);
        var activeThumb = $activeThumb.get(0);
        if (thumbRail && activeThumb) {
            if (window.innerWidth < 992) {
                thumbRail.scrollTo({
                    left: Math.max(0, activeThumb.offsetLeft - 8),
                    behavior: 'smooth'
                });
            } else {
                thumbRail.scrollTo({
                    top: Math.max(0, activeThumb.offsetTop - 8),
                    behavior: 'smooth'
                });
            }
        }

        $gallery.data('loomaamaGalleryIndex', index);
        initialiseProductLightbox();
    }

    function buildGalleryShell($gallery, items) {
        var $main = $('<div>', {
            'class': 'loomaama-gallery-main',
            'aria-live': 'polite'
        });
        var $thumbs = $('<div>', {
            'class': 'loomaama-gallery-thumbs',
            'role': 'tablist',
            'aria-label': '<?php echo esc_js(__('Product gallery thumbnails', 'loomaama')); ?>'
        });

        items.forEach(function(item, index) {
            var $button = $('<button>', {
                type: 'button',
                'class': 'gallery-thumb',
                'data-gallery-index': index,
                'role': 'tab',
                'aria-selected': index === 0 ? 'true' : 'false',
                'aria-label': '<?php echo esc_js(__('View product media', 'loomaama')); ?> ' + (index + 1)
            });

            if (item.type === 'video') {
                var $thumbVideo = $('<video>', {
                    muted: true,
                    loop: true,
                    playsinline: true,
                    preload: 'metadata',
                    poster: item.poster || ''
                });
                $thumbVideo.append($('<source>', {
                    src: item.src,
                    type: 'video/mp4'
                }));
                $button.append($thumbVideo);
                $button.append($('<span>', {
                    'class': 'gallery-thumb-video-badge',
                    'aria-hidden': 'true'
                }).append($('<i>', {'class': 'fa fa-play'})));
            } else {
                $button.append($('<img>', {
                    src: item.src,
                    alt: '',
                    loading: 'lazy',
                    decoding: 'async'
                }));
            }

            $thumbs.append($button);
        });

        $gallery
            .empty()
            .addClass('loomaama-gallery')
            .toggleClass('is-single-media', items.length === 1)
            .append($main, $thumbs)
            .data('loomaamaGalleryItems', items)
            .data('loomaamaGalleryIndex', 0)
            .attr('data-gallery-count', items.length);

        $gallery.off('click.loomaamaGallery').on('click.loomaamaGallery', '.gallery-thumb', function() {
            var index = parseInt($(this).attr('data-gallery-index'), 10);
            if (!Number.isNaN(index)) {
                renderMainItem($gallery, items[index], index);
            }
        });

        renderMainItem($gallery, items[0], 0);
        updateGalleryNavigation(items.length);
    }

    window.loomaamaInitProductGallery = function(galleryElement) {
        var $gallery = galleryElement ? $(galleryElement) : $('#productGallery');
        if (!$gallery.length) {
            return;
        }

        var items = collectGalleryItems($gallery);

        if (!items.length) {
            items = $gallery.data('loomaamaGalleryItems') || [];
        }

        if (!items.length) {
            destroyProductLightbox();
            updateGalleryNavigation(0);
            return;
        }

        buildGalleryShell($gallery, items);
    };

    window.loomaamaGalleryStep = function(direction) {
        var $gallery = $('#productGallery');
        var items = $gallery.data('loomaamaGalleryItems') || [];
        if (items.length < 2) {
            return;
        }

        var currentIndex = parseInt($gallery.data('loomaamaGalleryIndex'), 10) || 0;
        var nextIndex = (currentIndex + direction + items.length) % items.length;
        renderMainItem($gallery, items[nextIndex], nextIndex);
    };

    $(window)
        .off('resize.loomaamaGallery')
        .on('resize.loomaamaGallery', function() {
            var items = $('#productGallery').data('loomaamaGalleryItems') || [];
            updateGalleryNavigation(items.length);
        });
})(jQuery);
</script>

<script type="text/javascript">
jQuery(document).ready(function($) {
    var GalleryCount = $('.product-gallery a').length;
      if($(window).width()<992 && GalleryCount > 1){
        document.querySelectorAll('.gallery-nav').forEach(el => {
            el.style.display = 'flex';
        });
      }
      else{
        document.querySelectorAll('.gallery-nav').forEach(el => {
            el.style.display = 'none';
        });
      }                              
    


    let productId = <?php echo $product_id; ?>;
    let selectedVariationId = '<?php echo esc_js($pre_selected_variation_id); ?>';
    let selectedSizeSlug = '<?php echo esc_js($pre_selected_size_slug); ?>';
    let selectedWidthSlug = '<?php echo esc_js($pre_selected_width_slug); ?>';
    let currentMaxQty = <?php echo $pre_selected_max_qty ?: 99; ?>;
    let hasSize = $('#has_size').val() === '1';
    let hasWidth = $('#has_width').val() === '1';
    let availableVariations = <?php echo wp_json_encode($available_variations); ?>;

    // ==============================================
    // Helper Functions
    // ==============================================

    function findVariation(sizeSlug, widthSlug) {
        for (let i = 0; i < availableVariations.length; i++) {
            let variation = availableVariations[i];

            let sizeMatches = true;
            let widthMatches = true;

            if (hasSize && variation.size_slug) {
                sizeMatches = (variation.size_slug === sizeSlug);
            } else if (hasSize && !variation.size_slug) {
                sizeMatches = false;
            }

            if (hasWidth && variation.width_slug) {
                widthMatches = (variation.width_slug === widthSlug);
            } else if (hasWidth && !variation.width_slug) {
                widthMatches = false;
            }

            if (sizeMatches && widthMatches) {
                return variation;
            }
        }
        return null;
    }

    function applyVariation(variation) {
        if (!variation) {
            selectedVariationId = '';
            $('#selected_variation_id').val('');
            return;
        }

        selectedVariationId = variation.variation_id;
        selectedSizeSlug = variation.size_slug || selectedSizeSlug;
        selectedWidthSlug = variation.width_slug || selectedWidthSlug;
        currentMaxQty = variation.max_qty > 0 ? variation.max_qty : 99;

        $('#selected_variation_id').val(selectedVariationId);
        $('#selected_size_slug').val(selectedSizeSlug);
        $('#selected_width_slug').val(selectedWidthSlug);

        if (variation.price_html) {
            $('#product-price').html(variation.price_html);
        }

        $('#quantity').attr('max', currentMaxQty);
        if (parseInt($('#quantity').val(), 10) > currentMaxQty) {
            $('#quantity').val(currentMaxQty);
        }

        $('.size-error-message').hide().text('');
    }

    function updateSizeButtonsAvailability(widthSlug) {
        $('.size-pill').each(function() {
            let $pill = $(this);
            let sizeSlug = $pill.data('size-slug');
            let variation = findVariation(sizeSlug, widthSlug);

            if (variation) {
                $pill.removeClass('disabled').prop('disabled', false);
                $pill.css({
                    opacity: '1',
                    cursor: 'pointer'
                });
                $pill.attr('data-variation-id', variation.variation_id);
                $pill.attr('data-price-html', variation.price_html);
                $pill.attr('data-max-qty', variation.max_qty);
                $pill.attr('data-width-slug', variation.width_slug || '');
                $pill.attr('title', sizeSlug);
            } else {
                $pill.addClass('disabled').prop('disabled', true);
                $pill.css({
                    opacity: '0.5',
                    cursor: 'not-allowed'
                });
                $pill.attr('title', sizeSlug + ' not available for selected width');

                if ($pill.hasClass('active')) {
                    $pill.removeClass('active');
                }
            }
        });
    }

    function refreshWidthOptions() {
        if (!hasWidth || !$('#width-select').length) {
            return;
        }

        $('#width-select option').each(function() {
            let $option = $(this);
            let widthSlug = $option.val();

            if (!widthSlug) {
                return;
            }

            let variation = findVariation(selectedSizeSlug, widthSlug);
            let isAvailable = !!variation;

            $option.prop('disabled', !isAvailable);

            let originalText = $option.data('original-text') || $option.text().replace(
                ' (Not available)', '');
            $option.data('original-text', originalText);
            $option.text(isAvailable ? originalText : originalText + ' (Not available)');
        });

        if ($('#width-select').find(':selected').prop('disabled')) {
            $('#width-select').val('');
            selectedWidthSlug = '';
            $('#selected_width_slug').val('');
            $('#selected_variation_id').val('');

            $('#width-select option').each(function() {
                if (!$(this).prop('disabled') && $(this).val()) {
                    $('#width-select').val($(this).val()).trigger('change');
                    return false;
                }
            });
        }
    }

   // Function to update cart count in header and hide when zero
    function updateHeaderCartCount(count) {
        // Convert to number
        let cartCount = parseInt(count) || 0;
        
        // Update all cart count elements in header
        $('.cart-count, .cart-count-drawer, .header-cart-count, .cart-icon .count').each(function() {
            $(this).text(cartCount);
        });
        
        // Hide when count is 0, show when > 0
        if (cartCount === 0) {
            $('.cart-count, .cart-count-drawer, .header-cart-count').hide();
            // Also hide cart count in drawer heading
            $('.drawer__heading .cart-count-drawer').hide();
        } else {
            $('.cart-count, .cart-count-drawer, .header-cart-count').show();
            $('.drawer__heading .cart-count-drawer').show();
        }
        
        // Update aria-label for accessibility
        $('[aria-label*="Cart"]').attr('aria-label', 'Cart (' + cartCount + ')');
    }

    // ==============================================
    // Size Selection Handler
    // ==============================================
    <?php if ($has_size) : ?>
    $('.size-pill:not(.disabled)').on('click', function() {
        let $pill = $(this);
        let sizeSlug = $pill.data('size-slug');
        let variationId = $pill.data('variation-id');
        let priceHtml = $pill.data('price-html');
        let maxQty = $pill.data('max-qty');
        let widthSlug = $pill.data('width-slug');

        $('.size-pill').removeClass('active');
        $pill.addClass('active');

        selectedSizeSlug = sizeSlug;
        selectedWidthSlug = widthSlug || selectedWidthSlug;
        selectedVariationId = variationId;
        currentMaxQty = maxQty > 0 ? maxQty : 99;

        $('#selected_variation_id').val(selectedVariationId);
        $('#selected_size_slug').val(selectedSizeSlug);
        $('#selected_width_slug').val(selectedWidthSlug);

        if (priceHtml) {
            $('#product-price').html(priceHtml);
        }

        $('#quantity').attr('max', currentMaxQty);
        if (parseInt($('#quantity').val()) > currentMaxQty) {
            $('#quantity').val(currentMaxQty);
        }

        if (hasWidth && selectedWidthSlug) {
            $('#width-select').val(selectedWidthSlug);
            refreshWidthOptions();
        }

        $('.size-error-message').hide().text('');
    });
    <?php endif; ?>

    // ==============================================
    // Width Selection Handler
    // ==============================================
    <?php if ($has_width) : ?>
    $('#width-select').on('change', function() {
        let widthSlug = $(this).val();

        selectedWidthSlug = widthSlug;
        $('#selected_width_slug').val(selectedWidthSlug);

        if (!selectedWidthSlug) {
            $('#selected_variation_id').val('');
            $('.size-pill').removeClass('active');
            return;
        }

        updateSizeButtonsAvailability(selectedWidthSlug);

        let variation = findVariation(selectedSizeSlug, selectedWidthSlug);

        if (variation) {
            applyVariation(variation);
            $('.size-pill').removeClass('active');
            $(`.size-pill[data-size-slug="${selectedSizeSlug}"]`).addClass('active');
        } else {
            let firstAvailable = null;
            for (let i = 0; i < availableVariations.length; i++) {
                if (availableVariations[i].width_slug === selectedWidthSlug) {
                    firstAvailable = availableVariations[i];
                    break;
                }
            }

            if (firstAvailable) {
                selectedSizeSlug = firstAvailable.size_slug;
                selectedWidthSlug = firstAvailable.width_slug;
                $('#selected_size_slug').val(selectedSizeSlug);
                applyVariation(firstAvailable);

                $('.size-pill').removeClass('active');
                $(`.size-pill[data-size-slug="${selectedSizeSlug}"]`).addClass('active');
            } else {
                $('.size-error-message')
                    .text('<?php _e('No available variations for selected width', 'loomaama'); ?>')
                    .show();
            }
        }
    });
    <?php endif; ?>

    // ==============================================
    // Add to Cart Handler - FIXED with header update
    // ==============================================
    $('#add-to-cart-btn').on('click', function() {
        let $btn = $(this);
        let quantity = $('#quantity').val();
        let variationId = $('#selected_variation_id').val();
        let isVariableProduct = $('#is_variable_product').val() === '1';

        if (isVariableProduct && (!variationId || variationId === '')) {
            let message = '<?php _e('Please select all options', 'loomaama'); ?>';
            $('.size-error-message').text(message).show();
            return;
        }

        $btn.addClass('loading').prop('disabled', true);

        let formData = new FormData();
        formData.append('action', 'woocommerce_ajax_add_to_cart');
        formData.append('product_id', productId);
        formData.append('quantity', quantity);
        if (variationId && variationId !== '') {
            formData.append('variation_id', variationId);
        }
        formData.append('nonce', '<?php echo wp_create_nonce('loomaama_nonce'); ?>');

        if (hasSize && selectedSizeSlug) {
            formData.append('variation[attribute_pa_size]', selectedSizeSlug);
        }

        if (hasWidth && selectedWidthSlug) {
            formData.append('variation[attribute_pa_width]', selectedWidthSlug);
        }

        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Show success notification
                    showNotification(response.data.message ||
                        '<?php _e('Product added to cart!', 'loomaama'); ?>');

                    // Update header cart count immediately
                    let newCartCount = response.data.cart_count;
                    updateHeaderCartCount(newCartCount);

                    // Refresh cart drawer if function exists
                    if (typeof refreshCartDrawer === 'function') {
                        refreshCartDrawer();
                    }

                    // Trigger WooCommerce fragment refresh
                    $(document.body).trigger('wc_fragment_refresh');
                    
                    // Also trigger custom event for other components
                    $(document).trigger('cart_updated', [newCartCount]);

                } else {
                    let errorMsg = response.data.message ||
                        '<?php _e('Error adding to cart', 'loomaama'); ?>';
                    $('.size-error-message').text(errorMsg).show();
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                alert('<?php _e('Error adding to cart. Please try again.', 'loomaama'); ?>');
            },
            complete: function() {
                $btn.removeClass('loading').prop('disabled', false);
                setTimeout(function() {
                    $('.size-error-message').fadeOut();
                }, 3000);
            }
        });
    });
    // ==============================================
    // Quantity Buttons
    // ==============================================
    $('.quantity-minus').on('click', function() {
        let $input = $('#quantity');
        let val = parseInt($input.val());
        let min = parseInt($input.attr('min')) || 1;
        if (val > min) $input.val(val - 1);
    });

    $('.quantity-plus').on('click', function() {
        let $input = $('#quantity');
        let val = parseInt($input.val());
        let max = parseInt($input.attr('max')) || 99;
        if (val < max) $input.val(val + 1);
    });

    // ==============================================
    // Initialize: Set initial state
    // ==============================================
    <?php if ($pre_selected_price_html) : ?>
    $('#product-price').html('<?php echo esc_js($pre_selected_price_html); ?>');
    $('#quantity').attr('max', currentMaxQty);
    <?php endif; ?>

    <?php if ($has_width && $pre_selected_width_slug) : ?>
    updateSizeButtonsAvailability('<?php echo esc_js($pre_selected_width_slug); ?>');
    <?php endif; ?>

    refreshWidthOptions();

    // ==============================================
    // Accordion
    // ==============================================
    window.toggleAccordion = function(header) {
        const currentItem = header.closest('.accordion-item');
        const currentContent = currentItem.querySelector('.accordion-content');
        const currentIcon = header.querySelector('i');
        const isCurrentlyOpen = currentContent.classList.contains('active');
        const accordionContainer = currentItem.closest('.details-accordion');
        const allItems = accordionContainer.querySelectorAll('.accordion-item');

        allItems.forEach(item => {
            const content = item.querySelector('.accordion-content');
            const icon = item.querySelector('.accordion-header i');
            if (content.classList.contains('active')) {
                content.classList.remove('active');
                content.style.maxHeight = null;
            }
            if (icon) {
                icon.classList.remove('fa-minus');
                icon.classList.add('fa-plus');
            }
        });

        if (!isCurrentlyOpen) {
            currentContent.classList.add('active');
            currentContent.style.maxHeight = currentContent.scrollHeight + "px";
            currentIcon.classList.remove('fa-plus');
            currentIcon.classList.add('fa-minus');
        }
    };

    // ==============================================
    // Size Guide Drawer
    // ==============================================
    const openBtn = document.getElementById('openSizeGuide');
    const closeBtn = document.getElementById('closeSizeGuide');
    const drawer = document.getElementById('sizeGuideDrawer');
    if (openBtn && closeBtn && drawer) {
        openBtn.addEventListener('click', (e) => {
            e.preventDefault();
            drawer.classList.add('active');
            document.body.classList.add('drawer-open');
        });
        closeBtn.addEventListener('click', () => {
            drawer.classList.remove('active');
            document.body.classList.remove('drawer-open');
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                drawer.classList.remove('active');
                document.body.classList.remove('drawer-open');
            }
        });
    }

    // ==============================================
    // Gallery Navigation
    // ==============================================
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    if (prevBtn && nextBtn) {
        nextBtn.addEventListener('click', () => {
            if (typeof window.loomaamaGalleryStep === 'function') {
                window.loomaamaGalleryStep(1);
            }
        });
        prevBtn.addEventListener('click', () => {
            if (typeof window.loomaamaGalleryStep === 'function') {
                window.loomaamaGalleryStep(-1);
            }
        });
    }

    // ==============================================
    // Review Modal AJAX
    // ==============================================
    $('#viewReviewsModal').on('show.bs.modal', function() {
        var $container = $('#reviews-list-container');
        $container.html(
            '<div class="text-center py-4"><div class="spinner-border text-secondary"></div><p><?php _e('Loading reviews...', 'loomaama'); ?></p></div>'
            );
        $.ajax({
            url: loomaama_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'loomaama_get_reviews',
                product_id: productId,
                nonce: loomaama_ajax.nonce
            },
            success: function(response) {
                if (response.success) $container.html(response.data.html);
                else $container.html('<p class="text-center text-muted">' + response.data
                    .message + '</p>');
            },
            error: function() {
                $container.html(
                    '<p class="text-center text-danger"><?php _e('Failed to load reviews.', 'loomaama'); ?></p>'
                    );
            }
        });
    });

    $('#submit-review-form').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $btn = $form.find('button[type="submit"]');
        var $messageDiv = $('#review-form-message');
        $btn.prop('disabled', true).text('<?php _e('Submitting...', 'loomaama'); ?>');
        $messageDiv.html('');
        $.ajax({
            url: loomaama_ajax.ajax_url,
            type: 'POST',
            data: $form.serialize() + '&action=loomaama_submit_review',
            success: function(response) {
                if (response.success) {
                    $messageDiv.html('<div class="alert alert-success">' + response.data
                        .message + '</div>');
                    $form[0].reset();
                    setTimeout(function() {
                        $('#writeReviewModal').modal('hide');
                        $messageDiv.html('');
                    }, 2000);
                } else {
                    $messageDiv.html('<div class="alert alert-danger">' + response.data
                        .message + '</div>');
                }
            },
            error: function() {
                $messageDiv.html(
                    '<div class="alert alert-danger"><?php _e('An error occurred. Please try again.', 'loomaama'); ?></div>'
                    );
            },
            complete: function() {
                $btn.prop('disabled', false).text(
                    '<?php _e('Submit Review', 'loomaama'); ?>');
            }
        });
    });

    // Product gallery lightbox is initialized by loomaamaInitProductGallery().

    // ==============================================
    // Hover Image Effect
    // ==============================================
    $(document).trigger('hover_images_refresh');

    // ==============================================
    // Notification Function
    // ==============================================
    window.showNotification = function(message) {
        // Remove existing toast if any
        if ($('#notification-toast').length === 0) {
            $('body').append(
                '<div id="notification-toast" style="position: fixed; bottom: 20px; right: 20px; background: #28a745; color: white; padding: 12px 24px; border-radius: 8px; z-index: 9999; display: none; box-shadow: 0 2px 10px rgba(0,0,0,0.1); z-index: 99999;"></div>'
                );
        }
        var $toast = $('#notification-toast');
        $toast.text(message).fadeIn(300);
        setTimeout(function() {
            $toast.fadeOut(300);
        }, 3000);
    };
});
</script>
<script>
jQuery(function($) {
    
    // Store current variation ID to avoid redundant calls
    let currentLoadedVariationId = null;
    
    // Store default content on page load (for fallback)
    let defaultContent = {
        short_description: $('#variation-short-description-content').html(),
        long_description: $('#variation-long-description-content').html(),
        product_details: $('#variation-details-content').html(),
        artisan: $('#variation-artisan-content').html(),
        care: $('#variation-care-content').html(),
        sensory: $('#variation-sensory-content').html()
    };
    
    // Store default gallery HTML and video
    let defaultGalleryHtml = '';
    let hasDefaultVideo = false;
    let defaultVideoHtml = '';
    
    // Capture default gallery and video on page load
    function captureDefaultGallery() {
        var $gallery = $('#productGallery');
        defaultGalleryHtml = $gallery.html();
        
        // Check if there's a video in default gallery
        var $defaultVideo = $gallery.find('video').closest('a.glightbox');
        if ($defaultVideo.length) {
            hasDefaultVideo = true;
            defaultVideoHtml = $defaultVideo[0].outerHTML;
        } else {
            hasDefaultVideo = false;
            defaultVideoHtml = '';
        }
        
        console.log('Default gallery captured:', defaultGalleryHtml.substring(0, 100));
    }
    
    function updateVariationAccordionItem(itemSelector, contentSelector, value, defaultValue) {
        // Check if value exists and is not empty
        let hasValue = value && value.trim() !== '' && value !== '<p></p>' && value !== '<p><br></p>';
        
        if (hasValue) {
            // Use variation-specific content
            $(contentSelector).html(value);
            $(itemSelector).show();
        } else if (defaultValue && defaultValue.trim() !== '') {
            // Fallback to default content
            $(contentSelector).html(defaultValue);
            $(itemSelector).show();
        } else {
            // No content at all - hide the item
            $(contentSelector).html('');
            $(itemSelector).hide();
        }
    }
    
    // Function to update gallery with fallback to default
    function updateGallery(galleryData) {
        var $gallery = $('#productGallery');
        
        // Check if variation has custom gallery images
        if (galleryData && galleryData.length > 0) {
            // Build custom gallery HTML
            var galleryHtml = '';
            galleryData.forEach(function(img) {
                galleryHtml += `
                    <a href="${img.url}" class="glightbox" data-aos="fade-up">
                        <figure>
                            <img src="${img.url}" alt="${img.alt || ''}" loading="lazy">
                        </figure>
                    </a>
                `;
            });
            
            if (galleryHtml) {
                $gallery.removeData('loomaamaGalleryItems loomaamaGalleryIndex');
                $gallery.html(galleryHtml);
                if (typeof window.loomaamaInitProductGallery === 'function') {
                    window.loomaamaInitProductGallery($gallery.get(0));
                }
                console.log('Using custom variation gallery');
                return true;
            }
        }
        
        // No custom gallery - restore default gallery
        if (defaultGalleryHtml) {
            $gallery.removeData('loomaamaGalleryItems loomaamaGalleryIndex');
            $gallery.html(defaultGalleryHtml);
            if (typeof window.loomaamaInitProductGallery === 'function') {
                window.loomaamaInitProductGallery($gallery.get(0));
            }
            console.log('Restoring default gallery');
            return true;
        }
        
        return false;
    }
    
    function loomaamaLoadVariationACF(variationId) {
        if (!variationId || variationId === '0' || variationId === currentLoadedVariationId) {
            return;
        }
        
        console.log('Loading variation ACF data for:', variationId);
        
        $.ajax({
            url: loomaama_ajax.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'loomaama_get_variation_acf_data',
                variation_id: variationId,
                nonce: loomaama_ajax.nonce
            },
            beforeSend: function() {
                // Optional: Show loading state
                $('#variation-short-description-content').addClass('loading');
                $('#productGallery').addClass('loading-gallery');
            },
            success: function(response) {
                console.log('ACF Data response:', response);
                
                if (!response.success) {
                    console.error('Error loading variation data:', response.data);
                    loomaamaResetToDefaultContent();
                    return;
                }
                
                var data = response.data;
                currentLoadedVariationId = variationId;
                
                // Update gallery (with fallback to default)
                updateGallery(data.gallery);
                refreshAOS();
                 // Trigger hover handler refresh for related products
                if (window.loomaamaHoverHandler) {
                    window.loomaamaHoverHandler.refresh();
                }
                // Update each accordion item with proper fallback to default
                updateVariationAccordionItem(
                    '#variation-short-description-item', 
                    '#variation-short-description-content', 
                    data.short_description,
                    defaultContent.short_description
                );
                
                updateVariationAccordionItem(
                    '#variation-long-description-item', 
                    '#variation-long-description-content', 
                    data.long_description,
                    defaultContent.long_description
                );
                
                updateVariationAccordionItem(
                    '#variation-details-item', 
                    '#variation-details-content', 
                    data.product_details,
                    defaultContent.product_details
                );
                
                updateVariationAccordionItem(
                    '#variation-artisan-item', 
                    '#variation-artisan-content', 
                    data.artisan,
                    defaultContent.artisan
                );
                
                updateVariationAccordionItem(
                    '#variation-care-item', 
                    '#variation-care-content', 
                    data.care,
                    defaultContent.care
                );
                
                updateVariationAccordionItem(
                    '#variation-sensory-item', 
                    '#variation-sensory-content', 
                    data.sensory,
                    defaultContent.sensory
                );
                
                // The custom gallery renderer reinitializes its own lightbox.
                
                // Remove loading state
                $('#variation-short-description-content').removeClass('loading');
                $('#productGallery').removeClass('loading-gallery');
              
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                console.error('Response:', xhr.responseText);
                loomaamaResetToDefaultContent();
                $('#variation-short-description-content').removeClass('loading');
                $('#productGallery').removeClass('loading-gallery');
            }
        });
    }
    
    // Function to reset to default content
    function loomaamaResetToDefaultContent() {
        console.log('Resetting to default content');
        
        // Restore default gallery
        if (defaultGalleryHtml) {
            var $defaultGallery = $('#productGallery');
            $defaultGallery.removeData('loomaamaGalleryItems loomaamaGalleryIndex');
            $defaultGallery.html(defaultGalleryHtml);
            if (typeof window.loomaamaInitProductGallery === 'function') {
                window.loomaamaInitProductGallery($defaultGallery.get(0));
            }
        }
        
        // Update short description
        if (defaultContent.short_description && defaultContent.short_description.trim() !== '') {
            $('#variation-short-description-content').html(defaultContent.short_description);
            $('#variation-short-description-item').show();
        } else {
            $('#variation-short-description-item').hide();
        }
        
        // Update long description
        if (defaultContent.long_description && defaultContent.long_description.trim() !== '') {
            $('#variation-long-description-content').html(defaultContent.long_description);
            $('#variation-long-description-item').show();
        } else {
            $('#variation-long-description-item').hide();
        }
        
        // Update details
        if (defaultContent.product_details && defaultContent.product_details.trim() !== '') {
            $('#variation-details-content').html(defaultContent.product_details);
            $('#variation-details-item').show();
        } else {
            $('#variation-details-item').hide();
        }
        
        // Update artisan
        if (defaultContent.artisan && defaultContent.artisan.trim() !== '') {
            $('#variation-artisan-content').html(defaultContent.artisan);
            $('#variation-artisan-item').show();
        } else {
            $('#variation-artisan-item').hide();
        }
        
        // Update care
        if (defaultContent.care && defaultContent.care.trim() !== '') {
            $('#variation-care-content').html(defaultContent.care);
            $('#variation-care-item').show();
        } else {
            $('#variation-care-item').hide();
        }
        
        // Update sensory
        if (defaultContent.sensory && defaultContent.sensory.trim() !== '') {
            $('#variation-sensory-content').html(defaultContent.sensory);
            $('#variation-sensory-item').show();
        } else {
            $('#variation-sensory-item').hide();
        }
    }
    
    // Function to get current selected variation ID
    function getSelectedVariationId() {
        // Try WooCommerce variation form first
        var variationId = $('input[name="variation_id"]').val();
        
        // Fallback to our custom hidden field
        if (!variationId || variationId === '0') {
            variationId = $('#selected_variation_id').val();
        }
        
        return variationId;
    }
    
    // Watch for variation selection
    function checkAndLoadVariation() {
        var variationId = getSelectedVariationId();
        if (variationId && variationId !== '0' && variationId !== currentLoadedVariationId) {
            loomaamaLoadVariationACF(variationId);
        } else if (!variationId || variationId === '0') {
            // No variation selected, reset to default
            loomaamaResetToDefaultContent();
            currentLoadedVariationId = null;
        }
    }
    
    // Capture the unmodified gallery so variation fallbacks remain reliable.
    captureDefaultGallery();

    if (typeof window.loomaamaInitProductGallery === 'function') {
        window.loomaamaInitProductGallery(document.getElementById('productGallery'));
    }
    
    // WooCommerce native variation change event
    $(document).on('found_variation', 'form.variations_form', function(event, variation) {
        if (variation && variation.variation_id) {
            loomaamaLoadVariationACF(variation.variation_id);
        }
    });
    
    // Custom size pill click handler (if using custom size buttons)
    $(document).on('click', '.size-pill:not(.disabled)', function() {
        setTimeout(function() {
            checkAndLoadVariation();
        }, 100);
    });
    
    // Width select change handler
    $('#width-select').on('change', function() {
        setTimeout(function() {
            checkAndLoadVariation();
        }, 100);
    });
    
    // Watch for our custom variation ID hidden field changes
    $('#selected_variation_id').on('change', function() {
        checkAndLoadVariation();
    });
    
    // Initial load - check if variation is pre-selected
    setTimeout(function() {
        var initialVariationId = getSelectedVariationId();
        console.log('Initial variation ID:', initialVariationId);
        if (initialVariationId && initialVariationId !== '0') {
            loomaamaLoadVariationACF(initialVariationId);
        } else {
            // Make sure default content is shown initially
            loomaamaResetToDefaultContent();
        }
    }, 500);
    
    // Also watch for WooCommerce's built-in variation selection
    $(document.body).on('woocommerce_variation_has_changed', function() {
        checkAndLoadVariation();
    });
});
</script>

<?php get_footer(); ?>