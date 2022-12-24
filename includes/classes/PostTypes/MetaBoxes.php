<?php

class BoilerplateMetaBoxes {

    public function __construct() {
		if ( is_admin() ) {
            add_action( 'load-post.php', [ $this, 'init_metaboxes' ] );
            add_action( 'load-post-new.php', [ $this, 'init_metaboxes' ] );
        }
	}

    public function init_metaboxes() {
        add_action( 'add_meta_boxes', [ $this, 'add_metaboxes' ] );
        add_action( 'save_post', [ $this, 'save_metabox' ], 10, 2 );
    }

    public function add_metaboxes() {
        add_meta_box(
            'ticket-meta-box',
            __( 'Ticket Meta Box', 'boilerplate' ),
            [ $this, 'render_metabox' ],
            'ticket', // Post type
            'advanced',
            'default'
        );
 
    }

    public function render_metabox( $post ) {
        wp_nonce_field( 'nonce_action', 'nonce_name' );
		
		// Render content
    }

    public function save_metabox( $post_id, $post ) {
        $nonce_name = htmlspecialchars( filter_input( INPUT_POST, 'nonce_name' ) );
        $nonce_action = 'nonce_action';

        if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        if ( wp_is_post_autosave( $post_id ) ) {
            return;
        }

        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }
		
		// Save stuff
    }
	
}
