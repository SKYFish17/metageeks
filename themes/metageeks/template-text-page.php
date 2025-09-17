<?php
/**
 * Template Name: Text page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package MetaGeeks
 */

get_header(); ?>

<div class="container site-main">
	<?php if (function_exists('bda_posts_breadcrumbs')) bda_posts_breadcrumbs(); ?>

	<?php while ( have_posts() ) : the_post(); ?>
		<div class="page__single page__single--container-xs" <?php echo get_field('padding_bottom') ? ' style="padding-bottom:' . get_field('padding_bottom') . 'px;"' : '';?>

			<header class="page__single-header">

				<?php if ( get_field('show_title') ) :
					 the_title( '<h1 class="page__single-title">', '</h1>' );
				endif; ?>

				<?php
					if ( has_post_thumbnail() ) : ?>
					<div class="page__single-thumbnail">
						<?php echo get_the_post_thumbnail( null, 'full' ); ?>
					</div>
				<?php endif; ?>

			</header><!-- .entry-header -->

				<div class="page__single-content">
					<?php
						the_content(
							sprintf(
								wp_kses(
									/* translators: %s: Name of current post. */
									esc_html__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'metageeks' ),
									[
										'span' => [
											'class' => [],
										],
									]
								),
								the_title( '<span class="screen-reader-text">"', '"</span>', false )
							)
						);
					?>
				</div>

		</div>
	<?php endwhile; // End of the loop. ?>

</div>

<?php get_footer(); ?>
