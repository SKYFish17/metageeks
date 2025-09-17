<?php
/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-lost-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.2
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_lost_password_form' );
?>

<div class="metageeks-login">

	<div class="metageeks-login__wrapper">

		<div class="metageeks-login__logo">
			<?php the_custom_logo(); ?>
		</div>

		<div class="metageeks-login__form">
			<div class="metageeks-login__form-title"><?php esc_html_e( 'Forgot password?', 'woocommerce' ); ?></div>

			<div class="metageeks-login__form-subtitle">No worries, we’ll send you reset instructions.</div>

			<div class="metageeks-login__form-wrapper">
				<form method="post" class="woocommerce-ResetPassword lost_reset_password">

					<p class="mg-form__field woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
						<label for="user_login"><?php esc_html_e( 'Username or email', 'woocommerce' ); ?></label>
						<input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login" id="user_login" autocomplete="username" />
					</p>

					<div class="clear"></div>

					<?php do_action( 'woocommerce_lostpassword_form' ); ?>

					<p class="woocommerce-form-row form-row">
						<input type="hidden" name="wc_reset_password" value="true" />
						<button type="submit" class="mg-form__button woocommerce-Button button" value="<?php esc_attr_e( 'Reset password', 'woocommerce' ); ?>"><?php esc_html_e( 'Reset password', 'woocommerce' ); ?></button>
					</p>

					<?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>

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

</div>
<?php
do_action( 'woocommerce_after_lost_password_form' );
