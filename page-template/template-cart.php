<?php
/*
 * Template Name: Cart
 * Template Post Type: page
 */

defined( 'ABSPATH' ) || exit;

get_header();

?>
<div id="primary" class="content-area">
	<main id="main" class="site-main">
		<?php
		if ( class_exists( 'WooCommerce' ) ) {
			echo do_shortcode( '[woocommerce_cart]' );
		} else {
			?>
			<div class="woocommerce-notice">
				<p><?php esc_html_e( 'WooCommerce is not active. Please install and activate the WooCommerce plugin to use the cart.', 'loomaama' ); ?></p>
			</div>
			<?php
		}
		?>
	</main>
</div>

<?php
get_footer();

