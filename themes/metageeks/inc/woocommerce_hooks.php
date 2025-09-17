<?php
/**
 * WooCommerce Action hooks and filters.
 *
 * A place to put hooks and filters that aren't necessarily template tags.
 *
 * @package MetaGeeks
 */

/**
 * Adds availaibility status
 */
add_filter( 'woocommerce_get_availability_text', 'bda_get_availability_text', 10, 2 );
function bda_get_availability_text( $availability, $product ) {
	if ( ! $product->is_in_stock() ) {
		$availability = __( 'Sold out', 'woocommerce' );
	} elseif ( $product->managing_stock() && $product->is_on_backorder( 1 ) ) {
		$availability = $product->backorders_require_notification() ? __( 'Pre order', 'woocommerce' ) : '';
	} elseif ( ! $product->managing_stock() && $product->is_on_backorder( 1 ) ) {
		$availability = __( 'Pre order', 'woocommerce' );
	} elseif ( $product->managing_stock() ) {
		$availability = wc_format_stock_for_display( $product );
	} else {
		$availability = 'In stock';
	}

	return $availability;
}


/**
 * Change WooCommerce product availability
 *
 * @param array $data Availability text and class.
 * @return array
 */
function metageeks_woocommerce_get_availability( $data = array() ) {

	if ( 'Available for pre-ordering' === $data['availability'] ) {
		$data = array(
			'availability' => 'Pre order',
			'class'        => 'available-on-backorder',
		);
	}

	return $data;
}

add_filter( 'woocommerce_get_availability', 'metageeks_woocommerce_get_availability', 99, 2 );


/**
 * Remove sale-badge on product in catalog
 */
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );

/**
 * Disable the default stylesheet
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Disable notices for archive-product.php
 */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10 );

/**
 * Disable rating for product in content-product.php
 */
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

/**
 * Add product per page select into archive-product.php
 */
// add_action( 'woocommerce_before_shop_loop', 'bda_select_per_page', 35 );
function bda_select_per_page() {
	$per_page = filter_input( INPUT_GET, 'per-page', FILTER_SANITIZE_NUMBER_INT );

	echo '<div class="woocommerce-perpage">';
	echo '<span>Show: </span>';
	echo '<select onchange="if (this.value) window.location.href=this.value">';

	$orderby_options = array(
		'16' => '16 per page',
		'32' => '32 per page',
		'64' => '64 per page',
	);

	foreach ( $orderby_options as $value => $label ) {
		echo '<option ' . selected( $per_page, $value ) . " value='?per-page=$value'>$label</option>";
	}

	echo '</select>';
	echo '</div>';
}

// add_action( 'woocommerce_product_query', 'bda_woocommerce_product_query' );
function bda_woocommerce_product_query( $query ) {
	$per_page = filter_input( INPUT_GET, 'per-page', FILTER_SANITIZE_NUMBER_INT ) ?
				filter_input( INPUT_GET, 'per-page', FILTER_SANITIZE_NUMBER_INT ) :
				16;
	if ( $query->is_main_query() && ( $query->get( 'wc_query' ) === 'product_query' ) ) {
		$query->set( 'posts_per_page', $per_page );
	}
}

/**
 * Updating the cart-button values in the header when adding a product via regular ajax
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment', 10, 1 );
function woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;
	ob_start();
	?>

	<?php echo get_template_part( 'template-parts/header-cart-button', null ); ?>

	<?php
	$fragments['.site-header .shop-buttons .header-cart-btn'] = ob_get_clean();
	return $fragments;
}

/**
 * Updating the modal-cart inner content when adding a product via regular ajax
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_update_cart_modal_content_fragment', 15, 1 );
function woocommerce_update_cart_modal_content_fragment( $fragments ) {
	global $woocommerce;
	ob_start();
	?>

	<?php echo get_template_part( 'template-parts/modal-cart-inner', null ); ?>

	<?php
	$fragments['.modal-cart .modal-inner'] = ob_get_clean();
	return $fragments;
}

/**
 * Add event-handler for clear-basket button in modal
 */
add_action( 'wp_loaded', 'bda_wc_empty_cart_action', 5 );
function bda_wc_empty_cart_action() {
	global $woocommerce;

	if ( isset( $_GET['clear_basket'] ) && 'true' === esc_html( $_GET['clear_basket'] ) ) {
		$woocommerce->cart->empty_cart();
		$referer = wp_get_referer() ? esc_url( remove_query_arg( 'clear_basket' ) ) : wc_get_cart_url();

		if ( isset( $_GET['wc-ajax'] ) && 'add_to_cart' === esc_html( $_GET['wc-ajax'] ) ) {
			$referer = esc_url( remove_query_arg( 'wc-ajax' ) );
		}

		wp_safe_redirect( $referer );
	}
}

add_action( 'template_redirect', 'bda_recently_viewed_product_cookie', 20 );
function bda_recently_viewed_product_cookie() {

	if ( ! is_product() ) {
		return;
	}

	if ( empty( $_COOKIE['bda_woocommerce_recently_viewed'] ) ) {
		$viewed_products = array();
	} else {
		$viewed_products = (array) explode( '|', $_COOKIE['bda_woocommerce_recently_viewed'] );
	}

	// Add currrent product-id in array
	if ( ! in_array( get_the_ID(), $viewed_products ) ) {
		$viewed_products[] = get_the_ID();
	}

	// Limit for product-id in cookies
	if ( sizeof( $viewed_products ) > 15 ) {
		array_shift( $viewed_products );
	}

	// Set cookie
	wc_setcookie( 'bda_woocommerce_recently_viewed', join( '|', $viewed_products ) );

}

/**
 * Change checkout fields address
 */
add_filter( 'woocommerce_default_address_fields', 'custom_default_address_fields' );
function custom_default_address_fields( $fields ) {

  if ( is_checkout() ) {

		$fields['city']['label']       = __( 'City', 'woocommerce' );
		$fields['country']['priority'] = 40;
		$fields['city']['priority']    = 45;

  }

  return $fields;
}

/**
 * Change checkout fields
 */
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {

	if ( is_checkout() ) {
		// priority
		$fields['billing']['billing_email']['priority']     = 30;
		$fields['billing']['billing_address_1']['priority'] = 60;
		$fields['billing']['billing_address_2']['priority'] = 70;
		$fields['billing']['billing_phone']['priority']     = 100;
		// class
		$fields['shipping']['shipping_postcode']['class']  = array( 'full-width' );
		$fields['billing']['billing_address_1']['class']   = array( 'full-width' );
		$fields['billing']['billing_address_2']['class']   = array( 'full-width' );
		$fields['billing']['billing_email']['class']       = array( 'full-width' );
		$fields['shipping']['shipping_address_1']['class'] = array( 'full-width' );
		$fields['shipping']['shipping_address_2']['class'] = array( 'full-width' );

		$fields['order']['order_comments']['placeholder'] = __( 'Enter your notes here...', 'woocommerce' );

		$fields['account']['account_username']['label'] = __( 'Username ', 'woocommerce' );
		$fields['account']['account_password']['label'] = __( 'Password', 'woocommerce' );

		unset( $fields['billing']['billing_state'] );
		unset( $fields['shipping']['shipping_state'] );
  	unset( $fields['billing']['billing_company'] );
		unset( $fields['shipping']['shipping_company'] );

	}

  return $fields;
}

/**
 * Remove coupon checkout page
 */
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

/**
 * Change text button checkout
 */
add_filter( 'woocommerce_order_button_text', 'woo_custom_order_button_text' );
function woo_custom_order_button_text() {
  return __( 'Proceed payment', 'woocommerce' );
}

/**
 * Add checkbox page checkout
 */
add_action( 'woocommerce_review_order_before_submit', 'add_order_stock_checkbox', 9 );
function add_order_stock_checkbox() {
	woocommerce_form_field( 'order-stock', array(
		'type' => 'checkbox',
		'class' => array('form-row'),
		'label_class' => array('woocommerce-form__label woocommerce-form__label-for-checkbox checkbox'),
		'input_class' => array('woocommerce-form__input woocommerce-form__input-checkbox input-checkbox'),
		'required' => true,
		'label' => '<span>I understand that orders containing pre-order items will not be shipped until ALL items in the order are in stock.</span>',
	));
}
/**
 * Show notice if customer does not tick
 */
add_action( 'woocommerce_checkout_process', 'order_stock_checkbox_error_message' );
function order_stock_checkbox_error_message() {
	if ( ! (int) isset( $_POST['order-stock'] ) ) {
		wc_add_notice( __( 'Please confirm that you knowledge our <a href="' . get_permalink( 102 ) .'">pre-order policy</a>.' ), 'error' );
	}
}

/**
* Update custom shipping method
*/
function my_custom_shipping_list_update( $fragments ) {
	ob_start();
	?>
	<div class="my-custom-shipping-list">
		<?php wc_cart_totals_shipping_html(); ?>
	</div>
	<?php
	$woocommerce_shipping_methods = ob_get_clean();
	$fragments['.my-custom-shipping-list'] = $woocommerce_shipping_methods;
	return $fragments;
}
add_filter( 'woocommerce_update_order_review_fragments', 'my_custom_shipping_list_update');

/**
* Update count product
*/
function update_count_checkout() {
	$cart  = WC()->instance()->cart;
	$id    = sanitize_text_field( $_POST['product_id'] );
	$count = sanitize_text_field( $_POST['count'] );
	$sign  = sanitize_text_field( $_POST['sign'] );
	$max   = sanitize_text_field( $_POST['max'] );

	if ( $sign == 'minus' ) {
		$count -= 1;
	}
	else {
		$count += 1;
		if ( ! empty($max) ) {
			if ( $count > $max ) {
				return false;
			}
		}
	}

	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
		if ( $_product->get_id() == $id ) {
			$cart->set_quantity( $cart_item_key, $count );
			break;
		}
	}

	return false;
}
add_action('wp_ajax_update_count_checkout', 'update_count_checkout');
add_action('wp_ajax_nopriv_update_count_checkout', 'update_count_checkout');

/**
* Update checkout items
*/
function my_custom_product_update( $fragments ) {
	ob_start();
	?>
	<?php
	echo get_template_part( 'template-parts/checkout-items' );
	$woocommerce_checkout_items = ob_get_clean();
	$fragments['.woocommerce_checkout_wrapper_review__contents'] = $woocommerce_checkout_items;
	return $fragments;
}
add_filter( 'woocommerce_update_order_review_fragments', 'my_custom_product_update');

/**
* Update count items checkout page
*/
function count_product_checkout_page( $fragments ) {
	ob_start();
	?>
	<div class="count-in-checkount">
		<span><?php echo WC()->cart->get_cart_contents_count(); ?></span> items in card
	</div>
	<?php
	$woocommerce_count_product_checkout_page = ob_get_clean();
	$fragments['.count-in-checkount'] = $woocommerce_count_product_checkout_page;
	return $fragments;
}
add_filter( 'woocommerce_update_order_review_fragments', 'count_product_checkout_page');

/**
 * Change the order of pages in the account menu
 *
 * @param array $items Menu items.
 * @param array $endpoints Menu items endpoints.
 * @return array $items Menu items.
 */
function bda_woocommerce_account_menu_items( $items = array(), $endpoints = array() ) {

	// Delete endpoints.
	if ( isset( $items['pre-orders'] ) ) {
		unset( $items['pre-orders'] );
	}

	// Rename endpoints.
	if ( isset( $items['orders'] ) ) {
		$items['orders'] = 'Order history';
	}

	if ( isset( $items['edit-address'] ) ) {
		$items['edit-address'] = 'My addresses';
	}

	if ( isset( $items['tinv_wishlist'] ) ) {
		$items['tinv_wishlist'] = 'View my wishlist';
	}

	// Add new endpoints.
	$items['show-cart']        = 'View my basket';
	$items['account-feedback'] = 'Contact us';

	// Reorder endpoints.
	$endpoints_order = array(
		'dashboard',
		'edit-account',
		'points',
		'orders',
		'edit-address',
		'payment-methods',
		'show-cart',
		'tinv_wishlist',
		'account-feedback',
	);
	$endpoints_order = array_reverse( $endpoints_order );

	foreach ( $endpoints_order as $endpoint ) {
		if ( isset( $items[ $endpoint ] ) ) {
			$endpoint_name = $items[ $endpoint ];
			unset( $items[ $endpoint ] );
			$items = array( $endpoint => $endpoint_name ) + $items;
		}
	}

	return $items;
}

add_filter( 'woocommerce_account_menu_items', 'bda_woocommerce_account_menu_items', 10, 2 );


/**
 * Update account endpoint urls.
 *
 * @param string $url .
 * @param string $endpoint .
 * @param string $value .
 * @param string $permalink .
 * @return string
 */
function bda_woocommerce_get_endpoint_url( $url, $endpoint, $value, $permalink ) {

	if ( 'show-cart' === $endpoint ) {
		$url = wc_get_cart_url();
	}

	return $url;
}

add_filter( 'woocommerce_get_endpoint_url', 'bda_woocommerce_get_endpoint_url', 4, 10 );


/**
 * Add new rewrites for new WC account pages.
 *
 * @return void
 */
function bda_add_new_account_endpoints() {
	add_rewrite_endpoint( 'account-feedback', EP_ROOT | EP_PAGES );
}

add_action( 'init', 'bda_add_new_account_endpoints' );


/**
 * Set template for account-feedback page.
 *
 * @return void
 */
function bda_account_feedback_endpoint_template() {
	wc_get_template( 'myaccount/account-feedback.php' );
}

add_action( 'woocommerce_account_account-feedback_endpoint', 'bda_account_feedback_endpoint_template' );


/**
 * Сhange the display location of notices in the account
 */
remove_action( 'woocommerce_account_content', 'woocommerce_output_all_notices', 5 );
add_action( 'mg_woocommerce_account_notices', 'woocommerce_output_all_notices' );


/**
 * Remove password strength check.
 */
function bda_remove_password_strength() {
	wp_dequeue_script( 'wc-password-strength-meter' );
}

add_action( 'wp_print_scripts', 'bda_remove_password_strength', 10 );


/**
 * Remove password strength check.
 */
function change_existing_currency_symbol( $currency_symbol, $currency ) {
	if ('&#36;' === $currency_symbol) { // if symbol is '$', concat it with currency code
		$currency_symbol = $currency_symbol;
	}

	return wp_kses_post( html_entity_decode( $currency_symbol ) );
}

add_filter( 'woocommerce_currency_symbol', 'change_existing_currency_symbol', 15, 2 );


/**
 * Replace Stripe (Apple Pay, Google Pay) express payment in cart
 */
if ( class_exists( 'WC_Stripe' ) ) {
	remove_action( 'woocommerce_proceed_to_checkout', array( WC_Stripe::get_instance()->payment_request_configuration, 'display_payment_request_button_html' ), 1 );
	remove_action( 'woocommerce_proceed_to_checkout', array( WC_Stripe::get_instance()->payment_request_configuration, 'display_payment_request_button_separator_html' ), 2 );
	add_action( 'woocommerce_proceed_to_checkout', array( WC_Stripe::get_instance()->payment_request_configuration, 'display_payment_request_button_html' ), 20 );
}


/**
 * Replace Stripe (Apple Pay, Google Pay) express payment in checkout
 */
if ( class_exists( 'WC_Stripe' ) ) {
	remove_action( 'woocommerce_checkout_before_customer_details', array( WC_Stripe::get_instance()->payment_request_configuration, 'display_payment_request_button_html' ), 1 );
	remove_action( 'woocommerce_checkout_before_customer_details', array( WC_Stripe::get_instance()->payment_request_configuration, 'display_payment_request_button_separator_html' ), 2 );
	add_action( 'metageeks_express_payments_in_checkout', array( WC_Stripe::get_instance()->payment_request_configuration, 'display_payment_request_button_html' ), 1 );
}


/**
 * Replace PayPal express payment in checkout
 */
if ( function_exists( 'eh_paypal_express_run' ) ) {
	remove_action( 'woocommerce_before_checkout_form', array( eh_paypal_express_run()->hook_include, 'eh_express_checkout_hook' ) );
	add_action( 'metageeks_express_payments_in_checkout', array( eh_paypal_express_run()->hook_include, 'eh_express_checkout_hook' ) );
}


/**
 * Replace Stripe (Apple Pay, Google Pay) express payment in single product
 */
if ( class_exists( 'WC_Stripe' ) ) {
	remove_action( 'woocommerce_after_add_to_cart_quantity', array( WC_Stripe::get_instance()->payment_request_configuration, 'display_payment_request_button_html' ), 1 );
	remove_action( 'woocommerce_after_add_to_cart_quantity', array( WC_Stripe::get_instance()->payment_request_configuration, 'display_payment_request_button_separator_html' ), 2 );
	add_action( 'metageeks_express_payments_in_product', array( WC_Stripe::get_instance()->payment_request_configuration, 'display_payment_request_button_html' ), 1 );
}


/**
 * Add payments icons to the Stripe method
 *
 * @param string $icon Icon html tag.
 * @param string $gateway_id Payment ID/Name.
 * @return string
 */
function metageeks_stripe_woocommerce_gateway_icon( $icon = '', $gateway_id = '' ) {

	if ( 'stripe' === $gateway_id && ! $icon ) {
		$icon = '<img src="' . get_template_directory_uri() . '/build/images/credit-cards.svg" alt="credit-cards-logo">';
	}

	return $icon;
}

add_filter( 'woocommerce_gateway_icon', 'metageeks_stripe_woocommerce_gateway_icon', 10, 2 );


/**
 * Replace Points and Rewards plugin hooks
 */
if ( class_exists( 'Points_Rewards_For_WooCommerce_Public' ) ) {
	$points = new Points_Rewards_For_WooCommerce_Public( 'points-and-rewards-for-woocommerce', '1.2.2' );

	// Sign Up notice.
	remove_class_hook( 'woocommerce_before_customer_login_form', 'Points_Rewards_For_WooCommerce_Public', 'mwb_wpr_woocommerce_signup_point' );

	// Cart notices.
	remove_class_hook( 'woocommerce_before_cart_contents', 'Points_Rewards_For_WooCommerce_Public', 'mwb_wpr_woocommerce_before_cart_contents' );
	// add_action( 'woocommerce_before_cart', array( $points, 'mwb_wpr_woocommerce_before_cart_contents' ) );

	// My Account template.
	remove_class_hook( 'woocommerce_account_points_endpoint', 'Points_Rewards_For_WooCommerce_Public', 'mwb_wpr_account_points' );
	add_action( 'woocommerce_account_points_endpoint', 'metageeks_woocommerce_account_points_endpoint' );
}


/**
 * Points and Rewards plugin account template.
 *
 * @return void
 */
function metageeks_woocommerce_account_points_endpoint() {

	if ( apply_filters( 'mwb_wpr_allowed_user_roles_points_features', false ) ) {
		return;
	}

	$user_ID = get_current_user_ID();
	$user    = new WP_User( $user_ID );

	wc_get_template( 'myaccount/points.php' );
}


/**
 * Delete empty woocommerce error element from Points plugin (START)
 *
 * @return void
 */
function metageeks_delete_error_from_points_start() {
	ob_start();
}

// add_action( 'woocommerce_before_cart', 'metageeks_delete_error_from_points_start', -1 );


/**
 * Delete empty woocommerce error element from Points plugin (END)
 *
 * @return void
 */
function metageeks_delete_error_from_points_end() {
	$output = ob_get_clean();
	$output = str_replace( '<div class="woocommerce-error mwb_rwpr_settings_display_none_notice" id="mwb_wpr_cart_points_notice" ></div>', '', $output );
	echo $output; // phpcs:ignore
}

// add_action( 'woocommerce_before_cart', 'metageeks_delete_error_from_points_end', 99 );


/**
 * Replace position of shipping estimate widget
 */
if ( class_exists( 'pisol_ppscw_product_page_calculator' ) ) {
	remove_action( 'woocommerce_before_add_to_cart_form', array( 'pisol_ppscw_product_page_calculator', 'calculator' ) );
	remove_action( 'woocommerce_after_add_to_cart_form', array( 'pisol_ppscw_product_page_calculator', 'calculator' ) );
	add_action( 'metageeks_estimate_shipping', array( 'pisol_ppscw_product_page_calculator', 'calculator' ) );
}
