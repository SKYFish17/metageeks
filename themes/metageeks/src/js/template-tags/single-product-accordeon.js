'use strict';

const accordeon = document.querySelector( '.single-product-accordeon' );

( function ( $ ) {
	if ( accordeon ) {
		function accordeonClickEventHandler( evt ) {
			const target = evt.target;

			if ( target.closest( '.single-product-accordeon-item__header' ) ) {
				target.closest( 'li' ).classList.toggle( 'opened' );

				$(
					target
						.closest( 'li' )
						.querySelector(
							'.single-product-accordeon-item__content'
						)
				).slideToggle();
			}
		}

		accordeon.addEventListener( 'click', accordeonClickEventHandler );
	}
} )( jQuery );
