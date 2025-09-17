<?php
/**
 * Template part for displaying a custom navigation menu.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package MetaGeeks
 */

?>

<?php
$args = [
	'taxonomy'      => 'product_cat',
	'parent'        => '0',
	'meta_key'      => 'include_in_navigation_menu',
	'meta_value'    => true,
	'orderby'       => 'id',
	'order'         => 'ASC',
	'hide_empty'    => false,
];

$terms = get_terms( $args );
?>

<nav id="site-navigation" class="main-navigation navigation-menu" aria-label="Main Navigation">
	<ul class="custom-navigation-menu menu dropdown container" id="primary-menu">

	<?php foreach( $terms as $key => $term ) :

		$new_args = [
			'taxonomy' => 'product_cat',
			'parent' => $term->term_taxonomy_id,
			'hide_empty' => false,
		];
		$inner_terms = get_terms( $new_args );
		$has_children = count( $inner_terms ) ? 'menu-item-has-children' : '';
		$extra_sub_menu = get_field( 'extra_view', 'product_cat_'.$term->term_taxonomy_id );
		$four_columns_menu = get_field( 'four_columns_menu', 'product_cat_'.$term->term_taxonomy_id );
		$adds_links = get_field( 'additionally_links', 'product_cat_'.$term->term_taxonomy_id );
		$image_link = get_field( 'image_link', 'product_cat_'.$term->term_taxonomy_id );
		?>

		<li class="menu-item <?php echo $has_children ?>">
			<a href="/product-category/<?php echo $term->slug; ?>">
				<img class="menu-item-icon" src="<?php echo get_field( 'icon', 'product_cat_'.$term->term_taxonomy_id )['url']; ?>">
				<?php echo $term->name; ?>
			</a>

			<?php
			if ( count( $inner_terms ) ) { ?>

				<div class="sub-menu-wrapper <?php echo $extra_sub_menu ? 'extended-view' : ''; ?> <?php echo $four_columns_menu ? 'four-columns-menu' : ''; ?>">
					<span class="pointer"></span>
					<div class="row">

						<?php if ( $four_columns_menu ) :
							$four_columns_fields = get_field( 'menu_columns', 'product_cat_'.$term->term_taxonomy_id );

							foreach ( $four_columns_fields as $column ) {
								$column_heading = $column['column_heading'];
								$column_links = $column['column_menu_links']; ?>

								<div class="sub-menu__wrapper">
									<a class="sub-menu__heading" href="<?php echo $column_heading['url']; ?>">
										<?php echo $column_heading['title']; ?>
									</a>

									<ul class="sub-menu">

									<?php foreach ( $column_links as $column_link ) : ?>

										<li class="menu-item">
											<a href="/product-category/<?php echo $term->slug; ?>/<?php echo $column_link->slug; ?>">
												<?php echo $column_link->name; ?>
											</a>
										</li>

									<?php endforeach; ?>

									</ul>
								</div>

								<?php
								// print_r( $column_links );
							}
						?>
						<?php else : ?>

							<ul class="sub-menu">

								<?php foreach ( $inner_terms as $inner_term ) : ?>

									<li class="menu-item">
										<a href="/product-category/<?php echo $term->slug; ?>/<?php echo $inner_term->slug; ?>">
											<?php echo $inner_term->name; ?>
										</a>
									</li>

								<?php endforeach; ?>

							</ul>

						<?php endif; ?>

						<?php if ( $image_link && $image_link['img'] && $image_link['link'] ) : ?>
						<div class="img-wrapper">
							<a href="<?php echo $image_link['link']['url']; ?>">
								<img src="<?php echo $image_link['img']['url']; ?>" width="265" height="281" alt="<?php echo $image_link['img']['alt']; ?>">
							</a>
						</div>
						<?php endif; ?>
					</div>
					<?php if ( $adds_links && count( $adds_links ) ) :  ?>
						<ul class="additionally-links">
							<?php foreach ( $adds_links as $link ) : ?>
								<li class="item">
									<a href="<?php echo $link['link']['url']; ?>"><?php echo $link['link']['title']; ?></a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>

				<?php
			}
			?>
		</li>

	<?php endforeach; ?>

	</ul>
</nav>
