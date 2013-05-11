<?php
/**
 * WPEC PCF Functions
 *
 * Table Of Contents
 *
 * email_inquiry()
 */
class WPEC_PCF_Functions{	
	
	function email_inquiry($product_id, $your_name, $your_email, $your_phone, $your_message) {
		
		$wpec_pcf_show_button = esc_attr(get_option('wpec_pcf_show_button'));
		
		$wpec_pcf_user = esc_attr(get_option('wpec_pcf_user'));
		
		if ($wpec_pcf_show_button == 1 && ( !is_user_logged_in() || $wpec_pcf_user == 1 ) ) {
			
			if ( trim(esc_attr(get_option('wpec_pcf_contact_success'))) != '') $wpec_pcf_contact_success = esc_attr(get_option('wpec_pcf_contact_success'));
			else $wpec_pcf_contact_success = __("Thanks for your inquiry - we'll be in touch with you as soon as possible!", 'wpec_pcf');
		
			$to_email = get_option('purch_log_email');
			if (trim($to_email) == '') $to_email = get_option('admin_email');
			
			$from_email = get_option('purch_log_email');
			if (trim($from_email) == '') $from_email = get_option('admin_email');
			
			$cc_emails = '';
			
			$headers = array();
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=iso-8859-1';
			$headers[] = 'From: '.$from_email;
			if (trim($cc_emails) != '') {
				$cc_emails_a = explode("," , $cc_emails);
				if (is_array($cc_emails_a) && count($cc_emails_a) > 0) {
					foreach ($cc_emails_a as $cc_email) {
						$headers[] = 'Cc: '.$cc_email;
					}
				} else {
					$headers[] = 'Cc: '.$cc_emails;
				}
			}
			
			$product_name = get_the_title($product_id);
			$product_url = get_permalink($product_id);
			$subject = __('Email inquiry for', 'wpec_pcf').' '.$product_name;
			
			$content = '
	<table width="99%" cellspacing="0" cellpadding="1" border="0" bgcolor="#eaeaea"><tbody>
	  <tr>
		<td>
		  <table width="100%" cellspacing="0" cellpadding="5" border="0" bgcolor="#ffffff"><tbody>
			<tr bgcolor="#eaf2fa">
			  <td colspan="2"><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><strong>'.__('Name', 'wpec_pcf').'</strong></font> 
			  </td></tr>
			<tr bgcolor="#ffffff">
			  <td width="20">&nbsp;</td>
			  <td><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px">[your_name]</font> </td></tr>
			<tr bgcolor="#eaf2fa">
			  <td colspan="2"><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><strong>'.__('Email Address', 'wpec_pcf').'</strong></font> </td></tr>
			<tr bgcolor="#ffffff">
			  <td width="20">&nbsp;</td>
			  <td><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><a target="_blank" href="mailto:[your_email]">[your_email]</a></font> 
			  </td></tr>
			<tr bgcolor="#eaf2fa">
			  <td colspan="2"><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><strong>'.__('Phone', 'wpec_pcf').'</strong></font> </td></tr>
			<tr bgcolor="#ffffff">
			  <td width="20">&nbsp;</td>
			  <td><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px">[your_phone]</font> </td></tr>
			<tr bgcolor="#eaf2fa">
			  <td colspan="2"><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><strong>'.__('Product Name', 'wpec_pcf').'</strong></font> </td></tr>
			<tr bgcolor="#ffffff">
			  <td width="20">&nbsp;</td>
			  <td><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><a target="_blank" href="[product_url]">[product_name]</a></font> </td></tr>
			<tr bgcolor="#eaf2fa">
			  <td colspan="2"><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px"><strong>'.__('Message', 'wpec_pcf').'</strong></font> </td></tr>
			<tr bgcolor="#ffffff">
			  <td width="20">&nbsp;</td>
			  <td><font style="FONT-FAMILY:sans-serif;FONT-SIZE:12px">[your_message]</font> 
		  </td></tr></tbody></table></td></tr></tbody></table>';
		  
			$content = str_replace('[your_name]', $your_name, $content);
			$content = str_replace('[your_email]', $your_email, $content);
			$content = str_replace('[your_phone]', $your_phone, $content);
			$content = str_replace('[product_name]', $product_name, $content);
			$content = str_replace('[product_url]', $product_url, $content);
			$content = str_replace('[your_message]', wpautop($your_message), $content);
			
			$content = apply_filters('pcf_inquiry_content', $content);
			
			wp_mail( $to_email, $subject, $content, $headers, '' );
			
			return $wpec_pcf_contact_success;
		} else {
			return __("Sorry, this product don't enable email inquiry.", 'wpec_pcf');
		}
	}				
}
?>