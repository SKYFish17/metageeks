<?php
/**
 * Template part for displaying header cart button layout.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package MetaGeeks
 */
global $woocommerce;

$cart_subtotal = $woocommerce->cart->get_cart_subtotal();
$cart_contents_count = $woocommerce->cart->get_cart_contents_count();
?>

<button class="header-cart-btn" type="button" name="cart">
	<span class="visually-hidden">Total сost of goods</span>
	<?php
	bda_display_svg(
		[
			'icon'   => 'cart',
			'width'  => '24',
			'height' => '26',
		]
	);
	?>
	<span class="value"><?php echo wp_kses_post( $cart_subtotal ); ?></span>
	<span class="count"><?php echo wp_kses_post( $cart_contents_count ); ?></span>
</button>
