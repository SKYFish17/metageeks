<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
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

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<div class="metageeks-orders">

	<div class="metageeks-orders__header">Order history</div>

	<?php if ( $has_orders ) : ?>

		<div class="metageeks-orders__table">

			<div class="metageeks-orders__table-header">Your orders</div>

			<?php
			foreach ( $customer_orders->orders as $customer_order ) {
				$order           = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				$order_items     = $order->get_items();
				$billing_address = $order->get_address( 'billing' );
				?>
				<div class="metageeks-order">
					<div class="metageeks-order__header">
						<div class="metageeks-order__header-left">
							<strong>Order No. <?php echo esc_html( $order->get_order_number() ); ?>,</strong> <time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>
						</div>
						<div class="metageeks-order__header-right">
							Status: <strong><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></strong>
							<a href="#" class="metageeks-order__track mg-button">Track my order</a>
						</div>
						<a href="#" class="metageeks-order__track mg-button">Track my order</a>
					</div>

					<table class="metageeks-order__items">
						<?php
						foreach ( $order_items as $item_id => $item ) {
							$product           = $item->get_product();
							$is_visible        = $product && $product->is_visible();
							$product_permalink = apply_filters( 'woocommerce_order_item_permalink', $is_visible ? $product->get_permalink( $item ) : '', $item, $order );
							?>
							<tr class="metageeks-order__item">
								<td><div class="metageeks-order__item-image"><?php echo $product->get_image(); // phpcs:ignore ?></div></td>
								<td class="metageeks-order__item-name-wrapper"><div class="metageeks-order__item-name"><?php echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', $product_permalink ? sprintf( '<a href="%s">%s</a>', $product_permalink, $item->get_name() ) : $item->get_name(), $item, $is_visible ) ); ?></div></td>
								<td><div class="metageeks-order__item-quantity"><small>x</small> <span><?php echo esc_html( $item->get_quantity() ); ?></span></div></td>
								<td><div class="metageeks-order__item-subtotal"><?php echo $order->get_formatted_line_subtotal( $item ); // phpcs:ignore ?></div></td>
							</tr>
							<?php
						}
						?>
					</table>

					<div class="metageeks-order__footer">
						<div class="metageeks-order__footer-left">
							<?php if ( $billing_address['first_name'] || $billing_address['last_name'] ) : ?>
								<p><?php echo esc_html( $billing_address['first_name'] . ' ' . $billing_address['last_name'] ); ?></p>
							<?php endif; ?>

							<?php if ( $billing_address['company'] ) : ?>
								<p><?php echo esc_html( $billing_address['company'] ); ?></p>
							<?php endif; ?>

							<?php if ( $billing_address['country'] ) : ?>
								<p>
									<?php if ( $billing_address['address_1'] ) : ?>
										<?php echo esc_html( $billing_address['address_1'] . ', ' ); ?>
									<?php endif; ?>
									<?php if ( $billing_address['address_2'] ) : ?>
										<?php echo esc_html( $billing_address['address_2'] . ', ' ); ?>
									<?php endif; ?>
									<?php if ( $billing_address['city'] ) : ?>
										<?php echo esc_html( $billing_address['city'] . ', ' ); ?>
									<?php endif; ?>
									<?php if ( $billing_address['state'] ) : ?>
										<?php echo esc_html( $billing_address['state'] . ', ' ); ?>
									<?php endif; ?>
									<?php if ( $billing_address['postcode'] ) : ?>
										<?php echo esc_html( $billing_address['postcode'] . ', ' ); ?>
									<?php endif; ?>
									<?php if ( $billing_address['country'] ) : ?>
										<?php echo esc_html( $billing_address['country'] ); ?>
									<?php endif; ?>
								</p>
							<?php endif; ?>

							<?php if ( $billing_address['phone'] ) : ?>
								<p><?php echo esc_html( $billing_address['phone'] ); ?></p>
							<?php endif; ?>

							<?php if ( $billing_address['email'] ) : ?>
								<p><?php echo esc_html( $billing_address['email'] ); ?></p>
							<?php endif; ?>
						</div>
						<div class="metageeks-order__footer-right">
							<table class="metageeks-order__footer-total">
								<?php foreach ( $order->get_order_item_totals() as $key => $total ) : ?>
									<tr>
										<td><?php echo esc_html( $total['label'] ); ?></td>
										<td><?php echo ( 'payment_method' === $key ) ? esc_html( $total['value'] ) : wp_kses_post( $total['value'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
									</tr>
								<?php endforeach; ?>
							</table>
						</div>
					</div>
				</div>
				<?php
			}
			?>
		</div>

		<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

		<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
			<div class="metageeks-orders__pagination woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
				<?php if ( 1 !== $current_page ) : ?>
					<a class="metageeks-orders__pagination-prev woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>">
						<?php
						bda_display_svg(
							array(
								'icon'   => 'pag_left_arr',
								'width'  => '24',
								'height' => '24',
							)
						);
						?>
						<?php esc_html_e( 'Previous', 'woocommerce' ); ?>
					</a>
				<?php endif; ?>

				<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
					<a class="metageeks-orders__pagination-next woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>">
						<?php esc_html_e( 'Next', 'woocommerce' ); ?>
						<?php
						bda_display_svg(
							array(
								'icon'   => 'pag_right_arr',
								'width'  => '24',
								'height' => '24',
							)
						);
						?>
					</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php else : ?>
		<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
			<a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"><?php esc_html_e( 'Browse products', 'woocommerce' ); ?></a>
			<?php esc_html_e( 'No order has been made yet.', 'woocommerce' ); ?>
		</div>
		<?php endif; ?>

</div>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
