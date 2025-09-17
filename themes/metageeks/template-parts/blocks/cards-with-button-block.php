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
  $id = 'cards-with-button-block-' . $block['id'];

if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

  // Create class attribute allowing for custom "className" and "align" values.
  $className = 'cards-with-button-block';

if ( ! empty( $block['className'] ) ) {
	$className .= ' ' . $block['className'];
}

if ( ! empty( $block['align'] ) ) {
	$className .= ' align' . $block['align'];
}

?>

<section id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $className ); ?>">
	<div class="container">

		<?php if ( have_rows( 'items' ) ) : ?>

		<div class="cards-with-button-block__list">
			<?php
			while ( have_rows( 'items' ) ) :
				the_row();
				$img   = get_sub_field( 'image' );
				$title = get_sub_field( 'title' );
				$background_color = get_sub_field( 'background_color' );
				$link  = get_sub_field( 'link' );
				if ( $link ) :
					$link_url    = $link['url'];
					$link_title  = $link['title'];
					$link_target = $link['target'] ? $link['target'] : '_self';
					endif;
				?>

				<div class="cards-with-button-block__item-wrapper">
					<div class="cards-with-button-block__item" style="background-color:<?php echo $background_color;?>">
					<div class="cards-with-button-block__content">
						<h3 class="cards-with-button-block__title"><?php echo esc_attr( $title ); ?></h3>
						<a class="btn btn-red cards-with-button-block__btn" href="<?php echo esc_url( $link_url ); ?>"
						target="<?php echo esc_attr( $link_target ); ?>">
							<?php bda_display_svg( [ 'icon'   => 'cart-white', 'width'  => '24', 'height' => '22', ] ); ?>
							<?php echo esc_html( $link_title ); ?>
						</a>
					</div>
					<div class="cards-with-button-block__image-wrapper">
						<img class="cards-with-button-block__image" src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
					</div>
				</div>
				</div>

			<?php endwhile; ?>

		</div>

		<?php endif; ?>
	</div>
</section>
