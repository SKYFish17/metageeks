<?php
/**
 * Add ACF custom fields for menu.
 *
 * @package MetaGeeks
 */

add_filter('wp_nav_menu_objects', 'bda_wp_nav_menu_objects', 10, 2);

function bda_wp_nav_menu_objects( $items, $args ) {

	foreach( $items as $item ) {

		$icon = get_field('icon', $item);

		if( $icon ) {

			$item->title .= ' <img class="menu-item-icon" src="'.$icon['url'].'"" width="23px" height="23px">';

		}

	}

	return $items;
}
