<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Bloquear acceso de manera directa.
	}

	if(!function_exists('quecodig_logo')){
		function quecodig_logo() { ?>
			<style type="text/css">
				#login h1 a, .login h1 a {
					width:100%;
					max-width: 100%;
					background-image: url(<?php echo plugins_url('assets/img/logo_blanco-grande.png', QC_PLUGIN_FILE ); ?>); 
					padding: 20px;
					padding-bottom: 10px;
					background-size: 310px;
				}
			</style>
		<?php }
	}

	// Créditos del Footer de la página.
	add_filter('admin_footer_text', function() {
		/* translators: %s: five stars */
		return ' ' . sprintf( __( 'Creado por <strong>Qué Código</strong>, %1$s¿Tienes alguna pregunta?%2$s', 'QCText' ), '<a href="'.add_query_arg( array( 'page' => 'quecodigo_soporte' ), admin_url( 'admin.php' ) ).'">', '</a>' ) . ' ';
	});

	// Eliminar widgets de contenido de la portada
	if(!function_exists('quecodig_dashboard_widgets')){
		function quecodig_dashboard_widgets() {
			global $wp_meta_boxes;
			unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);  //remove at-a-glance
			unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);    //remove WordPress-newsfeed
			unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);  //remove quick-draft
		}
	}

	if(!function_exists('quecodig_logo_url')){
		function quecodig_logo_url() {
			return "https://www.quecodigo.com";
		}
	}

	if(!function_exists('quecodig_url_title')){
		function quecodig_url_title() {
			return 'Qué Código';
		}
	}

	if(!function_exists('quecodig_settings_link')){
		function quecodig_settings_link( $links_array ){
			$url = add_query_arg( array( 'page' => 'quecodigo_soporte' ), admin_url( 'admin.php' ) );
			array_unshift( $links_array, '<a href="'.$url.'">Soporte</a>' );
			return $links_array;
		}
	}

	if(!function_exists('quecodig_plugin_row_meta')){
		function quecodig_plugin_row_meta( $links, $file ) {
			if ( strpos( $file, 'quecodigo.php' ) !== false ) {
				$new_links = array(
					'donate' => '<a href="https://www.paypal.me/quecodig" target="_blank">Donar</a>',
					'support' => '<a onclick="window.chaport.open();" style="cursor:pointer">Chat</a>'
				);
				$links = array_merge( $links, $new_links );
			}
			return $links;
		}
	}

	function quecodig_integration(){
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if(is_plugin_active( 'autoptimize/autoptimize.php' )){
			add_filter( 'autoptimize_filter_main_imgopt_plug_notice', '__return_empty_string' );
		}

		if(is_plugin_active( 'jetpack/jetpack.php' )){
			/* Desactivar ventas cruzadas de Jetpack */
			add_filter('jetpack_just_in_time_msgs', '__return_false');
		}
	}
	add_action( 'init', 'quecodig_integration' );