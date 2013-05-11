<?php
/**
 * WPEC PCF Hook Filter
 *
 * Table Of Contents
 *
 * add_wpsc_settings_tabs()
 * hide_addcartbt()
 * add_contact_button()
 * script_show_contact_button()
 * show_contact_button()
 * pcf_contact_popup()
 * pcf_contact_action()
 * add_style_header()
 * footer_print_scripts()
 * script_contact_popup()
 * admin_footer_scripts()
 * wp_admin_footer_scripts()
 * admin_warning_noemail()
 * plugin_extra_links()
 */
class WPEC_PCF_Hook_Filter{
	
	function add_wpsc_settings_tabs($tabs) {
		$tabs['catalog_visibility'] = __('Catalog Visibility', 'wpec_pcf');
		return $tabs;
	}
		
	function hide_addcartbt() {
		global $post;
		$product_id = $post->ID;
		
		$wpec_pcf_hide_addcartbt = esc_attr(get_option('wpec_pcf_hide_addcartbt'));
		
		$wpec_pcf_user = esc_attr(get_option('wpec_pcf_user'));
		
		if ($wpec_pcf_hide_addcartbt == 1 && ( !is_user_logged_in() || $wpec_pcf_user == 1 ) && $post->post_type == 'wpsc-product') {
		?>
        <style type="text/css">
			body input#product_<?php echo $product_id; ?>_submit_button, input#product_<?php echo $product_id; ?>_submit_button { display:none !important; visibility:hidden !important; height:0 !important;}
			body button#product_<?php echo $product_id; ?>_submit_button, button#product_<?php echo $product_id; ?>_submit_button { display:none !important; visibility:hidden !important; height:0 !important;}
			body .product_<?php echo $product_id; ?> .wpsc_buy_button, .product_<?php echo $product_id; ?> .wpsc_buy_button { display:none !important; visibility:hidden !important; height:0 !important;}
		</style>
        <?php
		}
	}
	
	function add_contact_button() {
		WPEC_PCF_Hook_Filter::show_contact_button(false);
	}
	
	function script_show_contact_button() {
		WPEC_PCF_Hook_Filter::show_contact_button(true);
	}
	
	function show_contact_button($use_script=true) {
		global $post;
		$product_id = $post->ID;
		$wpsc_pcf_custom = array();
		
		if (!isset($wpsc_pcf_custom['wpec_pcf_hide_addcartbt'])) $wpec_pcf_hide_addcartbt = esc_attr(get_option('wpec_pcf_hide_addcartbt'));
		else $wpec_pcf_hide_addcartbt = esc_attr($wpsc_pcf_custom['wpec_pcf_hide_addcartbt']);
		
		if (!isset($wpsc_pcf_custom['wpec_pcf_show_button'])) $wpec_pcf_show_button = esc_attr(get_option('wpec_pcf_show_button'));
		else $wpec_pcf_show_button = esc_attr($wpsc_pcf_custom['wpec_pcf_show_button']);
		
		if (!isset($wpsc_pcf_custom['wpec_pcf_single_only'])) $wpec_pcf_single_only = esc_attr(get_option('wpec_pcf_single_only'));
		else $wpec_pcf_single_only = esc_attr($wpsc_pcf_custom['wpec_pcf_single_only']);
		
		if (!isset($wpsc_pcf_custom['wpec_pcf_user'])) $wpec_pcf_user = esc_attr(get_option('wpec_pcf_user'));
		else $wpec_pcf_user = esc_attr($wpsc_pcf_custom['wpec_pcf_user']);
		
		if (!isset($wpsc_pcf_custom['wpec_pcf_button_type'])) $wpec_pcf_button_type = esc_attr(get_option('wpec_pcf_button_type'));
		else $wpec_pcf_button_type = esc_attr($wpsc_pcf_custom['wpec_pcf_button_type']);
		
		if (!isset($wpsc_pcf_custom['wpec_pcf_text_before'])  || trim(esc_attr($wpsc_pcf_custom['wpec_pcf_text_before'])) == '') $wpec_pcf_text_before = esc_attr(get_option('wpec_pcf_text_before'));
		else $wpec_pcf_text_before = esc_attr($wpsc_pcf_custom['wpec_pcf_text_before']);
		
		if (!isset($wpsc_pcf_custom['wpec_pcf_hyperlink_text'])  || trim(esc_attr($wpsc_pcf_custom['wpec_pcf_hyperlink_text'])) == '') $wpec_pcf_hyperlink_text = esc_attr(get_option('wpec_pcf_hyperlink_text'));
		else $wpec_pcf_hyperlink_text = esc_attr($wpsc_pcf_custom['wpec_pcf_hyperlink_text']);
		if (trim($wpec_pcf_hyperlink_text) == '') $wpec_pcf_hyperlink_text = 'Click Here';
		
		if (!isset($wpsc_pcf_custom['wpec_pcf_trailing_text'])  || trim(esc_attr($wpsc_pcf_custom['wpec_pcf_trailing_text'])) == '') $wpec_pcf_trailing_text = esc_attr(get_option('wpec_pcf_trailing_text'));
		else $wpec_pcf_trailing_text = esc_attr($wpsc_pcf_custom['wpec_pcf_trailing_text']);
		
		if (!isset($wpsc_pcf_custom['wpec_pcf_button_title'])  || trim(esc_attr($wpsc_pcf_custom['wpec_pcf_button_title'])) == '') $wpec_pcf_button_title = esc_attr(get_option('wpec_pcf_button_title'));
		else $wpec_pcf_button_title = esc_attr($wpsc_pcf_custom['wpec_pcf_button_title']);
		if (trim($wpec_pcf_button_title) == '') $wpec_pcf_button_title = __('Product Enquiry', 'wpec_pcf');
		
		$wpec_pcf_button_position = esc_attr(get_option('wpec_pcf_button_position'));
		$wpec_pcf_button_size = esc_attr(get_option('wpec_pcf_button_size'));
		$wpec_pcf_button_dark_text = esc_attr(get_option('wpec_pcf_button_dark_text'));
		
		$wpec_pcf_button_class = '';
		if ( trim(esc_attr(get_option('wpec_pcf_button_class'))) != '') $wpec_pcf_button_class = esc_attr(get_option('wpec_pcf_button_class'));
		
		$button_link = '';
		if (trim($wpec_pcf_text_before) != '') $button_link .= '<span class="pcf_text_before pcf_text_before_'.$product_id.'">'.trim($wpec_pcf_text_before).'</span> ';
		$button_link .= '<a class="pcf_hyperlink_text pcf_hyperlink_text_'.$product_id.' pcf_contact_buton" id="pcf_contact_button_'.$product_id.'" product_name="'.addslashes($post->post_title).'" product_id="'.$product_id.'">'.$wpec_pcf_hyperlink_text.'</a>';
		if (trim($wpec_pcf_trailing_text) != '') $button_link .= ' <span class="pcf_trailing_text pcf_trailing_text_'.$product_id.'">'.trim($wpec_pcf_trailing_text).'</span>';
		
		$button_button = '<a class="pcf_button_button pcf_email_button '.$wpec_pcf_button_dark_text.' '.$wpec_pcf_button_size.' pcf_button_'.$product_id.' pcf_contact_buton '.$wpec_pcf_button_class.'" id="pcf_contact_button_'.$product_id.'" product_name="'.addslashes($post->post_title).'" product_id="'.$product_id.'"><span>'.$wpec_pcf_button_title.'</span></a>';
		
		if ( (is_singular('wpsc-product') || $wpec_pcf_single_only != 'yes') && $wpec_pcf_show_button == 1 && ( !is_user_logged_in() || $wpec_pcf_user == 1 ) && $post->post_type == 'wpsc-product') {
			add_action('wp_footer', array('WPEC_PCF_Hook_Filter', 'footer_print_scripts') );
			$button_ouput = '<span class="pcf_button_container">';
			if ($wpec_pcf_button_type == 'link') $button_ouput .= $button_link;
			else $button_ouput .= $button_button;
			
			$button_ouput .= '</span>';
		?>
       		
        <?php
			if ($use_script) {
				if ($wpec_pcf_button_position == 'above') {
		?>
				<script type="text/javascript">
                    (function($){		
                        $(function(){
                            if($("#pcf_contact_button_<?php echo $product_id; ?>").length <= 0){
								var add_cart_float = '';
                                if($("input#product_<?php echo $product_id; ?>_submit_button").length > 0){
									add_cart_float = $("input#product_<?php echo $product_id; ?>_submit_button").css("float");
                                    $("input#product_<?php echo $product_id; ?>_submit_button").before('<?php echo $button_ouput; ?><br />');
                                }else if($("button#product_<?php echo $product_id; ?>_submit_button").length > 0){
									add_cart_float = $("button#product_<?php echo $product_id; ?>_submit_button").css("float");
                                    $("button#product_<?php echo $product_id; ?>_submit_button").before('<?php echo $button_ouput; ?><br />');
                                }else if($(".product_view_<?php echo $product_id; ?>").length > 0){
                                    $(".product_view_<?php echo $product_id; ?>").find(".more_details").before('<?php echo $button_ouput; ?><br />');
                                }else{
									add_cart_float = $("input.wpsc_buy_button").css("float");
                                    $("input.wpsc_buy_button").before('<?php echo $button_ouput; ?><br />');
                                }
								$("#pcf_contact_button_<?php echo $product_id; ?>").parent(".pcf_button_container").css("float", add_cart_float);
                            }
                        });		  
                    })(jQuery);
                </script>
        <?php		
				} else {
		?>
				<script type="text/javascript">
                    (function($){		
                        $(function(){
                            if($("#pcf_contact_button_<?php echo $product_id; ?>").length <= 0){
								var add_cart_float = '';
                                if($("input#product_<?php echo $product_id; ?>_submit_button").length > 0){
									add_cart_float = $("input#product_<?php echo $product_id; ?>_submit_button").css("float");
                                    $("input#product_<?php echo $product_id; ?>_submit_button").after('<br /><?php echo $button_ouput; ?>');
                                }else if($("button#product_<?php echo $product_id; ?>_submit_button").length > 0){
									add_cart_float = $("button#product_<?php echo $product_id; ?>_submit_button").css("float");
                                    $("button#product_<?php echo $product_id; ?>_submit_button").after('<br /><?php echo $button_ouput; ?>');
                                }else if($(".product_view_<?php echo $product_id; ?>").length > 0){
                                    $(".product_view_<?php echo $product_id; ?>").find(".more_details").after('<br /><?php echo $button_ouput; ?>');
                                }else{
									add_cart_float = $("input.wpsc_buy_button").css("float");
                                    $("input.wpsc_buy_button").after('<br /><?php echo $button_ouput; ?>');
                                }
								$("#pcf_contact_button_<?php echo $product_id; ?>").parent(".pcf_button_container").css("float", add_cart_float);
                            }
                        });		  
                    })(jQuery);
                </script>
        <?php
				}
				if ($wpec_pcf_hide_addcartbt == 1 && ( !is_user_logged_in() || $wpec_pcf_user == 1 ) && $post->post_type == 'wpsc-product') {
		?>
        		<script type="text/javascript">
                    (function($){		
                        $(function(){
							if($("input#product_<?php echo $product_id; ?>_submit_button").length > 0){
								$("input#product_<?php echo $product_id; ?>_submit_button").remove();
							} else if($("button#product_<?php echo $product_id; ?>_submit_button").length > 0){
								$("button#product_<?php echo $product_id; ?>_submit_button").remove();
							}
                        });		  
                    })(jQuery);
                </script>
        <?php		
				}
			} else {
				echo $button_ouput;
			}
		}
	}
	
	function pcf_contact_popup() {
		check_ajax_referer( 'pcf_contact_popup', 'security' );
		
		$pcf_contact_action = wp_create_nonce("pcf_contact_action");
		$product_id = $_REQUEST['product_id'];
		$product_name = get_the_title($product_id);
		
		$wpsc_pcf_custom = get_post_meta( $product_id, '_wpsc_pcf_custom', true);
		
		if (!isset($wpsc_pcf_custom['wpec_pcf_button_title'])  || trim(esc_attr($wpsc_pcf_custom['wpec_pcf_button_title'])) == '') $wpec_pcf_button_title = esc_attr(get_option('wpec_pcf_button_title'));
		else $wpec_pcf_button_title = esc_attr($wpsc_pcf_custom['wpec_pcf_button_title']);
		if (trim($wpec_pcf_button_title) == '') $wpec_pcf_button_title = __('Product Enquiry', 'wpec_pcf');
		
		if ( trim(esc_attr(get_option('wpec_pcf_contact_heading'))) != '') $wpec_pcf_contact_heading = esc_attr(get_option('wpec_pcf_contact_heading'));
		else $wpec_pcf_contact_heading = $wpec_pcf_button_title;
		
		if ( trim(esc_attr(get_option('wpec_pcf_contact_text_button'))) != '') $wpec_pcf_contact_text_button = esc_attr(get_option('wpec_pcf_contact_text_button'));
		else $wpec_pcf_contact_text_button = __('SEND', 'wpec_pcf');
		
		$wpec_pcf_contact_button_size = esc_attr(get_option('wpec_pcf_contact_button_size'));
		$wpec_pcf_contact_button_dark_text = esc_attr(get_option('wpec_pcf_contact_button_dark_text'));
		
		$wpec_pcf_contact_button_class = '';
		$wpec_pcf_contact_form_class = '';
		if ( trim(esc_attr(get_option('wpec_pcf_contact_button_class'))) != '') $wpec_pcf_contact_button_class = esc_attr(get_option('wpec_pcf_contact_button_class'));
		if ( trim(esc_attr(get_option('wpec_pcf_contact_form_class'))) != '') $wpec_pcf_contact_form_class = esc_attr(get_option('wpec_pcf_contact_form_class'));
		
	?>	
<div class="pcf_contact_form <?php echo $wpec_pcf_contact_form_class; ?>">
	<h1 class="pcf_result_heading"><?php echo $wpec_pcf_contact_heading; ?></h1>
	<div class="pcf_contact_content" id="pcf_contact_content_<?php echo $product_id; ?>">
		<div class="pcf_contact_field">
        	<label class="pcf_contact_label" for="your_name_<?php echo $product_id; ?>"><?php _e('Name','wpec_pcf'); ?> <span class="pcf_required">*</span></label> 
			<input type="text" class="your_name" name="your_name" id="your_name_<?php echo $product_id; ?>" value="" /></div>
		<div class="pcf_contact_field">
        	<label class="pcf_contact_label" for="your_email_<?php echo $product_id; ?>"><?php _e('Email Address','wpec_pcf'); ?> <span class="pcf_required">*</span></label>
			<input type="text" class="your_email" name="your_email" id="your_email_<?php echo $product_id; ?>" value="" /></div>
		<div class="pcf_contact_field">
        	<label class="pcf_contact_label" for="your_phone_<?php echo $product_id; ?>"><?php _e('Phone','wpec_pcf'); ?> <span class="pcf_required">*</span></label> 
			<input type="text" class="your_phone" name="your_phone" id="your_phone_<?php echo $product_id; ?>" value="" /></div>
		<div class="pcf_contact_field">
        	<label class="pcf_contact_label"><?php _e('Subject','wpec_pcf'); ?> </label> 
			<?php echo $product_name; ?></div>
		<div class="pcf_contact_field">
        	<label class="pcf_contact_label" for="your_message_<?php echo $product_id; ?>"><?php _e('Message','wpec_pcf'); ?></label> 
			<textarea class="your_message" name="your_message" id="your_message_<?php echo $product_id; ?>"></textarea></div>
		<a class="pcf_button_button pcf_form_button <?php echo $wpec_pcf_contact_button_dark_text; ?> <?php echo $wpec_pcf_contact_button_size; ?> pcf_contact_bt_<?php echo $product_id; ?> <?php echo $wpec_pcf_contact_button_class; ?>" id="pcf_contact_bt_<?php echo $product_id; ?>" product_id="<?php echo $product_id; ?>"><span><?php echo $wpec_pcf_contact_text_button; ?></span></a> <span class="pcf_contact_loading" id="pcf_contact_loading_<?php echo $product_id; ?>"><img src="<?php echo WPEC_PCF_IMAGES_URL; ?>/ajax-loader.gif" /></span>
	</div>
</div>
	<?php		
		die();
	}
	
	function pcf_contact_action() {
		check_ajax_referer( 'pcf_contact_action', 'security' );
		$product_id 	= $_REQUEST['product_id'];
		$your_name 		= $_REQUEST['your_name'];
		$your_email 	= $_REQUEST['your_email'];
		$your_phone 	= $_REQUEST['your_phone'];
		$your_message 	= $_REQUEST['your_message'];
		
		$email_result = WPEC_PCF_Functions::email_inquiry($product_id, $your_name, $your_email, $your_phone, $your_message);
		echo json_encode($email_result );
		die();
	}
		
	function add_style_header() {
		wp_enqueue_style('a3_pcf_style', WPEC_PCF_CSS_URL . '/pcf_style.css');
	}
	
	function footer_print_scripts() {
		$wpec_pcf_button_padding = 10;
		if ( trim(esc_attr(get_option('wpec_pcf_button_padding'))) != '') $wpec_pcf_button_padding = intval(esc_attr(get_option('wpec_pcf_button_padding')));
		
		$wpec_pcf_button_bg_colour = '';		
		if ( trim(esc_attr(get_option('wpec_pcf_button_bg_colour'))) != '') $wpec_pcf_button_bg_colour = esc_attr(get_option('wpec_pcf_button_bg_colour'));
		
		$wpec_pcf_button_border_colour = '';
		if ( trim(esc_attr(get_option('wpec_pcf_button_border_colour'))) != '') $wpec_pcf_button_border_colour = esc_attr(get_option('wpec_pcf_button_border_colour'));
		
		$wpec_pcf_contact_button_bg_colour = '';
		if ( trim(esc_attr(get_option('wpec_pcf_contact_button_bg_colour'))) != '') $wpec_pcf_contact_button_bg_colour = esc_attr(get_option('wpec_pcf_contact_button_bg_colour'));
		
		$wpec_pcf_contact_button_border_colour = '';
		if ( trim(esc_attr(get_option('wpec_pcf_contact_button_border_colour'))) != '') $wpec_pcf_contact_button_border_colour = esc_attr(get_option('wpec_pcf_contact_button_border_colour'));
	?>
    	<style type="text/css">
		h1.pcf_result_heading {
			<?php if (trim(str_replace('#', '', $wpec_pcf_contact_button_bg_colour)) != '') { ?>
			color: <?php echo $wpec_pcf_contact_button_bg_colour; ?> !important;
			<?php } ?>
		}
		.pcf_button_container { padding-top:<?php echo $wpec_pcf_button_padding ?>px !important; padding-bottom:<?php echo $wpec_pcf_button_padding ?>px !important; }
		body a.pcf_email_button, body a.pcf_email_button:hover, body a.pcf_email_button.hover, body a.pcf_email_button.active {
			<?php if (trim(str_replace('#', '', $wpec_pcf_button_bg_colour)) != '') { ?>
			background: <?php echo $wpec_pcf_button_bg_colour; ?>  !important;
			<?php } ?>
			<?php if (trim(str_replace('#', '', $wpec_pcf_button_border_colour)) != '') { ?>
			border-color: <?php echo $wpec_pcf_button_border_colour; ?>  !important;
			<?php } ?>
		}
		<?php if (trim(str_replace('#', '', $wpec_pcf_button_bg_colour)) != '' || trim(str_replace('#', '', $wpec_pcf_button_border_colour)) != '') { ?>
		body a.pcf_email_button:hover {
			opacity:0.85;	
		}
		<?php } ?>
		body a.pcf_form_button, body a.pcf_form_button:hover, body a.pcf_form_button.hover, body a.pcf_form_button.active {
			<?php if (trim(str_replace('#', '', $wpec_pcf_contact_button_bg_colour)) != '') { ?>
			background: <?php echo $wpec_pcf_contact_button_bg_colour; ?> !important;
			<?php } ?>
			<?php if (trim(str_replace('#', '', $wpec_pcf_contact_button_border_colour)) != '') { ?>
			border-color: <?php echo $wpec_pcf_contact_button_border_colour; ?>  !important;
			<?php } ?>
		}
		<?php if (trim(str_replace('#', '', $wpec_pcf_contact_button_bg_colour)) != '' || trim(str_replace('#', '', $wpec_pcf_contact_button_border_colour)) != '') { ?>
		body a.pcf_form_button:hover {
			opacity:0.85;	
		}
		<?php } ?>
		</style>
    <?php
		wp_enqueue_style( 'woocommerce_fancybox_styles', WPEC_PCF_JS_URL . '/fancybox/fancybox.css' );
		wp_enqueue_script('jquery');
		wp_enqueue_script( 'fancybox', WPEC_PCF_JS_URL . '/fancybox/fancybox.min.js');
	}
	
	function script_contact_popup() {
		$pcf_contact_popup = wp_create_nonce("pcf_contact_popup");
		$pcf_contact_action = wp_create_nonce("pcf_contact_action");
	?>
<script type="text/javascript">
(function($){
	$(function(){
		var ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";
		$(".pcf_contact_buton").live("click", function(){
			var product_id = $(this).attr("product_id");
			var product_name = $(this).attr("product_name");
			$.fancybox({
				href: ajax_url+"?action=pcf_contact_popup&product_id="+product_id+"&security=<?php echo $pcf_contact_popup; ?>",
				maxWidth: 600,
				maxHeight: 400,
				openEffect	: "none",
				closeEffect	: "none"
			});
		});
		
		$(".pcf_form_button").live("click", function(){
			var product_id = $(this).attr("product_id");
			var your_name = $("#your_name_"+product_id).val();
			var your_email = $("#your_email_"+product_id).val();
			var your_phone = $("#your_phone_"+product_id).val();
			var your_message = $("#your_message_"+product_id).val();
			var pcf_contact_error = "";
			var pcf_have_error = false;
			var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			
			if (your_name == "") {
				pcf_contact_error += "<?php _e('Please enter your Name', 'wpec_pcf'); ?>\n";
				pcf_have_error = true;
			}
			if (your_email == "" || !filter.test(your_email)) {
				pcf_contact_error += "<?php _e('Please enter valid Email address', 'wpec_pcf'); ?>\n";
				pcf_have_error = true;
			}
			if (your_phone == "") {
				pcf_contact_error += "<?php _e('Please enter your Phone', 'wpec_pcf'); ?>\n";
				pcf_have_error = true;
			}
			if (pcf_have_error) {
				alert(pcf_contact_error);
				return false;
			}
			$(this).attr("disabled", "disabled");
			$("#pcf_contact_loading_"+product_id).show();
			
			var data = {
				action: 		"pcf_contact_action",
				product_id: 	product_id,
				your_name: 		your_name,
				your_email: 	your_email,
				your_phone: 	your_phone,
				your_message: 	your_message,
				security: 		"<?php echo $pcf_contact_action; ?>"
			};
			$.post( ajax_url, data, function(response) {
				pcf_response = $.parseJSON( response );
				$("#pcf_contact_loading_"+product_id).hide();
				$("#pcf_contact_content_"+product_id).html(pcf_response);
			});
		});
	});
})(jQuery);
</script>
    <?php
	}
	
	function admin_footer_scripts() {
		wp_enqueue_script('jquery');
		wp_enqueue_script('farbtastic');
		wp_enqueue_style('farbtastic');
	?>
    <script type="text/javascript">
		(function($){		
			$(function(){	
				// Color picker
				$('.colorpick').each(function(){
					$('.colorpickdiv', $(this).parent()).farbtastic(this);
					$(this).live('click',function() {
						if ( $(this).val() == "" ) $(this).val('#ee2b2b');
						$('.colorpickdiv', $(this).parent() ).show();
					});	
				});
				$(document).mousedown(function(){
					$('.colorpickdiv').hide();
				});
			});		  
		})(jQuery);
	</script>
    <?php
	}
	
	function wp_admin_footer_scripts() {
	?>
    <script type="text/javascript">
		(function($){		
			$(function(){	
				$("a.nav-tab").click(function(){
					if($(this).attr('data-tab-id') == 'catalog_visibility'){
						window.location.href=$(this).attr('href');
						return false;
					}
				});
			});		  
		})(jQuery);
	</script>
    <?php
	}
	
	function admin_warning_noemail() {
		$to_email = get_option('purch_log_email');
		if (trim($to_email) == '') {
			session_start();
			if (isset($_GET['hide-wpec-pcf-notice'])) $_SESSION['hide-wpec-pcf-notice'] = 1 ;
			if (!isset($_SESSION['hide-wpec-pcf-notice'])) {
				$html = '<style>#wpec_pcf_notice {text-shadow: 0 1px 0 rgba(255, 255, 255, 0.8); padding:8px 25px 8px 6px;position:relative;}#wpec_pcf_notice a.hide{color:#FF0808;float:right;text-decoration:none;position:absolute;top:0;right:0;line-height:24px;padding:2px 8px;font-size:20px;text-align:center}</style>';
			
				$html .= '<div class="updated"><div id="wpec_pcf_notice"><a href="http://a3rev.com/shop/" target="_blank" style="float:left; margin-right:10px;"><img src="'.WPEC_PCF_IMAGES_URL.'/logo_a3blue.png" /></a>'.__("<strong>ATTENTION:</strong> WP e-Commerce Catalog Visibility and Email Inquiry could not find a WP e-Commerce Store Admin Email address and is using your sites WordPress Admin email address as the send to address. You can change this by going to your Settings > Store > Admin and enter a 'Store Admin Email:' address and Save Changes", 'wpec_pcf').' <a class="hide" href="'.add_query_arg('hide-wpec-pcf-notice', 'true').'">&times;</a></div></div>';
				echo $html;	
			}
		}
	}
	
	function plugin_extra_links($links, $plugin_name) {
		if ( $plugin_name != WPEC_PCF_NAME) {
			return $links;
		}
		$links[] = '<a href="http://docs.a3rev.com/user-guides/wp-e-commerce/wpec-catalog-visibility-and-email-inquiry/" target="_blank">'.__('Documentation', 'wpec_pcf').'</a>';
		$links[] = '<a href="http://a3rev.com/shop/catalog-visibilty-and-email-inquiry/#help_tab" target="_blank">'.__('Support', 'wpec_pcf').'</a>';
		return $links;
	}
}
?>
