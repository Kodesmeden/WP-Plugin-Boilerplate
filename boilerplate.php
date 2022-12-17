<?php
/**
 * Plugin Name: Plugin Boilerplate by Kodesmeden
 * Version: 1.0.0
 * Description: Simple and powerful boilerplate for your next WordPress plugin.
 * Author: Kodesmeden
 * Author URI: https://kodesmeden.dk/
 * Requires at least: 5.0
 * Tested up to: 6.1.1
 *
 * Text Domain: boilerplate
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Daniel S. Nielsen
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BOILERPLATE_FILE', __FILE__ );
define( 'BOILERPLATE_VERSION', '1.0.0' );

// Load language early
load_plugin_textdomain( 'boilerplate', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

// Initialize Plugin
require_once( __DIR__ . '/includes/init.php' );

// Add plugin action links
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'boilerplate_action_links' );
function boilerplate_action_links( $links ) {
	$links[] = '<a href="' . esc_url( admin_url( 'admin.php?page=boilerplate-settings' ) ) . '">' . __( 'Settings', 'boilerplate' ) . '</a>';
	
	return $links;
}

// Add plugin meta links
add_filter( 'plugin_row_meta' , 'boilerplate_meta_links', 10, 2 );
function boilerplate_meta_links( $plugin_meta, $plugin_file ) {
	if ( $plugin_file === plugin_basename( BOILERPLATE_FILE ) ) {
		$plugin_version_style = 'background: #708090; color: #fff; padding: 4px 8px 6px; border-radius: 4px; user-select: none';
		$author_link_style = 'background: #f8a717; color: #444; padding: 4px 8px 6px; border-radius: 4px;';
		
		$plugin_meta = [
			'<span style="' . esc_attr( $plugin_version_style ) . '">' . __( 'Version', 'boilerplate' ) . ' ' . BOILERPLATE_VERSION . '</span>
			<a href="https://kodesmeden.dk/?utm_source=' . parse_url( home_url(), PHP_URL_HOST ) . '&utm_medium=referral" target="_blank" style="' . esc_attr( $author_link_style ) . '"><span class="dashicons dashicons-external"></span> ' . __( 'Visit Kodesmeden', 'boilerplate' ) . '</a>',
		];
	}
     
    return $plugin_meta;
}

// Load classes
new BoilerplatePostTypes();
new BoilerplateTaxonomies();
new BoilerplateMetaBoxes();
new BoilerplateSettings();
new BoilerplateBlocks();
