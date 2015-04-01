<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'bpfwpCompatibility', false ) ) {
	/**
	 * Class to handle backwards compatibility issues for Business Profile.
	 *
	 * @since 1.0.6
	 */
	class bpfwpCompatibility {

		/**
		 * Set up hooks
		 */
		public function __construct() {

			// Preserve this defined constant in case anyone relied on it
			// to check if the plugin was active
			define( 'BPFWP_TEXTDOMAIN', 'bpfwpdomain' );

			// Load a .mo file for an old textdomain if one exists
			add_filter( 'load_textdomain_mofile', array( $this, 'load_old_textdomain' ), 10, 2 );

		}

		/**
		 * Load a .mo file for an old textdomain if one exists
		 *
		 * In versions prior to 1.0.6, the textdomain did not match the plugin
		 * slug. This had to be changed to comply with upcoming changes to
		 * how translations are managed in the .org repo. This function
		 * checks to see if an old translation file exists and loads it if
		 * it does, so that people don't lose their translations.
		 *
		 * Old textdomain: bpfwpdomain
		 */
		public function load_old_textdomain( $mofile, $textdomain ) {
			if ( 'business-profile' !== $textdomain ) {
				return $mofile;
			}

			if ( 0 === strpos( $mofile, WP_LANG_DIR . '/plugins/' ) && ! file_exists( $mofile ) ) {
				$mofile = dirname( $mofile ) . DIRECTORY_SEPARATOR . str_replace( $textdomain, 'bpfwpdomain', basename( $mofile ) );
			}

			return $mofile;
		}

	}
} // end class exists check.
