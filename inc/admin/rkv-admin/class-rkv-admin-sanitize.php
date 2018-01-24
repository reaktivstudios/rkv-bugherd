<?php
/**
 * Rkv Sanitize Class
 *
 * @package Rkv\Sanitizer
 */

/**
 * Settings sanitization class. Ensures saved values are of the expected type.
 *
 * @package Rkv\Sanitizer
 */
class Rkv_Admin_Sanitize {
	/**
	 * Array of sanitize methods for option keys
	 *
	 * array(
	 *     'option_1' => 'one_zero',
	 *     'option_2' => 'no_html',
	 * )
	 *
	 * @var array
	 */
	public $sanitize_keys = array();

	/**
	 * Return array of known sanitization filter types.
	 *
	 * Array can be filtered via 'rkv_available_sanitizer_filters' to let
	 * child themes and plugins add their own sanitization filters.
	 *
	 * @return array Keys of sanitization types, and values of the
	 *               filter function name as a callback.
	 */
	public function get_available_filters() {
		$default_filters = array(
			'one_zero'                => array( $this, 'one_zero' ),
			'absint'                  => array( $this, 'absint' ),
			'text_field'              => array( $this, 'text_field' ),
			'no_html'                 => array( $this, 'no_html' ),
			'safe_html'               => array( $this, 'safe_html' ),
			'unfiltered_or_safe_html' => array( $this, 'unfiltered_or_safe_html' ),
			'url'                     => array( $this, 'url' ),
			'email_address'           => array( $this, 'email_address' ),
			'in_array'                => array( $this, 'in_array' ),
		);

		/**
		 * Filter the available sanitization filter types.
		 *
		 * @param array $default_filters Array with keys of sanitization types, and values of the filter function name as a callback
		 */
		return apply_filters( 'rkv_available_sanitizer_filters', $default_filters );
	}

	/**
	 * Sanitize a value, via the sanitization filter type associated with an
	 * option.
	 *
	 * @param mixed  $new_value New value.
	 * @param string $option    Name of the option.
	 *
	 * @return mixed Filtered, or unfiltered value.
	 */
	public function sanitize_deep( $new_value, $option ) {
		if ( empty( $this->sanitize_keys ) ) {
			return $this->sanitize_deep_default( $new_value );
		}

		if ( is_array( $new_value ) ) {
			$ret_value = array();

			foreach ( $new_value as $key => $value ) {
				$ret_value[ $key ] = $this->sanitize_by_key( $value, $key );
			}

			return $ret_value;
		}

		return $this->sanitize_by_key( $new_value, $option );
	}

	/**
	 * Sanitizes the values using the default of text_field.
	 *
	 * @param mixed|array|string $new_value The new value.
	 *
	 * @return array|int
	 */
	public function sanitize_deep_default( $new_value ) {
		if ( is_array( $new_value ) ) {
			$ret_value = array();

			foreach ( $new_value as $key => $value ) {
				$ret_value[ $key ] = $this->text_field( $value );
			}

			return $ret_value;
		}

		return $this->text_field( $new_value );
	}

	/**
	 * Sanitizes input based on the value key.
	 *
	 * @param string $value The value to be sanitized.
	 * @param string $key   The sanitize method key.
	 *
	 * @return mixed
	 */
	public function sanitize_by_key( $value, $key ) {
		$sanitize_method = empty( $this->sanitize_keys[ $key ] ) ? 'text_field' : $this->sanitize_keys[ $key ];

		if ( method_exists( $this, $sanitize_method ) ) {
			return $this->$sanitize_method( $value );
		}

		return $this->text_field( $value );
	}

	/**
	 * Returns a 1 or 0, for all truthy / falsy values.
	 *
	 * Uses double casting. First, we cast to bool, then to integer.
	 *
	 * @param mixed $new_value Should ideally be a 1 or 0 integer passed in.
	 * @return int `1` or `0`.
	 */
	public function one_zero( $new_value ) {
		return (int) (bool) $new_value;
	}

	/**
	 * Returns a positive integer value.
	 *
	 * @param mixed $new_value Should ideally be a positive integer.
	 * @return int Positive integer.
	 */
	public function absint( $new_value ) {
		return absint( $new_value );
	}

	/**
	 * Returns a string that has been sanitized as a text field.
	 *
	 * @param mixed $new_value The new value.
	 * @return int Sanitized text field.
	 */
	public function text_field( $new_value ) {
		return sanitize_text_field( $new_value );
	}
	/**
	 * Removes HTML tags from string.
	 *
	 * @param string $new_value String, possibly with HTML in it.
	 * @return string String without HTML in it.
	 */
	public function no_html( $new_value ) {
		return strip_tags( $new_value );
	}

	/**
	 * Makes URLs safe
	 *
	 * @param string $new_value String, a URL, possibly unsafe.
	 * @return string String a safe URL.
	 */
	public function url( $new_value ) {
		return esc_url_raw( $new_value );
	}

	/**
	 * Makes Email Addresses safe, via sanitize_email()
	 *
	 * @param string $new_value String, an email address, possibly unsafe.
	 * @return string String a safe email address.
	 */
	public function email_address( $new_value ) {
		return sanitize_email( $new_value );
	}

	/**
	 * Removes unsafe HTML tags, via wp_kses_post().
	 *
	 * @param string $new_value String with potentially unsafe HTML in it.
	 * @return string String with only safe HTML in it.
	 */
	public function safe_html( $new_value ) {
		return wp_kses_post( $new_value );
	}

	/**
	 * Removes unsafe HTML tags when user does not have unfiltered_html
	 * capability.
	 *
	 * @param  string $new_value New value.
	 * @return string New or safe HTML value, depending if user has correct
	 *                capability or not.
	 */
	public function unfiltered_or_safe_html( $new_value ) {
		if ( current_user_can( 'unfiltered_html' ) ) {
			return $new_value;
		}

		return wp_kses_post( $new_value );
	}

	/**
	 * Forces setting to match the available choices.
	 *
	 * @param string $new_value New value.
	 * @param array  $args      The setting args.
	 *
	 * @return string Value if it is in the array, or empty string.
	 */
	public function in_array( $new_value, $args ) {
		$opts = empty( $args['options'] ) ? array() : $args['options'];

		return isset( $opts[ $new_value ] ) ? $new_value : '';
	}

}
