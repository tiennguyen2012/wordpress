<?php
function wpsc_gridview_install(){
	update_option('a3rev_wpsc_gridview_version', '1.0.3');
	
	update_option('a3rev_wpsc_gridview_just_installed', true);
}

/**
 * Load languages file
 */
function wpsc_gridview_init() {
	if ( get_option('a3rev_wpsc_gridview_just_installed') ) {
		delete_option('a3rev_wpsc_gridview_just_installed');
		wp_redirect( ( ( is_ssl() || force_ssl_admin() || force_ssl_login() ) ? str_replace( 'http:', 'https:', admin_url( 'options-general.php?page=wpsc-settings&tab=presentation#wpsc-grid-settings' ) ) : str_replace( 'https:', 'http:', admin_url( 'options-general.php?page=wpsc-settings&tab=presentation#wpsc-grid-settings' ) ) ) );
		exit;
	}
	load_plugin_textdomain( 'wpsc_gridview', false, WPSC_GRID_VIEW_FOLDER.'/languages' );
}
// Add language
add_action('init', 'wpsc_gridview_init');

// Add text on right of Visit the plugin on Plugin manager page
add_filter( 'plugin_row_meta', array('WPSC_GridView_Hook_Filter', 'plugin_extra_links'), 10, 2 );

// Notices upgrade to PRO version to all pages in dashboard
add_action('admin_notices', array('WPSC_GridView_Hook_Filter', 'wpsc_gridview_upgrade_notice') );

add_action( 'wp_head', array('WPSC_GridView_Hook_Filter','wpsc_grid_view_styles') );
add_action( 'wp_head', array('WPSC_GridView_Hook_Filter','wpsc_grid_custom_styles'), 9 );

update_option('a3rev_wpsc_gridview_version', '1.0.3');

if ( !function_exists( 'product_display_list' ) ){
	function product_display_list( $product_list, $group_type, $group_sql = '', $search_sql = '' ) {
		WPSC_GridView_Hook_Filter::product_display_list( $product_list, $group_type, $group_sql , $search_sql );
	}
}

if ( !function_exists( 'product_display_grid' ) ){
	function product_display_grid( $product_list, $group_type, $group_sql = '', $search_sql = '' ) {
		WPSC_GridView_Hook_Filter::product_display_grid( $product_list, $group_type, $group_sql , $search_sql );
	}
}
?>