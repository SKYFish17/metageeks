'use strict';

const filtersBlocks = document.querySelectorAll( '.archive .filters' );

if ( filtersBlocks.length ) {
	filtersBlocks.forEach( ( filters ) => {
		const metaSlider = filters.querySelector( '.meta-slider' );

		function priceRangeUpdate() {
			const minRange = filters.querySelector( '.sf-range-min' );
			const maxRange = filters.querySelector( '.sf-range-max' );

			if ( minRange && maxRange ) {
				const MIN_VALUE_INPUT_RANGE = Math.round(
					Number( minRange.innerHTML ) * window.currencyRate
				);

				const MAX_VALUE_INPUT_RANGE = Math.round(
					Number( maxRange.innerHTML ) * window.currencyRate
				);

				minRange.innerHTML = MIN_VALUE_INPUT_RANGE;
				maxRange.innerHTML = MAX_VALUE_INPUT_RANGE;

				metaSlider.noUiSlider.on( 'update', () => {
					minRange.innerHTML = Math.round(
						Number( minRange.innerHTML ) * window.currencyRate
					);
					maxRange.innerHTML = Math.round(
						Number( maxRange.innerHTML ) * window.currencyRate
					);
				} );
			}
		}

		setTimeout( priceRangeUpdate, 3000 );
	} );
}
