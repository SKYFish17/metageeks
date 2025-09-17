<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-order">

	<?php
	if ( $order ) :

		do_action( 'woocommerce_before_thankyou', $order->get_id() );
		?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
				<?php endif; ?>
			</p>

		<?php else : ?>

			<h1 class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you!', 'woocommerce' ), $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h1>
      <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Your order has been received.', 'woocommerce' ), $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

			<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

				<li class="woocommerce-order-overview__order order">
					<?php esc_html_e( 'Order No.', 'woocommerce' ); ?>
					<?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</li>

				<li class="woocommerce-order-overview__date date">
					<?php echo wc_format_datetime( $order->get_date_created(), 'j/n/Y' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</li>

			</ul>

		<?php endif; ?>

		<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
		<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>


	<?php else : ?>

    <h1 class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you!', 'woocommerce' ), $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h1>
    <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Your order has been received.', 'woocommerce' ), $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

	<?php endif; ?>

  <div id="payment-block">
    <?php if ( $order->get_payment_method_title() ) : ?>
      <h3><?php esc_html_e( 'Payment info', 'woocommerce' ); ?></h3>
      <?php echo wp_kses_post( $order->get_payment_method_title() ); ?>
    <?php endif; ?>
  </div>

  <div id="addresses-block">
    <div class="left">
      <h3><?php esc_html_e( 'BILLING INFO', 'woocommerce' ); ?></h3>
      <ul>
        <li><?php echo wp_kses_post( $order->get_billing_first_name() ); ?> <?php echo wp_kses_post( $order->get_billing_last_name() ); ?></li>
        <li><?php echo wp_kses_post( $order->get_billing_address_1() ); ?>,<br> <?php echo wp_kses_post( $order->get_billing_address_2() ); ?></li>
        <li><?php echo wp_kses_post( $order->get_billing_country() ); ?></li>
        <li><?php echo wp_kses_post( $order->get_billing_city() ); ?></li>
        <li><?php echo wp_kses_post( $order->get_billing_postcode() ); ?></li>
        <li><a href="tel:<?php echo wp_kses_post( $order->get_billing_phone() ); ?>"><?php echo wp_kses_post( $order->get_billing_phone() ); ?></a></li>
        <li><a href="mailto:<?php echo wp_kses_post( $order->get_billing_email() ); ?>"><?php echo wp_kses_post( $order->get_billing_email() ); ?></a></li>
      </ul>
    </div>
    <div class="right">
      <h3><?php esc_html_e( 'SHIPPING ADDRESS', 'woocommerce' ); ?></h3>
      <ul>
        <li><?php echo wp_kses_post( $order->get_shipping_first_name() ); ?> <?php echo wp_kses_post( $order->get_shipping_last_name() ); ?></li>
        <li><?php echo wp_kses_post( $order->get_shipping_address_1() ); ?>,<br> <?php echo wp_kses_post( $order->get_shipping_address_2() ); ?></li>
        <li><?php echo wp_kses_post( $order->get_shipping_country() ); ?></li>
        <li><?php echo wp_kses_post( $order->get_shipping_city() ); ?></li>
        <li><?php echo wp_kses_post( $order->get_shipping_postcode() ); ?></li>
      </ul>
    </div>
  </div>

  <a class="track-my-order" href="#">Track my order</a>

</div>
