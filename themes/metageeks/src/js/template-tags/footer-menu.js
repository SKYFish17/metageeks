'use strict';

// Add contact us block into site-footer menu list (due to the hard grid on the mobile version)
const siteFooter = document.querySelector( '.site-footer' );

if ( siteFooter ) {
	const footerMenu = siteFooter.querySelector( '#footer-menu' );
	const contactUsMenuItem = footerMenu.querySelector(
		'.menu-item-contact-us'
	);
	const contactUsBlock = siteFooter.querySelector( '.contact-us' );
	const mascotImage = siteFooter.querySelector(
		'.site-footer__mascot-wrapper'
	);

	if ( contactUsBlock && contactUsMenuItem ) {
		contactUsMenuItem.insertAdjacentHTML(
			'beforeend',
			contactUsBlock.innerHTML
		);
	}

	if ( mascotImage ) {
		footerMenu.insertAdjacentElement( 'beforeend', mascotImage );
	}

	function removeContactUsBlock() {
		if ( contactUsBlock ) {
			contactUsBlock.remove();
		}
	}

	removeContactUsBlock();
}
