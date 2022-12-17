<?php

class BoilerplatePostTypes {
	
	public function __construct() {
		add_action( 'init', [ $this, 'load_post_types' ] );
		add_filter( 'enter_title_here', [ $this, 'change_title_text' ] );
	}
	
	public function register_post_type( $post_type, $single, $plural, $options = [] ) {
		$labels = [
			'name'                  => $plural,
			'menu_name'             => $plural,
			'single_name'         => $single,
			'name_admin_bar'        => $single,
			'add_new'               => _x( 'Add New', $post_type, 'boilerplate' ),
			'add_new_item'          => sprintf( __( 'Add New %s', 'boilerplate' ), $single ),
			'new_item'              => sprintf( __( 'New %s', 'boilerplate' ), $single ),
			'edit_item'             => sprintf( __( 'Edit %s', 'boilerplate' ), $single ),
			'view_item'             => sprintf( __( 'View %s', 'boilerplate' ), $single ),
			'all_items'             => sprintf( __( 'All %s', 'boilerplate' ), $plural ),
			'search_items'          => sprintf( __( 'Search %s', 'boilerplate' ), $plural ),
			'parent_item_colon'     => sprintf( __( 'Parent %s:', 'boilerplate' ), $single ),
			'not_found'             => sprintf( __( 'No %s found.', 'boilerplate' ), strtolower( $plural ) ),
			'not_found_in_trash'    => sprintf( __( 'No %s found in Trash.', 'boilerplate' ), strtolower( $plural ) ),
			'featured_image'        => sprintf( _x( '%s Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'boilerplate' ), $single ),
			'set_featured_image'    => sprintf( _x( 'Set %s image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'boilerplate' ), strtolower( $single ) ),
			'remove_featured_image' => sprintf( _x( 'Remove %s image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'boilerplate' ), strtolower( $single ) ),
			'use_featured_image'    => sprintf( _x( 'Use as %s image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'boilerplate' ), strtolower( $single ) ),
			'archives'              => sprintf( _x( '%s archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'boilerplate' ), $single ),
			'insert_into_item'      => sprintf( _x( 'Insert into %s', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'boilerplate' ), strtolower( $single ) ),
			'uploaded_to_this_item' => sprintf( _x( 'Uploaded to this %s', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'boilerplate' ), strtolower( $single ) ),
			'filter_items_list'     => sprintf( _x( 'Filter %s list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'boilerplate' ), strtolower( $plural ) ),
			'items_list_navigation' => sprintf( _x( '%s list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'boilerplate' ), $plural ),
			'items_list'            => sprintf( _x( '%s list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'boilerplate' ), $plural ),
		];
	 
		$args = [
			'labels'                => $labels,
			'public'                => true,
			'publicly_queryable'    => true,
			'exclude_from_search'   => false,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => true,
			'query_var'             => true,
			'can_export'            => true,
			'rewrite'               => true,
			'capability_type'       => 'page',
			'has_archive'           => false,
			'hierarchical'          => false,
			'show_in_rest'          => true,
			'rest_base'             => $post_type,
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'menu_icon'			    => 'dashicons-admin-post',
			'supports'              => [ 'title', 'editor', 'excerpt', 'comments', 'revisions', 'author', 'thumbnail', 'custom-fields' ],
			'menu_position'         => 5,
		];
		
		$args = array_merge( $args, $options );
	 
		register_post_type( $post_type, $args );
	}
	
	public function load_post_types() {
		// This is where you register post types
		$this->register_post_type( 'ticket', 'Ticket', 'Tickets', [
			'menu_icon' => 'dashicons-tickets-alt',
			'supports'  => [ 'title', 'editor', 'excerpt', 'revisions', 'author', 'thumbnail', 'custom-fields' ],
		] );
	}
	
	public function change_title_text( $title ) {
		$screen = get_current_screen();

		// Here you can can change the title placeholder, for specific post types
		if ( $screen->post_type == 'ticket' ) {
			$title = __( 'Ticket name', 'boilerplate' );
		}

		return $title;
	}
	
}
