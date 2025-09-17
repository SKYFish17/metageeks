<?php
/**
 * Login form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( is_user_logged_in() ) {
	return;
}

$register_link = add_query_arg( array( 'state' => 'register' ), get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) );
?>

<div class="blackout">
  <form class="woocommerce-form woocommerce-form-login login" method="post" <?php echo ( $hidden ) ? 'style="display:none;"' : ''; ?>>

  	<?php do_action( 'woocommerce_login_form_start' ); ?>

		<button class="modal-close" type="button"></button>

  	<h3><?php esc_html_e( 'Login to my account', 'woocommerce' ); ?></h3>

		<div class="social-login">
			<div class="social-login__title"><?php esc_html_e( 'Login with:', 'woocommerce' ); ?></div>
			<div class="social-login__place-for-social-login"></div>
		</div>

  	<p class="form-row form-row-first">
  		<label for="username"><?php esc_html_e( 'Email', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
  		<input type="text" class="input-text" name="username" id="username" autocomplete="username" />
  	</p>
  	<p class="form-row form-row-last">
  		<label for="password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
  		<input class="input-text" type="password" name="password" id="password" autocomplete="current-password" />
			<span class="password-switch"></span>
  	</p>
  	<div class="clear"></div>

  	<?php do_action( 'woocommerce_login_form' ); ?>

  	<p class="form-row form-forgot">
  		<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
  			<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
  		</label>
      <a class="forgot" href="<?php echo esc_url( wp_lostpassword_url() ); ?>" target="_blank"><?php esc_html_e( 'Forgot password?', 'woocommerce' ); ?></a>
		</p>

		<p class="form-row">
  		<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
  		<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect ); ?>" />
  		<button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login" value="<?php esc_attr_e( 'Login', 'woocommerce' ); ?>"><?php esc_html_e( 'Login', 'woocommerce' ); ?></button>
  	</p>

    <p class="woocommerce-form-register">New Customer? <a href="<?php echo esc_url( $register_link ); ?>" target="_blank">Register</a></p>

  	<div class="clear"></div>

  	<?php do_action( 'woocommerce_login_form_end' ); ?>

  </form>
</div>
