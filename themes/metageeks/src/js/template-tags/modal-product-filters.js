'use strict';

const archivePage = document.querySelector( '.archive' );
const btnOpenFiltersModal = document.querySelector( '.btn-filter-products' );
const modalProductFilters = document.querySelector( '.modal-product-filters' );
const body = document.querySelector( 'body' );

if ( archivePage && btnOpenFiltersModal && modalProductFilters ) {
	const notification = document.querySelector( '.woocommerce-info' );
	const btnCloseModal = modalProductFilters.querySelector( '.close' );

	// Disable filters button if no products found
	if ( notification ) {
		btnOpenFiltersModal.classList.add( 'disabled' );
	}

	btnOpenFiltersModal.addEventListener( 'click', () => {
		modalProductFilters.classList.add( 'active' );
		body.classList.add( 'modal-active' );
	} );

	btnCloseModal.addEventListener( 'click', () => {
		modalProductFilters.classList.remove( 'active' );
		body.classList.remove( 'modal-active' );
	} );
}
