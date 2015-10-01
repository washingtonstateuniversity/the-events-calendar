<?php
// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * The Events Calendar Customizer Section Class
 * Day List View
 *
 * @package Events Pro
 * @subpackage Customizer
 * @since 4.0
 */
final class Tribe__Events__Pro__Customizer__Section_Day_List_View {

	/**
	 * Private variable holding the class Instance
	 *
	 * @since 4.0
	 *
	 * @access private
	 * @var Tribe__Events__Pro__Customizer__Section_Day_List_View
	 */
	private static $instance;

	/**
	 * Method to return the Private instance of the Class
	 *
	 * @since 4.0
	 *
	 * @access public
	 * @return Tribe__Events__Pro__Customizer__Section_Day_List_View
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
	public $ID = 'day_list_view';

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
		add_action( 'tribe_events_customizer_register_' . $this->ID . '_settings', array( &$this, 'settings' ), 10, 2 );
		add_filter( 'tribe_events_customizer_pre_sections', array( &$this, 'register' ), 10, 2 );

		// Append this section CSS template
		add_filter( 'tribe_events_customizer_css_template', array( &$this, 'get_css_template' ), 10 );
		add_filter( 'tribe_events_customizer_section_' . $this->ID . '_defaults', array( &$this, 'get_defaults' ), 10 );

		// Create the Ghost Options
		add_filter( 'tribe_events_customizer_pre_get_option', array( &$this, 'filter_settings' ), 10, 2 );
	}

	/**
	 * Grab the CSS rules template
	 *
	 * @return string
	 */
	public function get_css_template( $template ) {
		$customizer = Tribe__Events__Pro__Customizer__Main::instance();

		if ( $customizer->has_option( $this->ID, 'calendar_datebar_color' ) ) {
			$template .= '
				.tribe-events-calendar div[id*="tribe-events-daynum-"],
				.tribe-events-calendar div[id*="tribe-events-daynum-"] a {
					background-color: <%= month_week_view.calendar_datebar_color %>;
				}
			';
		}

		if ( $customizer->has_option( $this->ID, 'calendar_header_color' ) ) {
			$template .= '
				.tribe-events-calendar thead th {
					background-color: <%= month_week_view.calendar_header_color %>;
					border-left-color: <%= month_week_view.calendar_header_color %>;
					border-right-color: <%= month_week_view.calendar_header_color %>;
				}
			';
		}

		if ( $customizer->has_option( $this->ID, 'calendar_highlight_color' ) ) {
			$template .= '
				.tribe-events-calendar td.tribe-events-present div[id*="tribe-events-daynum-"],
				.tribe-events-calendar td.tribe-events-present div[id*="tribe-events-daynum-"] > a {
					background-color: <%= month_week_view.calendar_highlight_color %>;
				}
			';
		}

		return $template;
	}

	public function filter_settings( $settings, $search ) {
		// Only Apply if getting the full options or Section
		if ( is_array( $search ) && count( $search ) > 1 ){
			return $settings;
		}

		if ( count( $search ) === 1 ){
			$settings = $this->create_ghost_settings( $settings );
		} else {
			$settings[ $this->ID ] = $this->create_ghost_settings( $settings[ $this->ID ] );
		}

		return $settings;
	}

	public function create_ghost_settings( $settings = array() ) {

		return $settings;
	}

	/**
	 * A way to apply filters when getting the Customizer options
	 * @return array
	 */
	public function get_defaults() {
		$defaults = array(
			'price_background_color' => '#eeeeee',
			'highlight_color' => '#21759b',
		);

		return $defaults;
	}

	/**
	 * Get the Default Value requested
	 * @return mixed
	 */
	public function get_default( $key ) {
		$defaults = $this->get_defaults();

		if ( ! isset( $defaults[ $key ] ) ){
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
			'priority'    => 40,
			'capability'  => 'edit_theme_options',
			'title'       => esc_html__( 'Day/List View', 'tribe-events-calendar-pro' ),
			'description' => esc_html__( 'Options selected here will override what was selected in the "General Theme" and "Global Elements" sections', 'tribe-events-calendar-pro' ),
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
			$customizer->get_setting_name( 'price_background_color', $section ),
			array(
				'default'              => $this->get_default( 'price_background_color' ),
				'type'                 => 'option',
				'transport'            => 'postMessage',

				'sanitize_callback'    => 'sanitize_hex_color',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
			)
		);

		$manager->add_control(
			new WP_Customize_Color_Control(
				$manager,
				$customizer->get_setting_name( 'price_background_color', $section ),
				array(
					'label'   => esc_html__( 'Price Background Color', 'tribe-events-calendar-pro' ),
					'section' => $section->id,
				)
			)
		);

		$manager->add_setting(
			$customizer->get_setting_name( 'highlight_color', $section ),
			array(
				'default'              => $this->get_default( 'highlight_color' ),
				'type'                 => 'option',
				'transport'            => 'postMessage',

				'sanitize_callback'    => 'sanitize_hex_color',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
			)
		);

		$manager->add_control(
			new WP_Customize_Color_Control(
				$manager,
				$customizer->get_setting_name( 'highlight_color', $section ),
				array(
					'label'   => esc_html__( 'Hightlight Color', 'tribe-events-calendar-pro' ),
					'section' => $section->id,
				)
			)
		);
	}


}