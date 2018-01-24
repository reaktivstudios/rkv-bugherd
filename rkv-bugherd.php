<?php
/**
 * Plugin Name: ReaktivStudios BugHerd
 * Description: Outputs the BugHerd script when WP_DEBUG enabled on site.
 * Version: 0.1.0
 * Author: Reaktiv Studios
 * License: GPL-2.0+
 *
 * @version 0.1.0
 * @package rkv-bugherd
 */

define( 'RKV_BUGHERD_PATH', plugin_dir_path( __FILE__ ) );

// Only enable on staging sites.
if ( ( defined( 'WP_DEBUG' ) && WP_DEBUG ) || ( defined( 'VIP_GO_ENV' ) && 'production' !== VIP_GO_ENV ) ) {
	if ( is_admin() ) {
		require_once RKV_BUGHERD_PATH . 'inc/admin/general-settings.php';
	} else {
		require_once RKV_BUGHERD_PATH . 'inc/scripts.php';
	}
}
