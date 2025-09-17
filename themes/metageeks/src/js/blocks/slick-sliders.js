'use strict';

import 'slick-carousel';

( function initSlickSliders( $ ) {
	/* Add slick-slider script here */

	// Deal-of-the-week slider
	const dealOfTheWeekSliderInit = function ( $block ) {
		const $slider = $block.find( '.images' );

		$slider.not( '.slick-initialized' ).slick( {
			slidesToScroll: 1,
			slidesToShow: 3,
			dots: true,
			appendDots: $block.find( '.images' ),
			arrows: false,
			rows: false,
			variableWidth: true,
			infinite: true,
			draggable: true,
			responsive: [
				{
					breakpoint: 900,
					settings: {
						draggable: true,
					},
				},
			],
		} );

		// Add custom slider scripts here
		const block = document.querySelector( '.deal-of-the-week-block' );
		const subtitle = block.querySelector( '.subtitle' );
		const textBlock = block.querySelector( '.text' );
		const slider = block.querySelector( '.images' );
		let isMobile = false;
		let isDesktop = true;

		// Mobile-version of block init
		if ( window.innerWidth <= 900 ) {
			subtitle.after( slider );
			isMobile = true;
			isDesktop = false;
		}

		// Init mobile or desktop version of the block depending on window-width
		window.addEventListener( 'resize', () => {
			if ( window.innerWidth <= 900 && ! isMobile ) {
				subtitle.after( slider );
				isMobile = true;
				isDesktop = false;
			} else if ( window.innerWidth > 900 && ! isDesktop ) {
				textBlock.after( slider );
				isDesktop = true;
				isMobile = false;
			}
		} );
	};

	// Blog-posts slider
	const blogPostsSliderInit = function ( $block ) {
		const $slider = $block.find( '.list' );

		$slider
			.not( '.slick-initialized' )
			.not( '.no-slider' )
			.slick( {
				slidesToScroll: 1,
				slidesToShow: 3,
				dots: true,
				arrows: true,
				prevArrow: `<button type="button" class="slick-prev"><svg width="9" height="15" viewBox="0 0 9 15" xmlns="http://www.w3.org/2000/svg">
				<path d="M1.22751 13.8821C1.5746 14.2292 2.13418 14.2292 2.48126 13.8821L8.36751 7.9959C8.64376 7.71965 8.64376 7.2734 8.36751 6.99715L2.48126 1.1109C2.13418 0.763815 1.5746 0.763815 1.22751 1.1109C0.88043 1.45798 0.88043 2.01757 1.22751 2.36465L6.35585 7.50007L1.22043 12.6355C0.88043 12.9755 0.88043 13.5421 1.22751 13.8821Z"/>
				</svg>
				</button>`,
				nextArrow: `<button type="button" class="slick-next"><svg width="9" height="15" viewBox="0 0 9 15" xmlns="http://www.w3.org/2000/svg">
				<path d="M1.22751 13.8821C1.5746 14.2292 2.13418 14.2292 2.48126 13.8821L8.36751 7.9959C8.64376 7.71965 8.64376 7.2734 8.36751 6.99715L2.48126 1.1109C2.13418 0.763815 1.5746 0.763815 1.22751 1.1109C0.88043 1.45798 0.88043 2.01757 1.22751 2.36465L6.35585 7.50007L1.22043 12.6355C0.88043 12.9755 0.88043 13.5421 1.22751 13.8821Z"/>
				</svg>
				</button>`,
				rows: false,
				variableWidth: false,
				infinite: true,
				draggable: true,
				centerMode: true,
				centerPadding: '0px',
				responsive: [
					{
						breakpoint: 576,
						settings: {
							slidesToShow: 1,
							arrows: false,
							centerPadding: '40px',
						},
					},
				],
			} );
	};

	// Our-partners slider
	const ourPartnersSliderInit = function ( $block ) {
		const $slider = $block.find( '.list' );

		$slider.not( '.slick-initialized' ).slick( {
			slidesToScroll: 1,
			slidesToShow: 8,
			autoplay: true,
			autoplaySpeed: 1000,
			dots: false,
			arrows: false,
			rows: false,
			infinite: true,
			draggable: true,
			responsive: [
				{
					breakpoint: 900,
					settings: {
						slidesToShow: 6,
					},
				},
				{
					breakpoint: 576,
					settings: {
						slidesToShow: 4,
					},
				},
			],
		} );
	};

	// Featured products slider
	const featuredProductsSlider = function ( $block ) {
		const $slider = $block.find( '.featured-products-block__list' );

		if ( window.innerWidth <= 1200 ) {
			featuredProductsSliderInit( $slider );
		}

		window.addEventListener( 'resize', () => {
			if ( window.innerWidth <= 1200 ) {
				featuredProductsSliderInit( $slider );
			} else if ( window.innerWidth > 1200 ) {
				$slider.slick( 'unslick' );
			}
		} );
	};

	const featuredProductsSliderInit = function ( $slider ) {
		$slider
			.not( '.slick-initialized' )
			.not( '.no-slider' )
			.slick( {
				slidesToScroll: 1,
				slidesToShow: 3.5,
				dots: false,
				arrows: false,
				rows: false,
				variableWidth: false,
				infinite: false,
				draggable: true,
				responsive: [
					{
						breakpoint: 900,
						settings: {
							slidesToShow: 2.8,
						},
					},
					{
						breakpoint: 576,
						settings: {
							slidesToShow: 1.7,
						},
					},
				],
			} );
	};

	// Block: Product Sliders
	const metageeksProductSliderInit = function ( $block ) {
		const $slider = $block.find( '.mg-product-slider' );

		$slider.slick( {
			slidesToShow: 5,
			infinite: true,
			prevArrow: $block.find( '.mg-product-slider__prev' ),
			nextArrow: $block.find( '.mg-product-slider__next' ),
			responsive: [
				{
					breakpoint: 1200,
					settings: {
						slidesToShow: 4,
					},
				},
				{
					breakpoint: 900,
					settings: {
						slidesToShow: 1.74,
						arrows: false,
						infinite: false,
					},
				},
			],
		} );
	};
	// -- Block: Product Sliders --

	// Initialize each block on page load (front end).
	$( function () {
		$( '.deal-of-the-week-block' ).each( function () {
			dealOfTheWeekSliderInit( $( this ) );
		} );

		$( '.blog-posts-block' ).each( function () {
			blogPostsSliderInit( $( this ) );
		} );

		$( '.our-partners-block' ).each( function () {
			ourPartnersSliderInit( $( this ) );
		} );

		$( '.featured-products-block' ).each( function () {
			featuredProductsSlider( $( this ) );
		} );

		$( '.mg-product-slider__container' ).each( function () {
			metageeksProductSliderInit( $( this ) );
		} );
	} );

	// Initialize dynamic block preview (editor).
	if ( window.acf ) {
		window.acf.addAction(
			'render_block_preview/type=deal-of-the-week-block',
			dealOfTheWeekSliderInit
		);

		window.acf.addAction(
			'render_block_preview/type=blog-posts-block',
			blogPostsSliderInit
		);

		window.acf.addAction(
			'render_block_preview/type=our-partners-block',
			ourPartnersSliderInit
		);

		window.acf.addAction(
			'render_block_preview/type=product-slider',
			metageeksProductSliderInit
		);

		window.acf.addAction(
			'render_block_preview/type=brand-products',
			metageeksProductSliderInit
		);
	}
} )( jQuery );
