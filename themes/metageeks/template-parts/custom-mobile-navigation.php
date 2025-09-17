<?php
/**
 * Template part for displaying a custom mobile-navigation menu.
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

<div class="off-canvas-content">
	<ul id="site-mobile-menu" class="mobile-menu">

	<?php foreach( $terms as $term ) :

		$new_args = [
			'taxonomy' => 'product_cat',
			'parent' => $term->term_taxonomy_id,
			'hide_empty' => false,
		];
		$inner_terms = get_terms( $new_args );
		$has_children = count( $inner_terms ) ? 'menu-item-has-children' : '';
		$four_columns_menu = get_field( 'four_columns_menu', 'product_cat_'.$term->term_taxonomy_id );
		?>

		<li class="menu-item <?php echo $has_children ?>">
			<a href="/product-category/<?php echo $term->slug; ?>">
				<img class="menu-item-icon" src="<?php echo get_field( 'icon_mobile', 'product_cat_'.$term->term_taxonomy_id )['url']; ?>">
				<?php echo $term->name; ?>
			</a>

			<?php
			if ( count( $inner_terms ) ) { ?>

				<ul class="sub-menu">

					<?php if ( $four_columns_menu ) :
						$four_columns_fields = get_field( 'menu_columns', 'product_cat_'.$term->term_taxonomy_id );

						foreach ( $four_columns_fields as $column ) {
							$column_links = $column['column_menu_links']; ?>

								<?php foreach ( $column_links as $column_link ) : ?>

									<li class="menu-item">
										<a href="/product-category/<?php echo $term->slug; ?>/<?php echo $column_link->slug; ?>">
											<?php echo $column_link->name; ?>
										</a>
									</li>

								<?php endforeach; ?>

							<?php
						}
					?>
					<?php else : ?>
						<?php foreach ( $inner_terms as $inner_term ) : ?>

							<li class="menu-item">
								<a href="/product-category/<?php echo $term->slug; ?>/<?php echo $inner_term->slug; ?>">
									<?php echo $inner_term->name; ?>
								</a>
							</li>

						<?php endforeach; ?>
					<?php endif; ?>

				</ul>

				<?php
			}
			?>
		</li>

	<?php endforeach; ?>
	</ul>

	<?php echo do_shortcode( '[yaycurrency-switcher]' ); ?>
</div>
