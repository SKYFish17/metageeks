'use strict';

const block = document.querySelector( '.product-order-controls' );
const productVariable = document.querySelector(
	'.single-product__wrapper.product-type-variable'
);

if ( block ) {
	const MIN_PRODUCT_NUMBER_TO_ORDER = block
		.querySelector( '.product-order-controls__count' )
		.getAttribute( 'min' );
	const MAX_PRODUCT_NUMBER_TO_ORDER = block
		.querySelector( '.product-order-controls__count' )
		.getAttribute( 'max' );

	const btnMinusProductCount = block.querySelector(
		'.product-order-controls__minus'
	);
	const btnPlusProductCount = block.querySelector(
		'.product-order-controls__plus'
	);

	if ( productVariable ) {
		const productCountInput = block.querySelector(
			'.quantity input[name="quantity"]'
		);

		// const btnAddToCart = block.querySelector(
		// 	'.single_add_to_cart_button'
		// );

		btnPlusProductCount.addEventListener( 'click', () => {
			if (
				productCountInput.getAttribute( 'max' ) === '' ||
				productCountInput.value <
					productCountInput.getAttribute( 'max' )
			) {
				productCountInput.value++;
			}
		} );

		btnMinusProductCount.addEventListener( 'click', () => {
			if (
				productCountInput.value >
				productCountInput.getAttribute( 'min' )
			) {
				productCountInput.value--;
			}
		} );
	} else {
		const productCountInput = block.querySelector(
			'.product-order-controls__count'
		);
		const btnAddToCart = block.querySelector( '.add_to_cart_button' );

		btnMinusProductCount.addEventListener( 'click', () => {
			if ( productCountInput.value > MIN_PRODUCT_NUMBER_TO_ORDER ) {
				productCountInput.value--;
				btnAddToCart.dataset.quantity--;
			}
		} );

		btnPlusProductCount.addEventListener( 'click', () => {
			if (
				( MAX_PRODUCT_NUMBER_TO_ORDER &&
					productCountInput.value < MAX_PRODUCT_NUMBER_TO_ORDER ) ||
				! MAX_PRODUCT_NUMBER_TO_ORDER
			) {
				productCountInput.value++;
				btnAddToCart.dataset.quantity++;
			}
		} );

		productCountInput.addEventListener( 'input', () => {
			if (
				Number( productCountInput.value ) >=
					MIN_PRODUCT_NUMBER_TO_ORDER &&
				( ( MAX_PRODUCT_NUMBER_TO_ORDER &&
					Number( productCountInput.value ) <=
						MAX_PRODUCT_NUMBER_TO_ORDER ) ||
					! MAX_PRODUCT_NUMBER_TO_ORDER )
			) {
				btnAddToCart.dataset.quantity = Number(
					productCountInput.value
				);
			}

			if (
				Number( productCountInput.value ) <
					MIN_PRODUCT_NUMBER_TO_ORDER &&
				productCountInput.value !== ''
			) {
				btnAddToCart.dataset.quantity = MIN_PRODUCT_NUMBER_TO_ORDER;
				productCountInput.value = MIN_PRODUCT_NUMBER_TO_ORDER;
			}

			if (
				MAX_PRODUCT_NUMBER_TO_ORDER &&
				Number( productCountInput.value ) > MAX_PRODUCT_NUMBER_TO_ORDER
			) {
				btnAddToCart.dataset.quantity = MAX_PRODUCT_NUMBER_TO_ORDER;
				productCountInput.value = MAX_PRODUCT_NUMBER_TO_ORDER;
			}
		} );

		productCountInput.addEventListener( 'mouseleave', () => {
			if ( productCountInput.value === '' ) {
				productCountInput.value = MIN_PRODUCT_NUMBER_TO_ORDER;
			}
		} );
	}
}
