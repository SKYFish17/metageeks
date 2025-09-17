<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );

$account_menu_items = wc_get_account_menu_items();

$endpoint_name = 'dashboard';
if ( WC()->query->get_current_endpoint() ) {
	$endpoint_name = WC()->query->get_current_endpoint();

	if ( 'add-payment-method' === $endpoint_name ) {
		$endpoint_name = 'payment-methods';
	}
} else {
	global $wp_query;

	if ( isset( $wp_query->query_vars['account-feedback'] ) ) {
		$endpoint_name = 'account-feedback';
	} elseif ( isset( $wp_query->query_vars['view-log'] ) ) {
		$endpoint_name = 'points';
	}
}

?>

<div class="metageeks-account-navigation__header">My Account</div>

<button class="metageeks-account-navigation__toggler" type="button">
	<span><?php echo esc_html( $account_menu_items[ $endpoint_name ] ); ?></span>
	<?php
	bda_display_svg(
		array(
			'icon'   => 'arrow-down',
			'width'  => '20',
			'height' => '20',
		)
	);
	?>
</button>

<nav class="metageeks-account-navigation__menu">
	<ul>
		<?php foreach ( $account_menu_items as $endpoint => $label ) : ?>
			<li class="<?php echo esc_attr( wc_get_account_menu_item_classes( $endpoint ) ); ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>">
					<?php
					bda_display_svg(
						array(
							'icon'   => $endpoint,
							'width'  => '20',
							'height' => '20',
						)
					);
					?>
					<span><?php echo esc_html( $label ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
