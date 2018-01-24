<?php
/**
 * Rkv Admin Menu class.
 *
 * @package Rkv\Admin
 */

/**
 * Adds admin menu items.
 *
 * @package Rkv\Admin
 */
class Rkv_Admin_Menu {

	/**
	 * Name of the page hook when the menu is registered.
	 *
	 * @var string Page hook
	 */
	public $pagehook;

	/**
	 * ID of the admin menu and settings page.
	 *
	 * @var string
	 */
	public $page_id;

	/**
	 * Associative array of configuration options for the admin menu(s).
	 *
	 * @var array
	 */
	public $menu_ops;

	/**
	 * Callback for the page.
	 *
	 * @var callable
	 */
	public $callback;

	/**
	 * Adds menu items to the admin menu.
	 *
	 * @param string   $page_id  ID of the admin menu and settings page.
	 * @param array    $menu_ops Optional. Config options for admin menu(s). Default is empty array.
	 * @param callable $callback Optional. The page output callback.
	 */
	public function __construct( $page_id = '', $menu_ops = array(), $callback = null ) {
		if ( empty( $page_id ) || empty( $menu_ops ) || empty( $callback ) ) {
			return;
		}

		$this->register_menu_item( $page_id, $menu_ops, $callback );
	}

	/**
	 * Adds menu items to the admin menu.
	 *
	 * @param string   $page_id  ID of the admin menu and settings page.
	 * @param array    $menu_ops Optional. Config options for admin menu(s). Default is empty array.
	 * @param callable $callback Optional. The page output callback.
	 */
	public function register_menu_item( $page_id, $menu_ops, $callback ) {
		if ( empty( $page_id ) || empty( $menu_ops ) || empty( $callback ) ) {
			return;
		}

		$this->page_id  = $page_id;
		$this->menu_ops = (array) $menu_ops;
		$this->callback = $callback;

		if ( empty( $this->menu_ops ) ) {
			return;
		}

		// Create the menu(s). Conditional logic happens within the separate methods.
		add_action( 'admin_menu', array( $this, 'maybe_add_main_menu' ), 5 );
		add_action( 'admin_menu', array( $this, 'maybe_add_first_submenu' ), 5 );
		add_action( 'admin_menu', array( $this, 'maybe_add_submenu' ) );
		add_action( 'admin_menu', array( $this, 'load_assets' ) );
	}

	/**
	 * Possibly create a new top level admin menu.
	 */
	public function maybe_add_main_menu() {

		// Maybe add a menu separator.
		if ( isset( $this->menu_ops['main_menu']['sep'] ) ) {
			$sep = wp_parse_args(
				$this->menu_ops['main_menu']['sep'],
				array(
					'sep_position'   => '',
					'sep_capability' => '',
				)
			);

			if ( $sep['sep_position'] && $sep['sep_capability'] ) {
				$GLOBALS['menu'][ $sep['sep_position'] ] = array( '', $sep['sep_capability'], 'separator', '', 'rkv-separator wp-menu-separator' );
			}
		}

		// Maybe add main menu.
		if ( isset( $this->menu_ops['main_menu'] ) && is_array( $this->menu_ops['main_menu'] ) ) {
			$menu = wp_parse_args(
				$this->menu_ops['main_menu'],
				array(
					'page_title' => '',
					'menu_title' => '',
					'capability' => 'edit_theme_options',
					'icon_url'   => '',
					'position'   => '',
				)
			);

			$this->pagehook = add_menu_page( $menu['page_title'], $menu['menu_title'], $menu['capability'], $this->page_id, $this->callback, $menu['icon_url'], $menu['position'] );
		}
	}

	/**
	 * Possibly create the first submenu item.
	 *
	 * Because the main menu and first submenu item are usually linked, if you
	 * don't create them at the same time, something can sneak in between the
	 * two, specifically custom post type menu items that are assigned to the
	 * custom top-level menu.
	 *
	 * Plus, maybe_add_first_submenu takes the guesswork out of creating a
	 * submenu of the top-level menu you just created. It's a shortcut of sorts.
	 */
	public function maybe_add_first_submenu() {

		// Maybe add first submenu.
		if ( isset( $this->menu_ops['first_submenu'] ) && is_array( $this->menu_ops['first_submenu'] ) ) {
			$menu = wp_parse_args(
				$this->menu_ops['first_submenu'],
				array(
					'page_title' => '',
					'menu_title' => '',
					'capability' => 'edit_theme_options',
				)
			);

			$this->pagehook = add_submenu_page( $this->page_id, $menu['page_title'], $menu['menu_title'], $menu['capability'], $this->page_id, $this->callback );
		}
	}

	/**
	 * Possibly create a submenu item.
	 */
	public function maybe_add_submenu() {

		// Maybe add submenu.
		if ( isset( $this->menu_ops['submenu'] ) && is_array( $this->menu_ops['submenu'] ) ) {
			$menu = wp_parse_args(
				$this->menu_ops['submenu'],
				array(
					'parent_slug' => '',
					'page_title'  => '',
					'menu_title'  => '',
					'capability'  => 'edit_theme_options',
				)
			);

			$this->pagehook = add_submenu_page( $menu['parent_slug'], $menu['page_title'], $menu['menu_title'], $menu['capability'], $this->page_id, $this->callback );
		}
	}

	/**
	 * Add load-pagehook action for enqueueing scripts and styles.
	 */
	public function load_assets() {
		add_action( "load-{$this->pagehook}", array( $this, 'assets' ) );
	}

	/**
	 * Adds the admin enqueue action.
	 */
	public function assets() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	/**
	 * Adds a dynamic hook for the page_id scripts.
	 */
	public function enqueue() {
		/**
		 * Dynamic hook added only on the $page_id.
		 */
		do_action( "rkv_admin_enqueue_{$this->page_id}_scripts" );
	}

	/**
	 * Gets the pagehook.
	 *
	 * @return string
	 */
	function get_pagehook() {
		return $this->pagehook;
	}

}
