<?php
/**
 * Rkv Admin Setting Sections class.
 *
 * @package Rkv\Admin
 */

/**
 * Adds new section.
 *
 * @package Rkv\Admin
 */
class Rkv_Admin_Setting_Sections {

	/**
	 * ID of the admin menu and settings page.
	 *
	 * @var string
	 */
	public $page_id;

	/**
	 * Any setting sections that will be registered and added to the page.
	 *
	 * @var array
	 */
	public $setting_sections;

	/**
	 * Invokes the set_setting_sections() method.
	 *
	 * @param string $page_id          ID of the admin menu and settings page.
	 * @param array  $setting_sections Optional. Any setting sections that will be registered and added to the page.
	 */
	public function __construct( $page_id = '', $setting_sections = array() ) {
		$this->set_setting_sections( $page_id, $setting_sections );
	}

	/**
	 * Prepares the setting sections. Must be invoked before admin_init.
	 *
	 * @param string $page_id          ID of the admin menu and settings page.
	 * @param array  $setting_sections Optional. Any setting sections that will be registered and added to the page.
	 */
	public function set_setting_sections( $page_id, $setting_sections ) {
		$this->page_id          = $page_id;
		$this->setting_sections = wp_parse_args(
			$setting_sections, array(
				'default' => array(),
			)
		);

		add_action( 'admin_init', array( $this, 'register_setting_sections' ) );
	}

	/**
	 * Registers setting sections.
	 *
	 * If set_setting_sections is used after admin_init, this must be manually called.
	 */
	public function register_setting_sections() {
		if ( empty( $this->setting_sections ) ) {
			return;
		}

		foreach ( $this->setting_sections as $id => $section ) {
			$title    = isset( $section['title'] ) ? $section['title'] : '';
			$callback = isset( $section['callback'] ) ? $section['callback'] : array( $this, 'section_callback' );

			add_settings_section( $id, $title, $callback, $this->page_id );
		}
	}

	/**
	 * Default section callback. Can be overriden with the section argument.
	 *
	 * @param array $arg The section callback args.
	 */
	public function section_callback( $arg ) {
		$id          = isset( $arg['id'] ) ? $arg['id'] : '';
		$description = isset( $this->setting_sections[ $id ]['description'] ) ? $this->setting_sections[ $id ]['description'] : '';

		if ( ! empty( $description ) ) {
			printf( '<p>%s</p>', esc_html( $description ) );
		}
	}

}
