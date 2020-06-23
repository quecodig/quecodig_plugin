<h4 class="title title--density-cozy title--level-4 typography typography--weight-light with-color with-color--color-darkest sg-margin-top-large">
	<?php esc_html_e('Noticias de Qué Código') ?>
</h4>
<div id="dashboard_primary" class="events--news with-padding with-padding--padding-bottom-large">
	<div class="container container--padding-none container--elevation-1">

		<div class="wordpress--news hide-if-no-js with-padding with-padding--padding-top-x-small with-padding--padding-left-large with-padding--padding-right-large with-padding--padding-bottom-medium">
			<h6 class="title title--density-cozy title--level-6 typography typography--weight-bold with-color with-color--color-dark"><?php echo esc_html_e( 'News') ?></h6>
			<div class="inside">
				<?php
					echo '<div class="rss-widget quecodig_feed">';
					wp_widget_rss_output(array( 'url' => 'https://www.quecodigo.com/rss/?v5', 'items' => 5, 'show_summary' => 0, 'show_author' => 0, 'show_date' => 1 ));
					echo "</div>";
				?>
			</div>
		</div>

		<div class="community-events-footer toolbar toolbar--background-light toolbar--density-cozy flex--justify-flex-end">
			<?php
			printf(
				'<a href="%1$s" target="_blank" class="sg--button button--neutral button--medium"><span class="button__content"><span class="button__text">%2$s</span></span> <span class="screen-reader-text">%3$s</span></a>',
				'https://www.quecodigo.com/',
				__('Page'),
				/* translators: accessibility text */
				__('(opens in a new window)')
			);
			printf(
				'<a href="%1$s" target="_blank" class="sg--button button--neutral button--medium"><span class="button__content"><span class="button__text">%2$s</span></span> <span class="screen-reader-text">%3$s</span></a>',
				/* translators: If a Rosetta site exists (e.g. https://es.wordpress.org/news/), then use that. Otherwise, leave untranslated. */
				'https://www.quecodigo.com/blog/',
				__('Blog'),
				/* translators: accessibility text */
				__('(opens in a new window)')
			);
			printf(
				'<a href="%1$s" target="_blank" class="sg--button button--neutral button--medium"><span class="button__content"><span class="button__text">%2$s</span></span> <span class="screen-reader-text">%3$s</span></a>',
				/* translators: If a Rosetta site exists (e.g. https://es.wordpress.org/news/), then use that. Otherwise, leave untranslated. */
				'https://www.quecodigo.com/clientes/',
				__('Support'),
				/* translators: accessibility text */
				__('(opens in a new window)')
			);
			?>
		</div>
	</div>
</div>
<script>
	window.onload = function(){
		var allLinks = document.querySelectorAll("div.quecodig_feed a");
		for(var i=0;i<allLinks.length;i++){
			var currentLink = allLinks[i];
			currentLink.setAttribute("target","_blank");
		}
	}
</script>