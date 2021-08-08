<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Bloquear acceso de manera directa.
	}
	
	// Hide Link login
	function quecodig_login_head(){
		$EHL_slug =  get_option('quecodig_slug_link');
		if( isset($_POST['redirect_slug']) && $_POST['redirect_slug'] == $EHL_slug){
			return false;
		}

		if( strpos($_SERVER['REQUEST_URI'], 'action=logout') !== false ){
			check_admin_referer( 'log-out' );
			$user = wp_get_current_user();
			wp_logout();
			wp_safe_redirect( home_url(), 302 );
			die;
		}
		
		if( ( strpos($_SERVER['REQUEST_URI'], $EHL_slug) === false  ) &&
			( strpos($_SERVER['REQUEST_URI'], 'wp-login.php') !== false  ) ){
			if(is_user_logged_in()){
				wp_safe_redirect( home_url( 'wp-admin' ), 302 );
			}else{
				wp_safe_redirect( home_url( '404' ), 302 );
			}
			exit();
		}
	}

	function quecodig_login_hidden_field(){
		$EHL_slug = get_option('quecodig_slug_link','');
		?>
		<input type="hidden" name="redirect_slug" value="<?php echo $EHL_slug ?>" />
		<?php 
	}

	function quecodig_login_init(){
		$EHL_slug =  get_option('quecodig_slug_link');
		if(parse_url($_SERVER['REQUEST_URI'],PHP_URL_QUERY) == $EHL_slug ){
			wp_safe_redirect(home_url("wp-login.php?$EHL_slug&redirect=false"));
			exit();
		}
	}

	function quecodig_login_lostpassword() {
		$EHL_slug =  get_option('quecodig_slug_link');
		return site_url("wp-login.php?action=lostpassword&$EHL_slug&redirect=false");
	}

	//logout url
	//add_filter( 'logout_url', 'quecodig_logout', 10, 2 );
	function quecodig_logout( $logout_url) {
		return home_url();
	}

	// This sends the user back to the login page after the password reset email has been sent. This is the same behaviour as vanilla WordPress
	function quecodig_login_lostpassword_redirect($lostpassword_redirect) {
		$EHL_slug = get_option('quecodig_slug_link');
		return 'wp-login.php?checkemail=confirm&redirect=false&' . $EHL_slug;
	}

	// Step 1.
	function quecodig_login_plugin_menu() {
		$usuario = wp_get_current_user();
  		if(($usuario->user_login === "admin") || ($usuario->user_login === "quecodigo") || ($usuario->user_email  === "webmaster@quecodigo.com")){
			add_submenu_page( 'quecodigo_soporte', 'Ocultar inicio de sesión', 'inicio de sesión', 'manage_options', 'quecodig_hide', 'quecodig_login_plugin_options' );
  		}
	}

	// Step 3.
	function quecodig_login_plugin_options() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		} 

		if ( (isset($_POST['slug'])) && check_admin_referer("quecodig_hide") ) {

			$slug = esc_sql($_POST['slug']);
			update_option('quecodig_slug_link',$slug);

		}

		$slug = get_option('quecodig_slug_link','');
		$nonce = wp_create_nonce('quecodig_hide');
	?>
	<div class="wrap quecodig_plugin" style="margin-top: 40px;">
		<div class="wrap content-api" id="main">
			<div class="main" id="panel">
				<div class="config">
					<h2>Ocultar inicio de sesión</h2>
					<form action="admin.php?page=quecodig_hide&_wpnonce=<?php echo $nonce; ?>" method="POST">
						<div class="form-group">
							<label> Slug:</label>
							<input class="form-control" type="text" value="<?=$slug?>" name = "slug" class="slug">
						</div>
						<div class="row2">Url actual:  <b><?php echo site_url(); ?>/<?php if($slug != ""){ echo "?".$slug; }else{ echo "wp-admin/"; } ?></b></div>
						<div class="form-group"><input class="form-control btn" type="submit" class="submit_admin" value="Guardar"></div>
					</form>
				</div>
			</div>
		</div>
	</div>
		<?php 
	}

	//Secure login, remplace login error message
	//Fuente: https://wordpress.stackexchange.com/a/233216
	if(!function_exists('quecodig_error_login_messages')){
		function quecodig_error_login_messages($error){
			global $errors;

			$err_codes = $errors->get_error_codes();

			// Invalid username.
			// Default: '<strong>ERROR</strong>: Invalid username. <a href="%s">Lost your password</a>?'
			if ( in_array( 'invalid_username', $err_codes ) ) {
				$error = '<strong>ERROR</strong>: Datos incorrectos. <a href="wp-login.php?action=lostpassword&panel-administracion&redirect=false">¿Has olvidado tus datos?</a>';
			}

			// Invalid email.
			if ( in_array( 'invalid_email', $err_codes ) ) {
				$error = '<strong>ERROR</strong>: Datos incorrectos. <a href="wp-login.php?action=lostpassword&panel-administracion&redirect=false">¿Has olvidado tus datos?</a>';
			}

			// Incorrect password.
			if ( in_array( 'incorrect_password', $err_codes ) ) {
				$error = '<strong>ERROR</strong>: Datos incorrectos. <a href="wp-login.php?action=lostpassword&panel-administracion&redirect=false">¿Has olvidado tus datos?</a>';
			}

			return $error;
		}
	}