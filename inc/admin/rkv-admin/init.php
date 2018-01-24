<?php
/**
 * Rkv Admin init.
 *
 * @package Rkv\Admin
 */

if ( defined( 'RKV_ADMIN_DIR' ) ) {
	return;
}

define( 'RKV_ADMIN_DIR', trailingslashit( dirname( __FILE__ ) ) );

spl_autoload_register( 'rkv_admin_autoload' );
/**
 * Autoloader for Rkv_Admin classes.
 *
 * @param string $class The class name.
 */
function rkv_admin_autoload( $class ) {
	if ( false === strpos( $class, 'Rkv_Admin' ) ) {
		return;
	}

	$file = sprintf( '%sclass-%s.php', RKV_ADMIN_DIR, strtolower( str_replace( '_', '-', $class ) ) );

	if ( ! file_exists( $file ) || 0 !== validate_file( $file ) ) {
		return;
	}

	require $file;
}
