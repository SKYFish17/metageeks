<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */
namespace Yay_Currency;

defined( 'ABSPATH' ) || exit;

// Add current-currency data in frontend
$currency_ID = sanitize_key( $_COOKIE['yay_currency_widget'] );

if ( class_exists('Yay_Currency\WooCommerceCurrency') ) {
	$wooCurrencyInstance = new WooCommerceCurrency();
	$currency = $wooCurrencyInstance->get_currency_by_ID( $currency_ID );
	echo '<script>window.currencyRate = '. $currency['rate'] .'</script>';
}

$term = get_queried_object();
$redirect_url = get_field('redirect_url', $term);

if ( $redirect_url ) :
	wp_redirect( $redirect_url, 301 );
	exit;
endif;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

?>
<header class="woocommerce-products-header products-header">
	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
		<h1 class="woocommerce-products-header__title mg-h1"><?php woocommerce_page_title(); ?></h1>
	<?php endif; ?>

	<?php
	/**
	 * Hook: woocommerce_archive_description.
	 *
	 * @hooked woocommerce_taxonomy_archive_description - 10
	 * @hooked woocommerce_product_archive_description - 10
	 */
	do_action( 'woocommerce_archive_description' );
	?>
</header>

<div class="filters">
	<h2 class="filters__title">Filters</h2>
	<?php echo do_shortcode( '[searchandfilter slug="filters"]' ); ?>
</div>

<button class="btn btn-red btn-filter-products">
	Filter Products
	<?php
	bda_display_svg(
		[
			'icon'   => 'filter',
			'width'  => '19',
			'height' => '19',
		]
	);
	?>
</button>

<?php
if ( woocommerce_product_loop() ) {

	/**
	 * Hook: woocommerce_before_shop_loop.
	 *
	 * @hooked woocommerce_output_all_notices - 10
	 * @hooked woocommerce_result_count - 20
	 * @hooked woocommerce_catalog_ordering - 30
	 */
	?>

	<div class="options">
		<?php do_action( 'woocommerce_before_shop_loop' ); ?>

		<ul class="layout-buttons">
			<li class="item active">
				<button data-layout-type="grid">
					<?php
					bda_display_svg(
						[
							'icon'   => 'grid-layout',
							'width'  => '35',
							'height' => '35',
						]
					);
					?>
					<span class="visually-hidden">Grid layout</span>
				</button>
			</li>
			<li class="item">
				<button data-layout-type="line">
					<?php
					bda_display_svg(
						[
							'icon'   => 'line-layout',
							'width'  => '35',
							'height' => '35',
						]
					);
					?>
					<span class="visually-hidden">Line layout</span>
				</button>
			</li>
		</ul>
	</div>

	<div class="mg-products">
		<?php
		if ( wc_get_loop_prop( 'total' ) ) {
			while ( have_posts() ) {
				the_post();

				/**
				 * Hook: woocommerce_shop_loop.
				 */
				do_action( 'woocommerce_shop_loop' );

				wc_get_template_part( 'content', 'product' );
			}
		}
		?>
	</div>

	<?php
	/**
	 * Hook: woocommerce_after_shop_loop.
	 *
	 * @hooked woocommerce_pagination - 10
	 */
	do_action( 'woocommerce_after_shop_loop' );
} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
// do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );
