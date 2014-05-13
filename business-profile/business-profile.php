<?php
/**
 * Plugin Name: Business Profile
 * Plugin URI: http://themeofthecrop.com
 * Description: Contact information, Google Maps and opening hours made easy for businesses.
 * Version: 0.0.1
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
		define( 'BPFWP_TEXTDOMAIN', 'bpfwpdomain' );
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

	}

	/**
	 * Load the plugin textdomain for localistion
	 * @since 0.0.1
	 */
	public function load_textdomain() {
		load_plugin_textdomain( BPFWP_TEXTDOMAIN, false, plugin_basename( dirname( __FILE__ ) ) . "/languages" );
	}

}
} // endif;

$bpfwp_controller = new bpfwpInit();