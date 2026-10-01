<?php
/**
 * WP Bootstrap Navwalker
 *
 * @package Loomaama
 */
class Bootstrap_Mega_Menu_Walker extends Walker_Nav_Menu {

    // Store items to check if it's the last child
    private $level_0_items = array();
    private $current_level_0_index = 0;

    function __construct() {
        add_filter('wp_nav_menu_objects', array($this, 'store_level_0_items'), 10, 2);
    }

    // Store all level 0 menu items and their children
    function store_level_0_items($items, $args) {
        $this->level_0_items = array();
        foreach ($items as $key => $item) {
            if ($item->menu_item_parent == 0) {
                $this->level_0_items[] = $item;
            }
        }
        return $items;
    }

    // Start Level: Handles the <ul> or <div> containers
    function start_lvl( &$output, $depth = 0, $args = null ) {
        if ( $depth === 0 ) {
            // This is the container for the mega menu dropdown
            $output .= '<div class="dropdown-menu mega-menu shadow-lg border-0 p-5"><div class="row g-5">';
        } elseif ( $depth === 1 ) {
            // We don't need a new tag here because start_el handles the col-md-X
        }
    }

    // End Level
    function end_lvl( &$output, $depth = 0, $args = null ) {
        if ( $depth === 0 ) {
            $output .= '</div></div>';
        }
    }

    // Start Element: Handles the links and column wrappers
    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        
        // Check if current item has children
        $has_children = ! empty( $args->walker->has_children );
        
        // Check if show_as_image is enabled (ACF true/false field)
        $show_as_image = function_exists('get_field') ? get_field('show_as_image', $item->ID) : false;

        if ( $depth === 0 ) {
            // Track current level 0 item index
            $this->current_level_0_index = array_search($item, $this->level_0_items);
            
            // Top Level Items (New, Shop, Brand)
            $output .= '<div class="' . ( $has_children ? 'dropdown mega-dropdown' : 'nav-item' ) . '">';
            
            if ( $has_children ) {
                // Item has children - show as dropdown
                $output .= '<a href="' . $item->url . '" class="nav-link-top dropdown-toggle" data-bs-toggle="dropdown">' . $item->title . '</a>';
            } else {
                // Item has no children - regular link without dropdown
                $output .= '<a href="' . $item->url . '" class="nav-link-top">' . $item->title . '</a>';
            }
        } 
        elseif ( $depth === 1 ) {
            // Check if this is a "show as image" item
            $is_last_item = $this->is_last_level_1_item($item);
            
            if ($show_as_image && $is_last_item && !$has_children) {
                // This item will be rendered as image in the last column
                // Don't output anything here - will be handled in end_lvl or separately
                $this->render_image_column($output, $item, $classes);
            } else {
                // Regular Column Headers (Nettle, Collection, Women)
                // Note: We use col-md-3 as default; you can use CSS classes in WP Admin to change this per item
                $col_class = in_array('col-6', $classes) ? 'col-md-6' : 'col-md-3'; 
                $output .= '<div class="' . $col_class . '">';
                $output .= '<h6 class="headline mega-title">' . $item->title . '</h6>';
            }
        } 
        elseif ( $depth === 2 ) {
            // Individual Links inside columns
            $output .= '<a href="' . $item->url . '" class="mega-link">' . $item->title . '</a>';
        }
    }

    function end_el( &$output, $item, $depth = 0, $args = null ) {
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $show_as_image = function_exists('get_field') ? get_field('show_as_image', $item->ID) : false;
        $has_children = ! empty( $args->walker->has_children );
        $is_last_item = $this->is_last_level_1_item($item);
        
        if ( $depth === 0 ) {
            $output .= '</div>';
        } 
        elseif ( $depth === 1 ) {
            // Don't close div for image items since they were handled differently
            if (!($show_as_image && $is_last_item && !$has_children)) {
                $output .= '</div>';
            }
        }
    }
    
    /**
     * Check if this is the last level 1 item under its parent level 0 item
     */
    private function is_last_level_1_item($item) {
        if (!isset($this->level_0_items[$this->current_level_0_index])) {
            return false;
        }
        
        $parent_item = $this->level_0_items[$this->current_level_0_index];
        
        // Get all children of this parent
        $children = get_posts(array(
            'post_type' => 'nav_menu_item',
            'posts_per_page' => -1,
            'meta_key' => '_menu_item_menu_item_parent',
            'meta_value' => $parent_item->ID,
            'fields' => 'ids'
        ));
        
        if (empty($children)) {
            return false;
        }
        
        // Check if current item is the last child
        return end($children) == $item->ID;
    }
    
    /**
     * Render a column with image for "show_as_image" items
     */
    private function render_image_column(&$output, $item, $classes) {
        $col_class = in_array('col-6', $classes) ? 'col-md-6' : 'col-md-3';
        $image_url = $this->get_menu_item_image($item);
        
        $output .= '<div class="' . $col_class . '">';
        $output .= '<div class="mega-image-column">';
        
        if ($image_url) {
            $output .= '<a href="' . $item->url . '" class="mega-image-link">';
            $output .= '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($item->title) . '" class="img-fluid rounded mega-menu-image">';
            $output .= '</a>';
        }
        
        // Optionally show title below image
        if ($item->title) {
            $output .= '<h6 class="mega-image-title mt-3">' . $item->title . '</h6>';
        }
        
        $output .= '</div>';
        $output .= '</div>';
    }
    
    /**
     * Get image URL from ACF custom field 'menu_item_image'
     * Returns the image URL if available, otherwise null
     */
    private function get_menu_item_image($item) {
        // Get image from ACF custom field 'menu_item_image'
        $image = function_exists('get_field') ? get_field('menu_item_image', $item->ID) : null;
        
        if ($image) {
            // Check if ACF returns an array (with ID, URL, etc.)
            if (is_array($image) && isset($image['url'])) {
                return $image['url'];
            }
            // Check if ACF returns an image ID
            elseif (is_numeric($image)) {
                $image_url = wp_get_attachment_image_url($image, 'medium');
                if ($image_url) {
                    return $image_url;
                }
            }
            // Check if it's a string (direct URL)
            elseif (is_string($image) && filter_var($image, FILTER_VALIDATE_URL)) {
                return $image;
            }
        }
        
        // Return null if no image found (won't display anything)
        return null;
    }
}