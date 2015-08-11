var uuid = getUUID();

function getUUID() {
  var d = new Date().getTime();
    var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = (d + Math.random()*16)%16 | 0;
        d = Math.floor(d/16);
        return (c=='x' ? r : (r&0x7|0x8)).toString(16);
    });
    return uuid;
}

function regenerateUuid() {
	uuid = getUUID();	
}






jQuery(document).ready(function() {




jQuery('#call_mode_select option[value="' + getCallMode() + '"]').attr('selected', true);



    jQuery( "#datepicker" ).datepicker({
        minDate: 0,
        firstDay: 1,
        onSelect: function(dateText, inst) {
            //console.log(inst)

            //block module
            jQuery('.new-module-videotranslation').block({
                message: '<h1>Processing ...</h1>',
                css: { border: 'none',
                    padding: '15px',
                    backgroundColor: '#000',
                    '-webkit-border-radius': '10px',
                    '-moz-border-radius': '10px',
                    opacity: .5,
                    color: '#fff',
                    font_size: '10px'
                },
                onBlock: function() {

                    jQuery('#timeline-window').timeline({
                        selectedDay : inst.selectedDay,
                        selectedMonth : inst.selectedMonth,
                        selectedYear: inst.selectedYear,
        //                startTime: '9:00:00',
        //                endTime: '18:00:00',
                        step: 900
                    });


                }
            });

        }
    });

    jQuery('#timeline-window').timeline('addEvents');

    jQuery('#call_mode_select').change( function() {
        jQuery('#timeline-window').timeline('addCallModeToSession');
    });
    //fix by kosmos.by@gmail.com request from alex
    //jQuery('#timeline-window').timeline('addCallModeToSession');


    var count = jQuery('#timeline-window').timeline('checkIfSomethingInSession');
        if (count.count_items > 0) {
            jQuery('#planBlock').show();
            jQuery('#nowBtn').html(jQuery('.removeTimeFromSession').prev().text().split(" -")[0] + "   <i class='icon-white icon-calendar'></i>");
            jQuery('.callTranslatorNow').hide();
        } else {
            jQuery('#planBlock').hide();
            jQuery('#nowBtn').html(nowButtonLabel());
            jQuery('.callTranslatorNow').show();
        }

    jQuery('#you_speak_select').change( function() {
        getPartnerLanguageSelect();
    });

    getPartnerLanguageSelect();


//кнопка закрытия модального окна
    jQuery('#closeModalButton').click(function() {
    		stat_marker = false;
    		socket.emit('forceDisconnect');
            sendStat('end');

			jQuery('#myModal').modal('hide');
	});




    jQuery('#dialog-modal-registration .button-dialogWindow').click(function() {
//        jQuery('.module-videotranslation').block({
//            message: '<h1>Logging ...</h1>',
//
//
//
//        return false;
    });

    //jQuery(".input-medium").selectbox();

//    jQuery("#cart").mCustomScrollbar({
//               horizontalScroll:true
 //    });


    });



function sendStat (status) {
	
	ajaxurl = jQuery('#live_site').html() + 'index.php?option=com_videotranslation&task=sendStat';
	data = {
		status: status,
		uuid: uuid
	};
    jQuery.ajax({
    type: "POST",
    url: ajaxurl,
    data: data,
    async: true,
    //dataType: "json",
    success: function()
    {
       console.log('ok, sendStat ajax success');
    }
});
}

function sendStat_start(sessionid) {
    ajaxurl = jQuery('#live_site').html() + 'index.php?option=com_videotranslation&task=sendStat';
    data = {
        status: 'start',
        sessionid: sessionid
    };
    jQuery.ajax({
    type: "POST",
    url: ajaxurl,
    data: data,
    async: true,
    success: function()
    {
       console.log('ok, sendStat_start ajax success');
    }
});
}

function sendStat_busy(sessionid) {
    ajaxurl = jQuery('#live_site').html() + 'index.php?option=com_videotranslation&task=sendStat';
    data = {
        status: 'busy',
        sessionid: sessionid
    };
    jQuery.ajax({
    type: "POST",
    url: ajaxurl,
    data: data,
    async: true,
    success: function()
    {
       console.log('ok, sendStat_busy ajax success');
    }
});
}

function sendStat_end(sessionid) {
    ajaxurl = jQuery('#live_site').html() + 'index.php?option=com_videotranslation&task=sendStat';
    data = {
        status: 'end',
        sessionid: sessionid
    };
    jQuery.ajax({
    type: "POST",
    url: ajaxurl,
    data: data,
    async: true,
    success: function()
    {
       console.log('ok, sendStat_end ajax success');
    }
});
}
function getPartnerLanguageSelect() {

    var ajaxurl = jQuery('#live_site').html() + 'index.php?option=com_videotranslation&task=getPartnerLanguageSelect';
    var data = {
        selectedLanguage: jQuery('#you_speak_select').val()
    }
    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: data,
        async: true,
        dataType: "json",
        success: function(newOptions)
        {
            //var newOptions = response;

            var select = jQuery('#your_partner_speak_select');
            if(select.prop) {
                var options = select.prop('options');
            }
            else {
              var options = select.attr('options');
            }
            jQuery('option', select).remove();


            jQuery.each(newOptions, function(val, text) {
            	    options[options.length] = new Option(text, val);
            });

            var partnerSelectedLanguageId = getPartnerSessionLanguage();

            if(partnerSelectedLanguageId) {
                //select.val(partnerSelectedLanguageId);
            }
        }
    });
}

function getPartnerSessionLanguage() {

    var languageTag = jQuery('#currentLanguageTag').html();

    var ajaxurl = jQuery('#live_site').html() + 'index.php?option=com_videotranslation&task=getPartnerSessionLanguage&lang='+languageTag;
    var data = {
        Currentlanguage: jQuery('#currentLanguage').html(),
        SelectedLanguage: jQuery('#you_speak_select').val()
    }

    var partnerSelectedLanguageId = 0;

    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: data,
        async: false,
        dataType: "json",
        success: function(response)
        {

            if(parseInt(response.your_partner_language)) {
                partnerSelectedLanguageId = parseInt(response.your_partner_language);
            }

            var lanugageValueFromTheBrowser = parseInt(jQuery('#currentLanguage').html());
            var languageValueFromTheRequest = parseInt(response.selectedLanguageId);

             if(lanugageValueFromTheBrowser != languageValueFromTheRequest) {
                window.location.href = jQuery('#live_site').html() + response.selected_language;
            }
        }
    });

    return partnerSelectedLanguageId;
}

function nowButtonLabel() {
    var langTag = jQuery('#currentLanguageTag').text();
    var label ='';
    var suffixLabel = "   <i class='icon-white icon-calendar'></i>";
        if (langTag == 'en') {
            label = "Now";
        }; 
        if (langTag == 'ru') {
            label = "Сейчас";
        };       
        return label + suffixLabel;
    }



function showCalend() {
    var count = jQuery('#timeline-window').timeline('checkIfSomethingInSession');
    if (count.count_items > 0) {
        jQuery('#nowBtn').html(jQuery('.removeTimeFromSession').prev().text().split(" -")[0] + "   <i class='icon-white icon-calendar'></i>");
        jQuery('#planBlock').show();
        //jQuery('#allElements').css('margin-left', '1%');
        jQuery('.callTranslatorNow').hide();
    } else {
        //jQuery('#nowBtn').html(Joomla.JText._('MOD_VIDEOTRANSLATION_NOW_BUTTON') + "   <i class='icon-white icon-calendar'></i>");
        jQuery('#nowBtn').html(nowButtonLabel());
        jQuery('#planBlock').hide();
        //jQuery('#allElements').css('margin-left', '15%');
            jQuery('.callTranslatorNow').show();
    }

    if (document.getElementById('datepicker').style.display == 'block') {
            document.getElementById('datepicker').style.display = 'none';
        } else {
            //document.getElementById('planBlock').style.display = 'block'; 
            var pos = document.getElementById('nowBtn');
            var popup = document.getElementById('datepicker');
            popup.style.display = 'block';
            popup.style.position = 'absolute';
            popup.style.offsetTop = pos.offsetTop;
            popup.style.offsetLeft =  pos.offsetLeft;
            document.getElementById('datepicker').style.display = 'block';
            }
}

function getCallMode() {

    var ajaxurl = jQuery('#live_site').html() + 'index.php?option=com_videotranslation&task=getCallMode';
    var data = {}
    var recievedCallMode = '';

    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: data,
        async: false,
        dataType: "json",
        success: function(response)
        {
            recievedCallMode = response;
        }
    });

    return recievedCallMode;
}
