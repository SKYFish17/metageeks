<?php

  /**
   * Images with links Block Template.
   *
   * @param   array $block The block settings and attributes.
   * @param   string $content The block inner HTML (empty).
   * @param   bool $is_preview True during AJAX preview.
   * @param   (int|string) $post_id The post ID this block is saved to.
   */

  // Create id attribute allowing for custom "anchor" value.
  $id = 'images-with-links-block-' . $block['id'];

  if ( !empty( $block['anchor'] ) ) {
    $id = $block['anchor'];
  }

  // Create class attribute allowing for custom "className" and "align" values.
  $className = 'images-with-links-block';

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
		<?php
		if ( count( $items ) ) { ?>

			<ul class="list">

			<?php foreach ( $items as $item ) {
				$link = $item['link'];
				$link_target = $link['target'] ? $link['target'] : '_self';
				$img = $item['img'];
				?>

				<li class="item">
					<a href="<?php echo esc_attr( $link['url'] ); ?>" target="<?php echo esc_attr( $link_target ); ?>" class="link">
						<img src="<?php echo $img['url'] ?>" width="306" height="227" alt="<?php echo $img['alt'] ?>" class="img">
					</a>
				</li>

			<?php } ?>

			</ul>
		<?php } ?>
	</div>
</section>
