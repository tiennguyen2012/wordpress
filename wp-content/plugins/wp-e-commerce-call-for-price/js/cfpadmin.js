jQuery.noConflict();
jQuery('#cfp_if_call_for_price').click(function() {
    jQuery('#cfp_message').toggle('normal', function() {
    });
});


jQuery(document).ready(function() {
    jQuery('#upload_image_button').click(function() {
        formfield = jQuery('#upload_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        return false;
    });

    window.send_to_editor = function(html) {
        imgurl = jQuery('img',html).attr('src');
        jQuery('#upload_image').val(imgurl);
        tb_remove();
    }
});


jQuery('#cfp_icons li img').click(function(){

    jQuery('#cfp_custom_icon').attr('value', jQuery(this).attr('src'));
    jQuery('#cfp_icons li').removeClass('selected');
    jQuery(this).parent().addClass('selected');

});
