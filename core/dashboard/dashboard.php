<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Bloquear acceso de manera directa.
	}

	function quecodig_add_dashboard_admin_bar_menu_item() {
		global $wp_admin_bar;

		// Remove the initial dashboard menu item.
		$wp_admin_bar->remove_node( 'dashboard' );

		// Add our custom dashboard item.
		$wp_admin_bar->add_menu(
			array(
				'id'     => 'quecodigo-wizard-dashboard',
				'title'  => 'Inicio',
				'href'   => get_admin_url( null, 'admin.php?page=custom-dashboard.php' ),
				'parent' => 'appearance',
			)
		);
	}

	function quecodig_reorder_admin_bar() {
		global $wp_admin_bar;

		// The desired order of identifiers (items).
		$ids = array(
			'quecodigo-wizard-dashboard',
			'themes',
			'widgets',
			'menus',
		);

		// Get an array of all the toolbar items on the current page.
		$nodes = $wp_admin_bar->get_nodes();

		// Perform recognized identifiers.
		foreach ( $ids as $id ) {
			if ( ! isset( $nodes[ $id ] ) ) {
				continue;
			}

			// This will cause the identifier to act as the last menu item.
			$wp_admin_bar->remove_menu( $id );
			$wp_admin_bar->add_node( $nodes[ $id ] );

			// Remove the identifier from the list of nodes.
			unset( $nodes[ $id ] );
		}

		// Unknown identifiers will be moved to appear after known identifiers.
		foreach ( $nodes as $id => &$obj ) {
			// There is no need to organize unknown children identifiers (sub items).
			if ( ! empty( $obj->parent ) ) {
				continue;
			}

			// This will cause the identifier to act as the last menu item.
			$wp_admin_bar->remove_menu( $id );
			$wp_admin_bar->add_node( $obj );
		}

	}

	function quecodig_redirect_to_dashboard() {
		global $pagenow;

		// Delete plugin transients on inital dashboard rendering.
		if ( isset( $_GET['hard-redirect'] ) ) {
			delete_plugins_redirect_transients();
		}

		if (
			( isset( $_GET['page'] ) && 'quecodigo-wizard' === $_GET['page'] && 'completed' === $status['status'] ) ||
			'index.php' === $pagenow && empty( $_GET )
		) {
			wp_safe_redirect( admin_url( 'admin.php?page=custom-dashboard.php' ) );
			exit;
		}
	}

	function quecodig_get_menu_slug() {
		return 'custom-dashboard.php';
	}

	function quecodig_remove_original_page() {
		remove_submenu_page( 'index.php', 'index.php' );
	}

	function quecodig_reorder_submenu_pages( $menu_order ) {
		// Load the global submenu.
		global $submenu;

		// Bail if for some reason the submenu is empty.
		if ( empty( $submenu ) ) {
			return;
		}

		// Try to get our custom page index.
		foreach ( $submenu['index.php'] as $key => $value ) {
			if ( 'custom-dashboard.php' === $value[2] ) {
				$page_index = $key;
			}
		}

		// Bail if our custom page is missing in `$submenu` for some reason.
		if ( empty( $page_index ) ) {
			return $menu_order;
		}

		// Store the custom dashboard in variable.
		$dashboard_menu_item = $submenu['index.php'][ $page_index ];

		// Remove the original custom dashboard page.
		unset( $submenu['index.php'][ $page_index ] );

		// Add the custom dashboard page in the beginning.
		array_unshift( $submenu['index.php'], $dashboard_menu_item );

		// Finally return the menu order.
		return $menu_order;
	}

	function quecodig_highlight_menu_item( $parent_file ) {
		// Get the current screen.
		$current_screen = get_current_screen();

		// Check whether is the custom dashboard page
		// and change the `parent_file` to custom-dashboard.php.
		if ( 'dashboard_page_custom-dashboard' == $current_screen->base ) {
			$parent_file = quecodig_get_menu_slug();
		}

		// Return the `parent_file`.
		return $parent_file;
	}

	function quecodig_admin_menu() {
		// Add the sub-menu page.
		$page = add_submenu_page(
			'index.php',
			__( 'Home'),
			__( 'Home'),
			'edit_posts',
			quecodig_get_menu_slug(),
			'quecodig_render'
		);

		// Finally return the page hook_suffix.
		return $page;
	}

	function quecodig_render() {
		require_once( ABSPATH . 'wp-admin/includes/dashboard.php' );

		// Include the partial.
		include QC_PLUGIN_PATH . 'core/dashboard/custom_dashboard.php';
	}
?>