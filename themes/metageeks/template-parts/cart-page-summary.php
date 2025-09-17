<?php
global $woocommerce;
$free_shipping_order_cost = get_field( 'shipping_terms', 'options' )['free_shipping'];
$cart_total = $woocommerce->cart->subtotal_ex_tax;
?>

<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

<section class="cart-page__summary cart-page-summary">
	<h2 class="cart-page-summary__title">Basket Summary</h2>

	<?php woocommerce_cart_totals(); ?>
</section>
