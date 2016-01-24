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
				'show_name'					=> __( 'Show Name', 'business-profile' ),
				'show_address'				=> __( 'Show Address', 'business-profile' ),
				'show_get_directions'		=> __( 'Show link to get directions on Google Maps', 'business-profile' ),
				'show_phone'				=> __( 'Show Phone number', 'business-profile' ),
				'show_cellphone'			=> __( 'Show Cellphone number', 'business-profile' ),
				'show_faxphone'				=> __( 'Show Fax number', 'business-profile' ),
				'show_contact'				=> __( 'Show contact details', 'business-profile' ),
				'show_opening_hours'		=> __( 'Show Opening Hours', 'business-profile' ),
				'show_opening_hours_brief'	=> __( 'Show brief opening hours on one line', 'business-profile' ),
				'show_map'					=> __( 'Show Google Map', 'business-profile' ),
			)
		);

		parent::__construct(
			'bpfwp_contact_card_widget',
			__('Contact Card', 'business-profile'),
			array( 'description' => __( 'Display a contact card with your name, address, phone number, opening hours and map.', 'business-profile' ), )
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
