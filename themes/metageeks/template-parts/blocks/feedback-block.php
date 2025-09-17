<?php

  /**
   * Feedback Block Template.
   *
   * @param   array $block The block settings and attributes.
   * @param   string $content The block inner HTML (empty).
   * @param   bool $is_preview True during AJAX preview.
   * @param   (int|string) $post_id The post ID this block is saved to.
   */

  // Create id attribute allowing for custom "anchor" value.
  $id = 'feedback-block-' . $block['id'];

if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

  // Create class attribute allowing for custom "className" and "align" values.
  $className = 'feedback-block';

if ( ! empty( $block['className'] ) ) {
	$className .= ' ' . $block['className'];
}

if ( ! empty( $block['align'] ) ) {
	$className .= ' align' . $block['align'];
}

$title = get_field( 'title' );
$background_color = get_field( 'background_color' );
?>

<section id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $className ); ?>" style="background-color:<?php echo $background_color;?>">
	<div class="container">
		<h2 class="feedback-block__title"><?php echo $title; ?></h2>

		<div id="feedback" class="feedback-block__carousel">
		<!-- TrustBox widget - Carousel -->
			<div class="trustpilot-widget" data-locale="en-GB" data-template-id="53aa8912dec7e10d38f59f36" data-businessunit-id="5e07fc91ace5b50001743525" data-style-height="140px" data-style-width="100%" data-theme="light" data-stars="3,4,5" data-review-languages="en">
				<a href="https://uk.trustpilot.com/review/metageeks.co.uk" target="_blank" rel="noopener">See reviews on Trustpilot</a>
			</div>
		<!-- End TrustBox widget -->
		</div>

	</div>
</section>
