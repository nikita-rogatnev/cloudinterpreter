jQuery(document).ready(function() {

    jQuery('#jform_reason_refuse1').click(function () {

        if(jQuery('.partner_personal_notes').css('display') == 'none') {
            jQuery('.partner_personal_notes').toggle('slow');
        }
    });

});