<?php
/**
 * Checkout Page
 *
 * @package Loomaama
 */

get_header(); ?>

<main class="checkout-page py-5">
    <div class="container">
        <h1 class="headline text-center mb-5"><?php _e('Checkout', 'loomaama'); ?></h1>

        <?php
        // If cart is empty, show message
        if (WC()->cart->is_empty()) :
        ?>
            <div class="text-center py-5">
                <p class="mb-4"><?php _e('Your cart is currently empty.', 'loomaama'); ?></p>
                <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn btn-dark px-4 py-3">
                    <?php _e('Continue Shopping', 'loomaama'); ?>
                </a>
            </div>
        <?php
        else :
            // Show checkout form
            wc_get_template_part('checkout/form-checkout');
        endif;
        ?>
    </div>
</main>

<?php get_footer(); ?>