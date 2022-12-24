<?php

class BoilerplateBlocks {
	
	public function __construct() {
		add_action( 'acf/init', [ $this, 'register_blocks' ] );
		add_filter( 'block_categories_all', [ $this, 'register_categories' ], 10, 2 );
	}
	
	public function register_blocks() {
		if ( function_exists( 'acf_register_block_type' ) ) {
			acf_register_block_type( [
				'name'            => 'custom-block',
				'title'           => __( 'Custom Block', 'boilerplate' ),
				'description'     => __( 'Presents a Custom block.', 'boilerplate' ),
				'render_callback' => [ $this, 'render_custom_block' ],
				'category'        => 'custom-category',
				'icon'            => 'editor-help', // Dashicons
				'keywords'        => [ 'Some', 'Keywords', 'Here' ],
				'example'         => [
					'attributes' => [
						'mode' => 'preview',
						'data' => [
							'is_preview' => true
						]
					]
				]
			] );
		}
	}
	
	public function register_categories( $categories, $post ) {
		return array_merge(
			$categories,
			[
				[
					'slug' => 'custom-category',
					'title' => __( 'Custom Blocks', 'boilerplate' ),
					'icon'  => 'heart',
				],
			]
		);
	}
	
	public function render_custom_block( $block, $content = '', $is_preview = false, $post_id = 0 ) {
		if ( get_field( 'is_preview' ) ) {
			// Render block preview here. Use Dashicons instead of fontawesome etc.
			
			return;
		}
		
		$id = 'custom-block-' . $block['id'];
		if ( ! empty( $block['anchor'] ) ) {
			$id = $block['anchor'];
		}

		$class = 'custom-block';
		if ( ! empty( $block['className'] ) ) {
			$class .= ' ' . $block['className'];
		}
		if ( ! empty( $block['align'] ) ) {
			$class .= ' align' . $block['align'];
		}

		echo '<div id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '">';
			// Do Frontend + Backend stuff here
			
			if ( is_admin() ) {
				// Do Backend stuff here
			} else {
				// Do Frontend stuff here
			}
		echo '</div>';
	}
	
}
