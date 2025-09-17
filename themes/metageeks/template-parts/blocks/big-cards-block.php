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
  $id = 'big-cards-block-' . $block['id'];

  if ( !empty( $block['anchor'] ) ) {
    $id = $block['anchor'];
  }

  // Create class attribute allowing for custom "className" and "align" values.
  $className = 'big-cards-block';

  if ( !empty( $block['className'] ) ) {
    $className .= ' ' . $block['className'];
  }

  if ( !empty( $block['align'] ) ) {
    $className .= ' align' . $block['align'];
  }

?>

<section id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $className ); ?>">
	<div class="container">

	<?php if( have_rows('items') ): ?>

				<div class="big-cards-block__list">
					<?php while( have_rows('items') ) : the_row();
					$img = get_sub_field('image');
					$link = get_sub_field('link');
					 if( $link ):
						$link_url = $link['url'];
						$link_title = $link['title'];
						$link_target = $link['target'] ? $link['target'] : '_self';
					endif;
					?>

						<div class="big-cards-block__item">
							<?php if ($link) : ?>
								<a class="big-cards-block__link" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>" title="<?php echo esc_html( $link_title ); ?>">
									<img class="big-cards-block__image" src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
								</a>
								<?php else : ?>
									<img class="big-cards-block__image" src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
								<?php endif; ?>
						</div>

					<?php endwhile; ?>

				</div>

			<?php endif; ?>
	</div>
</section>
