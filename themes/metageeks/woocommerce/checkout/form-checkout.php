<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 1000
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="cart-notices">
	<?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>
</div>

<?php
// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}
?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<div class="woocommerce_checkout_wrapper_customer_details">

		<div class="express-checkout">
		  <h3 class="express-checkout__title"><?php esc_html_e( 'Express checkout', 'woocommerce' ); ?></h3>
		  <div class="express-checkout__buttons">
		    <?php do_action( 'metageeks_express_payments_in_checkout' ) ?>
		  </div>
		</div>

		<?php if ( $checkout->get_checkout_fields() ) : ?>

			<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

			<div class="col2-set" id="customer_details">
				<div class="col-1">
					<?php do_action( 'woocommerce_checkout_billing' ); ?>
				</div>

				<div class="col-2">
					<?php do_action( 'woocommerce_checkout_shipping' ); ?>
				</div>
			</div>

			<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

		<?php endif; ?>

		<div class="woocommerce_checkout_shipping_method">

			<h3><?php esc_html_e( 'Shipping Method', 'woocommerce' ); ?></h3>

			<div id="wrapper-shipping_method">
				<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

					<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
					<div class="my-custom-shipping-list">
						<?php wc_cart_totals_shipping_html(); ?>
					</div>
					<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

				<?php endif; ?>
			</div>

		</div>

		<div class="woocommerce_checkout_card">

			<div class="wrapper-title">
				<h3><?php esc_html_e( 'Payment Methods', 'woocommerce' ); ?></h3>
			</div>
			<?php if ( WC()->cart->needs_payment() ) : ?>
				<?php
					$WC_Payment_Gateways = new WC_Payment_Gateways();
					$available_gateways = $WC_Payment_Gateways->get_available_payment_gateways(); ?>
				<ul class="wc_payment_methods payment_methods methods">
					<?php
					if ( ! empty( $available_gateways ) ) {
						foreach ( $available_gateways as $gateway ) {
							wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
						}
					} else {
						echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ) . '</li>'; // @codingStandardsIgnoreLine
					}
					?>
				</ul>
			<?php endif; ?>

		</div>

	</div>

	<div class="woocommerce_checkout_wrapper_review">

		<div class="woocommerce_checkout_wrapper_review__items">

			<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
			<div class="woocommerce_checkout_wrapper_review__header active">
				<h3 id="order_review_heading"><?php esc_html_e( 'Order Review', 'woocommerce' ); ?></h3>
				<?php
					bda_display_svg(
						[
							'icon'   => 'arrow-down-checkout',
							'width'  => '11',
							'height' => '11',
						]
					);
				?>
				<div class="count-in-checkount">
					<span><?php echo WC()->cart->get_cart_contents_count(); ?></span> items in card
				</div>
			</div>

			<div class="woocommerce_checkout_wrapper_review__contents">
				<?php
				do_action( 'woocommerce_review_order_before_cart_contents' );

				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

					$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

						$id = $cart_item['data']->get_id();
				    $product = wc_get_product( $id );
				    $is_variation = $product->get_type() === 'variation' ? true : false;

				    if ( $is_variation ) {
				      $product_max_qty = get_post_meta( $id, 'variation_maximum_allowed_quantity', true ) ? get_post_meta( $id, 'variation_maximum_allowed_quantity', true ) : '';
				      $product_min_qty = get_post_meta( $id, 'variation_minimum_allowed_quantity', true ) ? get_post_meta( $id, 'variation_minimum_allowed_quantity', true ) : 1;
				    }
				    else {
				      $product_max_qty  = $cart_item['data']->get_meta('maximum_allowed_quantity') ? $cart_item['data']->get_meta('maximum_allowed_quantity') : '';
				      $product_min_qty  = $cart_item['data']->get_meta('minimum_allowed_quantity') ? $cart_item['data']->get_meta('minimum_allowed_quantity') : 1 ;
				    }
						$product_stock = $_product->get_stock_quantity();
						if ( ! empty( $product_stock ) &&  empty( $product_max_qty ) ) {
				      $product_max_qty = $product_stock;
				    }
				    elseif ( ! empty( $product_stock ) &&  ! empty( $product_max_qty ) ) {
				      if ( $product_stock < $product_max_qty ) {
				        $product_max_qty = $product_stock;
				      }
				    }
				    $product_quantity = $cart_item['quantity'];
				    $product_availability = $cart_item[ 'data' ]->get_availability();
					?>
						<div class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>" <?php echo $product_max_qty ? 'data-product-max-qty="'.$product_max_qty.'"' : '' ?>>
							<a href="<?php echo $_product->get_permalink() ?>" class="product-image">
								<?php $thumb = get_the_post_thumbnail_url( $_product->get_id(), 'shop_catalog' ); ?>
								<?php if ( ! $thumb ): ?>
									<?php $thumb = wc_placeholder_img_src( 'thumbnail' ); ?>
								<?php endif; ?>
								<img class="image" src="<?php echo "{$thumb}"; ?>" alt="product-image">
							</a>
							<div class="product-name">
								<a href="<?php echo $_product->get_permalink() ?>">
				          <?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) . '&nbsp;'; ?>
				          <?php //echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times;&nbsp;%s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				        </a>
								<div class="count-controls">
									<div class="controls">
										<button type="button" class="minus" data-product_id="<?php echo esc_attr( $_product->get_id() ); ?>"></button>
										<input
											class="count"
											type="text"
											min="<?php echo $product_min_qty ?>"
											max="<?php echo $product_max_qty ?>"
											value="<?php echo $product_quantity; ?>"
											name="basket-item-count"
											readonly
										>
										<button type="button" class="plus" data-product_id="<?php echo esc_attr( $_product->get_id() ); ?>">
											<?php if ( $product_max_qty ): ?>
												<span class="popup-max-count">This item is limited to <?php echo $product_max_qty ?> per customer.</span>
											<?php endif; ?>
										</button>
									</div>
								</div>
							</div>
							<div class="product-total">
								<?php
									echo apply_filters(
										'woocommerce_cart_item_remove_link',
										sprintf(
											'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"></a>',
											esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
											esc_html__( 'Remove this item', 'woocommerce' ),
											esc_attr( $_product->get_id() ),
											esc_attr( $_product->get_sku() )
										),
										$cart_item_key
									);
								?>
								<div class="product-total-wrapper-price">
									<?php if ( $_product->is_on_sale() ): ?>
										<?php
											$regular_price = $_product->get_regular_price();
											if ( $product_quantity > 1 ) {
												$regular_price = $regular_price * $product_quantity;
											}
										 ?>
										<del class="product-total-wrapper-price__regular"><?php echo get_woocommerce_currency_symbol().$regular_price; ?></del>
									<?php endif; ?>
									<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</div>
							</div>
						</div>
						<?php
					}
				}

				do_action( 'woocommerce_review_order_after_cart_contents' );
				?>
			</div>

		</div>

		<div id="order_review" class="woocommerce-checkout-review-order">

			<h3><?php esc_html_e( 'Billing Summary', 'woocommerce' ); ?></h3>

			<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

			<?php do_action( 'woocommerce_checkout_order_review' ); ?>

			<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

		</div>

	</div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
