<?php
/**
 * WPEC PCF MetaBox
 *
 * Table Of Contents
 *
 * add_meta_boxes()
 * the_meta_forms()
 * save_meta_boxes()
 * wpec_pcf_meta_extensions()
 */
class WPEC_PCF_MetaBox{
	
	function add_meta_boxes(){
		global $post;
		$pagename = 'wpsc-product';
		add_meta_box( 'wpsc_product_find_seller_forms', __('Catalog Visibility and Email Inquiry', 'wpec_pcf'), array('WPEC_PCF_MetaBox', 'the_meta_forms'), $pagename, 'normal', 'high' );
	}
	
	function the_meta_forms() {
		global $post;
		
		$wpec_pcf_hide_addcartbt = esc_attr(get_option('wpec_pcf_hide_addcartbt'));
		
		$wpec_pcf_show_button = esc_attr(get_option('wpec_pcf_show_button'));
		
		$wpec_pcf_user = esc_attr(get_option('wpec_pcf_user'));
		
		$wpec_pcf_email_to = get_option('purch_log_email');
		if (trim($wpec_pcf_email_to) == '') $wpec_pcf_email_to = get_option('admin_email');
		
		$wpec_pcf_email_cc = esc_attr(get_option('wpec_pcf_email_cc'));
		
		$wpec_pcf_button_type = esc_attr(get_option('wpec_pcf_button_type'));
		
		$wpec_pcf_text_before = esc_attr(get_option('wpec_pcf_text_before'));
		
		$wpec_pcf_hyperlink_text = esc_attr(get_option('wpec_pcf_hyperlink_text'));
		
		$wpec_pcf_trailing_text = esc_attr(get_option('wpec_pcf_trailing_text'));
		
		$wpec_pcf_button_title = esc_attr(get_option('wpec_pcf_button_title'));
		
		
		$wpec_pcf_single_only = esc_attr(get_option('wpec_pcf_single_only'));
		
		?>
        <style type="text/css">
	   	#wpec_pcf_meta_extensions { background: #FFFBCC; -webkit-border-radius:4px;-moz-border-radius:4px;-o-border-radius:4px; border-radius: 4px 4px 4px 4px; color: #555555; float: right; margin: 0px; padding: 4px 8px 4px 8px; position: absolute; text-shadow: 0 1px 0 rgba(255, 255, 255, 0.8); width: 420px; right:15px; top:10px; border:1px solid #E6DB55}
        </style>
        <?php echo WPEC_PCF_MetaBox::wpec_pcf_meta_extensions(); ?>
        <table cellspacing="0" class="form-table">
			<tbody>
            	<tr valign="top">
                    <th class="titledesc" scope="rpw" colspan="2"><?php _e('Customize setting for this product', 'wpec_pcf'); ?></th>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_hide_addcartbt"><?php _e("Hide 'Add to Cart'",'wpec_pcf'); ?></label></th>
                    <td class="forminp"><input type="checkbox" disabled="disabled" name="_wpsc_pcf_custom[wpec_pcf_hide_addcartbt]" id="wpec_pcf_hide_addcartbt" value="1" <?php if ($wpec_pcf_hide_addcartbt == 1) echo 'checked="checked"'; ?> /> <?php _e('Yes', 'wpec_pcf'); ?>
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_show_button"><?php _e('Show Email Inquiry Button','wpec_pcf'); ?></label></th>
                    <td class="forminp">
                    <input type="checkbox" disabled="disabled" name="_wpsc_pcf_custom[wpec_pcf_show_button]" id="wpec_pcf_show_button" value="1" <?php if ($wpec_pcf_show_button == 1) echo 'checked="checked"'; ?> /> <?php _e('Yes', 'wpec_pcf'); ?>
                    </td>
               	</tr>
				<tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_user"><?php _e('Apply to Logged in Users','wpec_pcf'); ?></label></th>
                    <td class="forminp">
                    <input type="checkbox" disabled="disabled" name="_wpsc_pcf_custom[wpec_pcf_user]" id="wpec_pcf_user" value="1" <?php if ($wpec_pcf_user == 1) echo 'checked="checked"'; ?> /> <?php _e('Yes', 'wpec_pcf'); ?>
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_email_to"><?php _e('Inquiry Email goes to','wpec_pcf'); ?></label></th>
                    <td class="forminp"><input type="text" disabled="disabled" name="_wpsc_pcf_custom[wpec_pcf_email_to]" id="wpec_pcf_email_to" value="<?php echo $wpec_pcf_email_to;?>" style="min-width:300px" /> 
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_email_cc"><?php _e('Copy to','wpec_pcf'); ?></label></th>
                    <td class="forminp"><input type="text" disabled="disabled" name="_wpsc_pcf_custom[wpec_pcf_email_cc]" id="wpec_pcf_email_cc" value="" style="min-width:300px" /> 
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for=""><?php _e('Button or Hyperlink Text','wpec_pcf'); ?></label></th>
                    <td class="forminp">
                    <input type="radio" disabled="disabled" name="_wpsc_pcf_custom[wpec_pcf_button_type]" id="wpec_pcf_button" class="wpec_pcf_button_type" value="" checked="checked" /> <label for="wpec_pcf_button"><?php _e('Button', 'wpec_pcf'); ?></label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" disabled="disabled" name="_wpsc_pcf_custom[wpec_pcf_button_type]" id="wpec_pcf_link" class="wpec_pcf_button_type" value="link" <?php if ($wpec_pcf_button_type == 'link') echo 'checked="checked"'; ?> /> <label for="wpec_pcf_link"><?php _e('Link', 'wpec_pcf'); ?></label>
                    </td>
               	</tr>
                <tr valign="top" class="button_type_link" style=" <?php if($wpec_pcf_button_type != 'link') { echo 'display:none'; } ?>">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_text_before"><?php _e('Text before','wpec_pcf'); ?></label></th>
                    <td class="forminp"><input type="text" disabled="disabled" name="_wpsc_pcf_custom[wpec_pcf_text_before]" id="wpec_pcf_text_before" value="<?php echo $wpec_pcf_text_before;?>" style="min-width:300px" /> 
                    </td>
               	</tr>
                <tr valign="top" class="button_type_link" style=" <?php if($wpec_pcf_button_type != 'link') { echo 'display:none'; } ?>">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_hyperlink_text"><?php _e('Hyperlink text','wpec_pcf'); ?></label></th>
                    <td class="forminp"><input type="text" disabled="disabled" name="_wpsc_pcf_custom[wpec_pcf_hyperlink_text]" id="wpec_pcf_hyperlink_text" value="<?php echo $wpec_pcf_hyperlink_text;?>" style="min-width:300px" /> 
                    </td>
               	</tr>
                <tr valign="top" class="button_type_link" style=" <?php if($wpec_pcf_button_type != 'link') { echo 'display:none'; } ?>">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_trailing_text"><?php _e('Trailing text','wpec_pcf'); ?></label></th>
                    <td class="forminp"><input type="text" disabled="disabled" name="_wpsc_pcf_custom[wpec_pcf_trailing_text]" id="wpec_pcf_trailing_text" value="<?php echo $wpec_pcf_trailing_text;?>" style="min-width:300px" /> 
                    </td>
               	</tr>
                <tr valign="top" class="button_type_button" style=" <?php if($wpec_pcf_button_type == 'link') { echo 'display:none'; } ?>">
                    <th class="titledesc" scope="rpw"><label for="wpec_pcf_button_title"><?php _e('Button Title','wpec_pcf'); ?></label></th>
                    <td class="forminp"><input type="text" disabled="disabled" name="_wpsc_pcf_custom[wpec_pcf_button_title]" id="wpec_pcf_button_title" value="<?php echo $wpec_pcf_button_title;?>" style="min-width:300px" /> 
                    </td>
               	</tr>
                <tr valign="top">
                    <th class="titledesc" scope="rpw"><label for=""><?php _e('Single Product Page only','wpec_pcf'); ?></label></th>
                    <td class="forminp"><input type="radio" disabled="disabled" name="_wpsc_pcf_custom[wpec_pcf_single_only]" id="wpec_pcf_single_only_yes" value="yes" <?php if ($wpec_pcf_single_only == 'yes') echo 'checked="checked"'; ?> /> <label for="wpec_pcf_single_only_yes"><?php _e('Yes', 'wpec_pcf'); ?></label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" disabled="disabled" name="_wpsc_pcf_custom[wpec_pcf_single_only]" id="wpec_pcf_single_only_no" value="" <?php if ($wpec_pcf_single_only != 'yes' ) echo 'checked="checked"'; ?> /> <label for="wpec_pcf_single_only_no"><?php _e('No', 'wpec_pcf'); ?></label>
                    </td>
               	</tr>
			</tbody>
		</table>
        <script type="text/javascript">
			(function($){		
				$(function(){	
					$('.wpec_pcf_button_type').click(function(){
						if ($("input[name='_wpsc_pcf_custom[wpec_pcf_button_type]']:checked").val() == '') {
							$(".button_type_button").show();
							$(".button_type_link").hide();
						} else {
							$(".button_type_link").show();
							$(".button_type_button").hide();
						}
					});
				});		  
			})(jQuery);
		</script>
		<?php
	}
	
	function save_meta_boxes($post_id){
		
	}
	
	function wpec_pcf_meta_extensions() {
		$html = '';
		$html .= '<div id="wpec_pcf_meta_extensions"><a href="http://a3rev.com/shop/" target="_blank" style="float:left; margin-right:10px;"><img src="'.WPEC_PCF_IMAGES_URL.'/logo_a3blue.png" /></a>'.__('Upgrade to the', 'wpec_pcf').' <a target="_blank" href="'.WPEC_PCF_AUTHOR_URI.'">'.__('Pro Version', 'wpec_pcf').'</a> '.__('of WP e-Commerce Catalog Visibility and Email Inquiry to unleash the awesome feature of being able to customize these setting to suite this product including who the email inquiry should go to.', 'wpec_pcf').'</div>';
		return $html;	
	}
}
?>