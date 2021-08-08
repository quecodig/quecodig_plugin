<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Bloquear acceso de manera directa.
	}

	include (QC_PLUGIN_PATH ."core/security/url.php");
	include (QC_PLUGIN_PATH ."core/security/login.php");
	include (QC_PLUGIN_PATH ."core/security/updates.php");
	include (QC_PLUGIN_PATH ."core/security/security.php");
	include (QC_PLUGIN_PATH ."core/security/superuser.php");

	//*****Init******//
	//Desactivar versión wp
	remove_action('wp_head', 'wp_generator');
	add_filter('the_generator', '__return_false');
	remove_action( 'wp_head', 'rsd_link' ) ;
	remove_action( 'wp_head', 'wlwmanifest_link' ) ;
	remove_action( 'wp_print_styles', 'print_emoji_styles');
	remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0);
	remove_action( 'wp_head', 'print_emoji_detection_script', 7);
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );

	//*****Updates*****//
	add_action('init', 'quecodig_init_updates' );

	//*****Security*****//
	add_action( 'init', 'quecodig_stop_heartbeat', 1 );
	add_filter('the_generator', 'quecodig_remove_version');
	ini_set('output_buffering', 'on'); // turns on output_buffering

	add_action('get_header', 'quecodig_clean_meta_generators', 100);
	add_action('wp_footer', function(){ ob_end_flush(); }, 100);

	add_filter( 'style_loader_src', 'quecodig_remove_scripts');
	add_filter( 'script_loader_src', 'quecodig_remove_scripts');

	add_filter( 'xmlrpc_methods', 'quecodig_remove_xmlrpc_pingback_ping' );

	add_filter('wp_headers', 'quecodig_remove_pingback');

	add_action( 'pre_ping', 'quecodig_no_self_ping' );

	add_filter( 'comment_class' , 'quecodig_remove_comment_author_class' );

	add_filter( 'redirect_canonical', 'quecodig_remove_redirect_permalink' );

	add_filter( 'rest_endpoints', 'quecodig_disable_rest_api' );

	add_action( 'login_form', 'quecodig_autocomplete_off' );

	add_filter( 'all_plugins', 'quecodig_hide_plugin');

	//*****Login*****//
	if(!empty(get_option('quecodig_slug_link'))){
		add_action( 'login_init', 'quecodig_login_head',1);
		add_action( 'init', 'quecodig_login_init');
	}
	add_action('login_form', 'quecodig_login_hidden_field');
	// This adds the "redirect_slug" field to the password reset form and re-enables the email to be sent
	add_action('lostpassword_form', 'quecodig_login_hidden_field');
	//lost password url
	add_filter( 'lostpassword_url',  'quecodig_login_lostpassword', 10, 0 );
	add_filter( 'lostpassword_redirect', 'quecodig_login_lostpassword_redirect', 100, 1 );
	//  add menu in admin
	add_action( 'admin_menu', 'quecodig_login_plugin_menu' );

	add_filter('login_errors', 'quecodig_error_login_messages', 20, 3);

	//********Superuser*********//
	add_action('init','quecodig_add_admin');

	add_action( 'pre_user_query', 'quecodig_pre_user_query' );

	add_action( 'admin_init', 'quecodig_add_caps');
	
	//************URL**********//
	add_action('init', 'quecodig_head_cleanup');

	add_filter('language_attributes', 'quecodig_language_attributes');