<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.3.6
 */

defined( 'ABSPATH' ) || exit;

use Automattic\Jetpack\Constants;

global $woocommerce;
$free_shipping_order_cost = get_field( 'shipping_terms', 'options' )['free_shipping'];
$cart_total = $woocommerce->cart->subtotal_ex_tax;

?>
<div class="cart-page-totals cart_totals <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">

	<?php if ( $cart_total < $free_shipping_order_cost ) : ?>
			<div class="cart-page-totals__badge badge badge_feature">
				<span class="badge__status">Free Shipping</span>
				<p class="badge__text">
					Spend
					<span class="badge__value"><?php printf ( '%.2f', $free_shipping_order_cost - $cart_total ) ?></span>
					more and get free UK shipping!
				</p>
			</div>
		<?php endif; ?>

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<div class="shop_table cart-page-totals__table">

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

		<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

		<?php wc_cart_totals_shipping_html(); ?>

		<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

		<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>

		<div class="shipping cart-page-totals__shipping">
			<span><?php esc_html_e( 'Delivery option', 'woocommerce' ); ?></span>
			<div data-title="<?php esc_attr_e( 'Delivery option', 'woocommerce' ); ?>"><?php woocommerce_shipping_calculator(); ?></div>
		</div>

		<?php endif; ?>

		<div class="cart-page-totals__value-wrapper">
			<div class="cart-subtotal cart-page-totals__subtotal summary-text-row">
				<span><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></span>
				<div data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></div>
			</div>

			<div class="cart-page-totals__delivery-costs summary-text-row">
				<span><?php esc_html_e( 'Delivery', 'woocommerce' ); ?></span>
				<div data-title="<?php esc_attr_e( 'Delivery costs', 'woocommerce' ); ?>">
				<?php
				$packages = WC()->shipping()->get_packages();
				if ( !empty( $packages ) ) {
					foreach ( $packages as $i => $package ) {
						$chosen_method = isset( WC()->session->chosen_shipping_methods[ $i ] ) ? WC()->session->chosen_shipping_methods[ $i ] : '';

						if ( isset ( $package['rates'][$chosen_method] ) ) {
							print_r( wc_price( $package['rates'][$chosen_method]->cost ) );
						}
					}
				}
				?>
				</div>
			</div>

			<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
				<div class="cart-discount cart-page-totals__discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?> summary-text-row">
					<span>Discount</span>
					<?php
					$amount               = WC()->cart->get_coupon_discount_amount( $coupon->get_code(), WC()->cart->display_cart_ex_tax );
					$discount_amount_html = '-' . wc_price( $amount );
					?>
					<div data-title="Discount"><?php echo $discount_amount_html; ?></div>
				</div>
			<?php endforeach; ?>

			<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
				<div class="fee cart-page-totals__fee cart-page-totals__discount summary-text-row">
					<span><?php echo esc_html( $fee->name ); ?></span>
					<div data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>

		<?php
		if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
			$taxable_address = WC()->customer->get_taxable_address();
			$estimated_text  = '';

			if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
				/* translators: %s location. */
				$estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
			}

			if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
				foreach ( WC()->cart->get_tax_totals() as $code => $tax ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
					?>
					<div class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>cart-page-totals__tax-rate">
						<span><?php echo esc_html( $tax->label ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<div data-title="<?php echo esc_attr( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></div>
					</div>
					<?php
				}
			} else {
				?>
				<div class="tax-total cart-page-totals__tax-total">
					<span><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<div data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></div>
				</div>
				<?php
			}
		}
		?>

		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

		<div class="order-total cart-page-totals__order-total">
			<span><?php esc_html_e( 'Grand total', 'woocommerce' ); ?></span>
			<div data-title="<?php esc_attr_e( 'Grand total', 'woocommerce' ); ?>"><?php wc_cart_totals_order_total_html(); ?></div>
		</div>

		<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<div class="cart-discount cart-page-totals__discount-code coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?> summary-text-row">
				<p class="cart-page-totals__discount-text">
					<span>Applied discount:</span>
					<span class="cart-page-totals__discount-code-text"><?php echo $code; ?></span>
				</p>
				<div data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>">
					<?php
					if ( is_string( $coupon ) ) {
						$coupon = new WC_Coupon( $coupon );
					}

					$icon = bda_get_svg(
						[
							'icon'   => 'remove',
							'width'  => '20',
							'height' => '20',
						]
					);

					$coupon_html          = ' <a href="' . esc_url( add_query_arg( 'remove_coupon', rawurlencode( $coupon->get_code() ), Constants::is_defined( 'WOOCOMMERCE_CHECKOUT' ) ? wc_get_checkout_url() : wc_get_cart_url() ) ) . '" class="woocommerce-remove-coupon" data-coupon="' . esc_attr( $coupon->get_code() ) . '">' . $icon . '</a>';

					echo $coupon_html;
					?>
				</div>
			</div>
		<?php endforeach; ?>

		<p class="cart-page-totals__tax">Tax included. <b>Shipping</b> calculated at checkout.</p>

	</div>

	<div class="wc-proceed-to-checkout ca">
		<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
	</div>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>
