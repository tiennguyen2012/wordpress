<?php
/*
Plugin Name: WP e-Commerce Catalog Visibility Email inquiry LITE
Plugin URI: http://a3rev.com/shop/catalog-visibilty-and-email-inquiry/
Description: Transform your entire WP e-Commerce product catalog into an online brochure by replacing the Add to Cart Button with an Email Enquiry Button and pop-up email form. Alternatively use the plugin to add a Product Email Inquiry button and pop-up to your entire e-commerce activated product pages.
Version: 1.0.6
Author: A3 Revolution
Author URI: http://www.a3rev.com/
License: This software is under commercial license and copyright to A3 Revolution Software Development team

	WP e-Commerce Catalog Visibility Email inquiry. Plugin for the WP e-Commerce shopping Cart.
	Copyright© 2011 A3 Revolution Software Development team
	
	A3 Revolution Software Development team
	admin@a3rev.com
	PO Box 1170
	Gympie 4570
	QLD Australia
*/
?>
<?php
define('WPEC_PCF_FILE_PATH', dirname(__FILE__));
define('WPEC_PCF_DIR_NAME', basename(WPEC_PCF_FILE_PATH));
define('WPEC_PCF_FOLDER', dirname(plugin_basename(__FILE__)));
define('WPEC_PCF_URL', WP_CONTENT_URL.'/plugins/'.WPEC_PCF_FOLDER);
define('WPEC_PCF_DIR', WP_CONTENT_DIR.'/plugins/'.WPEC_PCF_FOLDER);
define('WPEC_PCF_NAME', plugin_basename(__FILE__) );
define('WPEC_PCF_IMAGES_URL',  WPEC_PCF_URL . '/assets/images' );
define('WPEC_PCF_JS_URL',  WPEC_PCF_URL . '/assets/js' );
define('WPEC_PCF_CSS_URL',  WPEC_PCF_URL . '/assets/css' );
if(!defined("WPEC_PCF_AUTHOR_URI"))
    define("WPEC_PCF_AUTHOR_URI", "http://a3rev.com/shop/catalog-visibilty-and-email-inquiry/");

include('admin/classes/class-pcf-settings.php');
include('classes/class-pcf-functions.php');
include('classes/class-pcf-hook.php');
include('classes/class-pcf-metabox.php');
include('admin/pcf-init.php');

/**
* Call when the plugin is activated and deactivated
*/
register_activation_hook(__FILE__,'wpec_pcf_install');
?>