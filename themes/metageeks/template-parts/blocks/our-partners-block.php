<?php

  /**
   * Our partners Block Template.
   *
   * @param   array $block The block settings and attributes.
   * @param   string $content The block inner HTML (empty).
   * @param   bool $is_preview True during AJAX preview.
   * @param   (int|string) $post_id The post ID this block is saved to.
   */

  // Create id attribute allowing for custom "anchor" value.
  $id = 'our-partners-block-' . $block['id'];

  if ( !empty( $block['anchor'] ) ) {
    $id = $block['anchor'];
  }

  // Create class attribute allowing for custom "className" and "align" values.
  $className = 'our-partners-block';

  if ( !empty( $block['className'] ) ) {
    $className .= ' ' . $block['className'];
  }

  if ( !empty( $block['align'] ) ) {
    $className .= ' align' . $block['align'];
  }

?>
<?php
  $title = get_field( 'title' );
	$items = get_field( 'items' );
?>

<section id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $className ); ?>">
	<div class="container">
			<h2 class="mg-h1"><?php echo $title; ?></h2>

			<?php if ( $items ) : ?>

				<div class="list">

					<?php foreach ( $items as $item ) :
					$img = $item['logo'];
					?>

						<div class="item">
							<img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
						</div>

					<?php endforeach; ?>

				</div>

			<?php endif; ?>
	</div>
</section>
