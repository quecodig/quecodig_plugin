<?php
	include (QC_PLUGIN_PATH ."core/updates/plugins.php");
	require (QC_PLUGIN_PATH ."core/updates/themes.php");
	// Check for theme & plugin updates.
	add_filter( 'http_request_args', 'quecodig_mul_update_check' , 5, 2 );

	// Inject plugin updates into the response array.
	add_filter('site_transient_update_plugins', 'quecodig_mul_update_plugins' );
	add_filter('pre_set_transient_update_plugins', 'quecodig_mul_update_plugins' );
	add_filter('pre_set_site_transient_update_plugins', 'quecodig_mul_update_plugins' );

	// Inject plugin information into the API calls.
	add_filter( 'plugins_api', 'quecodig_mul_plugins_api' , 10, 3 );

	if ( is_admin() ) {
		$license_manager = new Wp_License_Manager_Client(
			'wp-arduino',
			'Arduino-PA',
			'wp-arduino',
			'https://www.desarrollo.quecodigo.com/wordpress/',
			'theme'
		);
	}