<?php
/**
 * Initialize third-party integrations
 *
 * DEPRECATED in version 1.1
 *
 * This file loads and runs code to help the theme that can be found in
 * wp-content/themes/plate-up/includes. This code is separated from the normal
 * functions.php file to make that file easier to read. Ideally, all code that
 * is typically customized by users will be accessible through functions.php.
 * The code that is loaded here should only pertain to more advanced features
 * and functions that few users will ever touch if they are using this theme.
 *
 * @package   BusinessProfile
 * @copyright Copyright (c) 2016, Theme of the Crop
 * @license   GPL-2.0+
 * @since     0.0.1
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'bpfwpIntegrations', false ) ) :

	/**
	 * Deprecated 3rd party integrations class.
	 *
	 * @since 0.0.1
	 * @deprecated 1.1
	 */
	class bpfwpIntegrations {

		/**
		 * Initialize the class and register hooks.
		 *
		 * @since  0.0.1
		 * @access public
		 * @return void
		 */
		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
		}

		/**
		 * Integrations run after the plugins are loaded
		 *
		 * @since  0.0.1
		 * @access public
		 */
		public function plugins_loaded() {

			// Restaurant Reservations handles the integration from v1.6+.
			// This code is deprecated but will load if Restaurant
			// Reservations is active but below v1.6. The RTB_VERSION constant
			// was introduced in v1.6.
			if ( defined( 'RTB_PLUGIN_DIR' ) && ! defined( 'RTB_VERSION' ) ) {

				// Add default setting for booking link to template function/shortcode.
				add_filter( 'bpfwp_default_display_settings', array( $this, 'bpwfp_booking_link_default' ) );

				// Add the callback to print the booking link.
				add_filter( 'bpwfwp_component_callbacks', array( $this, 'bpfwp_booking_link_callback' ) );

				// Add display toggle for the booking link to the widget options.
				add_filter( 'bpfwp_widget_display_toggles', array( $this, 'bpfwp_booking_link_widget_option' ) );

			}
		}

		/**
		 * Add default setting for booking link to template function/shortcode
		 * Restaurant Reservations plugin
		 *
		 * @since  0.0.1
		 * @access public
		 * @param  array $defaults The booking link defaults.
		 * @return array $defaults The modified booking link defaults.
		 */
		public function bpwfp_booking_link_default( $defaults ) {

			$defaults['show_booking_link'] = true;

			return $defaults;
		}

		/**
		 * Add the callback to print the booking link
		 * Restaurant Reservations plugin
		 *
		 * @since  0.0.1
		 * @access public
		 * @param  array $data The booking link data.
		 * @return array $data The modified booking link data.
		 */
		public function bpfwp_booking_link_callback( $data ) {

			global $rtb_controller;
			$booking_page = $rtb_controller->settings->get_setting( 'booking-page' );

			if ( ! empty( $booking_page ) ) {

				// Place the link at the end of other short links if they're
				// displayed.
				if ( isset( $data['contact'] ) ) {
					$pos = array_search( 'contact', array_keys( $data ) );
				} elseif ( isset( $data['phone'] ) ) {
					$pos = array_search( 'phone', array_keys( $data ) );
				} elseif ( isset( $data['address'] ) ) {
					$pos = array_search( 'address', array_keys( $data ) );
				}

				if ( ! empty( $pos ) ) {
					$a = array_slice( $data, 0, $pos );
					$b = array_slice( $data, $pos );
					$data = array_merge( $a, array( 'booking_page' => array( $this, 'bpfwp_print_booking_link' ) ), $b );
				} else {
					// If no short links are being displayed, just add it to the bottom.
					$data['booking_page'] = array( $this, 'bpfwp_print_booking_link' );
				}
			}

			return $data;
		}

		/**
		 * Print the booking link from the Restaurant Reservations plugin.
		 *
		 * @since  0.0.1
		 * @access public
		 * @param  string $location The location associated with the booking link.
		 * @return void
		 */
		public function bpfwp_print_booking_link( $location = false ) {

			global $bpfwp_controller;
			global $rtb_controller;

			$booking_page = $rtb_controller->settings->get_setting( 'booking-page' );

			if ( $location && get_post_meta( $location, 'rtb_append_booking_form', true ) ) {
				$booking_page = $location;
			}

			if ( bpfwp_get_display( 'show_booking_link' ) ) :
				global $rtb_controller;
				?>
				<div class="bp-booking">
					<a href="<?php echo get_permalink( $booking_page ); ?>"><?php _e( 'Book a table', 'business-profile' ); ?></a>
				</div>
				<?php
			endif;
		}

		/**
		 * Add the booking page display option to the widget options
		 * Restaurant Reservations plugin
		 *
		 * @since 0.0.1
		 * @access public
		 * @param  array $toggles The toggle options for the widget.
		 * @return array $toggles The modified toggle options for the widget.
		 */
		public function bpfwp_booking_link_widget_option( $toggles ) {

			// Place the option below the contact option.
			$pos = array_search( 'show_contact', array_keys( $toggles ) );

			if ( ! empty( $pos ) ) {
				$a = array_slice( $toggles, 0, $pos );
				$b = array_slice( $toggles, $pos );
				$toggles = array_merge( $a, array( 'show_booking_link' => __( 'Show book a table link', 'business-profile' ) ) , $b );
			} else {
				// If no short links are being displayed, just add it to the bottom.
				$toggles['show_booking_link'] = __( 'Show book a table link', 'business-profile' );
			}

			return $toggles;
		}
	}
endif;
