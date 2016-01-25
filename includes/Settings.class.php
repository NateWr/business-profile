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
     * added cellphone and fax
	 */
	public function load_settings_panel() {

		require_once( BPFWP_PLUGIN_DIR . '/lib/simple-admin-pages/simple-admin-pages.php' );
		$sap = sap_initialize_library(
			$args = array(
				'version'       => '2.0',
				'lib_url'       => BPFWP_PLUGIN_URL . '/lib/simple-admin-pages/',
			)
		);

		$sap->add_page(
			'menu',
			array(
				'id'            => 'bpfwp-settings',
				'title'         => __( 'Business Profile', 'business-profile' ),
				'menu_title'    => __( 'Business Profile', 'business-profile' ),
				'capability'    => 'manage_options',
				'icon'			=> 'dashicons-businessman',
				'position'		=> null
			)
		);

		$sap->add_section(
			'bpfwp-settings',
			array(
				'id'            => 'bpfwp-seo',
				'title'         => __( 'Search Engine Optimization', 'business-profile' ),
			)
		);

		$sap->add_setting(
			'bpfwp-settings',
			'bpfwp-seo',
			'select',
			array(
				'id'            => 'schema_type',
				'title'         => __( 'Schema Type', 'business-profile' ),
				'description'   => __( 'Select the option that best describes your business to improve how search engines understand your website', 'business-profile' ) . ' <a href="http://schema.org/" target="_blank">Schema.org</a>',
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
				'title'         => __( 'Contact Information', 'business-profile' ),
			)
		);

		$sap->add_setting(
			'bpfwp-settings',
			'bpfwp-contact',
			'text',
			array(
				'id'            => 'name',
				'title'         => __( 'Name', 'business-profile' ),
				'description'   => __( 'Enter the name of your business if it is different than the website name.', 'business-profile' ),
				'placeholder'	=> $this->defaults['name'],
			)
		);

		$sap->add_setting(
			'bpfwp-settings',
			'bpfwp-contact',
			'address',
			array(
				'id'			=> 'address',
				'title'			=> __( 'Address', 'business-profile' ),
				'strings'		=> array(
					'sep-action-links'	=> _x( ' | ', 'separator between admin action links in address component', 'business-profile' ),
					'sep-lat-lon'		=> _x( ', ', 'separates latitude and longitude', 'business-profile' ),
					'no-setting'		=> __( 'No map coordinates set.', 'business-profile' ),
					'retrieving'		=> __( 'Requesting new coordinates', 'business-profile' ),
					'select'			=> __( 'Select a match below', 'business-profile' ),
					'view'				=> __( 'View', 'business-profile' ),
					'retrieve'			=> __( 'Retrieve map coordinates', 'business-profile' ),
					'remove'			=> __( 'Remove map coordinates', 'business-profile' ),
					'try_again'			=> __( 'Try again?', 'business-profile' ),
					'result_error'		=> __( 'Error', 'business-profile' ),
					'result_invalid'	=> __( 'Invalid request. Be sure to fill out the address field before retrieving coordinates.', 'business-profile' ),
					'result_denied'		=> __( 'Request denied.', 'business-profile' ),
					'result_limit'		=> __( 'Request denied because you are over your request quota.', 'business-profile' ),
					'result_empty'		=> __( 'Nothing was found at that address', 'business-profile' ),
				),
			)
		);

		$sap->add_setting(
			'bpfwp-settings',
			'bpfwp-contact',
			'text',
			array(
				'id'            => 'phone',
				'title'         => __( 'Phone', 'business-profile' ),
			)
		);
        
        $sap->add_setting(
            'bpfwp-settings',
            'bpfwp-contact',
            'text',
            array(
                'id'            => 'cellphone',
                'title'         => __( 'Cellphone', 'business-profile' ),
            )
        );
        $sap->add_setting(
            'bpfwp-settings',
            'bpfwp-contact',
            'text',
            array(
                'id'            => 'faxphone',
                'title'         => __( 'Fax', 'business-profile' ),
            )
        );
       
		$sap->add_setting(
			'bpfwp-settings',
			'bpfwp-contact',
			'post',
			array(
				'id'            => 'contact-page',
				'title'         => __( 'Contact Page', 'business-profile' ),
				'description'   => __( 'Select a page on your site where users can reach you, such as a contact form.', 'business-profile' ),
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
				'title'         => __( 'Email Address (optional)', 'business-profile' ),
				'description'   => __( 'Enter an email address only if you want to display this publicly. Showing your email address on your site may cause you to receive excessive spam.', 'business-profile' ),
			)
		);

		$sap->add_section(
			'bpfwp-settings',
			array(
				'id'            => 'bpfwp-schedule',
				'title'         => __( 'Schedule', 'business-profile' ),
			)
		);

		$sap->add_setting(
			'bpfwp-settings',
			'bpfwp-schedule',
			'scheduler',
			array(
				'id'			=> 'opening-hours',
				'title'			=> __( 'Opening Hours', 'business-profile' ),
				'description'	=> __( 'Define your weekly opening hours by adding scheduling rules.', 'business-profile' ),
				'weekdays'		=> array(
					'monday'		=> _x( 'Mo', 'Monday abbreviation', 'business-profile' ),
					'tuesday'		=> _x( 'Tu', 'Tuesday abbreviation', 'business-profile' ),
					'wednesday'		=> _x( 'We', 'Wednesday abbreviation', 'business-profile' ),
					'thursday'		=> _x( 'Th', 'Thursday abbreviation', 'business-profile' ),
					'friday'		=> _x( 'Fr', 'Friday abbreviation', 'business-profile' ),
					'saturday'		=> _x( 'Sa', 'Saturday abbreviation', 'business-profile' ),
					'sunday'		=> _x( 'Su', 'Sunday abbreviation', 'business-profile' )
				),
				'time_format'	=> _x( 'h:i A', 'Time format displayed in the opening hours setting panel in your admin area. Must match formatting rules at http://amsul.ca/pickadate.js/time.htm#formats', 'business-profile' ),
				'date_format'	=> _x( 'mmmm d, yyyy', 'Date format displayed in the opening hours setting panel in your admin area. Must match formatting rules at http://amsul.ca/pickadate.js/date.htm#formatting-rules', 'business-profile' ),
				'disable_weeks'	=> true,
				'disable_date'	=> true,
				'strings'		=> array(
					'add_rule'			=> __( 'Add another opening time', 'business-profile' ),
					'weekly'			=> _x( 'Weekly', 'Format of a scheduling rule', 'business-profile' ),
					'monthly'			=> _x( 'Monthly', 'Format of a scheduling rule', 'business-profile' ),
					'date'				=> _x( 'Date', 'Format of a scheduling rule', 'business-profile' ),
					'weekdays'			=> _x( 'Days of the week', 'Label for selecting days of the week in a scheduling rule', 'business-profile' ),
					'month_weeks'		=> _x( 'Weeks of the month', 'Label for selecting weeks of the month in a scheduling rule', 'business-profile' ),
					'date_label'		=> _x( 'Date', 'Label to select a date for a scheduling rule', 'business-profile' ),
					'time_label'		=> _x( 'Time', 'Label to select a time slot for a scheduling rule', 'business-profile' ),
					'allday'			=> _x( 'All day', 'Label to set a scheduling rule to last all day', 'business-profile' ),
					'start'				=> _x( 'Start', 'Label for the starting time of a scheduling rule', 'business-profile' ),
					'end'				=> _x( 'End', 'Label for the ending time of a scheduling rule', 'business-profile' ),
					'set_time_prompt'	=> _x( 'All day long. Want to %sset a time slot%s?', 'Prompt displayed when a scheduling rule is set without any time restrictions', 'business-profile' ),
					'toggle'			=> _x( 'Open and close this rule', 'Toggle a scheduling rule open and closed', 'business-profile' ),
					'delete'			=> _x( 'Delete rule', 'Delete a scheduling rule', 'business-profile' ),
					'delete_schedule'	=> __( 'Delete scheduling rule', 'business-profile' ),
					'never'				=> _x( 'Never', 'Brief default description of a scheduling rule when no weekdays or weeks are included in the rule', 'business-profile' ),
					'weekly_always'	=> _x( 'Every day', 'Brief default description of a scheduling rule when all the weekdays/weeks are included in the rule', 'business-profile' ),
					'monthly_weekdays'	=> _x( '%s on the %s week of the month', 'Brief default description of a scheduling rule when some weekdays are included on only some weeks of the month. %s should be left alone and will be replaced by a comma-separated list of days and weeks in the following format: M, T, W on the first, second week of the month', 'business-profile' ),
					'monthly_weeks'		=> _x( '%s week of the month', 'Brief default description of a scheduling rule when some weeks of the month are included but all or no weekdays are selected. %s should be left alone and will be replaced by a comma-separated list of weeks in the following format: First, second week of the month', 'business-profile' ),
					'all_day'			=> _x( 'All day', 'Brief default description of a scheduling rule when no times are set', 'business-profile' ),
					'before'			=> _x( 'Ends at', 'Brief default description of a scheduling rule when an end time is set but no start time. If the end time is 6pm, it will read: Ends at 6pm', 'business-profile' ),
					'after'				=> _x( 'Starts at', 'Brief default description of a scheduling rule when a start time is set but no end time. If the start time is 6pm, it will read: Starts at 6pm', 'business-profile' ),
					'separator'			=> _x( '&mdash;', 'Separator between times of a scheduling rule', 'business-profile' ),
				),
			)
		);

		$sap->add_section(
			'bpfwp-settings',
			array(
				'id'            => 'bpfwp-display',
				'title'         => __( 'Display', 'business-profile' ),
			)
		);

		$sap->add_setting(
			'bpfwp-settings',
			'bpfwp-display',
			'html',
			array(
				'id'			=> 'shortcode',
				'title'			=> __( 'Shortcode', 'business-profile' ),
				'description'	=> '',
				'html'			=> '<div><code>[contact-card]</code></div><p class="description">' . sprintf( __( 'Paste this shortcode into any page or post to display your contact details. Learn about %sall of the attributes%s in the documentation.', 'business-profile' ), '<a href="' . BPFWP_PLUGIN_URL . DIRECTORY_SEPARATOR . 'docs#shortcode">', '</a>' ) . ' </p>',
			)
		);

		$sap = apply_filters( 'bpfwp_settings_page', $sap );

		$sap->add_admin_menus();

	}

}
} // endif;
