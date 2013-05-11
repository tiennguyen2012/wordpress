jQuery.noConflict();

jQuery(document).ready(function(){

    jQuery('body').append(jQuery("<div id=\"cfp_notification\"><div id=\"cfp_close_icon\" onclick=\"cfp_msg_close()\"></div><div id=\"cfp_notification_content\"></div></div>"));

})



function cfp_msg_close(){

    jQuery('#cfp_notification').css(
        {
            'display': 'none',
            'top'    : '0',
            'left'   : '0'
        }
    );

}

function cfp_fancy_notification(elem, msg){

    jQuery("#cfp_notification_content").html(msg);

    ofset = jQuery(elem).offset();
    m_top = ofset.top - 20;
    m_left = (ofset.left + jQuery(elem).width()/2) - ( jQuery("#cfp_notification").width()/2 + 4) ;

    jQuery('#cfp_notification').css({

        'display':'block',
        'position':'absolute',
        'top':m_top,
        'left':m_left

    });
}
