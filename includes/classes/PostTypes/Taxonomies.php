<?php

class BoilerplateTaxonomies {
	
	public function __construct() {
		add_action( 'init', [ $this, 'load_taxonomies' ] );
	}
	
	public function register_taxonomy( $taxonomy, $single, $plural, $post_types = [], $options = [] ) {
		$labels = [
			'name'                       => $plural,
			'single_name'                => $single,
			'menu_name'                  => $plural,
			'all_items'                  => sprintf( __( 'All %s', 'wordpress-plugin-template' ), $plural ),
			'edit_item'                  => sprintf( __( 'Edit %s', 'wordpress-plugin-template' ), $single ),
			'view_item'                  => sprintf( __( 'View %s', 'wordpress-plugin-template' ), $single ),
			'update_item'                => sprintf( __( 'Update %s', 'wordpress-plugin-template' ), $single ),
			'add_new_item'               => sprintf( __( 'Add New %s', 'wordpress-plugin-template' ), $single ),
			'new_item_name'              => sprintf( __( 'New %s Name', 'wordpress-plugin-template' ), $single ),
			'parent_item'                => sprintf( __( 'Parent %s', 'wordpress-plugin-template' ), $single ),
			'parent_item_colon'          => sprintf( __( 'Parent %s:', 'wordpress-plugin-template' ), $single ),
			'search_items'               => sprintf( __( 'Search %s', 'wordpress-plugin-template' ), $plural ),
			'popular_items'              => sprintf( __( 'Popular %s', 'wordpress-plugin-template' ), $plural ),
			'separate_items_with_commas' => sprintf( __( 'Separate %s with commas', 'wordpress-plugin-template' ), strtolower( $plural ) ),
			'add_or_remove_items'        => sprintf( __( 'Add or remove %s', 'wordpress-plugin-template' ), strtolower( $plural ) ),
			'choose_from_most_used'      => sprintf( __( 'Choose from the most used %s', 'wordpress-plugin-template' ), strtolower( $plural ) ),
			'not_found'                  => sprintf( __( 'No %s found', 'wordpress-plugin-template' ), strtolower( $plural ) ),
		];
		//phpcs:enable
		$args = [
			'label'                 => $plural,
			'labels'                => $labels,
			'hierarchical'          => true,
			'public'                => true,
			'show_ui'               => true,
			'show_in_nav_menus'     => true,
			'show_tagcloud'         => true,
			'meta_box_cb'           => null,
			'show_admin_column'     => true,
			'show_in_quick_edit'    => true,
			'update_count_callback' => '',
			'show_in_rest'          => true,
			'rest_base'             => $taxonomy,
			'rest_controller_class' => 'WP_REST_Terms_Controller',
			'query_var'             => $taxonomy,
			'rewrite'               => true,
		];

		$args = array_merge( $args, $options );

		register_taxonomy( $taxonomy, $post_types, $args );
	}
	
	public function load_taxonomies() {
		// This is where you register taxonomies
		$this->register_taxonomy( 'ticket_cat', 'Ticket Category', 'Ticket Categories', [ 'ticket' ] );
	}
	
}
