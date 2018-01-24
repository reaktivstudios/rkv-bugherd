<?php
/**
 * Rkv Admin Setting Field class.
 *
 * @package Rkv\Admin
 */

/**
 * Outputs setting fields.
 *
 * @package Rkv\Admin
 */
class Rkv_Admin_Setting_Field {
	/**
	 * The setting args.
	 *
	 * @var array
	 */
	public $setting = array();

	/**
	 * Rkv_Admin_Setting_Field constructor.
	 *
	 * @param array $setting The setting args.
	 */
	public function __construct( $setting = array() ) {
		$this->setting = $setting;
	}

	/**
	 * Does the field output followed by the description.
	 */
	public function do_field() {
		$field_callback = isset( $this->setting['type'] ) && method_exists( $this, $this->setting['type'] ) ? $this->setting['type'] : 'text';

		$this->$field_callback();

		if ( ! empty( $this->setting['description'] ) ) {
			printf( '<p>%s</p>', esc_html( $this->setting['description'] ) );
		}
	}

	/**
	 * Outputs text field.
	 */
	public function text() {
		printf(
			'<input type="text" id="%1$s" name="%2$s" value="%3$s" />',
			esc_attr( $this->get_name() ),
			esc_attr( $this->get_id() ),
			esc_attr( $this->get_value() )
		);
	}

	/**
	 * Outputs text area.
	 */
	public function textarea() {
		printf(
			'<textarea rows="10" cols="50" class="large-text code" id="%1$s" name="%2$s">%3$s</textarea>',
			esc_attr( $this->get_name() ),
			esc_attr( $this->get_id() ),
			esc_html( $this->get_value() )
		);
	}

	/**
	 * Outputs checkbox.
	 */
	public function checkbox() {
		printf(
			'<input type="checkbox" id="%1$s" name="%2$s" value="1" %3$s />',
			esc_attr( $this->get_name() ),
			esc_attr( $this->get_id() ),
			checked( 1, $this->get_value(), false )
		);
	}

	/**
	 * Outputs checkbox.
	 */
	public function multicheck() {
		if ( empty( $this->setting['options'] ) || ! is_array( $this->setting['options'] ) ) {
			return;
		}

		$opts = (array) $this->get_value();

		foreach ( $this->setting['options'] as $value => $label ) {
			printf(
				'<label for="%1$s-%4$s"><input type="radio" id="%1$s-%4$s" name="%2$s[%4$s]" value="%4$s" %3$s /> %5$s</label><br />',
				esc_attr( $this->get_name() ),
				esc_attr( $this->get_id() ),
				empty( $opts[ esc_attr( $value ) ] ) ? '' : 'checked="checked"',
				esc_attr( $value ),
				esc_html( $label )
			);
		}
	}

	/**
	 * Outputs select field.
	 */
	public function select() {
		printf(
			'<select id="%1$s" name="%2$s">',
			esc_attr( $this->get_name() ),
			esc_attr( $this->get_id() ),
			checked( 1, $this->get_value(), false )
		);

		$this->do_options( $this->setting['options'] );

		echo '</select>';
	}

	/**
	 * Outputs option HTML.
	 *
	 * If the keyvalue is an array, the key is used as an optgroup label and the value is recursively passed to this method.
	 *
	 * @param array $options The options.
	 */
	public function do_options( $options ) {
		foreach ( $options as $value => $label ) {
			if ( is_array( $label ) ) {
				printf( '<optgroup label="%s">', esc_attr( $value ) );
				$this->do_options( $label );
				echo '</optgroup>';
			} else {
				printf(
					'<option value="%1$s" %2$s>%3$s</option>',
					esc_attr( $value ),
					selected( esc_attr( $value ), $this->get_value(), false ),
					esc_html( $label )
				);
			}
		}
	}

	/**
	 * Outputs radio options.
	 *
	 * The `options` key is required in the $setting.
	 */
	public function radio() {
		if ( empty( $this->setting['options'] ) || ! is_array( $this->setting['options'] ) ) {
			return;
		}

		foreach ( $this->setting['options'] as $value => $label ) {
			printf(
				'<label for="%1$s-%4$s"><input type="radio" id="%1$s-%4$s" name="%2$s" value="%4$s" %3$s /> %5$s</label><br />',
				esc_attr( $this->get_name() ),
				esc_attr( $this->get_id() ),
				checked( esc_attr( $value ), $this->get_value(), false ),
				esc_attr( $value ),
				esc_html( $label )
			);
		}
	}

	/**
	 * Gets the name field.
	 *
	 * @return string The name field.
	 */
	public function get_name() {
		return $this->setting['name'];
	}
	/**
	 * Gets the id field.
	 *
	 * @return string The id field.
	 */
	public function get_id() {
		return $this->setting['id'];
	}
	/**
	 * Gets the field value.
	 *
	 * @return string The field value.
	 */
	public function get_value() {
		return get_option( $this->setting['name'] );
	}
}
