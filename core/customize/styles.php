<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Bloquear acceso de manera directa.
	}

	function quecodig_esquema_color(){
		global $_wp_admin_css_colors;
		$_wp_admin_css_colors = 0;
	}

	function quecodig_add_favicon(){
		$url = plugins_url('assets/img/logo-azul.svg', QC_PLUGIN_FILE );
		$file_header = get_headers($url);
		$url = (strpos($file_header[0], '404') === false) ? $url : '';
		echo "<link rel='icon' href='{$url}' type='image/svg+xml' /> \n";
	}

	function quecodig_replace_footer_version(){
		return ' ';
	}

	if(!function_exists('quecodig_bar_color')){
		function quecodig_bar_color() {
			wp_enqueue_style('quecodig-bar-style', plugins_url('assets/css/styles_bar.css', QC_PLUGIN_FILE ));
		}
	}

	if(!function_exists('quecodig_custom_login')){
		function quecodig_custom_login() {

			$url = plugins_url('/', QC_PLUGIN_FILE).'assets/css/styles_login.css';
			wp_deregister_style('qc-login');
			wp_register_style('qc-login', $url);
			wp_enqueue_style('qc-login');

			?>
<style type='text/css'>
body, #wp-auth-check-wrap #wp-auth-check{
	background-color: rgba(66,66,66,1.0) !important;
	background-image: url("<?php echo plugins_url('assets/img/login-bg.png', QC_PLUGIN_FILE ); ?>") !important;
	background-position: center center !important;
}
.login form .input[type=text],.login form .input[type=password]{
	border: 1px solid rgba(125,125,125,0.2);
	padding: 5px;
}
#woocommerce_dashboard_status .wc_status_list li a::before{
	height:auto;
}
</style>
			<?php
		}
	}

	if(!function_exists('quecodig_admin_logo_url')){
		function quecodig_admin_logo_url() {
			global $menu;
			$url = get_admin_url();
			$menu[0] = array( __('QuéCódigo'), 'read', $url, 'quecodigo-logo', 'quecodigo-logo');
		}
	}

	if(!function_exists('quecodig_admin_logo')){
		function quecodig_admin_logo() {
			?>
<style type="text/css">
#adminmenu a.quecodigo-logo{
	display: block;
	background: url(<?php echo plugins_url('assets/img/logo.png', QC_PLUGIN_FILE ); ?>) no-repeat center center;
	background-size: 140px auto;
	width: 140px;
	height: 35px;
	margin: 0 auto;
	padding: 10px 0px;
	padding-bottom: 10px !important;
}
.folded #adminmenu a.quecodigo-logo,
.folded #adminmenu li.menu-top a.quecodigo-logo,
.folded #adminmenuback, .folded #adminmenuwrap a.quecodigo-logo{
	background: url(<?php echo plugins_url('assets/img/logo-azul.svg', QC_PLUGIN_FILE ); ?>) no-repeat center !important;
	width: 30px;
	height: 30px;
}

#adminmenu a.quecodigo-logo div.wp-menu-name {
	display: none;
}
#adminmenu a.current.toplevel_page_quecodigo_soporte div.wp-menu-image.svg,
#adminmenu a.wp-has-current-submenu.toplevel_page_quecodigo_soporte div.wp-menu-image.svg{
	background: url(<?php echo plugins_url('assets/img/logo-azul.svg', QC_PLUGIN_FILE ); ?>) no-repeat center !important;
	background-size: 22px !important;
}
#adminmenu #toplevel_page_woocommerce .menu-icon-generic div.wp-menu-image::before{
	content: "" !important;
	background: url(<?php echo plugins_url('assets/img/tienda.svg', QC_PLUGIN_FILE ); ?>) no-repeat center !important;
	background-size: 22px !important;
}
</style>
			<?php
		}
	}

	if(!function_exists('quecodig_admin_styles')){
		function quecodig_admin_styles () {
			wp_enqueue_style('quecodig-admin-style', plugins_url('assets/css/styles_admin.css', QC_PLUGIN_FILE ));
		}
	}

	if(!function_exists('quecodig_admin_bar_theme_style')){
		function quecodig_admin_bar_theme_style() {
			wp_enqueue_style('quecodig-admin-bar-style', plugins_url('assets/css/styles_adminbar.css', QC_PLUGIN_FILE ));
		}
	}