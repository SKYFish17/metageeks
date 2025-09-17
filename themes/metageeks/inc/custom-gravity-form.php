<?php
/**
 * Add custom function for a gravity form.
 *
 * @package MetaGeeks
 */

 // Form 1 field 1
add_filter( 'gform_field_validation_1_1', 'bda_gform_validation', 10, 4 );

function bda_gform_validation( $result, $value, $form, $field ) {

	if ( !$result['is_valid'] ) {
		$result['is_valid'] = false;

		if ( $value === '' ) {
			$result['message'] = 'Please enter your email address.';
		} else {
			$result['message'] = 'Please enter a valid email address.';
		}
	}
    return $result;
}
