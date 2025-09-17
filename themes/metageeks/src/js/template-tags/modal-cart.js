'use strict';

import 'simplebar';

const NOTIFICATION_DISPLAY_TIMER = 3000;

const openModalBtnWrapper = document.querySelector( '.shop-buttons .cart' );
const addToCartButtons = document.querySelectorAll( '.add_to_cart_button' );
const headerCartBtn = document.querySelector( '.header-cart-btn' );
const modal = document.querySelector( '.modal-cart' );
const modalRemoveProduct = document.querySelector( '.modal-product-remove' );
const modalBackground = document.querySelector( '.modal-background' );
const body = document.querySelector( 'body' );

if ( modal && modalRemoveProduct && modalBackground ) {
	let currentProduct;

	// function updateModalCart() {
	// 	jQuery
	// 		.ajax( {
	// 			method: 'POST',
	// 			url: window.bda_ajax.url,
	// 			data: {
	// 				action: 'update_modal_cart',
	// 			},
	// 		} )
	// 		.done( function ( msg ) {
	// 			const response = JSON.parse( msg );
	// 			modal.innerHTML = response;
	// 		} );
	// }

	function emptyModalCart() {
		jQuery
			.ajax( {
				method: 'POST',
				url: window.bda_ajax.url,
				data: {
					action: 'empty_modal_cart',
				},
			} )
			.done( function ( msg ) {
				const response = JSON.parse( msg );
				modal.innerHTML = response;
				changeHeaderCartBtnData( 0, '0.00' );
			} );
	}

	function addModalRemoveProductEventHandler( evt ) {
		const target = evt.target;

		if ( target.closest( '.modal-product-remove .btn-cancel' ) ) {
			closeModalProductRemove();
		} else if ( target.closest( '.modal-product-remove .btn-remove' ) ) {
			changeProductData(
				currentProduct,
				'product_delete',
				changeHeaderCartBtnData,
				setCartEmptyStatus
			);
			closeModalProductRemove();
		} else if (
			! target.closest( '.modal-product-remove' ) &&
			! evt.target.closest( '.delete' )
		) {
			closeModalProductRemove();
		}
	}

	function closeModalProductRemove() {
		modalRemoveProduct.classList.remove( 'active' );
		modalBackground.classList.remove( 'second-modal-active' );

		document.removeEventListener(
			'click',
			addModalRemoveProductEventHandler
		);
	}

	function openModalProductRemove() {
		modalRemoveProduct.classList.add( 'active' );
		modalBackground.classList.add( 'second-modal-active' );

		document.addEventListener( 'click', addModalRemoveProductEventHandler );
	}

	function setCartEmptyStatus( cartContentsCount ) {
		if ( 0 === Number( cartContentsCount ) ) {
			modal.querySelector( '.modal-inner' ).classList.add( 'empty-cart' );
		}
	}

	function changeHeaderCartBtnData( count, subtotal ) {
		document.querySelector(
			'.header-cart-btn .value'
		).innerHTML = subtotal;
		document.querySelector( '.header-cart-btn .count' ).innerHTML = count;
	}

	// Ajax update data
	function changeProductData( product, action, qty, cb1, cb2 ) {
		const productCountWrapper = product.querySelector( 'input.qty' );
		const modalCartContentsCount = modal.querySelector( '.title .count' );

		jQuery
			.ajax( {
				method: 'POST',
				url: window.bda_ajax.url,
				data: {
					action,
					qty,
					product_id: product.dataset.productId,
					variation_id: product.dataset.variationId,
				},
			} )
			.done( function ( msg ) {
				const response = JSON.parse( msg );

				const cartBottomPart = modal.querySelector( '.bottom' );

				if (
					Number( response.total_product_qty ) === 0 ||
					'product_delete' === action
				) {
					product.remove();
				}

				if ( 'product_delete' !== action && productCountWrapper ) {
					productCountWrapper.value = response.total_product_qty;
				}

				// Update bottom part of modal
				cartBottomPart.innerHTML = '';
				cartBottomPart.insertAdjacentHTML(
					'afterbegin',
					response.bottom_part
				);

				if ( response.cart_contents_count ) {
					modalCartContentsCount.innerHTML =
						response.cart_contents_count;
				}

				cb1( response.cart_contents_count, response.cart_subtotal );

				if ( cb2 ) {
					cb2( response.cart_contents_count );
				}
			} );
	}

	function updateHeaderCartBtnData() {
		jQuery
			.ajax( {
				method: 'POST',
				url: window.bda_ajax.url,
				data: {
					action: 'get_header_cart_btn',
				},
			} )
			.done( function ( msg ) {
				const response = JSON.parse( msg );

				headerCartBtn.innerHTML = response.innerHTML;
			} );
	}

	// Modal control
	if ( openModalBtnWrapper ) {
		function modalControl( command ) {
			if ( window.innerWidth >= 1200 ) {
				if ( command === 'active' ) {
					// updateModalCart(); //eslint-disable-line
					body.classList.add( 'modal-active' );
					modal.classList.add( 'active' );
					modalBackground.classList.add( 'active' );
				} else if ( command === 'inactive' ) {
					body.classList.remove( 'modal-active' );
					modal.classList.remove( 'active' );
					modalBackground.classList.remove( 'active' );
				}
			}
		}

		function servesNotification() {
			modal.classList.add( 'product-added' );
			setTimeout( () => {
				modal.classList.remove( 'product-added' );
			}, NOTIFICATION_DISPLAY_TIMER );
		}

		openModalBtnWrapper.addEventListener( 'click', () => {
			if ( window.innerWidth < 1200 ) {
				window.location.href = window.bda_home_url.url + '/cart';
			} else {
				modalControl( 'active' );
			}
		} );

		if ( addToCartButtons.length ) {
			addToCartButtons.forEach( ( btn ) => {
				btn.addEventListener( 'click', () => {
					modalControl( 'active' );
					servesNotification();
					modal.classList.remove( '.empty-cart' );
				} );
			} );
		}

		modal.addEventListener( 'click', ( evt ) => {
			currentProduct = evt.target.closest( '.item' );

			if (
				! evt.target.closest( '.modal-inner' ) ||
				evt.target.closest( '.empty-cart-stub .btn' )
			) {
				modalControl( 'inactive' );
				updateHeaderCartBtnData();
			}

			if ( evt.target.closest( '.plus' ) ) {
				const inputQty = evt.target
					.closest( '.controls' )
					.querySelector( 'input.qty' );
				const maxValue = inputQty.getAttribute( 'max' );

				if ( ( maxValue && inputQty.value < maxValue ) || ! maxValue ) {
					changeProductData(
						currentProduct,
						'product_qty_change',
						Number( inputQty.value ) + 1,
						changeHeaderCartBtnData
					);
				}
			} else if ( evt.target.closest( '.minus' ) ) {
				const inputQty = evt.target
					.closest( '.controls' )
					.querySelector( 'input.qty' );
				const minValue = 1;

				if ( inputQty.value > minValue ) {
					changeProductData(
						currentProduct,
						'product_qty_change',
						Number( inputQty.value ) - 1,
						changeHeaderCartBtnData
					);
				}
			} else if ( evt.target.closest( '.delete' ) ) {
				openModalProductRemove( currentProduct );
			}

			if ( evt.target.closest( '.btn-clear' ) ) {
				emptyModalCart();
			}
		} );

		modal.addEventListener( 'change', ( evt ) => {
			currentProduct = evt.target.closest( '.item' );

			if ( evt.target.closest( 'input.qty' ) ) {
				const target = evt.target.closest( 'input.qty' );
				const maxValue = target.getAttribute( 'max' );

				if ( target.value < 1 ) {
					target.value = 1;
				}

				if ( maxValue && Number( target.value ) > maxValue ) {
					target.value = Number( maxValue );
				}

				changeProductData(
					currentProduct,
					'product_qty_change',
					Number( target.value ),
					changeHeaderCartBtnData
				);
			}
		} );
	}
}
