<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package MetaGeeks
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Play&family=Roboto:wght@400;500;700&family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet">

	<?php wp_head(); ?>

</head>

<body <?php body_class( 'site-wrapper' ); ?>>

	<?php wp_body_open(); ?>

	<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'metageeks' ); ?></a>

	<?php if ( ! is_checkout() ): ?>

	<header class="site-header">

		<div class="top">
			<div class="container">

				<?php bda_display_social_network_links(); ?>

				<nav id="header-top-navigation" class="header-top-navigation" aria-label="<?php esc_attr_e( 'Header-top Navigation', 'metageeks' ); ?>">
					<?php
					wp_nav_menu(
						[
							'fallback_cb'    => false,
							'theme_location' => 'top',
							'menu_id'        => 'header-top-menu',
							'menu_class'     => 'header-top-menu',
							'container'      => false,
						]
					);
					?>
				</nav><!-- #header-top-navigation-->

				<div class="trustpilot">
					<?php bda_feedback_widget();?>
				</div>
			</div><!-- .container -->
		</div>

		<div class="user-navigation">
			<div class="container">
				<?php if ( has_nav_menu( 'primary' ) || has_nav_menu( 'mobile' ) ) : ?>
					<button type="button" class="off-canvas-open" aria-expanded="false" aria-label="<?php esc_attr_e( 'Open Menu', 'metageeks' ); ?>"></button>
				<?php endif; ?>

				<div class="site-branding">

					<?php the_custom_logo(); ?>

				</div><!-- .site-branding -->

				<div class="wrapper-search">
					<?php echo do_shortcode( '[searchandfilter slug="filters"]' ) ?>
					<div class="search-results-desktop"></div>
				</div>


				<button class="search-mobile" type="button" name="search-mobile">
					<span class="visually-hidden">Search</span>
					<?php
					bda_display_svg(
						[
							'icon'   => 'search-loupe',
							'width'  => '20',
							'height' => '20',
						]
					);
					?>
				</button>

				<ul class="shop-buttons">
					<li class="currency">
						<?php echo do_shortcode( '[yaycurrency-switcher]' ); ?>
					</li>
					<li class="user">
						<a href="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ); ?>" name="user-account">
							<span class="visually-hidden">User-account</span>
							<?php
							bda_display_svg(
								[
									'icon'   => 'user',
									'width'  => '24',
									'height' => '22',
								]
							);
							?>
						</a>
					</li>
					<li class="wishlist">
						<?php echo do_shortcode( '[ti_wishlist_products_counter]' ); ?>
					</li>
					<li class="cart">
						<?php echo get_template_part( 'template-parts/header-cart-button', null ) ?>
					</li>
				</ul>
			</div>
		</div>

		<!-- custom site-navigation -->
		<?php echo get_template_part( 'template-parts/custom-site-navigation' ); ?>
		<!-- custom site-navigation -->

	</header><!-- .site-header-->

	<?php else: ?>

	<header class="site-header">
		<div class="user-navigation">
			<div class="container">
				<div class="site-branding">

					<?php the_custom_logo(); ?>

				</div>

				<?php if ( ! is_user_logged_in()  && 'yes' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ): ?>
					<div class="wrapper-login-link">
						<?php wc_print_notice( apply_filters( 'woocommerce_checkout_login_message', esc_html__( 'Already have an account?', 'woocommerce' ) ) . ' <a href="#" class="showlogin-form">' . esc_html__( 'Log in', 'woocommerce' ) . '</a>', 'notice' ); ?>
					</div>
				<?php endif; ?>

			</div>
		</div>
	</header>

	<?php endif; ?>
