<?php
/**
 * Plugin Name: Plugin Boilerplate
 * Version: 1.0.0
 * Description: This is a boilerplate for your next WordPress plugin.
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
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'my_plugin_action_links' );
function my_plugin_action_links( $links ) {
	$button_style = 'background: slategrey;color: #fff;padding: 1px 5px 3px;border-radius: 3px;';
	
	$links[] = '<a href="' . esc_url( admin_url( 'admin.php?page=boilerplate-settings' ) ) . '">' . __( 'Settings', 'boilerplate' ) . '</a>';
	$links[] = '<a href="https://kodesmeden.dk/" target="_blank" style="' . $button_style . '">' . __( 'Visit the author', 'boilerplate' ) . '</a>';
	
	return $links;
}

// Load classes
new BoilerplatePostTypes();
new BoilerplateTaxonomies();
new BoilerplateMetaBoxes();
new BoilerplateSettings();
new BoilerplateBlocks();
