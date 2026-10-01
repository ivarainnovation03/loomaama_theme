<?php
/**
 * Theme Setup Functions
 *
 * @package Loomaama
 */

function loomaama_theme_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', array(
        'height' => 100,
        'width' => 300,
        'flex-height' => true,
        'flex-width' => true,
    ));
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    // WooCommerce 3.0+ compatibility
    add_theme_support('woocommerce', array(
        'thumbnail_image_width' => 600,
        'single_image_width'    => 800,
        'product_grid'          => array(
            'default_rows'    => 3,
            'min_rows'        => 2,
            'max_rows'        => 8,
            'default_columns' => 3,
            'min_columns'     => 2,
            'max_columns'     => 5,
        ),
    ));
    // Set the maximum content width
    global $content_width;
    if (!isset($content_width)) {
        $content_width = 1200;
    }
    
    // Load theme textdomain
    load_theme_textdomain('loomaama', LOOMAAMA_DIR . '/languages');
    
    // Editor styles
    add_editor_style('assets/css/editor-style.css');
}
add_action('after_setup_theme', 'loomaama_theme_setup');