<?php

  /**
   * Blog posts Block Template.
   *
   * @param   array $block The block settings and attributes.
   * @param   string $content The block inner HTML (empty).
   * @param   bool $is_preview True during AJAX preview.
   * @param   (int|string) $post_id The post ID this block is saved to.
   */

  // Create id attribute allowing for custom "anchor" value.
  $id = 'blog-posts-block-' . $block['id'];

  if ( !empty( $block['anchor'] ) ) {
    $id = $block['anchor'];
  }

  // Create class attribute allowing for custom "className" and "align" values.
  $className = 'blog-posts-block';

  if ( !empty( $block['className'] ) ) {
    $className .= ' ' . $block['className'];
  }

  if ( !empty( $block['align'] ) ) {
    $className .= ' align' . $block['align'];
  }

?>
<?php
	$block_title = get_field( 'title' );
	$terms = get_field( 'post_category' );
	if ( $terms ) :
	endif;
?>

<section id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $className ); ?>">
	<div class="container">
		<h2 class="blog-posts-block__title mg-h1 mg-h1-center"><?php echo $block_title; ?></h2>
		<?php
		$args = array(
			'post_type' => 'post',
			'category' => 'posts',
			'posts_per_page' => 5,
			'orderby' => 'date',
			'post_status' => 'publish',
			'cat' => $terms,
		);

		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			$count_posts = $query->found_posts;
			?>

			<div class="blog-posts-block__list list <?php if ($count_posts <= 3) echo 'no-slider';?>">

				<?php while ( $query->have_posts() ) {
					$query->the_post();
					?>

					<div class="blog-posts-block__item-wrapper blog__item-wrapper">
						<?php get_template_part( 'template-parts/content-blog', get_post_format() ); ?>
					</div>

				<?php } ?>

			</div>

			<?php
		}
		wp_reset_postdata();
		?>
	</div>
</section>
