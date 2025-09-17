<?php
/**
 * Deal of the week Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 *
 * @package MetaGeeks
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'deal-of-the-week-block-' . $block['id'];

if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'deal-of-the-week-block';

if ( ! empty( $block['className'] ) ) {
	$className .= ' ' . $block['className'];
}

if ( ! empty( $block['align'] ) ) {
	$className .= ' align' . $block['align'];
}

global $product;

$block_title = get_field( 'title' );
// get product id from acf field
$product_id = get_field( 'product' );
// get product object-data form wc
$product                    = wc_get_product( $product_id );
$product_title              = $product->get_title();
$product_desc               = $product->get_short_description();
$price_html                 = $product->get_price_html();
$product_image_id           = $product->get_image_id();
$product_gallery_images_ids = $product->get_gallery_image_ids();

// Push all product images in an one array
array_unshift( $product_gallery_images_ids, $product_image_id );
?>

<section id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $className ); ?>">
	<div class="container">
		<div class="content">
			<div class="text">
				<h2 class="title"><?php echo $block_title; ?></h2>
				<a class="subtitle" href="<?php echo esc_attr( get_permalink( $product_id ) ); ?>"><?php echo $product_title; ?></a>
				<p class="desc"><?php echo $product_desc; ?></p>

				<div class="shop">
					<p class="prices">
						<?php echo $price_html; ?>
					</p>
					<?php woocommerce_template_loop_add_to_cart(); ?>
				</div>
			</div>

			<div class="images">
				<?php
				foreach ( $product_gallery_images_ids as $image_id ) {
					$image_link = wp_get_attachment_url( $image_id );
					?>

					<div class="image-wrapper">
						<a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>">
							<img src="<?php echo $image_link; ?>" alt="<?php echo $product_title; ?>">
						</a>
					</div>

					<?php
				}
				?>
			</div>
		</div>
	</div>
</section>
