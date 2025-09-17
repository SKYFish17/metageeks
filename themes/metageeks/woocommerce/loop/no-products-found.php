<?php
/**
 * Displayed when no products are found matching the current query
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/no-products-found.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="woocommerce-info">
	<img src="<?php echo get_template_directory_uri().'/build/images/not-found.jpg'; ?>" width="120" height="83" alt="not-found">
	<h2 class="mg-h1">no results found</h2>
	<p class="desc">Sorry, we couldn’t find what you are looking for. </p>
</div>
