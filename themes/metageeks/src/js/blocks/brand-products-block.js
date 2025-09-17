( function ( $ ) {
	'use strict';

	$( function () {
		// Toggle sliders
		$( '.brand-products-block__switches-item' ).on( 'click', function () {
			const button = $( this );

			if ( button.hasClass( 'active' ) ) return false;

			const parent = button.parents( '.brand-products-block' );
			const currentSlider = parent.find(
				'.mg-product-slider__container[data-term-id="' +
					button.data( 'term-id' ) +
					'"]'
			);

			// Toggle swiches
			parent
				.find( '.brand-products-block__switches-item.active' )
				.removeClass( 'active' );
			button.addClass( 'active' );

			// Show/Hide sliders
			parent
				.find( '.mg-product-slider__container' )
				.addClass( 'hide-slider' );
			currentSlider.removeClass( 'hide-slider' );
		} );
	} );
} )( jQuery );
