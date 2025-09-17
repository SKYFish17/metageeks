<?php
/**
 * Template Name: Category landing page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package MetaGeeks
 */

get_header(); ?>

<div class="site-main">
	<div class="container">
		<?php if (function_exists('bda_posts_breadcrumbs')) bda_posts_breadcrumbs(); ?>
	</div>

	<?php while ( have_posts() ) : the_post(); ?>
		<div class="category-landing">

			<header class="category-landing__header container">
				<?php the_title( '<h1 class="category-landing__title">', '</h1>' ); ?>
			</header><!-- .entry-header -->

				<div class="category-landing__content">
					<?php the_content(); ?>
				</div>

		</div>
	<?php endwhile; // End of the loop. ?>

</div>

<?php get_footer(); ?>
