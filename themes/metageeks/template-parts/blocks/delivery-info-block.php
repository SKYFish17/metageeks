<?php

  /**
   * Delivery Info Block Template.
   *
   * @param   array $block The block settings and attributes.
   * @param   string $content The block inner HTML (empty).
   * @param   bool $is_preview True during AJAX preview.
   * @param   (int|string) $post_id The post ID this block is saved to.
   */

  // Create id attribute allowing for custom "anchor" value.
  $id = 'delivery-block-' . $block['id'];

if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

  // Create class attribute allowing for custom "className" and "align" values.
  $className = 'delivery-block';

if ( ! empty( $block['className'] ) ) {
	$className .= ' ' . $block['className'];
}

if ( ! empty( $block['align'] ) ) {
	$className .= ' align' . $block['align'];
}

$block_title = get_field('block_title');
$block_subtitle = get_field('block_subtitle');
?>

<section id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $className ); ?>">
	<div class="container">

		<?php if ( $block_title || $block_subtitle ) : ?>
			<header class="delivery-block__header">
				<h2 class="delivery-block__title"><?php echo $block_title; ?></h2>
				<div class="delivery-block__subtitle"><?php echo $block_subtitle;?></div>
			</header>
		<?php endif; ?>

		<?php if ( have_rows( 'delivery_groups' ) ) : ?>

		<div class="delivery-block__groups">
			<?php
			while ( have_rows( 'delivery_groups' ) ) :
				the_row();
				$group_title = get_sub_field( 'group_title' );
				$title_color = get_sub_field( 'title_color' );
				?>
				<div class="delivery-block__group">
					<h2 class="delivery-block__group-title" style="background-color:<?php echo esc_attr( $title_color );?>"><?php echo esc_attr( $group_title );?></h2>

					<?php
					if ( have_rows( 'delivery_info' ) ) :
						while ( have_rows( 'delivery_info' ) ) :
						the_row();
						$img   = get_sub_field( 'image' );
						$title   = get_sub_field( 'title' );
						$description   = get_sub_field( 'description' );
						?>

						<div class="delivery-block__info">
							<?php if ( $img ): ?>
								<img class="delivery-block__info-image" src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>">
							<?php endif; ?>
							<div class="delivery-block__info-text">
								<?php if ( $title ) : ?>
									<h3 class="delivery-block__info-title"><?php echo esc_attr( $title ); ?></h3>
								<?php endif; ?>
								<?php if ( $description ) : ?>
									<div class="delivery-block__info-description"><?php echo $description; ?></div>
								<?php endif; ?>
							</div>
							<div class="delivery-block__info-price">
								<?php if ( have_rows( 'price' ) ) : ?>
								<table class="delivery-block__info-table">
									<thead>
										<tr>
											<td><?php esc_attr_e('Order Total', 'metageeks');?></td>
											<td><?php esc_attr_e('Shipping Price', 'metageeks');?></td>
										</tr>
									</thead>
									<tbody>
										<?php while ( have_rows( 'price' ) ) :
											the_row();?>
											<tr>
												<td><?php echo esc_attr( get_sub_field( 'order_total' ) );?></td>
												<td><?php echo esc_attr( get_sub_field( 'shipping_price' ) );?></td>
											</tr>
										<?php
										endwhile;
										endif;?>
									</tbody>
								</table>
							</div>

						</div>
						<?php
						endwhile;
					endif;?>

				</div>
			<?php endwhile; ?>

		</div>

		<?php endif; ?>
	</div>
</section>
