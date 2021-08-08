<?php
	
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Bloquear acceso de manera directa.
	}

	function quecodig_add_debug_info( $debug_info ) {
		if(get_option('quecodig_sub') === "1"){
			$licence = __( 'Licencia activa', 'quecodig' );
		}else if(get_option('quecodig_sub') === "2"){
			$licence = __( 'Licencia vencida', 'quecodig' );
		}else{
			$licence = __( 'Sin licencia', 'quecodig' );
		}
		$debug_info['quecodig'] = array(
			'label'    => __( 'Soporte By Qué Código', 'quecodig' ),
			'description' => 'Información del plugin de soporte.',
			'fields'   => array(
				'version' => array(
					'label'    => __( 'Versión', 'quecodig' ),
					'value'   => QC_PLUGIN_VERSION,
					'private' => true,
				),
				'license' => array(
					'label'    => __( 'Licencia', 'quecodig' ),
					'value'   => $licence,
					'private' => true,
				),
			),
		);

		return $debug_info;
	}
	add_filter( 'debug_information', 'quecodig_add_debug_info' );