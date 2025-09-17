<?php

  /**
   * Images in the layout Block Template.
   *
   * @param   array $block The block settings and attributes.
   * @param   string $content The block inner HTML (empty).
   * @param   bool $is_preview True during AJAX preview.
   * @param   (int|string) $post_id The post ID this block is saved to.
   */

  // Create id attribute allowing for custom "anchor" value.
  $id = 'images-in-layout-block-' . $block['id'];

  if ( !empty( $block['anchor'] ) ) {
    $id = $block['anchor'];
  }

  // Create class attribute allowing for custom "className" and "align" values.
  $className = 'images-in-layout-block';

  if ( !empty( $block['className'] ) ) {
    $className .= ' ' . $block['className'];
  }

  if ( !empty( $block['align'] ) ) {
    $className .= ' align' . $block['align'];
  }

?>
<?php
  $left_item = get_field( 'left_item' );
	$right_item = get_field( 'right_item' );
	$center_items = get_field( 'center_items' );
	$center_top_item = $center_items['top_item'];
	$center_bottom_item = $center_items['bottom_item'];
?>

<section id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $className ); ?>">
	<div class="container">
		<ul class="images-list">
			<li class="item item-left">
				<?php
				$img = $left_item['image'];
				$mobile_img = $left_item['mobile_image'];
				$link = $left_item['link']['url'];
				?>
				<a href="<?php echo $link; ?>">
					<?php if ( ! empty( $mobile_img ) ) : ?>
						<img
							src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>"
							srcset="<?php echo $mobile_img['url']; ?> 575w, <?php echo $img['url']; ?>"
						>
					<?php else: ?>
						<img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
					<?php endif; ?>
				</a>
			</li>
			<li class="item item-top-center">
				<?php
				$img = $center_top_item['image'];
				$mobile_img = $center_top_item['mobile_image'];
				$link = $center_top_item['link']['url'];
				?>
				<a href="<?php echo $link; ?>">
					<?php if ( ! empty( $mobile_img ) ) : ?>
						<img
							src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>"
							srcset="<?php echo $mobile_img['url']; ?> 575w, <?php echo $img['url']; ?>"
						>
					<?php else: ?>
						<img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
					<?php endif; ?>
				</a>
			</li>
			<li class="item item-bottom-center">
				<?php
				$img = $center_bottom_item['image'];
				$link = $center_bottom_item['link']['url'];
				?>
				<a href="<?php echo $link; ?>">
					<img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
				</a>
			</li>
			<li class="item item-right">
				<?php
				$img = $right_item['image'];
				$mobile_img = $right_item['mobile_image'];
				$link = $right_item['link']['url'];
				?>
				<a href="<?php echo $link; ?>">
					<?php if ( ! empty( $mobile_img ) ) : ?>
						<img
							src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>"
							srcset="<?php echo $mobile_img['url']; ?> 575w, <?php echo $img['url']; ?>"
						>
					<?php else: ?>
						<img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
					<?php endif; ?>
				</a>
			</li>
		</ul>
	</div>
</section>
