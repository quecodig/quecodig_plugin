<?php
	function quecodig_mul_update_check( $request, $url ) {
		if ( false !== strpos( $url, '//api.wordpress.org/themes/update-check/1.1/' ) ) {
			/**
			 * Excluded theme slugs that should never ping the WordPress API.
			 * We don't need the extra http requests for themes we know are premium.
			 */
			// Decode JSON so we can manipulate the array.
			$data = json_decode( $request['body']['themes'] );
			// Remove the excluded themes.
			unset( $data->themes->{'wp-arduino'} );
			// Encode back into JSON and update the response.
			$request['body']['themes'] = wp_json_encode( $data );
		}
		// Plugin update request.
		if ( false !== strpos( $url, '//api.wordpress.org/plugins/update-check/1.1/' ) ) {
			// Decode JSON so we can manipulate the array.
			$data = json_decode( $request['body']['plugins'] );
			// Remove the Envato Market.
			unset( $data->plugins->{'qc_plugin/qc_plugin.php'} );
			// Encode back into JSON and update the response.
			$request['body']['plugins'] = wp_json_encode( $data );
		}
		return $request;
	}

	function quecodig_mul_update_plugins( $transient ) {
		global $wp_version;

		if ( empty($transient->checked ) ) {
			return $transient;
		}

		// trying to get from cache first, to disable cache comment 10,20,21,22,24
		if( false == $remote = get_transient( 'quecodig_mu_upgrade' ) ) {

			// info.json is the file with the actual plugin information on your server
			$remote = wp_remote_get( wp_nonce_url(QC_PLUGIN_API.'prueba.json?ver='.QC_PLUGIN_VERSION.'&public='.get_option('quecodig_public').'&code='.get_option('quecodig_code')), array(
				'timeout' => 10,
				'headers' => array(
					'Accept' => 'application/json'
				),
				'user-agent' => 'WordPress/'.$wp_version.'; '.get_bloginfo('url') )
			);

			if ( !is_wp_error( $remote ) && isset( $remote['response']['code'] ) && $remote['response']['code'] == 200 && !empty( $remote['body'] ) ) {
				set_transient( 'quecodig_mu_upgrade', $remote, 43200 ); // 12 hours cache
			}

		}

		if(!is_wp_error($remote) && ($remote['response']['code'] == 200 || $remote['response']['code'] == 201)) {
			$remote = json_decode( $remote['body'] );
			// your installed plugin version should be on the line below! You can obtain it dynamically of course 
			if( $remote && version_compare( '1.0', $remote->version, '<' ) && version_compare($remote->requires, get_bloginfo('version'), '<' ) ) {
				$res = new stdClass();
				$res->slug = 'qc_plugin';
				$res->plugin = 'qc_plugin/qc_plugin.php'; // it could be just YOUR_PLUGIN_SLUG.php if your plugin doesn't have its own directory
				$res->new_version = $remote->version;
				$res->tested = $remote->tested;
				$res->package = $remote->download_url;
				$res->url = $remote->author_homepage;
				$transient->response[$res->plugin] = $res;
				$transient->checked[$res->plugin] = $remote->version;
				$res->icons = array(
					"svg" => plugins_url( "assets/img/logo-azul.svg" , QC_PLUGIN_FILE),
				);
				update_option( 'quecodig_warnings', 1);
			}

		}
		return $transient;
	}

	function quecodig_mul_plugins_api($res, $action, $args) {
		global $wp_version;
			
		// do nothing if this is not about getting plugin information
		if( $action !== 'plugin_information' )
			return false;

		// do nothing if it is not our plugin
		if( 'qc_plugin' !== $args->slug )
			return $res;

		// info.json is the file with the actual plugin information on your server
		$remote = wp_remote_get( wp_nonce_url(QC_PLUGIN_API.'prueba.json?ver='.QC_PLUGIN_VERSION.'&public='.get_option('quecodig_public').'&code='.get_option('quecodig_code')), array(
			'timeout' => 10,
			'headers' => array(
				'Accept' => 'application/json'
			),
			'user-agent' => 'WordPress/'.$wp_version.'; '.get_bloginfo('url') )
		);

		if ( !is_wp_error( $remote ) && isset( $remote['response']['code'] ) && $remote['response']['code'] == 200 && !empty( $remote['body'] ) ) {
			set_transient( 'quecodig_mu_upgrade', $remote, 43200 ); // 12 hours cache
		}

		if(!is_wp_error($remote) && ($remote['response']['code'] == 200 || $remote['response']['code'] == 201)) {

			$remote = json_decode( $remote['body'] );
			$res = new stdClass();
			$res->name = $remote->name;
			$res->slug = 'qc_plugin';
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
				'low' => QC_PLUGIN_API.'hero.jpg', //banner-772x250.jpg
				'high' => QC_PLUGIN_API.'hero.jpg' //banner-1544x500.jpg
			);
			return $res;

		}

		return false;
	}

	if(!function_exists('quecodig_mu_update_notify')){
		function quecodig_mu_update_notify() {
			global $wp_version;
			$remote = wp_remote_get( wp_nonce_url(QC_PLUGIN_API.'prueba.json?ver='.QC_PLUGIN_VERSION.'&public='.get_option('quecodig_public').'&code='.get_option('quecodig_code')), array(
				'timeout' => 10,
				'headers' => array(
					'Accept' => 'application/json'
				),
				'user-agent' => 'WordPress/'.$wp_version.'; '.get_bloginfo('url') )
			);
			if(!is_wp_error($remote) && ($remote['response']['code'] == 200 || $remote['response']['code'] == 201)) {
				set_transient( 'quecodig_upgrade', $remote, 43200 ); // 12 hours cache
				$remote = json_decode( $remote['body'] );
				if( $remote && version_compare( '1.0', $remote->version, '<' ) && version_compare($remote->requires, get_bloginfo('version'), '<' ) ) {
					set_site_transient( 'update_plugins', null );
					update_option( 'quecodig_warnings', 1 );
					echo '<div class="updated"><p><strong>Hola, tenemos una nueva actualización:</strong>';
					echo ' Haz clic para <a class="button button-primary" href="' . wp_nonce_url(self_admin_url( 'update.php?action=upgrade-plugin&plugin=qc_plugin/qc_plugin.php' ), 'upgrade-plugin_qc_plugin/qc_plugin.php' ) . '">Actualizar</a>';
					echo '</p></div>';
				}else{
					update_option( 'quecodig_warnings', 0 );
				}
			}
		}
	}
	add_action( 'admin_footer', 'quecodig_mu_update_notify' );