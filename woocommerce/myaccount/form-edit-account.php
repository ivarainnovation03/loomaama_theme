<?php
/**
 * Edit account form
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' );
?>

<form class="woocommerce-EditAccountForm edit-account card shadow-sm border-0 mb-4" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >
	
	<div class="card-body p-4 p-md-5">
		<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

		<div class="row mb-3">
			<div class="col-md-6 mb-3 mb-md-0">
				<label for="account_first_name" class="form-label fw-semibold"><?php esc_html_e( 'First name', 'woocommerce' ); ?>&nbsp;<span class="text-danger" aria-hidden="true">*</span></label>
				<input type="text" class="form-control" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" aria-required="true" />
			</div>
			<div class="col-md-6">
				<label for="account_last_name" class="form-label fw-semibold"><?php esc_html_e( 'Last name', 'woocommerce' ); ?>&nbsp;<span class="text-danger" aria-hidden="true">*</span></label>
				<input type="text" class="form-control" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" aria-required="true" />
			</div>
		</div>

		<div class="mb-3">
			<label for="account_display_name" class="form-label fw-semibold"><?php esc_html_e( 'Display name', 'woocommerce' ); ?>&nbsp;<span class="text-danger" aria-hidden="true">*</span></label>
			<input type="text" class="form-control" name="account_display_name" id="account_display_name" aria-describedby="account_display_name_description" value="<?php echo esc_attr( $user->display_name ); ?>" aria-required="true" />
			<div id="account_display_name_description" class="form-text">
				<?php esc_html_e( 'This will be how your name will be displayed in the account section and in reviews', 'woocommerce' ); ?>
			</div>
		</div>

		<div class="mb-4">
			<label for="account_email" class="form-label fw-semibold"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="text-danger" aria-hidden="true">*</span></label>
			<input type="email" class="form-control" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" aria-required="true" />
		</div>

		<?php do_action( 'woocommerce_edit_account_form_fields' ); ?>

		<div class="card bg-light border-0 mt-5 mb-4">
			<div class="card-body p-4">
				<h5 class="card-title fw-bold mb-4"><?php esc_html_e( 'Password change', 'woocommerce' ); ?></h5>

				<div class="mb-3">
					<label for="password_current" class="form-label text-muted"><?php esc_html_e( 'Current password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
					<input type="password" class="form-control" name="password_current" id="password_current" autocomplete="current-password" />
				</div>
				<div class="mb-3">
					<label for="password_1" class="form-label text-muted"><?php esc_html_e( 'New password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
					<input type="password" class="form-control" name="password_1" id="password_1" autocomplete="new-password" />
				</div>
				<div class="mb-0">
					<label for="password_2" class="form-label text-muted"><?php esc_html_e( 'Confirm new password', 'woocommerce' ); ?></label>
					<input type="password" class="form-control" name="password_2" id="password_2" autocomplete="new-password" />
				</div>
			</div>
		</div>
		<!-- Additional fields can be added here using the 'woocommerce_edit_account_form_fields' action hook -->
		<?php do_action( 'woocommerce_edit_account_form' ); ?>

		<div class="mt-4">
			<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
			<button type="submit" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-medium" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>">
				<?php esc_html_e( 'Save changes', 'woocommerce' ); ?>
			</button>
			<input type="hidden" name="action" value="save_account_details" />
		</div>

		<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
	</div>
</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>