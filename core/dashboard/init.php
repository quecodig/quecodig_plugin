<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Bloquear acceso de manera directa.
	}

	include (QC_PLUGIN_PATH ."core/dashboard/dashboard.php");
	include (QC_PLUGIN_PATH ."core/dashboard/help.php");

	add_action( 'admin_head' , 'quecodig_helper' );

	//*****Dashboard*****//
	// Add the new submenu page.
	add_action( 'admin_menu', 'quecodig_admin_menu', 1 );
	// Remove the original page, since we are going to use our custom page.
	add_action( 'admin_menu', 'quecodig_remove_original_page', 999 );
	// Reorder submenu pages to replicate the initial order.
	add_filter( 'custom_menu_order', 'quecodig_reorder_submenu_pages' );
	// Change the parent_file in order to highlight the new menu item
	// when "Dashboard" is the currently selected item.
	add_action( 'submenu_file', 'quecodig_highlight_menu_item' );

	// Redirect to our custom dashboard.
	add_action( 'admin_init', 'quecodig_redirect_to_dashboard', 1 );

	add_action( 'wp_before_admin_bar_render', 'quecodig_add_dashboard_admin_bar_menu_item' );
	add_action( 'wp_before_admin_bar_render', 'quecodig_reorder_admin_bar' );