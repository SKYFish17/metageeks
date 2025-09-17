/**
 * Change order of elements in a Cards with Button Block/
 *
 */

( function ( $ ) {
	const itemButton = $( '.cards-with-button-block__btn' );
	let isMobile = false;
	let isDesktop = true;

	if ( window.innerWidth <= 900 ) {
		itemButton.each( function () {
			$( this ).appendTo( $( this ).parent().parent() );
			isMobile = true;
			isDesktop = false;
		} );
	}

	window.addEventListener( 'resize', () => {
		if ( window.innerWidth <= 900 && ! isMobile ) {
			itemButton.each( function () {
				$( this ).appendTo( $( this ).parent().parent() );
				isMobile = true;
				isDesktop = false;
			} );
		} else if ( window.innerWidth > 900 && ! isDesktop ) {
			itemButton.each( function () {
				$( this ).appendTo(
					$( this )
						.parent()
						.find( '.cards-with-button-block__content' )
				);
			} );
			isMobile = false;
			isDesktop = true;
		}
	} );
} )( jQuery );
