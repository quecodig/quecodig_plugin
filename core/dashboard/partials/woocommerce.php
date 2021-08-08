<?php
	$woorp = new WC_Admin_Dashboard();
	global $woocommerce, $wpdb, $product;
	include_once($woocommerce->plugin_path() . '/includes/admin/reports/class-wc-admin-report.php');
	
	// WooCommerce Admin Report
	$wc_report = new WC_Admin_Report();
	
	// Set date parameters for the current month
	$start_date = strtotime(date('Y-m', current_time('timestamp')) . '-01 midnight');
	$end_date = strtotime('+1month', $start_date) - 86400;
	$wc_report->start_date = $start_date;
	$wc_report->end_date = $end_date;
	
	// Avoid max join size error
	$wpdb->query('SET SQL_BIG_SELECTS=1');
	
	// Get data for current month sold products
	$sold_products = $wc_report->get_order_report_data(array(
		'data' => array(
			'_product_id' => array(
				'type' => 'order_item_meta',
				'order_item_type' => 'line_item',
				'function' => '',
				'name' => 'product_id'
			),
			'_qty' => array(
				'type' => 'order_item_meta',
				'order_item_type' => 'line_item',
				'function' => 'SUM',
				'name' => 'quantity'
			),
			'_line_subtotal' => array(
				'type' => 'order_item_meta',
				'order_item_type' => 'line_item',
				'function' => 'SUM',
				'name' => 'gross'
			),
			'_line_total' => array(
				'type' => 'order_item_meta',
				'order_item_type' => 'line_item',
				'function' => 'SUM',
				'name' => 'gross_after_discount'
			)
		),
		'query_type' => 'get_results',
		'group_by' => 'product_id',
		'where_meta' => '',
		'order_by' => 'quantity DESC',
		'order_types' => wc_get_order_types('order_count'),
		'filter_range' => TRUE,
		'order_status' => array('epayco-processing'),
	));
?>
<h4 class="title title--density-cozy title--level-4 typography typography--weight-light with-color with-color--color-darkest sg-margin-top-large">
	<?php esc_html_e('WooCommerce') ?>
</h4>
<div style="width: 100%;" class="useful-links">
	<div class="flex flex--gutter-none flex--margin-none">
		<div class="box box--sm-6 with-padding with-padding--padding-top-none  with-padding--padding-bottom-large mobile-side-reset mobile-space-reset">
			<div class="container container--padding-none container--elevation-1 with-padding with-padding--padding-top-x-small with-padding--padding-left-large with-padding--padding-right-x-small with-padding--padding-bottom-large">
				<div class="flex flex--align-center flex--gutter-none flex--direction-row flex--margin-none">
					<h6 class="title title--density-cozy title--level-6 typography typography--weight-bold with-color with-color--color-dark">
						<?php esc_html_e( 'WooCommerce Status', 'woocommerce' ); ?>
					</h6><br>
					<div id="woocommerce_dashboard_status">
						<?php echo $woorp->status_widget(); ?>
					</div>
				</div>
			</div>
		</div>
		<?php
			if(get_option( 'woocommerce_enable_reviews' ) === 'yes'):
		?>
		<div class="box box--sm-6 with-padding with-padding--padding-top-none  with-padding--padding-bottom-large mobile-side-reset mobile-space-reset">
			<div class="container container--padding-none container--elevation-1 with-padding with-padding--padding-top-x-small with-padding--padding-left-large with-padding--padding-right-x-small with-padding--padding-bottom-large">
				<div class="flex flex--align-center flex--gutter-none flex--direction-row flex--margin-none">
					<h6 class="title title--density-cozy title--level-6 typography typography--weight-bold with-color with-color--color-dark"  style="width: 100%">
						<?php esc_html_e( 'WooCommerce Recent Reviews', 'woocommerce' ); ?>
					</h6><br>
					<div id="woocommerce_dashboard_recent_reviews">
						<?php echo $woorp->recent_reviews(); ?>
					</div>
				</div>
			</div>
		</div>
		<?php
			endif;
		?>
		<div class="box box--sm-6 with-padding with-padding--padding-top-none  with-padding--padding-bottom-large mobile-side-reset mobile-space-reset">
			<div class="container container--padding-none container--elevation-1 with-padding with-padding--padding-top-x-small with-padding--padding-left-large with-padding--padding-right-x-small with-padding--padding-bottom-large">
				<div class="flex flex--align-center flex--gutter-none flex--direction-row flex--margin-none">
					<h6 class="title title--density-cozy title--level-6 typography typography--weight-bold with-color with-color--color-dark"  style="width: 100%">
						<?php esc_html_e( 'WooCommerce ventas del mes', 'woocommerce' ); ?>
					</h6><br><br>
					<div id="woocommerce_dashboard_ptmp"  style="width: 100%">
						<?php
							// List Sales Items
							if (!empty($sold_products)) {
						?>
						<div class="ptmp-products-list">
							<table class="ptmp-wp-list-table widefat striped">
								<thead>
									<tr>
										<th class="manage-column column-name" scope="col"><strong><?php _e('Name', 'woocommerce') ?></strong></th>
										<th class="manage-column column-count" scope="col"><strong><?php _e('Items', 'woocommerce') ?></strong></th>
										<th class="manage-column column-earnings" scope="col"><strong><?php _e('Totals', 'woocommerce') ?></strong></th>
									</tr>
								</thead>
								<tbody >
									<?php
										foreach ($sold_products as $product) {
									?>
									<tr>
										<td class="seller-this-month"><a href="<?php echo esc_url(get_edit_post_link(intval($product->product_id))); ?>"><strong><?php echo html_entity_decode(get_the_title($product->product_id)); ?></strong></a></td>
										<td><?php echo intval($product->quantity); ?></td>
										<?php
											$price = $product->gross;
											$product_price = wc_price($price);
										?>
										<td><?php echo $product_price; ?></td>
									</tr>
									<?php
										}
									?>
								</tbody>
							</table>
						</div>
						<?php
							} else {
								echo '<p>' . __( 'Actualmente, no hay ventas de este mes.', 'WooCommerce') . '</p>';
							}
						?>
					</div>
				</div>
			</div>
		</div>
		<?php
			if ( is_multisite() && is_main_site() ) {
		?>
		<div class="box box--sm-6 with-padding with-padding--padding-top-none  with-padding--padding-bottom-large mobile-side-reset mobile-space-reset">
			<div class="container container--padding-none container--elevation-1 with-padding with-padding--padding-top-x-small with-padding--padding-left-large with-padding--padding-right-x-small with-padding--padding-bottom-large">
				<div class="flex flex--align-center flex--gutter-none flex--direction-row flex--margin-none">
					<h6 class="title title--density-cozy title--level-6 typography typography--weight-bold with-color with-color--color-dark"  style="width: 100%">
						<?php esc_html_e( 'WooCommerce Recent Reviews', 'woocommerce' ); ?>
					</h6><br>
					<div id="woocommerce_network_orders">
						<?php echo $woorp->network_orders(); ?>
					</div>
				</div>
			</div>
		</div>
		<?php
			}
		?>
		<!-- <div class="box box--sm-6 with-padding with-padding--padding-top-none  with-padding--padding-bottom-large mobile-side-reset mobile-space-reset">
			<div class="container container--padding-none container--elevation-1 with-padding with-padding--padding-top-x-small with-padding--padding-left-large with-padding--padding-right-x-small with-padding--padding-bottom-large">
				<div class="flex flex--align-center flex--gutter-none flex--direction-row flex--margin-none">
					<h6 class="title title--density-cozy title--level-6 typography typography--weight-bold with-color with-color--color-dark"  style="width: 100%">
						<?php esc_html_e( 'WooCommerce nuevos pedidos', 'woocommerce' ); ?>
					</h6><br>
					<div id="woocommerce_network_orders">
						
					</div>
				</div>
			</div>
		</div> -->
	</div>
</div>