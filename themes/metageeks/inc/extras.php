<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package MetaGeeks
 */

/**
 * Returns true if a blog has more than 1 category, else false.
 *
 * @author WebDevStudios
 *
 * @return bool Whether the blog has more than one category.
 */
function bda_categorized_blog() {
	$category_count = get_transient( 'bda_categories' );

	if ( false === $category_count ) {
		$category_count_query = get_categories( [ 'fields' => 'count' ] );

		$category_count = isset( $category_count_query[0] ) ? (int) $category_count_query[0] : 0;

		set_transient( 'bda_categories', $category_count );
	}

	return $category_count > 1;
}

/**
 * Get an attachment ID from it's URL.
 *
 * @author WebDevStudios
 *
 * @param string $attachment_url The URL of the attachment.
 *
 * @return int    The attachment ID.
 */
function bda_get_attachment_id_from_url( $attachment_url = '' ) {
	global $wpdb;

	$attachment_id = false;

	// If there is no url, return.
	if ( '' === $attachment_url ) {
		return false;
	}

	// Get the upload directory paths.
	$upload_dir_paths = wp_upload_dir();

	// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image.
	if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {

		// If this is the URL of an auto-generated thumbnail, get the URL of the original image.
		$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );

		// Remove the upload path base directory from the attachment URL.
		$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );

		// Do something with $result.
		// phpcs:ignore phpcs:ignore WordPress.DB -- db call ok, cache ok, placeholder ok.
		$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM {$wpdb->posts} wposts, {$wpdb->postmeta} wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = %s AND wposts.post_type = 'attachment'", $attachment_url ) );
	}

	return $attachment_id;
}

/**
 * Shortcode to display copyright year.
 *
 * @author Haris Zulfiqar
 *
 * @param array $atts Optional attributes.
 *     $starting_year Optional. Define starting year to show starting year and current year e.g. 2015 - 2018.
 *     $separator Optional. Separator between starting year and current year.
 *
 * @return string Copyright year text.
 */
function bda_copyright_year( $atts ) {
	// Setup defaults.
	$args = shortcode_atts(
		[
			'starting_year' => '',
			'separator'     => ' - ',
		],
		$atts
	);

	$current_year = gmdate( 'Y' );

	// Return current year if starting year is empty.
	if ( ! $args['starting_year'] ) {
		return $current_year;
	}

	return esc_html( $args['starting_year'] . $args['separator'] . $current_year );
}

add_shortcode( 'bda_copyright_year', 'bda_copyright_year', 15 );

/**
 * Retrieve the URL of the custom logo uploaded, if one exists.
 *
 * @author Corey Collins
 */
function bda_get_custom_logo_url() {

	$custom_logo_id = get_theme_mod( 'custom_logo' );

	if ( ! $custom_logo_id ) {
		return;
	}

	$custom_logo_image = wp_get_attachment_image_src( $custom_logo_id, 'full' );

	if ( ! isset( $custom_logo_image[0] ) ) {
		return;
	}

	return $custom_logo_image[0];
}

/**
 * Display breadcrumbs for posts and pages.
 *
 */
function bda_posts_breadcrumbs() {
	$showOnHome = 0;
	$delimiter = '<span class="delimiter">&#47;</span>';
	$home = '<svg height="15" width="15" class="icon" aria-hidden="true" role="img">
		<title>home</title><use xlink:href="#home"></use></svg>';
	$showCurrent = 1;
	$before = '<span class="current">';
	$blog = '<a class="link" href="/blog">' . esc_attr('Blog', 'metageeks') . '</a>';
	$after = '</span>';

	global $post;
	$homeLink = get_bloginfo('url');
	if (is_home() || is_front_page()) {
		if ($showOnHome == 1) {
			echo '<div id="crumbs" class="breadcrumb breadcrumb--posts"><a class="link" href="' . $homeLink . '">' . $home . '</a></div>';
		}
	} else {
		echo '<div id="crumbs" class="breadcrumb breadcrumb--posts"><a class="link" href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
		if (is_category()) {
			$thisCat = get_category(get_query_var('cat'), false);
			if ($thisCat->parent != 0) {
				echo get_category_parents($thisCat->parent, true, ' ' . $delimiter . ' ');
			}
			echo $blog . ' ' . $delimiter . ' ' . $before . single_cat_title('', false) . $after;
		} elseif (is_search()) {
			echo $before . 'Search results for "' . get_search_query() . '"' . $after;
		} elseif (is_day()) {
			echo '<a class="link" href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
			echo '<a class="link" href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
			echo $before . get_the_time('d') . $after;
		} elseif (is_month()) {
			echo '<a class="link" href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
			echo $before . get_the_time('F') . $after;
		} elseif (is_year()) {
			echo $before . get_the_time('Y') . $after;
		} elseif (is_single() && !is_attachment()) {
			if (get_post_type() != 'post') {
				$post_type = get_post_type_object(get_post_type());
				$slug = $post_type->rewrite;
				echo '<a class="link" href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
				if ($showCurrent == 1) {
					echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
				}
			} else {
				$cat = get_the_category();
				$cat = $cat[0];
				$cats = get_category_parents($cat, true, ' ' . $delimiter . ' ');
				if ($showCurrent == 0) {
					$cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
				}
				echo $cats;
				if ($showCurrent == 1) {
					echo $before . get_the_title() . $after;
				}
			}
		} elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
			$post_type = get_post_type_object(get_post_type());
			echo $before . $post_type->labels->singular_name . $after;
		} elseif (is_attachment()) {
			$parent = get_post($post->post_parent);
			$cat = get_the_category($parent->ID);
			$cat = $cat[0];
			echo get_category_parents($cat, true, ' ' . $delimiter . ' ');
			echo '<a class="blog__breadcrumbs-link" href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
			if ($showCurrent == 1) {
				echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
			}
		} elseif (is_page() && !$post->post_parent) {
			if ($showCurrent == 1) {
				echo $before . get_the_title() . $after;
			}
		} elseif (is_page() && $post->post_parent) {
			$parent_id  = $post->post_parent;
			$breadcrumbs = array();
			while ($parent_id) {
				$page = get_page($parent_id);
				$breadcrumbs[] = '<a class="blog__breadcrumbs-link" href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
				$parent_id  = $page->post_parent;
			}
			$breadcrumbs = array_reverse($breadcrumbs);
			for ($i = 0; $i < count($breadcrumbs); $i++) {
				echo $breadcrumbs[$i];
				if ($i != count($breadcrumbs)-1) {
					echo ' ' . $delimiter . ' ';
				}
			}
			if ($showCurrent == 1) {
				echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
			}
		} elseif (is_tag()) {
			echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
		} elseif (is_author()) {
			global $author;
			$userdata = get_userdata($author);
			echo $before . 'Articles posted by ' . $userdata->display_name . $after;
		} elseif (is_404()) {
			echo $before . 'Error 404' . $after;
		}
		if (get_query_var('paged')) {
			if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
				echo ' (';
			}
			echo __('Page') . ' ' . get_query_var('paged');
			if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
				echo ')';
			}
		}
		echo '</div>';
	}
}

/**
 * Add custom color palette in Block editor.
 */
function bda_gutenberg_color_palette() {
	add_theme_support(
		'editor-color-palette', array(
			array(
				'name'  => esc_html__( 'Black', 'metageeks' ),
				'slug' => 'black',
				'color' => '#1D1F22',
			),
			array(
				'name'  => esc_html__( 'Steel Gray', 'metageeks' ),
				'slug' => 'gray',
				'color' => '#414248',
			),
			array(
				'name'  => esc_html__( 'White', 'metageeks' ),
				'slug' => 'white',
				'color' => '#fff',
			),
			array(
				'name'  => esc_html__( 'Purple', 'metageeks' ),
				'slug' => 'purple',
				'color' => '#81324A',
			),
			array(
				'name'  => esc_html__( 'Green', 'metageeks' ),
				'slug' => 'green',
				'color' => '#01B599',
			),
			array(
				'name'  => esc_html__( 'Red', 'metageeks' ),
				'slug' => 'red',
				'color' => '#DD3F3B',
			),
			array(
				'name'  => esc_html__( 'Orange', 'metageeks' ),
				'slug' => 'orange',
				'color' => '#F0AD52',
			),
		)
	);
}
add_action( 'after_setup_theme', 'bda_gutenberg_color_palette' );
