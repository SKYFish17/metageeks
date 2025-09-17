<?php
/**
 * Ajax posts loading
 */

function posts_loadmore_ajax(){

	$postsPerPage = (isset($_POST["postsPerPage"])) ? $_POST["postsPerPage"] : 9;
	$page = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] : 0;

	header("Content-Type: text/html");

	$args = array(
			'suppress_filters' => true,
			'post_type' => 'post',
			'post_status' => 'publish',
			'posts_per_page' => $postsPerPage,
			'paged'    => $page,
	);

	$loop = new WP_Query($args);

	$out = '';

	if ($loop -> have_posts()) :
		while ($loop -> have_posts()) :
			$loop -> the_post(); ?>
			<div class="blog__item-wrapper">
				<?php echo get_template_part( 'template-parts/content-blog', get_post_format() ); ?>
			</div>
		<?php endwhile;
	endif;
	wp_reset_postdata();
	die();
}

add_action('wp_ajax_nopriv_posts_loadmore_ajax', 'posts_loadmore_ajax');
add_action('wp_ajax_posts_loadmore_ajax', 'posts_loadmore_ajax');
