<?php
/**
 * Call this function when plugin is deactivated
 */
function wpec_pcf_install(){
	update_option('a3rev_wpec_pcf_version', '1.0.6');
	WPSC_Settings_Tab_Catalog_Visibility::set_settings_default(true);
}

update_option('a3rev_wpec_pcf_plugin', 'wpec_pcf');

/**
 * Load languages file
 */
function wpec_pcf_init() {
	load_plugin_textdomain( 'wpec_pcf', false, WPEC_PCF_FOLDER.'/languages' );
}
// Add language
add_action('init', 'wpec_pcf_init');

// Add warning message when does not find an email address enter in either - WPEC Store admin or WordPress admin
add_action( 'admin_notices', array('WPEC_PCF_Hook_Filter', 'admin_warning_noemail'), 1 );

// Add text on right of Visit the plugin on Plugin manager page
add_filter( 'plugin_row_meta', array('WPEC_PCF_Hook_Filter', 'plugin_extra_links'), 10, 2 );
	
		
	// Add Catalog Visibility tab into Store settings 	
	add_filter( 'wpsc_settings_tabs', array('WPEC_PCF_Hook_Filter', 'add_wpsc_settings_tabs') );
	
	// Include style into header
	add_action('get_header', array('WPEC_PCF_Hook_Filter', 'add_style_header'), 1);
	
	// Include script into footer
	add_action('get_footer', array('WPEC_PCF_Hook_Filter', 'script_contact_popup'), 1);
	
	// AJAX pcf contact popup
	add_action('wp_ajax_pcf_contact_popup', array('WPEC_PCF_Hook_Filter', 'pcf_contact_popup') );
	add_action('wp_ajax_nopriv_pcf_contact_popup', array('WPEC_PCF_Hook_Filter', 'pcf_contact_popup') );
	
	// AJAX pcf_contact_action
	add_action('wp_ajax_pcf_contact_action', array('WPEC_PCF_Hook_Filter', 'pcf_contact_action') );
	add_action('wp_ajax_nopriv_pcf_contact_action', array('WPEC_PCF_Hook_Filter', 'pcf_contact_action') );
	
	// Include script admin plugin
	add_action('admin_head', array('WPEC_PCF_Hook_Filter', 'admin_footer_scripts') );
	add_action('admin_footer', array('WPEC_PCF_Hook_Filter', 'wp_admin_footer_scripts') );
	
	// Add email button for each product
	if((get_option('hide_addtocart_button') == 0) &&  (get_option('addtocart_or_buynow') !='1')){
		require_once(ABSPATH.'wp-admin/includes/plugin.php');
		$wpec_version = get_plugin_data(WP_PLUGIN_DIR.'/wp-e-commerce/wp-shopping-cart.php');
		if(version_compare($wpec_version['Version'], '3.8.7', '<')){ 
			add_action('the_post', array('WPEC_PCF_Hook_Filter', 'hide_addcartbt'));
			add_action('the_post', array('WPEC_PCF_Hook_Filter', 'script_show_contact_button'));
		}else{
			add_action('wpsc_product_form_fields_begin', array('WPEC_PCF_Hook_Filter', 'hide_addcartbt'));
			add_action('wpsc_product_form_fields_begin', array('WPEC_PCF_Hook_Filter', 'script_show_contact_button'));
		}
	} else {
		add_action('wpsc_product_form_fields_end', array('WPEC_PCF_Hook_Filter', 'add_contact_button'), 100);
	}
	
	// Add meta boxes to product page
	add_action( 'admin_menu', array('WPEC_PCF_MetaBox', 'add_meta_boxes') );
	if(in_array(basename($_SERVER['PHP_SELF']), array('post.php', 'page.php', 'page-new.php', 'post-new.php'))){
		add_action('save_post', array('WPEC_PCF_MetaBox','save_meta_boxes' ) );
	}

	update_option('a3rev_wpec_pcf_version', '1.0.6');
?>