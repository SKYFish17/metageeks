<?php
/**
 * Block: Product Slider
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 *
 * @package MetaGeeks
 */

// Create id attribute allowing for custom "anchor" value.
$block_id = 'product-slider-block-' . $block['id'];

if ( ! empty( $block['anchor'] ) ) {
	$block_id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$block_class = 'product-slider-block mg-product-slider__section';

if ( ! empty( $block['className'] ) ) {
	$block_class .= ' ' . $block['className'];
}

if ( ! empty( $block['align'] ) ) {
	$block_class .= ' align' . $block['align'];
}

// Block data.
$args = array(
	'id'               => $block_id,
	'class'            => $block_class,
	'title'            => get_field( 'title' ),
	'background_color' => get_field( 'background_color' ),
	'link'             => get_field( 'link' ),
	'products'         => get_field( 'products' ),
);

// Load template.
get_template_part( 'template-parts/product-slider-block', null, $args );
