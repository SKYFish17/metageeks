'use strict';

import '../vendor/jquery.countdown.min.js';

const countdownWrapper = document.querySelector(
	'.product-order-key-info__item_dispatch[data-countdown-date]'
);

( function ( $ ) {
	if ( countdownWrapper ) {
		const countdownValue = countdownWrapper.querySelector(
			'.product-order-key-info__important'
		);
		const countdownDate = new Date(
			Date.parse( countdownWrapper.dataset.countdownDate )
		);

		$( countdownValue )
			.countdown( countdownDate, function ( evt ) {
				let str;
				let monthSign;
				let daySign;

				if ( evt.offset.months > 1 ) {
					monthSign = 'months';
				} else {
					monthSign = 'month';
				}

				if ( evt.offset.daysToMonth > 1 ) {
					daySign = 'days';
				} else {
					daySign = 'day';
				}

				if ( evt.offset.months >= 1 ) {
					if ( evt.offset.daysToMonth >= 1 ) {
						str =
							evt.offset.months +
							' ' +
							monthSign +
							' ' +
							evt.offset.daysToMonth +
							' ' +
							daySign;
					} else {
						str = evt.offset.months + ' ' + monthSign;
					}
				} else if ( evt.offset.months < 1 ) {
					if ( evt.offset.daysToMonth >= 1 ) {
						str =
							evt.offset.daysToMonth +
							' ' +
							daySign +
							' ' +
							evt.offset.hours +
							' hours ';
					} else if ( evt.offset.daysToMonth < 1 ) {
						if ( evt.offset.hours >= 1 ) {
							str = evt.offset.hours + ' hours ';
						} else {
							str =
								evt.offset.minutes +
								' minutes ' +
								evt.offset.seconds +
								' seconds ';
						}
					}
				}

				$( this ).text( str );
			} )
			.on( 'finish.countdown', () => {
				$( countdownWrapper ).text( 'Available for order now' );
			} );
	}
} )( jQuery );
