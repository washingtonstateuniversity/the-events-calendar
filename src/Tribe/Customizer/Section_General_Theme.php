<?php
// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * The Events Calendar Customizer Section Class
 * General Theme
 *
 * @package Events Pro
 * @subpackage Customizer
 * @since 4.0
 */
final class Tribe__Events__Pro__Customizer__Section_General_Theme {

	/**
	 * Private variable holding the class Instance
	 *
	 * @since 4.0
	 *
	 * @access private
	 * @var Tribe__Events__Pro__Customizer__Section_General_Theme
	 */
	private static $instance;

	/**
	 * Method to return the Private instance of the Class
	 *
	 * @since 4.0
	 *
	 * @access public
	 * @return Tribe__Events__Pro__Customizer__Section_General_Theme
	 */
	public static function instance() {
		// This also prevents double instancing the class
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * ID of the section
	 *
	 * @since 4.0
	 *
	 * @access public
	 * @var string
	 */
	public $ID = 'general_theme';

	/**
	 * Loads the Hooks
	 *
	 * @since  4.0
	 *
	 * @see  self::instance()
	 * @access private
	 *
	 * @return void
	 */
	private function __construct() {
		// Hook the Register methods
		add_action( 'tribe_events_pro_customizer_register_' . $this->ID . '_settings', array( $this, 'settings' ), 10, 2 );
		add_filter( 'tribe_events_pro_customizer_pre_sections', array( $this, 'register' ), 10, 2 );

		// Append this section CSS template
		add_filter( 'tribe_events_pro_customizer_css_template', array( $this, 'get_css_template' ), 10 );
		add_filter( 'tribe_events_pro_customizer_section_' . $this->ID . '_defaults', array( $this, 'get_defaults' ), 10 );
	}

	/**
	 * Grab the CSS rules template
	 *
	 * @return string
	 */
	public function get_css_template( $template ) {
		$customizer = Tribe__Events__Pro__Customizer__Main::instance();

		if ( $customizer->has_option( $this->ID, 'accent_color' ) ) {
			$template .= '
				.tribe-events-calendar td.tribe-events-present div[id*="tribe-events-daynum-"],
				#tribe_events_filters_wrapper input[type=submit],
				.tribe-events-button,
				#tribe-events .tribe-events-button,
				.tribe-events-button.tribe-inactive,
				#tribe-events .tribe-events-button:hover,
				.tribe-events-button:hover,
				.tribe-events-button.tribe-active:hover {
					background-color: <%= general_theme.accent_color %>;
				}

				#tribe-events-content .tribe-events-tooltip h4,
				#tribe_events_filters_wrapper .tribe_events_slider_val,
				.single-tribe_events a.tribe-events-ical,
				.single-tribe_events a.tribe-events-gcal {
					color: <%= general_theme.accent_color %>;
				}

				.tribe-grid-allday .tribe-events-week-allday-single,
				.tribe-grid-body .tribe-events-week-hourly-single,
				.tribe-grid-allday .tribe-events-week-allday-single:hover,
				.tribe-grid-body .tribe-events-week-hourly-single:hover {
					background-color: <%= general_theme.accent_color %>;
					border-color: rgba(0, 0, 0, 0.3);
				}
			';
		}

		return $template;
	}

	/**
	 * A way to apply filters when getting the Customizer options
	 * @return array
	 */
	public function get_defaults() {
		$defaults = array(
			'base_color_scheme' => 'light',
			'accent_color' => '#21759b',
		);

		return $defaults;
	}

	/**
	 * Get the Default Value requested
	 * @return mixed
	 */
	public function get_default( $key ) {
		$defaults = $this->get_defaults();

		if ( ! isset( $defaults[ $key ] ) ) {
			return null;
		}

		return $defaults[ $key ];
	}


	/**
	 * Register this Section
	 *
	 * @param  array  $sections   Array of Sections
	 * @param  Tribe__Events__Pro__Customizer__Main $customizer Our internal Cutomizer Class Instance
	 *
	 * @return array  Return the modified version of the Section array
	 */
	public function register( $sections, $customizer ) {
		$sections[ $this->ID ] = array(
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'title'       => esc_html__( 'General Theme', 'tribe-events-calendar-pro' ),
			'description' => esc_html__( 'Global configurations for the styling of The Events Calendar', 'tribe-events-calendar-pro' ),
		);

		return $sections;
	}

	/**
	 * Create the Fields/Settings for this sections
	 *
	 * @param  WP_Customize_Section $section The WordPress section instance
	 * @param  WP_Customize_Manager $manager [description]
	 *
	 * @return void
	 */
	public function settings( WP_Customize_Section $section, WP_Customize_Manager $manager ) {
		$customizer = Tribe__Events__Pro__Customizer__Main::instance();


		$manager->add_setting(
			$customizer->get_setting_name( 'accent_color', $section ),
			array(
				'default'              => $this->get_default( 'accent_color' ),
				'type'                 => 'option',

				'sanitize_callback'    => 'sanitize_hex_color',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
			)
		);

		$manager->add_control(
			new WP_Customize_Color_Control(
				$manager,
				$customizer->get_setting_name( 'accent_color', $section ),
				array(
					'label'   => esc_html__( 'Accent Color', 'tribe-events-calendar-pro' ),
					'section' => $section->id,
				)
			)
		);
	}

}