<?php
/**
 * Action hooks and filters.
 *
 * A place to put hooks and filters that aren't necessarily template tags.
 *
 * @package MetaGeeks
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @author WebDevStudios
 *
 * @param array $classes Classes for the body element.
 *
 * @return array Body classes.
 */
function bda_body_classes( $classes ) {
	// Allows for incorrect snake case like is_IE to be used without throwing errors.
	global $is_IE, $is_edge, $is_safari;

	// If it's IE, add a class.
	if ( $is_IE ) {
		$classes[] = 'ie';
	}

	// If it's Edge, add a class.
	if ( $is_edge ) {
		$classes[] = 'edge';
	}

	// If it's Safari, add a class.
	if ( $is_safari ) {
		$classes[] = 'safari';
	}

	// Are we on mobile?
	if ( wp_is_mobile() ) {
		$classes[] = 'mobile';
	}

	// Give all pages a unique class.
	if ( is_page() ) {
		$classes[] = 'page-' . basename( get_permalink() );
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds "no-js" class. If JS is enabled, this will be replaced (by javascript) to "js".
	$classes[] = 'no-js';

	// Add a cleaner class for the scaffolding page template.
	if ( is_page_template( 'template-scaffolding.php' ) ) {
		$classes[] = 'template-scaffolding';
	}

	// Add a `has-sidebar` class if we're using the sidebar template.
	if ( is_page_template( 'template-sidebar-right.php' ) ) {
		$classes[] = 'has-sidebar';
	}

	return $classes;
}

add_filter( 'body_class', 'bda_body_classes' );

/**
 * Flush out the transients used in bda_categorized_blog.
 *
 * @author WebDevStudios
 *
 * @return bool Whether or not transients were deleted.
 */
function bda_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return false;
	}

	// Like, beat it. Dig?
	return delete_transient( 'bda_categories' );
}

add_action( 'delete_category', 'bda_category_transient_flusher' );
add_action( 'save_post', 'bda_category_transient_flusher' );

/**
 * Customize "Read More" string on <!-- more --> with the_content();
 *
 * @author WebDevStudios
 *
 * @return string Read more link.
 */
function bda_content_more_link() {
	return ' <a class="more-link" href="' . get_permalink() . '">' . esc_html__( 'Read More', 'metageeks' ) . '...</a>';
}

add_filter( 'the_content_more_link', 'bda_content_more_link' );

/**
 * Customize the [...] on the_excerpt();
 *
 * @author WebDevStudios
 *
 * @param string $more The current $more string.
 *
 * @return string Read more link.
 */
function bda_excerpt_more( $more ) {
	return sprintf( ' <a class="more-link" href="%1$s">%2$s</a>', get_permalink( get_the_ID() ), esc_html__( 'Read more...', 'metageeks' ) );
}

add_filter( 'excerpt_more', 'bda_excerpt_more' );

/**
 * Filters WYSIWYG content with the_content filter.
 *
 * @author Jo Murgel
 *
 * @param string $content content dump from WYSIWYG.
 *
 * @return string|bool Content string if content exists, else empty.
 */
function bda_get_the_content( $content ) {
	return ! empty( $content ) ? $content : false;
}

add_filter( 'the_content', 'bda_get_the_content', 20 );

/**
 * Enable custom mime types.
 *
 * @author WebDevStudios
 *
 * @param array $mimes Current allowed mime types.
 *
 * @return array Mime types.
 */
function bda_custom_mime_types( $mimes ) {
	$mimes['svg']  = 'image/svg+xml';
	$mimes['svgz'] = 'image/svg+xml';

	return $mimes;
}

add_filter( 'upload_mimes', 'bda_custom_mime_types' );

/**
 * Add SVG definitions to footer.
 *
 * @author WebDevStudios
 */
function bda_include_svg_icons() {
	// Define SVG sprite file.
	$svg_icons = get_template_directory() . '/build/images/icons/sprite.svg';

	// If it exists, include it.
	if ( file_exists( $svg_icons ) ) {
		echo '<div class="svg-sprite-wrapper">';
		require_once $svg_icons;
		echo '</div>';
	}
}

add_action( 'wp_footer', 'bda_include_svg_icons', 9999 );

/**
 * Display the customizer header scripts.
 *
 * @author Greg Rickaby
 *
 * @return string Header scripts.
 */
function bda_display_customizer_header_scripts() {
	// Check for header scripts.
	$scripts = get_theme_mod( 'bda_header_scripts' );

	// None? Bail...
	if ( ! $scripts ) {
		return false;
	}

	// Otherwise, echo the scripts!
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- XSS OK.
	echo bda_get_the_content( $scripts );
}

add_action( 'wp_head', 'bda_display_customizer_header_scripts', 999 );

/**
 * Display the customizer footer scripts.
 *
 * @author Greg Rickaby
 *
 * @return string Footer scripts.
 */
function bda_display_customizer_footer_scripts() {
	// Check for footer scripts.
	$scripts = get_theme_mod( 'bda_footer_scripts' );

	// None? Bail...
	if ( ! $scripts ) {
		return false;
	}

	// Otherwise, echo the scripts!
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- XSS OK.
	echo bda_get_the_content( $scripts );
}

add_action( 'wp_footer', 'bda_display_customizer_footer_scripts', 999 );

/**
 * Adds OG tags to the head for better social sharing.
 *
 * @author Corey Collins
 *
 * @return string An empty string if Yoast is not found, otherwise a block of meta tag HTML.
 */
function bda_add_og_tags() {
	// Bail if Yoast is installed, since it will handle things.
	if ( class_exists( 'WPSEO_Options' ) ) {
		return '';
	}

	// Set a post global on single posts. This avoids grabbing content from the first post on an archive page.
	if ( is_singular() ) {
		global $post;
	}

	// Get the post content.
	$post_content = ! empty( $post ) ? $post->post_content : '';

	// Strip all tags from the post content we just grabbed.
	$default_content = ( $post_content ) ? wp_strip_all_tags( strip_shortcodes( $post_content ) ) : $post_content;

	// Set our default title.
	$default_title = get_bloginfo( 'name' );

	// Set our default URL.
	$default_url = get_permalink();

	// Set our base description.
	$default_base_description = ( get_bloginfo( 'description' ) ) ? get_bloginfo( 'description' ) : esc_html__( 'Visit our website to learn more.', 'metageeks' );

	// Set the card type.
	$default_type = 'article';

	// Get our custom logo URL. We'll use this on archives and when no featured image is found.
	$logo_id    = get_theme_mod( 'custom_logo' );
	$logo_image = ( $logo_id ) ? wp_get_attachment_image_src( $logo_id, 'full' ) : '';
	$logo_url   = ( $logo_id ) ? $logo_image[0] : '';

	// Set our final defaults.
	$card_title            = $default_title;
	$card_description      = $default_base_description;
	$card_long_description = $default_base_description;
	$card_url              = $default_url;
	$card_image            = $logo_url;
	$card_type             = $default_type;

	// Let's start overriding!
	// All singles.
	if ( is_singular() ) {

		if ( has_post_thumbnail() ) {
			$card_image = get_the_post_thumbnail_url();
		}
	}

	// Single posts/pages that aren't the front page.
	if ( is_singular() && ! is_front_page() ) {

		$card_title            = get_the_title() . ' - ' . $default_title;
		$card_description      = ( $default_content ) ? wp_trim_words( $default_content, 53, '...' ) : $default_base_description;
		$card_long_description = ( $default_content ) ? wp_trim_words( $default_content, 140, '...' ) : $default_base_description;
	}

	// Categories, Tags, and Custom Taxonomies.
	if ( is_category() || is_tag() || is_tax() ) {

		$term_name      = single_term_title( '', false );
		$card_title     = $term_name . ' - ' . $default_title;
		$specify        = ( is_category() ) ? esc_html__( 'categorized in', 'metageeks' ) : esc_html__( 'tagged with', 'metageeks' );
		$queried_object = get_queried_object();
		$card_url       = get_term_link( $queried_object );
		$card_type      = 'website';

		// Translators: get the term name.
		$card_long_description = sprintf( esc_html__( 'Posts %1$s %2$s.', 'metageeks' ), $specify, $term_name );
		$card_description      = $card_long_description;
	}

	// Search results.
	if ( is_search() ) {

		$search_term = get_search_query();
		$card_title  = $search_term . ' - ' . $default_title;
		$card_url    = get_search_link( $search_term );
		$card_type   = 'website';

		// Translators: get the search term.
		$card_long_description = sprintf( esc_html__( 'Search results for %s.', 'metageeks' ), $search_term );
		$card_description      = $card_long_description;
	}

	if ( is_home() ) {

		$posts_page = get_option( 'page_for_posts' );
		$card_title = get_the_title( $posts_page ) . ' - ' . $default_title;
		$card_url   = get_permalink( $posts_page );
		$card_type  = 'website';
	}

	// Front page.
	if ( is_front_page() ) {

		$front_page = get_option( 'page_on_front' );
		$card_title = ( $front_page ) ? get_the_title( $front_page ) . ' - ' . $default_title : $default_title;
		$card_url   = get_home_url();
		$card_type  = 'website';
	}

	// Post type archives.
	if ( is_post_type_archive() ) {

		$post_type_name = get_post_type();
		$card_title     = $post_type_name . ' - ' . $default_title;
		$card_url       = get_post_type_archive_link( $post_type_name );
		$card_type      = 'website';
	}

	// Media page.
	if ( is_attachment() ) {
		$attachment_id = get_the_ID();
		$card_image    = ( wp_attachment_is_image( $attachment_id ) ) ? wp_get_attachment_image_url( $attachment_id, 'full' ) : $card_image;
	}

	?>
	<meta property="og:title" content="<?php echo esc_attr( $card_title ); ?>" />
	<meta property="og:description" content="<?php echo esc_attr( $card_description ); ?>" />
	<meta property="og:url" content="<?php echo esc_url( $card_url ); ?>" />
	<?php if ( $card_image ) : ?>
		<meta property="og:image" content="<?php echo esc_url( $card_image ); ?>" />
	<?php endif; ?>
	<meta property="og:site_name" content="<?php echo esc_attr( $default_title ); ?>" />
	<meta property="og:type" content="<?php echo esc_attr( $card_type ); ?>" />
	<meta name="description" content="<?php echo esc_attr( $card_long_description ); ?>" />
	<?php
}

add_action( 'wp_head', 'bda_add_og_tags' );

/**
 * Removes or Adjusts the prefix on category archive page titles.
 *
 * @author Corey Collins
 *
 * @param string $block_title The default $block_title of the page.
 *
 * @return string The updated $block_title.
 */
function bda_remove_archive_title_prefix( $block_title ) {
	// Get the single category title with no prefix.
	$single_cat_title = single_term_title( '', false );

	if ( is_category() || is_tag() || is_tax() ) {
		return esc_html( $single_cat_title );
	}

	return $block_title;
}

add_filter( 'get_the_archive_title', 'bda_remove_archive_title_prefix' );

/**
 * Disables wpautop to remove empty p tags in rendered Gutenberg blocks.
 *
 * @author Corey Collins
 */
function bda_disable_wpautop_for_gutenberg() {
	// If we have blocks in place, don't add wpautop.
	if ( has_filter( 'the_content', 'wpautop' ) && has_blocks() ) {
		remove_filter( 'the_content', 'wpautop' );
	}
}

add_filter( 'init', 'bda_disable_wpautop_for_gutenberg', 9 );

/**
 * Add event listener for product qty change at modal cart
 */
add_action( 'wp_ajax_product_qty_change', 'bda_modal_cart_product_qty_change_callback' );
add_action( 'wp_ajax_nopriv_product_qty_change', 'bda_modal_cart_product_qty_change_callback' );
function bda_modal_cart_product_qty_change_callback() {
	$currency_ID = sanitize_key( $_COOKIE['yay_currency_widget'] );
	$wooCurrencyInstance = new Yay_Currency\WooCommerceCurrency();
	$currency = $wooCurrencyInstance->get_currency_by_ID( $currency_ID );
	$woo_currency = get_woocommerce_currency();
	$yay_currency = $currency['currency'];

	$product_id = $_POST[ 'product_id' ];
	$is_variation = $_POST[ 'variation_id' ] ? true : false;
	$product_qty = $_POST['qty'];

	if ( $is_variation ) {
		$variation_id = $_POST[ 'variation_id' ];
	}

	$total_product_qty;
	$product_max_qty;

	global $woocommerce;

	// Data before adding product
	$cart_items = $woocommerce->cart->get_cart();
	foreach ( $cart_items as $key => $values ) {
		if ( $is_variation && intval( $variation_id ) === $values['variation_id'] ) {

			if ( $values['data']->get_meta('variation_maximum_allowed_quantity') && $values['data']->get_stock_quantity() ) {
				$product_max_qty = min( $values['data']->get_meta('variation_maximum_allowed_quantity'), $values['data']->get_stock_quantity() );
			} else if ( $values['data']->get_meta('variation_maximum_allowed_quantity') && !$values['data']->get_stock_quantity() ) {
				$product_max_qty = get_post_meta( $id, 'variation_maximum_allowed_quantity', true );
			} else if ( !$values['data']->get_meta('variation_maximum_allowed_quantity') && $values['data']->get_stock_quantity() ) {
				$product_max_qty = $values['data']->get_stock_quantity();
			}

			$cart_id = $key;
		} else if ( !$is_variation && intval( $product_id ) === $values['product_id'] ) {

			if ( $values['data']->get_meta('maximum_allowed_quantity') && $values['data']->get_stock_quantity() ) {
				$product_max_qty = min( $values['data']->get_meta('maximum_allowed_quantity'), $values['data']->get_stock_quantity() );
			} else if ( $values['data']->get_meta('maximum_allowed_quantity') && !$values['data']->get_stock_quantity() ) {
				$product_max_qty = get_post_meta( $id, 'maximum_allowed_quantity', true );
			} else if ( !$values['data']->get_meta('maximum_allowed_quantity') && $values['data']->get_stock_quantity() ) {
				$product_max_qty = $values['data']->get_stock_quantity();
			}

			$cart_id = $key;
		}
	}

	// Check max and current quantity of product
	if (  ( !empty( $product_max_qty ) && $product_max_qty >= $product_qty ) || empty( $product_max_qty ) ) {
		// Add pcs of product
		if ( $woocommerce->cart->set_quantity( $cart_id, intval( $product_qty ), true ) ) {
			$total_product_qty = $product_qty;
			$cart_contents_count = $woocommerce->cart->get_cart_contents_count();

			if ( $woo_currency === $yay_currency ) {
				$cart_subtotal = $woocommerce->cart->get_cart_subtotal();
			} else {
				$cart_subtotal = $woocommerce->cart->get_cart_subtotal();
				$raw_subtotal = filter_var( wp_kses_data( $cart_subtotal ), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
				$cart_subtotal_converted = $currency['symbol'] . number_format( (float) ($raw_subtotal * $currency['rate']), 2, '.', '' );
				$cart_subtotal = $cart_subtotal_converted;
			}
		}
	}

	// Get bottom part of modal-cart
	ob_start();
	echo get_template_part( '/template-parts/modal-cart-bottom-part', null );
	$bottom_part = ob_get_contents();
	ob_clean();

	$response = array(
		'cart_subtotal' => wp_kses_data( $cart_subtotal ),
		'cart_contents_count' => wp_kses_data( $cart_contents_count ),
		'total_product_qty' => wp_kses_data( $total_product_qty ),
		'bottom_part' => $bottom_part,
	);

	echo json_encode( $response );

	wp_die();
}

/**
 * Add event listener for delete button at modal cart
 */
add_action( 'wp_ajax_product_delete', 'bda_modal_cart_product_delete_callback' );
add_action( 'wp_ajax_nopriv_product_delete', 'bda_modal_cart_product_delete_callback' );
function bda_modal_cart_product_delete_callback() {
	$product_id = $_POST[ 'product_id' ];
	$is_variation = $_POST[ 'variation_id' ] ? true : false;

	$currency_ID = sanitize_key( $_COOKIE['yay_currency_widget'] );
	$wooCurrencyInstance = new Yay_Currency\WooCommerceCurrency();
	$currency = $wooCurrencyInstance->get_currency_by_ID( $currency_ID );
	$woo_currency = get_woocommerce_currency();
	$yay_currency = $currency['currency'];

	if ( $is_variation ) {
		$variation_id = $_POST[ 'variation_id' ];
	}

	global $woocommerce;

	// Get quantity of product
	$cart_items = $woocommerce->cart->get_cart();
	foreach ( $cart_items as $key => $values ) {
		if (  ( $is_variation && intval( $variation_id ) === $values['variation_id'] ) ||
		 ( !$is_variation && intval( $product_id ) === $values['product_id'] ) ) {
				$cart_id = $key;
		}
	}

	if ( $woocommerce->cart->set_quantity( $cart_id, intval( 0 ), true ) ) {
		$cart_contents_count = $woocommerce->cart->get_cart_contents_count();
		$total_product_qty = 0;

		if ( $woo_currency === $yay_currency ) {
			$cart_subtotal = $woocommerce->cart->get_cart_subtotal();
		} else {
			$cart_subtotal = $woocommerce->cart->get_cart_subtotal();
			$raw_subtotal = filter_var( wp_kses_data( $cart_subtotal ), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
			$cart_subtotal_converted = $currency['symbol'] . number_format( (float) ($raw_subtotal * $currency['rate']), 2, '.', '' );
			$cart_subtotal = $cart_subtotal_converted;
		}

		// Get bottom part of modal-cart
		ob_start();
		echo get_template_part( '/template-parts/modal-cart-bottom-part', null );
		$bottom_part = ob_get_contents();
		ob_clean();

		$response = array(
			'cart_subtotal' => wp_kses_data( $cart_subtotal ),
			'cart_contents_count' => wp_kses_data( $cart_contents_count ),
			'total_product_qty' => 0,
			'bottom_part' => $bottom_part,
		);
	}

	echo json_encode( $response );

	wp_die();
}

/**
 * Add event listener for get header cart button
 */
add_action( 'wp_ajax_get_header_cart_btn', 'bda_get_header_cart_btn_callback' );
add_action( 'wp_ajax_nopriv_get_header_cart_btn', 'bda_get_header_cart_btn_callback' );
function bda_get_header_cart_btn_callback() {
	global $woocommerce;

	ob_start();
	// Get product id in cart
	echo get_template_part( 'template-parts/header-cart-button', null );
	$response = ob_get_contents();
	ob_clean();

	echo json_encode( $response );

	wp_die();
}

/**
 * Add event listener for update modal cart
 */
add_action( 'wp_ajax_update_modal_cart', 'bda_update_modal_cart_callback' );
add_action( 'wp_ajax_nopriv_update_modal_cart', 'bda_update_modal_cart_callback' );
function bda_update_modal_cart_callback() {
	global $woocommerce;

	ob_start();
	// Get product id in cart
	echo get_template_part( 'template-parts/modal-cart-inner', null );
	$response = ob_get_contents();
	ob_clean();

	echo json_encode( $response );

	wp_die();
}

/**
 * Add event listener for empty modal cart
 */
add_action( 'wp_ajax_empty_modal_cart', 'bda_empty_modal_cart_callback' );
add_action( 'wp_ajax_nopriv_empty_modal_cart', 'bda_empty_modal_cart_callback' );
function bda_empty_modal_cart_callback() {
	global $woocommerce;

	$woocommerce->cart->empty_cart();

	ob_start();
	// Get product id in cart
	echo get_template_part( 'template-parts/modal-cart-inner', null );
	$response = ob_get_contents();
	ob_clean();

	echo json_encode( $response );

	wp_die();
}

/**
 * Add event listener for remove recently viewed product cookies
 */
add_action( 'wp_ajax_remove_recently_viewed_cookie', 'bda_remove_recently_viewed_cookie_callback' );
add_action( 'wp_ajax_nopriv_remove_recently_viewed_cookie', 'bda_remove_recently_viewed_cookie_callback' );
function bda_remove_recently_viewed_cookie_callback() {
	unset( $_COOKIE[ 'bda_woocommerce_recently_viewed' ] );
	wc_setcookie( 'bda_woocommerce_recently_viewed', '', 1 );
	wp_die();
}

/**
 * Add event listener for change product qty in cart
 */
add_action( 'wp_ajax_cart_product_qty_change', 'bda_cart_product_qty_change_callback' );
add_action( 'wp_ajax_nopriv_cart_product_qty_change', 'bda_cart_product_qty_change_callback' );
function bda_cart_product_qty_change_callback() {
	$product_id = $_POST[ 'product_id' ];
	$product_qty = $_POST[ 'product_qty' ];

	global $woocommerce;

	// Get product id in cart
	$cart_id = $woocommerce->cart->generate_cart_id( $product_id );

	// Get quantity of product
	$cart_items = $woocommerce->cart->get_cart();

	if ( $woocommerce->cart->set_quantity( $cart_id, $product_qty, true ) ) {
		$cart_subtotal = $woocommerce->cart->get_cart_subtotal();
		$cart_contents_count = $woocommerce->cart->get_cart_contents_count();

		$response = array(
			'cart_subtotal' => wp_kses_data( $cart_subtotal ),
			'cart_contents_count' => wp_kses_data( $cart_contents_count ),
			// 'total_product_qty' => wp_kses_data( $total_product_qty ),
		);
	}

	echo json_encode( $response );

	wp_die();
}

/**
 * Add event listener for remove all products from cart
 */
add_action( 'wp_ajax_remove_all_products_from_cart', 'bda_remove_all_products_from_cart_callback' );
add_action( 'wp_ajax_nopriv_remove_all_products_from_cart', 'bda_remove_all_products_from_cart_callback' );
function bda_remove_all_products_from_cart_callback() {
	global $woocommerce;

	$woocommerce->cart->empty_cart();

	wp_die();
}

/**
 * Add event listener for update cart summary when product qty changing
 */
add_action( 'wp_ajax_update_cart_summary', 'bda_update_cart_summary_callback' );
add_action( 'wp_ajax_nopriv_update_cart_summary', 'bda_update_cart_summary_callback' );
function bda_update_cart_summary_callback() {
	global $woocommerce;

	$is_empty_cart = $woocommerce->cart->is_empty();
	$free_shipping_order_cost = get_field( 'shipping_terms', 'options' )['free_shipping'];

	ob_start();
	// Get cart summary
	echo get_template_part( 'template-parts/cart-page-summary', null );
	$response = ob_get_contents();
	ob_clean();

	echo json_encode( $response );

	wp_die();
}

/**
 * Ajax Search
 */
function ajax_search_results() {
	$result = '';

 	if ( isset( $_POST['search'] ) && ! empty( $_POST['search'] ) ) {
		$search_query = sanitize_text_field( $_POST['search'] );
		$template = sanitize_text_field( $_POST['template'] );

		$args = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			's'              => $search_query,
			'posts_per_page' => 4,
			'meta_query'     => array(
				array(
					'key'     => '_stock_status', // Exclude items that are out of stock.
					'value'   => 'outofstock',
					'compare' => '!=',
				),
			),
		);
		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			ob_start();
			while ( $query->have_posts() ) {
				$query->the_post();

				$product           = wc_get_product( get_the_ID() );
				$regular_price     = $product->get_regular_price();
				$sale_price        = $product->get_sale_price();
				$categories        = get_the_terms( get_the_ID(), 'product_cat' );
				$categories_names  = array_column( $categories, 'name' );
				$categories_string = implode( ', ', $categories_names );
				$currency          = get_woocommerce_currency_symbol();
				?>
				<?php if ( 'desktop' === $template ) : ?>
					<div class="product-search">
						<a href="<?php the_permalink(); ?>">
							<div class="product-image">
								<img src="<?php the_post_thumbnail_url( array( 144, 144 ) ); ?>"/>
							</div>
							<div class="product-data">
								<?php the_title( '<h3>', '</h3>' ); ?>
								<div class="text-flex">
									<div class="product-price">
										<span class="regular-price"><?php echo esc_html( $currency . $regular_price ); ?></span>
										<?php if ( $sale_price ) : ?>
											<span class="sale-price"><del><?php echo esc_html( $currency . $sale_price ); ?></del></span>
										<?php endif; ?>
									</div>
									<?php if ( $categories_string ) : ?>
										<div class="product-categories">
											<?php echo esc_html( $categories_string ); ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</a>
					</div>
				<?php else: ?>
					<div class="product-result-mobile">
						<a href="<?php the_permalink(); ?>">
							<p>
								<?php the_title(); ?>
								<span>- <?php echo esc_html( $currency . $regular_price ); ?></span>
								<?php if ( $sale_price ) : ?>
									<span>| <del><?php echo esc_html( $currency . $sale_price ); ?></del></span>
								<?php endif; ?>
							</p>
						</a>
					</div>
				<?php endif;
			}

			$result = ob_get_clean();
		}
		wp_reset_postdata();

	}

	echo $result;
	wp_die();
}

add_action('wp_ajax_search_results', 'ajax_search_results');
add_action('wp_ajax_nopriv_search_results', 'ajax_search_results');
