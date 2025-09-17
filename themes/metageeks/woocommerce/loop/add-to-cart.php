<?php
/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

$product_stock_qty = $product->get_stock_quantity();

$icon = bda_get_svg(
	[
		'icon'   => 'cart-white',
		'width'  => '28',
		'height' => '28',
	]
);

$class_disabled = '';
$link_text      = '';
if ( 'Read more' === $product->add_to_cart_text() ) {
	$class_disabled = 'out-of-stock';
	$link_text      = 'Sold out';
}

if ( 0 === $product_stock_qty && 'Pre-Order Now' === $product->add_to_cart_text() ) {
	$class_disabled = 'out-of-stock';
	$link_text      = 'Sold out';
}

echo apply_filters(
	'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
	sprintf(
		'<a href="%s" data-quantity="%s" class="%s" %s>%s %s</a>',
		esc_url( $product->add_to_cart_url() ),
		esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
		'btn btn-red ' . $class_disabled . ' ' . esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
		isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
		$link_text ? $link_text : esc_html( $product->add_to_cart_text() ),
		$icon
	),
	$product,
	$args
);
