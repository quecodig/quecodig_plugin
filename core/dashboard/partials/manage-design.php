<?php
$theme = wp_get_theme();
$pages = wp_count_posts( 'page' );
?>
<h4 class="title title--density-cozy title-manage-design title--level-4 typography typography--weight-light with-color with-color--color-darkest sg-margin-top-large">
	<?php esc_html_e( 'Customize'); ?>
</h4>
<div class="flex flex--gutter-none flex--margin-none">
	<div class="box box--sm-4 with-padding with-padding--padding-right-medium mobile-side-reset">
		<div class="container container--padding-large container--elevation-1">
			<div class="flex flex--align-center flex--gutter-none flex--direction-column flex--margin-none">

				<span class="icon" style="width: 46px; height: 46px">
					<img src="<?php echo plugins_url( 'assets/img/web.svg?qcv='.PLUGIN_VERSION, QC_PLUGIN_FILE ); ?>" alt="">
				</span>

				<h4 class="title title--density-none title--level-4 typography typography--weight-bold with-color with-color--color-darkest sg-margin-top-medium">Ver página</h4>

				<p class="text text--size-medium typography typography--weight-regular typography--align-center with-color with-color--color-dark sg-margin-bottom-medium">
					¡Comprueba cómo se ve tu página web!
				</p>

				<a href="<?php echo site_url(); ?>" target="_blank" class="sg--button button--primary button--medium">
					<span class="button__content">
						<span class="button__text">
							Ver página
						</span>
					</span>
				</a>
			</div>
		</div>
	</div>

	<div class="box box--sm-4 with-padding with-padding--padding-left-x-small with-padding--padding-right-x-small mobile-side-reset">
		<div class="container container--padding-large container--elevation-1">
			<div class="flex flex--align-center flex--gutter-none flex--direction-column flex--margin-none">
				<span class="icon" style="width: 46px; height: 46px">
					<img src="<?php echo plugins_url( 'assets/img/diseno-web.svg?qcv='.PLUGIN_VERSION, QC_PLUGIN_FILE ); ?>" alt="">
				</span>
				<h4 class="title title--density-none title--level-4 typography typography--weight-bold with-color with-color--color-darkest sg-margin-top-medium">
					Administrar Entradas
				</h4>
				<p class="text text--size-medium typography typography--weight-regular typography--align-center with-color with-color--color-dark sg-margin-bottom-medium">
					Editar y crear entradas nuevas
				</p>
				<a href="<?php echo admin_url(); ?>edit.php" class="sg--button button--primary button--medium">
					<span class="button__content">
						<span class="button__text">
							Administrar entradas
						</span>
					</span>
				</a>
			</div>
		</div>
	</div>
	<?php
		if(is_plugin_active( 'woocommerce/woocommerce.php' )):
	?>
	<div class="box box--sm-4 with-padding with-padding--padding-left-medium mobile-side-reset">
		<div class="container container--padding-large container--elevation-1">
			<div class="flex flex--align-center flex--gutter-none flex--direction-column flex--margin-none">
				<span class="icon" style="width: 48px; height: 46px;">
					<img src="<?php echo plugins_url( 'assets/img/caja.svg?qcv='.PLUGIN_VERSION, QC_PLUGIN_FILE ); ?>" alt="">
				</span>
				<h4 class="title title--density-none title--level-4 typography typography--weight-bold with-color with-color--color-darkest sg-margin-top-medium">
					Administrar productos
				</h4>
				<p class="text text--size-medium typography typography--weight-regular typography--align-center with-color with-color--color-dark sg-margin-bottom-medium">
					Editar y crear productos nuevos
				</p>
				<a href="<?php echo admin_url(); ?>edit.php?post_type=product" class="sg--button button--primary button--medium">
					<span class="button__content">
						<span class="button__text">
							Administrar productos
						</span>
					</span>
				</a>
			</div>
		</div>
	</div>
	<?php
		endif;
	?>
</div>