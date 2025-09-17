'use strict';

const archivePage = document.querySelector( '.mg-site-main' );
const layoutButtons = document.querySelector( '.layout-buttons' );
const catalog = document.querySelector( '.mg-products' );

if ( archivePage && layoutButtons && catalog ) {
	function clearActiveItemStatus() {
		const items = layoutButtons.querySelectorAll( '.item' );

		items.forEach( ( item ) => {
			item.classList.remove( 'active' );
		} );
	}

	function onLayoutButtonsClickHandler( evt ) {
		const elem = evt.target.closest( '.item' );

		// Set button active-status
		clearActiveItemStatus();

		if ( elem ) {
			elem.classList.add( 'active' );
		}

		// Change catalog class
		if ( elem.firstElementChild.dataset.layoutType === 'line' ) {
			catalog.classList.add( 'layout-line' );
		} else {
			catalog.classList.remove( 'layout-line' );
		}
	}

	layoutButtons.addEventListener( 'click', onLayoutButtonsClickHandler );

	window.addEventListener( 'resize', () => {
		if (
			window.innerWidth <= 768 &&
			catalog.classList.contains( 'layout-line' )
		) {
			catalog.classList.remove( 'layout-line' );
		}
	} );
}
