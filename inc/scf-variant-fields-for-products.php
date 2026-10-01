<?php
/**
 * Register variation-specific detail fields
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function loomaama_register_variation_acf_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    
    acf_add_local_field_group(array(
        'key' => 'group_variation_details',
        'title' => __('Variation Details', 'loomaama'),
        'fields' => array(
            array(
                'key' => 'field_variation_gallery',
                'label' => __('Variation Gallery', 'loomaama'),
                'name' => 'variation_gallery',
                'type' => 'gallery',
                'instructions' => __('Upload images for this variation', 'loomaama'),
                'required' => false,
                'return_format' => 'array',
                'preview_size' => 'thumbnail',
                'insert' => 'append',
                'library' => 'all',
            ),
             array(
                'key' => 'field_variation_short_description',
                'label' => __('Short Description (Size/Width Specific)', 'loomaama'),
                'name' => 'variation_short_description',
                'type' => 'wysiwyg',
                'instructions' => __('Product short description specific to this size/width variation', 'loomaama'),
                'required' => false,
                'rows' => 4,
                'wrapper' => array('width' => '50'),
            ),
             array(
                'key' => 'field_variation_long_description',
                'label' => __('Long Description (Size/Width Specific)', 'loomaama'),
                'name' => 'variation_long_description',
                'type' => 'wysiwyg',
                'instructions' => __('Product long description specific to this size/width variation', 'loomaama'),
                'required' => false,
                'rows' => 4,
                'wrapper' => array('width' => '50'),
            ),
            array(
                'key' => 'field_variation_product_details',
                'label' => __('Details (Size Specific)', 'loomaama'),
                'name' => 'variation_product_details',
                'type' => 'wysiwyg',
                'instructions' => __('Product details specific to this size variation', 'loomaama'),
                'required' => false,
                'wrapper' => array('width' => '50'),
            ),
            array(
                'key' => 'field_variation_artisan_story',
                'label' => __('Artisan Story (Size Specific)', 'loomaama'),
                'name' => 'variation_artisan_story',
                'type' => 'wysiwyg',
                'instructions' => __('Artisan story specific to this size variation', 'loomaama'),
                'required' => false,
                'wrapper' => array('width' => '50'),
            ),
            array(
                'key' => 'field_variation_care_description',
                'label' => __('Care Instructions (Size Specific)', 'loomaama'),
                'name' => 'variation_care_description',
                'type' => 'wysiwyg',
                'instructions' => __('Care instructions specific to this size variation', 'loomaama'),
                'required' => false,
                'wrapper' => array('width' => '50'),
            ),
            array(
                'key' => 'field_variation_sensory_description',
                'label' => __('Sensory Description (Size Specific)', 'loomaama'),
                'name' => 'variation_sensory_description',
                'type' => 'wysiwyg',
                'instructions' => __('Sensory description specific to this size variation', 'loomaama'),
                'required' => false,
                'wrapper' => array('width' => '50'),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'product_variation',
                ),
            ),
        ),
        'menu_order' => 15,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
    ));
}
add_action('acf/init', 'loomaama_register_variation_acf_fields', 5);

/**
 * Render ACF fields in variation admin panel
 */
add_action('woocommerce_product_after_variable_attributes', 'loomaama_render_variation_acf_fields', 10, 3);
function loomaama_render_variation_acf_fields($loop, $variation_data, $variation) {
    if (!function_exists('acf_get_field_groups')) {
        return;
    }

    $variation_id = $variation->ID;

    $field_groups = acf_get_field_groups(array(
        'post_id' => $variation_id,
        'post_type' => 'product_variation'
    ));

    if (empty($field_groups)) {
        return;
    }

    echo '<div class="variation-acf-fields" style="clear:both; padding:15px; border-top:1px solid #ddd;">';
    echo '<h4>Variation Custom Fields</h4>';

    foreach ($field_groups as $field_group) {
        $fields = acf_get_fields($field_group);

        if ($fields) {
            foreach ($fields as &$field) {
                $field['value']  = get_field($field['name'], $variation_id, false);
                $field['prefix'] = 'acf_variation[' . $variation_id . ']';
            }

            acf_render_fields($fields, $variation_id, 'div', 'field');
        }
    }

    echo '</div>';
}

/**
 * Save ACF fields when variation is saved
 */
add_action('woocommerce_save_product_variation', 'loomaama_save_variation_acf_data', 10, 2);
function loomaama_save_variation_acf_data($variation_id, $i) {
    if (!function_exists('update_field')) return;

    if (!empty($_POST['acf_variation'][$variation_id])) {
        foreach ($_POST['acf_variation'][$variation_id] as $field_key => $value) {
            if (is_array($value)) {
                $value = array_map('intval', $value);
            }
            update_field($field_key, $value, $variation_id);
        }
    }
}

/**
 * Load ACF field values for variations
 */
add_filter('acf/load_value', 'loomaama_load_variation_acf_value', 10, 3);
function loomaama_load_variation_acf_value($value, $post_id, $field) {
    if (get_post_type($post_id) === 'product_variation') {
        $saved_value = get_post_meta($post_id, $field['name'], true);
        if ($saved_value !== '') {
            return $saved_value;
        }
    }
    return $value;
}

add_action('admin_footer', 'loomaama_fix_variation_wysiwyg_init');
function loomaama_fix_variation_wysiwyg_init() {
    global $pagenow;

    if ($pagenow !== 'post.php' && $pagenow !== 'post-new.php') {
        return;
    }
    ?>
    <script>
    jQuery(function($) {
        $('#woocommerce-product-data').on('woocommerce_variations_loaded woocommerce_variations_added', function() {
            if (typeof acf !== 'undefined') {
                acf.doAction('append', $('#variable_product_options'));
            }
        });

        $('.woocommerce_variations').on('click', '.variation h3', function() {
            if (typeof acf !== 'undefined') {
                setTimeout(function() {
                    acf.doAction('append', $('#variable_product_options'));
                }, 300);
            }
        });
    });
    </script>
    <?php
}

/**
 * AJAX handler to get variation ACF data - FIXED VERSION
 */
add_action('wp_ajax_loomaama_get_variation_acf_data', 'loomaama_ajax_get_variation_acf_data');
add_action('wp_ajax_nopriv_loomaama_get_variation_acf_data', 'loomaama_ajax_get_variation_acf_data');

function loomaama_ajax_get_variation_acf_data() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'loomaama_nonce')) {
        wp_send_json_error('Invalid nonce');
    }
    
    $variation_id = intval($_POST['variation_id']);
    
    if (!$variation_id) {
        wp_send_json_error('Invalid variation ID');
    }
    
    $variation = wc_get_product($variation_id);
    
    if (!$variation) {
        wp_send_json_error('Variation not found');
    }
    
    // Get variation-specific ACF data
    $gallery = get_field('variation_gallery', $variation_id);
    $short_description = get_field('variation_short_description', $variation_id);
    $long_description = get_field('variation_long_description', $variation_id);
    $product_details = get_field('variation_product_details', $variation_id);
    $artisan = get_field('variation_artisan_story', $variation_id);
    $care = get_field('variation_care_description', $variation_id);
    $sensory = get_field('variation_sensory_description', $variation_id);
    
    // Log for debugging (remove in production)
    error_log("Loading variation ACF data for ID: {$variation_id}");
    
    // Format gallery for response
    $formatted_gallery = array();
    if ($gallery && is_array($gallery)) {
        foreach ($gallery as $image) {
            $formatted_gallery[] = array(
                'url' => $image['url'],
                'alt' => $image['alt'],
                'caption' => $image['caption']
            );
        }
    }
    
    // If variation doesn't have custom data, check main product
    $product_id = $variation->get_parent_id();
    $main_product = wc_get_product($product_id);
    
    // Short description fallback
    if (empty($short_description) || !trim(strip_tags($short_description))) {
        // Try ACF field first
        $short_description = get_field('short_description', $product_id);
        // Fallback to WooCommerce short description
        if (empty($short_description) && $main_product) {
            $short_description = $main_product->get_short_description();
        }
    }
    
    // Long description fallback
    if (empty($long_description) || !trim(strip_tags($long_description))) {
        $long_description = get_field('long_description', $product_id);
        if (empty($long_description) && $main_product) {
            $long_description = $main_product->get_description();
        }
    }
    
    // Product details fallback
    if (empty($product_details) || !trim(strip_tags($product_details))) {
        $product_details = get_field('product_details', $product_id);
    }
    
    // Artisan story fallback
    if (empty($artisan) || !trim(strip_tags($artisan))) {
        $artisan = get_field('artisan_story', $product_id);
    }
    
    // Care instructions fallback
    if (empty($care) || !trim(strip_tags($care))) {
        $care = get_field('care_description', $product_id);
    }
    
    // Sensory description fallback
    if (empty($sensory) || !trim(strip_tags($sensory))) {
        $sensory = get_field('sensory_description', $product_id);
    }
    
    wp_send_json_success(array(
        'gallery' => $formatted_gallery,
        'short_description' => $short_description ?: '',
        'long_description' => $long_description ?: '',
        'product_details' => $product_details ?: '',
        'artisan' => $artisan ?: '',
        'care' => $care ?: '',
        'sensory' => $sensory ?: '',
        'variation_id' => $variation_id
    ));
}