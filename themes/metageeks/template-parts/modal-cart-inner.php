<?php
/**
 * Template part for displaying modal-cart inner layout.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package MetaGeeks
 */
namespace Yay_Currency;
?>

<?php
global $woocommerce;
$cart_subtotal = $woocommerce->cart->get_cart_subtotal();
$cart_contents_count = $woocommerce->cart->get_cart_contents_count();
$is_empty_cart = $woocommerce->cart->is_empty();

$currency_ID = sanitize_key( $_COOKIE['yay_currency_widget'] );

$currency = null;
if ( class_exists( 'Yay_Currency\WooCommerceCurrency' ) ) {
	$wooCurrencyInstance = new WooCommerceCurrency();
	$currency = $wooCurrencyInstance->get_currency_by_ID( $currency_ID );
}

$woo_currency = get_woocommerce_currency();
$yay_currency = $currency['currency'];
?>

<div class="modal-inner <?php echo $is_empty_cart ? 'empty-cart' : ''; ?>">
	<div class="top">
		<div class="notification">
			<div class="icon-wrapper">
				<?php
				bda_display_svg(
					[
						'icon'   => 'modal-basket-check',
						'width'  => '24',
						'height' => '24',
					]
				);
				?>
			</div>
			<span class="text">PRODUCT ADDED TO CART</span>
		</div>
		<header>
			<h2 class="title">
				<span class="count"><?php echo wp_kses_data( $cart_contents_count ); ?></span>
				items in basket
			</h2>
			<button type="button"
				class="btn btn-clear"
				title="<?php echo esc_attr( 'Clear Basket', 'woocommerce' ) ?>">
				<?php echo esc_html( 'Clear Basket', 'woocommerce' ) ?>
			</button>
		</header>
		<?php
		$cart_items = $woocommerce->cart->get_cart();

		if ( count( $cart_items ) ) : ?>
			<div class="products-list" data-simplebar data-simplebar-auto-hide="false">
				<?php foreach ( $cart_items as $key => $values ) :
					$id = $values['data']->get_id();
					$product = wc_get_product( $id );
					$product_max_qty = 0;

					$is_variation = $product->get_type() === 'variation' ? true : false;

					if ( $is_variation ) {
						$parent_id = $product->get_parent_id();

						if ( get_post_meta( $id, 'variation_maximum_allowed_quantity', true ) && $product->get_stock_quantity() ) {
							$product_max_qty = min( get_post_meta( $id, 'variation_maximum_allowed_quantity', true ), $product->get_stock_quantity() );
						} else if ( get_post_meta( $id, 'variation_maximum_allowed_quantity', true ) && !$product->get_stock_quantity() ) {
							$product_max_qty = get_post_meta( $id, 'variation_maximum_allowed_quantity', true );
						} else if ( !get_post_meta( $id, 'variation_maximum_allowed_quantity', true ) && $product->get_stock_quantity() ) {
							$product_max_qty = $product->get_stock_quantity();
						}

						$product_min_qty = get_post_meta( $id, 'variation_minimum_allowed_quantity', true ) ? get_post_meta( $id, 'variation_minimum_allowed_quantity', true ) : 1;
					} else {

						if ( get_post_meta( $id, 'maximum_allowed_quantity', true ) && $product->get_stock_quantity() ) {
							$product_max_qty = min( get_post_meta( $id, 'maximum_allowed_quantity', true ), $product->get_stock_quantity() );
						} else if ( get_post_meta( $id, 'maximum_allowed_quantity', true ) && !$product->get_stock_quantity() ) {
							$product_max_qty = get_post_meta( $id, 'maximum_allowed_quantity', true );
						} else if ( !get_post_meta( $id, 'maximum_allowed_quantity', true ) && $product->get_stock_quantity() ) {
							$product_max_qty = $product->get_stock_quantity();
						}

						$product_min_qty = get_post_meta( $id, 'minimum_allowed_quantity', true ) ? get_post_meta( $id, 'minimum_allowed_quantity', true ) : 1;
					}

					$product_title = $product->get_title();
					$product_image_id = $product->get_image_id();
					$product_permalink = $product->get_permalink();
					$product_price = wc_price( wc_get_price_to_display( $product ) ) . $product->get_price_suffix();
					$product_quantity = $values['quantity'];

					$product_availability = $values[ 'data' ]->get_availability();

					// Pre Order product.
					$can_be_pre_ordered = false;
					if ( class_exists( 'WC_Pre_Orders_Product' ) ) {
						$can_be_pre_ordered = \WC_Pre_Orders_Product::product_can_be_pre_ordered( $id );
					}
					?>

					<div
						class="item"
						data-product-id="<?php echo ( !$is_variation ) ? $id : $parent_id; ?>"
						<?php echo $is_variation ? 'data-variation-id="'.$id.'"' : ''; ?>
						<?php echo $product_max_qty ? 'data-product-max-qty="'.$product_max_qty.'"' : ''; ?>
						>
						<div class="wrapper">
							<div class="img-wrapper">
								<?php
								$image_link = wp_get_attachment_url( $product_image_id );
								?>
								<img src="<?php echo $image_link; ?>" width="99" height="99" alt="<?php echo $product_title; ?>">
								<?php if ( $can_be_pre_ordered ) : ?>
									<p class="availability-status">pre-order</p>
								<?php endif; ?>
							</div>
							<div class="content">
								<h4 class="title"><a href="<?php echo $product_permalink;?>" alt="<?php echo $product_title;?>"><?php echo $product_title; ?></a></h4>
								<?php
								$product_regular_price = $product->get_regular_price() ? $product->get_regular_price() : '';
								$product_sale_price = $product->get_sale_price() ? $product->get_sale_price() : '';
								?>
								<p class="price">
									<?php
									if ( $woo_currency === $yay_currency ) {
										$price_number = $product_sale_price ? $product_sale_price : $product_regular_price;
									} else {
										$price_number = $product_sale_price ? $product_sale_price * $currency['rate'] : $product_regular_price * $currency['rate'];
									}
									echo get_woocommerce_currency_symbol() . number_format( (float) $price_number, 2, '.', '' ); ?>
								</p>
								<div class="count-controls">
									<div class="controls">
										<button class="minus">
											<?php
											bda_display_svg(
												[
													'icon'   => 'modal-basket-minus',
													'width'  => '13',
													'height' => '2',
												]
											);
											?>
										</button>

										<?php
										if ( $product->is_sold_individually() ) {
											$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1"/>', $key );
										} else {
											$product_quantity = woocommerce_quantity_input(
												array(
													'input_name'   => "cart[{$key}][qty]",
													'input_value'  => $values['quantity'],
													'max_value'    => $product->get_max_purchase_quantity(),
													'min_value'    => '0',
													'product_name' => $product->get_name(),
												),
												$product,
												false
											);
										}

										echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $key, $values ); // PHPCS: XSS ok.
										?>

										<button class="plus">
										<?php
											bda_display_svg(
												[
													'icon'   => 'modal-basket-plus',
													'width'  => '13',
													'height' => '13',
												]
											);
											?>
										</button>
									</div>

									<?php if ( ! $product_max_qty === $product->get_stock_quantity() ) : ?>
									<div class="limit">
										<p class="info">
											Max <?php echo $product_max_qty ?>
											<?php
											bda_display_svg(
												[
													'icon'   => 'attention',
													'width'  => '20',
													'height' => '20',
												]
											);
											?>
										</p>
										<p class="popup">This item is limited to <?php echo $product_max_qty; ?> per customer.</p>
									</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<button class="delete">
							<?php
							bda_display_svg(
								[
									'icon'   => 'modal-basket-delete',
									'width'  => '24',
									'height' => '24',
								]
							);
							?>
						</button>
					</div>

				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="bottom">
		<?php get_template_part( 'template-parts/modal-cart-bottom-part', null ); ?>
	</div>

	<div class="empty-cart-stub">
		<p class="desc">YOU HAVE NO ITEMS IN YOUR BASKET.</p>
		<img src="<?php echo get_template_directory_uri() . '/build/images/modal-basket-empty.svg' ?>" width="153" height="153" alt="shopping-bag">
		<button class="btn btn-red">Continue Shopping</button>
	</div>
</div>
