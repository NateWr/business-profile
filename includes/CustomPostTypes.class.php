<?php if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'bpfwpCustomPostTypes' ) ) {
/**
 * Class to handle custom post type and post meta fields
 *
 * @since 1.1
 */
class bpfwpCustomPostTypes {

	/**
	 * Location post type slug
	 *
	 * @since 1.1
	 */
	public $location_cpt_slug = 'location';

	/**
	 * Register hooks
	 *
	 * @since 1.1
	 */
	public function __construct() {

		// Register custom post types
		add_action( 'init', array( $this, 'load_cpts' ) );

		// Add meta boxes
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		// Sanitize and save post meta
		add_action( 'save_post', array( $this, 'save_meta' ) );
	}

	/**
	 * Register custom post types
	 *
	 * @since 1.1
	 */
	public function load_cpts() {

		// Define the booking custom post type
		$args = array(
			'labels' => array(
				'name'               => __( 'Locations',                   'business-profile' ),
				'singular_name'      => __( 'Location',                    'business-profile' ),
				'menu_name'          => __( 'Locations',                   'business-profile' ),
				'name_admin_bar'     => __( 'Locations',                   'business-profile' ),
				'add_new'            => __( 'Add New',                 	   'business-profile' ),
				'add_new_item'       => __( 'Add New Location',            'business-profile' ),
				'edit_item'          => __( 'Edit Location',               'business-profile' ),
				'new_item'           => __( 'New Location',                'business-profile' ),
				'view_item'          => __( 'View Location',               'business-profile' ),
				'search_items'       => __( 'Search Locations',            'business-profile' ),
				'not_found'          => __( 'No locations found',          'business-profile' ),
				'not_found_in_trash' => __( 'No locations found in trash', 'business-profile' ),
				'all_items'          => __( 'All Locations',               'business-profile' ),
			),
			'public'       => true,
			'show_in_menu' => 'bpfwp-locations',
			'has_archive'  => true,
		);

		$this->location_cpt_slug = apply_filters( 'bpfwp_location_cpt_slug', $this->location_cpt_slug );

		// Create filter so addons can modify the arguments
		$args = apply_filters( 'bpfwp_location_cpt_args', $args );

		// Register the post type
		register_post_type( $this->location_cpt_slug, $args );
	}

	/**
	 * Flush the rewrite rules
	 *
	 * This should only be called on plugin activation.
	 *
	 * @since 1.1
	 */
	public function flush_rewrite_rules() {

		// Load CPTs before flushing, as recommended in the
		// Codex
		$this->load_cpts();

		flush_rewrite_rules();
	}

	/**
	 * Add meta boxes when adding/editing locations
	 *
	 * @since 1.1
	 */
	public function add_meta_boxes() {

		$meta_boxes = array(

			// Metabox to enter phone number,
			// contact email address and select a
			// contact page.
			array (
				'id'		=>	'bpfwp_contact_metabox',
				'title'		=> __( 'Contact Details', 'business-profile' ),
				'callback'	=> array( $this, 'print_contact_metabox' ),
				'post_type'	=> $this->location_cpt_slug,
				'context'	=> 'side',
				'priority'	=> 'default'
			),

		);

		// Create filter so addons can modify the metaboxes
		$meta_boxes = apply_filters( 'bpfwp_meta_boxes', $meta_boxes );

		// Create the metaboxes
		foreach ( $meta_boxes as $meta_box ) {
			add_meta_box(
				$meta_box['id'],
				$meta_box['title'],
				$meta_box['callback'],
				$meta_box['post_type'],
				$meta_box['context'],
				$meta_box['priority']
			);
		}
	}

	/**
	 * Output the metabox HTML to enter a phone number,
	 * contact email address and select a contact page.
	 *
	 * @since 1.1
	 */
	public function print_contact_metabox() {

		// @todo This is just a scaffold now. The actual UI will be
		// built out later.
		?>

		<div class="bpfwp-meta-phone">
			<label for="bpfwp_location_phone">
				<?php _e( 'Phone Number', 'business-profile' ); ?>
			</label>
			<input type="text" name="_location_phone" id="bpfwp_location_phone">
		</div>

		<?php
	}

	/**
	 * Sanitize and save the post meta
	 *
	 * The actual sanitization and validation should be
	 * performed in a bpfwpLocation object which will
	 * handle all the location data, and perform loading
	 * and saving.
	 *
	 * @since 1.1
	 */
	public function save_meta( $post_id ) {

		// @todo save post meta
		return $post_id;
	}

}
} // endif;
