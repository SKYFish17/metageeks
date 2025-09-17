'use strict';

const cartPage = document.querySelector( '.cart-page' );

if ( cartPage ) {
	const btnMoveAllFromWishlistToCart = cartPage.querySelector(
		'.btn-cart-control_to-basket'
	);

	const btnMoveAllFromCartToWishlist = cartPage.querySelector(
		'.btn-cart-control_to-wishlist'
	);

	// Move all products from ti-wishlist to cart
	btnMoveAllFromWishlistToCart.addEventListener( 'click', () => {
		const fromWishlistPluginBtn = cartPage.querySelector(
			'button[value="product_all"]'
		);

		jQuery( fromWishlistPluginBtn ).click();
	} );

	// Move all products from cart to ti-wishlist
	btnMoveAllFromCartToWishlist.addEventListener( 'click', () => {
		const toWishlistPluginBtn = cartPage.querySelector(
			'.tinvwl_all_cart_to_wishlist_button'
		);

		jQuery( toWishlistPluginBtn ).click();
	} );

	// Move to wishlist selected product
	// function wrapper() {
	// 	window.location.reload();
	// }
	// cartPage.addEventListener( 'click', ( evt ) => {
	// 	const target = evt.target;
	// 	const saveForLaterBtn = target.closest(
	// 		'.tinvwl_cart_to_wishlist_button'
	// 	);

	// if ( saveForLaterBtn ) {
	// 	setTimeout( wrapper, 1000 );
	// }
	// } );
}
