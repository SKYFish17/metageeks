'use strict';

const cartPage = document.querySelector( '.cart-page' );
const btnRemoveAllProducts = document.querySelector(
	'.btn-cart-control_remove-all'
);

if ( cartPage && btnRemoveAllProducts ) {
	btnRemoveAllProducts.addEventListener( 'click', () => {
		jQuery
			.ajax( {
				method: 'POST',
				url: window.bda_ajax.url,
				data: {
					action: 'remove_all_products_from_cart',
				},
			} )
			.done( function () {
				window.location.reload();
			} );
	} );
}
