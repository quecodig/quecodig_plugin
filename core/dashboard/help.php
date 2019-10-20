<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Bloquear acceso de manera directa.
	}

	if(!function_exists('quecodig_helper')){
		function quecodig_helper(){
			// NOTA: El margin-top debe estar en minimo 40px
			//get the current screen object
			$current_screen = get_current_screen ();
			// Nota: para poderlo mostrar antes del Slug de la url debe ir 'toplevel_page_', Razón desconocida.
			if($current_screen->base == 'toplevel_page_quecodigo_soporte'){
				$current_screen->add_help_tab(
					array(
						'id'      => 'sp_faq',
						'title'   => 'Acerca de Soporte Qué Código',
						'content' => '<h2>Acerca de este plugin.</h2><p>Soporte Qué Código V'.PLUGIN_VERSION.'</p><p>Este plugin es desarrollado y mantenido por <a href="https://www.quecodigo.com" target="_blank">Qué Código</a> para sus clientes, y este contiene mejoras de seguridad, optimización y contacto directo con nosotros.</p><p>Quires saber más visitamos en <a href="https://www.desarrollo.quecodigo.com/wordpress/quecodigo/" target="_blank">desarrollo.quecodigo.com</a></p>'
					)
				);

				$current_screen->add_help_tab(
					array(
						'id'      => 'sp_support',
						'title'   => 'Soporte Avanzado',
						'callback' => function ( $screen, $tab ) {
							echo '<p>Para soporte, envíanos un mail a través de soporte@quecodigo.com</p><br><a class="btn primary" href="'.wp_nonce_url(add_query_arg( array( 'page' => 'quecodigo_soporte', 'force_support' => "true" ), admin_url( 'admin.php' )), 'quecodig_action_nonce').'">Verificar soporte</a><a class="btn primary" href="'.wp_nonce_url(add_query_arg( array( 'page' => 'quecodigo_soporte', 'force_support' => "true" ), admin_url( 'admin.php' )), 'quecodig_action_nonce').'">Forzar actualización</a>';
						}
					)
				);
			}
		}
	}
?>