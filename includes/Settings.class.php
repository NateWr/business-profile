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
				'version'       => '2.0.a.7',
				'lib_url'       => BPFWP_PLUGIN_URL . '/lib/simple-admin-pages/',
			)
		);

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
					'EducationalOrganization'		=> 'Educational Organization',
					'GovernmentOrganization'		=> 'Government Organization',
					'LocalBusiness'				=> 'Local Business',
					'AnimalShelter'				=> '- Animal Shelter',
					'AutomotiveBusiness'			=> '- Automotive Business',
					'ChildCare'				=> '- Child Care',
					'DryCleaningOrLaundry'		=> '- Dry Cleaning or Laundry',
					'EmergencyService'			=> '- Emergency Service',
					'EmploymentAgency'			=> '- Employment Agency',
					'EntertainmentBusiness'		=> '- Entertainment Business',
					'FinancialService'			=> '- Financial Service',
					'FoodEstablishment'			=> '- Food Establishment',
					'GovernmentOffice'			=> '- Government Office',
					'HealthAndBeautyBusiness'		=> '- Health and Beauty Business',
					'HomeAndConstructionBusiness'	=> '- Home and Construction Business',
					'HVACBusiness'				=> '-- HVAC Business',
					'InternetCafe'				=> '- Internet Cafe',
					'Library'				=> '- Library',
					'LodgingBusiness'			=> '- Lodging Business',
					'MedicalOrganization'			=> '- Medical Organization',
					'RadioStation'				=> '- Radio Station',
					'RealEstateAgent'			=> '- Real Estate Agent',
					'RecyclingCenter'			=> '- Recycling Center',
					'SelfStorage'				=> '- Self Storage',
					'SportsActivityLocation'		=> '- Sports Activity Location',
					'Store'					=> '- Store',
					'TouristInformationCenter'		=> '- Tourist Information Center',
					'TravelAgency'				=> '- Travel Agency',
					'NGO'					=> 'NGO',
					'PerformingGroup'			=> 'PerformingGroup',
					'SportsTeam'				=> 'SportsTeam',
				),
			)
		);

		$sap->add_section(
			'bpfwp-settings',
			array(
				'id'            => 'rtb-contact',
				'title'         => __( 'Contact Information', BPFWP_TEXTDOMAIN ),
			)
		);

		$sap->add_setting(
			'bpfwp-settings',
			'rtb-contact',
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
			'rtb-contact',
			'address',
			array(
				'id'			=> 'address',
				'title'			=> __( 'Address', BPFWP_TEXTDOMAIN ),
			)
		);

		$sap->add_setting(
			'bpfwp-settings',
			'rtb-contact',
			'text',
			array(
				'id'            => 'phone',
				'title'         => __( 'Phone', BPFWP_TEXTDOMAIN ),
			)
		);

		$sap->add_setting(
			'bpfwp-settings',
			'rtb-contact',
			'text',
			array(
				'id'            => 'phone-formatted',
				'title'         => __( 'Formatted Phone Number', BPFWP_TEXTDOMAIN ),
				'description'   => __( 'Enter phone number in the format of +18005551212 to be used for mobile phone call links.', BPFWP_TEXTDOMAIN ),
			)
		);
		$sap->add_setting(
			'bpfwp-settings',
			'rtb-contact',
			'text',
			array(
				'id'            => 'fax',
				'title'         => __( 'Fax', BPFWP_TEXTDOMAIN ),
			)
		);

		$sap->add_setting(
			'bpfwp-settings',
			'rtb-contact',
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
			'rtb-contact',
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
				'id'            => 'rtb-schedule',
				'title'         => __( 'Schedule', BPFWP_TEXTDOMAIN ),
			)
		);

		$sap->add_setting(
			'bpfwp-settings',
			'rtb-schedule',
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
			)
		);

		$sap = apply_filters( 'bpfwp_settings_page', $sap );

		$sap->add_admin_menus();

	}

}
} // endif;
