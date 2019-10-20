<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Bloquear acceso de manera directa.
	}

	// Function to add admin menu
	if(!function_exists('quecodig_menu')){
		function quecodig_menu(){
			//Page title
			$page_title = "Asistente Qué Código";
			//Menu section title
			//$warning_count = get_option( 'quecodig_warnings' );
			$warning_count = 0;
			if($warning_count > 0){
				$menu_title =  sprintf( __( 'Soporte %s' ), "<span class='update-plugins count-$warning_count' title='notify'><span class='update-count'>" . number_format_i18n($warning_count) . "</span></span>" );
			}else{
				$menu_title =  'Soporte';
			}
			//User compatibility
			$capability = 'edit_posts';
			//Menu section icon url
			$url  = 'quecodigo_soporte';
			//Function to display the page
			$function   = 'quecodig_site_page';
			// Icon url
			$icon_url   = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAxOS45Ij48ZGVmcz48c3R5bGU+LmNscy0xe2lzb2xhdGlvbjppc29sYXRlO30uY2xzLTJ7ZmlsbDojZmZmO29wYWNpdHk6MC43O30uY2xzLTMsLmNscy00e2ZpbGw6I2VlZTt9LmNscy00e29wYWNpdHk6MC4xMTttaXgtYmxlbmQtbW9kZTpkaWZmZXJlbmNlO308L3N0eWxlPjwvZGVmcz48dGl0bGU+UmVjdXJzbyAxPC90aXRsZT48ZyBjbGFzcz0iY2xzLTEiPjxnIGlkPSJDYXBhXzIiIGRhdGEtbmFtZT0iQ2FwYSAyIj48ZyBpZD0iQ2FwYV8xLTIiIGRhdGEtbmFtZT0iQ2FwYSAxIj48cGF0aCBjbGFzcz0iY2xzLTIiIGQ9Ik0xMy41MSwxMS42OGE0LjkzLDQuOTMsMCwwLDEsNS4xNC00LjQ1YzEuNTYsMC0xLjcyLjE2LTIsMy4zN0MxNi4yNiwxNC4zLDEzLjM3LDEzLjI0LDEzLjUxLDExLjY4WiIvPjxwYXRoIGNsYXNzPSJjbHMtMyIgZD0iTTIxLjE4LDcuNTZjMCwuMSwwLC4yLDAsLjMsMCwzLjM5LTIuNjEsNS43NC00LjU5LDgtLjI3LjMtLjY1LjcxLTEuMDgsMS4xNSwxLjQsMS40MywzLDIuOTQsMywyLjk0czItMiwyLjc0LTIuOEMyMi40NCwxNS43OCwyNCwxNC4zOCwyNCwxMi4zNkE1LjQ5LDUuNDksMCwwLDAsMjEuMTgsNy41NloiLz48cGF0aCBjbGFzcz0iY2xzLTMiIGQ9Ik02Ljg2LDE1LjQ3QzQuOCwxMy4zNiwyLjgxLDExLDIuODEsNy44NmMwLS4xLDAtLjIsMC0uM0E1LjQ5LDUuNDksMCwwLDAsMCwxMi4zNmMwLDEuODksMS4xOSwzLjI4LDIuNDIsNC41NCwxLjQxLDEuNDQsMy4wNiwzLDMuMDYsM3MyLTIsMi43NC0yLjhMOC4zNSwxN0M3Ljg1LDE2LjQ4LDcuMzUsMTYsNi44NiwxNS40N1oiLz48cGF0aCBjbGFzcz0iY2xzLTMiIGQ9Ik0xMiwxOC44OGMtLjg3LS44My0yLjcxLTIuNTktNC4zMi00LjI0QzUuODQsMTIuNzcsNC4wNSwxMC43MSw0LjA1LDhBOCw4LDAsMCwxLDIwLDhjMCwyLjUzLTEuNzQsNC40Mi0zLjQzLDYuMjRMMTUuOCwxNUMxNSwxNS45MiwxMi45MywxOCwxMiwxOC44OFoiLz48cGF0aCBjbGFzcz0iY2xzLTIiIGQ9Ik00LjQ5LDcuNDNjLjQxLTQuNSw0LjE2LTcsOC4xNS03LDIuNDcsMC0yLjczLjI2LTMuMjMsNS4zNUM4Ljg0LDExLjU4LDQuMjYsOS45MSw0LjQ5LDcuNDNaIi8+PHBhdGggY2xhc3M9ImNscy00IiBkPSJNMTIsMTguODhjLjk0LS45MywzLTMsMy44MS0zLjlsLjcyLS43OUMxOC4yMSwxMi4zNywyMCwxMC40OCwyMCw4QTcuODksNy44OSwwLDAsMCwxOC4yMSwzQzE2LjI5LDEzLjM5LDcuNjcsMTQuNjQsNy42NywxNC42NCw5LjI4LDE2LjI5LDExLjEyLDE4LjA1LDEyLDE4Ljg4WiIvPjwvZz48L2c+PC9nPjwvc3ZnPg==';
			// Position in the menu
			$position   = 61;
			// We add the options
			add_menu_page($page_title, $menu_title, $capability, $url, $function, $icon_url, $position);
		}
	}

	function quecodig_support_data($code = false, $public = false, $cron = true){
		global $wp_version;
		if(($code == false) && ($public == false) && (get_option('quecodig_code') != "0") && (get_option('quecodig_public') != "0")){
			$code = get_option('quecodig_code');
			$public = get_option('quecodig_public');
		}
		if( ($cron == true) || ( check_admin_referer("quecodig_action_nonce") ) ){
			$args = array(
				'method' => 'POST',
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(),
				'body' => array( 'code' => $code, 'public' => $public),
				'user-agent' => 'WordPress/'.$wp_version.'; '.get_bloginfo('url'),
				'cookies' => array()
			);
			$response = wp_remote_post( PLUGIN_API.'verify.php', $args );
			if(!is_wp_error($response) && ($response['response']['code'] == 200 || $response['response']['code'] == 201)) {
				$response = json_decode( wp_remote_retrieve_body($response), true );
				if($response["success"] === true){
					update_option("quecodig_sub", 1);
					update_option("quecodig_code", $code);
					update_option("quecodig_public", $public);
					if($cron == false){
						wp_safe_redirect( add_query_arg( array( 'page' => 'quecodigo_soporte' ), admin_url( 'admin.php' ) ) );
					}
				}else if(($response["success"] === false) && ($response["error"]) === false){
					update_option("quecodig_sub", 2);
					update_option("quecodig_code", $code);
					update_option("quecodig_public", $public);
					if($cron == false){
						wp_safe_redirect( add_query_arg( array( 'page' => 'quecodigo_soporte', 'vencido' => true), admin_url( 'admin.php' ) ) );
					}
				}else{
					if($cron == false){
						wp_safe_redirect( add_query_arg( array( 'page' => 'quecodigo_soporte', 'data_error' => 'true' ), admin_url( 'admin.php' ) ) );
					}
				}
			}else{
				if($cron == false){
					wp_safe_redirect( add_query_arg( array( 'page' => 'quecodigo_soporte', 'data_error' => 'true' ), admin_url( 'admin.php' ) ) );
				}
			}
		}
	}

	// Function to display the page
	if(!function_exists('quecodig_site_page')){
		function quecodig_site_page() {
			if ( !current_user_can( 'edit_posts' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}
			if(isset($_GET["force_support"])){
				quecodig_support_data(false, false, false);
			}
			if(isset($_GET["force_support"])){
				quecodig_update_notify();
			}
			if(isset($_POST["code"]) && isset($_POST["public"])){
				$code = esc_sql($_POST["code"]);
				$public = esc_sql($_POST["public"]);
				quecodig_support_data($code, $public, false);
				if( ! wp_next_scheduled( 'quecodig_support_data' ) ) {
					wp_schedule_event( current_time( 'timestamp' ), 'Monthly', 'quecodig_support_data' );
				}
			}
			// We include the page, separately for order
			include QC_PLUGIN_PATH."admin/soporte.php";
		}
	}
	add_action( 'admin_menu', 'quecodig_menu' );

	// Function to print required scripts
	if(!function_exists('quecodig_load_scripts')){
		function quecodig_load_scripts($hook) {
			global $woocommerce;
			//print_r($hook);
			$user_info = get_userdata(1);
			if( (get_option('quecodig_code') != "0") && (get_option('quecodig_public') != "0") && (get_option('quecodig_sub') === "1") ){
				if($hook != 'plugin-install.php' || $hook != 'update-core.php' || $hook != 'update.php'){
					echo '<script>window.chaportConfig = { appId : "5d03e38178b3b63a07887447", visitor: { name: "'.get_bloginfo( 'name' ).'", email: "'.$user_info->user_email.'", wightsKillCount: "Over 9000" }, language: {source: "html"}};</script>';
					echo "<script>(function(w,d,v3){if(w.chaport)return;v3=w.chaport={};v3._q=[];v3._l={};v3.q=function(){v3._q.push(arguments)};v3.on=function(e,fn){if(!v3._l[e])v3._l[e]=[];v3._l[e].push(fn)};var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://app.chaport.com/javascripts/insert.js';var ss=d.getElementsByTagName('script')[0];ss.parentNode.insertBefore(s,ss)})(window, document);</script>";
				}
			}
        	wp_enqueue_script( 'quecodig_script', plugins_url( 'assets/js/all.js', __FILE__ ), false, PLUGIN_VERSION);
        	//echo '<script type="text/javascript" src="'.plugins_url('assets/js/all.js?qcv='.PLUGIN_VERSION, QC_PLUGIN_FILE ).'"></script>';
			wp_register_style( 'quecodig_styles', plugins_url( 'assets/css/style.css', QC_PLUGIN_FILE ), false, PLUGIN_VERSION );
			//echo '<link rel="stylesheet" href="'.plugins_url( 'assets/css/style.css?qcv='.PLUGIN_VERSION, QC_PLUGIN_FILE ).'">';
			wp_register_style( 'quecodig_content', plugins_url('assets/css/content.css', QC_PLUGIN_FILE ), false, PLUGIN_VERSION );
			//echo '<link rel="stylesheet" href="'.plugins_url('assets/css/content.css?qcv='.PLUGIN_VERSION, QC_PLUGIN_FILE ).'">';
			if( is_admin() ) {
				$screen = get_current_screen();
				if( $screen->base == 'dashboard_page_custom-dashboard' ) {
					wp_register_style( 'quecodig_dashboard', plugins_url('assets/css/dashboard.css', QC_PLUGIN_FILE ), false, PLUGIN_VERSION );
					//echo '<link rel="stylesheet" href="'.plugins_url( 'assets/css/dashboard.css?qcv='.PLUGIN_VERSION, QC_PLUGIN_FILE ).'">';
					if(is_plugin_active( 'woocommerce/woocommerce.php' )){
						wp_register_style( 'quecodig_woocommerce', $woocommerce->plugin_url().'/assets/css/dashboard.css?ver=3.7.0', false, PLUGIN_VERSION );
						//echo '<link rel="stylesheet" href="'.$woocommerce->plugin_url().'/assets/css/dashboard.css?ver=3.7.0">';
					}
				}
			}
			wp_enqueue_style( 'quecodig_styles' );
			wp_enqueue_style( 'quecodig_content' );
			wp_enqueue_style( 'quecodig_dashboard' );
			wp_enqueue_style( 'quecodig_woocommerce' );
		}
	}
	add_action('admin_enqueue_scripts', 'quecodig_load_scripts');

	require QC_PLUGIN_PATH ."core/vendor/googleanalytics/googleanalytics.php";
	foreach ($core_inc as $file) {
		require QC_PLUGIN_PATH ."core/". $file."/init.php";
	}
	require QC_PLUGIN_PATH ."core/plugin_update.php";