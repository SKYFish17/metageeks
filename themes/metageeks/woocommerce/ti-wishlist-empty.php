<?php
/**
 * The Template for displaying empty wishlist.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/ti-wishlist-empty.php.
 *
 * @version             1.26.0
 * @package           TInvWishlist\Template
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
?>
<div class="cart-page-basket__products-table products-table">
	<div class="products-table__row products-table__row_header">
		<div class="products-table-col products-table-col_image">
		</div>
		<div class="products-table-col__wrapper">
			<div class="products-table-col products-table-col_product">
				Product
			</div>
			<div class="products-table-col__mobile-wrapper">
				<div class="products-table-col products-table-col_qty">
					Quantity
				</div>
				<div class="products-table-col products-table-col_price">
					Price
				</div>
			</div>
			<div class="products-table-col products-table-col_delete">
			</div>
		</div>
	</div>

	<div class="products-table__row products-table__row_content">
		Your wishlist is currently empty.
	</div>
</div>
