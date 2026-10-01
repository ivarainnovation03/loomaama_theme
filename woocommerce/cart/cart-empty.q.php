<?php
/**
 * Empty cart page
 *
 * @package Loomaama
 */

defined('ABSPATH') || exit;
?>

<div class="text-center py-5">
    <h2 class="headline mb-4"><?php _e('Your cart is currently empty.', 'loomaama'); ?></h2>
    
    <p class="text-secondary mb-5">
        <?php _e('Browse our collections and discover timeless pieces crafted with care.', 'loomaama'); ?>
    </p>
    
    <div class="d-flex justify-content-center gap-3">
        <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn btn-dark px-5 py-3">
            <?php _e('Continue Shopping', 'loomaama'); ?>
        </a>
        
        <?php if (has_nav_menu('primary')) : ?>
            <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" class="btn btn-outline-dark px-5 py-3">
                <?php _e('My Account', 'loomaama'); ?>
            </a>
        <?php endif; ?>
    </div>
    
    <!-- Suggested Categories -->
    <div class="row mt-5 pt-5">
        <div class="col-12">
            <h4 class="headline mb-4"><?php _e('Shop by Category', 'loomaama'); ?></h4>
        </div>
        
        <?php
        $categories = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
            'number' => 4,
        ));
        
        foreach ($categories as $category) :
            $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
            $image = wp_get_attachment_url($thumbnail_id);
        ?>
            <div class="col-md-3 mb-4">
                <a href="<?php echo esc_url(get_term_link($category)); ?>" class="text-decoration-none">
                    <div class="category-card text-center">
                        <?php if ($image) : ?>
                            <img src="<?php echo esc_url($image); ?>" class="img-fluid mb-3" alt="<?php echo esc_attr($category->name); ?>">
                        <?php endif; ?>
                        <h5 class="headline"><?php echo esc_html($category->name); ?></h5>
                        <span class="text-secondary small"><?php echo sprintf(_n('%s product', '%s products', $category->count, 'loomaama'), $category->count); ?></span>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>