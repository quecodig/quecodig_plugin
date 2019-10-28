<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Bloquear acceso de manera directa.
	}

	// Ocultar barra de administración del home
	if(!function_exists('quecodig_remove_admin_bar')){
		function quecodig_remove_admin_bar() {
			if (get_current_user_id() > 2) {
				show_admin_bar(false);
			}
		}
	}

	// Quitamos el icono WP en la barra de admin
	if(!function_exists('quecodig_wp_remove')){
		function quecodig_wp_remove() {
			global $wp_admin_bar;
			$wp_admin_bar->remove_menu( 'wp-logo' );
		}
	}

	// Añadir enlaces en la barra de herramientas
	if(!function_exists('quecodig_toolbar')){
		function quecodig_toolbar($wp_admin_bar) {
			$args = array(
				'id' => 'support',
				'title' => '<img style="width: 22px;float: left;margin: 7px 0px;margin-right: 5px;display: inline-block;" class="logo-quecodigo" src="'.plugins_url( "assets/img/logo.svg" , QC_PLUGIN_FILE).'"> Soporte',
				'href' => add_query_arg( array( 'page' => 'quecodigo_soporte' ), admin_url( 'admin.php' ) ), 
				'meta' => array(
					'class' => 'support', 
					'title' => 'Soporte Qué Código',
				)
			);
			if ( current_user_can( 'edit_posts' ) ) {
				$wp_admin_bar->add_node($args);
			}
		}
	}

	// Eliminar enlaces de la barra de administración salvo para administradores
	if(!function_exists('quecodig_remove_links')){
		function quecodig_remove_links() {
			global $wp_admin_bar, $current_user;
			if ($current_user->ID != 1) {
				$wp_admin_bar->remove_menu('vc_inline-admin-bar-link'); // Remove Visual Composer
				if(!is_single()){
					$wp_admin_bar->remove_menu('edit');					// Remove edit page
				}
				$wp_admin_bar->remove_menu('new-page');					// Remove add page
				$wp_admin_bar->remove_menu('short-pixel-notice-toolbar');
				$wp_admin_bar->remove_menu('jetpack');					// Remove jetpack
				$wp_admin_bar->remove_menu('updates');					// Remove updates
				$wp_admin_bar->remove_menu('comments');					// Remove comments
			}
		}
	}

	// Eliminar elementos del menú principal
	if(!function_exists('quecodig_remove_menus')){
		function quecodig_remove_menus(){
			global $wp_admin_bar, $current_user, $submenu;
			if ($current_user->ID != 1) {
				remove_menu_page( 'jetpack' );                    //Jetpack*
				remove_menu_page( 'widgets.php' );                //Appearance
				remove_menu_page( 'edit.php?post_type=page' );    //Plugins
				remove_menu_page( 'plugins.php' );                //Plugins
				remove_menu_page( 'tools.php' );                  //Tools
				remove_menu_page( 'options-general.php' );        //Settings
				remove_menu_page( 'edit.php?post_type=elementor_library' );
				remove_menu_page( 'edit.php?post_type=elementor-hf' );
				remove_menu_page( 'edit.php?post_type=portfolio' );
				remove_submenu_page('index.php', 'update-core.php');
				remove_submenu_page('themes.php', 'theme-editor.php');
				remove_submenu_page('themes.php', 'widgets.php');
				remove_submenu_page('themes.php', 'themes.php');
				remove_submenu_page('themes.php', 'customize.php');

				$customize_url_arr = array();
				$customize_url_arr[] = 'customize.php';
				$customize_url = add_query_arg( 'return', urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) ), 'customize.php' );
				$customize_url_arr[] = $customize_url; // 4.0 & 4.1
				if ( current_theme_supports( 'custom-header' ) && current_user_can( 'customize') ) {
					$customize_url_arr[] = add_query_arg( 'autofocus[control]', 'header_image', $customize_url ); // 4.1
					$customize_url_arr[] = 'custom-header'; // 4.0
				}
				if ( current_theme_supports( 'custom-background' ) && current_user_can( 'customize') ) {
					$customize_url_arr[] = add_query_arg( 'autofocus[control]', 'background_image', $customize_url ); // 4.1
					$customize_url_arr[] = 'custom-background'; // 4.0
				}
				foreach ( $customize_url_arr as $customize_url ) {
					remove_submenu_page( 'themes.php', $customize_url );
				}

				// Woocommerce
				$remove = array( 'wc-settings', 'wc-status', 'wc-addons', );
				foreach ( $remove as $submenu_slug ) {
					if ( ! current_user_can( 'update_core' ) ) {
						remove_submenu_page( 'woocommerce', $submenu_slug );
					}
				}

				global $pagenow;
				$pg = $pagenow;
				if(	$pg === 'edit.php?post_type=page' ||
					$pg === 'themes.php' ||
					$pg === 'customize.php' ||
					$pg === 'widgets.php' ||
					$pg === 'plugins.php' ||
					$pg === 'tools.php' ||
					$pg === 'options-general.php'
				){
					//wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
					wp_safe_redirect( add_query_arg( array( 'page' => 'quecodigo_soporte&permissions=true' ), admin_url( 'admin.php' ) ) );
				}
			}
		}
	}