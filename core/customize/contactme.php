<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Bloquear acceso de manera directa.
	}

	function option_exists($name, $site_wide=false){
		global $wpdb; return $wpdb->query("SELECT * FROM ". ($site_wide ? $wpdb->base_prefix : $wpdb->prefix). "options WHERE option_name ='$name' LIMIT 1");
	}

	function quecodig_contacme_plugin_menu(){
		add_submenu_page( 'quecodigo_soporte', 'Configurar Contacto', 'WhatsApp config', 'manage_options', 'quecodig_contactme', 'quecodig_contactme_plugin_options' );
	}

	function quecodig_contactme_plugin_options(){
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		if ( (isset($_POST['active'])) && (isset($_POST['number'])) && (isset($_POST['message'])) && check_admin_referer("quecodig_contactme")) {
			if(!option_exists('quecodig_contactme')){
				add_option('quecodig_contactme',array());
			}
			$active = esc_sql($_POST['active']);
			$style = esc_sql($_POST['style']);
			$number = esc_sql($_POST['number']);
			$message = esc_sql($_POST['message']);
			update_option('quecodig_contactme',array('active'=>$active, 'number'=>$number, 'message'=>$message, 'style'=>$style));
		}

		$contactme = get_option('quecodig_contactme','');
		$nonce = wp_create_nonce('quecodig_contactme');

		?>

		<div class="wrap quecodig_plugin" style="margin-top: 40px;">
			<div class="wrap content-api" id="main">
				<div class="main" id="home">
					<div class="text">
						<div class="logo">
							<img src="<?php echo plugins_url( 'assets/img/logo-azul.svg', QC_PLUGIN_FILE ); ?>" alt="">
						</div>
						<div class="welcome">
							<h2>Configurar integración de WhatsApp</h2>
							<p>Integra WhatsApp a tu página web para que tus potenciales clientes tengan la facilidad para comunicarse contigo. <br> Al activar esta característica se añadirá un icono de WhatsApp en la zona inferior derecha de tu página web. <br> Solo debes agregar el número con indicativo del país.</p>
						</div>
					</div>
				</div>
				<div class="main" id="panel">
					<div class="config">
						<form action="admin.php?page=quecodig_contactme&_wpnonce=<?php echo $nonce; ?>" method="POST" autocomplete="off" autofocus="false">
							<div class="form-group">
								<label> Activar integración:</label>
								<select name="active" class="form-control" style="display: inline-block;width: 50%;">
									<option value="0" <?php if(@$contactme['active'] == 0){ echo 'selected="true"'; } ?>>Desactivado</option>
									<option value="1" <?php if(@$contactme['active'] == 1){ echo 'selected="true"'; } ?>>Activado</option>
								</select>
							</div>
							<div class="form-group">
								<label> Estilos:</label>
								<select name="style" class="form-control" style="display: inline-block;width: 50%;">
									<option value="0" <?php if(@$contactme['style'] == 0){ echo 'selected="true"'; } ?>>Estilo 1</option>
									<option value="1" <?php if(@$contactme['style'] == 1){ echo 'selected="true"'; } ?>>Estilo 2</option>
									<option value="2" <?php if(@$contactme['style'] == 2){ echo 'selected="true"'; } ?>>Estilo 3</option>
								</select>
							</div>
							<div class="form-group">
								<label> Número: (+573001234567)</label>
								<input class="form-control" type="text" value="<?=@$contactme['number']?>" name="number" class="number" placeholder="Numero de telefono ejem: +573001234567" autocomplete="off">
							</div>
							<div class="form-group">
								<label> Mensaje personalizado:</label>
								<input class="form-control" type="text" value="<?=@$contactme['message']?>" name="message" class="message" placeholder="Mensaje personalizado" autocomplete="off">
							</div>
							<div class="form-group"><input class="form-control btn" type="submit" class="submit_admin" value="Guardar"></div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php 
	}

	function quecodigo_contactme_dialog_on_front(){
		if(option_exists('quecodig_contactme')){
			$contactme = get_option('quecodig_contactme','');
			if($contactme['active'] == 1){
				$styles = array(
					'0' => array(
						'text' => '#FFFFFF',
						'back' => '#4fce50'
					),
					'1' => array(
						'text' => '#4fce50',
						'back' => '#FFFFFF'
					),
					'2' => array(
						'text' => '#FFFFFF',
						'back' => '#000000'
					)
				);

				$style = ($contactme['style']) ? $contactme['style'] : '0';

				$whatsapp = $contactme['number'];
				$message = $contactme['message'];
				$ct ='<div id="wmn-fx" >';
				$ct .='<div class="wmn-wrap">';
				$ct .= '<div class="wmn-widget" style="background-color:'.$styles[$style]['back'].';">';
				$ct .= '<a href="https://wa.me/'.$whatsapp.'?text='.($message).'" target="_blank">';
				$ct .= '<svg fill="'.$styles[$style]['text'].'"  viewBox="0 0 90 90" width="32" height="32"><path d="M90,43.841c0,24.213-19.779,43.841-44.182,43.841c-7.747,0-15.025-1.98-21.357-5.455L0,90l7.975-23.522   c-4.023-6.606-6.34-14.354-6.34-22.637C1.635,19.628,21.416,0,45.818,0C70.223,0,90,19.628,90,43.841z M45.818,6.982   c-20.484,0-37.146,16.535-37.146,36.859c0,8.065,2.629,15.534,7.076,21.61L11.107,79.14l14.275-4.537   c5.865,3.851,12.891,6.097,20.437,6.097c20.481,0,37.146-16.533,37.146-36.857S66.301,6.982,45.818,6.982z M68.129,53.938   c-0.273-0.447-0.994-0.717-2.076-1.254c-1.084-0.537-6.41-3.138-7.4-3.495c-0.993-0.358-1.717-0.538-2.438,0.537   c-0.721,1.076-2.797,3.495-3.43,4.212c-0.632,0.719-1.263,0.809-2.347,0.271c-1.082-0.537-4.571-1.673-8.708-5.333   c-3.219-2.848-5.393-6.364-6.025-7.441c-0.631-1.075-0.066-1.656,0.475-2.191c0.488-0.482,1.084-1.255,1.625-1.882   c0.543-0.628,0.723-1.075,1.082-1.793c0.363-0.717,0.182-1.344-0.09-1.883c-0.27-0.537-2.438-5.825-3.34-7.977   c-0.902-2.15-1.803-1.792-2.436-1.792c-0.631,0-1.354-0.09-2.076-0.09c-0.722,0-1.896,0.269-2.889,1.344   c-0.992,1.076-3.789,3.676-3.789,8.963c0,5.288,3.879,10.397,4.422,11.113c0.541,0.716,7.49,11.92,18.5,16.223   C58.2,65.771,58.2,64.336,60.186,64.156c1.984-0.179,6.406-2.599,7.312-5.107C68.398,56.537,68.398,54.386,68.129,53.938z"></path></svg>';
				$ct .= '<span class="notification">1</span>';
				$ct .= '</a>';
				$ct .= '</div>';
				$ct .= '</div></div>';
				echo $ct;
			}
			return "";
		}
	}

	function quecodigo_contactme_style(){
		wp_enqueue_style( 'quecodig_contactme_style', plugins_url('assets/css/contactme_style.css', QC_PLUGIN_FILE ));
	}