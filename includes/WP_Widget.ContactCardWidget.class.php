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

		// Display toggles
		$this->toggles = apply_filters( 'bpfwp_widget_display_toggles', array(
				'show_name'					=> __( 'Show Name', BPFWP_TEXTDOMAIN ),
				'show_address'				=> __( 'Show Address', BPFWP_TEXTDOMAIN ),
				'show_get_directions'		=> __( 'Show link to get directions on Google Maps', BPFWP_TEXTDOMAIN ),
				'show_phone'				=> __( 'Show Phone number', BPFWP_TEXTDOMAIN ),
				'show_contact'				=> __( 'Show contact details', BPFWP_TEXTDOMAIN ),
				'show_opening_hours'		=> __( 'Show Opening Hours', BPFWP_TEXTDOMAIN ),
				'show_opening_hours_brief'	=> __( 'Show brief opening hours on one line', BPFWP_TEXTDOMAIN ),
				'show_map'					=> __( 'Show Google Map', BPFWP_TEXTDOMAIN ),
			)
		);

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

		<?php foreach( $this->toggles as $id => $label ) : ?>

		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id( $id ); ?>" name="<?php echo $this->get_field_name( $id ); ?>" value="1"<?php if ( !empty( $instance[$id] ) ) : ?> checked="checked"<?php endif; ?>>
			<label for="<?php echo $this->get_field_id( $id ); ?>"> <?php echo $label; ?></label>
		</p>

		<?php endforeach;
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

		foreach( $this->toggles as $id => $label ) {
			$instance[ $id ] = empty( $new_instance[ $id ] ) ? false : true;
		}

		return $instance;
	}

}
} // endif
