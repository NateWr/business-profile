<?php
/**
 * Initialize third-party integrations
 *
 * This file loads and runs code to help the theme that can be found in
 * wp-content/themes/plate-up/includes. This code is separated from the normal
 * functions.php file to make that file easier to read. Ideally, all code that
 * is typically customized by users will be accessible through functions.php.
 * The code that is loaded here should only pertain to more advanced features
 * and functions that few users will ever touch if they are using this theme.
 */
if ( ! defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'bpfwpIntegrations' ) ) {
class bpfwpIntegrations {

	public function __construct() {

		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );

	}

	/**
	 * Integrations run after the plugins are loaded
	 * @since 0.0.1
	 */
	public function plugins_loaded() {

		// Restaurant Reservations plugin
		if ( defined( 'RTB_PLUGIN_DIR' ) ) {

			// Add default setting for booking link to template function/shortcode
			add_filter( 'bpwfp_contact_card_defaults', array( $this, 'bpwfp_booking_link_default' ) );

			// Add the callback to print the booking link
			add_filter( 'bpwfwp_component_callbacks', array( $this, 'bpwfp_booking_link_callback' ) );

			// Add display toggle for the booking link to the widget options
			add_filter( 'bpfwp_widget_display_toggles', array( $this, 'bpfwp_booking_link_widget_option' ) );

		}
	}

	/**
	 * Add default setting for booking link to template function/shortcode
	 * Restaurant Reservations plugin
	 * @since 0.0.1
	 */
	public function bpwfp_booking_link_default( $defaults ) {

		$defaults['show_booking_link'] = true;

		return $defaults;
	}

	/**
	 * Add the callback to print the booking link
	 * Restaurant Reservations plugin
	 * @since 0.0.1
     * changed on 24 jan 2016 to add cellphone and fax
	 */
	public function bpwfp_booking_link_callback( $data ) {

		global $rtb_controller;
		$booking_page = $rtb_controller->settings->get_setting( 'booking-page' );

		if ( !empty( $booking_page ) ) {

			// Place the link at the end of other short links if they're
			// displayed
            // added cellphone and faxphone , names are chosen so they don't interfere with other snippets that already would exits
			if ( isset( $data['contact'] ) ) {
				$pos = array_search( 'contact', array_keys( $data ) );
			} elseif ( isset( $data['phone'] ) ) {
				$pos = array_search( 'phone', array_keys( $data ) );
            } elseif ( isset( $data['cellphone'] ) ) {
                $pos = array_search( 'cellphone', array_keys( $data ) );
            } elseif ( isset( $data['faxphone'] ) ) {
                $pos = array_search( 'faxphone', array_keys( $data ) );
            } elseif ( isset( $data['address'] ) ) {
				$pos = array_search( 'address', array_keys( $data ) );
			}

			if ( !empty( $pos ) ) {
				$a = array_slice( $data, 0, $pos );
				$b = array_slice( $data, $pos );
				$data = array_merge( $a, array( 'booking_page' => array( $this, 'bpfwp_print_booking_link' ) ) , $b );

			// If no short links are being displayed, just add it to the bottom.
			} else {
				$data['booking_page'] = array( $this, 'bpfwp_print_booking_link' );
			}
		}

		return $data;
	 }

	/**
	 * Print the booking link
	 * Restaurant Reservations plugin
	 * @since 0.0.1
	 */
	public function bpfwp_print_booking_link() {

		global $bpfwp_controller;

		if ( $bpfwp_controller->display_settings['show_booking_link'] ) :
			global $rtb_controller;
		?>

	<div class="bp-booking">
		<a href="<?php echo get_permalink( $rtb_controller->settings->get_setting( 'booking-page'  ) ); ?>"><?php _e( 'Book a table', 'business-profile' ); ?></a>
	</div>

		<?php
		endif;
	}

	/**
	 * Add the booking page display option to the widget options
	 * Restaurant Reservations plugin
	 * @since 0.0.1
	 */
	public function bpfwp_booking_link_widget_option( $toggles ) {

		// Place the option below the contact option
		$pos = array_search( 'show_contact', array_keys( $toggles ) );

		if ( !empty( $pos ) ) {
			$a = array_slice( $toggles, 0, $pos );
			$b = array_slice( $toggles, $pos );
			$toggles = array_merge( $a, array( 'show_booking_link' => __( 'Show book a table link', 'business-profile' ) ) , $b );

		// If no short links are being displayed, just add it to the bottom.
		} else {
			$toggles['show_booking_link'] = __( 'Show book a table link', 'business-profile' );
		}

		return $toggles;
	}

}
} // endif;

new bpfwpIntegrations();
