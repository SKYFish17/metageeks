<?php
/**
 * MetaGeeks functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package MetaGeeks
 */

/**
 * Get all the include files for the theme.
 *
 * @author WebDevStudios
 */
function bda_get_theme_include_files() {
	return [
		'inc/setup.php', // Theme set up. Should be included first.
		'inc/compat.php', // Backwards Compatibility.
		'inc/customizer/customizer.php', // Customizer additions.
		'inc/extras.php', // Custom functions that act independently of the theme templates.
		'inc/hooks.php', // Load custom filters and hooks.
		'inc/security.php', // WordPress hardening.
		'inc/scaffolding.php', // Scaffolding.
		'inc/scripts.php', // Load styles and scripts.
		'inc/template-tags.php', // Custom template tags for this theme.
		'inc/custom-menu.php', // Add ACF custom fields for a menu.
		'inc/custom-gravity-form.php', // Add custom function for a gravity form.
		'inc/gutenberg-blocks.php', // Add ACF gutenberg blocks.
		'inc/remove-class-hook.php', // Helper function.
		'inc/woocommerce_hooks.php', // Add WooCommerce hooks.
		'inc/acf-options-page.php', // Add ACF options page.
		'inc/custom-taxonomies.php', // Add custom taxonomies.
		'inc/posts-loadmore-ajax.php', // Ajax posts loading.
		'inc/faq-search-ajax.php', // Ajax FAQ search.
	];
}

foreach ( bda_get_theme_include_files() as $include ) {
	require trailingslashit( get_template_directory() ) . $include;
}
