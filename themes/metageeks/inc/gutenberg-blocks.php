<?php
/**
 * Gutenberg custom blocks
 */

add_action( 'acf/init', 'bda_gutenberg_blocks' );
function bda_gutenberg_blocks() {

	// Check function exists.
	if ( function_exists( 'acf_register_block_type' ) ) {

		// Register Images in the layout Block.
		acf_register_block_type(
			array(
				'name'            => 'images-in-layout-block',
				'title'           => 'Images in the layout Block',
				'description'     => 'Images in the layout Block.',
				'render_template' => 'template-parts/blocks/images-in-layout-block.php',
				'category'        => 'design',
				'icon'            => 'block-default',
				'keywords'        => array( 'image layout block' ),
				'supports'        => array( 'anchor' => true ),
				'mode'            => true,
			// 'enqueue_assets'  => function () {
			// wp_enqueue_script( 'ns-scripts', get_stylesheet_directory_uri() . '/build/index.js', array(), '1.1', true );
			// },
			)
		);

		// Register Images with links Block.
		acf_register_block_type(
			array(
				'name'            => 'images-with-links-block',
				'title'           => 'Images with links Block',
				'description'     => 'Images with links Block.',
				'render_template' => 'template-parts/blocks/images-with-links-block.php',
				'category'        => 'design',
				'icon'            => 'block-default',
				'keywords'        => array( 'images-with-links block' ),
				'supports'        => array( 'anchor' => true ),
				'mode'            => true,
			// 'enqueue_assets'  => function () {
			// wp_enqueue_script( 'ns-scripts', get_stylesheet_directory_uri() . '/build/index.js', array(), '1.1', true );
			// },
			)
		);

		// Register Deal of the week Block.
		acf_register_block_type(
			array(
				'name'            => 'deal-of-the-week-block',
				'title'           => 'Deal of the week Block',
				'description'     => 'Deal of the week Block.',
				'render_template' => 'template-parts/blocks/deal-of-the-week-block.php',
				'category'        => 'design',
				'icon'            => 'block-default',
				'keywords'        => array( 'deal-of-the-week block' ),
				'supports'        => array( 'anchor' => true ),
				'mode'            => true,
				'enqueue_assets'  => function () {
					wp_enqueue_style( 'slick', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css', array() );
					wp_enqueue_script( 'metageeks-scripts', get_stylesheet_directory_uri() . '/build/index.js', array(), '1.0', true );
				},
			)
		);

		// Register Advantages Block.
		acf_register_block_type(
			array(
				'name'            => 'advantages-block',
				'title'           => 'Advantages Block',
				'description'     => 'Advantages Block.',
				'render_template' => 'template-parts/blocks/advantages-block.php',
				'category'        => 'design',
				'icon'            => 'block-default',
				'keywords'        => array( 'advantages block' ),
				'supports'        => array( 'anchor' => true ),
				'mode'            => true,
			)
		);

		// Register Blog posts Block.
		acf_register_block_type(
			array(
				'name'            => 'blog-posts-block',
				'title'           => 'Blog posts Block',
				'description'     => 'Blog posts Block.',
				'render_template' => 'template-parts/blocks/blog-posts-block.php',
				'category'        => 'design',
				'icon'            => 'block-default',
				'keywords'        => array( 'blog-posts block' ),
				'supports'        => array( 'anchor' => true ),
				'mode'            => true,
				'enqueue_assets'  => function () {
					wp_enqueue_style( 'slick', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css', array() );
					wp_enqueue_script( 'metageeks-scripts', get_stylesheet_directory_uri() . '/build/index.js', array(), '1.0', true );
				},
			)
		);

		// Register Our partners Block.
		acf_register_block_type(
			array(
				'name'            => 'our-partners-block',
				'title'           => 'Our partners Block',
				'description'     => 'Our partners Block.',
				'render_template' => 'template-parts/blocks/our-partners-block.php',
				'category'        => 'design',
				'icon'            => 'block-default',
				'keywords'        => array( 'our-partners block' ),
				'supports'        => array( 'anchor' => true ),
				'mode'            => true,
				'enqueue_assets'  => function () {
					wp_enqueue_style( 'slick', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css', array() );
					wp_enqueue_script( 'metageeks-scripts', get_stylesheet_directory_uri() . '/build/index.js', array(), '1.0', true );
				},
			)
		);

		// Newsletter Widget Block.
		acf_register_block_type(
			array(
				'name'            => 'newsletter-widget-block',
				'title'           => 'Newsletter Widget',
				'description'     => 'Newsletter Widget.',
				'render_template' => 'template-parts/blocks/newsletter-widget-block.php',
				'category'        => 'design',
				'icon'            => 'email',
				'keywords'        => array( 'newsletter' ),
				'supports'        => array( 'anchor' => false ),
				'mode'            => true,
			)
		);

		// Links List Block.
		acf_register_block_type(
			array(
				'name'            => 'links-list-block',
				'title'           => 'Links List Block',
				'description'     => 'Display a Links List Block.',
				'render_template' => 'template-parts/blocks/links-list-block.php',
				'category'        => 'design',
				'icon'            => 'block-default',
				'keywords'        => array( 'links' ),
				'supports'        => array( 'anchor' => false ),
				'mode'            => true,
			)
		);

		// Big Cards Block.
		acf_register_block_type(
			array(
				'name'            => 'big-cards-block',
				'title'           => 'Big Cards Block',
				'description'     => 'Display a Big Cards Block.',
				'render_template' => 'template-parts/blocks/big-cards-block.php',
				'category'        => 'design',
				'icon'            => 'block-default',
				'keywords'        => array( 'cards' ),
				'supports'        => array( 'anchor' => false ),
				'mode'            => true,
			)
		);

		// Featured Products Block.
		acf_register_block_type(
			array(
				'name'            => 'featured-products-block',
				'title'           => 'Featured Products Block',
				'description'     => 'Display a Featured Products Block.',
				'render_template' => 'template-parts/blocks/featured-products-block.php',
				'category'        => 'design',
				'icon'            => 'block-default',
				'keywords'        => array( 'product' ),
				'supports'        => array( 'anchor' => false ),
				'mode'            => true,
			)
		);

		// Cards with Button Block.
		acf_register_block_type(
			array(
				'name'            => 'cards-with-button-block',
				'title'           => 'Cards with Button Block',
				'description'     => 'Display a Cards with Button Block.',
				'render_template' => 'template-parts/blocks/cards-with-button-block.php',
				'category'        => 'design',
				'icon'            => 'block-default',
				'keywords'        => array( 'cards' ),
				'supports'        => array( 'anchor' => false ),
				'mode'            => true,
			)
		);

		// Product Categories Block.
		acf_register_block_type(
			array(
				'name'            => 'product-categories-block',
				'title'           => 'Product Categories Block',
				'description'     => 'Display a Product Categories Block.',
				'render_template' => 'template-parts/blocks/product-categories-block.php',
				'category'        => 'design',
				'icon'            => 'block-default',
				'keywords'        => array( 'cards' ),
				'supports'        => array( 'anchor' => false ),
				'mode'            => true,
			)
		);

		// Newsletter Form Block.
		acf_register_block_type(
			array(
				'name'            => 'newsletter-form-block',
				'title'           => 'Newsletter Form Block',
				'description'     => 'Display a Newsletter Form Block.',
				'render_template' => 'template-parts/blocks/newsletter-form-block.php',
				'category'        => 'design',
				'icon'            => 'email',
				'keywords'        => array( 'newsletter' ),
				'supports'        => array( 'anchor' => false ),
				'mode'            => true,
			)
		);

		// FAQ Block.
		acf_register_block_type(
			array(
				'name'            => 'faq-block',
				'title'           => 'FAQ Block',
				'description'     => 'Display a FAQ Block.',
				'render_template' => 'template-parts/blocks/faq-block.php',
				'category'        => 'design',
				'icon'            => 'block-default',
				'keywords'        => array( 'faq' ),
				'supports'        => array( 'anchor' => false ),
				'mode'            => true,
			)
		);

		// Get in Touch Block.
		acf_register_block_type(
			array(
				'name'            => 'get-in-touch-block',
				'title'           => 'Get in Touch Block',
				'description'     => 'Display a Get in Touch Block.',
				'render_template' => 'template-parts/blocks/get-in-touch-block.php',
				'category'        => 'design',
				'icon'            => 'block-default',
				'keywords'        => array( 'contact' ),
				'supports'        => array( 'anchor' => false ),
				'mode'            => true,
			)
		);

		// Image with Text Block.
		acf_register_block_type(
			array(
				'name'            => 'image-with-text-block',
				'title'           => 'Image with Text Block',
				'description'     => 'Display a Image with Text Block.',
				'render_template' => 'template-parts/blocks/image-with-text-block.php',
				'category'        => 'design',
				'icon'            => 'block-default',
				'keywords'        => array( 'image', 'text' ),
				'supports'        => array( 'anchor' => false ),
				'mode'            => true,
			)
		);

		// Feedback Block.
		acf_register_block_type(
			array(
				'name'            => 'feedback-block',
				'title'           => 'Feedback Block',
				'description'     => 'Display a Feedback Block.',
				'render_template' => 'template-parts/blocks/feedback-block.php',
				'category'        => 'design',
				'icon'            => 'block-default',
				'keywords'        => array( 'image', 'text' ),
				'supports'        => array( 'anchor' => false ),
				'mode'            => true,
			)
		);

		// Vacancies Block.
		acf_register_block_type(
			array(
				'name'            => 'vacancies-block',
				'title'           => 'Vacancies Block',
				'description'     => 'Display a Vacancies Block.',
				'render_template' => 'template-parts/blocks/vacancies-block.php',
				'category'        => 'design',
				'icon'            => 'block-default',
				'keywords'        => array( 'vacancies', 'job' ),
				'supports'        => array( 'anchor' => false ),
				'mode'            => true,
			)
		);

		// Delivery Info Block.
		acf_register_block_type(
			array(
				'name'            => 'delivery-info-block',
				'title'           => 'Delivery Info Block',
				'description'     => 'Display a Delivery Info Block.',
				'render_template' => 'template-parts/blocks/delivery-info-block.php',
				'category'        => 'design',
				'icon'            => 'block-default',
				'keywords'        => array( 'delivery' ),
				'supports'        => array( 'anchor' => false ),
				'mode'            => true,
			)
		);

		// Register Product Slider block.
		acf_register_block_type(
			array(
				'name'            => 'product-slider',
				'title'           => 'Product Slider',
				'description'     => 'Product Slider Block.',
				'render_template' => 'template-parts/blocks/product-slider-block.php',
				'category'        => 'design',
				'icon'            => 'block-default',
				'keywords'        => array( 'product', 'slider' ),
				'supports'        => array( 'anchor' => true ),
				'mode'            => true,
				'enqueue_assets'  => function () {
					wp_enqueue_style( 'slick', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css', array(), '1.8.1' );
					wp_enqueue_script( 'metageeks-scripts', get_stylesheet_directory_uri() . '/build/index.js', array(), '1.0', true );
				},
			)
		);

		// Register Brand Products block.
		acf_register_block_type(
			array(
				'name'            => 'brand-products',
				'title'           => 'Brand Products',
				'description'     => 'Brand Products Block.',
				'render_template' => 'template-parts/blocks/brand-products-block.php',
				'category'        => 'design',
				'icon'            => 'block-default',
				'keywords'        => array( 'brand', 'products', 'slider' ),
				'supports'        => array( 'anchor' => true ),
				'mode'            => true,
				'enqueue_assets'  => function () {
					wp_enqueue_style( 'slick', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css', array(), '1.8.1' );
					wp_enqueue_script( 'metageeks-scripts', get_stylesheet_directory_uri() . '/build/index.js', array(), '1.0', true );
				},
			)
		);

	}

}
