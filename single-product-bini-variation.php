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
        } else {
            // If the link only carries size (or width), keep that choice and use the first compatible variation.
            $pre_selected = null;
            foreach ($available_variations as $candidate_variation) {
                $size_matches = !$url_selected_size || $candidate_variation['size_slug'] === $url_selected_size;
                $width_matches = !$url_selected_width || $candidate_variation['width_slug'] === $url_selected_width;

                if ($size_matches && $width_matches) {
                    $pre_selected = $candidate_variation;
                    break;
                }
            }

            if (!$pre_selected) {
                $pre_selected = $first_available_variation;
            }
        }

        if ($pre_selected) {
            $pre_selected_variation_id = $pre_selected['variation_id'];
            $pre_selected_max_qty = $pre_selected['max_qty'];
            $pre_selected_price_html = $pre_selected['price_html'];
            $pre_selected_size_slug = $pre_selected['size_slug'];
            $pre_selected_width_slug = $pre_selected['width_slug'];
            $first_available_variation = $pre_selected;
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

// ==============================================
// HERO + THUMBNAIL GALLERY DATA
// ==============================================
$hero_image_url = $featured_image_id ? wp_get_attachment_image_url($featured_image_id, 'full') : '';
$hero_category_name = '';
$hero_categories = wp_get_post_terms($product_id, 'product_cat');
if (!empty($hero_categories) && !is_wp_error($hero_categories)) {
    $hero_category_name = $hero_categories[0]->name;
}
$hero_excerpt = wp_trim_words(wp_strip_all_tags($product_short_description), 24, '…');

// Build a normalized media collection once so the main image and left thumbnail rail stay in sync.
$product_media_items = array();
$featured_thumb_url = $featured_image_id ? wp_get_attachment_image_url($featured_image_id, 'thumbnail') : '';

if ($featured_video && is_array($featured_video) && !empty($featured_video['url'])) {
    $product_media_items[] = array(
        'type'  => 'video',
        'url'   => $featured_video['url'],
        'thumb' => $featured_thumb_url,
        'alt'   => sprintf(__('%s product video', 'loomaama'), $product_name),
    );
}

if ($featured_image_id) {
    $product_media_items[] = array(
        'type'  => 'image',
        'url'   => wp_get_attachment_image_url($featured_image_id, 'full'),
        'image' => wp_get_attachment_image_url($featured_image_id, 'medium_large'),
        'thumb' => wp_get_attachment_image_url($featured_image_id, 'thumbnail'),
        'alt'   => get_post_meta($featured_image_id, '_wp_attachment_image_alt', true),
        'id'    => $featured_image_id,
    );
}

foreach ($product_gallery_ids as $attachment_id) {
    $product_media_items[] = array(
        'type'  => 'image',
        'url'   => wp_get_attachment_image_url($attachment_id, 'full'),
        'image' => wp_get_attachment_image_url($attachment_id, 'medium_large'),
        'thumb' => wp_get_attachment_image_url($attachment_id, 'thumbnail'),
        'alt'   => get_post_meta($attachment_id, '_wp_attachment_image_alt', true),
        'id'    => $attachment_id,
    );
}

// ==============================================
// PRODUCTS AVAILABLE FOR THE SELECTED SIZE / WIDTH
// ==============================================
// The left rail is intentionally product-based (not gallery-image based).
// Its size/width filter is built from all in-stock variations in the design group,
// independently of which attributes exist on the product currently being viewed.
$available_design_products = array();
$design_product_ids = !empty($variant_ids) ? array_values(array_unique(array_map('intval', $variant_ids))) : array($product_id);
if (!in_array((int) $product_id, $design_product_ids, true)) {
    array_unshift($design_product_ids, (int) $product_id);
}

foreach ($design_product_ids as $design_product_id) {
    $design_product = isset($design_group_products[$design_product_id])
        ? $design_group_products[$design_product_id]
        : wc_get_product($design_product_id);

    if (!$design_product || !$design_product->is_visible()) {
        continue;
    }

    $available_sizes = array();
    $available_widths = array();
    $product_has_size_attr = false;
    $product_has_width_attr = false;

    if ($design_product->is_type('variable')) {
        $design_attrs = $design_product->get_attributes();
        $product_has_size_attr = isset($design_attrs['pa_size']);
        $product_has_width_attr = isset($design_attrs['pa_width']);

        $assigned_sizes = $product_has_size_attr
            ? wp_get_post_terms($design_product_id, 'pa_size', array('fields' => 'slugs'))
            : array();
        $assigned_widths = $product_has_width_attr
            ? wp_get_post_terms($design_product_id, 'pa_width', array('fields' => 'slugs'))
            : array();

        foreach ($design_product->get_available_variations() as $design_variation) {
            if (empty($design_variation['is_in_stock'])) {
                continue;
            }

            $variation_attrs = isset($design_variation['attributes']) ? $design_variation['attributes'] : array();
            $variation_size = isset($variation_attrs['attribute_pa_size']) ? $variation_attrs['attribute_pa_size'] : '';
            $variation_width = isset($variation_attrs['attribute_pa_width']) ? $variation_attrs['attribute_pa_width'] : '';

            if ($product_has_size_attr) {
                if ($variation_size !== '') {
                    $available_sizes[$variation_size] = true;
                } else {
                    // WooCommerce blank attribute means "Any size" for this variation.
                    foreach ((array) $assigned_sizes as $assigned_size) {
                        $available_sizes[$assigned_size] = true;
                    }
                }
            }

            if ($product_has_width_attr) {
                if ($variation_width !== '') {
                    $available_widths[$variation_width] = true;
                } else {
                    foreach ((array) $assigned_widths as $assigned_width) {
                        $available_widths[$assigned_width] = true;
                    }
                }
            }
        }
    } elseif ($design_product->is_in_stock()) {
        // Simple products are not size-filtered.
        $available_sizes['*'] = true;
        $available_widths['*'] = true;
    }

    // A variable product without one of these attributes should remain visible for that filter.
    if (!$product_has_size_attr) {
        $available_sizes['*'] = true;
    }
    if (!$product_has_width_attr) {
        $available_widths['*'] = true;
    }

    $design_image_id = $design_product->get_image_id();
    $design_thumb = $design_image_id ? wp_get_attachment_image_url($design_image_id, 'medium') : wc_placeholder_img_src('woocommerce_thumbnail');

    $available_design_products[] = array(
        'product_id' => $design_product_id,
        'name'       => $design_product->get_name(),
        'url'        => get_permalink($design_product_id),
        'thumb'      => $design_thumb,
        'sizes'      => array_keys($available_sizes),
        'widths'     => array_keys($available_widths),
        'is_current' => ((int) $design_product_id === (int) $product_id),
    );
}

// Build the Available products filter from every in-stock product in the design group.
// This is intentionally independent from the size/width attributes of the current product.
$available_product_size_slugs = array();
$available_product_width_slugs = array();

foreach ($available_design_products as $available_design_product) {
    foreach ((array) $available_design_product['sizes'] as $size_slug) {
        if ($size_slug !== '' && $size_slug !== '*') {
            $available_product_size_slugs[$size_slug] = true;
        }
    }

    foreach ((array) $available_design_product['widths'] as $width_slug) {
        if ($width_slug !== '' && $width_slug !== '*') {
            $available_product_width_slugs[$width_slug] = true;
        }
    }
}

$available_product_size_terms = array();
$available_product_width_terms = array();

if (!empty($available_product_size_slugs)) {
    $group_size_terms = get_terms(array(
        'taxonomy'   => 'pa_size',
        'slug'       => array_keys($available_product_size_slugs),
        'hide_empty' => false,
        'orderby'    => 'meta_value_num',
        'order'      => 'ASC',
    ));

    if (!is_wp_error($group_size_terms)) {
        foreach ($group_size_terms as $term) {
            $available_product_size_terms[] = array(
                'slug' => $term->slug,
                'name' => $term->name,
            );
        }
    }
}

if (!empty($available_product_width_slugs)) {
    $group_width_terms = get_terms(array(
        'taxonomy'   => 'pa_width',
        'slug'       => array_keys($available_product_width_slugs),
        'hide_empty' => false,
        'orderby'    => 'meta_value_num',
        'order'      => 'ASC',
    ));

    if (!is_wp_error($group_width_terms)) {
        foreach ($group_width_terms as $term) {
            $available_product_width_terms[] = array(
                'slug' => $term->slug,
                'name' => $term->name,
            );
        }
    }
}

// Preserve an Available products filter passed through the URL. Otherwise use the
// current product variation, then fall back to the first group-wide size or width.
$initial_gallery_filter_mode = '';
$initial_gallery_filter_value = '';
$available_product_size_slug_list = array_keys($available_product_size_slugs);
$available_product_width_slug_list = array_keys($available_product_width_slugs);
$url_selected_size = isset($url_selected_size) ? $url_selected_size : (isset($_GET['size']) ? sanitize_text_field(wp_unslash($_GET['size'])) : '');
$url_selected_width = isset($url_selected_width) ? $url_selected_width : (isset($_GET['width']) ? sanitize_text_field(wp_unslash($_GET['width'])) : '');

if ($url_selected_size && in_array($url_selected_size, $available_product_size_slug_list, true)) {
    $initial_gallery_filter_mode = 'size';
    $initial_gallery_filter_value = $url_selected_size;
} elseif ($url_selected_width && in_array($url_selected_width, $available_product_width_slug_list, true)) {
    $initial_gallery_filter_mode = 'width';
    $initial_gallery_filter_value = $url_selected_width;
} elseif ($pre_selected_size_slug && in_array($pre_selected_size_slug, $available_product_size_slug_list, true)) {
    $initial_gallery_filter_mode = 'size';
    $initial_gallery_filter_value = $pre_selected_size_slug;
} elseif ($pre_selected_width_slug && in_array($pre_selected_width_slug, $available_product_width_slug_list, true)) {
    $initial_gallery_filter_mode = 'width';
    $initial_gallery_filter_value = $pre_selected_width_slug;
} elseif (!empty($available_product_size_terms)) {
    $initial_gallery_filter_mode = 'size';
    $initial_gallery_filter_value = $available_product_size_terms[0]['slug'];
} elseif (!empty($available_product_width_terms)) {
    $initial_gallery_filter_mode = 'width';
    $initial_gallery_filter_value = $available_product_width_terms[0]['slug'];
}

// Get all global colors
$all_global_colors = function_exists('loomaama_get_color_attributes') ? loomaama_get_color_attributes() : array();

// Get related products
$show_related = get_theme_mod('show_related_products', true);
$related_products = array();

if ($show_related) {
    $related_cache_key = 'related_products_' . $product_id;
    $related_products = get_transient($related_cache_key);
    
    if ($related_products === false) {
        $related_count = get_theme_mod('related_products_count', 8);
        $related_products = wc_get_related_products($product_id, $related_count);
        set_transient($related_cache_key, $related_products, 6 * HOUR_IN_SECONDS);
    }
}

// ==============================================
// RECENTLY VIEWED PRODUCTS
// ==============================================
// The browser stores viewed product IDs in loomaama_recently_viewed.
// Also read WooCommerce's standard recently viewed cookie when available.
$recently_viewed_ids = array();
$recent_cookie_ids = array();

if (!empty($_COOKIE['loomaama_recently_viewed'])) {
    $recent_cookie_value = sanitize_text_field(wp_unslash($_COOKIE['loomaama_recently_viewed']));
    $recent_cookie_ids = array_merge($recent_cookie_ids, explode(',', $recent_cookie_value));
}

if (!empty($_COOKIE['woocommerce_recently_viewed'])) {
    $wc_recent_cookie_value = sanitize_text_field(wp_unslash($_COOKIE['woocommerce_recently_viewed']));
    $recent_cookie_ids = array_merge($recent_cookie_ids, explode('|', $wc_recent_cookie_value));
}

foreach ($recent_cookie_ids as $recent_id) {
    $recent_id = absint($recent_id);

    if (!$recent_id || $recent_id === (int) $product_id || in_array($recent_id, $recently_viewed_ids, true)) {
        continue;
    }

    $recent_product = wc_get_product($recent_id);
    if (!$recent_product || !$recent_product->is_visible()) {
        continue;
    }

    $recently_viewed_ids[] = $recent_id;

    // Keep this section to one clean row on desktop.
    if (count($recently_viewed_ids) >= 4) {
        break;
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

<main>
    <?php while (have_posts()) : the_post(); ?>

    <section class="loomaama-product-hero" <?php if (!empty($hero_image_url)) : ?>style="background-image: url('<?php echo esc_url($hero_image_url); ?>');"<?php endif; ?> aria-label="<?php echo esc_attr($product_name); ?>">
        <div class="loomaama-product-hero__inner">
            <div class="loomaama-product-hero__content" data-aos="fade-up">
                <?php if (!empty($hero_category_name)) : ?>
                    <p class="loomaama-product-hero__eyebrow"><?php echo esc_html($hero_category_name); ?></p>
                <?php endif; ?>
                <h1 class="loomaama-product-hero__title"><?php echo esc_html($product_name); ?></h1>
                <?php if (!empty($hero_excerpt)) : ?>
                    <p class="loomaama-product-hero__copy"><?php echo esc_html($hero_excerpt); ?></p>
                <?php endif; ?>
                <a class="loomaama-product-hero__link" href="#product-detail">
                    <?php _e('Discover the piece', 'loomaama'); ?> <span aria-hidden="true">↓</span>
                </a>
            </div>
        </div>
    </section>

    <section class="product-container product-detail-shell px-4 px-lg-5" id="product-detail">
     
            <div class="product-gallery-wrap position-relative d-flex flex-column" data-aos="fade-up">
                <div class="product-gallery-stage">
                    <div class="product-gallery-left">
                        <div class="product-gallery-selection" aria-live="polite">
                            <?php _e('Available products', 'loomaama'); ?>
                            <?php if (!empty($available_product_size_terms) || !empty($available_product_width_terms)) : ?>
                                <select id="galleryVariationFilter" class="gallery-variation-filter" aria-label="<?php esc_attr_e('Filter available products by size or width', 'loomaama'); ?>">
                                    <?php foreach ($available_product_size_terms as $size_term) :
                                        $gallery_size_selected = (
                                            $initial_gallery_filter_mode === 'size' &&
                                            $initial_gallery_filter_value === $size_term['slug']
                                        );
                                    ?>
                                        <option value="size|<?php echo esc_attr($size_term['slug']); ?>" <?php selected($gallery_size_selected); ?>>
                                            <?php echo esc_html(sprintf(__('Size %s', 'loomaama'), $size_term['name'])); ?>
                                        </option>
                                    <?php endforeach; ?>

                                    <?php foreach ($available_product_width_terms as $width_term) :
                                        $gallery_width_selected = (
                                            $initial_gallery_filter_mode === 'width' &&
                                            $initial_gallery_filter_value === $width_term['slug']
                                        );
                                    ?>
                                        <option value="width|<?php echo esc_attr($width_term['slug']); ?>" <?php selected($gallery_width_selected); ?>>
                                            <?php echo esc_html(sprintf(__('Width %s', 'loomaama'), $width_term['name'])); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else : ?>
                                <strong><?php esc_html_e('All available', 'loomaama'); ?></strong>
                            <?php endif; ?>
                        </div>

                        <div class="available-product-thumbs" id="availableProductThumbs" aria-label="<?php esc_attr_e('Products available in selected size or width', 'loomaama'); ?>">
                            <?php foreach ($available_design_products as $design_product_item) :
                                $show_initial_product = true;
                                $initial_link_args = array();

                                if ($initial_gallery_filter_mode === 'size' && $initial_gallery_filter_value) {
                                    $show_initial_product = in_array('*', $design_product_item['sizes'], true)
                                        || in_array($initial_gallery_filter_value, $design_product_item['sizes'], true);
                                    $initial_link_args['size'] = $initial_gallery_filter_value;
                                } elseif ($initial_gallery_filter_mode === 'width' && $initial_gallery_filter_value) {
                                    $show_initial_product = in_array('*', $design_product_item['widths'], true)
                                        || in_array($initial_gallery_filter_value, $design_product_item['widths'], true);
                                    $initial_link_args['width'] = $initial_gallery_filter_value;
                                }

                                $design_link = !empty($initial_link_args) ? add_query_arg($initial_link_args, $design_product_item['url']) : $design_product_item['url'];
                            ?>
                                <a href="<?php echo esc_url($design_link); ?>"
                                    class="available-product-thumb <?php echo $design_product_item['is_current'] ? 'is-current' : ''; ?>"
                                    data-product-id="<?php echo esc_attr($design_product_item['product_id']); ?>"
                                    data-base-url="<?php echo esc_url($design_product_item['url']); ?>"
                                    data-sizes="<?php echo esc_attr('|' . implode('|', $design_product_item['sizes']) . '|'); ?>"
                                    data-widths="<?php echo esc_attr('|' . implode('|', $design_product_item['widths']) . '|'); ?>"
                                    title="<?php echo esc_attr($design_product_item['name']); ?>"
                                    aria-label="<?php echo esc_attr($design_product_item['name']); ?>"
                                    <?php echo $design_product_item['is_current'] ? 'aria-current="page"' : ''; ?>
                                    style="<?php echo $show_initial_product ? '' : 'display:none;'; ?>">
                                    <img src="<?php echo esc_url($design_product_item['thumb']); ?>" alt="<?php echo esc_attr($design_product_item['name']); ?>" loading="lazy">
                                    <span class="available-product-thumb__name"><?php echo esc_html($design_product_item['name']); ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <p class="available-products-empty" id="availableProductsEmpty"><?php _e('No products are available for this selection.', 'loomaama'); ?></p>
                    </div>

                    <div class="product-gallery position-relative <?php echo esc_attr($gallery_layout); ?>" id="productGallery">
                        <?php foreach ($product_media_items as $media_index => $media) :
                            $is_active_media = ($media_index === 0);
                        ?>
                            <?php if ($media['type'] === 'video') : ?>
                                <a href="<?php echo esc_url($media['url']); ?>"
                                    class="glightbox gallery-media-item <?php echo $is_active_media ? 'is-active' : ''; ?>"
                                    data-gallery-index="<?php echo esc_attr($media_index); ?>"
                                    data-type="video"
                                    aria-hidden="<?php echo $is_active_media ? 'false' : 'true'; ?>">
                                    <video autoplay muted loop playsinline class="hover-video" preload="metadata">
                                        <source src="<?php echo esc_url($media['url']); ?>" type="video/mp4">
                                    </video>
                                </a>
                            <?php else : ?>
                                <a href="<?php echo esc_url($media['url']); ?>"
                                    class="glightbox gallery-media-item <?php echo $is_active_media ? 'is-active' : ''; ?>"
                                    data-gallery-index="<?php echo esc_attr($media_index); ?>"
                                    aria-hidden="<?php echo $is_active_media ? 'false' : 'true'; ?>">
                                    <figure>
                                        <img <?php echo (!empty($media['id']) && $media['id'] === $featured_image_id) ? 'id="main-product-image"' : ''; ?>
                                            src="<?php echo esc_url(!empty($media['image']) ? $media['image'] : $media['url']); ?>"
                                            alt="<?php echo esc_attr($media['alt']); ?>"
                                            loading="<?php echo $is_active_media ? 'eager' : 'lazy'; ?>">
                                    </figure>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if (count($product_media_items) > 1) : ?>
                <div class="gallery-nav">
                    <button id="prevBtn" class="nav-btn" type="button" aria-label="<?php esc_attr_e('Previous image', 'loomaama'); ?>"><i class="fa fa-arrow-left"></i></button>
                    <button id="nextBtn" class="nav-btn" type="button" aria-label="<?php esc_attr_e('Next image', 'loomaama'); ?>"><i class="fa fa-arrow-right"></i></button>
                </div>
                <?php endif; ?>
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

                <h2 class="product-title product-detail-title fs-3" data-aos="fade-up" data-aos-delay="300" data-aos-once="true"><?php echo esc_html($product_name); ?></h2>
                <div class="product-price" id="product-price" data-aos="fade-up" data-aos-delay="400" data-aos-once="true"><?php echo $product_price_html; ?></div>

                <!-- Color and pattern selectors removed: product alternatives are shown in the left rail. -->
                <!-- SIZE OPTIONS (ONLY if product has size attribute) -->
                <?php if ($has_size && !empty($all_size_terms)) : ?>
                <div class="size-section" data-aos="fade-up" data-aos-delay="700" data-aos-once="true">
                    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
                        <div class="size-pills">
                            <span class="label-title"><?php _e('Size:', 'loomaama'); ?></span>
                            <select id="size-select" class="form-select size-select">
                                <option value=""><?php _e('Select Size', 'loomaama'); ?></option>
                                <?php foreach ($all_size_terms as $size_term) :
                                    $size_name = $size_term['name'];
                                    $size_slug = $size_term['slug'];
                                    $size_available = false;

                                    foreach ($available_variations as $available_variation) {
                                        if ($available_variation['size_slug'] !== $size_slug) {
                                            continue;
                                        }

                                        // If width exists, availability should match the currently selected width.
                                        if ($has_width && $pre_selected_width_slug && $available_variation['width_slug'] !== $pre_selected_width_slug) {
                                            continue;
                                        }

                                        $size_available = true;
                                        break;
                                    }

                                    $selected = ($pre_selected_size_slug === $size_slug) ? 'selected' : '';
                                ?>
                                <option value="<?php echo esc_attr($size_slug); ?>" <?php echo $selected; ?>
                                    <?php echo !$size_available ? 'disabled' : ''; ?>>
                                    <?php echo esc_html($size_name . (!$size_available ? ' (Not available)' : '')); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
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

            </div>
       
    </section>

    <!-- Product detail tabs: intentionally placed below section#product-detail -->
    <section class="product-detail-tabs-section px-4 px-lg-5" aria-label="<?php esc_attr_e('Product information', 'loomaama'); ?>">
        
            <div class="product-detail-tabs" id="productDetailTabs">
                <div class="product-detail-tabs__nav" role="tablist" aria-label="<?php esc_attr_e('Product details', 'loomaama'); ?>">
                    <button type="button" class="product-detail-tab-btn" role="tab"
                        data-tab-target="variation-long-description-item"
                        aria-controls="variation-long-description-item" aria-selected="false"
                        style="<?php echo empty($product_long_description) ? 'display:none;' : ''; ?>">
                        <?php _e('Long Description', 'loomaama'); ?>
                    </button>

                    <button type="button" class="product-detail-tab-btn" role="tab"
                        data-tab-target="variation-details-item"
                        aria-controls="variation-details-item" aria-selected="false"
                        style="<?php echo empty($product_details) ? 'display:none;' : ''; ?>">
                        <?php _e('Details', 'loomaama'); ?>
                    </button>

                    <?php if ($product->has_attributes() || $product->has_dimensions() || $product->has_weight()) : ?>
                    <button type="button" class="product-detail-tab-btn" role="tab"
                        data-tab-target="product-provenance-tab"
                        aria-controls="product-provenance-tab" aria-selected="false">
                        <?php _e('Provenance', 'loomaama'); ?>
                    </button>
                    <?php endif; ?>

                    <button type="button" class="product-detail-tab-btn" role="tab"
                        data-tab-target="variation-artisan-item"
                        aria-controls="variation-artisan-item" aria-selected="false"
                        style="<?php echo empty($artisan_story) ? 'display:none;' : ''; ?>">
                        <?php _e('Artisan Story', 'loomaama'); ?>
                    </button>

                    <button type="button" class="product-detail-tab-btn" role="tab"
                        data-tab-target="variation-care-item"
                        aria-controls="variation-care-item" aria-selected="false"
                        style="<?php echo empty($care_description) ? 'display:none;' : ''; ?>">
                        <?php _e('Care Instructions', 'loomaama'); ?>
                    </button>

                    <button type="button" class="product-detail-tab-btn" role="tab"
                        data-tab-target="variation-sensory-item"
                        aria-controls="variation-sensory-item" aria-selected="false"
                        style="<?php echo empty($sensory_description) ? 'display:none;' : ''; ?>">
                        <?php _e('Sensory Description', 'loomaama'); ?>
                    </button>

                    <button type="button" class="product-detail-tab-btn" role="tab"
                        data-tab-target="delivery-returns-tab"
                        aria-controls="delivery-returns-tab" aria-selected="false">
                        <?php _e('Delivery & Returns', 'loomaama'); ?>
                    </button>

                    <button type="button" class="product-detail-tab-btn" role="tab"
                        data-tab-target="product-reviews-tab"
                        aria-controls="product-reviews-tab" aria-selected="false">
                        <?php _e('Reviews', 'loomaama'); ?> (<?php echo esc_html($product_review_count); ?>)
                    </button>
                </div>

                <div class="product-detail-tabs__body">
                    <div class="product-detail-tab-panel" role="tabpanel" id="variation-long-description-item"
                        data-tab-available="<?php echo empty($product_long_description) ? '0' : '1'; ?>">
                        <div id="variation-long-description-content">
                            <?php echo wp_kses_post($product_long_description); ?>
                        </div>
                    </div>

                    <div class="product-detail-tab-panel" role="tabpanel" id="variation-details-item"
                        data-tab-available="<?php echo empty($product_details) ? '0' : '1'; ?>">
                        <div id="variation-details-content">
                            <?php echo wp_kses_post($product_details); ?>
                        </div>
                    </div>

                    <?php if ($product->has_attributes() || $product->has_dimensions() || $product->has_weight()) : ?>
                    <div class="product-detail-tab-panel" role="tabpanel" id="product-provenance-tab" data-tab-available="1">
                        <?php do_action('woocommerce_product_additional_information', $product); ?>
                    </div>
                    <?php endif; ?>

                    <div class="product-detail-tab-panel" role="tabpanel" id="variation-artisan-item"
                        data-tab-available="<?php echo empty($artisan_story) ? '0' : '1'; ?>">
                        <div id="variation-artisan-content">
                            <?php echo wp_kses_post($artisan_story); ?>
                        </div>
                    </div>

                    <div class="product-detail-tab-panel" role="tabpanel" id="variation-care-item"
                        data-tab-available="<?php echo empty($care_description) ? '0' : '1'; ?>">
                        <div id="variation-care-content">
                            <?php echo wp_kses_post($care_description); ?>
                        </div>
                    </div>

                    <div class="product-detail-tab-panel" role="tabpanel" id="variation-sensory-item"
                        data-tab-available="<?php echo empty($sensory_description) ? '0' : '1'; ?>">
                        <div id="variation-sensory-content">
                            <?php echo wp_kses_post($sensory_description); ?>
                        </div>
                    </div>

                    <div class="product-detail-tab-panel" role="tabpanel" id="delivery-returns-tab" data-tab-available="1">
                        <p><?php _e('Enjoy free carbon-neutral shipping on this item. Returns are accepted within 14 days of receipt for a full refund.', 'loomaama'); ?></p>
                    </div>

                    <div class="product-detail-tab-panel" role="tabpanel" id="product-reviews-tab" data-tab-available="1">
                        <div class="review-buttons d-flex gap-3 flex-wrap">
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
            </div>
        
    </section>
        <!-- Related Products -->
    <?php if ($show_related && !empty($related_products)) : ?>
    <section class="reveal" data-aos="fade-up">
        <div class="container-fluid px-4  px-lg-5">
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
    <!-- Recently Viewed Products -->
    <?php if (!empty($recently_viewed_ids)) : ?>
    <section class="recently-viewed-section reveal" data-aos="fade-up">
        <div class="container-fluid px-4 px-lg-5">
            <div class="row mb-4">
                <div class="col">
                    <h3 class="headline" data-aos="fade-up"><?php _e('Recently Viewed', 'loomaama'); ?></h3>
                </div>
            </div>

            <div class="recently-viewed-grid">
                <?php foreach ($recently_viewed_ids as $recent_id) :
                    $recent_product = wc_get_product($recent_id);
                    if (!$recent_product) continue;

                    $recent_name = $recent_product->get_name();
                    $recent_price = $recent_product->get_price_html();
                    $recent_categories = wp_get_post_terms($recent_id, 'product_cat');
                    $recent_category_name = !empty($recent_categories) ? $recent_categories[0]->name : __('NETTLE', 'loomaama');
                    $recent_regular_price = $recent_product->get_regular_price();
                    $recent_sale_price = $recent_product->get_sale_price();
                    $recent_discount = ($recent_regular_price && $recent_sale_price && $recent_regular_price > 0)
                        ? round((($recent_regular_price - $recent_sale_price) / $recent_regular_price) * 100)
                        : '';

                    $recent_main_img_id = get_post_thumbnail_id($recent_id);
                    $recent_main_img_url = wp_get_attachment_image_url($recent_main_img_id, 'medium_large');
                    if (!$recent_main_img_url) $recent_main_img_url = wc_placeholder_img_src('medium_large');

                    $recent_hover_img_url = '';
                    if (function_exists('get_field')) {
                        $recent_hover_field = get_field('hover_image', $recent_id);
                        if ($recent_hover_field) {
                            if (is_array($recent_hover_field)) {
                                $recent_hover_img_url = $recent_hover_field['sizes']['medium_large'] ?? $recent_hover_field['url'] ?? '';
                            } elseif (is_numeric($recent_hover_field)) {
                                $recent_hover_img_url = wp_get_attachment_image_url($recent_hover_field, 'medium_large');
                            } else {
                                $recent_hover_img_url = $recent_hover_field;
                            }
                        }
                    }
                ?>
                <div class="lux-card" data-aos="fade-up">
                    <div class="lux-img">
                        <a href="<?php echo esc_url(get_permalink($recent_id)); ?>">
                            <img src="<?php echo esc_url($recent_main_img_url); ?>" class="img-fluid main-image"
                                alt="<?php echo esc_attr($recent_name); ?>" loading="lazy">
                            <?php if ($recent_hover_img_url) : ?>
                                <img class="hover-overlay-img" src="<?php echo esc_url($recent_hover_img_url); ?>"
                                    alt="<?php echo esc_attr($recent_name); ?>" loading="lazy">
                            <?php endif; ?>
                        </a>
                        <?php if ($recent_product->is_on_sale() && $recent_discount) : ?>
                            <span class="badge-sale position-absolute top-0 start-0 m-3">-<?php echo esc_html($recent_discount); ?>%</span>
                        <?php endif; ?>
                        <?php if ($recent_product->is_featured()) : ?>
                            <span class="badge-best position-absolute top-0 end-0 m-3"><?php _e('Best-Seller', 'loomaama'); ?></span>
                        <?php endif; ?>
                        <?php if (!$recent_product->is_in_stock()) : ?>
                            <span class="badge-soldout position-absolute top-0 start-0 m-3"><?php _e('Sold out', 'loomaama'); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="lux-cat headline"><?php echo esc_html(strtoupper($recent_category_name)); ?></p>
                    </div>
                    <div class="d-flex justify-content-between loom-title-the-price">
                        <a href="<?php echo esc_url(get_permalink($recent_id)); ?>" class="text-decoration-none">
                            <h6 class="mb-0 text-truncate"><?php echo esc_html($recent_name); ?></h6>
                        </a>
                        <span class="text-secondary <?php echo ($recent_product->is_on_sale() && $recent_discount) ? 'sale-on' : 'sale-off'; ?>"
                            style="font-size: 13px;"><?php echo $recent_price; ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
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
    var GalleryCount = $('.product-gallery a').length;
    $('.gallery-nav').css('display', GalleryCount > 1 ? 'flex' : 'none');

    let productId = <?php echo $product_id; ?>;
    let selectedVariationId = '<?php echo esc_js($pre_selected_variation_id); ?>';
    let selectedSizeSlug = '<?php echo esc_js($pre_selected_size_slug); ?>';
    let selectedWidthSlug = '<?php echo esc_js($pre_selected_width_slug); ?>';
    let currentMaxQty = <?php echo $pre_selected_max_qty ?: 99; ?>;
    let hasSize = $('#has_size').val() === '1';
    let hasWidth = $('#has_width').val() === '1';
    let availableVariations = <?php echo wp_json_encode($available_variations); ?>;

    // Save this product as recently viewed for the visitor's next product page.
    (function trackRecentlyViewedProduct() {
        const cookieName = 'loomaama_recently_viewed';
        const cookieMatch = document.cookie.match(new RegExp('(?:^|; )' + cookieName + '=([^;]*)'));
        let viewedIds = [];

        if (cookieMatch && cookieMatch[1]) {
            try {
                viewedIds = decodeURIComponent(cookieMatch[1]).split(',').filter(function(id) {
                    return /^\d+$/.test(id);
                });
            } catch (e) {
                viewedIds = [];
            }
        }

        const currentId = String(productId);
        viewedIds = viewedIds.filter(function(id) { return id !== currentId; });
        viewedIds.unshift(currentId);
        viewedIds = viewedIds.slice(0, 12);

        document.cookie = cookieName + '=' + encodeURIComponent(viewedIds.join(',')) + '; path=/; max-age=2592000; SameSite=Lax';
    })();

    function getGalleryVariationFilter() {
        var filterValue = $('#galleryVariationFilter').val() || '';
        var parts = filterValue.split('|');

        if (parts.length === 2 && (parts[0] === 'size' || parts[0] === 'width')) {
            return { mode: parts[0], value: parts[1] };
        }

        if (hasSize) {
            return { mode: 'size', value: selectedSizeSlug };
        }

        if (hasWidth) {
            return { mode: 'width', value: selectedWidthSlug };
        }

        return { mode: '', value: '' };
    }

    function syncGalleryVariationFilter(mode, value) {
        var $filter = $('#galleryVariationFilter');
        if (!$filter.length || !value) return;

        var current = getGalleryVariationFilter();
        if (current.mode !== mode) return;

        var optionValue = mode + '|' + value;
        if ($filter.find('option[value="' + optionValue + '"]').length) {
            $filter.val(optionValue);
        }
    }

    function updateAvailableProductThumbs() {
        var galleryFilter = getGalleryVariationFilter();
        var filterMode = galleryFilter.mode;
        var selectedFilter = galleryFilter.value;
        var visibleCount = 0;

        $('#availableProductThumbs .available-product-thumb').each(function() {
            var $thumb = $(this);
            var availableValues = filterMode === 'width'
                ? String($thumb.attr('data-widths') || '')
                : String($thumb.attr('data-sizes') || '');
            var isAvailable = !selectedFilter || availableValues.indexOf('|*|') !== -1 || availableValues.indexOf('|' + selectedFilter + '|') !== -1;

            $thumb.toggle(isAvailable);
            if (isAvailable) visibleCount++;

            // Keep the selected gallery filter when opening another product in the design group.
            var baseUrl = $thumb.attr('data-base-url');
            if (baseUrl) {
                try {
                    var targetUrl = new URL(baseUrl, window.location.origin);
                    targetUrl.searchParams.delete('size');
                    targetUrl.searchParams.delete('width');
                    targetUrl.searchParams.delete('color');
                    targetUrl.searchParams.delete('pattern');
                    if (filterMode === 'size' && selectedFilter) {
                        targetUrl.searchParams.set('size', selectedFilter);
                    } else if (filterMode === 'width' && selectedFilter) {
                        targetUrl.searchParams.set('width', selectedFilter);
                    }
                    $thumb.attr('href', targetUrl.toString());
                } catch (e) {
                    // Keep the server-rendered href if URL parsing is unavailable.
                }
            }
        });

        $('#availableProductsEmpty').toggle(visibleCount === 0);
    }

    window.loomaamaUpdateAvailableProductThumbs = updateAvailableProductThumbs;

    $('#galleryVariationFilter').on('change', function() {
        updateAvailableProductThumbs();
    });

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

    function findFirstVariationForSize(sizeSlug) {
        for (let i = 0; i < availableVariations.length; i++) {
            if (!hasSize || availableVariations[i].size_slug === sizeSlug) {
                return availableVariations[i];
            }
        }
        return null;
    }

    function findFirstVariationForWidth(widthSlug) {
        for (let i = 0; i < availableVariations.length; i++) {
            if (!hasWidth || availableVariations[i].width_slug === widthSlug) {
                return availableVariations[i];
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

        if (hasSize && $('#size-select').length && selectedSizeSlug) {
            $('#size-select').val(selectedSizeSlug);
        }
        if (hasWidth && $('#width-select').length && selectedWidthSlug) {
            $('#width-select').val(selectedWidthSlug);
        }

        if (variation.price_html) {
            $('#product-price').html(variation.price_html);
        }

        $('#quantity').attr('max', currentMaxQty);
        if (parseInt($('#quantity').val(), 10) > currentMaxQty) {
            $('#quantity').val(currentMaxQty);
        }

        $('.size-error-message').hide().text('');
        if (typeof updateGalleryVariationSummary === 'function') updateGalleryVariationSummary();
    }

    function updateSizeOptionsAvailability(widthSlug) {
        if (!hasSize || !$('#size-select').length) {
            return;
        }

        $('#size-select option').each(function() {
            let $option = $(this);
            let sizeSlug = $option.val();

            if (!sizeSlug) {
                return;
            }

            let variation = (hasWidth && widthSlug)
                ? findVariation(sizeSlug, widthSlug)
                : findFirstVariationForSize(sizeSlug);
            let isAvailable = !!variation;

            $option.prop('disabled', !isAvailable);

            let originalText = $option.data('original-text') || $option.text().replace(' (Not available)', '');
            $option.data('original-text', originalText);
            $option.text(isAvailable ? originalText : originalText + ' (Not available)');
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

            let variation = (hasSize && selectedSizeSlug)
                ? findVariation(selectedSizeSlug, widthSlug)
                : findFirstVariationForWidth(widthSlug);
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
    $('#size-select').on('change', function() {
        let sizeSlug = $(this).val();
        selectedSizeSlug = sizeSlug;
        $('#selected_size_slug').val(selectedSizeSlug);
        syncGalleryVariationFilter('size', selectedSizeSlug);

        if (!selectedSizeSlug) {
            selectedVariationId = '';
            $('#selected_variation_id').val('');
            if (hasWidth) {
                refreshWidthOptions();
            }
            updateAvailableProductThumbs();
            return;
        }

        let variation = null;

        if (hasWidth && selectedWidthSlug) {
            variation = findVariation(selectedSizeSlug, selectedWidthSlug);
        }

        if (!variation) {
            variation = findFirstVariationForSize(selectedSizeSlug);
        }

        if (variation) {
            applyVariation(variation);

            if (hasWidth && variation.width_slug) {
                selectedWidthSlug = variation.width_slug;
                $('#selected_width_slug').val(selectedWidthSlug);
                $('#width-select').val(selectedWidthSlug);
            }

            updateSizeOptionsAvailability(selectedWidthSlug);
            refreshWidthOptions();
            syncGalleryVariationFilter('size', selectedSizeSlug);
            syncGalleryVariationFilter('width', selectedWidthSlug);
            $('.size-error-message').hide().text('');
        } else {
            selectedVariationId = '';
            $('#selected_variation_id').val('');
            $('.size-error-message')
                .text('<?php _e('No available variation for selected size', 'loomaama'); ?>')
                .show();
        }

        updateAvailableProductThumbs();
        if (typeof updateGalleryVariationSummary === 'function') updateGalleryVariationSummary();
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
        syncGalleryVariationFilter('width', selectedWidthSlug);

        if (!selectedWidthSlug) {
            selectedVariationId = '';
            $('#selected_variation_id').val('');
            updateSizeOptionsAvailability('');
            updateAvailableProductThumbs();
            return;
        }

        updateSizeOptionsAvailability(selectedWidthSlug);

        let variation = selectedSizeSlug ? findVariation(selectedSizeSlug, selectedWidthSlug) : null;

        if (!variation) {
            variation = findFirstVariationForWidth(selectedWidthSlug);
        }

        if (variation) {
            applyVariation(variation);

            if (hasSize && variation.size_slug) {
                selectedSizeSlug = variation.size_slug;
                $('#selected_size_slug').val(selectedSizeSlug);
                $('#size-select').val(selectedSizeSlug);
            }

            updateSizeOptionsAvailability(selectedWidthSlug);
            refreshWidthOptions();
            syncGalleryVariationFilter('size', selectedSizeSlug);
            syncGalleryVariationFilter('width', selectedWidthSlug);
            $('.size-error-message').hide().text('');
        } else {
            selectedVariationId = '';
            $('#selected_variation_id').val('');
            $('.size-error-message')
                .text('<?php _e('No available variations for selected width', 'loomaama'); ?>')
                .show();
        }

        updateAvailableProductThumbs();
        if (typeof updateGalleryVariationSummary === 'function') updateGalleryVariationSummary();
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
    updateSizeOptionsAvailability('<?php echo esc_js($pre_selected_width_slug); ?>');
    <?php endif; ?>

    refreshWidthOptions();
    updateAvailableProductThumbs();

    // ==============================================
    // Product Detail Tabs
    // ==============================================
    function activateProductDetailTab(targetId, focusTab) {
        const $tabs = $('#productDetailTabs');
        if (!$tabs.length) return;

        const $button = $tabs.find('.product-detail-tab-btn[data-tab-target="' + targetId + '"]:visible').first();
        const $panel = $('#' + targetId);
        const panelAvailable = $panel.attr('data-tab-available') !== '0';
        if (!$button.length || !$panel.length || !panelAvailable) return;

        $tabs.find('.product-detail-tab-btn')
            .removeClass('is-active')
            .attr('aria-selected', 'false')
            .attr('tabindex', '-1');
        $tabs.find('.product-detail-tab-panel')
            .removeClass('is-active')
            .attr('hidden', true);

        $button
            .addClass('is-active')
            .attr('aria-selected', 'true')
            .attr('tabindex', '0');
        $panel
            .addClass('is-active')
            .removeAttr('hidden');

        if (focusTab) $button.trigger('focus');
    }

    window.loomaamaSyncDetailTabs = function() {
        const $tabs = $('#productDetailTabs');
        if (!$tabs.length) return;

        $tabs.find('.product-detail-tab-btn').each(function() {
            const targetId = $(this).data('tab-target');
            const $panel = $('#' + targetId);
            const panelAvailable = $panel.length && $panel.attr('data-tab-available') !== '0';
            $(this).toggle(panelAvailable);
        });

        let $active = $tabs.find('.product-detail-tab-btn.is-active:visible').first();
        if (!$active.length) {
            $active = $tabs.find('.product-detail-tab-btn:visible').first();
        }

        if ($active.length) {
            activateProductDetailTab($active.data('tab-target'), false);
        }
    };

    $('#productDetailTabs').on('click', '.product-detail-tab-btn', function() {
        activateProductDetailTab($(this).data('tab-target'), false);
    });

    $('#productDetailTabs').on('keydown', '.product-detail-tab-btn', function(e) {
        if (!['ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(e.key)) return;
        e.preventDefault();

        const $visibleTabs = $('#productDetailTabs .product-detail-tab-btn:visible');
        let index = $visibleTabs.index(this);
        if (e.key === 'ArrowRight') index = (index + 1) % $visibleTabs.length;
        if (e.key === 'ArrowLeft') index = (index - 1 + $visibleTabs.length) % $visibleTabs.length;
        if (e.key === 'Home') index = 0;
        if (e.key === 'End') index = $visibleTabs.length - 1;

        const $next = $visibleTabs.eq(index);
        activateProductDetailTab($next.data('tab-target'), true);
    });

    window.loomaamaSyncDetailTabs();

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
    // Swipe / Scroll-Snap Gallery Navigation
    // ==============================================
    let currentGalleryIndex = 0;
    let galleryScrollTimer = null;

    function getGalleryItems() {
        return $('#productGallery .gallery-media-item');
    }

    function syncGalleryState(index) {
        let $items = getGalleryItems();
        let count = $items.length;
        if (!count) return;

        index = Math.max(0, Math.min(parseInt(index, 10) || 0, count - 1));
        currentGalleryIndex = index;
        $items.removeClass('is-active').attr('aria-hidden', 'true');
        $items.eq(index).addClass('is-active').attr('aria-hidden', 'false');

        $('.gallery-nav').css('display', count > 1 ? 'flex' : 'none');
        $('#prevBtn').prop('disabled', index <= 0);
        $('#nextBtn').prop('disabled', index >= count - 1);
    }

    function setGalleryIndex(index, behavior) {
        let $gallery = $('#productGallery');
        let $items = getGalleryItems();
        let count = $items.length;
        if (!count) return;

        index = Math.max(0, Math.min(parseInt(index, 10) || 0, count - 1));
        syncGalleryState(index);

        let galleryNode = $gallery.get(0);
        if (galleryNode) {
            galleryNode.scrollTo({
                left: index * galleryNode.clientWidth,
                behavior: behavior || 'smooth'
            });
        }
    }

    window.loomaamaRefreshProductGallery = function(index) {
        let galleryNode = document.getElementById('productGallery');
        if (galleryNode) galleryNode.scrollLeft = 0;
        syncGalleryState(typeof index === 'undefined' ? 0 : index);
        updateAvailableProductThumbs();
    };

    $('#nextBtn').on('click', function() { setGalleryIndex(currentGalleryIndex + 1, 'smooth'); });
    $('#prevBtn').on('click', function() { setGalleryIndex(currentGalleryIndex - 1, 'smooth'); });

    $('#productGallery').on('scroll', function() {
        clearTimeout(galleryScrollTimer);
        var galleryNode = this;
        galleryScrollTimer = setTimeout(function() {
            if (!galleryNode.clientWidth) return;
            var index = Math.round(galleryNode.scrollLeft / galleryNode.clientWidth);
            syncGalleryState(index);
        }, 60);
    });

    $(window).on('resize', function() {
        let galleryNode = document.getElementById('productGallery');
        if (galleryNode && galleryNode.clientWidth) {
            galleryNode.scrollLeft = currentGalleryIndex * galleryNode.clientWidth;
        }
    });

    syncGalleryState(0);
    updateAvailableProductThumbs();

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
    // GLightbox Initialization
    // ==============================================
    if (typeof GLightbox !== 'undefined') {
        const lightbox = GLightbox({
            selector: '.glightbox',
            touchNavigation: true,
            zoomable: true,
            preload: true,
            loop: true,
            autoplayVideos: true
        });
    }

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
        // Keep the existing function name because variation AJAX calls already use it,
        // but the target is now a tab panel rather than an accordion item.
        let hasValue = value && value.trim() !== '' && value !== '<p></p>' && value !== '<p><br></p>';
        let $panel = $(itemSelector);
        let panelId = $panel.attr('id');
        let isDetailTab = $panel.hasClass('product-detail-tab-panel');
        let $tabButton = panelId ? $('.product-detail-tab-btn[data-tab-target="' + panelId + '"]') : $();

        // The same AJAX helper also updates the short description, which remains
        // in the purchase panel and is not part of the tab interface.
        if (!isDetailTab) {
            if (hasValue) {
                $(contentSelector).html(value);
                $panel.show();
            } else if (defaultValue && defaultValue.trim() !== '') {
                $(contentSelector).html(defaultValue);
                $panel.show();
            } else {
                $(contentSelector).html('');
                $panel.hide();
            }
            return;
        }
        
        if (hasValue) {
            $(contentSelector).html(value);
            $panel.attr('data-tab-available', '1');
            $tabButton.show();
        } else if (defaultValue && defaultValue.trim() !== '') {
            $(contentSelector).html(defaultValue);
            $panel.attr('data-tab-available', '1');
            $tabButton.show();
        } else {
            $(contentSelector).html('');
            $panel.attr('data-tab-available', '0').removeClass('is-active').attr('hidden', true);
            $tabButton.hide().removeClass('is-active').attr('aria-selected', 'false');
        }
    }
    
    // Update only the main swipe gallery. The left rail is reserved for other products
    // that are available in the currently selected size.
    function updateGallery(galleryData) {
        var $gallery = $('#productGallery');

        function escapeHtml(value) {
            return $('<div>').text(value || '').html();
        }

        if (galleryData && galleryData.length > 0) {
            var galleryHtml = '';

            galleryData.forEach(function(img, index) {
                var fullUrl = img.full || img.url || img.src || '';
                var displayUrl = img.image || img.large || img.url || img.src || fullUrl;
                var alt = escapeHtml(img.alt || '');
                var activeClass = index === 0 ? ' is-active' : '';
                var ariaHidden = index === 0 ? 'false' : 'true';

                if (!fullUrl) return;

                galleryHtml += `
                    <a href="${fullUrl}" class="glightbox gallery-media-item${activeClass}" data-gallery-index="${index}" aria-hidden="${ariaHidden}">
                        <figure>
                            <img src="${displayUrl}" alt="${alt}" loading="${index === 0 ? 'eager' : 'lazy'}">
                        </figure>
                    </a>
                `;
            });

            if (galleryHtml) {
                $gallery.html(galleryHtml);
                if (window.loomaamaRefreshProductGallery) window.loomaamaRefreshProductGallery(0);
                console.log('Using custom variation swipe gallery');
                return true;
            }
        }

        if (defaultGalleryHtml) {
            $gallery.html(defaultGalleryHtml);
            if (window.loomaamaRefreshProductGallery) window.loomaamaRefreshProductGallery(0);
            console.log('Restoring default swipe gallery');
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

                if (window.loomaamaSyncDetailTabs) {
                    window.loomaamaSyncDetailTabs();
                }
                
                // Reinitialize GLightbox after gallery update
                setTimeout(function() {
                    if (typeof GLightbox !== 'undefined') {
                        GLightbox({ 
                            selector: '.glightbox', 
                            touchNavigation: true,
                            zoomable: true,
                            preload: true,
                            loop: true,
                            autoplayVideos: true
                        });
                    }
                }, 100);
                
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
            $('#productGallery').html(defaultGalleryHtml);
            if (window.loomaamaRefreshProductGallery) window.loomaamaRefreshProductGallery(0);
            
            // Reinitialize GLightbox
            setTimeout(function() {
                if (typeof GLightbox !== 'undefined') {
                    GLightbox({ 
                        selector: '.glightbox', 
                        touchNavigation: true,
                        zoomable: true,
                        preload: true,
                        loop: true,
                        autoplayVideos: true
                    });
                }
            }, 100);
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
            $('#variation-long-description-item').attr('data-tab-available', '1');
        } else {
            $('#variation-long-description-item').attr('data-tab-available', '0').removeClass('is-active').attr('hidden', true);
        }
        
        // Update details
        if (defaultContent.product_details && defaultContent.product_details.trim() !== '') {
            $('#variation-details-content').html(defaultContent.product_details);
            $('#variation-details-item').attr('data-tab-available', '1');
        } else {
            $('#variation-details-item').attr('data-tab-available', '0').removeClass('is-active').attr('hidden', true);
        }
        
        // Update artisan
        if (defaultContent.artisan && defaultContent.artisan.trim() !== '') {
            $('#variation-artisan-content').html(defaultContent.artisan);
            $('#variation-artisan-item').attr('data-tab-available', '1');
        } else {
            $('#variation-artisan-item').attr('data-tab-available', '0').removeClass('is-active').attr('hidden', true);
        }
        
        // Update care
        if (defaultContent.care && defaultContent.care.trim() !== '') {
            $('#variation-care-content').html(defaultContent.care);
            $('#variation-care-item').attr('data-tab-available', '1');
        } else {
            $('#variation-care-item').attr('data-tab-available', '0').removeClass('is-active').attr('hidden', true);
        }
        
        // Update sensory
        if (defaultContent.sensory && defaultContent.sensory.trim() !== '') {
            $('#variation-sensory-content').html(defaultContent.sensory);
            $('#variation-sensory-item').attr('data-tab-available', '1');
        } else {
            $('#variation-sensory-item').attr('data-tab-available', '0').removeClass('is-active').attr('hidden', true);
        }

        if (window.loomaamaSyncDetailTabs) {
            window.loomaamaSyncDetailTabs();
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
    
    // Custom size select change handler
    $('#size-select').on('change', function() {
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