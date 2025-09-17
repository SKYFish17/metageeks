<?php
/**
 * Ajax posts loading
 */

function bda_faq_search(){

	$search_term = isset( $_POST[ 'term' ] ) ? $_POST[ 'term' ] : '';
	$search_sources = isset( $_POST[ 'source' ] ) ? $_POST[ 'source' ] : [];

	$results = [];

	foreach ($search_sources as $search_source) {
		if ( stripos( $search_source, $search_term ) !== false ) {
			$results[] = array(
				'label' => $search_source,
				'id' => preg_replace('/\s+/', '_', strtolower( $search_source ) ),
			);
		}
	}
	wp_send_json( $results );
}

add_action( 'wp_ajax_faqsearch', 'bda_faq_search' );
add_action( 'wp_ajax_nopriv_faqsearch', 'bda_faq_search' );
