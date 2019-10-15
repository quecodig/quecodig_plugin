<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Bloquear acceso de manera directa.
	}
?>
<div class="wrap quecodig_plugin" style="margin-top: 40px;">
	<div class="wrap content-api" id="main">
		<?php
			if(isset($_GET["vencido"])):
		?>
		<div class="alert warning">
			<p>Error el id a consultar ya vencio.</p>
			<script>
				history.pushState({data:true}, 'Titulo', '<?php echo add_query_arg( array( 'page' => 'quecodigo_soporte'), admin_url( 'admin.php' ) ); ?>');
			</script>
		</div>
		<?php
			endif;
			if(isset($_GET["data_error"])):
		?>
		<div class="alert warning">
			<p>Error al consultar los datos en el servidor. Intentalo nuevamente</p>
			<script>
				history.pushState({data:true}, 'Titulo', '<?php echo add_query_arg( array( 'page' => 'quecodigo_soporte'), admin_url( 'admin.php' ) ); ?>');
			</script>
		</div>
		<?php
			endif;
			if(isset($_GET["permissions"])):
		?>
			<div class="alert warning">
				<p>No tienes permisos para realizar esta acción, consulta tu manual de usuario.</p>
				<script>
					history.pushState({data:true}, 'Titulo', '<?php echo add_query_arg( array( 'page' => 'quecodigo_soporte'), admin_url( 'admin.php' ) ); ?>');
				</script>
			</div>
		<?php endif; ?>
		<div class="main" id="home">
			<div class="text">
				<div class="logo">
					<img src="<?php echo plugins_url( 'assets/img/logo-azul.svg', QC_PLUGIN_FILE ); ?>" alt="">
				</div>
				<div class="welcome">
					<h2>¡Bienvenido al centro de Ayuda!</h2>
					<p>Aquí encontraras toda la información necesaria para saber manejar y administrar tu sitio web, los datos de usuario se te fueron entregados a la finalización del contrato.</p>
				</div>
			</div>
		</div>
		<?php
			if((get_option('quecodig_code') == 0) && (get_option('quecodig_public') == 0) && (get_option("quecodig_sub") == 0)){
		?>
		<div class="main" id="panel">
			<div class="config">
				<p>Configuración de la cuenta de usuario registrada en el sistema de Qué Código</p>
				<form action="<?php echo wp_nonce_url(add_query_arg( array( 'page' => 'quecodigo_soporte' ), admin_url( 'admin.php' ) ), 'quecodig_action_nonce')?>" method="post" autocomplete="off">
					<div class="form-group">
						<input class="form-control" type="text" name="code" placeholder="code" value="<?php echo get_option("code"); ?>" required>
					</div>
					<div class="form-group">
						<input class="form-control" type="text" name="public" placeholder="Public Key" value="<?php echo get_option("public"); ?>" required>
					</div>
					<div class="form-group">
						<input class="form-control btn" type="submit" name="submit" value="Validar" required>
					</div>
				</form>
			</div>
		</div>
		<?php
			}else if(get_option('quecodig_sub') == "2"){
		?>
		<div class="main" id="panel">
			<button class="accordion">Soporte especializado</button>
			<div class="panel">
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maiores voluptatibus, at itaque autem ducimus, voluptates perferendis reiciendis, quaerat alias soluta officiis eum odit magni cum recusandae inventore quo tempore incidunt?</p>
			</div>
		</div>
		<?php
			}
			$args = array(
				'method' => 'GET',
			);
			$response = wp_remote_request( PLUGIN_API.'request.php', $args );
			if(!is_wp_error($response) && ($response['response']['code'] == 200 || $response['response']['code'] == 201)) {
				$body = json_decode( wp_remote_retrieve_body( $response ) );
				foreach ( $body as $key) {
					$content = preg_replace("/\[br\]/is", "<br>", $key->content);
		?>
		<div class="main" id="panel">
			<button class="accordion"><?=$key->title?></button>
			<div class="panel">
				<p><?=$content?></p>
				<div class="content-video">
					<div class="hs-responsive-embed hs-responsive-embed-youtube"><iframe src="<?=$key->video?>?rel=0" frameborder="0" allowfullscreen></iframe></div>
				</div>
			</div>
		</div>
		<?php
				}
			}else{
				echo '<div class="main"><center><h2 style="color: red;">Error al mostrar los datos</h2><p>Si estas en una versión local, comprueba que tienes conexión a Internet.</p></center></div>';
			}
			if((get_option('quecodig_code') != "0") && (get_option('quecodig_public') != "0") && (get_option("quecodig_sub") == "1")):
		?>
		<div class="main" id="panel">
			<button class="accordion">Reporte</button>
			<div class="panel">
				<div class="reporter-log">
					<h2 class="title">Reporte</h2>
					<p>Este reporte nos ayuda a saber sobre tu sitio, si tienes algún problema envianos este texto.</p>
					<?php echo quecodig_debug_report(true); ?>
				</div>
				<div class="info-log">
					<h2 class="title">Solicitar Soporte.</h2>
					<p>Si tienes problemas tecnicos con tu sitio web, hemos preparado un medio por el cual podemos brindarte ayuda y solucionar tu problema.</p>
					<!-- <span style="color: red">No dejar activa esta opción, ya que puede contener fallos de seguridad grabes para tu sitio.</span> -->
					<a onclick="window.chaport.open();" style="cursor:pointer" class="btn">Abrir chat</a>
				</div>
			</div>
		</div>
		<?php
			endif;
		?>
	</div>
</div>
