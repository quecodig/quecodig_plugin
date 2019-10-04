<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Bloquear acceso de manera directa.
	}
	//Textos traducidos
	if(!function_exists("quecodig_translate_words")){
		function quecodig_translate_words( $translated ) {
			$words = array(
				// 'palabra a traducir' = > 'traducción'
				'WooCommerce' => 'Tienda',
				'WordPress' => 'Sistema',
				'Categories' => 'Categorias',
				'Add Portfolio' => 'Añadir portafolio',
				'Search Portfolio' => 'Buscar portafolio',
				'All Portfolios' => 'Todos los portafolios',
				'Portfolios' => 'Portafolios',
			);
			$translated = str_ireplace(  array_keys($words),  $words,  $translated );
			return $translated;
		}
	}

	if(!function_exists('quecodig_rename_menu_woo')){
		function quecodig_rename_menu_woo(){
			global $menu;
			$woo = the_array_search( 'WooCommerce', $menu );
			$products = the_array_search( 'Productos', $menu );
			if( !$woo )
				return;
			$menu[$woo][0] = 'Conf. Tienda'; //Sustitución para Woocommerce
			//$menu[$products][0] = 'Artículos'; //Sustitución para Productos
		}
	}

	if(!function_exists('the_array_search')){
		function the_array_search( $find, $items ) {
			foreach( $items as $key => $value ) {
				$current_key = $key;
				if($find === $value OR (is_array( $value ) && the_array_search( $find, $value ) !== false)){
					return $current_key;
				}
			}
			return false;
		}
	}