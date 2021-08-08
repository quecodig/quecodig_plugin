<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Bloquear acceso de manera directa.
	}
	//echo get_option('quecodig_code');
	//echo get_option('quecodig_public');
	//echo get_option("quecodig_sub");
?>
<div class="wrap quecodig_plugin" style="margin-top: 40px;">
	<div class="wrap content-api" id="main">
		<?php
			if(isset($_GET["vencido"])):
		?>
		<div class="alert warning">
			<p>Error el id a consultar ya vencio, renueva la suscripción.</p>
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
					<h2>¡Bienvenido al centro de Ayuda de Qué Código!</h2>
					<p>Aquí encontraras toda la información necesaria para saber manejar y administrar tu sitio web.</p>
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
				<p>Tu tiempo de soporte especializado ha finalizado, si deseas ayuda con tu sitio puedes contactarnos para ofrecerte un servicio de soporte al correo <a href="mailto:soporte@quecodigo.com">soporte@quecodigo.com</a>.</p>
			</div>
		</div>
		<?php
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
					<a href="https://wa.me/+573014486070?text=Hola%20necesito%20ayuda%20con%20mi%20sitio%20web" target="_blank" style="cursor:pointer" class="btn">Abrir chat</a>
				</div>
			</div>
		</div>
		<?php
			endif;
		?>
		<div class="boxes-filter">
			<ul class="list-unstyled row filter">
				<li class="col-md active" data-filter="all">Todos</li>
				<li class="col-md" data-filter="panel">Panel</li>
				<li class="col-md" data-filter="post">Entradas</li>
				<li class="col-md" data-filter="ecommerce">Tienda</li>
				<li class="col-md" data-filter="support">Soporte</li>
				<li class="col-md" data-filter="users">Usuarios</li>
			</ul>
		</div>
		<div class="boxes">
			<?php
				$args = array(
					'method' => 'GET',
				);
				$response = wp_remote_request( QC_PLUGIN_API.'request.php', $args );
				if(!is_wp_error($response) && ($response['response']['code'] == 200 || $response['response']['code'] == 201)) {
					$body = json_decode( wp_remote_retrieve_body( $response ) );
					if(!empty($body)){
						foreach ($body as $key) {
							$content = preg_replace("/\[br\]/is", "<br>", $key->content);
			?>
			<div class="box" data-video="<?=$key->type?>">
				<div class="content-video">
					<div class="hs-responsive-embed">
						<iframe src="<?=$key->video?>?rel=0" frameborder="0" allowfullscreen></iframe>
					</div>
				</div>
				<div class="description">
					<h2><?=$key->title?></h2>
					<p><?=$content?></p>
				</div>
			</div>
			<?php
						}
					}else{
						echo '<div class="main"><center><h2 style="color: red;">Aun no tenemos videos disponibles</h2></center></div>';
					}
				}else{
					echo '<div class="main"><center><h2 style="color: red;">Error al mostrar los datos</h2><p>Si estas en una versión local, comprueba que tienes conexión a Internet.</p></center></div>';
				}
			?>
		</div>
	</div>
</div>
<script>
	(function($) {
		'use strict';
		var $filters = $('.filter [data-filter]'),
		$boxes = $('.boxes [data-video]');

		$filters.on('click', function(e) {
			e.preventDefault();
			var $this = $(this);

			$filters.removeClass('active');
			$this.addClass('active');

			var $filterVideo = $this.attr('data-filter');

			if ($filterVideo == 'all') {
				$boxes.removeClass('is-animated')
				.fadeOut().promise().done(function() {
					$boxes.addClass('is-animated').fadeIn();
				});
			} else {
				$boxes.removeClass('is-animated')
				.fadeOut().promise().done(function() {
					$boxes.filter('[data-video = "' + $filterVideo + '"]')
					.addClass('is-animated').fadeIn();
				});
			}
		});
	})(jQuery);
</script>