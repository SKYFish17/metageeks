<?php
/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="container">

	<div class="metageeks-account">

		<?php
			/**
			 * My Account notices.
			 */
			do_action( 'mg_woocommerce_account_notices' );
		?>

		<div class="metageeks-account__breadcrumbs">
			<?php
			if ( function_exists( 'woocommerce_breadcrumb' ) ) {
				woocommerce_breadcrumb();
			}
			?>
		</div>

		<div class="metageeks-account__wrapper">

			<div class="metageeks-account-navigation">
				<?php
					/**
					 * My Account navigation.
					 *
					 * @since 2.6.0
					 */
					do_action( 'woocommerce_account_navigation' );
				?>
			</div>

			<div class="metageeks-account__content">
				<?php
					/**
					 * My Account content.
					 *
					 * @since 2.6.0
					 */
					do_action( 'woocommerce_account_content' );
				?>
			</div>

		</div>

	</div>

</div>
