<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package MetaGeeks
 */

?>

<?php
$post_title = get_the_title();
$title_length = strlen( $post_title );
$content_length = 18;
$post_url = esc_url( get_permalink() );
$post_categories = get_the_category();

$i = 0;
?>

	<article <?php post_class( 'post-container blog__item' ); ?>>

		<header class="blog__item-header">

			<div class="blog__item-image">
				<?php the_post_thumbnail( array( 700, 500 ) ); ?>
			</div>

			<ul class="blog__item-categories list-none">
				<?php
				foreach ($post_categories as $category) : ?>
				<li class="blog__item-category">
					<a href="<?php echo get_category_link($category->term_id);?>" class="blog__item-category-link no-underline" data-category="<?php echo $category->term_id;?>"><?php echo $category->name;?>
					<?php if (count($post_categories) > 1 && $i < ( count($post_categories) - 1) ) : echo ', ';
					endif;
					?></a>
				</li>
				<?php $i++; endforeach; ?>
			</ul>

			<h2 class="blog__item-title">
				<a class="blog__item-title-link no-underline" href="<?php echo $post_url; ?>" rel="bookmark"><?php echo $post_title; ?></a>
				<span class="blog__item-title-icon"><?php bda_display_svg( [ 'icon'   => 'arrow-up-right', 'width'  => '24', 'height' => '24', ] ); ?></span>
			</h2>

		</header><!-- .blog__item-header -->

		<div class="blog__item-content">
			<?php
			if ($title_length < 20) :
				$content_length = 27;
			elseif ($title_length >= 20 && $title_length < 42) :
				$content_length = 18;
			elseif ($title_length >= 42) :
				$content_length = 10;
			endif;
			?>
			<?php echo has_excerpt() ? wp_trim_words( get_the_excerpt(), $content_length ) : wp_trim_words( get_the_content(), $content_length );?>
		</div><!-- .blog__item-content -->

	</article><!-- #post-## -->
