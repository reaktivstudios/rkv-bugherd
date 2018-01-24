<?php
/**
 * Rkv Admin Settings class.
 *
 * @package Rkv\Admin
 */

/**
 * Base class to create menus and settings pages.
 *
 * @package Rkv\Admin
 */
class Rkv_Admin_Settings {

	/**
	 * ID of the admin menu and settings page.
	 *
	 * @var string
	 */
	public $page_id;

	/**
	 * The option group.
	 *
	 * @var string
	 */
	public $option_group;

	/**
	 * Any settings that will be registered and added to the page.
	 *
	 * @var array
	 */
	public $settings;

	/**
	 * Any setting sections that will be registered and added to the page.
	 *
	 * @var array
	 */
	public $setting_sections;

	/**
	 * Associative array (field name => values) for the default settings on this
	 * admin page.
	 *
	 * @var array
	 */
	public $default_settings;

	/**
	 * The sanitizer for use with the sanitizer callback.
	 *
	 * @var Rkv_Admin_Sanitize
	 */
	public $sanitizer;

	/**
	 * Handles Settings for Admin Page.
	 *
	 * @param string $page_id  ID of the admin menu and settings page.
	 * @param array  $settings Optional. Any settings that will be registered and added to the page.
	 */
	public function __construct( $page_id = '', $settings = array() ) {
		if ( empty( $page_id ) || empty( $settings ) ) {
			return;
		}

		$this->set_settings( $page_id, $settings );
	}

	/**
	 * Sets the settings.
	 *
	 * @param string $page_id  The page ID.
	 * @param array  $settings The settings to register, sanitize, and display.
	 */
	public function set_settings( $page_id, $settings ) {
		$this->page_id  = $page_id;
		$this->settings = $settings;

		if ( empty( $page_id ) || empty( $this->settings ) ) {
			return;
		}

		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Registers settings.
	 */
	public function register_settings() {
		if ( empty( $this->settings ) ) {
			return;
		}

		foreach ( $this->settings as $key => $setting ) {
			$this->register_default_setting( $key, $setting );
			$this->register_setting( $key );
			$this->add_settings_field( $key, $setting );
		}
	}

	/**
	 * @param string $key     Id for the option.
	 * @param array  $setting The setting arguments.
	 */
	public function register_default_setting( $key, $setting = array() ) {
		$this->default_settings[ $key ] = empty( $setting['default'] ) ? '' : $setting['default'];
	}

	/**
	 * Registers setting field.
	 *
	 * @param string $key The id for the setting field.
	 */
	public function register_setting( $key ) {
		register_setting( $this->page_id, $key, array( $this, 'sanitize' ) );
	}

	/**
	 * Adds the setting field.
	 *
	 * @param string $key     ID for the option.
	 * @param array  $setting Setting arguments.
	 */
	public function add_settings_field( $key, $setting = array() ) {
		$title = empty( $setting['label'] ) ? '' : $setting['label'];

		if ( empty( $title ) ) {
			return; // Title is required.
		}

		$section = isset( $setting['section'] ) ? $setting['section'] : 'default';

		$setting['id']        = empty( $setting['id'] ) ? $key : $setting['id'];
		$setting['name']      = empty( $setting['name'] ) ? $key : $setting['name'];
		$setting['label_for'] = $setting['id'];

		add_settings_field( $key, $title, array( $this, 'setting_field_callback' ), $this->page_id, $section, $setting );
	}

	/**
	 * Sanitizes option before save.
	 *
	 * @param mixed $new_value The value being sanitized.
	 *
	 * @return mixed
	 */
	public function sanitize( $new_value ) {
		$current_filter = current_filter();
		$option         = str_replace( 'sanitize_option_', '', $current_filter );
		$setting        = empty( $this->settings[ $option ] ) ? array() : $this->settings[ $option ];

		$sanitize_callback = $this->get_sanitize_callback( $setting );

		return call_user_func( $sanitize_callback, $new_value, $setting );
	}

	/**
	 * Sets the sanitizer property.
	 */
	public function set_sanitizer() {
		if ( empty( $this->sanitizer ) ) {
			$this->sanitizer = new Rkv_Admin_Sanitize();
		}
	}

	/**
	 * Gets the sanitize callback.
	 *
	 * @param $setting
	 *
	 * @return callable The sanitize callback.
	 */
	public function get_sanitize_callback( $setting ) {
		if ( ! empty( $setting['sanitize_callback'] ) && is_callable( $setting['sanitize_callback'] ) ) {
			return $setting['sanitize_callback'];
		}

		$sanitize_filter = empty( $setting['sanitize'] ) ? 'text_field' : $setting['sanitize'];

		$this->set_sanitizer();

		$available_filters = $this->sanitizer->get_available_filters();

		if ( ! empty( $available_filters[ $sanitize_filter ] ) && is_callable( $available_filters[ $sanitize_filter ] ) ) {
			return $available_filters[ $sanitize_filter ];
		}

		return 'sanitize_text_field';
	}

	/**
	 * Callback for the setting field, invokes the setting field object.
	 *
	 * @param array $setting The setting parameters.
	 */
	public function setting_field_callback( $setting ) {
		$setting = new Rkv_Admin_Setting_Field( $setting );
		$setting->do_field();

		unset( $setting );
	}

}
