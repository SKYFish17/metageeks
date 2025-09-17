'use strict';

( function ( $ ) {
	$( document ).on( 'click', function ( e ) {
		if (
			$( e.target ).hasClass( 'sf-input-text' ) ||
			( ! $( e.target ).hasClass( 'search-results-desktop' ) &&
				! $( e.target ).parents().hasClass( 'search-results-desktop' ) )
		) {
			$( '.search-results-desktop' ).hide();
		}
	} );

	$( '.modal-search__close' ).on( 'click', function () {
		$( '.modal-search' ).removeClass( 'show' );
	} );

	$( '.search-mobile' ).on( 'click', function () {
		$( '.modal-search' ).addClass( 'show' );
	} );

	let timer;

	$( '.searchandfilter .sf-field-search input' ).on( 'keyup', function () {
		$( this ).parents( '.searchandfilter' ).addClass( 'loading' );
		clearTimeout( timer );
		timer = setTimeout( searchProducts( this ), 500 );
	} );

	$( '.searchandfilter .sf-field-search input' ).on( 'keydown', function () {
		clearTimeout( timer );
	} );

	function searchProducts( searchInputEl ) {
		const searchInput = $( searchInputEl );
		const searchWrapper = searchInput
			.parents( '.searchandfilter' )
			.parent();
		const template = searchWrapper.hasClass( 'wrapper-search' )
			? 'desktop'
			: 'mobile';
		const resultsClass =
			'desktop' === template
				? '.search-results-desktop'
				: '.modal-search__results';
		const search = searchInput.val();

		if ( search !== '' ) {
			$.ajax( {
				type: 'POST',
				url: window.bda_ajax.url,
				data: {
					action: 'search_results',
					search,
					template,
				},
				success( response ) {
					searchWrapper
						.find( '.searchandfilter' )
						.removeClass( 'loading' );

					if ( response ) {
						$( resultsClass ).html( response ).show();
					} else {
						$( resultsClass )
							.html(
								'<p class="search-empty">Sorry, we couldn’t find what you are looking for.</p>'
							)
							.show();
					}
				},
			} );
		} else {
			searchWrapper.find( '.searchandfilter' ).removeClass( 'loading' );
			$( resultsClass ).hide();
		}
	}
} )( jQuery );
