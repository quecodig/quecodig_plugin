<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Bloquear acceso de manera directa.
	}

	/**
	* Manage WooCommerce styles and scripts.
	*/
	if(!function_exists('quecodig_woocommerce_script_cleaner')){
		function quecodig_woocommerce_script_cleaner() {
			// Remove the generator tag
			remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );
			// Unless we're in the store, remove all the cruft!
			if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() ) {
				wp_dequeue_style( 'woocommerce_frontend_styles' );
				wp_dequeue_style( 'woocommerce-general');
				wp_dequeue_style( 'woocommerce-layout' );
				wp_dequeue_style( 'woocommerce-smallscreen' );
				wp_dequeue_style( 'woocommerce_fancybox_styles' );
				wp_dequeue_style( 'woocommerce_chosen_styles' );
				wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
				wp_dequeue_script( 'selectWoo' );
				wp_deregister_script( 'selectWoo' );
				wp_dequeue_script( 'wc-add-payment-method' );
				wp_dequeue_script( 'wc-lost-password' );
				wp_dequeue_script( 'wc_price_slider' );
				wp_dequeue_script( 'wc-single-product' );
				wp_dequeue_script( 'wc-add-to-cart' );
				wp_dequeue_script( 'wc-cart-fragments' );
				wp_dequeue_script( 'wc-credit-card-form' );
				wp_dequeue_script( 'wc-checkout' );
				wp_dequeue_script( 'wc-add-to-cart-variation' );
				wp_dequeue_script( 'wc-single-product' );
				wp_dequeue_script( 'wc-cart' );
				wp_dequeue_script( 'wc-chosen' );
				wp_dequeue_script( 'woocommerce' );
				wp_dequeue_script( 'prettyPhoto' );
				wp_dequeue_script( 'prettyPhoto-init' );
				wp_dequeue_script( 'jquery-blockui' );
				wp_dequeue_script( 'jquery-placeholder' );
				wp_dequeue_script( 'jquery-payment' );
				wp_dequeue_script( 'fancybox' );
				wp_dequeue_script( 'jqueryui' );
			}
		}
	}

	/* Desactivar medidor de fortaleza de contraseñas */ 
	function quecodig_remove_password_strength() {
		wp_dequeue_script( 'wc-password-strength-meter' );
	}

	/* Desactivar menú extensiones de WooCommerce */
	if(!function_exists('quecodig_remove_admin_addon_submenu')){
		function quecodig_remove_admin_addon_submenu() {
			remove_submenu_page( 'woocommerce', 'wc-addons');
		}
	}