<?php
/**
 * The Template for displaying wishlist if a current user is owner.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/ti-wishlist.php.
 *
 * @version             1.24.5
 * @package           TInvWishlist\Template
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
wp_enqueue_script('tinvwl');
?>
<div class="tinv-wishlist woocommerce tinv-wishlist-clear">

	<?php if (function_exists('wc_print_notices') && isset(WC()->session)) {
		wc_print_notices();
	} ?>

	<?php
	$wl_paged = absint(get_query_var('wl_paged'));
	$form_url = tinv_url_wishlist($wishlist['share_key'], $wl_paged, true);
	?>

	<form class="cart-page-basket__form" action="<?php echo esc_url($form_url); ?>" method="post" autocomplete="off">
		<?php do_action('tinvwl_before_wishlist_table', $wishlist); ?>

		<div class="tinvwl-table-manage-list__buttons">
			<?php do_action('tinvwl_after_wishlist_table', $wishlist); ?>
		</div>

		<?php wp_nonce_field('tinvwl_wishlist_owner', 'wishlist_nonce'); ?>

		<div class="cart-page-basket__products-table products-table tinvwl-table-manage-list">
			<div class="products-table__row products-table__row_header">
				<div class="products-table-col products-table-col_image">
				</div>
				<div class="products-table-col__wrapper">
					<div class="products-table-col products-table-col_product">
						Product
					</div>
					<div class="products-table-col__mobile-wrapper">
						<div class="products-table-col products-table-col_qty"></div>
						<div class="products-table-col products-table-col_price">
							Price
						</div>
					</div>
					<div class="products-table-col products-table-col_delete">
					</div>
				</div>
			</div>

			<?php do_action('tinvwl_wishlist_contents_before'); ?>
			<?php

			global $product, $post;
			// store global product data.
			$_product_tmp = $product;
			// store global post data.
			$_post_tmp = $post;

			foreach ($products as $wl_product) {

				if (empty($wl_product['data'])) {
					continue;
				}

				// override global product data.
				$product = apply_filters('tinvwl_wishlist_item', $wl_product['data']);
				// override global post data.
				$post = get_post($product->get_id());
				unset($wl_product['data']);
				if (($wl_product['quantity'] > 0 || apply_filters('tinvwl_allow_zero_quantity', false)) && apply_filters('tinvwl_wishlist_item_visible', true, $wl_product, $product)) {
					$product_url = apply_filters('tinvwl_wishlist_item_url', $product->get_permalink(), $wl_product, $product);
					do_action('tinvwl_wishlist_row_before', $wl_product, $product);
					?>

					<div class="products-table__row products-table__row_content <?php echo esc_attr(apply_filters('tinvwl_wishlist_item_class', 'wishlist_item', $wl_product, $product)); ?>">
						<?php if (!isset($wishlist_table_row['colm_image']) || $wishlist_table_row['colm_image']) { ?>
							<div class="products-table-col products-table-col_image">
								<?php
								$thumbnail = apply_filters('tinvwl_wishlist_item_thumbnail', $product->get_image(), $wl_product, $product);

								if (!$product->is_visible()) {
									echo $thumbnail; // WPCS: xss ok.
								} else {
									printf('<a href="%s">%s</a>', esc_url($product_url), $thumbnail); // WPCS: xss ok.
								}
								?>

								<?php
								// Pre Order product.
								$can_be_pre_ordered     = false;
								$availability_timestamp = '';
								if ( class_exists( 'WC_Pre_Orders_Product' ) ) {
									$can_be_pre_ordered     = WC_Pre_Orders_Product::product_can_be_pre_ordered( $product->get_id() );
									$availability_timestamp = WC_Pre_Orders_Product::get_localized_availability_datetime_timestamp( $product->get_id() );
								}

								if ( $can_be_pre_ordered ) { ?>
									<div class="products-table-col__pre-order product-status-pre-order">
										<p class="stock available-on-backorder">Pre order</p>
									</div>
									<?php
								}
								?>
							</div>
						<?php } ?>
						<div class="products-table-col__wrapper">
							<div class="products-table-col products-table-col_product">
								<?php
								if (!$product->is_visible()) {
									echo apply_filters('tinvwl_wishlist_item_name', is_callable(array(
													$product,
													'get_name'
											)) ? $product->get_name() : $product->get_title(), $wl_product, $product) . '&nbsp;'; // WPCS: xss ok.
								} else {
									echo apply_filters('tinvwl_wishlist_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_url), is_callable(array(
											$product,
											'get_name'
									)) ? $product->get_name() : $product->get_title()), $wl_product, $product); // WPCS: xss ok.
								}

								echo apply_filters('tinvwl_wishlist_item_meta_data', tinv_wishlist_get_item_data($product, $wl_product), $wl_product, $product); // WPCS: xss ok.
								?>

								<?php
								if ( $can_be_pre_ordered ) { ?>
									<div class="products-table-col__pre-order product-status-pre-order">
										<p class="stock available-on-backorder">Pre order</p>

										<p class="product-status-pre-order__text">
											expected release
											<span class="product-status-pre-order__date"><?php echo esc_html( gmdate( 'F j, Y', $availability_timestamp ) ); ?></span>
										</p>
									</div>
									<?php
								}
								?>

								<?php if ((!isset($wishlist_table_row['move']) || $wishlist_table_row['move']) || (!isset($wishlist_table_row['add_to_cart']) || $wishlist_table_row['add_to_cart'])) { ?>
									<td class="product-action">
										<?php
										if (apply_filters('tinvwl_wishlist_item_action_add_to_cart', $wishlist_table_row['add_to_cart'], $wl_product, $product)) {
											?>
											<button class="button tinvwl-button alt" name="tinvwl-add-to-cart"
													value="<?php echo esc_attr($wl_product['ID']); ?>"
													title="<?php echo esc_html(apply_filters('tinvwl_wishlist_item_add_to_cart', $wishlist_table_row['text_add_to_cart'], $wl_product, $product)); ?>">
												<?php
												bda_display_svg(
													[
														'icon'   => 'add-to-the-basket',
														'width'  => '20',
														'height' => '20',
													]
												);
												?>
												<span class="tinvwl-txt"><?php echo wp_kses_post(apply_filters('tinvwl_wishlist_item_add_to_cart', $wishlist_table_row['text_add_to_cart'], $wl_product, $product)); ?></span>
											</button>
										<?php } elseif (apply_filters('tinvwl_wishlist_item_action_default_loop_button', $wishlist_table_row['add_to_cart'], $wl_product, $product)) {
											// woocommerce_template_loop_add_to_cart();
										} ?>
										<?php
										if (apply_filters('tinvwl_wishlist_item_action_move', $wishlist_table_row['move'], $wl_product, $product)) {
											echo apply_filters('tinvwl_wishlist_item_move', sprintf('<button class="button tinvwl-button tinvwl_move_product_button" type="button" name="move"><i class="ftinvwl ftinvwl-arrow-right"></i><span class="tinvwl-txt">%s</span></button>',
											esc_html(__('Move', 'ti-woocommerce-wishlist-premium'))),
											$wl_product,
											$product,
											'123'
										); // WPCS: xss ok.
										} ?>
									</td>
								<?php } ?>
							</div>
							<div class="products-table-col__mobile-wrapper">
								<div class="products-table-col products-table-col_qty"></div>
									<?php if (!isset($wishlist_table_row['colm_price']) || $wishlist_table_row['colm_price']) { ?>
										<div class="products-table-col product-subtotal products-table-col_price">
											<p class="products-table-col__price-value">
												<?php
												echo apply_filters('tinvwl_wishlist_item_price', $product->get_price_html(), $wl_product, $product); // WPCS: xss ok.
												?>
											</p>
										</div>
									<?php } ?>
							</div>
							<div class="products-table-col products-table-col_delete">
								<button type="submit" name="tinvwl-remove"
										class="products-table-col__btn-delete remove"
										value="<?php echo esc_attr($wl_product['ID']); ?>"
										title="<?php _e('Remove', 'ti-woocommerce-wishlist-premium') ?>">
										<?php
										bda_display_svg(
											[
												'icon'   => 'modal-basket-delete',
												'width'  => '24',
												'height' => '24',
											]
											);
										echo esc_html__( 'Remove', 'woocommerce' );
										bda_display_svg(
											[
												'icon'   => 'remove',
												'width'  => '20',
												'height' => '20',
											]
										);
										?>
								</button>
							</div>
						</div>
					</div>
					<?php
					do_action('tinvwl_wishlist_row_after', $wl_product, $product);
				} // End if().
			} // End foreach().
			// restore global product data.
			$product = $_product_tmp;
			// restore global post data.
			$post = $_post_tmp;
			?>
			<?php do_action('tinvwl_wishlist_contents_after'); ?>
		</div>
	</form>

	<?php do_action('tinvwl_after_wishlist', $wishlist); ?>
	<div class="tinv-lists-nav tinv-wishlist-clear">
		<?php do_action('tinvwl_pagenation_wishlist', $wishlist); ?>
	</div>
</div>
