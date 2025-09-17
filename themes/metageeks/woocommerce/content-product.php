<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
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

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$is_product_limited_qty_class = ( 0 === strpos( $product->get_availability()['availability'], 'Only' ) ) ? 'limited' : '';
?>

<div class="product product-type-<?php echo esc_attr( $product->get_type() ); ?>" data-product-id="<?php echo $product->get_id(); ?>">
	<?php $link = get_the_permalink(); ?>

	<a class="product-link" href="<?php echo esc_url( $link ) ?>">
		<div class="status <?php echo $is_product_limited_qty_class; ?>">
			<!-- Add product sales status -->
			<?php echo wc_get_template( 'loop/sale-flash.php' ); ?>

			<!-- Add product availability status -->
			<?php echo wc_get_stock_html( $product ); ?>
		</div>

		<?php
			echo woocommerce_get_product_thumbnail();
		?>

		<div class="text">
			<!-- Add product availability status -->
			<?php echo wc_get_stock_html( $product ); ?>

			<h2 class="product-title"><?php the_title(); ?></h2>

			<?php wc_get_template( 'loop/price.php' ); ?>
		</div>
	</a>

	<div class="price-and-button">
		<?php woocommerce_template_loop_add_to_cart(); ?>

		<a class="btn btn-product-card-inner-link" href="<?php echo esc_url( $link ) ?>">View Product</a>

		<?php wc_get_template( 'loop/price.php' ); ?>
	</div>
</div>
