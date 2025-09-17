'use strict';

const productPage = document.querySelector( '.single-product' );
const variablesList = document.querySelector( '.product-order-variations' );
const variablesSelect = document.querySelector( '#pa_value' );
const variationsForm = document.querySelector( '.variations_form' );

const mobileWrapper = document.querySelector(
	'.single-product-primary__mobile'
);
const productOrderInfo = document.querySelector(
	'.woocommerce-variation.single_variation'
);
const productSlider = document.querySelector( '.product-slider' );

if ( productPage && variablesList && variablesSelect ) {
	// Swithing a variable product
	variablesList.addEventListener( 'click', ( evt ) => {
		const target = evt.target;
		const item = target.closest( '.product-order-variation' );
		const button = target.closest( '.product-order-variation__button' );
		const submitButton = productPage.querySelector( '.add_to_cart_button' );

		if ( button ) {
			const selectedOption = jQuery( variablesSelect ).find(
				'option[selected="selected"]'
			);
			const variantToSelect = jQuery( variablesSelect ).find(
				`option[value="${ button.dataset.variationName }"]`
			);
			const selectedItem = jQuery( variablesList ).find( 'li.checked' );

			if ( variantToSelect ) {
				jQuery( selectedItem ).removeClass( 'checked' );
				jQuery( item ).addClass( 'checked' );

				jQuery( selectedOption ).prop( 'selected', false );

				jQuery( variantToSelect ).prop( 'selected', true ).click();
				jQuery( variantToSelect ).change();

				jQuery( submitButton ).attr(
					'href',
					`?add-to-cart=${ button.dataset.variationId }`
				);
				jQuery( submitButton ).attr(
					'data-product_id',
					`${ button.dataset.variationId }`
				);
			}
		}
	} );

	// Adaptive for variable product
	let isMobileView = false;

	function mobileView() {
		mobileWrapper.insertAdjacentElement( 'beforeEnd', variablesList );
		mobileWrapper.insertAdjacentElement( 'beforeEnd', variationsForm );
		productOrderInfo.insertAdjacentElement( 'afterEnd', productSlider );
		isMobileView = true;
	}

	function desktopView() {
		window.location.reload();
		isMobileView = false;
	}

	// First loading mobile view on mobile breakpoint
	if ( window.innerWidth < 769 ) {
		mobileView();
	}

	window.addEventListener( 'resize', () => {
		if ( window.innerWidth < 769 && ! isMobileView ) {
			mobileView();
		} else if ( window.innerWidth >= 769 && isMobileView ) {
			desktopView();
		}
	} );
}
