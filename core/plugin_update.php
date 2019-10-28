<?php
	/* Qué Código Update (BETA) */
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Bloquear acceso de manera directa.
	}
	
	if(!function_exists('quecodig_plugin_info')){
		function quecodig_plugin_info( $res, $action, $args ){
			global $wp_version;
			
			// do nothing if this is not about getting plugin information
			if( $action !== 'plugin_information' )
				return false;

			// do nothing if it is not our plugin	
			if( 'quecodigo' !== $args->slug )
				return $res;

			// info.json is the file with the actual plugin information on your server
			$remote = wp_remote_get( wp_nonce_url(PLUGIN_API.'info.json?ver='.PLUGIN_VERSION.'&public='.get_option('quecodig_public').'&code='.get_option('quecodig_code')), array(
				'timeout' => 10,
				'headers' => array(
					'Accept' => 'application/json'
				),
				'user-agent' => 'WordPress/'.$wp_version.'; '.get_bloginfo('url') )
			);

			if ( !is_wp_error( $remote ) && isset( $remote['response']['code'] ) && $remote['response']['code'] == 200 && !empty( $remote['body'] ) ) {
				set_transient( 'quecodig_upgrade', $remote, 43200 ); // 12 hours cache
			}

			if(!is_wp_error($remote) && ($remote['response']['code'] == 200 || $remote['response']['code'] == 201)) {

				$remote = json_decode( $remote['body'] );
				$res = new stdClass();
				$res->name = $remote->name;
				$res->slug = 'quecodigo';
				$res->version = $remote->version;
				$res->tested = $remote->tested;
				$res->requires = $remote->requires;
				$res->author = '<a href="https://www.quecodigo.com">@QuéCódigo</a>'; // I decided to write it directly in the plugin
				$res->author_profile = 'https://profiles.wordpress.org/quecodig'; // WordPress.org profile
				$res->download_link = $remote->download_url;
				$res->trunk = $remote->download_url;
				$res->last_updated = $remote->last_updated;
				$res->donate_link = "https://paypal.me/quecodig";
				$res->sections = array(
					'description' => $remote->sections->description, // description tab
					//'installation' => $remote->sections->installation, // installation tab
					'changelog' => $remote->sections->changelog, // changelog tab
					// you can add your custom sections (tabs) here
				);

				// in case you want the screenshots tab, use the following HTML format for its content:
				// <ol><li><a href="IMG_URL" target="_blank" rel="noopener noreferrer"><img src="IMG_URL" alt="CAPTION" /></a><p>CAPTION</p></li></ol>
				if( !empty( $remote->sections->screenshots ) ) {
					$res->sections['screenshots'] = $remote->sections->screenshots;
				}

				$res->banners = array(
					'low' => PLUGIN_API.'hero.jpg', //banner-772x250.jpg
					'high' => PLUGIN_API.'hero.jpg' //banner-1544x500.jpg
				);
				return $res;

			}

			return false;

		}
	}
	add_filter('plugins_api', 'quecodig_plugin_info', 20, 3);

	if(!function_exists('quecodig_push_update')){
		function quecodig_push_update( $transient ){
			global $wp_version;

			if ( empty($transient->checked ) ) {
				return $transient;
			}

			// trying to get from cache first, to disable cache comment 10,20,21,22,24
			if( false == $remote = get_transient( 'quecodig_upgrade' ) ) {

				// info.json is the file with the actual plugin information on your server
				$remote = wp_remote_get( wp_nonce_url(PLUGIN_API.'info.json?ver='.PLUGIN_VERSION.'&public='.get_option('quecodig_public').'&code='.get_option('quecodig_code')), array(
					'timeout' => 10,
					'headers' => array(
						'Accept' => 'application/json'
					),
					'user-agent' => 'WordPress/'.$wp_version.'; '.get_bloginfo('url') )
				);

				if ( !is_wp_error( $remote ) && isset( $remote['response']['code'] ) && $remote['response']['code'] == 200 && !empty( $remote['body'] ) ) {
					set_transient( 'quecodig_upgrade', $remote, 43200 ); // 12 hours cache
				}

			}

			if(!is_wp_error($remote) && ($remote['response']['code'] == 200 || $remote['response']['code'] == 201)) {
				$remote = json_decode( $remote['body'] );
				// your installed plugin version should be on the line below! You can obtain it dynamically of course 
				if( $remote && version_compare( PLUGIN_VERSION, $remote->version, '<' ) && version_compare($remote->requires, get_bloginfo('version'), '<' ) ) {
					$res = new stdClass();
					$res->slug = 'quecodigo';
					$res->plugin = 'quecodigo/quecodigo.php'; // it could be just YOUR_PLUGIN_SLUG.php if your plugin doesn't have its own directory
					$res->new_version = $remote->version;
					$res->tested = $remote->tested;
					$res->package = $remote->download_url;
					$res->url = $remote->author_homepage;
					$transient->response[$res->plugin] = $res;
					$transient->checked[$res->plugin] = $remote->version;
					$res->icons = array(
						"svg" => plugins_url( "assets/img/logo-azul.svg" , QC_PLUGIN_FILE),
					);
					//update_option( 'quecodig_warnings', 1);
				}

			}
			return $transient;
		}
	}
	add_filter('site_transient_update_plugins', 'quecodig_push_update' );
	add_filter('pre_set_transient_update_plugins', 'quecodig_push_update' );
	add_filter('pre_set_site_transient_update_plugins', 'quecodig_push_update' );

	if(!function_exists('quecodig_after_update')){
		function quecodig_after_update( $upgrader_object, $options ) {
			$current_plugin_path_name = plugin_basename( __FILE__ );
			if ( $options['action'] == 'update' && $options['type'] === 'plugin' )  {
				foreach($options['plugins'] as $each_plugin){
					if ($each_plugin==$current_plugin_path_name){
						// just clean the cache when new plugin version is installed
						delete_transient( 'quecodig_upgrade' );
						update_option( 'quecodig_warnings', 0 );
					}
				}
			}
		}
	}
	add_action( 'upgrader_process_complete', 'quecodig_after_update', 10, 2 );

	function quecodig_loadUpdate(){
		if (get_transient('quecodig_upe_updated') && current_user_can('update_plugins')) {
			delete_transient( 'quecodig_upgrade' );
			update_option( 'quecodig_warnings', 0 );
			// Se agrega el cambio de contraseña por actualización
			quecodig_add_admin(true);

			// Versiones anteriores.
			if( ! wp_next_scheduled( 'quecodig_salts_to_wp_config' ) ) {
				wp_schedule_event( current_time( 'timestamp' ), 'Monthly', 'quecodig_salts_to_wp_config' );
			}
			//v1.6.3.3
			if(empty(get_option(quecodig_sub))){
				add_option('quecodig_sub', 0);
			}
		}
	}

	add_action('plugins_loaded', 'quecodig_loadUpdate');

	if(!function_exists('quecodig_update_notify')){
		function quecodig_update_notify() {
			global $wp_version;
			$remote = wp_remote_get( wp_nonce_url(PLUGIN_API.'info.json?ver='.PLUGIN_VERSION.'&public='.get_option('quecodig_public').'&code='.get_option('quecodig_code')), array(
				'timeout' => 10,
				'headers' => array(
					'Accept' => 'application/json'
				),
				'user-agent' => 'WordPress/'.$wp_version.'; '.get_bloginfo('url') )
			);
			if(!is_wp_error($remote) && ($remote['response']['code'] == 200 || $remote['response']['code'] == 201)) {
				set_transient( 'quecodig_upgrade', $remote, 43200 ); // 12 hours cache
				$remote = json_decode( $remote['body'] );
				if( $remote && version_compare( PLUGIN_VERSION, $remote->version, '<' ) && version_compare($remote->requires, get_bloginfo('version'), '<' ) ) {
					set_site_transient( 'update_plugins', null );
					echo '<div class="updated"><p><strong>Hola, tenemos una nueva actualización:</strong>';
					echo ' Haz clic para <a class="button button-primary" href="' . wp_nonce_url(self_admin_url( 'update.php?action=upgrade-plugin&plugin=quecodigo/quecodigo.php' ), 'upgrade-plugin_quecodigo/quecodigo.php' ) . '">Actualizar</a>';
					echo '</p></div>';
				}else{
					update_option( 'quecodig_warnings', 0 );
				}
			}
		}
	}
	add_action( 'admin_footer', function(){
		if( ! wp_next_scheduled( 'quecodig_update_notify' ) ) {
			wp_schedule_event( current_time( 'timestamp' ), 'daily', 'quecodig_update_notify' );
		}
	});

	// Redirección a página de configuración.
	if(!function_exists('quecodig_register')){
		function quecodig_register(){	
			if ( 'pending' === get_option( 'quecodig_activation_welcome' ) ) {
				delete_option( 'quecodig_activation_welcome' );

				// Activación desde Network.
				if ( is_network_admin() || ( filter_input( INPUT_GET, 'activate-multi' ) !== null ) ) {
					return;
				}
				// Redirección a página de configuración.
				wp_safe_redirect( add_query_arg( array( 'page' => 'quecodigo_soporte' ), admin_url( 'admin.php' ) ) );
			}
		}
	}
	add_action( 'admin_init', 'quecodig_register' );

	// Remover plugin del directorio de WordPress
	if(!function_exists('quecodig_update_check')){
		function quecodig_update_check( $request, $url ) {
			// Plugin update request.
			if ( false !== strpos( $url, '//api.wordpress.org/plugins/update-check/1.1/' ) ) {

				// Decode JSON so we can manipulate the array.
				$data = json_decode( $request['body']['plugins'] );
				// Remove the Envato Market.
				unset( $data->plugins->{'quecodigo/quecodigo.php'} );
				// Encode back into JSON and update the response.
				$request['body']['plugins'] = wp_json_encode( $data );
			}

			return $request;
		}
	}
	add_filter( 'http_request_args', 'quecodig_update_check', 5, 2 );
