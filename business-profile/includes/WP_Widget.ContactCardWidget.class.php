<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WP_Widget' ) ) {
	require_once ABSPATH . 'wp-admin/includes/widgets.php';
}

if ( !class_exists( 'bpfwpContactCardWidget' ) ) {
/**
 * Contact card widget
 *
 * Extends WP_Widget to display a contact card in a widget.
 * @since 0.0.1
 */
class bpfwpContactCardWidget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 * @since 0.0.1
	 */
	function __construct() {

		parent::__construct(
			'bpfwp_contact_card_widget',
			__('Contact Card', BPFWP_TEXTDOMAIN),
			array( 'description' => __( 'Display a contact card with your name, address, phone number, opening hours and map.', BPFWP_TEXTDOMAIN ), )
		);

	}

	/**
	 * Print the widget content
	 * @since 0.0.1
	 */
	public function widget( $args, $instance ) {

		global $bpfwp_controller;

		// Print the widget's HTML markup
		echo $args['before_widget'];
		if( isset( $instance['title'] ) ) {
			$title = apply_filters( 'widget_title', $instance['title'] );
			echo $args['before_title'] . $title . $args['after_title'];
		}
		echo bpwfwp_print_contact_card( $instance );
		echo $args['after_widget'];

	}

	/**
	 * Print the form to configure this widget in the admin panel
	 * @since 1.0
	 */
	public function form( $instance ) {
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"> <?php _e( 'Title' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"<?php if ( isset( $instance['title'] ) ) : ?> value="<?php echo esc_attr( $instance['title'] ); ?>"<?php endif; ?>>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_name' ); ?>"> <?php _e( 'Show Name', BPFWP_TEXTDOMAIN ); ?></label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'show_name' ); ?>" name="<?php echo $this->get_field_name( 'show_name' ); ?>" value="1"<?php if ( !empty( $instance['show_name'] ) ) : ?> checked="checked"<?php endif; ?>>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_address' ); ?>"> <?php _e( 'Show Address', BPFWP_TEXTDOMAIN ); ?></label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'show_address' ); ?>" name="<?php echo $this->get_field_name( 'show_address' ); ?>" value="1"<?php if ( !empty( $instance['show_address'] ) ) : ?> checked="checked"<?php endif; ?>>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_get_directions' ); ?>"> <?php _e( 'Show link to get directions on Google Maps', BPFWP_TEXTDOMAIN ); ?></label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'show_get_directions' ); ?>" name="<?php echo $this->get_field_name( 'show_get_directions' ); ?>" value="1"<?php if ( !empty( $instance['show_get_directions'] ) ) : ?> checked="checked"<?php endif; ?>>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_phone' ); ?>"> <?php _e( 'Show Phone number', BPFWP_TEXTDOMAIN ); ?></label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'show_phone' ); ?>" name="<?php echo $this->get_field_name( 'show_phone' ); ?>" value="1"<?php if ( !empty( $instance['show_phone'] ) ) : ?> checked="checked"<?php endif; ?>>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_contact' ); ?>"> <?php _e( 'Show contact details', BPFWP_TEXTDOMAIN ); ?></label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'show_contact' ); ?>" name="<?php echo $this->get_field_name( 'show_contact' ); ?>" value="1"<?php if ( !empty( $instance['show_contact'] ) ) : ?> checked="checked"<?php endif; ?>>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_opening_hours' ); ?>"> <?php _e( 'Show Opening Hours', BPFWP_TEXTDOMAIN ); ?></label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'show_opening_hours' ); ?>" name="<?php echo $this->get_field_name( 'show_opening_hours' ); ?>" value="1"<?php if ( !empty( $instance['show_opening_hours'] ) ) : ?> checked="checked"<?php endif; ?>>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_opening_hours_brief' ); ?>"> <?php _e( 'Show brief opening hours on one line', BPFWP_TEXTDOMAIN ); ?></label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'show_opening_hours_brief' ); ?>" name="<?php echo $this->get_field_name( 'show_opening_hours_brief' ); ?>" value="1"<?php if ( !empty( $instance['show_opening_hours_brief'] ) ) : ?> checked="checked"<?php endif; ?>>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_map' ); ?>"> <?php _e( 'Show Google Map', BPFWP_TEXTDOMAIN ); ?></label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'show_map' ); ?>" name="<?php echo $this->get_field_name( 'show_map' ); ?>" value="1"<?php if ( !empty( $instance['show_map'] ) ) : ?> checked="checked"<?php endif; ?>>
		</p>

		<?php
	}

	/**
	 * Sanitize and save the widget form values.
	 * @since 1.0
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = array();
		if ( !empty( $new_instance['title'] ) ) {
			$instance['title'] = strip_tags( $new_instance['title'] );
		}
		$instance['show_name'] = empty( $new_instance['show_name'] ) ? false : true;
		$instance['show_address'] = empty( $new_instance['show_address'] ) ? false : true;
		$instance['show_get_directions'] = empty( $new_instance['show_get_directions'] ) ? false : true;
		$instance['show_phone'] = empty( $new_instance['show_phone'] ) ? false : true;
		$instance['show_contact'] = empty( $new_instance['show_contact'] ) ? false : true;
		$instance['show_opening_hours'] = empty( $new_instance['show_opening_hours'] ) ? false : true;
		$instance['show_opening_hours_brief'] = empty( $new_instance['show_opening_hours_brief'] ) ? false : true;
		$instance['show_map'] = empty( $new_instance['show_map'] ) ? false : true;

		return $instance;

	}

}
} // endif
