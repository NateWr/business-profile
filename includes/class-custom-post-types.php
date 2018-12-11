<?php
/**
 * Methods for our location custom post types.
 *
 * @package   BusinessProfile
 * @copyright Copyright (c) 2016, Theme of the Crop
 * @license   GPL-2.0+
 * @since     1.1.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'bpfwpCustomPostTypes', false ) ) :

	/**
	 * Class to handle custom post type and post meta fields
	 *
	 * @since 1.1
	 */
	class bpfwpCustomPostTypes {

		/**
		 * Location post type slug
		 *
		 * @since  1.1
		 * @access public
		 * @var    string
		 */
		public $location_cpt_slug = 'location';

		/**
		 * Register hooks
		 *
		 * @since  1.1
		 * @access public
		 * @return void
		 */
		public function run() {
			add_action( 'init',                  array( $this, 'load_cpts' ) );
			add_action( 'add_meta_boxes',        array( $this, 'add_meta_boxes' ) );
			add_action( 'edit_form_after_title', array( $this, 'add_meta_nonce' ) );
			add_action( 'save_post',             array( $this, 'save_meta' ) );
			add_action( 'current_screen',        array( $this, 'maybe_flush_rewrite_rules' ) );
			add_action( 'the_content',           array( $this, 'append_to_content' ) );
		}

		/**
		 * Register custom post types
		 *
		 * @since  1.1
		 * @access public
		 * @return void
		 */
		public function load_cpts() {

			// Define the booking custom post type.
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
					'view_items'         => __( 'View Locations',              'business-profile' ),
					'search_items'       => __( 'Search Locations',            'business-profile' ),
					'not_found'          => __( 'No locations found',          'business-profile' ),
					'not_found_in_trash' => __( 'No locations found in trash', 'business-profile' ),
					'all_items'          => __( 'All Locations',               'business-profile' ),
				),
				'public'       => true,
				'show_in_menu' => 'bpfwp-locations',
				'show_in_rest' => true,
				'has_archive'  => true,
				'supports'     => array( 'title', 'editor', 'thumbnail' ),
			);

			$this->location_cpt_slug = apply_filters( 'bpfwp_location_cpt_slug', $this->location_cpt_slug );

			// Create filter so addons can modify the arguments.
			$args = apply_filters( 'bpfwp_location_cpt_args', $args );

			// Register the post type.
			register_post_type( $this->location_cpt_slug, $args );
		}

		/**
		 * Flush the rewrite rules
		 *
		 * This should only be called on plugin activation.
		 *
		 * @since  1.1
		 * @access public
		 * @return void
		 */
		public function flush_rewrite_rules() {

			// Load CPTs before flushing, as recommended in the Codex.
			$this->load_cpts();

			flush_rewrite_rules();
		}

		/**
		 * Maybe flush the rewrite rules if the multiple locations option has
		 * been turned on.
		 *
		 * Should only be run on the Business Profile settings page
		 *
		 * @since  1.1
		 * @access public
		 * @param  string $current_screen The current admin screen slug.
		 * @return void
		 */
		public function maybe_flush_rewrite_rules( $current_screen ) {

			global $admin_page_hooks;
			if ( empty( $admin_page_hooks['bpfwp-locations'] ) || $current_screen->base !== $admin_page_hooks['bpfwp-locations'] . '_page_bpfwp-settings' ) {
				return;
			}

			if ( ! bpfwp_setting( 'multiple-locations' ) ) {
				return;
			}

			$rules = get_option( 'rewrite_rules' );
			if ( ! array_key_exists( $this->location_cpt_slug . '/?$', $rules ) ) {
				$this->flush_rewrite_rules();
			}
		}

		/**
		 * Add meta boxes when adding/editing locations
		 *
		 * @since  1.1
		 * @access public
		 * @return void
		 */
		public function add_meta_boxes() {

			$meta_boxes = array(

				// Metabox to enter schema type.
				array(
					'id'        => 'bpfwp_schema_metabox',
					'title'     => __( 'Schema Type', 'business-profile' ),
					'callback'  => array( $this, 'print_schema_metabox' ),
					'post_type' => $this->location_cpt_slug,
					'context'   => 'side',
					'priority'  => 'default',
				),

				// Metabox to enter phone number, contact email address and
				// select a contact page.
				array(
					'id'        => 'bpfwp_contact_metabox',
					'title'     => __( 'Contact Details', 'business-profile' ),
					'callback'  => array( $this, 'print_contact_metabox' ),
					'post_type' => $this->location_cpt_slug,
					'context'   => 'side',
					'priority'  => 'default',
				),

				// Metabox to enter opening hours.
				array(
					'id'        => 'bpfwp_opening_hours_metabox',
					'title'     => __( 'Opening Hours', 'business-profile' ),
					'callback'  => array( $this, 'print_opening_hours_metabox' ),
					'post_type' => $this->location_cpt_slug,
					'context'   => 'normal',
					'priority'  => 'default',
				),

			);

			// Create filter so addons can modify the metaboxes.
			$meta_boxes = apply_filters( 'bpfwp_meta_boxes', $meta_boxes );

			// Create the metaboxes.
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
		 * Output a hidden nonce field to secure the saving of post meta
		 *
		 * @since  1.1
		 * @access public
		 * @return void
		 */
		public function add_meta_nonce() {
			global $post;
			if ( $post->post_type === $this->location_cpt_slug ) {
				wp_nonce_field( 'bpfwp_location_meta', 'bpfwp_location_meta_nonce' );
			}
		}

		/**
		 * Output the metabox HTML to select a schema type
		 *
		 * @since  1.1
		 * @access public
		 * @param  WP_Post $post The current post object.
		 * @return void
		 */
		public function print_schema_metabox( $post ) {

			global $bpfwp_controller;
			$schema_types = $bpfwp_controller->settings->get_schema_types();
			$selected = bpfwp_setting( 'schema-type', $post->ID );

			// Fall back to general setting.
			if ( empty( $selected ) ) {
				$selected = bpfwp_setting( 'schema-type' );
			}
			?>

			<div class="bpfwp-meta-input bpfwp-meta-schema-type">
				<label for="bpfwp_schema-type">
					<?php esc_html_e( 'Schema type', 'business-profile' ); ?>
				</label>
				<select name="schema_type" id="bpfwp_schema-type" aria-describedby="bpfwp_schema-type_description">
					<?php foreach ( $schema_types as $key => $label ) : ?>
						<option value="<?php esc_attr_e( $key ); ?>"<?php if ( $selected === $key ) : ?> selected<?php endif; ?>>
							<?php esc_attr_e( $label ); ?>
						</option>
					<?php endforeach; ?>
				</select>
				<p class="description" id="bpfwp_schema-type_description">
					<?php esc_html_e( 'Select the option that best describes your business to improve how search engines understand your website.', 'business-profile' ); ?>
					<a href="http://schema.org/" target="_blank">Schema.org</a>
				</p>
			</div>

			<?php
		}

		/**
		 * Output the metabox HTML to enter a phone number,
		 * contact email address and select a contact page.
		 *
		 * @since  1.1
		 * @access public
		 * @param  WP_Post $post The current post object.
		 * @return void
		 */
		public function print_contact_metabox( $post ) {

			global $bpfwp_controller;

			// Address mimics HTML markup from Simple Admin Pages component.
			wp_enqueue_script( 'bpfwp-admin-location-address', BPFWP_PLUGIN_URL . '/lib/simple-admin-pages/js/address.js', array( 'jquery' ) );
			wp_localize_script(
				'bpfwp-admin-location-address',
				'sap_address',
				array(
					'api_key' => $bpfwp_controller->settings->get_setting( 'google-maps-api-key' ),
					'strings' => array(
						'no-setting'     => __( 'No map coordinates set.', 'business-profile' ),
						'sep-lat-lon'    => _x( ', ', 'separates latitude and longitude', 'business-profile' ),
						'retrieving'     => __( 'Requesting new coordinates', 'business-profile' ),
						'select'         => __( 'Select a match below', 'business-profile' ),
						'view'           => __( 'View', 'business-profile' ),
						'result_error'   => __( 'Error', 'business-profile' ),
						'result_invalid' => __( 'Invalid request. Be sure to fill out the address field before retrieving coordinates.', 'business-profile' ),
						'result_denied'  => __( 'Request denied.', 'business-profile' ),
						'result_limit'   => __( 'Request denied because you are over your request quota.', 'business-profile' ),
						'result_empty'   => __( 'Nothing was found at that address.', 'business-profile' ),
					),
				)
			);
			?>

			<div class="bpfwp-meta-input bpfwp-meta-geo_address sap-address">
				<textarea name="geo_address" id="bpfwp_address"><?php echo esc_textarea( get_post_meta( $post->ID, 'geo_address', true ) ); ?></textarea>
				<p class="sap-map-coords-wrapper">
					<span class="dashicons dashicons-location-alt"></span>
					<span class="sap-map-coords">
						<?php
						$geo_latitude = get_post_meta( $post->ID, 'geo_latitude', true );
						$geo_longitude = get_post_meta( $post->ID, 'geo_longitude', true );
						if ( empty( $geo_latitude ) || empty( $geo_longitude ) ) :
							esc_html_e( 'No map coordinates set.', 'business-profile' );
						else : ?>
							<?php echo get_post_meta( $post->ID, 'geo_latitude', true ) . esc_html_x( ', ', 'separates latitude and longitude', 'business-profile' ) . get_post_meta( $post->ID, 'geo_longitude', true ); ?>
							<a href="//maps.google.com/maps?q=<?php echo esc_attr( get_post_meta( $post->ID, 'geo_latitude', true ) ) . ',' . esc_attr( get_post_meta( $post->ID, 'geo_longitude', true ) ); ?>" class="sap-view-coords" target="_blank"><?php esc_html_e( 'View', 'business-profile' ); ?></a>
						<?php
						endif; ?>
					</span>
				</p>
				<p class="sap-coords-action-wrapper">
					<a href="#" class="sap-get-coords">
						<?php esc_html_e( 'Retrieve map coordinates', 'business-profile' ); ?>
					</a>
					<?php echo esc_html_x( ' | ', 'separator between admin action links in address component', 'business-profile' ); ?>
					<a href="#" class="sap-remove-coords">
						<?php esc_html_e( 'Remove map coordinates', 'business-profile' ); ?>
					</a>
				</p>
				<input type="hidden" class="lat" name="geo_latitude" value="<?php echo esc_attr( get_post_meta( $post->ID, 'geo_latitude', true ) ); ?>">
				<input type="hidden" class="lon" name="geo_longitude" value="<?php echo esc_attr( get_post_meta( $post->ID, 'geo_longitude', true ) ); ?>">
			</div>

			<?php
				// Get an array of all pages with sane limits.
				$pages = array();
				$query = new WP_Query( array(
					'post_type'              => array( 'page' ),
					'no_found_rows'          => true,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
					'posts_per_page'         => 500,
				) );
				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->next_post();
						$pages[ $query->post->ID ] = $query->post->post_title;
					}
				}
				wp_reset_postdata();
			?>

			<div class="bpfwp-meta-input bpfwp-meta-contact-page">
				<label for="bpfwp_contact-page">
					<?php esc_html_e( 'Contact Page', 'business-profile' ); ?>
				</label>
				<select name="contact_post" id="bpfwp_contact-page">
					<option></option>
					<?php foreach ( $pages as $id => $title ) : ?>
						<option value="<?php echo absint( $id ); ?>"<?php if ( get_post_meta( $post->ID, 'contact_post', true ) == $id ) : ?> selected<?php endif; ?>>
							<?php esc_attr_e( $title ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="bpfwp-meta-input bpfwp-meta-contact-email">
				<label for="bpfwp_contact-email">
					<?php esc_html_e( 'Email Address (optional)', 'business-profile' ); ?>
				</label>
				<input type="email" name="contact_email" id="bpfwp_contact-email" value="<?php esc_attr_e( get_post_meta( $post->ID, 'contact_email', true ) ); ?>">
			</div>

			<div class="bpfwp-meta-input bpfwp-meta-phone">
				<label for="bpfwp_phone">
					<?php esc_html_e( 'Phone Number', 'business-profile' ); ?>
				</label>
				<input type="tel" name="phone" id="bpfwp_phone" value="<?php esc_attr_e( get_post_meta( $post->ID, 'phone', true ) ); ?>">
			</div>

			<?php
		}

		/**
		 * Output the metabox HTML to define opening hours
		 *
		 * @since  1.1
		 * @access public
		 * @param  WP_Post $post The current post object.
		 * @return void
		 */
		public function print_opening_hours_metabox( $post ) {

			$scheduler = $this->get_scheduler_meta_object( get_post_meta( $post->ID, 'opening_hours', true ) );

			// Load required scripts and styles.
			wp_enqueue_style( 'bpfwp-admin-location-sap', BPFWP_PLUGIN_URL . '/lib/simple-admin-pages/css/admin.css' );
			foreach ( $scheduler->styles as $handle => $style ) {
				wp_enqueue_style( $handle, BPFWP_PLUGIN_URL . '/lib/simple-admin-pages/' . $style['path'], $style['dependencies'], $style['version'], $style['media'] );
			}
			foreach ( $scheduler->scripts as $handle => $script ) {
				wp_enqueue_script( $handle, BPFWP_PLUGIN_URL . '/lib/simple-admin-pages/' . $script['path'], $script['dependencies'], $script['version'], $script['footer'] );
			}
			?>

			<div class="bpfwp-meta-input bpfwp-meta-opening-hours">
				<?php $scheduler->display_setting(); ?>
			</div>

			<?php
		}

		/**
		 * Get a modified Scheduler object from the Simple Admin Pages library
		 *
		 * This modified scheduler is used to display and sanitize a scheduler
		 * component on the location post editing screen.
		 *
		 * @since  1.1
		 * @access public
		 * @see    lib/simple-admin-pages/classes/AdminPageSetting.Scheduler.class.php
		 * @param  string $values Optional values to be set.
		 * @return bpfwpSAPSchedulerMeta $scheduler An instance of the scheduler class.
		 */
		public function get_scheduler_meta_object( $values = null ) {

			require_once BPFWP_PLUGIN_DIR . '/includes/class-sap-scheduler-meta.php';
			$scheduler = new bpfwpSAPSchedulerMeta(
				array(
					'page'          => 'dummy_page', // Required but not used.
					'id'            => 'opening_hours',
					'title'         => __( 'Opening Hours', 'business-profile' ),
					'description'   => __( 'Define your weekly opening hours by adding scheduling rules.', 'business-profile' ),
					'weekdays'      => array(
						'monday'    => _x( 'Mo', 'Monday abbreviation', 'business-profile' ),
						'tuesday'   => _x( 'Tu', 'Tuesday abbreviation', 'business-profile' ),
						'wednesday' => _x( 'We', 'Wednesday abbreviation', 'business-profile' ),
						'thursday'  => _x( 'Th', 'Thursday abbreviation', 'business-profile' ),
						'friday'    => _x( 'Fr', 'Friday abbreviation', 'business-profile' ),
						'saturday'  => _x( 'Sa', 'Saturday abbreviation', 'business-profile' ),
						'sunday'    => _x( 'Su', 'Sunday abbreviation', 'business-profile' ),
					),
					'time_format'   => _x( 'h:i A', 'Time format displayed in the opening hours setting panel in your admin area. Must match formatting rules at http://amsul.ca/pickadate.js/time.htm#formats', 'business-profile' ),
					'date_format'   => _x( 'mmmm d, yyyy', 'Date format displayed in the opening hours setting panel in your admin area. Must match formatting rules at http://amsul.ca/pickadate.js/date.htm#formatting-rules', 'business-profile' ),
					'disable_weeks' => true,
					'disable_date'  => true,
					'strings'       => array(
						'add_rule'         => __( 'Add another opening time', 'business-profile' ),
						'weekly'           => _x( 'Weekly', 'Format of a scheduling rule', 'business-profile' ),
						'monthly'          => _x( 'Monthly', 'Format of a scheduling rule', 'business-profile' ),
						'date'             => _x( 'Date', 'Format of a scheduling rule', 'business-profile' ),
						'weekdays'         => _x( 'Days of the week', 'Label for selecting days of the week in a scheduling rule', 'business-profile' ),
						'month_weeks'      => _x( 'Weeks of the month', 'Label for selecting weeks of the month in a scheduling rule', 'business-profile' ),
						'date_label'       => _x( 'Date', 'Label to select a date for a scheduling rule', 'business-profile' ),
						'time_label'       => _x( 'Time', 'Label to select a time slot for a scheduling rule', 'business-profile' ),
						'allday'           => _x( 'All day', 'Label to set a scheduling rule to last all day', 'business-profile' ),
						'start'            => _x( 'Start', 'Label for the starting time of a scheduling rule', 'business-profile' ),
						'end'              => _x( 'End', 'Label for the ending time of a scheduling rule', 'business-profile' ),
						'set_time_prompt'  => _x( 'All day long. Want to %sset a time slot%s?', 'Prompt displayed when a scheduling rule is set without any time restrictions', 'business-profile' ),
						'toggle'           => _x( 'Open and close this rule', 'Toggle a scheduling rule open and closed', 'business-profile' ),
						'delete'           => _x( 'Delete rule', 'Delete a scheduling rule', 'business-profile' ),
						'delete_schedule'  => __( 'Delete scheduling rule', 'business-profile' ),
						'never'            => _x( 'Never', 'Brief default description of a scheduling rule when no weekdays or weeks are included in the rule', 'business-profile' ),
						'weekly_always'    => _x( 'Every day', 'Brief default description of a scheduling rule when all the weekdays/weeks are included in the rule', 'business-profile' ),
						'monthly_weekdays' => _x( '%s on the %s week of the month', 'Brief default description of a scheduling rule when some weekdays are included on only some weeks of the month. %s should be left alone and will be replaced by a comma-separated list of days and weeks in the following format: M, T, W on the first, second week of the month', 'business-profile' ),
						'monthly_weeks'    => _x( '%s week of the month', 'Brief default description of a scheduling rule when some weeks of the month are included but all or no weekdays are selected. %s should be left alone and will be replaced by a comma-separated list of weeks in the following format: First, second week of the month', 'business-profile' ),
						'all_day'          => _x( 'All day', 'Brief default description of a scheduling rule when no times are set', 'business-profile' ),
						'before'           => _x( 'Ends at', 'Brief default description of a scheduling rule when an end time is set but no start time. If the end time is 6pm, it will read: Ends at 6pm', 'business-profile' ),
						'after'            => _x( 'Starts at', 'Brief default description of a scheduling rule when a start time is set but no end time. If the start time is 6pm, it will read: Starts at 6pm', 'business-profile' ),
						'separator'        => _x( '&mdash;', 'Separator between times of a scheduling rule', 'business-profile' ),
					),
				)
			);

			if ( ! empty( $values ) ) {
				$scheduler->set_value( $values );
			}

			return $scheduler;
		}

		/**
		 * Sanitize and save the post meta
		 *
		 * The actual sanitization and validation should be
		 * performed in a bpfwpLocation object which will
		 * handle all the location data, and perform loading
		 * and saving.
		 *
		 * @since  1.1
		 * @access public
		 * @param  int $post_id The current post ID.
		 * @return int $post_id The current post ID.
		 */
		public function save_meta( $post_id ) {
			if ( ! isset( $_POST['bpfwp_location_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['bpfwp_location_meta_nonce'] ), 'bpfwp_location_meta' ) ) { // Input var okay.
				return $post_id;
			}

			if ( ! isset( $_POST['post_type'] ) || $_POST['post_type'] !== $this->location_cpt_slug ) { // Input var okay.
				return $post_id;
			}

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}

			$post_meta = array(
				'schema_type'   => 'sanitize_text_field',
				'geo_address'   => 'wp_kses_post',
				'geo_latitude'  => 'sanitize_text_field',
				'geo_longitude' => 'sanitize_text_field',
				'phone'         => 'sanitize_text_field',
				'contact_post'  => 'absint',
				'contact_email' => 'sanitize_email',
				'opening_hours' => array( $this, 'sanitize_opening_hours' ),
			);

			foreach ( $post_meta as $key => $sanitizer ) {

				if ( ! isset( $_POST[ $key ] ) ) { // Input var okay.
					$_POST[ $key ] = '';
				}

				$cur = get_post_meta( $post_id, $key, true );
				$new = call_user_func( $sanitizer, wp_unslash( $_POST[ $key ] ) ); // Input var okay.

				if ( $new !== $cur ) {
					update_post_meta( $post_id, $key, $new );
				}
			}

			return $post_id;
		}

		/**
		 * Sanitize opening hours
		 *
		 * This is a wrapper for the sanitization callback in the Scheduler
		 * component of Simple Admin Pages
		 *
		 * @since 1.1
		 * @access public
		 * @see    lib/simple-admin-pages/classes/AdminPageSetting.Scheduler.class.php
		 * @param  array $values Raw values for the opening hours.
		 * @return array $values Sanitized values for the opening hours.
		 */
		public function sanitize_opening_hours( $values ) {
			$scheduler = $this->get_scheduler_meta_object( $values );
			return $scheduler->sanitize_callback_wrapper( $values );
		}

		/**
		 * Automatically append a contact card to `the_content` on location
		 * single pages
		 *
		 * @since  1.1
		 * @access public
		 * @param  string $content The current WordPress content.
		 * @return string $content The modified WordPress content.
		 */
		public function append_to_content( $content ) {

			if ( ! is_main_query() || ! in_the_loop() || post_password_required() ) {
				return $content;
			}

			global $bpfwp_controller;

			if ( $bpfwp_controller->get_theme_support( 'disable_append_to_content' ) ) {
				return $content;
			}

			global $post;

			if ( ! $post instanceof WP_Post || $post->post_type !== $bpfwp_controller->cpts->location_cpt_slug ) {
				return $content;
			}

			return $content . '[contact-card location=' . $post->ID . ' show_name=0]';
		}
	}
endif;
