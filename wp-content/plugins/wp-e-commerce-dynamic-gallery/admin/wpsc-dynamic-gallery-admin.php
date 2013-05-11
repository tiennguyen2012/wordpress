<?php
function wpsc_dynamic_gallery_install(){
	update_option('a3rev_wpsc_dgallery_version', '1.1.3');
	WPSC_Settings_Tab_Gallery_Settings::wpsc_dynamic_gallery_set_setting(true, true);
	
	update_option('a3rev_wpsc_dgallery_just_installed', true);
}

/**
 * Load languages file
 */
function wpsc_dynamic_gallery_init() {
	if ( get_option('a3rev_wpsc_dgallery_just_installed') ) {
		delete_option('a3rev_wpsc_dgallery_just_installed');
		wp_redirect( ( ( is_ssl() || force_ssl_admin() || force_ssl_login() ) ? str_replace( 'http:', 'https:', admin_url( 'options-general.php?page=wpsc-settings&tab=gallery_settings' ) ) : str_replace( 'https:', 'http:', admin_url( 'options-general.php?page=wpsc-settings&tab=gallery_settings' ) ) ) );
		exit;
	}
	load_plugin_textdomain( 'wpsc_dgallery', false, WPSC_DYNAMIC_GALLERY_FOLDER.'/languages' );
	$thumb_width = get_option('thumb_width');
	$thumb_height = get_option('thumb_height');
	add_image_size( 'wpsc-dynamic-gallery-thumb', $thumb_width, $thumb_height, false  );
}
// Add language
add_action('init', 'wpsc_dynamic_gallery_init');

// Add text on right of Visit the plugin on Plugin manager page
add_filter( 'plugin_row_meta', array('WPSC_Dynamic_Gallery_Hook_Filter', 'plugin_extra_links'), 10, 2 );

// Add Dynamic Gallery tab into Store settings 	
add_filter( 'wpsc_settings_tabs', array('WPSC_Dynamic_Gallery_Hook_Filter', 'add_wpsc_settings_tabs') );

// Add extra fields for image in Product Edit Page
add_filter( 'attachment_fields_to_edit', array('WPSC_Dynamic_Gallery_Hook_Filter', 'wpsc_attachment_fields_filter'), 12, 2 );
add_filter( 'attachment_fields_to_save', array('WPSC_Dynamic_Gallery_Hook_Filter', 'wpsc_exclude_image_from_product_page_field_save'), 1, 2 );
add_action( 'add_attachment', array('WPSC_Dynamic_Gallery_Hook_Filter', 'wpsc_exclude_image_from_product_page_field_add') );

// Version 1.0.5
add_filter( 'attachment_fields_to_edit', array('WPSC_Dynamic_Gallery_Variations', 'media_fields'), 13, 2 );

add_action( 'wp_head',array('WPSC_Dynamic_Gallery_Hook_Filter', 'wpsc_hide_featured_image_single_product'),1 );

add_action('admin_footer', array('WPSC_Dynamic_Gallery_Hook_Filter', 'wp_admin_footer_scripts') );

//Ajax Preview gallery
add_action('wp_ajax_wpsc_dynamic_gallery', array('WPSC_Dynamic_Gallery_Hook_Filter', 'wpsc_dynamic_gallery_preview') );
add_action('wp_ajax_nopriv_wpsc_dynamic_gallery', array('WPSC_Dynamic_Gallery_Hook_Filter', 'wpsc_dynamic_gallery_preview') );

//Ajax do dynamic gallery frontend
add_action('wp_ajax_wpsc_dynamic_gallery_frontend', array('WPSC_Dynamic_Gallery_Hook_Filter', 'wpsc_dynamic_gallery_frontend') );
add_action('wp_ajax_nopriv_wpsc_dynamic_gallery_frontend', array('WPSC_Dynamic_Gallery_Hook_Filter', 'wpsc_dynamic_gallery_frontend') );

//Frontend do dynamic gallery
add_action('wp_head', 'wpsc_show_dynamic_gallery_with_goldcart' );

function wpsc_show_dynamic_gallery_with_goldcart() {
	if ( !function_exists( 'gold_shpcrt_display_gallery' ) ){
		function gold_shpcrt_display_gallery($product_id){
			if(is_singular('wpsc-product')){
				WPSC_Dynamic_Gallery_Hook_Filter::dynamic_gallery_frontend_script();
				echo WPSC_Dynamic_Gallery_Display_Class::wpsc_dynamic_gallery_display($product_id);
			}
		}
	}else{
		if(is_singular('wpsc-product')){
			WPSC_Dynamic_Gallery_Hook_Filter::dynamic_gallery_frontend_script();
			add_action('get_footer', array('WPSC_Dynamic_Gallery_Hook_Filter', 'do_dynamic_gallery'), 8 );
		}
	}	
}

// Upgrade to 1.0.4
if(version_compare(get_option('a3rev_wpsc_dgallery_version'), '1.0.4') === -1){
	update_option('width_type','px');
	update_option('a3rev_wpsc_dgallery_version', '1.0.4');
}

update_option('a3rev_wpsc_dgallery_version', '1.1.3');
?>