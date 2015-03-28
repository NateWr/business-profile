<?php
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'bpfwpSettings' ) ) {
/**
 * Class to handle configurable settings for Business Profile
 *
 * @since 0.0.1
 */
class bpfwpSettings {

	/**
	 * Default values for settings
	 * @since 0.0.1
	 */
	public $defaults = array();

	/**
	 * Stored values for settings
	 * @since 0.0.1
	 */
	public $settings = array();

	public function __construct() {

		add_action( 'init', array( $this, 'set_defaults' ) );

		add_action( 'init', array( $this, 'load_settings_panel' ) );

	}

	/**
	 * Load the plugin's default settings
	 * @since 0.0.1
	 */
	public function set_defaults() {

		$this->defaults = array(
			'schema_type'	=> 'Organization',
			'name'			=> get_bloginfo( 'name' ),
		);

		$this->defaults = apply_filters( 'bpfwp_defaults', $this->defaults );
	}

	/**
	 * Get a setting's value or fallback to a default if one exists
	 * @since 0.0.1
	 */
	public function get_setting( $setting ) {

		if ( empty( $this->settings ) ) {
			$this->settings = get_option( 'bpfwp-settings' );
		}

		if ( !empty( $this->settings[ $setting ] ) ) {
			return $this->settings[ $setting ];
		}

		if ( !empty( $this->defaults[ $setting ] ) ) {
			return $this->defaults[ $setting ];
		}

		return null;
	}

	/**
	 * Load the admin settings page
	 * @since 0.0.1
	 * @sa https://github.com/NateWr/simple-admin-pages
	 */
	public function load_settings_panel() {

		require_once( BPFWP_PLUGIN_DIR . '/lib/simple-admin-pages/simple-admin-pages.php' );
		$sap = sap_initialize_library(
			$args = array(
				'version'       => '2.0.a.9',
				'lib_url'       => BPFWP_PLUGIN_URL . '/lib/simple-admin-pages/',
			)
		);

		// Multiple location mode
		if ( $this->get_setting( 'multiple-locations' ) ) {

			$sap->add_page(
				'menu',
				array(
					'id'            => 'bpfwp-locations',
					'title'         => __( 'Locations', BPFWP_TEXTDOMAIN ),
					'menu_title'    => __( 'Locations', BPFWP_TEXTDOMAIN ),
					'capability'    => 'manage_options',
					'icon'			=> 'dashicons-location',
					'position'		=> null
				)
			);

			$sap->add_page(
				'submenu',
				array(
					'id'            => 'bpfwp-settings',
					'parent_menu'	=> 'bpfwp-locations',
					'title'         => __( 'Business Profile', BPFWP_TEXTDOMAIN ),
					'menu_title'    => __( 'Business Profile', BPFWP_TEXTDOMAIN ),
					'capability'    => 'manage_options',
				)
			);

		// Single location mode
		} else {

			$sap->add_page(
				'menu',
				array(
					'id'            => 'bpfwp-settings',
					'title'         => __( 'Business Profile', BPFWP_TEXTDOMAIN ),
					'menu_title'    => __( 'Business Profile', BPFWP_TEXTDOMAIN ),
					'capability'    => 'manage_options',
					'icon'			=> 'dashicons-businessman',
					'position'		=> null
				)
			);

		}

		$sap->add_section(
			'bpfwp-settings',
			array(
				'id'            => 'bpfwp-seo',
				'title'         => __( 'Search Engine Optimization', BPFWP_TEXTDOMAIN ),
			)
		);

		$sap->add_setting(
			'bpfwp-settings',
			'bpfwp-seo',
			'select',
			array(
				'id'            => 'schema_type',
				'title'         => __( 'Schema Type', BPFWP_TEXTDOMAIN ),
				'description'   => __( 'Select the option that best describes your business to improve how search engines understand your website', BPFWP_TEXTDOMAIN ) . ' <a href="http://schema.org/" target="_blank">Schema.org</a>',
				'blank_option'	=> false,
				'options'		=> array(
					'Organization'				=> 'Organization',
					'Corporation'				=> 'Corporation',
					'EducationalOrganization'	=> 'Educational Organization',
					'GovernmentOrganization'	=> 'Government Organization',
					'LocalBusiness'				=> 'Local Business',
					'AnimalShelter'				=> '- Animal Shelter',
					'AutomotiveBusiness'		=> '- Automotive Business',
					'ChildCare'					=> '- Child Care',
					'DryCleaningOrLaundry'		=> '- Dry Cleaning or Laundry',
					'EmergencyService'			=> '- Emergency Service',
					'EmploymentAgency'			=> '- Employment Agency',
					'EntertainmentBusiness'		=> '- Entertainment Business',
					'FinancialService'			=> '- Financial Service',
					'FoodEstablishment'			=> '- Food Establishment',
					'GovernmentOffice'			=> '- Government Office',
					'HealthAndBeautyBusiness'	=> '- Health and Beauty Business',
					'HomeAndConstructionBusiness'	=> '- Home and Construction Business',
					'InternetCafe'				=> '- Internet Cafe',
					'Library'					=> '- Library',
					'LodgingBusiness'			=> '- Lodging Business',
					'MedicalOrganization'		=> '- Medical Organization',
					'RadioStation'				=> '- Radio Station',
					'RealEstateAgent'			=> '- Real Estate Agent',
					'RecyclingCenter'			=> '- Recycling Center',
					'SelfStorage'				=> '- Self Storage',
					'SportsActivityLocation'	=> '- Sports Activity Location',
					'Store'						=> '- Store',
					'TouristInformationCenter'	=> '- Tourist Information Center',
					'TravelAgency'				=> '- Travel Agency',
					'NGO'						=> 'NGO',
					'PerformingGroup'			=> 'PerformingGroup',
					'SportsTeam'				=> 'SportsTeam',
				),
			)
		);

		$sap->add_section(
			'bpfwp-settings',
			array(
				'id'            => 'bpfwp-contact',
				'title'         => __( 'Contact Information', BPFWP_TEXTDOMAIN ),
			)
		);

		$sap->add_setting(
			'bpfwp-settings',
			'bpfwp-contact',
			'text',
			array(
				'id'            => 'name',
				'title'         => __( 'Name', BPFWP_TEXTDOMAIN ),
				'description'   => __( 'Enter the name of your business if it is different than the website name.', BPFWP_TEXTDOMAIN ),
				'placeholder'	=> $this->defaults['name'],
			)
		);

		$sap->add_setting(
			'bpfwp-settings',
			'bpfwp-contact',
			'address',
			array(
				'id'			=> 'address',
				'title'			=> __( 'Address', BPFWP_TEXTDOMAIN ),
				'strings'		=> array(
					'sep-action-links'	=> _x( ' | ', 'separator between admin action links in address component', BPFWP_TEXTDOMAIN ),
					'sep-lat-lon'		=> _x( ', ', 'separates latitude and longitude', BPFWP_TEXTDOMAIN ),
					'no-setting'		=> __( 'No map coordinates set.', BPFWP_TEXTDOMAIN ),
					'retrieving'		=> __( 'Requesting new coordinates', BPFWP_TEXTDOMAIN ),
					'select'			=> __( 'Select a match below', BPFWP_TEXTDOMAIN ),
					'view'				=> __( 'View', BPFWP_TEXTDOMAIN ),
					'retrieve'			=> __( 'Retrieve map coordinates', BPFWP_TEXTDOMAIN ),
					'remove'			=> __( 'Remove map coordinates', BPFWP_TEXTDOMAIN ),
					'try_again'			=> __( 'Try again?', BPFWP_TEXTDOMAIN ),
					'result_error'		=> __( 'Error', BPFWP_TEXTDOMAIN ),
					'result_invalid'	=> __( 'Invalid request. Be sure to fill out the address field before retrieving coordinates.', BPFWP_TEXTDOMAIN ),
					'result_denied'		=> __( 'Request denied.', BPFWP_TEXTDOMAIN ),
					'result_limit'		=> __( 'Request denied because you are over your request quota.', BPFWP_TEXTDOMAIN ),
					'result_empty'		=> __( 'Nothing was found at that address', BPFWP_TEXTDOMAIN ),
				),
			)
		);

		$sap->add_setting(
			'bpfwp-settings',
			'bpfwp-contact',
			'text',
			array(
				'id'            => 'phone',
				'title'         => __( 'Phone', BPFWP_TEXTDOMAIN ),
			)
		);

		$sap->add_setting(
			'bpfwp-settings',
			'bpfwp-contact',
			'post',
			array(
				'id'            => 'contact-page',
				'title'         => __( 'Contact Page', BPFWP_TEXTDOMAIN ),
				'description'   => __( 'Select a page on your site where users can reach you, such as a contact form.', BPFWP_TEXTDOMAIN ),
				'blank_option'	=> true,
				'args'			=> array(
					'post_type' 		=> 'page',
					'posts_per_page'	=> -1,
					'post_status'		=> 'publish',
				),
			)
		);

		$sap->add_setting(
			'bpfwp-settings',
			'bpfwp-contact',
			'text',
			array(
				'id'            => 'contact-email',
				'title'         => __( 'Email Address (optional)', BPFWP_TEXTDOMAIN ),
				'description'   => __( 'Enter an email address only if you want to display this publicly. Showing your email address on your site may cause you to receive excessive spam.', BPFWP_TEXTDOMAIN ),
			)
		);

		$sap->add_section(
			'bpfwp-settings',
			array(
				'id'            => 'bpfwp-schedule',
				'title'         => __( 'Schedule', BPFWP_TEXTDOMAIN ),
			)
		);

		$sap->add_setting(
			'bpfwp-settings',
			'bpfwp-schedule',
			'scheduler',
			array(
				'id'			=> 'opening-hours',
				'title'			=> __( 'Opening Hours', BPFWP_TEXTDOMAIN ),
				'description'	=> __( 'Define your weekly opening hours by adding scheduling rules.', BPFWP_TEXTDOMAIN ),
				'weekdays'		=> array(
					'monday'		=> _x( 'Mo', 'Monday abbreviation', BPFWP_TEXTDOMAIN ),
					'tuesday'		=> _x( 'Tu', 'Tuesday abbreviation', BPFWP_TEXTDOMAIN ),
					'wednesday'		=> _x( 'We', 'Wednesday abbreviation', BPFWP_TEXTDOMAIN ),
					'thursday'		=> _x( 'Th', 'Thursday abbreviation', BPFWP_TEXTDOMAIN ),
					'friday'		=> _x( 'Fr', 'Friday abbreviation', BPFWP_TEXTDOMAIN ),
					'saturday'		=> _x( 'Sa', 'Saturday abbreviation', BPFWP_TEXTDOMAIN ),
					'sunday'		=> _x( 'Su', 'Sunday abbreviation', BPFWP_TEXTDOMAIN )
				),
				'time_format'	=> _x( 'h:i A', 'Time format displayed in the opening hours setting panel in your admin area. Must match formatting rules at http://amsul.ca/pickadate.js/time.htm#formats', BPFWP_TEXTDOMAIN ),
				'date_format'	=> _x( 'mmmm d, yyyy', 'Date format displayed in the opening hours setting panel in your admin area. Must match formatting rules at http://amsul.ca/pickadate.js/date.htm#formatting-rules', BPFWP_TEXTDOMAIN ),
				'disable_weeks'	=> true,
				'disable_date'	=> true,
				'strings'		=> array(
					'add_rule'			=> __( 'Add another opening time', BPFWP_TEXTDOMAIN ),
					'weekly'			=> _x( 'Weekly', 'Format of a scheduling rule', BPFWP_TEXTDOMAIN ),
					'monthly'			=> _x( 'Monthly', 'Format of a scheduling rule', BPFWP_TEXTDOMAIN ),
					'date'				=> _x( 'Date', 'Format of a scheduling rule', BPFWP_TEXTDOMAIN ),
					'weekdays'			=> _x( 'Days of the week', 'Label for selecting days of the week in a scheduling rule', BPFWP_TEXTDOMAIN ),
					'month_weeks'		=> _x( 'Weeks of the month', 'Label for selecting weeks of the month in a scheduling rule', BPFWP_TEXTDOMAIN ),
					'date_label'		=> _x( 'Date', 'Label to select a date for a scheduling rule', BPFWP_TEXTDOMAIN ),
					'time_label'		=> _x( 'Time', 'Label to select a time slot for a scheduling rule', BPFWP_TEXTDOMAIN ),
					'allday'			=> _x( 'All day', 'Label to set a scheduling rule to last all day', BPFWP_TEXTDOMAIN ),
					'start'				=> _x( 'Start', 'Label for the starting time of a scheduling rule', BPFWP_TEXTDOMAIN ),
					'end'				=> _x( 'End', 'Label for the ending time of a scheduling rule', BPFWP_TEXTDOMAIN ),
					'set_time_prompt'	=> _x( 'All day long. Want to %sset a time slot%s?', 'Prompt displayed when a scheduling rule is set without any time restrictions', BPFWP_TEXTDOMAIN ),
					'toggle'			=> _x( 'Open and close this rule', 'Toggle a scheduling rule open and closed', BPFWP_TEXTDOMAIN ),
					'delete'			=> _x( 'Delete rule', 'Delete a scheduling rule', BPFWP_TEXTDOMAIN ),
					'delete_schedule'	=> __( 'Delete scheduling rule', BPFWP_TEXTDOMAIN ),
					'never'				=> _x( 'Never', 'Brief default description of a scheduling rule when no weekdays or weeks are included in the rule', BPFWP_TEXTDOMAIN ),
					'weekly_always'	=> _x( 'Every day', 'Brief default description of a scheduling rule when all the weekdays/weeks are included in the rule', BPFWP_TEXTDOMAIN ),
					'monthly_weekdays'	=> _x( '%s on the %s week of the month', 'Brief default description of a scheduling rule when some weekdays are included on only some weeks of the month. %s should be left alone and will be replaced by a comma-separated list of days and weeks in the following format: M, T, W on the first, second week of the month', BPFWP_TEXTDOMAIN ),
					'monthly_weeks'		=> _x( '%s week of the month', 'Brief default description of a scheduling rule when some weeks of the month are included but all or no weekdays are selected. %s should be left alone and will be replaced by a comma-separated list of weeks in the following format: First, second week of the month', BPFWP_TEXTDOMAIN ),
					'all_day'			=> _x( 'All day', 'Brief default description of a scheduling rule when no times are set', BPFWP_TEXTDOMAIN ),
					'before'			=> _x( 'Ends at', 'Brief default description of a scheduling rule when an end time is set but no start time. If the end time is 6pm, it will read: Ends at 6pm', BPFWP_TEXTDOMAIN ),
					'after'				=> _x( 'Starts at', 'Brief default description of a scheduling rule when a start time is set but no end time. If the start time is 6pm, it will read: Starts at 6pm', BPFWP_TEXTDOMAIN ),
					'separator'			=> _x( '&mdash;', 'Separator between times of a scheduling rule', BPFWP_TEXTDOMAIN ),
				),
			)
		);

		$sap->add_section(
			'bpfwp-settings',
			array(
				'id'            => 'bpfwp-locations',
				'title'         => __( 'Multiple Locations', BPFWP_TEXTDOMAIN ),
			)
		);

		$sap->add_setting(
			'bpfwp-settings',
			'bpfwp-locations',
			'toggle',
			array(
				'id'			=> 'multiple-locations',
				'title'			=> __( 'Multiple Locations', BPFWP_TEXTDOMAIN ),
				'label'			=> __( 'Enable support for multiple business locations.', BPFWP_TEXTDOMAIN ),
			)
		);

		$sap = apply_filters( 'bpfwp_settings_page', $sap );

		$sap->add_admin_menus();

	}

}
} // endif;
