<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package MetaGeeks
 */

get_header(); ?>

	<main id="main" class="container site-main">

		<section class="error-404">

			<header class="error-404__header">
				<p class="error-404__subtitle"><?php esc_html_e( "404 error", 'metageeks' ); ?></p>
				<h1 class="error-404__title"><?php esc_html_e( "We can’t find that page", 'metageeks' ); ?></h1>
			</header><!-- .404__header -->

			<div class="error-404__content">
				<p class="error-404__description"><?php esc_html_e( "Sorry, the page you are looking for doesn't exist or has been moved.", 'metageeks' ); ?></p>
				<img class="error-404__image" src="<?php echo get_template_directory_uri().'/build/images/404.jpg'; ?>" width="120" height="83" alt="not-found">
			</div><!-- .error-404__content -->

			<footer class="error-404__footer">
				<?php if ( wp_get_referer() ) : ?>
					<button type="button" href="<?php wp_get_referer() ?>" class="btn btn-outline error-404__btn" onclick="javascript:history.back()">
					<?php bda_display_svg( [ 'icon'   => 'arrow-left', 'width'  => '24', 'height' => '24', ] ); ?>
					<?php esc_html_e( "Go back", 'metageeks' ); ?>
				</button>
				<?php endif; ?>
				<a class="btn btn-red error-404__btn" href="/"><?php esc_html_e( "Take me home", 'metageeks' ); ?></a>
			</footer>

		</section><!-- .error-404 -->

	</main><!-- #main -->

<?php get_footer(); ?>
