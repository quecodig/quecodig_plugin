<?php
	if(class_exists("Ga_Helper")):
?>
<div id="dashboard_primary" class="events--news with-padding with-padding--padding-bottom-large">
	<div class="container container--padding-none container--elevation-1">

		<div class="wordpress--news hide-if-no-js with-padding with-padding--padding-top-x-small with-padding--padding-left-large with-padding--padding-right-large with-padding--padding-bottom-medium">
			<h6 class="title title--density-cozy title--level-6 typography typography--weight-bold with-color with-color--color-dark"><?php echo esc_html_e( 'Google Analytics') ?></h6>
			<div class="inside">
				<div id='gadwp-widget'>
				<?php
					$ga = new Ga_Helper();
					echo $ga->add_ga_dashboard_widget();
				?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
	endif;
?>