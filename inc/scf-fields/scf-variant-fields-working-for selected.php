<?php
/**
 * ACF (Advanced Custom Fields) Configuration for Product Variants
 * 
 * This file registers custom fields for products to enable variant indicators
 * on the shop archive page (color dots and variant count)
 * 
 * @package Loomaama
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Register ACF fields for product variants
 */
function loomaama_register_variant_acf_fields() {
    
    // Check if ACF function exists
    if (!function_exists('acf_add_local_field_group')) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p><strong>Loomaama Theme:</strong> Advanced Custom Fields (ACF) plugin is required for product variant fields.</p></div>';
        });
        return;
    }
    
    acf_add_local_field_group(array(
        'key' => 'group_product_variants',
        'title' => __('Product Variants', 'loomaama'),
        'fields' => array(
            // DESIGN GROUP SELECTION - Dropdown with "Create New" option
            array(
                'key' => 'field_design_group_select',
                'label' => __('Design Group', 'loomaama'),
                'name' => 'design_group_select',
                'type' => 'select',
                'instructions' => __('Select an existing Design Group OR choose "Create New" to add a new one.', 'loomaama'),
                'required' => false,
                'choices' => loomaama_get_design_group_choices(),
                'default_value' => false,
                'allow_null' => false,
                'multiple' => false,
                'ui' => true,
                'ajax' => false,
                'wrapper' => array('width' => '50'),
            ),
            
            // NEW DESIGN GROUP ID - Will be shown/hidden with JavaScript
            array(
                'key' => 'field_new_design_group_id',
                'label' => __('New Design Group ID', 'loomaama'),
                'name' => 'new_design_group_id',
                'type' => 'text',
                'instructions' => __('Enter a unique ID for the new design group (e.g., "diamond_shawl_001"). Use lowercase letters, numbers, and hyphens only.', 'loomaama'),
                'required' => false,
                'placeholder' => __('e.g., diamond_shawl_001', 'loomaama'),
                'wrapper' => array(
                    'width' => '50',
                    'class' => 'new-design-group-wrapper',
                ),
            ),
            
            // DESIGN GROUP TITLE
            array(
                'key' => 'field_design_group_title',
                'label' => __('Design Group Title', 'loomaama'),
                'name' => 'design_group_title',
                'type' => 'text',
                'instructions' => __('Optional: A descriptive name for this design group (e.g., "Diamond Pattern Shawl Collection").', 'loomaama'),
                'required' => false,
                'placeholder' => __('e.g., Diamond Pattern Shawl', 'loomaama'),
                'wrapper' => array('width' => '50'),
            ),
            
            // VARIANT COLOR (5 colors only)
            array(
                'key' => 'field_variant_color',
                'label' => __('Variant Color', 'loomaama'),
                'name' => 'variant_color',
                'type' => 'select',
                'instructions' => __('Select the color of this product variant.', 'loomaama'),
                'required' => false,
                'choices' => loomaama_get_acf_color_choices(),
                'default_value' => false,
                'allow_null' => true,
                'ui' => true,
                'wrapper' => array('width' => '33'),
            ),
            
            // VARIANT PATTERN
            array(
                'key' => 'field_variant_pattern',
                'label' => __('Variant Pattern', 'loomaama'),
                'name' => 'variant_pattern',
                'type' => 'select',
                'instructions' => __('Select the pattern of this product variant.', 'loomaama'),
                'required' => false,
                'choices' => loomaama_get_acf_pattern_choices(),
                'default_value' => false,
                'allow_null' => true,
                'ui' => true,
                'wrapper' => array('width' => '33'),
            ),
            
            // PATTERN THUMBNAIL
            array(
                'key' => 'field_pattern_thumbnail',
                'label' => __('Pattern Thumbnail', 'loomaama'),
                'name' => 'pattern_thumbnail',
                'type' => 'image',
                'instructions' => __('Upload a pattern preview image (60x60px recommended). Shows as circular thumbnail.', 'loomaama'),
                'required' => false,
                'return_format' => 'array',
                'preview_size' => 'thumbnail',
                'wrapper' => array('width' => '34'),
            ),
            
            // Hidden field to store the actual design_group_id
            array(
                'key' => 'field_design_group_id',
                'label' => __('Design Group ID (stored)', 'loomaama'),
                'name' => 'design_group_id',
                'type' => 'hidden',
                'required' => false,
                'wrapper' => array('style' => 'display:none;'),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'product',
                ),
            ),
        ),
        'menu_order' => 10,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
    ));
}
add_action('acf/init', 'loomaama_register_variant_acf_fields', 5);

/**
 * Get all existing Design Group IDs for dropdown
 */
function loomaama_get_design_group_choices() {
    $choices = array();
    
    // Query all products that have design_group_id meta
    $design_groups = get_posts(array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_key' => 'design_group_id',
        'meta_compare' => 'EXISTS',
        'fields' => 'ids',
    ));
    
    $design_group_map = array();
    
    foreach ($design_groups as $product_id) {
        $design_group_id = get_post_meta($product_id, 'design_group_id', true);
        $design_group_title = get_post_meta($product_id, 'design_group_title', true);
        
        if (!empty($design_group_id) && !isset($design_group_map[$design_group_id])) {
            // Format: "design_id - Title (if exists)"
            $label = $design_group_id;
            if (!empty($design_group_title)) {
                $label = $design_group_id . ' - ' . $design_group_title;
            }
            
            // Add count of products in this group
            $count = loomaama_count_products_in_design_group($design_group_id);
            if ($count > 1) {
                $label .= ' (' . $count . ' products)';
            }
            
            $design_group_map[$design_group_id] = $label;
        }
    }
    
    // Sort alphabetically
    asort($design_group_map);
    
    // Build the choices array
    $choices[''] = '— Select an Existing Design Group —';
    $choices = array_merge($choices, $design_group_map);
    $choices['_create_new'] = '✨ + Create New Design Group ✨';
    
    return $choices;
}

/**
 * Count products in a design group
 */
function loomaama_count_products_in_design_group($design_group_id) {
    if (empty($design_group_id)) return 0;
    
    $products = get_posts(array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_key' => 'design_group_id',
        'meta_value' => $design_group_id,
        'fields' => 'ids',
    ));
    
    return count($products);
}

/**
 * Get color choices (5 colors only)
 */
function loomaama_get_acf_color_choices() {
    return array(
        '' => __('— Select Color —', 'loomaama'),
        'natural-cream' => __('Natural Cream', 'loomaama'),
        'oatmeal' => __('Oatmeal', 'loomaama'),
        'sand' => __('Sand', 'loomaama'),
        'taupe' => __('Taupe', 'loomaama'),
        'earth-brown' => __('Earth Brown', 'loomaama'),
    );
}

/**
 * Get pattern choices from WooCommerce pa_pattern attribute
 */
function loomaama_get_acf_pattern_choices() {
    $choices = array(
        '' => __('— Select Pattern —', 'loomaama'),
    );
    
    // Try to get patterns from WooCommerce attribute
    $pattern_terms = get_terms(array(
        'taxonomy' => 'pa_pattern',
        'hide_empty' => false,
    ));
    
    if (!empty($pattern_terms) && !is_wp_error($pattern_terms)) {
        foreach ($pattern_terms as $term) {
            $choices[$term->slug] = $term->name;
        }
    }
    
    // Fallback pattern choices if no WooCommerce patterns exist
    if (count($choices) <= 1) {
        $choices = array_merge($choices, array(
            'diamond' => __('Diamond', 'loomaama'),
            'stripe' => __('Stripe', 'loomaama'),
            'floral' => __('Floral', 'loomaama'),
            'geometric' => __('Geometric', 'loomaama'),
            'plain' => __('Plain', 'loomaama'),
            'ikat' => __('Ikat', 'loomaama'),
        ));
    }
    
    return $choices;
}



/**
 * Auto-save hex color when variant color is selected
 */
add_action('acf/save_post', 'loomaama_auto_save_hex_color', 20);
function loomaama_auto_save_hex_color($post_id) {
    if (get_post_type($post_id) !== 'product') {
        return;
    }
    
    $color_slug = get_post_meta($post_id, 'variant_color', true);
    
    if (!empty($color_slug)) {
        $color_term = get_term_by('slug', $color_slug, 'pa_color');
        $color_name = $color_term ? $color_term->name : $color_slug;
        $hex = loomaama_get_color_hex_from_slug($color_slug, $color_name);
        update_post_meta($post_id, 'color_swatch_hex', $hex);
    }
}

/**
 * Save the design group ID - Handles both selection and new creation
 */
add_action('acf/save_post', 'loomaama_save_design_group', 4);
function loomaama_save_design_group($post_id) {
    if (get_post_type($post_id) !== 'product') {
        return;
    }
    
    // Get values
    $design_group_select = get_post_meta($post_id, 'design_group_select', true);
    $new_design_group_id = get_post_meta($post_id, 'new_design_group_id', true);
    
    $final_design_group_id = '';
    
    // If "Create New" was selected and a new ID was provided
    if ($design_group_select === '_create_new' && !empty($new_design_group_id)) {
        $final_design_group_id = sanitize_title($new_design_group_id);
    } 
    // Otherwise, if an existing group was selected
    elseif (!empty($design_group_select) && $design_group_select !== '_create_new' && $design_group_select !== '') {
        $final_design_group_id = sanitize_text_field($design_group_select);
    }
    
    // Save the final design group ID
    if (!empty($final_design_group_id)) {
        update_post_meta($post_id, 'design_group_id', $final_design_group_id);
    } else {
        delete_post_meta($post_id, 'design_group_id');
    }
}

/**
 * Load existing values into the select field
 */
add_filter('acf/load_value/name=design_group_select', 'loomaama_load_design_group_select', 10, 3);
function loomaama_load_design_group_select($value, $post_id, $field) {
    if (get_post_type($post_id) === 'product') {
        $saved_group_id = get_post_meta($post_id, 'design_group_id', true);
        if (!empty($saved_group_id)) {
            return $saved_group_id;
        }
    }
    return $value;
}

/**
 * Display helpful notice on product edit screen
 */
function loomaama_variant_fields_notice() {
    global $post;
    
    if (!$post || $post->post_type !== 'product') {
        return;
    }
    
    $design_group_id = get_post_meta($post->ID, 'design_group_id', true);
    
    if (empty($design_group_id)) {
        ?>
        <div class="notice notice-info inline" style="margin: 10px 0; padding: 12px;">
            <p>
                <strong><?php _e('💡 Variant Grouping Tip:', 'loomaama'); ?></strong><br>
                <?php _e('To show color dots on the shop page, select an existing <strong>Design Group</strong> or choose "✨ + Create New Design Group ✨" to create one. Then select the <strong>Variant Color</strong> for each product.', 'loomaama'); ?>
            </p>
        </div>
        <?php
    } else {
        $count = loomaama_count_products_in_design_group($design_group_id);
        if ($count > 1) {
            ?>
            <div class="notice notice-success inline" style="margin: 10px 0; padding: 12px;">
                <p>
                    <strong><?php _e('✓ Design Group Active', 'loomaama'); ?></strong><br>
                    <?php echo sprintf(__('This product is part of a design group with <strong>%d</strong> total products. Color dots will appear on the shop page.', 'loomaama'), $count); ?>
                </p>
            </div>
            <?php
        }
    }
}
add_action('edit_form_after_title', 'loomaama_variant_fields_notice');

/**
 * Add custom columns to products list table
 */
function loomaama_add_variant_columns($columns) {
    $new_columns = array();
    
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key === 'title') {
            $new_columns['design_group'] = __('Design Group', 'loomaama');
            $new_columns['variant_color'] = __('Color', 'loomaama');
        }
    }
    
    return $new_columns;
}
add_filter('manage_product_posts_columns', 'loomaama_add_variant_columns');

/**
 * Display custom column values
 */
function loomaama_display_variant_columns($column, $post_id) {
    switch ($column) {
        case 'design_group':
            $design_group = get_post_meta($post_id, 'design_group_id', true);
            $design_title = get_post_meta($post_id, 'design_group_title', true);
            $count = loomaama_count_products_in_design_group($design_group);
            
            if (!empty($design_group)) {
                echo '<code>' . esc_html($design_group) . '</code>';
                if (!empty($design_title)) {
                    echo '<br><small style="color: #666;">' . esc_html($design_title) . '</small>';
                }
                if ($count > 1) {
                    echo '<br><small style="color: #888;">(' . $count . ' variants)</small>';
                }
            } else {
                echo '<span style="color: #ccc;">—</span>';
            }
            break;
            
        case 'variant_color':
            $color_slug = get_post_meta($post_id, 'variant_color', true);
            $color_hex = get_post_meta($post_id, 'color_swatch_hex', true);
            
            if (!empty($color_slug)) {
                $color_name = '';
                $colors = loomaama_get_acf_color_choices();
                $color_name = isset($colors[$color_slug]) ? $colors[$color_slug] : $color_slug;
                
                if ($color_hex) {
                    echo '<span style="display: inline-block; width: 16px; height: 16px; background: ' . esc_attr($color_hex) . '; border: 1px solid #ddd; border-radius: 50%; margin-right: 8px; vertical-align: middle;"></span>';
                }
                echo esc_html($color_name);
            } else {
                echo '<span style="color: #ccc;">—</span>';
            }
            break;
    }
}
add_action('manage_product_posts_custom_column', 'loomaama_display_variant_columns', 10, 2);

/**
 * Make variant columns sortable
 */
function loomaama_sortable_variant_columns($columns) {
    $columns['design_group'] = 'design_group_id';
    $columns['variant_color'] = 'variant_color';
    return $columns;
}
add_filter('manage_edit-product_sortable_columns', 'loomaama_sortable_variant_columns');

/**
 * Add JavaScript to handle showing/hiding the new design group field
 * This is the key fix for the conditional field visibility
 */
add_action('acf/input/admin_footer', 'loomaama_design_group_admin_js');
function loomaama_design_group_admin_js() {
    global $post;
    if (!$post || $post->post_type !== 'product') return;
    ?>
    <script type="text/javascript">
    (function($) {
        $(document).ready(function() {
            
            // Function to toggle the new design group field
            function toggleNewDesignGroupField() {
                // Find the select field - ACF select with name "design_group_select"
                var $select = $('select[name="acf[field_design_group_select]"]');
                var $newFieldRow = $('.acf-field[data-name="new_design_group_id"]');
                
                if ($select.length && $newFieldRow.length) {
                    var selectedValue = $select.val();
                    
                    if (selectedValue === '_create_new') {
                        $newFieldRow.show();
                        // Add a small animation
                        $newFieldRow.css('transition', 'all 0.3s ease');
                    } else {
                        $newFieldRow.hide();
                    }
                }
            }
            
            // Run on page load (with a small delay to ensure ACF is ready)
            setTimeout(function() {
                toggleNewDesignGroupField();
            }, 100);
            
            // Run when select value changes
            $(document).on('change', 'select[name="acf[field_design_group_select]"]', function() {
                toggleNewDesignGroupField();
            });
            
            // Also listen for ACF's select2 changes
            $(document).on('select2:select', 'select[name="acf[field_design_group_select]"]', function(e) {
                toggleNewDesignGroupField();
            });
            
            $(document).on('select2:clear', 'select[name="acf[field_design_group_select]"]', function(e) {
                toggleNewDesignGroupField();
            });
        });
    })(jQuery);
    </script>
    
    <style>
        /* Style for the new design group field */
        .acf-field[data-name="new_design_group_id"] {
            background: #f8f9fa;
            border-left: 4px solid #2271b1;
            padding: 12px 15px !important;
            margin: 10px 0 !important;
            border-radius: 4px;
            display: none;
        }
        .acf-field[data-name="new_design_group_id"] .acf-label label {
            font-weight: 600;
            color: #2271b1;
        }
        .acf-field[data-name="new_design_group_id"] .acf-label label:before {
            content: "✨ ";
            font-size: 14px;
        }
    </style>
    <?php
}

/**
 * Also add a filter to ensure the new group ID is saved when submitted via AJAX
 */
add_action('wp_ajax_acf/validate_save_post', 'loomaama_ensure_new_group_save', 1);
function loomaama_ensure_new_group_save() {
    if (isset($_POST['acf']['field_design_group_select']) && $_POST['acf']['field_design_group_select'] === '_create_new') {
        if (isset($_POST['acf']['field_new_design_group_id']) && !empty($_POST['acf']['field_new_design_group_id'])) {
            // Ensure the value is sanitized
            $_POST['acf']['field_design_group_id'] = sanitize_title($_POST['acf']['field_new_design_group_id']);
        }
    }
}