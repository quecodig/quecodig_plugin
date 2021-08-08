<div class="page dashboard-page wrap main">
	<div class="section section--content-size-default with-padding with-padding with-padding--padding-top-x-small">
		<div class="section__content">
			<h2 class="hidden alerts-quecodig">Panel</h2>
			<?php
				include( 'partials/manage-design.php' );
				include( 'partials/useful-links.php' );
				if(is_plugin_active( 'woocommerce/woocommerce.php' )){
					include( 'partials/woocommerce.php' );
				}
				//include( 'partials/google-analytics-for-wp.php' );
				include( 'partials/news-and-events.php' );
			?>
		</div>
	</div>
</div>
<script>
	document.addEventListener("DOMContentLoaded", function(event) {
		var alert_classes = '.update-nag, .notice, .notice-success, .updated, .settings-error, .error, .notice-error, .notice-warning, .notice-info';
		var $alerts = jQuery( alert_classes )
		.not( '.inline, .theme-update-message, .hidden, .hide-if-js' )
		// Plugin exceptions
		// Also see _theme-alerts.scss
		.not( '#gadwp-notice, .rs-update-notice-wrap' );
	});
</script>
