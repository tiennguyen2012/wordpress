<?php
/*
Plugin Name: WP e-Commerce Call for Price
Plugin URI: http://ecommercewp.com/wp-e-commerce-call-for-price/
Description: This is a WP e-Commerce plugin that allows you to hide the price of a specific product and repplace it with a message asking your customers to call for price.
Version: 1.0
Author: Andre Pidbereznyak
Author URI: http://transparentideas.com
License: GPL2

Copyright 2012  Andre Pidbereznyak  (email : andy@transparentideas.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!defined('PHP_VERSION_ID')) {

    $version = explode('.', PHP_VERSION);

    define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));

}


if (file_exists('../wp-admin/includes/plugin.php')){
    require_once('../wp-admin/includes/plugin.php');
}

define('WPEC_CALL_FOR_PRICE_VERSION', '1.0');

define('WPEC_CALL_FOR_PRICE_PLUGIN_PATH',   plugin_basename(__FILE__) );

if ( PHP_VERSION_ID < 50300 ) {

    wp_die('<div class="error"><p>'.__("The WPEC Call for Price plugin requieres PHP version not less then 5.3", 'wpec-call-for-price').'</p></div>');

    if(is_plugin_active(WPEC_CALL_FOR_PRICE_PLUGIN_PATH)) {

        deactivate_plugins(WPEC_CALL_FOR_PRICE_PLUGIN_PATH);

    }
}



class TICallForPrice{

   function __construct(){

       add_action('plugins_loaded', array($this, 'init'), 9);

   }


   function init(){

       if($this->check_wpec_plugin()){

           add_action( 'admin_head' , array($this, 'override_wpsc_price_control_forms') );
           add_action( 'wp_enqueue_scripts' ,  array($this, 'load_front_end_js') );
           add_action( 'admin_enqueue_scripts', array($this, 'load_back_end_js') );
           add_action( 'wp_head', array($this, 'load_front_end_css') );
           add_action( 'admin_init' , array($this, 'overload_product_submit' ));
           add_action( 'wpsc_product_form_fields_end', array($this, 'show_cfp') );
           add_action( 'wpsc_product_form_fields_end', array($this, 'hide_price') );
           load_plugin_textdomain( 'wpec-call-for-price', false, dirname( plugin_basename( __FILE__ ) ) . '/locales/' );

           if ( is_admin() ){

               add_action( 'admin_menu', array($this, 'cfp_menu' ));
               add_action( 'admin_init', array($this, 'register_cfpsettings') );

           }

       }
   }

    function register_cfpsettings(){

        register_setting( 'cfpoption-group', 'cfp_default_message' );
        register_setting( 'cfpoption-group', 'cfp_icon' );
        register_setting( 'cfpoption-group', 'cfp_icon_image' );
    }

    function override_wpsc_price_control_forms(){

        remove_meta_box( 'wpsc_price_control_forms', 'wpsc-product', 'side' );
        add_meta_box( 'cfp_wpsc_price_control_forms', __('Price Control', 'wpsc'), array($this, 'cfp_wpsc_price_control_forms'), 'wpsc-product', 'side', 'default' );

   }


   function cfp_menu(){

       add_options_page( __('Call for Price', 'wpec-call-for-price'), __('WPEC Call for Price', 'wpec-call-for-price'), 'manage_options', 'cfp_admin_options', array($this, 'cfp_plugin_options') );

   }

   function cfp_plugin_options(){

       if ( !current_user_can( 'manage_options' ) )  {
           wp_die( __( 'You do not have sufficient permissions to access this page.', 'wpec-call-for-price' ) );
       }
       echo '<div class="wrap">
                <div id="icon-options-general" class="icon32"><br></div>
                ';
       echo '<h2>'.__('WP e-Commerce Call for Price','wpec-call-for-price').'</h2>';
       echo '<form method="post" action="options.php">';
       settings_fields( 'cfpoption-group' );
       do_settings_fields( 'cfpoption-group' , 'cfpoption');
       ?>
    
       <div class="form-field" style="width: 50%">
           <table class="form-table">
               <tr valign="top">
                   <th scope="row"><?php _e('Default "Call for Price" message: ', 'wpec-call-for-price') ?> </th>
                   <td><input type="text" name="cfp_default_message" value="<?php echo get_option('cfp_default_message'); ?>" /></td>
               </tr>
           </table>

        
           <br/><br/>
    
       <div id="poststuff" class="postbox">
           <h3 class="hndle"><?php _e('Call for Price Icon','wpec-call-for-price'); ?></h3>
    
           <div class="inside">
                
                <p>
                    <?php _e('You may use either one of the listed below "Call for Price" icons or chose your own ','wpec-call-for-price'); ?>
                </p>
    
               <label for="cfp_icons"><?php _e('Call for Price Icon','wpec-call-for-price'); ?></label>
                <br/>
               <input id="upload_image" type="text" size="16" name="cfp_icon_image" value="<?php echo get_option('cfp_icon_image');?>" style="width:55%;" />

               <input id="upload_image_button" style="width:85px" type="button" value="<?php _e('Upload Icon', 'wpec-call-for-price') ?>" />


    
    
               <ul class="cfp_icons" id="cfp_icons">
                   <li <?php echo $msg = ( WP_PLUGIN_URL."/".plugin_basename(__DIR__). "/media/cfp_1.png" == get_option('cfp_icon') ) ? 'class="selected"' : ''   ?> ><img id="cfp_1" src=<?php echo WP_PLUGIN_URL."/".plugin_basename(__DIR__); ?>/media/cfp_1.png  alt="<?php _e('Call for Price', 'wpec-call-for-price'); ?>"></li>
                   <li <?php echo $msg =  ( WP_PLUGIN_URL."/".plugin_basename(__DIR__). "/media/cfp_2.png" == get_option('cfp_icon') ) ? 'class="selected"' : ''   ?> ><img id="cfp_2" src=<?php echo WP_PLUGIN_URL."/".plugin_basename(__DIR__); ?>/media/cfp_2.png alt="<?php _e('Call for Price', 'wpec-call-for-price'); ?>"></li>
                   <li <?php echo $msg =  ( WP_PLUGIN_URL."/".plugin_basename(__DIR__). "/media/cfp_3.png" == get_option('cfp_icon') ) ? 'class="selected"' : ''   ?> ><img id="cfp_3" src=<?php echo WP_PLUGIN_URL."/".plugin_basename(__DIR__); ?>/media/cfp_3.png  alt="<?php _e('Call for Price', 'wpec-call-for-price'); ?>"></li>
                   <li <?php echo $msg =  ( WP_PLUGIN_URL."/".plugin_basename(__DIR__). "/media/cfp_4.png" == get_option('cfp_icon') ) ? 'class="selected"' : ''   ?> ><img id="cfp_4" src=<?php echo WP_PLUGIN_URL."/".plugin_basename(__DIR__); ?>/media/cfp_4.png alt="<?php _e('Call for Price', 'wpec-call-for-price'); ?>"></li>
                   <li <?php echo $msg =  ( WP_PLUGIN_URL."/".plugin_basename(__DIR__). "/media/cfp_5.png" == get_option('cfp_icon') ) ? 'class="selected"' : ''   ?> ><img id="cfp_5" src=<?php echo WP_PLUGIN_URL."/".plugin_basename(__DIR__); ?>/media/cfp_5.png alt="<?php _e('Call for Price', 'wpec-call-for-price'); ?>"></li>
                   <li <?php echo $msg =  ( WP_PLUGIN_URL."/".plugin_basename(__DIR__). "/media/cfp_6.png" == get_option('cfp_icon') ) ? 'class="selected"' : ''   ?> ><img id="cfp_6" src=<?php echo WP_PLUGIN_URL."/".plugin_basename(__DIR__); ?>/media/cfp_6.png alt="<?php _e('Call for Price', 'wpec-call-for-price'); ?>"></li>
                   <li <?php echo $msg =  ( WP_PLUGIN_URL."/".plugin_basename(__DIR__). "/media/cfp_7.png" == get_option('cfp_icon') ) ? 'class="selected"' : ''   ?> ><img id="cfp_7" src=<?php echo WP_PLUGIN_URL."/".plugin_basename(__DIR__); ?>/media/cfp_7.png alt="<?php _e('Call for Price', 'wpec-call-for-price'); ?>"></li>
                   <li <?php echo $msg =  ( WP_PLUGIN_URL."/".plugin_basename(__DIR__). "/media/cfp_8.png" == get_option('cfp_icon') ) ? 'class="selected"' : ''   ?> ><img id="cfp_8" src=<?php echo WP_PLUGIN_URL."/".plugin_basename(__DIR__); ?>/media/cfp_8.png alt="<?php _e('Call for Price', 'wpec-call-for-price'); ?>"></li>

                   <li <?php echo $msg = ( WP_PLUGIN_URL."/".plugin_basename(__DIR__). "/media/cfp_9.png" == get_option('cfp_icon') ) ? 'class="selected"' : ''   ?> ><img id="cfp_9" src=<?php echo WP_PLUGIN_URL."/".plugin_basename(__DIR__); ?>/media/cfp_9.png  alt="<?php _e('Call for Price', 'wpec-call-for-price'); ?>"></li>
                   <li <?php echo $msg =  ( WP_PLUGIN_URL."/".plugin_basename(__DIR__). "/media/cfp_10.png" == get_option('cfp_icon') ) ? 'class="selected"' : ''   ?> ><img id="cfp_10" src=<?php echo WP_PLUGIN_URL."/".plugin_basename(__DIR__); ?>/media/cfp_10.png alt="<?php _e('Call for Price', 'wpec-call-for-price'); ?>"></li>
                   <li <?php echo $msg =  ( WP_PLUGIN_URL."/".plugin_basename(__DIR__). "/media/cfp_11.png" == get_option('cfp_icon') ) ? 'class="selected"' : ''   ?> ><img id="cfp_11" src=<?php echo WP_PLUGIN_URL."/".plugin_basename(__DIR__); ?>/media/cfp_11.png  alt="<?php _e('Call for Price', 'wpec-call-for-price'); ?>"></li>
                   <li <?php echo $msg =  ( WP_PLUGIN_URL."/".plugin_basename(__DIR__). "/media/cfp_12.png" == get_option('cfp_icon') ) ? 'class="selected"' : ''   ?> ><img id="cfp_12" src=<?php echo WP_PLUGIN_URL."/".plugin_basename(__DIR__); ?>/media/cfp_12.png alt="<?php _e('Call for Price', 'wpec-call-for-price'); ?>"></li>
                   <li <?php echo $msg =  ( WP_PLUGIN_URL."/".plugin_basename(__DIR__). "/media/cfp_13.png" == get_option('cfp_icon') ) ? 'class="selected"' : ''   ?> ><img id="cfp_13" src=<?php echo WP_PLUGIN_URL."/".plugin_basename(__DIR__); ?>/media/cfp_13.png alt="<?php _e('Call for Price', 'wpec-call-for-price'); ?>"></li>
                   <li <?php echo $msg =  ( WP_PLUGIN_URL."/".plugin_basename(__DIR__). "/media/cfp_14.png" == get_option('cfp_icon') ) ? 'class="selected"' : ''   ?> ><img id="cfp_14" src=<?php echo WP_PLUGIN_URL."/".plugin_basename(__DIR__); ?>/media/cfp_14.png alt="<?php _e('Call for Price', 'wpec-call-for-price'); ?>"></li>
                   <li <?php echo $msg =  ( WP_PLUGIN_URL."/".plugin_basename(__DIR__). "/media/cfp_15.png" == get_option('cfp_icon') ) ? 'class="selected"' : ''   ?> ><img id="cfp_15" src=<?php echo WP_PLUGIN_URL."/".plugin_basename(__DIR__); ?>/media/cfp_15.png alt="<?php _e('Call for Price', 'wpec-call-for-price'); ?>"></li>

                   <?php $custom_image = get_option('cfp_icon_image'); if ($custom_image  != '') : ?><li <?php echo $msg = ( $custom_image == get_option('cfp_icon') ) ? 'class="selected"' : ''   ?> ><img id="cfp_custom" src=<?php echo $custom_image; ?> alt="<?php _e('Call for Price', 'wpec-call-for-price'); ?>"></li><?php endif;?>
               </ul>

               <input type="hidden" id="cfp_custom_icon" name="cfp_icon" value="<?php echo get_option('cfp_icon'); ?>" />


               <div class="clear"></div>


                <style>

                    .cfp_icons li{

                        float: left;
                        padding-left: 37px;
                        width: 165px;
                        height: 40px;
                        background: url(<?php echo WP_PLUGIN_URL."/".plugin_basename(__DIR__); ?>/media/unselected.png) center left no-repeat;
                        margin-right:10px;
                        margin-bottom: 20px;
                        vertical-align: middle;

                    }

                    .cfp_icons li img{

                        cursor: pointer;
                    }

                    .cfp_icons li.selected, .cfp_icons li:hover{

                        background: url(<?php echo WP_PLUGIN_URL."/".plugin_basename(__DIR__); ?>/media/selected.png) center left no-repeat;

                    }

                </style>
    
    
    
           </div>
       </div>
       </div>

   

       <?php
       echo "<p class='submit'>
                <input type='submit' class='button-primary' value='".__('Save Changes')."' />
            </p>";

       echo '</form>';
       echo '</div>';

   }

//---Modification of the wpsc_price_control_forms original WP e-Commerce Plugin's function------------------------------

    function cfp_wpsc_price_control_forms() {
        global $post, $wpdb, $variations_processor, $wpsc_product_defaults;
        $product_data = get_post_custom( $post->ID );
        $product_data['meta'] = maybe_unserialize( $product_data );

        foreach ( $product_data['meta'] as $meta_key => $meta_value )
            $product_data['meta'][$meta_key] = $meta_value[0];

        $product_meta = array();
        if ( !empty( $product_data["_wpsc_product_metadata"] ) )
            $product_meta = maybe_unserialize( $product_data["_wpsc_product_metadata"][0] );

        if ( isset( $product_data['meta']['_wpsc_currency'] ) )
            $product_alt_currency = maybe_unserialize( $product_data['meta']['_wpsc_currency'] );

        if ( !isset( $product_data['meta']['_wpsc_table_rate_price'] ) ) {
            $product_data['meta']['_wpsc_table_rate_price'] = $wpsc_product_defaults['meta']['table_rate_price'];
        }
        if ( isset( $product_meta['_wpsc_table_rate_price'] ) ) {
            $product_meta['table_rate_price']['state'] = 1;
            $product_meta['table_rate_price'] += $product_meta['_wpsc_table_rate_price'];
            $product_data['meta']['_wpsc_table_rate_price'] = $product_meta['_wpsc_table_rate_price'];
        }


        if ( !isset( $product_data['meta']['_wpsc_is_donation'] ) )
            $product_data['meta']['_wpsc_is_donation'] = $wpsc_product_defaults['donation'];

        if ( !isset( $product_meta['table_rate_price']['state'] ) )
            $product_meta['table_rate_price']['state'] = null;

        if ( !isset( $product_meta['table_rate_price']['quantity'] ) )
            $product_meta['table_rate_price']['quantity'] = $wpsc_product_defaults['meta']['table_rate_price']['quantity'][0];

        if ( !isset( $product_data['meta']['_wpsc_price'] ) )
            $product_data['meta']['_wpsc_price'] = $wpsc_product_defaults['price'];

        if ( !isset( $product_data['special'] ) )
            $product_data['special'] = $wpsc_product_defaults['special'];

        if ( !isset( $product_data['meta']['_wpsc_special_price'] ) )
            $product_data['meta']['_wpsc_special_price'] = $wpsc_product_defaults['special_price'];

        $currency_data = $wpdb->get_results( "SELECT * FROM `" . WPSC_TABLE_CURRENCY_LIST . "` ORDER BY `country` ASC", ARRAY_A );
        ?>
    <input type="hidden" id="parent_post" name="parent_post" value="<?php echo $post->post_parent; ?>" />
    <?php /* Lots of tedious work is avoided with this little line. */ ?>
    <input type="hidden" id="product_id" name="product_id" value="<?php echo $post->ID; ?>" />

    <?php /* Check product if a product has variations (Wording doesn't make sense.  If Variations box is closed, you don't go there, and it's not necessarily "below") */ ?>
    <?php if ( wpsc_product_has_children( $post->ID ) ) : ?>
        <?php $price = wpsc_product_variation_price_available( $post->ID ); ?>
        <p><?php _e( 'This Product has variations, to edit the price please use the <a href="#variation_control">Variation Controls</a> below.' , 'wpsc'  ); ?></p>
        <p><?php printf( __( 'Price: %s and above.' , 'wpsc' ) , $price ); ?></p>
        <?php else: ?>

        <div class='wpsc_floatleft' style="width:85px;">
            <label><?php _e( 'Price', 'wpsc' ); ?>:</label><br />
            <input type='text' class='text' size='10' name='meta[_wpsc_price]' value='<?php echo ( isset($product_data['meta']['_wpsc_price']) ) ? number_format( (float)$product_data['meta']['_wpsc_price'], 2, '.', '' ) : '0.00';  ?>' />
        </div>
        <div class='wpsc_floatleft' style='display:<?php if ( ( $product_data['special'] == 1 ) ? 'block' : 'none'
        ); ?>; width:85px; margin-left:30px;'>
            <label for='add_form_special'><?php _e( 'Sale Price', 'wpsc' ); ?>:</label>
            <div id='add_special'>
                <input type='text' size='10' value='<?php echo ( isset($product_data['meta']['_wpsc_special_price']) ) ? number_format( (float)$product_data['meta']['_wpsc_special_price'], 2, '.', '' ) : '0.00' ; ?>' name='meta[_wpsc_special_price]' />
            </div>
        </div>
        <br style="clear:both" />
        <br style="clear:both" />
        <a href='#' class='wpsc_add_new_currency'><?php _e( '+ New Currency', 'wpsc' ); ?></a>
        <br />
        <!-- add new currency layer -->
        <div class='new_layer'>
            <label for='newCurrency[]'><?php _e( 'Currency type', 'wpsc' ); ?>:</label><br />
            <select name='newCurrency[]' class='newCurrency' style='width:42%'>
                <?php
                foreach ( (array)$currency_data as $currency ) {?>
                    <option value='<?php echo $currency['id']; ?>' >
                        <?php echo htmlspecialchars( $currency['country'] ); ?> (<?php echo $currency['currency']; ?>)
                    </option> <?php
                } ?>
            </select>
            <?php _e( 'Price', 'wpsc' ); ?> :
            <input type='text' class='text' size='8' name='newCurrPrice[]' value='0.00' style='display:inline' />
            <a href='' class='wpsc_delete_currency_layer'><img src='<?php echo WPSC_CORE_IMAGES_URL; ?>/cross.png' /></a>

        </div> <!-- close new_layer -->
        <?php
            if ( isset( $product_alt_currency ) && is_array( $product_alt_currency ) ) :
                $i = 0;
                foreach ( $product_alt_currency as $iso => $alt_price ) {
                    $i++; ?>
                <div class='wpsc_additional_currency'>
                    <label for='newCurrency[]'><?php _e( 'Currency type', 'wpsc' ); ?>:</label><br />
                    <select name='newCurrency[]' class='newCurrency' style='width:42%'> <?php
                        foreach ( $currency_data as $currency ) {
                            if ( $iso == $currency['isocode'] )
                                $selected = "selected='selected'";
                            else
                                $selected = ""; ?>
                            <option value='<?php echo $currency['id']; ?>' <?php echo $selected; ?> >
                                <?php echo htmlspecialchars( $currency['country'] ); ?> (<?php echo $currency['currency']; ?>)
                            </option> <?php
                        } ?>
                    </select>
                    <?php _e( 'Price:', 'wpsc' ); ?> <input type='text' class='text' size='8' name='newCurrPrice[]' value='<?php echo $alt_price; ?>' style=' display:inline' />
                    <a href='' class='wpsc_delete_currency_layer' rel='<?php echo $iso; ?>'><img src='<?php echo WPSC_CORE_IMAGES_URL; ?>/cross.png' /></a></div>
                <?php }

            endif;

            echo "<br style='clear:both' />
          <br/><input id='add_form_donation' type='checkbox' name='meta[_wpsc_is_donation]' value='yes' " . ( isset($product_data['meta']['_wpsc_is_donation']) && ( $product_data['meta']['_wpsc_is_donation'] == 1 ) ? 'checked="checked"' : '' ) . " />&nbsp;<label for='add_form_donation'>" . __( 'This is a donation, checking this box populates the donations widget.', 'wpsc' ) . "</label>";
            ?>

        <br/><br/>


        <input id='cfp_if_call_for_price' type='checkbox'  name='meta[_wpsc_cfp_ready]' style="float:left; margin-right:5px;" value='yes' <?php echo $cfp_checked = ( isset($product_data['meta']['_wpsc_cfp_ready']) && ( $product_data['meta']['_wpsc_cfp_ready'] == 1 ) ? 'checked="checked"' : '' )  ?> />
        <label style="float:left; line-height: 13px;" for='cfp_if_call_for_price'><?php _e('Call for Price', 'wpec-call-for-price'); ?></label>&nbsp;
        <br/>
        <div class="cfp_custom_message" id="cfp_message" <?php echo $cfp_checked = ( isset($product_data['meta']['_wpsc_cfp_ready']) && ( $product_data['meta']['_wpsc_cfp_ready'] == 1 ) ? '' : 'style="display:none"' )  ?>>
            <label for="cfp_custom_message"><?php _e('Call for Price Message', 'wpec-call-for-price'); ?></label>
            <input type="text" size="32" id="cfp_custom_message" name='meta[_wpsc_cfp_message]' value="<?php echo $cfp_message = (isset($product_data['meta']['_wpsc_cfp_message']) ? $product_data['meta']['_wpsc_cfp_message'] : '' )  ?>" />
        </div>

        <br /><br /> <input type='checkbox' value='1' name='table_rate_price[state]' id='table_rate_price'  <?php echo ( ( isset($product_meta['table_rate_price']['state']) && (bool)$product_meta['table_rate_price']['state'] == true ) ? 'checked=\'checked\'' : '' ); ?> />
        <label for='table_rate_price'><?php _e( 'Table Rate Price', 'wpsc' ); ?></label>
        <div id='table_rate'>
            <a class='add_level' style='cursor:pointer;'><?php _e( '+ Add level', 'wpsc' ); ?></a><br />
            <br style='clear:both' />
            <table>
                <tr>
                    <th><?php _e( 'Quantity In Cart', 'wpsc' ); ?></th>
                    <th colspan='2'><?php _e( 'Discounted Price', 'wpsc' ); ?></th>
                </tr>
                <?php
                if ( count( $product_meta['table_rate_price']['quantity'] ) > 0 ) {
                    foreach ( (array)$product_meta['table_rate_price']['quantity'] as $key => $quantity ) {
                        if ( $quantity != '' ) {
                            $table_price = number_format( $product_meta['table_rate_price']['table_price'][$key], 2, '.', '' );
                            ?>
                            <tr>
                                <td>
                                    <input type="text" size="5" value="<?php echo $quantity; ?>" name="table_rate_price[quantity][]"/><span class='description'><?php _e( 'and above', 'wpsc' ); ?></span>
                                </td>
                                <td>
                                    <input type="text" size="10" value="<?php echo $table_price; ?>" name="table_rate_price[table_price][]" />
                                </td>
                                <td><img src="<?php echo WPSC_CORE_IMAGES_URL; ?>/cross.png" class="remove_line" /></td>
                            </tr>
                            <?php
                        }
                    }
                }
                ?>
                <tr>
                    <td><input type="text" size="5" value="" name="table_rate_price[quantity][]"/><span class='description'><?php _e( 'and above', 'wpsc' ); ?></span> </td>
                    <td><input type='text' size='10' value='' name='table_rate_price[table_price][]'/></td>
                </tr>
            </table>
        </div>
        <?php endif; ?>
    <?php
    }





   function show_wpec_missing_notice(){

       printf('<div class="error"><p>'.__("The WPEC Call for Price plugin will not work without WP e-Commerce plugin.", 'wpec-call-for-price').'</p></div>');

       printf('<div class="updated"><p>'.__('Please install <a href="http://wordpress.org/extend/plugins/wp-e-commerce/">WP e-Commerce plugin</a>', 'wpec-call-for-price').'</p></div>');

   }

   function load_front_end_js(){

       wp_enqueue_script('jquery');
       wp_enqueue_script('cfp_js', plugins_url('/js/cfp.js',__FILE__), array('jquery'), '', false );

   }

   function load_back_end_js(){

       wp_enqueue_style('thickbox');
       wp_enqueue_script('jquery');
       wp_enqueue_script('media-upload');
       wp_enqueue_script('thickbox');
       wp_enqueue_script('cfpadmin_js', plugins_url('/js/cfpadmin.js', __FILE__), array('jquery'), '', true );

   }

   function load_front_end_css(){

       wp_register_style( 'cfp-style', plugins_url('/css/cfp.css', __FILE__) );
       wp_enqueue_style( 'cfp-style' );

   }


   function hide_price(){

       global $post;

       $cfp = $this->cfp_wpsc_custom_meta($post->ID);

       if ($cfp['if_cfp'] == '1') {
       printf ('
            <script>

            jQuery.noConflict(); jQuery("#product_'.$post->ID.' .pricedisplay").remove();
            jQuery("#product_'.$post->ID.' .wpsc_buy_button").remove();
            jQuery("#product_'.$post->ID.' .wpsc_quantity_update").remove();
            jQuery("#product_'.$post->ID.' .qty_up").remove();
            jQuery("#stock_display_'.$post->ID.'").remove();

            </script>
       ');
       }
   }



   function show_cfp($post_id){

       global $wpdb, $post;

       $cfp = $this->cfp_wpsc_custom_meta($post->ID);

        if ($cfp['if_cfp'] == '1') {

            if ($cfp['cfp_message'] != '') $msg = htmlentities($cfp['cfp_message']); else $msg = htmlentities(get_option('cfp_default_message'));
            printf("<img src=".get_option('cfp_icon')." onclick=\"cfp_fancy_notification(this, '".$msg."' )\" class=\"cfp_icons\" alt= \"".__('Call for Price', 'wpec-call-for-price')." \">");

        }

   }



//---Modification of cfp_wpsc_custom_meta

    function cfp_wpsc_custom_meta($postid) {
        global $wpdb, $post;

        if (isset($postid)) $post_id = $postid; else $post_id = $post->ID;

        $results = $wpdb->get_results( $wpdb->prepare("SELECT meta_key, meta_value, meta_id, post_id
			FROM $wpdb->postmeta
			WHERE post_id = %d
			AND `meta_key` REGEXP '^_wpsc_cfp'
			ORDER BY meta_key,meta_id", $postid), ARRAY_A );
        if (isset($results[1]))
        return array('if_cfp' => $results[1]['meta_value'], 'cfp_message' => (isset($results[0]['meta_value']) ? $results[0]['meta_value']: ''));

    }


//---Modification of wpsc_admin_submit_product--------------------------------------------------------------------------

    function cfp_wpsc_admin_submit_product( $post_ID, $post ) {
        global $current_screen, $wpdb;

        if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || empty( $current_screen ) || $current_screen->id != 'wpsc-product' || $post->post_type != 'wpsc-product' || empty( $_POST['meta'] ) )
            return $post_ID;

        //Type-casting ( not so much sanitization, which would be good to do )
        $post_data = $_POST;
        $product_id = $post_ID;
        $post_data['additional_description'] = isset($post_data['additional_description']) ? $post_data['additional_description'] : '';
        $post_meta['meta'] = (array)$_POST['meta'];
        if ( isset( $post_data['meta']['_wpsc_price'] ) )
            $post_data['meta']['_wpsc_price'] = abs( (float) str_replace( ',', '', $post_data['meta']['_wpsc_price'] ) );
        if ( isset( $post_data['meta']['_wpsc_special_price'] ) )
            $post_data['meta']['_wpsc_special_price'] = abs((float)str_replace( ',','',$post_data['meta']['_wpsc_special_price'] ));
        if($post_data['meta']['_wpsc_sku'] == __('N/A', 'wpsc'))
            $post_data['meta']['_wpsc_sku'] = '';
        if( isset( $post_data['meta']['_wpsc_is_donation'] ) )
            $post_data['meta']['_wpsc_is_donation'] = 1;
        else
            $post_data['meta']['_wpsc_is_donation'] = 0;
        if ( ! isset( $post_data['meta']['_wpsc_limited_stock'] ) ){
            $post_data['meta']['_wpsc_stock'] = false;
        } else {
            $post_data['meta']['_wpsc_stock'] = isset( $post_data['meta']['_wpsc_stock'] ) ? (int) $post_data['meta']['_wpsc_stock'] : 0;
        }

        unset($post_data['meta']['_wpsc_limited_stock']);
        if(!isset($post_data['meta']['_wpsc_product_metadata']['unpublish_when_none_left'])) $post_data['meta']['_wpsc_product_metadata']['unpublish_when_none_left'] = '';
        if(!isset($post_data['quantity_limited'])) $post_data['quantity_limited'] = '';
        if(!isset($post_data['special'])) $post_data['special'] = '';
        if(!isset($post_data['meta']['_wpsc_product_metadata']['no_shipping'])) $post_data['meta']['_wpsc_product_metadata']['no_shipping'] = '';

        $post_data['meta']['_wpsc_product_metadata']['unpublish_when_none_left'] = (int)(bool)$post_data['meta']['_wpsc_product_metadata']['unpublish_when_none_left'];
        $post_data['meta']['_wpsc_product_metadata']['quantity_limited'] = (int)(bool)$post_data['quantity_limited'];
        $post_data['meta']['_wpsc_product_metadata']['special'] = (int)(bool)$post_data['special'];
        $post_data['meta']['_wpsc_product_metadata']['no_shipping'] = (int)(bool)$post_data['meta']['_wpsc_product_metadata']['no_shipping'];

        // Product Weight
        if(!isset($post_data['meta']['_wpsc_product_metadata']['display_weight_as'])) $post_data['meta']['_wpsc_product_metadata']['display_weight_as'] = '';
        if(!isset($post_data['meta']['_wpsc_product_metadata']['display_weight_as'])) $post_data['meta']['_wpsc_product_metadata']['display_weight_as'] = '';

        $weight = wpsc_convert_weight($post_data['meta']['_wpsc_product_metadata']['weight'], $post_data['meta']['_wpsc_product_metadata']['weight_unit'], "pound", true);
        $post_data['meta']['_wpsc_product_metadata']['weight'] = (float)$weight;
        $post_data['meta']['_wpsc_product_metadata']['display_weight_as'] = $post_data['meta']['_wpsc_product_metadata']['weight_unit'];

        // table rate price
        $post_data['meta']['_wpsc_product_metadata']['table_rate_price'] = isset( $post_data['table_rate_price'] ) ? $post_data['table_rate_price'] : array();

        // if table_rate_price is unticked, wipe the table rate prices
        if ( empty( $post_data['table_rate_price']['state'] ) ) {
            $post_data['meta']['_wpsc_product_metadata']['table_rate_price']['table_price'] = array();
            $post_data['meta']['_wpsc_product_metadata']['table_rate_price']['quantity'] = array();
        }

        if ( ! empty( $post_data['meta']['_wpsc_product_metadata']['table_rate_price']['table_price'] ) ) {
            foreach ( (array) $post_data['meta']['_wpsc_product_metadata']['table_rate_price']['table_price'] as $key => $value ){
                if(empty($value)){
                    unset($post_data['meta']['_wpsc_product_metadata']['table_rate_price']['table_price'][$key]);
                    unset($post_data['meta']['_wpsc_product_metadata']['table_rate_price']['quantity'][$key]);
                }
            }
        }


        $post_data['meta']['_wpsc_product_metadata']['shipping']['local'] = (float)$post_data['meta']['_wpsc_product_metadata']['shipping']['local'];
        $post_data['meta']['_wpsc_product_metadata']['shipping']['international'] = (float)$post_data['meta']['_wpsc_product_metadata']['shipping']['international'];


        // Advanced Options
        $post_data['meta']['_wpsc_product_metadata']['engraved'] = (int)(bool)$post_data['meta']['_wpsc_product_metadata']['engraved'];
        $post_data['meta']['_wpsc_product_metadata']['can_have_uploaded_image'] = (int)(bool)$post_data['meta']['_wpsc_product_metadata']['can_have_uploaded_image'];
        if(!isset($post_data['meta']['_wpsc_product_metadata']['google_prohibited'])) $post_data['meta']['_wpsc_product_metadata']['google_prohibited'] = '';
        $post_data['meta']['_wpsc_product_metadata']['google_prohibited'] = (int)(bool)$post_data['meta']['_wpsc_product_metadata']['google_prohibited'];

        $post_data['meta']['_wpsc_product_metadata']['enable_comments'] = $post_data['meta']['_wpsc_product_metadata']['enable_comments'];
        $post_data['meta']['_wpsc_product_metadata']['merchant_notes'] = $post_data['meta']['_wpsc_product_metadata']['merchant_notes'];

        $post_data['files'] = $_FILES;

        if(isset($post_data['post_title']) && $post_data['post_title'] != '') {

            $product_columns = array(
                'name' => '',
                'description' => '',
                'additional_description' => '',
                'price' => null,
                'weight' => null,
                'weight_unit' => '',
                'pnp' => null,
                'international_pnp' => null,
                'file' => null,
                'image' => '0',
                'quantity_limited' => '',
                'quantity' => null,
                'special' => null,
                'special_price' => null,
                'display_frontpage' => null,
                'notax' => null,
                'publish' => null,
                'active' => null,
                'donation' => null,
                'no_shipping' => null,
                'thumbnail_image' => null,
                'thumbnail_state' => null
            );

            foreach($product_columns as $column => $default)
            {
                if (!isset($post_data[$column])) $post_data[$column] = '';

                if($post_data[$column] !== null) {
                    $update_values[$column] = stripslashes($post_data[$column]);
                } else if(($update != true) && ($default !== null)) {
                    $update_values[$column] = stripslashes($default);
                }
            }
            // if we succeed, we can do further editing (todo - if_wp_error)

            // if we have no categories selected, assign one.
            if( isset( $post_data['tax_input']['wpsc_product_category'] ) && count( $post_data['tax_input']['wpsc_product_category'] ) == 1 && $post_data['tax_input']['wpsc_product_category'][0] == 0){
                $post_data['tax_input']['wpsc_product_category'][1] = wpsc_add_product_category_default($product_id);

            }

            if ( isset($post_data['meta']['_wpsc_cfp_ready']) ){

                $post_data['meta']['_wpsc_cfp_ready'] = 1;

            } else {

                $post_data['meta']['_wpsc_cfp_ready'] = 0;

            }

            update_post_meta($product_id, '_wpsc_cfp_ready', $post_data['meta']['_wpsc_cfp_ready']);

            if ( $post_data['meta']['_wpsc_cfp_ready'] ){

                update_post_meta($product_id, '_wpsc_cfp_message', $post_data['meta']['_wpsc_cfp_message']);

            } else {

                update_post_meta($product_id, '_wpsc_cfp_message', '');

            }


            // and the meta
            wpsc_update_product_meta($product_id, $post_data['meta']);

            // and the custom meta
            wpsc_update_custom_meta($product_id, $post_data);

            // sort out the variations
            wpsc_edit_product_variations( $product_id, $post_data );

            //and the alt currency
            if ( ! empty( $post_data['newCurrency'] ) ) {
                foreach( (array) $post_data['newCurrency'] as $key =>$value ){
                    wpsc_update_alt_product_currency( $product_id, $value, $post_data['newCurrPrice'][$key] );
                }
            }

            if($post_data['files']['file']['tmp_name'] != '') {
                wpsc_item_process_file($product_id, $post_data['files']['file']);
            } else {
                if (!isset($post_data['select_product_file'])) $post_data['select_product_file'] = null;
                wpsc_item_reassign_file($product_id, $post_data['select_product_file']);
            }

            if(isset($post_data['files']['preview_file']['tmp_name']) && ($post_data['files']['preview_file']['tmp_name'] != '')) {
                wpsc_item_add_preview_file($product_id, $post_data['files']['preview_file']);
            }
            do_action('wpsc_edit_product', $product_id);
            wpsc_ping();
        }
        return $product_id;
    }


    function overload_product_submit() {

        remove_action( 'save_post', 'wpsc_admin_submit_product', 10, 2 );
        add_action( 'save_post', array($this, 'cfp_wpsc_admin_submit_product'), 10, 2 );

    }

    function on_activate(){

        add_option( 'cfp_icon', WP_PLUGIN_URL."/".plugin_basename(__DIR__). "/media/cfp_1.png", '', 'no');
        add_option( 'cfp_default_message', '<em>Please call us at:</em><br/> <b> 1-800-12-12</b>', '', 'no');

    }


   function check_wpec_plugin(){

        if ( !class_exists('WP_eCommerce') ){

            deactivate_plugins(WPEC_CALL_FOR_PRICE_PLUGIN_PATH);

            add_action('admin_notices', array($this,'show_wpec_missing_notice'));

            return false;
        }

       return true;
   }

}


$cfp = new TICallForPrice();
register_activation_hook( __FILE__, array($cfp, 'on_activate') );

