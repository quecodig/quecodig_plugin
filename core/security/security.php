<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Bloquear acceso de manera directa.
	}

	function quecodig_stop_heartbeat() {
		wp_deregister_script('heartbeat');
	}

	//Version WP
	if(!function_exists('quecodig_remove_version')){
		function quecodig_remove_version() {
			return '';
		}
	}

	if(!function_exists('quecodig_meta_generators')){
		function quecodig_meta_generators($html) {
			$pattern = '/<meta name(.*)=(.*)"generator"(.*)>/i';
			$html = preg_replace($pattern, '', $html);
			return $html;
		}
	}

	if(!function_exists('quecodig_clean_meta_generators')){
		function quecodig_clean_meta_generators($html) {
			ob_start('quecodig_meta_generators');
		}
	}
	
	if(!function_exists('quecodig_remove_scripts')){
		function quecodig_remove_scripts( $src ) {
			if ( strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) )
				$src = remove_query_arg( 'ver', $src );
			return $src;
		}
	}

	//Remove pingback
	if(!function_exists('quecodig_remove_xmlrpc_pingback_ping')){
		function quecodig_remove_xmlrpc_pingback_ping( $methods ) {
			unset( $methods['pingback.ping'] );
			return $methods;
		}
	}
	
	if(!function_exists('quecodig_remove_pingback')){
		function quecodig_remove_pingback($headers) {
			unset($headers['X-Pingback']);
			return $headers;
		}
	}

	//Ping
	if(!function_exists('quecodig_no_self_ping')){
		function quecodig_no_self_ping( &$links ) {
			$home = get_option( 'home' );
			foreach ( $links as $l => $link ){
				if ( 0 === strpos( $link, $home ) ){
					unset($links[$l]);
				}
			}
		}
	}

	// Quitar clase que revela usuario admin
	// @Source: https://ayudawp.com/fallo-seguridad-comment-author/
	if(!function_exists('quecodig_remove_comment_author_class')){
		function quecodig_remove_comment_author_class( $classes ) {
			foreach( $classes as $key => $class ) {
				if(strstr($class, "comment-author-")) {
					unset( $classes[$key] );
				}
			}
			return $classes;
		}
	}

	if(!function_exists('quecodig_remove_redirect_permalink')){
		function quecodig_remove_redirect_permalink( $redirect_url ) {
			if ( is_404() && !isset($_GET['p']) )
				return false;
			return $redirect_url;
		}
	}

	//Reporter
	if(!function_exists('quecodig_debug_report')){
		function quecodig_debug_report($args) {
			global $wp_version;
			if($args == true){
				/* Get from WooCommerce by WooThemes http://woothemes.com  */
				$active_plugins = (array) get_option('active_plugins', array());
				if (is_multisite())
					$active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));

				$active_plugins = array_map('strtolower', $active_plugins);
				$pp_plugins = array();

				foreach ($active_plugins as $plugin) {
					$plugin_data = @get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);
					if (!empty($plugin_data['Name'])) {
						$pp_plugins[] = $plugin_data['Name'] . ' ' . $plugin_data['Version'] . ' [' . $plugin_data['PluginURI'] . "]";
					}
				}

				if ($pp_plugins)
					$plugin_list = implode("\n", $pp_plugins);


				$wp_info = ( is_multisite() ) ? 'WPMU ' . $wp_version : 'WP ' . $wp_version;
				$wp_debug = ( defined('WP_DEBUG') && WP_DEBUG ) ? 'true' : 'false';
				$is_ssl = ( is_ssl() ) ? 'true' : 'false';
				$is_rtl = ( is_rtl() ) ? 'true' : 'false';
				$fsockopen = ( function_exists('fsockopen') ) ? 'true' : 'false';
				$curl = ( function_exists('curl_init') ) ? 'true' : 'false';
				$max_upload_size = (function_exists('size_format')) ? size_format(wp_max_upload_size()) : wp_convert_bytes_to_hr(wp_max_upload_size());

				if (function_exists('phpversion')) {
					$php_info = phpversion();
					$max_server_upload = ini_get('upload_max_filesize');
					$post_max_size = ini_get('post_max_size');
				}

				//Debug
				$log_debug = "";
				if(file_exists(WP_CONTENT_DIR .'/debug.log')){
					$file = file(WP_CONTENT_DIR .'/debug.log');
					for ($i = max(0, count($file)-11); $i < count($file); $i++) {
						$log_debug .= $file[$i] . "\n";
					}
				}


				$value = '
==================================================
WP Settings
==================================================
WordPress version:      ' . $wp_info . '
Home URL:       ' . home_url() . '
Site URL:       ' . site_url() . '
Is SSL:         ' . $is_ssl . '
Is RTL:         ' . $is_rtl . '                                         
Permalink:      ' . get_option('permalink_structure') . '
Theme Active:   ' .wp_get_theme()->get("Name"). '
Theme version:   ' .wp_get_theme()->get("Version"). '
Qué Código version:      ' .PLUGIN_VERSION. '

==================================================
Server Environment
==================================================
PHP Version:            ' . $php_info . '
Server Software:        ' . $_SERVER['SERVER_SOFTWARE'] . '
WP Max Upload Size: ' . $max_upload_size . '
Server upload_max_filesize:     ' . $max_server_upload . '
Server post_max_size:   ' . $post_max_size . '
WP Memory Limit:        ' . WP_MEMORY_LIMIT . '
WP Debug Mode:      ' . $wp_debug . '
CURL:               ' . $curl . '
fsockopen:          ' . $fsockopen . '

==================================================
Active plugins   
==================================================
' . $plugin_list . '

==================================================
Debug log
==================================================
' . $log_debug . '
';


				$html = sprintf('<textarea readonly="readonly" rows="5" cols="65" style="%4$s" class="%1$s" id="%2$s" name="%2$s">%3$s</textarea>', $args['class'], 'debug_report', $value, 'width:100% !important;height:400px !important');
				$html .= sprintf('<br><span class="description"> %s</span>', $args['desc']);

				echo $html;
			}
		}
	}

	// Evitar la enumeración de usuarios en WordPress
	// Fuente: https://desarrollowp.com/blog/tutoriales/evitar-la-enumeracion-usuarios-wordpress-bola-extra/
	//Stop User Enumeration
	if ( ! is_admin() && isset($_SERVER['REQUEST_URI'])){
		if(preg_match('/(wp-comments-post)/', $_SERVER['REQUEST_URI']) === 0 && !empty($_REQUEST['author']) ) {
			wp_die('forbidden');
		}
	}

	// Disable REST API user endpoints
	if(!function_exists('quecodig_disable_rest_api')){
		function quecodig_disable_rest_api ( $endpoints ){
			if ( isset( $endpoints['/wp/v2/users'] ) ) {
				unset( $endpoints['/wp/v2/users'] );
			}
			if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
				unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
			}
			return $endpoints;
		}
	}

	// Autocomplete Off in wp-login
	if(!function_exists('quecodig_autocomplete_off')){
		function quecodig_autocomplete_off() {
			wp_register_script( 'autocomplete-off', plugins_url('assets/js/autocomplete.js', QC_PLUGIN_FILE ), array('jquery'), '1.0' );
			wp_enqueue_script( 'autocomplete-off' );
		}
	}

	//Bloquear Plugin
	if(!function_exists('quecodig_hide_plugin') && !defined('quecodigAD_plugin')){
		function quecodig_hide_plugin($plugins){
			if(is_plugin_active('quecodigo/quecodigo.php')) {
				unset( $plugins['quecodigo/quecodigo.php'] );
			}
			return $plugins;
		}
	}

	//Salts wp-config.php
	function quecodig_wp_config_path() {
		$paths = array(
			ABSPATH . 'wp-config.php',
			dirname( ABSPATH ) . '/wp-config.php'
		);
		foreach ( $paths as $path ) {
			if ( file_exists( $path ) ) {
				return $path;
			}
		}
		return false;
	}

	function quecodig_write_to_wp_config() {
		$wp_config = quecodig_wp_config_path();
		if ( is_writable( $wp_config ) ) {
			return true;
		}
		return false;
	}

	function quecodig_salts() {
		return trim( preg_replace( '/\s\s+/', ' ', file_get_contents( 'https://api.wordpress.org/secret-key/1.1/salt' ) ) );
	}

	function quecodig_salts_to_wp_config() {
		$wp_config = quecodig_wp_config_path();
		if ( false === $wp_config || ! quecodig_write_to_wp_config() ) {
			return null;
		}
		$config_contents = @file_get_contents( $wp_config );
		if ( false === $config_contents ) {
			return null;
		}
		$old_prefix = '/**#@+';
		$new_prefix = '# BEGIN Qué Código WP';
		$new_suffix = '# END Qué Código WP';
		if ( false !== strpos( $config_contents, $old_prefix ) ) {
			$config_contents = str_replace( array( $old_prefix, '/**#@-*' ), array( $new_prefix, $new_suffix ), $config_contents );
			$config_contents = str_replace( $new_suffix . '/', $new_suffix, $config_contents );
		}
		if ( false !== strpos( $config_contents, $new_prefix ) ) {
			$config_contents = preg_replace(
				'/\\' . $new_prefix . '(.*?)\\' . $new_suffix . '/s',
				$new_prefix . PHP_EOL . quecodig_salts() . PHP_EOL . $new_suffix,
				$config_contents
			);
		}
		@file_put_contents( $wp_config, $config_contents, LOCK_EX );
	}