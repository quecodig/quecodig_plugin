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
				include( 'partials/google-analytics-for-wp.php' );
				include( 'partials/news-and-events.php' );
			?>
		</div>
	</div>
</div>
<script>
	document.addEventListener("DOMContentLoaded", function(event) {
		var $toolbar_item = jQuery( '#wp-admin-bar-clientside-notification-center' );
		var $submenu = $toolbar_item.find( '.ab-submenu' );
		var notification_count = 0;
		var important_flag = false;
		var alert_classes = '.update-nag, .notice, .notice-success, .updated, .settings-error, .error, .notice-error, .notice-warning, .notice-info';
		var $alerts = jQuery( alert_classes )
		.not( '.inline, .theme-update-message, .hidden, .hide-if-js' )
		// Plugin exceptions
		// Also see _theme-alerts.scss
		.not( '#gadwp-notice, .rs-update-notice-wrap' );
		var greens = [ 'updated', 'notice-success' ];
		var reds = [ 'error', 'notice-error', 'settings-error' ];
		var blues = [ 'update-nag', 'notice', 'notice-info', 'update-nag', 'notice-warning' ];

		// Itirate page alerts to analyse & copy to the toolbar
		$alerts.each( function( i ) {

			var $alert = jQuery( this );
			//var content = $alert.html();

			// Strip content whitespace
			// content = content.replace( /^\s+|\s+$/g, '' );

			// Skip if alert is empty
			if ( ! $alert.html().replace( /^\s+|\s+$/g, '' ).length ) {
				return true;
			}

			// Determine the priority
			var j;
			var priority = 'neutral';
			// Red
			for ( j = 0; j < reds.length; j += 1 ) {
				if ( $alert.hasClass( reds[ j ] ) ) {
					if ( ! $alert.hasClass( 'updated' ) ) { // Because of .settings-error.updated
						priority = 'red';
						// Color toolbar icon red if it contains important/error notifications
						if ( ! important_flag ) {
							$toolbar_item.addClass( '-important' );
							important_flag = true;
						}
					}
				}
			}

			// Add it to the notification list
			var $new_item = jQuery( '<li><div class="ab-item ab-empty-item clientside-notification-center-item--' + priority + '"></div></li>' ).appendTo( $submenu );
			$alert.clone( true, true ).removeClass( alert_classes.replace( /,|\./g, '' ) ).appendTo( $new_item.children( 'div' ) );
			notification_count += 1;

		} );

		// Populate the counter
		jQuery( '.clientside-notification-count' ).text( notification_count );

		// Show the toolbar item
		if ( notification_count ) {
			$alerts.remove(); // Make sure they don't cause extra spacing by breaking "+" selectors
			$toolbar_item.fadeIn();
		}

	});
</script>
