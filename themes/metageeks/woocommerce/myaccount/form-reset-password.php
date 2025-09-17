<?php
/**
 * Lost password reset form.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-reset-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.5
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_reset_password_form' );
?>

<div class="metageeks-login">

<div class="metageeks-login__wrapper">

	<div class="metageeks-login__logo">
		<?php the_custom_logo(); ?>
	</div>

	<div class="metageeks-login__form">
		<div class="metageeks-login__form-title"><?php esc_html_e( 'Set new password', 'woocommerce' ); ?></div>

		<div class="metageeks-login__form-subtitle">Your new password must be different to previously used passwords.</div>

		<div class="metageeks-login__form-wrapper">

			<form method="post" class="woocommerce-ResetPassword lost_reset_password">

				<p class="mg-form__field woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
					<label for="password_1"><?php esc_html_e( 'New password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_1" id="password_1" autocomplete="new-password" />
				</p>

				<p class="mg-form__field woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
					<label for="password_2"><?php esc_html_e( 'Re-enter new password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_2" id="password_2" autocomplete="new-password" />
				</p>

				<input type="hidden" name="reset_key" value="<?php echo esc_attr( $args['key'] ); ?>" />
				<input type="hidden" name="reset_login" value="<?php echo esc_attr( $args['login'] ); ?>" />

				<div class="clear"></div>

				<?php do_action( 'woocommerce_resetpassword_form' ); ?>

				<p class="woocommerce-form-row form-row">
					<input type="hidden" name="wc_reset_password" value="true" />
					<button type="submit" class="mg-form__button woocommerce-Button button" value="<?php esc_attr_e( 'Reset password', 'woocommerce' ); ?>"><?php esc_html_e( 'Reset password', 'woocommerce' ); ?></button>
				</p>

				<?php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ); ?>

			</form>

		</div>

		<a class="metageeks-login__form-back" href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="Back to Log In">
			<?php
			bda_display_svg(
				array(
					'icon'   => 'arrow-left',
					'width'  => '20',
					'height' => '20',
				)
			);
			?>
			Back to Log In
		</a>

	</div>

</div>
<?php
do_action( 'woocommerce_after_reset_password_form' );

