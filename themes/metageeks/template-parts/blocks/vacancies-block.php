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
  $id = 'vacancies-block-' . $block['id'];

if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

  // Create class attribute allowing for custom "className" and "align" values.
  $className = 'vacancies-block';

if ( ! empty( $block['className'] ) ) {
	$className .= ' ' . $block['className'];
}

if ( ! empty( $block['align'] ) ) {
	$className .= ' align' . $block['align'];
}

$block_title = get_field('title');
$block_subtitle = get_field('subtitle');
$available = 'There are no job opportunities at this time.';
$count = '';

if ( get_field('vacancies') ) :
	$count = count(get_field('vacancies'));
	if ( $count === 1 ) :
		$available = 'Job Available';
	else:
		$available = 'Jobs Available';
	endif;
endif;
?>

<section id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $className ); ?>">
	<div class="container">

		<header class="vacancies-block__header">
			<?php if ( $block_title ) : ?>
				<h2 class="vacancies-block__title"><?php echo $block_title; ?></h2>
			<?php endif; ?>
			<?php if ($block_subtitle ) : ?>
				<div class="vacancies-block__subtitle"><?php echo $block_subtitle;?></div>
			<?php endif; ?>
				<div class="vacancies-block__count"><?php echo $count . ' ' . $available;?></div>
		</header>

		<?php if ( have_rows( 'vacancies' ) ) : ?>

		<div class="vacancies-block__list">
			<?php
			while ( have_rows( 'vacancies' ) ) :
				the_row();
				$vacancy = get_sub_field( 'vacancy' );
				$status = get_sub_field( 'status' );

				if ( "ongoing" === $status['value'] ) :
					$status_class = "green";
				else:
					$status_class = "orange";
				endif;
				$salary = get_sub_field( 'salary' );
				$info = get_sub_field( 'info' );
				?>

				<div class="vacancies-block__item">
					<div class="vacancies-block__info">
						<h3 class="vacancies-block__vacancy"><?php echo esc_attr( $vacancy ); ?></h3>
						<div class="vacancies-block__status vacancies-block__status--<?php echo $status_class;?>"><?php echo esc_attr( $status['label'] ); ?></div>
					</div>
					<div class="vacancies-block__details">
						<?php if ( isset( $salary ) ) : ?>
							<div class="vacancies-block__salary">
								<?php
								echo 'GBP ';
								echo $salary['min'] ? esc_attr( $salary['min'] ) : '';
								echo $salary['max'] ? ' - ' . esc_attr( $salary['max'] ) : ''; ?>
							</div>
						<?php endif; ?>
						<?php if ( $info ) : ?>
							<div class="vacancies-block__info">
								<?php echo esc_attr( $info ); ?>
							</div>
						<?php endif; ?>
					</div>
					<a class="btn btn-red vacancies-block__btn" href="/apply-for-a-job/?vacancy=<?php echo urlencode( $vacancy );?>"
						target="_blank">Apply</a>
				</div>

			<?php endwhile; ?>

		</div>

		<?php endif; ?>
	</div>
</section>
