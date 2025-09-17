( function ( $ ) {
	'use strict';

	$( function () {
		// Toggle account menu
		$( '.metageeks-account-navigation__toggler' ).on( 'click', function () {
			const menu = $( '.metageeks-account-navigation__menu' );

			if ( menu.hasClass( 'active' ) ) {
				menu.removeClass( 'active' );
				menu.css( 'max-height', '' );
			} else {
				menu.addClass( 'active' );
				menu.css( 'max-height', menu.prop( 'scrollHeight' ) + 'px' );
			}
		} );

		// Switch between login and register forms
		$( '.metageeks-login__switch-button' ).on( 'click', function () {
			const newState = $( this ).data( 'show' );

			$( '.metageeks-login__form' ).hide();
			$( '.metageeks-login__form.' + newState ).show();
			$( 'html, body' ).animate( { scrollTop: 0 } );
		} );

		// Toggle points coupon generation
		$( '.metageeks-points-toggle-coupon' ).on( 'click', function () {
			$( '.metageeks-points-coupon' ).slideToggle();
		} );
	} );
} )( jQuery );
