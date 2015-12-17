<?php
/**
 * Plugin Name: Business Profile
 * Plugin URI: http://themeofthecrop.com
 * Description: Contact information, Google Maps and opening hours made easy for businesses.
 * Version: 1.0.8
 * Author: Theme of the Crop
 * Author URI: http://themeofthecrop.com
 * License:     GNU General Public License v2.0 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Text Domain: bpfwpdomain
 * Domain Path: /languages/
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation; either version 2 of the License,
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */
if ( ! defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'bpfwpInit' ) ) {
class bpfwpInit {

	/**
	 * Settings for displaying the contact card currently being handled
	 * @since 0.0.1
	 */
	public $display_settings = array();

	/**
	 * Initialize the plugin and register hooks
	 */
	public function __construct() {

		// Common strings
		define( 'BPFWP_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'BPFWP_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
		define( 'BPFWP_PLUGIN_FNAME', plugin_basename( __FILE__ ) );
		define( 'BPFWP_VERSION', 1 );

		// Load the textdomain
		add_action( 'init', array( $this, 'load_textdomain' ) );

		// Load settings
		require_once( BPFWP_PLUGIN_DIR . '/includes/Settings.class.php' );
		$this->settings = new bpfwpSettings();

		// Load the template functions which print the contact cards
		require_once( BPFWP_PLUGIN_DIR . '/includes/template-functions.php' );

		// Load integrations with third-party plugins/apps
		require_once( BPFWP_PLUGIN_DIR . '/includes/Integrations.class.php' );

		// Load assets
		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );

		// Register the widget
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );

		// Add links to plugin listing
		add_filter('plugin_action_links', array( $this, 'plugin_action_links' ), 10, 2);

		// Load backwards compatibility functions
		require_once( BPFWP_PLUGIN_DIR . '/includes/Compatibility.class.php' );
		new bpfwpCompatibility();

	}

	/**
	 * Load the plugin textdomain for localistion
	 * @since 0.0.1
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'business-profile', false, plugin_basename( dirname( __FILE__ ) ) . "/languages/" );
	}

	/**
	 * Register the front-end CSS styles
	 * @since 0.0.1
	 */
	function register_assets() {
		wp_register_style( 'bpfwp-default', BPFWP_PLUGIN_URL . '/assets/css/contact-card.css' );
		wp_register_script( 'bpfwp-map', BPFWP_PLUGIN_URL . '/assets/js/map.js' );
	}

	/**
	 * Register the widgets
	 * @since 0.0.1
	 */
	public function register_widgets() {
		require_once( BPFWP_PLUGIN_DIR . '/includes/WP_Widget.ContactCardWidget.class.php' );
		register_widget( 'bpfwpContactCardWidget' );
	}

	/**
	 * Add links to the plugin listing on the installed plugins page
	 * @since 0.0.1
	 */
	public function plugin_action_links( $links, $plugin ) {

		if ( $plugin == BPFWP_PLUGIN_FNAME ) {

			$links['help'] = '<a href="' . BPFWP_PLUGIN_URL . '/docs" title="' . __( 'View the help documentation for Business Profile', 'business-profile' ) . '">' . __( 'Help', 'business-profile' ) . '</a>';
		}

		return $links;

	}

}
} // endif;

global $bpfwp_controller;
$bpfwp_controller = new bpfwpInit();
