<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package MetaGeeks
 */

$payment_method_icons = get_field( 'payment_method_icons', 'options' );
$company_info = get_field( 'company_info', 'options' );
$mascot_image = get_field( 'mascot_image', 'options' );
?>
	<?php if ( ! is_checkout() ): ?>


	<footer class="site-footer">

		<div class="top">
			<div class="container">
				<div class="site-footer__wrapper">
					<div class="subscribe">
						<span class="title">Subscribe to our newsletter</span>
						<p class="desc">Wanting to know more about our next meta discounts and giveaways? Sign up to stay in the loop with what we have going on!</p>

						<?php echo do_shortcode( '[gravityform id=1 title=false description=false ajax=true]' ); ?>
					</div>

					<nav id="site-footer-navigation" class="footer-navigation" aria-label="<?php esc_attr_e( 'Footer Navigation', 'metageeks' ); ?>">
						<?php
						wp_nav_menu(
							[
								'fallback_cb'    => false,
								'theme_location' => 'footer',
								'menu_id'        => 'footer-menu',
								'menu_class'     => 'menu container',
								'container'      => false,
								'depth'          => 2,
							]
						);
						?>
					</nav><!-- #site-navigation-->

					<li class="menu-item site-footer__mascot-wrapper">
						<a href="<?php echo home_url() . '/'; ?>">
							<img class="site-footer__mascot-img" src="<?php echo $mascot_image['url']; ?>" width="196" height="283" alt="Mascot-wolf">
						</a>
					</li>

					<div class="contact-us">
						<?php $link = get_field( 'contact_link', 'options' );
						if( $link ):
							$link_url = $link['url'];
							$link_title = $link['title'];
							$link_target = $link['target'] ? $link['target'] : '_self';
						endif;
						?>
						<p class="desc">If you need to contact us, we can be found on our social media or via our <a href="<?php echo $link_url; ?>" target="<?php echo $link_target; ?>">contact page</a>.</p>
						<?php bda_display_social_network_links(); ?>
					</div>
				</div>
			</div>
		</div>

		<div class="bottom">
			<div class="container">
				<div class="payment-methods">
					<!-- <span class="title">We Accept</span> -->

					<?php if ( $payment_method_icons ) : ?>
						<ul class="list">
							<?php foreach ( $payment_method_icons as $method ): ?>
								<li class="item">
									<img class="icon" src="<?php echo esc_url( wp_get_attachment_image_url( $method, array( 76, 48 ) ) ); ?>" width="38" height="24">
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

				</div>

				<p class="company-info"><?php echo $company_info; ?></p>
			</div>
		</div>

	</footer><!-- .site-footer container-->

	<div class="modal-background"></div>

	<div class="modal-cart">
		<?php echo get_template_part( 'template-parts/modal-cart-inner', null ); ?>
	</div>

	<div class="modal-product-remove">
		<h3 class="title">Remove item</h3>
		<p class="desc">Are you sure you would like to remove this item from the shopping basket?</p>
		<?php
		bda_display_svg(
			[
				'icon'   => 'modal-basket-delete',
				'width'  => '40',
				'height' => '40',
			]
		);
		?>
		<div class="buttons">
			<button class="btn btn-gray btn-cancel">Cancel</button>
			<button class="btn btn-red btn-remove">Remove</button>
		</div>
	</div>

	<div class="modal-product-filters">
		<div class="modal-inner">
			<button class="close">
				<?php
				bda_display_svg(
					[
						'icon'   => 'modal-basket-close',
						'width'  => '14',
						'height' => '14',
					]
				);
				?>
			</button>

			<div class="filters">
			<h2 class="filters__title">Filters</h2>
				<?php echo do_shortcode( '[searchandfilter slug="filters"]' ); ?>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<?php echo get_template_part( 'template-parts/modal-search', null ); ?>
	<?php bda_display_mobile_menu(); ?>
	<?php wp_footer(); ?>

</body>

</html>
