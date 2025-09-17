'use strict';

import 'select2';

const shopPageOptions = document.querySelector( '.mg-site-main .options' );

if ( shopPageOptions ) {
	jQuery( document ).ready( function () {
		jQuery( 'select[class="orderby"]' ).select2();
	} );
}
