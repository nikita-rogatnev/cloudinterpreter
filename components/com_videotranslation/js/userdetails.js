jQuery(document).ready(function() {

    jQuery('#jform_subj_id').change(function () {

        countPriceDependsOnSubject();
    });

    countPriceDependsOnSubject();

    jQuery('.styled-select').parent().addClass('styled-select');

});

function countPriceDependsOnSubject() {

    var ajaxurl = jQuery('#live_site').html() + 'index.php?option=com_videotranslation&task=countPriceDependsOnSubject';
    var data = {
        selectedSubject: jQuery('#jform_subj_id').val()
    }
    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: data,
        async: true,
        dataType: "json",
        success: function(response)
        {

            jQuery('#jform_amount').val(response.amount);

            var newOptions = response.subjects;

            var select = jQuery('#jform_subj_id');
            if(select.prop) {
                var options = select.prop('options');
            }
            else {
                var options = select.attr('options');
            }
            jQuery('option', select).remove();
//
            jQuery.each(newOptions, function(val1,val2) {
                options[options.length] = new Option(val2.name, val2.id);
            });
            select.val(response.selectedSubject);

        }
    });

}