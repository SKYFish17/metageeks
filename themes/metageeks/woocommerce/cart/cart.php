<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
 */

defined( 'ABSPATH' ) || exit;

use Automattic\Jetpack\Constants;

global $woocommerce;
$is_empty_cart = $woocommerce->cart->is_empty();
$free_shipping_order_cost = get_field( 'shipping_terms', 'options' )['free_shipping'];

// Data for You Might Also Like block.
$cart_categories_ids = array();
$cart_brands_ids     = array();
$cart_products_ids   = array_column( WC()->cart->get_cart(), 'product_id' );
?>

<div class="cart-page">
	<div class="cart-page__free-delivery-notice free-delivery-notice">Spend over <?php echo '£' . $free_shipping_order_cost; ?> to recieve free delivery</div>

	<div class="container">
		<?php woocommerce_breadcrumb(); ?>

		<div class="cart-page__wrapper wrapper">
			<h2 class="cart-page-basket__title cart-page-subtitle">Basket</h2>

			<div class="cart-notices">
				<?php do_action( 'woocommerce_before_cart' ); ?>
			</div>

			<h1 class="visually-hidden">Viewing the shopping cart</h1>

			<div class="columns">
				<div class="columns__one">
					<section class="cart-page__basket cart-page-basket">

						<form class="cart-page-basket__form woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post" >
							<div class="woocommerce-cart-form__contents">

								<?php do_action( 'woocommerce_before_cart_table' ); ?>

								<div class="cart-notices">
									<?php do_action( 'woocommerce_before_cart_contents' ); ?>
								</div>

								<div class="cart-page-basket__hide">
									<button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>

									<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>

									<?php
									if ( class_exists( 'TInvWL_Public_CartSave' ) ) {
										$instance = new TInvWL_Public_CartSave( 'TI WooCommerce Wishlist Premium' );
										$move_cart_to_wishlist_btn = $instance->save_cart_addtowishlist();

										echo $move_cart_to_wishlist_btn;
									}
									?>
								</div>

								<div class="cart-page-basket__products-table products-table">
									<div class="products-table__row products-table__row_header">
										<div class="products-table-col products-table-col_image">
										</div>
										<div class="products-table-col__wrapper">
											<div class="products-table-col products-table-col_product">
												Product
											</div>
											<div class="products-table-col__mobile-wrapper">
												<div class="products-table-col products-table-col_qty">
													Quantity
												</div>
												<div class="products-table-col products-table-col_price">
													Price
												</div>
											</div>
											<div class="products-table-col products-table-col_delete">
											</div>
										</div>
									</div>

									<?php if ( $is_empty_cart ) : ?>
										<div class="products-table__row products-table__row_content">
											Your basket is currently empty.
										</div>
									<?php endif; ?>

									<?php
									foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
										$product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
										$is_variation = $product->get_type() === 'variation' ? true : false;
										$product_id = $cart_item['data']->get_id();

										// Pre Order product.
										$can_be_pre_ordered     = false;
										$availability_timestamp = '';
										if ( class_exists( 'WC_Pre_Orders_Product' ) ) {
											$can_be_pre_ordered     = WC_Pre_Orders_Product::product_can_be_pre_ordered( $product_id );
											$availability_timestamp = WC_Pre_Orders_Product::get_localized_availability_datetime_timestamp( $product_id );
										}

										$product_regular_price = $product->get_regular_price( $product );
										$product_sale_price = $product->get_sale_price( $product );
										$product_price = wc_price( $product_sale_price ? $product_sale_price : $product_regular_price );

										// Data for You Might Also Like block.
										$product_categories = $product->get_category_ids();
										$cart_categories_ids    = array_merge( $cart_categories_ids, $product_categories );

										$product_brands = get_the_terms( $product_id, 'product_brands' );
										if ( $product_brands ) {
											$product_brands = array_column( $product_brands, 'term_id' );
											$cart_brands_ids = array_merge( $cart_brands_ids, $product_brands );
										}

										// Product qty
										if ( $is_variation ) {
											$product_max_qty = get_post_meta( $product_id, 'variation_maximum_allowed_quantity', true );
											$product_min_qty = get_post_meta( $product_id, 'variation_minimum_allowed_quantity', true ) ? get_post_meta( $product_id, 'variation_minimum_allowed_quantity', true ) : 1;
										} else {
											$product_max_qty = get_post_meta( $product_id, 'maximum_allowed_quantity', true );
											$product_min_qty = get_post_meta( $product_id, 'minimum_allowed_quantity', true ) ? get_post_meta( $product_id, 'minimum_allowed_quantity', true ) : 1;
										}
										$product_qty = $cart_item['quantity'];

										if ( $product && $product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
											$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $product->is_visible() ? $product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
											?>

											<div class="products-table__row products-table__row_content <?php echo $can_be_pre_ordered ? 'products-table__row_pre-order' : ''; ?> <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>" data-product-id="<?php echo $product_id; ?>">
												<div class="products-table-col products-table-col_image">
													<?php
													$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $product->get_image(), $cart_item, $cart_item_key );

													if ( ! $product_permalink ) {
														echo $thumbnail; // PHPCS: XSS ok.
													} else {
														printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
													}
													?>

													<?php if ( $can_be_pre_ordered ) : ?>
														<div class="products-table-col__pre-order product-status-pre-order">
															<p class="stock available-on-backorder">Pre order</p>
														</div>
													<?php endif; ?>
												</div>
												<div class="products-table-col__wrapper">
													<div class="products-table-col products-table-col_product">
														<?php
														if ( ! $product_permalink ) { ?>
															<h3 class="products-table-col__title">
																<?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );	 ?>
															</h3>
															<?php
														} else {
															echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s"><h3 class="products-table-col__title">%s</h3></a>', esc_url( $product_permalink ), $product->get_name() ), $cart_item, $cart_item_key ) );
														}
														?>

														<?php if ( $can_be_pre_ordered ) : ?>
															<div class="products-table-col__pre-order product-status-pre-order">
																<p class="stock available-on-backorder">Pre order</p>

																<p class="product-status-pre-order__text">
																	expected release
																	<span class="product-status-pre-order__date"><?php echo esc_html( gmdate( 'F j, Y', $availability_timestamp ) ); ?></span>
																</p>
															</div>
														<?php endif; ?>

														<?php
														if ( class_exists( 'TInvWL_Public_CartSave' ) ) {
															$instance = new TInvWL_Public_CartSave( 'TI WooCommerce Wishlist Premium' );
															$save_for_later_btn = $instance->save_cart_item_addtowishlist( $cart_item, $cart_item_key );

															echo $save_for_later_btn;
														}
														?>
													</div>
													<div class="products-table-col__mobile-wrapper">
														<div class="products-table-col products-table-col_qty">
															<div class="products-table-col__qty">
															<?php
																if ( $product->is_sold_individually() ) {
																	$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1"/>', $cart_item_key );
																} else {
																	$product_quantity = woocommerce_quantity_input(
																		array(
																			'input_name'   => "cart[{$cart_item_key}][qty]",
																			'input_value'  => $cart_item['quantity'],
																			'max_value'    => $product->get_max_purchase_quantity(),
																			'min_value'    => '0',
																			'product_name' => $product->get_name(),
																		),
																		$product,
																		false
																	);
																}

																echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
																?>
																<?php if ( $product_max_qty ) : ?>
																	<div class="products-table-col__qty-limit product-qty-limit">
																		<p class="products-table-col__qty-limit-info product-qty-limit__info">
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
																		<p class="products-table-col__qty-limit-popup product-qty-limit__popup">This item is limited to <?php echo $product_max_qty; ?> per customer.</p>
																	</div>
																<?php endif; ?>
															</div>
														</div>
														<div class="products-table-col product-subtotal products-table-col_price">
															<p class="products-table-col__price-value">
																<?php echo WC()->cart->get_product_subtotal( $product, $cart_item['quantity'] ); ?>
															</p>
														</div>
													</div>
													<div class="products-table-col products-table-col_delete">
														<?php
															echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
																'woocommerce_cart_item_remove_link',
																sprintf(
																	'<a href="%s" class="products-table-col__btn-delete remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s%s%s</a>',
																	esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
																	esc_html__( 'Remove this item', 'woocommerce' ),
																	esc_attr( $product_id ),
																	esc_attr( $product->get_sku() ),
																	bda_get_svg(
																		[
																			'icon'   => 'modal-basket-delete',
																			'width'  => '24',
																			'height' => '24',
																		]
																	),
																	esc_html__( 'Remove', 'woocommerce' ),
																	bda_get_svg(
																		[
																			'icon'   => 'remove',
																			'width'  => '20',
																			'height' => '20',
																		]
																	),
																),
																$cart_item_key
															);
														?>
													</div>
													</div>
											</div>
										<?php
										}
									}
									?>

									<?php do_action( 'woocommerce_cart_contents' ); ?>
									<?php // do_action( 'woocommerce_cart_actions' ); ?>
								</div>

								<?php do_action( 'woocommerce_after_cart_table' ); ?>
							</div>
						</form>

						<div class="cart-page-basket__wrapper">
							<div class="cart-page-basket__row">
								<div class="cart-page-basket__buttons">
									<button class="cart-page-basket__to-wishlist-btn btn-cart-control btn-cart-control_to-wishlist">
										<?php
										bda_display_svg(
											[
												'icon'   => 'arrow-down',
												'width'  => '24',
												'height' => '24',
											]
										);
										?>
										Move all from basket to wishlist
									</button>
									<button class="cart-page-basket__remove-all-btn btn-cart-control btn-cart-control_remove-all">
										<?php
										bda_display_svg(
											[
												'icon'   => 'remove',
												'width'  => '24',
												'height' => '24',
											]
										);
										?>
										Remove all from basket
									</button>
								</div>

								<?php
								if ( wc_coupons_enabled() ) {
									wc_get_template( 'checkout/form-coupon.php' );
								}
								?>
							</div>
						</div>
					</section>

					<section class="cart-page__wishlist cart-page-wishlist">
						<header class="cart-page-wishlist__header">
							<h2 class="cart-page-wishlist__title cart-page-subtitle">Wishlist</h2>

							<button class="cart-page-wishlist__to-basket-btn btn-cart-control btn-cart-control_to-basket">
								<?php
								bda_display_svg(
									[
										'icon'   => 'arrow-down',
										'width'  => '24',
										'height' => '24',
									]
								);
								?>
								Move all from wishlist to basket
							</button>
						</header>

						<?php echo do_shortcode( '[ti_wishlistsview]' ); ?>
					</section>
				</div>

				<div class="columns__two">
					<?php echo get_template_part( 'template-parts/cart-page-summary' ); ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>

<?php
/**
 * You Might Also Like block
 */


$cart_categories_ids = array_unique( $cart_categories_ids );
$cart_brands_ids     = array_unique( $cart_brands_ids );

$args  = array(
	'post_type'      => 'product',
	'orderby'        => 'rand',
	'posts_per_page' => 10,
	'post__not_in'   => $cart_products_ids, // Exclude items from the cart.
	'meta_query'     => array(
		array(
			'key'     => '_stock_status', // Exclude items that are out of stock.
			'value'   => 'outofstock',
			'compare' => '!=',
		),
	),
	'tax_query'      => array(
		'relation' => 'OR', // Include more items.
		array(
			'taxonomy' => 'product_cat', // Include products from the same categories.
			'field'    => 'term_id',
			'terms'    => $cart_categories_ids,
		),
		array(
			'taxonomy' => 'product_brands', // Include products with the same brands.
			'field'    => 'term_id',
			'terms'    => $cart_brands_ids,
		),
	),
);
$query = new WP_Query( $args );

// Block data.
$args = array(
	'id'               => 'product-slider-block-' . uniqid(),
	'class'            => 'product-slider-block mg-product-slider__section',
	'title'            => 'You Might Also Like',
	'background_color' => 'white',
	'link'             => null,
	'products'         => $query->posts,
);

// Load template.
get_template_part( 'template-parts/product-slider-block', null, $args );
?>
