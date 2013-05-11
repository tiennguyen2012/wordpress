=== WP e-Commerce Catalog Visibility  ===
Contributors: a3rev, A3 Revolution Software Development team
Tags: WP e-Commerce Catalog Visibility, WP e-Commerce, WP e-Commerce Email Inquiry, e-commerce, wordpress ecommerce
Requires at least: 3.3
Tested up to: 3.5.1
Stable tag: 1.0.6
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

WP e-Commerce Catalog Visibility and Email Inquiry transform WP e-Commerce into an online brochure with Product Email Inquiry button and pop-up form.
  
== Description ==

WP e-Commerce Catalog Visibility and Email Inquiry Lite Version allows you to configure Add to Cart and Email Inquiry functionality globally across your WP e-Commerce store on your single product pages, shop pages - list view, grid view, category and tags pages. 

= Configure =

1. Add to Cart Button to show with an Email Inquiry button with a stylish pop-up product email inquiry form
OR
2. Hide the add to cart Button and just show the Email Inquiry Button turning your site into a brochure site.
OR
3. Hide the Add to Cart Button and don't show Email Button.
PLUS
4. Choose to apply the above setting to logged in users.

WP e-Commerce Catalog Visibility and Email Inquiry uses the WordPress email config and requires no external email plugin. The email sent to the WP e-Commerce email admin address from each product inquiry contains:

* Senders Name
* Senders Email address
* Senders Phone
* Product name the inquiry is sent from as the email subject plus a link to the product page they sent the inquiry from.
* Senders Message.

WP e-Commerce Catalog Visibility and Email Inquiry Lite is very simple to set up. Once you have installed and activated go to you wp_admin dashboard Settings > Store > Catalog Visibility Tab.

= WP e-Commerce Version Compatibility =Please note that WP e-Commerce Catalog Visibility and Email Inquiry requires that you have <strong>WP e-Commerce version 3.8.8.5 and after</strong> to work. Older versions of WP e-Commerce do not support the functions to hook the email inquiry button to the add to cart button.= WP e-Commerce Theme Compatibility =Whilst you might have version 3.8.8.5 your theme may still use an earlier version of code. For the Email Inquiry button to show on the single product pages and products pages you must ensure that it has this code do_action('wpsc_product_form_fields_begin') and do_action('wpsc_product_form_fields_end') in these files:* wpsc-grid_view.php* wpsc-list_view.php* wpsc-products_page.php* wpsc-single_product.php

= Documentation =

Find detailed step-by-step set up and configuration instructions in the [plugins documents](http://docs.a3rev.com/user-guides/wp-e-commerce/wpec-catalog-visibility-and-email-inquiry/) on the a3rev wiki.

= Pro Version =

For those who want more control there is a PRO Version upgrade. The PRO version advanced features include:

= Global Settings: =
* Set custom email recipient (different to the default WPEC admin email)
* Set a cc recipient
* Set your own custom Email Button title and set custom style for email button.
* Use text on page with hyperlink pop-up email form instead of the Email Button 
* Customize Hyperlink text
* Style pop-up window email form and submit button.
* Customize the successfully submitted message that shows in the pop up when a message has been sent.

And for ultimate control:

= Customize Single Product Page: =
* Customize Catalog visibility settings and email inquiry settings on any single product page. 
* Over-ride any of the global Catalog Visibility options from the product page.
* Change the Email Inquiry form primary recipient and cc recipient
* Change Button for linked text and visa versa - Change button title or link text.
* Only show this products settings on the single product page or on all catalog listing thumbnails. 

= Localization =

* English (default) - always included.
*.po file (wpec_pcf.po) in languages folder for translations.
* If you do a translation for your site please send it to us for inclusion in the plugin language folder. We'll acknowledge your work here. [Go here](http://a3rev.com/contact/) to send your translation files to us.

= Plugin Resources =

[PRO Version](http://a3rev.com/shop/catalog-visibilty-and-email-inquiry/) |
[Documentation](http://docs.a3rev.com/user-guides/wp-e-commerce/wpec-catalog-visibility-and-email-inquiry/) |
[Support](http://a3rev.com/shop/catalog-visibilty-and-email-inquiry/#help_tab)


== Installation ==

= Minimum Requirements =

* WordPress 3.4.1
* WP e-Commerce 3.8.8.5
* PHP version 5.2.4 or greater
* MySQL version 5.0 or greater
 
= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don't even need to leave your web browser. To do an automatic install of WP e-Commerce Catalog Visibility and Email Inquiry, log in to your WordPress admin panel, navigate to the Plugins menu and click Add New. 

In the search field type "WP e-Commerce Catalog Visibility and Email Inquiry" and click Search Plugins. Once you have found our plugin you can install it by simply clicking Install Now. After clicking that link you will be asked if you are sure you want to install the plugin. Click yes and WordPress will automatically complete the installation. 

= Manual installation =

The manual installation method involves downloading our plugin and uploading it to your web server via your favorite FTP application.

1. Download the plugin file to your computer and unzip it
2. Using an FTP program, or your hosting control panel, upload the unzipped plugin folder to your WordPress installations wp-content/plugins/ directory.
3. Activate the plugin from the Plugins menu within the WordPress admin.


== Screenshots ==

1. Transform WP e-Commerce into a brochure site 
2. WP e-Commerce pop-up email form
3. After email sent success message that shows in the pop-up window.
4. The email you receive with attractive styling built in.
 
== Usage ==

1. Install and activate the plugin

2. Go to Settings > Store > Catalog Visibility tab

3. Select the global settings you require.

4. Scroll to the bottom and click save.
 
5. Have fun.

== Frequently Asked Questions ==

= When can I use this plugin? =

You can use this plugin only when you have installed the WP e-Commerce plugin.
 
== Support ==

Support and access to this plugin documents are available from the [HELP tab](http://a3rev.com/shop/catalog-visibilty-and-email-inquiry/#help_tab) on the Pro Versions Home page.

== Changelog ==

= 1.0.6 - 2013/04/22 =
* Feature: Updated all plugin code to be 100% compatible with the new WP e-Commerce version 3.8.10. The plugin is backward compatible with older version to 3.8.5

= 1.0.5 - 2013/01/25 =
* Fixed: Email Delivery address - when the plugin is installed it now checks for WP e-Commerce Store Admin Email address and uses that if one has been entered. If it does not find a Store Admin Email Address, then it checks for and uses the WordPress admin email address. The WP e-Commerce Store Admin Email address always takes priority the WordPress Admin email address. This means that if the Store admin address is entered after the WP e-Commerce Catalog Visibility an Email Inquiry is installed it auto becomes the plugins delivery address.
* Tweak: Added an on screen Attention message if the plugin does not find a WP e-Commerce store admin email address. Notifies the user that the WordPress admin email address is being used by the plugin as the 'send to' address and asks the user to add a WP e-commerce Store email address.

= 1.0.4 - 2013/01/19 =
* Feature: Added auto support for custom button type for 'add to cart' button so that our plugin can find the add to cart button on themes that use custom button code.

= 1.0.3 - 2013/01/09 =
* Tweak: Updated Support and Pro Version link URL's on wordpress.org description, plugins and plugins dashboard. Links were returning 404 errors since the launch of the all new a3rev.com mobile responsive site as the base e-commerce permalinks is changed.

= 1.0.2 - 2012/08/27 =* Fixed: Combined button and pop-up window style scripts in one script to overcome javascript conflict with some IE javascript styling scripts.* Tweak: Updated WordPress description text and readme to show the WP e-Commerce 3.8.8.5 do_action functions that must be included in themes for the Email Button to show. Some older themes don't have updated function and even though users have the 3.8.8.5 WP e-commerce version the button can't show.* Plugin documentation completed.

= 1.0.1 - 2012/08/25 =
* Fixed: Moved style from header to footer to resolve plugin breaking themes css in IE 7, 8 and 9
* Tweak: Removed left and right 10px float and set so button left / right float is the same as the add to cart button.

= 1.0.0 - 2012/08/23 =
* First Release.


== Upgrade Notice ==

= 1.0.6 =
Important upgrade! Install now to ensure this plugin continues to work when you upgrade WP e-Commerce to new version 3.8.10.  