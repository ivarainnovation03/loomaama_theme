<?php
/**
 * WP Bootstrap Navwalker
 *
 * @package Loomaama
 */
class Bootstrap_Mega_Menu_Walker extends Walker_Nav_Menu {

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

        if ( $depth === 0 ) {
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
            // Column Headers (Nettle, Collection, Women)
            // Note: We use col-md-3 as default; you can use CSS classes in WP Admin to change this per item
            $col_class = in_array('col-6', $classes) ? 'col-md-6' : 'col-md-3'; 
            $output .= '<div class="' . $col_class . '">';
            $output .= '<h6 class="headline mega-title">' . $item->title . '</h6>';
        } 
        elseif ( $depth === 2 ) {
            // Individual Links inside columns
            $output .= '<a href="' . $item->url . '" class="mega-link">' . $item->title . '</a>';
        }
    }

    function end_el( &$output, $item, $depth = 0, $args = null ) {
        if ( $depth === 0 || $depth === 1 ) {
            $output .= '</div>';
        }
    }
}