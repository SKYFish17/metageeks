<?php
/**
 * Block: Brand Products
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 *
 * @package MetaGeeks
 */

// Create id attribute allowing for custom "anchor" value.
$block_id = 'brand-products-block-' . $block['id'];

if ( ! empty( $block['anchor'] ) ) {
	$block_id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$block_class = 'brand-products-block mg-product-slider__section';

if ( ! empty( $block['className'] ) ) {
	$block_class .= ' ' . $block['className'];
}

if ( ! empty( $block['align'] ) ) {
	$block_class .= ' align' . $block['align'];
}

// Block data.
$block_title  = get_field( 'title' );
$block_link   = get_field( 'link' );
$block_brands = get_field( 'brands' );
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $block_class ); ?>">

	<div class="container">

		<div class="mg-product-slider__top">
			<h2 class="mg-product-slider__title"><?php echo esc_html( $block_title ); ?></h2>
			<a class="mg-product-slider__link" href="<?php echo esc_attr( $block_link['url'] ); ?>" target="<?php echo esc_attr( $block_link['target'] ); ?>">
				<?php echo esc_html( $block_link['title'] ); ?>
			</a>
		</div>

		<div class="brand-products-block__switches">
			<?php
			foreach ( $block_brands as $key => $brand_id ) :
				$term_data = get_term( $brand_id, 'product_brands' );
				?>
				<button class="brand-products-block__switches-item <?php echo 0 === $key ? 'active' : ''; ?>" data-term-id="<?php echo esc_attr( $brand_id ); ?>" type="button"><?php echo esc_html( $term_data->name ); ?></button>
			<?php endforeach; ?>
		</div>

	</div>

	<?php
	foreach ( $block_brands as $key => $brand_id ) :
		$args  = array(
			'post_type'      => 'product',
			'orderby'        => 'rand',
			'posts_per_page' => 10,
			'meta_query'     => array(
				array(
					'key'     => '_stock_status', // Exclude items that are out of stock.
					'value'   => 'outofstock',
					'compare' => '!=',
				),
			),
			'tax_query'      => array(
				array(
					'taxonomy' => 'product_brands',
					'field'    => 'term_id',
					'terms'    => $brand_id,
				),
			),
		);
		$query = new WP_Query( $args );
		?>
		<div class="mg-product-slider__container container <?php echo 0 !== $key ? 'hide-slider' : ''; ?>" data-term-id="<?php echo esc_attr( $brand_id ); ?>">

			<div class="mg-product-slider__wrapper">

				<div class="product-slider-block__products mg-product-slider">
					<?php
					while ( $query->have_posts() ) {
						$query->the_post();
						wc_get_template( 'content-product.php' );
					}
					wp_reset_postdata();
					?>
				</div>

				<button class="mg-product-slider__prev mg-product-slider__arrow" type="button">
					<?php
					bda_display_svg(
						array(
							'icon'   => 'pag_left_arr',
							'width'  => '24',
							'height' => '24',
						)
					);
					?>
				</button>

				<button class="mg-product-slider__next mg-product-slider__arrow" type="button">
					<?php
					bda_display_svg(
						array(
							'icon'   => 'pag_right_arr',
							'width'  => '24',
							'height' => '24',
						)
					);
					?>
				</button>

			</div>

		</div>
	<?php endforeach; ?>

</section>
