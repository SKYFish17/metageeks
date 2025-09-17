<?php
/**
 * Custom WooCommerce template for Contact Us page in account.
 *
 * @package MetaGeeks
 */

if ( function_exists( 'gravity_form' ) ) {
	gravity_form( 'Contact us', $display_title = true, $display_description = true, $display_inactive = false, $field_values = null, $ajax = false, $tabindex = null, $echo = true );
}
