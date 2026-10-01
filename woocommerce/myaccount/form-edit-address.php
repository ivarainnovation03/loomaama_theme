<?php
/**
 * Edit address form
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

defined( 'ABSPATH' ) || exit;

$page_title = ( 'billing' === $load_address ) ? esc_html__( 'Billing address', 'woocommerce' ) : esc_html__( 'Shipping address', 'woocommerce' );

do_action( 'woocommerce_before_edit_account_address_form' ); ?>

<style>
	/* Force WooCommerce dynamic fields to look like Bootstrap 5.3 form-controls */
	.woocommerce-address-fields .form-row {
		margin-bottom: 1rem;
	}
	.woocommerce-address-fields label {
		display: inline-block;
		margin-bottom: 0.5rem;
		font-weight: 600;
	}
	.woocommerce-address-fields .input-text,
	.woocommerce-address-fields select,
	.woocommerce-address-fields textarea {
		display: block;
		width: 100%;
		padding: 0.375rem 0.75rem;
		font-size: 1rem;
		font-weight: 400;
		line-height: 1.5;
		color: var(--bs-body-color);
		background-color: var(--bs-body-bg);
		background-clip: padding-box;
		border: var(--bs-border-width) solid var(--bs-border-color);
		border-radius: var(--bs-border-radius);
		transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
	}
	.woocommerce-address-fields .input-text:focus,
	.woocommerce-address-fields select:focus,
	.woocommerce-address-fields textarea:focus {
		color: var(--bs-body-color);
		background-color: var(--bs-body-bg);
		border-color: #86b7fe;
		outline: 0;
		box-shadow: 0 0 0 .25rem rgba(13,110,253,.25);
	}
	.select2-container--default .select2-selection--single {
		border: var(--bs-border-width) solid var(--bs-border-color) !important;
		border-radius: var(--bs-border-radius) !important;
		height: calc(1.5em + 0.75rem + 2px) !important;
		padding: 0.375rem 0.75rem;
	}
	.select2-container--default .select2-selection--single .select2-selection__arrow {
		top: 50% !important;
		transform: translateY(-50%) !important;
	}
</style>

<?php if ( ! $load_address ) : ?>
	<?php wc_get_template( 'myaccount/my-address.php' ); ?>
<?php else : ?>

	<form method="post" novalidate class="card shadow-sm border-0 mb-4">
		<div class="card-body p-4 p-md-5">
			
			<div class="d-flex align-items-center mb-4 pb-3 border-bottom">
				<i class="fas <?php echo ( 'billing' === $load_address ) ? 'fa-file-invoice-dollar' : 'fa-truck'; ?> text-primary fs-4 me-3"></i>
				<h2 class="h4 mb-0 text-dark fw-bold">
					<?php echo apply_filters( 'woocommerce_my_account_edit_address_title', $page_title, $load_address ); ?>
				</h2>
			</div>

			<div class="woocommerce-address-fields">
				<?php do_action( "woocommerce_before_edit_address_form_{$load_address}" ); ?>

				<div class="woocommerce-address-fields__field-wrapper row g-3">
					<?php
					foreach ( $address as $key => $field ) {
						
						// 1. Inject Bootstrap form-control / form-select classes into the inputs
						if ( ! isset( $field['input_class'] ) ) {
							$field['input_class'] = array();
						}
						if ( isset($field['type']) && $field['type'] === 'select' ) {
							$field['input_class'][] = 'form-select';
						} else {
							$field['input_class'][] = 'form-control';
						}

						// 2. Map WooCommerce layout classes to the Bootstrap 5 Grid
						if ( ! isset( $field['class'] ) ) {
							$field['class'] = array();
						}
						
						$is_half_width = false;
						foreach ( $field['class'] as $index => $class_name ) {
							if ( in_array( $class_name, array('form-row-first', 'form-row-last') ) ) {
								$is_half_width = true;
								unset( $field['class'][$index] ); // Strip Woo class
							} elseif ( $class_name === 'form-row-wide' ) {
								unset( $field['class'][$index] ); // Strip Woo class
							}
						}

						// Apply Bootstrap column sizing
						if ( $is_half_width ) {
							$field['class'][] = 'col-md-6';
						} else {
							$field['class'][] = 'col-12';
						}

						// Strip the default 'form-row' wrapper so it doesn't break Bootstrap's flexbox grid
						$field['class'] = array_diff($field['class'], array('form-row'));

						// 3. Style the labels
						if ( ! isset( $field['label_class'] ) ) {
							$field['label_class'] = array();
						}
						$field['label_class'][] = 'form-label fw-semibold text-secondary mb-1';

						// Render the customized field
						woocommerce_form_field( $key, $field, wc_get_post_data_by_key( $key, $field['value'] ) );
					}
					?>
				</div>

				<?php do_action( "woocommerce_after_edit_address_form_{$load_address}" ); ?>

				<div class="mt-4 pt-3 border-top d-flex justify-content-end">
					<?php wp_nonce_field( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce' ); ?>
					<input type="hidden" name="action" value="edit_address" />
					<button type="submit" class="btn btn-outline-dark rounded-pill px-5 py-2 fw-medium shadow-sm d-flex align-items-center gap-2" name="save_address" value="<?php esc_attr_e( 'Save address', 'woocommerce' ); ?>">
						<i class="fas fa-save"></i> <?php esc_html_e( 'Save address', 'woocommerce' ); ?>
					</button>
				</div>
			</div>
		</div>
	</form>

<?php endif; ?>

<?php do_action( 'woocommerce_after_edit_account_address_form' ); ?>