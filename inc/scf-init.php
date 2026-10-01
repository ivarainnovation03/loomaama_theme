<?php
/**
 * SCF (Secure Custom Fields) Initialization
 * 
 * @package Loomaama
 */

/**
 * Register all SCF field groups
 */
function loomaama_register_scf_fields() {
    if (!function_exists('register_field_group')) {
        return;
    }
    
    // Include all field group definitions
    $scf_files = array(
        'scf-care-guide.php',
        'scf-packaging.php',
        'scf-shipping-returns.php',
        'scf-social-contract.php',
        'scf-archive.php',
        'scf-artisans.php',
        'scf-forest.php',
        'scf-about.php',
        'scf-size-fit-guide.php',
        'scf-faq.php',
        'scf-contact-us.php',
        'scf-loomaama-archive.php',
    );
    
    foreach ($scf_files as $file) {
        $file_path = LOOMAAMA_DIR . '/inc/scf-fields/' . $file;
        if (file_exists($file_path)) {
            require_once $file_path;
        }
    }
}
add_action('acf/init', 'loomaama_register_scf_fields');

/**
 * Fix SCF script dependencies to prevent errors in widgets editor
 */
function loomaama_fix_scf_scripts() {
    // Only run in admin
    if (!is_admin()) {
        return;
    }
    
    // Get current screen
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    
    // If we're on widgets page or a page that uses block editor
    if ($screen && ($screen->base === 'widgets' || $screen->base === 'customize')) {
        // Remove wp-editor dependency from any SCF scripts
        global $wp_scripts;
        
        if (isset($wp_scripts) && property_exists($wp_scripts, 'registered')) {
            $handles = array('scf-bindings', 'scf-commands-admin', 'scf-commands-custom-post-types');
            
            foreach ($handles as $handle) {
                if (isset($wp_scripts->registered[$handle]) && isset($wp_scripts->registered[$handle]->deps)) {
                    // Remove wp-editor from dependencies
                    $wp_scripts->registered[$handle]->deps = array_filter(
                        $wp_scripts->registered[$handle]->deps,
                        function($dep) {
                            return $dep !== 'wp-editor';
                        }
                    );
                }
            }
        }
    }
}
add_action('admin_enqueue_scripts', 'loomaama_fix_scf_scripts', 1);