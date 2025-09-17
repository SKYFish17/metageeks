<?php

  /**
   * Image with Text Block Template.
   *
   * @param   array $block The block settings and attributes.
   * @param   string $content The block inner HTML (empty).
   * @param   bool $is_preview True during AJAX preview.
   * @param   (int|string) $post_id The post ID this block is saved to.
   */

  // Create id attribute allowing for custom "anchor" value.
  $id = 'image-with-text-block-' . $block['id'];

if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

  // Create class attribute allowing for custom "className" and "align" values.
  $className = 'image-with-text-block';

if ( ! empty( $block['className'] ) ) {
	$className .= ' ' . $block['className'];
}

if ( ! empty( $block['align'] ) ) {
	$className .= ' align' . $block['align'];
}

$img   = get_field( 'image' );
$title = get_field( 'title' );
$description = get_field( 'description' );
$background_img = get_field( 'background_image' );
$background_form = get_field( 'background_form' );
$text_color_theme = get_field( 'text_color_theme' );
$image_position = get_field( 'image_position' );

?>

<section id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $className ); ?>">
	<?php if ( $background_img ) : ?>
		<img class="image-with-text-block__background-image image-with-text-block__background-image--form-<?php echo $background_form;?>" src="<?php echo $background_img['url']; ?>" alt="<?php echo $background_img['alt']; ?>">
	<?php endif; ?>
	<div class="image-with-text-block__wrapper image-with-text-block__wrapper--position-<?php echo $image_position;?>">
		<?php if ( $img ) : ?>
			<img class="image-with-text-block__image image-with-text-block__image--position-<?php echo $image_position;?>" src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
		<?php endif;?>
		<div class="image-with-text-block__content image-with-text-block__content--position-<?php echo $image_position;?>">
			<?php if ( $title ) : ?>
				<h3 class="image-with-text-block__title image-with-text-block__title--theme-<?php echo $text_color_theme;?>"><?php echo esc_attr( $title ); ?></h3>
			<?php endif;?>
			<?php if ( $description ) : ?>
				<div class="image-with-text-block__description image-with-text-block__description--theme-<?php echo $text_color_theme;?>"><?php echo $description; ?></div>
			<?php endif;?>
		</div>
	</div>
</section>
