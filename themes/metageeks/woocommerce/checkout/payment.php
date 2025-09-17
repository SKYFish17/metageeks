<?php
/**
 * Checkout Payment Section
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 1000
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_ajax() ) {
	do_action( 'woocommerce_review_order_before_payment' );
}
?>
<div id="payment" class="woocommerce-checkout-payment">


  <div class="woocommerce-additional-fields">
    <?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

		<?php if ( WC()->cart->get_coupons() ): ?>
			<div class="delete-coupons">
				<div class="delete-coupons__title"><?php esc_html_e( 'Applied discount:', 'woocommerce' ); ?></div>
				<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
					<div class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
						<div>
							<?php echo esc_html( sanitize_title( $code ) ); ?>
							<a href="<?php echo home_url( '/checkout/?remove_coupon=' ) ?><?php echo esc_attr( sanitize_title( $code ) ); ?>" data-coupon="<?php echo esc_attr( sanitize_title( $code ) ); ?>">
								<?php
								bda_display_svg(
									[
										'icon'   => 'remove',
										'width'  => '20',
										'height' => '20',
									]
								);
								?>
							</a>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

    <?php if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) : ?>

      <?php if ( ! WC()->cart->needs_shipping() || wc_ship_to_billing_address_only() ) : ?>

        <h3><?php esc_html_e( 'Additional information', 'woocommerce' ); ?></h3>

      <?php endif; ?>

      <div class="woocommerce-additional-fields__field-wrapper">
        <?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
          <?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
        <?php endforeach; ?>
      </div>

    <?php endif; ?>

    <?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
  </div>
	<div class="form-row place-order">
		<noscript>
			<?php
			/* translators: $1 and $2 opening and closing emphasis tags respectively */
			printf( esc_html__( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ), '<em>', '</em>' );
			?>
			<br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>"><?php esc_html_e( 'Update totals', 'woocommerce' ); ?></button>
		</noscript>

		<?php wc_get_template( 'checkout/terms.php' ); ?>

		<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

		<?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ); // @codingStandardsIgnoreLine ?>

		<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

		<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
	</div>
</div>
<?php
if ( ! is_ajax() ) {
	do_action( 'woocommerce_review_order_after_payment' );
}
