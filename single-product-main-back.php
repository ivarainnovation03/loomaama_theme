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
 * @version 2.1.8-seamless-loop-gallery
 */

/* This template uses its own inline zoom, so the old lightbox assets are not needed. */
add_action('wp_enqueue_scripts', function() {
    if (is_product()) {
        wp_dequeue_style('photoswipe');
        wp_dequeue_style('photoswipe-css');
        wp_dequeue_script('photoswipe-lightbox');
    }
}, 100);

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
    $related_cache_key = 'related_products_carousel_v2_' . $product_id;
    $related_products = get_transient($related_cache_key);
    
    if ($related_products === false) {
        // Keep enough items available for the existing carousel arrows to move.
        $related_count = max(8, (int) get_theme_mod('related_products_count', 4));
        $related_products = wc_get_related_products($product_id, $related_count);
        set_transient($related_cache_key, $related_products, 6 * HOUR_IN_SECONDS);
    }
}
?>

<?php
// =====================================================
// EDITORIAL SINGLE-PRODUCT VIEW DATA
// =====================================================
$product_sku = $product->get_sku();
$product_categories = wp_get_post_terms($product_id, 'product_cat');
$product_category_name = (!empty($product_categories) && !is_wp_error($product_categories))
    ? $product_categories[0]->name
    : __('Collection', 'loomaama');

// Current colour and pattern, with URL selection taking priority.
$url_color = isset($_GET['color']) ? sanitize_title(wp_unslash($_GET['color'])) : '';
$current_color_slug = $url_color ?: $current_color_slug;

if (empty($current_color_slug)) {
    $color_terms = wp_get_post_terms($product_id, 'pa_color', array('fields' => 'slugs'));
    $current_color_slug = !empty($color_terms) ? $color_terms[0] : '';
}

if (empty($current_pattern_slug)) {
    $pattern_terms = wp_get_post_terms($product_id, 'pa_pattern', array('fields' => 'slugs'));
    $current_pattern_slug = !empty($pattern_terms) ? $pattern_terms[0] : '';
}

// Create a colour + pattern lookup once, before rendering either selector.
$design_group_products_map = array();
$lookup_product_ids = !empty($variant_ids) ? $variant_ids : array($product_id);

foreach ($lookup_product_ids as $lookup_product_id) {
    $lookup_color = get_post_meta($lookup_product_id, 'variant_color', true);
    $lookup_pattern = get_post_meta($lookup_product_id, 'variant_pattern', true);

    if (empty($lookup_color)) {
        $lookup_color_terms = wp_get_post_terms($lookup_product_id, 'pa_color', array('fields' => 'slugs'));
        $lookup_color = !empty($lookup_color_terms) ? $lookup_color_terms[0] : '';
    }

    if (empty($lookup_pattern)) {
        $lookup_pattern_terms = wp_get_post_terms($lookup_product_id, 'pa_pattern', array('fields' => 'slugs'));
        $lookup_pattern = !empty($lookup_pattern_terms) ? $lookup_pattern_terms[0] : '';
    }

    if ($lookup_color && $lookup_pattern) {
        $design_group_products_map[$lookup_color . '|' . $lookup_pattern] = $lookup_product_id;
    }
}

$color_patterns_map = array();
foreach ($design_group_products_map as $lookup_key => $lookup_product_id) {
    $lookup_parts = explode('|', $lookup_key, 2);
    if (count($lookup_parts) !== 2) {
        continue;
    }

    list($lookup_color, $lookup_pattern) = $lookup_parts;
    if (!isset($color_patterns_map[$lookup_color])) {
        $color_patterns_map[$lookup_color] = array();
    }
    $color_patterns_map[$lookup_color][$lookup_pattern] = $lookup_product_id;
}

$all_patterns = function_exists('loomaama_get_pattern_attributes_with_images')
    ? loomaama_get_pattern_attributes_with_images()
    : array();

$display_color_name = '';
foreach ($all_global_colors as $global_color) {
    if ($global_color['slug'] === $current_color_slug) {
        $display_color_name = $global_color['name'];
        break;
    }
}

$display_pattern_name = $current_pattern_slug
    ? ucwords(str_replace('-', ' ', $current_pattern_slug))
    : '';

/*
 * Replace colour dots and pattern swatches with the featured images of products
 * in the same design group. Labels remain available to assistive technology,
 * but the visible control is image-only.
 */
$variation_image_choices = array();
$variation_choice_ids = array_values(array_unique(array_merge(array($product_id), $lookup_product_ids)));

foreach ($variation_choice_ids as $variation_choice_id) {
    $variation_choice_product = isset($design_group_products[$variation_choice_id])
        ? $design_group_products[$variation_choice_id]
        : wc_get_product($variation_choice_id);

    if (!$variation_choice_product || !$variation_choice_product->is_visible()) {
        continue;
    }

    $variation_choice_image_id = $variation_choice_product->get_image_id();
    $variation_choice_image = $variation_choice_image_id
        ? wp_get_attachment_image_url($variation_choice_image_id, 'woocommerce_thumbnail')
        : wc_placeholder_img_src('woocommerce_thumbnail');

    if (!$variation_choice_image) {
        continue;
    }

    $variation_choice_labels = array();
    foreach (array('pa_color', 'pa_pattern') as $variation_taxonomy) {
        $variation_terms = wp_get_post_terms($variation_choice_id, $variation_taxonomy, array('fields' => 'names'));
        if (!is_wp_error($variation_terms) && !empty($variation_terms)) {
            $variation_choice_labels = array_merge($variation_choice_labels, $variation_terms);
        }
    }

    $variation_image_choices[] = array(
        'product_id' => $variation_choice_id,
        'url'        => get_permalink($variation_choice_id),
        'image'      => $variation_choice_image,
        'label'      => !empty($variation_choice_labels)
            ? implode(', ', array_unique($variation_choice_labels))
            : $variation_choice_product->get_name(),
        'is_current' => $variation_choice_id === $product_id,
    );
}

$variation_images_use_slider = count($variation_image_choices) > 4;

$display_size_name = '';
foreach ($all_size_terms as $size_term) {
    if ($size_term['slug'] === $pre_selected_size_slug) {
        $display_size_name = $size_term['name'];
        break;
    }
}

$display_width_name = '';
foreach ($all_width_terms as $width_term) {
    if ($width_term['slug'] === $pre_selected_width_slug) {
        $display_width_name = $width_term['name'];
        break;
    }
}

// Build one responsive gallery data set. The featured image is always first.
$featured_video_url = '';
if (is_array($featured_video) && !empty($featured_video['url'])) {
    $featured_video_url = $featured_video['url'];
} elseif (is_string($featured_video)) {
    $featured_video_url = $featured_video;
}

$product_media_items = array();
$seen_media_ids = array();

if ($featured_image_id) {
    $product_media_items[] = array(
        'type'          => 'image',
        'attachment_id' => $featured_image_id,
        'url'           => wp_get_attachment_image_url($featured_image_id, 'full'),
        'thumb'         => wp_get_attachment_image_url($featured_image_id, 'woocommerce_thumbnail'),
        'alt'           => get_post_meta($featured_image_id, '_wp_attachment_image_alt', true) ?: $product_name,
    );
    $seen_media_ids[$featured_image_id] = true;
}

foreach ($product_gallery_ids as $gallery_attachment_id) {
    if (isset($seen_media_ids[$gallery_attachment_id])) {
        continue;
    }

    $product_media_items[] = array(
        'type'          => 'image',
        'attachment_id' => $gallery_attachment_id,
        'url'           => wp_get_attachment_image_url($gallery_attachment_id, 'full'),
        'thumb'         => wp_get_attachment_image_url($gallery_attachment_id, 'woocommerce_thumbnail'),
        'alt'           => get_post_meta($gallery_attachment_id, '_wp_attachment_image_alt', true) ?: $product_name,
    );
    $seen_media_ids[$gallery_attachment_id] = true;
}

if ($featured_video_url) {
    $product_media_items[] = array(
        'type'          => 'video',
        'attachment_id' => 0,
        'url'           => $featured_video_url,
        'thumb'         => $featured_image_id ? wp_get_attachment_image_url($featured_image_id, 'woocommerce_thumbnail') : '',
        'alt'           => sprintf(__('%s product video', 'loomaama'), $product_name),
    );
}

if (empty($product_media_items)) {
    $placeholder_url = wc_placeholder_img_src('woocommerce_single');
    $product_media_items[] = array(
        'type'          => 'image',
        'attachment_id' => 0,
        'url'           => $placeholder_url,
        'thumb'         => $placeholder_url,
        'alt'           => $product_name,
    );
}

$selection_label = $has_size
    ? __('Select Size', 'loomaama')
    : ($has_width ? __('Select Width', 'loomaama') : __('Add to Bag', 'loomaama'));
?>

<div class="product-drawer-overlay" id="productDrawerOverlay" aria-hidden="true"></div>

<?php if ($has_size || $has_width) : ?>
<aside class="size-guide-drawer product-selection-drawer" id="sizeGuideDrawer" aria-hidden="true" aria-labelledby="productSelectionTitle">
    <div class="product-selection-drawer__header">
        <div>
            <p class="product-selection-drawer__eyebrow"><?php echo esc_html($product_name); ?></p>
            <h2 id="productSelectionTitle"><?php echo $has_size ? esc_html__('Sizes', 'loomaama') : esc_html__('Widths', 'loomaama'); ?></h2>
        </div>
        <button class="drawer-close" id="closeSizeGuide" type="button" aria-label="<?php esc_attr_e('Close size selection', 'loomaama'); ?>">
            <span aria-hidden="true">×</span>
        </button>
    </div>

    <div class="product-selection-drawer__body">
        <?php if ($has_size && !empty($all_size_terms)) : ?>
        <section class="drawer-option-group" aria-labelledby="drawer-size-title">
            <div class="drawer-option-group__heading">
                <h3 id="drawer-size-title"><?php esc_html_e('Choose your size', 'loomaama'); ?></h3>
                <span id="drawer-selected-size"><?php echo esc_html($display_size_name); ?></span>
            </div>

            <div class="size-buttons-wrapper size-buttons-wrapper--drawer">
                <?php foreach ($all_size_terms as $size_term) :
                    $size_name = $size_term['name'];
                    $size_slug = $size_term['slug'];

                    if ($has_size && $has_width) {
                        $current_width = isset($_GET['width'])
                            ? sanitize_title(wp_unslash($_GET['width']))
                            : $pre_selected_width_slug;
                        $variation_key = $size_slug . '|' . $current_width;
                    } else {
                        $variation_key = $size_slug;
                    }

                    $is_available = isset($available_variations_lookup[$variation_key]);
                    $variation_data = $is_available ? $available_variations_lookup[$variation_key] : null;
                    $is_active = $variation_data && ((string) $pre_selected_variation_id === (string) $variation_data['variation_id']);
                ?>
                    <?php if ($variation_data) : ?>
                    <button type="button"
                        class="size-pill<?php echo $is_active ? ' active' : ''; ?>"
                        data-variation-id="<?php echo esc_attr($variation_data['variation_id']); ?>"
                        data-size-slug="<?php echo esc_attr($size_slug); ?>"
                        data-size-name="<?php echo esc_attr($size_name); ?>"
                        data-width-slug="<?php echo esc_attr($variation_data['width_slug']); ?>"
                        data-price-html="<?php echo esc_attr($variation_data['price_html']); ?>"
                        data-max-qty="<?php echo esc_attr($variation_data['max_qty']); ?>">
                        <?php echo esc_html($size_name); ?>
                    </button>
                    <?php else : ?>
                    <button type="button" class="size-pill disabled" disabled aria-disabled="true">
                        <?php echo esc_html($size_name); ?>
                    </button>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <div class="size-error-message" role="alert" aria-live="polite"></div>
        </section>
        <?php endif; ?>

        <?php if ($has_width && !empty($all_width_terms)) : ?>
        <section class="drawer-option-group" aria-labelledby="drawer-width-title">
            <div class="drawer-option-group__heading">
                <h3 id="drawer-width-title"><?php esc_html_e('Choose your width', 'loomaama'); ?></h3>
                <span id="drawer-selected-width"><?php echo esc_html($display_width_name); ?></span>
            </div>
            <select id="width-select" class="form-select width-select" aria-label="<?php esc_attr_e('Choose width', 'loomaama'); ?>">
                <option value=""><?php esc_html_e('Select Width', 'loomaama'); ?></option>
                <?php foreach ($all_width_terms as $width_term) :
                    $width_name = $width_term['name'];
                    $width_slug = $width_term['slug'];
                    $width_available = false;

                    foreach ($available_variations as $available_variation) {
                        if ($available_variation['width_slug'] === $width_slug) {
                            $width_available = true;
                            break;
                        }
                    }
                ?>
                <option value="<?php echo esc_attr($width_slug); ?>"
                    <?php selected($pre_selected_width_slug, $width_slug); ?>
                    <?php disabled(!$width_available); ?>>
                    <?php echo esc_html($width_name . (!$width_available ? ' (' . __('Not available', 'loomaama') . ')' : '')); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </section>
        <?php endif; ?>

        <section class="drawer-option-group drawer-quantity-group" aria-labelledby="drawer-quantity-title">
            <div class="drawer-option-group__heading">
                <h3 id="drawer-quantity-title"><?php esc_html_e('Quantity', 'loomaama'); ?></h3>
            </div>
            <div class="quantity-selector">
                <button type="button" class="quantity-btn quantity-minus" aria-label="<?php esc_attr_e('Decrease quantity', 'loomaama'); ?>">−</button>
                <input type="number" id="quantity" name="quantity" class="quantity-input" value="1" min="1" max="<?php echo esc_attr($pre_selected_max_qty ?: 99); ?>" step="1">
                <button type="button" class="quantity-btn quantity-plus" aria-label="<?php esc_attr_e('Increase quantity', 'loomaama'); ?>">+</button>
            </div>
        </section>

        <button type="button" id="add-to-cart-btn" class="add-to-cart-btn" data-product-id="<?php echo esc_attr($product_id); ?>">
            <?php esc_html_e('Add to Bag', 'loomaama'); ?>
        </button>

        <details class="drawer-size-guide">
            <summary><?php esc_html_e('Size Guide', 'loomaama'); ?></summary>
            <p><?php esc_html_e('Measure close to the body without pulling the tape tight. Product measurements may vary slightly because every piece is shaped by hand.', 'loomaama'); ?></p>
            <table class="size-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Size', 'loomaama'); ?></th>
                        <th><?php esc_html_e('Chest', 'loomaama'); ?></th>
                        <th><?php esc_html_e('Waist', 'loomaama'); ?></th>
                        <th><?php esc_html_e('Hips', 'loomaama'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>XS</td><td>80–84</td><td>62–66</td><td>88–92</td></tr>
                    <tr><td>S</td><td>84–88</td><td>66–70</td><td>92–96</td></tr>
                    <tr><td>M</td><td>88–92</td><td>70–74</td><td>96–100</td></tr>
                    <tr><td>L</td><td>92–96</td><td>74–78</td><td>100–104</td></tr>
                    <tr><td>XL</td><td>96–100</td><td>78–82</td><td>104–108</td></tr>
                </tbody>
            </table>
        </details>
    </div>
</aside>
<?php endif; ?>

<style id="loomaama-single-product-feature-styles">
/* Interaction-only rules: the existing product-gallery dimensions and layout stay untouched. */
@media screen and (min-width:821px){
		.single-product .loomaama-product-summary__purchase{
		position:sticky;
		top:85px;
		}
}
#productGallery .product-media-item figure {
    overflow: hidden;
}

#productGallery {
    position: relative;
    overflow: hidden;
    touch-action: pan-y pinch-zoom;
}

#productGallery.is-touch-dragging .product-gallery-track {
    transition: none !important;
}

#productGallery.is-loop-resetting > .product-gallery-track {
    transition: none !important;
}

#productGallery.is-loop-animating {
    pointer-events: none;
}

#productGallery.is-loop-animating > .product-gallery-track {
    visibility: hidden;
}

#productGallery .product-gallery-loop-ghost {
    position: absolute;
    inset: 0;
    z-index: 8;
    overflow: hidden;
    pointer-events: none;
}

#productGallery .product-gallery-loop-strip {
    display: flex;
    width: 200%;
    height: 100%;
    will-change: transform;
    transition: transform 520ms cubic-bezier(0.22, 0.61, 0.36, 1);
}

#productGallery .product-gallery-loop-panel {
    position: relative;
    flex: 0 0 50%;
    width: 50%;
    height: 100%;
    overflow: hidden;
}

#productGallery .product-gallery-loop-panel > .product-gallery-track {
    height: 100%;
    transition: none !important;
}

#productGallery .product-gallery-loop-panel .product-media-item {
    pointer-events: none;
}

@media (prefers-reduced-motion: reduce) {
    #productGallery .product-gallery-loop-strip {
        transition-duration: 1ms;
    }
}

#productGallery .product-media-item[data-type="video"] {
    cursor: pointer;
}

#productGallery .product-media-item:not([data-type="video"]) {
    cursor: zoom-in;
}

#productGallery .product-media-item:not([data-type="video"]) img {
    user-select: none;
    -webkit-user-drag: none;
    will-change: transform;
    transform: translate3d(0, 0, 0) scale(1);
    transition: transform 220ms ease;
}

#productGallery .product-media-item.is-zoomed {
    cursor: grab;
    touch-action: none;
}

#productGallery .product-media-item.is-zoomed.is-dragging {
    cursor: grabbing;
}

#productGallery .product-media-item.is-zoomed img,
#productGallery .product-media-item.is-dragging img {
    transition: none;
}

.variation-image-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 10px;
}

.variation-image-slider-shell {
    display: grid;
    grid-template-columns: auto minmax(0, 1fr) auto;
    gap: 8px;
    align-items: center;
}

.variation-image-slider {
    min-width: 0;
    width: 100%;
    overflow: hidden;
}

.variation-image-choice {
    display: block;
    width: 100%;
    aspect-ratio: 1 / 1;
    overflow: hidden;
    border: 1px solid rgba(39, 35, 31, 0.18);
    background: #f4eee4;
}

.variation-image-choice.active {
    border: 2px solid #9e8574;
}

.variation-image-choice img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.variation-image-arrow {
    display: inline-grid;
    place-items: center;
    width: 30px;
    height: 30px;
    padding: 0;
    border: 1px solid rgba(39, 35, 31, 0.28);
    border-radius: 50%;
    background: transparent;
    color: inherit;
    line-height: 1;
}

.variation-image-arrow.swiper-button-disabled {
    opacity: 0.3;
    cursor: default;
}
	#variation-artisan-content a{color:blue;}
	.variation-image-grid a:hover img{ transform:scale(1.3); transition:transform 700ms cubic-bezier(.22, 1, .36, 1);}
</style>

<main class="single-product-main">
    <?php while (have_posts()) : the_post(); ?>

    <section class="editorial-product-gallery" aria-label="<?php esc_attr_e('Product gallery', 'loomaama'); ?>">
        <div class="product-gallery-editorial" id="productGallery" data-gallery-build="2026-07-26-v5-seamless-loop"
            style="--gallery-count: <?php echo esc_attr(max(1, count($product_media_items))); ?>;">
            <div class="product-gallery-track" id="productGalleryTrack">
                <?php foreach ($product_media_items as $media_index => $media_item) : ?>
                <a href="<?php echo esc_url($media_item['url']); ?>"
                    class="product-media-item<?php echo $media_index === 0 ? ' is-active' : ''; ?>"
                    data-media-index="<?php echo esc_attr($media_index); ?>"
                    aria-pressed="false"
                    <?php echo $media_item['type'] === 'video' ? 'data-type="video"' : ''; ?>>
                    <figure>
                        <?php if ($media_item['type'] === 'video') : ?>
                        <video muted loop playsinline preload="metadata" poster="<?php echo esc_url($media_item['thumb']); ?>">
                            <source src="<?php echo esc_url($media_item['url']); ?>" type="video/mp4">
                        </video>
                        <span class="product-media-play" aria-hidden="true">▶</span>
                        <?php elseif (!empty($media_item['attachment_id'])) : ?>
                            <?php echo wp_get_attachment_image(
                                $media_item['attachment_id'],
                                'full',
                                false,
                                array(
                                    'alt'           => $media_item['alt'],
                                    'loading'       => $media_index === 0 ? 'eager' : 'lazy',
                                    'fetchpriority' => $media_index === 0 ? 'high' : 'auto',
                                    'decoding'      => 'async',
                                )
                            ); ?>
                        <?php else : ?>
                        <img src="<?php echo esc_url($media_item['url']); ?>" alt="<?php echo esc_attr($media_item['alt']); ?>" loading="eager">
                        <?php endif; ?>
                    </figure>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="product-gallery-controls<?php echo count($product_media_items) <= 1 ? ' is-single' : ''; ?>">
            <div class="product-gallery-thumbs" id="productGalleryThumbs" aria-label="<?php esc_attr_e('Choose a product image', 'loomaama'); ?>">
                <?php foreach ($product_media_items as $media_index => $media_item) : ?>
                <button type="button"
                    class="product-gallery-thumb<?php echo $media_index === 0 ? ' active' : ''; ?>"
                    data-gallery-thumb="<?php echo esc_attr($media_index); ?>"
                    aria-label="<?php echo esc_attr(sprintf(__('Show media %d', 'loomaama'), $media_index + 1)); ?>"
                    aria-pressed="<?php echo $media_index === 0 ? 'true' : 'false'; ?>">
                    <?php if (!empty($media_item['thumb'])) : ?>
                    <img src="<?php echo esc_url($media_item['thumb']); ?>" alt="" loading="lazy">
                    <?php else : ?>
                    <span aria-hidden="true"><?php echo $media_item['type'] === 'video' ? '▶' : $media_index + 1; ?></span>
                    <?php endif; ?>
                    <?php if ($media_item['type'] === 'video') : ?><span class="thumb-video-mark" aria-hidden="true">▶</span><?php endif; ?>
                </button>
                <?php endforeach; ?>
            </div>
            <button id="prevBtn" class="product-gallery-step" type="button" aria-label="<?php esc_attr_e('Previous image', 'loomaama'); ?>">←</button>
            <button id="nextBtn" class="product-gallery-step" type="button" aria-label="<?php esc_attr_e('Next image', 'loomaama'); ?>">→</button>
        </div>
    </section>

    <section class="loomaama-product-summary">
        <div class="loomaama-product-summary__details">
            <p class="product-category-kicker"><?php echo esc_html($product_category_name); ?></p>
            <h1 class="product-title"><?php echo esc_html($product_name); ?></h1>
            <div class="product-price" id="product-price"><?php echo wp_kses_post($product_price_html); ?></div>

            <?php if (count($variation_image_choices) > 1) : ?>
            <div class="product-choice-group variation-image-section">
                <div class="product-choice-heading">
                    <span><?php esc_html_e('Variation', 'loomaama'); ?></span>
                </div>
                <?php if ($variation_images_use_slider) : ?>
                <div class="variation-image-slider-shell">
                   
                    <div class="swiper variation-image-slider">
                        <div class="swiper-wrapper">
                            <?php foreach ($variation_image_choices as $variation_choice) : ?>
                            <div class="swiper-slide">
                                <a href="<?php echo esc_url($variation_choice['url']); ?>"
                                    class="variation-image-choice<?php echo $variation_choice['is_current'] ? ' active' : ''; ?>"
                                    aria-label="<?php echo esc_attr($variation_choice['label']); ?>"
                                    <?php echo $variation_choice['is_current'] ? 'aria-current="page"' : ''; ?>>
                                    <img src="<?php echo esc_url($variation_choice['image']); ?>" alt="" loading="lazy" decoding="async">
                                </a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                   
                </div>
                <?php else : ?>
                <div class="variation-image-grid">
                    <?php foreach ($variation_image_choices as $variation_choice) : ?>
                    <a href="<?php echo esc_url($variation_choice['url']); ?>"
                        class="variation-image-choice<?php echo $variation_choice['is_current'] ? ' active' : ''; ?>"
                        aria-label="<?php echo esc_attr($variation_choice['label']); ?>"
                        <?php echo $variation_choice['is_current'] ? 'aria-current="page"' : ''; ?>>
                        <img src="<?php echo esc_url($variation_choice['image']); ?>" alt="" loading="lazy" decoding="async">
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if ($has_size || $has_width) : ?>
            <button type="button" class="product-option-row js-open-product-drawer">
                <span><?php echo $has_size ? esc_html__('Size', 'loomaama') : esc_html__('Width', 'loomaama'); ?></span>
                <span>
                    <span id="selected-size-label"><?php echo esc_html($has_size ? $display_size_name : $display_width_name); ?></span>
                    <span class="product-option-row__plus" aria-hidden="true">+</span>
                </span>
            </button>
            <?php endif; ?>

            <div class="product-description-block" id="variation-short-description-item">
                <h2><?php esc_html_e('Product Description', 'loomaama'); ?></h2>
                <?php if ($product_sku) : ?><p class="product-style-code"><?php echo esc_html(sprintf(__('SKU: %s', 'loomaama'), $product_sku)); ?></p><?php endif; ?>
                <div class="product-description" id="variation-short-description-content">
                    <?php echo wp_kses_post(wpautop($product_short_description)); ?>
                </div>
            </div>

            <div class="details-accordion">
                <div class="accordion-item" id="variation-long-description-item" style="<?php echo empty($product_long_description) ? 'display:none;' : ''; ?>">
                    <button type="button" class="accordion-header" onclick="toggleAccordion(this)" aria-expanded="false">
                        <span><?php esc_html_e('Product Details', 'loomaama'); ?></span><i class="fa fa-plus" aria-hidden="true"></i>
                    </button>
                    <div class="accordion-content" id="variation-long-description-content"><?php echo wp_kses_post(wpautop($product_long_description)); ?></div>
                </div>

                <div class="accordion-item" id="variation-details-item" style="<?php echo empty($product_details) ? 'display:none;' : ''; ?>">
                    <button type="button" class="accordion-header" onclick="toggleAccordion(this)" aria-expanded="false">
                        <span><?php esc_html_e('Material & Construction', 'loomaama'); ?></span><i class="fa fa-plus" aria-hidden="true"></i>
                    </button>
                    <div class="accordion-content" id="variation-details-content"><?php echo wp_kses_post($product_details); ?></div>
                </div>

                <?php if ($product->has_attributes() || $product->has_dimensions() || $product->has_weight()) : ?>
                <div class="accordion-item">
                    <button type="button" class="accordion-header" onclick="toggleAccordion(this)" aria-expanded="false">
                        <span><?php esc_html_e('Provenance', 'loomaama'); ?></span><i class="fa fa-plus" aria-hidden="true"></i>
                    </button>
                    <div class="accordion-content"><?php do_action('woocommerce_product_additional_information', $product); ?></div>
                </div>
                <?php endif; ?>

                <div class="accordion-item" id="variation-artisan-item" style="<?php echo empty($artisan_story) ? 'display:none;' : ''; ?>">
                    <button type="button" class="accordion-header" onclick="toggleAccordion(this)" aria-expanded="false">
                        <span><?php esc_html_e('The Artisan', 'loomaama'); ?></span><i class="fa fa-plus" aria-hidden="true"></i>
                    </button>
                    <div class="accordion-content" id="variation-artisan-content"><?php echo wp_kses_post($artisan_story); ?></div>
                </div>

                <div class="accordion-item" id="variation-care-item" style="<?php echo empty($care_description) ? 'display:none;' : ''; ?>">
                    <button type="button" class="accordion-header" onclick="toggleAccordion(this)" aria-expanded="false">
                        <span><?php esc_html_e('Care', 'loomaama'); ?></span><i class="fa fa-plus" aria-hidden="true"></i>
                    </button>
                    <div class="accordion-content" id="variation-care-content"><?php echo wp_kses_post(wpautop($care_description)); ?></div>
                </div>

                <div class="accordion-item" id="variation-sensory-item" style="<?php echo empty($sensory_description) ? 'display:none;' : ''; ?>">
                    <button type="button" class="accordion-header" onclick="toggleAccordion(this)" aria-expanded="false">
                        <span><?php esc_html_e('How It Feels', 'loomaama'); ?></span><i class="fa fa-plus" aria-hidden="true"></i>
                    </button>
                    <div class="accordion-content" id="variation-sensory-content"><?php echo wp_kses_post($sensory_description); ?></div>
                </div>

                <div class="accordion-item">
                    <button type="button" class="accordion-header" onclick="toggleAccordion(this)" aria-expanded="false">
                        <span><?php esc_html_e('Delivery & Returns', 'loomaama'); ?></span><i class="fa fa-plus" aria-hidden="true"></i>
                    </button>
                    <div class="accordion-content"><p><?php esc_html_e('Returns are accepted within 14 days of receipt, subject to the Loomaama returns policy.', 'loomaama'); ?></p></div>
                </div>
            </div>

            <div class="product-review-links">
                <button type="button" data-bs-toggle="modal" data-bs-target="#viewReviewsModal">
                    <?php echo esc_html(sprintf(__('Read Reviews (%d)', 'loomaama'), $product_review_count)); ?>
                </button>
                <button type="button" data-bs-toggle="modal" data-bs-target="#writeReviewModal"><?php esc_html_e('Write a Review', 'loomaama'); ?></button>
            </div>
        </div>

        <aside id="summary_purchase" class="loomaama-product-summary__purchase" aria-label="<?php esc_attr_e('Purchase options', 'loomaama'); ?>">
            <div id="purchase-panel" class="purchase-panel">
                <p><?php esc_html_e('Choose your options to continue.', 'loomaama'); ?></p>

                <?php if ($has_size || $has_width) : ?>
                <button type="button" id="openSizeGuide" class="purchase-primary-button js-open-product-drawer">
                    <?php echo esc_html($selection_label); ?>
                </button>
                <?php else : ?>
                <div class="quantity-section">
                    <span class="label-title"><?php esc_html_e('Quantity', 'loomaama'); ?></span>
                    <div class="quantity-selector">
                        <button type="button" class="quantity-btn quantity-minus" aria-label="<?php esc_attr_e('Decrease quantity', 'loomaama'); ?>">−</button>
                        <input type="number" id="quantity" name="quantity" class="quantity-input" value="1" min="1" max="<?php echo esc_attr($pre_selected_max_qty ?: 99); ?>" step="1">
                        <button type="button" class="quantity-btn quantity-plus" aria-label="<?php esc_attr_e('Increase quantity', 'loomaama'); ?>">+</button>
                    </div>
                </div>
                <button type="button" id="add-to-cart-btn" class="purchase-primary-button add-to-cart-btn" data-product-id="<?php echo esc_attr($product_id); ?>">
                    <?php esc_html_e('Add to Bag', 'loomaama'); ?>
                </button>
                <?php endif; ?>

                <ul class="purchase-services">
                    <li><span aria-hidden="true">◇</span><span><?php esc_html_e('Secure checkout', 'loomaama'); ?></span></li>
                    <li><span aria-hidden="true">◇</span><span><?php esc_html_e('Handmade in Nepal', 'loomaama'); ?></span></li>
                    <li><span aria-hidden="true">◇</span><span><?php esc_html_e('Returns within 14 days', 'loomaama'); ?></span></li>
                </ul>

                <p class="purchase-note"><?php esc_html_e('Handwoven colour and texture can vary slightly from one piece to the next.', 'loomaama'); ?></p>
            </div>
        </aside>
    </section>

    <input type="hidden" id="selected_variation_id" name="variation_id" value="<?php echo esc_attr($pre_selected_variation_id); ?>">
    <input type="hidden" id="selected_size_slug" value="<?php echo esc_attr($pre_selected_size_slug); ?>">
    <input type="hidden" id="selected_width_slug" value="<?php echo esc_attr($pre_selected_width_slug); ?>">
    <input type="hidden" id="has_size" value="<?php echo $has_size ? '1' : '0'; ?>">
    <input type="hidden" id="has_width" value="<?php echo $has_width ? '1' : '0'; ?>">
    <input type="hidden" id="is_variable_product" value="<?php echo $is_variable_product ? '1' : '0'; ?>">

    <?php if ($show_related && !empty($related_products)) : ?>
    <section class="related-products-editorial">
        <h2><?php esc_html_e('You May Also Like', 'loomaama'); ?></h2>
        <div class="swiper related-slider">
            <div class="swiper-wrapper">
                <?php foreach ($related_products as $related_id) :
                    $related_product = wc_get_product($related_id);
                    if (!$related_product) {
                        continue;
                    }

                    $related_name = $related_product->get_name();
                    $related_price = $related_product->get_price_html();
                    $related_categories = wp_get_post_terms($related_id, 'product_cat');
                    $related_category = !empty($related_categories) ? $related_categories[0]->name : __('Nettle', 'loomaama');
                    $main_img_id = get_post_thumbnail_id($related_id);
                    $main_img_url = wp_get_attachment_image_url($main_img_id, 'medium_large') ?: wc_placeholder_img_src('medium_large');
                    $hover_img_url = '';

                    if (function_exists('get_field')) {
                        $hover_field = get_field('hover_image', $related_id);
                        if (is_array($hover_field) && !empty($hover_field['sizes']['medium_large'])) {
                            $hover_img_url = $hover_field['sizes']['medium_large'];
                        } elseif (is_numeric($hover_field)) {
                            $hover_img_url = wp_get_attachment_image_url($hover_field, 'medium_large');
                        } elseif (is_string($hover_field)) {
                            $hover_img_url = $hover_field;
                        }
                    }
                ?>
                <div class="swiper-slide">
                    <article class="lux-card">
                        <a href="<?php echo esc_url(get_permalink($related_id)); ?>" class="lux-img">
                            <img src="<?php echo esc_url($main_img_url); ?>" alt="<?php echo esc_attr($related_name); ?>" loading="lazy">
                            <?php if ($hover_img_url) : ?>
                            <img class="hover-overlay-img" src="<?php echo esc_url($hover_img_url); ?>" alt="" loading="lazy">
                            <?php endif; ?>
                        </a>
                        <div class="related-product-meta">
                            <p><?php echo esc_html($related_category); ?></p>
                           
                        </div>
                        <div class="related-product-title-price">
                            <a href="<?php echo esc_url(get_permalink($related_id)); ?>"><?php echo esc_html($related_name); ?></a>
                            <span><?php echo wp_kses_post($related_price); ?></span>
                        </div>
                    </article>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="related-products-navigation">
                <button class="btn-related-prev" type="button" aria-label="<?php esc_attr_e('Previous related products', 'loomaama'); ?>">←</button>
                <div class="swiper-pagination"></div>
                <button class="btn-related-next" type="button" aria-label="<?php esc_attr_e('Next related products', 'loomaama'); ?>">→</button>
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

<script type="text/javascript">
jQuery(document).ready(function($) {
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
                    closeProductDrawer();

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
                const itemHeader = item.querySelector('.accordion-header');
                if (itemHeader) itemHeader.setAttribute('aria-expanded', 'false');
                content.style.maxHeight = null;
            }
            if (icon) {
                icon.classList.remove('fa-minus');
                icon.classList.add('fa-plus');
            }
        });

        if (!isCurrentlyOpen) {
            currentContent.classList.add('active');
            header.setAttribute('aria-expanded', 'true');
            currentContent.style.maxHeight = currentContent.scrollHeight + "px";
            currentIcon.classList.remove('fa-plus');
            currentIcon.classList.add('fa-minus');
        }
    };

    // ==============================================
    // Product selection drawer
    // ==============================================
    const drawer = document.getElementById('sizeGuideDrawer');
    const drawerOverlay = document.getElementById('productDrawerOverlay');
    const closeBtn = document.getElementById('closeSizeGuide');
    const drawerOpenButtons = document.querySelectorAll('.js-open-product-drawer');

    function openProductDrawer(event) {
        if (event) event.preventDefault();
        if (!drawer) return;
        drawer.classList.add('active');
        drawer.setAttribute('aria-hidden', 'false');
        if (drawerOverlay) {
            drawerOverlay.classList.add('active');
            drawerOverlay.setAttribute('aria-hidden', 'false');
        }
        document.body.classList.add('drawer-open');
        if (closeBtn) closeBtn.focus();
    }

    function closeProductDrawer() {
        if (!drawer) return;
        drawer.classList.remove('active');
        drawer.setAttribute('aria-hidden', 'true');
        if (drawerOverlay) {
            drawerOverlay.classList.remove('active');
            drawerOverlay.setAttribute('aria-hidden', 'true');
        }
        document.body.classList.remove('drawer-open');
    }

    drawerOpenButtons.forEach(function(button) {
        button.addEventListener('click', openProductDrawer);
    });
    if (closeBtn) closeBtn.addEventListener('click', closeProductDrawer);
    if (drawerOverlay) drawerOverlay.addEventListener('click', closeProductDrawer);
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') closeProductDrawer();
    });

    // Keep the product summary and drawer labels synchronized.
    $(document).on('click', '.size-pill:not(.disabled)', function() {
        const sizeName = $(this).data('size-name') || $(this).text().trim();
        $('#selected-size-label, #drawer-selected-size').text(sizeName);
    });

    $('#width-select').on('change', function() {
        const widthName = $(this).find('option:selected').text().replace(/\s*\(Not available\)\s*$/, '');
        $('#drawer-selected-width').text(widthName);
        if (!hasSize) $('#selected-size-label').text(widthName);
    });

    // ==============================================
    // Editorial gallery behaviour
    // Desktop:
    //   Image 1 opens full width.
    //   Image 2 creates a 50/50 pair: 1 + 2.
    //   Image 3 and later slide the complete pair: 2 + 3, 3 + 4, and so on.
    // Mobile/tablet: one image slides at a time.
    // Arrow and swipe navigation use a cloned boundary transition, so moving
    // from last to first (or first to last) never visibly rewinds the track.
    // ==============================================
    window.LoomaamaGalleryBuild = '2026-07-26-v5-seamless-loop';
    window.LoomaamaProductGallery = (function() {
        let activeIndex = 0;
        let isLoopAnimating = false;
        let loopCleanup = null;
        let swipePointerId = null;
        let swipeStartX = 0;
        let swipeStartY = 0;
        let swipeDeltaX = 0;
        let swipeDeltaY = 0;
        let swipeBaseTranslate = 0;
        let isHorizontalSwipe = false;
        let suppressSwipeClickUntil = 0;
        const mobileQuery = window.matchMedia('(max-width: 820px)');
        const swipeThreshold = 46;

        function getElements() {
            const gallery = document.getElementById('productGallery');
            const track = document.getElementById('productGalleryTrack');

            return {
                gallery: gallery,
                track: track,
                thumbs: Array.from(document.querySelectorAll('[data-gallery-thumb]')),
                items: track
                    ? Array.from(track.children).filter(function(item) {
                        return item.classList.contains('product-media-item');
                    })
                    : [],
                previous: document.getElementById('prevBtn'),
                next: document.getElementById('nextBtn')
            };
        }

        function updateTrack(elements) {
            const count = elements.items.length;
            if (!elements.track || count === 0) return;

            const isMobile = mobileQuery.matches;
            const isSplit = !isMobile && activeIndex > 0 && count > 1;
            const firstVisibleIndex = isMobile ? activeIndex : (isSplit ? activeIndex - 1 : 0);
            const itemViewportWidth = isSplit ? 50 : 100;
            const trackWidth = count * itemViewportWidth;
            const translateAsTrackPercent = (firstVisibleIndex / count) * 100;

            elements.gallery.classList.toggle('is-split', isSplit);
            elements.gallery.classList.toggle('is-single-view', !isSplit);
            elements.track.style.width = trackWidth + '%';
            elements.track.style.transform = 'translate3d(-' + translateAsTrackPercent + '%, 0, 0)';
            elements.track.dataset.galleryTranslate = String(-translateAsTrackPercent);

            elements.items.forEach(function(item, itemIndex) {
                item.style.flexBasis = (100 / count) + '%';
                item.style.width = (100 / count) + '%';
                item.classList.toggle('is-active', itemIndex === activeIndex);
                item.classList.toggle(
                    'is-in-view',
                    isMobile
                        ? itemIndex === activeIndex
                        : (isSplit ? itemIndex === activeIndex - 1 || itemIndex === activeIndex : itemIndex === 0)
                );
            });
        }

        function syncControls(elements, safeIndex, thumbBehavior) {
            elements.thumbs.forEach(function(thumb, thumbIndex) {
                const isActive = thumbIndex === safeIndex;
                thumb.classList.toggle('active', isActive);
                thumb.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                if (isActive && typeof thumb.scrollIntoView === 'function') {
                    thumb.scrollIntoView({
                        behavior: thumbBehavior || 'smooth',
                        block: 'nearest',
                        inline: 'center'
                    });
                }
            });

            const hasMultipleItems = elements.items.length > 1;
            if (elements.previous) elements.previous.disabled = !hasMultipleItems;
            if (elements.next) elements.next.disabled = !hasMultipleItems;
        }

        function captureVisiblePanel(elements) {
            const panel = document.createElement('div');
            panel.className = 'product-gallery-loop-panel';
            panel.setAttribute('aria-hidden', 'true');

            if (!elements.gallery) return panel;

            const galleryRect = elements.gallery.getBoundingClientRect();
            const visibleItems = elements.items.filter(function(item) {
                return item.classList.contains('is-in-view');
            });
            const itemsToCapture = visibleItems.length
                ? visibleItems
                : elements.items.filter(function(item, itemIndex) {
                    return itemIndex === activeIndex;
                });

            panel.style.backgroundColor = window.getComputedStyle(elements.gallery).backgroundColor;

            itemsToCapture.forEach(function(item) {
                const itemRect = item.getBoundingClientRect();
                const clone = item.cloneNode(true);

                clone.removeAttribute('id');
                clone.querySelectorAll('[id]').forEach(function(node) {
                    node.removeAttribute('id');
                });
                clone.classList.remove('is-zoomed', 'is-dragging');
                clone.setAttribute('tabindex', '-1');
                clone.setAttribute('aria-hidden', 'true');
                clone.style.position = 'absolute';
                clone.style.left = (itemRect.left - galleryRect.left) + 'px';
                clone.style.top = (itemRect.top - galleryRect.top) + 'px';
                clone.style.width = itemRect.width + 'px';
                clone.style.height = itemRect.height + 'px';
                clone.style.margin = '0';
                clone.style.flex = 'none';
                clone.style.transform = 'none';

                clone.querySelectorAll('img').forEach(function(image) {
                    image.style.transform = 'translate3d(0, 0, 0) scale(1)';
                });

                panel.appendChild(clone);
            });

            return panel;
        }

        function animateBoundary(safeIndex, direction, elements) {
            if (isLoopAnimating || !elements.gallery || !elements.track) return;

            isLoopAnimating = true;

            if (window.LoomaamaProductZoom) {
                window.LoomaamaProductZoom.reset();
            }

            const gallery = elements.gallery;
            const previousMinHeight = gallery.style.minHeight;
            const currentHeight = gallery.getBoundingClientRect().height;
            const currentPanel = captureVisiblePanel(elements);
            const ghost = document.createElement('div');
            const strip = document.createElement('div');

            ghost.className = 'product-gallery-loop-ghost';
            ghost.setAttribute('aria-hidden', 'true');
            strip.className = 'product-gallery-loop-strip';
            ghost.appendChild(strip);
            gallery.appendChild(ghost);
            gallery.classList.add('is-loop-animating');

            gallery.classList.add('is-loop-resetting');
            activeIndex = safeIndex;
            updateTrack(elements);
            void elements.track.offsetWidth;
            syncControls(elements, safeIndex, 'auto');

            const targetHeight = gallery.getBoundingClientRect().height;
            const targetPanel = captureVisiblePanel(elements);
            const loopHeight = Math.max(currentHeight, targetHeight);
            gallery.classList.remove('is-loop-resetting');

            ghost.style.height = loopHeight + 'px';
            gallery.style.minHeight = loopHeight + 'px';

            if (direction > 0) {
                strip.appendChild(currentPanel);
                strip.appendChild(targetPanel);
                strip.style.transform = 'translate3d(0, 0, 0)';
            } else {
                strip.appendChild(targetPanel);
                strip.appendChild(currentPanel);
                strip.style.transform = 'translate3d(-50%, 0, 0)';
            }

            let finished = false;
            function finishBoundary() {
                if (finished) return;
                finished = true;
                ghost.remove();
                gallery.classList.remove('is-loop-animating');
                gallery.style.minHeight = previousMinHeight;
                isLoopAnimating = false;
                loopCleanup = null;
            }

            loopCleanup = finishBoundary;
            strip.addEventListener('transitionend', finishBoundary, { once: true });
            window.setTimeout(finishBoundary, 720);

            window.requestAnimationFrame(function() {
                window.requestAnimationFrame(function() {
                    strip.style.transform = direction > 0
                        ? 'translate3d(-50%, 0, 0)'
                        : 'translate3d(0, 0, 0)';
                });
            });
        }

        function activate(index, options) {
            const settings = options || {};
            let elements = getElements();

            if (isLoopAnimating) {
                if (settings.seamless) return;
                if (loopCleanup) loopCleanup();
                elements = getElements();
            }

            if (!elements.gallery || elements.items.length === 0 || isLoopAnimating) return;

            if (window.LoomaamaProductZoom) {
                window.LoomaamaProductZoom.reset();
            }

            const requestedIndex = Number(index);
            const safeIndex = Number.isFinite(requestedIndex)
                ? ((Math.round(requestedIndex) % elements.items.length) + elements.items.length) % elements.items.length
                : 0;
            const direction = Number(settings.direction) || 0;
            const isForwardBoundary =
                settings.seamless && direction > 0 &&
                activeIndex === elements.items.length - 1 && safeIndex === 0;
            const isBackwardBoundary =
                settings.seamless && direction < 0 &&
                activeIndex === 0 && safeIndex === elements.items.length - 1;

            if (isForwardBoundary || isBackwardBoundary) {
                animateBoundary(safeIndex, direction, elements);
                return;
            }

            activeIndex = safeIndex;
            updateTrack(elements);
            syncControls(elements, safeIndex, settings.thumbBehavior || 'smooth');
        }

        function refresh() {
            if (loopCleanup) loopCleanup();
            const elements = getElements();
            if (!elements.gallery || elements.items.length === 0) return;
            activeIndex = ((activeIndex % elements.items.length) + elements.items.length) % elements.items.length;
            activate(activeIndex, { thumbBehavior: 'auto' });
        }

        function movePrevious() {
            activate(activeIndex - 1, { direction: -1, seamless: true });
        }

        function moveNext() {
            activate(activeIndex + 1, { direction: 1, seamless: true });
        }

        document.addEventListener('click', function(event) {
            const thumb = event.target.closest('[data-gallery-thumb]');
            if (thumb) {
                event.preventDefault();
                activate(Number(thumb.getAttribute('data-gallery-thumb')));
                return;
            }

            if (event.target.closest('#prevBtn')) {
                event.preventDefault();
                movePrevious();
                return;
            }

            if (event.target.closest('#nextBtn')) {
                event.preventDefault();
                moveNext();
            }
        });

        const gallery = document.getElementById('productGallery');
        if (gallery) {
            gallery.addEventListener('pointerdown', function(event) {
                if (isLoopAnimating) return;
                if (!event.isPrimary) return;
                if (event.pointerType === 'mouse' && event.button !== 0) return;

                const elements = getElements();
                const mediaItem = event.target.closest('.product-media-item');
                if (
                    elements.items.length <= 1 ||
                    !mediaItem ||
                    mediaItem.classList.contains('is-zoomed')
                ) {
                    return;
                }

                swipePointerId = event.pointerId;
                swipeStartX = event.clientX;
                swipeStartY = event.clientY;
                swipeDeltaX = 0;
                swipeDeltaY = 0;
                isHorizontalSwipe = false;
                swipeBaseTranslate = Number(elements.track.dataset.galleryTranslate || 0);
            });

            gallery.addEventListener('pointermove', function(event) {
                if (event.pointerId !== swipePointerId) return;

                swipeDeltaX = event.clientX - swipeStartX;
                swipeDeltaY = event.clientY - swipeStartY;

                if (!isHorizontalSwipe) {
                    if (Math.abs(swipeDeltaX) < 8 && Math.abs(swipeDeltaY) < 8) return;
                    if (Math.abs(swipeDeltaY) >= Math.abs(swipeDeltaX)) {
                        swipePointerId = null;
                        return;
                    }
                    isHorizontalSwipe = true;
                    gallery.classList.add('is-touch-dragging');
                }

                event.preventDefault();
                const elements = getElements();
                if (elements.track) {
                    elements.track.style.transform =
                        'translate3d(calc(' + swipeBaseTranslate + '% + ' + swipeDeltaX + 'px), 0, 0)';
                }
            });

            function finishSwipe(event) {
                if (event.pointerId !== swipePointerId) return;

                const wasHorizontalSwipe = isHorizontalSwipe;
                const didSwipe = wasHorizontalSwipe && Math.abs(swipeDeltaX) >= swipeThreshold;
                const direction = swipeDeltaX < 0 ? 1 : -1;

                gallery.classList.remove('is-touch-dragging');
                swipePointerId = null;
                isHorizontalSwipe = false;

                if (wasHorizontalSwipe) {
                    suppressSwipeClickUntil = Date.now() + 450;
                }

                if (didSwipe) {
                    activate(activeIndex + direction, {
                        direction: direction,
                        seamless: true
                    });
                } else {
                    updateTrack(getElements());
                }
            }

            gallery.addEventListener('pointerup', finishSwipe);
            gallery.addEventListener('pointercancel', function(event) {
                if (event.pointerId !== swipePointerId) return;
                gallery.classList.remove('is-touch-dragging');
                swipePointerId = null;
                isHorizontalSwipe = false;
                updateTrack(getElements());
            });

            gallery.addEventListener('click', function(event) {
                if (Date.now() < suppressSwipeClickUntil) {
                    event.preventDefault();
                    event.stopImmediatePropagation();
                }
            }, true);
        }

        if (typeof mobileQuery.addEventListener === 'function') {
            mobileQuery.addEventListener('change', refresh);
        } else if (typeof mobileQuery.addListener === 'function') {
            mobileQuery.addListener(refresh);
        }

        refresh();
        return {
            activate: activate,
            refresh: refresh,
            next: moveNext,
            previous: movePrevious
        };
    })();

    // Click once to zoom, drag the media-item parent to pan, and click again to reset.
    window.LoomaamaProductZoom = (function() {
        const gallery = document.getElementById('productGallery');
        const zoomScale = 2.35;
        let activeItem = null;
        let activeImage = null;
        let panX = 0;
        let panY = 0;
        let startX = 0;
        let startY = 0;
        let startPanX = 0;
        let startPanY = 0;
        let pointerId = null;
        let dragged = false;
        let suppressClick = false;

        function clampPan() {
            if (!activeItem) return;
            const rect = activeItem.getBoundingClientRect();
            const maxX = rect.width * (zoomScale - 1) / 2;
            const maxY = rect.height * (zoomScale - 1) / 2;
            panX = Math.max(-maxX, Math.min(maxX, panX));
            panY = Math.max(-maxY, Math.min(maxY, panY));
        }

        function render() {
            if (!activeImage) return;
            clampPan();
            activeImage.style.transform = 'translate3d(' + panX + 'px, ' + panY + 'px, 0) scale(' + zoomScale + ')';
        }

        function reset() {
            if (activeImage) {
                activeImage.style.transform = '';
            }
            if (activeItem) {
                activeItem.classList.remove('is-zoomed', 'is-dragging');
                activeItem.setAttribute('aria-pressed', 'false');
            }
            activeItem = null;
            activeImage = null;
            panX = 0;
            panY = 0;
            pointerId = null;
            dragged = false;
        }

        function zoomAt(item, clientX, clientY) {
            const image = item.querySelector('figure img');
            if (!image) return;

            reset();
            activeItem = item;
            activeImage = image;
            const rect = item.getBoundingClientRect();
            panX = (rect.width / 2 - (clientX - rect.left)) * (zoomScale - 1);
            panY = (rect.height / 2 - (clientY - rect.top)) * (zoomScale - 1);
            item.classList.add('is-zoomed');
            item.setAttribute('aria-pressed', 'true');
            render();
        }

        if (gallery) {
            gallery.addEventListener('pointerdown', function(event) {
                const item = event.target.closest('.product-media-item');
                if (!item || item !== activeItem || item.dataset.type === 'video') return;

                event.preventDefault();
                pointerId = event.pointerId;
                startX = event.clientX;
                startY = event.clientY;
                startPanX = panX;
                startPanY = panY;
                dragged = false;
                item.classList.add('is-dragging');
                if (typeof item.setPointerCapture === 'function') {
                    item.setPointerCapture(pointerId);
                }
            });

            gallery.addEventListener('pointermove', function(event) {
                if (!activeItem || event.pointerId !== pointerId) return;
                const deltaX = event.clientX - startX;
                const deltaY = event.clientY - startY;
                if (Math.abs(deltaX) > 4 || Math.abs(deltaY) > 4) {
                    dragged = true;
                }
                panX = startPanX + deltaX;
                panY = startPanY + deltaY;
                render();
            });

            function finishDrag(event) {
                if (!activeItem || event.pointerId !== pointerId) return;
                if (typeof activeItem.releasePointerCapture === 'function' && activeItem.hasPointerCapture(pointerId)) {
                    activeItem.releasePointerCapture(pointerId);
                }
                activeItem.classList.remove('is-dragging');
                pointerId = null;
                suppressClick = dragged;
                if (suppressClick) {
                    window.setTimeout(function() {
                        suppressClick = false;
                    }, 350);
                }
            }

            gallery.addEventListener('pointerup', finishDrag);
            gallery.addEventListener('pointercancel', finishDrag);

            gallery.addEventListener('click', function(event) {
                const item = event.target.closest('.product-media-item');
                if (!item) return;
                event.preventDefault();

                if (item.dataset.type === 'video') {
                    const video = item.querySelector('video');
                    if (video) {
                        if (video.paused) video.play();
                        else video.pause();
                    }
                    return;
                }

                if (suppressClick) {
                    suppressClick = false;
                    return;
                }

                if (item === activeItem) {
                    reset();
                } else {
                    zoomAt(item, event.clientX, event.clientY);
                }
            });

            gallery.addEventListener('keydown', function(event) {
                const item = event.target.closest('.product-media-item');
                if (!item || item.dataset.type === 'video' || (event.key !== 'Enter' && event.key !== ' ')) return;
                event.preventDefault();
                if (item === activeItem) {
                    reset();
                } else {
                    const rect = item.getBoundingClientRect();
                    zoomAt(item, rect.left + rect.width / 2, rect.top + rect.height / 2);
                }
            });
        }

        window.addEventListener('resize', reset);
        return { reset: reset };
    })();

    // Image variation rail: it becomes a four-item Swiper only when there are more than four choices.
    const variationSliderElement = document.querySelector('.variation-image-slider');
    if (variationSliderElement && typeof Swiper !== 'undefined') {
        const variationSliderShell = variationSliderElement.closest('.variation-image-slider-shell');
        new Swiper(variationSliderElement, {
            slidesPerView: 4,
            spaceBetween: 10,
            speed: 450,
            watchOverflow: true,
            navigation: {
                prevEl: variationSliderShell ? variationSliderShell.querySelector('.variation-image-prev') : null,
                nextEl: variationSliderShell ? variationSliderShell.querySelector('.variation-image-next') : null
            }
        });
    }

    // Re-create the related carousel with navigation scoped to this exact section.
    const relatedSection = document.querySelector('.related-products-editorial');
    const relatedSliderElement = relatedSection ? relatedSection.querySelector('.related-slider') : null;
    if (relatedSliderElement && typeof Swiper !== 'undefined') {
        if (relatedSliderElement.swiper) {
            relatedSliderElement.swiper.destroy(true, true);
        }
        new Swiper(relatedSliderElement, {
            slidesPerView: 1.45,
            spaceBetween: 1,
            speed: 650,
            watchOverflow: true,
            navigation: {
                nextEl: relatedSection.querySelector('.btn-related-next'),
                prevEl: relatedSection.querySelector('.btn-related-prev')
            },
            pagination: {
                el: relatedSection.querySelector('.swiper-pagination'),
                clickable: true
            },
            breakpoints: {
                520: { slidesPerView: 2.2 },
                820: { slidesPerView: 3.4 },
                1200: { slidesPerView: 4.8 }
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

    // Store the original gallery and thumbnails so variation changes can be reversed.
    let defaultGalleryHtml = '';
    let defaultGalleryThumbsHtml = '';

    function captureDefaultGallery() {
        defaultGalleryHtml = $('#productGallery').html();
        defaultGalleryThumbsHtml = $('#productGalleryThumbs').html();
    }

    function refreshEditorialGallery() {
        if (window.LoomaamaProductGallery) {
            window.LoomaamaProductGallery.activate(0);
            window.LoomaamaProductGallery.refresh();
        }
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

    // Rebuild the editorial gallery for variation-specific images.
    function updateGallery(galleryData) {
        const $gallery = $('#productGallery');
        const $thumbs = $('#productGalleryThumbs');

        if (Array.isArray(galleryData) && galleryData.length > 0) {
            const validGalleryData = galleryData.filter(function(img) {
                return Boolean(img && (img.url || img.full_url));
            });

            if (validGalleryData.length === 0) return false;

            $gallery.empty();
            $thumbs.empty();

            const $track = $('<div>', {
                class: 'product-gallery-track',
                id: 'productGalleryTrack'
            });

            validGalleryData.forEach(function(img, index) {
                const imageUrl = img.url || img.full_url || '';
                const thumbUrl = img.thumb || img.thumbnail || imageUrl;

                const $link = $('<a>', {
                    href: imageUrl,
                    class: 'product-media-item' + (index === 0 ? ' is-active' : ''),
                    'data-media-index': index,
                    'aria-pressed': 'false'
                });
                const $figure = $('<figure>');
                const $image = $('<img>', {
                    src: imageUrl,
                    alt: img.alt || '',
                    loading: index === 0 ? 'eager' : 'lazy',
                    decoding: 'async'
                });
                $figure.append($image);
                $link.append($figure);
                $track.append($link);

                const $thumb = $('<button>', {
                    type: 'button',
                    class: 'product-gallery-thumb' + (index === 0 ? ' active' : ''),
                    'data-gallery-thumb': index,
                    'aria-label': '<?php echo esc_js(__('Show product image', 'loomaama')); ?> ' + (index + 1),
                    'aria-pressed': index === 0 ? 'true' : 'false'
                });
                $thumb.append($('<img>', { src: thumbUrl, alt: '', loading: 'lazy' }));
                $thumbs.append($thumb);
            });

            $gallery.append($track);
            $gallery.get(0).style.setProperty('--gallery-count', validGalleryData.length);
            refreshEditorialGallery();
            return true;
        }

        if (defaultGalleryHtml) {
            $gallery.html(defaultGalleryHtml);
            $thumbs.html(defaultGalleryThumbsHtml);
            $gallery.get(0).style.setProperty(
                '--gallery-count',
                Math.max(1, $gallery.find('.product-media-item').length)
            );
            refreshEditorialGallery();
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

        // Restore default gallery and its thumbnail controls.
        if (defaultGalleryHtml) {
            $('#productGallery').html(defaultGalleryHtml);
            $('#productGalleryThumbs').html(defaultGalleryThumbsHtml);
            refreshEditorialGallery();
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
    
    // Capture default gallery on page load
    captureDefaultGallery();
    
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
<style>
#variation-long-description-content span,#variation-short-description-content span {
    /* line-break: initial; */
    margin-bottom: 10px;
    display: block;   
    } 
</style>
<?php get_footer(); ?>