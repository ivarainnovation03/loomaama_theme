<?php
/**
 * Theme Customizer Settings
 *
 * @package Loomaama
 */

function loomaama_customize_register($wp_customize) {
    
    // =============================================
    // Hero Section
    // =============================================
    $wp_customize->add_section('hero_section', array(
        'title' => __('Hero Section', 'loomaama'),
        'priority' => 30,
    ));
    
    // Hero Type
    $wp_customize->add_setting('hero_type', array(
        'default' => 'video',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control('hero_type', array(
        'label' => __('Hero Type', 'loomaama'),
        'section' => 'hero_section',
        'type' => 'select',
        'choices' => array(
            'video' => __('Video Background', 'loomaama'),
            'image' => __('Image Background', 'loomaama'),
            'parallax' => __('Parallax Image', 'loomaama'),
        ),
    ));
    
    // Hero Video (MP4)
    $wp_customize->add_setting('hero_video_mp4', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'hero_video_mp4', array(
        'label' => __('Hero Video (MP4)', 'loomaama'),
        'section' => 'hero_section',
        'mime_type' => 'video',
    )));
    
    // Hero Image (Fallback)
    $wp_customize->add_setting('hero_image', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_image', array(
        'label' => __('Hero Image (Fallback)', 'loomaama'),
        'section' => 'hero_section',
    )));
    
    // Hero Title
    $wp_customize->add_setting('hero_title', array(
        'default' => __('The Nettle Journey', 'loomaama'),
        'sanitize_callback' => 'wp_kses_post',
        'transport' => 'postMessage',
    ));
    
    $wp_customize->add_control('hero_title', array(
        'label' => __('Hero Title', 'loomaama'),
        'section' => 'hero_section',
        'type' => 'text',
    ));
    
    // Hero Subtitle
    $wp_customize->add_setting('hero_subtitle', array(
        'default' => __('Once wild and untamed, nettle now finds new life in fabric that is both enduring and graceful.', 'loomaama'),
        'sanitize_callback' => 'wp_kses_post',
        'transport' => 'postMessage',
    ));
    
    $wp_customize->add_control('hero_subtitle', array(
        'label' => __('Hero Subtitle', 'loomaama'),
        'section' => 'hero_section',
        'type' => 'textarea',
    ));
    
    // Hero Button Text
    $wp_customize->add_setting('hero_button_text', array(
        'default' => __('Discover Collection →', 'loomaama'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ));
    
    $wp_customize->add_control('hero_button_text', array(
        'label' => __('Button Text', 'loomaama'),
        'section' => 'hero_section',
        'type' => 'text',
    ));
    
    // Hero Button URL
    $wp_customize->add_setting('hero_button_url', array(
        'default' => '#',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control('hero_button_url', array(
        'label' => __('Button URL', 'loomaama'),
        'section' => 'hero_section',
        'type' => 'url',
    ));
    
    // Overlay Opacity
    $wp_customize->add_setting('hero_overlay_opacity', array(
        'default' => '0.25',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control('hero_overlay_opacity', array(
        'label' => __('Overlay Opacity (0-1)', 'loomaama'),
        'section' => 'hero_section',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 0,
            'max' => 1,
            'step' => 0.05,
        ),
    ));
    
    // Hero Text Position
    $wp_customize->add_setting('hero_text_position', array(
        'default' => 'center',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control('hero_text_position', array(
        'label' => __('Text Position', 'loomaama'),
        'section' => 'hero_section',
        'type' => 'radio',
        'choices' => array(
            'left' => __('Left', 'loomaama'),
            'center' => __('Center', 'loomaama'),
            'right' => __('Right', 'loomaama'),
        ),
    ));
    
    // =============================================
    // Colors Section
    // =============================================
    $wp_customize->add_setting('primary_color', array(
        'default' => '#9E8574',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color', array(
        'label' => __('Primary Color', 'loomaama'),
        'section' => 'colors',
    )));
    
    $wp_customize->add_setting('secondary_color', array(
        'default' => '#6F6A63',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'secondary_color', array(
        'label' => __('Secondary Color', 'loomaama'),
        'section' => 'colors',
    )));
    
    $wp_customize->add_setting('background_color', array(
        'default' => '#FAF9F7',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'background_color', array(
        'label' => __('Background Color', 'loomaama'),
        'section' => 'colors',
    )));
    
    // =============================================
    // Homepage Content
    // =============================================
    $wp_customize->add_section('homepage_content', array(
        'title' => __('Homepage Content', 'loomaama'),
        'priority' => 35,
    ));

    // Quote Section
    $wp_customize->add_setting('quote_subtitle', array(
        'default' => __('Nettle. Reclaimed.', 'loomaama'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('quote_subtitle', array(
        'label' => __('Quote Subtitle', 'loomaama'),
        'section' => 'homepage_content',
    ));

    $wp_customize->add_setting('quote_text', array(
        'default' => __('From the slopes of the Himalayas to the hands of our artisans. Crafting timeless pieces with nature\'s rarest fiber', 'loomaama'),
        'sanitize_callback' => 'wp_kses_post',
    ));
    $wp_customize->add_control('quote_text', array(
        'label' => __('Quote Text', 'loomaama'),
        'section' => 'homepage_content',
        'type' => 'textarea',
    ));

    // Split Section
    $wp_customize->add_setting('split_title', array(
        'default' => __('A choreography of nettle and artisan hand', 'loomaama'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('split_title', array(
        'label' => __('Split Section Title', 'loomaama'),
        'section' => 'homepage_content',
    ));

    $wp_customize->add_setting('split_text', array(
        'default' => __('Forged at the intersection of nature\'s thorns and the artisan\'s touch. Raw elements, elevated by hand into timeless form.', 'loomaama'),
        'sanitize_callback' => 'wp_kses_post',
    ));
    $wp_customize->add_control('split_text', array(
        'label' => __('Split Section Text', 'loomaama'),
        'section' => 'homepage_content',
        'type' => 'textarea',
    ));

    $wp_customize->add_setting('split_image', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'split_image', array(
        'label' => __('Split Section Image', 'loomaama'),
        'section' => 'homepage_content',
    )));

    // =============================================
    // Footer Section
    // =============================================
    $wp_customize->add_section('footer_section', array(
        'title' => __('Footer', 'loomaama'),
        'priority' => 90,
    ));
    
    // Footer Newsletter Title
    $wp_customize->add_setting('footer_newsletter_title', array(
        'default' => __('Newsletter', 'loomaama'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ));
    
    $wp_customize->add_control('footer_newsletter_title', array(
        'label' => __('Newsletter Title', 'loomaama'),
        'section' => 'footer_section',
        'type' => 'text',
    ));
    
    // Footer Newsletter Text
    $wp_customize->add_setting('footer_newsletter_text', array(
        'default' => __('Subscribe to receive communications about our products and collaborations.', 'loomaama'),
        'sanitize_callback' => 'wp_kses_post',
        'transport' => 'postMessage',
    ));
    
    $wp_customize->add_control('footer_newsletter_text', array(
        'label' => __('Newsletter Text', 'loomaama'),
        'section' => 'footer_section',
        'type' => 'textarea',
    ));
    
    // Copyright Text
    $wp_customize->add_setting('copyright_text', array(
        'default' => __('© 2026 LOOMAAMA. ALL RIGHTS RESERVED.', 'loomaama'),
        'sanitize_callback' => 'wp_kses_post',
        'transport' => 'postMessage',
    ));
    
    $wp_customize->add_control('copyright_text', array(
        'label' => __('Copyright Text', 'loomaama'),
        'section' => 'footer_section',
        'type' => 'text',
    ));
    
    // =============================================
    // Product Page Settings
    // =============================================
    $wp_customize->add_section('product_page', array(
        'title' => __('Product Page', 'loomaama'),
        'priority' => 40,
    ));
    
    // Gallery Layout
    $wp_customize->add_setting('product_gallery_layout', array(
        'default' => 'grid',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control('product_gallery_layout', array(
        'label' => __('Gallery Layout', 'loomaama'),
        'section' => 'product_page',
        'type' => 'select',
        'choices' => array(
            'grid' => __('Grid', 'loomaama'),
            'stack' => __('Stacked', 'loomaama'),
            'carousel' => __('Carousel', 'loomaama'),
        ),
    ));
    
    // Related Products Count
    $wp_customize->add_setting('related_products_count', array(
        'default' => 4,
        'sanitize_callback' => 'absint',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control('related_products_count', array(
        'label' => __('Number of Related Products', 'loomaama'),
        'section' => 'product_page',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 2,
            'max' => 8,
            'step' => 1,
        ),
    ));
}
add_action('customize_register', 'loomaama_customize_register');

/**
 * Generate customizer CSS
 */
function loomaama_customizer_css() {
    ?>
    <style type="text/css">
        :root {
            --brand: <?php echo esc_attr(get_theme_mod('primary_color', '#9E8574')); ?>;
            --brand-hover: <?php echo esc_attr(adjust_brightness(get_theme_mod('primary_color', '#9E8574'), -20)); ?>;
            --text-secondary: <?php echo esc_attr(get_theme_mod('secondary_color', '#6F6A63')); ?>;
            --bg-main: <?php echo esc_attr(get_theme_mod('background_color', '#FAF9F7')); ?>;
            --overlay-opacity: <?php echo esc_attr(get_theme_mod('hero_overlay_opacity', '0.25')); ?>;
        }
        
       
    </style>
    <?php
}
add_action('wp_head', 'loomaama_customizer_css');

/**
 * Helper function to adjust color brightness
 */
function adjust_brightness($hex, $steps) {
    $hex = str_replace('#', '', $hex);
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    $r = max(0, min(255, $r + $steps));
    $g = max(0, min(255, $g + $steps));
    $b = max(0, min(255, $b + $steps));
    
    return '#' . dechex($r) . dechex($g) . dechex($b);
}


/**
 * Fix encoded <br> tags in product descriptions
 * Usage: echo fix_product_br_tags($content);
 */
function fix_product_br_tags($content) {
    // Return if not a string
    if (!is_string($content)) {
        return $content;
    }
    
    // Replace all encoded br variations
    $search = array(
        '&lt;br&gt;',
        '&lt;br /&gt;',
        '&lt;br/&gt;',
        '&amp;lt;br&amp;gt;',
        '&amp;lt;br /&amp;gt;',
        '&amp;lt;br/&amp;gt;',
        '&#60;br&#62;',
        '&#60;br /&#62;',
        '&#60;br/&#62;'
    );
    
    $replace = array(
        '<br>',
        '<br>',
        '<br>',
        '<br>',
        '<br>',
        '<br>',
        '<br>',
        '<br>',
        '<br>'
    );
    
    $content = str_replace($search, $replace, $content);
    
    // Decode all HTML entities
    $content = html_entity_decode($content, ENT_QUOTES, 'UTF-8');
    
    // Convert any remaining <br> tags to line breaks
    // (in case they got converted back)
    $content = str_replace(array('<br>', '<br />', '<br/>'), '<br>', $content);
    
    return $content;
}