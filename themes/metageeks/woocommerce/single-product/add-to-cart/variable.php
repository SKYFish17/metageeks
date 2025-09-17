<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.5
 */

defined( 'ABSPATH' ) || exit;

global $product;

$attribute_keys  = array_keys( $attributes );
$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true ); ?>

<?php
$default_attributes = $product->get_default_attributes();

foreach( $available_variations as $item ) {
	if ( $default_attributes['pa_value'] === $item['attributes']['attribute_pa_value'] ) {
		$default_product_id = $item['variation_id'];
	}
}
?>

<ul class="product-order__variations product-order-variations">
	<?php foreach( $available_variations as $variation ) : ?>
		<li class="product-order-variations__item product-order-variation <?php echo ( $default_attributes['pa_value'] === $variation['attributes']['attribute_pa_value'] ) ? 'checked' : ''; ?>">
			<button class="product-order-variation__button" type="button" data-variation-name="<?php echo $variation['attributes']['attribute_pa_value']; ?>" data-variation-id="<?php echo $variation['variation_id']; ?>" data-variation-image-id="<?php echo $variation['image_id']; ?>">
				<span class="product-order-variation__price"><?php echo wc_price( $variation['display_price'] ); ?></span>
				<span class="product-order-variation__name"><?php echo $variation['attributes']['attribute_pa_value']; ?></span>
			</button>
		</li>
	<?php endforeach; ?>
</ul>

<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok. ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock"><?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'woocommerce' ) ) ); ?></p>
	<?php else : ?>
		<table class="variations" cellspacing="0" style="display: none;">
			<tbody>
				<?php foreach ( $attributes as $attribute_name => $options ) : ?>
					<tr>
						<td class="label"><label for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>"><?php echo wc_attribute_label( $attribute_name ); // WPCS: XSS ok. ?></label></td>
						<td class="value">
							<?php
								wc_dropdown_variation_attribute_options(
									array(
										'options'   => $options,
										'attribute' => $attribute_name,
										'product'   => $product,
									)
								);
								echo end( $attribute_keys ) === $attribute_name ? wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'woocommerce' ) . '</a>' ) ) : '';
							?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<div class="single_variation_wrap">
			<?php
				/**
				 * Hook: woocommerce_before_single_variation.
				 */
				do_action( 'woocommerce_before_single_variation' );

				woocommerce_single_variation(); ?>

				<div class="product-order__wrapper">
					<div class="product-order__controls product-order-controls">
						<div class="product-order-controls__add-to-cart">
							<div class="product-order-controls__inputs">
								<button class="product-order-controls__minus" type="button">
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

								<div class="woocommerce-variation-add-to-cart variations_button product-order-controls__count">
									<?php
									do_action( 'woocommerce_before_add_to_cart_quantity' );

									woocommerce_quantity_input(
										array(
											'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
											'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
											'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
										)
									);

									do_action( 'woocommerce_after_add_to_cart_quantity' );
									?>

									<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

									<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
									<input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
									<input type="hidden" name="variation_id" class="variation_id" value="0" />
								</div>

								<button class="product-order-controls__plus" type="button">
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
						</div>

						<button type="submit" class="btn btn-red single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>

						<?php echo do_shortcode( '[ti_wishlists_addtowishlist]' ); ?>
					</div>

				</div>
				<?php
				/**
				 * Hook: woocommerce_after_single_variation.
				 */
				do_action( 'woocommerce_after_single_variation' );
			?>
		</div>
	<?php endif; ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php
do_action( 'woocommerce_after_add_to_cart_form' );
