'use strict';

const cartPage = document.querySelector( '.cart-page' );

if ( cartPage ) {
	jQuery( document ).on( 'change', '.cart_item input', function () {
		document
			.querySelector( 'button[name="update_cart"]' )
			.removeAttribute( 'disabled' );
		document.querySelector( 'button[name="update_cart"]' ).click();
	} );
}
