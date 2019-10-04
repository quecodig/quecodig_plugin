<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Bloquear acceso de manera directa.
	}

	if(!function_exists('quecodig_init_updates')){
		function quecodig_init_updates(){
			global $wp_admin_bar, $current_user, $submenu;
			flush_rewrite_rules();
			if ($current_user->ID != 1) {
				//Desactivar las actualizaciones
				#Plugins
				//remove_action('load-update-core.php', 'wp_update_plugins');
				//add_filter('pre_site_transient_update_plugins', '__return_null');
				#Themes
				remove_action('load-update-core.php', 'wp_update_themes');
				#Updates Core
				add_filter( 'automatic_updater_disabled', '__return_true' );
				// Deshabilitar actualizaciones de desarrollo
				add_filter( 'allow_dev_auto_core_updates', '__return_false' );
				// Deshabilitar actualizaciones menores
				add_filter( 'allow_minor_auto_core_updates', '__return_false' );
				// Deshabilitar actualizaciones mayores
				add_filter( 'allow_major_auto_core_updates', '__return_false' );
				#Notification
				add_action('after_setup_theme', 'quecodig_core_updates');
				function quecodig_core_updates() {
					if (!current_user_can('update_core')) {
						return;
					}
					add_action('init', create_function('$a', "remove_action( 'init', 'wp_version_check' );"), 2);
					add_filter('pre_option_update_core', '__return_null');
					add_filter('pre_site_transient_update_core', '__return_null');
				}
			}
		}
	}