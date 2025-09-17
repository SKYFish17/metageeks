<?php
namespace Yay_Currency;

global $woocommerce;

$cart_subtotal = $woocommerce->cart->get_cart_subtotal();
$cart_contents_count = $woocommerce->cart->get_cart_contents_count();
$free_shipping_order_cost = get_field( 'shipping_terms', 'options' )['free_shipping'];
$free_next_day_shipping_order_cost = get_field( 'shipping_terms', 'options' )['free_next_day_shipping'];
$currency_ID = sanitize_key( $_COOKIE['yay_currency_widget'] );

$currency = null;
if ( class_exists( 'Yay_Currency\WooCommerceCurrency' ) ) {
	$wooCurrencyInstance = new WooCommerceCurrency();
	$currency = $wooCurrencyInstance->get_currency_by_ID( $currency_ID );
}

$woo_currency = get_woocommerce_currency();
$yay_currency = $currency['currency'];

if ( $woo_currency === $yay_currency ) {
	$cart_subtotal = $woocommerce->cart->get_cart_subtotal();
	$raw_subtotal = filter_var( wp_kses_data( $cart_subtotal ), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
} else {
	$cart_subtotal = $woocommerce->cart->get_cart_subtotal();
	$raw_subtotal = filter_var( wp_kses_data( $cart_subtotal ), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
	$cart_subtotal_converted = $currency['symbol'] . number_format( (float) ($raw_subtotal * $currency['rate']), 2, '.', '' );
	$cart_subtotal = $cart_subtotal_converted;
}

$is_free_shipping = $raw_subtotal >= intval( $free_shipping_order_cost );
$is_free_next_day_shipping = $raw_subtotal >= intval( $free_next_day_shipping_order_cost );
?>
<div class="bottom__top">
	<div class="subtotal">
		<div class="subtotal__wrapper">
			<h3 class="title">Subtotal:</h3>
			<p class="total-price">
				<?php echo wp_kses_data( $cart_subtotal ); ?>
			</p>
		</div>
	</div>

	<?php if ( $currency && $currency['currency'] === 'GBP' ) : ?>
		<?php if ( $is_free_shipping ) : ?>
			<div class="calculator-badges">
				<p class="calculator-badges__item">
					<?php
					bda_display_svg(
						[
							'icon'   => 'check',
							'width'  => '24',
							'height' => '24',
						]
					);
					?>
					Free shipping
				</p>

				<?php if ( $is_free_next_day_shipping ) : ?>
					<p class="calculator-badges__item">
						<?php
						bda_display_svg(
							[
								'icon'   => 'check',
								'width'  => '24',
								'height' => '24',
							]
						);
						?>
						Next day delivery
					</p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</div>

<?php if ( $currency && $currency['currency'] === 'GBP' ) : ?>
	<div class="free-shipping-calculator">

		<?php if ( !$is_free_shipping ) : ?>
			<p class="first-step text">Spend <span class="value"><?php echo wc_price( intval( $free_shipping_order_cost ) - $raw_subtotal ); ?></span> more and get <b>free UK shipping</b>!</p>
		<?php elseif ( $is_free_shipping && !$is_free_next_day_shipping ) : ?>
			<p class="first-step text no-margin">You've qualified for <b>free UK shipping</b>!</p>
			<p class="second-step text">Spend <span class="value"><?php echo wc_price( intval( $free_next_day_shipping_order_cost ) - $raw_subtotal ); ?></span> more for <b>next day delivery</b>!</p>
		<?php else : ?>
			<p class="text">You've qualified for <b>free shipping</b> & <b>next day delivery</b>!</p>
		<?php endif; ?>

		<div class="status-bar">
			<?php
			if ( !$is_free_shipping ) {
				$progress = ( $raw_subtotal > 0 ) ? ( $raw_subtotal / intval( $free_shipping_order_cost ) * 100 ) : 0;
			} else {
				$progress = ( $raw_subtotal > 0 ) ? ( $raw_subtotal / intval( $free_next_day_shipping_order_cost ) * 100 ) : 0;
			}

			$progress_class = '';
			if ( $progress <= 0  ) {
				$progress = 0;
				$progress_class = 'start';
			} elseif ( $progress >= 100 ) {
				$progress = 100;
				$progress_class = 'finish';
			}
			?>
			<div class="status-bar__track" style="width: <?php echo $progress .'%'; ?>"></div>
			<span class="status-bar__progress <?php echo $progress_class; ?>" style="left: <?php echo $progress .'%'; ?>"><?php echo $progress .'%'; ?></span>
		</div>
	</div>
<?php endif; ?>

<div class="buttons">
	<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="btn btn-view-basket" target="_self">View  Basket</a>
	<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="btn btn-red btn-checkout" target="_self">Checkout</a>
</div>
