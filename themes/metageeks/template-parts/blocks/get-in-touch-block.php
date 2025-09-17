<?php

  /**
   * Get in Touch Block Template.
   *
   * @param   array $block The block settings and attributes.
   * @param   string $content The block inner HTML (empty).
   * @param   bool $is_preview True during AJAX preview.
   * @param   (int|string) $post_id The post ID this block is saved to.
   */

  // Create id attribute allowing for custom "anchor" value.
  $id = 'get-in-touch-block-' . $block['id'];

if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

  // Create class attribute allowing for custom "className" and "align" values.
  $className = 'get-in-touch-block';

if ( ! empty( $block['className'] ) ) {
	$className .= ' ' . $block['className'];
}

if ( ! empty( $block['align'] ) ) {
	$className .= ' align' . $block['align'];
}

$img   = get_field( 'image' );
$title = get_field( 'title' );
$subtitle = get_field( 'subtitle' );
$background_color = get_field( 'background_color' );
$link  = get_field( 'link' );
if ( $link ) :
	$link_url    = $link['url'];
	$link_title  = $link['title'];
	$link_target = $link['target'] ? $link['target'] : '_self';
	endif;
?>

<section id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $className ); ?>" style="background-color:<?php echo $background_color;?>">
	<div class="container">
		<div class="get-in-touch-block__wrapper">
			<?php if ( $img ) : ?>
				<img class="get-in-touch-block__image" src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
			<?php endif;?>
			<?php if ( $title ) : ?>
				<h3 class="get-in-touch-block__title"><?php echo esc_attr( $title ); ?></h3>
			<?php endif;?>
			<?php if ( $subtitle ) : ?>
				<div class="get-in-touch-block__subtitle"><?php echo esc_attr( $subtitle ); ?></div>
			<?php endif;?>
			<?php if ( $link ) : ?>
				<a class="btn btn-red get-in-touch-block__btn" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
			<?php endif;?>
		</div>
	</div>
</section>
