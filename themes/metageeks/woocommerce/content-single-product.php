<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $woocommerce, $product;

$product_id = get_the_ID();
$product = wc_get_product( $product_id );
$is_variable = $product->is_type( 'variable' );

// Product brand labels
$product_brands = get_the_terms( $product_id, 'product_brands');
?>

<div class="container">
<?php
/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );
?>
</div>

<?php
if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'single-product__wrapper', $product ); ?>">
	<section class="single-product__primary single-product-primary">

		<?php
		$product_title = $product->get_title();
		$product_desc = $product->get_short_description();

		$product_image_id = $product->get_image_id();
		$product_gallery_images_ids = $product->get_gallery_image_ids();

		// Push all product images in an one array
		array_unshift( $product_gallery_images_ids, $product_image_id );

		if ( $is_variable ) {
			$variations = $product->get_available_variations();

			$product_regular_price = $product->get_variation_regular_price() ? number_format( (float) $product->get_variation_regular_price(), 2, '.', '' ) : '';
			$product_sale_price = $product->get_variation_sale_price() ? number_format( (float) $product->get_variation_sale_price(), 2, '.', '' ) : '';

			foreach( $variations as $variation ) {
				array_push($product_gallery_images_ids, $variation['image_id']);
			}

			$product_gallery_images_ids = array_unique( $product_gallery_images_ids );
		} else {
			$product_regular_price = $product->get_regular_price() ? number_format( (float) $product->get_regular_price(), 2, '.', '' ) : '';
			$product_sale_price = $product->get_sale_price() ? number_format( (float) $product->get_sale_price(), 2, '.', '' ) : '';
		}

		$product_max_qty =
			$product->get_meta('maximum_allowed_quantity') ?
			$product->get_meta('maximum_allowed_quantity') :
			'';
		$product_min_qty =
			$product->get_meta('minimum_allowed_quantity') ?
			$product->get_meta('minimum_allowed_quantity') :
			1;

		// Pre Order product.
		$can_be_pre_ordered     = false;
		$availability_timestamp = '';
		if ( class_exists( 'WC_Pre_Orders_Product' ) ) {
			$can_be_pre_ordered     = WC_Pre_Orders_Product::product_can_be_pre_ordered( $product_id );
			$availability_timestamp = WC_Pre_Orders_Product::get_localized_availability_datetime_timestamp( $product_id );
		}

		$product_stock_qty = $product->get_stock_quantity();

		// For social-share buttons
		$product_summary = $product->get_description();
		$url = get_the_permalink();
		$image_url = wp_get_attachment_url( $product_image_id );

		// Free shipping terms
		$free_shipping_order_cost = get_field( 'shipping_terms', 'options' )['free_shipping'];
		$free_next_day_shipping_order_cost = get_field( 'shipping_terms', 'options' )['free_next_day_shipping'];

		$is_product_limited_qty_class = ( 0 === strpos( $product->get_availability()['availability'], 'Only' ) ) ? 'limited' : '';
		?>

		<div class="container">
			<div class="single-product-primary__mobile">
				<?php if ( $is_variable ) : ?>
					<h1 class="product-order__title"><?php echo $product_title; ?></h1>
				<?php else : ?>
					<div class="product-order__bonus badge badge_feature">
						<span class="badge__status">Exclusive Benefits</span>
						<p class="badge__text">
							You could earn
							<span class="badge__value">
								<?php echo esc_html( ceil( $product->get_price() * 3 ) ); ?>
								points
							</span>
							on this order!
						</p>
					</div>

					<?php if ( $product_brands ) : ?>
						<ul class="product-order__brands product-order-brands">

							<?php foreach ( $product_brands as $brand ) :
								$term_id = $brand->term_id;
								$brand_name = $brand->name;
								$term = get_term( $term_id );
								$image = get_field( 'logo', $term );
								?>
								<li class="product-order-brands__item">
									<a href="<?php echo esc_url( get_term_link( $term_id, 'product_brands' ) ); ?>" class="product-order-brands__link">
										<?php if ( $image ) : ?>
											<img
												src="<?php echo esc_url( wp_get_attachment_image_url( $image['ID'], array( 144, 76 ) ) ); ?>"
												alt="<?php echo esc_attr( $brand_name ); ?>"
												class="product-order-brands__img">
										<?php else : ?>
											<?php echo esc_html( $brand_name ); ?>
										<?php endif; ?>
									</a>
								</li>
							<?php endforeach; ?>

						</ul>
					<?php endif; ?>

					<h1 class="product-order__title"><?php echo $product_title; ?></h1>

					<?php if ( $can_be_pre_ordered ) : ?>
						<div class="product-order__pre-order product-status-pre-order">
							<p class="stock available-on-backorder">Pre order</p>

							<p class="product-status-pre-order__text">
								expected release
								<span class="product-status-pre-order__date"><?php echo esc_html( gmdate( 'F j, Y', $availability_timestamp ) ); ?></span>
							</p>
						</div>
					<?php endif; ?>

					<p class="product-order__price product-order-price">
						<?php if ( $product->is_on_sale() ) : ?>
							<ins class="product-order-price__sale"><?php echo get_woocommerce_currency_symbol(). $product_sale_price; ?></ins>
							<?php $discount = abs( round( (float) $product_sale_price / (float) $product_regular_price * 100 - 100 ) ); ?>
							<span class="product-order-price__sale-value"><?php echo '(-'.$discount.'%)' ?></span>
							<del class="product-order-price__regular"><?php echo get_woocommerce_currency_symbol().$product_regular_price; ?></del>
						<?php else: ?>
							<ins class="product-order-price__base"><?php echo get_woocommerce_currency_symbol().$product_regular_price; ?></ins>
						<?php endif; ?>
					</p>
				<?php endif; ?>
			</div>

			<div class="single-product-primary__slider product-slider">
				<?php if ( !$is_variable ) : ?>
					<div class="product-slider__labels <?php echo $is_product_limited_qty_class; ?>">
						<?php echo wc_get_stock_html( $product ); ?>
						<?php echo wc_get_template( 'loop/sale-flash.php' ); ?>
					</div>
				<?php endif; ?>

				<div class="product-slider__nav product-slider-nav">
					<?php
					foreach( $product_gallery_images_ids as $image_id ) {
						$image_link = wp_get_attachment_url( $image_id );
						?>

						<div class="product-slider-nav__img-wrapper">
							<img class="product-slider-nav__img" src="<?php echo $image_link; ?>" alt="<?php echo $product_title; ?>">
						</div>

						<?php
					}
					?>
				</div>

				<div class="product-slider__for product-slider-for">
					<?php
					foreach( $product_gallery_images_ids as $image_id ) {
						$image_link = wp_get_attachment_url( $image_id );
						?>

						<div class="product-slider-for__img-wrapper" data-variation-image-id="<?php echo $image_id; ?>">
							<img class="product-slider-for__img" src="<?php echo $image_link; ?>" alt="<?php echo $product_title; ?>">
						</div>

						<?php
					}
					?>
				</div>

				<div class="product-slider__social-share product-slider-social-share">
					<button class="product-slider-social-share__btn">
						<span class="visually-hidden">Social-share</span>
						<?php
						bda_display_svg(
							[
								'icon'   => 'share',
								'width'  => '24',
								'height' => '24',
							]
						);
						?>
					</button>

					<ul class="product-slider-social-share__list">
						<li class="product-slider-social-share__item">
							<?php
							$facebook_link  = 'http://www.facebook.com/sharer.php?s=100';
							$facebook_link .= '&p[url]=' . $url;
							$facebook_link .= '&p[title]=' . strip_tags( $product_title );
							$facebook_link .= '&p[summary]=' . strip_tags( $product_summary );
							$facebook_link .= '&p[images][0]=' . $image_url;
							?>
							<a
								class="product-slider-social-share__link"
								href="<?php echo esc_url( $facebook_link ); ?>"
								onclick="window.open(this.href, this.title, 'toolbar=0, status=0, width=548, height=325'); return false" title="Share a link to Facebook" target="_parent">

								<?php
								bda_display_svg(
									[
										'icon'   => 'facebook-sp',
										'width'  => '11',
										'height' => '21',
									]
								);
								?>
								<span class="visually-hidden">Facebook</span>
							</a>
						</li>
						<li class="product-slider-social-share__item">
							<?php
							$twitter_link  = 'http://twitter.com/share';
							$twitter_link .= '?text=' . strip_tags( $product_summary );
							$twitter_link .= '&url=' . $url;
							?>
							<a
								class="product-slider-social-share__link"
								href="<?php echo esc_url( $twitter_link ); ?>"
								title="Share a link to Twitter"
								onclick="window.open(this.href, this.title, 'toolbar=0, status=0, width=548, height=325'); return false"
								target="_parent">

								<?php
								bda_display_svg(
									[
										'icon'   => 'twitter-sp',
										'width'  => '21',
										'height' => '17',
									]
								);
								?>
								<span class="visually-hidden">Twitter</span>
							</a>
						</li>
					</ul>
				</div>
			</div>

			<div class="single-product-primary__order product-order">
				<?php if ( $is_variable ) : ?>
					<div class="single-product-primary__desktop">
						<h1 class="product-order__title"><?php echo $product_title; ?></h1>

						<?php woocommerce_template_single_add_to_cart(); ?>
					</div>
				<?php else: ?>
					<div class="single-product-primary__desktop">
						<div class="product-order__bonus badge badge_feature">
							<span class="badge__status">Exclusive Benefits</span>
							<p class="badge__text">
								You could earn
								<span class="badge__value">
									<?php echo esc_html( ceil( $product->get_price() * 3 ) ); ?>
									points
								</span>
								on this order!
							</p>
						</div>

						<?php if ( $product_brands ) : ?>
							<ul class="product-order__brands product-order-brands">

								<?php foreach ( $product_brands as $brand ) :
									$term_id = $brand->term_id;
									$brand_name = $brand->name;
									$term = get_term( $term_id );
									$image = get_field( 'logo', $term );
									?>
									<li class="product-order-brands__item">
										<a href="<?php echo esc_url( get_term_link( $term_id, 'product_brands' ) ); ?>" class="product-order-brands__link">
											<?php if ( $image ) : ?>
												<img
													src="<?php echo esc_url( wp_get_attachment_image_url( $image['ID'], array( 144, 76 ) ) ); ?>"
													alt="<?php echo esc_attr( $brand_name ); ?>"
													class="product-order-brands__img">
											<?php else : ?>
												<?php echo esc_html( $brand_name ); ?>
											<?php endif; ?>
										</a>
									</li>
								<?php endforeach; ?>

							</ul>
						<?php endif; ?>

						<h1 class="product-order__title"><?php echo $product_title; ?></h1>
						<?php if ( $can_be_pre_ordered ) : ?>
							<div class="product-order__pre-order product-status-pre-order">
								<p class="stock available-on-backorder">Pre order</p>

								<p class="product-status-pre-order__text">
									expected release
									<span class="product-status-pre-order__date"><?php echo esc_html( gmdate( 'F j, Y', $availability_timestamp ) ); ?></span>
								</p>
							</div>
						<?php endif; ?>

						<p class="product-order__price product-order-price">
							<?php if ( $product->is_on_sale() ) : ?>
								<ins class="product-order-price__sale"><?php echo get_woocommerce_currency_symbol(). $product_sale_price; ?></ins>
								<?php $discount = abs( round( (float) $product_sale_price / (float) $product_regular_price * 100 - 100 ) ); ?>
								<span class="product-order-price__sale-value"><?php echo '(-'.$discount.'%)' ?></span>
								<del class="product-order-price__regular"><?php echo get_woocommerce_currency_symbol().$product_regular_price; ?></del>
							<?php else: ?>
								<ins class="product-order-price__base"><?php echo get_woocommerce_currency_symbol().$product_regular_price; ?></ins>
							<?php endif; ?>
						</p>
					</div>

					<?php if ( $can_be_pre_ordered ) : ?>
						<p class="product-order__pre-order-notification product-order-pre-order-notification">
							<?php
							bda_display_svg(
								[
									'icon'   => 'attention',
									'width'  => '22',
									'height' => '22',
								]
							);
							?>
							Pre-order products will ship when all items in your order are available. Please order in stock items separately to avoid delay.
						</p>
					<?php endif; ?>

					<ul class="product-order__key-info product-order-key-info">
						<?php if ( $product->is_in_stock() && $product->managing_stock() ) : ?>
							<li class="product-order-key-info__item product-order-key-info__item_stock">
								<span class="product-order-key-info__important"><?php echo $product_stock_qty; ?></span>
								in stock now
							</li>
						<?php endif; ?>

						<?php if ( $product->is_in_stock() ) : ?>
							<li class="product-order-key-info__item product-order-key-info__item_delivery">
								<span class="product-order-key-info__important">Free shipping</span> on UK orders over
								£<?php echo $free_shipping_order_cost; ?>,
								<br>
								or spend
								£<?php echo $free_next_day_shipping_order_cost; ?>+ to upgrade to <span class="product-order-key-info__important">free tracked</span>
							</li>

							<?php if ( ! $product->is_on_backorder() ) : ?>
								<li class="product-order-key-info__item product-order-key-info__item_dispatch">
									Order before <span class="product-order-key-info__important">1PM</span> to for same day dispatch
								</li>
								<?php if ( $can_be_pre_ordered ) : ?>
									<li class="product-order-key-info__item product-order-key-info__item_dispatch" data-countdown-date="<?php echo esc_attr( gmdate( 'Y-m-d H:i:s', $availability_timestamp ) ); ?>">
										Expected In the next
										<span class="product-order-key-info__important"></span>
									</li>
								<?php endif; ?>
							<?php else : ?>
							<?php endif; ?>
						<?php endif; ?>

						<?php if ( $product_max_qty ) : ?>
							<li class="product-order-key-info__item product-order-key-info__item_limit">
								Customers are limited to only purchasing
								<span class="product-order-key-info__important"><?php echo $product_max_qty; ?></span>
								of this product
							</li>
						<?php endif; ?>
					</ul>

					<div class="product-order__controls product-order-controls">
						<div class="product-order-controls__add-to-cart">
							<div class="product-order-controls__inputs">
								<button class="product-order-controls__minus">
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
								<input
									class="product-order-controls__count"
									type="number"
									min="<?php echo $product_min_qty; ?>"
									max="<?php echo $product_max_qty; ?>"
									value="1"
									step="1"
									name="single-product-count"
								>
								<button class="product-order-controls__plus">
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

							<?php woocommerce_template_loop_add_to_cart(); ?>
						</div>

						<?php echo do_shortcode( '[ti_wishlists_addtowishlist]' ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $product->is_in_stock() ) : ?>
					<div class="product-order__pay-later product-order-pay-later">
						<?php do_action( 'metageeks_express_payments_in_product' ); ?>
					</div>

					<div class="product-order__pay-later-images product-order-pay-later-images">
						<div class="product-order-pay-later__wrapper">
							<img src="<?php echo get_template_directory_uri() . '/build/images/klarna.svg'; ?>" alt="Klarna payment-method" class="product-order-pay-later__klarna-img">
						</div>
						<div class="product-order-pay-later__wrapper">
							<img src="<?php echo get_template_directory_uri() . '/build/images/clearpay.svg'; ?>" alt="ClearPay payment-method" class="product-order-pay-later__clearpay-img">
						</div>
					</div>
				<?php endif; ?>

			</div>
		</div>
	</section>

	<section class="single-product__extra-info single-product-extra-info">
		<?php
		$product_desc = $product->get_short_description();
		?>
		<div class="container">
			<h2 class="visually-hidden">Extra data of the product</h2>

			<ul class="single-product-extra-info__accordeon single-product-accordeon">
				<!-- From product -->
				<li class="single-product-accordeon__item single-product-accordeon-item single-product-accordeon-item">
					<header class="single-product-accordeon-item__header">
						<img src="<?php echo get_template_directory_uri() . '/build/images/icons/product-desc.svg'?>" width="34" height="34" alt="loupe-icon" class="single-product-accordeon-item__icon">
						<h3 class="single-product-accordeon-item__title">Product description</h3>
						<button class="single-product-accordeon-item__btn">
							<span class="visually-hidden">Toggle description</span>
							<span class="single-product-accordeon-item__btn-decor"></span>
						</button>
					</header>
					<div class="single-product-accordeon-item__content"><?php echo $product_desc; ?></div>
				</li>
				<!-- From ACF -->
				<?php
				$items = get_field( 'extra_data_of_the_product_list', 'options' );

				foreach ( $items as $item ) :
					$title = $item['title'];
					$icon = $item['icon'];
					$text = $item['text'];
				?>

				<li class="single-product-accordeon__item single-product-accordeon-item single-product-accordeon-item">
					<header class="single-product-accordeon-item__header">
						<img src="<?php echo $icon['url']; ?>" width="34" height="34" alt="<?php echo $icon['alt']; ?>" class="single-product-accordeon-item__icon">
						<h3 class="single-product-accordeon-item__title"><?php echo $title; ?></h3>
						<button class="single-product-accordeon-item__btn">
							<span class="visually-hidden">Toggle description</span>
							<span class="single-product-accordeon-item__btn-decor"></span>
						</button>
					</header>
					<div class="single-product-accordeon-item__content"><?php echo $text; ?></div>
				</li>

				<?php endforeach; ?>
			</ul>

			<div class="single-product-extra-info__estimate-shipping single-product-estimate-shipping">
				<div class="single-product-estimate-shipping__title">Estimate shipping</div>
				<?php do_action( 'metageeks_estimate_shipping' ); ?>
			</div>
		</div>
	</section>

	<section class="single-product__maybe-like single-product-maybe-like">
		<?php
			$term_id = $product->get_category_ids()[0];
			$term = get_term_by( 'id', $term_id, 'product_cat' );
			$product_brands_slug_arr = array();

			$args_tax = array(
				'post_type' => 'product',
				'posts_per_page' => 5,
				'product_cat' => $term->slug,
				'orderby' => 'rand',
				'meta_query' => [
					[
						'key' => '_stock_status',
						'value' => 'outofstock',
						'compare' => 'NOT LIKE'
					],
				],
			);

			if ( ! empty( $product_brands ) ) {
				foreach ( $product_brands as $brand ) {
					array_push( $product_brands_slug_arr, $brand->slug );
				}

				$args_brand = array(
					'post_type' => 'product',
					'posts_per_page' => 5,
					'product_brands' => $product_brands_slug_arr[0],
					'orderby' => 'rand',
					'meta_query' => [
						[
							'key' => '_stock_status',
							'value' => 'outofstock',
							'compare' => 'NOT LIKE'
						],
					],
				);

				$args_brands = array(
					'post_type' => 'product',
					'posts_per_page' => 5,
					'product_brands' => $product_brands_slug_arr,
					'orderby' => 'rand',
					'meta_query' => [
						[
							'key' => '_stock_status',
							'value' => 'outofstock',
							'compare' => 'NOT LIKE'
						],
					],
				);

				$query = new WP_Query( $args_brand );

				if ( count( $query->posts ) < 5 ) {
					$query = new WP_Query( $args_brands );

					if ( count( $query->posts ) < 5 ) {
						$query = new WP_Query( $args_tax );
					}
				}
			} else {
				$query = new WP_Query( $args_tax );
			}
		?>

		<div class="container">
			<h2 class="single-product-maybe-like__title mg-h1">You might also like</h2>

			<div class="single-product-maybe-like__products single-product-maybe-like-products">

				<?php
					if ( $query->have_posts() ) {
						while ( $query->have_posts() ) {
							$query->the_post();

							global $product;
							?>

							<?php wc_get_template('content-product.php'); ?>

						<?php }
					} else {
						echo __( 'No products found' );
					}

					wp_reset_postdata();
				?>
			</div>
		</div>
	</section>

	<?php if ( ! empty( $_COOKIE[ 'bda_woocommerce_recently_viewed' ] ) ) : ?>
		<section class="single-product__recently-viewed single-product-recently-viewed">
			<div class="container">
				<header class="single-product-recently-viewed__header">
					<h2 class="single-product-recently-viewed__title mg-h1">Recently viewed</h2>
					<button class="single-product-recently-viewed__clear btn">Clear all</button>
				</header>

				<div class="single-product-recently-viewed__products single-product-recently-viewed-products">
					<?php
					if( empty( $_COOKIE[ 'bda_woocommerce_recently_viewed' ] ) ) {
						$viewed_products = array();
					} else {
						$viewed_products = (array) explode( '|', $_COOKIE[ 'bda_woocommerce_recently_viewed' ] );
					}

					if ( empty( $viewed_products ) ) {
						return;
					}

					$viewed_products = array_reverse( array_map( 'absint', $viewed_products ) );

					$args = array(
						'post_type' => 'product',
						'post__in' => $viewed_products,
					);

					$query = new WP_Query( $args );

					if ( $query->have_posts() ) {
						while ( $query->have_posts() ) {
							$query->the_post();

							global $product;
							?>

							<?php the_ID(); ?>
							<?php wc_get_template('content-product.php'); ?>

						<?php }
					} else {
						echo __( 'No products found' );
					}

					wp_reset_postdata();
					?>
				</div>
			</div>
		</section>
	<?php endif; ?>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
