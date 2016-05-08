<?php
/**
 * Methods for our location custom post types.
 *
 * @package   BusinessProfile
 * @copyright Copyright (c) 2015, Theme of the Crop
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
		 * @since 1.1
		 */
		public $location_cpt_slug = 'location';

		/**
		 * Register hooks
		 *
		 * @since 1.1
		 */
		public function run() {
			add_action( 'init',                  array( $this, 'load_cpts' ) );
			add_action( 'add_meta_boxes',        array( $this, 'add_meta_boxes' ) );
			add_action( 'edit_form_after_title', array( $this, 'add_meta_nonce' ) );
			add_action( 'save_post',             array( $this, 'save_meta' ) );
			add_action( 'current_screen',        array( $this, 'maybe_flush_rewrite_rules' ) );
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
		 * Maybe flush the rewrite rules if the multiple locations option has
		 * been turned on.
		 *
		 * Should only be run on the Business Profile settings page
		 *
		 * @since 0.1
		 */
		public function maybe_flush_rewrite_rules( $current_screen ) {

			global $admin_page_hooks;
			if ( empty( $admin_page_hooks['bpfwp-locations'] ) || $current_screen->base != $admin_page_hooks['bpfwp-locations'] . '_page_bpfwp-settings' ) {
				return;
			}

			global $bpfwp_controller;
			if ( !$bpfwp_controller->settings->get_setting( 'multiple-locations' ) ) {
				return;
			}

			$rules = get_option( 'rewrite_rules' );
			if ( !array_key_exists( $this->location_cpt_slug . '/?$', $rules ) ) {
				$this->flush_rewrite_rules();
			}
		}

		/**
		 * Add meta boxes when adding/editing locations
		 *
		 * @since 1.1
		 */
		public function add_meta_boxes() {

			$meta_boxes = array(

				// Metabox to enter schema type
				array(
					'id'        => 'bpfwp_schema_metabox',
					'title'     => __( 'Schema Type', 'business-profile' ),
					'callback'  => array( $this, 'print_schema_metabox' ),
					'post_type' => $this->location_cpt_slug,
					'context'   => 'side',
					'priority'  => 'default',
				),

				// Metabox to enter phone number,
				// contact email address and select a
				// contact page.
				array(
					'id'        => 'bpfwp_contact_metabox',
					'title'     => __( 'Contact Details', 'business-profile' ),
					'callback'  => array( $this, 'print_contact_metabox' ),
					'post_type' => $this->location_cpt_slug,
					'context'   => 'side',
					'priority'  => 'default',
				),

				// Metabox to enter opening hours
				array(
					'id'        => 'bpfwp_opening_hours_metabox',
					'title'     => __( 'Opening Hours', 'business-profile' ),
					'callback'  => array( $this, 'print_opening_hours_metabox' ),
					'post_type' => $this->location_cpt_slug,
					'context'   => 'normal',
					'priority'  => 'default',
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
		 * Output a hidden nonce field to secure the saving of post meta
		 *
		 * @since 1.1
		 */
		public function add_meta_nonce() {
			global $post;
			if ( $post->post_type == $this->location_cpt_slug ) {
				wp_nonce_field( 'bpfwp_location_meta', 'bpfwp_location_meta_nonce' );
			}
		}

		/**
		* Output the metabox HTML to select a schema type
		*
		* @since 1.1
		*/
		public function print_schema_metabox( $post ) {

			global $bpfwp_controller;
			$schema_types = $bpfwp_controller->settings->get_schema_types();
			$selected = $bpfwp_controller->settings->get_setting( 'schema-type', $post->ID );

			// Fall back to general setting
			if ( empty( $selected ) ) {
				$selected = $bpfwp_controller->settings->get_setting( 'schema-type' );
			}


			?>

			<div class="bpfwp-meta-input bpfwp-meta-schema-type">
				<label for="bpfwp_schema-type">
					<?php esc_html_e( 'Schema type', 'business-profile' ); ?>
				</label>
				<select name="schema_type" id="bpfwp_schema-type" aria-describedby="bpfwp_schema-type_description">
					<?php foreach( $schema_types as $key => $label ) : ?>
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
		 * @since 1.1
		 */
		public function print_contact_metabox( $post ) {

			// Get an array of all pages with sane limits
			$pages = array();
			$query = new WP_Query(
				array(
					'post_type' => array( 'page' ),
					'no_found_rows' => true,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
					'posts_per_page' => 500,
				)
			);
			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					$pages[get_the_ID()] = get_the_title();
				}
			}
			wp_reset_postdata();

			// @todo Address component should use the address component from
			// simple-admin-pages
			?>

			<div class="bpfwp-meta-input bpfwp-meta-address">
				<label for="bpfwp_address">
					<?php esc_html_e( 'Address', 'business-profile' ); ?>
				</label>
				<textarea name="geo_address" id="bpfwp_address"><?php echo esc_textarea( get_post_meta( $post->ID, 'geo_address', true ) ); ?></textarea>
			</div>

			<div class="bpfwp-meta-input bpfwp-meta-contact-page">
				<label for="bpfwp_contact-page">
					<?php esc_html_e( 'Contact Page', 'business-profile' ); ?>
				</label>
				<select name="contact_post" id="bpfwp_contact-page">
					<option></option>
					<?php foreach( $pages as $id => $title ) : ?>
						<option value="<?php echo absint( $id ); ?>"<?php if ( $id == get_post_meta( $post->ID, 'contact_post', true ) ) : ?> selected<?php endif; ?>>
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
		* @since 1.1
		*/
		public function print_opening_hours_metabox( $post ) {

			// @todo this is just a placeholder. The scheduler component from
			// simple-admin-pages will be used
			?>

			<div class="bpfwp-meta-input bpfwp-meta-opening-hours">
				<!-- nothing here yet -->
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

			if ( !isset( $_POST['post_type'] ) || $_POST['post_type'] != $this->location_cpt_slug ) {
				return $post_id;
			}

			if ( !isset( $_POST['bpfwp_location_meta_nonce'] ) || !wp_verify_nonce( $_POST['bpfwp_location_meta_nonce'], 'bpfwp_location_meta' ) ) {
				return $post_id;
			}

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTHOSAVE ) {
				return $post_id;
			}

			if ( !current_user_can( 'edit_post', $post_id ) ) {
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
				'opening_hours' => 'sanitize_text_field', // @todo
			);

			foreach( $post_meta as $key => $callback ) {

				if ( !isset( $_POST[$key] ) ) {
					continue;
				}

				$cur = get_post_meta( $post_id, $key, true );
				$new = call_user_func( $callback, $_POST[$key] );
				if ( $new !== $cur ) {
					update_post_meta( $post_id, $key, $new );
				}
			}

			return $post_id;
		}

	}
endif;
