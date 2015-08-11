jQuery(document).ready(function() {

    var lang = jQuery('#currentLanguageTag').html();

    var ajaxurl = jQuery('#live_site').html() + 'index.php?option=com_videotranslation&task=getTimeBeforeSession&lang='+lang;
    var data = {
        order_id: jQuery('#order_id').html()
    }
    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: data,
        async: true,
        dataType: "json",
        success: function(response)
        {

            var startTime = parseInt(response.startTime);
            var endTime = parseInt(response.endTime);
            var nowTime = parseInt(response.nowTime);

            var restTime = (startTime - nowTime) + 60;

            //console.log('my response',restTime)

            //var austDay = new Date();
            //austDay = new Date(+900) ;
            jQuery('#defaultCountdown').countdown({until: +restTime,format: 'HMS', compact: true,onExpiry: liftOff});
            //$('#year').text(austDay.getFullYear());

        }
    });


});

function liftOff() {
    location.reload();
}
