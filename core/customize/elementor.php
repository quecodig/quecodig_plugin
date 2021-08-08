<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Bloquear acceso de manera directa.
	}

	// desactivas las Google Fonts que carga Elementor:
	add_filter( 'elementor/frontend/print_google_fonts', '__return_false' );

	// Desactivar familias de iconos de Elementor
	add_action( 'elementor/frontend/after_register_styles',function() {
		foreach( [ 'solid', 'regular', 'brands' ] as $style ) {
			wp_deregister_style( 'elementor-icons-fa-' . $style );
		}
	}, 20 );

	// Eicons
	add_action( 'wp_enqueue_scripts', 'quecodig_remove_default_stylesheet', 20 ); 
	function quecodig_remove_default_stylesheet() { 
		wp_deregister_style( 'elementor-icons' ); 
	}