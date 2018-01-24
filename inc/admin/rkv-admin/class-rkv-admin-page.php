<?php
/**
 * Rkv Admin Page class.
 *
 * @package Rkv\Admin
 */

/**
 * Creates the page output.
 *
 * @package Rkv\Admin
 */
class Rkv_Admin_Page {

	/**
	 * ID of the admin menu and settings page.
	 *
	 * @var string
	 */
	public $page_id;

	/**
	 * Associative array of configuration options for the settings page.
	 *
	 * @var array
	 */
	public $page_ops;

	/**
	 * Adds the settings page.
	 *
	 * @param string $page_id      ID of the admin menu and settings page.
	 * @param array  $page_ops     Optional. Config options for settings page. Default is empty array.
	 */
	public function __construct( $page_id = '', array $page_ops = array() ) {
		if ( empty( $page_id ) || empty( $page_ops ) ) {
			return;
		}

		$this->register_page( $page_id, $page_ops );
	}

	/**
	 * Registers the settings page.
	 *
	 * @param string $page_id      ID of the admin menu and settings page.
	 * @param array  $page_ops     Optional. Config options for settings page. Default is empty array.
	 */
	public function register_page( $page_id, $page_ops ) {
		$this->page_id = $page_id;

		if ( ! $this->page_id ) {
			return;
		}

		$this->page_ops = $this->page_ops ? $this->page_ops : (array) $page_ops;

		$this->page_ops = wp_parse_args(
			$this->page_ops,
			array(
				'type'             => 'form',
				'description'      => '',
				'save_button_text' => __( 'Save Changes', 'rkv-admin' ),
			)
		);
	}

	/**
	 * Default page callback.
	 *
	 * Generates a standard WordPress page.
	 * If the page_ops['type'] is "form" this will output the form markup.
	 *
	 * Includes a "before" and "after" hook which is fired before and after the page form.
	 */
	public function page() {
		printf( '<div class="wrap"><h1>%s</h1>', esc_html( get_admin_page_title() ) );

		if ( ! empty( $this->page_ops['description'] ) ) {
			printf( '<p>%s</p>', esc_html( $this->page_ops['description'] ) );
		}

		/**
		 * Runs at the top of the page for the dynamically created page.
		 *
		 * @param array $page_ops {
		 *     Page options.
		 *
		 *     @type string $type             The type of page.
		 *     @type string $title            Page Title.
		 *     @type string $save_button_text The save button text.
		 *     @type string $option_group     Specified option group.
		 * }
		 */
		do_action( "rkv_before_page_{$this->page_id}", $this->page_ops );

		if ( 'form' === $this->page_ops['type'] ) {
			echo '<form method="POST" action="options.php">';
			settings_fields( $this->page_id );
			do_settings_sections( $this->page_id );
			submit_button( $this->page_ops['save_button_text'] );
			echo '</form>';
		}

		/**
		 * Runs at the bottom of the page for the dynamically created page.
		 *
		 * @param array $page_ops see rkv_before_page_{$this->page_id}.
		 */
		do_action( "rkv_after_page_{$this->page_id}", $this->page_ops );

		echo '</div>';
	}

}
