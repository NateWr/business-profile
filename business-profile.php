<?php
/**
 * Plugin Name: Business Profile
 * Plugin URI:  http://themeofthecrop.com
 * Description: Contact information, Google Maps and opening hours made easy for businesses.
 * Version:     1.0.6
 * Author:      Theme of the Crop
 * Author URI:  http://themeofthecrop.com
 * License:     GNU General Public License v2.0 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Text Domain: business-profile
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
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'bpfwpInit', false ) ) :
	class bpfwpInit {

		/**
		 * Settings for displaying the contact card currently being handled
		 * @since 0.0.1
		 */
		public $display_settings = array();

		public $settings;

		public $cpts;

		/**
		 * Initialize the plugin and register hooks
		 */
		public function __construct() {
			self::constants();
			self::includes();
			self::instantiate();
			self::wp_hooks();
			if ( $this->settings->get_setting( 'multiple-locations' ) ) {
				register_activation_hook( __FILE__, array( $this->cpts, 'flush_rewrite_rules' ) );
			}
		}

		/**
		 * Define plugin constants.
		 *
		 * @since  1.1.0
		 * @access protected
		 * @return void
		 */
		protected function constants() {
			define( 'BPFWP_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
			define( 'BPFWP_PLUGIN_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
			define( 'BPFWP_PLUGIN_FNAME', plugin_basename( __FILE__ ) );
			define( 'BPFWP_VERSION', '1.0.6' );
		}

		/**
		 * Include all plugin files.
		 *
		 * @since  1.1.0
		 * @access protected
		 * @return void
		 */
		protected function includes() {
			require_once BPFWP_PLUGIN_DIR . '/includes/class-compatibility.php';
			require_once BPFWP_PLUGIN_DIR . '/includes/class-custom-post-types.php';
			require_once BPFWP_PLUGIN_DIR . '/includes/class-integrations.php';
			require_once BPFWP_PLUGIN_DIR . '/includes/class-settings.php';
			require_once BPFWP_PLUGIN_DIR . '/includes/class-template-loader.php';
			require_once BPFWP_PLUGIN_DIR . '/includes/template-functions.php';
		}

		/**
		 * Spin up instances of our plugin classes.
		 *
		 * @since  1.1.0
		 * @access protected
		 * @return void
		 */
		protected function instantiate() {
			new bpfwpCompatibility();
			new bpfwpIntegrations();
			$this->settings = new bpfwpSettings();
			if ( $this->settings->get_setting( 'multiple-locations' ) ) {
				$this->cpts = new bpfwpCustomPostTypes();
				$this->cpts->run();
			}
		}

		/**
		 * Hook into WordPress.
		 *
		 * @since  1.1.0
		 * @access protected
		 * @return void
		 */
		protected function wp_hooks() {
			add_action( 'init',                  array( $this, 'load_textdomain' ) );
			add_action( 'wp_enqueue_scripts',    array( $this, 'register_assets' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
			add_action( 'widgets_init',          array( $this, 'register_widgets' ) );
			add_filter( 'plugin_action_links',   array( $this, 'plugin_action_links' ), 10, 2 );
		}

		/**
		 * Load the plugin textdomain for localistion
		 * @since 0.0.1
		 */
		public function load_textdomain() {
			load_plugin_textdomain(
				'business-profile',
				false,
				plugin_basename( dirname( __FILE__ ) ) . '/languages'
			);
		}

		/**
		 * Register the front-end CSS styles
		 * @since 0.0.1
		 */
		function register_assets() {
			wp_register_style(
				'bpfwp-default',
				BPFWP_PLUGIN_URL . '/assets/css/contact-card.css',
				null,
				BPFWP_VERSION
			);
			wp_register_script(
				'bpfwp-map',
				BPFWP_PLUGIN_URL . '/assets/js/map.js',
				array( 'jquery' ),
				BPFWP_VERSION,
				true
			);
		}

		/**
		 * Register the widgets
		 * @since 0.0.1
		 */
		public function register_widgets() {
			require_once BPFWP_PLUGIN_DIR . '/includes/class-contact-card-widget.php';
			register_widget( 'bpfwpContactCardWidget' );
		}

		/**
		 * Enqueue the admin CSS for locations
		 * @since 1.1
		 */
		public function enqueue_admin_assets( $hook_suffix ) {

			global $post;

			if ( $hook_suffix == 'post-new.php' || $hook_suffix == 'post.php' ) {
				if ( $this->cpts->location_cpt_slug === $post->post_type ) {
					wp_enqueue_style( 'bpfwp-admin-location', BPFWP_PLUGIN_URL . '/assets/css/admin.css' );
				}
			}
		}

		/**
		 * Add links to the plugin listing on the installed plugins page
		 * @since 0.0.1
		 */
		public function plugin_action_links( $links, $plugin ) {
			if ( BPFWP_PLUGIN_FNAME === $plugin ) {
				$links['help'] = sprintf( '<a href="%s/docs" title="%s">%s</a>',
					BPFWP_PLUGIN_URL,
					__( 'View the help documentation for Business Profile', 'business-profile' ),
					__( 'Help', 'business-profile' )
				);
			}

			return $links;
		}

		/**
		 * Retrieve the get_theme_supports() value for a feature
		 *
		 * @since 1.1
		 */
		public function get_theme_support( $feature ) {

			$theme_support = get_theme_support( 'business-profile' );

			if ( $theme_support === true ) {
				return true;
			} elseif( $theme_support === false ) {
				return false;
			} else {
				$theme_suppot = (array) $theme_support;
				$theme_support = array_shift( $theme_support );
				return isset( $theme_support[ $feature ] ) && $theme_support[ $feature ] == true;
			}
		}

		/**
		 * Return a single instance of the main plugin class.
		 *
		 * Developers and tests may still create multiple instances by spinning
		 * them up directly, but for most uses, this method is preferred.
		 *
		 * @since 1.1.0
		 * @static
		 * @return bpfwpInit
		 */
		public static function instance() {
			static $instance;
			if ( null === $instance ) {
				$instance = new self;
			}
			return $instance;
		}
	}
endif;

$bpfwp_controller = bpfwpInit::instance();
