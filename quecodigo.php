<?php
	/*
	Plugin Name: Soporte By Qué Código
	Plugin URI: https://www.desarrollo.quecodigo.com/wordpress/quecodigo/
	Description: Qué Código es un conjunto de herramientas ligeras y sencillas que facilitan el desarrollo y uso de WordPress, funciones que incluyen <strong>Estilos, Seguridad, Optimización, seguimiento con Google Analytics y soporte</strong>.
	Version: 1.6.3.2
	Author: Qué Código
	Author URI: https://www.quecodigo.com
	License: GPL2
	Requires at least: 4.0
	Tested up to: 5.2.2
	Text Domain: QCText
	Domain Path: /languages/
	*/

	defined( 'ABSPATH' ) or die( 'Error de acceso al plugin' );

	if( ! function_exists('add_filter')){
		header('Status: 403 Forbidden');
		header(' HTTP/1.1 403 Forbidden');
		exit();
	}

	//Init
	if( ! defined( 'PLUGIN_VERSION' ) ){
		define("PLUGIN_VERSION", "1.6.3.1");
	}
	// Define "FILE" del plugin
	if ( ! defined( 'QC_PLUGIN_FILE' ) ) {
		define( 'QC_PLUGIN_FILE', __FILE__ );
	}
	if ( ! defined( 'PLUGIN_API' ) ) {
		define("PLUGIN_API", "https://www.api.quecodigo.com/plugins/quecodigo/");
	}
	if( ! defined( 'QC_PLUGIN_PATH' ) ){
		define('QC_PLUGIN_PATH', realpath( plugin_dir_path( QC_PLUGIN_FILE ) ) . '/' );
	}

	// Bail early if attempting to run on non-supported php versions.
	if ( version_compare( PHP_VERSION, '5.3', '<' ) ) {
		function quecodig_incompatible_admin_notice() {
			echo '<div class="error"><p>' . __( 'QuéCódigo requires PHP 5.3 (or higher) to function properly. Please upgrade PHP. The Plugin has been auto-deactivated.', 'QCText' ) . '</p></div>';
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}
		}
		function quecodig_deactivate_self() {
			deactivate_plugins( plugin_basename( QC_PLUGIN_FILE ) );
		}
		add_action( 'admin_notices', 'quecodig_incompatible_admin_notice' );
		add_action( 'admin_init', 'quecodig_deactivate_self' );
		return;
	}

	add_filter( 'cron_schedules', 'quecodig_cron_schedules');
	function quecodig_cron_schedules( $schedules ) {
		$schedules['monthly'] = array(
			'interval' => 2592000, // segundos en 30 dias
			'display' => __( 'Monthly', 'QCText' ) // nombre del intervalo
		);
		return $schedules;
	}

	//=======
	$core_inc = array(
		//'updates' => 'updates',
		'security' => 'security',
		'dashboard' => 'dashboard',
		'customize' => 'customize',
		'optimization' => 'optimization',
	);

	include QC_PLUGIN_PATH."init.php";

	// Se registra la activación y desactivación del Plugin.
	register_activation_hook(
		__FILE__, function() {
			add_option('quecodig_activation_welcome', 'pending');
			add_option('quecodig_slug_link', 'panel-administracion', '', 'yes');
			add_option('quecodig_warnings', 0);
			add_option('quecodig_public', 0);
			add_option('quecodig_code', 0);
			add_option('quecodig_sub', 0);
			quecodig_htaccess();
			if( ! wp_next_scheduled( 'quecodig_salts_to_wp_config' ) ) {
				quecodig_salts_to_wp_config();
				wp_schedule_event( current_time( 'timestamp' ), 'Monthly', 'quecodig_salts_to_wp_config' );
			}
		}
	);
	register_deactivation_hook( 
		__FILE__, function() {
			wp_clear_scheduled_hook( 'quecodig_salts_to_wp_config' );
			delete_option('quecodig_public');
			delete_option('quecodig_code');
			quecodig_delete_htaccess();
			flush_rewrite_rules();
		}
	);