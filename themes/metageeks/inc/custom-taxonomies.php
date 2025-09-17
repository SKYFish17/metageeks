<?php
/**
 * Create taxonomies.
 *
 * @see register_post_type() for registering custom post types.
 */
function bda_create_product_brands_taxonomies() {
    // Add new taxonomy, NOT hierarchical (like tags)
    $labels = array(
        'name'                       => _x( 'Product brands', 'taxonomy general name', 'textdomain' ),
        'singular_name'              => _x( 'Product brand', 'taxonomy singular name', 'textdomain' ),
        'search_items'               => __( 'Search product brands', 'textdomain' ),
        'popular_items'              => __( 'Popular product brands', 'textdomain' ),
        'all_items'                  => __( 'All product brands', 'textdomain' ),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'edit_item'                  => __( 'Edit product brand', 'textdomain' ),
        'update_item'                => __( 'Update product brand', 'textdomain' ),
        'add_new_item'               => __( 'Add New product brand', 'textdomain' ),
        'new_item_name'              => __( 'New product brand Name', 'textdomain' ),
        'separate_items_with_commas' => __( 'Separate product brands with commas', 'textdomain' ),
        'add_or_remove_items'        => __( 'Add or remove product brand', 'textdomain' ),
        'choose_from_most_used'      => __( 'Choose from the most used product brands', 'textdomain' ),
        'not_found'                  => __( 'No product brands found.', 'textdomain' ),
        'menu_name'                  => __( 'Product brands', 'textdomain' ),
    );

    $args = array(
        'hierarchical'          => false,
        'labels'                => $labels,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var'             => true,
        'rewrite'               => array( 'slug' => 'product_brands' ),
    );

    register_taxonomy( 'product_brands', 'product', $args );
}
// hook into the init action and call bda_create_product_brands_taxonomies when it fires
add_action( 'init', 'bda_create_product_brands_taxonomies', 0 );
