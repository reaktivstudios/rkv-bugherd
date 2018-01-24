<?php
/**
 * Add bugherd settings to general opts.
 *
 * @package rkv-bugherd
 */

/**
 * RKV admin init file. Checks for duplication occur in the file.
 */
require_once trailingslashit( dirname( __FILE__ ) ) . 'rkv-admin/init.php';

$rkv_bugherd_setting_sections = array(
	'rkv_bugherd' => array(
		'title'       => __( 'BugHerd Settings', 'rkv-bugherd' ),
		'description' => __( 'These settings affect display of the BugHerd script.', 'rkv-bugherd' ),
	),
);

$rkv_bugherd_settings = array(
	'rkv_bugherd_api_key' => array(
		'label'    => __( 'API Key', 'rkv-bugherd' ),
		'default'  => '',
		'type'     => 'text',
		'sanitize' => 'no_html',
		'section'  => 'rkv_bugherd',
	),
);

$rkv_bugherd_my_admin = new Rkv_Admin( 'general', array(), array(), $rkv_bugherd_settings, $rkv_bugherd_setting_sections );
