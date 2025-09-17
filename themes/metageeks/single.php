<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package MetaGeeks
 */

get_header(); ?>

<div class="container site-main">
	<?php if (function_exists('bda_posts_breadcrumbs')) bda_posts_breadcrumbs(); ?>

	<?php while ( have_posts() ) : the_post(); ?>
		<div class="blog__single">

			<header class="blog__single-header">

				<?php the_title( '<h1 class="blog__single-title">', '</h1>' ); ?>

				<?php if ( is_single() && has_excerpt() ) : ?>
					<div class="blog__single-excerpt">
						<?php echo get_the_excerpt(); ?>
					</div>
				<?php endif; ?>

				<?php
					if ( has_post_thumbnail() ) : ?>
					<div class="blog__single-thumbnail">
						<?php echo get_the_post_thumbnail( null, 'full' ); ?>
					</div>
				<?php endif; ?>

				<?php if ( 'post' === get_post_type() ) : ?>
					<div class="blog__single-meta">
						<?php bda_posted_on(); ?>
					</div><!-- .entry-meta -->
				<?php endif; ?>

			</header><!-- .entry-header -->

			<div class="blog__single-wrapper">

				<div class="blog__single-content">
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
					<footer class="blog__single-footer">
						<?php bda_blog_post_footer(); ?>
					</footer><!-- .entry-footer -->
				</div>

				<div class="blog__single-sidebar">
					<?php is_single() && get_sidebar(); ?>
				</div>

			</div>

		</div>
	<?php endwhile; // End of the loop. ?>

</div>

<?php get_footer(); ?>
