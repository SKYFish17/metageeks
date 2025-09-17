<?php
/**
 * Template name: Blog section template
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package MetaGeeks
 */

get_header(); ?>

<?php
	$title = get_field('title', $post->ID);
  $description = get_field( 'description', $post->ID );
  $form_id = get_field( 'form_id', $post->ID );
	$form_shortcode = '[gravityform id=' . $form_id . ' title=false description=false ajax=true]';
?>

<main id="main" class="container site-main blog">
	<?php if (function_exists('bda_posts_breadcrumbs')) bda_posts_breadcrumbs(); ?>

	<?php
	global $wp_query;
	$postsPerPage = 9;

	$args = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => $postsPerPage,
	);

	query_posts($args); ?>

	<?php if ( have_posts() ) : ?>

		<header class="blog__header">
			<h1 class="blog__title"><?php esc_attr_e('Our blog', 'metageeks'); ?></h1>

			<ul class="blog__categories blog__categories--list list-none">
				<li class="blog__categories-item">
					<span class="blog__categories-link blog__categories-link--active"><?php esc_attr_e('All', 'metageeks'); ?></span>
				</li>
				<?php $categories = get_categories([
					'taxonomy'     => 'category',
					'type'         => 'post',
					'child_of'     => 0,
					'parent'       => '',
					'orderby'      => 'name',
					'order'        => 'ASC',
					'hide_empty'   => 1
				]);

				foreach ($categories as $category) : ?>
					<li class="blog__categories-item">
						<a href="<?php echo get_category_link($category->term_id);?>" class="blog__categories-link"><?php echo $category->name;?></a>
					</li>
				<?php endforeach; ?>
			</ul>

			<div class="select__wrapper blog__categories-select-wrapper">
				<select name="post_categories" id="post_categories" class="select blog__categories blog__categories--select">
					<option value="<?php echo get_home_url() . '/blog';?>" selected><?php esc_attr_e('All', 'metageeks');?></option>
					<?php foreach ($categories as $category) : ?>
					<option value="<?php echo get_category_link($category->term_id);?>" class="blog__categories-option"><?php echo $category->name;?>
					</option>
				<?php endforeach; ?>
				</select>
			</div>

		</header><!-- .page-header -->

		<div id="ajax-posts" class="blog__list">
			<?php
			while ( have_posts() ) :
				the_post(); ?>
				<div class="blog__item-wrapper">
					<?php get_template_part( 'template-parts/content-blog', get_post_format() ); ?>
				</div>
			<?php endwhile;
			wp_reset_postdata();
			?>
		</div><!-- .blog__list -->

		<?php if (  $wp_query->max_num_pages > 1 ) : ?>
			<button type="button" class="btn btn-outline blog__button" id="posts_loadmore">
				<span class="btn__icon"><?php bda_display_svg( [ 'icon'   => 'arrow-down-big', 'width'  => '24', 'height' => '24', ] ); ?></span>
				<?php esc_attr_e('Load more', 'metageeks');?>
			</button>
		<?php endif;?>

	<?php else :
		get_template_part( 'template-parts/content', 'none' );
		endif;
	?>

</main><!-- #main -->

<div class="container blog__newsletter">
	<div class="newsletter-widget__form newsletter-widget__form--horizontal">
		<div class="newsletter-widget">
			<div class="newsletter-widget__wrapper">
				<div class="newsletter-widget__text">
					<h3 class="newsletter-widget__title"><?php echo esc_attr( $title ); ?></h3>
					<div class="newsletter-widget__description"><?php echo $description; ?></div>
				</div>
				<div class="newsletter-widget__form">
					<?php echo do_shortcode( $form_shortcode ); ?>
				</div>
			</div>
		</div>
	</div>
</div><!-- .blog__newsletter -->

<?php get_footer(); ?>
