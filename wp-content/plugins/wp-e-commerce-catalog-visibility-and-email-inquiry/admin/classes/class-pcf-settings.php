<?php
/**
 * WPEC Product Contact Form Settings Class
 *
 * Class Function into WP e-Commerce plugin
 *
 * Table Of Contents
 *
 * get_email_shortcodes()
 * set_settings_default()
 * is_submit_button_displayed()
 * is_update_message_displayed()
 * update_settings()
 * display()
 * wpec_pcf_extensions()
 */
class WPSC_Settings_Tab_Catalog_Visibility {
	
	public function get_email_shortcodes() {
		return $args_email_contactform = array(
			'visitor_name' => __('Visitor Name', 'wpec_pcf'),
			'visitor_email' =>  __('Visitor Email', 'wpec_pcf'),
			'visitor_phone' =>  __('Visitor Phone', 'wpec_pcf'),
			'product_url' =>  __('Product Url', 'wpec_pcf'),
			'product_name' =>  __('Product Name', 'wpec_pcf'),
			'visitor_message' =>  __('Visitor Message', 'wpec_pcf')
		);
	}
	
	public function set_settings_default($reset=false, $free_version=false) {
		if ( (esc_attr(get_option('wpec_pcf_hide_addcartbt')) == '' || $reset) && !$free_version ) {
			update_option('wpec_pcf_hide_addcartbt', 1);
		}
		if ( (esc_attr(get_option('wpec_pcf_show_button')) == '' || $reset) && !$free_version ) {
			update_option('wpec_pcf_show_button', 1);
		}
		if ( (esc_attr(get_option('wpec_pcf_user')) == '' || $reset) && !$free_version ) {
			update_option('wpec_pcf_user', 0);
		}
		if ( esc_attr(get_option('wpec_pcf_button_padding')) == '' || $reset ) {
			update_option('wpec_pcf_button_padding', 10);
		}
		if ( esc_attr(get_option('wpec_pcf_email_to')) == '' || $reset ) {
			update_option('wpec_pcf_email_to', '' );
		}
		if ( esc_attr(get_option('wpec_pcf_hyperlink_text')) == '' || $reset ) {
			update_option('wpec_pcf_hyperlink_text', __('Click Here', 'wpec_pcf') );
		}
		if ( esc_attr(get_option('wpec_pcf_button_title')) == '' || $reset ) {
			update_option('wpec_pcf_button_title', __('Product Enquiry', 'wpec_pcf') );
		}
		if ( esc_attr(get_option('wpec_pcf_contact_heading')) == '' || $reset ) {
			update_option('wpec_pcf_contact_heading', __('Product Enquiry', 'wpec_pcf') );
		}
		if ( esc_attr(get_option('wpec_pcf_contact_text_button')) == '' || $reset ) {
			update_option('wpec_pcf_contact_text_button', __('SEND', 'wpec_pcf') );
		}
		if ( esc_attr(get_option('wpec_pcf_button_bg_colour')) == '' || $reset ) {
			update_option('wpec_pcf_button_bg_colour', '#ee2b2b' );
		}
		if ( esc_attr(get_option('wpec_pcf_button_border_colour')) == '' || $reset ) {
			update_option('wpec_pcf_button_border_colour', '#ee2b2b' );
		}
		if ( esc_attr(get_option('wpec_pcf_contact_button_bg_colour')) == '' || $reset ) {
			update_option('wpec_pcf_contact_button_bg_colour', '#ee2b2b' );
		}
		if ( esc_attr(get_option('wpec_pcf_contact_button_border_colour')) == '' || $reset ) {
			update_option('wpec_pcf_contact_button_border_colour', '#ee2b2b' );
		}
		if ( esc_attr(get_option('wpec_pcf_contact_success')) == '' || $reset ) {
			update_option('wpec_pcf_contact_success', __("Thanks for your inquiry - we'll be in touch with you as soon as possible!", "wpec_pcf") );
		}
		if ( esc_attr(get_option('wpec_pcf_email_contactform')) == '' || $reset ) {
			update_option('wpec_pcf_email_contactform', __('Hello admin, \n\nYou have a request from [visitor_name] about [product_name] | [product_url] \nVisitor Email: [visitor_email] \nVisitor Phone: [visitor_phone] \nMessage: \n[visitor_message]', 'wpec_pcf') );	
		}
		
		if ( $reset ) {
			update_option('wpec_pcf_button_position', '');
			update_option('wpec_pcf_button_size', '');
			update_option('wpec_pcf_button_dark_text', '');
			update_option('wpec_pcf_contact_button_size', '');
			update_option('wpec_pcf_contact_button_dark_text', '');
			update_option('wpec_pcf_single_only', '');
		}
		
	}
	
	public function is_submit_button_displayed() {
		return true;
	}
	
	public function is_update_message_displayed() {
		if (isset($_REQUEST['wpsc-update-options'])) {
			$this->update_settings($_POST);
			$this->set_settings_default(true, true);
		}
		return true;
	}
	function update_settings($request){
		
		if( is_array($request) && count($request) > 0 ){
			unset($request['wpsc_admin_action']);
			unset($request['wpsc-update-options']);
			unset($request['_wp_http_referer']);
			unset($request['updateoption']);
			
			if(!isset($request['wpec_pcf_setting']['wpec_pcf_hide_addcartbt'])){
				update_option('wpec_pcf_hide_addcartbt', 0);
			}
			if(!isset($request['wpec_pcf_setting']['wpec_pcf_show_button'])){
				update_option('wpec_pcf_show_button', 0);
			}
			if(!isset($request['wpec_pcf_setting']['wpec_pcf_user'])){
				update_option('wpec_pcf_user', 0);
			}
			if(!isset($request['wpec_pcf_setting']['wpec_pcf_button'])){
				update_option('wpec_pcf_button', '');
			}
			if(!isset($request['wpec_pcf_setting']['wpec_pcf_single_only'])){
				update_option('wpec_pcf_single_only', '');
			}
			
			if (is_array($request) && isset($request['wpec_pcf_setting']) && count($request['wpec_pcf_setting']) > 0) {
				foreach ( $request['wpec_pcf_setting'] as $key => $value ) {
					if ( stripslashes($value) != get_option( $key ) ) {
						update_option( $key, stripslashes($value) );
					}
				}
			}
			
		}
	}
	public function display() {
		?>
        <style type="text/css">
		.form-table { margin:0; }
		input.colorpick{text-transform:uppercase;}
		#wpec_pcf_upgrade_area { border:2px solid #E6DB55;-webkit-border-radius:10px;-moz-border-radius:10px;-o-border-radius:10px; border-radius: 10px; padding:0 40% 0 0; position:relative; background:#FFFBCC;}
		#wpec_pcf_upgrade_inner { background:#FFF; -webkit-border-radius:10px 0 0 10px;-moz-border-radius:10px 0 0 10px;-o-border-radius:10px 0 0 10px; border-radius: 10px 0 0 10px;}
		#wpec_pcf_upgrade_inner h3{ margin-left:10px;}
	   	#wpec_pcf_extensions { -webkit-border-radius:4px;-moz-border-radius:4px;-o-border-radius:4px; border-radius: 4px 4px 4px 4px; color: #555555; float: right; margin: 0px; padding: 5px; position: absolute; text-shadow: 0 1px 0 rgba(255, 255, 255, 0.8); width: 38%; right:0; top:0px; }
        </style>
		<h3><?php _e('Catalog Page Settings', 'wpec_pcf'); ?></h3>
        <p><?php _e('Global settings apply to whole store. PRO version users can over-ride these on a per product basis.','wpec_pcf'); ?></p>
		<table cellspacing="0" class="form-table">
			<tbody>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_hide_addcartbt"><?php _e("Hide 'Add to Cart'",'wpec_pcf'); ?></label></th>
                    <td class="forminp"><input type="checkbox" name="wpec_pcf_setting[wpec_pcf_hide_addcartbt]" id="wpec_pcf_hide_addcartbt" value="1" <?php if (esc_attr( get_option('wpec_pcf_hide_addcartbt') ) == 1) echo 'checked="checked"'; ?> /> <?php _e('Yes', 'wpec_pcf'); ?>
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_show_button"><?php _e('Show Email Inquiry Button','wpec_pcf'); ?></label></th>
                    <td class="forminp">
                    <input type="checkbox" name="wpec_pcf_setting[wpec_pcf_show_button]" id="wpec_pcf_show_button" value="1" <?php if (esc_attr( get_option('wpec_pcf_show_button') ) == 1) echo 'checked="checked"'; ?> /> <?php _e('Yes', 'wpec_pcf'); ?>
                    </td>
               	</tr>
				<tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_user"><?php _e('Apply to Logged in Users','wpec_pcf'); ?></label></th>
                    <td class="forminp">
                    <input type="checkbox" name="wpec_pcf_setting[wpec_pcf_user]" id="wpec_pcf_user" value="1" <?php if (esc_attr( get_option('wpec_pcf_user') ) == 1) echo 'checked="checked"'; ?> /> <?php _e('Yes', 'wpec_pcf'); ?>
                    </td>
               	</tr>
			</tbody>
        </table>
        <div id="wpec_pcf_upgrade_area"><?php echo WPSC_Settings_Tab_Catalog_Visibility::wpec_pcf_extensions(); ?><div id="wpec_pcf_upgrade_inner">
        <table cellspacing="0" class="form-table">
        	<tbody>            
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for=""><?php _e('Button or Hyperlink Text','wpec_pcf'); ?></label></th>
                    <td class="forminp">
                    <input type="radio" disabled="disabled" name="wpec_pcf_setting[wpec_pcf_button_type]" id="wpec_pcf_button" value="" checked="checked" /> <label for="wpec_pcf_button"><?php _e('Button', 'wpec_pcf'); ?></label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" disabled="disabled" name="wpec_pcf_setting[wpec_pcf_button_type]" id="wpec_pcf_link" value="link" /> <label for="wpec_pcf_link"><?php _e('Link', 'wpec_pcf'); ?></label>
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_button_position"><?php _e('Relative Position','wpec_pcf'); ?></label></th>
                    <td class="forminp">
                    <select disabled="disabled" name="wpec_pcf_setting[wpec_pcf_button_position]" id="wpec_pcf_button_position" style="min-width:300px">
                    	<option value="" selected="selected"><?php _e('Below (Default)','wpec_pcf'); ?></option>
                        <option value="above"><?php _e('Above','wpec_pcf'); ?></option>
                    </select> <p class="description"><?php _e('Position relative to add to cart button location', 'wpec_pcf'); ?></p>
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_button_padding"><?php _e('Padding','wpec_pcf'); ?></label></th>
                    <td class="forminp"><input type="text" disabled="disabled" name="wpec_pcf_setting[wpec_pcf_button_padding]" id="wpec_pcf_button_padding" value="<?php esc_attr_e(get_option('wpec_pcf_button_padding') );?>" style="width:80px" /> px <span class="description"><?php _e('&lt;empty&gt; for default 10px padding from the add to cart button or enter a px value.', 'wpec_pcf'); ?></span>
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for=""><?php _e('Single Product Page only','wpec_pcf'); ?></label></th>
                    <td class="forminp"><input type="radio" disabled="disabled" name="wpec_pcf_setting[wpec_pcf_single_only]" id="wpec_pcf_single_only_yes" value="yes" /> <label for="wpec_pcf_single_only_yes"><?php _e('Yes', 'wpec_pcf'); ?></label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" disabled="disabled" name="wpec_pcf_setting[wpec_pcf_single_only]" id="wpec_pcf_single_only_no" value="" checked="checked" /> <label for="wpec_pcf_single_only_no"><?php _e('No', 'wpec_pcf'); ?></label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span class="description"><?php _e('Default =  No. Button / Link text shows on single products pages as well as products list view, grid view, category and tag pages.', 'wpec_pcf'); ?></span>
                    </td>
               	</tr>
			</tbody>
		</table>
        <h3><?php _e('Email Delivery', 'wpec_pcf'); ?></h3>
        <table cellspacing="0" class="form-table">
			<tbody>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_email_to"><?php _e('Inquiry Email goes to','wpec_pcf'); ?></label></th>
                    <td class="forminp"><input type="text" disabled="disabled" name="wpec_pcf_setting[wpec_pcf_email_to]" id="wpec_pcf_email_to" value="<?php if (esc_attr(get_option('purch_log_email') ) != '' ) esc_attr_e(get_option('purch_log_email')); else esc_attr_e(get_option('admin_email'));?>" style="min-width:300px" /> <p class="description"><?php _e('&lt;empty&gt; defaults to WPEC admin email address', 'wpec_pcf'); ?></p>
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_email_cc"><?php _e('CC','wpec_pcf'); ?></label></th>
                    <td class="forminp"><input type="text" disabled="disabled" name="wpec_pcf_setting[wpec_pcf_email_cc]" id="wpec_pcf_email_cc" value="" style="min-width:300px" /> <p class="description"><?php _e("&lt;empty&gt; defaults to 'no copy sent' or add an email address", 'wpec_pcf'); ?></p>
                    </td>
               	</tr>
			</tbody>
		</table>
        <h3><?php _e('Customize Hyperlink text', 'wpec_pcf'); ?></h3>
        <table cellspacing="0" class="form-table">
			<tbody>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_text_before"><?php _e('Text before','wpec_pcf'); ?></label></th>
                    <td class="forminp"><input type="text" disabled="disabled" name="wpec_pcf_setting[wpec_pcf_text_before]" id="wpec_pcf_text_before" value="" style="min-width:300px" /> <p class="description"><?php _e("&lt;empty&gt; for default 'no text' or add text to prepent link text", 'wpec_pcf'); ?></p>
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_hyperlink_text"><?php _e('Hyperlink text','wpec_pcf'); ?></label></th>
                    <td class="forminp"><input type="text" disabled="disabled" name="wpec_pcf_setting[wpec_pcf_hyperlink_text]" id="wpec_pcf_hyperlink_text" value="<?php esc_attr_e(get_option('wpec_pcf_hyperlink_text') );?>" style="min-width:300px" /> <p class="description"><?php _e("&lt;empty&gt; for default 'Click Here' or your own text", 'wpec_pcf'); ?></p>
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_trailing_text"><?php _e('Trailing text','wpec_pcf'); ?></label></th>
                    <td class="forminp"><input type="text" disabled="disabled" name="wpec_pcf_setting[wpec_pcf_trailing_text]" id="wpec_pcf_trailing_text" value="" style="min-width:300px" /> <p class="description"><?php _e("&lt;empty&gt; for default 'no text' or add text to trail linked text", 'wpec_pcf'); ?></p>
                    </td>
               	</tr>
			</tbody>
		</table>
        <h3><?php _e('Customize Email Inquiry Button', 'wpec_pcf'); ?></h3>
        <table cellspacing="0" class="form-table">
			<tbody>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_button_title"><?php _e('Title','wpec_pcf'); ?></label></th>
                    <td class="forminp"><input type="text" disabled="disabled" name="wpec_pcf_setting[wpec_pcf_button_title]" id="wpec_pcf_button_title" value="<?php esc_attr_e(get_option('wpec_pcf_button_title') );?>" style="min-width:300px" /> <p class="description"><?php _e("&lt;empty&gt; for default 'Product Enquiry' or enter text", 'wpec_pcf'); ?></p>
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_button_size"><?php _e('Size','wpec_pcf'); ?></label></th>
                    <td class="forminp">
                    <select disabled="disabled" name="wpec_pcf_setting[wpec_pcf_button_size]" id="wpec_pcf_button_size" style="min-width:300px">
                    	<option value="small" <?php if (esc_attr( get_option('wpec_pcf_button_size') ) == 'small') echo 'selected="selected"'; ?>><?php _e('small','wpec_pcf'); ?></option>
                        <option value="" <?php if (esc_attr( get_option('wpec_pcf_button_size') ) == '') echo 'selected="selected"'; ?>><?php _e('medium (Default)','wpec_pcf'); ?></option>
                        <option value="large" <?php if (esc_attr( get_option('wpec_pcf_button_size') ) == 'large') echo 'selected="selected"'; ?>><?php _e('large','wpec_pcf'); ?></option>
                        <option value="xl" <?php if (esc_attr( get_option('wpec_pcf_button_size') ) == 'xl') echo 'selected="selected"'; ?>><?php _e('xl','wpec_pcf'); ?></option>
                    </select>
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_button_bg_colour"><?php _e('Background Colour','wpec_pcf'); ?></label></th>
                    <td class="forminp">
                    	<input type="text" disabled="disabled" class="colorpick" value="<?php esc_attr_e(get_option('wpec_pcf_button_bg_colour') );?>" style="width:80px;" id="wpec_pcf_button_bg_colour" name="wpec_pcf_setting[wpec_pcf_button_bg_colour]" /> <span class="description"><?php _e('Button colour. Default #ee2b2b.', 'wpec_pcf');?></span>
            			<div id="colorPickerDiv_wpec_pcf_button_bg_colour" class="colorpickdiv" style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;"></div>
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_button_border_colour"><?php _e('Border','wpec_pcf'); ?></label></th>
                    <td class="forminp">
                    	<input type="text" disabled="disabled" class="colorpick" value="<?php esc_attr_e(get_option('wpec_pcf_button_border_colour') );?>" style="width:80px;" id="wpec_pcf_button_border_colour" name="wpec_pcf_setting[wpec_pcf_button_border_colour]" /> <span class="description"><?php _e('Border colour. Default #ee2b2b.', 'wpec_pcf');?></span>
            			<div id="colorPickerDiv_wpec_pcf_button_border_colour" class="colorpickdiv" style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;"></div>
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_button_dark_text"><?php _e('Dark Text?','wpec_pcf'); ?></label></th>
                    <td class="forminp">
                    <select disabled="disabled" name="wpec_pcf_setting[wpec_pcf_button_dark_text]" id="wpec_pcf_button_dark_text" style="min-width:300px">
                    	<option value="" selected="selected"><?php _e('light (Default)','wpec_pcf'); ?></option>
                        <option value="dark" <?php if (esc_attr( get_option('wpec_pcf_button_dark_text') ) == 'dark') echo 'selected="selected"'; ?>><?php _e('dark','wpec_pcf'); ?></option>
                    </select> <p class="description"><?php _e("Use 'dark' for light background colour buttons.", 'wpec_pcf'); ?></p>
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_button_class"><?php _e('CSS Class','wpec_pcf'); ?></label></th>
                    <td class="forminp"><input type="text" disabled="disabled" name="wpec_pcf_setting[wpec_pcf_button_class]" id="wpec_pcf_button_class" value="" style="min-width:300px" /> <p class="description"><?php _e("Enter your own button CSS class", 'wpec_pcf'); ?></p>
                    </td>
               	</tr>
			</tbody>
		</table>
        <h3><?php _e('Customize Email Pop-up', 'wpec_pcf'); ?></h3>
		<table cellspacing="0" class="form-table">
			<tbody>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_contact_heading"><?php _e('Header title','wpec_pcf'); ?></label></th>
                    <td class="forminp"><input type="text" disabled="disabled" name="wpec_pcf_setting[wpec_pcf_contact_heading]" id="wpec_pcf_contact_heading" value="<?php esc_attr_e(get_option('wpec_pcf_contact_heading') );?>" style="min-width:300px" /> <p class="description"><?php _e('&lt;empty&gt; and the form title will be the Button title', 'wpec_pcf'); ?></p>
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_contact_text_button"><?php _e('Send Button Title','wpec_pcf'); ?></label></th>
                    <td class="forminp"><input type="text" disabled="disabled" name="wpec_pcf_setting[wpec_pcf_contact_text_button]" id="wpec_pcf_contact_text_button" value="<?php esc_attr_e(get_option('wpec_pcf_contact_text_button') );?>" style="min-width:300px" /> <p class="description"><?php _e('&lt;empty&gt; for default SEND', 'wpec_pcf'); ?></p>
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_contact_button_size"><?php _e('Button Size','wpec_pcf'); ?></label></th>
                    <td class="forminp">
                    <select disabled="disabled" name="wpec_pcf_setting[wpec_pcf_contact_button_size]" id="wpec_pcf_contact_button_size" style="min-width:300px">
                    	<option value="small" <?php if (esc_attr( get_option('wpec_pcf_contact_button_size') ) == 'small') echo 'selected="selected"'; ?>><?php _e('small','wpec_pcf'); ?></option>
                        <option value="" <?php if (esc_attr( get_option('wpec_pcf_contact_button_size') ) == '') echo 'selected="selected"'; ?>><?php _e('medium (Default)','wpec_pcf'); ?></option>
                        <option value="large" <?php if (esc_attr( get_option('wpec_pcf_contact_button_size') ) == 'large') echo 'selected="selected"'; ?>><?php _e('large','wpec_pcf'); ?></option>
                        <option value="xl" <?php if (esc_attr( get_option('wpec_pcf_contact_button_size') ) == 'xl') echo 'selected="selected"'; ?>><?php _e('xl','wpec_pcf'); ?></option>
                    </select>
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_contact_button_bg_colour"><?php _e('Button Background Colour','wpec_pcf'); ?></label></th>
                    <td class="forminp">
                    	<input type="text" disabled="disabled" class="colorpick" value="<?php esc_attr_e(get_option('wpec_pcf_contact_button_bg_colour') );?>" style="width:80px;" id="wpec_pcf_contact_button_bg_colour" name="wpec_pcf_setting[wpec_pcf_contact_button_bg_colour]" /> <span class="description"><?php _e('Button colour. Default #ee2b2b.', 'wpec_pcf');?></span>
            			<div id="colorPickerDiv_wpec_pcf_contact_button_bg_colour" class="colorpickdiv" style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;"></div>
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_contact_button_border_colour"><?php _e('Button Border','wpec_pcf'); ?></label></th>
                    <td class="forminp">
                    	<input type="text" disabled="disabled" class="colorpick" value="<?php esc_attr_e(get_option('wpec_pcf_contact_button_border_colour') );?>" style="width:80px;" id="wpec_pcf_contact_button_border_colour" name="wpec_pcf_setting[wpec_pcf_contact_button_border_colour]" /> <span class="description"><?php _e('Border colour. Default #ee2b2b.', 'wpec_pcf');?></span>
            			<div id="colorPickerDiv_wpec_pcf_contact_button_border_colour" class="colorpickdiv" style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;"></div>
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_contact_button_dark_text"><?php _e('Dark Text?','wpec_pcf'); ?></label></th>
                    <td class="forminp">
                    <select disabled="disabled" name="wpec_pcf_setting[wpec_pcf_contact_button_dark_text]" id="wpec_pcf_contact_button_dark_text" style="min-width:300px;">
                    	<option value="" selected="selected"><?php _e('light (Default)','wpec_pcf'); ?></option>
                        <option value="dark" <?php if (esc_attr( get_option('wpec_pcf_contact_button_dark_text') ) == 'dark') echo 'selected="selected"'; ?>><?php _e('dark','wpec_pcf'); ?></option>
                    </select> <p class="description"><?php _e("Use 'dark' for light background colour buttons.", 'wpec_pcf'); ?></p>
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_contact_button_class"><?php _e('Button CSS Class','wpec_pcf'); ?></label></th>
                    <td class="forminp"><input type="text" disabled="disabled" name="wpec_pcf_setting[wpec_pcf_contact_button_class]" id="wpec_pcf_contact_button_class" value="" style="min-width:300px" /> <p class="description"><?php _e("Enter your own button CSS class", 'wpec_pcf'); ?></p>
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_contact_form_class"><?php _e('Form CSS Class','wpec_pcf'); ?></label></th>
                    <td class="forminp"><input type="text" disabled="disabled" name="wpec_pcf_setting[wpec_pcf_contact_form_class]" id="wpec_pcf_contact_form_class" value="" style="min-width:300px" /> <p class="description"><?php _e("&lt;empty&gt; for default or enter custom form CSS", 'wpec_pcf'); ?></p>
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_contact_success"><?php _e('Success Message','wpec_pcf'); ?></label></th>
                    <td class="forminp">
                    	<?php wp_editor(get_option('wpec_pcf_contact_success'), 'wpec_pcf_contact_success', array('textarea_name' => 'wpec_pcf_setting[wpec_pcf_contact_success]', 'wpautop' => true, 'textarea_rows' => 15) ); ?>
                        <!--<textarea style="min-width:300px;height:200px" name="wpec_pcf_setting['wpec_pcf_contact_success']" id="wpec_pcf_contact_success"><?php //esc_attr_e(get_option('wpec_pcf_contact_success') );?></textarea>-->
						<span class="description"><?php _e('Message that user sees on page after form is submitted', 'wpec_pcf'); ?></span>
                    </td>
               	</tr>
			</tbody>
		</table>
        </div>
        </div>
		<?php
	}
	
	function wpec_pcf_extensions() {
		$html = '';
		$html .= '<div id="wpec_pcf_extensions">';
		$html .= '<a href="http://a3rev.com/shop/" target="_blank" style="float:right;margin-top:5px; margin-left:10px;" ><img src="'.WPEC_PCF_IMAGES_URL.'/a3logo.png" /></a>';
		$html .= '<h3>'.__('Upgrade to Catalog Visibility Email inquiry Pro', 'wpec_pcf').'</h3>';
		$html .= '<p>'.__("Visit the", 'wpec_pcf').' <a href="'.WPEC_PCF_AUTHOR_URI.'" target="_blank">'.__("a3rev website", 'wpec_pcf').'</a> '.__("to see all the extra features the Pro version of this plugin offers in the yellow box below plus the awesome power to individually customize these Catalog visibility and email inquiry settings on any product.", 'wpec_pcf').':</p>';
		$html .= '<h3>'.__('Plugin Documentation', 'wpec_pcf').'</h3>';
		$html .= '<p>'.__('All of our plugins have comprehensive online documentation. Please refer to the plugins docs before raising a support request', 'wpec_pcf').'. <a href="http://docs.a3rev.com/user-guides/wp-e-commerce/wpec-catalog-visibility-and-email-inquiry/" target="_blank">'.__('Visit the a3rev wiki.', 'wpec_pcf').'</a></p>';
		$html .= '<h3>'.__('More a3rev Quality Plugins', 'wpec_pcf').'</h3>';
		$html .= '<p>'.__('Below is a list of the a3rev plugins that are available for free download from wordpress.org', 'wpec_pcf').'</p>';
		$html .= '<h3>'.__('WP e-Commerce Plugins', 'wpec_pcf').'</h3>';
		$html .= '<p>';
		$html .= '<ul style="padding-left:10px;">';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/wp-e-commerce-dynamic-gallery/" target="_blank">'.__('WP e-Commerce Dynamic Gallery', 'wpec_pcf').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/wp-e-commerce-predictive-search/" target="_blank">'.__('WP e-Commerce Predictive Search', 'wpec_pcf').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/wp-ecommerce-compare-products/" target="_blank">'.__('WP e-Commerce Compare Products', 'wpec_pcf').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/wp-e-commerce-catalog-visibility-and-email-inquiry/" target="_blank">'.__('WP e-Commerce Catalog Visibility & Email Inquiry', 'wpec_pcf').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/wp-e-commerce-grid-view/" target="_blank">'.__('WP e-Commerce Grid View', 'wpec_pcf').'</a></li>';
		$html .= '</ul>';
		$html .= '</p>';
		
		$html .= '<h3>'.__('WordPress Plugins', 'wpec_pcf').'</h3>';
		$html .= '<p>';
		$html .= '<ul style="padding-left:10px;">';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/wp-email-template/" target="_blank">'.__('WordPress Email Template', 'wpec_pcf').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/page-views-count/" target="_blank">'.__('Page View Count', 'wpec_pcf').'</a></li>';
		$html .= '</ul>';
		$html .= '</p>';
		
		$html .= '<h3>'.__('WooCommerce Plugins', 'wpec_pcf').'</h3>';
		$html .= '<p>';
		$html .= '<ul style="padding-left:10px;">';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/woocommerce-dynamic-gallery/" target="_blank">'.__('WooCommerce Dynamic Products Gallery', 'wpec_pcf').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/woocommerce-predictive-search/" target="_blank">'.__('WooCommerce Predictive Search', 'wpec_pcf').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/woocommerce-compare-products/" target="_blank">'.__('WooCommerce Compare Products', 'wpec_pcf').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/woo-widget-product-slideshow/" target="_blank">'.__('WooCommerce Widget Product Slideshow', 'wpec_pcf').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/woocommerce-email-inquiry-cart-options/" target="_blank">'.__('WooCommerce Email Inquiry & Cart Options', 'wpec_pcf').'</a></li>';
		$html .= '</ul>';
		$html .= '</p>';
		
		$html .= '<h3>'.__('Help spread the Word about this plugin', 'wpec_pcf').'</h3>';
		$html .= '<p>'.__("Things you can do to help others find this plugin", 'wpec_pcf');
		$html .= '<ul style="padding-left:10px;">';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/wp-e-commerce-catalog-visibility-and-email-inquiry/" target="_blank">'.__('Rate this plugin 5', 'wpec_pcf').' <img src="'.WPEC_PCF_IMAGES_URL.'/stars.png" align="top" /> '.__('on WordPress.org', 'wpec_pcf').'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/wp-e-commerce-catalog-visibility-and-email-inquiry/" target="_blank">'.__('Mark the plugin as a fourite', 'wpec_pcf').'</a></li>';
		$html .= '</ul>';
		$html .= '</p>';
		$html .= '</div>';
		return $html;
	}
	
}
?>