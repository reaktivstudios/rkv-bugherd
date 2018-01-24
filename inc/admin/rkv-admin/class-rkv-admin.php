<?php
/**
 * Rkv Admin class.
 *
 * @package Rkv\Admin
 */

/**
 * Base class to create menus and settings pages.
 *
 * @package Rkv\Admin
 */
class Rkv_Admin {

	/**
	 * The Rkv_Admin_Menu object.
	 *
	 * @var Rkv_Admin_Menu
	 */
	public $admin_menu;

	/**
	 * The Rkv_Admin_Menu object.
	 *
	 * @var Rkv_Admin_Menu
	 */
	public $admin_page;

	/**
	 * The Rkv_Admin_Settings object.
	 *
	 * @var Rkv_Admin_Settings
	 */
	public $settings;

	/**
	 * The Rkv_Admin_Setting_Sections object.
	 *
	 * @var Rkv_Admin_Setting_Sections
	 */
	public $setting_sections;

	/**
	 * Call this method in a subclass constructor to create an admin menu and settings page.
	 *
	 * @since 1.8.0
	 *
	 * @param string $page_id          ID of the admin menu and settings page.
	 * @param array  $menu_ops         Optional. Config options for admin menu(s). Default is empty array.
	 * @param array  $page_ops         Optional. Config options for settings page. Default is empty array.
	 * @param array  $settings         Optional. Any settings that will be registered and added to the page.
	 * @param array  $setting_sections Optional. Any setting sections that will be registered and added to the page.
	 */
	public function __construct( $page_id = '', $menu_ops = array(), $page_ops = array(), $settings = array(), $setting_sections = array() ) {
		if ( ! $page_id ) {
			return;
		}

		$this->setting_sections = new Rkv_Admin_Setting_Sections( $page_id, $setting_sections );
		$this->settings         = new Rkv_Admin_Settings( $page_id, $settings );
		$this->admin_page       = new Rkv_Admin_Page( $page_id, $page_ops );
		$this->admin_menu       = new Rkv_Admin_Menu( $page_id, $menu_ops, array( $this->admin_page, 'page' ) );
	}

	/**
	 * Gets the pagehook.
	 *
	 * @return string
	 */
	function get_pagehook() {
		return $this->admin_menu->get_pagehook();
	}

}
