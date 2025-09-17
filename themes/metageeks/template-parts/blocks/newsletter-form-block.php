<?php

  /**
   * Newsletter Widget Block Template.
   *
   * @param   array $block The block settings and attributes.
   * @param   string $content The block inner HTML (empty).
   * @param   bool $is_preview True during AJAX preview.
   * @param   (int|string) $post_id The post ID this block is saved to.
   */

  // Create id attribute allowing for custom "anchor" value.
  $id = 'newsletter-widget-block-' . $block['id'];

  if ( !empty( $block['anchor'] ) ) {
    $id = $block['anchor'];
  }

  // Create class attribute allowing for custom "className" and "align" values.
  $className = 'newsletter-widget__form newsletter-widget__form--horizontal';

  if ( !empty( $block['className'] ) ) {
    $className .= ' ' . $block['className'];
  }

  if ( !empty( $block['align'] ) ) {
    $className .= ' align' . $block['align'];
  }

?>
<?php
  $title = get_field( 'title' );
  $description = get_field( 'description' );
  $form_id = get_field( 'form_id' );
  $img = get_field( 'image' );

	$form_shortcode = '[gravityform id=' . $form_id . ' title=false description=false ajax=true]';
?>

<div id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $className ); ?>">
<div class="newsletter-widget">
			<div class="newsletter-widget__wrapper">
				<?php if ( $img ) : ?>
					<img class="newsletter-widget__image" src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
				<?php endif; ?>
				<div class="newsletter-widget__text">
					<?php if ( $title ) : ?>
					<h3 class="newsletter-widget__title"><?php echo esc_attr( $title ); ?></h3>
					<?php endif; ?>
					<?php if ( $description ) : ?>
					<div class="newsletter-widget__description"><?php echo $description; ?></div>
					<?php endif; ?>
				</div>
				<div class="newsletter-widget__form">
					<?php echo do_shortcode( $form_shortcode ); ?>
				</div>
			</div>
		</div>
</div>
