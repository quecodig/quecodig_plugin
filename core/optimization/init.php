<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Bloquear acceso de manera directa.
	}

	include (QC_PLUGIN_PATH ."core/optimization/gzip.php");
	include (QC_PLUGIN_PATH ."core/optimization/compress.php");

	//*****optimization*****//
	add_action('get_header', 'quecodig_compression_start');

	add_action( 'wp_enqueue_scripts', 'quecodig_woocommerce_script_cleaner', 99 );