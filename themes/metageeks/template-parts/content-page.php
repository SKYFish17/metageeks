<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package MetaGeeks
 */

?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php echo get_field('padding_bottom') ? ' style="padding-bottom:' . get_field('padding_bottom') . 'px;"' : '';?>>

		<?php if ( get_field('show_title') ) : ?>
			<header class="entry-header">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</header><!-- .entry-header -->
		<?php endif;?>

		<div class="entry-content">
			<?php
				the_content();

				wp_link_pages(
					[
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'metageeks' ),
						'after'  => '</div>',
					]
				);
				?>
		</div><!-- .entry-content -->

		<?php if ( get_edit_post_link() ) : ?>
			<footer class="entry-footer">
				<?php
					edit_post_link(
						sprintf(
							/* translators: %s: Name of current post */
							esc_html__( 'Edit %s', 'metageeks' ),
							the_title( '<span class="screen-reader-text">"', '"</span>', false )
						),
						'<span class="edit-link">',
						'</span>'
					);
				?>
			</footer><!-- .entry-footer -->
		<?php endif; ?>

	</article><!-- #post-## -->
