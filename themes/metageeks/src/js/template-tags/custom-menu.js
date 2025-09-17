'use strict';

const menu = document.querySelector( 'ul.custom-navigation-menu' );

if ( menu ) {
	const menuItemsWithChild = menu.querySelectorAll(
		'.menu-item-has-children'
	);

	menuItemsWithChild.forEach( ( menuItem ) => {
		menuItem.addEventListener( 'mouseenter', () => {
			const subMenuWrapper = menuItem.querySelector(
				'.sub-menu-wrapper'
			);
			const pointer = menuItem.querySelector( '.pointer' );
			const menuItemWidth = menuItem.getBoundingClientRect().width;

			// set coord for  pointer-arrow of default subMenuWrapper
			pointer.style.left = menuItemWidth / 2 + 'px';

			if ( subMenuWrapper.classList.contains( 'extended-view' ) ) {
				menuItem.style.position = 'static';

				// set coord for pointer-arrow of extended-view - subMenuWrapper
				pointer.style.left =
					menuItem.getBoundingClientRect().left -
					menu.getBoundingClientRect().left +
					menuItemWidth / 2 +
					'px';
			}

			subMenuWrapper.classList.add( 'show' );
		} );

		menuItem.addEventListener( 'mouseleave', () => {
			const subMenuWrapper = menuItem.querySelector(
				'.sub-menu-wrapper '
			);

			subMenuWrapper.classList.remove( 'show' );
		} );
	} );
}
