<?php

  /**
   * Product Categories Block Template.
   *
   * @param   array $block The block settings and attributes.
   * @param   string $content The block inner HTML (empty).
   * @param   bool $is_preview True during AJAX preview.
   * @param   (int|string) $post_id The post ID this block is saved to.
   */

// Create id attribute allowing for custom "anchor" value.
$id = 'faq-block-' . $block['id'];

if ( !empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'faq-block';

if ( !empty( $block['className'] ) ) {
	$className .= ' ' . $block['className'];
}

if ( !empty( $block['align'] ) ) {
	$className .= ' align' . $block['align'];
}

?>
<?php
$title = get_field( 'title' );
$subtitle = get_field( 'subtitle' );
$show_search = get_field( 'show_search_form' );

?>

<section id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $className ); ?>">

	<div class="container">

		<?php if ( $title || $subtitle ) : ?>
			<header class="faq-block__header">
				<h2 class="faq-block__title"><?php echo $title; ?></h2>
				<div class="faq-block__subtitle"><?php echo $subtitle;?></div>
			</header>
		<?php endif; ?>

		<?php if ( $show_search ) : ?>
			<form class="faq-block__search search-form">
					<div class="search-form__wrapper">
						<label class="search-form__label">
							<span class="visually-hidden">Search query</span>
							<input class="search-form__input" type="text" name="faq_search_query" placeholder="Search">
						</label>
					</div>
				</form>
		<?php endif; ?>

		<div class="faq-block__list">
			<?php
			$i = 0;
			if ( have_rows( 'items' ) ):
				$fag_source = [];
				while ( have_rows( 'items' ) ): the_row();

					$question = get_sub_field( 'question' );
					$question_id = preg_replace('/\s+/', '_', strtolower( $question  ) );
					$answer = get_sub_field( 'answer' ); ?>
					<div id="<?php echo $question_id;?>" class="faq-block__item">
						<h3 class="faq-block__heading faq-block__heading--open">
							<div class="faq-block__heading-title"><?php echo $question ?></div>
							<div class="faq-block__heading-icon <?php echo 0 === $i ? 'faq-block__heading-icon--open' : '';?>">
								<?php
											bda_display_svg( [
												'icon'   => 'arrow-down',
												'width'  => 20,
												'height' => 20,
												'title' => 'Open'
											] );
										?>
							</div>
						</h3>

						<div class="faq-block__content" style="<?php echo 0 === $i ? '' : 'display: none;';?>">
							<?php echo $answer ?>
						</div>

					</div>
				<?php
				$fag_source[] = $question;
				$i++;
				endwhile; ?>
				<?php endif; ?>
		</div>
		<div id="faq_source" data-faq-source='<?php echo wp_json_encode( $fag_source );?>'></div>
	</div>
</section>
