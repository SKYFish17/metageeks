<?php

  /**
   * Product Categories Block Template.
   *
   * @param   array $block The block settings and attributes.
   * @param   string $content The block inner HTML (empty).
   * @param   bool $is_preview True during AJAX preview.
   * @param   (int|string) $post_id The post ID this block is saved to.
   */

  // Create id attribute allowing for custom "anchor" value.
  $id = 'product-categories-block-' . $block['id'];

if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

  // Create class attribute allowing for custom "className" and "align" values.
  $className = 'product-categories-block';

if ( ! empty( $block['className'] ) ) {
	$className .= ' ' . $block['className'];
}

if ( ! empty( $block['align'] ) ) {
	$className .= ' align' . $block['align'];
}

$title = get_field('title');

?>

<section id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $className ); ?>">
	<div class="container">
		<h2 class="product-categories-block__title"><?php echo esc_attr( $title );?></h2>

		<?php if ( have_rows( 'categories' ) ) : ?>

		<div class="product-categories-block__list">
			<?php
			while ( have_rows( 'categories' ) ) :
				the_row();
				$img   = get_sub_field( 'image' );
				$title = get_sub_field( 'title' );
				$category  = get_sub_field( 'category' );

				if ($category) :
					$category_title = $category->name;
					$category_url = get_category_link( $category->term_id );
				endif;

				?>

				<div class="product-categories-block__item">
					<a class="product-categories-block__link" href="<?php echo esc_url( $category_url ); ?>" title="<?php echo esc_attr( $category_title ); ?>">
					<div class="product-categories-block__image-wrapper">
						<img class="product-categories-block__image" src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
					</div>
						<div class="product-categories-block__content">
							<h3 class="product-categories-block__name"><?php echo esc_attr( $category_title ); ?></h3>
						</div>
					</a>
				</div>

			<?php endwhile; ?>

		</div>

		<?php endif; ?>
	</div>
</section>
