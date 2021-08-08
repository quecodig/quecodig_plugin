<h4 class="title title--density-cozy title--level-4 typography typography--weight-light with-color with-color--color-darkest sg-margin-top-large">
	<?php esc_html_e( 'Links') ?>
</h4>
<div style="width: 100%;" class="useful-links">
	<div class="flex flex--gutter-none flex--margin-none">
		<div class="box box--sm-6 with-padding with-padding--padding-top-none mobile-side-reset mobile-space-reset ">
			<div class="container container--padding-none container--elevation-1 with-padding with-padding--padding-top-x-small with-padding--padding-left-large with-padding--padding-right-x-small with-padding--padding-bottom-large">
				<div class="flex flex--gutter-none flex--direction-row flex--margin-none">
					<div class="box box--sm-9">
						<h6 class="title title--density-cozy title--level-6 typography typography--weight-bold with-color with-color--color-dark">
							Visita nuestro panel de ayuda y aprendizaje
						</h6>
						<p class="text text--size-medium typography typography--weight-regular with-color with-color--color-dark">
							Si tienes alguna duda del funcionamiento de tu sitio es bastante probable que la respuesta esté en nuestra Base de Conocimiento.
						</p>
						<a href="<?php echo add_query_arg( array( 'page' => 'quecodigo_soporte' ), admin_url( 'admin.php' ) ); ?>" class="link sg-margin-top-x-small">
							Visita la Base de Conocimiento
						</a>
					</div>
					<div class="box box--sm-3 typography--align-center">
						<span class="icon sg--hide-mobile" style="width: 80px">
							<img src="<?php echo plugins_url( 'assets/img/capacitacion.svg?qcv='.QC_PLUGIN_VERSION, QC_PLUGIN_FILE ); ?>" alt="">
						</span>
					</div>
				</div>
			</div>
		</div>
		<?php
			if( (get_option('quecodig_code') != "0") && (get_option('quecodig_public') != "0") && (get_option('quecodig_sub') === "1") ):
		?>
		<div class="box box--sm-6 with-padding with-padding--padding-top-none  with-padding--padding-bottom-large mobile-side-reset mobile-space-reset">
			<div class="container container--padding-none container--elevation-1 with-padding with-padding--padding-top-x-small with-padding--padding-left-large with-padding--padding-right-x-small with-padding--padding-bottom-large">
				<div class="flex flex--align-center flex--gutter-none flex--direction-row flex--margin-none">
					<div class="box box--sm-9">
						<h6 class="title title--density-cozy title--level-6 typography typography--weight-bold with-color with-color--color-dark">
							¿Tienes problemas tecnicos con tu sitio?
						</h6>
						<p class="text text--size-medium typography typography--weight-regular with-color with-color--color-dark">
							Si tienes problemas tecnicos con tu sitio web, hemos preparado un medio por el cual podemos brindarte ayuda y solucionar tu problema.
						</p>
						<a href="https://wa.me/+573014486070?text=Hola%20necesito%20ayuda%20con%20mi%20sitio%20web" target="_blank" class="link sg-margin-top-x-small">
							¡Contactanos para mas ayuda!
						</a>
					</div>
					<div class="box box--sm-3 typography--align-center">
						<span class="icon sg--hide-mobile" style="width: 80px">
							<img src="<?php echo plugins_url( 'assets/img/apoyo-tecnico.svg?qcv='.QC_PLUGIN_VERSION, QC_PLUGIN_FILE ); ?>" alt="">
						</span>
					</div>
				</div>
			</div>
		</div>
		<?php
			endif;
		?>
	</div>
</div>
