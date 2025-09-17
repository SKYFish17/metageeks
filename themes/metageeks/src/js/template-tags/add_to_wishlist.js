/**
 * Changes icon on the wishlist
 *
 */

( function ( $ ) {
	const addToWishListBtn = $( '.product-order-controls__wishlist-btn' );

	const btnIcon = addToWishListBtn.find( 'img' );

	const imagePath =
		window.bda_home_url.url +
		'/wp-content/themes/metageeks/build/images/icons/';

	window.setTimeout( function () {
		if ( addToWishListBtn.hasClass( 'tinvwl-product-in-list' ) ) {
			btnIcon.attr( 'src', imagePath + 'heart_red.svg' );
		} else {
			btnIcon.attr( 'src', imagePath + 'heart.svg' );
		}
	}, 1500 );

	if ( addToWishListBtn.hasClass( 'tinvwl-product-in-list' ) ) {
		btnIcon.attr( 'src', imagePath + 'heart_red.svg' );
	} else {
		btnIcon.attr( 'src', imagePath + 'heart.svg' );
	}

	$( document ).on(
		'click',
		'.product-order-controls__wishlist-btn',
		function () {
			if ( addToWishListBtn.hasClass( 'tinvwl-product-in-list' ) ) {
				btnIcon.attr( 'src', imagePath + 'heart.svg' );
			} else {
				btnIcon.attr( 'src', imagePath + 'heart_red.svg' );
			}
		}
	);
} )( jQuery );
