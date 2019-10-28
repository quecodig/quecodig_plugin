<?php
/**
 * Uninstall script
 *
 * This file contains all the logic required to uninstall the plugin
 *
 * @package QC/Uninstall
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Bloquear acceso de manera directa.
}

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if ( ! current_user_can( 'install_plugins' ) ) {
	exit;
}

if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

if ( is_plugin_active( 'quecodigo/quecodigo.php' ) ) {
	return;
}

wp_clear_scheduled_hook('quecodig_salts_to_wp_config');
wp_clear_scheduled_hook('quecodig_update_notify');
wp_clear_scheduled_hook('quecodig_support_data');
delete_transient('quecodig_upgrade');
delete_option('quecodig_slug_link');
delete_option('quecodig_warnings');
delete_option('quecodig_public');
delete_option('quecodig_code');
delete_option('quecodig_sub');