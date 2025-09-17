/**
 * Load posts ajax
 *
 */

( function ( $ ) {
	const postsPerPage = 9;
	let pageNumber = 1;

	function loadPosts() {
		pageNumber++;
		const str =
			'&pageNumber=' +
			pageNumber +
			'&postsPerPage=' +
			postsPerPage +
			'&action=posts_loadmore_ajax';
		$.ajax( {
			type: 'POST',
			dataType: 'html',
			url: window.bda_ajax.url,
			data: str,
			success( data ) {
				const $data = $( data );
				if ( $data.length !== 0 && $data.length > 26 ) {
					$( '#ajax-posts' ).append( $data );
				} else if ( $data.length !== 0 && $data.length <= 26 ) {
					$( '#ajax-posts' ).append( $data );
					$( '#posts_loadmore' ).hide();
				} else {
					$( '#posts_loadmore' ).hide();
				}
			},
			error( jqXHR, textStatus, errorThrown ) {
				// eslint-disable-next-line no-undef
				$( '#posts_loadmore' ).html(
					jqXHR + ' :: ' + textStatus + ' :: ' + errorThrown
				);
			},
		} );
		return false;
	}

	if ( $( '#posts_loadmore' ).length ) {
		$( '#posts_loadmore' ).on( 'click', function () {
			$( '#posts_loadmore' ).attr( 'disabled', true );
			loadPosts();
			$( this ).insertAfter( '#ajax-posts' );
			$( '#posts_loadmore' ).attr( 'disabled', false );
		} );
	}

	if ( $( '#post_categories' ).length ) {
		$( '#post_categories' ).on( 'change', function () {
			window.location.href = this.value;
		} );
	}
} )( jQuery );
