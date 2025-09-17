/**
 * Create an accordion FAQ.
 */

( function ( $ ) {
	if ( $( '.faq-block__list' ).length !== 0 ) {
		$( '.faq-block__list' ).each( function () {
			$( this )
				.find( '.faq-block__heading' )
				.on( 'click', function () {
					const icon = $( this ).find( '.faq-block__heading-icon' );

					if ( $( this ).hasClass( 'faq-block__heading--open' ) ) {
						$( this ).removeClass( 'faq-block__heading--open' );
						icon.removeClass( 'faq-block__heading-icon--open' );
						$( this )
							.siblings( '.faq-block__content' )
							.slideUp( 200 );
					} else {
						$( '.faq-block__heading' ).removeClass(
							'faq-block__heading--open'
						);
						$( '.faq-block__heading-icon' ).removeClass(
							'faq-block__heading-icon--open'
						);
						$( this ).addClass( 'faq-block__heading--open' );
						icon.addClass( 'faq-block__heading-icon--open' );
						$( '.faq-block__content' ).slideUp( 200 );
						$( this )
							.siblings( '.faq-block__content' )
							.slideDown( 200 );
					}
				} );
		} );
	}

	if ( $( '#faq_source' ).length !== 0 ) {
		const source = $( '#faq_source' ).data( 'faq-source' );

		$( 'input[ name="faq_search_query" ]' ).autocomplete( {
			source( request, response ) {
				$.ajax( {
					type: 'POST',
					url: window.bda_ajax.url,
					data: {
						action: 'faqsearch',
						term: request.term,
						source,
					},
					success( data ) {
						response( data );
					},
				} );
			},
			select( event, ui ) {
				$( [ document.documentElement, document.body ] ).animate(
					{
						scrollTop: $( '#' + ui.item.id ).offset().top - 300,
					},
					1000
				);
				$( '.faq-block__heading' ).removeClass(
					'faq-block__heading--open'
				);
				$( '.faq-block__heading-icon' ).removeClass(
					'faq-block__heading-icon--open'
				);
				$( '.faq-block__content' ).slideUp( 200 );
				$( '#' + ui.item.id )
					.find( '.faq-block__heading-icon' )
					.addClass( 'faq-block__heading-icon--open' );
				$( '#' + ui.item.id )
					.find( '.faq-block__content' )
					.slideDown( 200 );
			},
		} );
	}
} )( jQuery );
