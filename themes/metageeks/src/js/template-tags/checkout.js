'use strict';

( function ( $ ) {
	function activeMethod() {
		$(
			'.page-checkout .woocommerce_checkout_shipping_method #shipping_method li'
		).removeClass( 'active' );
		$(
			'.page-checkout .woocommerce_checkout_shipping_method #shipping_method input:checked'
		)
			.parents( 'li' )
			.addClass( 'active' );
	}

	$( '.woocommerce_checkout_wrapper_review__header' ).on(
		'click',
		function () {
			$( '.woocommerce_checkout_wrapper_review__contents' ).slideToggle();
			$( this ).toggleClass( 'active' );
		}
	);

	$( '.page-checkout' ).on( 'update_checkout', function () {
		setTimeout( activeMethod, 100 );
	} );

	$( '.page-checkout #woo_slp_social_login' ).appendTo(
		'.social-login__place-for-social-login'
	);

	$( '.page-checkout .password-switch' ).on( 'click', function () {
		$( this ).toggleClass( 'on' );
		const x = $( '.page-checkout #password' );
		if ( $( x ).attr( 'type' ) === 'password' ) {
			$( x ).attr( 'type', 'text' );
		} else {
			$( x ).attr( 'type', 'password' );
		}
	} );

	$( '.page-checkout .showlogin-form' ).on( 'click', function () {
		$( '.page-checkout .blackout' ).toggleClass( 'show' );
		$( '.page-checkout .woocommerce-form-login' ).fadeToggle( 100 );
		$( 'body' ).toggleClass( 'overhidden' );
	} );

	$( '.page-checkout .blackout' ).on( 'click', function ( e ) {
		if (
			$( e.target ).hasClass( 'modal-close' ) ||
			( ! $( e.target ).hasClass( 'woocommerce-form-login' ) &&
				! $( e.target ).parents().hasClass( 'woocommerce-form-login' ) )
		) {
			$( '.page-checkout .blackout' ).toggleClass( 'show' );
			$( '.page-checkout .woocommerce-form-login' ).fadeToggle( 100 );
			$( 'body' ).toggleClass( 'overhidden' );
		}
	} );

	$( '.page-checkout' ).on( 'click', function ( e ) {
		if (
			$( e.target ).hasClass( 'minus' ) ||
			$( e.target ).hasClass( 'plus' )
		) {
			const btn = $( e.target );
			let max = $( btn )
				.parents( '.controls' )
				.find( 'input' )
				.attr( 'max' );
			if ( Number( max ) === 0 ) {
				max = 1000;
			}
			const count = $( btn ).parents( '.controls' ).find( 'input' ).val();
			if (
				Number( max ) < Number( count ) + 1 &&
				$( e.target ).hasClass( 'plus' )
			) {
				$( btn )
					.parents( '.controls' )
					.find( 'input' )
					.css( 'border-color', '#f00' );
				return false;
			}
			$( '.woocommerce_checkout_wrapper_review__contents' ).css(
				'opacity',
				0.6
			);
			$.ajax( {
				type: 'POST',
				url: window.bda_ajax.url,
				data: {
					action: 'update_count_checkout',
					product_id: String( $( btn ).data( 'product_id' ) ),
					count: String( count ),
					max: String( max ),
					sign: $( btn ).attr( 'class' ),
				},
				success() {
					$( '.page-checkout' ).on( 'updated_checkout', function () {
						$( '.woocommerce_checkout_wrapper_review__items' ).css(
							'opacity',
							1
						);
					} );
					$( '.page-checkout' ).trigger( 'update_checkout' );
				},
			} );
		}
	} );
} )( jQuery );
