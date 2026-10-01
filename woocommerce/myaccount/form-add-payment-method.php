<?php
/**
 * Add payment method form form
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.8.0
 */

defined( 'ABSPATH' ) || exit;

$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
?>

<?php if ( $available_gateways ) : ?>
	<form id="add_payment_method" method="post" class="card shadow-sm border-0 mb-4">
		<div class="card-body p-4" id="payment">
			<ul class="woocommerce-PaymentMethods payment_methods methods list-group mb-4">
				<?php
				// Chosen Method.
				if ( count( $available_gateways ) ) {
					current( $available_gateways )->set_current();
				}

				foreach ( $available_gateways as $gateway ) {
					?>
					<li class="woocommerce-PaymentMethod woocommerce-PaymentMethod--<?php echo esc_attr( $gateway->id ); ?> payment_method_<?php echo esc_attr( $gateway->id ); ?> list-group-item py-3">
						<div class="form-check">
							<input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="form-check-input" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> />
							<label class="form-check-label w-100 d-flex justify-content-between align-items-center fw-medium" for="payment_method_<?php echo esc_attr( $gateway->id ); ?>">
								<?php echo wp_kses_post( $gateway->get_title() ); ?> 
								<span class="ms-auto"><?php echo wp_kses_post( $gateway->get_icon() ); ?></span>
							</label>
						</div>
						
						<?php
						if ( $gateway->has_fields() || $gateway->get_description() ) {
							echo '<div class="woocommerce-PaymentBox woocommerce-PaymentBox--' . esc_attr( $gateway->id ) . ' payment_box payment_method_' . esc_attr( $gateway->id ) . ' mt-3 p-3 bg-light rounded text-muted small" style="display: none;">';
							$gateway->payment_fields();
							echo '</div>';
						}
						?>
					</li>
					<?php
				}
				?>
			</ul>

			<?php do_action( 'woocommerce_add_payment_method_form_bottom' ); ?>

			<div class="d-flex justify-content-start">
				<?php wp_nonce_field( 'woocommerce-add-payment-method', 'woocommerce-add-payment-method-nonce' ); ?>
				<button type="submit" class="btn btn-primary rounded-pill px-4 py-2" id="place_order" value="<?php esc_attr_e( 'Add payment method', 'woocommerce' ); ?>">
					<?php esc_html_e( 'Add payment method', 'woocommerce' ); ?>
				</button>
				<input type="hidden" name="woocommerce_add_payment_method" id="woocommerce_add_payment_method" value="1" />
			</div>
		</div>
	</form>
<?php else : ?>
	<div class="alert alert-info" role="alert">
		<?php echo esc_html__( 'New payment methods can only be added during checkout. Please contact us if you require assistance.', 'woocommerce' ); ?>
	</div>
<?php endif; ?>