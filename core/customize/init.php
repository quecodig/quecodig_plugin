<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Bloquear acceso de manera directa.
	}

	include (QC_PLUGIN_PATH ."core/customize/text.php");
	include (QC_PLUGIN_PATH ."core/customize/menus.php");
	include (QC_PLUGIN_PATH ."core/customize/styles.php");
	include (QC_PLUGIN_PATH ."core/customize/options.php");

	//*****Text*****//
	add_action( 'admin_menu', 'quecodig_rename_menu_woo', 999 );

	//*****Styles*****//
	add_action('admin_head', 'quecodig_esquema_color');

	add_action('admin_head', 'quecodig_add_favicon');

	add_filter('update_footer', 'quecodig_replace_footer_version', 999);

	add_action('wp_head', 'quecodig_bar_color');

	add_action('login_enqueue_scripts', 'quecodig_custom_login',99);

	add_action('admin_menu', 'quecodig_admin_logo_url');

	add_action('admin_enqueue_scripts', 'quecodig_admin_logo');

	add_action ('admin_enqueue_scripts', 'quecodig_admin_styles');

	add_action( 'admin_enqueue_scripts', 'quecodig_admin_bar_theme_style' );

	//*****Options*****//
	add_action( 'login_enqueue_scripts', 'quecodig_logo' );

	add_filter( 'login_headerurl', 'quecodig_logo_url' );

	add_filter( 'login_headertext', 'quecodig_url_title' );

	add_filter( 'plugin_action_links_quecodigo/quecodigo.php', 'quecodig_settings_link' );

	add_filter( 'plugin_row_meta', 'quecodig_plugin_row_meta', 10, 4 );

	//*****Menus*****//
	add_action('after_setup_theme', 'quecodig_remove_admin_bar');
	
	add_action( 'admin_menu', 'quecodig_remove_menus', 99 );

	add_action( 'wp_before_admin_bar_render', 'quecodig_wp_remove', 0 );

	add_action('admin_bar_menu', 'quecodig_toolbar', 999);

	add_action( 'wp_before_admin_bar_render', 'quecodig_remove_links', 999 );

	add_action( 'admin_footer', function(){
	?>
	<script>
		jQuery("#setting-error-bigcart, #ga_widget_error, #vc_license-activation-notice, .updated.woocommerce-message").remove();
	</script>
	<?php
	});