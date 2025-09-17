<?php

  /**
   * Big Cards Block Template.
   *
   * @param   array $block The block settings and attributes.
   * @param   string $content The block inner HTML (empty).
   * @param   bool $is_preview True during AJAX preview.
   * @param   (int|string) $post_id The post ID this block is saved to.
   */

  // Create id attribute allowing for custom "anchor" value.
  $id = 'featured-products-block-' . $block['id'];

  if ( !empty( $block['anchor'] ) ) {
    $id = $block['anchor'];
  }

  // Create class attribute allowing for custom "className" and "align" values.
  $className = 'featured-products-block';

  if ( !empty( $block['className'] ) ) {
    $className .= ' ' . $block['className'];
  }

  if ( !empty( $block['align'] ) ) {
    $className .= ' align' . $block['align'];
  }

	$block_title = get_field('title');
	$background_color = get_field('background_color');

?>

<section id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $className ); ?>" style="background-color:<?php echo $background_color;?> ">
	<div class="container">
		<?php if ( $block_title ) : ?>
		<h2 class="featured-products-block__title"><?php echo $block_title;?></h2>
		<?php endif;?>

		<?php
		$featured_products = get_field('products');
		if( $featured_products ):

			$args = array(
				'post_type' => 'product',
				'post__in' => $featured_products,
			);

			$query = new WP_Query( $args ); ?>

			<ul class="featured-products-block__list">
				<?php if ( $query->have_posts() ) :
						while ( $query->have_posts() ) :
							$query->the_post();
							global $product; ?>
							<div class="featured-products-block__item">
								<?php wc_get_template('content-product.php'); ?>
							</div>
						<?php endwhile;
					endif;
				?>
			</ul>



<?php endif; ?>
	</div>
</section>
