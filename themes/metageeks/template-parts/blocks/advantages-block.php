<?php

  /**
   * Advantages Block Template.
   *
   * @param   array $block The block settings and attributes.
   * @param   string $content The block inner HTML (empty).
   * @param   bool $is_preview True during AJAX preview.
   * @param   (int|string) $post_id The post ID this block is saved to.
   */

  // Create id attribute allowing for custom "anchor" value.
  $id = 'advantages-block-' . $block['id'];

  if ( !empty( $block['anchor'] ) ) {
    $id = $block['anchor'];
  }

  // Create class attribute allowing for custom "className" and "align" values.
  $className = 'advantages-block';

  if ( !empty( $block['className'] ) ) {
    $className .= ' ' . $block['className'];
  }

  if ( !empty( $block['align'] ) ) {
    $className .= ' align' . $block['align'];
  }

?>
<?php
  $items = get_field( 'items' );
?>

<section id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $className ); ?>">
	<div class="container">
			<h2 class="visually-hidden">Advantages</h2>

			<?php if ( $items ) : ?>

				<ul class="list">

					<?php foreach ( $items as $item ) :
					$title = $item['title'];
					$desc = $item['desc'];
					$img = $item['img'];
					?>

						<li class="item">
							<div class="img-wrapper">
								<img src="<?php echo $img['url']; ?>" width="40" height="40" alt="<?php echo $title; ?>">
							</div>
							<div class="text">
								<h3 class="title"><?php echo $title; ?></h3>
								<p class="desc"><?php echo $desc; ?></p>
							</div>
						</li>

					<?php endforeach; ?>

				</ul>

			<?php endif; ?>
	</div>
</section>
