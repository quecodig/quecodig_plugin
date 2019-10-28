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

	function od_dashboard_woo() {
		global $wpdb;
		global $woocommerce ;
		$od_woo_odr_no = get_option( 'od_woo_odr_no' );
		$customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
			'numberposts' => $od_woo_odr_no,
			'meta_key'    => '_customer_user',
			//'meta_value'  => wp_get_current_user()->ID,
			'post_type'   => 'shop_order',
			'post_status' => 'publish'

		) ) );
	?>   
	<?php
		if ( isset($_POST['submit']) ) { 
			$nonce = $_REQUEST['_wpnonce'];
			if (! wp_verify_nonce($nonce, 'php-woo-odr-updatesettings' ) ) {
				die('security error');
			}
			$woo_odr_no = $_POST['woo_odr_no'];
			update_option( 'od_woo_odr_no', $woo_odr_no );
		} 
		$od_woo_odr_no = get_option( 'od_woo_odr_no' );
		if ( $customer_orders ):
	?>
		<table class="shop_table my_account_orders" width="100%">
			<thead>
				<tr>
					<th class="order-number"><span class="nobr"><?php _e( 'Order', 'woocommerce' ); ?></span></th>
					<th class="order-date"><span class="nobr"><?php _e( 'Date', 'woocommerce' ); ?></span></th>
					<th class="order-status"><span class="nobr"><?php _e( 'Status', 'woocommerce' ); ?></span></th>
					<th class="order-total"><span class="nobr"><?php _e( 'Total', 'woocommerce' ); ?></span></th>
					<th class="order-actions"><span class="nobr"><?php _e( 'Action', 'woocommerce' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach ( $customer_orders as $customer_order ) {
						$order = new WC_Order();
						$order->populate( $customer_order );
						$status     = get_term_by( 'slug', $order->status, 'shop_order_status' );
						$item_count = $order->get_item_count();
				?>
				<tr class="order">
					<td class="order-number">
						<a href="<?php echo get_home_url(); ?>/wp-admin/post.php?post=<?php echo $order->get_order_number() ;?>&action=edit">
							<?php echo $order->get_order_number(); ?>
						</a>
					</td>
					<td class="order-date">
						<time datetime="<?php echo date( 'Y-m-d', strtotime( $order->order_date ) ); ?>" title="<?php echo esc_attr( strtotime( $order->order_date ) ); ?>"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></time>
					</td>
					<td class="order-status" style="text-align:left; white-space:nowrap;">
						<?php $ostatus = $order->status ;if($ostatus == "on-hold"){?>
						<span style="color:#FF0000"><?php echo ucfirst( __( $order->status, 'woocommerce' ) ); ?></span>
						<?php } ?>
						<?php $ostatus = $order->status ;if($ostatus == "processing"){?>
							<span style="color:#F8BD27"><?php echo ucfirst( __( $order->status, 'woocommerce' ) ); ?></span>
						<?php } ?>
						<?php $ostatus = $order->status ;if($ostatus == "completed"){?>
							<span style="color:#0F9D58"><?php echo ucfirst( __( $order->status, 'woocommerce' ) ); ?></span>
						<?php } ?>
					</td>
					<td class="order-total">
						<?php echo sprintf( _n( '%s for %s item', '%s for %s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count ); ?>
					</td>
					<td class="order-actions">
						<?php
							$actions = array();
							if ( in_array( $order->status, apply_filters( 'woocommerce_valid_order_statuses_for_payment', array( 'pending', 'failed' ), $order ) ) ) {
								$actions['pay'] = array(
									'url'  => $order->get_checkout_payment_url(),
									'name' => __( 'Pay', 'woocommerce' )
								);
							}
							if ( in_array( $order->status, apply_filters( 'woocommerce_valid_order_statuses_for_cancel', array( 'pending', 'failed' ), $order ) ) ) {
								$actions['cancel'] = array(
									'url'  => $order->get_cancel_order_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ),
									'name' => __( 'Cancel', 'woocommerce' )
								);
							}
							$actions['view'] = array(
								'url'  => $order->get_view_order_url(),
								'name' => __( 'View', 'woocommerce' )
							);

							$actions = apply_filters( 'woocommerce_my_account_my_orders_actions', $actions, $order );
							if ($actions) {
								foreach ( $actions as $key => $action ) {
									echo '<a href="' .get_home_url() . '/wp-admin/post.php?post= '. $order->get_order_number().'&action=edit" class="button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
								}
							}
						?>
					</td>
				</tr>
				<?php }	?>
			</tbody>
		</table>
		<div style="border-top: 1px solid #000;">
			<form method="post" action="" id="php_odr_config_page">
				<?php wp_nonce_field('php-woo-odr-updatesettings'); ?>                          
				<table class="form-table">
					<tbody>
						<tr>
							<th><label># de órdenes para mostrar: </label></th>
							<td>
								<Input type = 'text' Name ='woo_odr_no' <?php if($od_woo_odr_no!=""){?>value= '<?php echo $od_woo_odr_no ; ?>' <?php } else { ?> value = '5' <?php } ?> />
							</td>
						</tr>
					</tbody>
				</table>
				<p class="submit"><input type="submit" value="Save Changes" class="button-primary" id="submit" name="submit" /></p>  
			</form>
		</div>
	<?php
		;else:
			echo '<p>' . __( 'Actualmente, no hay nuevos pedidos.', 'WooCommerce') . '</p>';
		endif; 
	}
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
		<div class="box box--sm-6 with-padding with-padding--padding-top-none  with-padding--padding-bottom-large mobile-side-reset mobile-space-reset">
			<div class="container container--padding-none container--elevation-1 with-padding with-padding--padding-top-x-small with-padding--padding-left-large with-padding--padding-right-x-small with-padding--padding-bottom-large">
				<div class="flex flex--align-center flex--gutter-none flex--direction-row flex--margin-none">
					<h6 class="title title--density-cozy title--level-6 typography typography--weight-bold with-color with-color--color-dark"  style="width: 100%">
						<?php esc_html_e( 'WooCommerce nuevos pedidos', 'woocommerce' ); ?>
					</h6><br>
					<div id="woocommerce_network_orders">
						<?php echo od_dashboard_woo(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>