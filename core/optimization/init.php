<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Bloquear acceso de manera directa.
	}
	
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	include (QC_PLUGIN_PATH ."core/optimization/gzip.php");
	include (QC_PLUGIN_PATH ."core/optimization/compress.php");

	//*****optimization*****//
	add_action('get_header', 'quecodig_compression_start');

	if(is_plugin_active( 'woocommerce/woocommerce.php' ) ){
		include (QC_PLUGIN_PATH ."core/optimization/woocommerce.php");

		add_action( 'wp_enqueue_scripts', 'quecodig_woocommerce_script_cleaner', 99 );
		add_action( 'wp_print_scripts', 'quecodig_remove_password_strength', 10 );
		// https://ayudawp.com/quitar-lo-que-sobra-woocommerce/
		add_action( 'admin_menu', 'quecodig_remove_admin_addon_submenu', 999 );
		add_filter( 'woocommerce_allow_marketplace_suggestions', '__return_false', 999 ); //Sugerencias extensiones
		add_filter( 'woocommerce_helper_suppress_admin_notices', '__return_true' ); //Conectarse a woocommerce.com
	}