<?php
	function quecodig_head_cleanup() {
		// Eliminamos lo que sobra de la cabecera
		remove_action('wp_head', 'rsd_link');
		remove_action('wp_head', 'wp_generator');
		remove_action('wp_head', 'feed_links', 2);
		remove_action('wp_head', 'index_rel_link');
		remove_action('wp_head', 'wlwmanifest_link');
		remove_action('wp_head', 'feed_links_extra', 3);
		remove_action('wp_head', 'start_post_rel_link', 10, 0);
		remove_action('wp_head', 'parent_post_rel_link', 10, 0);
		remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
		remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
		remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
		remove_action('wp_head', 'feed_links', 2);
		remove_action('wp_head', 'feed_links_extra', 3);

		global $wp_widget_factory;
		remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));

		if (!class_exists('WPSEO_Frontend')) {
			remove_action('wp_head', 'rel_canonical');
			add_action('wp_head', 'quecodig_rel_canonical');
		}
	}

	function quecodig_rel_canonical() {
		global $wp_the_query;
		
		if (!is_singular()) {
			return;
		}
		
		if (!$id = $wp_the_query->get_queried_object_id()) {
			return;
		}
		
		$link = get_permalink($id);
		echo "\t<link rel=\"canonical\" href=\"$link\">\n";
	}
 
	/**
	 * Limpieza de los language_attributes() usados en la etiqueta <html>
	 *
	 * Cambia lang="es-ES" a lang="es"
	 * Elimina dir="ltr"
	 */
	function quecodig_language_attributes() {
		$attributes = array();
		$output = '';
		
		if (function_exists('is_rtl')) {
			if (is_rtl() == 'rtl') {
				$attributes[] = 'dir="rtl"';
			}
		}
		
		$lang = get_bloginfo('language');
		
		if ($lang && $lang !== 'es-ES') {
			$attributes[] = "lang=\"$lang\"";
		} else {
			$attributes[] = 'lang="es"';
		}
		
		$output = implode(' ', $attributes);
		$output = apply_filters('quecodig_language_attributes', $output);
		
		return $output;
	}