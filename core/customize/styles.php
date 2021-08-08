<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Bloquear acceso de manera directa.
	}

	if(!function_exists('quecodig_esquema_color')){
		function quecodig_esquema_color(){
			global $_wp_admin_css_colors;
			$_wp_admin_css_colors = 0;
		}
	}

	if(!function_exists('quecodig_replace_footer_version')){
		function quecodig_replace_footer_version(){
			$user_info = get_userdata(1);
			if( (get_option('quecodig_code') != "0") && (get_option('quecodig_public') != "0") && (get_option('quecodig_sub') === "1") ){
				echo '<div id="wmn-fx" ><div class="wmn-wrap"><div class="wmn-widget" style="background-color:#fff;"><a href="https://wa.me/+573014486070?text=Hola necesito ayuda con mi sitio web" target="_blank"><svg fill="#4fce50"  viewBox="0 0 90 90" width="32" height="32"><path d="M90,43.841c0,24.213-19.779,43.841-44.182,43.841c-7.747,0-15.025-1.98-21.357-5.455L0,90l7.975-23.522   c-4.023-6.606-6.34-14.354-6.34-22.637C1.635,19.628,21.416,0,45.818,0C70.223,0,90,19.628,90,43.841z M45.818,6.982   c-20.484,0-37.146,16.535-37.146,36.859c0,8.065,2.629,15.534,7.076,21.61L11.107,79.14l14.275-4.537   c5.865,3.851,12.891,6.097,20.437,6.097c20.481,0,37.146-16.533,37.146-36.857S66.301,6.982,45.818,6.982z M68.129,53.938   c-0.273-0.447-0.994-0.717-2.076-1.254c-1.084-0.537-6.41-3.138-7.4-3.495c-0.993-0.358-1.717-0.538-2.438,0.537   c-0.721,1.076-2.797,3.495-3.43,4.212c-0.632,0.719-1.263,0.809-2.347,0.271c-1.082-0.537-4.571-1.673-8.708-5.333   c-3.219-2.848-5.393-6.364-6.025-7.441c-0.631-1.075-0.066-1.656,0.475-2.191c0.488-0.482,1.084-1.255,1.625-1.882   c0.543-0.628,0.723-1.075,1.082-1.793c0.363-0.717,0.182-1.344-0.09-1.883c-0.27-0.537-2.438-5.825-3.34-7.977   c-0.902-2.15-1.803-1.792-2.436-1.792c-0.631,0-1.354-0.09-2.076-0.09c-0.722,0-1.896,0.269-2.889,1.344   c-0.992,1.076-3.789,3.676-3.789,8.963c0,5.288,3.879,10.397,4.422,11.113c0.541,0.716,7.49,11.92,18.5,16.223   C58.2,65.771,58.2,64.336,60.186,64.156c1.984-0.179,6.406-2.599,7.312-5.107C68.398,56.537,68.398,54.386,68.129,53.938z"></path></svg><span class="notification">1</span></a></div></div></div>';
				//echo '<script>window.chaportConfig = { appId : "5d03e38178b3b63a07887447", visitor: { name: "'.get_bloginfo( 'name' ).'", email: "'.$user_info->user_email.'", wightsKillCount: "Over 9000" }, language: {source: "html"}};</script>';
				//echo "<script>(function(w,d,v3){if(w.chaport)return;v3=w.chaport={};v3._q=[];v3._l={};v3.q=function(){v3._q.push(arguments)};v3.on=function(e,fn){if(!v3._l[e])v3._l[e]=[];v3._l[e].push(fn)};var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://app.chaport.com/javascripts/insert.js';var ss=d.getElementsByTagName('script')[0];ss.parentNode.insertBefore(s,ss)})(window, document);</script>";
			}
			return ' ';
		}
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
body::before, #wp-auth-check-wrap::before #wp-auth-check::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(66, 66, 66, 0.4) !important;
}
body, #wp-auth-check-wrap #wp-auth-check{
	background-color: rgba(66,66,66,1.0) !important;
	background-image: url("<?php echo plugins_url('assets/img/login-bg.png', QC_PLUGIN_FILE ); ?>") !important;
	background-size: cover !important;
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

	if(!function_exists('quecodig_admin_logo')){
		function quecodig_admin_logo() {
			?>
<style type="text/css">
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
#wpwrap .woocommerce-layout__header{
	top: 43px;
}
</style>
			<?php
		}
	}

	if(!function_exists('quecodig_admin_styles')){
		function quecodig_admin_styles () {
			wp_enqueue_style('quecodig-admin-style', plugins_url('assets/css/styles_admin.css?v='.QC_PLUGIN_VERSION, QC_PLUGIN_FILE ));
		}
	}

	if(!function_exists('quecodig_admin_bar_theme_style')){
		function quecodig_admin_bar_theme_style() {
			wp_enqueue_style('quecodig-admin-bar-style', plugins_url('assets/css/styles_adminbar.css?v='.QC_PLUGIN_VERSION, QC_PLUGIN_FILE ));
		}
	}