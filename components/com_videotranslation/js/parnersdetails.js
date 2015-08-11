jQuery(document).ready(function() {

    jQuery('#jform_partner_personal_note-lbl').click(function () {
        jQuery('#jform_partner_personal_note').toggle('slow');
    });

    jQuery('#jform_partner_dont_know_name').click(function() {

        var is_checked = jQuery('#jform_partner_dont_know_name').prop("checked");

        if(is_checked) {
            jQuery('#jform_partner_name').attr("disabled",true);
        }
        else {
            jQuery('#jform_partner_name').attr("disabled",false);
        }

    })

    jQuery('#jform_partner_dont_know_name').attr("checked",false);

    jQuery('#jform_partner_dont_know_name-lbl').parent().addClass('dont-know-name');
});

