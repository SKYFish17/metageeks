'use strict';

import 'slick-carousel';

( function ( $ ) {
	const productPage = document.querySelector( '.single-product' );
	const variablesList = document.querySelector( '.product-order-variations' );
	const variablesSelect = document.querySelector( '#pa_value' );

	// Single-product slider
	$( '.product-slider .product-slider-for' ).slick( {
		slidesToShow: 1,
		slidesToScroll: 1,
		rows: 1,
		asNavFor: '.product-slider .product-slider-nav',
		arrows: false,
		dots: false,
		fade: true,
	} );

	$( '.product-slider .product-slider-nav' ).slick( {
		slidesToShow: 3,
		slidesToScroll: 1,
		rows: 1,
		asNavFor: '.product-slider .product-slider-for',
		arrows: false,
		dots: false,
		centerMode: false,
		focusOnSelect: true,
		vertical: true,
		verticalSwiping: true,
		responsive: [
			{
				breakpoint: 769,
				settings: {
					vertical: false,
					verticalSwiping: false,
				},
			},
		],
	} );

	if ( productPage && variablesList && variablesSelect ) {
		variablesList.addEventListener( 'click', ( evt ) => {
			const target = evt.target;
			const button = target.closest( '.product-order-variation__button' );

			function getSlickIndex() {
				const index = $( '.product-slider-for' )
					.find(
						`.product-slider-for__img-wrapper[data-variation-image-id="${ button.dataset.variationImageId }"]`
					)
					.closest( '.slick-slide' )
					.data( 'slick-index' );

				return index;
			}

			if ( button ) {
				$( '.product-slider .product-slider-for' ).slick(
					'slickGoTo',
					getSlickIndex()
				);
			}
		} );
	}

	// Maybe-like slider
	$( '.single-product-maybe-like-products' ).slick( {
		slidesToShow: 5,
		slidesToScroll: 1,
		rows: 1,
		arrows: false,
		dots: false,
		responsive: [
			{
				breakpoint: 1200,
				settings: {
					slidesToShow: 4,
					variableWidth: true,
				},
			},
		],
	} );

	// Recently-viewed slider
	$( '.single-product-recently-viewed-products' ).slick( {
		slidesToShow: 5,
		slidesToScroll: 1,
		rows: 1,
		arrows: false,
		dots: false,
		responsive: [
			{
				breakpoint: 1200,
				settings: {
					slidesToShow: 4,
					variableWidth: true,
				},
			},
		],
	} );
} )( jQuery );
