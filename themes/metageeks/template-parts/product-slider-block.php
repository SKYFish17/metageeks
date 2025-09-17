<?php
/**
 * Template for product slider blocks
 *
 * @package MetaGeeks
 */

$block_id               = $args['id'];
$block_class            = $args['class'];
$block_background_color = $args['background_color'];
$block_title            = $args['title'];
$block_link             = $args['link'];
$block_products         = $args['products'];
?>

<section id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $block_class . ' background-' . $block_background_color ); ?>">

	<div class="container">

		<div class="mg-product-slider__top">

			<h2 class="mg-product-slider__title"><?php echo esc_html( $block_title ); ?></h2>

			<?php if ( $block_link ) : ?>
				<a class="mg-product-slider__link" href="<?php echo esc_attr( $block_link['url'] ); ?>" target="<?php echo esc_attr( $block_link['target'] ); ?>">
					<?php echo esc_html( $block_link['title'] ); ?>
				</a>
			<?php endif; ?>

		</div>

	</div>

	<div class="mg-product-slider__container container">

		<div class="mg-product-slider__wrapper">

			<div class="product-slider-block__products mg-product-slider">
				<?php
				foreach ( $block_products as $key => $block_product ) {
					global $post;
					$post = $block_product; // phpcs:ignore
					setup_postdata( $block_product );
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

</section>
