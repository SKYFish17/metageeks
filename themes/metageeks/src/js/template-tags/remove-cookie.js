'use strict';

const btn = document.querySelector( '.single-product-recently-viewed__clear' );
const block = document.querySelector( '.single-product-recently-viewed' );

if ( btn && block ) {
	( function ( $ ) {
		btn.addEventListener( 'click', () => {
			$.ajax( {
				method: 'POST',
				url: window.bda_ajax.url,
				data: {
					action: 'remove_recently_viewed_cookie',
				},
			} ).done( function () {
				block.remove();
			} );
		} );
	} )( jQuery );
}
