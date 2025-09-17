<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package MetaGeeks
 */

/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @author WebDevStudios
 */
function bda_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf(
		$time_string,
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( DATE_W3C ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = '<span class="blog__single-meta-title">' . esc_attr('Published on', 'metageeks') . '</span>' . $time_string;
	$byline = '<span class="blog__single-meta-title">' . esc_attr('Written by', 'metageeks') . '</span>' . esc_attr( get_the_author() );

	 // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- XSS OK.
	echo '<span class="blog__single-meta-item"> ' . $byline . '</span><span class="blog__single-meta-item">' . $posted_on . '</span>';
}

/**
 * Prints HTML with meta information for the categories, tags and comments.
 *
 * @author WebDevStudios
 */
function bda_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_attr__( ', ', 'metageeks' ) );
		if ( $categories_list && bda_categorized_blog() ) {

			/* translators: the post category */
			printf( '<span class="cat-links">' . esc_attr__( 'Posted in %1$s', 'metageeks' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- XSS OK.
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_attr__( ', ', 'metageeks' ) );
		if ( $tags_list ) {

			/* translators: the post tags */
			printf( '<span class="tags-links">' . esc_attr__( 'Tagged %1$s', 'metageeks' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- XSS OK.
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( esc_attr__( 'Leave a comment', 'metageeks' ), esc_attr__( '1 Comment', 'metageeks' ), esc_attr__( '% Comments', 'metageeks' ) );
		echo '</span>';
	}

	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			esc_html__( 'Edit %s', 'metageeks' ),
			wp_kses_post( get_the_title( '<span class="screen-reader-text">"', '"</span>', false ) )
		),
		'<span class="edit-link">',
		'</span>'
	);
}

/**
 * Prints Post footer meta.
 *
 */
function bda_blog_post_footer() {
	$post_url = get_permalink();
	$post_image = get_the_post_thumbnail_url();
	$post_content = get_the_content();
	$post_title = get_the_title();

	// Facebook link.
	$facebook_link  = 'http://www.facebook.com/sharer.php?s=100';
	$facebook_link .= '&p[url]=' . $post_url;
	$facebook_link .= '&p[title]=' . strip_tags( $post_title );
	$facebook_link .= '&p[summary]=' . strip_tags( $post_content );
	$facebook_link .= '&p[images][0]=' . $post_image;

	// Twitter link.
	$twitter_link  = 'http://twitter.com/share';
	$twitter_link .= '?text=' . strip_tags( $post_content );
	$twitter_link .= '&url=' . $post_url;

	if ( 'post' === get_post_type() ) : ?>
		<div class="blog__single-footer-wrapper">
			<button type="button" id="copy_post_url_button" class="blog__single-button">
				<?php bda_display_svg( array( 'icon'   => 'copy', 'width'  => '20', 'height' => '20', ) ); ?>
				<span><?php esc_attr_e('Copy link', 'metageeks');?></span>
			</button>
			<ul class="blog__single-social-share list-none">
				<li class="blog__single-social-share-item">
					<a
						class="blog__single-social-share-link"
						href="<?php echo esc_url( $twitter_link ); ?>"
						title="Share a link to Twitter"
						onclick="window.open(this.href, this.title, 'toolbar=0, status=0, width=548, height=325'); return false"
						target="_parent">

						<?php
						bda_display_svg(
							[
								'icon'   => 'twitter-sp',
								'width'  => '20',
								'height' => '20',
							]
						);
						?>
						<span class="visually-hidden">Twitter</span>
					</a>
				</li>
				<li class="blog__single-social-share-item">
					<a
						class="blog__single-social-share-link"
						href="<?php echo esc_url( $facebook_link ); ?>"
						onclick="window.open(this.href, this.title, 'toolbar=0, status=0, width=548, height=325'); return false" title="Share a link to Facebook" target="_parent">

						<?php
						bda_display_svg(
							[
								'icon'   => 'facebook',
								'width'  => '20',
								'height' => '20',
							]
						);
						?>
						<span class="visually-hidden">Facebook</span>
					</a>
				</li>
			</ul>

		</button>
	<?php endif;
}

/**
 * Display SVG Markup.
 *
 * @author WebDevStudios
 *
 * @param array $args The parameters needed to get the SVG.
 */
function bda_display_svg( $args = [] ) {
	$kses_defaults = wp_kses_allowed_html( 'post' );

	$svg_args = [
		'svg'   => [
			'class'           => true,
			'aria-hidden'     => true,
			'aria-labelledby' => true,
			'role'            => true,
			'xmlns'           => true,
			'width'           => true,
			'height'          => true,
			'viewbox'         => true, // <= Must be lower case!
			'color'           => true,
			'stroke-width'    => true,
		],
		'g'     => [ 'color' => true ],
		'title' => [
			'title' => true,
			'id'    => true,
		],
		'path'  => [
			'd'     => true,
			'color' => true,
		],
		'use'   => [
			'xlink:href' => true,
		],
	];

	$allowed_tags = array_merge(
		$kses_defaults,
		$svg_args
	);

	echo wp_kses(
		bda_get_svg( $args ),
		$allowed_tags
	);
}

/**
 * Return SVG markup.
 *
 * @author WebDevStudios
 *
 * @param array $args The parameters needed to display the SVG.
 *
 * @return string Error string or SVG markup.
 */
function bda_get_svg( $args = [] ) {
	// Make sure $args are an array.
	if ( empty( $args ) ) {
		return esc_attr__( 'Please define default parameters in the form of an array.', 'metageeks' );
	}

	// Define an icon.
	if ( false === array_key_exists( 'icon', $args ) ) {
		return esc_attr__( 'Please define an SVG icon filename.', 'metageeks' );
	}

	// Set defaults.
	$defaults = [
		'color'        => '',
		'icon'         => '',
		'title'        => '',
		'desc'         => '',
		'stroke-width' => '',
		'height'       => '',
		'width'        => '',
	];

	// Parse args.
	$args = wp_parse_args( $args, $defaults );

	// Figure out which title to use.
	$block_title = ( $args['title'] ) ? $args['title'] : $args['icon'];

	// Generate random IDs for the title and description.
	$random_number  = wp_rand( 0, 99999 );
	$block_title_id = 'title-' . sanitize_title( $block_title ) . '-' . $random_number;
	$desc_id        = 'desc-' . sanitize_title( $block_title ) . '-' . $random_number;

	// Set ARIA.
	$aria_hidden     = ' aria-hidden="true"';
	$aria_labelledby = '';

	if ( $args['title'] && $args['desc'] ) {
		$aria_labelledby = ' aria-labelledby="' . $block_title_id . ' ' . $desc_id . '"';
		$aria_hidden     = '';
	}

	// Set SVG parameters.
	$color        = ( $args['color'] ) ? ' color="' . $args['color'] . '"' : '';
	$stroke_width = ( $args['stroke-width'] ) ? ' stroke-width="' . $args['stroke-width'] . '"' : '';
	$height       = ( $args['height'] ) ? ' height="' . $args['height'] . '"' : '';
	$width        = ( $args['width'] ) ? ' width="' . $args['width'] . '"' : '';

	// Start a buffer...
	ob_start();
	?>

	<svg
	<?php
		echo bda_get_the_content( $height ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- XSS OK.
		echo bda_get_the_content( $width ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- XSS OK.
		echo bda_get_the_content( $color ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- XSS OK.
		echo bda_get_the_content( $stroke_width ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- XSS OK.
	?>
		class="icon <?php echo esc_attr( $args['icon'] ); ?>"
	<?php
		echo bda_get_the_content( $aria_hidden ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- XSS OK.
		echo bda_get_the_content( $aria_labelledby ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- XSS OK.
	?>
		role="img">
		<title id="<?php echo esc_attr( $block_title_id ); ?>">
			<?php echo esc_html( $block_title ); ?>
		</title>

		<?php
		// Display description if available.
		if ( $args['desc'] ) :
			?>
			<desc id="<?php echo esc_attr( $desc_id ); ?>">
				<?php echo esc_html( $args['desc'] ); ?>
			</desc>
		<?php endif; ?>

		<?php
		// Use absolute path in the Customizer so that icons show up in there.
		if ( is_customize_preview() ) :
			?>
			<use xlink:href="<?php echo esc_url( get_parent_theme_file_uri( '/build/images/icons/sprite.svg#' . esc_html( $args['icon'] ) ) ); ?>"></use>
		<?php else : ?>
			<use xlink:href="#<?php echo esc_html( $args['icon'] ); ?>"></use>
		<?php endif; ?>

	</svg>

	<?php
	// Get the buffer and return.
	return ob_get_clean();
}

/**
 * Trim the title length.
 *
 * @author WebDevStudios
 *
 * @param array $args Parameters include length and more.
 *
 * @return string The title.
 */
function bda_get_the_title( $args = [] ) {
	// Set defaults.
	$defaults = [
		'length' => 12,
		'more'   => '...',
	];

	// Parse args.
	$args = wp_parse_args( $args, $defaults );

	// Trim the title.
	return wp_kses_post( wp_trim_words( get_the_title( get_the_ID() ), $args['length'], $args['more'] ) );
}

/**
 * Limit the excerpt length.
 *
 * @author WebDevStudios
 *
 * @param array $args Parameters include length and more.
 *
 * @return string The excerpt.
 */
function bda_get_the_excerpt( $args = [] ) {

	// Set defaults.
	$defaults = [
		'length' => 20,
		'more'   => '...',
		'post'   => '',
	];

	// Parse args.
	$args = wp_parse_args( $args, $defaults );

	// Trim the excerpt.
	return wp_trim_words( get_the_excerpt( $args['post'] ), absint( $args['length'] ), esc_html( $args['more'] ) );
}

/**
 * Echo the copyright text saved in the Customizer.
 *
 * @author WebDevStudios
 */
function bda_display_copyright_text() {
	// Grab our customizer settings.
	$copyright_text = get_theme_mod( 'bda_copyright_text' );

	if ( $copyright_text ) {
		echo bda_get_the_content( do_shortcode( $copyright_text ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- XSS OK.
	}
}

/**
 * Display the social links saved in the customizer.
 *
 * @author WebDevStudios
 */
function bda_display_social_network_links() {
	// Create an array of our social links for ease of setup.
	// Change the order of the networks in this array to change the output order.
	$social_networks = [
		'facebook',
		'instagram',
		'linkedin',
		'twitter',
	];

	?>
	<ul class="flex social-icons menu">
		<?php
		// Loop through our network array.
		foreach ( $social_networks as $network ) :

			// Look for the social network's URL.
			$network_url = get_theme_mod( 'bda_' . $network . '_link' );

			// Only display the list item if a URL is set.
			if ( ! empty( $network_url ) ) :
				?>
				<li class="social-icon <?php echo esc_attr( $network ); ?> mr-2">
					<a href="<?php echo esc_url( $network_url ); ?>">
						<?php
						bda_display_svg(
							[
								'icon'   => $network . '-square',
								'width'  => '24',
								'height' => '24',
							]
						);
						?>
						<span class="screen-reader-text">
						<?php
						/* translators: the social network name */
						printf( esc_attr__( 'Link to %s', 'metageeks' ), ucwords( esc_html( $network ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- XSS OK.
						?>
						</span>
					</a>
				</li><!-- .social-icon -->
				<?php
			endif;
		endforeach;
		?>
	</ul><!-- .social-icons -->
	<?php
}

/**
 * Displays numeric pagination on archive pages.
 *
 * @author WebDevStudios
 *
 * @param array    $args  Array of params to customize output.
 * @param WP_Query $query The Query object; only passed if a custom WP_Query is used.
 */
function bda_display_numeric_pagination( $args = [], $query = null ) {
	if ( ! $query ) {
		global $wp_query;
		$query = $wp_query;
	}

	// Make the pagination work on custom query loops.
	$total_pages = isset( $query->max_num_pages ) ? $query->max_num_pages : 1;

	// Set defaults.
	$defaults = [
		'prev_text' => '&laquo;',
		'next_text' => '&raquo;',
		'mid_size'  => 4,
		'total'     => $total_pages,
	];

	// Parse args.
	$args = wp_parse_args( $args, $defaults );

	if ( null === paginate_links( $args ) ) {
		return;
	}
	?>

	<nav class="container pagination-container" aria-label="<?php esc_attr_e( 'numeric pagination', 'metageeks' ); ?>">
		<?php echo paginate_links( $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- XSS OK. ?>
	</nav>

	<?php
}

/**
 * Displays the mobile menu with off-canvas background layer.
 *
 * @author WebDevStudios
 *
 * @return string An empty string if no menus are found at all.
 */
function bda_display_mobile_menu() {
	// Bail if no mobile or primary menus are set.
	if ( ! has_nav_menu( 'mobile' ) && ! has_nav_menu( 'primary' ) ) {
		return '';
	}

	?>
	<div class="off-canvas-screen"></div>
	<nav class="off-canvas-container" aria-label="<?php esc_attr_e( 'Mobile Menu', 'metageeks' ); ?>" aria-hidden="true" tabindex="-1">
		<ul class="buttons-list">
			<li class="item">
			<a href="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ); ?>" name="user-account">
				<button>
					<?php
					bda_display_svg(
						[
							'icon'   => 'user',
							'width'  => '24',
							'height' => '24',
						]
					);
					?>
					<span class="caption">Account</span>
				</button>
				</a>
			</li>
			<li class="item">
				<a href="<?php echo esc_url( wc_get_cart_url() ); ?>">
					<button>
						<?php
						bda_display_svg(
							[
								'icon'   => 'heart',
								'width'  => '24',
								'height' => '22',
							]
						);
						?>
						<span class="caption">Wishlist</span>
					</button>
				</a>
			</li>
			<li class="item">
				<?php
				$link = get_field( 'contact_link', 'options' );

				if( $link ):
					$link_url = $link['url'];
					$link_title = $link['title'];
					$link_target = $link['target'] ? $link['target'] : '_self';
					?>
					<a href="<?php echo $link_url; ?>" target="<?php echo $link_target; ?>">
						<?php
						bda_display_svg(
							[
								'icon'   => 'email',
								'width'  => '24',
								'height' => '24',
							]
						);
						?>
						<span class="caption"><?php echo $link_title; ?></span>
					</a>
				<?php endif; ?>
			</li>
		</ul>
		<button class="off-canvas-open off-canvas-open-mobile is-visible mobile-menu" aria-expanded="true" aria-label="Open Menu">
			<span class="visually-hidden">close menu</span>
		</button>

		<?php
		// Display the custom mobile menu.
		echo get_template_part( 'template-parts/custom-mobile-navigation' );
		?>
	</nav>
	<?php
}

/**
 * Display the comments if the count is more than 0.
 *
 * @author WebDevStudios
 */
function bda_display_comments() {
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
}

/**
 * Displays the Trustpilot Fedback widget.
 *
 */
function bda_feedback_widget() { ?>
	<!-- TrustBox widget - Micro Combo -->
	<!-- <div class="trustpilot-widget trustpilot-widget--desktop" data-locale="en-US" data-template-id="5419b6ffb0d04a076446a9af" data-businessunit-id="5e07fc91ace5b50001743525" data-style-height="20px" data-style-width="100%" data-theme="dark">
	<a href="https://uk.trustpilot.com/review/metageeks.co.uk" target="_blank" rel="noopener">Trustpilot</a>
	</div> -->
	<!-- End TrustBox widget -->
	<!-- TrustBox widget - Micro Star -->
	<div class="trustpilot-widget trustpilot-widget--mobile" data-locale="en-GB" data-template-id="5419b732fbfb950b10de65e5" data-businessunit-id="5e07fc91ace5b50001743525" data-style-height="20px" data-style-width="100%" data-theme="dark" data-stars="1,2,3,4,5" data-sku="DBS-BOX-B11-2" data-no-reviews="hide" data-scroll-to-list="true" data-allow-robots="true">
		<a href="https://uk.trustpilot.com/review/metageeks.co.uk" target="_blank" rel="noopener">Trustpilot</a>
	</div>
<!-- End TrustBox widget -->
<?php }

/**
 * Displays the Vacancies list on the Apply for a job form.
 *
 */
add_filter( 'gform_pre_render_2', 'bda_vacancies_list' );
add_filter( 'gform_pre_validation_2', 'bda_vacancies_list' );
add_filter( 'gform_pre_submission_filter_2', 'bda_vacancies_list' );
add_filter( 'gform_admin_pre_render_2', 'bda_vacancies_list' );

function bda_vacancies_list( $form ) {
	$page = get_page_by_path('jobs-careers');
	$fields = bda_get_acf_fields_of_block( $page );
	$selected_vacancy = isset( $_GET["vacancy"] ) ? htmlspecialchars($_GET["vacancy"]) : null;
	$vacancies = array();

	foreach ( $form['fields'] as $field ) {

		if ( $field->type != 'select' || strpos( $field->cssClass, 'vacancies' ) === false ) {
				continue;
		}

		$choices = array();

		foreach( $fields as $key => $values ) :
			if ('vacancies' === $key ) :
				foreach ( $values as $value ) :
					$choices[] = array( 'text' => $value['vacancy'], 'value' => $value['vacancy'] );
				endforeach;
			endif;
		endforeach;

		$field->placeholder = 'Select a Vacancy';
		$field->choices = $choices;

		if ( $selected_vacancy ) :
			$field->defaultValue = $selected_vacancy;
		endif;
}

return $form;
}

/**
 * Get ACF fields of blocks from $post
 *
 */
function bda_get_acf_fields_of_block( $post ) {
	$blocks = parse_blocks( $post->post_content );
	$fields = array();
	if ( isset( $blocks ) ) :
		foreach ( $blocks as $block ) :
			if ( isset( $block['attrs']['data'] ) && !empty( $block['attrs']['data'][array_keys( $block['attrs']['data'] )[0]] ) ) :
				acf_setup_meta( $block['attrs']['data'], $block['attrs']['id'], true );
				$fields = get_fields();
				acf_reset_meta( $block['attrs']['id'] );
			endif;
		endforeach;
	endif;

	return $fields;
}

/**
 * Add custom classes to the Gravity form button
 *
 */
add_filter( 'gform_submit_button', 'bda_add_custom_css_classes', 10, 2 );
function bda_add_custom_css_classes( $button, $form ) {
    $dom = new DOMDocument();
    $dom->loadHTML( '<?xml encoding="utf-8" ?>' . $button );
    $input = $dom->getElementsByTagName( 'input' )->item(0);
    $classes = $input->getAttribute( 'class' );
    $classes .= " btn btn-red";
    $input->setAttribute( 'class', $classes );
    return $dom->saveHtml( $input );
}
