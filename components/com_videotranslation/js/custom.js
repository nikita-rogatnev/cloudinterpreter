jQuery(document).ready(function() {
   

check_valid_version_browser();


function check_valid_version_browser() {
	var valid_browser = true;
	if (( get_browser() == 'Opera') && ( get_browser_version() < 18 ) ) {
		valid_browser = false;
	} 

	if (( get_browser() == 'Firefox') && ( get_browser_version() < 22 ) ) {
		valid_browser = false;
	}

	if (!valid_browser) {
		alert(jQuery('#invalidBrowserInfo').text());
	};
}

function get_browser(){
    var ua=navigator.userAgent,tem,M=ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || []; 
    if(/trident/i.test(M[1])){
        tem=/\brv[ :]+(\d+)/g.exec(ua) || []; 
        return 'IE '+(tem[1]||'');
        }   
    if(M[1]==='Chrome'){
        tem=ua.match(/\bOPR\/(\d+)/)
        if(tem!=null)   {return 'Opera '+tem[1];}
        }   
    M=M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
    if((tem=ua.match(/version\/(\d+)/i))!=null) {M.splice(1,1,tem[1]);}
    return M[0];
    }

function get_browser_version(){
    var ua=navigator.userAgent,tem,M=ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];                                                                                                                         
    if(/trident/i.test(M[1])){
        tem=/\brv[ :]+(\d+)/g.exec(ua) || [];
        return 'IE '+(tem[1]||'');
        }
    if(M[1]==='Chrome'){
        tem=ua.match(/\bOPR\/(\d+)/)
        if(tem!=null)   {return 'Opera '+tem[1];}
        }   
    M=M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
    if((tem=ua.match(/version\/(\d+)/i))!=null) {M.splice(1,1,tem[1]);}
    return M[1];
    } 

function publishVideo() {

    jQuery('.OT_video-poster').toggle();

    if ( jQuery('.OT_video-poster').is(":hidden") ) {
        webrtc.resumeLocalVideo();
        jQuery('#pubVid').css('opacity','1');
    } else if ( jQuery('.OT_video-poster').is(":visible") ) {
        webrtc.pauseLocalVideo();
        jQuery('#pubVid').css('opacity','.2');
    }

}

function publishAudio() {

    if ( jQuery('#pubAud').css("opacity") < 1 ) {
        webrtc.resumeLocalAudio();
        jQuery('#pubAud').css('opacity','1');
    } else if ( jQuery('#pubAud').css("opacity") == 1 ) {
        webrtc.pauseLocalAudio();
        jQuery('#pubAud').css('opacity','.2');
    }

}



    jQuery('#pubVid').click(function() {
        publishVideo();
    });
    jQuery('#pubAud').click(function() {
        publishAudio();
    });


    // grab the room from the URL
    //var room = location.search && location.search.split('?')[1];

    //var room = "<?php echo $_REQUEST['ses_id'].$_REQUEST['order_id'];?>";
    var room = jQuery('#room-id').html();

    //var userinfo = jQuery('#userinfo').html();

    // create our webrtc connection
    var webrtc = new SimpleWebRTC({
        // the id/element dom element that will hold "our" video
        localVideoEl: 'localVideo',
        // the id/element dom element that will hold remote videos
        remoteVideosEl: 'VideoLine',
        // immediately ask for camera access
        autoRequestMedia: true,
        log: true
    });



    // when it's ready, join if we got a room from the URL
    webrtc.on('readyToCall', function () {
        // you can name it anything



        if (room) webrtc.joinRoom(room);
    });


    webrtc.on('localMediaError', function(){
                alert(jQuery("#noMediaInfo").val());
           });

    // Since we use this twice we put it here
    function setRoom(name) {
        jQuery('form').remove();
        jQuery('h1').text(name);
        jQuery('#subTitle').text('Link to join: ' + location.href);
        jQuery('body').addClass('active');
    }

    if (room) {
        setRoom(room);
    } else {
        jQuery('form').submit(function () {
            var val = jQuery('#sessionInput').val().toLowerCase().replace(/\s/g, '-').replace(/[^A-Za-z0-9_\-]/g, '');
            webrtc.createRoom(val, function (err, name) {
                var newUrl = location.pathname + '?' + name;
                if (!err) {
                    history.replaceState({foo: 'bar'}, null, newUrl);
                    setRoom(name);
                }
            });
            return false;
        });
    }

    var lang = jQuery('#currentLanguageTag').html();

    var ajaxurl = jQuery('#live_site').html() + 'index.php?option=com_videotranslation&task=getRemainingTime&lang='+lang;
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

            var restTime = (endTime - nowTime) + 60;

            //console.log('my response',restTime)

            //var austDay = new Date();
            //austDay = new Date(+900) ;
            jQuery('#defaultCountdown').countdown({until: +restTime,format: 'HMS',onTick: watchCountdown, compact: true,onExpiry: liftOff});
            //$('#year').text(austDay.getFullYear());

        }
    });


    jQuery('#yes').click(function() {
        jQuery('#session-time-out').remove();
    });

    jQuery('#no').click(function() {
        jQuery('#session-time-out').remove();
    });


   // jQuery( "#chat-window" ).draggable();


});


function liftOff() {
    location.reload();
}

function watchCountdown(periods) {

    if(periods[4] == 0 && periods[5] <= 5) {
        //alert('time is finishing')

        if ( !jQuery( "#defaultCountdown" ).is( "hasCountdownLight" ) ) {

            //$( "#defaultCountdown" ).show();

            jQuery( "#defaultCountdown" ).addClass( "hasCountdownLight" );

            jQuery(".timer-container, #defaultCountdown").effect("highlight", {}, 3000);
        }

//        if(jQuery("#session-time-out").length) {
//            jQuery("#session-time-out").show();
//        }
    }
}


function volume_control(id) {

    //Store frequently elements in variables
    var slider  = jQuery('#'+id+'-slider'),
        tooltip = jQuery('#'+id+'-tooltip');

    //Hide the Tooltip at first
    tooltip.hide();

    //Call the Slider
    slider.slider({
        //Config
        range: "min",
        min: 1,
        value: 100,

        start: function(event,ui) {
            tooltip.fadeIn('fast');
        },

        //Slider Event
        slide: function(event, ui) { //When the slider is sliding

            var value  = slider.slider('value'),
                volume = jQuery('#'+id+'-volume');


            document.getElementById(id).volume = value/100;

//            $('#'+id).volume = value/100;

            tooltip.css('left', value).text(ui.value);  //Adjust the tooltip accordingly

            if(value <= 5) {
                volume.css('background-position', '0 0');
            }
            else if (value <= 25) {
                volume.css('background-position', '0 -25px');
            }
            else if (value <= 75) {
                volume.css('background-position', '0 -50px');
            }
            else {
                volume.css('background-position', '0 -75px');
            };

        },

        stop: function(event,ui) {
            tooltip.fadeOut('fast');
           // console.log(document.getElementById(id).volume)
        }
    });

    setTimeout(function () {
        jQuery('#'+id+'-volume-control').show();
    }, 3000);

}

function add_video_control(id) {

    var volume_container = '<div id="'+id+'-volume-control" class="volume-control"><section><span id="'+id+'-tooltip" class="tooltip"></span><div id="'+id+'-slider" class="slider"></div><span id="'+id+'-volume" class="volume"></span></section></div>'

    //var volume_container = '<section><span id="'+id+'-tooltip" class="tooltip"></span><div id="'+id+'-slider" class="slider"></div><span id="'+id+'-volume" class="volume"></span></div>'

    //new element on the page
jQuery('#'+id+'-container').append(volume_container);

    //alert(id)
   volume_control(id);

}

function add_user_info(id,some_info_about_user) {
    var user_info_div = '<div class="some-info-about-user user-description-'+id+'">'+some_info_about_user+'</div>';
    jQuery('#'+id+'-container').append(user_info_div);
}


